<?php helper(['pwa', 'setting']); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/pwa-install-banner.css') ?>">

    <!-- Early Anti-Flicker Theme Detection -->
    <script>
        (function() {
            const storedTheme = localStorage.getItem('theme-mode') || 'dark';
            document.documentElement.setAttribute('data-bs-theme', storedTheme);
        })();
    </script>

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

        <a href="<?= base_url('dashboard') ?>" class="d-flex align-items-center gap-2 mb-3 text-white text-decoration-none px-2 font-heading flex-shrink-0">
            <img src="<?= (strpos(get_setting('site_logo', 'assets/logo-mm-2023.png'), 'http') === 0) ? esc(get_setting('site_logo', 'assets/logo-mm-2023.png')) : base_url(get_setting('site_logo', 'assets/logo-mm-2023.png')) ?>" alt="MMC Logo" style="height: 38px;" class="rounded-2 p-1 bg-white">
            <div class="d-flex flex-column">
                <span class="fs-6 fw-bold lh-1 text-white">MMC <span class="text-danger">Platform</span></span>
                <span class="text-secondary small font-monospace" style="font-size: 0.7rem;">SMAN 1 Tamansari</span>
            </div>
        </a>

        <!-- Scrollable Navigation Items -->
        <div class="sidebar-nav-scroll pe-1 my-2">
            <div class="px-2 mb-2">
                <span class="text-uppercase text-secondary font-monospace fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.1em;">MENU UTAMA</span>
            </div>

            <nav class="nav flex-column">
                <a href="<?= base_url('dashboard') ?>" class="sidebar-link <?= (url_is('dashboard*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-gauge text-danger me-1"></i> Dashboard
                </a>

                <?php if (in_array(session()->get('role_slug'), ['superadmin', 'pembina', 'bph'])): ?>
                    <!-- Admin CMS Links -->
                    <a href="<?= base_url('admin/meetings') ?>" class="sidebar-link <?= (url_is('admin/meetings*')) ? 'active' : '' ?>">
                        <i class="fa-solid fa-calendar-check text-info me-1"></i> Pertemuan
                    </a>

                    <a href="<?= base_url('admin/attendance') ?>" class="sidebar-link <?= (url_is('admin/attendance*')) ? 'active' : '' ?>">
                        <i class="fa-solid fa-qrcode text-warning me-1"></i> Rekap Presensi Absensi
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

                    <?php if (session()->get('role_slug') !== 'superadmin'): ?>
                        <a href="<?= base_url('attendance/history') ?>" class="sidebar-link <?= (url_is('attendance/history*')) ? 'active' : '' ?>">
                            <i class="fa-solid fa-clock-rotate-left text-warning me-1"></i> Riwayat Presensi Saya
                        </a>
                    <?php endif; ?>

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

                    <a href="<?= base_url('admin/informasi') ?>" class="sidebar-link <?= (url_is('admin/informasi*')) ? 'active' : '' ?>">
                        <i class="fa-solid fa-bullhorn text-warning me-1"></i> Kelola Informasi
                    </a>

                    <a href="<?= base_url('admin/cms/messages') ?>" class="sidebar-link <?= (url_is('admin/cms/messages*')) ? 'active' : '' ?>">
                        <i class="fa-solid fa-comments text-info me-1"></i> Kritik & Saran
                    </a>

                    <?php if (in_array(session()->get('role_slug'), ['superadmin', 'pembina', 'bph'])): ?>
                        <div class="px-2 mt-3 mb-2">
                            <span class="text-uppercase text-secondary font-monospace fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.1em;">SISTEM</span>
                        </div>

                        <a href="<?= base_url('admin/audit-logs') ?>" class="sidebar-link <?= (url_is('admin/audit-logs*')) ? 'active' : '' ?>">
                            <i class="fa-solid fa-shield-halved text-danger me-1"></i> Audit Logs
                        </a>

                        <?php if (session()->get('role_slug') === 'superadmin'): ?>
                            <a href="<?= base_url('admin/storage') ?>" class="sidebar-link <?= (url_is('admin/storage*')) ? 'active' : '' ?>">
                                <i class="fa-solid fa-hard-drive text-warning me-1"></i> Asset & Storage Disk
                            </a>
                        <?php endif; ?>

                        <a href="<?= base_url('admin/settings') ?>" class="sidebar-link <?= (url_is('admin/settings*')) ? 'active' : '' ?>">
                            <i class="fa-solid fa-sliders text-secondary me-1"></i> Pengaturan
                        </a>
                    <?php endif; ?>

                <?php else: ?>
                    <!-- Member Links -->
                    <?php
                        $navDisabledPagesRaw = get_setting('disabled_member_pages', '[]');
                        $navDisabledPages    = json_decode($navDisabledPagesRaw, true) ?: [];
                    ?>
                    <a href="<?= base_url('attendance/scan') ?>" class="sidebar-link <?= (url_is('attendance/scan*')) ? 'active' : '' ?>">
                        <i class="fa-solid fa-qrcode text-danger me-1"></i> Presensi QR / PIN
                        <?php if (in_array('attendance_scan', $navDisabledPages)): ?>
                            <span class="badge bg-secondary bg-opacity-25 text-secondary border border-secondary style-tiny ms-auto">Off</span>
                        <?php endif; ?>
                    </a>

                    <a href="<?= base_url('attendance/history') ?>" class="sidebar-link <?= (url_is('attendance/history*')) ? 'active' : '' ?>">
                        <i class="fa-solid fa-clock-rotate-left text-warning me-1"></i> Riwayat Presensi
                        <?php if (in_array('attendance_history', $navDisabledPages)): ?>
                            <span class="badge bg-secondary bg-opacity-25 text-secondary border border-secondary style-tiny ms-auto">Off</span>
                        <?php endif; ?>
                    </a>

                    <a href="<?= base_url('member/tasks') ?>" class="sidebar-link <?= (url_is('member/tasks*')) ? 'active' : '' ?>">
                        <i class="fa-solid fa-file-arrow-up text-info me-1"></i> Tugas Saya
                        <?php if (in_array('tasks', $navDisabledPages)): ?>
                            <span class="badge bg-secondary bg-opacity-25 text-secondary border border-secondary style-tiny ms-auto">Off</span>
                        <?php endif; ?>
                    </a>

                    <a href="<?= base_url('member/learning') ?>" class="sidebar-link <?= (url_is('member/learning*')) ? 'active' : '' ?>">
                        <i class="fa-solid fa-book-bookmark text-danger me-1"></i> Materi Pembelajaran
                        <?php if (in_array('learning', $navDisabledPages)): ?>
                            <span class="badge bg-secondary bg-opacity-25 text-secondary border border-secondary style-tiny ms-auto">Off</span>
                        <?php endif; ?>
                    </a>

                    <a href="<?= base_url('admin/cms/messages') ?>" class="sidebar-link <?= (url_is('admin/cms/messages*')) ? 'active' : '' ?>">
                        <i class="fa-solid fa-comments text-info me-1"></i> Kritik & Saran
                        <?php if (in_array('messages', $navDisabledPages)): ?>
                            <span class="badge bg-secondary bg-opacity-25 text-secondary border border-secondary style-tiny ms-auto">Off</span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>

                <div class="px-2 mt-3 mb-2">
                    <span class="text-uppercase text-secondary font-monospace fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.1em;">KOMUNIKASI & AKUN</span>
                </div>

                <?php
                    $navNotifModel = new \App\Models\NotificationModel();
                    $navChatModel  = new \App\Models\ChatMessageModel();
                    $navUserId     = session()->get('user_id');
                    $navUnread     = $navUserId ? $navNotifModel->getUnreadCount($navUserId) : 0;
                    $navNotifs     = $navUserId ? $navNotifModel->getUserNotifications($navUserId, 'all', 6) : [];

                    $navUnreadChat = 0;
                    if ($navUserId) {
                        $navUserConvs = array_column((new \App\Models\ChatParticipantModel())->where('user_id', $navUserId)->findAll(), 'conversation_id');
                        if (!empty($navUserConvs)) {
                            $navUnreadChat = $navChatModel->whereIn('conversation_id', $navUserConvs)
                                                          ->where('sender_id !=', $navUserId)
                                                          ->where('is_read', 0)
                                                          ->countAllResults();
                        }
                    }
                ?>

                <a href="<?= base_url('informasi') ?>" class="sidebar-link <?= (url_is('informasi*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-circle-info text-warning me-1"></i> Informasi
                </a>

                <a href="<?= base_url('feed') ?>" class="sidebar-link <?= (url_is('feed*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-square-rss text-info me-1"></i> Beranda MM
                </a>

                <a href="<?= base_url('inbox') ?>" class="sidebar-link <?= (url_is('inbox*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-comments text-danger me-1"></i> Inbox Obrolan
                    <?php if ($navUnreadChat > 0): ?>
                        <span class="badge bg-danger ms-auto style-tiny"><?= $navUnreadChat > 99 ? '99+' : $navUnreadChat ?></span>
                    <?php endif; ?>
                </a>

                <a href="<?= base_url('notifications') ?>" class="sidebar-link <?= (url_is('notifications*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-bell text-warning me-1"></i> Notifikasi
                    <?php if ($navUnread > 0): ?>
                        <span class="badge bg-danger ms-auto style-tiny"><?= $navUnread > 99 ? '99+' : $navUnread ?></span>
                    <?php endif; ?>
                </a>

                <a href="<?= base_url('profile') ?>" class="sidebar-link <?= (url_is('profile*')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-id-card text-light me-1"></i> Profil & QR
                </a>

                <a href="<?= base_url('/') ?>" target="_blank" class="sidebar-link">
                    <i class="fa-solid fa-globe text-secondary me-1"></i> Website Publik <i class="fa-solid fa-arrow-up-right-from-square ms-auto small"></i>
                </a>
            </nav>
        </div>

        <!-- User Profile Card Sidebar Footer (Interactive Dropdown Menu) -->
        <div class="pt-3 mt-auto border-top border-secondary border-opacity-25 flex-shrink-0">
            <div class="dropup position-relative">
                <div class="d-flex align-items-center gap-2 p-2 rounded-3 bg-dark cursor-pointer user-select-none hover-bg-body-secondary transition-all" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                    <?php if (session()->get('avatar')): ?>
                        <img src="<?= base_url(session()->get('avatar')) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-danger border-opacity-50" style="width: 36px; height: 36px; min-width: 36px;">
                    <?php else: ?>
                        <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px; min-width: 36px;">
                            <?= strtoupper(substr(session()->get('full_name') ?: 'U', 0, 1)) ?>
                    </div>
                    <?php endif; ?>
                    <div class="d-flex flex-column text-truncate overflow-hidden" style="min-width: 0; flex: 1 1 0%;">
                        <span class="fw-semibold text-white small text-truncate"><?= esc(session()->get('full_name')) ?></span>
                        <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 py-0 px-2 rounded-pill font-monospace" style="font-size: 0.65rem; width: fit-content;">
                            <?= esc(session()->get('role_name')) ?>
                        </span>
                    </div>
                    <i class="fa-solid fa-chevron-up text-secondary style-tiny ms-auto opacity-75"></i>
                </div>

                <!-- Dropdown Menu Options -->
                <ul class="dropdown-menu dropdown-menu-dark w-100 shadow-lg border border-secondary border-opacity-50 p-1.5 style-tiny mb-1">
                    <li>
                        <a class="dropdown-item py-2 rounded-2 d-flex align-items-center gap-2" href="<?= base_url('profile') ?>">
                            <i class="fa-solid fa-id-card text-info"></i>
                            <div>
                                <span class="fw-bold d-block">Lihat Profil</span>
                                <small class="text-secondary style-tiny d-block opacity-75">Profile saya</small>
                            </div>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider border-secondary border-opacity-25 my-1"></li>
                    <li>
                        <a class="dropdown-item py-2 rounded-2 d-flex align-items-center gap-2 text-danger" href="<?= base_url('logout') ?>" onclick="return confirm('Apakah Anda yakin ingin keluar dari akun?')">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <div>
                                <span class="fw-bold d-block">Keluar (Logout)</span>
                                <small class="text-danger opacity-75 style-tiny d-block">Keluar dari sesi aplikasi</small>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-grow-1 d-flex flex-column min-vh-100 w-100" style="background-color: var(--bg-main); min-width: 0;">
        <!-- Top Bar -->
        <header class="navbar navbar-expand border-bottom border-secondary border-opacity-25 px-3 px-lg-4 admin-topbar" style="position: sticky; top: 0; z-index: 1050; backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); background: rgba(13,13,18,0.96); overflow: visible;">
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
                    <!-- Notifications Bell Dropdown -->
                    <div class="dropdown">
                        <button type="button" class="btn btn-sm btn-saas-dark p-0 rounded-circle border border-secondary border-opacity-25 d-flex align-items-center justify-content-center flex-shrink-0 position-relative" style="width: 36px; height: 36px; min-height: 36px; overflow: visible;" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false" title="Notifikasi System">
                            <i class="fa-solid fa-bell text-warning small"></i>
                            <?php if ($navUnread > 0): ?>
                                <span class="position-absolute badge rounded-pill bg-danger border border-dark" style="font-size: 0.6rem; padding: 0.2em 0.4em; top: -5px; right: -6px; min-width: 18px; line-height: 1.2; z-index: 10;">
                                    <?= $navUnread > 99 ? '99+' : $navUnread ?>
                                </span>
                            <?php endif; ?>
                        </button>

                        <div class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow-lg border border-secondary border-opacity-50 p-0 notif-dropdown-menu">
                            <div class="p-3 border-bottom border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
                                <h6 class="m-0 text-white font-heading style-tiny fw-bold">
                                    <i class="fa-solid fa-bell text-warning me-1"></i> Notifikasi System
                                </h6>
                                <?php if ($navUnread > 0): ?>
                                    <a href="<?= base_url('notifications/mark-all-read') ?>" class="style-tiny text-info text-decoration-none">Tandai dibaca</a>
                                <?php endif; ?>
                            </div>

                            <div style="max-height: 300px; overflow-y: auto;">
                                <?php if (empty($navNotifs)): ?>
                                    <div class="p-3 text-center text-secondary style-tiny">Belum ada notifikasi.</div>
                                <?php else: ?>
                                    <?php foreach ($navNotifs as $nn): ?>
                                        <?php
                                            $nnIcon = 'fa-solid fa-bell text-secondary';
                                            if ($nn['type'] === 'task') $nnIcon = 'fa-solid fa-list-check text-warning';
                                            elseif ($nn['type'] === 'profile') $nnIcon = 'fa-solid fa-user-gear text-info';
                                            elseif ($nn['type'] === 'feedback') $nnIcon = 'fa-solid fa-comments text-danger';
                                            elseif ($nn['type'] === 'attendance') $nnIcon = 'fa-solid fa-qrcode text-success';
                                            elseif ($nn['type'] === 'information') $nnIcon = 'fa-solid fa-bullhorn text-warning';
                                        ?>
                                        <a href="<?= !empty($nn['link']) ? base_url('notifications/mark-read/' . $nn['id']) : base_url('notifications') ?>" class="dropdown-item py-2 px-3 border-bottom border-secondary border-opacity-10 d-flex align-items-start gap-2 <?= empty($nn['is_read']) ? 'bg-dark bg-opacity-75' : '' ?>">
                                            <i class="<?= $nnIcon ?> style-tiny mt-1 flex-shrink-0"></i>
                                            <div class="text-wrap" style="min-width: 0;">
                                                <div class="text-white style-tiny fw-bold text-truncate"><?= esc($nn['title']) ?></div>
                                                <div class="text-secondary style-tiny text-truncate" style="max-width: 230px;"><?= esc($nn['message']) ?></div>
                                                <small class="text-secondary font-monospace style-tiny opacity-75" style="font-size: 0.65rem;"><?= date('H:i, d M Y', strtotime($nn['created_at'])) ?></small>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <div class="p-2 border-top border-secondary border-opacity-25 text-center bg-black rounded-bottom">
                                <a href="<?= base_url('notifications') ?>" class="style-tiny text-danger text-decoration-none fw-bold">
                                    Lihat Semua Notifikasi <i class="fa-solid fa-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="adminThemeToggleBtn" onclick="toggleThemeMode()" class="btn btn-sm btn-saas-dark p-0 rounded-circle border border-secondary border-opacity-25 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; min-height: 36px;" title="Ubah Mode Terang / Gelap">
                        <i class="fa-solid fa-moon text-warning small" id="adminThemeToggleIcon"></i>
                    </button>
                    <a href="<?= base_url('/') ?>" class="btn btn-sm btn-saas-dark d-none d-sm-inline-flex">
                        <i class="fa-solid fa-arrow-left me-1"></i> Website Utama
                    </a>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-3 p-md-4 flex-grow-1" style="min-width: 0;">
            <?php
                $lockMemberAct = get_setting('lock_member_activities', '0');
                $userRoleSlug  = session()->get('role_slug');
            ?>
            <?php if ($lockMemberAct === '1' && !in_array($userRoleSlug, ['superadmin', 'pembina', 'bph'])): ?>
                <div class="alert alert-warning border border-warning border-opacity-50 bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-between p-3 mb-4 rounded-3 shadow-sm" role="alert">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-2 bg-warning bg-opacity-25 rounded-circle text-warning flex-shrink-0">
                            <i class="fa-solid fa-lock fs-5"></i>
                        </div>
                        <div>
                            <strong class="font-heading d-block style-tiny">Aktivitas Anggota Sedang Dinonaktifkan oleh Pengurus (Freeze Mode)</strong>
                            <span class="style-tiny opacity-90 d-block">
                                Saat ini seluruh pengiriman presensi, tugas, posting beranda, komentar, dan obrolan anggota dibekukan sementara oleh Pengurus / Admin. Anda tetap dapat membaca materi dan informasi.
                            </span>
                        </div>
                    </div>
                    <span class="badge bg-warning text-dark font-monospace style-tiny px-2.5 py-1 rounded-pill flex-shrink-0 d-none d-md-inline-block">READ-ONLY</span>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </main>

        <footer class="p-3 border-top border-secondary border-opacity-25 text-center text-secondary small pb-5 pb-lg-3">
            MMC SMAN 1 Tamansari Platform &copy; <?= date('Y') ?>.
        </footer>
    </div>

    <!-- Floating Mobile Bottom Navigation Bar (Sleek Modern SaaS UI) -->
    <?php
        $roleSlug = session()->get('role_slug');
        $isAdminRole = in_array($roleSlug, ['superadmin', 'pembina', 'bph']);
        
        $urlDashboard  = base_url('dashboard');
        $urlAttendance = $isAdminRole ? base_url('admin/attendance') : base_url('attendance/scan');
        $urlTasks      = $isAdminRole ? base_url('admin/tasks') : base_url('member/tasks');
        $urlLearning   = $isAdminRole ? base_url('admin/learning') : base_url('member/learning');
        $urlFeed       = base_url('feed');
        $urlProfile    = base_url('profile');
    ?>
    <!-- Floating Restore Button (Hanya Muncul Saat Bottom Nav Di-Minimize) -->
    <button type="button" id="btnRestoreBottomNav" onclick="toggleBottomNavMinimize(false)" class="btn btn-danger rounded-circle shadow-lg d-none align-items-center justify-content-center border border-white border-opacity-50 d-lg-none" style="width: 44px; height: 44px; position: fixed; bottom: calc(1rem + env(safe-area-inset-bottom, 0px)); right: 1rem; z-index: 1070; pointer-events: auto;" title="Buka Menu Navigasi Bawah">
        <i class="fa-solid fa-compass fs-5"></i>
    </button>

    <div class="mobile-bottom-nav d-lg-none" id="mobileBottomNavWrapper">
        <div class="mobile-bottom-nav-inner position-relative">
            <!-- Toggle Minimize Button (X) -->
            <button type="button" onclick="toggleBottomNavMinimize(true)" class="btn-close-nav-mini" title="Sembunyikan Navigasi Bawah">
                <i class="fa-solid fa-chevron-down"></i>
            </button>

            <a href="<?= $urlDashboard ?>" class="bottom-nav-item <?= url_is('dashboard*') ? 'active' : '' ?>">
                <div class="bottom-nav-icon p-05 d-flex align-items-center justify-content-center">
                    <img src="<?= (strpos(get_setting('site_logo', 'assets/logo-mm-2023.png'), 'http') === 0) ? esc(get_setting('site_logo', 'assets/logo-mm-2023.png')) : base_url(get_setting('site_logo', 'assets/logo-mm-2023.png')) ?>" alt="MMC Logo" style="height: 20px; width: 20px; object-fit: contain;" class="rounded-1 bg-white p-0.5">
                </div>
                <span class="bottom-nav-label">Dashboard</span>
            </a>

            <a href="<?= $urlAttendance ?>" class="bottom-nav-item <?= (url_is('attendance*') || url_is('admin/attendance*')) ? 'active' : '' ?>">
                <div class="bottom-nav-icon"><i class="fa-solid fa-qrcode"></i></div>
                <span class="bottom-nav-label">Absensi</span>
            </a>

            <a href="<?= $urlTasks ?>" class="bottom-nav-item <?= (url_is('member/tasks*') || url_is('admin/tasks*')) ? 'active' : '' ?>">
                <div class="bottom-nav-icon"><i class="fa-solid fa-list-check"></i></div>
                <span class="bottom-nav-label">Tugas</span>
            </a>

            <a href="<?= $urlLearning ?>" class="bottom-nav-item <?= (url_is('member/learning*') || url_is('admin/learning*')) ? 'active' : '' ?>">
                <div class="bottom-nav-icon"><i class="fa-solid fa-book-bookmark"></i></div>
                <span class="bottom-nav-label">Materi</span>
            </a>

            <a href="<?= $urlFeed ?>" class="bottom-nav-item <?= url_is('feed*') ? 'active' : '' ?>">
                <div class="bottom-nav-icon"><i class="fa-solid fa-square-rss"></i></div>
                <span class="bottom-nav-label">Beranda</span>
            </a>

            <a href="<?= $urlProfile ?>" class="bottom-nav-item <?= url_is('profile*') ? 'active' : '' ?>">
                <div class="bottom-nav-icon"><i class="fa-solid fa-user"></i></div>
                <span class="bottom-nav-label">Profil</span>
            </a>
        </div>
    </div>

    <!-- Local Vendor Scripts -->
    <script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap5.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/sweetalert2/sweetalert2.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/qrious/qrious.min.js') ?>"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- SweetAlert2 Toast & Auto-Close Mobile Nav Handler -->
    <script>
        $(document).ready(function() {
            const isMobile = window.innerWidth < 768;
            const Toast = Swal.mixin({
                toast: true,
                position: isMobile ? 'top' : 'top-end',
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

            // Smart Scroll Topbar Auto Hide / Show on Mobile
            let lastScrollTop = 0;
            const topbar = document.querySelector('.admin-topbar');

            window.addEventListener('scroll', function() {
                if (window.innerWidth >= 992 || !topbar) return;

                let st = window.pageYOffset || document.documentElement.scrollTop;
                
                // Kalau di bagian paling atas halaman (st <= 20px)
                if (st <= 20) {
                    topbar.classList.remove('nav-hidden');
                    topbar.classList.add('nav-visible');
                    lastScrollTop = st;
                    return;
                }

                // Scroll Down -> Sembunyikan Topbar
                if (st > lastScrollTop && st > 60) {
                    topbar.classList.remove('nav-visible');
                    topbar.classList.add('nav-hidden');
                } 
                // Scroll Up -> Tampilkan Topbar
                else if (st < lastScrollTop) {
                    topbar.classList.remove('nav-hidden');
                    topbar.classList.add('nav-visible');
                }
                
                lastScrollTop = st <= 0 ? 0 : st;
            }, { passive: true });

            <?php if (session()->getFlashdata('warning')): ?>
                Toast.fire({
                    icon: 'warning',
                    title: '<?= session()->getFlashdata('warning') ?>'
                });
            <?php endif; ?>

            // Toggle Hide / Restore Bottom Nav Bar (with localStorage state persistence)
            window.toggleBottomNavMinimize = function(isHide) {
                const navWrapper = document.getElementById('mobileBottomNavWrapper');
                const btnRestore = document.getElementById('btnRestoreBottomNav');
                
                if (isHide) {
                    if (navWrapper) navWrapper.classList.add('d-none');
                    if (btnRestore) {
                        btnRestore.classList.remove('d-none');
                        btnRestore.classList.add('d-flex');
                    }
                    localStorage.setItem('bottomNavHidden', 'true');
                } else {
                    if (navWrapper) navWrapper.classList.remove('d-none');
                    if (btnRestore) {
                        btnRestore.classList.remove('d-flex');
                        btnRestore.classList.add('d-none');
                    }
                    localStorage.removeItem('bottomNavHidden');
                }
            };

            // Restore state from localStorage on page load
            if (localStorage.getItem('bottomNavHidden') === 'true') {
                window.toggleBottomNavMinimize(true);
            }

            // Toggle Mobile Bottom Nav Visibility when Offcanvas Sidebar Opens / Closes
            const sidebarOffcanvas = document.getElementById('adminSidebar');
            const bottomNavWrapper = document.getElementById('mobileBottomNavWrapper');
            const btnRestoreNav = document.getElementById('btnRestoreBottomNav');
            
            if (sidebarOffcanvas) {
                sidebarOffcanvas.addEventListener('show.bs.offcanvas', function () {
                    if (bottomNavWrapper) bottomNavWrapper.style.setProperty('display', 'none', 'important');
                    if (btnRestoreNav) btnRestoreNav.style.setProperty('display', 'none', 'important');
                });
                sidebarOffcanvas.addEventListener('hidden.bs.offcanvas', function () {
                    const isHiddenByUser = localStorage.getItem('bottomNavHidden') === 'true';
                    if (isHiddenByUser) {
                        if (btnRestoreNav) btnRestoreNav.style.removeProperty('display');
                    } else {
                        if (bottomNavWrapper) bottomNavWrapper.style.removeProperty('display');
                    }
                });
            }

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

    <!-- PWA Service Worker Registration & Scripts -->
    <?= pwa_sw_script() ?>
    <script src="<?= base_url('assets/js/pwa-install-banner.js') ?>"></script>

    <script>
        function updateAdminThemeIcon(theme) {
            const icon = document.getElementById('adminThemeToggleIcon');
            const btn = document.getElementById('adminThemeToggleBtn');
            if (!icon) return;
            if (theme === 'light') {
                icon.className = 'fa-solid fa-sun text-warning';
                if (btn) btn.title = 'Ubah ke Mode Gelap';
            } else {
                icon.className = 'fa-solid fa-moon text-light';
                if (btn) btn.title = 'Ubah ke Mode Terang';
            }
        }

        function toggleThemeMode() {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'dark';
            const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', nextTheme);
            localStorage.setItem('theme-mode', nextTheme);
            updateAdminThemeIcon(nextTheme);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'dark';
            updateAdminThemeIcon(currentTheme);
        });
    </script>
    <!-- Global Information Popup Modal (Non-blocking Popup System) -->
    <?php
        $globalUserId = session()->get('user_id');
        $globalUnreadPopups = [];
        if ($globalUserId) {
            $globalInfoModel = new \App\Models\InformationModel();
            $globalUnreadPopups = $globalInfoModel->getUnreadPopupsForUser((int)$globalUserId);
        }
    ?>
    <?php if (!empty($globalUnreadPopups)): ?>
        <?php $popupItem = $globalUnreadPopups[0]; ?>
        <div class="modal fade" id="globalInformationPopupModal" tabindex="-1" aria-labelledby="globalInformationPopupModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content bg-body text-body border border-danger border-opacity-50 shadow-lg" style="box-shadow: 0 0 40px rgba(220, 53, 69, 0.25) !important;">
                    <div class="modal-header border-bottom border-secondary border-opacity-25 py-2.5 px-3 bg-danger bg-opacity-10">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-danger text-white font-monospace style-tiny py-1 px-2.5 rounded-pill">
                                <i class="fa-solid fa-bell me-1"></i> PENGUMUMAN PENTING
                            </span>
                            <span class="badge bg-secondary bg-opacity-25 text-light border border-secondary style-tiny rounded-pill font-monospace">
                                <?= esc($popupItem['category']) ?>
                            </span>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <h4 class="text-body font-heading fw-bold mb-2">
                            <?= esc($popupItem['title']) ?>
                        </h4>

                        <div class="d-flex align-items-center gap-3 text-secondary style-tiny font-monospace mb-3 pb-3 border-bottom border-secondary border-opacity-25 flex-wrap">
                            <div>
                                <i class="fa-solid fa-calendar-day text-danger me-1"></i>
                                <?= date('d M Y, H:i', strtotime($popupItem['date_time'])) ?> WIB
                            </div>
                            <div>
                                <i class="fa-solid fa-user-pen text-info me-1"></i>
                                <?= esc($popupItem['author_name']) ?> (<?= esc($popupItem['author_role']) ?>)
                            </div>
                        </div>

                        <div class="text-body style-tiny whitespace-pre-line" style="font-size: 0.92rem; line-height: 1.7;">
                            <?= esc($popupItem['description']) ?>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary border-opacity-25 py-2.5 justify-content-between">
                        <button type="button" class="btn btn-sm btn-saas-dark text-secondary border border-secondary border-opacity-25 rounded-pill px-4" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark me-1"></i> Tutup
                        </button>
                        <button type="button" class="btn btn-sm btn-success rounded-pill px-4" onclick="markGlobalPopupAsRead(<?= $popupItem['id'] ?>)">
                            <i class="fa-solid fa-circle-check me-1"></i> Sudah Dibaca
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const popupModalEl = document.getElementById('globalInformationPopupModal');
                if (popupModalEl) {
                    const popupModal = bootstrap.Modal.getOrCreateInstance(popupModalEl);
                    popupModal.show();
                }
            });

            function markGlobalPopupAsRead(infoId) {
                if (!infoId) return;
                fetch('<?= base_url('informasi/mark-read/') ?>' + infoId, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    const popupModalEl = document.getElementById('globalInformationPopupModal');
                    if (popupModalEl) {
                        const popupModal = bootstrap.Modal.getInstance(popupModalEl);
                        if (popupModal) popupModal.hide();
                    }
                })
                .catch(() => {
                    const popupModalEl = document.getElementById('globalInformationPopupModal');
                    if (popupModalEl) {
                        const popupModal = bootstrap.Modal.getInstance(popupModalEl);
                        if (popupModal) popupModal.hide();
                    }
                });
            }
        </script>
    <?php endif; ?>

</body>
</html>
