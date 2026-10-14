<div class="dashboard__header">
    <h2>Profit Record</h2>
    <p class="text-muted" style="font-size:13px;">Your investment earnings history</p>
</div>

<div class="card">
    <?php if (empty($earnings)): ?>
    <p class="text-muted text-center" style="padding:30px 0;">No earnings yet. <a href="<?= url('dashboard/mpack') ?>">Start investing</a> to earn profits.</p>
    <?php else: ?>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>Date</th><th>Description</th><th>Amount</th><th>Balance After</th></tr></thead>
            <tbody>
                <?php foreach ($earnings as $e): ?>
                <tr>
                    <td style="white-space:nowrap;font-size:13px;"><?= date('M j, Y g:i A', strtotime($e['created_at'])) ?></td>
                    <td><?= e($e['description'] ?? 'Investment earning') ?></td>
                    <td class="text-accent" style="font-weight:600;">+$<?= number_format($e['amount'], 2) ?></td>
                    <td>$<?= number_format($e['balance_after'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
