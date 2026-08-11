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
                    <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
                        <div class="modal-header border-bottom border-secondary border-opacity-25">
                            <h5 class="modal-title font-heading"><i class="fa-solid fa-pen text-info me-2"></i> Edit Pengurus Organisasi</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="<?= base_url('admin/cms/structure/update/' . $s['id']) ?>" method="POST" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="modal-body">
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
                                                        <i class="fa-solid fa-trash me-1"></i> Hapus foto ini (kembalikan ke inisial)
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
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
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title font-heading"><i class="fa-solid fa-sitemap text-danger me-2"></i> Tambah Pengurus Organisasi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/cms/structure/store') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
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
                        <input type="file" name="photo_file" class="form-control mb-1 bg-dark text-white border-secondary border-opacity-50" accept="image/png, image/jpeg, image/jpg, image/webp">
                        <input type="text" name="photo" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-50 font-monospace mb-1" placeholder="Atau tempel URL foto (misal: https://...)">
                        <div class="form-text text-secondary style-tiny">Format: JPG, PNG, WEBP (Maksimal 10MB).</div>
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

<?= $this->endSection() ?>
