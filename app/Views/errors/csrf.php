<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSRF Token Mismatch</title>
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
</head>
<body>
<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh;text-align:center;padding:20px;">
    <h1 style="font-size:60px;color:var(--warning);">419</h1>
    <p class="text-muted" style="font-size:18px;">Session expired. Please try again.</p>
    <a href="<?= url('/') ?>" class="btn btn--primary mt-3">Go Home</a>
</div>
</body>
</html>
