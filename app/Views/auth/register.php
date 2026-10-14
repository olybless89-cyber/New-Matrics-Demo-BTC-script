<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trustcrypto | Create Account</title>
    <link rel="icon" type="image/svg+xml" href="<?= asset('img/logo.svg') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <style>
        body { background: linear-gradient(135deg, #0e1113 0%, #1a2a3a 100%); min-height: 100vh; }
    </style>
</head>
<body>
<div style="display:flex;justify-content:space-between;align-items:center;padding:20px;max-width:500px;margin:0 auto;width:100%;">
    <a href="<?= url('/') ?>" style="font-size:22px;font-weight:800;color:var(--accent)">TRUST<span style="color:#fff">CRYPTO</span></a>
    <span style="color:var(--text-muted);font-size:13px;">EN</span>
</div>
<div class="auth-card" style="max-width:520px;">
    <div class="auth-logo">
        <span style="font-size:32px;font-weight:800;color:var(--accent)">TRUST<span style="color:#fff">CRYPTO</span></span>
    </div>
    <h4>Create an Account</h4>
    <p class="auth-date"><?= date('l jS \of F Y h:i:s A') ?></p>
    <?php View::partial('_flash'); ?>
    <form method="POST" action="<?= url('register') ?>">
        <?= csrf_field() ?>
        <div class="form-group">
            <label>UserName *</label>
            <input type="text" name="username" class="form-control" placeholder="Enter Unique Username" required value="<?= e(old('username')) ?>">
        </div>
        <div class="form-group">
            <label>FullName *</label>
            <input type="text" name="fullname" class="form-control" placeholder="Enter FullName" required value="<?= e(old('fullname')) ?>">
        </div>
        <div class="form-group">
            <label>Your Email *</label>
            <input type="email" name="email" class="form-control" placeholder="name@example.com" required value="<?= e(old('email')) ?>">
        </div>
        <div class="form-group">
            <label>Phone Number *</label>
            <input type="tel" name="phone" class="form-control" placeholder="Enter Phone number" required value="<?= e(old('phone')) ?>">
        </div>
        <div class="form-group">
            <label>Password *</label>
            <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
        </div>
        <div class="form-group">
            <label>Confirm Password *</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
        </div>
        <div class="form-group">
            <label>Country *</label>
            <select name="country" class="form-control" required>
                <option value="">Choose Country</option>
                <?php
                $countries = ['Afghanistan','Albania','Algeria','Argentina','Australia','Austria','Azerbaijan','Bahamas','Bahrain','Bangladesh','Belgium','Brazil','Canada','China','Colombia','Denmark','Egypt','Finland','France','Germany','Ghana','Greece','India','Indonesia','Ireland','Israel','Italy','Jamaica','Japan','Kenya','Malaysia','Mexico','Netherlands','New Zealand','Nigeria','Norway','Pakistan','Philippines','Poland','Portugal','Russia','Saudi Arabia','Singapore','South Africa','South Korea','Spain','Sri Lanka','Sweden','Switzerland','Thailand','Turkey','Ukraine','United Arab Emirates','United Kingdom','United States of America','Vietnam'];
                foreach ($countries as $c):
                ?>
                <option value="<?= e($c) ?>" <?= old('country') === $c ? 'selected' : '' ?>><?= e($c) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Referral ID *</label>
            <input type="text" name="referral_id" class="form-control" placeholder="optional referral id" value="<?= e(old('referral_id', $_GET['ref'] ?? '')) ?>">
        </div>
        <p class="text-muted" style="font-size:12px;margin-bottom:20px;">By registering, you agree to our <a href="<?= url('terms') ?>">Terms And Privacy Policy</a></p>
        <button type="submit" class="btn btn--primary btn--block">Register</button>
    </form>
    <p class="text-center mt-2" style="font-size:14px;color:var(--text-muted)">Already have an account <a href="<?= url('login') ?>">Login</a></p>
</div>
<p style="text-align:center;color:var(--text-muted);font-size:12px;padding:20px;">© Copyright <?= date('Y') ?> Trustcrypto All Rights Reserved.</p>
</body>
</html>
