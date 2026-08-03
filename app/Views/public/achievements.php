<?= $this->extend('layouts/master_public') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="py-5 text-center position-relative overflow-hidden" style="background: radial-gradient(circle at 50% 20%, rgba(220, 38, 38, 0.18) 0%, rgba(9, 9, 11, 1) 75%);">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill font-monospace mb-3">
                    <i class="fa-solid fa-trophy text-warning me-1"></i> HALL OF FAME & TIM JUARA
                </span>
                
                <h1 class="display-4 fw-bold text-white font-heading mb-3">
                    Prestasi & Rekor Kejuaraan
                </h1>
                
                <p class="lead text-secondary mb-4 px-lg-4">
                    Catatan rekor kemenangan, penghargaan kejuaraan, dan apresiasi karya yang diraih oleh siswa-siswi Multimedia Club SMAN 1 Tamansari di berbagai kompetisi lokal, tingkat provinsi, hingga tingkat nasional.
                </p>
            </div>
        </div>

        <!-- Summary Stats Counter -->
        <div class="row g-3 justify-content-center mt-3">
            <div class="col-6 col-md-3">
                <div class="saas-card p-3 text-center border border-secondary border-opacity-25">
                    <div class="text-danger fs-4 mb-1"><i class="fa-solid fa-award"></i></div>
                    <h3 class="fw-bold text-white font-heading m-0"><?= $totalCount ?></h3>
                    <span class="text-secondary style-tiny uppercase font-monospace">Total Penghargaan</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="saas-card p-3 text-center border border-secondary border-opacity-25">
                    <div class="text-warning fs-4 mb-1"><i class="fa-solid fa-medal"></i></div>
                    <h3 class="fw-bold text-white font-heading m-0"><?= $totalGold ?></h3>
                    <span class="text-secondary style-tiny uppercase font-monospace">Juara 1 / Medali Emas</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="saas-card p-3 text-center border border-secondary border-opacity-25">
                    <div class="text-info fs-4 mb-1"><i class="fa-solid fa-flag-checkered"></i></div>
                    <h3 class="fw-bold text-white font-heading m-0"><?= $totalNational ?></h3>
                    <span class="text-secondary style-tiny uppercase font-monospace">Tingkat Nasional</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filter & Achievement Grid Section -->
