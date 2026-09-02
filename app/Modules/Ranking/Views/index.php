<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">

    <!-- Header Section -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-warning text-dark font-monospace style-tiny px-2.5 py-1 rounded-pill fw-bold">
                    <i class="fa-solid fa-trophy me-1"></i> LEADERBOARD MMC
                </span>
                <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 font-monospace style-tiny px-2.5 py-1 rounded-pill">
                    Reset Setiap 1 Bulan
                </span>
            </div>
            <h3 class="text-white font-heading fw-bold m-0 d-flex align-items-center gap-2">
                Ranking MM <span class="text-danger">•</span> Top 10 Anggota
            </h3>
            <p class="text-secondary style-tiny m-0 mt-1">
                Peringkat keaktifan & prestasi anggota Multimedia Club periode <strong><?= esc($monthName) ?> <?= esc($selectedYear) ?></strong>
            </p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <!-- Period Filter Dropdown -->
            <form action="<?= base_url('ranking') ?>" method="GET" class="d-flex align-items-center gap-2 m-0">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-body-secondary text-secondary border-secondary border-opacity-50">
                        <i class="fa-solid fa-calendar-days"></i>
                    </span>
                    <select name="period" class="form-select bg-body-secondary text-body border-secondary border-opacity-50 style-tiny fw-semibold" onchange="this.form.submit()">
                        <?php foreach ($availableMonths as $m): ?>
                            <option value="<?= $m['key'] ?>" <?= $m['key'] === $selectedPeriod ? 'selected' : '' ?>>
                                Periode: <?= esc($m['label']) ?> <?= $m['is_current'] ? '(Bulan Ini)' : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <button type="button" class="btn btn-sm btn-outline-info style-tiny rounded-pill px-3 shadow-sm font-monospace" data-bs-toggle="modal" data-bs-target="#scoringRulesModal">
                <i class="fa-solid fa-circle-question me-1"></i> Sistem Penilaian
            </button>

            <?php if (session()->get('role_slug') === 'superadmin'): ?>
                <button type="button" class="btn btn-sm btn-outline-danger style-tiny rounded-pill px-3 shadow-sm font-monospace" data-bs-toggle="modal" data-bs-target="#resetPointsModal">
                    <i class="fa-solid fa-rotate-left me-1"></i> Reset Poin (0 Pts)
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Alert If Period Points Are Reset (Khusus Superadmin) -->
    <?php if (!empty($resetCutoff) && session()->get('role_slug') === 'superadmin'): ?>
        <div class="alert alert-info border border-info border-opacity-25 bg-info bg-opacity-10 text-body style-tiny mb-4 rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2 shadow-sm">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-info text-dark p-2 rounded-circle"><i class="fa-solid fa-rotate-left"></i></span>
                <div>
                    <strong>Poin Periode Ini Telah Direset ke 0</strong> pada tanggal <strong><?= date('d M Y, H:i', strtotime($resetCutoff)) ?> WIB</strong>.
                    <span class="d-block text-secondary style-tiny">Aktivitas presensi, tugas, dan beranda yang dikerjakan setelah tanggal reset akan dihitung mulai dari 0 poin.</span>
                </div>
            </div>
            <form action="<?= base_url('ranking/cancel-reset') ?>" method="POST" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reset poin dan memulihkan seluruh perolehan skor awal pada periode ini?');">
                <?= csrf_field() ?>
                <input type="hidden" name="period" value="<?= esc($selectedPeriod) ?>">
                <button type="submit" class="btn btn-sm btn-outline-info style-tiny rounded-pill px-3">
                    <i class="fa-solid fa-arrow-rotate-left me-1"></i> Batalkan & Pulihkan Skor Awal
                </button>
            </form>
        </div>
    <?php endif; ?>

    <?php
        $disabledMemberPages = json_decode(get_setting('disabled_member_pages', '[]'), true) ?: [];
        $isRankingLockedForMembers = in_array('ranking', $disabledMemberPages);
        $isAdminRole = in_array(session()->get('role_slug'), ['superadmin', 'pembina', 'bph']);
    ?>
    <?php if ($isRankingLockedForMembers && $isAdminRole): ?>
        <div class="alert alert-warning border border-warning border-opacity-25 bg-warning bg-opacity-10 text-warning style-tiny mb-4 rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <i class="fa-solid fa-lock me-1"></i>
                <strong>Status Fitur: Dinonaktifkan untuk Anggota</strong>. Hanya Superadmin, Pembina, dan BPH yang saat ini dapat mengakses halaman ini.
            </div>
            <a href="<?= base_url('admin/settings') ?>" class="btn btn-sm btn-outline-warning style-tiny rounded-pill px-3">
                <i class="fa-solid fa-sliders me-1"></i> Ubah di Pengaturan
            </a>
        </div>
    <?php endif; ?>

    <!-- My Personal Rank Highlight Banner -->
    <?php if ($myRank): ?>
        <?php 
            $isTop10 = $myRank['rank'] <= 10;
            $rankColor = $myRank['rank'] === 1 ? 'warning' : ($myRank['rank'] === 2 ? 'light' : ($myRank['rank'] === 3 ? 'danger' : 'info'));
        ?>
        <div class="saas-card p-3 p-md-4 mb-4 border <?= $isTop10 ? 'border-warning border-opacity-50' : 'border-secondary border-opacity-25' ?> bg-body-tertiary shadow-sm position-relative overflow-hidden">
            <div class="row align-items-center g-3">
                <div class="col-auto">
                    <div class="position-relative">
                        <img src="<?= avatar_url($myRank['avatar'] ?? null, $myRank['full_name'] ?? 'User') ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-<?= $rankColor ?> border-2 shadow" style="width: 58px; height: 58px;" onerror="this.onerror=null; this.src='<?= base_url('media/avatar?name=' . urlencode($myRank['full_name'] ?? 'User')) ?>';">
                        <span class="position-absolute bottom-0 end-0 badge bg-<?= $rankColor ?> <?= $rankColor === 'warning' ? 'text-dark' : 'text-white' ?> rounded-circle p-1 d-flex align-items-center justify-content-center shadow font-monospace fw-bold" style="width: 24px; height: 24px; font-size: 0.72rem;">
                            #<?= $myRank['rank'] ?>
                        </span>
                    </div>
                </div>

                <div class="col">
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                        <h5 class="text-body font-heading m-0 fw-bold"><?= esc($myRank['full_name']) ?></h5>
                        <?php if ($isTop10): ?>
                            <span class="badge bg-warning text-dark font-monospace style-tiny px-2 py-0.5 rounded-pill fw-bold">
                                🎉 Masuk Top 10 Ranking MM!
                            </span>
                        <?php endif; ?>
                    </div>
                    <p class="text-secondary style-tiny m-0">
                        Posisi Anda saat ini: <strong class="text-<?= $rankColor ?> font-monospace">Peringkat #<?= $myRank['rank'] ?></strong> dari seluruh anggota dengan total <strong class="text-white font-monospace"><?= number_format($myRank['total_points']) ?> Poin</strong> di periode <?= esc($monthName) ?> <?= esc($selectedYear) ?>.
                    </p>
                </div>

                <div class="col-12 col-md-auto">
                    <div class="d-flex align-items-center gap-2 flex-wrap style-tiny font-monospace">
                        <span class="badge bg-body-secondary text-body border border-secondary border-opacity-25 px-2.5 py-1.5 rounded-3">
                            <i class="fa-solid fa-qrcode text-success me-1"></i> Absen: <?= $myRank['attendance_points'] ?> Pts
                        </span>
                        <span class="badge bg-body-secondary text-body border border-secondary border-opacity-25 px-2.5 py-1.5 rounded-3">
                            <i class="fa-solid fa-list-check text-info me-1"></i> Tugas: <?= $myRank['task_points'] ?> Pts
                        </span>
                        <span class="badge bg-body-secondary text-body border border-secondary border-opacity-25 px-2.5 py-1.5 rounded-3">
                            <i class="fa-solid fa-square-rss text-warning me-1"></i> Feed: <?= $myRank['feed_points'] ?> Pts
                        </span>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (empty($leaderboard)): ?>
        <div class="saas-card text-center py-5 border border-secondary border-opacity-25 bg-body-tertiary">
            <i class="fa-solid fa-trophy display-1 text-secondary opacity-25 mb-3"></i>
            <h5 class="text-white font-heading">Belum Ada Catatan Keaktifan</h5>
            <p class="text-secondary style-tiny max-w-md mx-auto">
                Belum ada aktivitas presensi, tugas, atau interaksi beranda yang tercatat pada periode <?= esc($monthName) ?> <?= esc($selectedYear) ?>.
            </p>
        </div>
    <?php else: ?>

        <?php
            $rank1 = $leaderboard[0] ?? null;
            $rank2 = $leaderboard[1] ?? null;
            $rank3 = $leaderboard[2] ?? null;
            $otherRanks = array_slice($leaderboard, 3);
        ?>

        <!-- TOP 3 PODIUM SECTION -->
        <div class="mb-5 mt-3">
            <div class="row g-4 align-items-end justify-content-center">
                
                <!-- Rank 2 Podium (Silver) -->
                <?php if ($rank2): ?>
                <div class="col-12 col-md-4 order-2 order-md-1">
                    <div class="saas-card p-3 p-md-4 text-center border border-secondary border-opacity-50 bg-body-tertiary rounded-4 shadow-sm h-100 pt-4">
                        <div class="mb-2 pt-1">
                            <div class="position-relative d-inline-block">
                                <a href="<?= base_url('feed/user/' . $rank2['user_id']) ?>">
                                    <img src="<?= avatar_url($rank2['avatar'], $rank2['full_name']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-secondary border-3 shadow" style="width: 76px; height: 76px;" onerror="this.onerror=null; this.src='<?= base_url('media/avatar?name=' . urlencode($rank2['full_name'])) ?>';">
                                </a>
                                <span class="position-absolute top-0 start-50 translate-middle badge bg-secondary text-white rounded-pill px-2.5 py-0.5 shadow font-monospace fw-bold style-tiny border border-white border-opacity-50" style="white-space: nowrap;">
                                    🥈 #2
                                </span>
                            </div>
                        </div>

                        <h6 class="text-body font-heading fw-bold mb-1 text-truncate">
                            <a href="<?= base_url('feed/user/' . $rank2['user_id']) ?>" class="text-body text-decoration-none hover-text-danger">
                                <?= esc($rank2['full_name']) ?>
                            </a>
                        </h6>
                        <small class="text-secondary style-tiny d-block mb-2 text-truncate"><?= esc($rank2['class_dept'] ?: $rank2['role_name']) ?></small>

                        <div class="display-6 font-heading fw-bold text-body font-monospace mb-2">
                            <?= number_format($rank2['total_points']) ?> <span class="fs-6 text-secondary fw-normal">Pts</span>
                        </div>

                        <div class="d-flex align-items-center justify-content-center gap-1.5 flex-wrap style-tiny font-monospace pt-2 border-top border-secondary border-opacity-25">
                            <span class="badge bg-body-secondary text-body border border-secondary border-opacity-25" title="Poin Absensi"><i class="fa-solid fa-qrcode text-success me-1"></i><?= $rank2['attendance_points'] ?></span>
                            <span class="badge bg-body-secondary text-body border border-secondary border-opacity-25" title="Poin Tugas"><i class="fa-solid fa-list-check text-info me-1"></i><?= $rank2['task_points'] ?></span>
                            <span class="badge bg-body-secondary text-body border border-secondary border-opacity-25" title="Poin Beranda Feed"><i class="fa-solid fa-square-rss text-warning me-1"></i><?= $rank2['feed_points'] ?></span>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Rank 1 Podium (Gold Champion) -->
                <?php if ($rank1): ?>
                <div class="col-12 col-md-4 order-1 order-md-2">
                    <div class="saas-card p-4 text-center border border-warning border-2 bg-body-tertiary rounded-4 shadow-sm h-100 pt-4">
                        <div class="mb-2 pt-1">
                            <div class="position-relative d-inline-block">
                                <a href="<?= base_url('feed/user/' . $rank1['user_id']) ?>">
                                    <img src="<?= avatar_url($rank1['avatar'], $rank1['full_name']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-warning border-3 shadow-lg" style="width: 88px; height: 88px;" onerror="this.onerror=null; this.src='<?= base_url('media/avatar?name=' . urlencode($rank1['full_name'])) ?>';">
                                </a>
                                <span class="position-absolute top-0 start-50 translate-middle badge bg-warning text-dark rounded-pill px-3 py-1 shadow-lg font-monospace fw-bold style-tiny border border-white" style="white-space: nowrap; font-size: 0.78rem;">
                                    👑 TOP 1 JUARA
                                </span>
                            </div>
                        </div>

                        <h5 class="text-body font-heading fw-bold mb-1 text-truncate mt-1">
                            <a href="<?= base_url('feed/user/' . $rank1['user_id']) ?>" class="text-body text-decoration-none hover-text-warning">
                                <?= esc($rank1['full_name']) ?>
                            </a>
                        </h5>
                        <small class="text-secondary style-tiny d-block mb-3 text-truncate"><?= esc($rank1['class_dept'] ?: $rank1['role_name']) ?></small>

                        <div class="display-5 font-heading fw-bold text-warning font-monospace mb-3">
                            <?= number_format($rank1['total_points']) ?> <span class="fs-6 text-secondary fw-normal">Pts</span>
                        </div>

                        <div class="d-flex align-items-center justify-content-center gap-1.5 flex-wrap style-tiny font-monospace pt-3 border-top border-warning border-opacity-25">
                            <span class="badge bg-body-secondary text-body border border-success border-opacity-50" title="Poin Absensi"><i class="fa-solid fa-qrcode text-success me-1"></i>Absen: <?= $rank1['attendance_points'] ?></span>
                            <span class="badge bg-body-secondary text-body border border-info border-opacity-50" title="Poin Tugas"><i class="fa-solid fa-list-check text-info me-1"></i>Tugas: <?= $rank1['task_points'] ?></span>
                            <span class="badge bg-body-secondary text-body border border-warning border-opacity-50" title="Poin Beranda Feed"><i class="fa-solid fa-square-rss text-warning me-1"></i>Feed: <?= $rank1['feed_points'] ?></span>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Rank 3 Podium (Bronze) -->
                <?php if ($rank3): ?>
                <div class="col-12 col-md-4 order-3 order-md-3">
                    <div class="saas-card p-3 p-md-4 text-center border border-danger border-opacity-50 bg-body-tertiary rounded-4 shadow-sm h-100 pt-4">
                        <div class="mb-2 pt-1">
                            <div class="position-relative d-inline-block">
                                <a href="<?= base_url('feed/user/' . $rank3['user_id']) ?>">
                                    <img src="<?= avatar_url($rank3['avatar'], $rank3['full_name']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-danger border-3 shadow" style="width: 76px; height: 76px;" onerror="this.onerror=null; this.src='<?= base_url('media/avatar?name=' . urlencode($rank3['full_name'])) ?>';">
                                </a>
                                <span class="position-absolute top-0 start-50 translate-middle badge bg-danger text-white rounded-pill px-2.5 py-0.5 shadow font-monospace fw-bold style-tiny border border-white border-opacity-50" style="white-space: nowrap;">
                                    🥉 #3
                                </span>
                            </div>
                        </div>

                        <h6 class="text-body font-heading fw-bold mb-1 text-truncate">
                            <a href="<?= base_url('feed/user/' . $rank3['user_id']) ?>" class="text-body text-decoration-none hover-text-danger">
                                <?= esc($rank3['full_name']) ?>
                            </a>
                        </h6>
                        <small class="text-secondary style-tiny d-block mb-2 text-truncate"><?= esc($rank3['class_dept'] ?: $rank3['role_name']) ?></small>

                        <div class="display-6 font-heading fw-bold text-body font-monospace mb-2">
                            <?= number_format($rank3['total_points']) ?> <span class="fs-6 text-secondary fw-normal">Pts</span>
                        </div>

                        <div class="d-flex align-items-center justify-content-center gap-1.5 flex-wrap style-tiny font-monospace pt-2 border-top border-secondary border-opacity-25">
                            <span class="badge bg-body-secondary text-body border border-secondary border-opacity-25" title="Poin Absensi"><i class="fa-solid fa-qrcode text-success me-1"></i><?= $rank3['attendance_points'] ?></span>
                            <span class="badge bg-body-secondary text-body border border-secondary border-opacity-25" title="Poin Tugas"><i class="fa-solid fa-list-check text-info me-1"></i><?= $rank3['task_points'] ?></span>
                            <span class="badge bg-body-secondary text-body border border-secondary border-opacity-25" title="Poin Beranda Feed"><i class="fa-solid fa-square-rss text-warning me-1"></i><?= $rank3['feed_points'] ?></span>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>

        <!-- RANK 4 S.D. 10 LIST TABLE -->
        <?php if (!empty($otherRanks)): ?>
            <div class="saas-card p-3 p-md-4 mb-4 border border-secondary border-opacity-25 bg-body-tertiary">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-secondary border-opacity-25">
                    <h5 class="text-body font-heading m-0 fw-bold d-flex align-items-center gap-2 fs-6">
                        <i class="fa-solid fa-ranking-star text-info"></i> Peringkat 4 s.d. 10 Teratas
                    </h5>
                    <span class="badge bg-secondary bg-opacity-25 text-secondary font-monospace style-tiny">
                        <?= count($otherRanks) ?> Anggota Terdaftar
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-dark-saas align-middle w-100">
                        <thead>
                            <tr class="style-tiny font-monospace text-secondary">
                                <th style="width: 60px;" class="text-center">RANK</th>
                                <th>ANGGOTA</th>
                                <th class="text-center">PRESENSI</th>
                                <th class="text-center">TUGAS</th>
                                <th class="text-center">AKTIVITAS BERANDA FEED</th>
                                <th class="text-end">TOTAL SKOR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($otherRanks as $r): ?>
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-secondary bg-opacity-25 text-body font-monospace fw-bold px-2.5 py-1 rounded-pill style-tiny">
                                            #<?= $r['rank'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2.5">
                                            <a href="<?= base_url('feed/user/' . $r['user_id']) ?>" class="flex-shrink-0">
                                                <img src="<?= avatar_url($r['avatar'], $r['full_name']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-secondary border-opacity-50" style="width: 38px; height: 38px;" onerror="this.onerror=null; this.src='<?= base_url('media/avatar?name=' . urlencode($r['full_name'])) ?>';">
                                            </a>
                                            <div class="text-truncate">
                                                <a href="<?= base_url('feed/user/' . $r['user_id']) ?>" class="text-body style-tiny fw-bold text-decoration-none hover-text-danger d-block text-truncate">
                                                    <?= esc($r['full_name']) ?>
                                                </a>
                                                <small class="text-secondary style-tiny opacity-75 font-monospace d-block text-truncate">
                                                    <?= esc($r['class_dept'] ?: $r['role_name']) ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center font-monospace style-tiny">
                                        <span class="text-success fw-bold"><?= $r['attendance_points'] ?> Pts</span>
                                        <small class="text-secondary d-block opacity-75">(<?= $r['attendance_count'] ?> Sesi)</small>
                                    </td>
                                    <td class="text-center font-monospace style-tiny">
                                        <span class="text-info fw-bold"><?= $r['task_points'] ?> Pts</span>
                                        <small class="text-secondary d-block opacity-75">(<?= $r['task_count'] ?> Tugas<?= $r['task_avg_grade'] !== null ? ' • Avg ' . $r['task_avg_grade'] : '' ?>)</small>
                                    </td>
                                    <td class="text-center font-monospace style-tiny">
                                        <span class="text-warning fw-bold"><?= $r['feed_points'] ?> Pts</span>
                                        <small class="text-secondary d-block opacity-75">(<?= $r['feed_post_count'] ?> Post, <?= $r['feed_comment_count'] ?> Komen)</small>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50 font-monospace fs-6 px-3 py-1.5 rounded-3 fw-bold">
                                            <?= number_format($r['total_points']) ?> <small class="style-tiny fw-normal">Pts</small>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

    <?php endif; ?>

