<?= $this->extend('layouts/master_public') ?>

<?= $this->section('content') ?>

<!-- Dynamic Homepage Section Builder Render Loop -->
<?php foreach ($sections as $sec): ?>
    <?php if ($sec['section_key'] === 'hero'): ?>
        <!-- Hero Section -->
        <section class="<?= esc($sec['padding_top']) ?> <?= esc($sec['padding_bottom']) ?> text-center position-relative overflow-hidden" style="background: radial-gradient(circle at 50% 20%, rgba(220, 38, 38, 0.15) 0%, rgba(9, 9, 11, 1) 70%);">
            <div class="<?= esc($sec['container_type']) ?>">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill font-monospace mb-3">
                            <i class="fa-solid fa-sparkles me-1"></i> <?= esc($hero['subtitle'] ?? 'Official Hub SMAN 1 Tamansari') ?>
                        </span>
                        
                        <h1 class="display-3 fw-bold text-white font-heading mb-4">
                            <?= esc($hero['title'] ?? 'Inovasi Visual & Kreativitas Digital Tanpa Batas') ?>
                        </h1>
                        
                        <p class="lead text-secondary mb-5 px-lg-5">
                            <?= esc($hero['description'] ?? 'Platform terpadu Ekstrakurikuler Multimedia Club SMAN 1 Tamansari. Wadah bagi para kreator muda di bidang videografi, fotografi, desain grafis, broadcasting, dan web development.') ?>
                        </p>

                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <a href="<?= base_url($hero['primary_btn_url'] ?? '/register') ?>" class="btn btn-red btn-lg px-5 py-3 fs-6">
                                <i class="fa-solid fa-user-plus me-2"></i> <?= esc($hero['primary_btn_text'] ?? 'Bergabung Sekarang') ?>
                            </a>
                            <a href="<?= base_url($hero['secondary_btn_url'] ?? '/portfolio') ?>" class="btn btn-saas-dark btn-lg px-5 py-3 fs-6">
                                <i class="fa-solid fa-photo-film me-2"></i> <?= esc($hero['secondary_btn_text'] ?? 'Lihat Portofolio Karya') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    <?php elseif ($sec['section_key'] === 'stats'): ?>
        <!-- Dynamic Stats Counter Section -->
        <section class="py-4 border-y border-secondary border-opacity-25" style="background: #0d0d12;">
            <div class="<?= esc($sec['container_type']) ?>">
                <div class="row g-4 justify-content-center">
                    <?php if (!empty($stats)): ?>
                        <?php foreach ($stats as $st): ?>
                            <div class="col-6 col-md-3">
                                <div class="saas-card p-4 text-center h-100">
                                    <i class="fa-solid <?= esc($st['icon']) ?> text-danger fs-3 mb-2"></i>
                                    <h2 class="display-5 fw-bold text-white font-heading mb-1">
                                        <?= esc(($st['prefix'] ?? '') . $st['value'] . ($st['suffix'] ?? '')) ?>
                                    </h2>
                                    <span class="text-secondary small fw-semibold"><?= esc($st['label']) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-6 col-md-3">
                            <div class="saas-card p-4 text-center">
                                <h2 class="display-5 fw-bold text-white font-heading mb-1"><?= $totalMembers ?>+</h2>
                                <span class="text-secondary small">Anggota Aktif</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

    <?php elseif ($sec['section_key'] === 'divisions'): ?>
        <!-- Dynamic Divisions Showcase -->
        <section class="<?= esc($sec['padding_top']) ?> <?= esc($sec['padding_bottom']) ?>">
            <div class="<?= esc($sec['container_type']) ?>">
                <div class="text-center mb-5">
                    <span class="text-danger font-monospace text-uppercase fw-bold" style="letter-spacing: 0.1em;">DIVISI EKSTRAKURIKULER</span>
                    <h2 class="display-6 fw-bold text-white font-heading mt-2">Spesialisasi Keterampilan Kreatif</h2>
                </div>

                <div class="row g-4 justify-content-center">
                    <?php foreach ($divisions as $d): ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="saas-card saas-card-glow p-4 h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="rounded-3 bg-danger bg-opacity-10 p-3 text-danger mb-3" style="width: fit-content;">
                                        <i class="fa-solid <?= esc($d['icon']) ?> fs-3"></i>
                                    </div>
                                    <h5 class="text-white font-heading mb-2"><?= esc($d['name']) ?></h5>
                                    <p class="text-secondary small mb-3"><?= esc($d['short_description']) ?></p>
                                </div>
                                <a href="<?= base_url('learning-path#' . $d['slug']) ?>" class="text-danger small font-monospace fw-bold text-decoration-none">
                                    Pelajari Silabus <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

    <?php elseif ($sec['section_key'] === 'portfolio'): ?>
        <!-- Dynamic Featured Portfolios Showcase with Video Player -->
        <section class="<?= esc($sec['padding_top']) ?> <?= esc($sec['padding_bottom']) ?>" style="background: #0b0b0f;">
            <div class="<?= esc($sec['container_type']) ?>">
                <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between mb-5">
                    <div>
                        <span class="text-danger font-monospace text-uppercase fw-bold" style="letter-spacing: 0.1em;">SHOWCASE KARYA ANGGOTA</span>
                        <h2 class="display-6 fw-bold text-white font-heading mt-2 mb-0">Portofolio Hasil Karya Terpilih</h2>
                    </div>
                    <a href="<?= base_url('portfolio') ?>" class="btn btn-saas-dark mt-3 mt-md-0">
                        Lihat Semua Portofolio <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="row g-4 justify-content-center">
                    <?php foreach (array_slice($portfolios, 0, 3) as $p): ?>
                        <?php
                            // Helper to convert YouTube URL to embed URL for Homepage
                            $embedUrl = null;
                            if (!empty($p['external_url'])) {
                                if (strpos($p['external_url'], 'youtube.com/watch') !== false) {
                                    parse_str(parse_url($p['external_url'], PHP_URL_QUERY), $queryVars);
                                    if (isset($queryVars['v'])) {
                                        $embedUrl = 'https://www.youtube.com/embed/' . $queryVars['v'];
                                    }
                                } elseif (strpos($p['external_url'], 'youtu.be/') !== false) {
                                    $path = parse_url($p['external_url'], PHP_URL_PATH);
                                    $embedUrl = 'https://www.youtube.com/embed/' . ltrim($path, '/');
                                } elseif (strpos($p['external_url'], 'youtube.com/embed/') !== false) {
                                    $embedUrl = $p['external_url'];
                                }
                            }
                        ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="saas-card saas-card-glow overflow-hidden h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <!-- Embedded YouTube Video Player or Thumbnail -->
                                    <?php if ($embedUrl): ?>
                                        <div class="ratio ratio-16x9">
                                            <iframe src="<?= esc($embedUrl) ?>" title="<?= esc($p['title']) ?>" allowfullscreen class="w-100 border-0"></iframe>
                                        </div>
                                    <?php elseif (!empty($p['thumbnail'])): ?>
                                        <div class="position-relative bg-dark overflow-hidden" style="height: 200px;">
                                            <img src="<?= esc($p['thumbnail']) ?>" alt="<?= esc($p['title']) ?>" class="w-100 h-100 object-fit-cover">
                                        </div>
                                    <?php else: ?>
                                        <div class="position-relative bg-dark d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(135deg, #1e1b4b, #31102f);">
                                            <i class="fa-solid fa-play text-danger display-4"></i>
                                        </div>
                                    <?php endif; ?>

                                    <div class="p-4">
                                        <span class="badge bg-danger mb-2 font-monospace"><?= esc($p['category']) ?></span>
                                        <h5 class="text-white font-heading mb-2"><?= esc($p['title']) ?></h5>
                                        <p class="text-secondary small mb-3"><?= esc(mb_strimwidth($p['description'] ?? '', 0, 100, '...')) ?></p>
                                    </div>
                                </div>

                                <div class="p-4 pt-0 border-top border-secondary border-opacity-10 mt-auto">
                                    <div class="d-flex align-items-center justify-content-between pt-3 text-secondary style-tiny font-monospace">
                                        <div>
                                            <i class="fa-solid fa-users me-1 text-danger"></i>
                                            <?php if (!empty($p['contributors'])): ?>
                                                <?= esc(implode(', ', array_column($p['contributors'], 'full_name'))) ?>
                                            <?php else: ?>
                                                Anggota Multimedia Club
                                            <?php endif; ?>
                                        </div>
                                        <span class="badge bg-secondary font-monospace"><?= esc($p['year']) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

    <?php elseif ($sec['section_key'] === 'achievements'): ?>
        <!-- Dynamic Achievements & Winning Teams Section -->
        <?php $renderedAchievementsSection = true; ?>
        <section class="<?= esc($sec['padding_top'] ?? 'py-5') ?> <?= esc($sec['padding_bottom'] ?? 'py-5') ?>" style="background: #09090c;">
            <div class="<?= esc($sec['container_type'] ?? 'container') ?>">
                <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between mb-5">
                    <div>
                        <span class="text-danger font-monospace text-uppercase fw-bold" style="letter-spacing: 0.1em;">REKOR KEJUARAAN</span>
                        <h2 class="display-6 fw-bold text-white font-heading mt-2 mb-0">Prestasi & Tim Juara Terkini</h2>
                    </div>
                    <a href="<?= base_url('achievements') ?>" class="btn btn-saas-dark mt-3 mt-md-0">
                        Lihat Semua Prestasi <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>

                <?php if (!empty($achievements)): ?>
                    <div class="row g-4 justify-content-center">
                        <?php foreach (array_slice($achievements, 0, 3) as $ach): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="saas-card saas-card-glow h-100 d-flex flex-column justify-content-between p-4 border border-secondary border-opacity-25">
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                            <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25 px-2 py-1 font-monospace">
                                                <i class="fa-solid fa-trophy me-1"></i> <?= esc($ach['award']) ?>
                                            </span>
                                            <span class="badge bg-secondary font-monospace style-tiny"><?= esc($ach['category']) ?></span>
                                        </div>

                                        <h5 class="text-white font-heading fw-bold mb-2"><?= esc($ach['title']) ?></h5>
                                        <div class="text-danger small font-monospace fw-semibold mb-2">
                                            <i class="fa-solid fa-award me-1"></i> <?= esc($ach['competition']) ?>
                                        </div>
                                        <?php if (!empty($ach['description'])): ?>
                                            <p class="text-secondary small mb-3"><?= esc(mb_strimwidth($ach['description'], 0, 110, '...')) ?></p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="pt-3 border-top border-secondary border-opacity-10 mt-auto">
                                        <div class="mb-2">
                                            <span class="text-secondary style-tiny uppercase font-monospace fw-bold d-block mb-1">
                                                <i class="fa-solid fa-users text-danger me-1"></i> Tim Juara:
                                            </span>
                                            <?php if (!empty($ach['team_members'])): ?>
                                                <div class="d-flex flex-wrap gap-1">
                                                    <?php foreach ($ach['team_members'] as $tm): ?>
                                                        <span class="badge bg-dark text-light border border-secondary border-opacity-50 font-monospace style-tiny">
                                                            <?= esc($tm['full_name']) ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-secondary style-tiny fst-italic">Kategori Perorangan</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-secondary style-tiny font-monospace text-end pt-1">
                                            <i class="fa-regular fa-calendar-check me-1"></i> <?= date('d M Y', strtotime($ach['event_date'])) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4 saas-card p-4">
                        <p class="text-secondary mb-0">Belum ada data prestasi yang ditampilkan.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

    <?php elseif ($sec['section_key'] === 'faq'): ?>
        <!-- Dynamic FAQ Accordion -->
        <section class="<?= esc($sec['padding_top']) ?> <?= esc($sec['padding_bottom']) ?>">
            <div class="<?= esc($sec['container_type']) ?>">
                <div class="text-center mb-5">
                    <span class="text-danger font-monospace text-uppercase fw-bold" style="letter-spacing: 0.1em;">PERTANYAAN UMUM</span>
                    <h2 class="display-6 fw-bold text-white font-heading mt-2">Frequently Asked Questions</h2>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-9">
                        <div class="accordion accordion-flush" id="faqAccordionHome">
                            <?php foreach ($faqs as $i => $fq): ?>
                                <div class="accordion-item bg-dark text-white border border-secondary border-opacity-25 mb-3 rounded-3 overflow-hidden">
                                    <h2 class="accordion-header" id="headingHome<?= $i ?>">
                                        <button class="accordion-button collapsed bg-dark text-white font-heading shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHome<?= $i ?>">
                                            <i class="fa-solid fa-circle-question text-danger me-2"></i> <?= esc($fq['question']) ?>
                                        </button>
                                    </h2>
                                    <div id="collapseHome<?= $i ?>" class="accordion-collapse collapse" data-bs-parent="#faqAccordionHome">
                                        <div class="accordion-body text-secondary leading-relaxed small border-top border-secondary border-opacity-25">
                                            <?= esc($fq['answer']) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
