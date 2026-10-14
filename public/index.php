<?php

define('ROOT', dirname(__DIR__));

require_once ROOT . '/vendor/autoload.php';

use Dotenv\Dotenv;

if (file_exists(ROOT . '/.env')) {
    Dotenv::createImmutable(ROOT)->load();
}

date_default_timezone_set('UTC');

$sessionDir = ROOT . '/storage/sessions';
if (!is_dir($sessionDir)) {
    @mkdir($sessionDir, 0777, true);
}
if (is_dir($sessionDir) && is_writable($sessionDir)) {
    session_save_path($sessionDir);
}
session_start();

require_once ROOT . '/app/Core/helpers.php';
require_once ROOT . '/app/Core/DB.php';
require_once ROOT . '/app/Core/View.php';
require_once ROOT . '/app/Core/Auth.php';
require_once ROOT . '/app/Core/CSRF.php';
require_once ROOT . '/app/Core/Router.php';

$driver = env('DB_DRIVER', null);
$host = env('DB_HOST', env('MYSQLHOST', ''));
$port = (int) env('DB_PORT', env('MYSQLPORT', '3306'));
$dbName = env('DB_NAME', env('MYSQLDATABASE', ''));
$dbUser = env('DB_USER', env('MYSQLUSER', 'root'));
$dbPass = env('DB_PASS', env('MYSQLPASSWORD', ''));

if ($driver === null) {
    if (!empty($host) && !empty($dbName)) {
        $driver = 'mysql';
    } else {
        $driver = 'sqlite';
        $dbName = ROOT . '/storage/database.sqlite';
    }
} elseif ($driver === 'sqlite' && ($dbName === '../storage/database.sqlite' || empty($dbName))) {
    $dbName = ROOT . '/storage/database.sqlite';
}

DB::init(
    host: $host ?: '127.0.0.1',
    port: $port ?: 3306,
    name: $dbName ?: (ROOT . '/storage/database.sqlite'),
    user: $dbUser ?: 'root',
    pass: $dbPass ?: '',
    driver: $driver ?: 'sqlite',
);

$router = new Router();
require_once ROOT . '/app/routes.php';
$router->dispatch();
