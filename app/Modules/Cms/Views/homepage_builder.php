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
<ul class="nav nav-tabs mb-4" id="cmsTab" role="tablist">
    <li class="nav-item">
        <button class="nav-link active fw-semibold" id="sections-tab" data-bs-toggle="tab" data-bs-target="#sections-pane">
            <i class="fa-solid fa-layer-group me-1 text-danger"></i> Susunan Seksi Homepage
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-semibold" id="hero-tab" data-bs-toggle="tab" data-bs-target="#hero-pane">
            <i class="fa-solid fa-heading me-1 text-info"></i> Hero Banner
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-semibold" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats-pane">
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
    <!-- 2. Hero Section Settings -->
    <div class="tab-pane fade" id="hero-pane">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="saas-card p-4 h-100">
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
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Teks Hero Section
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="saas-card p-4 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h5 class="text-white font-heading m-0"><i class="fa-solid fa-film text-danger me-2"></i> Hero Video Slider</h5>
                                <p class="text-secondary style-tiny m-0">Tambahkan dan atur slide video background pada bagian Hero Banner</p>
                            </div>
                            <button type="button" class="btn btn-sm btn-red" data-bs-toggle="modal" data-bs-target="#addHeroVideoModal">
                                <i class="fa-solid fa-plus me-1"></i> Tambah Video
                            </button>
                        </div>

                        <?php if (empty($heroVideoSlides)): ?>
                            <div class="p-4 rounded-3 bg-black border border-secondary border-opacity-25 text-center text-secondary my-3">
                                <i class="fa-solid fa-video-slash fs-2 mb-2 d-block opacity-25"></i>
                                Belum ada slide video hero. Klik "Tambah Video" untuk mengunggah file MP4/WebM atau memasukkan URL video.
                            </div>
                        <?php else: ?>
                            <div class="d-flex flex-column gap-3 my-3" style="max-height: 420px; overflow-y: auto;">
                                <?php foreach ($heroVideoSlides as $vSlide): ?>
                                    <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 d-flex align-items-center justify-content-between gap-3">
                                        <div class="d-flex align-items-center gap-3 overflow-hidden">
                                            <div class="rounded bg-black border border-secondary border-opacity-25 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 80px; height: 50px; overflow: hidden;">
                                                <?php if (str_contains($vSlide['video_url'], 'youtube') || str_contains($vSlide['video_url'], 'youtu.be') || str_contains($vSlide['video_url'], '<iframe')): ?>
                                                    <i class="fa-brands fa-youtube text-danger fs-3"></i>
                                                <?php elseif ($vSlide['video_type'] === 'file'): ?>
                                                    <video src="<?= base_url($vSlide['video_url']) ?>" class="w-100 h-100 object-fit-cover" muted></video>
                                                <?php else: ?>
                                                    <i class="fa-solid fa-link text-info fs-4"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="overflow-hidden">
                                                <h6 class="text-white font-heading mb-1 text-truncate" style="max-width: 220px;">
                                                    <?= esc($vSlide['title'] ?: 'Slide Video #' . $vSlide['id']) ?>
                                                </h6>
                                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                                    <span class="badge bg-secondary bg-opacity-25 text-secondary style-tiny">
                                                        <?= strtoupper($vSlide['video_type']) ?>
                                                    </span>
                                                    <span class="badge <?= $vSlide['is_active'] ? 'bg-success bg-opacity-25 text-success' : 'bg-danger bg-opacity-25 text-danger' ?> style-tiny">
                                                        <?= $vSlide['is_active'] ? 'Aktif' : 'Non-aktif' ?>
                                                    </span>
                                                    <span class="text-secondary style-tiny font-monospace">Urutan: <?= $vSlide['sort_order'] ?></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2 flex-shrink-0">
                                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#editHeroVideoModal<?= $vSlide['id'] ?>">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <a href="<?= base_url('admin/cms/hero/video/delete/' . $vSlide['id']) ?>" onclick="return confirm('Hapus video slide ini?')" class="btn btn-sm btn-outline-danger">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Homepage Stats CMS -->
    <div class="tab-pane fade" id="stats-pane">
        <div class="saas-card p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h5 class="text-white font-heading m-0"><i class="fa-solid fa-chart-simple text-warning me-2"></i> Kartu Angka Statistik Homepage</h5>
                    <p class="text-secondary small m-0">Kelola angka indikator statistik yang ditampilkan di halaman utama</p>
                </div>
                <button type="button" class="btn btn-sm btn-red" data-bs-toggle="modal" data-bs-target="#addStatModal">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Statistik
                </button>
            </div>

            <div class="row g-3">
                <?php if (empty($stats)): ?>
                    <div class="col-12 text-center py-5 text-secondary">
                        <i class="fa-solid fa-chart-pie fs-1 mb-2 d-block opacity-25"></i>
                        Belum ada kartu statistik. Klik "Tambah Statistik" untuk membuat baru.
                    </div>
                <?php endif; ?>

                <?php foreach ($stats as $st): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 d-flex flex-column justify-content-between h-100 position-relative">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <i class="fa-solid <?= esc($st['icon']) ?> fs-4 text-danger"></i>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <?php if (!empty($st['is_auto'])): ?>
                                            <span class="badge bg-info bg-opacity-25 text-info font-monospace" style="font-size: 0.65rem;" title="Nilai dihitung otomatis real-time dari database"><i class="fa-solid fa-rotate me-1"></i> Auto DB</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary font-monospace" style="font-size: 0.65rem;"><i class="fa-solid fa-keyboard me-1"></i> Manual</span>
                                        <?php endif; ?>

                                        <?php if ($st['is_active']): ?>
                                            <span class="badge bg-success bg-opacity-25 text-success font-monospace" style="font-size: 0.65rem;">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary font-monospace" style="font-size: 0.65rem;">Nonaktif</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <h3 class="text-white font-heading fw-bold mb-1"><?= esc($st['prefix'] . $st['value'] . $st['suffix']) ?></h3>
                                <div class="text-secondary small fw-semibold"><?= esc($st['label']) ?></div>
                            </div>
                            <div class="mt-3 pt-2 border-top border-secondary border-opacity-10 d-flex justify-content-end gap-1">
                                <button type="button" class="btn btn-sm btn-outline-warning py-1 px-2" data-bs-toggle="modal" data-bs-target="#editStatModal<?= $st['id'] ?>" title="Edit Statistik">
                                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                </button>
                                <a href="<?= base_url('admin/cms/stats/delete/' . $st['id']) ?>" onclick="return confirm('Hapus statistik ini?')" class="btn btn-sm btn-outline-danger py-1 px-2" title="Hapus Statistik">
                                    <i class="fa-solid fa-trash me-1"></i> Hapus
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Edit Statistik -->
                    <div class="modal fade" id="editStatModal<?= $st['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
                                <div class="modal-header border-bottom border-secondary border-opacity-25">
                                    <h5 class="modal-title font-heading"><i class="fa-solid fa-pen-to-square text-warning me-2"></i> Edit Kartu Statistik</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="<?= base_url('admin/cms/stats/save') ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= $st['id'] ?>">

                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label text-secondary small">Label Statistik <span class="text-danger">*</span></label>
                                            <input type="text" name="label" class="form-control" value="<?= esc($st['label']) ?>" placeholder="Contoh: Total Anggota Active" required>
                                        </div>

                                        <?php
                                            $cardAutoSource = $st['auto_source'] ?? '';
                                            if (empty($cardAutoSource)) {
                                                if (stripos($st['label'], 'Anggota') !== false) $cardAutoSource = 'total_members';
                                                elseif (stripos($st['label'], 'Juara') !== false || stripos($st['label'], 'Penghargaan') !== false || stripos($st['label'], 'Prestasi') !== false) $cardAutoSource = 'total_achievements';
                                                elseif (stripos($st['label'], 'Proyek') !== false || stripos($st['label'], 'Karya') !== false || stripos($st['label'], 'Portofolio') !== false) $cardAutoSource = 'total_portfolios';
                                            }

                                            $sourceName = 'Database Real-time';
                                            if ($cardAutoSource === 'total_members') $sourceName = 'Total Anggota (Tabel Users)';
                                            elseif ($cardAutoSource === 'total_achievements') $sourceName = 'Penghargaan Juara (Tabel Achievements)';
                                            elseif ($cardAutoSource === 'total_portfolios') $sourceName = 'Proyek Karya (Tabel Portfolios)';
                                        ?>
                                        <input type="hidden" name="auto_source" value="<?= esc($cardAutoSource) ?>">

                                        <!-- Dedicated Auto Switch for this specific card -->
                                        <div class="p-3 rounded-3 bg-black border border-secondary border-opacity-25 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_auto" id="is_auto_<?= $st['id'] ?>" value="1" <?= (!empty($st['is_auto'])) ? 'checked' : '' ?>>
                                                <label class="form-check-label text-info fw-semibold small" for="is_auto_<?= $st['id'] ?>">
                                                    <i class="fa-solid fa-calculator me-1"></i> Hitung Otomatis Real-time <?= esc($sourceName) ?>
                                                </label>
                                            </div>
                                            <div class="style-tiny text-secondary mt-1">Apabila switch aktif, nilai statistik diambil dari database. Jika mati, menggunakan nilai manual di bawah.</div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label text-secondary small">Nilai Angka Manual (Digunakan Jika Switch Mati)</label>
                                            <input type="text" name="value" class="form-control font-monospace" value="<?= esc($st['value']) ?>" placeholder="Contoh: 120 / 2017">
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label class="form-label text-secondary small">Prefix (Awalan)</label>
                                                <input type="text" name="prefix" class="form-control" value="<?= esc($st['prefix']) ?>" placeholder="Contoh: Rp / >">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label text-secondary small">Suffix (Akhiran)</label>
                                                <input type="text" name="suffix" class="form-control" value="<?= esc($st['suffix']) ?>" placeholder="Contoh: + / K">
                                            </div>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label class="form-label text-secondary small">Ikon FontAwesome</label>
                                                <input type="text" name="icon" class="form-control font-monospace" value="<?= esc($st['icon']) ?>" placeholder="fa-users">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label text-secondary small">Urutan (Sort Order)</label>
                                                <input type="number" name="sort_order" class="form-control font-monospace" value="<?= esc($st['sort_order']) ?>" min="1">
                                            </div>
                                        </div>
                                        <div class="form-check form-switch mt-3">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active_<?= $st['id'] ?>" value="1" <?= $st['is_active'] ? 'checked' : '' ?>>
                                            <label class="form-check-input-label text-secondary small ms-2" for="is_active_<?= $st['id'] ?>">Tampilkan di Homepage (Aktif)</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top border-secondary border-opacity-25">
                                        <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-red">Perbarui Statistik</button>
                                    </div>
                                </form>
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

                    <!-- Option: Auto DB Switch & Source Selection -->
                    <div class="p-3 rounded-3 bg-black border border-secondary border-opacity-25 mb-3">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_auto" id="add_is_auto" value="1" onchange="document.getElementById('add_auto_source_wrapper').style.display = this.checked ? 'block' : 'none'">
                            <label class="form-check-label text-info fw-semibold small" for="add_is_auto">
                                <i class="fa-solid fa-calculator me-1"></i> Hitung Otomatis Real-time dari Database
                            </label>
                        </div>
                        
                        <div id="add_auto_source_wrapper" style="display: none;" class="mt-2 pt-2 border-top border-secondary border-opacity-25">
                            <label class="form-label text-secondary style-tiny mb-1">Pilih Kategori Hitung Otomatis:</label>
                            <select name="auto_source" class="form-select form-select-sm font-monospace">
                                <option value="total_members">👥 Total Anggota (Tabel Users)</option>
                                <option value="total_achievements">🏆 Penghargaan Juara (Tabel Achievements)</option>
                                <option value="total_portfolios">📹 Proyek Karya (Tabel Portfolios)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Nilai Angka Manual (Diisi jika memilih Input Manual)</label>
                        <input type="text" name="value" class="form-control font-monospace" placeholder="Contoh: 120 / 2017">
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
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="add_is_active" value="1" checked>
                        <label class="form-check-input-label text-secondary small ms-2" for="add_is_active">Tampilkan di Homepage (Aktif)</label>
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

