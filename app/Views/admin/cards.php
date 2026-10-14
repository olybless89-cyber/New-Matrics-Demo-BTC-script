<div class="dashboard__header">
    <div>
        <h2>Debit Cards</h2>
        <p class="text-muted" style="font-size:13px;">Issue and manage user debit cards</p>
    </div>
</div>

<div class="card" style="margin-bottom:30px;border-color:var(--accent);">
    <h3 style="color:var(--accent);"><i class="fa fa-plus-circle"></i> Issue New Debit Card</h3>
    <form method="POST" action="<?= url('admin/cards/issue') ?>" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:15px;align-items:end;">
        <?= csrf_field() ?>
        <div class="form-group" style="margin:0;">
            <label>Select User</label>
            <select name="user_id" class="form-control" required>
                <option value="">Choose user</option>
                <?php foreach ($users as $u): ?>
                <option value="<?= $u['id'] ?>"><?= e($u['username']) ?> (<?= e($u['email']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" style="margin:0;">
            <label>Card Type</label>
            <select name="card_type" class="form-control">
                <option value="visa">Visa</option>
                <option value="mastercard">Mastercard</option>
            </select>
        </div>
        <div class="form-group" style="margin:0;">
            <label>Card Tier</label>
            <select name="card_tier" class="form-control">
                <option value="virtual">Virtual Card (Free)</option>
                <option value="metal">Titanium Metal ($25)</option>
                <option value="vip">VIP Black Diamond ($100)</option>
            </select>
        </div>
        <div class="form-group" style="margin:0;">
            <label>Daily Limit ($)</label>
            <input type="number" step="0.01" name="daily_limit" class="form-control" value="5000">
        </div>
        <div class="form-group" style="margin:0;">
            <label>Initial Balance ($)</label>
            <input type="number" step="0.01" name="initial_balance" class="form-control" value="0" min="0">
        </div>
        <button type="submit" class="btn btn--accent" style="height:44px;"><i class="fa fa-credit-card"></i> Issue Card</button>
    </form>
</div>

<div class="card">
    <h3>All Issued Cards</h3>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr><th>ID</th><th>User</th><th>Card Number</th><th>Tier</th><th>Type</th><th>Expiry</th><th>Balance</th><th>Status</th><th>Issued</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($cards as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= e($c['username']) ?><br><span style="font-size:11px;color:var(--text-muted);"><?= e($c['email']) ?></span></td>
                    <td style="font-family:monospace;"><?= chunk_split($c['card_number'], 4, ' ') ?></td>
                    <td><span class="badge badge--<?= ($c['card_tier'] ?? 'virtual') === 'vip' ? 'success' : 'primary' ?>"><?= strtoupper($c['card_tier'] ?? 'virtual') ?></span></td>
                    <td><?= strtoupper($c['card_type']) ?></td>
                    <td><?= str_pad($c['expiry_month'], 2, '0', STR_PAD_LEFT) ?>/<?= $c['expiry_year'] ?></td>
                    <td>$<?= number_format($c['balance'], 2) ?></td>
                    <td><span class="badge badge--<?= $c['status'] === 'active' ? 'success' : ($c['status'] === 'blocked' ? 'danger' : 'warning') ?>"><?= ucfirst($c['status']) ?></span></td>
                    <td style="font-size:12px;"><?= date('M j, Y', strtotime($c['issued_at'])) ?></td>
                    <td>
                        <?php if ($c['status'] === 'active'): ?>
                        <form method="POST" action="<?= url('admin/cards/' . $c['id'] . '/block') ?>">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn--danger btn--sm">Block</button>
                        </form>
                        <?php else: ?>
                        <form method="POST" action="<?= url('admin/cards/' . $c['id'] . '/activate') ?>">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn--accent btn--sm">Activate</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
