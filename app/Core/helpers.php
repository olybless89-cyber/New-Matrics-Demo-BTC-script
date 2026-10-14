<?php

function env(string $key, $default = null)
{
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? null;
    if ($value === null) {
        return $default;
    }
    $value = trim($value, "\"'");
    return match (strtolower($value)) {
        'true' => true,
        'false' => false,
        'null' => null,
        default => $value,
    };
}

function config(string $key, $default = null)
{
    return env($key, $default);
}

function base_path(string $path = ''): string
{
    return ROOT . ($path ? DIRECTORY_SEPARATOR . ltrim($path, '\\/') : '');
}

function url(string $path = ''): string
{
    $base = rtrim(env('APP_URL', '/'), '/');
    return $base . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

function old(string $key, $default = '')
{
    return $_SESSION['_old'][$key] ?? $default;
}

function flash(string $key, $value = null)
{
    if ($value === null) {
        $val = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $val;
    }
    $_SESSION['_flash'][$key] = $value;
}

function has_flash(string $key): bool
{
    return isset($_SESSION['_flash'][$key]);
}

function csrf_token(): string
{
    return CSRF::token();
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
}

function money(float $amount, string $currency = 'USD'): string
{
    $symbol = match ($currency) {
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
        'NGN' => '₦',
        default => '$',
    };
    return $symbol . number_format($amount, 2);
}

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
