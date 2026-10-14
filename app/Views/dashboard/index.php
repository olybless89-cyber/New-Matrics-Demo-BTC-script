<?php $u = \Auth::user(); ?>
<div class="dashboard__header">
    <div>
        <h2>Welcome, <?= e($u['fullname']) ?></h2>
        <p class="text-muted" style="font-size:13px;">Here's your account overview</p>
    </div>
    <a href="<?= url('deposit') ?>" class="btn btn--accent btn--sm"><i class="fa fa-plus"></i> New Deposit</a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card__label">Account Balance</div>
        <div class="stat-card__value">$<?= number_format($stats['balance'], 2) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card__label">Total Profit</div>
        <div class="stat-card__value" style="color:var(--accent);">$<?= number_format($stats['total_profit'], 2) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card__label">Total Bonus</div>
        <div class="stat-card__value">$<?= number_format($stats['total_bonus'], 2) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card__label">Total Referral Bonus</div>
        <div class="stat-card__value">$<?= number_format($stats['total_referral_bonus'], 2) ?></div>
    </div>
</div>

<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-top:16px;">
    <div class="stat-card" style="padding:16px;">
        <div class="stat-card__label">Total Investment Plans</div>
        <div class="stat-card__value" style="font-size:24px;"><?= $stats['total_plans'] ?></div>
    </div>
    <div class="stat-card" style="padding:16px;">
        <div class="stat-card__label">Active Investment Plans</div>
        <div class="stat-card__value" style="font-size:24px;color:var(--accent);"><?= $stats['active_plans'] ?></div>
    </div>
    <div class="stat-card" style="padding:16px;">
        <div class="stat-card__label">Total Deposit</div>
        <div class="stat-card__value" style="font-size:24px;">$<?= number_format($stats['total_deposits'], 2) ?></div>
    </div>
    <div class="stat-card" style="padding:16px;">
        <div class="stat-card__label">Total Withdrawals</div>
        <div class="stat-card__value" style="font-size:24px;">$<?= number_format($stats['total_withdrawals'], 2) ?></div>
    </div>
</div>

<!-- Crypto Debit Cards & Offers Spotlight -->
<div class="card" style="background:linear-gradient(135deg, rgba(20,28,38,0.95) 0%, rgba(30,45,60,0.9) 100%);border:1px solid var(--border);border-left:4px solid var(--accent);padding:22px;margin:20px 0;">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:15px;">
        <div style="display:flex;align-items:center;gap:16px;">
            <div style="width:48px;height:48px;border-radius:12px;background:rgba(65,224,136,0.15);display:flex;align-items:center;justify-content:center;color:var(--accent);font-size:22px;">
                <i class="fa fa-credit-card"></i>
            </div>
            <div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <h4 style="margin:0;font-size:16px;color:#fff;">TrustCrypto Debit Card & Cashback Hub</h4>
                    <span class="badge badge--success" style="font-size:9px;">UP TO 5% CASHBACK</span>
                </div>
                <p class="text-muted" style="margin:4px 0 0;font-size:13px;">
                    <?php if (($cardCount ?? 0) > 0): ?>
                        You have <strong><?= $cardCount ?></strong> active debit card(s). Fund your card, track cashback, or test transactions anytime.
                    <?php else: ?>
                        Spend your crypto balance worldwide with 0% FX fees. Virtual cards are 100% free with instant activation!
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="<?= url('dashboard/offers') ?>" class="btn btn--outline btn--sm" style="border-color:#d4af37;color:#d4af37;">
                <i class="fa fa-gift"></i> Special Offers
            </a>
            <a href="<?= url('cards') ?>" class="btn btn--accent btn--sm">
                <i class="fa fa-credit-card"></i> <?= (($cardCount ?? 0) > 0) ? 'Manage Cards' : 'Get Free Virtual Card' ?>
            </a>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;" class="dashboard-grid">
    <div class="card">
        <div class="d-flex justify-between align-center mb-2">
            <h3>Active Investments</h3>
            <a href="<?= url('invest') ?>" class="btn btn--outline btn--sm">View All</a>
        </div>
        <?php if (empty($activeInvestments)): ?>
            <p class="text-muted" style="text-align:center;padding:30px 0;">No active investments. <a href="<?= url('invest') ?>">Start investing</a></p>
        <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr><th>Plan</th><th>Amount</th><th>Rate</th><th>Earned</th><th>End Date</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($activeInvestments as $inv): ?>
                    <tr>
                        <td><?= e($inv['plan_name']) ?></td>
                        <td>$<?= number_format($inv['amount'], 2) ?></td>
                        <td><?= rtrim(rtrim(number_format($inv['rate'], 4), '0'), '.') ?>% <?= e($inv['period']) ?></td>
                        <td class="text-accent">$<?= number_format($inv['earned'], 2) ?></td>
                        <td><?= date('M j, Y', strtotime($inv['end_date'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <h3>Recent Deposits</h3>
        <?php if (empty($recentDeposits)): ?>
            <p class="text-muted" style="text-align:center;padding:20px 0;">No deposits yet</p>
        <?php else: ?>
            <?php foreach ($recentDeposits as $d): ?>
            <div style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border);">
                <div>
                    <div style="font-weight:600;">$<?= number_format($d['amount'], 2) ?></div>
                    <div style="font-size:12px;color:var(--text-muted);"><?= e($d['payment_method']) ?></div>
                </div>
                <span class="badge badge--<?= $d['status'] === 'approved' ? 'success' : ($d['status'] === 'pending' ? 'warning' : 'danger') ?>"><?= ucfirst($d['status']) ?></span>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div class="card mt-3">
    <h3>Recent Transactions</h3>
    <?php if (empty($recentTxns)): ?>
        <p class="text-muted" style="text-align:center;padding:20px 0;">No transactions yet</p>
    <?php else: ?>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr><th>Type</th><th>Amount</th><th>Description</th><th>Date</th></tr>
            </thead>
            <tbody>
                <?php foreach ($recentTxns as $t): ?>
                <tr>
                    <td><span class="badge badge--<?= $t['type'] === 'deposit' || $t['type'] === 'earning' || $t['type'] === 'referral_bonus' ? 'success' : 'warning' ?>"><?= ucfirst(str_replace('_', ' ', $t['type'])) ?></span></td>
                    <td class="<?= ($t['amount'] >= 0) ? 'text-accent' : '' ?>"><?= $t['amount'] >= 0 ? '+' : '' ?>$<?= number_format($t['amount'], 2) ?></td>
                    <td class="text-muted"><?= e($t['description'] ?? '-') ?></td>
                    <td style="white-space:nowrap;"><?= date('M j, Y g:i A', strtotime($t['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
