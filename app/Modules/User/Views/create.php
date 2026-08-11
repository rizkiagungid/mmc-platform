<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <a href="<?= base_url('admin/users') ?>" class="btn btn-sm btn-saas-dark mb-2">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Anggota
    </a>
    <h4 class="text-white font-heading m-0">Tambah Anggota / Pengguna Baru</h4>
</div>

<div class="saas-card p-4 col-lg-10">
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger border-0 bg-danger bg-opacity-25 text-danger small p-3 mb-4 rounded-3">
            <ul class="mb-0 ps-3">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/users/store') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="row g-3">
            <!-- Informasi Utama -->
            <div class="col-12">
                <h6 class="text-white font-heading border-bottom border-secondary border-opacity-25 pb-2 mb-1">
                    <i class="fa-solid fa-user-gear me-2 text-red"></i>Informasi Utama Akun
                </h6>
            </div>

            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">Role Hak Akses <span class="text-danger">*</span></label>
                <select name="role_id" class="form-select" required>
                    <?php foreach ($roles as $r): ?>
                        <?php if (session()->get('role_slug') !== 'superadmin' && $r['id'] == 1) continue; ?>
                        <?php if (session()->get('role_slug') === 'bph' && $r['id'] == 2) continue; ?>
                        <option value="<?= $r['id'] ?>" <?= old('role_id') == $r['id'] ? 'selected' : '' ?>><?= esc($r['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="full_name" class="form-control" value="<?= old('full_name') ?>" placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">Username <span class="text-danger">*</span></label>
                <input type="text" name="username" class="form-control" value="<?= old('username') ?>" placeholder="Username unik tanpa spasi" required>
            </div>

            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" value="<?= old('email') ?>" placeholder="contoh@domain.com" required>
            </div>

            <div class="col-md-4">
                <label class="form-label text-secondary small fw-medium">NIS / NIP</label>
                <input type="text" name="nis_nip" class="form-control" value="<?= old('nis_nip') ?>" placeholder="Nomor Induk">
            </div>

            <div class="col-md-4">
                <label class="form-label text-secondary small fw-medium">Phone / WhatsApp</label>
                <input type="text" name="phone" class="form-control" value="<?= old('phone') ?>" placeholder="08xxxxxxxxxx">
            </div>

            <div class="col-md-4">
                <label class="form-label text-secondary small fw-medium">Tanggal Lahir</label>
                <input type="date" name="birth_date" class="form-control" value="<?= old('birth_date') ?>">
            </div>

            <div class="col-md-12">
                <label class="form-label text-secondary small fw-medium">Kelas, Ruang & Divisi</label>
                <div class="row g-2">
                    <div class="col-md-4">
                        <select name="class_grade" class="form-select">
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach (['X', 'XI', 'XII'] as $grade): ?>
                                <option value="<?= $grade ?>" <?= old('class_grade') === $grade ? 'selected' : '' ?>>Kelas <?= $grade ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="class_room" class="form-select">
                            <option value="">-- Pilih Ruang --</option>
                            <?php for ($r = 1; $r <= 10; $r++): ?>
                                <option value="<?= $r ?>" <?= old('class_room') == $r ? 'selected' : '' ?>>Ruang <?= $r ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="division" class="form-select">
                            <option value="">-- Pilih Divisi --</option>
                            <?php foreach (['Broadcasting', 'Programming'] as $div): ?>
                                <option value="<?= $div ?>" <?= old('division') === $div ? 'selected' : '' ?>><?= $div ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Alamat Lengkap -->
            <div class="col-12 mt-4">
                <h6 class="text-white font-heading border-bottom border-secondary border-opacity-25 pb-2 mb-1">
                    <i class="fa-solid fa-map-location-dot me-2 text-red"></i>Alamat Tempat Tinggal
                </h6>
            </div>

            <div class="col-md-12">
                <label class="form-label text-secondary small fw-medium">Alamat Lengkap</label>
                <textarea name="address" class="form-control" rows="3" placeholder="Masukkan alamat lengkap rumah / tempat tinggal"><?= old('address') ?></textarea>
            </div>

            <!-- Social Media -->
            <div class="col-12 mt-4">
                <h6 class="text-white font-heading border-bottom border-secondary border-opacity-25 pb-2 mb-1">
                    <i class="fa-solid fa-share-nodes me-2 text-red"></i>Akun Media Sosial (Opsional)
                </h6>
            </div>

            <div class="col-md-4">
                <label class="form-label text-secondary small fw-medium">
                    <i class="fa-brands fa-instagram text-danger me-1"></i> Instagram
                </label>
                <input type="text" name="social_instagram" class="form-control" value="<?= old('social_instagram') ?>" placeholder="@username atau URL">
            </div>

            <div class="col-md-4">
                <label class="form-label text-secondary small fw-medium">
                    <i class="fa-brands fa-tiktok text-white me-1"></i> TikTok
                </label>
                <input type="text" name="social_tiktok" class="form-control" value="<?= old('social_tiktok') ?>" placeholder="@username atau URL">
            </div>

            <div class="col-md-4">
                <label class="form-label text-secondary small fw-medium">
                    <i class="fa-brands fa-facebook text-primary me-1"></i> Facebook
                </label>
                <input type="text" name="social_facebook" class="form-control" value="<?= old('social_facebook') ?>" placeholder="Username atau URL">
            </div>

            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">
                    <i class="fa-brands fa-linkedin text-info me-1"></i> LinkedIn
                </label>
                <input type="text" name="social_linkedin" class="form-control" value="<?= old('social_linkedin') ?>" placeholder="Username atau URL">
            </div>

            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">
                    <i class="fa-brands fa-github text-white me-1"></i> GitHub
                </label>
                <input type="text" name="social_github" class="form-control" value="<?= old('social_github') ?>" placeholder="Username atau URL">
            </div>

            <!-- Password -->
            <div class="col-12 mt-4">
                <h6 class="text-white font-heading border-bottom border-secondary border-opacity-25 pb-2 mb-1">
                    <i class="fa-solid fa-lock me-2 text-red"></i>Keamanan Akun
                </h6>
            </div>

            <div class="col-md-12">
                <label class="form-label text-secondary small fw-medium">Password Awal <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top border-secondary border-opacity-25 d-flex gap-2">
            <button type="submit" class="btn btn-red px-4">
                <i class="fa-solid fa-save me-1"></i> Simpan Pengguna
            </button>
            <a href="<?= base_url('admin/users') ?>" class="btn btn-saas-dark">Batal</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
