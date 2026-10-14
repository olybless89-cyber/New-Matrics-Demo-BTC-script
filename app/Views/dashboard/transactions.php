<div class="dashboard__header">
    <h2>Transaction History</h2>
</div>

<div class="card">
    <?php if (empty($transactions)): ?>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr><th>Type</th><th>Amount</th><th>Balance After</th><th>Description</th><th>Reference</th><th>Date</th></tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $t): ?>
                <tr>
                    <td><span class="badge badge--<?= in_array($t['type'], ['deposit','earning','referral_bonus']) ? 'success' : 'warning' ?>"><?= ucfirst(str_replace('_', ' ', $t['type'])) ?></span></td>
                    <td class="<?= $t['amount'] >= 0 ? 'text-accent' : '' ?>"><?= $t['amount'] >= 0 ? '+' : '' ?>$<?= number_format($t['amount'], 2) ?></td>
                    <td>$<?= number_format($t['balance_after'], 2) ?></td>
                    <td class="text-muted"><?= e($t['description'] ?? '-') ?></td>
                    <td class="text-muted"><?= e($t['reference'] ?? '-') ?></td>
                    <td style="white-space:nowrap;"><?= date('M j, Y g:i A', strtotime($t['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <p class="text-muted text-center" style="padding:40px 0;">No transactions found.</p>
    <?php endif; ?>
</div>
