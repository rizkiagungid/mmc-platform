<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Homepage Section Builder & WCMS</h4>
        <p class="text-secondary small m-0">Atur susunan seksi, visibilitas, teks Hero banner, dan kartu statistik halaman utama secara dinamis</p>
    </div>

    <a href="<?= base_url('admin/cms/media') ?>" class="btn btn-saas-dark">
        <i class="fa-solid fa-photo-film me-2"></i> Media Library
    </a>
</div>

<!-- Tabs Navigation -->
<ul class="nav nav-tabs border-secondary border-opacity-25 mb-4" id="cmsTab" role="tablist">
    <li class="nav-item">
        <button class="nav-link active text-white fw-semibold" id="sections-tab" data-bs-toggle="tab" data-bs-target="#sections-pane">
            <i class="fa-solid fa-layer-group me-1 text-danger"></i> Susunan Seksi Homepage
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link text-white fw-semibold" id="hero-tab" data-bs-toggle="tab" data-bs-target="#hero-pane">
            <i class="fa-solid fa-heading me-1 text-info"></i> Hero Banner
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link text-white fw-semibold" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats-pane">
            <i class="fa-solid fa-chart-simple me-1 text-warning"></i> Kartu Statistik Homepage
        </button>
    </li>
</ul>

<div class="tab-content" id="cmsTabContent">
    <!-- 1. Homepage Sections Order & Layout Settings -->
    <div class="tab-pane fade show active" id="sections-pane">
        <div class="saas-card p-4">
            <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-sliders text-danger me-2"></i> Pengaturan Visibilitas & Tata Letak Seksi</h5>
            <form action="<?= base_url('admin/cms/sections/update') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="table-responsive mb-4">
                    <table class="table table-dark table-dark-saas align-middle">
                        <thead>
                            <tr>
                                <th style="width: 60px;">Urutan</th>
                                <th>Nama Seksi</th>
                                <th>Tipe Container</th>
                                <th>Warna Latar</th>
                                <th>Padding Top/Bottom</th>
                                <th class="text-center">Tampilkan (Active)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sections as $idx => $sec): ?>
                                <tr>
                                    <td>
                                        <input type="hidden" name="sections[<?= $idx ?>][id]" value="<?= $sec['id'] ?>">
                                        <input type="number" name="sections[<?= $idx ?>][sort_order]" class="form-control form-control-sm font-monospace text-center" value="<?= esc($sec['sort_order']) ?>" min="1" max="99" style="width: 70px;">
                                    </td>
                                    <td>
                                        <input type="text" name="sections[<?= $idx ?>][name]" class="form-control form-control-sm fw-semibold" value="<?= esc($sec['name']) ?>" required>
                                        <span class="text-secondary style-tiny font-monospace">Key: <?= esc($sec['section_key']) ?></span>
                                    </td>
                                    <td>
                                        <select name="sections[<?= $idx ?>][container_type]" class="form-select form-select-sm">
                                            <option value="container" <?= $sec['container_type'] === 'container' ? 'selected' : '' ?>>Container (Fixed Width)</option>
                                            <option value="container-fluid" <?= $sec['container_type'] === 'container-fluid' ? 'selected' : '' ?>>Container-Fluid (Full Width)</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="sections[<?= $idx ?>][bg_color]" class="form-control form-control-sm font-monospace" value="<?= esc($sec['bg_color'] ?: 'transparent') ?>">
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <input type="text" name="sections[<?= $idx ?>][padding_top]" class="form-control form-control-sm font-monospace" placeholder="py-5" value="<?= esc($sec['padding_top']) ?>">
                                            <input type="text" name="sections[<?= $idx ?>][padding_bottom]" class="form-control form-control-sm font-monospace" placeholder="py-5" value="<?= esc($sec['padding_bottom']) ?>">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" name="sections[<?= $idx ?>][is_active]" value="1" <?= $sec['is_active'] ? 'checked' : '' ?>>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <button type="submit" class="btn btn-red px-4">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Penataan Seksi
                </button>
            </form>
        </div>
    </div>

    <!-- 2. Hero Section Settings -->
    <div class="tab-pane fade" id="hero-pane">
        <div class="saas-card p-4 col-lg-9 mx-auto">
            <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-heading text-info me-2"></i> Edit Teks & CTA Hero Banner</h5>
            <form action="<?= base_url('admin/cms/hero/update') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-medium">Sub-Judul Atas (Subtitle Badge)</label>
                    <input type="text" name="subtitle" class="form-control" value="<?= esc($hero['subtitle'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-medium">Judul Utama (Hero Title) <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control form-control-lg" required value="<?= esc($hero['title'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-medium">Deskripsi Singkat</label>
                    <textarea name="description" class="form-control" rows="3"><?= esc($hero['description'] ?? '') ?></textarea>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium">Teks Tombol Utama (Primary CTA)</label>
                        <input type="text" name="primary_btn_text" class="form-control" value="<?= esc($hero['primary_btn_text'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium">URL Tombol Utama</label>
                        <input type="text" name="primary_btn_url" class="form-control" value="<?= esc($hero['primary_btn_url'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium">Teks Tombol Sekunder</label>
                        <input type="text" name="secondary_btn_text" class="form-control" value="<?= esc($hero['secondary_btn_text'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium">URL Tombol Sekunder</label>
                        <input type="text" name="secondary_btn_url" class="form-control" value="<?= esc($hero['secondary_btn_url'] ?? '') ?>">
                    </div>
                </div>

                <button type="submit" class="btn btn-red px-4">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Hero Section
                </button>
            </form>
        </div>
    </div>

    <!-- 3. Homepage Stats CMS -->
    <div class="tab-pane fade" id="stats-pane">
        <div class="saas-card p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="text-white font-heading m-0"><i class="fa-solid fa-chart-simple text-warning me-2"></i> Kartu Angka Statistik Homepage</h5>
                <button type="button" class="btn btn-sm btn-red" data-bs-toggle="modal" data-bs-target="#addStatModal">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Statistik
                </button>
            </div>

            <div class="row g-3">
                <?php foreach ($stats as $st): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 d-flex flex-column justify-content-between h-100">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <i class="fa-solid <?= esc($st['icon']) ?> fs-4 text-danger"></i>
                                    <span class="badge bg-secondary font-monospace">Urutan: <?= esc($st['sort_order']) ?></span>
                                </div>
                                <h3 class="text-white font-heading fw-bold mb-1"><?= esc($st['prefix'] . $st['value'] . $st['suffix']) ?></h3>
                                <div class="text-secondary small fw-semibold"><?= esc($st['label']) ?></div>
                            </div>
                            <div class="mt-3 pt-2 border-top border-secondary border-opacity-10 text-end">
                                <a href="<?= base_url('admin/cms/stats/delete/' . $st['id']) ?>" onclick="return confirm('Hapus statistik ini?')" class="btn btn-sm btn-outline-danger py-0">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Statistik -->
<div class="modal fade" id="addStatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title font-heading"><i class="fa-solid fa-plus text-danger me-2"></i> Tambah Kartu Statistik</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/cms/stats/save') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Label Statistik <span class="text-danger">*</span></label>
                        <input type="text" name="label" class="form-control" placeholder="Contoh: Total Anggota Active" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Nilai Angka (Value) <span class="text-danger">*</span></label>
                        <input type="text" name="value" class="form-control font-monospace" placeholder="Contoh: 120" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label text-secondary small">Prefix (Awalan)</label>
                            <input type="text" name="prefix" class="form-control" placeholder="Contoh: Rp / >">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small">Suffix (Akhiran)</label>
                            <input type="text" name="suffix" class="form-control" placeholder="Contoh: + / K">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label text-secondary small">Ikon FontAwesome</label>
                            <input type="text" name="icon" class="form-control font-monospace" value="fa-users" placeholder="fa-users">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small">Urutan (Sort Order)</label>
                            <input type="number" name="sort_order" class="form-control font-monospace" value="1" min="1">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25">
                    <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-red">Simpan Statistik</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
