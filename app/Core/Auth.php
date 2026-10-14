<?php

class Auth
{
    public static function user(): ?array
    {
        if (empty($_SESSION['user_id'])) {
            return null;
        }
        static $cache = null;
        if ($cache !== null && $cache['id'] == $_SESSION['user_id']) {
            return $cache;
        }
        $cache = DB::fetch("SELECT * FROM users WHERE id = :id", ['id' => $_SESSION['user_id']]);
        return $cache;
    }

    public static function check(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    public static function isAdmin(): bool
    {
        $user = self::user();
        return $user && (int) $user['is_admin'] === 1;
    }

    public static function attempt(string $email, string $password): bool
    {
        $user = DB::fetch("SELECT * FROM users WHERE email = :email LIMIT 1", ['email' => $email]);
        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        DB::update('users', ['last_login' => date('Y-m-d H:i:s')], 'id = :id', ['id' => $user['id']]);
        return true;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    public static function requireAuth(): void
    {
        if (!self::check()) {
            flash('error', 'Please log in to continue.');
            redirect('login');
        }
    }

    public static function requireAdmin(): void
    {
        self::requireAuth();
        if (!self::isAdmin()) {
            http_response_code(403);
            View::render('errors/403', [], 'plain');
            exit;
        }
    }

    public static function guestOnly(): void
    {
        if (self::check()) {
            redirect('dashboard');
        }
    }
}
