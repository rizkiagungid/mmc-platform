<?= $this->extend('layouts/master_public') ?>

<?= $this->section('content') ?>
<section class="py-5" style="background: radial-gradient(circle at 50% 20%, rgba(220, 38, 38, 0.15) 0%, rgba(9, 9, 11, 1) 75%);">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-7">
                <div class="saas-card p-4 p-md-5 border border-danger border-opacity-25 shadow-lg">

                    <div class="text-center mb-4">
                        <div class="rounded-3 bg-danger bg-gradient d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 48px; height: 48px;">
                            <i class="fa-solid fa-user-plus text-white fs-4"></i>
                        </div>
                        <h3 class="text-white font-heading mb-1 fw-bold">Pendaftaran Anggota Baru</h3>
                        <p class="text-secondary small">Bergabung dengan Multimedia Club SMAN 1 Tamansari</p>
                    </div>

                    <?php 
                        $errors = session()->getFlashdata('errors') ?: [];
                        $errorMsg = session()->getFlashdata('error');
                    ?>

                    <?php if ($errorMsg): ?>
                        <div class="alert alert-danger border border-danger border-opacity-50 bg-danger bg-opacity-10 text-danger small p-3 mb-4 rounded-3 shadow-sm d-flex align-items-center gap-2">
                            <i class="fa-solid fa-triangle-exclamation fs-5 flex-shrink-0"></i>
                            <div><?= esc($errorMsg) ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger border border-danger border-opacity-50 bg-danger bg-opacity-10 text-danger small p-3 mb-4 rounded-3 shadow-sm">
                            <div class="d-flex align-items-center gap-2 mb-2 fw-bold fs-6">
                                <i class="fa-solid fa-circle-exclamation fs-5"></i> Mohon Periksa Kesalahan Pengisian Formulir Berikut:
                            </div>
                            <ul class="mb-0 ps-3 lh-lg">
                                <?php foreach ($errors as $field => $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('register') ?>" method="POST" autocomplete="off">
                        <?= csrf_field() ?>

                        <div class="row g-3">
                            <!-- Pilihan Tipe Keanggotaan: Anggota Baru vs Alumni -->
                            <div class="col-12 mb-2">
                                <label class="form-label text-secondary small fw-medium d-block mb-2">
                                    <i class="fa-solid fa-id-badge text-danger me-1"></i> Tipe Keanggotaan <span class="text-danger">*</span>
                                </label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="radio" class="btn-check" name="member_type" id="type_member" value="member" <?= (old('member_type', 'member') === 'member') ? 'checked' : '' ?> onchange="toggleMemberType(this.value)">
                                        <label class="btn btn-outline-danger w-100 p-3 rounded-3 text-start d-flex align-items-center gap-2.5 h-100" for="type_member">
                                            <div class="p-2 rounded-circle bg-danger bg-opacity-25 text-danger flex-shrink-0">
                                                <i class="fa-solid fa-graduation-cap fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-white small">Anggota Baru</div>
                                                <small class="text-secondary style-tiny d-block">Siswa Aktif SMAN 1 Tamansari</small>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-6">
                                        <input type="radio" class="btn-check" name="member_type" id="type_alumni" value="alumni" <?= (old('member_type') === 'alumni') ? 'checked' : '' ?> onchange="toggleMemberType(this.value)">
                                        <label class="btn btn-outline-warning w-100 p-3 rounded-3 text-start d-flex align-items-center gap-2.5 h-100" for="type_alumni">
                                            <div class="p-2 rounded-circle bg-warning bg-opacity-25 text-warning flex-shrink-0">
                                                <i class="fa-solid fa-user-tie fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-white small">Alumni</div>
                                                <small class="text-secondary style-tiny d-block">Lulusan / Alumni Ekskul MMC</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Nama Lengkap -->
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control <?= isset($errors['full_name']) ? 'is-invalid border-danger' : '' ?>" placeholder="Contoh: Ahmad Fauzi" value="<?= old('full_name') ?>" required>
                                <?php if (isset($errors['full_name'])): ?>
                                    <div class="invalid-feedback d-block style-tiny mt-1">
                                        <i class="fa-solid fa-circle-exclamation me-1"></i> <?= esc($errors['full_name']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- NIS / NIP -->
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-medium">NIS / NIP <span class="text-danger">*</span></label>
                                <input type="text" name="nis_nip" class="form-control <?= isset($errors['nis_nip']) ? 'is-invalid border-danger' : '' ?>" placeholder="Contoh: 222310105" value="<?= old('nis_nip') ?>" required>
                                <?php if (isset($errors['nis_nip'])): ?>
                                    <div class="invalid-feedback d-block style-tiny mt-1">
                                        <i class="fa-solid fa-circle-exclamation me-1"></i> <?= esc($errors['nis_nip']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Username -->
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-medium">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control <?= isset($errors['username']) ? 'is-invalid border-danger' : '' ?>" placeholder="Contoh: fauzi_mmc" value="<?= old('username') ?>" pattern="^\S+$" title="Username tidak boleh mengandung spasi" oninput="this.value = this.value.replace(/\s+/g, '')" required>
                                <small class="text-secondary style-tiny d-block mt-1">
                                    <i class="fa-solid fa-circle-info text-info me-1"></i> Wajib <strong>tanpa spasi</strong> (contoh: <code>ahmad_fauzi</code> atau <code>ahmad.fauzi</code>)
                                </small>
                                <?php if (isset($errors['username'])): ?>
                                    <div class="invalid-feedback d-block style-tiny mt-1">
                                        <i class="fa-solid fa-circle-exclamation me-1"></i> <?= esc($errors['username']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-medium">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid border-danger' : '' ?>" placeholder="fauzi@gmail.com" value="<?= old('email') ?>" required>
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback d-block style-tiny mt-1">
                                        <i class="fa-solid fa-circle-exclamation me-1"></i> <?= esc($errors['email']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Kelas, Ruang & Divisi -->
                            <div class="col-md-12" id="class_dept_container">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <label class="form-label text-secondary small fw-medium m-0">
                                        Kelas, Ruang & Divisi <span id="class_required_asterisk" class="text-danger">*</span>
                                    </label>
                                    <span id="class_lock_badge" class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 font-monospace style-tiny" style="display: none;">
                                        <i class="fa-solid fa-lock me-1"></i> Kelas & Ruangan Terkunci (Alumni)
                                    </span>
                                </div>

                                <div class="row g-2">
                                    <div class="col-md-4" id="class_grade_wrapper">
                                        <select name="class_grade" id="reg_class_grade" class="form-select bg-dark text-white border-secondary border-opacity-50 <?= isset($errors['class_grade']) ? 'is-invalid border-danger' : '' ?>" required>
                                            <option value="" disabled <?= !old('class_grade') ? 'selected' : '' ?>>-- Pilih Kelas --</option>
                                            <?php foreach (['X', 'XI', 'XII'] as $grade): ?>
                                                <option value="<?= $grade ?>" <?= old('class_grade') === $grade ? 'selected' : '' ?>>Kelas <?= $grade ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4" id="class_room_wrapper">
                                        <select name="class_room" id="reg_class_room" class="form-select bg-dark text-white border-secondary border-opacity-50 <?= isset($errors['class_room']) ? 'is-invalid border-danger' : '' ?>" required>
                                            <option value="" disabled <?= !old('class_room') ? 'selected' : '' ?>>-- Pilih Ruang --</option>
                                            <?php for ($r = 1; $r <= 10; $r++): ?>
                                                <option value="<?= $r ?>" <?= old('class_room') == $r ? 'selected' : '' ?>>Ruang <?= $r ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4" id="division_wrapper">
                                        <select name="division" id="reg_division" class="form-select bg-dark text-white border-secondary border-opacity-50 <?= isset($errors['division']) ? 'is-invalid border-danger' : '' ?>">
                                            <option value="" disabled <?= !old('division') ? 'selected' : '' ?>>-- Pilih Divisi --</option>
                                            <?php foreach (['Broadcasting', 'Programming'] as $div): ?>
                                                <option value="<?= $div ?>" <?= old('division') === $div ? 'selected' : '' ?>><?= $div ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <?php if (isset($errors['class_grade']) || isset($errors['class_room']) || isset($errors['division'])): ?>
                                    <div class="invalid-feedback d-block style-tiny mt-1">
                                        <i class="fa-solid fa-circle-exclamation me-1"></i> <?= esc($errors['class_grade'] ?? $errors['class_room'] ?? $errors['division']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- WhatsApp / Phone -->
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-medium">Nomor WhatsApp / HP Aktif <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control <?= isset($errors['phone']) ? 'is-invalid border-danger' : '' ?>" placeholder="Contoh: 081234567890" value="<?= old('phone') ?>" required>
                                <?php if (isset($errors['phone'])): ?>
                                    <div class="invalid-feedback d-block style-tiny mt-1">
                                        <i class="fa-solid fa-circle-exclamation me-1"></i> <?= esc($errors['phone']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Tanggal Lahir -->
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-medium"><i class="fa-solid fa-cake-candles text-warning me-1"></i> Tanggal Lahir <span class="text-secondary opacity-75 style-tiny">(Opsional)</span></label>
                                <input type="date" name="birth_date" class="form-control bg-dark text-white border-secondary border-opacity-50 <?= isset($errors['birth_date']) ? 'is-invalid border-danger' : '' ?>" value="<?= old('birth_date') ?>">
                                <?php if (isset($errors['birth_date'])): ?>
                                    <div class="invalid-feedback d-block style-tiny mt-1">
                                        <i class="fa-solid fa-circle-exclamation me-1"></i> <?= esc($errors['birth_date']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Alamat -->
                            <div class="col-12">
                                <label class="form-label text-secondary small fw-medium"><i class="fa-solid fa-location-dot text-danger me-1"></i> Alamat Tempat Tinggal <span class="text-secondary opacity-75 style-tiny">(Opsional)</span></label>
                                <input type="text" name="address" class="form-control <?= isset($errors['address']) ? 'is-invalid border-danger' : '' ?>" placeholder="Contoh: Jl. Raya Ciapus No. 12, Tamansari, Bogor" value="<?= old('address') ?>">
                                <?php if (isset($errors['address'])): ?>
                                    <div class="invalid-feedback d-block style-tiny mt-1">
                                        <i class="fa-solid fa-circle-exclamation me-1"></i> <?= esc($errors['address']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Sosmed -->
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

                            <!-- Password -->
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-medium">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control <?= isset($errors['password']) ? 'is-invalid border-danger' : '' ?>" placeholder="Minimal 6 karakter" required>
                                <small class="text-secondary style-tiny d-block mt-1">
                                    <i class="fa-solid fa-lock text-secondary me-1"></i> Minimal 6 karakter
                                </small>
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback d-block style-tiny mt-1">
                                        <i class="fa-solid fa-circle-exclamation me-1"></i> <?= esc($errors['password']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Konfirmasi Password -->
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-medium">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" name="confirm_password" class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid border-danger' : '' ?>" placeholder="Ulangi password di atas" required>
                                <small class="text-secondary style-tiny d-block mt-1">
                                    <i class="fa-solid fa-check-double text-secondary me-1"></i> Masukkan password yang sama persis
                                </small>
                                <?php if (isset($errors['confirm_password'])): ?>
                                    <div class="invalid-feedback d-block style-tiny mt-1">
                                        <i class="fa-solid fa-circle-exclamation me-1"></i> <?= esc($errors['confirm_password']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-red w-100 py-2.5 fw-semibold shadow">
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

<script>
    function toggleMemberType(type) {
        const gradeSelect = document.getElementById('reg_class_grade');
        const roomSelect = document.getElementById('reg_class_room');
        const classGradeWrapper = document.getElementById('class_grade_wrapper');
        const classRoomWrapper = document.getElementById('class_room_wrapper');
        const classRequiredAsterisk = document.getElementById('class_required_asterisk');
        const classLockBadge = document.getElementById('class_lock_badge');

        if (type === 'alumni') {
            if (gradeSelect) {
                gradeSelect.disabled = true;
                gradeSelect.removeAttribute('required');
                gradeSelect.value = '';
                gradeSelect.classList.add('opacity-50', 'bg-secondary', 'bg-opacity-10');
            }
            if (roomSelect) {
                roomSelect.disabled = true;
                roomSelect.removeAttribute('required');
                roomSelect.value = '';
                roomSelect.classList.add('opacity-50', 'bg-secondary', 'bg-opacity-10');
            }
            if (classRequiredAsterisk) classRequiredAsterisk.style.display = 'none';
            if (classLockBadge) classLockBadge.style.display = 'inline-block';
        } else {
            if (gradeSelect) {
                gradeSelect.disabled = false;
                gradeSelect.setAttribute('required', 'required');
                gradeSelect.classList.remove('opacity-50', 'bg-secondary', 'bg-opacity-10');
            }
            if (roomSelect) {
                roomSelect.disabled = false;
                roomSelect.setAttribute('required', 'required');
                roomSelect.classList.remove('opacity-50', 'bg-secondary', 'bg-opacity-10');
            }
            if (classRequiredAsterisk) classRequiredAsterisk.style.display = 'inline';
            if (classLockBadge) classLockBadge.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const checkedType = document.querySelector('input[name="member_type"]:checked')?.value || 'member';
        toggleMemberType(checkedType);
    });
</script>
<?= $this->endSection() ?>
