<header class="header">
    <div class="container">
        <div class="header__row">
            <nav class="header__nav">
                <ul>
                    <li class="<?= ($active ?? '') === 'home' ? 'is-active' : '' ?>"><a href="<?= url('/') ?>">Home page</a></li>
                    <li class="<?= ($active ?? '') === 'about' ? 'is-active' : '' ?>"><a href="<?= url('about') ?>">About us</a></li>
                    <li class="<?= ($active ?? '') === 'investments' ? 'is-active' : '' ?>"><a href="<?= url('investments') ?>">Investments</a></li>
                    <li class="<?= ($active ?? '') === 'cards' ? 'is-active' : '' ?>"><a href="<?= url('cards') ?>">Debit Cards</a></li>
                    <li class="<?= ($active ?? '') === 'offers' ? 'is-active' : '' ?>"><a href="<?= url('offers') ?>">Offers</a></li>
                    <li class="<?= ($active ?? '') === 'faq' ? 'is-active' : '' ?>"><a href="<?= url('faq') ?>">FAQ</a></li>
                    <li class="<?= ($active ?? '') === 'contacts' ? 'is-active' : '' ?>"><a href="<?= url('contacts') ?>">Contacts</a></li>
                </ul>
            </nav>
            <a class="header__logo" href="<?= url('/') ?>">
                <span style="font-size:24px;font-weight:800;color:var(--accent)">TRUST<span style="color:#fff">CRYPTO</span></span>
            </a>
            <div class="header__right">
                <a class="link-icon" href="<?= url('statistic') ?>">
                    <svg viewBox="0 0 24 24"><path d="M3 13h2v8H3v-8zm4-5h2v13H7V8zm4-3h2v16h-2V5zm4 6h2v10h-2V11zm4-4h2v14h-2V7z"/></svg>
                    <span>Statistics</span>
                </a>
                <?php if (Auth::check()): ?>
                    <a class="link-icon" href="<?= url('dashboard') ?>">
                        <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        <span>Dashboard</span>
                    </a>
                <?php else: ?>
                    <a class="link-icon" href="<?= url('register') ?>">
                        <svg viewBox="0 0 24 24"><path d="M21 18v1c0 1.1-.9 2-2 2H5c-1.11 0-2-.9-2-2V5c0-1.1.89-2 2-2h14c1.1 0 2 .9 2 2v1h-9c-1.11 0-2 .9-2 2v8c0 1.1.89 2 2 2h9zm-9-2h10V8H12v8zm4-2.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/></svg>
                        <span>Registration</span>
                    </a>
                    <a class="link-icon" href="<?= url('login') ?>">
                        <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
                        <span>Login</span>
                    </a>
                <?php endif; ?>
                <div class="select-language"><a class="select-language__link"><span>EN</span></a></div>
            </div>
        </div>
        <div class="header__slogan">
            <p>Crypto Investments</p>
        </div>
        <div class="ticker">
            <iframe src="https://widget.coinlib.io/widget?type=horizontal_v2&theme=dark&pref_coin_id=1505&invert_hover=no" scrolling="auto" marginwidth="0" marginheight="0" frameborder="0" border="0" style="border:0;margin:0;padding:0;"></iframe>
        </div>
    </div>
</header>
