<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Multimedia Club SMAN 1 Tamansari') ?></title>
    
    <!-- Meta SEO -->
    <meta name="description" content="Official website & member platform of Multimedia Club SMAN 1 Tamansari. Photography, Videography, Graphic Design, Web Development & Broadcast.">
    <meta name="author" content="Multimedia Club SMAN 1 Tamansari">

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
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Glassmorphic Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-saas sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 font-heading" href="<?= base_url('/') ?>">
                <img src="<?= base_url('assets/logo-mm-2023.png') ?>" alt="Multimedia Club Logo" style="height: 38px;" class="rounded-2 p-1 bg-white">
                <span class="fs-5 fw-bold text-white">MMC <span class="text-danger">SMAN 1 Tamansari</span></span>
            </a>
            
            <button class="navbar-toggler border-0 p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPublic" aria-controls="navbarPublic" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarPublic">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-2">
                    <li class="nav-item"><a class="nav-link text-light px-3 <?= (url_is('/')) ? 'active fw-bold text-danger' : '' ?>" href="<?= base_url('/') ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-light px-3 <?= (url_is('about*')) ? 'active fw-bold text-danger' : '' ?>" href="<?= base_url('about') ?>">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link text-light px-3 <?= (url_is('learning-path*')) ? 'active fw-bold text-danger' : '' ?>" href="<?= base_url('learning-path') ?>">Learning Path</a></li>
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
                        <a href="<?= base_url('login') ?>" class="btn btn-outline-red px-4 flex-fill flex-lg-grow-0">Login</a>
                        <a href="<?= base_url('register') ?>" class="btn btn-red px-4 flex-fill flex-lg-grow-0">Daftar Member</a>
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
                        <img src="<?= base_url('assets/logo-mm-2023.png') ?>" alt="Multimedia Club Logo" style="height: 34px;" class="rounded-2 p-1 bg-white">
                        <span class="fs-5 fw-bold text-white font-heading">MMC SMAN 1 Tamansari</span>
                    </div>
                    <p class="text-secondary small pe-lg-5 mb-3">
                        Wadah kreativitas siswa SMAN 1 Tamansari dalam bidang videografi, fotografi, desain grafis, pemrograman web, dan penyiaran media digital.
                    </p>
                    <div class="d-flex gap-3 text-secondary">
                        <a href="#" class="text-secondary fs-5"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-secondary fs-5"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="text-secondary fs-5"><i class="fab fa-tiktok"></i></a>
                        <a href="#" class="text-secondary fs-5"><i class="fab fa-github"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <h6 class="text-white font-heading mb-3">Navigasi Cepat</h6>
                    <ul class="list-unstyled text-secondary small d-flex flex-column gap-2">
                        <li><a href="<?= base_url('about') ?>" class="text-secondary">Tentang Klub</a></li>
                        <li><a href="<?= base_url('learning-path') ?>" class="text-secondary">Divisi & Kurikulum</a></li>
                        <li><a href="<?= base_url('portfolio') ?>" class="text-secondary">Portofolio Karya</a></li>
                        <li><a href="<?= base_url('achievements') ?>" class="text-secondary">Prestasi & Tim Juara</a></li>
                        <li><a href="<?= base_url('gallery') ?>" class="text-secondary">Galeri Kegiatan</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-4">
                    <h6 class="text-white font-heading mb-3">Kontak & Lokasi</h6>
                    <p class="text-secondary small mb-1"><i class="fa-solid fa-location-dot me-2 text-danger"></i> SMAN 1 Tamansari, Kab. Bogor</p>
                    <p class="text-secondary small mb-1"><i class="fa-solid fa-envelope me-2 text-danger"></i> multimedia@sman1tamansari.sch.id</p>
                    <p class="text-secondary small mb-0"><i class="fa-solid fa-phone me-2 text-danger"></i> +62 812-3456-7890</p>
                </div>
            </div>
            <div class="border-top border-secondary border-opacity-10 mt-4 pt-4 text-center text-secondary small">
                &copy; <?= date('Y') ?> Multimedia Club SMAN 1 Tamansari. Built with CodeIgniter 4 & Dark SaaS UI.
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
</body>
</html>
