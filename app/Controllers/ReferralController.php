<?php

namespace App\Controllers;

class ReferralController
{
    public function index()
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        $userId = $user['id'];

        $referralLink = url('register?ref=' . ($user['referral_id'] ?? $userId));
        $referrals = \DB::fetchAll("SELECT id, fullname, email, created_at, balance FROM users WHERE referred_by = ? ORDER BY id DESC", [$userId]);
        $totalReferrals = count($referrals);
        $activeReferrals = \DB::fetch("SELECT COUNT(*) c FROM users WHERE referred_by = ? AND total_deposits > 0", [$userId])['c'];
        $referralBonus = (float) $user['referral_bonus'];

        \View::render('referral.index', [
            'active' => 'referrals',
            'title' => 'Refer Users',
            'referralLink' => $referralLink,
            'referrals' => $referrals,
            'totalReferrals' => $totalReferrals,
            'activeReferrals' => $activeReferrals,
            'referralBonus' => $referralBonus,
        ], 'dashboard');
    }
}
