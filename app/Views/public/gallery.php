<?= $this->extend('layouts/master_public') ?>

<?= $this->section('content') ?>

<style>
/* Modern Gallery Aesthetics & Mobile Responsiveness */
.gallery-hero-badge {
    background: linear-gradient(135deg, rgba(220, 38, 38, 0.15), rgba(239, 68, 68, 0.05));
    border: 1px solid rgba(220, 38, 38, 0.3);
    color: #ef4444;
    letter-spacing: 0.12em;
    font-size: 0.75rem;
}

.gallery-card {
    background: #14141d;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    overflow: hidden;
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.25s ease, box-shadow 0.25s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.gallery-card:hover {
    transform: translateY(-4px);
    border-color: rgba(239, 68, 68, 0.4);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.45);
}

.gallery-media-wrapper {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 10;
    overflow: hidden;
    background: #0d0d12;
}

.gallery-media-wrapper img, 
.gallery-media-wrapper video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.gallery-card:hover .gallery-media-wrapper img {
    transform: scale(1.04);
}

.gallery-count-badge {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background: rgba(10, 10, 15, 0.85);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 20px;
    padding: 4px 10px;
    font-size: 0.72rem;
    font-family: monospace;
    color: #fff;
    z-index: 2;
}

.gallery-date-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(10, 10, 15, 0.85);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 3px 8px;
    font-size: 0.72rem;
    color: #cbd5e1;
    z-index: 2;
}

.gallery-category-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    border-radius: 8px;
    padding: 3px 8px;
    font-size: 0.72rem;
    z-index: 2;
}

