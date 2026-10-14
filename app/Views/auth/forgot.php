<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trustcrypto | Forgot Password</title>
    <link rel="icon" type="image/svg+xml" href="<?= asset('img/logo.svg') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <style>
        body { background: linear-gradient(135deg, #0e1113 0%, #1a2a3a 100%); min-height: 100vh; display:flex;flex-direction:column; }
    </style>
</head>
<body>
<div style="display:flex;justify-content:space-between;align-items:center;padding:20px;max-width:500px;margin:0 auto;width:100%;">
    <a href="<?= url('/') ?>" style="font-size:22px;font-weight:800;color:var(--accent)">TRUST<span style="color:#fff">CRYPTO</span></a>
</div>
<div class="auth-card">
    <h4>Forgot Password</h4>
    <p class="text-muted" style="text-align:center;font-size:13px;margin-bottom:20px;">Enter your email and we'll send you a reset link</p>
    <?php View::partial('_flash'); ?>
    <form method="POST" action="<?= url('forgot-password') ?>">
        <?= csrf_field() ?>
        <div class="form-group">
            <label>Your Email *</label>
            <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
        </div>
        <button type="submit" class="btn btn--primary btn--block">Send Reset Link</button>
    </form>
    <p class="text-center mt-2" style="font-size:14px;"><a href="<?= url('login') ?>">Back to Login</a></p>
</div>
<p style="text-align:center;color:var(--text-muted);font-size:12px;padding:20px;">© Copyright <?= date('Y') ?> Trustcrypto All Rights Reserved.</p>
</body>
</html>
