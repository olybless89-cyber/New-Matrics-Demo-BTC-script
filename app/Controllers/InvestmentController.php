<?php

namespace App\Controllers;

class InvestmentController
{
    public function index()
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        $investments = \DB::fetchAll("SELECT * FROM investments WHERE is_active = 1 ORDER BY min_amount ASC");
        $userInvestments = \DB::fetchAll("SELECT ui.*, i.name as plan_name FROM user_investments ui JOIN investments i ON i.id=ui.investment_id WHERE ui.user_id=? ORDER BY ui.id DESC", [$user['id']]);
        \View::render('invest.index', [
            'active' => 'invest',
            'title' => 'Invest',
            'investments' => $investments,
            'userInvestments' => $userInvestments,
            'balance' => $user['balance'],
        ], 'dashboard');
    }

    public function store()
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        $userId = $user['id'];
        $investmentId = (int) ($_POST['investment_id'] ?? 0);
        $amount = (float) ($_POST['amount'] ?? 0);

        $plan = \DB::fetch("SELECT * FROM investments WHERE id=? AND is_active=1", [$investmentId]);
        if (!$plan) {
            flash('error', 'Invalid investment plan.');
            redirect('invest');
        }
        if ($amount < $plan['min_amount'] || $amount > $plan['max_amount']) {
            flash('error', "Amount must be between \${$plan['min_amount']} and \${$plan['max_amount']}.");
            redirect('invest');
        }
        if ($amount > $user['balance']) {
            flash('error', 'Insufficient balance. Please deposit funds first.');
            redirect('invest');
        }

        \DB::pdo()->beginTransaction();
        try {
            $newBalance = $user['balance'] - $amount;
            \DB::update('users', ['balance' => $newBalance], 'id = :id', ['id' => $userId]);

            $depositId = \DB::insert('deposits', [
                'user_id' => $userId,
                'investment_id' => $investmentId,
                'amount' => $amount,
                'payment_method' => 'balance',
                'status' => 'approved',
                'approved_at' => date('Y-m-d H:i:s'),
            ]);

            $startDate = date('Y-m-d H:i:s');
            $endDate = date('Y-m-d H:i:s', strtotime("+{$plan['duration_hours']} hours"));

            \DB::insert('user_investments', [
                'user_id' => $userId,
                'deposit_id' => $depositId,
                'investment_id' => $investmentId,
                'amount' => $amount,
                'rate' => $plan['rate'],
                'period' => $plan['period'],
                'duration_hours' => $plan['duration_hours'],
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'active',
            ]);

            \DB::insert('transactions', [
                'user_id' => $userId,
                'type' => 'deposit',
                'amount' => -$amount,
                'balance_after' => $newBalance,
                'description' => "Investment in {$plan['name']} plan",
                'reference' => 'INV-' . strtoupper(substr(md5(uniqid()), 0, 8)),
            ]);

            \DB::pdo()->commit();
            flash('success', "Investment of \${$amount} in {$plan['name']} started!");
        } catch (\Exception $e) {
            \DB::pdo()->rollBack();
            flash('error', 'Failed to start investment.');
        }
        redirect('invest');
    }
}
