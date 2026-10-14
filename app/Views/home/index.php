<?php View::partial('_nav'); ?>

<section class="intro">
    <div class="intro__bg">
        <div style="position:absolute;top:20%;left:10%;width:300px;height:300px;background:radial-gradient(circle,rgba(65,224,136,0.1),transparent 70%);border-radius:50%;"></div>
        <div style="position:absolute;bottom:10%;right:15%;width:400px;height:400px;background:radial-gradient(circle,rgba(52,152,219,0.08),transparent 70%);border-radius:50%;"></div>
    </div>
    <div class="container">
        <div class="intro__row">
            <div class="intro__col">
                <div class="intro__content">
                    <h1 class="intro__title">Safe investment with <span class="intro-bremby">Trustcrypto</span></h1>
                    <div class="intro__description">
                        <p><span class="color-primary">GET</span> LIFETIME INCOME ON INVESTMENT</p>
                    </div>
                    <a class="btn btn--primary" href="<?= url('login') ?>">OPEN DEPOSIT</a>
                    <ul class="intro-icons">
                        <li>
                            <div class="intro-icons__icon">
                                <svg viewBox="0 0 24 24"><path d="M12 2L2 7v10l10 5 10-5V7L12 2zm0 2.18L19.82 8 12 11.82 4.18 8 12 4.18zM4 9.82l7 3.5v7.36l-7-3.5V9.82zm9 10.86v-7.36l7-3.5v7.36l-7 3.5z"/></svg>
                            </div>
                            <div class="intro-icons__description">
                                <p>Professional Crypto Industry Development Team</p>
                            </div>
                        </li>
                        <li>
                            <div class="intro-icons__icon">
                                <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.11 0-2 .89-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.11-.9-2-2-2zm0 16H5V5h14v14zm-5-9l-4 4-3-3-1 1 4 4 5-5z"/></svg>
                            </div>
                            <div class="intro-icons__description">
                                <p>Unique robot for trading</p>
                            </div>
                        </li>
                        <li>
                            <div class="intro-icons__icon">
                                <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            </div>
                            <div class="intro-icons__description">
                                <p>Manage operations without user intervention</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="intro__col" style="display:flex;justify-content:center;">
                <div style="width:100%;max-width:450px;padding:40px;background:var(--bg-card);border:1px solid var(--border);border-radius:16px;text-align:center;">
                    <div style="font-size:80px;margin-bottom:20px;">🤖</div>
                    <h3 style="color:var(--accent);margin-bottom:10px;">Trustcrypto Trading Robot</h3>
                    <p class="text-muted" style="font-size:14px;">Our advanced AI-powered trading robot analyzes markets 24/7 and executes profitable trades automatically.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="tradingview-widget-container" style="padding:0 20px;">
    <iframe scrolling="no" allowtransparency="true" frameborder="0" src="https://www.tradingview-widget.com/embed-widget/market-overview/?locale=en#%7B%22colorTheme%22%3A%22dark%22%2C%22dateRange%22%3A%2212m%22%2C%22showChart%22%3Atrue%2C%22width%22%3A%22100%25%22%2C%22height%22%3A500%2C%22utm_source%22%3A%22trustcrypto%22%2C%22utm_medium%22%3A%22widget_new%22%7D" title="market overview" style="width:100%;height:500px;"></iframe>
</div>

<section class="deposits">
    <div class="container container-large">
        <div class="section-intro">
            <h3 class="title">Investment Proposals</h3>
            <div class="section-intro__description">
                <p>Trustcrypto employees ensure that every investor in our company can earn money</p>
            </div>
        </div>
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
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<div class="tradingview-widget-container" style="padding:0 20px;">
    <iframe scrolling="no" allowtransparency="true" frameborder="0" src="https://www.tradingview-widget.com/embed-widget/forex-cross-rates/?locale=en#%7B%22width%22%3A%22100%25%22%2C%22height%22%3A400%2C%22colorTheme%22%3A%22dark%22%2C%22currencies%22%3A%5B%22EUR%22%2C%22USD%22%2C%22JPY%22%2C%22GBP%22%2C%22CHF%22%2C%22AUD%22%2C%22CAD%22%2C%22NZD%22%2C%22CNY%22%5D%7D" title="forex cross-rates" style="width:100%;height:400px;"></iframe>
</div>

<?php View::partial('_footer'); ?>
