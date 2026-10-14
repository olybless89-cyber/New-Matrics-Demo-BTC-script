<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Admin') ?> | Trustcrypto Admin</title>
    <link rel="icon" type="image/svg+xml" href="<?= asset('img/logo.svg') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
</head>
<body>
<div class="dashboard">
    <?php View::partial('_admin_sidebar'); ?>
    <main class="dashboard__content">
        <?php View::partial('_flash'); ?>
        <?= $content ?? '' ?>
    </main>
</div>
</body>
</html>
