<?= $this->extend('layouts/master_public') ?>

<?= $this->section('content') ?>
<section class="py-5">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="text-danger font-monospace text-uppercase fw-bold" style="letter-spacing: 0.1em;">SHOWCASE KARYA ANGGOTA</span>
            <h1 class="display-5 fw-bold text-white font-heading mt-2">Portofolio Karya Multimedia</h1>
            <p class="text-secondary col-lg-8 mx-auto">Koleksi sinematografi, video streaming, desain grafis, dan platform web buatan anggota SMAN 1 Tamansari.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php foreach ($portfolios as $p): ?>
                <?php
                    // Helper to convert YouTube URL to embed URL
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
                            <!-- Thumbnail / Embedded Video Player -->
                            <?php if ($embedUrl): ?>
                                <div class="ratio ratio-16x9">
                                    <iframe src="<?= esc($embedUrl) ?>" title="<?= esc($p['title']) ?>" allowfullscreen class="w-100 border-0"></iframe>
                                </div>
                            <?php elseif ($p['thumbnail']): ?>
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
                                <p class="text-secondary small mb-3"><?= esc($p['description']) ?></p>
                            </div>
                        </div>

                        <div class="p-4 pt-0 border-top border-secondary border-opacity-10 mt-auto">
                            <div class="d-flex align-items-center justify-content-between pt-3 text-secondary style-tiny font-monospace">
                                <div>
                                    <i class="fa-solid fa-users me-1 text-danger"></i>
                                    <?php if (!empty($p['contributors'])): ?>
                                        <?= esc(implode(', ', array_column($p['contributors'], 'full_name'))) ?>
                                    <?php else: ?>
                                        Tim MMC
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
<?= $this->endSection() ?>
