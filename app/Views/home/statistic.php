<?php View::partial('_nav'); ?>

<section class="page-header">
    <div class="container">
        <h1>PLATFORM <span style="color:var(--accent)">STATISTICS</span></h1>
        <p class="text-muted">Real-time Trustcrypto platform statistics</p>
    </div>
</section>

<section class="container" style="padding:40px 20px;">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card__label">Total Investors</div>
            <div class="stat-card__value"><?= number_format($totalUsers) ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-card__label">Total Deposits</div>
            <div class="stat-card__value">$<?= number_format($totalDeposits, 2) ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-card__label">Total Withdrawals</div>
            <div class="stat-card__value">$<?= number_format($totalWithdrawals, 2) ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-card__label">Active Plans</div>
            <div class="stat-card__value"><?= number_format(\DB::fetch("SELECT COUNT(*) as c FROM investments WHERE is_active=1")['c']) ?></div>
        </div>
    </div>

    <div class="tradingview-widget-container">
        <iframe scrolling="no" allowtransparency="true" frameborder="0" src="https://www.tradingview-widget.com/embed-widget/market-overview/?locale=en#%7B%22colorTheme%22%3A%22dark%22%2C%22width%22%3A%22100%25%22%2C%22height%22%3A500%7D" title="market overview" style="width:100%;height:500px;"></iframe>
    </div>
</section>

<?php View::partial('_footer'); ?>
