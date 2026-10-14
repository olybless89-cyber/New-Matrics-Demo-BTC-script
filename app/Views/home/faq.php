<?php View::partial('_nav'); ?>

<section class="page-header">
    <div class="container">
        <h1>FREQUENTLY ASKED <span style="color:var(--accent)">QUESTIONS</span></h1>
        <p class="text-muted">Find answers to common questions about Trustcrypto</p>
    </div>
</section>

<section class="container" style="padding:40px 20px;">
    <div class="faq-list">
        <div class="faq-category">About Us</div>
        <div class="faq-item">
            <div class="faq-item__question" onclick="this.parentElement.classList.toggle('is-open')">What is Trustcrypto? <span>+</span></div>
            <div class="faq-item__answer"><p>Trustcrypto is a professional cryptocurrency investment platform that uses an advanced AI trading robot to generate stable returns for investors.</p></div>
        </div>
        <div class="faq-item">
            <div class="faq-item__question" onclick="this.parentElement.classList.toggle('is-open')">Is Trustcrypto an officially registered company? <span>+</span></div>
            <div class="faq-item__answer"><p>Yes, Trustcrypto is a legally registered company operating in full compliance with international financial regulations.</p></div>
        </div>
        <div class="faq-item">
            <div class="faq-item__question" onclick="this.parentElement.classList.toggle('is-open')">Do you have any country restrictions? <span>+</span></div>
            <div class="faq-item__answer"><p>We accept investors from most countries worldwide. Please check our terms for the complete list of supported regions.</p></div>
        </div>

        <div class="faq-category">My Account</div>
        <div class="faq-item">
            <div class="faq-item__question" onclick="this.parentElement.classList.toggle('is-open')">How many accounts can I open? <span>+</span></div>
            <div class="faq-item__answer"><p>Each user is allowed one account. Multiple accounts from the same individual may result in suspension.</p></div>
        </div>
        <div class="faq-item">
            <div class="faq-item__question" onclick="this.parentElement.classList.toggle('is-open')">How much does it cost to open an account? <span>+</span></div>
            <div class="faq-item__answer"><p>Opening an account is completely free. You only need to deposit funds to start investing.</p></div>
        </div>
        <div class="faq-item">
            <div class="faq-item__question" onclick="this.parentElement.classList.toggle('is-open')">Can I lose my funds by investing? <span>+</span></div>
            <div class="faq-item__answer"><p>All investments carry some risk. However, our trading robot is designed to minimize risk and maximize returns through diversified strategies.</p></div>
        </div>

        <div class="faq-category">Deposit</div>
        <div class="faq-item">
            <div class="faq-item__question" onclick="this.parentElement.classList.toggle('is-open')">What payment methods can I use to deposit? <span>+</span></div>
            <div class="faq-item__answer"><p>We accept Bitcoin, Ethereum, USDT, and major debit/credit cards for deposits.</p></div>
        </div>
        <div class="faq-item">
            <div class="faq-item__question" onclick="this.parentElement.classList.toggle('is-open')">Are there any restrictions on the amount of investment? <span>+</span></div>
            <div class="faq-item__answer"><p>Each investment plan has a minimum and maximum deposit amount. Please check the investments page for details.</p></div>
        </div>

        <div class="faq-category">Withdrawal of Funds</div>
        <div class="faq-item">
            <div class="faq-item__question" onclick="this.parentElement.classList.toggle('is-open')">How often will I receive accruals on my deposits? <span>+</span></div>
            <div class="faq-item__answer"><p>Earnings are credited according to your chosen plan - hourly or daily.</p></div>
        </div>
        <div class="faq-item">
            <div class="faq-item__question" onclick="this.parentElement.classList.toggle('is-open')">How long does it take to process a withdrawal? <span>+</span></div>
            <div class="faq-item__answer"><p>Withdrawals are typically processed within 24 hours. Crypto withdrawals are usually instant.</p></div>
        </div>
        <div class="faq-item">
            <div class="faq-item__question" onclick="this.parentElement.classList.toggle('is-open')">What is the minimum withdrawal amount? <span>+</span></div>
            <div class="faq-item__answer"><p>The minimum withdrawal amount is $10.</p></div>
        </div>

        <div class="faq-category">Referral Program</div>
        <div class="faq-item">
            <div class="faq-item__question" onclick="this.parentElement.classList.toggle('is-open')">Do you offer a referral program? <span>+</span></div>
            <div class="faq-item__answer"><p>Yes! Earn 5% commission on every deposit made by your referrals.</p></div>
        </div>
        <div class="faq-item">
            <div class="faq-item__question" onclick="this.parentElement.classList.toggle('is-open')">Do I need to invest to participate in the affiliate program? <span>+</span></div>
            <div class="faq-item__answer"><p>No, you can earn referral commissions without making a deposit yourself.</p></div>
        </div>
    </div>
</section>

<?php View::partial('_footer'); ?>
