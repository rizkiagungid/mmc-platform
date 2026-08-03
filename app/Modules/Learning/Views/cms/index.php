<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h4 class="text-white font-heading m-0"><i class="fa-solid fa-book-bookmark text-danger me-2"></i> Learning Center — Materi Pembelajaran</h4>
        <p class="text-secondary small m-0">Kelola dan publikasikan modul kurikulum, artikel tutorial, dan panduan belajar anggota MMC</p>
    </div>
    <?php if (session()->get('role_slug') === 'superadmin'): ?>
        <a href="<?= base_url('admin/learning/create') ?>" class="btn btn-red px-3">
            <i class="fa-solid fa-plus me-1"></i> Buat Materi Baru
        </a>
    <?php endif; ?>
</div>

<!-- Navigation Status Tabs -->
<?php $currentStatus = $filters['status'] ?? 'all'; ?>
<ul class="nav nav-tabs border-secondary border-opacity-25 mb-4 style-tabs">
    <li class="nav-item">
        <a class="nav-link <?= $currentStatus === 'all' ? 'active' : '' ?>" href="<?= base_url('admin/learning?status=all') ?>">
            <i class="fa-solid fa-list me-1"></i> Semua Materi
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $currentStatus === 'published' ? 'active' : '' ?>" href="<?= base_url('admin/learning?status=published') ?>">
            <i class="fa-solid fa-circle-check text-success me-1"></i> Terbit (Published)
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $currentStatus === 'scheduled' ? 'active' : '' ?>" href="<?= base_url('admin/learning?status=scheduled') ?>">
            <i class="fa-solid fa-clock text-info me-1"></i> Terjadwal (Scheduled)
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $currentStatus === 'draft' ? 'active' : '' ?>" href="<?= base_url('admin/learning?status=draft') ?>">
            <i class="fa-solid fa-file-pen text-warning me-1"></i> Draft
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $currentStatus === 'archived' ? 'active' : '' ?>" href="<?= base_url('admin/learning?status=archived') ?>">
            <i class="fa-solid fa-box-archive text-secondary me-1"></i> Arsip
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $currentStatus === 'trash' ? 'active text-danger' : '' ?>" href="<?= base_url('admin/learning?status=trash') ?>">
            <i class="fa-solid fa-trash me-1"></i> Sampah (Trash)
        </a>
    </li>
</ul>