<section class="py-5" style="background: #09090b;">
    <div class="container">
        <!-- Category Filter Tabs -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-5 pb-3 border-bottom border-secondary border-opacity-25">
            <div class="d-flex flex-wrap gap-2" id="categoryFilters">
                <button type="button" class="btn btn-sm btn-red filter-btn active" data-category="all">
                    <i class="fa-solid fa-layer-group me-1"></i> Semua Prestasi (<?= count($achievements) ?>)
                </button>
                <button type="button" class="btn btn-sm btn-saas-dark filter-btn" data-category="Tingkat Nasional">
                    <i class="fa-solid fa-star text-warning me-1"></i> Tingkat Nasional
                </button>
                <button type="button" class="btn btn-sm btn-saas-dark filter-btn" data-category="Tingkat Provinsi">
                    <i class="fa-solid fa-building-columns text-info me-1"></i> Tingkat Provinsi
                </button>
                <button type="button" class="btn btn-sm btn-saas-dark filter-btn" data-category="Tingkat Kabupaten/Kota">
                    <i class="fa-solid fa-location-dot text-danger me-1"></i> Tingkat Kota/Kab
                </button>
            </div>

            <div class="input-group input-group-sm" style="max-width: 280px;">
                <span class="input-group-text bg-dark text-secondary border-secondary border-opacity-25"><i class="fa-solid fa-search"></i></span>
                <input type="text" id="searchAchievementInput" class="form-control bg-dark text-white border-secondary border-opacity-25 shadow-none" placeholder="Cari karya / lomba...">
            </div>
        </div>

        <!-- Achievement Cards Grid -->
        <?php if (!empty($achievements)): ?>
            <div class="row g-4" id="achievementsGrid">
                <?php foreach ($achievements as $ach): ?>
                    <div class="col-md-6 col-lg-4 achievement-item" data-category="<?= esc($ach['category']) ?>" data-title="<?= esc(strtolower($ach['title'] . ' ' . $ach['competition'] . ' ' . $ach['award'])) ?>">
                        <div class="saas-card saas-card-glow h-100 d-flex flex-column justify-content-between p-4 border border-secondary border-opacity-25 position-relative">
                            <div>
                                <!-- Top Badges Header -->
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                    <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25 px-2 py-1 font-monospace">
                                        <i class="fa-solid fa-trophy me-1"></i> <?= esc($ach['award']) ?>
                                    </span>
                                    <span class="badge bg-secondary font-monospace style-tiny">
                                        <?= esc($ach['category']) ?>
                                    </span>
                                </div>

                                <!-- Title & Competition -->
                                <h5 class="text-white font-heading fw-bold mb-2">
                                    <?= esc($ach['title']) ?>
                                </h5>
                                
                                <div class="text-danger small font-monospace fw-semibold mb-2">
                                    <i class="fa-solid fa-award me-1"></i> <?= esc($ach['competition']) ?>
                                </div>

                                <?php if (!empty($ach['organizer'])): ?>
                                    <p class="text-secondary style-tiny mb-3">
                                        <i class="fa-solid fa-landmark me-1"></i> Penyelenggara: <strong class="text-light"><?= esc($ach['organizer']) ?></strong>
                                    </p>
                                <?php endif; ?>

                                <?php if (!empty($ach['description'])): ?>
                                    <p class="text-secondary small mb-3">
                                        <?= esc(mb_strimwidth($ach['description'], 0, 140, '...')) ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <div class="pt-3 border-top border-secondary border-opacity-10 mt-auto">
                                <!-- Team Members Section -->
                                <div class="mb-3">
                                    <span class="text-secondary style-tiny uppercase font-monospace fw-bold d-block mb-2">
                                        <i class="fa-solid fa-users text-danger me-1"></i> Tim Juara / Anggota:
                                    </span>

                                    <?php if (!empty($ach['team_members'])): ?>
                                        <div class="d-flex flex-wrap gap-1">
                                            <?php foreach ($ach['team_members'] as $tm): ?>
                                                <span class="badge bg-dark text-light border border-secondary border-opacity-50 py-1 px-2 font-monospace style-tiny d-inline-flex align-items-center gap-1">
                                                    <span class="rounded-circle bg-danger text-white d-inline-flex align-items-center justify-content-center" style="width: 16px; height: 16px; font-size: 9px;">
                                                        <?= strtoupper(substr($tm['full_name'], 0, 1)) ?>
                                                    </span>
                                                    <?= esc($tm['full_name']) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-secondary style-tiny fst-italic">Kategori Perorangan / Individu</span>
                                    <?php endif; ?>
                                </div>

                                <!-- Date & Certificate Footer -->
                                <div class="d-flex align-items-center justify-content-between style-tiny font-monospace text-secondary pt-2 border-top border-secondary border-opacity-10">
                                    <span>
                                        <i class="fa-regular fa-calendar-check me-1"></i> <?= date('d M Y', strtotime($ach['event_date'])) ?>
                                    </span>

                                    <?php if (!empty($ach['certificate_image'])): ?>
                                        <button type="button" class="btn btn-link text-danger p-0 style-tiny text-decoration-none" data-bs-toggle="modal" data-bs-target="#certModal<?= $ach['id'] ?>">
                                            <i class="fa-solid fa-certificate me-1"></i> Lihat Sertifikat
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate Modal if Available -->
                    <?php if (!empty($ach['certificate_image'])): ?>
                        <div class="modal fade" id="certModal<?= $ach['id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
                                    <div class="modal-header border-bottom border-secondary border-opacity-25">
                                        <h5 class="modal-title font-heading"><i class="fa-solid fa-certificate text-warning me-2"></i> Sertifikat Kemenangan</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center p-3">
                                        <img src="<?= esc($ach['certificate_image']) ?>" alt="Sertifikat <?= esc($ach['title']) ?>" class="img-fluid rounded border border-secondary">
                                        <div class="mt-3 text-secondary small">
                                            <strong><?= esc($ach['title']) ?></strong> — <?= esc($ach['award']) ?> (<?= esc($ach['competition']) ?>)
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5 saas-card p-5 border border-secondary border-opacity-25">
                <i class="fa-solid fa-trophy display-3 text-secondary mb-3 opacity-50"></i>
                <h4 class="text-white font-heading">Belum Ada Rekor Prestasi</h4>
                <p class="text-secondary small mb-0">Data kejuaraan dan tim juara akan ditampilkan di sini setelah ditambahkan melalui panel CMS.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Category Filter
        $('.filter-btn').on('click', function() {
            $('.filter-btn').removeClass('btn-red active').addClass('btn-saas-dark');
            $(this).removeClass('btn-saas-dark').addClass('btn-red active');

            const category = $(this).data('category');
            filterGrid(category, $('#searchAchievementInput').val().toLowerCase());
        });

        // Search Filter
        $('#searchAchievementInput').on('keyup', function() {
            const query = $(this).val().toLowerCase();
            const activeCategory = $('.filter-btn.active').data('category');
            filterGrid(activeCategory, query);
        });

        function filterGrid(category, query) {
            $('.achievement-item').each(function() {
                const itemCategory = $(this).data('category');
                const itemTitle = $(this).data('title');

                const matchesCategory = (category === 'all' || itemCategory === category);
                const matchesSearch = (!query || itemTitle.includes(query));

                if (matchesCategory && matchesSearch) {
                    $(this).fadeIn(200);
                } else {
                    $(this).fadeOut(150);
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>
