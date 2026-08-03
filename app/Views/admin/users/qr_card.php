<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="mb-4 d-flex align-items-center justify-content-between">
    <div>
        <a href="<?= base_url('admin/users') ?>" class="btn btn-sm btn-saas-dark mb-2">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Pengguna
        </a>
        <h4 class="text-white font-heading m-0">Digital Member Card & Permanent QR</h4>
    </div>
    <button onclick="window.print()" class="btn btn-red">
        <i class="fa-solid fa-print me-1"></i> Cetak / Simpan PDF
    </button>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-5">
        <!-- Member Card Element -->
        <div class="saas-card p-4 text-center border border-danger border-opacity-50 position-relative overflow-hidden shadow-lg" style="background: linear-gradient(135deg, #181824 0%, #09090b 100%);">
            
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom border-secondary border-opacity-25">
                <div class="d-flex align-items-center gap-2 text-start">
                    <img src="<?= base_url('assets/logo-mm-2023.png') ?>" alt="MMC Logo" style="height: 36px;" class="rounded-2 p-1 bg-white">
                    <div>
                        <div class="fw-bold text-white font-heading lh-1">MULTIMEDIA CLUB</div>
                        <small class="text-secondary font-monospace" style="font-size: 0.65rem;">SMAN 1 TAMANSARI</small>
                    </div>
                </div>
                <span class="badge bg-danger px-2.5 py-1 font-monospace">MEMBER CARD</span>
            </div>

            <div class="my-3">
                <div id="card-qr-render" class="bg-white p-3 rounded-4 d-inline-block shadow-sm"></div>
            </div>

            <h4 class="text-white font-heading fw-bold mt-3 mb-1"><?= esc($user['full_name']) ?></h4>
            <div class="text-secondary small font-monospace mb-2">@<?= esc($user['username']) ?></div>

            <div class="row g-2 justify-content-center mt-3 pt-3 border-top border-secondary border-opacity-25 font-monospace text-start small">
                <div class="col-6">
                    <span class="text-secondary d-block" style="font-size: 0.7rem;">NIS / NIP:</span>
                    <strong class="text-white"><?= esc($user['nis_nip'] ?: '-') ?></strong>
                </div>
                <div class="col-6">
                    <span class="text-secondary d-block" style="font-size: 0.7rem;">KELAS / DIVISI:</span>
                    <strong class="text-white"><?= esc($user['class_dept'] ?: '-') ?></strong>
                </div>
                <div class="col-12 mt-2">
                    <span class="text-secondary d-block" style="font-size: 0.7rem;">MEMBER UUID (QR DATA):</span>
                    <span class="text-danger small text-break"><?= esc($user['member_uuid']) ?></span>
                </div>
            </div>

            <div class="mt-4 pt-2 text-secondary" style="font-size: 0.65rem;">
                QR Version: <strong>v<?= esc($user['qr_version']) ?></strong> | Diperbarui: <?= date('d/m/Y H:i', strtotime($user['qr_updated_at'])) ?>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        const uuidData = "<?= esc($user['member_uuid']) ?>";
        new QRCode(document.getElementById("card-qr-render"), {
            text: uuidData,
            width: 180,
            height: 180,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    });
</script>
<?= $this->endSection() ?>
