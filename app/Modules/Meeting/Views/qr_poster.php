<?= $this->extend('layouts/master_public') ?>

<?= $this->section('content') ?>

<style>
    body { background-color: #09090b !important; }
    .poster-card {
        background: radial-gradient(circle at 50% 20%, rgba(220, 38, 38, 0.25) 0%, rgba(18, 18, 24, 0.95) 80%);
        border: 1px solid rgba(220, 38, 38, 0.4);
        border-radius: 24px;
        box-shadow: 0 0 50px rgba(220, 38, 38, 0.25);
    }
    .pin-display {
        background: rgba(0, 0, 0, 0.6);
        border: 2px dashed rgba(220, 38, 38, 0.5);
        border-radius: 16px;
        letter-spacing: 8px;
    }
</style>

<div class="container py-5">
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <a href="<?= base_url('admin/meetings') ?>" class="btn btn-saas-dark">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke CMS Pertemuan
        </a>
        <button onclick="window.print()" class="btn btn-red">
            <i class="fa-solid fa-print me-1"></i> Cetak / Proyeksikan Fullscreen
        </button>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <div class="poster-card p-4 p-md-5 text-center position-relative">
                
                <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom border-secondary border-opacity-25">
                    <div class="d-flex align-items-center gap-3">
                        <img src="<?= base_url('assets/logo-mm-2023.png') ?>" alt="MMC Logo" style="height: 48px;" class="rounded-3 p-1.5 bg-white shadow">
                        <div class="text-start">
                            <div class="fw-bold text-white font-heading lh-1 fs-5">MULTIMEDIA CLUB</div>
                            <small class="text-secondary font-monospace" style="font-size: 0.75rem;">SMAN 1 TAMANSARI</small>
                        </div>
                    </div>
                    <?php if ($meeting['status'] === 'active'): ?>
                        <span class="badge bg-danger px-3 py-2 font-monospace fs-6 animate-pulse">SESI AKTIF</span>
                    <?php else: ?>
                        <span class="badge bg-secondary px-3 py-2 font-monospace fs-6">STATUS: <?= strtoupper($meeting['status']) ?></span>
                    <?php endif; ?>
                </div>

                <span class="text-danger font-monospace fw-semibold small text-uppercase tracking-wider">PRESENSI DIGITAL PERTEMUAN</span>
                <h2 class="text-white font-heading fw-bold mt-1 mb-3"><?= esc($meeting['title']) ?></h2>

                <div class="d-flex justify-content-center gap-4 text-secondary small font-monospace mb-4">
                    <div><i class="fa-solid fa-calendar text-danger me-1"></i> <?= date('d F Y', strtotime($meeting['meeting_date'])) ?></div>
                    <div><i class="fa-solid fa-clock text-danger me-1"></i> <?= date('H:i', strtotime($meeting['start_time'])) ?> - <?= date('H:i', strtotime($meeting['end_time'])) ?> WIB</div>
                    <div><i class="fa-solid fa-location-dot text-danger me-1"></i> <?= esc($meeting['location'] ?: 'SMAN 1 Tamansari') ?></div>
                </div>

                <!-- Meeting QR Render -->
                <?php if (!empty($meeting['qr_token'])): ?>
                    <div class="my-4">
                        <canvas id="poster-qr-canvas" class="bg-white p-3 rounded-4 shadow-lg border border-4 border-danger"></canvas>
                    </div>
                    <p class="text-white small fw-medium mb-1">Scan QR Code ini menggunakan kamera scanner di portal siswa</p>
                    <small class="text-secondary font-monospace">Token: <?= esc($meeting['qr_token']) ?></small>
                <?php else: ?>
                    <div class="alert alert-warning my-4 py-3">
                        <i class="fa-solid fa-circle-info me-2"></i> Pertemuan belum diaktifkan. Klik tombol <strong>Aktifkan Sesi Ini</strong> pada dashboard untuk membuat QR token.
                    </div>
                <?php endif; ?>

                <!-- PIN Code Render -->
                <?php if (!empty($meeting['pin_code'])): ?>
                    <div class="mt-4 pt-3">
                        <small class="text-secondary font-monospace d-block mb-1">ATAU MASUKKAN 4-DIGIT PIN PRESENSI:</small>
                        <div class="pin-display d-inline-block py-2 px-4 text-warning font-monospace fs-1 fw-bold">
                            <?= esc($meeting['pin_code']) ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if (!empty($meeting['qr_token'])): ?>
<script>
    $(document).ready(function() {
        const qrData = "<?= esc($meeting['qr_token']) ?>";
        if (typeof QRious !== 'undefined') {
            new QRious({
                element: document.getElementById("poster-qr-canvas"),
                value: qrData,
                size: 240,
                level: 'H'
            });
        }
    });
</script>
<?php endif; ?>
<?= $this->endSection() ?>
