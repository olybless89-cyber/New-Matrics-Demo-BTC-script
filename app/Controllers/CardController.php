<?php

namespace App\Controllers;

class CardController
{
    public function index()
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        $userId = $user['id'];

        $cards = \DB::fetchAll("SELECT * FROM debit_cards WHERE user_id=? ORDER BY id DESC", [$userId]);
        $cardTxns = [];
        $totalCardBalance = 0;
        $totalCashback = 0;

        foreach ($cards as $c) {
            $txns = \DB::fetchAll("SELECT * FROM card_transactions WHERE card_id=? ORDER BY id DESC LIMIT 15", [$c['id']]);
            $cardTxns[$c['id']] = $txns;
            $totalCardBalance += (float) $c['balance'];
            
            $cb = \DB::fetch("SELECT COALESCE(SUM(cashback_amount), 0) as cb FROM card_transactions WHERE card_id=?", [$c['id']]);
            if ($cb) {
                $totalCashback += (float) $cb['cb'];
            }
        }

        // Get top 3 featured offers for the cards dashboard
        $offers = \DB::fetchAll("SELECT * FROM offers WHERE is_active=1 ORDER BY id ASC LIMIT 3");

        \View::render('cards.index', [
            'active' => 'cards',
            'title' => 'Crypto Debit Cards',
            'cards' => $cards,
            'cardTxns' => $cardTxns,
            'balance' => (float) $user['balance'],
            'totalCardBalance' => $totalCardBalance,
            'totalCashback' => $totalCashback,
            'offers' => $offers,
            'user' => $user,
        ], 'dashboard');
    }

    public function apply()
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        $userId = $user['id'];

        $tier = $_POST['card_tier'] ?? 'virtual';
        $type = in_array(strtolower($_POST['card_type'] ?? 'visa'), ['visa', 'mastercard']) ? strtolower($_POST['card_type']) : 'visa';
        $currency = in_array(strtoupper($_POST['currency'] ?? 'USD'), ['USD', 'EUR', 'GBP']) ? strtoupper($_POST['currency']) : 'USD';
        $cardholderName = trim($_POST['cardholder_name'] ?? '');
        if (empty($cardholderName)) {
            $cardholderName = $user['fullname'] ?: $user['username'];
        }
        $pin = trim($_POST['pin'] ?? '');
        if (strlen($pin) !== 4 || !ctype_digit($pin)) {
            $pin = (string) random_int(1000, 9999);
        }
        $billingAddress = trim($_POST['billing_address'] ?? '');

        // Fee and limits per tier
        $fee = 0.00;
        $dailyLimit = 5000.00;
        $monthlyLimit = 25000.00;
        $cashbackRate = 1.50;

        if ($tier === 'metal') {
            $fee = 25.00;
            $dailyLimit = 25000.00;
            $monthlyLimit = 100000.00;
            $cashbackRate = 3.00;
        } elseif ($tier === 'vip') {
            $fee = 100.00;
            $dailyLimit = 100000.00;
            $monthlyLimit = 500000.00;
            $cashbackRate = 5.00;
        } else {
            $tier = 'virtual';
        }

        if ($fee > 0 && $user['balance'] < $fee) {
            flash('error', "Insufficient account balance for {$tier} card issuance fee of $" . number_format($fee, 2) . ". Please deposit funds first.");
            redirect('cards');
        }

        $cardNumber = $this->generateCardNumber($type);
        $cvv = str_pad((string) random_int(100, 999), 3, '0', STR_PAD_LEFT);
        $expiryMonth = (int) date('n');
        $expiryYear = (int) date('Y') + 4;

        \DB::pdo()->beginTransaction();
        try {
            if ($fee > 0) {
                $newBal = $user['balance'] - $fee;
                \DB::update('users', ['balance' => $newBal], 'id = :id', ['id' => $userId]);
                \DB::insert('transactions', [
                    'user_id' => $userId,
                    'type' => 'card_issuance',
                    'amount' => -$fee,
                    'balance_after' => $newBal,
                    'description' => ucfirst($tier) . " {$type} Debit Card Issuance Fee",
                    'reference' => 'CRD-' . strtoupper(substr(md5(uniqid()), 0, 8)),
                ]);
            }

            $cardId = \DB::insert('debit_cards', [
                'user_id' => $userId,
                'card_number' => $cardNumber,
                'cardholder_name' => $cardholderName,
                'expiry_month' => $expiryMonth,
                'expiry_year' => $expiryYear,
                'cvv' => $cvv,
                'card_type' => $type,
                'card_tier' => $tier,
                'currency' => $currency,
                'pin' => $pin,
                'balance' => 0.00,
                'daily_limit' => $dailyLimit,
                'monthly_limit' => $monthlyLimit,
                'is_online_enabled' => 1,
                'is_intl_enabled' => 1,
                'cashback_rate' => $cashbackRate,
                'billing_address' => $billingAddress,
                'status' => 'active',
                'activated_at' => date('Y-m-d H:i:s'),
                'issued_by' => null,
            ]);

            \DB::pdo()->commit();
            flash('success', "Your " . ucfirst($tier) . " " . strtoupper($type) . " Card ending in " . substr($cardNumber, -4) . " has been successfully issued and activated!");
        } catch (\Throwable $e) {
            \DB::pdo()->rollBack();
            flash('error', 'Failed to issue card: ' . $e->getMessage());
        }

        redirect('cards');
    }

    public function fund()
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        $userId = $user['id'];
        $cardId = (int) ($_POST['card_id'] ?? 0);
        $amount = (float) ($_POST['amount'] ?? 0);

        $card = \DB::fetch("SELECT * FROM debit_cards WHERE id=? AND user_id=?", [$cardId, $userId]);
        if (!$card) {
            flash('error', 'Card not found.');
            redirect('cards');
        }
        if ($card['status'] !== 'active') {
            flash('error', 'Cannot fund a card that is frozen or inactive.');
            redirect('cards');
        }
        if ($amount <= 0 || $amount > $user['balance']) {
            flash('error', 'Invalid amount or insufficient account balance.');
            redirect('cards');
        }

        \DB::pdo()->beginTransaction();
        try {
            $newBalance = $user['balance'] - $amount;
            \DB::update('users', ['balance' => $newBalance], 'id = :id', ['id' => $userId]);
            \DB::update('debit_cards', ['balance' => $card['balance'] + $amount], 'id = :id', ['id' => $cardId]);
            \DB::insert('card_transactions', [
                'card_id' => $cardId,
                'user_id' => $userId,
                'type' => 'fund',
                'amount' => $amount,
                'description' => 'Card funded from main wallet',
                'merchant' => 'TrustCrypto Wallet Transfer',
                'cashback_amount' => 0.00,
            ]);
            \DB::insert('transactions', [
                'user_id' => $userId,
                'type' => 'card_fund',
                'amount' => -$amount,
                'balance_after' => $newBalance,
                'description' => "Funded debit card ending in " . substr($card['card_number'], -4),
                'reference' => 'CF-' . strtoupper(substr(md5(uniqid()), 0, 8)),
            ]);
            \DB::pdo()->commit();
            flash('success', "Successfully loaded $" . number_format($amount, 2) . " onto your card ending in " . substr($card['card_number'], -4) . ".");
        } catch (\Throwable $e) {
            \DB::pdo()->rollBack();
            flash('error', 'Failed to fund card: ' . $e->getMessage());
        }
        redirect('cards');
    }

    public function withdrawToWallet($id)
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        $userId = $user['id'];
        $cardId = (int) $id;
        $amount = (float) ($_POST['amount'] ?? 0);

        $card = \DB::fetch("SELECT * FROM debit_cards WHERE id=? AND user_id=?", [$cardId, $userId]);
        if (!$card) {
            flash('error', 'Card not found.');
            redirect('cards');
        }
        if ($amount <= 0 || $amount > $card['balance']) {
            flash('error', 'Invalid amount or insufficient card balance.');
            redirect('cards');
        }

        \DB::pdo()->beginTransaction();
        try {
            $newUserBal = $user['balance'] + $amount;
            $newCardBal = $card['balance'] - $amount;

            \DB::update('debit_cards', ['balance' => $newCardBal], 'id = :id', ['id' => $cardId]);
            \DB::update('users', ['balance' => $newUserBal], 'id = :id', ['id' => $userId]);

            \DB::insert('card_transactions', [
                'card_id' => $cardId,
                'user_id' => $userId,
                'type' => 'withdraw',
                'amount' => $amount,
                'description' => 'Transferred funds back to main wallet balance',
                'merchant' => 'TrustCrypto Wallet',
                'cashback_amount' => 0.00,
            ]);

            \DB::insert('transactions', [
                'user_id' => $userId,
                'type' => 'adjustment',
                'amount' => $amount,
                'balance_after' => $newUserBal,
                'description' => "Transferred from card ending in " . substr($card['card_number'], -4) . " back to balance",
                'reference' => 'CW-' . strtoupper(substr(md5(uniqid()), 0, 8)),
            ]);

            \DB::pdo()->commit();
            flash('success', "Successfully moved $" . number_format($amount, 2) . " from card to your main account balance.");
        } catch (\Throwable $e) {
            \DB::pdo()->rollBack();
            flash('error', 'Withdrawal failed: ' . $e->getMessage());
        }
        redirect('cards');
    }

    public function freeze($id)
    {
        \Auth::requireAuth();
        $userId = \Auth::user()['id'];
        $cardId = (int) $id;

        $card = \DB::fetch("SELECT * FROM debit_cards WHERE id=? AND user_id=?", [$cardId, $userId]);
        if ($card) {
            \DB::update('debit_cards', ['status' => 'blocked'], 'id = :id', ['id' => $cardId]);
            flash('info', "Card ending in " . substr($card['card_number'], -4) . " has been frozen. All new transactions are temporarily blocked.");
        } else {
            flash('error', 'Card not found.');
        }
        redirect('cards');
    }

    public function unfreeze($id)
    {
        \Auth::requireAuth();
        $userId = \Auth::user()['id'];
        $cardId = (int) $id;

        $card = \DB::fetch("SELECT * FROM debit_cards WHERE id=? AND user_id=?", [$cardId, $userId]);
        if ($card) {
            \DB::update('debit_cards', ['status' => 'active'], 'id = :id', ['id' => $cardId]);
            flash('success', "Card ending in " . substr($card['card_number'], -4) . " is now unblocked and ready for transactions!");
        } else {
            flash('error', 'Card not found.');
        }
        redirect('cards');
    }

    public function updateLimits($id)
    {
        \Auth::requireAuth();
        $userId = \Auth::user()['id'];
        $cardId = (int) $id;

        $card = \DB::fetch("SELECT * FROM debit_cards WHERE id=? AND user_id=?", [$cardId, $userId]);
        if (!$card) {
            flash('error', 'Card not found.');
            redirect('cards');
        }

        $dailyLimit = (float) ($_POST['daily_limit'] ?? $card['daily_limit']);
        $monthlyLimit = (float) ($_POST['monthly_limit'] ?? ($card['monthly_limit'] ?? 25000));
        $isOnline = isset($_POST['is_online_enabled']) ? 1 : 0;
        $isIntl = isset($_POST['is_intl_enabled']) ? 1 : 0;
        $newPin = trim($_POST['pin'] ?? '');

        $data = [
            'daily_limit' => max(100, $dailyLimit),
            'monthly_limit' => max(500, $monthlyLimit),
            'is_online_enabled' => $isOnline,
            'is_intl_enabled' => $isIntl,
        ];

        if (strlen($newPin) === 4 && ctype_digit($newPin)) {
            $data['pin'] = $newPin;
        }

        \DB::update('debit_cards', $data, 'id = :id', ['id' => $cardId]);
        flash('success', 'Card limits and security settings updated successfully.');
        redirect('cards');
    }

    public function simulateSpend($id)
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        $userId = $user['id'];
        $cardId = (int) $id;

        $card = \DB::fetch("SELECT * FROM debit_cards WHERE id=? AND user_id=?", [$cardId, $userId]);
        if (!$card) {
            flash('error', 'Card not found.');
            redirect('cards');
        }
        if ($card['status'] !== 'active') {
            flash('error', 'Card is frozen or inactive. Please unfreeze it first.');
            redirect('cards');
        }

        $merchant = trim($_POST['merchant'] ?? 'Amazon Web Store');
        $amount = (float) ($_POST['amount'] ?? 25.00);

        if ($amount <= 0) {
            flash('error', 'Invalid spend amount.');
            redirect('cards');
        }
        if ($amount > $card['balance']) {
            flash('error', "Transaction declined: Insufficient card balance (Card Balance: $" . number_format($card['balance'], 2) . ", Attempted: $" . number_format($amount, 2) . "). Please fund your card first.");
            redirect('cards');
        }
        if ($amount > $card['daily_limit']) {
            flash('error', "Transaction declined: Exceeds your daily card limit of $" . number_format($card['daily_limit'], 2) . ".");
            redirect('cards');
        }

        // Calculate cashback
        $rate = (float) ($card['cashback_rate'] ?? 2.0);
        // Special bonus cashback on streaming services
        if (stripos($merchant, 'netflix') !== false || stripos($merchant, 'spotify') !== false) {
            $rate = max($rate, 10.0);
        }
        $cashback = round(($amount * $rate) / 100, 2);

        \DB::pdo()->beginTransaction();
        try {
            $newCardBal = $card['balance'] - $amount;
            \DB::update('debit_cards', ['balance' => $newCardBal], 'id = :id', ['id' => $cardId]);

            \DB::insert('card_transactions', [
                'card_id' => $cardId,
                'user_id' => $userId,
                'type' => 'purchase',
                'amount' => $amount,
                'description' => "POS Purchase at {$merchant}",
                'merchant' => $merchant,
                'cashback_amount' => $cashback,
            ]);

            // Credit cashback directly to user wallet balance!
            if ($cashback > 0) {
                $newAccBal = $user['balance'] + $cashback;
                \DB::update('users', ['balance' => $newAccBal], 'id = :id', ['id' => $userId]);
                \DB::insert('transactions', [
                    'user_id' => $userId,
                    'type' => 'earning',
                    'amount' => $cashback,
                    'balance_after' => $newAccBal,
                    'description' => "{$rate}% Card Cashback from {$merchant}",
                    'reference' => 'CB-' . strtoupper(substr(md5(uniqid()), 0, 8)),
                ]);
            }

            \DB::pdo()->commit();
            flash('success', "Card payment of $" . number_format($amount, 2) . " at {$merchant} approved! You earned +$" . number_format($cashback, 2) . " ({$rate}%) instant crypto cashback.");
        } catch (\Throwable $e) {
            \DB::pdo()->rollBack();
            flash('error', 'Transaction simulation failed: ' . $e->getMessage());
        }

        redirect('cards');
    }

    public function offers()
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        $userId = $user['id'];

        $offers = \DB::fetchAll("SELECT * FROM offers WHERE is_active=1 ORDER BY id ASC");
        $claimedRows = \DB::fetchAll("SELECT offer_id FROM user_offers WHERE user_id=?", [$userId]);
        $claimedIds = array_column($claimedRows, 'offer_id');

        $cards = \DB::fetchAll("SELECT * FROM debit_cards WHERE user_id=? AND status='active'", [$userId]);

        \View::render('cards.offers', [
            'active' => 'offers',
            'title' => 'Offers, Cashbacks & Rewards Hub',
            'offers' => $offers,
            'claimedIds' => $claimedIds,
            'user' => $user,
            'cards' => $cards,
        ], 'dashboard');
    }

    public function claimOffer()
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        $userId = $user['id'];
        $offerId = (int) ($_POST['offer_id'] ?? 0);

        $offer = \DB::fetch("SELECT * FROM offers WHERE id=? AND is_active=1", [$offerId]);
        if (!$offer) {
            flash('error', 'Offer not found or expired.');
            redirect('dashboard/offers');
        }

        $already = \DB::fetch("SELECT id FROM user_offers WHERE user_id=? AND offer_id=?", [$userId, $offerId]);
        if ($already) {
            flash('info', 'You have already activated this offer.');
            redirect('dashboard/offers');
        }

        \DB::pdo()->beginTransaction();
        try {
            \DB::insert('user_offers', [
                'user_id' => $userId,
                'offer_id' => $offerId,
                'status' => 'active',
            ]);

            // If it's a fixed cash bonus offer, reward it!
            if ($offer['reward_type'] === 'fixed' && (float) $offer['reward_amount'] > 0) {
                $reward = (float) $offer['reward_amount'];
                $newBal = $user['balance'] + $reward;
                \DB::update('users', ['balance' => $newBal], 'id = :id', ['id' => $userId]);
                \DB::insert('transactions', [
                    'user_id' => $userId,
                    'type' => 'earning',
                    'amount' => $reward,
                    'balance_after' => $newBal,
                    'description' => "Offer Claim Bonus: {$offer['title']}",
                    'reference' => 'OFFER-' . strtoupper(substr(md5(uniqid()), 0, 8)),
                ]);
                flash('success', "Offer \"{$offer['title']}\" activated! $" . number_format($reward, 2) . " bonus has been credited to your balance.");
            } else {
                flash('success', "Offer \"{$offer['title']}\" has been successfully activated on your account!");
            }

            \DB::pdo()->commit();
        } catch (\Throwable $e) {
            \DB::pdo()->rollBack();
            flash('error', 'Failed to activate offer: ' . $e->getMessage());
        }

        redirect('dashboard/offers');
    }

    public function redeemCode()
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        $userId = $user['id'];
        $code = strtoupper(trim($_POST['promo_code'] ?? ''));

        if (empty($code)) {
            flash('error', 'Please enter a valid promo code.');
            redirect('dashboard/offers');
        }

        $validCodes = [
            'WELCOME50' => ['amount' => 50.00, 'name' => '$50 Welcome Balance Booster'],
            'CRYPTOVIP' => ['amount' => 100.00, 'name' => '$100 VIP Elite Trader Bonus'],
            'STREAM10' => ['amount' => 15.00, 'name' => '$15 Streaming Subscription Grant'],
            'WEEKEND15' => ['amount' => 45.00, 'name' => '$45 Weekend Surge Investment Match'],
            'BOOST45' => ['amount' => 25.00, 'name' => '$25 APY Staking Grant'],
            'ZEROFEES' => ['amount' => 10.00, 'name' => '$10 Global FX Fee Rebate'],
        ];

        if (!isset($validCodes[$code])) {
            flash('error', "Invalid or expired promo code: '{$code}'. Please check and try again.");
            redirect('dashboard/offers');
        }

        // Check if user already redeemed this promo code in transactions
        $refCheck = 'PROMO-' . $code;
        $exists = \DB::fetch("SELECT id FROM transactions WHERE user_id=? AND reference=?", [$userId, $refCheck]);
        if ($exists) {
            flash('info', "You have already redeemed promo code '{$code}'.");
            redirect('dashboard/offers');
        }

        $promo = $validCodes[$code];
        $reward = (float) $promo['amount'];

        \DB::pdo()->beginTransaction();
        try {
            $newBal = $user['balance'] + $reward;
            \DB::update('users', ['balance' => $newBal], 'id = :id', ['id' => $userId]);
            \DB::insert('transactions', [
                'user_id' => $userId,
                'type' => 'earning',
                'amount' => $reward,
                'balance_after' => $newBal,
                'description' => "Promo Code Redeemed: {$promo['name']} ({$code})",
                'reference' => $refCheck,
            ]);
            \DB::pdo()->commit();
            flash('success', "🎉 Promo code '{$code}' redeemed successfully! +$" . number_format($reward, 2) . " has been added to your account balance.");
        } catch (\Throwable $e) {
            \DB::pdo()->rollBack();
            flash('error', 'Redemption failed: ' . $e->getMessage());
        }

        redirect('dashboard/offers');
    }

    private function generateCardNumber(string $type = 'visa'): string
    {
        $prefix = ($type === 'mastercard') ? '54' : '45';
        $number = $prefix;
        for ($i = 0; $i < 14; $i++) {
            $number .= (string) random_int(0, 9);
        }
        return $number;
    }
}
