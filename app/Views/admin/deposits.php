<div class="dashboard__header">
    <h2>Deposits</h2>
</div>

<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr><th>ID</th><th>User</th><th>Amount</th><th>Method</th><th>Txn Hash</th><th>Status</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($deposits as $d): ?>
                <tr>
                    <td><?= $d['id'] ?></td>
                    <td><?= e($d['username']) ?><br><span style="font-size:11px;color:var(--text-muted);"><?= e($d['email']) ?></span></td>
                    <td>$<?= number_format($d['amount'], 2) ?></td>
                    <td><?= e($d['payment_method']) ?></td>
                    <td style="font-size:11px;max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($d['txn_hash'] ?? '-') ?></td>
                    <td><span class="badge badge--<?= $d['status'] === 'approved' ? 'success' : ($d['status'] === 'pending' ? 'warning' : 'danger') ?>"><?= ucfirst($d['status']) ?></span></td>
                    <td style="font-size:12px;"><?= date('M j, Y g:i A', strtotime($d['created_at'])) ?></td>
                    <td>
                        <?php if ($d['status'] === 'pending'): ?>
                        <div class="d-flex gap-1">
                            <form method="POST" action="<?= url('admin/deposits/' . $d['id'] . '/approve') ?>">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn--accent btn--sm">Approve</button>
                            </form>
                            <form method="POST" action="<?= url('admin/deposits/' . $d['id'] . '/reject') ?>">
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
