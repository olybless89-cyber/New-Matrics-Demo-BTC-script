<div class="dashboard__header">
    <h2>Refer Users</h2>
    <p class="text-muted" style="font-size:13px;">Earn referral bonuses when your friends invest</p>
</div>

<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px;">
    <div class="stat-card">
        <div class="stat-card__label">Total Referrals</div>
        <div class="stat-card__value"><?= $totalReferrals ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card__label">Active Referrals</div>
        <div class="stat-card__value"><?= $activeReferrals ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card__label">Referral Bonus Earned</div>
        <div class="stat-card__value">$<?= number_format($referralBonus, 2) ?></div>
    </div>
</div>

<div class="card mb-3">
    <h3>Your Referral Link</h3>
    <p class="text-muted" style="font-size:13px;margin-bottom:12px;">Share this link with friends. You earn a bonus when they make their first deposit.</p>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <input type="text" class="form-control" id="referralLink" value="<?= e($referralLink) ?>" readonly style="flex:1;min-width:250px;">
        <button class="btn btn--accent" onclick="navigator.clipboard.writeText(document.getElementById('referralLink').value);this.textContent='Copied!'">Copy Link</button>
    </div>
</div>

<div class="card">
    <h3>Your Referrals</h3>
    <?php if (empty($referrals)): ?>
    <p class="text-muted text-center" style="padding:30px 0;">No referrals yet. Share your link to start earning!</p>
    <?php else: ?>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>Name</th><th>Email</th><th>Balance</th><th>Joined</th></tr></thead>
            <tbody>
                <?php foreach ($referrals as $r): ?>
                <tr>
                    <td><?= e($r['fullname']) ?></td>
                    <td><?= e($r['email']) ?></td>
                    <td>$<?= number_format($r['balance'], 2) ?></td>
                    <td style="font-size:12px;"><?= date('M j, Y', strtotime($r['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
