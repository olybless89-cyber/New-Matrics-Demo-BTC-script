<?php

namespace App\Controllers;

class DepositController
{
    public function index()
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        $deposits = \DB::fetchAll("SELECT * FROM deposits WHERE user_id=? ORDER BY id DESC", [$user['id']]);
        \View::render('deposit.index', [
            'active' => 'deposit',
            'title' => 'Deposit',
            'deposits' => $deposits,
            'balance' => $user['balance'],
        ], 'dashboard');
    }

    public function store()
    {
        \Auth::requireAuth();
        $userId = \Auth::user()['id'];
        $amount = (float) ($_POST['amount'] ?? 0);
        $method = trim($_POST['payment_method'] ?? '');
        $txnHash = trim($_POST['txn_hash'] ?? '');

        if ($amount <= 0) {
            flash('error', 'Please enter a valid amount.');
            redirect('deposit');
        }
        if (empty($method)) {
            flash('error', 'Please select a payment method.');
            redirect('deposit');
        }

        \DB::insert('deposits', [
            'user_id' => $userId,
            'amount' => $amount,
            'payment_method' => $method,
            'txn_hash' => $txnHash,
            'status' => 'pending',
        ]);

        flash('success', 'Deposit request submitted. It will be approved shortly.');
        redirect('deposit');
    }
}
