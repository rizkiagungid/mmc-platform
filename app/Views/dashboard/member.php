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

<!-- Welcome & Active Meeting Row -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="saas-card p-4 h-100 position-relative overflow-hidden hero-welcome-card">
            <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 px-3 py-1 rounded-pill mb-2 font-monospace">MEMBER PORTAL</span>
            <h2 class="text-body font-heading fw-bold mb-2">Halo, <?= esc($user['full_name']) ?>!</h2>
            <p class="text-secondary small mb-4">Selamat datang di Portal Anggota Multimedia Club SMAN 1 Tamansari. Selalu pantau presensi dan pengumpulan tugasmu di sini.</p>

            <?php if ($activeMeeting): ?>
                <div class="p-3 rounded-3 bg-body-secondary border border-danger border-opacity-50 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                            <span class="badge bg-danger style-tiny"><i class="fa-solid fa-signal me-1"></i> PERTEMUAN AKTIF SEKARANG</span>
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
                        </div>
                        <h5 class="text-body font-heading m-0"><?= esc($activeMeeting['title']) ?></h5>
                        <small class="text-secondary"><i class="fa-solid fa-clock me-1 text-danger"></i> <?= esc($activeMeeting['start_time']) ?> - <?= esc($activeMeeting['end_time']) ?> WIB @ <?= esc($activeMeeting['location']) ?></small>
                    </div>
                    <?php if (empty($myActiveAttendance)): ?>
                        <a href="<?= base_url('attendance/scan') ?>" class="btn btn-red px-4 py-2 flex-shrink-0 fw-bold shadow">
                            <i class="fa-solid fa-qrcode me-2"></i> Scan Presensi Sekarang (QR / PIN)
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('attendance/scan') ?>" class="btn btn-outline-success px-4 py-2 flex-shrink-0 fw-semibold">
                            <i class="fa-solid fa-circle-check me-2"></i> Sudah Absen
                        </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="p-3 rounded-3 bg-body-secondary border border-secondary border-opacity-25 text-secondary small">
                    <i class="fa-solid fa-circle-info me-2 text-info"></i> Belum ada sesi pertemuan yang dibuka saat ini.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Member Permanent QR Preview Card -->
    <div class="col-lg-4">
        <div class="saas-card p-4 text-center h-100 border border-secondary border-opacity-25 bg-body-secondary">
            <h6 class="text-body font-heading mb-2">ID Member & Permanent QR</h6>
            <p class="text-secondary small mb-3">Tunjukkan QR ini ke BPH saat absensi.</p>

            <canvas id="member-qr-canvas" class="bg-white p-2 rounded-3 mx-auto mb-3"></canvas>

            <div class="small text-secondary font-monospace">
                <div>NIS/NIP: <strong class="text-body"><?= esc($user['nis_nip'] ?: '-') ?></strong></div>
                <div>Version: <span class="badge bg-body-secondary border border-secondary text-body">v<?= esc($user['qr_version']) ?></span></div>
            </div>

            <a href="<?= base_url('profile') ?>" class="btn btn-sm btn-saas-dark w-100 mt-3">
                <i class="fa-solid fa-id-card me-1"></i> Pengaturan QR & Profil
            </a>
        </div>
    </div>
</div>

