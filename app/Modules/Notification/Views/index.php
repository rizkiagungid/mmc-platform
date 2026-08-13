<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Pusat Notifikasi System</h4>
        <p class="text-secondary small m-0">Informasi terbaru mengenai penugasan, presensi, kritik & saran, serta pembaruan profil Anda</p>
    </div>

    <div class="d-flex align-items-center gap-2 flex-wrap">
        <button type="button" class="btn btn-sm btn-outline-warning animate-pulse" onclick="window.requestNativeNotificationPermission ? window.requestNativeNotificationPermission() : Notification.requestPermission()">
            <i class="fa-solid fa-bell me-1"></i> Aktifkan Notifikasi
        </button>
        <?php if ($unreadCount > 0): ?>
            <a href="<?= base_url('notifications/mark-all-read') ?>" class="btn btn-sm btn-outline-info">
                <i class="fa-solid fa-check-double me-1"></i> Tandai Semua Dibaca
            </a>
        <?php endif; ?>
        <a href="<?= base_url('notifications/clear-all') ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus SELURUH riwayat notifikasi Anda?')" class="btn btn-sm btn-saas-dark text-danger">
            <i class="fa-solid fa-trash-can me-1"></i> Bersihkan Semua
        </a>
    </div>
</div>

<!-- Category Filters -->
<div class="saas-card p-3 mb-4">
    <div class="d-flex align-items-center gap-2 overflow-x-auto py-1">
        <a href="<?= base_url('notifications?type=all') ?>" class="btn btn-sm <?= ($filterType === 'all') ? 'btn-red' : 'btn-saas-dark' ?> text-nowrap">
            <i class="fa-solid fa-bell me-1"></i> Semua Notifikasi
        </a>
        <a href="<?= base_url('notifications?type=task') ?>" class="btn btn-sm <?= ($filterType === 'task') ? 'btn-warning text-dark fw-bold' : 'btn-saas-dark' ?> text-nowrap">
            <i class="fa-solid fa-list-check me-1"></i> Task & Tugas
        </a>
        <a href="<?= base_url('notifications?type=profile') ?>" class="btn btn-sm <?= ($filterType === 'profile') ? 'btn-info text-dark fw-bold' : 'btn-saas-dark' ?> text-nowrap">
            <i class="fa-solid fa-user-gear me-1"></i> Perubahan Profil
        </a>
        <a href="<?= base_url('notifications?type=feedback') ?>" class="btn btn-sm <?= ($filterType === 'feedback') ? 'btn-danger' : 'btn-saas-dark' ?> text-nowrap">
            <i class="fa-solid fa-comments me-1"></i> Kritik & Saran
        </a>
        <a href="<?= base_url('notifications?type=information') ?>" class="btn btn-sm <?= ($filterType === 'information') ? 'btn-danger' : 'btn-saas-dark' ?> text-nowrap">
            <i class="fa-solid fa-bullhorn me-1"></i> Informasi MMC
        </a>
        <a href="<?= base_url('notifications?type=attendance') ?>" class="btn btn-sm <?= ($filterType === 'attendance') ? 'btn-success text-dark fw-bold' : 'btn-saas-dark' ?> text-nowrap">
            <i class="fa-solid fa-qrcode me-1"></i> Presensi & Absen
        </a>
    </div>
</div>

<!-- Notifications List -->
<div class="saas-card p-4">
    <?php if (empty($notifications)): ?>
        <div class="text-center py-5 text-secondary">
            <i class="fa-solid fa-bell-slash display-1 mb-3 text-secondary opacity-50"></i>
            <h5 class="text-white font-heading">Tidak Ada Notifikasi</h5>
            <p class="small mb-0">Belum ada pembaruan atau notifikasi baru untuk kategori ini.</p>
        </div>
    <?php else: ?>
        <div class="d-flex flex-column gap-3">
            <?php foreach ($notifications as $n): ?>
                <?php
                    $bgClass   = empty($n['is_read']) ? 'bg-dark border-danger border-opacity-50' : 'bg-black border-secondary border-opacity-25';
                    $iconClass = 'fa-solid fa-bell text-secondary';
                    $badgeClass = 'bg-secondary';
                    $categoryName = 'Umum';

                    $isTask = in_array($n['type'], ['task', 'task_eval', 'mention']) || str_contains(strtolower($n['title']), 'tugas');

                    if ($isTask) {
                        $iconClass = 'fa-solid fa-list-check text-warning';
                        $badgeClass = 'bg-warning text-dark';
                        $categoryName = 'Task';
                    } elseif ($n['type'] === 'information') {
                        $iconClass = 'fa-solid fa-bullhorn text-warning';
                        $badgeClass = 'bg-warning text-dark';
                        $categoryName = 'Informasi';
                    } elseif ($n['type'] === 'profile') {
                        $iconClass = 'fa-solid fa-user-gear text-info';
                        $badgeClass = 'bg-info text-dark';
                        $categoryName = 'Profil';
                    } elseif ($n['type'] === 'feedback') {
                        $iconClass = 'fa-solid fa-comments text-danger';
                        $badgeClass = 'bg-danger text-white';
                        $categoryName = 'Kritik & Saran';
                    } elseif ($n['type'] === 'attendance') {
                        $iconClass = 'fa-solid fa-qrcode text-success';
                        $badgeClass = 'bg-success text-dark';
                        $categoryName = 'Presensi';
                    }
                ?>
                <div class="p-3.5 rounded-3 <?= $bgClass ?> border d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 transition-all">
                    <div class="d-flex align-items-start gap-3 flex-grow-1 cursor-pointer" onclick="window.location.href='<?= base_url('notifications/mark-read/' . $n['id']) ?>'" style="cursor: pointer;">
                        <div class="p-2.5 rounded-circle bg-dark border border-secondary border-opacity-25 flex-shrink-0 mt-1">
                            <i class="<?= $iconClass ?> fs-5"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <span class="badge <?= $badgeClass ?> font-monospace style-tiny"><?= $categoryName ?></span>
                                <h6 class="text-white font-heading m-0 fw-semibold text-hover-danger"><?= esc($n['title']) ?></h6>
                                <?php if (empty($n['is_read'])): ?>
                                    <span class="badge bg-danger animate-pulse style-tiny">BARU</span>
                                <?php endif; ?>
                            </div>
                            <p class="text-secondary small m-0 leading-relaxed"><?= esc($n['message']) ?></p>
                            <small class="text-secondary font-monospace style-tiny d-block mt-1">
                                <i class="fa-solid fa-clock me-1 opacity-75"></i> <?= date('H:i, d M Y', strtotime($n['created_at'])) ?>
                            </small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 ms-auto flex-shrink-0">
                        <?php if (!empty($n['link']) || $isTask): ?>
                            <a href="<?= base_url('notifications/mark-read/' . $n['id']) ?>" class="btn btn-sm btn-outline-danger">
                                <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Buka Detail
                            </a>
                        <?php elseif (empty($n['is_read'])): ?>
                            <a href="<?= base_url('notifications/mark-read/' . $n['id']) ?>" class="btn btn-sm btn-saas-dark text-info">
                                <i class="fa-solid fa-check me-1"></i> Tandai Dibaca
                            </a>
                        <?php endif; ?>

                        <a href="<?= base_url('notifications/delete/' . $n['id']) ?>" class="btn btn-sm btn-saas-dark text-secondary" title="Hapus Notifikasi">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
