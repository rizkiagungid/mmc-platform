<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<!-- Overview Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="saas-card saas-card-glow p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary small fw-medium">TOTAL ANGGOTA</span>
                <div class="rounded-3 bg-danger bg-opacity-10 p-2 text-danger">
                    <i class="fa-solid fa-users fs-5"></i>
                </div>
            </div>
            <h3 class="text-white font-heading fw-bold m-0"><?= $totalMembers ?></h3>
            <small class="text-secondary">Anggota terdaftar</small>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="saas-card saas-card-glow p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary small fw-medium">PERTEMUAN/WORKSHOP</span>
                <div class="rounded-3 bg-info bg-opacity-10 p-2 text-info">
                    <i class="fa-solid fa-calendar-days fs-5"></i>
                </div>
            </div>
            <h3 class="text-white font-heading fw-bold m-0"><?= $totalMeetings ?></h3>
            <small class="text-secondary">Total sesi pertemuan</small>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="saas-card saas-card-glow p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary small fw-medium">TUGAS & PROYEK</span>
                <div class="rounded-3 bg-warning bg-opacity-10 p-2 text-warning">
                    <i class="fa-solid fa-list-check fs-5"></i>
                </div>
            </div>
            <h3 class="text-white font-heading fw-bold m-0"><?= $totalTasks ?></h3>
            <small class="text-secondary">Tugas aktif & dikerjakan</small>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="saas-card saas-card-glow p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary small fw-medium">STATUS PRESENSI</span>
                <div class="rounded-3 bg-success bg-opacity-10 p-2 text-success">
                    <i class="fa-solid fa-qrcode fs-5"></i>
                </div>
            </div>
            <h3 class="text-white font-heading fw-bold m-0"><?= $activeMeeting ? 'AKTIF' : 'NON-AKTIF' ?></h3>
            <small class="text-secondary"><?= $activeMeeting ? 'Presensi QR terbuka' : 'Tidak ada pertemuan aktif' ?></small>
        </div>
    </div>
</div>

<!-- Active Meeting Action Card -->
<?php if ($activeMeeting): ?>
<div class="saas-card p-4 border border-danger border-opacity-50 mb-4 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(220, 38, 38, 0.15), rgba(18, 18, 24, 1));">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
            <span class="badge bg-danger mb-2 font-monospace"><i class="fa-solid fa-tower-broadcast me-1"></i> SESI AKTIF SAAT INI</span>
            <h4 class="text-white font-heading m-0"><?= esc($activeMeeting['title']) ?></h4>
            <div class="text-secondary small mt-2">
                <span class="me-3"><i class="fa-solid fa-location-dot me-1 text-danger"></i> <?= esc($activeMeeting['location']) ?></span>
                <span class="me-3"><i class="fa-solid fa-clock me-1 text-danger"></i> <?= esc($activeMeeting['start_time']) ?> - <?= esc($activeMeeting['end_time']) ?> WIB</span>
                <span><i class="fa-solid fa-key me-1 text-warning"></i> PIN: <strong><?= esc($activeMeeting['pin_code']) ?></strong></span>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= base_url('admin/meetings/qr/' . $activeMeeting['id']) ?>" class="btn btn-red px-3">
                <i class="fa-solid fa-expand me-1"></i> Tampilkan QR Poster
            </a>
            <a href="<?= base_url('admin/attendance/scan-member') ?>" class="btn btn-outline-light px-3">
                <i class="fa-solid fa-camera me-1"></i> Operator Scan Member QR
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Main Row: Recent Meetings & Tasks -->
<div class="row g-4">
    <div class="col-lg-7">
        <div class="saas-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="text-white font-heading m-0"><i class="fa-solid fa-calendar-day text-danger me-2"></i> Jadwal Pertemuan Terbaru</h5>
                <a href="<?= base_url('admin/meetings') ?>" class="small text-danger">Lihat Semua</a>
            </div>

            <div class="table-responsive">
                <table class="table table-dark-saas align-middle">
                    <thead>
                        <tr>
                            <th>Judul Pertemuan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentMeetings as $m): ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold text-white"><?= esc($m['title']) ?></div>
                                    <small class="text-secondary"><?= esc($m['location']) ?></small>
                                </td>
                                <td class="small text-secondary"><?= date('d/m/Y', strtotime($m['meeting_date'])) ?></td>
                                <td>
                                    <?php if ($m['status'] === 'active'): ?>
                                        <span class="badge bg-danger">AKTIF</span>
                                    <?php else: ?>
                                        <span class="badge bg-dark border border-secondary text-secondary"><?= strtoupper($m['status']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <?php if ($m['status'] !== 'active'): ?>
                                        <a href="<?= base_url('admin/meetings/activate/' . $m['id']) ?>" class="btn btn-sm btn-outline-danger">Aktifkan</a>
                                    <?php else: ?>
                                        <a href="<?= base_url('admin/meetings/qr/' . $m['id']) ?>" class="btn btn-sm btn-red">Poster QR</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="saas-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="text-white font-heading m-0"><i class="fa-solid fa-list-check text-danger me-2"></i> Tugas Terkini</h5>
                <a href="<?= base_url('admin/tasks') ?>" class="small text-danger">Kelola Tugas</a>
            </div>

            <div class="d-flex flex-column gap-3">
                <?php foreach ($recentTasks as $t): ?>
                    <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge" style="background-color: <?= $t['priority_color'] ?>;"><?= esc($t['priority_name']) ?></span>
                        </div>
                        <a href="<?= base_url('admin/tasks/detail/' . $t['id']) ?>" class="fw-semibold text-white font-heading d-block mb-2"><?= esc($t['title']) ?></a>
                        <div class="d-flex align-items-center justify-content-between text-secondary small">
                            <span><i class="fa-solid fa-user-group me-1"></i> <?= count($t['assignees']) ?> Member</span>
                            <span class="text-danger"><i class="fa-solid fa-hourglass-half me-1"></i> <?= $t['deadline'] ? date('d M', strtotime($t['deadline'])) : 'No Deadline' ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