<!-- Filter Toolbar -->
<div class="saas-card p-3 mb-4">
    <form action="<?= base_url('admin/learning') ?>" method="GET" class="row g-2 align-items-center">
        <input type="hidden" name="status" value="<?= esc($currentStatus) ?>">
        <div class="col-md-5">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-dark text-secondary border-secondary"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" name="search" class="form-control bg-dark text-white border-secondary" placeholder="Cari judul, kata kunci, isi materi..." value="<?= esc($filters['search'] ?? '') ?>">
            </div>
        </div>
        <div class="col-md-3">
            <select name="division_id" class="form-select form-select-sm bg-dark text-white border-secondary">
                <option value="">-- Filter Divisi --</option>
                <?php foreach ($divisions as $d): ?>
                    <option value="<?= $d['id'] ?>" <?= ($filters['division_id'] ?? '') == $d['id'] ? 'selected' : '' ?>><?= esc($d['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="category" class="form-select form-select-sm bg-dark text-white border-secondary">
                <option value="">-- Filter Kategori --</option>
                <option value="Tutorial" <?= ($filters['category'] ?? '') === 'Tutorial' ? 'selected' : '' ?>>Tutorial</option>
                <option value="Kurikulum" <?= ($filters['category'] ?? '') === 'Kurikulum' ? 'selected' : '' ?>>Kurikulum</option>
                <option value="Fundamental" <?= ($filters['category'] ?? '') === 'Fundamental' ? 'selected' : '' ?>>Fundamental</option>
                <option value="Best Practice" <?= ($filters['category'] ?? '') === 'Best Practice' ? 'selected' : '' ?>>Best Practice</option>
                <option value="Guide" <?= ($filters['category'] ?? '') === 'Guide' ? 'selected' : '' ?>>Guide</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-sm btn-red flex-fill">Filter</button>
            <a href="<?= base_url('admin/learning?status=' . $currentStatus) ?>" class="btn btn-sm btn-outline-light">Reset</a>
        </div>
    </form>
</div>

<!-- Bulk Action Form & Data Table -->
<form action="<?= base_url('admin/learning/bulk-action') ?>" method="POST" id="bulkForm">
    <?= csrf_field() ?>
    <div class="saas-card p-4">
        <?php if (session()->get('role_slug') === 'superadmin'): ?>
            <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom border-secondary border-opacity-25 flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <select name="bulk_action" class="form-select form-select-sm bg-dark text-white border-secondary font-monospace style-tiny" style="width: 200px;">
                        <option value="">-- Aksi Masal (Bulk) --</option>
                        <?php if ($currentStatus !== 'trash'): ?>
                            <option value="publish">Publikasikan Terpilih</option>
                            <option value="draft">Ubah ke Draft Terpilih</option>
                            <option value="archive">Arsipkan Terpilih</option>
                            <option value="trash">Pindahkan ke Sampah</option>
                        <?php else: ?>
                            <option value="restore">Pulihkan dari Sampah</option>
                            <option value="purge">Hapus Permanen</option>
                        <?php endif; ?>
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline-light style-tiny px-3" onclick="return confirm('Jalankan aksi masal untuk materi terpilih?')">Terapkan Aksi</button>
                </div>
                <div class="text-secondary style-tiny font-monospace">Total Data: <?= count($materials) ?> Materi</div>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle m-0">
                <thead>
                    <tr class="border-bottom border-secondary border-opacity-25 text-secondary font-monospace style-tiny">
                        <?php if (session()->get('role_slug') === 'superadmin'): ?>
                            <th width="30"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                        <?php endif; ?>
                        <th width="60">Cover</th>
                        <th>Judul & Excerpt Materi</th>
                        <th>Divisi & Kategori</th>
                        <th>Status & Akses</th>
                        <th>Pembaca</th>
                        <th>Penulis / Tanggal</th>
                        <th class="text-end" width="160">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($materials)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-secondary">
                                <i class="fa-solid fa-folder-open fs-1 mb-2 d-block opacity-25"></i>
                                Tidak ada data materi pembelajaran.
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($materials as $m): ?>
                        <tr>
                            <?php if (session()->get('role_slug') === 'superadmin'): ?>
                                <td><input type="checkbox" name="selected_ids[]" value="<?= $m['id'] ?>" class="form-check-input item-check"></td>
                            <?php endif; ?>
                            <td>
                                <?php if (!empty($m['thumbnail'])): ?>
                                    <img src="<?= (strpos($m['thumbnail'], 'http') === 0) ? esc($m['thumbnail']) : base_url($m['thumbnail']) ?>" alt="Thumb" class="rounded-2 object-fit-cover" style="width: 48px; height: 48px;">
                                <?php else: ?>
                                    <div class="rounded-2 bg-black d-flex align-items-center justify-content-center border border-secondary border-opacity-25" style="width: 48px; height: 48px;">
                                        <i class="fa-solid fa-book-open text-danger"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold text-white mb-1">
                                    <?php if ($m['is_featured']): ?>
                                        <span class="badge bg-warning text-dark me-1" title="Featured Material"><i class="fa-solid fa-star"></i></span>
                                    <?php endif; ?>
                                    <?= esc($m['title']) ?>
                                </div>
                                <div class="text-secondary style-tiny line-clamp-1"><?= esc(mb_strimwidth($m['excerpt'] ?? '', 0, 90, '...')) ?></div>
                                <?php if (!empty($m['tags'])): ?>
                                    <div class="mt-1 d-flex flex-wrap gap-1">
                                        <?php foreach ($m['tags'] as $t): ?>
                                            <span class="badge bg-black border border-secondary border-opacity-25 text-secondary style-tiny font-monospace">#<?= esc($t['name']) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-danger bg-opacity-25 text-danger font-monospace mb-1 d-inline-block"><?= esc($m['division_name'] ?: 'Umum') ?></span><br>
                                <span class="badge bg-secondary font-monospace style-tiny"><?= esc($m['category']) ?></span>
                            </td>
                            <td>
                                <?php
                                    $isScheduled = ($m['status'] === 'published' && !empty($m['published_at']) && strtotime($m['published_at']) > time());
                                ?>
                                <?php if ($m['deleted_at'] !== null): ?>
                                    <span class="badge bg-danger font-monospace"><i class="fa-solid fa-trash me-1"></i> Trash</span>
                                <?php elseif ($isScheduled): ?>
                                    <span class="badge bg-info bg-opacity-25 text-info font-monospace" title="Tayang pada: <?= esc($m['published_at']) ?>"><i class="fa-solid fa-clock me-1"></i> Terjadwal</span>
                                <?php elseif ($m['status'] === 'published'): ?>
                                    <span class="badge bg-success bg-opacity-25 text-success font-monospace"><i class="fa-solid fa-circle-check me-1"></i> Published</span>
                                <?php elseif ($m['status'] === 'draft'): ?>
                                    <span class="badge bg-warning bg-opacity-25 text-warning font-monospace"><i class="fa-solid fa-pen me-1"></i> Draft</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary font-monospace"><i class="fa-solid fa-box-archive me-1"></i> Archived</span>
                                <?php endif; ?>

                                <div class="mt-1 style-tiny font-monospace">
                                    <?php if ($m['visibility'] === 'member'): ?>
                                        <span class="text-warning"><i class="fa-solid fa-lock me-1"></i> Khusus Anggota</span>
                                    <?php else: ?>
                                        <span class="text-secondary"><i class="fa-solid fa-globe me-1"></i> Publik</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="text-white font-monospace small"><i class="fa-solid fa-eye text-danger me-1"></i> <?= number_format($m['views_count']) ?></div>
                                <div class="text-secondary style-tiny font-monospace"><i class="fa-solid fa-clock me-1"></i> <?= $m['reading_time'] ?> min baca</div>
                            </td>
                            <td>
                                <div class="text-white small fw-semibold"><?= esc($m['author_name'] ?: 'Admin') ?></div>
                                <div class="text-secondary style-tiny font-monospace"><?= date('d M Y', strtotime($m['created_at'])) ?></div>
                            </td>
                            <td class="text-end">
                                <?php $publicUrl = base_url('materi/' . $m['slug']); ?>
                                <div class="d-flex justify-content-end gap-1 flex-wrap">
                                    <!-- Copy Link Button -->
                                    <button type="button" class="btn btn-sm btn-outline-light py-1 px-2" onclick="copyToClipboard('<?= esc($publicUrl) ?>')" title="Salin URL Materi">
                                        <i class="fa-solid fa-copy"></i>
                                    </button>

                                    <!-- Open in New Tab -->
                                    <a href="<?= esc($publicUrl) ?>" target="_blank" class="btn btn-sm btn-outline-info py-1 px-2" title="Buka di Tab Baru">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>

                                    <?php if (session()->get('role_slug') === 'superadmin'): ?>
                                        <?php if ($m['deleted_at'] !== null): ?>
                                            <a href="<?= base_url('admin/learning/restore/' . $m['id']) ?>" class="btn btn-sm btn-outline-success py-1 px-2" title="Pulihkan dari Trash">
                                                <i class="fa-solid fa-rotate-left"></i>
                                            </a>
                                            <a href="<?= base_url('admin/learning/purge/' . $m['id']) ?>" onclick="return confirm('HAPUS PERMANEN materi ini beserta pembersihan file aset aman? Aksi tidak dapat dibatalkan!')" class="btn btn-sm btn-danger py-1 px-2" title="Hapus Permanen">
                                                <i class="fa-solid fa-fire"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= base_url('admin/learning/edit/' . $m['id']) ?>" class="btn btn-sm btn-outline-warning py-1 px-2" title="Edit Materi">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="<?= base_url('admin/learning/delete/' . $m['id']) ?>" onclick="return confirm('Pindahkan materi ini ke Trash?')" class="btn btn-sm btn-outline-danger py-1 px-2" title="Pindahkan ke Trash">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</form>

<script>
    document.getElementById('selectAll')?.addEventListener('change', function() {
        document.querySelectorAll('.item-check').forEach(cb => cb.checked = this.checked);
    });

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('URL Materi berhasil disalin ke clipboard:\n' + text);
        }, function(err) {
            prompt('Salin link materi ini:', text);
        });
    }
</script>
<?= $this->endSection() ?>
