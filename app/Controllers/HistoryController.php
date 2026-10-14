<?php

namespace App\Controllers;

class HistoryController
{
    public function trading()
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        $earnings = \DB::fetchAll("SELECT t.*, ui.plan_name FROM transactions t LEFT JOIN (SELECT ui.id, i.name as plan_name FROM user_investments ui JOIN investments i ON i.id = ui.investment_id) ui ON ui.id = t.reference WHERE t.user_id = ? AND t.type = 'earning' ORDER BY t.id DESC", [$user['id']]);
        \View::render('history.trading', [
            'active' => 'trading',
            'title' => 'Profit Record',
            'earnings' => $earnings,
        ], 'dashboard');
    }

    public function account()
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        $transactions = \DB::fetchAll("SELECT * FROM transactions WHERE user_id = ? ORDER BY id DESC", [$user['id']]);
        $deposits = \DB::fetchAll("SELECT * FROM deposits WHERE user_id = ? ORDER BY id DESC", [$user['id']]);
        $withdrawals = \DB::fetchAll("SELECT * FROM withdrawals WHERE user_id = ? ORDER BY id DESC", [$user['id']]);
        \View::render('history.account', [
            'active' => 'account',
            'title' => 'Account History',
            'transactions' => $transactions,
            'deposits' => $deposits,
            'withdrawals' => $withdrawals,
        ], 'dashboard');
    }
}
