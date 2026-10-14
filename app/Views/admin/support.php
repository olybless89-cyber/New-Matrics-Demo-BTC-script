<div class="dashboard__header">
    <h2>Support Tickets</h2>
</div>

<div class="card">
    <?php if (empty($tickets)): ?>
    <p class="text-muted text-center" style="padding:30px 0;">No support tickets.</p>
    <?php else: ?>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>Subject</th><th>User</th><th>Category</th><th>Priority</th><th>Status</th><th>Last Update</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($tickets as $t): ?>
                <tr>
                    <td><a href="<?= url('admin/support/' . $t['id']) ?>" style="color:var(--accent);"><?= e($t['subject']) ?></a></td>
                    <td>
                        <div style="font-weight:600;"><?= e($t['username']) ?></div>
                        <div style="font-size:11px;color:var(--text-muted);"><?= e($t['email']) ?></div>
                    </td>
                    <td><?= ucfirst($t['category']) ?></td>
                    <td><span class="badge badge--<?= $t['priority'] === 'high' ? 'danger' : ($t['priority'] === 'medium' ? 'warning' : 'info') ?>"><?= ucfirst($t['priority']) ?></span></td>
                    <td><span class="badge badge--<?= $t['status'] === 'closed' ? 'danger' : ($t['status'] === 'answered' ? 'success' : 'warning') ?>"><?= ucfirst($t['status']) ?></span></td>
                    <td style="font-size:12px;"><?= $t['last_message_at'] ? date('M j, g:i A', strtotime($t['last_message_at'])) : date('M j, g:i A', strtotime($t['created_at'])) ?></td>
                    <td><a href="<?= url('admin/support/' . $t['id']) ?>" class="btn btn--outline btn--sm">View</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
