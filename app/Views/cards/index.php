<div class="dashboard__header">
    <div>
        <h2><i class="fa fa-credit-card" style="color:var(--accent);margin-right:8px;"></i> Crypto Debit Cards</h2>
        <p class="text-muted" style="font-size:13px;">Manage your physical and virtual debit cards, real-time spending, security controls, and cashbacks.</p>
    </div>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
        <a href="<?= url('dashboard/offers') ?>" class="btn btn--outline btn--sm" style="border-color:var(--accent);color:var(--accent);">
            <i class="fa fa-gift"></i> Offers & Rewards <span class="badge badge--success" style="font-size:9px;margin-left:4px;">HOT</span>
        </a>
        <a href="#applySection" class="btn btn--accent btn--sm" onclick="document.getElementById('applySection').scrollIntoView({behavior:'smooth'});">
            <i class="fa fa-plus-circle"></i> Apply for Card
        </a>
    </div>
</div>

<!-- Stats Overview Banner -->
<div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr));margin-bottom:30px;">
    <div class="stat-card" style="padding:20px;">
        <div class="stat-card__label">Total Card Balance</div>
        <div class="stat-card__value" style="color:var(--accent);font-size:26px;">$<?= number_format($totalCardBalance, 2) ?></div>
        <div style="font-size:11px;color:var(--text-muted);margin-top:6px;">Across <?= count($cards) ?> active card(s)</div>
    </div>
    <div class="stat-card" style="padding:20px;">
        <div class="stat-card__label">Available Main Wallet</div>
        <div class="stat-card__value" style="font-size:26px;">$<?= number_format($balance, 2) ?></div>
        <div style="font-size:11px;color:var(--text-muted);margin-top:6px;">Ready for card funding</div>
    </div>
    <div class="stat-card" style="padding:20px;">
        <div class="stat-card__label">Total Cashback Earned</div>
        <div class="stat-card__value" style="color:#d4af37;font-size:26px;">+$<?= number_format($totalCashback, 2) ?></div>
        <div style="font-size:11px;color:var(--text-muted);margin-top:6px;">Direct rewards to balance</div>
    </div>
    <div class="stat-card" style="padding:20px;">
        <div class="stat-card__label">Card Network</div>
        <div class="stat-card__value" style="font-size:26px;">Visa & MC</div>
        <div style="font-size:11px;color:var(--text-muted);margin-top:6px;">Global acceptance at 60M+ POS</div>
    </div>
</div>

<?php if (empty($cards)): ?>
<!-- Empty State Hero with Instant Application -->
<div class="card text-center" style="padding:40px 20px;border:1px dashed var(--accent);margin-bottom:30px;">
    <div style="font-size:55px;margin-bottom:15px;animation:pulse 2s infinite;">💳</div>
    <h3 style="font-size:24px;margin-bottom:8px;">You don't have any debit cards yet</h3>
    <p class="text-muted" style="max-width:550px;margin:0 auto 24px;font-size:14px;line-height:1.6;">
        Unlock borderless spending directly from your crypto wallet! Choose between an Instant Virtual Card (Free) or a Luxury Metal Physical Card with up to 5% crypto cashback.
    </p>
    <a href="#applySection" class="btn btn--accent" style="padding:12px 32px;font-weight:700;">
        <i class="fa fa-sparkles"></i> Choose & Apply for Your Card Below
    </a>
</div>
<?php else: ?>

