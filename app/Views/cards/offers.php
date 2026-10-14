<div class="dashboard__header">
    <div>
        <h2><i class="fa fa-gift" style="color:var(--accent);margin-right:8px;"></i> Special Offers & Rewards Hub</h2>
        <p class="text-muted" style="font-size:13px;">Exclusive cashback perks, yield boosters, welcome bonuses, and debit card rewards.</p>
    </div>
    <div style="display:flex;gap:10px;align-items:center;">
        <div style="background:var(--bg-card);border:1px solid var(--border);padding:8px 16px;border-radius:10px;text-align:right;">
            <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;">Account Balance</div>
            <div style="font-size:18px;font-weight:800;color:var(--accent);">$<?= number_format($user['balance'], 2) ?></div>
        </div>
        <a href="<?= url('cards') ?>" class="btn btn--outline btn--sm"><i class="fa fa-credit-card"></i> My Debit Cards</a>
    </div>
</div>

<!-- Promo Code Redemption Banner -->
<div class="card" style="background:linear-gradient(135deg, rgba(65,224,136,0.08) 0%, rgba(20,28,38,0.9) 100%);border:1px solid rgba(65,224,136,0.3);padding:24px;margin-bottom:30px;">
    <div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:20px;">
        <div style="max-width:550px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                <span class="badge badge--success" style="font-size:10px;letter-spacing:1px;">INSTANT BONUS CODE</span>
                <span style="font-size:12px;color:var(--text-muted);">Have a promotion or voucher code?</span>
            </div>
            <h3 style="margin:0 0 6px 0;font-size:20px;color:#fff;">Redeem Promo Voucher & Unlock Cash Credits</h3>
            <p class="text-muted" style="font-size:13px;margin:0;">Enter official promo codes such as <strong style="color:var(--accent);">WELCOME50</strong>, <strong style="color:var(--accent);">CRYPTOVIP</strong>, or <strong style="color:var(--accent);">WEEKEND15</strong> to receive instant wallet bonuses!</p>
        </div>
        <form method="POST" action="<?= url('dashboard/offers/redeem') ?>" style="display:flex;gap:10px;flex-wrap:wrap;min-width:320px;">
            <?= csrf_field() ?>
            <div style="position:relative;flex:1;min-width:180px;">
                <input type="text" name="promo_code" class="form-control" placeholder="ENTER CODE (e.g. WELCOME50)" style="text-transform:uppercase;font-weight:700;letter-spacing:1px;height:44px;" required>
            </div>
            <button type="submit" class="btn btn--accent" style="height:44px;padding:0 24px;font-weight:700;">
                <i class="fa fa-bolt"></i> Redeem Code
            </button>
        </form>
    </div>
</div>

<!-- Offers Filter Tabs -->
<div style="display:flex;gap:10px;margin-bottom:20px;overflow-x:auto;padding-bottom:5px;">
    <button class="btn btn--sm btn--accent offer-filter-btn" onclick="filterOffers('all', this)"><i class="fa fa-th-large"></i> All Offers (<?= count($offers) ?>)</button>
    <button class="btn btn--sm btn--outline offer-filter-btn" onclick="filterOffers('card', this)"><i class="fa fa-credit-card"></i> Card Perks</button>
    <button class="btn btn--sm btn--outline offer-filter-btn" onclick="filterOffers('cashback', this)"><i class="fa fa-percentage"></i> Cashbacks</button>
    <button class="btn btn--sm btn--outline offer-filter-btn" onclick="filterOffers('deposit', this)"><i class="fa fa-chart-line"></i> Yield Boosters</button>
</div>

