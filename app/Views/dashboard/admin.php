<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<?php if (!empty($todayBirthdays)): ?>
    <!-- Today Birthday Celebrants Banner -->
    <div class="saas-card p-3 p-md-4 mb-4 border border-warning border-opacity-50 bg-body-tertiary position-relative overflow-hidden shadow-lg">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
            <div class="d-flex align-items-center gap-2">
                <span class="fs-3">🎂</span>
                <div>
                    <h5 class="text-body font-heading m-0 fw-bold d-flex align-items-center gap-2">
                        Selamat Ulang Tahun Hari Ini! 🎉
                    </h5>
                    <small class="text-secondary style-tiny">Mari berikan ucapan hangat dan doa terbaik untuk anggota klub yang merayakan ulang tahun hari ini!</small>
                </div>
            </div>
            <a href="<?= base_url('feed') ?>" class="btn btn-sm btn-warning text-dark font-weight-bold rounded-pill style-tiny px-3">
                <i class="fa-solid fa-paper-plane me-1"></i> Tulis Ucapan di Beranda MM
            </a>
        </div>

        <div class="row g-3">
            <?php foreach ($todayBirthdays as $bUser): ?>
                <?php
                    $bAge = '';
                    if (!empty($bUser['birth_date'])) {
                        $bYears = date_diff(date_create($bUser['birth_date']), date_create('today'))->y;
                        if ($bYears > 0) $bAge = " ({$bYears} Thn)";
                    }
                ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="p-3 rounded-3 bg-body border border-warning border-opacity-25 d-flex align-items-center justify-content-between gap-3 shadow-sm">
                        <div class="d-flex align-items-center gap-3 overflow-hidden">
                            <?php if (!empty($bUser['avatar'])): ?>
                                <img src="<?= base_url($bUser['avatar']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-warning shadow-sm" style="width: 44px; height: 44px;">
                            <?php else: ?>
                                <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center fw-bold fs-5 shadow-sm" style="width: 44px; height: 44px;">
                                    <?= strtoupper(substr($bUser['full_name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>

                            <div class="text-truncate">
                                <div class="d-flex align-items-center gap-1">
                                    <strong class="text-body style-tiny fw-bold text-truncate"><?= esc($bUser['full_name']) ?></strong>
                                    <?php if (in_array(strtolower((string)$bUser['role_slug']), ['superadmin', 'pembina', 'bph'])): ?>
                                        <i class="fa-solid fa-circle-check text-primary style-tiny"></i>
                                    <?php endif; ?>
                                </div>
                                <small class="text-warning font-monospace style-tiny d-block fw-semibold" style="font-size: 0.7rem;">
                                    🥳 Ulang Tahun<?= $bAge ?>
                                </small>
                                <small class="text-secondary style-tiny opacity-75 d-block text-truncate"><?= esc($bUser['class_dept'] ?: $bUser['role_name']) ?></small>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-1 flex-shrink-0">
                            <a href="<?= base_url('inbox') ?>" class="btn btn-sm btn-saas-dark text-warning border border-warning border-opacity-25 rounded-circle p-1.5" title="Kirim Chat Pesan">
                                <i class="fa-solid fa-comments style-tiny"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

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
            <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                <span class="badge bg-danger style-tiny"><i class="fa-solid fa-tower-broadcast me-1"></i> SESI AKTIF SAAT INI</span>
                <?php if (session()->get('role_slug') !== 'superadmin'): ?>
                    <?php if (!empty($myActiveAttendance)): ?>
                        <?php
                            $attMethod = strtoupper((string)$myActiveAttendance['method']);
                            if (strpos($attMethod, 'QR') !== false) $attMethod = 'QR';
                            elseif (strpos($attMethod, 'PIN') !== false) $attMethod = 'PIN';
                            elseif (strpos($attMethod, 'MANUAL') !== false) $attMethod = 'Manual';
                        ?>
                        <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 px-2.5 py-1.5 style-tiny text-wrap text-break mw-100 font-monospace lh-base">
                            <i class="fa-solid fa-circle-check me-1"></i> Anda Sudah Absen (via <?= $attMethod ?>)
                        </span>
                    <?php else: ?>
                        <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-50 px-2.5 py-1.5 style-tiny text-wrap text-break mw-100 font-monospace lh-base animate-pulse">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> Anda Belum Absen pada Sesi Aktif Ini!
                        </span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <h4 class="text-white font-heading m-0"><?= esc($activeMeeting['title']) ?></h4>
            <div class="text-secondary small mt-2">
                <span class="me-3"><i class="fa-solid fa-location-dot me-1 text-danger"></i> <?= esc($activeMeeting['location']) ?></span>
                <span class="me-3"><i class="fa-solid fa-clock me-1 text-danger"></i> <?= esc($activeMeeting['start_time']) ?> - <?= esc($activeMeeting['end_time']) ?> WIB</span>
                <span><i class="fa-solid fa-key me-1 text-warning"></i> PIN: <strong><?= esc($activeMeeting['pin_code']) ?></strong></span>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <?php if (session()->get('role_slug') !== 'superadmin'): ?>
                <?php if (empty($myActiveAttendance)): ?>
                    <a href="<?= base_url('attendance/scan') ?>" class="btn btn-warning px-3 fw-bold shadow-sm">
                        <i class="fa-solid fa-qrcode me-1"></i> Absen Saya Sekarang (QR / PIN)
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('attendance/scan') ?>" class="btn btn-outline-success px-3 fw-semibold">
                        <i class="fa-solid fa-circle-check me-1"></i> Sudah Absen
                    </a>
                <?php endif; ?>
            <?php endif; ?>
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

<!-- AI Performance & Attendance Summary Card (Excluding Superadmin) -->
<?php if (session()->get('role_slug') !== 'superadmin'): ?>
<div class="saas-card p-3 p-md-4 mb-4 border border-secondary border-opacity-25 position-relative overflow-hidden shadow-lg">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle bg-danger bg-opacity-10 p-2 text-danger">
                <i class="fa-solid fa-robot fs-5"></i>
            </div>
            <div>
                <h6 class="text-white font-heading m-0 fw-bold d-flex align-items-center gap-2">
                    Ringkasan AI Presensi &amp; Partisipasi
                </h6>
                <span class="text-secondary style-tiny">Analisis otomatis kedisiplinan presensi dan performa aktivitas</span>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="badge <?= $aiSummary['badgeClass'] ?> px-2.5 py-1.5 font-monospace style-tiny border">
                <i class="fa-solid fa-certificate me-1"></i> <?= esc($aiSummary['badge']) ?>
            </span>
            <span class="badge bg-black border border-secondary text-secondary font-monospace style-tiny">
                Score: <?= $aiSummary['score'] ?>/100
            </span>
            <button class="btn btn-sm btn-saas-dark border border-secondary border-opacity-25 text-white style-tiny font-monospace px-3 py-1.5" type="button" data-bs-toggle="collapse" data-bs-target="#aiSummaryCollapseAdmin" aria-expanded="false">
                <i class="fa-solid fa-chevron-down me-1"></i> Detail AI
            </button>
        </div>
    </div>

    <!-- Collapsible Body (Default Minimized / Collapsed) -->
    <div class="collapse mt-3 pt-3 border-top border-secondary border-opacity-25" id="aiSummaryCollapseAdmin">
        <div class="row g-4 align-items-center">
            <!-- Attendance & Task Performance Stats -->
            <div class="col-lg-4 border-end border-secondary border-opacity-25">
                <div class="d-flex flex-column gap-2">
                    <!-- Attendance Rate Progress -->
                    <div class="p-3 bg-black rounded-3 border border-secondary border-opacity-25">
                        <div class="d-flex align-items-center justify-content-between text-secondary style-tiny font-monospace mb-1">
                            <span><i class="fa-solid fa-qrcode text-danger me-1"></i> KEHADIRAN PRESENSI</span>
                            <strong class="text-white"><?= $aiSummary['attendanceRate'] ?>%</strong>
                        </div>
                        <div class="progress bg-dark mb-1" style="height: 6px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $aiSummary['attendanceRate'] ?>%;"></div>
                        </div>
                        <div class="text-secondary style-tiny">
                            Hadir <strong class="text-white"><?= $aiSummary['attendedCount'] ?></strong> / <?= $aiSummary['totalMeetings'] ?> sesi pertemuan
                        </div>
                    </div>

                    <!-- Task Completion Rate Progress -->
                    <div class="p-3 bg-black rounded-3 border border-secondary border-opacity-25">
                        <div class="d-flex align-items-center justify-content-between text-secondary style-tiny font-monospace mb-1">
                            <span><i class="fa-solid fa-list-check text-warning me-1"></i> PENGERJAAN TUGAS</span>
                            <strong class="text-white"><?= $aiSummary['taskRate'] ?>%</strong>
                        </div>
                        <div class="progress bg-dark mb-1" style="height: 6px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $aiSummary['taskRate'] ?>%;"></div>
                        </div>
                        <div class="text-secondary style-tiny">
                            Terkirim <strong class="text-white"><?= $aiSummary['completedTasks'] ?></strong> / <?= $aiSummary['totalAssignedTasks'] ?> tugas ditugaskan
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Advice: Pertahankan & Perbaikan -->
            <div class="col-lg-8">
                <div class="row g-3">
                    <!-- Points to Keep Up (Pertahankan) -->
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 bg-success bg-opacity-10 border border-success border-opacity-25 h-100">
                            <div class="d-flex align-items-center gap-2 text-success font-monospace style-tiny fw-bold mb-2">
                                <i class="fa-solid fa-circle-check"></i> PERTAHANKAN (KEEP UP)
                            </div>
                            <ul class="list-unstyled style-tiny text-secondary mb-0 d-flex flex-column gap-1">
                                <?php foreach ($aiSummary['pertahankan'] as $item): ?>
                                    <li class="d-flex align-items-start gap-1">
                                        <i class="fa-solid fa-check text-success mt-1"></i>
                                        <span class="text-white"><?= esc($item) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Points needing Improvement (Perbaikan) -->
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 bg-warning bg-opacity-10 border border-warning border-opacity-25 h-100">
                            <div class="d-flex align-items-center gap-2 text-warning font-monospace style-tiny fw-bold mb-2">
                                <i class="fa-solid fa-wrench"></i> PERBAIKAN &amp; SARAN AI
                            </div>
                            <ul class="list-unstyled style-tiny text-secondary mb-0 d-flex flex-column gap-1">
                                <?php foreach ($aiSummary['perbaikan'] as $item): ?>
                                    <li class="d-flex align-items-start gap-1">
                                        <i class="fa-solid fa-arrow-right text-warning mt-1"></i>
                                        <span class="text-white"><?= esc($item) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-3 p-3 rounded-3 bg-dark border border-secondary border-opacity-25 style-tiny text-secondary d-flex align-items-center gap-2">
                    <i class="fa-solid fa-lightbulb text-warning fs-5 flex-shrink-0"></i>
                    <div>
                        <strong class="text-white">AI Advisor Note:</strong> <?= esc($aiSummary['recommendation']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- BPH Quick Actions: Member QR & Scanner -->
<?php if (session()->get('role_slug') === 'bph'): ?>
<div class="row g-4 mb-4">
    <!-- QR Member Card Widget -->
    <div class="col-lg-4">
        <div class="saas-card p-4 text-center border border-danger border-opacity-50 shadow-lg h-100 d-flex flex-column align-items-center justify-content-center">
            <div class="mb-2">
                <span class="badge bg-danger bg-opacity-10 text-danger font-monospace px-3 py-1 mb-2">
                    <i class="fa-solid fa-id-card me-1"></i> ID Member &amp; Permanent QR
                </span>
            </div>
            <p class="text-secondary small mb-3">Tunjukan QR ini ke BPH saat absensi</p>
            <div class="my-2">
                <canvas id="dashboard-qr-canvas" class="bg-white p-3 rounded-4 shadow-sm"></canvas>
            </div>
            <div class="text-secondary font-monospace small mt-3">
                <div>NIS/NIP: <strong class="text-white"><?= esc($user['nis_nip'] ?: '-') ?></strong></div>
                <div class="mt-1">Version: <span class="badge bg-dark border border-secondary text-secondary">v<?= esc($user['qr_version']) ?></span></div>
            </div>
            <a href="<?= base_url('profile') ?>" class="btn btn-saas-dark w-100 mt-3 btn-sm">
                <i class="fa-solid fa-id-card me-1"></i> Pengaturan QR &amp; Profil
            </a>
        </div>
    </div>

    <!-- Scanner Operator Shortcut -->
    <div class="col-lg-4">
        <div class="saas-card p-4 text-center border border-info border-opacity-25 shadow-lg h-100 d-flex flex-column align-items-center justify-content-center" style="background: linear-gradient(135deg, rgba(6,182,212,0.07), rgba(18,18,24,1));">
            <div class="rounded-circle bg-info bg-opacity-10 p-4 mb-3">
                <i class="fa-solid fa-camera text-info" style="font-size: 2.5rem;"></i>
            </div>
            <h5 class="text-white font-heading mb-2">Scanner Operator</h5>
            <p class="text-secondary small mb-4">Scan QR Member untuk mencatat presensi anggota pada sesi yang sedang aktif</p>
            <?php if ($activeMeeting): ?>
                <div class="badge bg-danger mb-3 font-monospace">
                    <i class="fa-solid fa-tower-broadcast me-1"></i> Sesi Aktif: <?= esc($activeMeeting['title']) ?>
                </div>
                <a href="<?= base_url('admin/attendance/scan-member') ?>" class="btn btn-info px-4 fw-semibold w-100">
                    <i class="fa-solid fa-qrcode me-2"></i> Buka Scanner Operator
                </a>
            <?php else: ?>
                <div class="text-secondary small mb-3">
                    <i class="fa-solid fa-circle-xmark text-danger me-1"></i> Tidak ada sesi pertemuan aktif
                </div>
                <button class="btn btn-saas-dark w-100" disabled>
                    <i class="fa-solid fa-qrcode me-2"></i> Buka Scanner Operator
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Stats BPH -->
    <div class="col-lg-4">
        <div class="saas-card p-4 h-100 d-flex flex-column justify-content-between">
            <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-bolt text-warning me-2"></i> Akses Cepat</h5>
            <div class="d-flex flex-column gap-2">
                <a href="<?= base_url('admin/meetings') ?>" class="btn btn-saas-dark text-start">
                    <i class="fa-solid fa-calendar-days text-info me-2"></i> Kelola Pertemuan
                </a>
                <a href="<?= base_url('admin/attendance') ?>" class="btn btn-saas-dark text-start">
                    <i class="fa-solid fa-clipboard-list text-success me-2"></i> Rekap Presensi
                </a>
                <a href="<?= base_url('admin/users') ?>" class="btn btn-saas-dark text-start">
                    <i class="fa-solid fa-users text-danger me-2"></i> Manajemen Anggota
                </a>
                <a href="<?= base_url('admin/tasks') ?>" class="btn btn-saas-dark text-start">
                    <i class="fa-solid fa-list-check text-warning me-2"></i> Tugas &amp; Proyek
                </a>
            </div>
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

<!-- Informasi Terbaru MMC Section (Admin Dashboard Bottom) -->
<div class="saas-card p-4 mt-4 border border-secondary border-opacity-25 bg-body-tertiary">
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <h5 class="text-body font-heading m-0 d-flex align-items-center gap-2">
            <i class="fa-solid fa-bullhorn text-warning"></i> Informasi Terbaru MMC
        </h5>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('admin/informasi') ?>" class="btn btn-sm btn-saas-dark text-secondary style-tiny rounded-pill">
                <i class="fa-solid fa-sliders me-1"></i> Kelola
            </a>
            <a href="<?= base_url('informasi') ?>" class="small text-danger text-decoration-none">
                Lihat Semua <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

    <?php if (empty($latestInformations)): ?>
        <div class="text-center py-4 text-secondary style-tiny">Belum ada informasi atau pengumuman terbaru saat ini.</div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach (array_slice($latestInformations, 0, 3) as $info): ?>
                <div class="col-12 col-md-4">
                    <div class="p-3 rounded-3 bg-body-secondary border border-secondary border-opacity-25 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 style-tiny font-monospace">
                                    <?= esc($info['category']) ?>
                                </span>
                                <small class="text-secondary style-tiny font-monospace" style="font-size: 0.68rem;">
                                    <?= date('d M Y', strtotime($info['date_time'])) ?>
                                </small>
                            </div>
                            <h6 class="text-body font-heading fw-bold mb-2 style-tiny line-clamp-2"><?= esc($info['title']) ?></h6>
                            <p class="text-secondary style-tiny line-clamp-2 m-0 opacity-75" style="font-size: 0.75rem;"><?= esc($info['description']) ?></p>
                        </div>
                        <div class="pt-2 mt-3 border-top border-secondary border-opacity-10 d-flex align-items-center justify-content-between">
                            <small class="text-secondary style-tiny"><i class="fa-solid fa-user-pen me-1"></i> <?= esc($info['author_name']) ?></small>
                            <a href="<?= base_url('informasi') ?>" class="btn btn-sm btn-saas-dark text-info style-tiny py-0.5 px-2 rounded-pill">Lihat</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if (session()->get('role_slug') === 'bph' && !empty($user['member_uuid'])): ?>
<script>
    $(document).ready(function() {
        const canvasEl = document.getElementById('dashboard-qr-canvas');
        if (canvasEl && typeof QRious !== 'undefined') {
            new QRious({
                element: canvasEl,
                value: '<?= esc($user['member_uuid']) ?>',
                size: 160,
                level: 'H'
            });
        }
    });
</script>
<?php endif; ?>
<?= $this->endSection() ?>
