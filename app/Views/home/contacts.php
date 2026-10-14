<?php View::partial('_nav'); ?>

<section class="page-header">
    <div class="container">
        <h1>OUR <span style="color:var(--accent)">CONTACTS</span></h1>
        <p class="text-muted">Get in touch with our support team</p>
    </div>
</section>

<section class="container" style="padding:40px 20px;">
    <div class="contacts-grid">
        <div>
            <div class="card">
                <h3>Contact Information</h3>
                <div style="margin-top:20px;display:flex;flex-direction:column;gap:15px;">
                    <div class="d-flex align-center gap-2">
                        <i class="fa fa-envelope" style="color:var(--accent);font-size:20px;width:30px;"></i>
                        <span>support@trustcrypto.com</span>
                    </div>
                    <div class="d-flex align-center gap-2">
                        <i class="fa fa-clock" style="color:var(--accent);font-size:20px;width:30px;"></i>
                        <span>24/7 Customer Support</span>
                    </div>
                    <div class="d-flex align-center gap-2">
                        <i class="fa fa-globe" style="color:var(--accent);font-size:20px;width:30px;"></i>
                        <span>Worldwide Service</span>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="card">
                <h3>Send Message</h3>
                <p class="text-muted" style="font-size:13px;margin-bottom:20px;">Send us a message and we will reply to you within 24 hours!</p>
                <form method="POST" action="<?= url('contacts') ?>">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label>Your Login</label>
                        <input type="text" name="login" class="form-control" placeholder="Your username" value="<?= e(old('login')) ?>">
                    </div>
                    <div class="form-group">
                        <label>E-MAIL</label>
                        <input type="email" name="email" class="form-control" placeholder="name@example.com" required value="<?= e(old('email')) ?>">
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="message" class="form-control" rows="5" required placeholder="Your message..."><?= e(old('message')) ?></textarea>
                    </div>
                    <button type="submit" class="btn btn--primary btn--block">SEND</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php View::partial('_footer'); ?>
