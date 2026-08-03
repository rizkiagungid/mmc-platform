<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Manajemen Bagan Organisasi & Pengurus</h4>
        <p class="text-secondary small m-0">Kelola daftar Pembina, BPH (Ketua, Wakil, Sekretaris, Bendahara), dan Koordinator Divisi</p>
    </div>

    <button type="button" class="btn btn-red" data-bs-toggle="modal" data-bs-target="#addOrgModal">
        <i class="fa-solid fa-plus me-1"></i> Tambah Pengurus Baru
    </button>
</div>

<div class="saas-card p-4">
    <div class="row g-4 justify-content-center">
        <?php foreach ($structures as $s): ?>
            <div class="col-md-6 col-lg-4">
                <div class="saas-card p-3 border border-secondary border-opacity-25 text-center h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold fs-3 mx-auto mb-3" style="width: 70px; height: 70px;">
                            <?= strtoupper(substr($s['name'], 0, 1)) ?>
                        </div>

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

            <!-- Modal Edit Pengurus -->
            <div class="modal fade" id="editOrgModal<?= $s['id'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
                        <div class="modal-header border-bottom border-secondary border-opacity-25">
                            <h5 class="modal-title font-heading"><i class="fa-solid fa-pen text-info me-2"></i> Edit Pengurus Organisasi</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="<?= base_url('admin/cms/structure/update/' . $s['id']) ?>" method="POST">
                            <?= csrf_field() ?>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label text-secondary small">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="<?= esc($s['name']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-secondary small">Jabatan / Posisi <span class="text-danger">*</span></label>
                                    <input type="text" name="position" class="form-control" value="<?= esc($s['position']) ?>" required>
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
            <form action="<?= base_url('admin/cms/structure/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Rizki Agung Febrian" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Jabatan / Posisi <span class="text-danger">*</span></label>
                        <select name="position" class="form-select" required>
                            <option value="Pembina Utama">Pembina Utama</option>
                            <option value="Ketua Umum">Ketua Umum</option>
                            <option value="Wakil Ketua">Wakil Ketua</option>
                            <option value="Sekretaris Utama">Sekretaris Utama</option>
                            <option value="Bendahara Utama">Bendahara Utama</option>
                            <option value="Koordinator Divisi Broadcasting">Koordinator Divisi Broadcasting</option>
                            <option value="Koordinator Divisi Programming">Koordinator Divisi Programming</option>
                            <option value="Koordinator Divisi Videography">Koordinator Divisi Videography</option>
                            <option value="Koordinator Divisi Photography">Koordinator Divisi Photography</option>
                        </select>
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