<!-- Card Showcase Carousel / Grid -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(420px,1fr));gap:26px;margin-bottom:40px;">
    <?php foreach ($cards as $card): ?>
    <?php 
        $isBlocked = ($card['status'] === 'blocked');
        $tier = $card['card_tier'] ?? 'virtual';
        $brand = strtolower($card['card_type'] ?? 'visa');
        
        // Dynamic card styling based on tier
        $cardBg = match($tier) {
            'metal' => 'linear-gradient(135deg, #2b3a4a 0%, #151d26 50%, #0d131a 100%)',
            'vip' => 'linear-gradient(135deg, #1b1b1b 0%, #0a0a0a 50%, #222222 100%)',
            default => 'linear-gradient(135deg, #133a28 0%, #0c1815 50%, #112836 100%)'
        };
        $borderColor = match($tier) {
            'metal' => '#64b5f6',
            'vip' => '#d4af37',
            default => 'var(--accent)'
        };
        $tierLabel = match($tier) {
            'metal' => 'TITANIUM METAL',
            'vip' => 'VIP BLACK DIAMOND',
            default => 'VIRTUAL CRYPTO'
        };
        $cashbackRate = (float) ($card['cashback_rate'] ?? ($tier === 'vip' ? 5.0 : ($tier === 'metal' ? 3.0 : 1.5)));
    ?>
    <div class="card" style="padding:24px;border:1px solid var(--border);border-top:3px solid <?= $borderColor ?>;border-radius:16px;">
        
        <!-- Interactive 3D Card Container -->
        <div class="flip-card-wrapper" id="cardFlip_<?= $card['id'] ?>" style="perspective:1000px;margin-bottom:20px;">
            <div class="flip-card-inner" style="position:relative;width:100%;height:230px;transition:transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);transform-style:preserve-3d;">
                
                <!-- Card Front Side -->
                <div class="flip-card-front" style="position:absolute;width:100%;height:100%;backface-visibility:hidden;-webkit-backface-visibility:hidden;border-radius:16px;padding:22px;background:<?= $cardBg ?>;border:1px solid <?= $borderColor ?>;box-shadow:0 12px 35px rgba(0,0,0,0.6);display:flex;flex-direction:column;justify-content:space-between;overflow:hidden;">
                    
                    <!-- Decorative Light Reflection -->
                    <div style="position:absolute;top:-40%;right:-30%;width:260px;height:260px;background:radial-gradient(circle,rgba(255,255,255,0.08),transparent 70%);border-radius:50%;pointer-events:none;"></div>
                    
                    <!-- Card Top Row -->
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;position:relative;z-index:2;">
                        <div>
                            <span style="font-size:10px;font-weight:800;letter-spacing:1px;padding:3px 8px;border-radius:6px;background:rgba(0,0,0,0.5);border:1px solid <?= $borderColor ?>;color:<?= $borderColor ?>;">
                                <?= $tierLabel ?>
                            </span>
                            <span style="margin-left:6px;font-size:10px;color:#cbd5e1;"><?= strtoupper($card['currency'] ?? 'USD') ?></span>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <?php if ($isBlocked): ?>
                            <span class="badge badge--danger" style="font-size:10px;"><i class="fa fa-lock"></i> FROZEN</span>
                            <?php else: ?>
                            <span class="badge badge--success" style="font-size:10px;"><i class="fa fa-circle" style="font-size:7px;"></i> ACTIVE</span>
                            <?php endif; ?>
                            <button type="button" class="btn btn--sm" style="padding:2px 8px;font-size:11px;background:rgba(255,255,255,0.1);color:#fff;" onclick="toggleCardFlip(<?= $card['id'] ?>)" title="Flip to view CVV & PIN">
                                <i class="fa fa-repeat"></i> Flip Card
                            </button>
                        </div>
                    </div>

                    <!-- Chip and Contactless Icon -->
                    <div style="display:flex;align-items:center;gap:14px;position:relative;z-index:2;margin-top:8px;">
                        <div style="width:44px;height:32px;background:linear-gradient(135deg, #f5d77a 0%, #d4af37 50%, #aa820a 100%);border-radius:6px;position:relative;border:1px solid rgba(0,0,0,0.3);box-shadow:inset 0 0 4px rgba(0,0,0,0.3);">
                            <div style="position:absolute;top:50%;left:0;right:0;height:1px;background:rgba(0,0,0,0.25);"></div>
                            <div style="position:absolute;top:0;bottom:0;left:50%;width:1px;background:rgba(0,0,0,0.25);"></div>
                        </div>
                        <i class="fa fa-wifi" style="transform:rotate(90deg);font-size:18px;color:rgba(255,255,255,0.6);"></i>
                    </div>

                    <!-- 16 Digit Card Number -->
                    <div style="position:relative;z-index:2;display:flex;align-items:center;justify-content:space-between;">
                        <span style="font-family:'Courier New', monospace;font-size:21px;font-weight:700;letter-spacing:3px;color:#ffffff;text-shadow:0 2px 4px rgba(0,0,0,0.8);">
                            <?= chunk_split($card['card_number'], 4, ' ') ?>
                        </span>
                        <button type="button" style="background:none;border:none;color:rgba(255,255,255,0.7);cursor:pointer;font-size:13px;" onclick="navigator.clipboard.writeText('<?= $card['card_number'] ?>');alert('Card number copied!');" title="Copy Card Number">
                            <i class="fa fa-copy"></i>
                        </button>
                    </div>

                    <!-- Bottom Details Row -->
                    <div style="display:flex;justify-content:space-between;align-items:flex-end;position:relative;z-index:2;">
                        <div>
                            <div style="font-size:9px;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;">Cardholder Name</div>
                            <div style="font-size:13px;font-weight:700;letter-spacing:1.5px;color:#fff;text-shadow:0 1px 2px rgba(0,0,0,0.8);">
                                <?= e(strtoupper($card['cardholder_name'])) ?>
                            </div>
                        </div>
                        <div>
                            <div style="font-size:9px;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;">Expires</div>
                            <div style="font-family:'Courier New', monospace;font-size:13px;font-weight:700;color:#fff;">
                                <?= str_pad($card['expiry_month'], 2, '0', STR_PAD_LEFT) ?>/<?= substr((string)$card['expiry_year'], -2) ?>
                            </div>
                        </div>
                        <div>
                            <?php if ($brand === 'mastercard'): ?>
                            <div style="font-size:22px;font-weight:900;color:#eb001b;letter-spacing:-2px;"><span style="color:#eb001b;">●</span><span style="color:#f79e1b;margin-left:-8px;">●</span></div>
                            <?php else: ?>
                            <div style="font-size:22px;font-weight:900;font-style:italic;color:#fff;letter-spacing:1px;">VISA</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Card Back Side (Reveals CVV & PIN) -->
                <div class="flip-card-back" style="position:absolute;width:100%;height:100%;backface-visibility:hidden;-webkit-backface-visibility:hidden;transform:rotateY(180deg);border-radius:16px;background:#0d131a;border:1px solid <?= $borderColor ?>;box-shadow:0 12px 35px rgba(0,0,0,0.6);display:flex;flex-direction:column;justify-content:space-between;padding:16px 0;overflow:hidden;">
                    
                    <!-- Magnetic Strip -->
                    <div style="width:100%;height:40px;background:#000;margin-top:6px;"></div>

                    <!-- Signature & CVV Strip -->
                    <div style="padding:0 20px;display:flex;align-items:center;gap:12px;">
                        <div style="flex:1;height:34px;background:#f8fafc;border-radius:4px;display:flex;align-items:center;padding:0 12px;font-family:'Brush Script MT', cursive;font-size:16px;color:#334155;">
                            Authorized Signature
                        </div>
                        <div style="background:#fff;color:#000;padding:6px 12px;border-radius:4px;font-family:'Courier New', monospace;font-weight:800;font-size:15px;letter-spacing:1px;" id="cvvBox_<?= $card['id'] ?>">
                            <?= e($card['cvv']) ?>
                        </div>
                    </div>

                    <!-- PIN & Flip Back -->
                    <div style="padding:0 20px;display:flex;justify-content:space-between;align-items:center;">
                        <div>
                            <span style="font-size:10px;color:#94a3b8;">ATM PIN:</span>
                            <span style="font-family:'Courier New', monospace;font-weight:800;color:var(--accent);font-size:14px;margin-left:6px;" id="pinBox_<?= $card['id'] ?>">
                                <?= e($card['pin'] ?? '1234') ?>
                            </span>
                        </div>
                        <button type="button" class="btn btn--sm" style="padding:3px 10px;font-size:11px;background:rgba(255,255,255,0.1);color:#fff;" onclick="toggleCardFlip(<?= $card['id'] ?>)">
                            <i class="fa fa-undo"></i> Show Front
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Card Balances & Status Header -->
        <div style="background:var(--bg-alt);border:1px solid var(--border);border-radius:12px;padding:16px;margin-bottom:18px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                <div>
                    <div class="text-muted" style="font-size:11px;text-transform:uppercase;">Card Available Balance</div>
                    <div style="font-size:24px;font-weight:800;color:var(--accent);">$<?= number_format($card['balance'], 2) ?></div>
                </div>
                <div style="text-align:right;">
                    <div class="text-muted" style="font-size:11px;text-transform:uppercase;">Cashback Rate</div>
                    <div style="font-size:16px;font-weight:700;color:#d4af37;"><i class="fa fa-coins"></i> <?= $cashbackRate ?>% Crypto</div>
                </div>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--text-muted);border-top:1px solid rgba(255,255,255,0.06);padding-top:8px;">
                <span>Daily Limit: <strong>$<?= number_format($card['daily_limit'], 2) ?></strong></span>
                <span>Monthly: <strong>$<?= number_format($card['monthly_limit'] ?? 25000, 2) ?></strong></span>
            </div>
        </div>

        <!-- Quick Action Buttons -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:18px;">
            <!-- Fund Card Trigger -->
            <button class="btn btn--accent btn--sm" onclick="toggleFundModal(<?= $card['id'] ?>)">
                <i class="fa fa-arrow-down"></i> Fund Card
            </button>
            
            <!-- Withdraw from Card Trigger -->
            <button class="btn btn--outline btn--sm" onclick="toggleWithdrawModal(<?= $card['id'] ?>)">
                <i class="fa fa-arrow-up"></i> Move to Wallet
            </button>
        </div>

        <!-- Card Security Controls -->
        <div style="display:flex;gap:8px;margin-bottom:18px;">
            <?php if ($card['status'] === 'active'): ?>
            <form method="POST" action="<?= url('cards/' . $card['id'] . '/freeze') ?>" style="flex:1;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn--sm" style="width:100%;background:rgba(241,76,76,0.15);color:#f14c4c;border:1px solid rgba(241,76,76,0.3);">
                    <i class="fa fa-snowflake"></i> Freeze Card
                </button>
            </form>
            <?php else: ?>
            <form method="POST" action="<?= url('cards/' . $card['id'] . '/unfreeze') ?>" style="flex:1;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn--sm" style="width:100%;background:rgba(65,224,136,0.15);color:var(--accent);border:1px solid var(--accent);">
                    <i class="fa fa-unlock"></i> Unfreeze Card
                </button>
            </form>
            <?php endif; ?>

            <button class="btn btn--outline btn--sm" style="flex:1;" onclick="toggleSettingsModal(<?= $card['id'] ?>)">
                <i class="fa fa-cog"></i> Limits & PIN
            </button>
            
            <button class="btn btn--sm" style="flex:1;background:rgba(52,152,219,0.15);color:#3498db;border:1px solid rgba(52,152,219,0.3);" onclick="toggleSimulatorModal(<?= $card['id'] ?>)">
                <i class="fa fa-shopping-cart"></i> Test Spend
            </button>
        </div>

        <!-- Hidden Popups / In-place Accordions -->
        
        <!-- 1. FUND CARD FORM -->
        <div id="fundCard_<?= $card['id'] ?>" style="display:none;background:var(--bg-card);border:1px solid var(--accent);border-radius:10px;padding:16px;margin-bottom:18px;">
            <div class="d-flex justify-between align-center mb-1">
                <h4 style="font-size:14px;color:var(--accent);margin:0;"><i class="fa fa-wallet"></i> Fund Card from Account Wallet</h4>
                <button type="button" onclick="toggleFundModal(<?= $card['id'] ?>)" style="background:none;border:none;color:#fff;cursor:pointer;">&times;</button>
            </div>
            <p class="text-muted" style="font-size:12px;margin-bottom:10px;">Available Wallet Balance: <strong>$<?= number_format($balance, 2) ?></strong></p>
            <form method="POST" action="<?= url('cards/fund') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="card_id" value="<?= $card['id'] ?>">
                <div style="display:flex;gap:8px;margin-bottom:8px;">
                    <button type="button" class="btn btn--sm btn--outline" style="font-size:11px;padding:4px 8px;" onclick="document.getElementById('fundAmount_<?= $card['id'] ?>').value=50;">$50</button>
                    <button type="button" class="btn btn--sm btn--outline" style="font-size:11px;padding:4px 8px;" onclick="document.getElementById('fundAmount_<?= $card['id'] ?>').value=100;">$100</button>
                    <button type="button" class="btn btn--sm btn--outline" style="font-size:11px;padding:4px 8px;" onclick="document.getElementById('fundAmount_<?= $card['id'] ?>').value=250;">$250</button>
                    <button type="button" class="btn btn--sm btn--outline" style="font-size:11px;padding:4px 8px;" onclick="document.getElementById('fundAmount_<?= $card['id'] ?>').value=<?= (int)$balance ?>;">Max</button>
                </div>
                <div style="display:flex;gap:8px;">
                    <input type="number" step="0.01" min="1" max="<?= $balance ?>" id="fundAmount_<?= $card['id'] ?>" name="amount" class="form-control" placeholder="Amount ($)" required>
                    <button type="submit" class="btn btn--accent btn--sm">Confirm</button>
                </div>
            </form>
        </div>

        <!-- 2. WITHDRAW BACK TO WALLET FORM -->
        <div id="withdrawCard_<?= $card['id'] ?>" style="display:none;background:var(--bg-card);border:1px solid #3498db;border-radius:10px;padding:16px;margin-bottom:18px;">
            <div class="d-flex justify-between align-center mb-1">
                <h4 style="font-size:14px;color:#3498db;margin:0;"><i class="fa fa-undo"></i> Transfer Card Balance back to Wallet</h4>
                <button type="button" onclick="toggleWithdrawModal(<?= $card['id'] ?>)" style="background:none;border:none;color:#fff;cursor:pointer;">&times;</button>
            </div>
            <p class="text-muted" style="font-size:12px;margin-bottom:10px;">Available on Card: <strong>$<?= number_format($card['balance'], 2) ?></strong></p>
            <form method="POST" action="<?= url('cards/' . $card['id'] . '/withdraw') ?>">
                <?= csrf_field() ?>
                <div style="display:flex;gap:8px;">
                    <input type="number" step="0.01" min="1" max="<?= $card['balance'] ?>" name="amount" class="form-control" placeholder="Amount to transfer ($)" required>
                    <button type="submit" class="btn btn--accent btn--sm" style="background:#3498db;border-color:#3498db;">Transfer</button>
                </div>
            </form>
        </div>

        <!-- 3. LIMITS & SETTINGS FORM -->
        <div id="settingsCard_<?= $card['id'] ?>" style="display:none;background:var(--bg-card);border:1px solid var(--border);border-radius:10px;padding:16px;margin-bottom:18px;">
            <div class="d-flex justify-between align-center mb-2">
                <h4 style="font-size:14px;margin:0;"><i class="fa fa-shield-alt"></i> Card Limits & Security Settings</h4>
                <button type="button" onclick="toggleSettingsModal(<?= $card['id'] ?>)" style="background:none;border:none;color:#fff;cursor:pointer;">&times;</button>
            </div>
            <form method="POST" action="<?= url('cards/' . $card['id'] . '/limits') ?>">
                <?= csrf_field() ?>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
                    <div>
                        <label style="font-size:11px;color:var(--text-muted);">Daily Limit ($)</label>
                        <input type="number" step="100" min="100" name="daily_limit" class="form-control" value="<?= (float)$card['daily_limit'] ?>" required>
                    </div>
                    <div>
                        <label style="font-size:11px;color:var(--text-muted);">Monthly Limit ($)</label>
                        <input type="number" step="500" min="500" name="monthly_limit" class="form-control" value="<?= (float)($card['monthly_limit'] ?? 25000) ?>" required>
                    </div>
                </div>
                <div style="margin-bottom:10px;">
                    <label style="font-size:11px;color:var(--text-muted);">Change 4-Digit Security PIN</label>
                    <input type="password" maxlength="4" name="pin" class="form-control" placeholder="Leave blank to keep current PIN">
                </div>
                <div style="display:flex;gap:15px;margin-bottom:12px;font-size:12px;">
                    <label style="display:flex;align-items:center;gap:6px;cursor:pointer;">
                        <input type="checkbox" name="is_online_enabled" value="1" <?= (!isset($card['is_online_enabled']) || $card['is_online_enabled']) ? 'checked' : '' ?>>
                        Online Transactions
                    </label>
                    <label style="display:flex;align-items:center;gap:6px;cursor:pointer;">
                        <input type="checkbox" name="is_intl_enabled" value="1" <?= (!isset($card['is_intl_enabled']) || $card['is_intl_enabled']) ? 'checked' : '' ?>>
                        International Spending
                    </label>
                </div>
                <button type="submit" class="btn btn--accent btn--sm" style="width:100%;">Save Security Settings</button>
            </form>
        </div>

        <!-- 4. POS TEST SPENDING SIMULATOR -->
        <div id="simulatorCard_<?= $card['id'] ?>" style="display:none;background:var(--bg-card);border:1px solid #3498db;border-radius:10px;padding:16px;margin-bottom:18px;">
            <div class="d-flex justify-between align-center mb-1">
                <h4 style="font-size:14px;color:#3498db;margin:0;"><i class="fa fa-shopping-bag"></i> Live Card Spending Simulator</h4>
                <button type="button" onclick="toggleSimulatorModal(<?= $card['id'] ?>)" style="background:none;border:none;color:#fff;cursor:pointer;">&times;</button>
            </div>
            <p class="text-muted" style="font-size:12px;margin-bottom:12px;">Test your card swipe at popular online and in-store merchants. Automatically deducts card funds and credits your <?= $cashbackRate ?>% crypto cashback!</p>
            <form method="POST" action="<?= url('cards/' . $card['id'] . '/simulate') ?>">
                <?= csrf_field() ?>
                <div style="margin-bottom:10px;">
                    <label style="font-size:11px;color:var(--text-muted);">Select Test Merchant</label>
                    <select name="merchant" class="form-control" onchange="updateSimAmount(this, <?= $card['id'] ?>)">
                        <option value="Amazon Web Store" data-amount="49.99">Amazon Marketplace ($49.99)</option>
                        <option value="Netflix 4K Ultra Subscription" data-amount="19.99">Netflix Subscription (10% Cashback Bonus! - $19.99)</option>
                        <option value="Apple Store Online" data-amount="129.00">Apple Store ($129.00)</option>
                        <option value="Uber Ride & Eats" data-amount="28.50">Uber Ride & Eats ($28.50)</option>
                        <option value="Starbucks Reserve" data-amount="12.75">Starbucks Coffee ($12.75)</option>
                        <option value="Custom Merchant" data-amount="25.00">Custom Merchant & Amount</option>
                    </select>
                </div>
                <div style="display:flex;gap:8px;margin-bottom:10px;">
                    <input type="number" step="0.01" min="1" max="<?= $card['balance'] ?>" id="simAmount_<?= $card['id'] ?>" name="amount" class="form-control" value="49.99" placeholder="Amount ($)" required>
                    <button type="submit" class="btn btn--accent btn--sm" style="white-space:nowrap;">
                        <i class="fa fa-check"></i> Process Swipe
                    </button>
                </div>
            </form>
        </div>

        <!-- Recent Transactions for this Card -->
        <div>
            <div class="d-flex justify-between align-center mb-1">
                <span style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;">Recent Activity</span>
                <span style="font-size:11px;color:var(--text-muted);"><?= count($cardTxns[$card['id']] ?? []) ?> record(s)</span>
            </div>
            <?php if (empty($cardTxns[$card['id']])): ?>
            <div style="padding:15px 0;text-align:center;font-size:12px;color:var(--text-muted);">
                No transactions yet on this card. Try the <a href="javascript:void(0)" onclick="toggleSimulatorModal(<?= $card['id'] ?>)">Test Spend Simulator</a>!
            </div>
            <?php else: ?>
            <div style="max-height:160px;overflow-y:auto;padding-right:4px;">
                <?php foreach (array_slice($cardTxns[$card['id']], 0, 5) as $tx): ?>
                <?php 
                    $isFund = ($tx['type'] === 'fund');
                    $isPurchase = ($tx['type'] === 'purchase');
                    $icon = $isFund ? 'fa-arrow-down' : ($isPurchase ? 'fa-shopping-cart' : 'fa-arrow-up');
                    $iconColor = $isFund ? 'var(--accent)' : ($isPurchase ? '#e74c3c' : '#3498db');
                ?>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid rgba(255,255,255,0.05);font-size:12px;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div style="width:26px;height:26px;border-radius:50%;background:rgba(255,255,255,0.05);display:flex;align-items:center;justify-content:center;color:<?= $iconColor ?>;font-size:10px;">
                            <i class="fa <?= $icon ?>"></i>
                        </div>
                        <div>
                            <div style="font-weight:600;color:#fff;"><?= e($tx['merchant'] ?? $tx['description']) ?></div>
                            <div style="font-size:10px;color:var(--text-muted);"><?= date('M j, g:i a', strtotime($tx['created_at'])) ?></div>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-weight:700;color:<?= $isFund ? 'var(--accent)' : '#fff' ?>;">
                            <?= $isFund ? '+' : '-' ?>$<?= number_format($tx['amount'], 2) ?>
                        </div>
                        <?php if ((float)($tx['cashback_amount'] ?? 0) > 0): ?>
                        <div style="font-size:10px;color:#d4af37;">
                            +<?= number_format($tx['cashback_amount'], 2) ?> cashback
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Quick Promo / Offers Banner -->
<div class="card" style="background:linear-gradient(135deg, rgba(20,28,38,0.9) 0%, rgba(30,45,60,0.8) 100%);border:1px solid var(--border);padding:24px;margin-bottom:40px;">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:15px;">
        <div style="display:flex;align-items:center;gap:16px;">
            <div style="width:48px;height:48px;border-radius:12px;background:rgba(65,224,136,0.15);display:flex;align-items:center;justify-content:center;color:var(--accent);font-size:24px;">
                <i class="fa fa-gift"></i>
            </div>
            <div>
                <h4 style="margin:0 0 4px 0;font-size:16px;">Special Offers & Cashback Perks Available!</h4>
                <p class="text-muted" style="margin:0;font-size:13px;">Activate 10% Netflix & Spotify cashback, +4.5% Yield booster, and $50 welcome card bonuses.</p>
            </div>
        </div>
        <a href="<?= url('dashboard/offers') ?>" class="btn btn--accent btn--sm" style="font-weight:700;padding:10px 20px;">
            Explore All Offers & Perks <i class="fa fa-arrow-right"></i>
        </a>
    </div>