</div>

<!-- Modal Panduan Sistem Penilaian Poin -->
<div class="modal fade" id="scoringRulesModal" tabindex="-1" aria-labelledby="scoringRulesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-50 shadow-lg">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title font-heading fs-6 d-flex align-items-center gap-2" id="scoringRulesModalLabel">
                    <i class="fa-solid fa-chart-line text-warning"></i> Panduan & Sistem Penilaian Poin Ranking MM
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-secondary small mb-4">
                    Sistem <strong>Ranking MM</strong> menghitung seluruh kontribusi, kedisiplinan, dan keaktifan Anda secara otomatis setiap bulan (direset setiap awal bulan baru) berdasarkan 3 pilar utama:
                </p>

                <div class="row g-3 mb-4">
                    <!-- Rule 1: Absensi -->
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 bg-body-secondary border border-success border-opacity-25 h-100">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-success text-white p-2 rounded-2"><i class="fa-solid fa-qrcode fs-6"></i></span>
                                <strong class="text-white style-tiny font-heading">1. Kehadiran Workshop</strong>
                            </div>
                            <ul class="text-secondary style-tiny m-0 ps-3 lh-lg">
                                <li><strong>+25 Poin:</strong> Hadir Tepat Waktu (Scan QR / PIN)</li>
                                <li><strong>+15 Poin:</strong> Hadir Terlambat</li>
                                <li><strong>+5 Poin:</strong> Izin / Sakit Terverifikasi</li>
                                <li><strong>0 Poin:</strong> Alpa (Tidak Hadir)</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Rule 2: Tugas -->
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 bg-body-secondary border border-info border-opacity-25 h-100">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-info text-dark p-2 rounded-2"><i class="fa-solid fa-list-check fs-6"></i></span>
                                <strong class="text-white style-tiny font-heading">2. Tugas & Nilai</strong>
                            </div>
                            <ul class="text-secondary style-tiny m-0 ps-3 lh-lg">
                                <li><strong>+20 Poin:</strong> Mengirimkan jawaban/berkas tugas</li>
                                <li><strong>+0.5 × Nilai:</strong> Tambahan poin evaluasi dari Pembina/BPH (contoh nilai 100 = <strong>+50 Poin</strong>)</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Rule 3: Feed -->
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 bg-body-secondary border border-warning border-opacity-25 h-100">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-warning text-dark p-2 rounded-2"><i class="fa-solid fa-square-rss fs-6"></i></span>
                                <strong class="text-white style-tiny font-heading">3. Beranda / Feed</strong>
                            </div>
                            <ul class="text-secondary style-tiny m-0 ps-3 lh-lg">
                                <li><strong>+1 Poin:</strong> Mempublikasikan status atau karya (Maks 20 Poin/Bln)</li>
                                <li><strong>+1 Poin:</strong> Memberikan komentar/diskusi di status (Maks 20 Poin/Bln)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="p-3 rounded-3 bg-black border border-secondary border-opacity-50 text-secondary style-tiny">
                    <i class="fa-solid fa-circle-info text-info me-1"></i> <strong>Catatan:</strong> Peringkat direset setiap tanggal 1 setiap bulannya. Raih posisi Top 10 dan buktikan dedikasi serta kreativitas Anda di Multimedia Club!
                </div>
            </div>
            <div class="modal-footer border-top border-secondary border-opacity-25">
                <button type="button" class="btn btn-red btn-sm px-4 rounded-pill font-monospace" data-bs-dismiss="modal">Saya Mengerti</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Reset Poin Ranking MM (Superadmin Only) -->
