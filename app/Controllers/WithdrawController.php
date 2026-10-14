<?php

namespace App\Controllers;

class WithdrawController
{
    public function index()
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        $withdrawals = \DB::fetchAll("SELECT * FROM withdrawals WHERE user_id=? ORDER BY id DESC", [$user['id']]);
        \View::render('withdraw.index', [
            'active' => 'withdraw',
            'title' => 'Withdraw',
            'withdrawals' => $withdrawals,
            'balance' => $user['balance'],
        ], 'dashboard');
    }

    public function store()
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        $userId = $user['id'];
        $amount = (float) ($_POST['amount'] ?? 0);
        $method = trim($_POST['payment_method'] ?? '');
        $wallet = trim($_POST['wallet_address'] ?? '');

        if ($amount <= 0) {
            flash('error', 'Please enter a valid amount.');
            redirect('withdraw');
        }
        if ($amount > $user['balance']) {
            flash('error', 'Insufficient balance.');
            redirect('withdraw');
        }
        if ($amount < 10) {
            flash('error', 'Minimum withdrawal is $10.');
            redirect('withdraw');
        }
        if (empty($method) || empty($wallet)) {
            flash('error', 'Please fill in all fields.');
            redirect('withdraw');
        }

        \DB::pdo()->beginTransaction();
        try {
            \DB::update('users', ['balance' => $user['balance'] - $amount], 'id = :id', ['id' => $userId]);
            \DB::insert('withdrawals', [
                'user_id' => $userId,
                'amount' => $amount,
                'payment_method' => $method,
                'wallet_address' => $wallet,
                'status' => 'pending',
            ]);
            \DB::insert('transactions', [
                'user_id' => $userId,
                'type' => 'withdrawal',
                'amount' => -$amount,
                'balance_after' => $user['balance'] - $amount,
                'description' => "Withdrawal via {$method}",
                'reference' => 'WD-' . strtoupper(substr(md5(uniqid()), 0, 8)),
            ]);
            \DB::pdo()->commit();
            flash('success', 'Withdrawal request submitted.');
        } catch (\Exception $e) {
            \DB::pdo()->rollBack();
            flash('error', 'Failed to process withdrawal.');
        }
        redirect('withdraw');
    }
}
