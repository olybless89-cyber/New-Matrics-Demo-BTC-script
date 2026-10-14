<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

if (file_exists(__DIR__ . '/../.env')) {
    Dotenv::createImmutable(__DIR__ . '/..')->load();
}

$driver = getenv('DB_DRIVER') ?: null;
$host = getenv('DB_HOST') ?: getenv('MYSQLHOST') ?: '';
$port = getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: '3306';
$name = getenv('DB_NAME') ?: getenv('MYSQLDATABASE') ?: '';
$user = getenv('DB_USER') ?: getenv('MYSQLUSER') ?: 'root';
$pass = getenv('DB_PASS') ?: getenv('MYSQLPASSWORD') ?: '';

if ($driver === null) {
    if (!empty($host) && !empty($name)) {
        $driver = 'mysql';
    } else {
        $driver = 'sqlite';
    }
}

try {
    if ($driver === 'sqlite') {
        $sqlitePath = __DIR__ . '/../storage/database.sqlite';
        if (!is_dir(dirname($sqlitePath))) {
            @mkdir(dirname($sqlitePath), 0777, true);
        }
        $pdo = new PDO("sqlite:{$sqlitePath}", null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        $pdo->exec('PRAGMA foreign_keys = ON');
    } else {
        $pdo = new PDO("mysql:host={$host};port={$port};charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `{$name}`");
    }

    $schema = file_get_contents(__DIR__ . '/schema.sql');
    // If sqlite, execute statements line by line or entire string
    if ($driver === 'sqlite') {
        // SQLite doesn't support ENGINE=InnoDB or some MySQL syntax, execute schema safely
        $schemaClean = preg_replace('/ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;?/i', ';', $schema);
        $schemaClean = preg_replace('/INT UNSIGNED AUTO_INCREMENT PRIMARY KEY/i', 'INTEGER PRIMARY KEY AUTOINCREMENT', $schemaClean);
        $schemaClean = preg_replace('/INT UNSIGNED/i', 'INTEGER', $schemaClean);
        // SQLite has no ENUM type and no ON UPDATE clause
        $schemaClean = preg_replace('/ENUM\s*\([^)]*\)/i', 'VARCHAR(50)', $schemaClean);
        $schemaClean = preg_replace('/\s+ON UPDATE CURRENT_TIMESTAMP/i', '', $schemaClean);
        $pdo->exec($schemaClean);
    } else {
        $pdo->exec($schema);
    }
    echo "Database schema created.\n";

    // "INSERT IGNORE" is MySQL-only; SQLite uses "INSERT OR IGNORE"
    $insertIgnore = $driver === 'sqlite' ? 'INSERT OR IGNORE' : 'INSERT IGNORE';
    $isEmpty = fn(string $table): bool => (int) $pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn() === 0;

    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("{$insertIgnore} INTO users (username, fullname, email, password, is_admin, status, referral_id) VALUES (?, ?, ?, ?, 1, 'active', ?)");
    $stmt->execute(['admin', 'Site Admin', 'admin@trustcrypto.com', $adminPassword, 'ADMIN001']);
    echo "Admin user created: admin@trustcrypto.com / admin123\n";

    $coderPassword = password_hash('12345678', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("{$insertIgnore} INTO users (username, fullname, email, password, is_admin, status, referral_id, balance, total_deposits) VALUES (?, ?, ?, ?, 0, 'active', ?, 12500.00, 15000.00)");
    $stmt->execute(['coder', 'Alex Morgan', 'coder@gmail.com', $coderPassword, 'REFCODER88']);
    echo "User created: coder@gmail.com / 12345678\n";

    // Seed default cards for coder
    $coderUser = $pdo->query("SELECT id FROM users WHERE email='coder@gmail.com'")->fetch();
    if ($coderUser) {
        $coderId = $coderUser['id'];
        $cardCheck = $pdo->query("SELECT COUNT(*) FROM debit_cards WHERE user_id={$coderId}")->fetchColumn();
        if ((int)$cardCheck === 0) {
            $cardStmt = $pdo->prepare("INSERT INTO debit_cards (user_id, card_number, cardholder_name, expiry_month, expiry_year, cvv, card_type, card_tier, currency, pin, balance, daily_limit, monthly_limit, cashback_rate, status, activated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', ?)");
            $cardStmt->execute([
                $coderId,
                '4532890123458892',
                'ALEX MORGAN',
                11,
                (int) date('Y') + 4,
                '742',
                'visa',
                'vip',
                'USD',
                '5829',
                3450.00,
                100000.00,
                500000.00,
                5.00,
                date('Y-m-d H:i:s'),
            ]);
            $cardStmt->execute([
                $coderId,
                '5412758963214418',
                'ALEX MORGAN',
                8,
                (int) date('Y') + 3,
                '319',
                'mastercard',
                'virtual',
                'USD',
                '1234',
                820.00,
                5000.00,
                25000.00,
                1.50,
                date('Y-m-d H:i:s'),
            ]);
            echo "Sample debit cards seeded for coder@gmail.com\n";
        }
    }

    $plans = [
        ['Starting', 5.0000, 'Hourly', 200.00, 200000.00, 12, '5% hourly for 12 hours. Start earning immediately.', '#41e088'],
        ['Standard', 3.5000, 'Hourly', 500.00, 500000.00, 24, '3.5% hourly for 24 hours. Steady returns.', '#3498db'],
        ['Premium', 2.5000, 'Hourly', 1000.00, 1000000.00, 48, '2.5% hourly for 48 hours. Premium plan.', '#9b59b6'],
        ['VIP', 8.0000, 'Daily', 5000.00, 5000000.00, 168, '8% daily for 7 days. VIP exclusive.', '#e67e22'],
    ];
    $stmt = $pdo->prepare("{$insertIgnore} INTO investments (name, rate, period, min_amount, max_amount, duration_hours, description, color) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($isEmpty('investments')) {
        foreach ($plans as $plan) {
            $stmt->execute($plan);
        }
        echo "Investment plans seeded.\n";
    }

    $settings = [
        ['site_name', 'Trustcrypto'],
        ['site_slogan', 'Crypto Investments'],
        ['min_withdrawal', '10'],
        ['referral_rate', '5'],
        ['company_email', 'support@trustcrypto.com'],
    ];
    $stmt = $pdo->prepare("{$insertIgnore} INTO settings (`key`, value) VALUES (?, ?)");
    foreach ($settings as $s) {
        $stmt->execute($s);
    }
    echo "Settings seeded.\n";

    $defaultOffers = [
        ['10% Crypto Cashback on Subscriptions', 'HOT OFFER', 'cashback', 'Earn 10% instant crypto cashback when paying for Netflix, Spotify, Prime Video, or ChatGPT Plus with your TrustCrypto Card.', 'Up to $50 monthly cashback;Instant reward to balance;Valid for all card tiers', 10.00, 'percentage', 'STREAM10', 10.00, date('Y-m-d H:i:s', strtotime('+60 days'))],
        ['$50 Card Activation Welcome Bonus', 'WELCOME BONUS', 'card', 'Order and activate your Metal or Virtual Debit Card and fund $200+ to receive an instant $50 cash credit directly to your account.', 'Instant $50 credited;Zero lock-in period;Eligible for first-time cardholders', 50.00, 'fixed', 'WELCOME50', 200.00, date('Y-m-d H:i:s', strtotime('+45 days'))],
        ['VIP APY Staking Yield Booster (+4.5%)', 'HIGH YIELD', 'deposit', 'Hold an active debit card and boost your daily returns by +4.5% across all active and future investment proposals.', '+4.5% extra yield;Applies to Hourly & Daily plans;Automated compound returns', 4.50, 'percentage', 'BOOST45', 500.00, date('Y-m-d H:i:s', strtotime('+90 days'))],
        ['Zero Foreign Exchange (FX) Fees Worldwide', 'GLOBAL PERK', 'card', 'Spend in 150+ currencies with 0% foreign transaction fees and live institutional crypto-to-fiat conversion rates.', '0% FX markups;Interbank live exchange rate;Accepted at 60M+ merchants', 0.00, 'perk', 'ZEROFEES', 0.00, date('Y-m-d H:i:s', strtotime('+120 days'))],
        ['Weekend Surge: 15% Deposit Match', 'LIMITED TIME', 'deposit', 'Deposit or fund your wallet with $300 or more this weekend to get an instant 15% investment match bonus credited to your portfolio.', '15% instant deposit match;Instant trading activation;Limited to 1 claim per user', 15.00, 'percentage', 'WEEKEND15', 300.00, date('Y-m-d H:i:s', strtotime('+7 days'))],
        ['Airport Lounge & Travel Priority Pass', 'VIP EXCLUSIVE', 'card', 'Complimentary access to 1,200+ airport lounges worldwide, travel insurance, and dedicated 24/7 concierge for Metal and Diamond cards.', 'Priority Pass worldwide;Travel accident insurance;Dedicated personal VIP agent', 0.00, 'perk', 'VIPLOUNGE', 1000.00, date('Y-m-d H:i:s', strtotime('+180 days'))],
    ];
    $stmt = $pdo->prepare("{$insertIgnore} INTO offers (title, badge, category, description, perks, reward_amount, reward_type, promo_code, min_spend, expires_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($isEmpty('offers')) {
        foreach ($defaultOffers as $offer) {
            $stmt->execute($offer);
        }
        echo "Promotional offers seeded.\n";
    }

    echo "Seeding complete.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
