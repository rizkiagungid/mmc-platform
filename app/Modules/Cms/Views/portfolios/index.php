<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Manajemen Portofolio & Multi-Kontributor</h4>
        <p class="text-secondary small m-0">Kelola koleksi karya sinematografi, desain grafis, dan web development karya anggota klub</p>
    </div>

    <button type="button" class="btn btn-red" data-bs-toggle="modal" data-bs-target="#addPortfolioModal">
        <i class="fa-solid fa-plus me-1"></i> Tambah Karya Baru
    </button>
</div>

<div class="saas-card p-4">
    <div class="table-responsive">
        <table class="table table-dark table-dark-saas align-middle datatable-saas">
            <thead>
                <tr>
                    <th>Judul Karya</th>
                    <th>Kategori</th>
                    <th>Tahun</th>
                    <th>Kontributor Anggota (Multi-Contributors)</th>
                    <th class="text-center">Featured</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($portfolios as $p): ?>
                    <tr>
                        <td>
                            <div class="fw-semibold text-white"><?= esc($p['title']) ?></div>
                            <span class="text-secondary style-tiny"><?= esc(mb_strimwidth($p['description'] ?? '', 0, 60, '...')) ?></span>
                        </td>
                        <td><span class="badge bg-danger bg-opacity-25 text-danger font-monospace"><?= esc($p['category']) ?></span></td>
                        <td><span class="font-monospace text-secondary"><?= esc($p['year']) ?></span></td>
                        <td>
                            <?php if (empty($p['contributors'])): ?>
                                <span class="text-secondary style-tiny fst-italic">Tidak ada kontributor ditandai</span>
                            <?php else: ?>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php foreach ($p['contributors'] as $c): ?>
                                        <span class="badge bg-dark border border-secondary border-opacity-25 text-white font-monospace">
                                            <i class="fa-solid fa-user me-1 text-danger"></i> <?= esc($c['full_name']) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($p['is_featured']): ?>
                                <span class="badge bg-warning text-dark font-monospace"><i class="fa-solid fa-star me-1"></i> Featured</span>
                            <?php else: ?>
                                <span class="text-secondary style-tiny font-monospace">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-info me-1" data-bs-toggle="modal" data-bs-target="#editPortfolioModal<?= $p['id'] ?>">
                                <i class="fa-solid fa-pen"></i> Edit
                            </button>
                            <a href="<?= base_url('admin/cms/portfolios/delete/' . $p['id']) ?>" onclick="return confirm('Hapus portofolio ini?')" class="btn btn-sm btn-outline-danger">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- Modal Edit Portofolio Karya -->
                    <div class="modal fade" id="editPortfolioModal<?= $p['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
                                <div class="modal-header border-bottom border-secondary border-opacity-25">
                                    <h5 class="modal-title font-heading"><i class="fa-solid fa-pen text-info me-2"></i> Edit Karya Portofolio</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="<?= base_url('admin/cms/portfolios/update/' . $p['id']) ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <div class="modal-body">
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-8">
                                                <label class="form-label text-secondary small">Judul Karya / Proyek <span class="text-danger">*</span></label>
                                                <input type="text" name="title" class="form-control" value="<?= esc($p['title']) ?>" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-secondary small">Kategori Divisi</label>
                                                <select name="category" class="form-select">
                                                    <option value="Videography" <?= $p['category'] === 'Videography' ? 'selected' : '' ?>>Videography</option>
                                                    <option value="Photography" <?= $p['category'] === 'Photography' ? 'selected' : '' ?>>Photography</option>
                                                    <option value="Graphic Design" <?= $p['category'] === 'Graphic Design' ? 'selected' : '' ?>>Graphic Design</option>
                                                    <option value="Web Development" <?= $p['category'] === 'Web Development' ? 'selected' : '' ?>>Web Development</option>
                                                    <option value="Programming" <?= $p['category'] === 'Programming' ? 'selected' : '' ?>>Programming</option>
                                                    <option value="Broadcasting" <?= $p['category'] === 'Broadcasting' ? 'selected' : '' ?>>Broadcasting</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row g-3 mb-3">
                                            <div class="col-md-4">
                                                <label class="form-label text-secondary small">Tahun Karya</label>
                                                <input type="text" name="year" class="form-control font-monospace" value="<?= esc($p['year']) ?>">
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label text-secondary small">URL External / YouTube Embed</label>
                                                <input type="text" name="external_url" class="form-control font-monospace" value="<?= esc($p['external_url']) ?>" placeholder="https://youtube.com/watch?v=...">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label text-secondary small">Deskripsi Singkat Karya</label>
                                            <textarea name="description" class="form-control" rows="3"><?= esc($p['description']) ?></textarea>
                                        </div>

                                        <!-- Multi-Contributors Selection -->
                                        <?php $activeContribIds = array_column($p['contributors'] ?? [], 'user_id'); ?>
                                        <div class="mb-3">
                                            <label class="form-label text-white small fw-bold"><i class="fa-solid fa-users text-danger me-1"></i> Pilih Anggota Kontributor (Multi-Contributors):</label>
                                            <div class="p-3 rounded-3 bg-black border border-secondary border-opacity-25" style="max-height: 180px; overflow-y: auto;">
                                                <div class="row g-2">
                                                    <?php foreach ($members as $m): ?>
                                                        <div class="col-md-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="contributors[]" value="<?= $m['id'] ?>" id="edit_contrib_<?= $p['id'] ?>_<?= $m['id'] ?>" <?= in_array($m['id'], $activeContribIds) ? 'checked' : '' ?>>
                                                                <label class="form-check-label text-secondary small" for="edit_contrib_<?= $p['id'] ?>_<?= $m['id'] ?>">
                                                                    <strong class="text-white"><?= esc($m['full_name']) ?></strong> (@<?= esc($m['username']) ?>)
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="edit_is_featured_check<?= $p['id'] ?>" <?= $p['is_featured'] ? 'checked' : '' ?>>
                                            <label class="form-check-label text-warning small fw-semibold" for="edit_is_featured_check<?= $p['id'] ?>">
                                                Tampilkan sebagai Karya Unggulan di Halaman Utama (Featured Project)
                                            </label>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top border-secondary border-opacity-25">
                                        <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-red">Update Portofolio</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Portofolio Karya -->
