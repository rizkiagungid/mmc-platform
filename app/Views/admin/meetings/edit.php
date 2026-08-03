<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <a href="<?= base_url('admin/meetings') ?>" class="btn btn-sm btn-saas-dark mb-2">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Pertemuan
    </a>
    <h4 class="text-white font-heading m-0">Edit Pertemuan: <?= esc($meeting['title']) ?></h4>
</div>

<div class="saas-card p-4 col-lg-8">
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger border-0 bg-danger bg-opacity-25 text-danger small p-3 mb-4 rounded-3">
            <ul class="mb-0 ps-3">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/meetings/update/' . $meeting['id']) ?>" method="POST">
        <?= csrf_field() ?>

        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label text-secondary small fw-medium">Judul Pertemuan</label>
                <input type="text" name="title" class="form-control" value="<?= esc($meeting['title']) ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label text-secondary small fw-medium">Status Sesi</label>
                <select name="status" class="form-select" required>
                    <option value="draft" <?= $meeting['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="active" <?= $meeting['status'] === 'active' ? 'selected' : '' ?>>Active (Buka Presensi)</option>
                    <option value="completed" <?= $meeting['status'] === 'completed' ? 'selected' : '' ?>>Completed (Selesai)</option>
                    <option value="cancelled" <?= $meeting['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>

            <div class="col-md-12">
                <label class="form-label text-secondary small fw-medium">Deskripsi Kegiatan</label>
                <textarea name="description" class="form-control" rows="3"><?= esc($meeting['description']) ?></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">Mentor / Pemateri</label>
                <input type="text" name="mentor" class="form-control" value="<?= esc($meeting['mentor']) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">Lokasi / Ruangan</label>
                <input type="text" name="location" class="form-control" value="<?= esc($meeting['location']) ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label text-secondary small fw-medium">Tanggal Pertemuan</label>
                <input type="date" name="meeting_date" class="form-control" value="<?= esc($meeting['meeting_date']) ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label text-secondary small fw-medium">Jam Mulai</label>
                <input type="time" name="start_time" class="form-control" value="<?= esc($meeting['start_time']) ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label text-secondary small fw-medium">Jam Selesai</label>
                <input type="time" name="end_time" class="form-control" value="<?= esc($meeting['end_time']) ?>" required>
            </div>

            <div class="col-md-12">
                <label class="form-label text-secondary small fw-medium">Link Tautan Materi</label>
                <input type="url" name="learning_material" class="form-control" value="<?= esc($meeting['learning_material']) ?>">
            </div>
        </div>

        <div class="mt-4 pt-3 border-top border-secondary border-opacity-25 d-flex gap-2">
            <button type="submit" class="btn btn-red px-4">
                <i class="fa-solid fa-save me-1"></i> Perbarui Pertemuan
            </button>
            <a href="<?= base_url('admin/meetings') ?>" class="btn btn-saas-dark">Batal</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
