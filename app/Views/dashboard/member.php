<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<!-- Welcome & Active Meeting Row -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="saas-card p-4 h-100 position-relative overflow-hidden" style="background: radial-gradient(circle at 10% 20%, rgba(220, 38, 38, 0.2) 0%, rgba(18, 18, 24, 1) 70%);">
            <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 px-3 py-1 rounded-pill mb-2 font-monospace">MEMBER PORTAL</span>
            <h2 class="text-white font-heading fw-bold mb-2">Halo, <?= esc($user['full_name']) ?>!</h2>
            <p class="text-secondary small mb-4">Selamat datang di Portal Anggota Multimedia Club SMAN 1 Tamansari. Selalu pantau presensi dan pengumpulan tugasmu di sini.</p>

            <?php if ($activeMeeting): ?>
                <div class="p-3 rounded-3 bg-dark border border-danger border-opacity-50 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                    <div>
                        <span class="badge bg-danger mb-1"><i class="fa-solid fa-signal me-1"></i> PERTEMUAN AKTIF SEKARANG</span>
                        <h5 class="text-white font-heading m-0"><?= esc($activeMeeting['title']) ?></h5>
                        <small class="text-secondary"><i class="fa-solid fa-clock me-1 text-danger"></i> <?= esc($activeMeeting['start_time']) ?> - <?= esc($activeMeeting['end_time']) ?> WIB @ <?= esc($activeMeeting['location']) ?></small>
                    </div>
                    <a href="<?= base_url('attendance/scan') ?>" class="btn btn-red px-4 py-2 flex-shrink-0">
                        <i class="fa-solid fa-qrcode me-2"></i> Scan Presensi QR / PIN
                    </a>
                </div>
            <?php else: ?>
                <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 text-secondary small">
                    <i class="fa-solid fa-circle-info me-2 text-info"></i> Belum ada sesi pertemuan yang dibuka saat ini.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Member Permanent QR Preview Card -->
    <div class="col-lg-4">
        <div class="saas-card p-4 text-center h-100 border border-secondary border-opacity-25">
            <h6 class="text-white font-heading mb-2">ID Member & Permanent QR</h6>
            <p class="text-secondary small mb-3">Tunjukkan QR ini ke Operator / Pembina saat absensi manual</p>

            <canvas id="member-qr-canvas" class="bg-white p-2 rounded-3 mx-auto mb-3"></canvas>

            <div class="small text-secondary font-monospace">
                <div>NIS/NIP: <strong class="text-white"><?= esc($user['nis_nip'] ?: '-') ?></strong></div>
                <div>Version: <span class="badge bg-dark border border-secondary">v<?= esc($user['qr_version']) ?></span></div>
            </div>

            <a href="<?= base_url('profile') ?>" class="btn btn-sm btn-saas-dark w-100 mt-3">
                <i class="fa-solid fa-id-card me-1"></i> Pengaturan QR & Profil
            </a>
        </div>
    </div>
</div>

<!-- Member Tasks & Attendance History -->
<div class="row g-4">
    <div class="col-lg-7">
        <div class="saas-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="text-white font-heading m-0"><i class="fa-solid fa-list-check text-danger me-2"></i> Tugas Ditugaskan Kepada Anda</h5>
                <a href="<?= base_url('member/tasks') ?>" class="small text-danger">Lihat Semua Tugas</a>
            </div>

            <?php if (empty($myTasks)): ?>
                <div class="text-center py-4 text-secondary small">Belum ada tugas yang ditugaskan kepada Anda saat ini.</div>
            <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($myTasks as $t): ?>
                        <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>
                                    <span class="badge me-1" style="background-color: <?= $t['priority_color'] ?>;"><?= esc($t['priority_name']) ?></span>
                                    <span class="badge" style="background-color: <?= $t['my_status_color'] ?? '#3b82f6' ?>;"><?= esc($t['my_status_name'] ?? 'Todo') ?></span>
                                </div>
                                <?php if (!empty($t['is_submitted']) || !empty($t['my_submission'])): ?>
                                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25"><i class="fa-solid fa-circle-check me-1"></i> sudah dikirim</span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25"><i class="fa-solid fa-circle-xmark me-1"></i> belum dikirim</span>
                                <?php endif; ?>
                            </div>
                            <h6 class="text-white font-heading mb-2"><?= esc($t['title']) ?></h6>
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
                <h5 class="text-white font-heading m-0"><i class="fa-solid fa-history text-danger me-2"></i> Riwayat Presensi Terakhir</h5>
                <a href="<?= base_url('attendance/history') ?>" class="small text-danger">Semua Riwayat</a>
            </div>

            <?php if (empty($myAttendances)): ?>
                <div class="text-center py-4 text-secondary small">Belum ada riwayat presensi recorded.</div>
            <?php else: ?>
                <div class="d-flex flex-column gap-2">
                    <?php foreach (array_slice($myAttendances, 0, 5) as $att): ?>
                        <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-white font-heading m-0 small"><?= esc($att['meeting_title']) ?></h6>
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
