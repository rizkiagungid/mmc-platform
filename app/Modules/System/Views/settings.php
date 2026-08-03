<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Pengaturan Sistem & Platform</h4>
        <p class="text-secondary small m-0">Konfigurasi nama klub, alamat sekolah, kontak email, dan aturan platform</p>
    </div>
</div>

<div class="saas-card p-4 col-lg-8 mx-auto">
    <form action="<?= base_url('admin/settings') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="mb-4">
            <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-sliders text-danger me-2"></i> Informasi Umum Organisasi</h5>

            <div class="mb-3">
                <label class="form-label text-secondary small fw-medium">Nama Ekstrakurikuler / Klub</label>
                <input type="text" name="site_title" class="form-control" value="<?= esc($settings['site_title'] ?? 'Multimedia Club SMAN 1 Tamansari') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-secondary small fw-medium">Nama Sekolah / Instansi</label>
                <input type="text" name="school_name" class="form-control" value="<?= esc($settings['school_name'] ?? 'SMAN 1 Tamansari') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-secondary small fw-medium">Email Resmi Kontak</label>
                <input type="email" name="contact_email" class="form-control" value="<?= esc($settings['contact_email'] ?? 'multimedia@sman1tamansari.sch.id') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-secondary small fw-medium">Alamat Sekolah / Sekretariat MMC</label>
                <textarea name="school_address" class="form-control" rows="2"><?= esc($settings['school_address'] ?? 'Jl. Raya Tamansari No. 1, Kab. Bogor, Jawa Barat') ?></textarea>
            </div>
        </div>

        <div class="mb-4 pt-3 border-top border-secondary border-opacity-25">
            <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-lock text-danger me-2"></i> Keamanan & Presensi</h5>

            <div class="mb-3">
                <label class="form-label text-secondary small fw-medium">Durasi Masa Berlaku Token QR Pertemuan (Menit)</label>
                <input type="number" name="qr_expiry_minutes" class="form-control" value="<?= esc($settings['qr_expiry_minutes'] ?? 15) ?>" min="1" max="120" required>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 border-top border-secondary border-opacity-25 pt-3">
            <button type="submit" class="btn btn-red px-4">
                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
