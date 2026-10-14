<?php if (has_flash('success')): ?>
<div class="alert alert--success"><?= e(flash('success')) ?></div>
<?php endif; ?>
<?php if (has_flash('error')): ?>
<div class="alert alert--error"><?= e(flash('error')) ?></div>
<?php endif; ?>
<?php if (has_flash('info')): ?>
<div class="alert alert--info"><?= e(flash('info')) ?></div>
<?php endif; ?>
