<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="row g-4 justify-content-center">
    <div class="col-lg-5">
        <!-- Permanent QR Card -->
        <div class="saas-card p-4 text-center border border-danger border-opacity-50 shadow-lg">
            <h5 class="text-white font-heading mb-1">Permanent Member QR Code</h5>
            <p class="text-secondary small mb-3">Tunjukkan QR ini ke Operator saat absensi sesi pertemuan</p>

            <div class="bg-white p-3 rounded-4 d-inline-block mx-auto mb-3">
                <div id="profile-qr-code"></div>
            </div>

            <div class="text-secondary font-monospace small mb-3">
                <div>Version: <span class="badge bg-dark border border-secondary text-secondary">v<?= esc($user['qr_version']) ?></span></div>
                <div class="mt-1" style="font-size: 0.7rem;">UUID: <span class="text-danger"><?= esc($user['member_uuid']) ?></span></div>
            </div>

            <a href="<?= base_url('admin/users/regenerate-qr/' . session()->get('user_id')) ?>" onclick="return confirm('Regenerasi QR Code akan membatalkan QR lama. Lanjutkan?')" class="btn btn-sm btn-outline-danger w-100">
                <i class="fa-solid fa-arrows-rotate me-1"></i> Regenerasi Permanent QR Saya
            </a>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="saas-card p-4">
            <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-user-gear text-danger me-2"></i> Pengaturan Profil Akun</h5>

            <form action="<?= base_url('profile') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium">Nama Lengkap</label>
                        <input type="text" name="full_name" class="form-control" value="<?= esc($user['full_name']) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= esc($user['email']) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium">NIS / NIP</label>
                        <input type="text" class="form-control" value="<?= esc($user['nis_nip']) ?>" disabled readonly>
                        <small class="text-secondary" style="font-size: 0.7rem;">Hubungi admin untuk mengubah NIS/NIP</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium">Kelas / Divisi</label>
                        <input type="text" name="class_dept" class="form-control" value="<?= esc($user['class_dept']) ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium">Nomor WhatsApp / HP</label>
                        <input type="text" name="phone" class="form-control" value="<?= esc($user['phone']) ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium">Password Baru (Opsional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ubah">
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top border-secondary border-opacity-25">
                    <button type="submit" class="btn btn-red px-4">
                        <i class="fa-solid fa-save me-1"></i> Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        const uuid = "<?= esc($user['member_uuid']) ?>";
        new QRCode(document.getElementById("profile-qr-code"), {
            text: uuid,
            width: 160,
            height: 160,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    });
</script>
<?= $this->endSection() ?>
