<div class="dashboard__header">
    <h2>Investment Plans</h2>
</div>

<p class="text-muted" style="margin-bottom:20px;">Available Balance: <span style="color:var(--accent);font-weight:700;">$<?= number_format($balance, 2) ?></span></p>

<div class="deposits__slider" style="margin-bottom:30px;">
    <?php foreach ($investments as $inv): ?>
    <div class="deposits-item">
        <div class="deposits-item__content">
            <div class="deposits-item__count">
                <div class="deposits-item__count-number"><?= rtrim(rtrim(number_format($inv['rate'], 4), '0'), '.') ?></div>
                <div class="deposits-item__count-content">
                    <p class="deposits-item__count-sub">%</p>
                    <p class="deposits-item__count-desc"><?= e($inv['name']) ?></p>
                </div>
            </div>
            <div class="deposits-item__item deposits-item__item--earn">
                <p class="deposits-item__item-title"><?= e($inv['period']) ?></p>
            </div>
            <div class="deposits-item__item">
                <p class="deposits-item__item-title">Deposit:</p>
                <p class="deposits-item__item-value">$<?= number_format($inv['min_amount']) ?> - $<?= number_format($inv['max_amount']) ?></p>
            </div>
            <div class="deposits-item__item deposits-item__item--earn">
                <p class="deposits-item__item-title"><?= $inv['duration_hours'] ?> Hours</p>
            </div>
            <form method="POST" action="<?= url('invest') ?>" style="margin-top:15px;">
                <?= csrf_field() ?>
                <input type="hidden" name="investment_id" value="<?= $inv['id'] ?>">
                <input type="number" step="0.01" name="amount" class="form-control" placeholder="Amount" style="margin-bottom:10px;" min="<?= $inv['min_amount'] ?>" max="<?= $inv['max_amount'] ?>" required>
                <button type="submit" class="btn btn--accent btn--block btn--sm">INVEST</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="card">
    <h3>My Investments</h3>
    <?php if (empty($userInvestments)): ?>
    <p class="text-muted text-center" style="padding:30px 0;">No investments yet</p>
    <?php else: ?>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>Plan</th><th>Amount</th><th>Rate</th><th>Earned</th><th>Start</th><th>End</th><th>Status</th></tr></thead>
            <tbody>
                <?php foreach ($userInvestments as $ui): ?>
                <tr>
                    <td><?= e($ui['plan_name']) ?></td>
                    <td>$<?= number_format($ui['amount'], 2) ?></td>
                    <td><?= rtrim(rtrim(number_format($ui['rate'], 4), '0'), '.') ?>% <?= e($ui['period']) ?></td>
                    <td class="text-accent">$<?= number_format($ui['earned'], 2) ?></td>
                    <td style="font-size:12px;"><?= date('M j', strtotime($ui['start_date'])) ?></td>
                    <td style="font-size:12px;"><?= date('M j', strtotime($ui['end_date'])) ?></td>
                    <td><span class="badge badge--<?= $ui['status'] === 'active' ? 'success' : 'muted' ?>"><?= ucfirst($ui['status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
