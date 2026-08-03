<?= $this->extend('layouts/master_public') ?>

<?= $this->section('content') ?>
<section class="py-5">
    <div class="container py-4">
        <!-- Hero Header -->
        <div class="text-center mb-5">
            <span class="text-danger font-monospace text-uppercase fw-bold" style="letter-spacing: 0.1em;">LEARNING CENTER MMC</span>
            <h1 class="display-5 fw-bold text-white font-heading mt-2">Pusat Materi Pembelajaran & Panduan Digital</h1>
            <p class="text-secondary col-lg-8 mx-auto">Eksplorasi modul kurikulum, tutorial videografi, fotografi, penyiaran broadcast, dan pengembangan software buatan Multimedia Club SMAN 1 Tamansari.</p>

            <!-- Search Bar & Filters -->
            <form action="<?= base_url('materi') ?>" method="GET" class="col-lg-8 mx-auto mt-4">
                <div class="input-group input-group-lg shadow-lg">
                    <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fa-solid fa-magnifying-glass text-danger"></i></span>
                    <input type="text" name="q" class="form-control bg-dark text-white border-secondary" placeholder="Cari materi pembelajaran, topik, atau kata kunci..." value="<?= esc($filters['search'] ?? '') ?>">
                    <button class="btn btn-red px-4 font-heading fw-bold" type="submit">Cari Materi</button>
                </div>
            </form>

            <!-- Division Filter Pills -->
            <div class="d-flex flex-wrap align-items-center justify-content-center gap-2 mt-4">
                <a href="<?= base_url('materi') ?>" class="badge p-2 px-3 <?= empty($filters['division_id']) ? 'bg-danger text-white' : 'bg-dark text-secondary border border-secondary' ?> text-decoration-none font-monospace">
                    Semua Divisi
                </a>
                <?php foreach ($divisions as $d): ?>
                    <a href="<?= base_url('materi?div=' . $d['id']) ?>" class="badge p-2 px-3 <?= ($filters['division_id'] ?? '') == $d['id'] ? 'bg-danger text-white' : 'bg-dark text-secondary border border-secondary' ?> text-decoration-none font-monospace">
                        <?= esc($d['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Featured Materials Banner (If Any) -->
        <?php if (!empty($featured) && empty($filters['search']) && empty($filters['division_id'])): ?>
            <div class="mb-5">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="text-white font-heading fw-bold m-0"><i class="fa-solid fa-star text-warning me-2"></i> Materi Pembelajaran Unggulan (Featured)</h4>
                </div>
                <div class="row g-4">
                    <?php foreach ($featured as $f): ?>
                        <div class="col-lg-4">
                            <div class="saas-card saas-card-glow h-100 d-flex flex-column justify-content-between overflow-hidden">
                                <div>
                                    <div class="position-relative bg-dark overflow-hidden" style="height: 180px;">
                                        <?php if (!empty($f['thumbnail'])): ?>
                                            <img src="<?= (strpos($f['thumbnail'], 'http') === 0) ? esc($f['thumbnail']) : base_url($f['thumbnail']) ?>" alt="<?= esc($f['title']) ?>" class="w-100 h-100 object-fit-cover">
                                        <?php else: ?>
                                            <div class="w-100 h-100 bg-black d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #1e1b4b, #31102f);">
                                                <i class="fa-solid fa-book-open text-danger display-4"></i>
                                            </div>
                                        <?php endif; ?>
                                        <span class="position-absolute top-0 end-0 bg-warning text-dark font-monospace fw-bold style-tiny p-1 px-2 m-2 rounded shadow">FEATURED</span>
                                    </div>
                                    <div class="p-4">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge bg-danger font-monospace"><?= esc($f['division_name'] ?: 'Umum') ?></span>
                                            <span class="badge bg-secondary font-monospace"><?= esc($f['category']) ?></span>
                                        </div>
                                        <h5 class="text-white font-heading mb-2">
                                            <a href="<?= base_url('materi/' . $f['slug']) ?>" class="text-white text-decoration-none hover-danger"><?= esc($f['title']) ?></a>
                                        </h5>
                                        <p class="text-secondary small mb-3"><?= esc(mb_strimwidth($f['excerpt'] ?? '', 0, 100, '...')) ?></p>
                                    </div>
                                </div>
                                <div class="p-4 pt-0 border-top border-secondary border-opacity-10 mt-auto d-flex align-items-center justify-content-between text-secondary style-tiny font-monospace pt-3">
                                    <div><i class="fa-solid fa-clock me-1 text-danger"></i> <?= $f['reading_time'] ?> min baca</div>
                                    <a href="<?= base_url('materi/' . $f['slug']) ?>" class="btn btn-sm btn-outline-danger px-3">Baca Sekarang <i class="fa-solid fa-arrow-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Main Materials Grid -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="text-white font-heading fw-bold m-0"><i class="fa-solid fa-book-open text-danger me-2"></i> Daftar Materi Pembelajaran</h4>
            <div class="text-secondary font-monospace style-tiny">Menampilkan <?= count($materials) ?> Modul</div>
        </div>

        <div class="row g-4">
            <?php if (empty($materials)): ?>
                <div class="col-12 text-center py-5 text-secondary">
                    <i class="fa-solid fa-book-bookmark fs-1 mb-2 d-block opacity-25"></i>
                    Belum ada materi pembelajaran yang dipublikasikan.
                </div>
            <?php endif; ?>

            <?php foreach ($materials as $m): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="saas-card saas-card-glow h-100 d-flex flex-column justify-content-between overflow-hidden">
                        <div>
                            <div class="position-relative bg-dark overflow-hidden" style="height: 190px;">
                                <?php if (!empty($m['thumbnail'])): ?>
                                    <img src="<?= (strpos($m['thumbnail'], 'http') === 0) ? esc($m['thumbnail']) : base_url($m['thumbnail']) ?>" alt="<?= esc($m['title']) ?>" class="w-100 h-100 object-fit-cover">
                                <?php else: ?>
                                    <div class="w-100 h-100 bg-black d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #0f172a, #1e1b4b);">
                                        <i class="fa-solid fa-graduation-cap text-danger display-4"></i>
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
                                    <span class="badge bg-danger font-monospace"><?= esc($m['division_name'] ?: 'Umum') ?></span>
                                    <span class="badge bg-secondary font-monospace"><?= esc($m['category']) ?></span>
                                </div>
                                <h5 class="text-white font-heading mb-2">
                                    <a href="<?= base_url('materi/' . $m['slug']) ?>" class="text-white text-decoration-none hover-danger"><?= esc($m['title']) ?></a>
                                </h5>
                                <p class="text-secondary small mb-3"><?= esc(mb_strimwidth($m['excerpt'] ?? '', 0, 110, '...')) ?></p>
                            </div>
                        </div>

                        <div class="p-4 pt-0 border-top border-secondary border-opacity-10 mt-auto">
                            <div class="d-flex align-items-center justify-content-between pt-3 text-secondary style-tiny font-monospace mb-3">
                                <div><i class="fa-solid fa-user me-1 text-danger"></i> <?= esc($m['author_name'] ?: 'Admin') ?></div>
                                <div><i class="fa-solid fa-clock me-1 text-warning"></i> <?= $m['reading_time'] ?> min</div>
                                <div><i class="fa-solid fa-eye me-1 text-info"></i> <?= number_format($m['views_count']) ?></div>
                            </div>
                            <a href="<?= base_url('materi/' . $m['slug']) ?>" class="btn btn-sm btn-red w-100">
                                <i class="fa-solid fa-book-reader me-1"></i> Baca Materi Lengkap
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
