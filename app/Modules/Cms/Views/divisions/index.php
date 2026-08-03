<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Manajemen Divisi & Learning Programs</h4>
        <p class="text-secondary small m-0">Kelola daftar divisi keahlian klub beserta silabus kurikulum program pembelajarannya</p>
    </div>

    <div class="d-flex gap-2">
        <button type="button" class="btn btn-saas-dark" data-bs-toggle="modal" data-bs-target="#addProgramModal">
            <i class="fa-solid fa-plus me-1"></i> Tambah Program Belajar
        </button>
        <button type="button" class="btn btn-red" data-bs-toggle="modal" data-bs-target="#addDivisionModal">
            <i class="fa-solid fa-plus me-1"></i> Tambah Divisi Baru
        </button>
    </div>
</div>

<div class="row g-4 justify-content-center">
    <?php foreach ($divisions as $div): ?>
        <div class="col-lg-6">
            <div class="saas-card p-4 h-100 border border-secondary border-opacity-25 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-3 bg-danger bg-opacity-25 p-2 text-danger">
                                <i class="fa-solid <?= esc($div['icon']) ?> fs-4"></i>
                            </div>
                            <div>
                                <h5 class="text-white font-heading m-0"><?= esc($div['name']) ?></h5>
                                <span class="text-secondary style-tiny font-monospace">Slug: /learning-path#<?= esc($div['slug']) ?></span>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-info py-1 px-2" data-bs-toggle="modal" data-bs-target="#editDivisionModal<?= $div['id'] ?>" title="Edit Divisi">
                                <i class="fa-solid fa-pen"></i> Edit
                            </button>
                            <a href="<?= base_url('admin/cms/divisions/delete/' . $div['id']) ?>" onclick="return confirm('Hapus divisi ini?')" class="btn btn-sm text-danger p-0" title="Hapus">
                                <i class="fa-solid fa-trash fs-5"></i>
                            </a>
                        </div>
                    </div>

                    <p class="text-secondary small leading-relaxed mb-3"><?= esc($div['short_description']) ?></p>

                    <!-- Learning Programs Sub-list -->
                    <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 mb-3">
                        <label class="text-white small fw-semibold mb-2"><i class="fa-solid fa-graduation-cap text-danger me-1"></i> Silabus Program Belajar:</label>
                        <?php if (empty($div['programs'])): ?>
                            <div class="text-secondary style-tiny fst-italic">Belum ada program belajar ditambahkan.</div>
                        <?php else: ?>
                            <div class="d-flex flex-column gap-2">
                                <?php foreach ($div['programs'] as $p): ?>
                                    <div class="d-flex align-items-center justify-content-between p-2 rounded bg-black border border-secondary border-opacity-10">
                                        <div>
                                            <div class="text-white small fw-semibold"><?= esc($p['title']) ?></div>
                                            <div class="text-secondary style-tiny font-monospace"><?= esc($p['difficulty']) ?> | <?= esc($p['duration']) ?></div>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm text-info p-0" data-bs-toggle="modal" data-bs-target="#editProgramModal<?= $p['id'] ?>" title="Edit Program">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <a href="<?= base_url('admin/cms/divisions/programs/delete/' . $p['id']) ?>" onclick="return confirm('Hapus program belajar ini?')" class="text-secondary hover-danger ms-2">
                                                <i class="fa-solid fa-xmark"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Modal Edit Program Belajar -->
                                    <div class="modal fade" id="editProgramModal<?= $p['id'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
                                                <div class="modal-header border-bottom border-secondary border-opacity-25">
                                                    <h5 class="modal-title font-heading"><i class="fa-solid fa-pen text-info me-2"></i> Edit Program Belajar</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="<?= base_url('admin/cms/divisions/programs/update/' . $p['id']) ?>" method="POST">
                                                    <?= csrf_field() ?>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label text-secondary small">Judul Program / Modul Belajar <span class="text-danger">*</span></label>
                                                            <input type="text" name="title" class="form-control" value="<?= esc($p['title']) ?>" required>
                                                        </div>
                                                        <div class="row g-2 mb-3">
                                                            <div class="col-6">
                                                                <label class="form-label text-secondary small">Tingkat Kesulitan</label>
                                                                <input type="text" name="difficulty" class="form-control" value="<?= esc($p['difficulty']) ?>">
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="form-label text-secondary small">Estimasi Durasi</label>
                                                                <input type="text" name="duration" class="form-control" value="<?= esc($p['duration']) ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-top border-secondary border-opacity-25">
                                                        <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-red">Update Program</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Divisi -->
        <div class="modal fade" id="editDivisionModal<?= $div['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
                    <div class="modal-header border-bottom border-secondary border-opacity-25">
                        <h5 class="modal-title font-heading"><i class="fa-solid fa-pen text-danger me-2"></i> Edit Divisi</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?= base_url('admin/cms/divisions/update/' . $div['id']) ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label text-secondary small">Nama Divisi <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="<?= esc($div['name']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary small">Ikon FontAwesome</label>
                                <input type="text" name="icon" class="form-control font-monospace" value="<?= esc($div['icon']) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary small">Deskripsi Singkat</label>
                                <textarea name="short_description" class="form-control" rows="3"><?= esc($div['short_description']) ?></textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-top border-secondary border-opacity-25">
                            <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-red">Update Divisi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Modal Tambah Divisi -->
<div class="modal fade" id="addDivisionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title font-heading"><i class="fa-solid fa-layer-group text-danger me-2"></i> Tambah Divisi Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/cms/divisions/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Nama Divisi <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Audio Production & Sound Design" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Ikon FontAwesome</label>
                        <input type="text" name="icon" class="form-control font-monospace" value="fa-headphones" placeholder="fa-headphones">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Deskripsi Singkat</label>
                        <textarea name="short_description" class="form-control" rows="2" placeholder="Ringkasan peminatan divisi..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25">
                    <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-red">Simpan Divisi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Program Belajar -->
<div class="modal fade" id="addProgramModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title font-heading"><i class="fa-solid fa-graduation-cap text-info me-2"></i> Tambah Program Belajar</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/cms/divisions/programs/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Pilih Divisi Induk <span class="text-danger">*</span></label>
                        <select name="division_id" class="form-select" required>
                            <?php foreach ($divisions as $d): ?>
                                <option value="<?= $d['id'] ?>"><?= esc($d['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Judul Program / Modul Belajar <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: Pengenalan Komposisi Lighting Studio" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label text-secondary small">Tingkat Kesulitan</label>
                            <input type="text" name="difficulty" class="form-control" value="Pemula (Basic)" placeholder="Pemula / Menengah">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small">Estimasi Durasi</label>
                            <input type="text" name="duration" class="form-control" value="2 Sesi (4 Jam)" placeholder="2 Sesi">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25">
                    <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-red">Simpan Program</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