<div class="modal fade" id="addPortfolioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title font-heading"><i class="fa-solid fa-film text-danger me-2"></i> Tambah Karya Portofolio Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/cms/portfolios/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label text-secondary small">Judul Karya / Proyek <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="Contoh: Aftermovie MPLS SMAN 1 Tamansari 2026" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-secondary small">Kategori Divisi</label>
                            <select name="category" class="form-select">
                                <option value="Videography">Videography</option>
                                <option value="Photography">Photography</option>
                                <option value="Graphic Design">Graphic Design</option>
                                <option value="Web Development">Web Development</option>
                                <option value="Programming">Programming</option>
                                <option value="Broadcasting">Broadcasting</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label text-secondary small">Tahun Karya</label>
                            <input type="text" name="year" class="form-control font-monospace" value="<?= date('Y') ?>">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label text-secondary small">URL External / YouTube Embed</label>
                            <input type="text" name="external_url" class="form-control font-monospace" placeholder="https://youtube.com/watch?v=...">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Deskripsi Singkat Karya</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Penjelasan teknis karya, alat yang digunakan, dll..."></textarea>
                    </div>

                    <!-- Multi-Contributors Selection -->
                    <div class="mb-3">
                        <label class="form-label text-white small fw-bold"><i class="fa-solid fa-users text-danger me-1"></i> Pilih Anggota Kontributor (Multi-Contributors):</label>
                        <div class="p-3 rounded-3 bg-black border border-secondary border-opacity-25" style="max-height: 180px; overflow-y: auto;">
                            <div class="row g-2">
                                <?php foreach ($members as $m): ?>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="contributors[]" value="<?= $m['id'] ?>" id="contrib_<?= $m['id'] ?>">
                                            <label class="form-check-label text-secondary small" for="contrib_<?= $m['id'] ?>">
                                                <strong class="text-white"><?= esc($m['full_name']) ?></strong> (@<?= esc($m['username']) ?>)
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured_check">
                        <label class="form-check-label text-warning small fw-semibold" for="is_featured_check">
                            Tampilkan sebagai Karya Unggulan di Halaman Utama (Featured Project)
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25">
                    <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-red">Simpan Portofolio</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
