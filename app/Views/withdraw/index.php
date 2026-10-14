<div class="dashboard__header">
    <h2>Withdraw Funds</h2>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;" class="dashboard-grid">
    <div class="card">
        <h3>New Withdrawal</h3>
        <p class="text-muted" style="font-size:13px;margin-bottom:20px;">Available Balance: <span style="color:var(--accent);font-weight:700;">$<?= number_format($balance, 2) ?></span></p>
        <form method="POST" action="<?= url('withdraw') ?>">
            <?= csrf_field() ?>
            <div class="form-group">
                <label>Amount (USD)</label>
                <input type="number" step="0.01" min="10" name="amount" class="form-control" placeholder="Enter amount" required>
            </div>
            <div class="form-group">
                <label>Payment Method</label>
                <select name="payment_method" class="form-control" required>
                    <option value="">Select method</option>
                    <option value="Bitcoin">Bitcoin (BTC)</option>
                    <option value="Ethereum">Ethereum (ETH)</option>
                    <option value="USDT">USDT (TRC20)</option>
                    <option value="USDC">USDC</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
            </div>
            <div class="form-group">
                <label>Wallet Address / Account Details</label>
                <textarea name="wallet_address" class="form-control" rows="3" placeholder="Enter your wallet address or bank details" required></textarea>
            </div>
            <button type="submit" class="btn btn--primary btn--block">Request Withdrawal</button>
        </form>
        <div class="card" style="margin-top:20px;background:var(--bg-alt);">
            <p class="text-muted" style="font-size:12px;"><i class="fa fa-info-circle"></i> Minimum withdrawal: $10. Withdrawals are processed within 24 hours.</p>
        </div>
    </div>

    <div class="card">
        <h3>Withdrawal History</h3>
        <?php if (empty($withdrawals)): ?>
        <p class="text-muted text-center" style="padding:30px 0;">No withdrawals yet</p>
        <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead><tr><th>Amount</th><th>Method</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                    <?php foreach ($withdrawals as $w): ?>
                    <tr>
                        <td>$<?= number_format($w['amount'], 2) ?></td>
                        <td><?= e($w['payment_method']) ?></td>
                        <td><span class="badge badge--<?= $w['status'] === 'approved' ? 'success' : ($w['status'] === 'pending' || $w['status'] === 'processing' ? 'warning' : 'danger') ?>"><?= ucfirst($w['status']) ?></span></td>
                        <td style="font-size:12px;"><?= date('M j, Y', strtotime($w['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