<?php if (session()->get('role_slug') === 'superadmin'): ?>
<div class="modal fade" id="resetPointsModal" tabindex="-1" aria-labelledby="resetPointsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-body-tertiary border border-danger border-opacity-50 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-bottom border-secondary border-opacity-25 bg-danger bg-opacity-10 py-3">
                <h5 class="modal-title font-heading fw-bold text-danger d-flex align-items-center gap-2 fs-6" id="resetPointsModalLabel">
                    <i class="fa-solid fa-triangle-exclamation fs-5"></i> Konfirmasi Reset Poin Ranking MM
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="<?= base_url('ranking/reset') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="period" value="<?= esc($selectedPeriod) ?>">

                <div class="modal-body p-4">
                    <div class="p-3 rounded-3 bg-danger bg-opacity-10 border border-danger border-opacity-25 mb-3">
                        <div class="d-flex gap-3">
                            <div class="text-danger fs-3">
                                <i class="fa-solid fa-rotate-left"></i>
                            </div>
                            <div>
                                <h6 class="text-danger fw-bold mb-1">Apakah Anda yakin ingin mereset perolehan poin?</h6>
                                <p class="text-secondary style-tiny m-0 lh-base">
                                    Tindakan ini akan menyetel ulang total skor seluruh anggota pada periode <strong><?= esc($monthName) ?> <?= esc($selectedYear) ?></strong> kembali menjadi <strong>0 Poin</strong>.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 rounded-3 bg-body-secondary border border-secondary border-opacity-25 text-body style-tiny">
                        <div class="fw-bold mb-2 text-info"><i class="fa-solid fa-shield-halved me-1"></i> Keamanan Data:</div>
                        <ul class="m-0 ps-3 text-secondary lh-lg">
                            <li>Data presensi, berkas tugas, dan nilai siswa <strong>tetap aman tersimpan</strong> di database.</li>
                            <li>Aktivitas baru yang dikerjakan siswa setelah reset akan dihitung mulai dari awal.</li>
                            <li>Anda dapat membatalkan / memulihkan skor awal kapan saja jika diperlukan.</li>
                        </ul>
                    </div>
                </div>

                <div class="modal-footer border-top border-secondary border-opacity-25 bg-body-tertiary d-flex align-items-center justify-content-between">
                    <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3.5 font-monospace" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-sm btn-danger rounded-pill px-4 font-monospace fw-bold shadow">
                        <i class="fa-solid fa-rotate-left me-1"></i> Ya, Reset Poin Periode Ini
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
