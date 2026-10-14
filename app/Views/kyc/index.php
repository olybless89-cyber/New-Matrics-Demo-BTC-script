<div class="dashboard__header">
    <h2>KYC Verification</h2>
    <p class="text-muted" style="font-size:13px;">Verify your identity to unlock full account features</p>
</div>

<?php if ($kyc): ?>
<div class="card mb-3">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <div>
            <h3 style="margin:0;">Verification Status</h3>
            <p class="text-muted" style="font-size:13px;margin:4px 0 0;">Submitted: <?= date('M j, Y g:i A', strtotime($kyc['created_at'])) ?></p>
        </div>
        <span class="badge badge--<?= $kyc['status'] === 'approved' ? 'success' : ($kyc['status'] === 'pending' ? 'warning' : 'danger') ?>" style="font-size:14px;padding:8px 16px;">
            <?= ucfirst($kyc['status']) ?>
        </span>
    </div>
    <?php if ($kyc['status'] === 'rejected' && $kyc['admin_note']): ?>
    <div style="margin-top:12px;padding:12px;background:rgba(231,76,60,0.1);border-radius:8px;color:#e74c3c;font-size:13px;">
        <strong>Reason:</strong> <?= e($kyc['admin_note']) ?>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if (!$kyc || $kyc['status'] !== 'pending'): ?>
<div class="card">
    <h3>Submit Verification</h3>
    <p class="text-muted" style="font-size:13px;margin-bottom:20px;">Please provide accurate information. Upload clear photos of your documents.</p>
    <form method="POST" action="<?= url('dashboard/kyc') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="form-group">
            <label>Full Legal Name *</label>
            <input type="text" name="full_name" class="form-control" placeholder="As shown on your ID" value="<?= e($kyc['full_name'] ?? '') ?>" required>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;" class="form-row">
            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="date_of_birth" class="form-control" value="<?= e($kyc['date_of_birth'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>ID Type *</label>
                <select name="id_type" class="form-control" required>
                    <option value="">Select ID type</option>
                    <option value="passport" <?= ($kyc['id_type'] ?? '') === 'passport' ? 'selected' : '' ?>>Passport</option>
                    <option value="driver_license" <?= ($kyc['id_type'] ?? '') === 'driver_license' ? 'selected' : '' ?>>Driver's License</option>
                    <option value="national_id" <?= ($kyc['id_type'] ?? '') === 'national_id' ? 'selected' : '' ?>>National ID Card</option>
                    <option value="voters_card" <?= ($kyc['id_type'] ?? '') === 'voters_card' ? 'selected' : '' ?>>Voter's Card</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>ID Number</label>
            <input type="text" name="id_number" class="form-control" placeholder="ID document number" value="<?= e($kyc['id_number'] ?? '') ?>">
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;" class="form-row">
            <div class="form-group">
                <label>City</label>
                <input type="text" name="city" class="form-control" placeholder="City" value="<?= e($kyc['city'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Country</label>
                <input type="text" name="country" class="form-control" placeholder="Country" value="<?= e($kyc['country'] ?? '') ?>">
            </div>
        </div>
        <div class="form-group">
            <label>Residential Address</label>
            <textarea name="address" class="form-control" rows="2" placeholder="Full address"><?= e($kyc['address'] ?? '') ?></textarea>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;" class="form-row">
            <div class="form-group">
                <label>ID Front (JPG/PNG/PDF, max 5MB)</label>
                <input type="file" name="id_front" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                <?php if (!empty($kyc['id_front'])): ?>
                <a href="<?= asset($kyc['id_front']) ?>" target="_blank" style="font-size:12px;color:var(--accent);">View current</a>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>ID Back (JPG/PNG/PDF, max 5MB)</label>
                <input type="file" name="id_back" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                <?php if (!empty($kyc['id_back'])): ?>
                <a href="<?= asset($kyc['id_back']) ?>" target="_blank" style="font-size:12px;color:var(--accent);">View current</a>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Selfie with ID (JPG/PNG, max 5MB)</label>
                <input type="file" name="selfie" class="form-control" accept=".jpg,.jpeg,.png">
                <?php if (!empty($kyc['selfie'])): ?>
                <a href="<?= asset($kyc['selfie']) ?>" target="_blank" style="font-size:12px;color:var(--accent);">View current</a>
                <?php endif; ?>
            </div>
        </div>
        <button type="submit" class="btn btn--accent btn--block">Submit Verification</button>
    </form>
</div>
<?php else: ?>
<div class="card text-center" style="padding:40px;">
    <div style="font-size:48px;margin-bottom:16px;">⏳</div>
    <h3>Verification Under Review</h3>
    <p class="text-muted">Your KYC verification is being reviewed by our team. You will be notified once it's approved.</p>
</div>
<?php endif; ?>
