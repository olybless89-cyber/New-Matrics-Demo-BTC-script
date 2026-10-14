<div class="dashboard__header">
    <h2>KYC Verifications</h2>
</div>

<div class="card">
    <?php if (empty($kycList)): ?>
    <p class="text-muted text-center" style="padding:30px 0;">No KYC submissions yet.</p>
    <?php else: ?>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>User</th><th>Full Name</th><th>ID Type</th><th>Status</th><th>Documents</th><th>Submitted</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($kycList as $k): ?>
                <tr>
                    <td>
                        <div style="font-weight:600;"><?= e($k['username']) ?></div>
                        <div style="font-size:11px;color:var(--text-muted);"><?= e($k['email']) ?></div>
                    </td>
                    <td><?= e($k['full_name']) ?></td>
                    <td><?= ucfirst(str_replace('_', ' ', $k['id_type'])) ?></td>
                    <td><span class="badge badge--<?= $k['status'] === 'approved' ? 'success' : ($k['status'] === 'pending' ? 'warning' : 'danger') ?>"><?= ucfirst($k['status']) ?></span></td>
                    <td>
                        <?php if ($k['id_front']): ?><a href="<?= asset($k['id_front']) ?>" target="_blank" class="btn btn--outline btn--sm" style="margin:2px;">ID Front</a><?php endif; ?>
                        <?php if ($k['id_back']): ?><a href="<?= asset($k['id_back']) ?>" target="_blank" class="btn btn--outline btn--sm" style="margin:2px;">ID Back</a><?php endif; ?>
                        <?php if ($k['selfie']): ?><a href="<?= asset($k['selfie']) ?>" target="_blank" class="btn btn--outline btn--sm" style="margin:2px;">Selfie</a><?php endif; ?>
                    </td>
                    <td style="font-size:12px;"><?= date('M j, Y', strtotime($k['created_at'])) ?></td>
                    <td>
                        <?php if ($k['status'] === 'pending'): ?>
                        <form method="POST" action="<?= url('admin/kyc/' . $k['id'] . '/approve') ?>" style="display:inline;">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn--accent btn--sm" style="margin:2px;">Approve</button>
                        </form>
                        <form method="POST" action="<?= url('admin/kyc/' . $k['id'] . '/reject') ?>" style="display:inline;" onsubmit="var n=prompt('Reason for rejection:');if(n){this.admin_note.value=n;return true;}return false;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="admin_note" value="">
                            <button type="submit" class="btn btn--sm" style="background:#e74c3c;color:#fff;margin:2px;">Reject</button>
                        </form>
                        <?php else: ?>
                        <span class="text-muted" style="font-size:12px;">Reviewed</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
