<div class="dashboard__header">
    <div>
        <h2><?= e($ticket['subject']) ?></h2>
        <p class="text-muted" style="font-size:13px;">
            <span class="badge badge--<?= $ticket['status'] === 'closed' ? 'danger' : ($ticket['status'] === 'answered' ? 'success' : 'warning') ?>"><?= ucfirst($ticket['status']) ?></span>
            · <?= ucfirst($ticket['priority']) ?> priority · <?= ucfirst($ticket['category']) ?>
        </p>
    </div>
    <a href="<?= url('dashboard/support') ?>" class="btn btn--outline btn--sm">← All Tickets</a>
</div>

<div class="card mb-3">
    <div style="display:flex;flex-direction:column;gap:16px;">
        <?php foreach ($messages as $m): ?>
        <div style="display:flex;gap:12px;<?= $m['is_admin'] ? 'justify-content:flex-end;' : '' ?>">
            <div style="max-width:75%;padding:12px 16px;border-radius:12px;<?= $m['is_admin'] ? 'background:rgba(65,224,136,0.1);border:1px solid rgba(65,224,136,0.3);' : 'background:var(--bg-alt);' ?>">
                <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">
                    <strong style="color:<?= $m['is_admin'] ? 'var(--accent)' : 'inherit' ?>;"><?= $m['is_admin'] ? 'Support Team' : e($m['fullname'] ?? 'You') ?></strong>
                    · <?= date('M j, g:i A', strtotime($m['created_at'])) ?>
                </div>
                <div style="white-space:pre-wrap;"><?= e($m['message']) ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php if ($ticket['status'] !== 'closed'): ?>
<div class="card">
    <h3>Reply</h3>
    <form method="POST" action="<?= url('dashboard/support/' . $ticket['id'] . '/reply') ?>">
        <?= csrf_field() ?>
        <div class="form-group">
            <textarea name="message" class="form-control" rows="4" placeholder="Type your reply..." required></textarea>
        </div>
        <button type="submit" class="btn btn--accent">Send Reply</button>
    </form>
    <form method="POST" action="<?= url('dashboard/support/' . $ticket['id'] . '/close') ?>" style="margin-top:12px;" onsubmit="return confirm('Close this ticket?')">
        <?= csrf_field() ?>
        <button type="submit" class="btn btn--outline" style="color:#e74c3c;border-color:#e74c3c;">Close Ticket</button>
    </form>
</div>
<?php endif; ?>
