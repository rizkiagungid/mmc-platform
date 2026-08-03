<?= $this->extend('layouts/master_public') ?>

<?= $this->section('content') ?>
<section class="py-5 min-vh-100 d-flex align-items-center justify-content-center" style="background: radial-gradient(circle at 50% 30%, rgba(220, 38, 38, 0.15) 0%, rgba(9, 9, 11, 1) 75%);">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="saas-card p-4 p-md-5 border border-danger border-opacity-25 shadow-lg">
                    
                    <div class="text-center mb-4">
                        <img src="<?= base_url('assets/logo-mm-2023.png') ?>" alt="MMC Logo" style="height: 54px;" class="rounded-3 p-1.5 bg-white mb-3 shadow">
                        <h3 class="text-white font-heading mb-1">Login Portal MMC</h3>
                        <p class="text-secondary small">Masuk ke akun anggota / pengurus Multimedia Club</p>
                    </div>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger border-0 bg-danger bg-opacity-25 text-danger small py-2 px-3 mb-4 rounded-3">
                            <i class="fa-solid fa-circle-exclamation me-1"></i> <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success border-0 bg-success bg-opacity-25 text-success small py-2 px-3 mb-4 rounded-3">
                            <i class="fa-solid fa-circle-check me-1"></i> <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('login') ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-medium">Username atau Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fa-solid fa-user"></i></span>
                                <input type="text" name="login" class="form-control" placeholder="Contoh: rizki_member" value="<?= old('login') ?>" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label text-secondary small fw-medium mb-0">Password</label>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-red w-100 py-2.5 mb-3 fw-semibold">
                            <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk Sekarang
                        </button>
                    </form>

                    <!-- Quick Demo Credentials Box -->
                    <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 mt-4">
                        <small class="text-white font-heading d-block fw-semibold mb-2"><i class="fa-solid fa-key text-warning me-1"></i> Demo Access Credentials:</small>
                        <div class="row g-2 text-secondary font-monospace" style="font-size: 0.75rem;">
                            <div class="col-6"><strong>Super Admin:</strong><br>superadmin / password123</div>
                            <div class="col-6"><strong>Pembina:</strong><br>pembina / password123</div>
                            <div class="col-6"><strong>BPH:</strong><br>bph_ketua / password123</div>
                            <div class="col-6"><strong>Member:</strong><br>rizki_member / password123</div>
                        </div>
                    </div>

                    <div class="text-center mt-4 text-secondary small">
                        Belum punya akun? <a href="<?= base_url('register') ?>" class="text-danger font-semibold">Daftar Anggota Baru</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