<!-- Modal Tambah Hero Video Slide -->
<div class="modal fade" id="addHeroVideoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title font-heading"><i class="fa-solid fa-video text-danger me-2"></i> Tambah Video Hero Banner</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/cms/hero/video/save') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Judul Slide (Opsional)</label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: Showcase Videografi 2026">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Sub-judul / Teks Singkat (Opsional)</label>
                        <input type="text" name="subtitle" class="form-control" placeholder="Contoh: Karya Siswa Ekstrakurikuler MMC">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Metode Sumber Video</label>
                        <select name="video_type" class="form-select bg-dark text-white border-secondary border-opacity-50" onchange="
                            if (this.value === 'file') {
                                document.getElementById('add_file_input_wrap').style.display = 'block';
                                document.getElementById('add_url_input_wrap').style.display = 'none';
                            } else {
                                document.getElementById('add_file_input_wrap').style.display = 'none';
                                document.getElementById('add_url_input_wrap').style.display = 'block';
                            }
                        ">
                            <option value="file" selected>📁 Unggah File Video (MP4 / WebM / OGG)</option>
                            <option value="url">🌐 Tempel Link / URL Video (MP4 Direct Link)</option>
                        </select>
                    </div>

                    <div id="add_file_input_wrap" class="mb-3">
                        <label class="form-label text-secondary small">File Video <span class="text-danger">*</span></label>
                        <input type="file" name="video_file" class="form-control bg-dark text-white border-secondary border-opacity-50" accept="video/mp4,video/webm,video/ogg">
                        <div class="form-text text-secondary style-tiny">Format: MP4, WebM, OGG (Maksimal 30MB).</div>
                    </div>

                    <div id="add_url_input_wrap" class="mb-3" style="display: none;">
                        <label class="form-label text-secondary small">URL Video Direct Link <span class="text-danger">*</span></label>
                        <input type="text" name="video_url_input" class="form-control font-monospace" placeholder="https://example.com/video.mp4">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label text-secondary small">Urutan Tampil (Sort Order)</label>
                            <input type="number" name="sort_order" class="form-control font-monospace" value="1" min="0">
                        </div>
                        <div class="col-6 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_active" id="add_v_active" value="1" checked>
                                <label class="form-check-label text-secondary small ms-1" for="add_v_active">Aktifkan Slide</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25">
                    <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-red">Simpan Video Slide</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if (!empty($heroVideoSlides)): ?>
    <?php foreach ($heroVideoSlides as $vSlide): ?>
        <!-- Modal Edit Hero Video Slide -->
        <div class="modal fade" id="editHeroVideoModal<?= $vSlide['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
                    <div class="modal-header border-bottom border-secondary border-opacity-25">
                        <h5 class="modal-title font-heading"><i class="fa-solid fa-pen text-info me-2"></i> Edit Video Hero Banner</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?= base_url('admin/cms/hero/video/save') ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $vSlide['id'] ?>">

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label text-secondary small">Judul Slide (Opsional)</label>
                                <input type="text" name="title" class="form-control" value="<?= esc($vSlide['title']) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary small">Sub-judul / Teks Singkat (Opsional)</label>
                                <input type="text" name="subtitle" class="form-control" value="<?= esc($vSlide['subtitle']) ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-secondary small">Metode Sumber Video</label>
                                <select name="video_type" class="form-select bg-dark text-white border-secondary border-opacity-50" onchange="
                                    if (this.value === 'file') {
                                        document.getElementById('edit_file_wrap<?= $vSlide['id'] ?>').style.display = 'block';
                                        document.getElementById('edit_url_wrap<?= $vSlide['id'] ?>').style.display = 'none';
                                    } else {
                                        document.getElementById('edit_file_wrap<?= $vSlide['id'] ?>').style.display = 'none';
                                        document.getElementById('edit_url_wrap<?= $vSlide['id'] ?>').style.display = 'block';
                                    }
                                ">
                                    <option value="file" <?= $vSlide['video_type'] === 'file' ? 'selected' : '' ?>>📁 Unggah File Video (MP4 / WebM / OGG)</option>
                                    <option value="url" <?= $vSlide['video_type'] === 'url' ? 'selected' : '' ?>>🌐 Tempel Link / URL Video (MP4 Direct Link)</option>
                                </select>
                            </div>

                            <div id="edit_file_wrap<?= $vSlide['id'] ?>" class="mb-3" style="display: <?= $vSlide['video_type'] === 'file' ? 'block' : 'none' ?>;">
                                <label class="form-label text-secondary small">Ganti File Video (Opsional)</label>
                                <?php if ($vSlide['video_type'] === 'file' && !empty($vSlide['video_url'])): ?>
                                    <div class="p-2 mb-2 bg-black rounded border border-secondary border-opacity-25 style-tiny font-monospace text-truncate">
                                        <i class="fa-solid fa-video me-1 text-danger"></i> <?= esc($vSlide['video_url']) ?>
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="video_file" class="form-control bg-dark text-white border-secondary border-opacity-50" accept="video/mp4,video/webm,video/ogg">
                                <div class="form-text text-secondary style-tiny">Format: MP4, WebM, OGG (Maksimal 30MB). Biarkan kosong jika tidak mengubah file.</div>
                            </div>

                            <div id="edit_url_wrap<?= $vSlide['id'] ?>" class="mb-3" style="display: <?= $vSlide['video_type'] === 'url' ? 'block' : 'none' ?>;">
                                <label class="form-label text-secondary small">URL Video Direct Link</label>
                                <input type="text" name="video_url_input" class="form-control font-monospace" value="<?= esc($vSlide['video_url']) ?>" placeholder="https://example.com/video.mp4">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label text-secondary small">Urutan Tampil (Sort Order)</label>
                                    <input type="number" name="sort_order" class="form-control font-monospace" value="<?= esc($vSlide['sort_order']) ?>" min="0">
                                </div>
                                <div class="col-6 d-flex align-items-end">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="edit_v_active<?= $vSlide['id'] ?>" value="1" <?= $vSlide['is_active'] ? 'checked' : '' ?>>
                                        <label class="form-check-label text-secondary small ms-1" for="edit_v_active<?= $vSlide['id'] ?>">Aktifkan Slide</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-top border-secondary border-opacity-25">
                            <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-red">Update Video Slide</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>
