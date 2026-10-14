<div class="dashboard__header">
    <h2>Profile Settings</h2>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;" class="dashboard-grid">
    <div class="card">
        <h3>Personal Information</h3>
        <form method="POST" action="<?= url('dashboard/profile') ?>">
            <?= csrf_field() ?>
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullname" class="form-control" value="<?= e($user['fullname']) ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" value="<?= e($user['email']) ?>" disabled>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="tel" name="phone" class="form-control" value="<?= e($user['phone']) ?>">
            </div>
            <div class="form-group">
                <label>Country</label>
                <input type="text" name="country" class="form-control" value="<?= e($user['country']) ?>">
            </div>
            <button type="submit" class="btn btn--primary">Update Profile</button>
        </form>
    </div>

    <div class="card">
        <h3>Account Details</h3>
        <div style="margin-bottom:20px;">
            <div class="text-muted" style="font-size:13px;">Username</div>
            <div style="font-weight:600;"><?= e($user['username']) ?></div>
        </div>
        <div style="margin-bottom:20px;">
            <div class="text-muted" style="font-size:13px;">Referral ID</div>
            <div style="font-weight:600;color:var(--accent);"><?= e($user['referral_id']) ?></div>
        </div>
        <div style="margin-bottom:20px;">
            <div class="text-muted" style="font-size:13px;">Referral Link</div>
            <div style="display:flex;gap:8px;align-items:center;">
                <input type="text" class="form-control" value="<?= url('register?ref=' . $user['referral_id']) ?>" readonly id="refLink">
                <button class="btn btn--outline btn--sm" onclick="copyLink()">Copy</button>
            </div>
        </div>
        <div>
            <div class="text-muted" style="font-size:13px;">Member Since</div>
            <div style="font-weight:600;"><?= date('F j, Y', strtotime($user['created_at'])) ?></div>
        </div>
    </div>
</div>

<script>
function copyLink() {
    const link = document.getElementById('refLink');
    link.select();
    document.execCommand('copy');
    alert('Referral link copied!');
}
</script>
