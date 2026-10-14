<div class="dashboard__header">
    <h2>Users</h2>
</div>

<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr><th>ID</th><th>Username</th><th>Full Name</th><th>Email</th><th>Country</th><th>Balance</th><th>Status</th><th>Admin</th><th>Joined</th></tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= e($u['username']) ?></td>
                    <td><?= e($u['fullname']) ?></td>
                    <td><?= e($u['email']) ?></td>
                    <td><?= e($u['country'] ?? '-') ?></td>
                    <td>$<?= number_format($u['balance'], 2) ?></td>
                    <td><span class="badge badge--<?= $u['status'] === 'active' ? 'success' : 'danger' ?>"><?= ucfirst($u['status']) ?></span></td>
                    <td><?= $u['is_admin'] ? '<span class="badge badge--info">Admin</span>' : '-' ?></td>
                    <td style="font-size:12px;"><?= date('M j, Y', strtotime($u['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
