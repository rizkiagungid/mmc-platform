<?= $this->extend('layouts/master_public') ?>

<?= $this->section('content') ?>
<section class="py-5">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="text-danger font-monospace text-uppercase fw-bold" style="letter-spacing: 0.1em;">DOKUMENTASI KEGIATAN</span>
            <h1 class="display-5 fw-bold text-white font-heading mt-2">Galeri Workshop & Event</h1>
            <p class="text-secondary col-lg-8 mx-auto">Dokumentasi momen latihan rutin, workshop sinematografi, dan liputan event SMAN 1 Tamansari.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="saas-card p-3">
                    <div class="rounded-3 bg-dark d-flex align-items-center justify-content-center mb-3" style="height: 180px; background: linear-gradient(45deg, #181824, #27273a);">
                        <i class="fa-solid fa-camera-retro text-danger fs-1"></i>
                    </div>
                    <h6 class="text-white font-heading mb-1">Workshop Outdoor Photography</h6>
                    <small class="text-secondary">Praktek teknik lighting natural di lingkungan sekolah</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="saas-card p-3">
                    <div class="rounded-3 bg-dark d-flex align-items-center justify-content-center mb-3" style="height: 180px; background: linear-gradient(45deg, #2a0808, #121218);">
                        <i class="fa-solid fa-sliders text-danger fs-1"></i>
                    </div>
                    <h6 class="text-white font-heading mb-1">Pelatihan Audio Podcast</h6>
                    <small class="text-secondary">Perekaman vokal dan mixing audio dengan Mic Kondensor</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="saas-card p-3">
                    <div class="rounded-3 bg-dark d-flex align-items-center justify-content-center mb-3" style="height: 180px; background: linear-gradient(45deg, #091e3a, #121218);">
                        <i class="fa-solid fa-laptop-code text-danger fs-1"></i>
                    </div>
                    <h6 class="text-white font-heading mb-1">Hackathon Web Design</h6>
                    <small class="text-secondary">Pengerjaan proyek landing page klub secara kolaboratif</small>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
