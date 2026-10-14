<?php
$user = \Auth::user();
$kyc = $user ? \DB::fetch("SELECT status FROM kyc_verifications WHERE user_id = ? ORDER BY id DESC LIMIT 1", [$user['id']]) : null;
?>
<aside class="dashboard__sidebar">
    <div style="padding:0 24px 20px;border-bottom:1px solid var(--border);margin-bottom:10px;">
        <a href="<?= url('/') ?>" style="font-size:20px;font-weight:800;color:var(--accent)">TRUST<span style="color:#fff">CRYPTO</span></a>
    </div>
    <ul class="dashboard__nav">
        <li class="<?= ($active ?? '') === 'dashboard' ? 'is-active' : '' ?>">
            <a href="<?= url('dashboard') ?>"><svg viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg> Dashboard</a>
        </li>
        <li class="<?= ($active ?? '') === 'deposit' ? 'is-active' : '' ?>">
            <a href="<?= url('dashboard/deposits') ?>"><svg viewBox="0 0 24 24"><path d="M19 3H5c-1.11 0-2 .89-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.11-.9-2-2-2zm0 16H5v-3h14v3zm0-5H5V5h14v9z"/></svg> Fund your Account</a>
        </li>
        <li class="<?= ($active ?? '') === 'withdraw' ? 'is-active' : '' ?>">
            <a href="<?= url('dashboard/withdrawals') ?>"><svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/></svg> Withdraw Funds</a>
        </li>
        <li class="<?= ($active ?? '') === 'trading' ? 'is-active' : '' ?>">
            <a href="<?= url('dashboard/tradinghistory') ?>"><svg viewBox="0 0 24 24"><path d="M3.5 18.49l6-6.01 4 4L22 6.92l-1.41-1.41-7.09 7.97-4-4L2 16.99z"/></svg> Profit Record</a>
        </li>
        <li class="<?= ($active ?? '') === 'account' || ($active ?? '') === 'transactions' ? 'is-active' : '' ?>">
            <a href="<?= url('dashboard/accounthistory') ?>"><svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/></svg> Transactions history</a>
        </li>
        <li class="<?= ($active ?? '') === 'exchange' ? 'is-active' : '' ?>">
            <a href="<?= url('dashboard/asset-balance') ?>"><svg viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg> Crypto Exchange</a>
        </li>
        <li class="<?= ($active ?? '') === 'invest' ? 'is-active' : '' ?>">
            <a href="<?= url('dashboard/mpack') ?>"><svg viewBox="0 0 24 24"><path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/></svg> Invest Now</a>
        </li>
        <li class="<?= ($active ?? '') === 'cards' ? 'is-active' : '' ?>">
            <a href="<?= url('cards') ?>"><svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg> Debit Cards</a>
        </li>
        <li class="<?= ($active ?? '') === 'offers' ? 'is-active' : '' ?>">
            <a href="<?= url('dashboard/offers') ?>"><svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg> Special Offers <span style="font-size:9px;background:var(--accent);color:#0a0e17;font-weight:800;padding:2px 6px;border-radius:6px;margin-left:6px;">HOT</span></a>
        </li>
        <li class="<?= ($active ?? '') === 'referrals' ? 'is-active' : '' ?>">
            <a href="<?= url('dashboard/referuser') ?>"><svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg> Refer Users</a>
        </li>
        <li class="<?= ($active ?? '') === 'kyc' ? 'is-active' : '' ?>">
            <a href="<?= url('dashboard/kyc') ?>">
                <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
                KYC
                <?php if (!$kyc || $kyc['status'] !== 'approved'): ?>
                <span style="font-size:9px;background:#e67e22;color:#fff;padding:2px 6px;border-radius:8px;margin-left:6px;"><?= $kyc ? ucfirst($kyc['status']) : 'Verify' ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li class="<?= ($active ?? '') === 'support' ? 'is-active' : '' ?>">
            <a href="<?= url('dashboard/support') ?>"><svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/></svg> Help/Support</a>
        </li>
        <li class="<?= ($active ?? '') === 'profile' ? 'is-active' : '' ?>">
            <a href="<?= url('dashboard/profile') ?>"><svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg> Profile</a>
        </li>
        <?php if ($user && $user['is_admin']): ?>
        <li>
            <a href="<?= url('admin') ?>"><svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg> Admin Panel</a>
        </li>
        <?php endif; ?>
        <li>
            <a href="<?= url('logout') ?>"><svg viewBox="0 0 24 24"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg> Logout</a>
        </li>
    </ul>
</aside>
