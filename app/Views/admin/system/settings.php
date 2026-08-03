<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <h4 class="text-white font-heading m-0">Pengaturan Aplikasi & Klub</h4>
    <p class="text-secondary small">Konfigurasi identitas organisasi, email, nomor kontak, dan opsi maintenance</p>
</div>

<div class="saas-card p-4 col-lg-8">
    <form action="<?= base_url('admin/settings') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">Nama Organisasi / Klub</label>
                <input type="text" name="site_title" class="form-control" value="<?= esc($settings['site_title'] ?? '') ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">Tagline / Sub-judul</label>
                <input type="text" name="site_tagline" class="form-control" value="<?= esc($settings['site_tagline'] ?? '') ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">Email Resmi Klub</label>
                <input type="email" name="club_email" class="form-control" value="<?= esc($settings['club_email'] ?? '') ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">Nomor Telepon / WhatsApp</label>
                <input type="text" name="club_phone" class="form-control" value="<?= esc($settings['club_phone'] ?? '') ?>">
            </div>

            <div class="col-md-12">
                <label class="form-label text-secondary small fw-medium">Alamat Sekolah / Sekretariat</label>
                <input type="text" name="club_address" class="form-control" value="<?= esc($settings['club_address'] ?? '') ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">Pendaftaran Anggota Baru</label>
                <select name="registration_open" class="form-select">
                    <option value="1" <?= ($settings['registration_open'] ?? '1') === '1' ? 'selected' : '' ?>>Buka (Open Registration)</option>
                    <option value="0" <?= ($settings['registration_open'] ?? '1') === '0' ? 'selected' : '' ?>>Tutup (Closed)</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">Mode Pemeliharaan (Maintenance Mode)</label>
                <select name="maintenance_mode" class="form-select">
                    <option value="0" <?= ($settings['maintenance_mode'] ?? '0') === '0' ? 'selected' : '' ?>>Non-Aktif (Normal Operasi)</option>
                    <option value="1" <?= ($settings['maintenance_mode'] ?? '0') === '1' ? 'selected' : '' ?>>Aktifkan Maintenance</option>
                </select>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top border-secondary border-opacity-25">
            <button type="submit" class="btn btn-red px-4">
                <i class="fa-solid fa-save me-1"></i> Simpan Konfigurasi
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