<!-- AI Performance & Attendance Summary Card -->
<div class="saas-card p-3 p-md-4 mb-4 border border-secondary border-opacity-25 position-relative overflow-hidden shadow-lg">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle bg-danger bg-opacity-10 p-2 text-danger">
                <i class="fa-solid fa-robot fs-5"></i>
            </div>
            <div>
                <h6 class="text-body font-heading m-0 fw-bold d-flex align-items-center gap-2">
                    Ringkasan AI Presensi &amp; Kinerja Anda
                </h6>
                <span class="text-secondary style-tiny">Analisis otomatis kedisiplinan presensi dan tingkat keaktifan tugas</span>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="badge <?= $aiSummary['badgeClass'] ?> px-2.5 py-1.5 font-monospace style-tiny border">
                <i class="fa-solid fa-certificate me-1"></i> <?= esc($aiSummary['badge']) ?>
            </span>
            <span class="badge bg-body border border-secondary text-body font-monospace style-tiny">
                Score: <?= $aiSummary['score'] ?>/100
            </span>
            <button class="btn btn-sm btn-saas-dark border border-secondary border-opacity-25 text-body style-tiny font-monospace px-3 py-1.5" type="button" data-bs-toggle="collapse" data-bs-target="#aiSummaryCollapseMember" aria-expanded="false">
                <i class="fa-solid fa-chevron-down me-1"></i> Detail AI
            </button>
        </div>
    </div>

    <!-- Collapsible Body (Default Minimized / Collapsed) -->
    <div class="collapse mt-3 pt-3 border-top border-secondary border-opacity-25" id="aiSummaryCollapseMember">
        <div class="row g-4 align-items-center">
            <!-- Attendance & Task Performance Stats -->
            <div class="col-lg-4 border-end border-secondary border-opacity-25">
                <div class="d-flex flex-column gap-2">
                    <!-- Attendance Rate Progress -->
                    <div class="p-3 bg-body-secondary rounded-3 border border-secondary border-opacity-25">
                        <div class="d-flex align-items-center justify-content-between text-secondary style-tiny font-monospace mb-1">
                            <span><i class="fa-solid fa-qrcode text-danger me-1"></i> KEHADIRAN PRESENSI</span>
                            <strong class="text-body"><?= $aiSummary['attendanceRate'] ?>%</strong>
                        </div>
                        <div class="progress bg-dark mb-1" style="height: 6px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $aiSummary['attendanceRate'] ?>%;"></div>
                        </div>
                        <div class="text-secondary style-tiny">
                            Hadir <strong class="text-body"><?= $aiSummary['attendedCount'] ?></strong> / <?= $aiSummary['totalMeetings'] ?> sesi pertemuan
                        </div>
                    </div>

                    <!-- Task Completion Rate Progress -->
                    <div class="p-3 bg-body-secondary rounded-3 border border-secondary border-opacity-25">
                        <div class="d-flex align-items-center justify-content-between text-secondary style-tiny font-monospace mb-1">
                            <span><i class="fa-solid fa-list-check text-warning me-1"></i> PENGERJAAN TUGAS</span>
                            <strong class="text-body"><?= $aiSummary['taskRate'] ?>%</strong>
                        </div>
                        <div class="progress bg-dark mb-1" style="height: 6px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $aiSummary['taskRate'] ?>%;"></div>
                        </div>
                        <div class="text-secondary style-tiny">
                            Terkirim <strong class="text-body"><?= $aiSummary['completedTasks'] ?></strong> / <?= $aiSummary['totalAssignedTasks'] ?> tugas ditugaskan
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
                                        <span class="text-body"><?= esc($item) ?></span>
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
                                        <span class="text-body"><?= esc($item) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-3 p-3 rounded-3 bg-body-secondary border border-secondary border-opacity-25 style-tiny text-secondary d-flex align-items-center gap-2">
                    <i class="fa-solid fa-lightbulb text-warning fs-5 flex-shrink-0"></i>
                    <div>
                        <strong class="text-body">AI Advisor Note:</strong> <?= esc($aiSummary['recommendation']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Member Tasks & Attendance History -->
<div class="row g-4">
    <div class="col-lg-7">
        <div class="saas-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="text-body font-heading m-0"><i class="fa-solid fa-list-check text-danger me-2"></i> Tugas Ditugaskan Kepada Anda</h5>
                <a href="<?= base_url('member/tasks') ?>" class="small text-danger">Lihat Semua Tugas</a>
            </div>

            <?php if (empty($myTasks)): ?>
                <div class="text-center py-4 text-secondary small">Belum ada tugas yang ditugaskan kepada Anda saat ini.</div>
            <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($myTasks as $t): ?>
                        <div class="p-3 rounded-3 bg-body-secondary border border-secondary border-opacity-25">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>
                                    <span class="badge me-1" style="background-color: <?= $t['priority_color'] ?>;"><?= esc($t['priority_name']) ?></span>
                                    <span class="badge" style="background-color: <?= $t['my_status_color'] ?? '#6c757d' ?>;"><?= esc($t['my_status_name'] ?? 'Belum dikerjakan') ?></span>
                                </div>
                                <?php if (!empty($t['is_submitted']) || !empty($t['my_submission'])): ?>
                                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25"><i class="fa-solid fa-circle-check me-1"></i> sudah dikirim</span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25"><i class="fa-solid fa-circle-xmark me-1"></i> belum dikirim</span>
                                <?php endif; ?>
                            </div>
                            <h6 class="text-body font-heading mb-2"><?= esc($t['title']) ?></h6>
                            <p class="text-secondary small mb-3"><?= esc($t['description']) ?></p>
                            <div class="d-flex align-items-center justify-content-between pt-2 border-top border-secondary border-opacity-10">
                                <small class="text-danger font-monospace"><i class="fa-solid fa-clock me-1"></i> Deadline: <?= $t['deadline'] ? date('d M Y, H:i', strtotime($t['deadline'])) : 'Tanpa Batas' ?></small>
                                <a href="<?= base_url('member/tasks') ?>" class="btn btn-sm btn-red">Kirim Tugas</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="saas-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="text-body font-heading m-0"><i class="fa-solid fa-history text-danger me-2"></i> Riwayat Presensi Terakhir</h5>
                <a href="<?= base_url('attendance/history') ?>" class="small text-danger">Semua Riwayat</a>
            </div>

            <?php if (empty($myAttendances)): ?>
                <div class="text-center py-4 text-secondary small">Belum ada riwayat presensi recorded.</div>
            <?php else: ?>
                <div class="d-flex flex-column gap-2">
                    <?php foreach (array_slice($myAttendances, 0, 5) as $att): ?>
                        <div class="p-3 rounded-3 bg-body-secondary border border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-body font-heading m-0 small"><?= esc($att['meeting_title']) ?></h6>
                                <small class="text-secondary font-monospace"><?= date('H:i, d M Y', strtotime($att['scan_time'])) ?> (<?= esc($att['method']) ?>)</small>
                            </div>
                            <span class="badge badge-<?= esc($att['status']) ?>"><?= strtoupper(esc($att['status'])) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        const memberUuid = "<?= esc($user['member_uuid']) ?>";
        if (typeof QRious !== 'undefined') {
            new QRious({
                element: document.getElementById("member-qr-canvas"),
                value: memberUuid,
                size: 120,
                level: 'H'
            });
        }
    });
</script>
<?= $this->endSection() ?>
