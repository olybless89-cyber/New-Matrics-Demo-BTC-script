<div class="dashboard__header">
    <h2>Admin Overview</h2>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card__label">Total Users</div>
        <div class="stat-card__value"><?= number_format($stats['total_users']) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card__label">Total Deposits</div>
        <div class="stat-card__value">$<?= number_format($stats['total_deposits'], 2) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card__label">Total Withdrawals</div>
        <div class="stat-card__value">$<?= number_format($stats['total_withdrawals'], 2) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card__label">Active Cards</div>
        <div class="stat-card__value"><?= number_format($stats['active_cards']) ?></div>
    </div>
    <div class="stat-card" style="border-left:3px solid var(--warning);">
        <div class="stat-card__label">Pending Deposits</div>
        <div class="stat-card__value" style="color:var(--warning);"><?= number_format($stats['pending_deposits']) ?></div>
    </div>
    <div class="stat-card" style="border-left:3px solid var(--warning);">
        <div class="stat-card__label">Pending Withdrawals</div>
        <div class="stat-card__value" style="color:var(--warning);"><?= number_format($stats['pending_withdrawals']) ?></div>
    </div>
</div>

<div class="card">
    <h3>Quick Actions</h3>
    <div class="d-flex gap-2 flex-wrap">
        <a href="<?= url('admin/deposits') ?>" class="btn btn--outline btn--sm">Review Deposits</a>
        <a href="<?= url('admin/withdrawals') ?>" class="btn btn--outline btn--sm">Review Withdrawals</a>
        <a href="<?= url('admin/cards') ?>" class="btn btn--accent btn--sm">Issue Debit Card</a>
        <a href="<?= url('admin/users') ?>" class="btn btn--outline btn--sm">Manage Users</a>
    </div>
</div>
