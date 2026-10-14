<?php

namespace App\Controllers;

class AdminController
{
    public function index()
    {
        \Auth::requireAdmin();
        $stats = [
            'total_users' => (int) \DB::fetch("SELECT COUNT(*) c FROM users")['c'],
            'total_deposits' => (float) \DB::fetch("SELECT COALESCE(SUM(amount),0) s FROM deposits WHERE status='approved'")['s'],
            'total_withdrawals' => (float) \DB::fetch("SELECT COALESCE(SUM(amount),0) s FROM withdrawals WHERE status='approved'")['s'],
            'pending_deposits' => (int) \DB::fetch("SELECT COUNT(*) c FROM deposits WHERE status='pending'")['c'],
            'pending_withdrawals' => (int) \DB::fetch("SELECT COUNT(*) c FROM withdrawals WHERE status='pending'")['c'],
            'active_cards' => (int) \DB::fetch("SELECT COUNT(*) c FROM debit_cards WHERE status='active'")['c'],
        ];
        \View::render('admin.index', ['active' => 'admin', 'title' => 'Admin Dashboard', 'stats' => $stats], 'admin');
    }

    public function users()
    {
        \Auth::requireAdmin();
        $users = \DB::fetchAll("SELECT * FROM users ORDER BY id DESC");
        \View::render('admin.users', ['active' => 'users', 'title' => 'Users', 'users' => $users], 'admin');
    }

    public function deposits()
    {
        \Auth::requireAdmin();
        $deposits = \DB::fetchAll("SELECT d.*, u.username, u.email FROM deposits d JOIN users u ON u.id=d.user_id ORDER BY d.id DESC");
        \View::render('admin.deposits', ['active' => 'deposits', 'title' => 'Deposits', 'deposits' => $deposits], 'admin');
    }

    public function approveDeposit($id)
    {
        \Auth::requireAdmin();
        $deposit = \DB::fetch("SELECT * FROM deposits WHERE id=?", [$id]);
        if (!$deposit || $deposit['status'] !== 'pending') {
            flash('error', 'Deposit not found or already processed.');
            redirect('admin/deposits');
        }
        $adminId = \Auth::user()['id'];
        \DB::pdo()->beginTransaction();
        try {
            \DB::update('deposits', ['status' => 'approved', 'approved_at' => date('Y-m-d H:i:s')], 'id=:id', ['id' => $id]);
            $user = \DB::fetch("SELECT * FROM users WHERE id=?", [$deposit['user_id']]);
            $newBalance = $user['balance'] + $deposit['amount'];
            \DB::update('users', [
                'balance' => $newBalance,
                'total_deposits' => $user['total_deposits'] + $deposit['amount'],
            ], 'id=:id', ['id' => $deposit['user_id']]);
            \DB::insert('transactions', [
                'user_id' => $deposit['user_id'],
                'type' => 'deposit',
                'amount' => $deposit['amount'],
                'balance_after' => $newBalance,
                'description' => "Deposit approved via {$deposit['payment_method']}",
                'reference' => 'DEP-' . strtoupper(substr(md5(uniqid()), 0, 8)),
            ]);
            \DB::pdo()->commit();
            flash('success', "Deposit of \${$deposit['amount']} approved.");
        } catch (\Exception $e) {
            \DB::pdo()->rollBack();
            flash('error', 'Failed to approve deposit.');
        }
        redirect('admin/deposits');
    }

    public function rejectDeposit($id)
    {
        \Auth::requireAdmin();
        \DB::update('deposits', ['status' => 'rejected'], 'id=:id', ['id' => $id]);
        flash('info', 'Deposit rejected.');
        redirect('admin/deposits');
    }

    public function withdrawals()
    {
        \Auth::requireAdmin();
        $withdrawals = \DB::fetchAll("SELECT w.*, u.username, u.email FROM withdrawals w JOIN users u ON u.id=w.user_id ORDER BY w.id DESC");
        \View::render('admin.withdrawals', ['active' => 'withdrawals', 'title' => 'Withdrawals', 'withdrawals' => $withdrawals], 'admin');
    }

    public function approveWithdrawal($id)
    {
        \Auth::requireAdmin();
        \DB::update('withdrawals', ['status' => 'approved', 'approved_at' => date('Y-m-d H:i:s')], 'id=:id', ['id' => $id]);
        $w = \DB::fetch("SELECT * FROM withdrawals WHERE id=?", [$id]);
        $user = \DB::fetch("SELECT * FROM users WHERE id=?", [$w['user_id']]);
        \DB::update('users', ['total_withdrawals' => $user['total_withdrawals'] + $w['amount']], 'id=:id', ['id' => $w['user_id']]);
        flash('success', 'Withdrawal approved.');
        redirect('admin/withdrawals');
    }

