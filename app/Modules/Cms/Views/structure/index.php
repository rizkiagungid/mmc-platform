<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<?php
$positions = [
    'Ketua Umum',
    'Wakil Ketua Umum',
    'Sekretaris 1',
    'Sekretaris 2',
    'Bendahara 1',
    'Bendahara 2',
    'Humas 1',
    'Humas 2',
    'Ketua Divisi Broadcasting',
    'Wakil Ketua Divisi Broadcasting',
    'Ketua Divisi Programing',
    'Wakil Ketua Divisi Programing',
    'PJ desain poster',
    'PJ Editing Video',
    'PJ backup',
];

$bphUsers    = array_filter($users ?? [], fn($u) => in_array($u['role_slug'] ?? '', ['bph', 'superadmin']));
$memberUsers = array_filter($users ?? [], fn($u) => ($u['role_slug'] ?? '') === 'member');
$otherUsers  = array_filter($users ?? [], fn($u) => !in_array($u['role_slug'] ?? '', ['bph', 'superadmin', 'member']));
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Manajemen Bagan Organisasi & Pengurus</h4>
        <p class="text-secondary small m-0">Kelola daftar BPH (Ketua, Wakil, Sekretaris, Bendahara, Humas), Koordinator Divisi, dan Penanggung Jawab (PJ)</p>
    </div>

    <button type="button" class="btn btn-red" data-bs-toggle="modal" data-bs-target="#addOrgModal">
        <i class="fa-solid fa-plus me-1"></i> Tambah Pengurus Baru
    </button>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success bg-success bg-opacity-25 border-0 text-success small mb-4 rounded-3 p-3">
        <i class="fa-solid fa-circle-check me-2"></i> <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger bg-danger bg-opacity-25 border-0 text-danger small mb-4 rounded-3 p-3">
        <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="saas-card p-4">
    <div class="row g-4 justify-content-center">
        <?php foreach ($structures as $s): ?>
            <div class="col-md-6 col-lg-4">
                <div class="saas-card p-3 border border-secondary border-opacity-25 text-center h-100 d-flex flex-column justify-content-between">
                    <div>
                        <?php if (!empty($s['photo'])): ?>
                            <div class="position-relative d-inline-block cursor-pointer mx-auto mb-3" data-bs-toggle="modal" data-bs-target="#viewPhotoModal<?= $s['id'] ?>" title="Klik untuk memperbesar foto">
                                <img src="<?= base_url($s['photo']) ?>" alt="<?= esc($s['name']) ?>" class="rounded-circle object-fit-cover border border-danger border-2 shadow-sm" style="width: 76px; height: 76px; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                                <span class="position-absolute bottom-0 end-0 bg-danger text-white rounded-circle p-1 d-flex align-items-center justify-content-center shadow" style="width: 22px; height: 22px; font-size: 0.65rem;" title="Lihat Foto Full">
                                    <i class="fa-solid fa-magnifying-glass-plus"></i>
                                </span>
                            </div>
                        <?php else: ?>
                            <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold fs-3 mx-auto mb-3" style="width: 70px; height: 70px;">
                                <?= strtoupper(substr($s['name'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>

                        <span class="badge bg-danger bg-opacity-25 text-danger font-monospace mb-2"><?= esc($s['position']) ?></span>
                        <h5 class="text-white font-heading mb-1"><?= esc($s['name']) ?></h5>
                        <p class="text-secondary small mb-3"><?= esc($s['bio'] ?: 'Pengurus Aktif MMC') ?></p>
                    </div>

                    <div class="mt-3 pt-2 border-top border-secondary border-opacity-10 d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                            <?php if ($s['instagram']): ?>
                                <a href="<?= esc($s['instagram']) ?>" target="_blank" class="text-secondary hover-white"><i class="fab fa-instagram"></i></a>
                            <?php endif; ?>
                            <?php if ($s['linkedin']): ?>
                                <a href="<?= esc($s['linkedin']) ?>" target="_blank" class="text-secondary hover-white"><i class="fab fa-linkedin"></i></a>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#editOrgModal<?= $s['id'] ?>">
                                <i class="fa-solid fa-pen"></i> Edit
                            </button>
                            <a href="<?= base_url('admin/cms/structure/delete/' . $s['id']) ?>" onclick="return confirm('Hapus pengurus ini?')" class="btn btn-sm btn-outline-danger">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($s['photo'])): ?>
                <!-- Modal Preview Foto Full Pengurus -->
                <div class="modal fade" id="viewPhotoModal<?= $s['id'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25 shadow-lg">
                            <div class="modal-header border-bottom border-secondary border-opacity-25">
                                <h5 class="modal-title font-heading">
                                    <i class="fa-solid fa-image text-danger me-2"></i> Foto Pengurus - <?= esc($s['name']) ?>
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center p-4">
                                <img src="<?= base_url($s['photo']) ?>" alt="<?= esc($s['name']) ?>" class="img-fluid rounded-3 shadow-lg border border-secondary border-opacity-50" style="max-height: 75vh; object-fit: contain;">
                                <div class="mt-3">
                                    <h5 class="text-white font-heading mb-1"><?= esc($s['name']) ?></h5>
                                    <span class="badge bg-danger font-monospace px-3 py-1"><?= esc($s['position']) ?></span>
                                    <?php if ($s['bio']): ?>
                                        <p class="text-secondary small mt-2 mb-0"><?= esc($s['bio']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="modal-footer border-top border-secondary border-opacity-25 justify-content-between">
                                <a href="<?= base_url($s['photo']) ?>" target="_blank" class="btn btn-sm btn-outline-light">
                                    <i class="fa-solid fa-external-link me-1"></i> Buka Gambar Ukuran Asli
                                </a>
                                <button type="button" class="btn btn-saas-dark btn-sm" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Modal Edit Pengurus -->
            <div class="modal fade" id="editOrgModal<?= $s['id'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content bg-dark text-white border border-secondary border-opacity-25 shadow-lg">
                        <div class="modal-header border-bottom border-secondary border-opacity-25">
                            <h5 class="modal-title font-heading"><i class="fa-solid fa-pen text-info me-2"></i> Edit Pengurus Organisasi</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="<?= base_url('admin/cms/structure/update/' . $s['id']) ?>" method="POST" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="modal-body">
                                <!-- Quick Select Anggota / BPH for Edit -->
                                <div class="mb-3 p-2.5 rounded-3 bg-black bg-opacity-25 border border-secondary border-opacity-25">
                                    <div class="d-flex align-items-center justify-content-between mb-1.5 flex-wrap gap-1">
                                        <label class="form-label text-warning style-tiny fw-bold m-0 d-flex align-items-center gap-1">
                                            <i class="fa-solid fa-bolt"></i> Ganti / Sinkronkan dari Anggota:
                                        </label>
                                        <button type="button" class="btn btn-sm btn-outline-info py-0 px-2 style-tiny" data-bs-toggle="modal" data-bs-target="#browseMemberModal" data-target-modal="#editOrgModal<?= $s['id'] ?>">
                                            <i class="fa-solid fa-users-viewfinder me-1"></i> Buka Katalog
                                        </button>
                                    </div>
                                    <select class="form-select form-select-sm bg-dark text-white border-secondary border-opacity-50 select2-member-picker-edit" data-target="#editOrgModal<?= $s['id'] ?>">
                                        <option value="">-- Pilih untuk mengganti data dari anggota/BPH --</option>
                                        <?php if (!empty($bphUsers)): ?>
                                            <optgroup label="⭐ Badan Pengurus Harian (BPH)">
                                                <?php foreach ($bphUsers as $u): ?>
                                                    <option value="<?= $u['id'] ?>"
                                                        data-name="<?= esc($u['full_name']) ?>"
                                                        data-avatar="<?= esc($u['avatar'] ?? '') ?>"
                                                        data-avatar-url="<?= esc(avatar_url($u['avatar'], $u['full_name'])) ?>"
                                                        data-instagram="<?= esc($u['social_instagram'] ?? '') ?>"
                                                        data-linkedin="<?= esc($u['social_linkedin'] ?? '') ?>"
                                                        data-dept="<?= esc($u['class_dept'] ?? '') ?>"
                                                        data-role="<?= esc($u['role_name'] ?? 'BPH') ?>"
                                                        data-username="<?= esc($u['username']) ?>">
                                                        <?= esc($u['full_name']) ?> (<?= esc($u['role_name']) ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        <?php endif; ?>
                                        <?php if (!empty($memberUsers)): ?>
                                            <optgroup label="👥 Anggota Club (Member)">
                                                <?php foreach ($memberUsers as $u): ?>
                                                    <option value="<?= $u['id'] ?>"
                                                        data-name="<?= esc($u['full_name']) ?>"
                                                        data-avatar="<?= esc($u['avatar'] ?? '') ?>"
                                                        data-avatar-url="<?= esc(avatar_url($u['avatar'], $u['full_name'])) ?>"
                                                        data-instagram="<?= esc($u['social_instagram'] ?? '') ?>"
                                                        data-linkedin="<?= esc($u['social_linkedin'] ?? '') ?>"
                                                        data-dept="<?= esc($u['class_dept'] ?? '') ?>"
                                                        data-role="<?= esc($u['role_name'] ?? 'Member') ?>"
                                                        data-username="<?= esc($u['username']) ?>">
                                                        <?= esc($u['full_name']) ?> (<?= esc($u['class_dept'] ?: 'Member') ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        <?php endif; ?>
                                        <?php if (!empty($otherUsers)): ?>
                                            <optgroup label="👤 Pengguna Lainnya / Pembina">
                                                <?php foreach ($otherUsers as $u): ?>
                                                    <option value="<?= $u['id'] ?>"
                                                        data-name="<?= esc($u['full_name']) ?>"
                                                        data-avatar="<?= esc($u['avatar'] ?? '') ?>"
                                                        data-avatar-url="<?= esc(avatar_url($u['avatar'], $u['full_name'])) ?>"
                                                        data-instagram="<?= esc($u['social_instagram'] ?? '') ?>"
                                                        data-linkedin="<?= esc($u['social_linkedin'] ?? '') ?>"
                                                        data-dept="<?= esc($u['class_dept'] ?? '') ?>"
                                                        data-role="<?= esc($u['role_name'] ?? '') ?>"
                                                        data-username="<?= esc($u['username']) ?>">
                                                        <?= esc($u['full_name']) ?> (<?= esc($u['role_name']) ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-secondary small">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="<?= esc($s['name']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-secondary small">Jabatan / Posisi <span class="text-danger">*</span></label>
                                    <select name="position" class="form-select bg-dark text-white border-secondary border-opacity-50" required>
                                        <option value="" disabled>-- Pilih Jabatan / Posisi --</option>
                                        <?php foreach ($positions as $pos): ?>
                                            <option value="<?= $pos ?>" <?= $s['position'] === $pos ? 'selected' : '' ?>><?= $pos ?></option>
                                        <?php endforeach; ?>
                                        <?php if (!in_array($s['position'], $positions) && !empty($s['position'])): ?>
                                            <option value="<?= esc($s['position']) ?>" selected><?= esc($s['position']) ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-secondary small">Foto Pengurus</label>
                                    <?php if (!empty($s['photo'])): ?>
                                        <div class="d-flex align-items-center gap-3 mb-2 p-2 bg-black bg-opacity-25 rounded border border-secondary border-opacity-25">
                                            <img src="<?= base_url($s['photo']) ?>" alt="Foto" class="rounded-circle object-fit-cover border border-danger" style="width: 44px; height: 44px;">
                                            <div class="flex-grow-1">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="remove_photo" value="1" id="remPhoto<?= $s['id'] ?>">
                                                    <label class="form-check-label text-danger style-tiny" for="remPhoto<?= $s['id'] ?>">
                                                        <i class="fa-solid fa-trash me-1"></i> Hapus foto saat ini (kembalikan ke inisial)
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Dynamic Avatar Sync Preview -->
                                    <div class="member-photo-preview-card p-2 mb-2 rounded bg-black bg-opacity-40 border border-success border-opacity-50 align-items-center gap-3" style="display: none;">
                                        <img class="member-photo-preview-img rounded-circle object-fit-cover border border-success" src="" alt="Avatar" style="width: 44px; height: 44px;">
                                        <div class="flex-grow-1">
                                            <div class="style-tiny text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Foto profil anggota terdeteksi</div>
                                            <div class="style-tiny text-secondary">Akan digunakan sebagai foto pengurus</div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger style-tiny py-0.5 px-2 btn-cancel-member-photo" title="Batal gunakan foto ini">
                                            <i class="fa-solid fa-xmark me-1"></i> Batal
                                        </button>
                                    </div>

                                    <input type="file" name="photo_file" class="form-control mb-1 bg-dark text-white border-secondary border-opacity-50" accept="image/png, image/jpeg, image/jpg, image/webp">
                                    <input type="text" name="photo" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-50 font-monospace mb-1" value="<?= esc($s['photo']) ?>" placeholder="Atau tempel URL foto (misal: https://...)">
                                    <div class="form-text text-secondary style-tiny">Format: JPG, PNG, WEBP (Maksimal 10MB).</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-secondary small">Biografi Singkat</label>
                                    <textarea name="bio" class="form-control" rows="2"><?= esc($s['bio']) ?></textarea>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label text-secondary small">URL Instagram</label>
                                        <input type="text" name="instagram" class="form-control font-monospace" value="<?= esc($s['instagram']) ?>">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-secondary small">URL LinkedIn</label>
                                        <input type="text" name="linkedin" class="form-control font-monospace" value="<?= esc($s['linkedin']) ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-top border-secondary border-opacity-25">
                                <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-red">Update Pengurus</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal Tambah Pengurus -->
<div class="modal fade" id="addOrgModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25 shadow-lg">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title font-heading"><i class="fa-solid fa-sitemap text-danger me-2"></i> Tambah Pengurus Organisasi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/cms/structure/store') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <!-- Quick Member Selector Panel -->
                    <div class="mb-3 p-3 rounded-3 border border-danger border-opacity-50 bg-danger bg-opacity-10 shadow-sm">
                        <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                            <label class="form-label text-white fw-bold small m-0 d-flex align-items-center gap-1.5">
                                <i class="fa-solid fa-bolt text-warning"></i> Ambil Cepat dari Anggota / BPH:
                            </label>
                            <button type="button" class="btn btn-sm btn-outline-danger py-0.5 px-2 font-monospace style-tiny" data-bs-toggle="modal" data-bs-target="#browseMemberModal" data-target-modal="#addOrgModal">
                                <i class="fa-solid fa-users-viewfinder me-1"></i> Buka Katalog Anggota
                            </button>
                        </div>
                        <select id="quickMemberSelectAdd" class="form-select bg-dark text-white border-secondary border-opacity-50 select2-member-picker">
                            <option value="">-- Ketik Nama atau Pilih Anggota / BPH --</option>
                            <?php if (!empty($bphUsers)): ?>
                                <optgroup label="⭐ Badan Pengurus Harian (BPH)">
                                    <?php foreach ($bphUsers as $u): ?>
                                        <option value="<?= $u['id'] ?>"
                                            data-name="<?= esc($u['full_name']) ?>"
                                            data-avatar="<?= esc($u['avatar'] ?? '') ?>"
                                            data-avatar-url="<?= esc(avatar_url($u['avatar'], $u['full_name'])) ?>"
                                            data-instagram="<?= esc($u['social_instagram'] ?? '') ?>"
                                            data-linkedin="<?= esc($u['social_linkedin'] ?? '') ?>"
                                            data-dept="<?= esc($u['class_dept'] ?? '') ?>"
                                            data-role="<?= esc($u['role_name'] ?? 'BPH') ?>"
                                            data-username="<?= esc($u['username']) ?>">
                                            <?= esc($u['full_name']) ?> (<?= esc($u['role_name']) ?><?= !empty($u['class_dept']) ? ' - ' . esc($u['class_dept']) : '' ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>
                            <?php if (!empty($memberUsers)): ?>
                                <optgroup label="👥 Anggota Club (Member)">
                                    <?php foreach ($memberUsers as $u): ?>
                                        <option value="<?= $u['id'] ?>"
                                            data-name="<?= esc($u['full_name']) ?>"
                                            data-avatar="<?= esc($u['avatar'] ?? '') ?>"
                                            data-avatar-url="<?= esc(avatar_url($u['avatar'], $u['full_name'])) ?>"
                                            data-instagram="<?= esc($u['social_instagram'] ?? '') ?>"
                                            data-linkedin="<?= esc($u['social_linkedin'] ?? '') ?>"
                                            data-dept="<?= esc($u['class_dept'] ?? '') ?>"
                                            data-role="<?= esc($u['role_name'] ?? 'Member') ?>"
                                            data-username="<?= esc($u['username']) ?>">
                                            <?= esc($u['full_name']) ?> (<?= esc($u['class_dept'] ?: 'Member') ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>
                            <?php if (!empty($otherUsers)): ?>
                                <optgroup label="👤 Pengguna Lainnya / Pembina">
                                    <?php foreach ($otherUsers as $u): ?>
                                        <option value="<?= $u['id'] ?>"
                                            data-name="<?= esc($u['full_name']) ?>"
                                            data-avatar="<?= esc($u['avatar'] ?? '') ?>"
                                            data-avatar-url="<?= esc(avatar_url($u['avatar'], $u['full_name'])) ?>"
                                            data-instagram="<?= esc($u['social_instagram'] ?? '') ?>"
                                            data-linkedin="<?= esc($u['social_linkedin'] ?? '') ?>"
                                            data-dept="<?= esc($u['class_dept'] ?? '') ?>"
                                            data-role="<?= esc($u['role_name'] ?? '') ?>"
                                            data-username="<?= esc($u['username']) ?>">
                                            <?= esc($u['full_name']) ?> (<?= esc($u['role_name']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>
                        </select>
                        <div class="d-flex align-items-center justify-content-between mt-1.5">
                            <span class="text-secondary style-tiny"><i class="fa-solid fa-circle-info text-info me-1"></i> Klik anggota untuk otomatis mengisi Nama, Foto, Instagram & LinkedIn.</span>
                            <button type="button" class="btn btn-link text-secondary p-0 style-tiny text-decoration-none btn-clear-quickfill" style="display: none;">
                                <i class="fa-solid fa-rotate-left me-1"></i> Reset Isian
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Rizki Agung Febrian" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Jabatan / Posisi <span class="text-danger">*</span></label>
                        <select name="position" class="form-select bg-dark text-white border-secondary border-opacity-50" required>
                            <option value="" disabled selected>-- Pilih Jabatan / Posisi --</option>
                            <?php foreach ($positions as $pos): ?>
                                <option value="<?= $pos ?>"><?= $pos ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Foto Pengurus (Opsional)</label>
                        <!-- Dynamic Avatar Sync Preview -->
                        <div class="member-photo-preview-card p-2 mb-2 rounded bg-black bg-opacity-40 border border-success border-opacity-50 align-items-center gap-3" style="display: none;">
                            <img class="member-photo-preview-img rounded-circle object-fit-cover border border-success" src="" alt="Avatar" style="width: 44px; height: 44px;">
                            <div class="flex-grow-1">
                                <div class="style-tiny text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Foto profil anggota terdeteksi</div>
                                <div class="style-tiny text-secondary">Akan digunakan otomatis sebagai foto pengurus</div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger style-tiny py-0.5 px-2 btn-cancel-member-photo" title="Hapus foto ini">
                                <i class="fa-solid fa-xmark me-1"></i> Hapus
                            </button>
                        </div>
                        <input type="file" name="photo_file" class="form-control mb-1 bg-dark text-white border-secondary border-opacity-50" accept="image/png, image/jpeg, image/jpg, image/webp">
                        <input type="text" name="photo" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-50 font-monospace mb-1" placeholder="Atau tempel URL foto (misal: https://...)">
                        <div class="form-text text-secondary style-tiny">Format: JPG, PNG, WEBP (Maksimal 10MB). Terisi otomatis jika anggota memiliki avatar profil.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Biografi Singkat</label>
                        <textarea name="bio" class="form-control" rows="2" placeholder="Fokus bidang dan tanggung jawab..."></textarea>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label text-secondary small">URL Instagram</label>
                            <input type="text" name="instagram" class="form-control font-monospace" placeholder="https://instagram.com/...">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small">URL LinkedIn</label>
                            <input type="text" name="linkedin" class="form-control font-monospace" placeholder="https://linkedin.com/in/...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25">
                    <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-red">Simpan Pengurus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Katalog / Pencarian Cepat Anggota -->
<div class="modal fade" id="browseMemberModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content bg-dark text-white border border-danger border-opacity-50 shadow-lg">
            <div class="modal-header border-bottom border-secondary border-opacity-25 py-2.5 px-3 bg-danger bg-opacity-10">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-danger text-white font-monospace style-tiny py-1 px-2.5 rounded-pill">
                        <i class="fa-solid fa-users me-1"></i> KATALOG ANGGOTA MMC
                    </span>
                    <h6 class="modal-title font-heading fw-bold m-0 text-white">
                        Pilih Anggota / BPH Club
                    </h6>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <!-- Search & Filter Controls -->
                <div class="row g-2 mb-3">
                    <div class="col-md-7">
                        <div class="input-group">
                            <span class="input-group-text bg-black text-secondary border-secondary border-opacity-50">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="text" id="catalogSearchInput" class="form-control bg-dark text-white border-secondary border-opacity-50" placeholder="Ketik nama anggota, username, kelas, divisi...">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="d-flex gap-1 overflow-auto py-1">
                            <button type="button" class="btn btn-sm btn-red filter-role-btn active py-1 px-2.5" data-filter="all">Semua (<?= count($users ?? []) ?>)</button>
                            <button type="button" class="btn btn-sm btn-saas-dark filter-role-btn py-1 px-2.5" data-filter="bph">BPH (<?= count($bphUsers ?? []) ?>)</button>
                            <button type="button" class="btn btn-sm btn-saas-dark filter-role-btn py-1 px-2.5" data-filter="member">Anggota (<?= count($memberUsers ?? []) ?>)</button>
                        </div>
                    </div>
                </div>

                <!-- Member Cards Grid -->
                <div class="row g-2" id="catalogMemberList" style="max-height: 52vh; overflow-y: auto;">
                    <?php if (empty($users)): ?>
                        <div class="col-12 text-center py-5 text-secondary">
                            <i class="fa-solid fa-user-slash fs-1 mb-2 d-block opacity-50"></i>
                            Belum ada data anggota / BPH terdaftar di sistem.
                        </div>
                    <?php else: ?>
                        <?php foreach ($users as $u): ?>
                            <?php
                            $isBph = in_array($u['role_slug'] ?? '', ['bph', 'superadmin']);
                            $filterCat = $isBph ? 'bph' : (($u['role_slug'] ?? '') === 'member' ? 'member' : 'other');
                            $userAvatarUrl = avatar_url($u['avatar'] ?? null, $u['full_name']);
                            ?>
                            <div class="col-md-6 member-catalog-card"
                                data-cat="<?= $filterCat ?>"
                                data-search="<?= strtolower(esc($u['full_name'] . ' ' . ($u['username'] ?? '') . ' ' . ($u['class_dept'] ?? '') . ' ' . ($u['role_name'] ?? ''))) ?>">
                                <div class="p-2.5 rounded-3 bg-black bg-opacity-40 border border-secondary border-opacity-25 h-100 d-flex align-items-center justify-content-between gap-2 hover-border-danger transition">
                                    <div class="d-flex align-items-center gap-2.5 overflow-hidden">
                                        <img src="<?= $userAvatarUrl ?>" alt="<?= esc($u['full_name']) ?>" class="rounded-circle object-fit-cover border border-danger border-opacity-50 flex-shrink-0" style="width: 42px; height: 42px;">
                                        <div class="overflow-hidden">
                                            <div class="fw-semibold text-white text-truncate" title="<?= esc($u['full_name']) ?>">
                                                <?= esc($u['full_name']) ?>
                                            </div>
                                            <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                                <span class="badge <?= $isBph ? 'bg-danger text-white' : 'bg-info bg-opacity-25 text-info' ?> font-monospace style-tiny py-0.5 px-1.5">
                                                    <?= esc($u['role_name'] ?: 'Anggota') ?>
                                                </span>
                                                <?php if (!empty($u['class_dept'])): ?>
                                                    <span class="text-secondary style-tiny text-truncate" style="max-width: 140px;" title="<?= esc($u['class_dept']) ?>">
                                                        <?= esc($u['class_dept']) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-select-catalog-member flex-shrink-0 py-1 px-2.5"
                                        data-id="<?= $u['id'] ?>"
                                        data-name="<?= esc($u['full_name']) ?>"
                                        data-avatar="<?= esc($u['avatar'] ?? '') ?>"
                                        data-avatar-url="<?= esc($userAvatarUrl) ?>"
                                        data-instagram="<?= esc($u['social_instagram'] ?? '') ?>"
                                        data-linkedin="<?= esc($u['social_linkedin'] ?? '') ?>"
                                        data-dept="<?= esc($u['class_dept'] ?? '') ?>"
                                        data-role="<?= esc($u['role_name'] ?? '') ?>"
                                        data-username="<?= esc($u['username']) ?>">
                                        <i class="fa-solid fa-check me-1"></i> Pilih
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="modal-footer border-top border-secondary border-opacity-25 py-2 justify-content-between">
                <span class="text-secondary style-tiny"><i class="fa-solid fa-bolt text-warning me-1"></i> Sekali klik tombol "Pilih", form akan langsung terisi lengkap!</span>
                <button type="button" class="btn btn-saas-dark btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<style>
    /* Dark Theme Styles for Select2 */
    .select2-container--default .select2-selection--single {
        background-color: #16161f !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: #ffffff !important;
        height: 38px !important;
        border-radius: 0.375rem !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #f8f9fa !important;
        line-height: 36px !important;
        padding-left: 12px !important;
        font-size: 0.9rem !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
    .select2-dropdown {
        background-color: #16161f !important;
        border: 1px solid rgba(220, 53, 69, 0.5) !important;
        color: #ffffff !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6) !important;
        border-radius: 0.375rem !important;
        z-index: 1070 !important;
    }
    .select2-search--dropdown .select2-search__field {
        background-color: #0e0e14 !important;
        border: 1px solid rgba(255, 255, 255, 0.25) !important;
        color: #ffffff !important;
        border-radius: 0.25rem !important;
        padding: 6px 10px !important;
    }
    .select2-container--default .select2-results__option {
        color: #e2e8f0 !important;
        padding: 8px 12px !important;
        font-size: 0.85rem !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #dc3545 !important;
        color: #ffffff !important;
    }
    .select2-container--default .select2-results__group {
        color: #ff6b6b !important;
        font-weight: 700 !important;
        font-size: 0.72rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        padding: 6px 12px !important;
        background: rgba(220, 53, 69, 0.12) !important;
    }
    .hover-border-danger:hover {
        border-color: rgba(220, 53, 69, 0.6) !important;
        background-color: rgba(220, 53, 69, 0.08) !important;
    }
    .transition {
        transition: all 0.2s ease-in-out;
    }
</style>

<script>
$(document).ready(function() {
    let targetModalForCatalog = '#addOrgModal';
    let memberWasPicked = false;
    let activeCatalogRole = 'all';

    // Helper: format Instagram URL
    function formatInstagramUrl(handle) {
        if (!handle) return '';
        handle = handle.trim();
        if (handle.startsWith('http://') || handle.startsWith('https://')) {
            return handle;
        }
        handle = handle.replace(/^@/, '');
        return 'https://instagram.com/' + handle;
    }

    // Helper: format LinkedIn URL
    function formatLinkedinUrl(url) {
        if (!url) return '';
        url = url.trim();
        if (url.startsWith('http://') || url.startsWith('https://')) {
            return url;
        }
        url = url.replace(/^@/, '');
        return 'https://linkedin.com/in/' + url;
    }

    // Helper: Smart Position Detector based on member data
    function detectPosition(dept, role, name) {
        const text = ((dept || '') + ' ' + (role || '') + ' ' + (name || '')).toLowerCase();
        
        if (text.includes('wakil ketua umum') || (text.includes('wakil') && text.includes('ketua') && !text.includes('divisi'))) {
            return 'Wakil Ketua Umum';
        }
        if (text.includes('ketua umum') || (text.includes('ketua') && !text.includes('wakil') && !text.includes('divisi'))) {
            return 'Ketua Umum';
        }
        if (text.includes('sekretaris 2')) return 'Sekretaris 2';
        if (text.includes('sekretaris 1') || text.includes('sekretaris')) return 'Sekretaris 1';
        if (text.includes('bendahara 2')) return 'Bendahara 2';
        if (text.includes('bendahara 1') || text.includes('bendahara')) return 'Bendahara 1';
        if (text.includes('humas 2')) return 'Humas 2';
        if (text.includes('humas 1') || text.includes('humas')) return 'Humas 1';
        
        if (text.includes('broadcasting')) {
            if (text.includes('wakil')) return 'Wakil Ketua Divisi Broadcasting';
            if (text.includes('ketua') || text.includes('koor')) return 'Ketua Divisi Broadcasting';
        }
        if (text.includes('programing') || text.includes('programming')) {
            if (text.includes('wakil')) return 'Wakil Ketua Divisi Programing';
            if (text.includes('ketua') || text.includes('koor')) return 'Ketua Divisi Programing';
        }
        if (text.includes('poster') || text.includes('desain')) return 'PJ desain poster';
        if (text.includes('video') || text.includes('editing')) return 'PJ Editing Video';
        if (text.includes('backup')) return 'PJ backup';
        
        return null;
    }

    // Central function to populate form inputs from member data
    function fillMemberData(modalEl, data) {
        if (!modalEl || !data) return;

        // 1. Nama Lengkap
        const nameInput = modalEl.querySelector('input[name="name"]');
        if (nameInput) {
            nameInput.value = data.name || '';
            nameInput.classList.add('is-valid');
            setTimeout(() => nameInput.classList.remove('is-valid'), 2500);
        }

        // 2. Instagram
        const igInput = modalEl.querySelector('input[name="instagram"]');
        if (igInput && data.instagram) {
            igInput.value = formatInstagramUrl(data.instagram);
        }

        // 3. LinkedIn
        const liInput = modalEl.querySelector('input[name="linkedin"]');
        if (liInput && data.linkedin) {
            liInput.value = formatLinkedinUrl(data.linkedin);
        }

        // 4. Bio suggestion if empty or previously auto-filled
        const bioTextarea = modalEl.querySelector('textarea[name="bio"]');
        if (bioTextarea && (!bioTextarea.value || bioTextarea.value.trim() === '' || bioTextarea.dataset.autofilled === '1')) {
            const roleLabel = data.role ? data.role : 'Anggota';
            const deptLabel = data.dept ? ' (' + data.dept + ')' : '';
            bioTextarea.value = `${roleLabel} Multimedia Club SMAN 1 Tamansari${deptLabel}`;
            bioTextarea.dataset.autofilled = '1';
        }

        // 5. Position Auto Suggestion
        const posSelect = modalEl.querySelector('select[name="position"]');
        if (posSelect) {
            const detectedPos = detectPosition(data.dept, data.role, data.name);
            if (detectedPos) {
                let found = false;
                for (let i = 0; i < posSelect.options.length; i++) {
                    if (posSelect.options[i].value === detectedPos) {
                        posSelect.selectedIndex = i;
                        found = true;
                        break;
                    }
                }
                if (!found && detectedPos) {
                    const newOpt = new Option(detectedPos, detectedPos, true, true);
                    posSelect.add(newOpt);
                }
            }
        }

        // 6. Photo & Avatar
        const photoInput = modalEl.querySelector('input[name="photo"]');
        const photoPreviewCard = modalEl.querySelector('.member-photo-preview-card');
        const photoPreviewImg = modalEl.querySelector('.member-photo-preview-img');

        if (data.avatar && data.avatar.trim() !== '') {
            if (photoInput) {
                photoInput.value = data.avatar;
            }
            if (photoPreviewCard && photoPreviewImg) {
                photoPreviewImg.src = data.avatarUrl || ('/' + data.avatar);
                photoPreviewCard.style.display = 'flex';
            }
        } else {
            if (photoPreviewCard) {
                photoPreviewCard.style.display = 'none';
            }
        }

        // Uncheck remove_photo if checked in edit modal
        const removePhotoChk = modalEl.querySelector('input[name="remove_photo"]');
        if (removePhotoChk) {
            removePhotoChk.checked = false;
        }

        // Show reset button if present
        const resetBtn = modalEl.querySelector('.btn-clear-quickfill');
        if (resetBtn) {
            resetBtn.style.display = 'inline-block';
        }

        // SweetAlert Toast Notification
        const isMobile = window.innerWidth < 768;
        const Toast = Swal.mixin({
            toast: true,
            position: isMobile ? 'top' : 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#121218',
            color: '#fff'
        });
        Toast.fire({
            icon: 'success',
            title: `✓ Data <b>${data.name}</b> berhasil dimuat!`
        });
    }

    // Initialize Select2 inside Add Modal
    $('#addOrgModal').on('shown.bs.modal', function () {
        $('#quickMemberSelectAdd').select2({
            dropdownParent: $('#addOrgModal'),
            width: '100%',
            placeholder: '-- Ketik Nama atau Pilih Anggota / BPH --',
            allowClear: true
        });
    });

    // Handle Quick Member Select inside Add Modal
    $('#quickMemberSelectAdd').on('change', function() {
        const val = $(this).val();
        if (!val) return;
        const opt = this.options[this.selectedIndex];
        if (!opt) return;

        const memberData = {
            id: val,
            name: opt.getAttribute('data-name'),
            avatar: opt.getAttribute('data-avatar'),
            avatarUrl: opt.getAttribute('data-avatar-url'),
            instagram: opt.getAttribute('data-instagram'),
            linkedin: opt.getAttribute('data-linkedin'),
            dept: opt.getAttribute('data-dept'),
            role: opt.getAttribute('data-role'),
            username: opt.getAttribute('data-username')
        };

        fillMemberData(document.getElementById('addOrgModal'), memberData);
    });

    // Handle Quick Member Select inside Edit Modals
    $(document).on('change', '.select2-member-picker-edit', function() {
        const val = $(this).val();
        if (!val) return;
        const targetSelector = $(this).attr('data-target');
        const modalEl = document.querySelector(targetSelector);
        if (!modalEl) return;

        const opt = this.options[this.selectedIndex];
        if (!opt) return;

        const memberData = {
            id: val,
            name: opt.getAttribute('data-name'),
            avatar: opt.getAttribute('data-avatar'),
            avatarUrl: opt.getAttribute('data-avatar-url'),
            instagram: opt.getAttribute('data-instagram'),
            linkedin: opt.getAttribute('data-linkedin'),
            dept: opt.getAttribute('data-dept'),
            role: opt.getAttribute('data-role'),
            username: opt.getAttribute('data-username')
        };

        fillMemberData(modalEl, memberData);
    });

    // Cancel member photo preview and clear input
    $(document).on('click', '.btn-cancel-member-photo', function() {
        const modal = $(this).closest('.modal');
        modal.find('input[name="photo"]').val('');
        modal.find('.member-photo-preview-card').hide();
        modal.find('.member-photo-preview-img').attr('src', '');
    });

    // Reset quickfill inputs
    $(document).on('click', '.btn-clear-quickfill', function() {
        const modal = $(this).closest('.modal');
        modal.find('input[name="name"]').val('');
        modal.find('input[name="instagram"]').val('');
        modal.find('input[name="linkedin"]').val('');
        modal.find('textarea[name="bio"]').val('').removeAttr('data-autofilled');
        modal.find('input[name="photo"]').val('');
        modal.find('.member-photo-preview-card').hide();
        modal.find('.member-photo-preview-img').attr('src', '');
        modal.find('select[name="position"]').prop('selectedIndex', 0);
        
        const selectPicker = modal.find('#quickMemberSelectAdd');
        if (selectPicker.length) {
            selectPicker.val('').trigger('change.select2');
        }
        $(this).hide();
    });

    // Handle Opening Catalog Modal from another modal
    $(document).on('click', '[data-bs-target="#browseMemberModal"]', function() {
        const target = $(this).attr('data-target-modal');
        if (target) {
            targetModalForCatalog = target;
        }

        const parentModal = $(this).closest('.modal');
        if (parentModal.length) {
            bootstrap.Modal.getInstance(parentModal[0])?.hide();
        }
    });

    // Restore parent modal if Catalog Modal is closed without choosing
    $('#browseMemberModal').on('hidden.bs.modal', function() {
        if (!memberWasPicked) {
            const targetModalEl = document.querySelector(targetModalForCatalog);
            if (targetModalEl) {
                bootstrap.Modal.getOrCreateInstance(targetModalEl).show();
            }
        }
        memberWasPicked = false;
    });

    // Handle Member Pick from Catalog Modal
    $(document).on('click', '.btn-select-catalog-member', function() {
        memberWasPicked = true;
        const memberData = {
            id: $(this).data('id'),
            name: $(this).data('name'),
            avatar: $(this).data('avatar'),
            avatarUrl: $(this).data('avatar-url'),
            instagram: $(this).data('instagram'),
            linkedin: $(this).data('linkedin'),
            dept: $(this).data('dept'),
            role: $(this).data('role'),
            username: $(this).data('username')
        };

        const browseModalEl = document.getElementById('browseMemberModal');
        const browseModal = bootstrap.Modal.getInstance(browseModalEl);
        if (browseModal) browseModal.hide();

        const targetModalEl = document.querySelector(targetModalForCatalog);
        if (targetModalEl) {
            const modalInstance = bootstrap.Modal.getOrCreateInstance(targetModalEl);
            modalInstance.show();

            // Fill member data
            fillMemberData(targetModalEl, memberData);

            // Sync select2 if target is Add modal
            if (targetModalForCatalog === '#addOrgModal') {
                const $addSelect = $('#quickMemberSelectAdd');
                if ($addSelect.length && memberData.id) {
                    $addSelect.val(memberData.id).trigger('change.select2');
                }
            }

            // Focus on Position select
            const posSelect = targetModalEl.querySelector('select[name="position"]');
            if (posSelect) {
                posSelect.focus();
            }
        }
    });

    // Catalog Modal: Live Search Filtering
    $('#catalogSearchInput').on('input', function() {
        const query = $(this).val().toLowerCase().trim();
        filterCatalog(query, activeCatalogRole);
    });

    // Catalog Modal: Role Filter Buttons
    $('.filter-role-btn').on('click', function() {
        $('.filter-role-btn').removeClass('active btn-red').addClass('btn-saas-dark');
        $(this).addClass('active btn-red').removeClass('btn-saas-dark');
        activeCatalogRole = $(this).data('filter');
        filterCatalog($('#catalogSearchInput').val().toLowerCase().trim(), activeCatalogRole);
    });

    function filterCatalog(search, role) {
        $('.member-catalog-card').each(function() {
            const text = $(this).data('search') || '';
            const cat = $(this).data('cat') || '';

            const matchSearch = !search || text.includes(search);
            const matchRole = (role === 'all') || (cat === role);

            if (matchSearch && matchRole) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }
});
</script>
<?= $this->endSection() ?>
