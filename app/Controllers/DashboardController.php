<?php

namespace App\Controllers;

class DashboardController
{
    public function index()
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        $userId = $user['id'];

        $totalDeposits = (float) \DB::fetch("SELECT COALESCE(SUM(amount),0) s FROM deposits WHERE user_id=? AND status='approved'", [$userId])['s'];
        $totalWithdrawals = (float) \DB::fetch("SELECT COALESCE(SUM(amount),0) s FROM withdrawals WHERE user_id=? AND status='approved'", [$userId])['s'];
        $totalEarnings = (float) \DB::fetch("SELECT COALESCE(SUM(amount),0) s FROM transactions WHERE user_id=? AND type='earning'", [$userId])['s'];
        $totalReferralBonus = (float) \DB::fetch("SELECT COALESCE(SUM(amount),0) s FROM transactions WHERE user_id=? AND type='referral_bonus'", [$userId])['s'];
        $totalBonus = $totalEarnings + $totalReferralBonus;
        $totalPlans = (int) \DB::fetch("SELECT COUNT(*) c FROM user_investments WHERE user_id=?", [$userId])['c'];
        $activePlans = (int) \DB::fetch("SELECT COUNT(*) c FROM user_investments WHERE user_id=? AND status='active'", [$userId])['c'];

        $stats = [
            'balance' => (float) $user['balance'],
            'total_deposits' => $totalDeposits,
            'total_withdrawals' => $totalWithdrawals,
            'total_earnings' => $totalEarnings,
            'total_profit' => $totalEarnings,
            'total_bonus' => $totalBonus,
            'total_referral_bonus' => $totalReferralBonus,
            'total_plans' => $totalPlans,
            'active_plans' => $activePlans,
        ];

        $recentDeposits = \DB::fetchAll("SELECT * FROM deposits WHERE user_id=? ORDER BY id DESC LIMIT 5", [$userId]);
        $activeInvestments = \DB::fetchAll("SELECT ui.*, i.name as plan_name FROM user_investments ui JOIN investments i ON i.id=ui.investment_id WHERE ui.user_id=? AND ui.status='active' ORDER BY ui.id DESC", [$userId]);
        $cardCount = (int) (\DB::fetch("SELECT COUNT(*) c FROM debit_cards WHERE user_id=?", [$userId])['c'] ?? 0);
        $userCards = \DB::fetchAll("SELECT * FROM debit_cards WHERE user_id=? ORDER BY id DESC LIMIT 2", [$userId]);
        $recentTxns = \DB::fetchAll("SELECT * FROM transactions WHERE user_id=? ORDER BY id DESC LIMIT 5", [$userId]);

        \View::render('dashboard.index', [
            'active' => 'dashboard',
            'title' => 'Dashboard',
            'stats' => $stats,
            'recentDeposits' => $recentDeposits,
            'activeInvestments' => $activeInvestments,
            'recentTxns' => $recentTxns,
            'cardCount' => $cardCount,
            'userCards' => $userCards,
        ], 'dashboard');
    }

    public function transactions()
    {
        \Auth::requireAuth();
        $userId = \Auth::user()['id'];
        $transactions = \DB::fetchAll("SELECT * FROM transactions WHERE user_id=? ORDER BY id DESC", [$userId]);
        \View::render('dashboard.transactions', ['active' => 'transactions', 'title' => 'Transactions', 'transactions' => $transactions], 'dashboard');
    }

    public function profile()
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        \View::render('dashboard.profile', ['active' => 'profile', 'title' => 'Profile', 'user' => $user], 'dashboard');
    }

    public function updateProfile()
    {
        \Auth::requireAuth();
        $userId = \Auth::user()['id'];
        $fullname = trim($_POST['fullname'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $country = trim($_POST['country'] ?? '');

        \DB::update('users', [
            'fullname' => $fullname,
            'phone' => $phone,
            'country' => $country,
        ], 'id = :id', ['id' => $userId]);

        flash('success', 'Profile updated successfully.');
        redirect('dashboard/profile');
    }
}
