<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="container-fluid p-0">

    <!-- Header Navigation Back Bar -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <a href="<?= base_url('admin/informasi') ?>" class="btn btn-saas-dark rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" title="Kembali ke Kelola Informasi">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="text-body font-heading m-0 fw-bold">Buat Informasi Baru</h4>
                <p class="text-secondary style-tiny m-0">Tambah pengumuman atau informasi baru untuk anggota MMC</p>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show bg-danger bg-opacity-25 border-danger text-white style-tiny mb-3" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-1.5"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close btn-close-white style-tiny" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="saas-card p-4 border border-secondary border-opacity-25 bg-body-tertiary">
        <form action="<?= base_url('admin/informasi/store') ?>" method="POST">
            <?= csrf_field() ?>

            <div class="row g-3">
                <!-- Judul Informasi -->
                <div class="col-12 col-md-8">
                    <label class="form-label text-body style-tiny fw-bold">Judul Informasi <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control bg-body border-secondary border-opacity-50 text-body style-tiny" placeholder="Contoh: Jadwal Pengambilan Kartu Anggota Ekskul MMC..." value="<?= old('title') ?>" required>
                </div>

                <!-- Jenis Informasi (Category) -->
                <div class="col-12 col-md-4">
                    <label class="form-label text-body style-tiny fw-bold">Jenis Informasi <span class="text-danger">*</span></label>
                    <select name="category" class="form-select bg-body border-secondary border-opacity-50 text-body style-tiny" required>
                        <option value="" disabled selected>-- Pilih Jenis Informasi --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= esc($cat) ?>" <?= old('category') === $cat ? 'selected' : '' ?>>
                                <?= esc($cat) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Waktu / Tanggal -->
                <div class="col-12 col-md-6">
                    <label class="form-label text-body style-tiny fw-bold">Waktu & Tanggal Informasi <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="date_time" class="form-control bg-body border-secondary border-opacity-50 text-body style-tiny" value="<?= old('date_time', date('Y-m-d\TH:i')) ?>" required>
                </div>

                <!-- Status Publikasi -->
                <div class="col-12 col-md-6">
                    <label class="form-label text-body style-tiny fw-bold">Status Publikasi</label>
                    <select name="status" class="form-select bg-body border-secondary border-opacity-50 text-body style-tiny">
                        <option value="active" <?= old('status') === 'active' ? 'selected' : '' ?>>Aktif (Tampilkan)</option>
                        <option value="archived" <?= old('status') === 'archived' ? 'selected' : '' ?>>Arsip (Sembunyikan)</option>
                    </select>
                </div>

                <!-- Mode Popup Switch -->
                <div class="col-12">
                    <div class="p-3 rounded-3 bg-body border border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
                        <div>
                            <strong class="text-body style-tiny d-block"><i class="fa-solid fa-window-restore text-warning me-1.5"></i> Tampilkan Sebagai Popup Modal?</strong>
                            <small class="text-secondary style-tiny d-block mt-0.5">Jika diaktifkan, informasi ini akan muncul sebagai popup modal di layar anggota saat mereka membuka aplikasi sampai mereka mengklik tombol <strong>"Sudah Dibaca"</strong>.</small>
                        </div>
                        <div class="form-check form-switch m-0 ms-3">
                            <input class="form-check-input cursor-pointer" type="checkbox" name="is_popup" value="1" id="is_popup_switch" <?= old('is_popup') ? 'checked' : '' ?> style="width: 2.5em; height: 1.3em;">
                        </div>
                    </div>
                </div>

                <!-- Deskripsi Informasi -->
                <div class="col-12">
                    <label class="form-label text-body style-tiny fw-bold">Deskripsi / Isi Informasi Detail <span class="text-danger">*</span></label>
                    <textarea name="description" rows="7" class="form-control bg-body border-secondary border-opacity-50 text-body style-tiny" placeholder="Tuliskan isi pengumuman atau informasi secara lengkap dan jelas..." required><?= old('description') ?></textarea>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top border-secondary border-opacity-25 d-flex align-items-center justify-content-end gap-2">
                <a href="<?= base_url('admin/informasi') ?>" class="btn btn-sm btn-saas-dark text-secondary border border-secondary border-opacity-25 px-4 rounded-pill">
                    Batal
                </a>
                <button type="submit" class="btn btn-sm btn-red px-4 rounded-pill">
                    <i class="fa-solid fa-paper-plane me-1"></i> Simpan & Publikasikan
                </button>
            </div>

        </form>
    </div>

</div>

<?= $this->endSection() ?>
