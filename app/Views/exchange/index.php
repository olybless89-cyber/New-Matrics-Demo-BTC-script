<div class="dashboard__header">
    <h2>Crypto Exchange</h2>
    <p class="text-muted" style="font-size:13px;">Your digital asset balances and live market rates</p>
</div>

<div class="card mb-3">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <div>
            <p class="text-muted" style="font-size:13px;margin:0;">Total Account Balance</p>
            <h3 style="margin:4px 0 0;color:var(--accent);">$<?= number_format($balance, 2) ?></h3>
        </div>
        <a href="<?= url('dashboard/deposits') ?>" class="btn btn--accent btn--sm">+ Deposit</a>
    </div>
</div>

<div class="card">
    <h3>Asset Balances</h3>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>Asset</th><th>Symbol</th><th>Balance</th><th>Price (USD)</th><th>24h Change</th><th>Value (USD)</th></tr></thead>
            <tbody>
                <?php foreach ($assets as $a): ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:32px;height:32px;border-radius:50%;background:var(--bg-alt);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:11px;color:var(--accent);"><?= substr($a['symbol'], 0, 3) ?></div>
                            <div>
                                <div style="font-weight:600;"><?= e($a['name']) ?></div>
                                <div style="font-size:11px;color:var(--text-muted);"><?= e($a['symbol']) ?></div>
                            </div>
                        </div>
                    </td>
                    <td><?= e($a['symbol']) ?></td>
                    <td><?= rtrim(rtrim(number_format($a['balance'], 8), '0'), '.') ?: '0.00' ?></td>
                    <td>$<?= number_format($a['price'], 2) ?></td>
                    <td style="color:<?= $a['change'] >= 0 ? '#41e088' : '#e74c3c' ?>;font-weight:600;"><?= $a['change'] >= 0 ? '+' : '' ?><?= number_format($a['change'], 2) ?>%</td>
                    <td>$<?= number_format($a['balance'] * $a['price'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card mt-3">
    <h3>Live Market Overview</h3>
    <div class="tradingview-widget-container" style="height:400px;">
        <iframe scrolling="no" allowtransparency="true" frameborder="0" src="https://www.tradingview-widget.com/embed-widget/market-overview/?locale=en#%7B%22colorTheme%22%3A%22dark%22%2C%22dateRange%22%3A%2212m%22%2C%22showChart%22%3Atrue%2C%22width%22%3A%22100%25%22%2C%22height%22%3A400%2C%22utm_source%22%3A%22trustcrypto%22%2C%22utm_medium%22%3A%22widget_new%22%7D" title="market overview" style="width:100%;height:400px;"></iframe>
    </div>
</div>
