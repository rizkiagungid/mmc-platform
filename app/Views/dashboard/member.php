<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<?php
    $isAlumni = (session()->get('role_slug') === 'alumni' || ($user['role_slug'] ?? '') === 'alumni');
?>

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

<?php if (!empty($myMonthlyRank) && $myMonthlyRank['rank'] <= 10): ?>
    <?php
        $mRank = $myMonthlyRank['rank'];
    ?>
    <!-- Top 10 Ranking Achievement Card -->
    <div class="saas-card p-3 p-md-4 mb-4 border border-warning border-opacity-50 bg-body-tertiary position-relative overflow-hidden shadow-sm">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center fw-bold fs-4 flex-shrink-0 shadow-sm" style="width: 52px; height: 52px; border: 2px solid rgba(234, 179, 8, 0.6);">
                    <?= $mRank <= 3 ? ($mRank === 1 ? '👑' : ($mRank === 2 ? '🥈' : '🥉')) : '🏆' ?>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                        <span class="badge bg-warning text-dark font-monospace style-tiny px-2.5 py-0.5 rounded-pill fw-bold">
                            PRESTASI BULANAN
                        </span>
                        <span class="text-secondary style-tiny font-monospace">
                            Periode <?= esc($currentMonthName) ?> <?= esc($currentYear) ?>
                        </span>
                    </div>
                    <h5 class="text-body font-heading m-0 fw-bold">
                        🎉 Luar Biasa! Anda Masuk Top 10 Ranking MM (Peringkat #<?= $mRank ?>)
                    </h5>
                    <p class="text-secondary style-tiny m-0 mt-1">
                        Total Skor Keaktifan Anda: <strong class="text-warning font-monospace"><?= number_format($myMonthlyRank['total_points']) ?> Poin</strong>. Pertahankan kedisiplinan dan keaktifan Anda di Multimedia Club!
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="<?= base_url('ranking') ?>" class="btn btn-sm btn-red font-monospace style-tiny px-3.5 py-2 rounded-pill shadow-sm fw-bold">
                    <i class="fa-solid fa-trophy me-1.5 text-warning"></i> Lihat Papan Peringkat MM
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Welcome & Hero Section -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <?php if ($isAlumni): ?>
            <!-- Alumni Tribute & Portal Welcome Card -->
            <div class="saas-card p-3 p-sm-4 h-100 position-relative overflow-hidden alumni-welcome-card d-flex flex-column justify-content-between">
                <div>
                    <!-- Top Badges Row -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-50 px-3 py-1.5 rounded-pill font-monospace fw-bold style-tiny">
                                <i class="fa-solid fa-graduation-cap me-1.5"></i> ALUMNI KEHORMATAN MMC
                            </span>
                            <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 px-2.5 py-1.5 rounded-pill font-monospace style-tiny">
                                <i class="fa-solid fa-star me-1"></i> Hall of Fame & Legacy
                            </span>
                        </div>
                        <span class="text-secondary style-tiny font-monospace d-none d-sm-inline-flex align-items-center gap-1.5">
                            <i class="fa-solid fa-crown text-warning"></i> Selamanya Keluarga Besar MMC
                        </span>
                    </div>

                    <!-- Greeting Heading -->
                    <h3 class="text-body font-heading fw-bold mb-2">
                        Selamat Datang Kembali, <span class="alumni-title-highlight"><?= esc($user['full_name']) ?></span>! 🎓✨
                    </h3>
                    
                    <p class="text-body text-opacity-75 small mb-3 lh-base">
                        Terima kasih atas segala dedikasi, karya, dan jejak prestasi yang telah Anda torehkan untuk Multimedia Club SMAN 1 Tamansari. Setiap karya yang pernah Anda ciptakan dan semangat yang Anda wariskan menjadi teladan dan inspirasi abadi bagi generasi penerus kita.
                    </p>
                </div>

                <!-- Alumni Perks & Action Buttons -->
                <div>
                    <div class="p-3 rounded-3 alumni-perks-box mb-3">
                        <div class="row g-2 align-items-center">
                            <div class="col-12 col-md-7">
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="rounded-circle bg-warning bg-opacity-25 text-warning p-2 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                        <i class="fa-solid fa-shield-heart fs-6"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-body style-tiny font-heading">Status Kehormatan: Bebas Presensi & Tugas</div>
                                        <div class="text-secondary style-tiny">Akses penuh materi pembelajaran & komunitas sosial</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-5 text-md-end">
                                <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 font-monospace style-tiny">
                                    <i class="fa-solid fa-circle-check me-1"></i> Hak Akses Seumur Hidup
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Navigation Actions & Active Meeting Info -->
                    <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                        <a href="<?= base_url('feed') ?>" class="btn btn-sm btn-warning text-dark fw-bold px-3 py-2 rounded-3 shadow-sm font-monospace style-tiny">
                            <i class="fa-solid fa-newspaper me-1.5"></i> Beranda & Feed
                        </a>
                        <a href="<?= base_url('portfolio') ?>" target="_blank" class="btn btn-sm btn-saas-dark border border-secondary border-opacity-25 px-3 py-2 rounded-3 font-monospace style-tiny text-body">
                            <i class="fa-solid fa-film me-1.5 text-danger"></i> Galeri Karya
                        </a>
                        <a href="<?= base_url('profile') ?>" class="btn btn-sm btn-saas-dark border border-secondary border-opacity-25 px-3 py-2 rounded-3 font-monospace style-tiny text-body">
                            <i class="fa-solid fa-user-pen me-1.5 text-info"></i> Update Profil
                        </a>
                    </div>

                    <?php if ($activeMeeting): ?>
                        <div class="p-2.5 rounded-3 bg-body border border-warning border-opacity-30 d-flex align-items-center justify-content-between flex-wrap gap-2 style-tiny mt-2">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="badge bg-warning text-dark font-monospace style-tiny"><i class="fa-solid fa-signal me-1"></i> SESI AKTIF</span>
                                <strong class="text-body"><?= esc($activeMeeting['title']) ?></strong>
                                <span class="text-secondary">(<?= esc($activeMeeting['start_time']) ?> - <?= esc($activeMeeting['end_time']) ?> WIB @ <?= esc($activeMeeting['location']) ?>)</span>
                            </div>
                            <span class="badge bg-body-secondary text-secondary border border-secondary border-opacity-25 font-monospace style-tiny">
                                <i class="fa-solid fa-shield-heart me-1 text-warning"></i> Bebas Presensi
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        <?php else: ?>
            <!-- Regular Member Hero Welcome Card -->
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
        <?php endif; ?>
    </div>

    <!-- Member / Alumni Permanent QR Preview Card -->
    <div class="col-lg-4">
        <div class="saas-card p-4 text-center h-100 border <?= $isAlumni ? 'border-warning border-opacity-25' : 'border-secondary border-opacity-25' ?> bg-body-secondary d-flex flex-column justify-content-between">
            <div>
                <h6 class="text-body font-heading mb-1">
                    <?= $isAlumni ? 'ID Digital & QR Alumni' : 'ID Member & Permanent QR' ?>
                </h6>
                <p class="text-secondary small mb-3">
                    <?= $isAlumni ? 'Identitas resmi keanggotaan alumni Multimedia Club.' : 'Tunjukkan QR ini ke BPH saat absensi.' ?>
                </p>

                <canvas id="member-qr-canvas" class="bg-white p-2 rounded-3 mx-auto mb-3"></canvas>

                <div class="small text-secondary font-monospace">
                    <div>NIS/NIP: <strong class="text-body"><?= esc($user['nis_nip'] ?: '-') ?></strong></div>
                    <div>Status: <span class="badge <?= $isAlumni ? 'bg-warning bg-opacity-25 text-warning border border-warning' : 'bg-body-secondary border border-secondary text-body' ?>"><?= $isAlumni ? 'Alumni' : 'v' . esc($user['qr_version']) ?></span></div>
                </div>
            </div>

            <a href="<?= base_url('profile') ?>" class="btn btn-sm btn-saas-dark w-100 mt-3 text-body border border-secondary border-opacity-25">
                <i class="fa-solid fa-id-card me-1"></i> Pengaturan QR & Profil
            </a>
        </div>
    </div>
</div>

<!-- Informasi Terbaru / Berita MMC Section (Responsive Layout) -->
<div class="saas-card p-3 p-md-4 mb-4 border border-secondary border-opacity-25 bg-body-tertiary shadow-sm">
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <h5 class="text-body font-heading m-0 fw-bold d-flex align-items-center gap-2">
            <i class="fa-solid fa-newspaper text-danger"></i> Berita &amp; Informasi MMC
        </h5>
        <a href="<?= base_url('informasi') ?>" class="small text-danger text-decoration-none fw-semibold style-tiny">
            Lihat Semua Informasi <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
    </div>

    <?php if (empty($latestInformations)): ?>
        <div class="text-center py-4 text-secondary small">Belum ada informasi atau pengumuman terbaru saat ini.</div>
    <?php else: ?>
        <div class="d-flex flex-column gap-3">
            <?php foreach (array_slice($latestInformations, 0, 5) as $info): ?>
                <?php
                    $cat = strtolower((string)$info['category']);
                    $badgeStyle = 'bg-danger bg-opacity-25 text-danger border-danger border-opacity-50';
                    if (strpos($cat, 'pembaruan') !== false || strpos($cat, 'layanan') !== false || strpos($cat, 'sekolah') !== false) {
                        $badgeStyle = 'bg-warning bg-opacity-25 text-warning border-warning border-opacity-50';
                    } elseif (strpos($cat, 'maintenance') !== false || strpos($cat, 'sistem') !== false) {
                        $badgeStyle = 'bg-info bg-opacity-25 text-info border-info border-opacity-50';
                    }
                ?>
                <div class="p-3 p-md-4 rounded-3 rounded-md-4 bg-body-secondary border border-secondary border-opacity-25 transition-all shadow-sm">
                    <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                        <span class="badge rounded-pill <?= $badgeStyle ?> px-2.5 py-1 font-monospace style-tiny border text-nowrap">
                            <?= esc($info['category']) ?>
                        </span>
                        <span class="text-secondary style-tiny font-monospace">
                            <i class="fa-regular fa-clock me-1 text-warning opacity-75"></i> <?= date('d M Y, H:i', strtotime($info['date_time'])) ?> WIB
                        </span>
                        <span class="text-secondary style-tiny ms-auto d-none d-md-inline">
                            <i class="fa-solid fa-user-pen me-1 text-info opacity-75"></i> <?= esc($info['author_name']) ?>
                        </span>
                    </div>
                    <h6 class="fw-bold text-body font-heading mt-2.5 mb-2 fs-6 lh-base"><?= esc($info['title']) ?></h6>
                    <p class="text-secondary small lh-base mb-0" style="word-break: break-word; overflow-wrap: break-word;">
                        <?= nl2br(esc($info['description'])) ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (count($latestInformations) > 5): ?>
            <div class="collapse d-flex flex-column gap-3 mt-3" id="moreMemberInformationsCollapse">
                <?php foreach (array_slice($latestInformations, 5) as $info): ?>
                    <?php
                        $cat = strtolower((string)$info['category']);
                        $badgeStyle = 'bg-danger bg-opacity-25 text-danger border-danger border-opacity-50';
                        if (strpos($cat, 'pembaruan') !== false || strpos($cat, 'layanan') !== false || strpos($cat, 'sekolah') !== false) {
                            $badgeStyle = 'bg-warning bg-opacity-25 text-warning border-warning border-opacity-50';
                        } elseif (strpos($cat, 'maintenance') !== false || strpos($cat, 'sistem') !== false) {
                            $badgeStyle = 'bg-info bg-opacity-25 text-info border-info border-opacity-50';
                        }
                    ?>
                    <div class="p-3 p-md-4 rounded-3 rounded-md-4 bg-body-secondary border border-secondary border-opacity-25 transition-all shadow-sm">
                        <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                            <span class="badge rounded-pill <?= $badgeStyle ?> px-2.5 py-1 font-monospace style-tiny border text-nowrap">
                                <?= esc($info['category']) ?>
                            </span>
                            <span class="text-secondary style-tiny font-monospace">
                                <i class="fa-regular fa-clock me-1 text-warning opacity-75"></i> <?= date('d M Y, H:i', strtotime($info['date_time'])) ?> WIB
                            </span>
                            <span class="text-secondary style-tiny ms-auto d-none d-md-inline">
                                <i class="fa-solid fa-user-pen me-1 text-info opacity-75"></i> <?= esc($info['author_name']) ?>
                            </span>
                        </div>
                        <h6 class="fw-bold text-body font-heading mt-2.5 mb-2 fs-6 lh-base"><?= esc($info['title']) ?></h6>
                        <p class="text-secondary small lh-base mb-0" style="word-break: break-word; overflow-wrap: break-word;">
                            <?= nl2br(esc($info['description'])) ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="pt-3 border-top border-secondary border-opacity-10 mt-3">
                <button class="btn btn-sm btn-saas-dark border border-secondary border-opacity-25 w-100 text-body style-tiny font-monospace py-2" type="button" data-bs-toggle="collapse" data-bs-target="#moreMemberInformationsCollapse" aria-expanded="false" onclick="const isExp = this.getAttribute('aria-expanded') === 'true'; this.innerHTML = isExp ? '<i class=\'fa-solid fa-chevron-up me-1\'></i> Sembunyikan Informasi' : '<i class=\'fa-solid fa-chevron-down me-1\'></i> Tampilkan <?= count($latestInformations) - 5 ?> Informasi Lainnya';">
                    <i class="fa-solid fa-chevron-down me-1"></i> Tampilkan <?= count($latestInformations) - 5 ?> Informasi Lainnya
                </button>
            </div>
        <?php endif; ?>
    <?php endif; ?>
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
<div class="row g-3 g-md-4">
    <div class="col-lg-7">
        <div class="saas-card p-3 p-md-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-3.5 flex-wrap gap-2">
                    <h5 class="text-body font-heading m-0 fw-bold"><i class="fa-solid fa-list-check <?= $isAlumni ? 'text-warning' : 'text-danger' ?> me-2"></i> <?= $isAlumni ? 'Riwayat Tugas Anda' : 'Tugas Ditugaskan Kepada Anda' ?></h5>
                    <a href="<?= base_url('member/tasks') ?>" class="small <?= $isAlumni ? 'text-warning' : 'text-danger' ?> text-decoration-none style-tiny fw-semibold">
                        Lihat Semua Tugas <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>

                <?php if (empty($myTasks)): ?>
                    <div class="text-center py-4 text-secondary small">
                        <?= $isAlumni ? 'Sebagai Alumni, Anda bebas dari kewajiban tugas harian.' : 'Belum ada tugas yang ditugaskan kepada Anda saat ini.' ?>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach (array_slice($myTasks, 0, 3) as $t): ?>
                            <div class="p-3.5 p-md-4 rounded-4 bg-body-secondary border border-secondary border-opacity-25 shadow-sm">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                    <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                        <span class="badge rounded-pill font-monospace style-tiny px-2.5 py-1" style="background-color: <?= $t['priority_color'] ?>;"><?= esc($t['priority_name']) ?></span>
                                        <span class="badge rounded-pill font-monospace style-tiny px-2.5 py-1" style="background-color: <?= $t['my_status_color'] ?? '#6c757d' ?>;"><?= esc($t['my_status_name'] ?? 'Belum dikerjakan') ?></span>
                                    </div>
                                    <?php if (!empty($t['is_submitted']) || !empty($t['my_submission'])): ?>
                                        <span class="badge rounded-pill bg-success bg-opacity-25 text-success border border-success border-opacity-25 style-tiny font-monospace px-2.5 py-1"><i class="fa-solid fa-circle-check me-1"></i> Terkirim</span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 style-tiny font-monospace px-2.5 py-1"><i class="fa-solid fa-circle-xmark me-1"></i> Belum Kirim</span>
                                    <?php endif; ?>
                                </div>

                                <h6 class="text-body font-heading fw-bold fs-6 mb-2 lh-base"><?= esc($t['title']) ?></h6>
                                
                                <?php $descLength = strlen($t['description']); ?>
                                <div class="text-secondary small lh-base mb-3" style="word-break: break-word;">
                                    <?php if ($descLength > 110): ?>
                                        <span><?= esc(substr($t['description'], 0, 110)) ?>...</span>
                                        <div class="collapse mt-2" id="dashMemberTaskDesc_<?= $t['id'] ?>">
                                            <?= nl2br(esc($t['description'])) ?>
                                        </div>
                                        <div class="mt-1.5">
                                            <a class="text-danger text-decoration-none style-tiny fw-bold d-inline-flex align-items-center gap-1" data-bs-toggle="collapse" href="#dashMemberTaskDesc_<?= $t['id'] ?>" role="button" aria-expanded="false" onclick="const isExp = this.getAttribute('aria-expanded') === 'true'; this.innerHTML = isExp ? '<i class=\'fa-solid fa-chevron-up\'></i> Sembunyikan' : '<i class=\'fa-solid fa-chevron-down\'></i> Selengkapnya';">
                                                <i class="fa-solid fa-chevron-down"></i> Selengkapnya
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <?= nl2br(esc($t['description'])) ?>
                                    <?php endif; ?>
                                </div>

                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pt-3 border-top border-secondary border-opacity-15 mt-2">
                                    <span class="text-danger font-monospace style-tiny"><i class="fa-regular fa-clock me-1 text-danger"></i> <?= $t['deadline'] ? date('d M Y, H:i', strtotime($t['deadline'])) . ' WIB' : 'Tanpa Batas' ?></span>
                                    <a href="<?= base_url('member/tasks/submit/' . $t['id']) ?>" class="btn btn-sm btn-red style-tiny font-monospace px-3.5 py-1.5 fw-bold rounded-pill shadow-sm">
                                        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Cek Tugas
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if (count($myTasks) > 3): ?>
                        <div class="collapse d-flex flex-column gap-3 mt-3" id="moreMemberTasksCollapse">
                            <?php foreach (array_slice($myTasks, 3) as $t): ?>
                                <div class="p-3.5 p-md-4 rounded-4 bg-body-secondary border border-secondary border-opacity-25 shadow-sm">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                        <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                            <span class="badge rounded-pill font-monospace style-tiny px-2.5 py-1" style="background-color: <?= $t['priority_color'] ?>;"><?= esc($t['priority_name']) ?></span>
                                            <span class="badge rounded-pill font-monospace style-tiny px-2.5 py-1" style="background-color: <?= $t['my_status_color'] ?? '#6c757d' ?>;"><?= esc($t['my_status_name'] ?? 'Belum dikerjakan') ?></span>
                                        </div>
                                        <?php if (!empty($t['is_submitted']) || !empty($t['my_submission'])): ?>
                                            <span class="badge rounded-pill bg-success bg-opacity-25 text-success border border-success border-opacity-25 style-tiny font-monospace px-2.5 py-1"><i class="fa-solid fa-circle-check me-1"></i> Terkirim</span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 style-tiny font-monospace px-2.5 py-1"><i class="fa-solid fa-circle-xmark me-1"></i> Belum Kirim</span>
                                        <?php endif; ?>
                                    </div>

                                    <h6 class="text-body font-heading fw-bold fs-6 mb-2 lh-base"><?= esc($t['title']) ?></h6>
                                    
                                    <?php $descLength = strlen($t['description']); ?>
                                    <div class="text-secondary small lh-base mb-3" style="word-break: break-word;">
                                        <?php if ($descLength > 110): ?>
                                            <span><?= esc(substr($t['description'], 0, 110)) ?>...</span>
                                            <div class="collapse mt-2" id="dashMemberTaskDesc_<?= $t['id'] ?>">
                                                <?= nl2br(esc($t['description'])) ?>
                                            </div>
                                            <div class="mt-1.5">
                                                <a class="text-danger text-decoration-none style-tiny fw-bold d-inline-flex align-items-center gap-1" data-bs-toggle="collapse" href="#dashMemberTaskDesc_<?= $t['id'] ?>" role="button" aria-expanded="false" onclick="const isExp = this.getAttribute('aria-expanded') === 'true'; this.innerHTML = isExp ? '<i class=\'fa-solid fa-chevron-up\'></i> Sembunyikan' : '<i class=\'fa-solid fa-chevron-down\'></i> Selengkapnya';">
                                                    <i class="fa-solid fa-chevron-down"></i> Selengkapnya
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <?= nl2br(esc($t['description'])) ?>
                                        <?php endif; ?>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pt-3 border-top border-secondary border-opacity-15 mt-2">
                                        <span class="text-danger font-monospace style-tiny"><i class="fa-regular fa-clock me-1 text-danger"></i> <?= $t['deadline'] ? date('d M Y, H:i', strtotime($t['deadline'])) . ' WIB' : 'Tanpa Batas' ?></span>
                                        <a href="<?= base_url('member/tasks/submit/' . $t['id']) ?>" class="btn btn-sm btn-red style-tiny font-monospace px-3.5 py-1.5 fw-bold rounded-pill shadow-sm">
                                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Cek Tugas
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="pt-3 border-top border-secondary border-opacity-10 mt-3">
                            <button class="btn btn-sm btn-saas-dark border border-secondary border-opacity-25 w-100 text-body style-tiny font-monospace" type="button" data-bs-toggle="collapse" data-bs-target="#moreMemberTasksCollapse" aria-expanded="false" onclick="const isExp = this.getAttribute('aria-expanded') === 'true'; this.innerHTML = isExp ? '<i class=\'fa-solid fa-chevron-up me-1\'></i> Sembunyikan Tugas' : '<i class=\'fa-solid fa-chevron-down me-1\'></i> Tampilkan <?= count($myTasks) - 3 ?> Tugas Lainnya';">
                                <i class="fa-solid fa-chevron-down me-1"></i> Tampilkan <?= count($myTasks) - 3 ?> Tugas Lainnya
                            </button>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="saas-card p-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <h5 class="text-body font-heading m-0"><i class="fa-solid fa-history <?= $isAlumni ? 'text-warning' : 'text-danger' ?> me-2"></i> Riwayat Presensi</h5>
                    <a href="<?= base_url('attendance/history') ?>" class="small <?= $isAlumni ? 'text-warning' : 'text-danger' ?> text-decoration-none style-tiny fw-semibold">
                        Semua Riwayat <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>

                <?php if (empty($myAttendances)): ?>
                    <div class="text-center py-4 text-secondary small">
                        <?= $isAlumni ? 'Sebagai Alumni Kehormatan, Anda tidak memiliki kewajiban presensi.' : 'Belum ada riwayat presensi yang tercatat.' ?>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2">
                        <?php foreach (array_slice($myAttendances, 0, 5) as $att): ?>
                            <div class="p-3 rounded-3 bg-body-secondary border border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-body font-heading m-0 small fw-bold"><?= esc($att['meeting_title']) ?></h6>
                                    <small class="text-secondary font-monospace style-tiny"><?= date('H:i, d M Y', strtotime($att['scan_time'])) ?> (<?= esc($att['method']) ?>)</small>
                                </div>
                                <span class="badge badge-<?= esc($att['status']) ?>"><?= strtoupper(esc($att['status'])) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if (count($myAttendances) > 5): ?>
                        <div class="collapse d-flex flex-column gap-2 mt-2" id="moreMemberAttendancesCollapse">
                            <?php foreach (array_slice($myAttendances, 5) as $att): ?>
                                <div class="p-3 rounded-3 bg-body-secondary border border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-body font-heading m-0 small fw-bold"><?= esc($att['meeting_title']) ?></h6>
                                        <small class="text-secondary font-monospace style-tiny"><?= date('H:i, d M Y', strtotime($att['scan_time'])) ?> (<?= esc($att['method']) ?>)</small>
                                    </div>
                                    <span class="badge badge-<?= esc($att['status']) ?>"><?= strtoupper(esc($att['status'])) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <?php if (!empty($myAttendances) && count($myAttendances) > 5): ?>
                <div class="pt-3 border-top border-secondary border-opacity-10 mt-3">
                    <button class="btn btn-sm btn-saas-dark border border-secondary border-opacity-25 w-100 text-body style-tiny font-monospace py-1.5" type="button" data-bs-toggle="collapse" data-bs-target="#moreMemberAttendancesCollapse" aria-expanded="false" onclick="const isExp = this.getAttribute('aria-expanded') === 'true'; this.innerHTML = isExp ? '<i class=\'fa-solid fa-chevron-up me-1\'></i> Sembunyikan Riwayat' : '<i class=\'fa-solid fa-chevron-down me-1\'></i> Tampilkan <?= count($myAttendances) - 5 ?> Riwayat Lainnya';">
                        <i class="fa-solid fa-chevron-down me-1"></i> Tampilkan <?= count($myAttendances) - 5 ?> Riwayat Lainnya
                    </button>
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
