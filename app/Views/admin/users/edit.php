<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <a href="<?= base_url('admin/users') ?>" class="btn btn-sm btn-saas-dark mb-2">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Anggota
    </a>
    <h4 class="text-white font-heading m-0">Edit Pengguna: <?= esc($user['full_name']) ?></h4>
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

    <form action="<?= base_url('admin/users/update/' . $user['id']) ?>" method="POST">
        <?= csrf_field() ?>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">Role Hak Akses</label>
                <select name="role_id" class="form-select" required>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['id'] ?>" <?= $user['role_id'] == $r['id'] ? 'selected' : '' ?>><?= esc($r['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">Status Akun</label>
                <select name="status" class="form-select" required>
                    <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Aktif (Active)</option>
                    <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>Non-Aktif (Inactive)</option>
                    <option value="suspended" <?= $user['status'] === 'suspended' ? 'selected' : '' ?>>Ditangguhkan (Suspended)</option>
                    <option value="left" <?= ($user['status'] === 'left' || $user['status'] === 'keluar') ? 'selected' : '' ?>>Keluar Ekskul</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">Nama Lengkap</label>
                <input type="text" name="full_name" class="form-control" value="<?= esc($user['full_name']) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">Username</label>
                <input type="text" name="username" class="form-control" value="<?= esc($user['username']) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">Email</label>
                <input type="email" name="email" class="form-control" value="<?= esc($user['email']) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">NIS / NIP</label>
                <input type="text" name="nis_nip" class="form-control" value="<?= esc($user['nis_nip']) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">Kelas / Divisi</label>
                <input type="text" name="class_dept" class="form-control" value="<?= esc($user['class_dept']) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">Phone / WhatsApp</label>
                <input type="text" name="phone" class="form-control" value="<?= esc($user['phone']) ?>">
            </div>

            <div class="col-md-12">
                <label class="form-label text-secondary small fw-medium">Password Baru (Opsional)</label>
                <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
            </div>
        </div>

        <div class="mt-4 pt-3 border-top border-secondary border-opacity-25 d-flex gap-2">
            <button type="submit" class="btn btn-red px-4">
                <i class="fa-solid fa-save me-1"></i> Perbarui Data
            </button>
            <a href="<?= base_url('admin/users') ?>" class="btn btn-saas-dark">Batal</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
