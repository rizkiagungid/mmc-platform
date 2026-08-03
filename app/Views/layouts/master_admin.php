<?php helper(['pwa', 'setting']); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Portal Multimedia Club') ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Local Vendor CSS -->
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap-icons/bootstrap-icons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap5.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/sweetalert2/dark.css') ?>">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

    <!-- PWA Meta Tags -->
    <?php helper('pwa'); ?>
    <?= pwa_meta_tags() ?>
</head>
<body class="d-flex flex-column flex-lg-row">

    <!-- Responsive Sidebar Offcanvas Drawer (Mobile & Desktop) -->
    <aside class="offcanvas-lg offcanvas-start offcanvas-dark sidebar-wrapper d-flex flex-column flex-shrink-0 p-3" id="adminSidebar" tabindex="-1" aria-labelledby="adminSidebarLabel">
        <div class="offcanvas-header p-0 mb-3 d-lg-none">
            <h5 class="offcanvas-title text-white font-heading" id="adminSidebarLabel">MMC Platform</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#adminSidebar" aria-label="Close"></button>
        </div>

        <a href="<?= base_url('dashboard') ?>" class="d-flex align-items-center gap-2 mb-4 text-white text-decoration-none px-2 font-heading">
            <img src="<?= (strpos(get_setting('site_logo', 'assets/logo-mm-2023.png'), 'http') === 0) ? esc(get_setting('site_logo', 'assets/logo-mm-2023.png')) : base_url(get_setting('site_logo', 'assets/logo-mm-2023.png')) ?>" alt="MMC Logo" style="height: 38px;" class="rounded-2 p-1 bg-white">
            <div class="d-flex flex-column">
                <span class="fs-6 fw-bold lh-1 text-white">MMC <span class="text-danger">Platform</span></span>
                <span class="text-secondary small font-monospace" style="font-size: 0.7rem;">SMAN 1 Tamansari</span>
            </div>
        </a>

        <div class="px-2 mb-2">
            <span class="text-uppercase text-secondary font-monospace fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.1em;">MENU UTAMA</span>
        </div>

        <nav class="nav flex-column mb-auto">
            <a href="<?= base_url('dashboard') ?>" class="sidebar-link <?= (url_is('dashboard*')) ? 'active' : '' ?>">
                <i class="fa-solid fa-gauge text-danger me-1"></i> Dashboard
            </a>

            <?php if (in_array(session()->get('role_slug'), ['superadmin', 'pembina', 'bph'])): ?>
                <!-- Admin CMS Links -->
                <a href="<?= base_url('admin/meetings') ?>" class="sidebar-link <?= (url_is('admin/meetings*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-calendar-check text-info me-1"></i> Pertemuan
                </a>

                <a href="<?= base_url('admin/attendance') ?>" class="sidebar-link <?= (url_is('admin/attendance*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-qrcode text-warning me-1"></i> Presensi Absensi
                </a>

                <a href="<?= base_url('admin/tasks') ?>" class="sidebar-link <?= (url_is('admin/tasks*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-list-check text-primary me-1"></i> Tugas & Proyek
                </a>

                  <a href="<?= base_url('admin/learning') ?>" class="sidebar-link <?= (url_is('admin/learning*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-book-bookmark text-danger me-1"></i> Materi Pembelajaran
                </a>
                
                <a href="<?= base_url('admin/users') ?>" class="sidebar-link <?= (url_is('admin/users*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-users-gear text-success me-1"></i> Manajemen Anggota
                </a>

                

                <div class="px-2 mt-3 mb-2">
                    <span class="text-uppercase text-secondary font-monospace fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.1em;">WEBSITE CMS</span>
                </div>

                <a href="<?= base_url('admin/cms/builder') ?>" class="sidebar-link <?= (url_is('admin/cms/builder*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-layer-group text-danger me-1"></i> Homepage Builder
                </a>

                <a href="<?= base_url('admin/cms/media') ?>" class="sidebar-link <?= (url_is('admin/cms/media*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-photo-film text-warning me-1"></i> Media Library
                </a>

                <a href="<?= base_url('admin/cms/divisions') ?>" class="sidebar-link <?= (url_is('admin/cms/divisions*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-graduation-cap text-info me-1"></i> Divisi & Silabus
                </a>

                <a href="<?= base_url('admin/cms/portfolios') ?>" class="sidebar-link <?= (url_is('admin/cms/portfolios*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-film text-primary me-1"></i> Portofolio Karya
                </a>

                <a href="<?= base_url('admin/cms/achievements') ?>" class="sidebar-link <?= (url_is('admin/cms/achievements*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-trophy text-warning me-1"></i> Prestasi Juara
                </a>

                <a href="<?= base_url('admin/cms/history') ?>" class="sidebar-link <?= (url_is('admin/cms/history*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-timeline text-success me-1"></i> Sejarah
                </a>

                <a href="<?= base_url('admin/cms/structure') ?>" class="sidebar-link <?= (url_is('admin/cms/structure*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-sitemap text-danger me-1"></i> Bagan Pengurus
                </a>

                <a href="<?= base_url('admin/cms/faqs') ?>" class="sidebar-link <?= (url_is('admin/cms/faqs*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-circle-question text-warning me-1"></i> Kelola FAQ
                </a>

                <a href="<?= base_url('admin/cms/messages') ?>" class="sidebar-link <?= (url_is('admin/cms/messages*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-inbox text-info me-1"></i> Pesan Kontak
                </a>

                <?php if (in_array(session()->get('role_slug'), ['superadmin', 'pembina'])): ?>
                    <div class="px-2 mt-3 mb-2">
                        <span class="text-uppercase text-secondary font-monospace fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.1em;">SISTEM</span>
                    </div>

                    <a href="<?= base_url('admin/audit-logs') ?>" class="sidebar-link <?= (url_is('admin/audit-logs*')) ? 'active' : '' ?>">
                        <i class="fa-solid fa-shield-halved text-danger me-1"></i> Audit Logs
                    </a>

                    <a href="<?= base_url('admin/settings') ?>" class="sidebar-link <?= (url_is('admin/settings*')) ? 'active' : '' ?>">
                        <i class="fa-solid fa-sliders text-secondary me-1"></i> Pengaturan
                    </a>
                <?php endif; ?>

            <?php else: ?>
                <!-- Member Links -->
                <a href="<?= base_url('attendance/scan') ?>" class="sidebar-link <?= (url_is('attendance/scan*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-qrcode text-danger me-1"></i> Presensi QR / PIN
                </a>

                <a href="<?= base_url('attendance/history') ?>" class="sidebar-link <?= (url_is('attendance/history*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-clock-rotate-left text-warning me-1"></i> Riwayat Presensi
                </a>

                <a href="<?= base_url('member/tasks') ?>" class="sidebar-link <?= (url_is('member/tasks*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-file-arrow-up text-info me-1"></i> Tugas Saya
                </a>

                <a href="<?= base_url('member/learning') ?>" class="sidebar-link <?= (url_is('member/learning*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-book-bookmark text-danger me-1"></i> Materi Pembelajaran
                </a>
            <?php endif; ?>

            <div class="px-2 mt-3 mb-2">
                <span class="text-uppercase text-secondary font-monospace fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.1em;">AKUN</span>
            </div>

            <a href="<?= base_url('profile') ?>" class="sidebar-link <?= (url_is('profile*')) ? 'active' : '' ?>">
                <i class="fa-solid fa-id-card text-light me-1"></i> Profil & QR Member
            </a>

            <a href="<?= base_url('/') ?>" target="_blank" class="sidebar-link">
                <i class="fa-solid fa-globe text-secondary me-1"></i> Website Publik <i class="fa-solid fa-arrow-up-right-from-square ms-auto small"></i>
            </a>
        </nav>

        <!-- User Profile Card Sidebar Footer -->
        <div class="pt-3 mt-auto border-top border-secondary border-opacity-25">
            <div class="d-flex align-items-center gap-2 p-2 rounded-3 bg-dark">
                <?php if (session()->get('avatar')): ?>
                    <img src="<?= base_url(session()->get('avatar')) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-danger border-opacity-50" style="width: 36px; height: 36px; min-width: 36px;">
                <?php else: ?>
                    <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px; min-width: 36px;">
                        <?= strtoupper(substr(session()->get('full_name') ?? 'U', 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div class="d-flex flex-column text-truncate">
                    <span class="fw-semibold text-white small text-truncate"><?= esc(session()->get('full_name')) ?></span>
                    <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 py-0 px-2 rounded-pill font-monospace" style="font-size: 0.65rem; width: fit-content;">
                        <?= esc(session()->get('role_name')) ?>
                    </span>
                </div>
                <a href="<?= base_url('logout') ?>" class="btn btn-sm text-secondary hover-white ms-auto" title="Keluar">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-grow-1 d-flex flex-column min-vh-100 w-100" style="background-color: var(--bg-main); min-width: 0;">
        <!-- Top Bar -->
        <header class="navbar navbar-expand border-bottom border-secondary border-opacity-25 px-3 px-lg-4 py-3" style="background: rgba(18, 18, 24, 0.85); backdrop-filter: blur(10px);">
            <div class="container-fluid p-0 d-flex align-items-center justify-content-between">
                
                <div class="d-flex align-items-center gap-2">
                    <!-- Mobile Hamburger Offcanvas Button -->
                    <button class="btn btn-saas-dark d-lg-none p-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar" aria-label="Toggle navigation">
                        <i class="fa-solid fa-bars fs-5"></i>
                    </button>

                    <div>
                        <!-- Breadcrumb Navigation -->
                        <ol class="breadcrumb-saas m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>" class="text-secondary">Dashboard</a></li>
                            <?php
                                $uri = current_url(true);
                                $segments = $uri->getSegments();
                                $totalSegments = count($segments);
                                foreach ($segments as $index => $segment):
                                    if ($segment === 'index.php' || $segment === 'dashboard') continue;
                                    $isLast = ($index === $totalSegments - 1);
                                    $segmentTitle = ucfirst(str_replace('-', ' ', $segment));
                            ?>
                                <?php if ($isLast): ?>
                                    <li class="breadcrumb-item active" aria-current="page"><?= esc($segmentTitle) ?></li>
                                <?php else: ?>
                                    <li class="breadcrumb-item"><span class="text-secondary"><?= esc($segmentTitle) ?></span></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ol>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="<?= base_url('/') ?>" class="btn btn-sm btn-saas-dark d-none d-sm-inline-flex">
                        <i class="fa-solid fa-arrow-left me-1"></i> Website Utama
                    </a>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-3 p-md-4 flex-grow-1" style="min-width: 0;">
            <?= $this->renderSection('content') ?>
        </main>

        <footer class="p-3 border-top border-secondary border-opacity-25 text-center text-secondary small">
            MMC SMAN 1 Tamansari Platform &copy; <?= date('Y') ?>. Modern Dark SaaS Architecture.
        </footer>
    </div>

    <!-- Local Vendor Scripts -->
    <script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap5.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/sweetalert2/sweetalert2.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/qrious/qrious.min.js') ?>"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>

    <!-- SweetAlert2 Toast & Auto-Close Mobile Nav Handler -->
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

            <?php if (session()->getFlashdata('warning')): ?>
                Toast.fire({
                    icon: 'warning',
                    title: '<?= session()->getFlashdata('warning') ?>'
                });
            <?php endif; ?>

            // Auto close offcanvas sidebar on mobile when link is clicked
            $('.sidebar-link').on('click', function() {
                if ($(window).width() < 992) {
                    const bsOffcanvas = bootstrap.Offcanvas.getInstance('#adminSidebar');
                    if (bsOffcanvas) {
                        bsOffcanvas.hide();
                    }
                }
            });
        });
    </script>

    <?= $this->renderSection('scripts') ?>

    <!-- PWA Install Banner (Android / Chrome / Edge) -->
    <div id="pwaInstallBanner" class="position-fixed bottom-0 start-50 translate-middle-x mb-3 p-3 rounded-3 bg-dark border border-danger shadow-lg text-white style-tiny z-3" style="display: none; max-width: 480px; width: 92%;">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2">
                <img src="<?= base_url('assets/logo-mm-2023.png') ?>" alt="MMC Logo" style="height: 32px;" class="bg-white p-1 rounded-2">
                <div>
                    <div class="fw-bold">Install MMC Platform</div>
                    <div class="text-secondary style-tiny">Akses cepat seperti aplikasi native di smartphone.</div>
                </div>
            </div>
            <div class="d-flex gap-1 text-nowrap">
                <button class="btn btn-sm btn-red px-3" onclick="triggerPwaInstall()">Install</button>
                <button class="btn btn-sm btn-outline-light px-2" onclick="dismissPwaInstallBanner()">Nanti</button>
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
            <button class="btn btn-sm btn-outline-light px-2 text-nowrap" onclick="dismissPwaInstallBanner()">Mengerti</button>
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

    <!-- PWA Service Worker Registration & Scripts -->
    <?= pwa_sw_script() ?>
</body>
</html>