    public function rejectWithdrawal($id)
    {
        \Auth::requireAdmin();
        $w = \DB::fetch("SELECT * FROM withdrawals WHERE id=?", [$id]);
        if ($w && $w['status'] === 'pending') {
            $user = \DB::fetch("SELECT * FROM users WHERE id=?", [$w['user_id']]);
            \DB::update('users', ['balance' => $user['balance'] + $w['amount']], 'id=:id', ['id' => $w['user_id']]);
            \DB::insert('transactions', [
                'user_id' => $w['user_id'],
                'type' => 'adjustment',
                'amount' => $w['amount'],
                'balance_after' => $user['balance'] + $w['amount'],
                'description' => 'Withdrawal rejected - funds returned',
                'reference' => 'REJ-' . strtoupper(substr(md5(uniqid()), 0, 8)),
            ]);
        }
        \DB::update('withdrawals', ['status' => 'rejected'], 'id=:id', ['id' => $id]);
        flash('info', 'Withdrawal rejected and funds returned.');
        redirect('admin/withdrawals');
    }

    public function cards()
    {
        \Auth::requireAdmin();
        $cards = \DB::fetchAll("SELECT c.*, u.username, u.email FROM debit_cards c JOIN users u ON u.id=c.user_id ORDER BY c.id DESC");
        $users = \DB::fetchAll("SELECT id, username, email, fullname FROM users WHERE is_admin=0 ORDER BY username");
        \View::render('admin.cards', ['active' => 'cards', 'title' => 'Debit Cards', 'cards' => $cards, 'users' => $users], 'admin');
    }

    public function issueCard()
    {
        \Auth::requireAdmin();
        $userId = (int) ($_POST['user_id'] ?? 0);
        $cardType = $_POST['card_type'] ?? 'visa';
        $cardTier = $_POST['card_tier'] ?? 'virtual';
        $dailyLimit = (float) ($_POST['daily_limit'] ?? 5000);
        $initialBalance = (float) ($_POST['initial_balance'] ?? 0);
        $pin = (string) random_int(1000, 9999);

        $user = \DB::fetch("SELECT * FROM users WHERE id=?", [$userId]);
        if (!$user) {
            flash('error', 'User not found.');
            redirect('admin/cards');
        }

        $cardNumber = $this->generateCardNumber();
        $cvv = str_pad((string) random_int(100, 999), 3, '0', STR_PAD_LEFT);
        $expiryMonth = (int) date('n');
        $expiryYear = (int) date('Y') + 4;

        \DB::pdo()->beginTransaction();
        try {
            $cardId = \DB::insert('debit_cards', [
                'user_id' => $userId,
                'card_number' => $cardNumber,
                'cardholder_name' => $user['fullname'] ?: $user['username'],
                'expiry_month' => $expiryMonth,
                'expiry_year' => $expiryYear,
                'cvv' => $cvv,
                'card_type' => $cardType,
                'card_tier' => $cardTier,
                'currency' => 'USD',
                'pin' => $pin,
                'balance' => $initialBalance,
                'daily_limit' => $dailyLimit,
                'monthly_limit' => $dailyLimit * 5,
                'is_online_enabled' => 1,
                'is_intl_enabled' => 1,
                'cashback_rate' => ($cardTier === 'vip' ? 5.0 : ($cardTier === 'metal' ? 3.0 : 1.5)),
                'status' => 'active',
                'activated_at' => date('Y-m-d H:i:s'),
                'issued_by' => \Auth::user()['id'],
            ]);

            if ($initialBalance > 0) {
                \DB::insert('card_transactions', [
                    'card_id' => $cardId,
                    'user_id' => $userId,
                    'type' => 'fund',
                    'amount' => $initialBalance,
                    'description' => 'Initial card funding by admin',
                ]);
            }
            \DB::pdo()->commit();
            flash('success', "Card issued to {$user['username']} ending in " . substr($cardNumber, -4));
        } catch (\Exception $e) {
            \DB::pdo()->rollBack();
            flash('error', 'Failed to issue card.');
        }
        redirect('admin/cards');
    }

    public function blockCard($id)
    {
        \Auth::requireAdmin();
        \DB::update('debit_cards', ['status' => 'blocked'], 'id=:id', ['id' => $id]);
        flash('info', 'Card blocked.');
        redirect('admin/cards');
    }

    public function activateCard($id)
    {
        \Auth::requireAdmin();
        \DB::update('debit_cards', ['status' => 'active', 'activated_at' => date('Y-m-d H:i:s')], 'id=:id', ['id' => $id]);
        flash('success', 'Card activated.');
        redirect('admin/cards');
    }

    public function investments()
    {
        \Auth::requireAdmin();
        $investments = \DB::fetchAll("SELECT * FROM investments ORDER BY id ASC");
        \View::render('admin.investments', ['active' => 'investments', 'title' => 'Investment Plans', 'investments' => $investments], 'admin');
    }

