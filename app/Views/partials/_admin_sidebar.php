<?php $user = \Auth::user(); ?>
<aside class="dashboard__sidebar">
    <div style="padding:0 24px 20px;border-bottom:1px solid var(--border);margin-bottom:10px;">
        <a href="<?= url('admin') ?>" style="font-size:20px;font-weight:800;color:var(--accent)">ADMIN<span style="color:#fff">PANEL</span></a>
    </div>
    <ul class="dashboard__nav">
        <li class="<?= ($active ?? '') === 'admin' ? 'is-active' : '' ?>">
            <a href="<?= url('admin') ?>"><svg viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg> Overview</a>
        </li>
        <li class="<?= ($active ?? '') === 'users' ? 'is-active' : '' ?>">
            <a href="<?= url('admin/users') ?>"><svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg> Users</a>
        </li>
        <li class="<?= ($active ?? '') === 'deposits' ? 'is-active' : '' ?>">
            <a href="<?= url('admin/deposits') ?>"><svg viewBox="0 0 24 24"><path d="M19 3H5c-1.11 0-2 .89-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.11-.9-2-2-2zm0 16H5v-3h14v3zm0-5H5V5h14v9z"/></svg> Deposits</a>
        </li>
        <li class="<?= ($active ?? '') === 'withdrawals' ? 'is-active' : '' ?>">
            <a href="<?= url('admin/withdrawals') ?>"><svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/></svg> Withdrawals</a>
        </li>
        <li class="<?= ($active ?? '') === 'cards' ? 'is-active' : '' ?>">
            <a href="<?= url('admin/cards') ?>"><svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg> Debit Cards</a>
        </li>
        <li class="<?= ($active ?? '') === 'investments' ? 'is-active' : '' ?>">
            <a href="<?= url('admin/investments') ?>"><svg viewBox="0 0 24 24"><path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/></svg> Investments</a>
        </li>
        <li class="<?= ($active ?? '') === 'messages' ? 'is-active' : '' ?>">
            <a href="<?= url('admin/messages') ?>"><svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg> Messages</a>
        </li>
        <li class="<?= ($active ?? '') === 'kyc' ? 'is-active' : '' ?>">
            <a href="<?= url('admin/kyc') ?>"><svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg> KYC Verifications</a>
        </li>
        <li class="<?= ($active ?? '') === 'support' ? 'is-active' : '' ?>">
            <a href="<?= url('admin/support') ?>"><svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/></svg> Support Tickets</a>
        </li>
        <li>
            <a href="<?= url('dashboard') ?>"><svg viewBox="0 0 24 24"><path d="M10 20l5-5-5-5v10z"/><path d="M12 4h8v16h-2V6h-6V4z"/></svg> User Dashboard</a>
        </li>
        <li>
            <a href="<?= url('logout') ?>"><svg viewBox="0 0 24 24"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg> Logout</a>
        </li>
    </ul>
</aside>
