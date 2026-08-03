<?= $this->extend('layouts/master_public') ?>

<?= $this->section('content') ?>
<section class="py-5">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="text-danger font-monospace text-uppercase fw-bold" style="letter-spacing: 0.1em;">PERTANYAAN UMUM</span>
            <h1 class="display-5 fw-bold text-white font-heading mt-2">Frequently Asked Questions</h1>
            <p class="text-secondary col-lg-8 mx-auto">Jawaban atas pertanyaan seputar pendaftaran, jadwal latihan, dan fasilitas Multimedia Club SMAN 1 Tamansari.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if (!empty($faqs)): ?>
                    <div class="accordion accordion-flush" id="faqAccordion">
                        <?php foreach ($faqs as $i => $fq): ?>
                            <div class="accordion-item saas-card mb-3 border border-secondary border-opacity-25 rounded-3 overflow-hidden">
                                <h2 class="accordion-header" id="headingFaqPage<?= $i ?>">
                                    <button class="accordion-button bg-dark text-white font-heading collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFaqPage<?= $i ?>" aria-expanded="false" aria-controls="collapseFaqPage<?= $i ?>">
                                        <i class="fa-solid fa-circle-question text-danger me-2"></i> <?= esc($fq['question']) ?>
                                    </button>
                                </h2>
                                <div id="collapseFaqPage<?= $i ?>" class="accordion-collapse collapse bg-dark" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-secondary small leading-relaxed border-top border-secondary border-opacity-25">
                                        <?= esc($fq['answer']) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 saas-card p-5 border border-secondary border-opacity-25">
                        <i class="fa-solid fa-circle-question display-3 text-secondary mb-3 opacity-50"></i>
                        <h4 class="text-white font-heading">Belum Ada Pertanyaan FAQ</h4>
                        <p class="text-secondary small mb-0">Pertanyaan umum belum ditambahkan oleh administrator.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