<!-- Offers Grid -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(340px,1fr));gap:24px;margin-bottom:40px;">
    <?php foreach ($offers as $offer): ?>
    <?php 
        $isClaimed = in_array($offer['id'], $claimedIds ?? []);
        $badgeClass = match($offer['badge']) {
            'HOT OFFER' => 'background:#ff4757;color:#fff;',
            'WELCOME BONUS' => 'background:var(--accent);color:#0a0e17;font-weight:800;',
            'HIGH YIELD' => 'background:#9b59b6;color:#fff;',
            'VIP EXCLUSIVE' => 'background:linear-gradient(135deg, #d4af37, #f5d77a);color:#000;font-weight:800;',
            'LIMITED TIME' => 'background:#e67e22;color:#fff;',
            default => 'background:#3498db;color:#fff;'
        };
        $perksList = !empty($offer['perks']) ? explode(';', $offer['perks']) : [];
    ?>
    <div class="card offer-card-item" data-category="<?= e($offer['category']) ?>" style="display:flex;flex-direction:column;justify-content:space-between;border:1px solid var(--border);position:relative;overflow:hidden;transition:all 0.3s ease;">
        <div>
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
                <span style="font-size:11px;padding:3px 10px;border-radius:20px;letter-spacing:0.5px;<?= $badgeClass ?>">
                    <?= e($offer['badge']) ?>
                </span>
                <?php if ($offer['expires_at']): ?>
                <span class="text-muted" style="font-size:12px;"><i class="fa fa-clock"></i> Exp: <?= date('M j, Y', strtotime($offer['expires_at'])) ?></span>
                <?php endif; ?>
            </div>

            <h3 style="font-size:18px;margin-bottom:8px;color:#fff;"><?= e($offer['title']) ?></h3>
            <p class="text-muted" style="font-size:13px;line-height:1.5;margin-bottom:16px;"><?= e($offer['description']) ?></p>

            <?php if (!empty($perksList)): ?>
            <div style="margin-bottom:18px;background:rgba(255,255,255,0.02);padding:12px;border-radius:8px;border:1px solid rgba(255,255,255,0.05);">
                <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;margin-bottom:6px;font-weight:700;">Included Privileges</div>
                <ul style="list-style:none;padding:0;margin:0;font-size:12px;">
                    <?php foreach ($perksList as $perk): ?>
                    <li style="display:flex;align-items:center;gap:6px;margin-bottom:4px;color:#cbd5e1;">
                        <i class="fa fa-check-circle" style="color:var(--accent);font-size:11px;"></i> <?= e($perk) ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>

        <div style="border-top:1px solid var(--border);padding-top:16px;margin-top:10px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
                <div>
                    <div style="font-size:11px;color:var(--text-muted);">Reward Value</div>
                    <div style="font-size:18px;font-weight:800;color:var(--accent);">
                        <?= $offer['reward_type'] === 'percentage' ? rtrim(rtrim(number_format($offer['reward_amount'], 2), '0'), '.') . '%' : ($offer['reward_type'] === 'fixed' ? '$' . number_format($offer['reward_amount'], 2) : 'VIP Perk') ?>
                    </div>
                </div>
                <?php if (!empty($offer['promo_code'])): ?>
                <div style="text-align:right;">
                    <div style="font-size:11px;color:var(--text-muted);">Promo Code</div>
                    <code style="background:rgba(65,224,136,0.1);color:var(--accent);padding:2px 8px;border-radius:4px;font-weight:700;font-size:12px;cursor:pointer;" onclick="navigator.clipboard.writeText('<?= e($offer['promo_code']) ?>');alert('Code <?= e($offer['promo_code']) ?> copied!');" title="Click to copy">
                        <?= e($offer['promo_code']) ?> <i class="fa fa-copy" style="font-size:10px;"></i>
                    </code>
                </div>
                <?php endif; ?>
            </div>

            <?php if ($isClaimed): ?>
            <button class="btn btn--outline" style="width:100%;border-color:var(--accent);color:var(--accent);cursor:default;" disabled>
                <i class="fa fa-check"></i> Activated on Account
            </button>
            <?php else: ?>
            <form method="POST" action="<?= url('dashboard/offers/claim') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="offer_id" value="<?= $offer['id'] ?>">
                <button type="submit" class="btn btn--accent" style="width:100%;">
                    <i class="fa fa-unlock-alt"></i> Activate Offer Now
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Debit Card Tiers Comparison Matrix -->
<div class="card" style="margin-bottom:40px;">
    <div class="d-flex justify-between align-center mb-2" style="flex-wrap:wrap;gap:10px;">
        <div>
            <h3><i class="fa fa-layer-group" style="color:var(--accent);margin-right:8px;"></i> TrustCrypto Card Tier Benefits & Privileges</h3>
            <p class="text-muted" style="font-size:13px;margin:0;">Choose the card tier tailored to your spending volume and lifestyle rewards.</p>
        </div>
        <a href="<?= url('cards') ?>" class="btn btn--accent btn--sm"><i class="fa fa-plus-circle"></i> Apply for a Card</a>
    </div>

    <div class="table-wrapper" style="margin-top:20px;">
        <table>
            <thead>
                <tr>
                    <th>Feature & Perk</th>
                    <th style="color:#a0aec0;">Virtual Crypto Card</th>
                    <th style="color:#d4af37;">Titanium Metal Card</th>
                    <th style="color:var(--accent);">VIP Black Diamond</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Issuance Fee</strong></td>
                    <td><span class="badge badge--success">FREE ($0)</span></td>
                    <td>$25.00</td>
                    <td>$100.00</td>
                </tr>
                <tr>
                    <td><strong>Crypto Cashback</strong></td>
                    <td>1.5% Unlimited</td>
                    <td>3.0% on All Purchases</td>
                    <td><strong style="color:var(--accent);">5.0% Unlimited</strong></td>
                </tr>
                <tr>
                    <td><strong>Streaming & Entertainment</strong></td>
                    <td>5% Netflix & Spotify</td>
                    <td>10% Cashback</td>
                    <td>10% + 100% Rebate (up to $20/mo)</td>
                </tr>
                <tr>
                    <td><strong>Daily Spending Limit</strong></td>
                    <td>$5,000 / day</td>
                    <td>$25,000 / day</td>
                    <td>$100,000 / day</td>
                </tr>
                <tr>
                    <td><strong>Monthly Spending Limit</strong></td>
                    <td>$25,000 / month</td>
                    <td>$100,000 / month</td>
                    <td>$500,000 / month</td>
                </tr>
                <tr>
                    <td><strong>ATM Cash Withdrawals</strong></td>
                    <td>Not Applicable (Virtual)</td>
                    <td>$1,000 / month Free</td>
                    <td>$5,000 / month Free</td>
                </tr>
                <tr>
                    <td><strong>Foreign Exchange (FX) Fees</strong></td>
                    <td>1.0%</td>
                    <td>0.5%</td>
                    <td><span class="badge badge--success">0.0% Free</span></td>
                </tr>
                <tr>
                    <td><strong>Investment APY Booster</strong></td>
                    <td>+1.0% APY</td>
                    <td>+2.5% APY</td>
                    <td><strong style="color:var(--accent);">+4.5% APY Bonus</strong></td>
                </tr>
                <tr>
                    <td><strong>Airport Lounge Access</strong></td>
                    <td>—</td>
                    <td>Discounted Entry</td>
                    <td><i class="fa fa-check text-accent"></i> Priority Pass Worldwide</td>
                </tr>
                <tr>
                    <td><strong>Delivery & Shipping</strong></td>
                    <td>Instant (10 Seconds)</td>
                    <td>Express Global Courier (3-5 Days)</td>
                    <td>VIP Priority Handcrafted Metal Box</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
function filterOffers(cat, btn) {
    document.querySelectorAll('.offer-filter-btn').forEach(b => {
        b.classList.remove('btn--accent');
        b.classList.add('btn--outline');
    });
    btn.classList.remove('btn--outline');
    btn.classList.add('btn--accent');

    const items = document.querySelectorAll('.offer-card-item');
    items.forEach(item => {
        if (cat === 'all' || item.getAttribute('data-category') === cat) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>
