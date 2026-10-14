<div class="dashboard__header">
    <div>
        <h2>New Support Ticket</h2>
        <p class="text-muted" style="font-size:13px;">Describe your issue and we'll help you</p>
    </div>
    <a href="<?= url('dashboard/support') ?>" class="btn btn--outline btn--sm">← Back</a>
</div>

<div class="card">
    <form method="POST" action="<?= url('dashboard/support/create') ?>">
        <?= csrf_field() ?>
        <div class="form-group">
            <label>Subject *</label>
            <input type="text" name="subject" class="form-control" placeholder="Brief summary of your issue" required>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;" class="form-row">
            <div class="form-group">
                <label>Category</label>
                <select name="category" class="form-control">
                    <option value="general">General</option>
                    <option value="deposit">Deposit</option>
                    <option value="withdrawal">Withdrawal</option>
                    <option value="investment">Investment</option>
                    <option value="account">Account</option>
                    <option value="technical">Technical</option>
                </select>
            </div>
            <div class="form-group">
                <label>Priority</label>
                <select name="priority" class="form-control">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Message *</label>
            <textarea name="message" class="form-control" rows="6" placeholder="Describe your issue in detail..." required></textarea>
        </div>
        <button type="submit" class="btn btn--accent btn--block">Submit Ticket</button>
    </form>
</div>
