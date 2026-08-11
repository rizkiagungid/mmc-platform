<?= $this->extend('layouts/master_public') ?>

<?= $this->section('content') ?>
<section class="py-5" style="background: radial-gradient(circle at 50% 20%, rgba(220, 38, 38, 0.15) 0%, rgba(9, 9, 11, 1) 75%);">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-7">
                <div class="saas-card p-4 p-md-5 border border-danger border-opacity-25 shadow-lg">

                    <div class="text-center mb-4">
                        <div class="rounded-3 bg-danger bg-gradient d-inline-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px;">
                            <i class="fa-solid fa-user-plus text-white fs-4"></i>
                        </div>
                        <h3 class="text-white font-heading mb-1">Pendaftaran Anggota Baru</h3>
                        <p class="text-secondary small">Bergabung dengan Multimedia Club SMAN 1 Tamansari</p>
                    </div>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger border-0 bg-danger bg-opacity-25 text-danger small p-3 mb-4 rounded-3">
                            <ul class="mb-0 ps-3">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('register') ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-medium">Nama Lengkap</label>
                                <input type="text" name="full_name" class="form-control" placeholder="Contoh: Ahmad Fauzi" value="<?= old('full_name') ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-medium">NIS / NIP</label>
                                <input type="text" name="nis_nip" class="form-control" placeholder="Contoh: 222310105" value="<?= old('nis_nip') ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-medium">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control" placeholder="Contoh: fauzi_mmc" value="<?= old('username') ?>" pattern="^\S+$" title="Username tidak boleh mengandung spasi" onkeyup="this.value = this.value.replace(/\s+/g, '')" required>
                                <small class="text-secondary style-tiny d-block mt-1"><i class="fa-solid fa-circle-info text-info me-1"></i> Tanpa spasi</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-medium">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="fauzi@gmail.com" value="<?= old('email') ?>" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label text-secondary small fw-medium">Kelas, Ruang & Divisi</label>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <select name="class_grade" class="form-select bg-dark text-white border-secondary border-opacity-50" required>
                                            <option value="" disabled <?= !old('class_grade') ? 'selected' : '' ?>>-- Pilih Kelas --</option>
                                            <?php foreach (['X', 'XI', 'XII'] as $grade): ?>
                                                <option value="<?= $grade ?>" <?= old('class_grade') === $grade ? 'selected' : '' ?>>Kelas <?= $grade ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select name="class_room" class="form-select bg-dark text-white border-secondary border-opacity-50" required>
                                            <option value="" disabled <?= !old('class_room') ? 'selected' : '' ?>>-- Pilih Ruang --</option>
                                            <?php for ($r = 1; $r <= 10; $r++): ?>
                                                <option value="<?= $r ?>" <?= old('class_room') == $r ? 'selected' : '' ?>>Ruang <?= $r ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select name="division" class="form-select bg-dark text-white border-secondary border-opacity-50" required>
                                            <option value="" disabled <?= !old('division') ? 'selected' : '' ?>>-- Pilih Divisi --</option>
                                            <?php foreach (['Broadcasting', 'Programming'] as $div): ?>
                                                <option value="<?= $div ?>" <?= old('division') === $div ? 'selected' : '' ?>><?= $div ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-medium">Nomor WhatsApp / Phone <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" placeholder="081234567890" value="<?= old('phone') ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-medium"><i class="fa-solid fa-cake-candles text-warning me-1"></i> Tanggal Lahir <span class="text-secondary opacity-75 style-tiny">(Opsional - bisa diisi nanti)</span></label>
                                <input type="date" name="birth_date" class="form-control bg-dark text-white border-secondary border-opacity-50" value="<?= old('birth_date') ?>">
                            </div>

                            <div class="col-12">
                                <label class="form-label text-secondary small fw-medium"><i class="fa-solid fa-location-dot text-danger me-1"></i> Alamat Tempat Tinggal <span class="text-secondary opacity-75 style-tiny">(Opsional - bisa diisi nanti)</span></label>
                                <input type="text" name="address" class="form-control" placeholder="Contoh: Jl. Raya Ciapus No. 12, Tamansari, Bogor" value="<?= old('address') ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-medium"><i class="fa-brands fa-instagram text-danger me-1"></i> Instagram <span class="text-secondary opacity-75 style-tiny">(Opsional)</span></label>
                                <input type="text" name="social_instagram" class="form-control" placeholder="@username" value="<?= old('social_instagram') ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-medium"><i class="fa-brands fa-tiktok text-light me-1"></i> TikTok <span class="text-secondary opacity-75 style-tiny">(Opsional)</span></label>
                                <input type="text" name="social_tiktok" class="form-control" placeholder="@username TikTok" value="<?= old('social_tiktok') ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-medium"><i class="fa-brands fa-facebook text-primary me-1"></i> Facebook <span class="text-secondary opacity-75 style-tiny">(Opsional)</span></label>
                                <input type="text" name="social_facebook" class="form-control" placeholder="Nama Akun / URL Facebook" value="<?= old('social_facebook') ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-medium">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-medium">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password" required>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-red w-100 py-2.5 fw-semibold">
                                <i class="fa-solid fa-paper-plane me-2"></i> Kirim Pendaftaran
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4 text-secondary small">
                        Sudah memiliki akun? <a href="<?= base_url('login') ?>" class="text-danger font-semibold">Login Masuk</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>