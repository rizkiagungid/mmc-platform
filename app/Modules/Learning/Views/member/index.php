<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h4 class="text-white font-heading m-0"><i class="fa-solid fa-book-bookmark text-danger me-2"></i> Materi Pembelajaran — Portal Anggota</h4>
        <p class="text-secondary small m-0">Akses seluruh modul kurikulum pelatihan, panduan teknis, dan tutorial eksklusif MMC</p>
    </div>
    <a href="<?= base_url('materi') ?>" target="_blank" class="btn btn-sm btn-outline-light style-tiny">
        <i class="fa-solid fa-globe me-1"></i> Buka Website Publik
    </a>
</div>

<!-- Search & Filter Card -->
<div class="saas-card p-3 mb-4">
    <form action="<?= base_url('member/learning') ?>" method="GET" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-dark text-secondary border-secondary"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" name="q" class="form-control bg-dark text-white border-secondary" placeholder="Cari judul, topik, atau isi materi..." value="<?= esc($filters['search'] ?? '') ?>">
            </div>
        </div>
        <div class="col-md-3">
            <select name="div" class="form-select form-select-sm bg-dark text-white border-secondary">
                <option value="">-- Filter Divisi --</option>
                <?php foreach ($divisions as $d): ?>
                    <option value="<?= $d['id'] ?>" <?= ($filters['division_id'] ?? '') == $d['id'] ? 'selected' : '' ?>><?= esc($d['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="cat" class="form-select form-select-sm bg-dark text-white border-secondary">
                <option value="">-- Filter Kategori --</option>
                <option value="Tutorial" <?= ($filters['category'] ?? '') === 'Tutorial' ? 'selected' : '' ?>>Tutorial</option>
                <option value="Kurikulum" <?= ($filters['category'] ?? '') === 'Kurikulum' ? 'selected' : '' ?>>Kurikulum</option>
                <option value="Fundamental" <?= ($filters['category'] ?? '') === 'Fundamental' ? 'selected' : '' ?>>Fundamental</option>
                <option value="Best Practice" <?= ($filters['category'] ?? '') === 'Best Practice' ? 'selected' : '' ?>>Best Practice</option>
                <option value="Guide" <?= ($filters['category'] ?? '') === 'Guide' ? 'selected' : '' ?>>Guide</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-sm btn-red flex-fill">Cari</button>
            <a href="<?= base_url('member/learning') ?>" class="btn btn-sm btn-outline-light">Reset</a>
        </div>
    </form>
</div>

<!-- Material Cards Grid -->
<div class="row g-4">
    <?php if (empty($materials)): ?>
        <div class="col-12 text-center py-5 text-secondary saas-card">
            <i class="fa-solid fa-book-open fs-1 mb-2 d-block opacity-25"></i>
            Belum ada materi pembelajaran yang cocok dengan filter Anda.
        </div>
    <?php endif; ?>

    <?php foreach ($materials as $m): ?>
        <div class="col-md-6 col-lg-4">
            <div class="saas-card saas-card-glow h-100 d-flex flex-column justify-content-between overflow-hidden">
                <div>
                    <div class="position-relative bg-dark overflow-hidden" style="height: 170px;">
                        <?php if (!empty($m['thumbnail'])): ?>
                            <img src="<?= (strpos($m['thumbnail'], 'http') === 0) ? esc($m['thumbnail']) : base_url($m['thumbnail']) ?>" alt="<?= esc($m['title']) ?>" class="w-100 h-100 object-fit-cover">
                        <?php else: ?>
                            <div class="w-100 h-100 bg-black d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #0f172a, #1e1b4b);">
                                <i class="fa-solid fa-graduation-cap text-danger fs-1"></i>
                            </div>
                        <?php endif; ?>

                        <?php if ($m['visibility'] === 'member'): ?>
                            <span class="position-absolute top-0 start-0 bg-warning text-dark font-monospace fw-bold style-tiny p-1 px-2 m-2 rounded shadow">
                                <i class="fa-solid fa-lock me-1"></i> KHUSUS ANGGOTA
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="p-4">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-danger font-monospace style-tiny"><?= esc($m['division_name'] ?: 'Umum') ?></span>
                            <span class="badge bg-secondary font-monospace style-tiny"><?= esc($m['category']) ?></span>
                        </div>
                        <h6 class="text-white font-heading mb-2">
                            <a href="<?= base_url('member/learning/' . $m['slug']) ?>" class="text-white text-decoration-none hover-danger"><?= esc($m['title']) ?></a>
                        </h6>
                        <p class="text-secondary style-tiny mb-3 line-clamp-2"><?= esc(mb_strimwidth($m['excerpt'] ?? '', 0, 100, '...')) ?></p>
                    </div>
                </div>

                <div class="p-4 pt-0 border-top border-secondary border-opacity-10 mt-auto">
                    <div class="d-flex align-items-center justify-content-between pt-3 text-secondary style-tiny font-monospace mb-3">
                        <div><i class="fa-solid fa-user me-1 text-danger"></i> <?= esc($m['author_name'] ?: 'Admin') ?></div>
                        <div><i class="fa-solid fa-clock me-1 text-warning"></i> <?= $m['reading_time'] ?> min</div>
                        <div><i class="fa-solid fa-eye me-1 text-info"></i> <?= number_format($m['views_count']) ?></div>
                    </div>
                    <a href="<?= base_url('member/learning/' . $m['slug']) ?>" class="btn btn-sm btn-red w-100">
                        <i class="fa-solid fa-book-reader me-1"></i> Pelajari Materi Ini
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?= $this->endSection() ?>
