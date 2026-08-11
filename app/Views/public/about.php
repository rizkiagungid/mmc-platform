<?= $this->extend('layouts/master_public') ?>

<?= $this->section('content') ?>
<section class="py-5" style="background: radial-gradient(circle at 50% 0%, rgba(220, 38, 38, 0.1) 0%, rgba(9, 9, 11, 1) 70%);">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="text-danger font-monospace text-uppercase fw-bold" style="letter-spacing: 0.1em;">TENTANG KAMI</span>
            <h1 class="display-5 fw-bold text-white font-heading mt-2"><?= esc($history['title'] ?? 'Profil, Sejarah & Struktur Organisasi') ?></h1>
            <p class="text-secondary col-lg-8 mx-auto">Mengenal visi, misi, jejak rekam sejarah, serta susunan kepengurusan Multimedia Club SMAN 1 Tamansari.</p>
        </div>

        <!-- Vision & Missions -->
        <div class="row g-4 mb-5 justify-content-center">
            <div class="col-md-6">
                <div class="saas-card p-4 h-100 border border-secondary border-opacity-25">
                    <h3 class="text-white font-heading mb-3"><i class="fa-solid fa-eye text-danger me-2"></i> Visi Klub</h3>
                    <p class="text-secondary leading-relaxed">
                        <?= esc($history['vision'] ?? 'Menjadi pusat keunggulan kreativitas digital dan teknologi media bagi seluruh siswa SMAN 1 Tamansari, yang menghasilkan karya berkualitas profesional, beretika, serta adaptif terhadap perkembangan industri kreatif global.') ?>
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="saas-card p-4 h-100 border border-secondary border-opacity-25">
                    <h3 class="text-white font-heading mb-3"><i class="fa-solid fa-bullseye text-danger me-2"></i> Misi Utama</h3>
                    <ul class="text-secondary small d-flex flex-column gap-2 mb-0 list-unstyled">
                        <?php if (!empty($missions)): ?>
                            <?php foreach ($missions as $m): ?>
                                <li><i class="fa-solid fa-check text-danger me-2"></i> <?= esc($m['mission_text']) ?></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li><i class="fa-solid fa-check text-danger me-2"></i> Mengembangkan keterampilan praktis videografi, fotografi, desain grafis, dan web development.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Historical Timelines -->
        <?php if (!empty($timelines)): ?>
            <div class="text-center mb-4">
                <span class="text-danger font-monospace text-uppercase fw-bold" style="letter-spacing: 0.1em;">JEJAK REKAM</span>
                <h3 class="text-white font-heading mt-1">Garis Waktu Perjalanan Klub</h3>
            </div>
            <div class="row g-4 justify-content-center mb-5">
                <?php foreach ($timelines as $t): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="saas-card p-4 h-100 text-center">
                            <span class="badge bg-danger font-monospace fs-6 px-3 py-1 mb-3"><?= esc($t['year']) ?></span>
                            <h5 class="text-white font-heading mb-2"><?= esc($t['title']) ?></h5>
                            <p class="text-secondary small mb-0"><?= esc($t['description']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Organizational Structure Section -->
        <div class="text-center mb-4">
            <span class="text-danger font-monospace text-uppercase fw-bold" style="letter-spacing: 0.1em;">SUSUNAN ORGANISASI</span>
            <h3 class="text-white font-heading mt-1">Bagan Kepengurusan</h3>
        </div>

        <div class="row g-4 justify-content-center">
            <?php foreach ($structures as $s): ?>
                <div class="col-md-4 col-lg-3">
                    <div class="saas-card p-4 text-center h-100 d-flex flex-column justify-content-between">
                        <div>
                            <?php if (!empty($s['photo'])): ?>
                                <div class="position-relative d-inline-block cursor-pointer mx-auto mb-3" data-bs-toggle="modal" data-bs-target="#publicPhotoModal<?= $s['id'] ?>" title="Klik untuk memperbesar foto">
                                    <img src="<?= base_url($s['photo']) ?>" alt="<?= esc($s['name']) ?>" class="rounded-circle object-fit-cover border border-danger border-2 shadow" style="width: 76px; height: 76px; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                                    <span class="position-absolute bottom-0 end-0 bg-danger text-white rounded-circle p-1 d-flex align-items-center justify-content-center shadow" style="width: 22px; height: 22px; font-size: 0.65rem;" title="Lihat Foto Full">
                                        <i class="fa-solid fa-magnifying-glass-plus"></i>
                                    </span>
                                </div>
                            <?php else: ?>
                                <div class="rounded-circle bg-danger bg-opacity-25 border border-danger text-white fs-3 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                                    <?= strtoupper(substr($s['name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <h5 class="text-white font-heading mb-1"><?= esc($s['name']) ?></h5>
                            <span class="badge bg-danger mb-2 font-monospace"><?= esc($s['position']) ?></span>
                            <p class="text-secondary small mb-0"><?= esc($s['bio'] ?: 'Pengurus Aktif MMC') ?></p>
                        </div>
                    </div>

                    <?php if (!empty($s['photo'])): ?>
                        <!-- Modal Preview Foto Full Pengurus Publik -->
                        <div class="modal fade" id="publicPhotoModal<?= $s['id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content bg-dark text-white border border-secondary border-opacity-25 shadow-lg">
                                    <div class="modal-header border-bottom border-secondary border-opacity-25">
                                        <h5 class="modal-title font-heading">
                                            <i class="fa-solid fa-user-tie text-danger me-2"></i> <?= esc($s['name']) ?> - <?= esc($s['position']) ?>
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center p-4">
                                        <img src="<?= base_url($s['photo']) ?>" alt="<?= esc($s['name']) ?>" class="img-fluid rounded-3 shadow-lg border border-secondary border-opacity-50" style="max-height: 75vh; object-fit: contain;">
                                        <div class="mt-3">
                                            <h4 class="text-white font-heading mb-1"><?= esc($s['name']) ?></h4>
                                            <span class="badge bg-danger font-monospace px-3 py-1"><?= esc($s['position']) ?></span>
                                            <?php if ($s['bio']): ?>
                                                <p class="text-secondary small mt-2 mb-0"><?= esc($s['bio']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top border-secondary border-opacity-25 justify-content-between">
                                        <a href="<?= base_url($s['photo']) ?>" target="_blank" class="btn btn-sm btn-outline-light">
                                            <i class="fa-solid fa-external-link me-1"></i> Buka Gambar Ukuran Asli
                                        </a>
                                        <button type="button" class="btn btn-saas-dark btn-sm" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
