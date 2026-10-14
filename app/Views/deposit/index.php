<div class="dashboard__header">
    <h2>Deposit Funds</h2>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;" class="dashboard-grid">
    <div class="card">
        <h3>New Deposit</h3>
        <p class="text-muted" style="font-size:13px;margin-bottom:20px;">Available Balance: <span style="color:var(--accent);font-weight:700;">$<?= number_format($balance, 2) ?></span></p>
        <form method="POST" action="<?= url('deposit') ?>">
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
                    <option value="Debit Card">Debit Card</option>
                </select>
            </div>
            <div class="form-group">
                <label>Transaction Hash / Reference</label>
                <input type="text" name="txn_hash" class="form-control" placeholder="Enter txn hash (for crypto)">
            </div>
            <button type="submit" class="btn btn--accent btn--block">Submit Deposit</button>
        </form>
        <div class="card" style="margin-top:20px;background:var(--bg-alt);">
            <p class="text-muted" style="font-size:12px;"><i class="fa fa-info-circle"></i> Deposits are approved within 24 hours. Crypto deposits require 3 network confirmations.</p>
        </div>
    </div>

    <div class="card">
        <h3>Deposit History</h3>
        <?php if (empty($deposits)): ?>
        <p class="text-muted text-center" style="padding:30px 0;">No deposits yet</p>
        <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead><tr><th>Amount</th><th>Method</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                    <?php foreach ($deposits as $d): ?>
                    <tr>
                        <td>$<?= number_format($d['amount'], 2) ?></td>
                        <td><?= e($d['payment_method']) ?></td>
                        <td><span class="badge badge--<?= $d['status'] === 'approved' ? 'success' : ($d['status'] === 'pending' ? 'warning' : 'danger') ?>"><?= ucfirst($d['status']) ?></span></td>
                        <td style="font-size:12px;"><?= date('M j, Y', strtotime($d['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
