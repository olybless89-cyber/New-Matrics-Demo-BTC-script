<?php

class DB
{
    private static ?PDO $pdo = null;
    private static array $config = [];

    public static function init(string $host, int $port, string $name, string $user, string $pass, string $driver = 'mysql'): void
    {
        self::$config = compact('host', 'port', 'name', 'user', 'pass', 'driver');
    }

    private static function connect(): void
    {
        if (self::$pdo !== null) {
            return;
        }
        $c = self::$config;
        if ($c['driver'] === 'sqlite') {
            $dsn = "sqlite:{$c['name']}";
            self::$pdo = new PDO($dsn, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            self::$pdo->exec('PRAGMA foreign_keys = ON');
            self::ensureSchema();
            return;
        }
        $dsn = "mysql:host={$c['host']};port={$c['port']};dbname={$c['name']};charset=utf8mb4";
        self::$pdo = new PDO($dsn, $c['user'], $c['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        self::ensureSchema();
    }

    private static function ensureSchema(): void
    {
        try {
            self::$pdo->exec("CREATE TABLE IF NOT EXISTS offers (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title VARCHAR(150) NOT NULL,
                badge VARCHAR(50) DEFAULT 'FEATURED',
                category VARCHAR(50) NOT NULL DEFAULT 'card',
                description TEXT NOT NULL,
                perks TEXT DEFAULT NULL,
                reward_amount DECIMAL(18,2) DEFAULT 0.00,
                reward_type VARCHAR(50) NOT NULL DEFAULT 'percentage',
                promo_code VARCHAR(50) DEFAULT NULL,
                min_spend DECIMAL(18,2) DEFAULT 0.00,
                expires_at DATETIME DEFAULT NULL,
                is_active TINYINT(1) NOT NULL DEFAULT 1,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )");

            self::$pdo->exec("CREATE TABLE IF NOT EXISTS user_offers (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INT UNSIGNED NOT NULL,
                offer_id INT UNSIGNED NOT NULL,
                claimed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                status VARCHAR(50) NOT NULL DEFAULT 'active'
            )");

            $cols = [
                'card_tier' => "VARCHAR(30) NOT NULL DEFAULT 'virtual'",
                'currency' => "VARCHAR(10) NOT NULL DEFAULT 'USD'",
                'pin' => "VARCHAR(4) NOT NULL DEFAULT '1234'",
                'monthly_limit' => "DECIMAL(18,2) NOT NULL DEFAULT 25000.00",
                'is_online_enabled' => "TINYINT(1) NOT NULL DEFAULT 1",
                'is_intl_enabled' => "TINYINT(1) NOT NULL DEFAULT 1",
                'cashback_rate' => "DECIMAL(5,2) NOT NULL DEFAULT 1.50",
                'billing_address' => "TEXT DEFAULT NULL",
            ];
            foreach ($cols as $col => $type) {
                try {
                    self::$pdo->exec("ALTER TABLE debit_cards ADD COLUMN {$col} {$type}");
                } catch (\Throwable $e) {}
            }

            try {
                self::$pdo->exec("ALTER TABLE card_transactions ADD COLUMN cashback_amount DECIMAL(18,2) NOT NULL DEFAULT 0.00");
            } catch (\Throwable $e) {}

            $count = (int) (self::$pdo->query("SELECT COUNT(*) FROM offers")->fetchColumn() ?: 0);
            if ($count === 0) {
                $defaultOffers = [
                    ['10% Crypto Cashback on Subscriptions', 'HOT OFFER', 'cashback', 'Earn 10% instant crypto cashback when paying for Netflix, Spotify, Prime Video, or ChatGPT Plus with your TrustCrypto Card.', 'Up to $50 monthly cashback;Instant reward to balance;Valid for all card tiers', 10.00, 'percentage', 'STREAM10', 10.00, date('Y-m-d H:i:s', strtotime('+60 days'))],
                    ['$50 Card Activation Welcome Bonus', 'WELCOME BONUS', 'card', 'Order and activate your Metal or Virtual Debit Card and fund $200+ to receive an instant $50 cash credit directly to your account.', 'Instant $50 credited;Zero lock-in period;Eligible for first-time cardholders', 50.00, 'fixed', 'WELCOME50', 200.00, date('Y-m-d H:i:s', strtotime('+45 days'))],
                    ['VIP APY Staking Yield Booster (+4.5%)', 'HIGH YIELD', 'deposit', 'Hold an active debit card and boost your daily returns by +4.5% across all active and future investment proposals.', '+4.5% extra yield;Applies to Hourly & Daily plans;Automated compound returns', 4.50, 'percentage', 'BOOST45', 500.00, date('Y-m-d H:i:s', strtotime('+90 days'))],
                    ['Zero Foreign Exchange (FX) Fees Worldwide', 'GLOBAL PERK', 'card', 'Spend in 150+ currencies with 0% foreign transaction fees and live institutional crypto-to-fiat conversion rates.', '0% FX markups;Interbank live exchange rate;Accepted at 60M+ merchants', 0.00, 'perk', 'ZEROFEES', 0.00, date('Y-m-d H:i:s', strtotime('+120 days'))],
                    ['Weekend Surge: 15% Deposit Match', 'LIMITED TIME', 'deposit', 'Deposit or fund your wallet with $300 or more this weekend to get an instant 15% investment match bonus credited to your portfolio.', '15% instant deposit match;Instant trading activation;Limited to 1 claim per user', 15.00, 'percentage', 'WEEKEND15', 300.00, date('Y-m-d H:i:s', strtotime('+7 days'))],
                    ['Airport Lounge & Travel Priority Pass', 'VIP EXCLUSIVE', 'card', 'Complimentary access to 1,200+ airport lounges worldwide, travel insurance, and dedicated 24/7 concierge for Metal and Diamond cards.', 'Priority Pass worldwide;Travel accident insurance;Dedicated personal VIP agent', 0.00, 'perk', 'VIPLOUNGE', 1000.00, date('Y-m-d H:i:s', strtotime('+180 days'))],
                ];
                $stmt = self::$pdo->prepare("INSERT INTO offers (title, badge, category, description, perks, reward_amount, reward_type, promo_code, min_spend, expires_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                foreach ($defaultOffers as $offer) {
                    $stmt->execute($offer);
                }
            }
        } catch (\Throwable $e) {}
    }

    public static function pdo(): PDO
    {
        self::connect();
        return self::$pdo;
    }

    public static function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetch(string $sql, array $params = []): ?array
    {
        $row = self::query($sql, $params)->fetch();
        return $row ?: null;
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    public static function insert(string $table, array $data): int
    {
        $columns = array_keys($data);
        $placeholders = array_map(fn($c) => ":{$c}", $columns);
        $sql = "INSERT INTO `{$table}` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $placeholders) . ")";
        self::query($sql, $data);
        return (int) self::pdo()->lastInsertId();
    }

    public static function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $sets = [];
        foreach (array_keys($data) as $col) {
            $sets[] = "`{$col}` = :set_{$col}";
        }
        $sql = "UPDATE `{$table}` SET " . implode(', ', $sets) . " WHERE {$where}";
        $params = [];
        foreach ($data as $col => $val) {
            $params["set_{$col}"] = $val;
        }
        foreach ($whereParams as $k => $v) {
            $params[$k] = $v;
        }
        return self::query($sql, $params)->rowCount();
    }

    public static function delete(string $table, string $where, array $params = []): int
    {
        return self::query("DELETE FROM `{$table}` WHERE {$where}", $params)->rowCount();
    }
}
