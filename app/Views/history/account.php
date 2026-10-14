<div class="dashboard__header">
    <h2>Account History</h2>
    <p class="text-muted" style="font-size:13px;">All your account transactions</p>
</div>

<div class="card mb-3">
    <h3>Transactions</h3>
    <?php if (empty($transactions)): ?>
    <p class="text-muted text-center" style="padding:30px 0;">No transactions yet.</p>
    <?php else: ?>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>Date</th><th>Type</th><th>Description</th><th>Reference</th><th>Amount</th><th>Balance</th></tr></thead>
            <tbody>
                <?php foreach ($transactions as $t): ?>
                <tr>
                    <td style="white-space:nowrap;font-size:13px;"><?= date('M j, Y g:i A', strtotime($t['created_at'])) ?></td>
                    <td><span class="badge badge--<?= in_array($t['type'], ['deposit','earning','referral_bonus']) ? 'success' : ($t['type'] === 'withdrawal' ? 'danger' : 'info') ?>"><?= ucfirst(str_replace('_', ' ', $t['type'])) ?></span></td>
                    <td class="text-muted"><?= e($t['description'] ?? '-') ?></td>
                    <td style="font-size:12px;"><?= e($t['reference'] ?? '-') ?></td>
                    <td class="<?= $t['amount'] >= 0 ? 'text-accent' : '' ?>" style="font-weight:600;"><?= $t['amount'] >= 0 ? '+' : '' ?>$<?= number_format($t['amount'], 2) ?></td>
                    <td>$<?= number_format($t['balance_after'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;" class="dashboard-grid">
    <div class="card">
        <h3>Deposit History</h3>
        <?php if (empty($deposits)): ?>
        <p class="text-muted text-center" style="padding:20px 0;">No deposits</p>
        <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead><tr><th>Amount</th><th>Method</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                    <?php foreach ($deposits as $d): ?>
                    <tr>
                        <td>$<?= number_format($d['amount'], 2) ?></td>
                        <td><?= e($d['payment_method']) ?></td>
                        <td><span class="badge badge--<?= $d['status'] === 'approved' ? 'success' : ($d['status'] === 'pending' ? 'warning' : 'danger') ?>"><?= ucfirst($d['status']) ?></span></td>
                        <td style="font-size:12px;"><?= date('M j, Y', strtotime($d['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <h3>Withdrawal History</h3>
        <?php if (empty($withdrawals)): ?>
        <p class="text-muted text-center" style="padding:20px 0;">No withdrawals</p>
        <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead><tr><th>Amount</th><th>Method</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                    <?php foreach ($withdrawals as $w): ?>
                    <tr>
                        <td>$<?= number_format($w['amount'], 2) ?></td>
                        <td><?= e($w['payment_method']) ?></td>
                        <td><span class="badge badge--<?= $w['status'] === 'approved' ? 'success' : ($w['status'] === 'pending' || $w['status'] === 'processing' ? 'warning' : 'danger') ?>"><?= ucfirst($w['status']) ?></span></td>
                        <td style="font-size:12px;"><?= date('M j, Y', strtotime($w['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