</div>

<!-- APPLY FOR A NEW CARD SECTION -->
<div class="card" id="applySection" style="margin-bottom:40px;border-color:var(--border);">
    <div class="d-flex justify-between align-center mb-2" style="flex-wrap:wrap;gap:10px;">
        <div>
            <h3><i class="fa fa-layer-group" style="color:var(--accent);margin-right:8px;"></i> Order / Apply for a Debit Card</h3>
            <p class="text-muted" style="font-size:13px;margin:0;">Select your preferred card tier below. Virtual cards are issued instantly!</p>
        </div>
    </div>

    <!-- Tier Selection Radio Cards -->
    <form method="POST" action="<?= url('cards/apply') ?>" id="cardApplyForm">
        <?= csrf_field() ?>
        
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;margin:24px 0;">
            
            <!-- Tier 1: Virtual Card -->
            <label class="card-tier-option is-selected" style="border:2px solid var(--accent);background:rgba(65,224,136,0.03);border-radius:12px;padding:20px;cursor:pointer;position:relative;transition:all 0.2s ease;">
                <input type="radio" name="card_tier" value="virtual" checked style="position:absolute;top:16px;right:16px;" onchange="updateTierSelection(this, 0)">
                <span class="badge badge--success" style="font-size:10px;">FREE ($0)</span>
                <h4 style="margin:10px 0 6px;font-size:18px;">Virtual Crypto Card</h4>
                <div style="font-size:22px;font-weight:800;color:var(--accent);margin-bottom:12px;">$0.00 <span style="font-size:12px;color:var(--text-muted);font-weight:400;">/ instant</span></div>
                <ul style="list-style:none;padding:0;margin:0;font-size:12px;line-height:1.8;color:#cbd5e1;">
                    <li><i class="fa fa-check text-accent"></i> 1.5% Unlimited Crypto Cashback</li>
                    <li><i class="fa fa-check text-accent"></i> Instant Activation (10 Seconds)</li>
                    <li><i class="fa fa-check text-accent"></i> $5,000 / Day Spending Limit</li>
                    <li><i class="fa fa-check text-accent"></i> Apple Pay & Google Pay ready</li>
                </ul>
            </label>

            <!-- Tier 2: Metal Card -->
            <label class="card-tier-option" style="border:2px solid var(--border);border-radius:12px;padding:20px;cursor:pointer;position:relative;transition:all 0.2s ease;">
                <input type="radio" name="card_tier" value="metal" style="position:absolute;top:16px;right:16px;" onchange="updateTierSelection(this, 25)">
                <span class="badge" style="background:#64b5f6;color:#0a0e17;font-size:10px;font-weight:800;">POPULAR</span>
                <h4 style="margin:10px 0 6px;font-size:18px;">Titanium Metal Card</h4>
                <div style="font-size:22px;font-weight:800;color:#64b5f6;margin-bottom:12px;">$25.00 <span style="font-size:12px;color:var(--text-muted);font-weight:400;">issuance fee</span></div>
                <ul style="list-style:none;padding:0;margin:0;font-size:12px;line-height:1.8;color:#cbd5e1;">
                    <li><i class="fa fa-check text-accent"></i> 3.0% Cashback on All Purchases</li>
                    <li><i class="fa fa-check text-accent"></i> Heavy Matte Titanium Metal Card</li>
                    <li><i class="fa fa-check text-accent"></i> $25,000 / Day Spending Limit</li>
                    <li><i class="fa fa-check text-accent"></i> 10% Netflix & Spotify Cashback</li>
                    <li><i class="fa fa-check text-accent"></i> $1,000 / mo Free ATM Withdrawals</li>
                </ul>
            </label>

            <!-- Tier 3: VIP Black Diamond Card -->
            <label class="card-tier-option" style="border:2px solid var(--border);border-radius:12px;padding:20px;cursor:pointer;position:relative;transition:all 0.2s ease;">
                <input type="radio" name="card_tier" value="vip" style="position:absolute;top:16px;right:16px;" onchange="updateTierSelection(this, 100)">
                <span class="badge" style="background:linear-gradient(135deg, #d4af37, #f5d77a);color:#0a0e17;font-size:10px;font-weight:800;">VIP PRESTIGE</span>
                <h4 style="margin:10px 0 6px;font-size:18px;">VIP Black Diamond Card</h4>
                <div style="font-size:22px;font-weight:800;color:#d4af37;margin-bottom:12px;">$100.00 <span style="font-size:12px;color:var(--text-muted);font-weight:400;">issuance fee</span></div>
                <ul style="list-style:none;padding:0;margin:0;font-size:12px;line-height:1.8;color:#cbd5e1;">
                    <li><i class="fa fa-check text-accent"></i> 5.0% Unlimited Crypto Cashback</li>
                    <li><i class="fa fa-check text-accent"></i> Custom Engraved Black Diamond Finish</li>
                    <li><i class="fa fa-check text-accent"></i> $100,000 / Day Spending Limit</li>
                    <li><i class="fa fa-check text-accent"></i> Priority Pass Worldwide Airport Lounges</li>
                    <li><i class="fa fa-check text-accent"></i> 0% FX Fees + 4.5% APY Staking Boost</li>
                </ul>
            </label>

        </div>

        <!-- Customization Fields -->
        <div style="background:var(--bg-alt);border:1px solid var(--border);border-radius:12px;padding:20px;display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;align-items:end;">
            <div class="form-group" style="margin:0;">
                <label>Cardholder Name (Embossed)</label>
                <input type="text" name="cardholder_name" class="form-control" value="<?= e($user['fullname'] ?: $user['username']) ?>" placeholder="e.g. JOHN DOE" required>
            </div>
            <div class="form-group" style="margin:0;">
                <label>Card Payment Network</label>
                <select name="card_type" class="form-control">
                    <option value="visa">Visa Network (Worldwide)</option>
                    <option value="mastercard">Mastercard (Worldwide)</option>
                </select>
            </div>
            <div class="form-group" style="margin:0;">
                <label>Billing Currency</label>
                <select name="currency" class="form-control">
                    <option value="USD">USD ($ - United States Dollar)</option>
                    <option value="EUR">EUR (€ - Euro)</option>
                    <option value="GBP">GBP (£ - British Pound)</option>
                </select>
            </div>
            <div class="form-group" style="margin:0;">
                <label>Set 4-Digit Security PIN</label>
                <input type="password" maxlength="4" name="pin" class="form-control" placeholder="4 digits (e.g. 5829)" value="5829" required>
            </div>
            <div style="grid-column:1/-1;">
                <label>Physical Delivery Address (for Metal & Diamond Cards)</label>
                <input type="text" name="billing_address" class="form-control" placeholder="Street Address, City, Postal Code, Country (Optional for Virtual cards)">
            </div>
        </div>

        <div style="margin-top:20px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:15px;">
            <div id="feeNotice" style="font-size:13px;color:var(--text-muted);">
                Issuance Fee: <strong style="color:var(--accent);">$0.00</strong> (Virtual card is 100% Free)
            </div>
            <button type="submit" class="btn btn--accent" style="padding:12px 36px;font-weight:700;">
                <i class="fa fa-check-circle"></i> Complete Application & Issue Card
            </button>
        </div>

    </form>