.gallery-card-body {
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.gallery-desc-clamp {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    color: #94a3b8;
    font-size: 0.86rem;
    line-height: 1.5;
}

.gallery-modal-carousel-img {
    max-height: 70vh;
    width: 100%;
    object-fit: contain;
    background: #09090d;
}

.gallery-modal-video {
    max-height: 70vh;
    width: 100%;
    background: #000;
}

.gallery-thumb-btn {
    width: 64px;
    height: 48px;
    border-radius: 6px;
    overflow: hidden;
    cursor: pointer;
    opacity: 0.55;
    transition: opacity 0.2s, border-color 0.2s;
    border: 2px solid transparent;
    padding: 0;
    background: #111;
}

.gallery-thumb-btn.active, 
.gallery-thumb-btn:hover {
    opacity: 1;
    border-color: #ef4444;
}

/* Mobile Optimizations */
@media (max-width: 576px) {
    .gallery-media-wrapper {
        aspect-ratio: 16 / 11;
    }
    .gallery-card-body {
        padding: 1rem;
    }
    .gallery-modal-carousel-img,
    .gallery-modal-video {
        max-height: 50vh;
    }
    .gallery-thumb-btn {
        width: 50px;
        height: 38px;
    }
}
</style>

<section class="py-4 py-md-5">
    <div class="container py-2">

        <!-- Top Header & Banner -->
        <div class="text-center mb-4 mb-md-5">
            <span class="gallery-hero-badge px-3 py-1 rounded-pill fw-bold text-uppercase d-inline-flex align-items-center gap-2 mb-2">
                <i class="fa-solid fa-camera-retro"></i> DOKUMENTASI KEGIATAN & WORKSHOP
            </span>
            <h1 class="display-6 display-md-5 fw-bold text-white font-heading mt-2 mb-3">
                Galeri Multimedia Club
            </h1>
            <p class="text-secondary col-lg-8 mx-auto lead-sm mb-0">
                Arsip visual perjalanan, liputan acara sekolah, pelatihan rutin, dan karya eksplorasi multimedia keluarga besar SMAN 1 Tamansari.
            </p>

            <!-- Admin / BPH / Pembina Quick Bar -->
            <?php if (!empty($canManage)): ?>
                <div class="mt-4 d-inline-flex flex-wrap align-items-center justify-content-center gap-2 p-2 rounded-3 bg-dark border border-danger border-opacity-25 shadow">
                    <span class="text-danger small font-monospace px-2">
                        <i class="fa-solid fa-shield-halved me-1"></i> Mode Pengelola:
                    </span>
                    <a href="<?= base_url('admin/cms/gallery') ?>" class="btn btn-sm btn-red px-3">
                        <i class="fa-solid fa-plus me-1"></i> Tambah / Kelola Galeri di CMS
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Filter & Search Bar -->
        <div class="row g-3 align-items-center justify-content-between mb-4">
            <!-- Category Pills Filter -->
            <div class="col-12 col-md-8">
                <div class="d-flex align-items-center gap-2 overflow-auto pb-2 pb-md-0" style="scrollbar-width: thin;">
                    <a href="<?= base_url('gallery' . (!empty($searchQuery) ? '?q=' . urlencode($searchQuery) : '')) ?>" 
                       class="btn btn-sm px-3 rounded-pill text-nowrap <?= empty($activeCategory) || $activeCategory === 'all' ? 'btn-red' : 'btn-outline-secondary' ?>">
                        <i class="fa-solid fa-layer-group me-1"></i> Semua Kegiatan
                    </a>

                    <?php
                    $defaultFilterList = ['Workshop', 'Liputan Acara', 'Latihan Rutin', 'Lomba & Ekskul'];
                    $mergedCats = array_unique(array_filter(array_merge($defaultFilterList, $categories ?? [])));
                    foreach ($mergedCats as $cat):
                        $isActive = ($activeCategory === $cat);
                    ?>
                        <a href="<?= base_url('gallery?category=' . urlencode($cat) . (!empty($searchQuery) ? '&q=' . urlencode($searchQuery) : '')) ?>" 
                           class="btn btn-sm px-3 rounded-pill text-nowrap <?= $isActive ? 'btn-red' : 'btn-outline-secondary' ?>">
                            <?= esc($cat) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Search Form -->
            <div class="col-12 col-md-4">
                <form action="<?= base_url('gallery') ?>" method="GET" class="position-relative">
                    <?php if (!empty($activeCategory)): ?>
                        <input type="hidden" name="category" value="<?= esc($activeCategory) ?>">
                    <?php endif; ?>
                    <div class="input-group">
                        <input type="text" name="q" class="form-control bg-dark text-white border-secondary border-opacity-50 small rounded-start-pill ps-3" placeholder="Cari judul kegiatan / acara..." value="<?= esc($searchQuery ?? '') ?>">
                        <button class="btn btn-red rounded-end-pill px-3" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Gallery Grid (Responsive) -->
        <?php if (empty($albums)): ?>
            <!-- Empty State -->
            <div class="saas-card p-5 text-center my-4">
                <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-inline-flex align-items-center justify-content-center p-4 mb-3" style="width: 80px; height: 80px;">
                    <i class="fa-solid fa-photo-film fs-2"></i>
                </div>
                <h4 class="text-white font-heading fw-bold">Belum Ada Dokumentasi Kegiatan</h4>
                <p class="text-secondary col-md-6 mx-auto small mb-4">
                    <?php if (!empty($searchQuery) || !empty($activeCategory)): ?>
                        Tidak ada kegiatan yang sesuai dengan filter atau pencarian Anda. Coba kata kunci lain atau reset filter.
                    <?php else: ?>
                        Dokumentasi foto dan video kegiatan MMC sedang dalam tahap pengarsipan. Nantikan foto-foto seru kegiatan kami segera!
                    <?php endif; ?>
                </p>
                <?php if (!empty($searchQuery) || !empty($activeCategory)): ?>
                    <a href="<?= base_url('gallery') ?>" class="btn btn-outline-secondary btn-sm px-3">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset Filter & Pencarian
                    </a>
                <?php elseif (!empty($canManage)): ?>
                    <a href="<?= base_url('admin/cms/gallery') ?>" class="btn btn-red btn-sm px-3">
                        <i class="fa-solid fa-plus me-1"></i> Tambah Dokumentasi Pertama
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="row g-3 g-md-4">
                <?php foreach ($albums as $album): ?>
                    <?php
                    $mediaList = $album['media_list'] ?? [];
                    $totalMedia = count($mediaList);
                    $firstMedia = !empty($mediaList) ? $mediaList[0] : null;
                    $coverSrc = !empty($album['cover_image']) ? base_url($album['cover_image']) : (!empty($firstMedia) ? base_url($firstMedia['url']) : base_url('assets/images/placeholder-gallery.jpg'));
                    $isFirstVideo = !empty($firstMedia) && ($firstMedia['type'] === 'video');
                    ?>
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="gallery-card">
                            <!-- Media Preview Header -->
                            <div class="gallery-media-wrapper cursor-pointer" data-bs-toggle="modal" data-bs-target="#galleryModal<?= $album['id'] ?>" role="button">
                                <?php if (!empty($album['cover_image'])): ?>
                                    <img src="<?= base_url($album['cover_image']) ?>" alt="<?= esc($album['title']) ?>" loading="lazy">
                                <?php elseif ($isFirstVideo): ?>
                                    <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-black text-white p-3 text-center">
                                        <div class="rounded-circle bg-danger bg-opacity-25 text-danger p-3 mb-2 d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fa-solid fa-play fs-4 ms-1"></i>
                                        </div>
                                        <span class="small font-monospace text-secondary">Video Dokumentasi</span>
                                    </div>
                                <?php elseif (!empty($firstMedia)): ?>
                                    <img src="<?= base_url($firstMedia['url']) ?>" alt="<?= esc($album['title']) ?>" loading="lazy">
                                <?php else: ?>
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-dark text-secondary">
                                        <i class="fa-solid fa-camera-retro fs-1"></i>
                                    </div>
                                <?php endif; ?>

                                <!-- Date Badge -->
                                <?php if (!empty($album['event_date'])): ?>
                                    <div class="gallery-date-badge font-monospace">
                                        <i class="fa-regular fa-calendar text-danger me-1"></i>
                                        <?= date('d M Y', strtotime($album['event_date'])) ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Category Badge -->
                                <div class="gallery-category-badge badge bg-danger bg-opacity-75 text-white font-monospace">
                                    <?= esc($album['category'] ?? 'Kegiatan') ?>
                                </div>

                                <!-- Media Count Badge -->
                                <div class="gallery-count-badge d-flex align-items-center gap-2">
                                    <?php if ($album['photo_count'] > 0): ?>
                                        <span><i class="fa-solid fa-image text-success me-1"></i><?= $album['photo_count'] ?></span>
                                    <?php endif; ?>
                                    <?php if ($album['video_count'] > 0): ?>
                                        <span><i class="fa-solid fa-video text-info me-1"></i><?= $album['video_count'] ?></span>
                                    <?php endif; ?>
                                    <?php if ($totalMedia === 0 && !empty($album['cover_image'])): ?>
                                        <span><i class="fa-solid fa-image me-1"></i>1</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="gallery-card-body">
                                <h5 class="text-white font-heading fw-bold mb-2 cursor-pointer" data-bs-toggle="modal" data-bs-target="#galleryModal<?= $album['id'] ?>" role="button">
                                    <?= esc($album['title']) ?>
                                </h5>

                                <?php if (!empty($album['description'])): ?>
                                    <p class="gallery-desc-clamp mb-3">
                                        <?= esc($album['description']) ?>
                                    </p>
                                <?php endif; ?>

                                <!-- Action Buttons -->
                                <div class="mt-auto pt-2 border-top border-secondary border-opacity-25 d-flex align-items-center justify-content-between gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3 d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#galleryModal<?= $album['id'] ?>">
                                        <i class="fa-solid fa-images text-danger me-1"></i> Buka Galeri
                                    </button>

                                    <?php if (!empty($album['external_link'])): ?>
                                        <a href="<?= esc($album['external_link']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-danger rounded-pill px-3 text-truncate" style="max-width: 150px;" title="<?= esc($album['external_link_title'] ?: 'Link Eksternal') ?>">
                                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>
                                            <?= esc($album['external_link_title'] ?: 'Dokumentasi') ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Interactive Detail Modal (Responsive Carousel for Mobile & Desktop) -->
                    <div class="modal fade" id="galleryModal<?= $album['id'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $album['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered modal-fullscreen-sm-down">
                            <div class="modal-content bg-dark text-white border border-secondary border-opacity-25 shadow-lg">
                                <!-- Modal Header -->
                                <div class="modal-header border-bottom border-secondary border-opacity-25 py-3">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="badge bg-danger bg-opacity-25 text-danger font-monospace px-2 py-1">
                                                <?= esc($album['category'] ?? 'Kegiatan') ?>
                                            </span>
                                            <?php if (!empty($album['event_date'])): ?>
                                                <span class="text-secondary small font-monospace">
                                                    <i class="fa-regular fa-calendar me-1"></i> <?= date('d F Y', strtotime($album['event_date'])) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <h5 class="modal-title font-heading fw-bold text-white mb-0" id="modalLabel<?= $album['id'] ?>">
                                            <?= esc($album['title']) ?>
                                        </h5>
                                    </div>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>

                                <!-- Modal Body -->
                                <div class="modal-body p-0">
                                    <?php if (!empty($mediaList)): ?>
                                        <!-- Carousel of Media -->
                                        <div id="carouselModal<?= $album['id'] ?>" class="carousel slide bg-black" data-bs-interval="false">
                                            <div class="carousel-inner">
                                                <?php foreach ($mediaList as $mIdx => $mItem): ?>
                                                    <?php $isVideo = (($mItem['type'] ?? 'image') === 'video'); ?>
                                                    <div class="carousel-item <?= ($mIdx === 0) ? 'active' : '' ?>">
                                                        <div class="d-flex align-items-center justify-content-center" style="min-height: 280px; max-height: 70vh;">
                                                            <?php if ($isVideo): ?>
                                                                <video controls playsinline preload="metadata" class="gallery-modal-video">
                                                                    <source src="<?= base_url($mItem['url']) ?>" type="<?= esc($mItem['mime'] ?? 'video/mp4') ?>">
                                                                    Browser Anda tidak mendukung tag video.
                                                                </video>
                                                            <?php else: ?>
                                                                <img src="<?= base_url($mItem['url']) ?>" class="gallery-modal-carousel-img" alt="<?= esc($album['title']) . ' - ' . ($mIdx + 1) ?>" loading="lazy">
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>

                                            <?php if (count($mediaList) > 1): ?>
                                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselModal<?= $album['id'] ?>" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon p-3 rounded-circle bg-dark bg-opacity-75" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Sebelumnya</span>
                                                </button>
                                                <button class="carousel-control-next" type="button" data-bs-target="#carouselModal<?= $album['id'] ?>" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon p-3 rounded-circle bg-dark bg-opacity-75" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Selanjutnya</span>
                                                </button>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Thumbnail Strip (for quick jump between photos/videos) -->
                                        <?php if (count($mediaList) > 1): ?>
                                            <div class="p-2 bg-dark bg-opacity-50 border-top border-secondary border-opacity-25 d-flex gap-2 overflow-auto justify-content-start justify-content-md-center">
                                                <?php foreach ($mediaList as $mIdx => $mItem): ?>
                                                    <?php $isVideo = (($mItem['type'] ?? 'image') === 'video'); ?>
                                                    <button type="button" class="gallery-thumb-btn <?= ($mIdx === 0) ? 'active' : '' ?>" data-bs-target="#carouselModal<?= $album['id'] ?>" data-bs-slide-to="<?= $mIdx ?>">
                                                        <?php if ($isVideo): ?>
                                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-info small">
                                                                <i class="fa-solid fa-play"></i>
                                                            </div>
                                                        <?php else: ?>
                                                            <img src="<?= base_url($mItem['url']) ?>" class="w-100 h-100 object-fit-cover" alt="Thumb <?= $mIdx + 1 ?>">
                                                        <?php endif; ?>
                                                    </button>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php elseif (!empty($album['cover_image'])): ?>
                                        <div class="bg-black text-center p-2">
                                            <img src="<?= base_url($album['cover_image']) ?>" class="gallery-modal-carousel-img" alt="<?= esc($album['title']) ?>">
                                        </div>
                                    <?php endif; ?>

                                    <!-- Description & Info Details -->
                                    <div class="p-3 p-md-4">
                                        <?php if (!empty($album['description'])): ?>
                                            <div class="text-light lead-sm mb-3" style="line-height: 1.7; font-size: 0.95rem;">
                                                <?= nl2br(esc($album['description'])) ?>
                                            </div>
                                        <?php else: ?>
                                            <p class="text-secondary small fst-italic mb-3">Tidak ada catatan deskripsi tambahan untuk dokumentasi ini.</p>
                                        <?php endif; ?>

                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-3 border-top border-secondary border-opacity-25">
                                            <div class="d-flex align-items-center gap-2 text-secondary small font-monospace">
                                                <span><i class="fa-solid fa-photo-film text-danger me-1"></i> Total Media: <strong><?= $totalMedia ?></strong></span>
                                                <?php if ($album['photo_count'] > 0): ?>
                                                    <span>(<?= $album['photo_count'] ?> Foto)</span>
                                                <?php endif; ?>
                                                <?php if ($album['video_count'] > 0): ?>
                                                    <span>(<?= $album['video_count'] ?> Video)</span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <?php if (!empty($album['external_link'])): ?>
                                                    <a href="<?= esc($album['external_link']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-red btn-sm px-3 fw-semibold">
                                                        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>
                                                        <?= esc($album['external_link_title'] ?: 'Buka Link Lengkap (Google Drive / Video)') ?>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if (!empty($canManage)): ?>
                                                    <a href="<?= base_url('admin/cms/gallery') ?>" class="btn btn-outline-info btn-sm px-3">
                                                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit di CMS
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<script>
// Thumbnail click sync with carousel active indicator
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.carousel').forEach(function(carouselEl) {
        carouselEl.addEventListener('slide.bs.carousel', function(event) {
            const modal = carouselEl.closest('.modal');
            if (!modal) return;
            const thumbBtns = modal.querySelectorAll('.gallery-thumb-btn');
            thumbBtns.forEach((btn, idx) => {
                if (idx === event.to) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
            // Pause any running videos when sliding
            modal.querySelectorAll('video').forEach(vid => vid.pause());
        });
    });

    // Pause videos when modal is closed
    document.querySelectorAll('.modal').forEach(function(modalEl) {
        modalEl.addEventListener('hidden.bs.modal', function() {
            modalEl.querySelectorAll('video').forEach(vid => vid.pause());
        });
    });
});
</script>

<?= $this->endSection() ?>
