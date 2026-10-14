<?php

namespace App\Controllers;

class AuthController
{
    public function login()
    {
        \Auth::guestOnly();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            if (\Auth::attempt($email, $password)) {
                $user = \Auth::user();
                flash('success', "Welcome back, {$user['fullname']}!");
                if ($user['is_admin']) {
                    redirect('admin');
                }
                redirect('dashboard');
            }
            flash('error', 'Invalid email or password.');
            redirect('login');
        }
        \View::render('auth.login', ['title' => 'User Login'], 'plain');
    }

    public function register()
    {
        \Auth::guestOnly();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $fullname = trim($_POST['fullname'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['password_confirmation'] ?? '';
            $country = trim($_POST['country'] ?? '');
            $referralId = trim($_POST['referral_id'] ?? '');

            if (strlen($password) < 6) {
                flash('error', 'Password must be at least 6 characters.');
                redirect('register');
            }
            if ($password !== $confirm) {
                flash('error', 'Passwords do not match.');
                redirect('register');
            }
            $existing = \DB::fetch("SELECT id FROM users WHERE email = :email OR username = :username LIMIT 1", ['email' => $email, 'username' => $username]);
            if ($existing) {
                flash('error', 'Email or username already exists.');
                redirect('register');
            }

            $referredBy = null;
            if ($referralId) {
                $referrer = \DB::fetch("SELECT id FROM users WHERE referral_id = :rid LIMIT 1", ['rid' => $referralId]);
                if ($referrer) {
                    $referredBy = $referrer['id'];
                }
            }

            $userReferralId = strtoupper(substr(md5(uniqid()), 0, 8));
            $userId = \DB::insert('users', [
                'username' => $username,
                'fullname' => $fullname,
                'email' => $email,
                'phone' => $phone,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'country' => $country,
                'referral_id' => $userReferralId,
                'referred_by' => $referredBy,
                'status' => 'active',
            ]);

            $_SESSION['user_id'] = $userId;
            flash('success', 'Account created successfully! Welcome to Trustcrypto.');
            redirect('dashboard');
        }
        \View::render('auth.register', ['title' => 'Create Account'], 'plain');
    }

    public function logout()
    {
        \Auth::logout();
        redirect('/');
    }

    public function forgot()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $user = \DB::fetch("SELECT id FROM users WHERE email = :email LIMIT 1", ['email' => $email]);
            if ($user) {
                $token = bin2hex(random_bytes(32));
                \DB::insert('password_resets', [
                    'email' => $email,
                    'token' => $token,
                    'expires_at' => date('Y-m-d H:i:s', strtotime('+1 hour')),
                ]);
                flash('info', 'If the email exists, a reset link has been sent. (Demo: use the token in the database)');
            } else {
                flash('info', 'If the email exists, a reset link has been sent.');
            }
            redirect('forgot-password');
        }
        \View::render('auth.forgot', ['title' => 'Forgot Password'], 'plain');
    }
}
