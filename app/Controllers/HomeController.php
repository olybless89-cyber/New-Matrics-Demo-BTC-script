<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        $investments = \DB::fetchAll("SELECT * FROM investments WHERE is_active = 1 ORDER BY min_amount ASC");
        \View::render('home.index', ['investments' => $investments, 'active' => 'home', 'title' => 'Safe investment with Trustcrypto']);
    }

    public function about()
    {
        \View::render('home.about', ['active' => 'about', 'title' => 'About Us']);
    }

    public function investments()
    {
        $investments = \DB::fetchAll("SELECT * FROM investments WHERE is_active = 1 ORDER BY min_amount ASC");
        \View::render('home.investments', ['investments' => $investments, 'active' => 'investments', 'title' => 'Investment Plans']);
    }

    public function faq()
    {
        \View::render('home.faq', ['active' => 'faq', 'title' => 'FAQ']);
    }

    public function contacts()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'login' => trim($_POST['login'] ?? ''),
                'message' => trim($_POST['message'] ?? ''),
            ];
            if (empty($data['email']) || empty($data['message'])) {
                flash('error', 'Email and message are required.');
                redirect('contacts');
            }
            \DB::insert('messages', $data);
            flash('success', 'Message sent successfully! We will reply within 24 hours.');
            redirect('contacts');
        }
        \View::render('home.contacts', ['active' => 'contacts', 'title' => 'Contacts']);
    }

    public function statistic()
    {
        $totalUsers = \DB::fetch("SELECT COUNT(*) as c FROM users")['c'];
        $totalDeposits = \DB::fetch("SELECT COALESCE(SUM(amount),0) as s FROM deposits WHERE status='approved'")['s'];
        $totalWithdrawals = \DB::fetch("SELECT COALESCE(SUM(amount),0) as s FROM withdrawals WHERE status='approved'")['s'];
        \View::render('home.statistic', [
            'active' => 'statistic',
            'title' => 'Statistics',
            'totalUsers' => $totalUsers,
            'totalDeposits' => $totalDeposits,
            'totalWithdrawals' => $totalWithdrawals,
        ]);
    }

    public function terms()
    {
        \View::render('home.terms', ['title' => 'Privacy Policy']);
    }
}
