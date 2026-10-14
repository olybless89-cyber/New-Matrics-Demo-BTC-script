<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trustcrypto | User Login</title>
    <link rel="icon" type="image/svg+xml" href="<?= asset('img/logo.svg') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <style>
        body { background: linear-gradient(135deg, #0e1113 0%, #1a2a3a 100%); min-height: 100vh; display: flex; flex-direction: column; }
    </style>
</head>
<body>
<div style="display:flex;justify-content:space-between;align-items:center;padding:20px;max-width:500px;margin:0 auto;width:100%;">
    <a href="<?= url('/') ?>" style="font-size:22px;font-weight:800;color:var(--accent)">TRUST<span style="color:#fff">CRYPTO</span></a>
    <span style="color:var(--text-muted);font-size:13px;">EN</span>
</div>
<div class="auth-card">
    <div class="auth-logo">
        <span style="font-size:32px;font-weight:800;color:var(--accent)">TRUST<span style="color:#fff">CRYPTO</span></span>
    </div>
    <h4>User Login</h4>
    <p class="auth-date"><?= date('l jS \of F Y h:i:s A') ?></p>
    <?php View::partial('_flash'); ?>
    <form method="POST" action="<?= url('login') ?>">
        <?= csrf_field() ?>
        <div class="form-group">
            <label>Your Email *</label>
            <input type="email" name="email" class="form-control" placeholder="name@example.com" required value="<?= e(old('email')) ?>">
        </div>
        <div class="form-group">
            <label>Password *</label>
            <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
        </div>
        <div class="d-flex align-center justify-between mb-2">
            <div class="form-check">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Remember me</label>
            </div>
            <a href="<?= url('forgot-password') ?>" style="font-size:13px;">Forgot password ?</a>
        </div>
        <button type="submit" class="btn btn--primary btn--block">Sign in</button>
    </form>
    <p class="text-center mt-2" style="font-size:14px;color:var(--text-muted)">Don't have an account ? <a href="<?= url('register') ?>">Sign Up</a></p>
</div>
<p style="text-align:center;color:var(--text-muted);font-size:12px;padding:20px;">© Copyright <?= date('Y') ?> Trustcrypto All Rights Reserved.</p>
</body>
</html>
