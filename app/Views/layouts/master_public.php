<?php helper(['pwa', 'setting']); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? get_setting('site_title', 'Multimedia Club SMAN 1 Tamansari')) ?></title>
    
    <!-- Dynamic Meta SEO -->
    <meta name="description" content="<?= esc(get_setting('meta_description', 'Official website & member platform of Multimedia Club SMAN 1 Tamansari.')) ?>">
    <meta name="keywords" content="<?= esc(get_setting('meta_keywords', 'multimedia club, sman 1 tamansari, fotografi, videografi, koding')) ?>">
    <meta name="author" content="<?= esc(get_setting('meta_author', 'Multimedia Club SMAN 1 Tamansari')) ?>">
    <meta property="og:title" content="<?= esc($title ?? get_setting('site_title', 'Multimedia Club SMAN 1 Tamansari')) ?>">
    <meta property="og:description" content="<?= esc(get_setting('meta_description', 'Official website & member platform of Multimedia Club SMAN 1 Tamansari.')) ?>">
    <meta property="og:image" content="<?= (strpos(get_setting('meta_image', 'assets/icons/icon-512.png'), 'http') === 0) ? esc(get_setting('meta_image', 'assets/icons/icon-512.png')) : base_url(get_setting('meta_image', 'assets/icons/icon-512.png')) ?>">

    <!-- Dynamic Favicon -->
    <link rel="shortcut icon" href="<?= (strpos(get_setting('site_favicon', 'assets/icons/favicon.png'), 'http') === 0) ? esc(get_setting('site_favicon', 'assets/icons/favicon.png')) : base_url(get_setting('site_favicon', 'assets/icons/favicon.png')) ?>" type="image/png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Local Vendor CSS -->
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap-icons/bootstrap-icons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/sweetalert2/dark.css') ?>">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/pwa-install-banner.css') ?>">

    <!-- PWA Meta Tags -->
    <?= pwa_meta_tags() ?>

    <!-- Custom Head Tags Injection -->
    <?= get_setting('custom_head_tags', '') ?>
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Glassmorphic Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-saas sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 font-heading" href="<?= base_url('/') ?>">
                <img src="<?= (strpos(get_setting('site_logo', 'assets/logo-mm-2023.png'), 'http') === 0) ? esc(get_setting('site_logo', 'assets/logo-mm-2023.png')) : base_url(get_setting('site_logo', 'assets/logo-mm-2023.png')) ?>" alt="Logo" style="height: 38px;" class="rounded-2 p-1 bg-white">
                <span class="fs-6 fs-sm-5 fw-bold text-white text-truncate" style="max-width: 240px;"><?= esc(get_setting('site_title', 'Multimedia Club SMAN 1 Tamansari')) ?></span>
            </a>
            
            <button class="navbar-toggler border-0 p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPublic" aria-controls="navbarPublic" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarPublic">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-2">
                    <li class="nav-item"><a class="nav-link text-light px-3 <?= (url_is('/')) ? 'active fw-bold text-danger' : '' ?>" href="<?= base_url('/') ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-light px-3 <?= (url_is('about*')) ? 'active fw-bold text-danger' : '' ?>" href="<?= base_url('about') ?>">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link text-light px-3 <?= (url_is('learning-path*')) ? 'active fw-bold text-danger' : '' ?>" href="<?= base_url('learning-path') ?>">Learning Path</a></li>
                    <li class="nav-item"><a class="nav-link text-light px-3 <?= (url_is('materi*')) ? 'active fw-bold text-danger' : '' ?>" href="<?= base_url('materi') ?>">Materi</a></li>
                    <li class="nav-item"><a class="nav-link text-light px-3 <?= (url_is('portfolio*')) ? 'active fw-bold text-danger' : '' ?>" href="<?= base_url('portfolio') ?>">Portofolio</a></li>
                    <li class="nav-item"><a class="nav-link text-light px-3 <?= (url_is('achievements*') || url_is('prestasi*')) ? 'active fw-bold text-danger' : '' ?>" href="<?= base_url('achievements') ?>">Prestasi</a></li>
                    <li class="nav-item"><a class="nav-link text-light px-3 <?= (url_is('gallery*')) ? 'active fw-bold text-danger' : '' ?>" href="<?= base_url('gallery') ?>">Galeri</a></li>
                    <li class="nav-item"><a class="nav-link text-light px-3 <?= (url_is('faq*')) ? 'active fw-bold text-danger' : '' ?>" href="<?= base_url('faq') ?>">FAQ</a></li>
                </ul>

                <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0 flex-wrap">
                    <?php if (session()->get('is_logged_in')): ?>
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-red px-4 w-100 w-lg-auto">
                            <i class="fa-solid fa-gauge me-2"></i> Dashboard
                        </a>
                    <?php else: ?>
                        <?php 
                            $enableRegNav = (new \App\Models\SettingModel())->getSetting('enable_registration', '1');
                        ?>
                        <a href="<?= base_url('login') ?>" class="btn btn-outline-red px-4 flex-fill flex-lg-grow-0">Login</a>
                        <?php if ($enableRegNav === '1'): ?>
                            <a href="<?= base_url('register') ?>" class="btn btn-red px-4 flex-fill flex-lg-grow-0">Daftar Member</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow-1">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="mt-auto py-5 border-top border-secondary border-opacity-25" style="background: #07070a;">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="<?= (strpos(get_setting('site_logo', 'assets/logo-mm-2023.png'), 'http') === 0) ? esc(get_setting('site_logo', 'assets/logo-mm-2023.png')) : base_url(get_setting('site_logo', 'assets/logo-mm-2023.png')) ?>" alt="Multimedia Club Logo" style="height: 34px;" class="rounded-2 p-1 bg-white">
                        <span class="fs-5 fw-bold text-white font-heading"><?= esc(get_setting('footer_brand_name', get_setting('site_title', 'MMC SMAN 1 Tamansari'))) ?></span>
                    </div>
                    <p class="text-secondary small pe-lg-5 mb-3">
                        <?= esc(get_setting('footer_about', 'Wadah kreativitas siswa SMAN 1 Tamansari dalam bidang videografi, fotografi, desain grafis, pemrograman web, dan penyiaran media digital.')) ?>
                    </p>
                    <div class="d-flex gap-3 text-secondary">
                        <?php if ($ig = get_setting('social_instagram', '#')): ?>
                            <a href="<?= esc($ig) ?>" target="_blank" class="text-secondary fs-5" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <?php endif; ?>
                        <?php if ($yt = get_setting('social_youtube', '#')): ?>
                            <a href="<?= esc($yt) ?>" target="_blank" class="text-secondary fs-5" title="YouTube"><i class="fab fa-youtube"></i></a>
                        <?php endif; ?>
                        <?php if ($tt = get_setting('social_tiktok', '#')): ?>
                            <a href="<?= esc($tt) ?>" target="_blank" class="text-secondary fs-5" title="TikTok"><i class="fab fa-tiktok"></i></a>
                        <?php endif; ?>
                        <?php if ($gh = get_setting('social_github', '#')): ?>
                            <a href="<?= esc($gh) ?>" target="_blank" class="text-secondary fs-5" title="GitHub"><i class="fab fa-github"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <h6 class="text-white font-heading mb-3"><?= esc(get_setting('footer_nav_title', 'Navigasi Cepat')) ?></h6>
                    <ul class="list-unstyled text-secondary small d-flex flex-column gap-2">
                        <li><a href="<?= base_url('about') ?>" class="text-secondary">Tentang Klub</a></li>
                        <li><a href="<?= base_url('learning-path') ?>" class="text-secondary">Divisi & Kurikulum</a></li>
                        <li><a href="<?= base_url('portfolio') ?>" class="text-secondary">Portofolio Karya</a></li>
                        <li><a href="<?= base_url('achievements') ?>" class="text-secondary">Prestasi & Tim Juara</a></li>
                        <li><a href="<?= base_url('gallery') ?>" class="text-secondary">Galeri Kegiatan</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-4">
                    <h6 class="text-white font-heading mb-3"><?= esc(get_setting('footer_contact_title', 'Kontak & Lokasi')) ?></h6>
                    <?php if ($addr = get_setting('footer_address', get_setting('school_address', 'SMAN 1 Tamansari, Kab. Bogor'))): ?>
                        <p class="text-secondary small mb-1"><i class="fa-solid fa-location-dot me-2 text-danger"></i> <?= esc($addr) ?></p>
                    <?php endif; ?>
                    <?php if ($email = get_setting('footer_email', get_setting('contact_email', 'multimediasman1t@gmail.com'))): ?>
                        <p class="text-secondary small mb-1"><i class="fa-solid fa-envelope me-2 text-danger"></i> <?= esc($email) ?></p>
                    <?php endif; ?>
                    <?php if ($phone = get_setting('footer_phone', get_setting('club_phone', '+62 812-3456-7890'))): ?>
                        <p class="text-secondary small mb-0"><i class="fa-solid fa-phone me-2 text-danger"></i> <?= esc($phone) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="border-top border-secondary border-opacity-10 mt-4 pt-4 text-center text-secondary small">
                <?php 
                    $defaultCopy = '&copy; {year} Multimedia Club SMAN 1 Tamansari. Built with CodeIgniter 4 & Dark SaaS UI.';
                    $copyrightText = get_setting('footer_copyright', $defaultCopy);
                    $copyrightText = str_replace('{year}', date('Y'), $copyrightText);
                ?>
                <?= $copyrightText ?>
            </div>
        </div>
    </footer>

    <!-- Local Vendor Scripts -->
    <script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/sweetalert2/sweetalert2.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/qrious/qrious.min.js') ?>"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>

    <!-- SweetAlert2 Toast Handler -->
    <script>
        $(document).ready(function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                background: '#121218',
                color: '#fff',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            <?php if (session()->getFlashdata('success')): ?>
                Toast.fire({
                    icon: 'success',
                    title: '<?= session()->getFlashdata('success') ?>'
                });
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                Toast.fire({
                    icon: 'error',
                    title: '<?= session()->getFlashdata('error') ?>'
                });
            <?php endif; ?>
        });
    </script>
    
    <?= $this->renderSection('scripts') ?>

    <!-- Progressier-Style Top Floating PWA Install Banner -->
    <div id="pwaProgressierBanner">
        <div class="pwa-banner-container">
            <div class="pwa-banner-brand">
                <img src="<?= (strpos(get_setting('site_logo', 'assets/logo-mm-2023.png'), 'http') === 0) ? esc(get_setting('site_logo', 'assets/logo-mm-2023.png')) : base_url(get_setting('site_logo', 'assets/logo-mm-2023.png')) ?>" alt="MMC Logo" class="pwa-banner-logo">
                <div class="pwa-banner-info">
                    <div class="pwa-banner-title"><?= esc(get_setting('site_title', 'Multimedia Club SMAN 1 Tamansari')) ?></div>
                    <div class="pwa-banner-desc">Install aplikasi resmi agar akses lebih cepat & dapat digunakan secara offline.</div>
                </div>
                <div class="pwa-banner-benefits d-none d-lg-flex">
                    <span class="pwa-benefit-chip"><i class="fa-solid fa-bolt text-warning"></i> Cepat</span>
                    <span class="pwa-benefit-chip"><i class="fa-solid fa-wifi-slash text-danger"></i> Offline</span>
                    <span class="pwa-benefit-chip"><i class="fa-solid fa-bell text-info"></i> Notifikasi</span>
                    <span class="pwa-benefit-chip"><i class="fa-solid fa-expand text-success"></i> Fullscreen</span>
                </div>
            </div>
            <div class="pwa-banner-actions">
                <button class="btn-pwa-install" onclick="triggerPwaInstall()"><i class="fa-solid fa-download"></i> Install</button>
                <button class="btn-pwa-secondary" onclick="showPwaLearnMore()"><i class="fa-solid fa-circle-info me-1"></i> Pelajari</button>
                <button class="btn-pwa-secondary" onclick="postponePwaInstall()">Nanti</button>
                <button class="btn-pwa-close" onclick="closePwaBanner()" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button>
            </div>
        </div>
    </div>

    <!-- PWA Install Helper Banner (iOS Safari) -->
    <div id="pwaIosBanner" class="position-fixed bottom-0 start-50 translate-middle-x mb-3 p-3 rounded-3 bg-dark border border-danger shadow-lg text-white style-tiny z-3" style="display: none; max-width: 480px; width: 92%;">
        <div class="d-flex align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <img src="<?= base_url('assets/logo-mm-2023.png') ?>" alt="MMC Logo" style="height: 32px;" class="bg-white p-1 rounded-2">
                <div>
                    <div class="fw-bold text-white">Install MMC Platform (iOS)</div>
                    <div class="text-secondary style-tiny">Tekan tombol <i class="fa-solid fa-arrow-up-from-bracket text-info"></i> Share lalu pilih <strong>'Tambah ke Layar Utama'</strong>.</div>
                </div>
            </div>
            <button class="btn-pwa-secondary py-1 px-2" onclick="dismissPwaInstallBanner()">Mengerti</button>
        </div>
    </div>

    <!-- PWA Update Available Toast -->
    <div id="pwaUpdateToast" class="position-fixed bottom-0 end-0 m-3 p-3 rounded-3 bg-dark border border-info shadow-lg text-white style-tiny z-3" style="display: none; max-width: 360px;">
        <div class="d-flex align-items-center justify-content-between gap-2">
            <div>
                <div class="fw-bold text-info"><i class="fa-solid fa-cloud-arrow-down me-1"></i> Versi Baru Tersedia</div>
                <div class="text-secondary style-tiny">Pembaruan aplikasi MMC Platform siap digunakan.</div>
            </div>
            <button class="btn btn-sm btn-info text-dark font-monospace fw-bold text-nowrap px-3" onclick="reloadPwaForUpdate()">Muat Ulang</button>
        </div>
    </div>

    <!-- PWA Benefits Learn More Modal -->
    <div class="modal fade" id="pwaLearnMoreModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border border-secondary border-opacity-25 shadow-lg">
                <div class="modal-header border-bottom border-secondary border-opacity-25">
                    <h5 class="modal-title font-heading"><i class="fa-solid fa-mobile-screen-button text-danger me-2"></i> Keuntungan Install Aplikasi MMC</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <img src="<?= base_url('assets/logo-mm-2023.png') ?>" style="height: 54px;" class="bg-white p-1 rounded-3 mb-2">
                        <h6 class="fw-bold text-white font-heading">Aplikasi MMC Platform Native</h6>
                        <p class="text-secondary style-tiny m-0">Pengalaman belajar dan manajemen tugas yang lebih praktis di perangkat Anda.</p>
                    </div>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex gap-3 align-items-start">
                            <div class="p-2 rounded bg-danger bg-opacity-25 text-danger"><i class="fa-solid fa-wifi-slash fs-5"></i></div>
                            <div>
                                <div class="fw-bold text-white small">Akses Offline Lengkap</div>
                                <div class="text-secondary style-tiny">Modul materi dan halaman penting tetap dapat dibuka walau tanpa internet.</div>
                            </div>
                        </div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="p-2 rounded bg-warning bg-opacity-25 text-warning"><i class="fa-solid fa-bolt fs-5"></i></div>
                            <div>
                                <div class="fw-bold text-white small">Waktu Muat Instan</div>
                                <div class="text-secondary style-tiny">Aset disimpan secara lokal untuk pengoperasian yang sangat cepat.</div>
                            </div>
                        </div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="p-2 rounded bg-info bg-opacity-25 text-info"><i class="fa-solid fa-bell fs-5"></i></div>
                            <div>
                                <div class="fw-bold text-white small">Pemberitahuan Langsung</div>
                                <div class="text-secondary style-tiny">Dapatkan notifikasi tugas baru dan presensi langsung ke HP Anda.</div>
                            </div>
                        </div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="p-2 rounded bg-success bg-opacity-25 text-success"><i class="fa-solid fa-expand fs-5"></i></div>
                            <div>
                                <div class="fw-bold text-white small">Tampilan Fullscreen Tanpa Bar Browser</div>
                                <div class="text-secondary style-tiny">Merasa seperti menggunakan aplikasi Play Store / App Store native.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25">
                    <button type="button" class="btn btn-red w-100" onclick="triggerPwaInstall()" data-bs-dismiss="modal">
                        <i class="fa-solid fa-download me-1"></i> Install Aplikasi Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Body Tags Injection -->
    <?= get_setting('custom_body_tags', '') ?>

    <!-- PWA Service Worker Registration & Scripts -->
    <?= pwa_sw_script() ?>
    <script src="<?= base_url('assets/js/pwa-install-banner.js') ?>"></script>
</body>
</html>
