<div class="dashboard__header">
    <h2>Contact Messages</h2>
</div>

<div class="card">
    <?php if (empty($messages)): ?>
    <p class="text-muted text-center" style="padding:30px 0;">No messages yet</p>
    <?php else: ?>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr><th>ID</th><th>Name</th><th>Login</th><th>Email</th><th>Message</th><th>Date</th></tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $m): ?>
                <tr>
                    <td><?= $m['id'] ?></td>
                    <td><?= e($m['name'] ?? '-') ?></td>
                    <td><?= e($m['login'] ?? '-') ?></td>
                    <td><?= e($m['email']) ?></td>
                    <td style="max-width:400px;"><?= e($m['message']) ?></td>
                    <td style="font-size:12px;white-space:nowrap;"><?= date('M j, Y g:i A', strtotime($m['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
