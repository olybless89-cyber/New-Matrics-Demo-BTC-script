<div class="dashboard__header">
    <h2>Investment Plans</h2>
</div>

<div class="card" style="margin-bottom:30px;">
    <h3>Add New Plan</h3>
    <form method="POST" action="<?= url('admin/investments') ?>" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:15px;align-items:end;">
        <?= csrf_field() ?>
        <div class="form-group" style="margin:0;">
            <label>Name</label>
            <input type="text" name="name" class="form-control" placeholder="e.g. Starter" required>
        </div>
        <div class="form-group" style="margin:0;">
            <label>Rate (%)</label>
            <input type="number" step="0.0001" name="rate" class="form-control" placeholder="5.00" required>
        </div>
        <div class="form-group" style="margin:0;">
            <label>Period</label>
            <select name="period" class="form-control">
                <option value="Hourly">Hourly</option>
                <option value="Daily">Daily</option>
            </select>
        </div>
        <div class="form-group" style="margin:0;">
            <label>Min Amount</label>
            <input type="number" step="0.01" name="min_amount" class="form-control" placeholder="200" required>
        </div>
        <div class="form-group" style="margin:0;">
            <label>Max Amount</label>
            <input type="number" step="0.01" name="max_amount" class="form-control" placeholder="200000" required>
        </div>
        <div class="form-group" style="margin:0;">
            <label>Duration (hours)</label>
            <input type="number" name="duration_hours" class="form-control" placeholder="12" required>
        </div>
        <button type="submit" class="btn btn--accent" style="height:44px;"><i class="fa fa-plus"></i> Add Plan</button>
    </form>
</div>

<div class="card">
    <h3>Existing Plans</h3>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr><th>ID</th><th>Name</th><th>Rate</th><th>Period</th><th>Min</th><th>Max</th><th>Duration</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php foreach ($investments as $inv): ?>
                <tr>
                    <td><?= $inv['id'] ?></td>
                    <td><?= e($inv['name']) ?></td>
                    <td class="text-accent"><?= rtrim(rtrim(number_format($inv['rate'], 4), '0'), '.') ?>%</td>
                    <td><?= e($inv['period']) ?></td>
                    <td>$<?= number_format($inv['min_amount']) ?></td>
                    <td>$<?= number_format($inv['max_amount']) ?></td>
                    <td><?= $inv['duration_hours'] ?>h</td>
                    <td><span class="badge badge--<?= $inv['is_active'] ? 'success' : 'muted' ?>"><?= $inv['is_active'] ? 'Active' : 'Inactive' ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
