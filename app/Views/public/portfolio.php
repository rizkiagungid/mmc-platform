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

                    $mediaGallery = !empty($p['media_gallery']) ? json_decode($p['media_gallery'], true) : [];
                    $mediaFile = $p['media_file'] ?? null;
                    $isVideo = false;
                    if ($mediaFile) {
                        $ext = strtolower(pathinfo($mediaFile, PATHINFO_EXTENSION));
                        if (in_array($ext, ['mp4', 'webm', 'mov', 'mkv', 'avi'])) {
                            $isVideo = true;
                        }
                    }
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="saas-card saas-card-glow overflow-hidden h-100 d-flex flex-column justify-content-between">
                        <div>
                            <!-- Media Preview (Direct Video, YouTube Embed, Image File, or Thumbnail) -->
                            <?php if ($isVideo): ?>
                                <div class="ratio ratio-16x9 bg-black">
                                    <video controls preload="metadata" class="w-100 h-100 object-fit-cover" src="<?= base_url($mediaFile) ?>"></video>
                                </div>
                            <?php elseif ($embedUrl): ?>
                                <div class="ratio ratio-16x9">
                                    <iframe src="<?= esc($embedUrl) ?>" title="<?= esc($p['title']) ?>" allowfullscreen class="w-100 border-0"></iframe>
                                </div>
                            <?php elseif (!empty($mediaFile)): ?>
                                <div class="position-relative bg-dark overflow-hidden" style="height: 200px;">
                                    <img src="<?= base_url($mediaFile) ?>" alt="<?= esc($p['title']) ?>" class="w-100 h-100 object-fit-cover">
                                </div>
                            <?php elseif (!empty($p['thumbnail'])): ?>
                                <div class="position-relative bg-dark overflow-hidden" style="height: 200px;">
                                    <img src="<?= (strpos($p['thumbnail'], 'http') === 0) ? esc($p['thumbnail']) : base_url($p['thumbnail']) ?>" alt="<?= esc($p['title']) ?>" class="w-100 h-100 object-fit-cover">
                                </div>
                            <?php else: ?>
                                <div class="position-relative bg-dark d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(135deg, #1e1b4b, #31102f);">
                                    <i class="fa-solid <?= ($p['category'] === 'Programming') ? 'fa-code' : (($p['category'] === 'Broadcasting') ? 'fa-tower-cell' : 'fa-film') ?> text-danger display-4"></i>
                                </div>
                            <?php endif; ?>

                            <div class="p-4">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-danger font-monospace"><?= esc($p['category']) ?></span>
                                    <span class="badge bg-secondary font-monospace"><?= esc($p['year']) ?></span>
                                </div>
                                <h5 class="text-white font-heading mb-2"><?= esc($p['title']) ?></h5>
                                <p class="text-secondary small mb-3"><?= esc(mb_strimwidth($p['description'] ?? '', 0, 110, '...')) ?></p>
                            </div>
                        </div>

                        <div class="p-4 pt-0 border-top border-secondary border-opacity-10 mt-auto">
                            <div class="d-flex align-items-center justify-content-between pt-3 text-secondary style-tiny font-monospace mb-3">
                                <div>
                                    <i class="fa-solid fa-users me-1 text-danger"></i>
                                    <?php if (!empty($p['contributors'])): ?>
                                        <?= esc(implode(', ', array_column($p['contributors'], 'full_name'))) ?>
                                    <?php else: ?>
                                        Tim MMC
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-red flex-fill" data-bs-toggle="modal" data-bs-target="#portfolioModal<?= $p['id'] ?>">
                                    <i class="fa-solid fa-circle-info me-1"></i> Detail Karya
                                </button>
                                <?php if (!empty($p['external_url'])): ?>
                                    <a href="<?= esc($p['external_url']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-light px-3" title="Buka Link External">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Detail Portofolio Karya (Popup) -->
                <div class="modal fade" id="portfolioModal<?= $p['id'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
                            <div class="modal-header border-bottom border-secondary border-opacity-25">
                                <div>
                                    <h5 class="modal-title font-heading text-white fw-bold mb-1"><?= esc($p['title']) ?></h5>
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="badge bg-danger font-monospace"><?= esc($p['category']) ?></span>
                                        <span class="badge bg-secondary font-monospace">Tahun: <?= esc($p['year']) ?></span>
                                    </div>
                                </div>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <!-- Media & Gallery Carousel -->
                                <?php if (!empty($mediaGallery)): ?>
                                    <div id="carouselGallery<?= $p['id'] ?>" class="carousel slide mb-4 rounded-3 overflow-hidden border border-secondary border-opacity-25" data-bs-ride="carousel">
                                        <div class="carousel-inner bg-black">
                                            <?php foreach ($mediaGallery as $idx => $gItem): ?>
                                                <?php
                                                    $gExt = strtolower(pathinfo($gItem, PATHINFO_EXTENSION));
                                                    $isGVideo = in_array($gExt, ['mp4', 'webm', 'mov', 'mkv', 'avi']);
                                                ?>
                                                <div class="carousel-item <?= $idx === 0 ? 'active' : '' ?>">
                                                    <?php if ($isGVideo): ?>
                                                        <div class="ratio ratio-16x9">
                                                            <video controls preload="metadata" class="w-100 h-100 object-fit-cover" src="<?= base_url($gItem) ?>"></video>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="position-relative text-center bg-black" style="max-height: 420px;">
                                                            <img src="<?= base_url($gItem) ?>" alt="Galeri <?= $idx + 1 ?>" class="img-fluid style-gallery-img" style="max-height: 420px; object-fit: contain;">
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php if (count($mediaGallery) > 1): ?>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselGallery<?= $p['id'] ?>" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon"></span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#carouselGallery<?= $p['id'] ?>" data-bs-slide="next">
                                                <span class="carousel-control-next-icon"></span>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                <?php elseif ($isVideo): ?>
                                    <div class="ratio ratio-16x9 mb-4 rounded-3 overflow-hidden border border-secondary border-opacity-25 bg-black">
                                        <video controls preload="metadata" class="w-100 h-100 object-fit-cover" src="<?= base_url($mediaFile) ?>"></video>
                                    </div>
                                <?php elseif ($embedUrl): ?>
                                    <div class="ratio ratio-16x9 mb-4 rounded-3 overflow-hidden border border-secondary border-opacity-25">
                                        <iframe src="<?= esc($embedUrl) ?>" title="<?= esc($p['title']) ?>" allowfullscreen class="w-100 border-0"></iframe>
                                    </div>
                                <?php elseif (!empty($mediaFile)): ?>
                                    <div class="mb-4 rounded-3 overflow-hidden border border-secondary border-opacity-25 text-center bg-black">
                                        <img src="<?= base_url($mediaFile) ?>" alt="<?= esc($p['title']) ?>" class="img-fluid" style="max-height: 380px;">
                                    </div>
                                <?php elseif (!empty($p['thumbnail'])): ?>
                                    <div class="mb-4 rounded-3 overflow-hidden border border-secondary border-opacity-25 text-center bg-black">
                                        <img src="<?= (strpos($p['thumbnail'], 'http') === 0) ? esc($p['thumbnail']) : base_url($p['thumbnail']) ?>" alt="<?= esc($p['title']) ?>" class="img-fluid" style="max-height: 380px;">
                                    </div>
                                <?php endif; ?>

                                <!-- Deskripsi Karya -->
                                <div class="mb-4">
                                    <h6 class="text-white font-heading fw-semibold mb-2"><i class="fa-solid fa-align-left text-danger me-2"></i> Deskripsi & Spesifikasi Projek</h6>
                                    <p class="text-secondary small leading-relaxed m-0"><?= nl2br(esc($p['description'] ?: 'Tidak ada deskripsi rinci.')) ?></p>
                                </div>

                                <!-- Kontributor -->
                                <div class="mb-4">
                                    <h6 class="text-white font-heading fw-semibold mb-2"><i class="fa-solid fa-users text-warning me-2"></i> Tim Kontributor Karya</h6>
                                    <?php if (!empty($p['contributors'])): ?>
                                        <div class="d-flex flex-wrap gap-2">
                                            <?php foreach ($p['contributors'] as $c): ?>
                                                <span class="badge bg-dark border border-secondary p-2 text-white font-monospace">
                                                    <i class="fa-solid fa-user text-danger me-1"></i> <?= esc($c['full_name']) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-secondary small fst-italic">Dikembangkan oleh Tim Multimedia Club SMAN 1 Tamansari</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="modal-footer border-top border-secondary border-opacity-25 justify-content-between">
                                <?php if (!empty($p['external_url'])): ?>
                                    <a href="<?= esc($p['external_url']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-red px-4">
                                        <i class="fa-solid fa-arrow-up-right-from-square me-2"></i> Kunjungi Link / Repository / Website
                                    </a>
                                <?php else: ?>
                                    <div></div>
                                <?php endif; ?>
                                <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
