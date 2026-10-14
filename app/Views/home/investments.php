<?php View::partial('_nav'); ?>

<section class="page-header">
    <div class="container">
        <h1>INVESTMENT <span style="color:var(--accent)">PLANS</span></h1>
        <p class="text-muted">Choose the plan that suits your investment goals</p>
    </div>
</section>

<section class="deposits">
    <div class="container container-large">
        <div class="deposits__block">
            <div class="deposits__slider">
                <?php foreach ($investments as $inv): ?>
                <div class="deposits-item <?= ($inv['id'] === 1) ? 'is-active' : '' ?>">
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
                        <?php if ($inv['description']): ?>
                        <p class="text-muted" style="font-size:12px;margin-top:15px;"><?= e($inv['description']) ?></p>
                        <?php endif; ?>
                        <div class="deposits-item__action" style="margin-top:20px;">
                            <a href="<?= url('login') ?>" class="btn btn--primary btn--block">INVEST NOW</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<section class="container" style="padding:40px 20px;">
    <div class="card text-center">
        <h3>How It Works</h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:30px;margin-top:30px;">
            <div>
                <div style="width:60px;height:60px;margin:0 auto 15px;background:var(--accent-glow);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:800;color:var(--accent);">1</div>
                <h4>Register</h4>
                <p class="text-muted" style="font-size:13px;">Create your free account in minutes</p>
            </div>
            <div>
                <div style="width:60px;height:60px;margin:0 auto 15px;background:var(--accent-glow);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:800;color:var(--accent);">2</div>
                <h4>Deposit</h4>
                <p class="text-muted" style="font-size:13px;">Fund your account with crypto or card</p>
            </div>
            <div>
                <div style="width:60px;height:60px;margin:0 auto 15px;background:var(--accent-glow);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:800;color:var(--accent);">3</div>
                <h4>Invest</h4>
                <p class="text-muted" style="font-size:13px;">Choose a plan and start earning</p>
            </div>
            <div>
                <div style="width:60px;height:60px;margin:0 auto 15px;background:var(--accent-glow);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:800;color:var(--accent);">4</div>
                <h4>Withdraw</h4>
                <p class="text-muted" style="font-size:13px;">Withdraw your earnings anytime</p>
            </div>
        </div>
    </div>
</section>

<?php View::partial('_footer'); ?>