    public function storeInvestment()
    {
        \Auth::requireAdmin();
        \DB::insert('investments', [
            'name' => trim($_POST['name'] ?? ''),
            'rate' => (float) ($_POST['rate'] ?? 0),
            'period' => trim($_POST['period'] ?? 'Hourly'),
            'min_amount' => (float) ($_POST['min_amount'] ?? 0),
            'max_amount' => (float) ($_POST['max_amount'] ?? 0),
            'duration_hours' => (int) ($_POST['duration_hours'] ?? 24),
            'description' => trim($_POST['description'] ?? ''),
        ]);
        flash('success', 'Investment plan created.');
        redirect('admin/investments');
    }

    public function messages()
    {
        \Auth::requireAdmin();
        $messages = \DB::fetchAll("SELECT * FROM messages ORDER BY id DESC");
        \View::render('admin.messages', ['active' => 'messages', 'title' => 'Messages', 'messages' => $messages], 'admin');
    }

    public function kyc()
    {
        \Auth::requireAdmin();
        $kycList = \DB::fetchAll("SELECT k.*, u.username, u.email FROM kyc_verifications k JOIN users u ON u.id = k.user_id ORDER BY k.id DESC");
        \View::render('admin.kyc', ['active' => 'kyc', 'title' => 'KYC Verifications', 'kycList' => $kycList], 'admin');
    }

    public function approveKyc($id)
    {
        \Auth::requireAdmin();
        \DB::update('kyc_verifications', [
            'status' => 'approved',
            'reviewed_at' => date('Y-m-d H:i:s'),
        ], 'id = :id', ['id' => $id]);
        flash('success', 'KYC verification approved.');
        redirect('admin/kyc');
    }

    public function rejectKyc($id)
    {
        \Auth::requireAdmin();
        $note = trim($_POST['admin_note'] ?? 'Verification rejected.');
        \DB::update('kyc_verifications', [
            'status' => 'rejected',
            'admin_note' => $note,
            'reviewed_at' => date('Y-m-d H:i:s'),
        ], 'id = :id', ['id' => $id]);
        flash('success', 'KYC verification rejected.');
        redirect('admin/kyc');
    }

    public function support()
    {
        \Auth::requireAdmin();
        $tickets = \DB::fetchAll("SELECT t.*, u.username, u.email FROM support_tickets t JOIN users u ON u.id = t.user_id ORDER BY t.last_message_at DESC, t.id DESC");
        \View::render('admin.support', ['active' => 'support', 'title' => 'Support Tickets', 'tickets' => $tickets], 'admin');
    }

    public function supportShow($id)
    {
        \Auth::requireAdmin();
        $ticket = \DB::fetch("SELECT t.*, u.username, u.email FROM support_tickets t JOIN users u ON u.id = t.user_id WHERE t.id = ?", [$id]);
        if (!$ticket) {
            http_response_code(404);
            exit;
        }
        $messages = \DB::fetchAll("SELECT sm.*, u.fullname, u.is_admin FROM support_messages sm LEFT JOIN users u ON u.id = sm.user_id WHERE sm.ticket_id = ? ORDER BY sm.id ASC", [$id]);
        \View::render('admin.support_show', ['active' => 'support', 'title' => $ticket['subject'], 'ticket' => $ticket, 'messages' => $messages], 'admin');
    }

    public function supportReply($id)
    {
        \Auth::requireAdmin();
        $admin = \Auth::user();
        $message = trim($_POST['message'] ?? '');
        if (empty($message)) {
            flash('error', 'Message cannot be empty.');
            redirect('admin/support/' . $id);
        }
        \DB::insert('support_messages', [
            'ticket_id' => $id,
            'user_id' => $admin['id'],
            'is_admin' => 1,
            'message' => $message,
        ]);
        \DB::update('support_tickets', [
            'status' => 'answered',
            'last_message_at' => date('Y-m-d H:i:s'),
        ], 'id = :id', ['id' => $id]);
        flash('success', 'Reply sent.');
        redirect('admin/support/' . $id);
    }

    public function supportClose($id)
    {
        \Auth::requireAdmin();
        \DB::update('support_tickets', ['status' => 'closed'], 'id = :id', ['id' => $id]);
        flash('success', 'Ticket closed.');
        redirect('admin/support');
    }

    private function generateCardNumber(): string
    {
        $prefix = '4';
        $number = $prefix;
        for ($i = 0; $i < 15; $i++) {
            $number .= random_int(0, 9);
        }
        $checksum = 0;
        for ($i = 0; $i < 16; $i++) {
            $digit = (int) $number[$i];
            if ($i % 2 === 0) {
                $digit *= 2;
                if ($digit > 9) $digit -= 9;
            }
            $checksum += $digit;
        }
        $checkDigit = (10 - ($checksum % 10)) % 10;
        return substr($number, 0, 15) . $checkDigit;
    }
}