<?php endforeach; ?>

<?php if (empty($renderedAchievementsSection)): ?>
    <!-- Fallback Achievements & Winning Teams Section if section key not in DB homepage_sections -->
    <section class="py-5" style="background: #09090c;">
        <div class="container">
            <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between mb-5">
                <div>
                    <span class="text-danger font-monospace text-uppercase fw-bold" style="letter-spacing: 0.1em;">REKOR KEJUARAAN</span>
                    <h2 class="display-6 fw-bold text-white font-heading mt-2 mb-0">Prestasi & Tim Juara Terkini</h2>
                </div>
                <a href="<?= base_url('achievements') ?>" class="btn btn-saas-dark mt-3 mt-md-0">
                    Lihat Semua Prestasi <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>

            <?php if (!empty($achievements)): ?>
                <div class="row g-4 justify-content-center">
                    <?php foreach (array_slice($achievements, 0, 3) as $ach): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="saas-card saas-card-glow h-100 d-flex flex-column justify-content-between p-4 border border-secondary border-opacity-25">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                        <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25 px-2 py-1 font-monospace">
                                            <i class="fa-solid fa-trophy me-1"></i> <?= esc($ach['award']) ?>
                                        </span>
                                        <span class="badge bg-secondary font-monospace style-tiny"><?= esc($ach['category']) ?></span>
                                    </div>

                                    <h5 class="text-white font-heading fw-bold mb-2"><?= esc($ach['title']) ?></h5>
                                    <div class="text-danger small font-monospace fw-semibold mb-2">
                                        <i class="fa-solid fa-award me-1"></i> <?= esc($ach['competition']) ?>
                                    </div>
                                    <?php if (!empty($ach['description'])): ?>
                                        <p class="text-secondary small mb-3"><?= esc(mb_strimwidth($ach['description'], 0, 110, '...')) ?></p>
                                    <?php endif; ?>
                                </div>

                                <div class="pt-3 border-top border-secondary border-opacity-10 mt-auto">
                                    <div class="mb-2">
                                        <span class="text-secondary style-tiny uppercase font-monospace fw-bold d-block mb-1">
                                            <i class="fa-solid fa-users text-danger me-1"></i> Tim Juara:
                                        </span>
                                        <?php if (!empty($ach['team_members'])): ?>
                                            <div class="d-flex flex-wrap gap-1">
                                                <?php foreach ($ach['team_members'] as $tm): ?>
                                                    <span class="badge bg-dark text-light border border-secondary border-opacity-50 font-monospace style-tiny">
                                                        <?= esc($tm['full_name']) ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-secondary style-tiny fst-italic">Kategori Perorangan</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-secondary style-tiny font-monospace text-end pt-1">
                                        <i class="fa-regular fa-calendar-check me-1"></i> <?= date('d M Y', strtotime($ach['event_date'])) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-4 saas-card p-4">
                    <p class="text-secondary mb-0">Belum ada data prestasi yang ditampilkan.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>

<?= $this->endSection() ?>
