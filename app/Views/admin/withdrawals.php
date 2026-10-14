<div class="dashboard__header">
    <h2>Withdrawals</h2>
</div>

<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr><th>ID</th><th>User</th><th>Amount</th><th>Method</th><th>Wallet/Details</th><th>Status</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($withdrawals as $w): ?>
                <tr>
                    <td><?= $w['id'] ?></td>
                    <td><?= e($w['username']) ?><br><span style="font-size:11px;color:var(--text-muted);"><?= e($w['email']) ?></span></td>
                    <td>$<?= number_format($w['amount'], 2) ?></td>
                    <td><?= e($w['payment_method']) ?></td>
                    <td style="font-size:11px;max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($w['wallet_address'] ?? '-') ?></td>
                    <td><span class="badge badge--<?= $w['status'] === 'approved' ? 'success' : ($w['status'] === 'rejected' ? 'danger' : 'warning') ?>"><?= ucfirst($w['status']) ?></span></td>
                    <td style="font-size:12px;"><?= date('M j, Y g:i A', strtotime($w['created_at'])) ?></td>
                    <td>
                        <?php if ($w['status'] === 'pending'): ?>
                        <div class="d-flex gap-1">
                            <form method="POST" action="<?= url('admin/withdrawals/' . $w['id'] . '/approve') ?>">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn--accent btn--sm">Approve</button>
                            </form>
                            <form method="POST" action="<?= url('admin/withdrawals/' . $w['id'] . '/reject') ?>">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn--danger btn--sm">Reject</button>
                            </form>
                        </div>
                        <?php else: ?>
                        <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
