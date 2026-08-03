<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <h4 class="text-white font-heading m-0">Audit Logs & Activity Records</h4>
    <p class="text-secondary small">Rekam jejak seluruh aktivitas keamanan, autentikasi, dan perubahan data sistem</p>
</div>

<div class="saas-card p-4">
    <div class="table-responsive">
        <table class="table table-dark-saas datatable-saas align-middle">
            <thead>
                <tr>
                    <th>Waktu (Timestamp)</th>
                    <th>User & Role</th>
                    <th>Aksi (Action)</th>
                    <th>Rincian Deskripsi</th>
                    <th>IP Address & User Agent</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $l): ?>
                    <tr>
                        <td class="font-monospace small text-white">
                            <i class="fa-solid fa-clock me-1 text-danger"></i> <?= date('d/m/Y H:i:s', strtotime($l['created_at'])) ?>
                        </td>
                        <td>
                            <?php if ($l['full_name']): ?>
                                <div class="fw-semibold text-white"><?= esc($l['full_name']) ?></div>
                                <small class="text-secondary font-monospace">@<?= esc($l['username']) ?> (<?= esc($l['role_name']) ?>)</small>
                            <?php else: ?>
                                <span class="badge bg-secondary font-monospace">GUEST / ANONYMOUS</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-dark border border-danger text-danger font-monospace px-2.5 py-1">
                                <?= esc($l['action']) ?>
                            </span>
                        </td>
                        <td class="small text-secondary"><?= esc($l['description']) ?></td>
                        <td class="font-monospace small text-secondary">
                            <div><?= esc($l['ip_address']) ?></div>
                            <small class="text-truncate d-block text-dim" style="max-width: 150px;"><?= esc($l['user_agent']) ?></small>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
