<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="row g-4 justify-content-center">
    <div class="col-lg-5">
        <!-- Permanent QR Card -->
        <div class="saas-card p-4 text-center border border-danger border-opacity-50 shadow-lg">
            <h5 class="text-white font-heading mb-1">Permanent Member QR Code</h5>
            <p class="text-secondary small mb-3">Tunjukkan QR ini ke Operator saat absensi sesi pertemuan</p>

            <div class="my-3 text-center">
                <canvas id="profile-qr-canvas" class="bg-white p-3 rounded-4 shadow-sm"></canvas>
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

            <form action="<?= base_url('profile') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <!-- Foto Profil Avatar Header -->
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom border-secondary border-opacity-25">
                    <div class="position-relative cursor-pointer" <?php if (!empty($user['avatar'])): ?>data-bs-toggle="modal" data-bs-target="#avatarFullModal" title="Klik untuk lihat foto ukuran penuh"<?php endif; ?>>
                        <?php if (!empty($user['avatar'])): ?>
                            <img src="<?= base_url($user['avatar']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-danger border-2 shadow-sm" style="width: 76px; height: 76px; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                            <span class="position-absolute bottom-0 end-0 bg-danger text-white rounded-circle p-1 d-flex align-items-center justify-content-center shadow" style="width: 22px; height: 22px; font-size: 0.65rem;" title="Lihat Foto Full">
                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                            </span>
                        <?php else: ?>
                            <div class="rounded-circle bg-danger bg-opacity-25 text-danger fw-bold d-flex align-items-center justify-content-center border border-danger border-opacity-50" style="width: 76px; height: 76px; font-size: 1.8rem;">
                                <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center justify-content-between">
                            <label class="form-label text-white small fw-bold mb-1">
                                <i class="fa-solid fa-camera text-danger me-1"></i> Foto Profil (Avatar)
                            </label>
                            <?php if (!empty($user['avatar'])): ?>
                                <button type="button" class="btn btn-sm btn-outline-light py-0 px-2 style-tiny" data-bs-toggle="modal" data-bs-target="#avatarFullModal">
                                    <i class="fa-solid fa-expand me-1"></i> Lihat Foto Full
                                </button>
                            <?php endif; ?>
                        </div>
                        <input type="file" name="avatar" class="form-control form-control-sm bg-dark text-white border-secondary mb-1" accept="image/png, image/jpeg, image/jpg, image/webp">
                        <div class="form-text text-secondary style-tiny">Format: JPG, PNG, WEBP (Maksimal 2MB).</div>

                        <?php if (!empty($user['avatar'])): ?>
                            <div class="form-check mt-1">
                                <input class="form-check-input" type="checkbox" name="remove_avatar" value="1" id="removeAvatarCheck">
                                <label class="form-check-label text-danger style-tiny" for="removeAvatarCheck">
                                    <i class="fa-solid fa-trash me-1"></i> Hapus foto profil ini (kembalikan ke default)
                                </label>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

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

<?php if (!empty($user['avatar'])): ?>
    <!-- Modal Preview Foto Profil Full -->
    <div class="modal fade" id="avatarFullModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
                <div class="modal-header border-bottom border-secondary border-opacity-25">
                    <h5 class="modal-title font-heading"><i class="fa-solid fa-image text-danger me-2"></i> Foto Profil Full - <?= esc($user['full_name']) ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <img src="<?= base_url($user['avatar']) ?>" alt="Foto Full" class="img-fluid rounded-3 shadow-lg border border-secondary border-opacity-50" style="max-height: 75vh; object-fit: contain;">
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25 justify-content-between">
                    <a href="<?= base_url($user['avatar']) ?>" target="_blank" class="btn btn-sm btn-outline-light">
                        <i class="fa-solid fa-external-link me-1"></i> Buka File Asli
                    </a>
                    <button type="button" class="btn btn-saas-dark btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        const uuid = "<?= esc($user['member_uuid']) ?>";
        if (typeof QRious !== 'undefined') {
            new QRious({
                element: document.getElementById("profile-qr-canvas"),
                value: uuid,
                size: 160,
                level: 'H'
            });
        }
    });
</script>
<?= $this->endSection() ?>
