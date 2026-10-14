<?php

namespace App\Controllers;

class ExchangeController
{
    public function index()
    {
        \Auth::requireAuth();
        $user = \Auth::user();

        $assets = [
            ['symbol' => 'BTC', 'name' => 'Bitcoin', 'balance' => 0, 'price' => 67234.50, 'change' => 2.34],
            ['symbol' => 'ETH', 'name' => 'Ethereum', 'balance' => 0, 'price' => 3521.80, 'change' => 1.12],
            ['symbol' => 'USDT', 'name' => 'Tether', 'balance' => 0, 'price' => 1.00, 'change' => 0.01],
            ['symbol' => 'USDC', 'name' => 'USD Coin', 'balance' => 0, 'price' => 1.00, 'change' => 0.00],
            ['symbol' => 'LTC', 'name' => 'Litecoin', 'balance' => 0, 'price' => 84.23, 'change' => -0.56],
            ['symbol' => 'DOGE', 'name' => 'Dogecoin', 'balance' => 0, 'price' => 0.142, 'change' => 3.21],
        ];

        \View::render('exchange.index', [
            'active' => 'exchange',
            'title' => 'Crypto Exchange',
            'balance' => $user['balance'],
            'assets' => $assets,
        ], 'dashboard');
    }
}