</div>

<!-- Dynamic Javascript Helper Functions -->
<script>
function toggleCardFlip(cardId) {
    const wrapper = document.getElementById('cardFlip_' + cardId);
    if (!wrapper) return;
    const inner = wrapper.querySelector('.flip-card-inner');
    if (inner.style.transform === 'rotateY(180deg)') {
        inner.style.transform = 'rotateY(0deg)';
    } else {
        inner.style.transform = 'rotateY(180deg)';
    }
}

function toggleFundModal(id) {
    const el = document.getElementById('fundCard_' + id);
    if (el) el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'block' : 'none';
}

function toggleWithdrawModal(id) {
    const el = document.getElementById('withdrawCard_' + id);
    if (el) el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'block' : 'none';
}

function toggleSettingsModal(id) {
    const el = document.getElementById('settingsCard_' + id);
    if (el) el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'block' : 'none';
}

function toggleSimulatorModal(id) {
    const el = document.getElementById('simulatorCard_' + id);
    if (el) el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'block' : 'none';
}

function updateSimAmount(select, id) {
    const opt = select.options[select.selectedIndex];
    const amount = opt.getAttribute('data-amount');
    const input = document.getElementById('simAmount_' + id);
    if (input && amount) {
        input.value = amount;
    }
}

function updateTierSelection(radio, fee) {
    document.querySelectorAll('.card-tier-option').forEach(el => {
        el.style.borderColor = 'var(--border)';
        el.style.background = 'transparent';
    });
    const parent = radio.closest('.card-tier-option');
    if (parent) {
        parent.style.borderColor = fee === 0 ? 'var(--accent)' : (fee === 25 ? '#64b5f6' : '#d4af37');
        parent.style.background = fee === 0 ? 'rgba(65,224,136,0.03)' : (fee === 25 ? 'rgba(100,181,246,0.04)' : 'rgba(212,175,55,0.04)');
    }
    const notice = document.getElementById('feeNotice');
    if (notice) {
        if (fee === 0) {
            notice.innerHTML = 'Issuance Fee: <strong style="color:var(--accent);">$0.00</strong> (Virtual card is 100% Free)';
        } else {
            notice.innerHTML = 'Issuance Fee: <strong style="color:#d4af37;">$' + fee.toFixed(2) + '</strong> (Deducted from account balance)';
        }
    }
}
</script>
