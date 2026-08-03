<?= $this->extend('layouts/master_public') ?>

<?= $this->section('content') ?>
<section class="py-5">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="text-danger font-monospace text-uppercase fw-bold" style="letter-spacing: 0.1em;">SILABUS & KURIKULUM</span>
            <h1 class="display-5 fw-bold text-white font-heading mt-2">Learning Path Per Divisi</h1>
            <p class="text-secondary col-lg-8 mx-auto">Pilih divisi minat bakat Anda dan pelajari alur kurikulum pelatihan terstruktur Multimedia Club SMAN 1 Tamansari.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php foreach ($divisions as $div): ?>
                <div class="col-md-6 col-lg-6" id="<?= esc($div['slug']) ?>">
                    <div class="saas-card saas-card-glow p-4 h-100 border border-secondary border-opacity-25 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="rounded-3 bg-danger bg-opacity-25 p-3 text-danger">
                                    <i class="fa-solid <?= esc($div['icon']) ?> fs-2"></i>
                                </div>
                                <div>
                                    <h3 class="text-white font-heading mb-1"><?= esc($div['name']) ?></h3>
                                    <span class="badge bg-secondary font-monospace">Spesialisasi Keterampilan</span>
                                </div>
                            </div>

                            <p class="text-secondary leading-relaxed mb-4"><?= esc($div['full_description'] ?: $div['short_description']) ?></p>

                            <!-- Dynamic Programs Syllabus -->
                            <h6 class="text-white font-heading mb-3"><i class="fa-solid fa-graduation-cap text-danger me-2"></i> Modul & Program Belajar:</h6>
                            <div class="d-flex flex-column gap-2 mb-4">
                                <?php if (empty($div['programs'])): ?>
                                    <div class="text-secondary small fst-italic">Program belajar sedang dalam penyusunan.</div>
                                <?php else: ?>
                                    <?php foreach ($div['programs'] as $idx => $p): ?>
                                        <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="badge bg-danger rounded-circle p-2 font-monospace" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;"><?= $idx + 1 ?></span>
                                                <div>
                                                    <div class="text-white fw-semibold small"><?= esc($p['title']) ?></div>
                                                    <div class="text-secondary style-tiny"><?= esc($p['description']) ?></div>
                                                </div>
                                            </div>
                                            <span class="badge bg-secondary bg-opacity-25 text-secondary border border-secondary border-opacity-25 font-monospace style-tiny">
                                                <?= esc($p['difficulty']) ?>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <a href="<?= base_url('register') ?>" class="btn btn-red w-100">
                            <i class="fa-solid fa-user-plus me-2"></i> Daftar Divisi Ini
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
