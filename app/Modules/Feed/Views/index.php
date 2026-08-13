<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="container-fluid p-0">

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show bg-success bg-opacity-25 border-success text-white style-tiny mb-3" role="alert">
            <i class="fa-solid fa-circle-check me-1.5"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close btn-close-white style-tiny" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show bg-danger bg-opacity-25 border-danger text-white style-tiny mb-3" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-1.5"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close btn-close-white style-tiny" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Header Banner -->
    <div class="saas-card mb-4 p-4 position-relative border border-secondary border-opacity-25 bg-body-tertiary" style="z-index: 1050;">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 position-relative style-tiny">
            <div>
                <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 py-1 px-2.5 rounded-pill font-monospace mb-2">
                    <i class="fa-solid fa-square-rss me-1"></i> Komunitas & Feed Sosial
                </span>
                <h3 class="text-body font-heading fw-bold m-0 d-flex align-items-center gap-2">
                    Beranda Club Multimedia
                </h3>
                <p class="text-secondary style-tiny m-0 mt-1 max-w-xl">
                    Bagikan pembaruan status, karya multimedia, ide, dan berinteraksi dengan sesama anggota klub.
                </p>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
                <!-- Search Member Input Box (Live Floating Dropdown) -->
                <div class="position-relative w-100" style="max-width: 320px; z-index: 1060;">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-body border-secondary border-opacity-50 text-secondary"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" id="feedSearchMemberInput" class="form-control bg-body border-secondary border-opacity-50 text-body style-tiny rounded-end-pill" placeholder="Cari anggota / pengurus..." autocomplete="off">
                    </div>
                    <!-- Live Search Results Dropdown -->
                    <div id="feedSearchDropdown" class="position-absolute top-100 start-0 end-0 mt-1 rounded-3 bg-body border border-secondary border-opacity-50 shadow-lg p-2 d-none" style="z-index: 1070; width: 100%; max-height: 360px; overflow-y: auto;">
                        <?php if (!empty($allMembers)): ?>
                            <?php foreach ($allMembers as $m): ?>
                                <?php 
                                    $isVerifiedRole = in_array(strtolower((string)$m['role_slug']), ['superadmin', 'pembina', 'bph']);
                                    $searchKeyword  = strtolower(($m['full_name'] ?? '') . ' ' . ($m['username'] ?? '') . ' ' . ($m['nis_nip'] ?? '') . ' ' . ($m['class_dept'] ?? '') . ' ' . ($m['role_name'] ?? ''));
                                ?>
                                <a href="<?= base_url('feed/user/' . $m['id']) ?>" class="feed-search-member-item d-flex align-items-center gap-2 p-2 rounded-2 text-decoration-none text-body hover-bg-body-secondary transition-all mb-1" style="min-width: 0;" data-search="<?= esc($searchKeyword) ?>">
                                    <?php if (!empty($m['avatar'])): ?>
                                        <img src="<?= base_url($m['avatar']) ?>" class="rounded-circle object-fit-cover flex-shrink-0" style="width: 32px; height: 32px;" onerror="this.onerror=null; this.src='<?= base_url('assets/logo-mm-2023.png') ?>';">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold style-tiny flex-shrink-0" style="width: 32px; height: 32px;">
                                            <?= strtoupper(substr($m['full_name'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="overflow-hidden flex-grow-1" style="min-width: 0;">
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="style-tiny fw-bold text-truncate hover-text-danger member-name"><?= esc($m['full_name']) ?></span>
                                            <?php if ($isVerifiedRole): ?>
                                                <i class="fa-solid fa-circle-check text-primary style-tiny flex-shrink-0" title="Akun Terverifikasi"></i>
                                            <?php endif; ?>
                                        </div>
                                        <small class="text-secondary style-tiny d-block opacity-75 text-truncate member-info" style="font-size: 0.65rem;">
                                            <?= esc(($m['username'] ? '@' . $m['username'] . ' • ' : '') . ($m['class_dept'] ?: $m['role_name'])) ?>
                                        </small>
                                    </div>
                                    <span class="badge bg-secondary bg-opacity-25 text-secondary style-tiny rounded-pill px-2 py-0.5 font-monospace flex-shrink-0" style="font-size: 0.60rem;">Lihat</span>
                                </a>
                            <?php endforeach; ?>
                            <div id="feedSearchNoResults" class="text-center py-3 text-secondary style-tiny d-none">
                                <i class="fa-solid fa-user-slash me-1 opacity-50"></i> Anggota tidak ditemukan.
                            </div>
                        <?php else: ?>
                            <div class="text-center py-2 text-secondary style-tiny">Belum ada data anggota.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <a href="<?= base_url('feed') ?>" class="btn btn-sm <?= $activeFilter === 'all' ? 'btn-red' : 'btn-saas-dark text-secondary' ?> px-3 rounded-pill">
                    <i class="fa-solid fa-globe me-1"></i> Semua Status
                </a>
                <a href="<?= base_url('feed?filter=following') ?>" class="btn btn-sm <?= $activeFilter === 'following' ? 'btn-red' : 'btn-saas-dark text-secondary' ?> px-3 rounded-pill">
                    <i class="fa-solid fa-user-group me-1"></i> Yang Diikuti
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        
        <!-- Left Main Feed Column -->
        <div class="col-12 col-lg-8 order-2 order-lg-1">
            
            <!-- Create Status Post Box -->
            <div class="saas-card p-3 p-md-4 mb-4 border border-secondary border-opacity-25 bg-body-tertiary">
                <form action="<?= base_url('feed/create') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="d-flex align-items-start gap-3">
                        <?php if (!empty($myProfile['user']['avatar'])): ?>
                            <img src="<?= base_url($myProfile['user']['avatar']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-danger border-opacity-50 flex-shrink-0" style="width: 46px; height: 46px;">
                        <?php else: ?>
                            <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold fs-5 flex-shrink-0" style="width: 46px; height: 46px;">
                                <?= strtoupper(substr($myProfile['user']['full_name'] ?? 'U', 0, 1)) ?>
                            </div>
                        <?php endif; ?>

                        <div class="flex-grow-1 overflow-hidden" style="min-width: 0;">
                            <div class="d-flex align-items-center gap-1.5 mb-1.5">
                                <strong class="text-body style-tiny fw-bold"><?= esc($myProfile['user']['full_name'] ?? '') ?></strong>
                                <?php if (!empty($myProfile['user']['is_verified'])): ?>
                                    <i class="fa-solid fa-circle-check text-primary style-tiny" title="Akun Pengurus / Pembina Terverifikasi"></i>
                                <?php endif; ?>
                            </div>

                            <textarea name="content" class="form-control bg-body border-secondary border-opacity-50 text-body rounded-3 p-3 style-tiny" rows="3" placeholder="Apa yang sedang Anda kerjakan atau pikirkan hari ini, <?= esc($myProfile['user']['full_name'] ?? '') ?>?" required style="resize: none;"></textarea>

                            <!-- Attachment Media Preview Box -->
                            <div id="feedMediaPreviewBox" class="d-none mt-2.5 p-2.5 rounded-3 bg-black border border-warning border-opacity-50 align-items-center justify-content-between gap-2 shadow-lg w-100" style="min-width: 0;">
                                <div class="d-flex align-items-center gap-2.5 overflow-hidden" style="min-width: 0; flex: 1 1 0%;">
                                    <div id="feedMediaThumbnailWrapper" class="flex-shrink-0"></div>
                                    <div class="overflow-hidden" style="min-width: 0; flex: 1 1 0%;">
                                        <span class="badge bg-warning text-dark font-monospace style-tiny py-0.5 px-1.5 mb-1 d-inline-block">Media Lampiran Siap Diposting</span>
                                        <span id="feedMediaFileName" class="text-white style-tiny fw-bold d-block text-truncate" style="max-width: 100%;"></span>
                                        <small id="feedMediaFileSize" class="text-secondary font-monospace style-tiny opacity-75 d-block" style="font-size: 0.65rem;"></small>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-saas-dark text-danger border border-danger border-opacity-25 p-1.5 rounded-circle flex-shrink-0 ms-auto" onclick="cancelFeedMedia()" title="Batal Lampiran">
                                    <i class="fa-solid fa-xmark fs-6"></i>
                                </button>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mt-3 flex-wrap gap-2">
                                <label class="btn btn-sm btn-saas-dark text-secondary hover-white border border-secondary border-opacity-25 px-3 rounded-pill style-tiny cursor-pointer m-0">
                                    <i class="fa-solid fa-image text-success me-1.5"></i> Lampirkan Media / Foto
                                    <input type="file" name="media" id="feedMediaInput" class="d-none" accept="image/*,video/*,.pdf,.doc,.docx" onchange="previewFeedMedia(this)">
                                </label>

                                <button type="submit" class="btn btn-red px-4 rounded-pill style-tiny fw-bold">
                                    <i class="fa-solid fa-paper-plane me-1.5"></i> Publikasikan Status
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Posts List Feed -->
            <?php if (empty($posts)): ?>
                <div class="saas-card text-center py-5 border border-secondary border-opacity-25 bg-dark">
                    <i class="fa-solid fa-newspaper display-3 text-secondary opacity-25 mb-3"></i>
                    <h5 class="text-white font-heading">Belum Ada Status Publikasi</h5>
                    <p class="text-secondary style-tiny max-w-md mx-auto">
                        <?= $activeFilter === 'following' ? 'Belum ada postingan dari anggota yang Anda ikuti. Coba ikuti lebih banyak anggota klub!' : 'Jadilah yang pertama membagikan status atau postingan aktivitas klub hari ini.' ?>
                    </p>
                </div>
            <?php else: ?>

            <!-- New Posts Notification Bar (hidden by default) -->
            <div id="feedNewPostsBar" class="d-none mb-3 p-2 rounded-3 border border-info border-opacity-50 bg-body-secondary d-flex align-items-center justify-content-between gap-2 style-tiny" style="cursor:pointer;" onclick="refreshFeed()">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-arrow-rotate-right text-info fa-spin" id="refreshSpinIcon" style="display:none;"></i>
                    <i class="fa-solid fa-circle-dot text-info" id="newPostDotIcon"></i>
                    <span id="feedNewPostsText" class="text-info fw-semibold">Ada postingan baru! Klik untuk refresh.</span>
                </div>
                <span class="badge bg-info text-dark font-monospace" id="feedNewPostsCount">0</span>
            </div>

            <div class="d-flex flex-column gap-4" id="feedPostsContainer">
                    <?php foreach ($posts as $post): ?>
                        <?= view('App\Modules\Feed\Views\_post_card', ['post' => $post]) ?>
                    <?php endforeach; ?>
            </div>

            <!-- Show More / Refresh Footer -->
            <?php if ($totalPosts > $perPage): ?>
            <div class="text-center mt-4" id="feedLoadMoreWrapper">
                <button id="feedLoadMoreBtn" class="btn btn-saas-dark border border-secondary border-opacity-50 px-5 rounded-pill style-tiny fw-semibold"
                        onclick="loadMorePosts()">
                    <i class="fa-solid fa-chevron-down me-1"></i>
                    Tampilkan Lebih Banyak
                    <span class="badge bg-secondary ms-1 font-monospace" id="feedRemainingCount"><?= $totalPosts - $perPage ?></span>
                </button>
                <div id="feedLoadMoreSpinner" class="d-none mt-2">
                    <div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>
                    <span class="text-secondary style-tiny ms-2">Memuat post...</span>
                </div>
            </div>
            <?php endif; ?>

        <?php endif; ?>

        </div><!-- end col-12 col-lg-8 (left feed column) -->

        <!-- Right Sidebar Widget Column -->
        <div class="col-12 col-lg-4 order-1 order-lg-2">
            
            <!-- My Social Profile Card -->
            <div class="saas-card p-3 p-md-4 mb-4 border border-secondary border-opacity-25 bg-body-tertiary text-center">
                <div class="position-relative d-inline-block mb-3">
                    <?php if (!empty($myProfile['user']['avatar'])): ?>
                        <img src="<?= base_url($myProfile['user']['avatar']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-danger border-opacity-50 shadow" style="width: 72px; height: 72px;">
                    <?php else: ?>
                        <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold display-6 mx-auto shadow" style="width: 72px; height: 72px;">
                            <?= strtoupper(substr($myProfile['user']['full_name'] ?? 'U', 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <h5 class="text-body font-heading m-0 fw-bold d-flex align-items-center justify-content-center gap-1.5">
                    <?= esc($myProfile['user']['full_name'] ?? '') ?>
                    <?php if (!empty($myProfile['user']['is_verified'])): ?>
                        <i class="fa-solid fa-circle-check text-primary style-tiny" title="Akun Terverifikasi (Pengurus / Pembina MMC)"></i>
                    <?php endif; ?>
                </h5>
                
                <span class="badge bg-danger bg-opacity-25 text-danger font-monospace style-tiny py-0.5 px-2 rounded-pill mt-1">
                    <?= esc($myProfile['user']['role_name'] ?? 'Anggota MMC') ?>
                </span>
                
                <small class="text-secondary style-tiny d-block mt-1"><?= esc(($myProfile['user']['class_dept'] ?? '') ?: 'Multimedia Club') ?></small>

                <!-- Social Stats Counter -->
                <div class="row g-2 mt-3 pt-3 border-top border-secondary border-opacity-25 style-tiny font-monospace">
                    <div class="col-4 cursor-pointer hover-opacity-100" onclick="showFollowsListModal(<?= session()->get('user_id') ?>, 'followers', '<?= esc(addslashes($myProfile['user']['full_name'] ?? '')) ?>')">
                        <strong class="text-body d-block fs-6"><?= $myProfile['follower_count'] ?? 0 ?></strong>
                        <span class="text-secondary opacity-75 style-tiny">Pengikut</span>
                    </div>
                    <div class="col-4 cursor-pointer hover-opacity-100" onclick="showFollowsListModal(<?= session()->get('user_id') ?>, 'following', '<?= esc(addslashes($myProfile['user']['full_name'] ?? '')) ?>')">
                        <strong class="text-body d-block fs-6"><?= $myProfile['following_count'] ?? 0 ?></strong>
                        <span class="text-secondary opacity-75 style-tiny">Diikuti</span>
                    </div>
                    <div class="col-4">
                        <strong class="text-body d-block fs-6"><?= $myProfile['posts_count'] ?? 0 ?></strong>
                        <span class="text-secondary opacity-75 style-tiny">Status</span>
                    </div>
                </div>

                <div class="mt-3 pt-3 border-top border-secondary border-opacity-25 d-flex flex-column gap-2">
                    <a href="<?= base_url('feed/user/' . session()->get('user_id')) ?>" class="btn btn-sm btn-red w-100 rounded-pill style-tiny font-monospace">
                        <i class="fa-solid fa-user me-1"></i> Lihat Feed Saya
                    </a>
                    <a href="<?= base_url('feed/user/' . session()->get('user_id')) ?>#bookmarks-tab-pane" onclick="setTimeout(() => { const bTab = document.getElementById('bookmarks-tab'); if(bTab) bTab.click(); }, 300);" class="btn btn-sm btn-saas-dark text-warning border border-warning border-opacity-25 w-100 rounded-pill style-tiny font-monospace">
                        <i class="fa-solid fa-bookmark me-1 text-warning"></i> Status Disimpan (Bookmark)
                    </a>
                </div>
            </div>

            <?php if (!empty($todayBirthdays)): ?>
                <!-- Birthday Celebrants Widget -->
                <div class="saas-card p-3 p-md-4 mb-4 border border-warning border-opacity-50 bg-body-tertiary shadow-lg">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-secondary border-opacity-25">
                        <h6 class="text-body font-heading m-0 fw-bold d-flex align-items-center gap-2">
                            <span class="fs-5">🎂</span> Ulang Tahun Hari Ini!
                        </h6>
                        <span class="badge bg-warning text-dark font-monospace style-tiny px-2 py-0.5 rounded-pill fw-bold">
                            <?= count($todayBirthdays) ?> Anggota
                        </span>
                    </div>

                    <div class="d-flex flex-column gap-2.5">
                        <?php foreach ($todayBirthdays as $bUser): ?>
                            <?php
                                $bAge = '';
                                if (!empty($bUser['birth_date'])) {
                                    $bYears = date_diff(date_create($bUser['birth_date']), date_create('today'))->y;
                                    if ($bYears > 0) $bAge = " ({$bYears} Thn)";
                                }
                                $wishText = "Selamat Ulang Tahun untuk " . addslashes($bUser['full_name']) . "! 🎉 Wish you all the best! 🎂🥳";
                            ?>
                            <div class="p-2.5 rounded-3 bg-body border border-warning border-opacity-25 d-flex align-items-center justify-content-between gap-2">
                                <div class="d-flex align-items-center gap-2.5 overflow-hidden">
                                    <a href="<?= base_url('feed/user/' . $bUser['id']) ?>" class="flex-shrink-0">
                                        <?php if (!empty($bUser['avatar'])): ?>
                                            <img src="<?= base_url($bUser['avatar']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-warning shadow-sm" style="width: 38px; height: 38px;">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center fw-bold style-tiny shadow-sm" style="width: 38px; height: 38px;">
                                                <?= strtoupper(substr($bUser['full_name'], 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                    </a>

                                    <div class="text-truncate">
                                        <div class="d-flex align-items-center gap-1">
                                            <a href="<?= base_url('feed/user/' . $bUser['id']) ?>" class="text-body style-tiny fw-bold text-decoration-none hover-text-danger text-truncate">
                                                <?= esc($bUser['full_name']) ?>
                                            </a>
                                            <?php if (in_array(strtolower((string)$bUser['role_slug']), ['superadmin', 'pembina', 'bph'])): ?>
                                                <i class="fa-solid fa-circle-check text-primary style-tiny"></i>
                                            <?php endif; ?>
                                        </div>
                                        <small class="text-warning font-monospace style-tiny d-block fw-semibold" style="font-size: 0.68rem;">
                                            🥳 Ultah<?= $bAge ?>
                                        </small>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-sm btn-outline-warning style-tiny rounded-pill py-1 px-2.5 flex-shrink-0 font-monospace" onclick="writeBirthdayWish('<?= esc($wishText) ?>')">
                                    <i class="fa-solid fa-pen-to-square me-1"></i> Ucapan
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Who to Follow Recommendations -->
            <div class="saas-card p-3 p-md-4 border border-secondary border-opacity-25 bg-body-tertiary">
                <h6 class="text-body font-heading fw-bold mb-3 d-flex align-items-center gap-2 style-tiny">
                    <i class="fa-solid fa-user-plus text-info"></i> Rekomendasi Untuk Diikuti
                </h6>

                <?php if (empty($whoToFollow)): ?>
                    <p class="text-secondary style-tiny m-0">Seluruh anggota klub sudah Anda ikuti.</p>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2.5">
                        <?php foreach ($whoToFollow as $rec): ?>
                            <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-body-secondary border border-secondary border-opacity-25 gap-2" style="min-width: 0;">
                                <div class="d-flex align-items-center gap-2 overflow-hidden" style="min-width: 0; flex-grow: 1;">
                                    <a href="<?= base_url('feed/user/' . $rec['id']) ?>" class="flex-shrink-0">
                                        <?php if (!empty($rec['avatar'])): ?>
                                            <img src="<?= base_url($rec['avatar']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-secondary border-opacity-50" style="width: 36px; height: 36px;" onerror="this.onerror=null; this.src='<?= base_url('assets/logo-mm-2023.png') ?>';">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold style-tiny" style="width: 36px; height: 36px;">
                                                <?= strtoupper(substr($rec['full_name'], 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                    </a>

                                    <div style="min-width: 0; flex-grow: 1;" class="overflow-hidden">
                                        <div class="d-flex align-items-center gap-1 overflow-hidden">
                                            <a href="<?= base_url('feed/user/' . $rec['id']) ?>" class="text-body style-tiny fw-bold text-decoration-none hover-text-danger text-truncate d-inline-block" style="max-width: 100%;">
                                                <?= esc($rec['full_name']) ?>
                                            </a>
                                            <?php if (!empty($rec['is_verified'])): ?>
                                                <i class="fa-solid fa-circle-check text-primary style-tiny flex-shrink-0" title="Akun Terverifikasi"></i>
                                            <?php endif; ?>
                                        </div>
                                        <small class="text-secondary style-tiny d-block opacity-75 text-truncate" style="font-size: 0.65rem;"><?= esc($rec['class_dept'] ?: $rec['role_name']) ?></small>
                                    </div>
                                </div>

                                <form action="<?= base_url('feed/follow/' . $rec['id']) ?>" method="POST" class="d-inline flex-shrink-0">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-info rounded-pill style-tiny py-1 px-2.5 text-nowrap">
                                        <i class="fa-solid fa-user-plus me-1"></i> <span>Ikuti</span>
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>

    </div>

</div>

<!-- Modal: Followers / Following List -->
<div class="modal fade" id="userFollowsListModal" tabindex="-1" aria-labelledby="userFollowsListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-body text-body border border-secondary border-opacity-50 shadow-lg">
            <div class="modal-header border-bottom border-secondary border-opacity-25 py-2.5">
                <h5 class="modal-title font-heading style-tiny fw-bold" id="userFollowsListModalTitle">
                    <i class="fa-solid fa-users text-info me-1.5"></i> Daftar Anggota
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="d-flex flex-column gap-2 overflow-y-auto pe-1" id="followsListContainer" style="max-height: 350px;">
                    <div class="text-center py-4 text-secondary style-tiny">
                        <span class="spinner-border spinner-border-sm me-1 text-danger"></span> Memuat daftar...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: All Members Directory -->
<div class="modal fade" id="allMembersDirectoryModal" tabindex="-1" aria-labelledby="allMembersDirectoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-body text-body border border-secondary border-opacity-50 shadow-lg">
            <div class="modal-header border-bottom border-secondary border-opacity-25 py-2.5">
                <h5 class="modal-title font-heading style-tiny fw-bold" id="allMembersDirectoryModalTitle">
                    <i class="fa-solid fa-address-book text-warning me-1.5"></i> Direktori Semua Anggota Klub MMC
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="mb-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-body border-secondary border-opacity-50 text-secondary"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" id="directorySearchInput" class="form-control bg-body border-secondary border-opacity-50 text-body style-tiny" placeholder="Ketik nama atau kelas anggota..." onkeyup="filterDirectoryMembers()">
                    </div>
                </div>
                <div class="row g-2 overflow-y-auto pe-1" id="directoryMembersContainer" style="max-height: 420px;">
                    <div class="text-center py-4 text-secondary style-tiny">
                        <span class="spinner-border spinner-border-sm me-1 text-danger"></span> Memuat anggota...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Share to Chat Inbox -->
<div class="modal fade" id="shareToChatModal" tabindex="-1" aria-labelledby="shareToChatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-body text-body border border-secondary border-opacity-50 shadow-lg">
            <div class="modal-header border-bottom border-secondary border-opacity-25 py-2.5">
                <h5 class="modal-title font-heading style-tiny fw-bold" id="shareToChatModalLabel">
                    <i class="fa-solid fa-paper-plane text-danger me-1.5"></i> Bagikan Status ke Chat Inbox
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <p class="text-secondary style-tiny mb-3">Pilih anggota atau grup obrolan tujuan untuk membagikan postingan ini:</p>
                <div class="d-flex flex-column gap-2 overflow-y-auto pe-1" style="max-height: 320px;">
                    <?php if (!empty($allMembers)): ?>
                        <?php foreach ($allMembers as $m): ?>
                            <?php if ($m['id'] != session()->get('user_id')): ?>
                                <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-body-secondary border border-secondary border-opacity-25">
                                    <div class="d-flex align-items-center gap-2.5 overflow-hidden" style="min-width: 0;">
                                        <?php if (!empty($m['avatar'])): ?>
                                            <img src="<?= base_url($m['avatar']) ?>" class="rounded-circle object-fit-cover flex-shrink-0" style="width: 34px; height: 34px;" onerror="this.onerror=null; this.src='<?= base_url('assets/logo-mm-2023.png') ?>';">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold style-tiny flex-shrink-0" style="width: 34px; height: 34px;">
                                                <?= strtoupper(substr($m['full_name'], 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="overflow-hidden" style="min-width: 0;">
                                            <span class="text-body style-tiny fw-bold d-block text-truncate"><?= esc($m['full_name']) ?></span>
                                            <small class="text-secondary style-tiny opacity-75 d-block text-truncate" style="font-size: 0.65rem;"><?= esc($m['class_dept'] ?: $m['role_name']) ?></small>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-red rounded-pill style-tiny py-1 px-3" onclick="sendShareToChat('personal', <?= $m['id'] ?>)">
                                        <i class="fa-solid fa-paper-plane me-1"></i> Kirim
                                    </button>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Fullscreen Media Lightbox -->
<div class="modal fade" id="feedMediaLightboxModal" tabindex="-1" aria-hidden="true" style="z-index: 1080;">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-black text-white border border-secondary border-opacity-50 shadow-lg">
            <div class="modal-header border-bottom border-secondary border-opacity-25 py-2.5 px-3">
                <span class="style-tiny font-monospace text-secondary" id="lightboxMediaTitle">
                    <i class="fa-solid fa-expand text-info me-1.5"></i> Pratinjau Media Penuh
                </span>
                <div class="d-flex align-items-center gap-2">
                    <a id="lightboxDownloadBtn" href="#" target="_blank" download class="btn btn-sm btn-saas-dark text-info border border-secondary border-opacity-25 rounded-pill style-tiny px-3">
                        <i class="fa-solid fa-download me-1"></i> Unduh Berkas Media
                    </a>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-2 text-center overflow-auto d-flex align-items-center justify-content-center bg-black" style="min-height: 350px; max-height: 85vh;">
                <img id="lightboxImage" src="" alt="Fullscreen Image" class="img-fluid rounded-3 shadow-lg object-fit-contain" style="max-height: 80vh; max-width: 100%; display: none;">
                <video id="lightboxVideo" src="" controls class="w-100 rounded-3 shadow-lg" style="max-height: 80vh; display: none;"></video>
            </div>
        </div>
    </div>
</div>

<script>
    // Open Media Fullscreen Lightbox
    function openMediaLightbox(mediaUrl, mediaType) {
        if (!mediaUrl) return;
        const imgEl = document.getElementById('lightboxImage');
        const videoEl = document.getElementById('lightboxVideo');
        const downloadBtn = document.getElementById('lightboxDownloadBtn');
        const modalEl = document.getElementById('feedMediaLightboxModal');

        if (!modalEl) return;

        downloadBtn.href = mediaUrl;

        if (mediaType === 'image') {
            imgEl.src = mediaUrl;
            imgEl.style.setProperty('display', 'inline-block', 'important');
            videoEl.style.setProperty('display', 'none', 'important');
            try { videoEl.pause(); } catch(e){}
        } else if (mediaType === 'video') {
            videoEl.src = mediaUrl;
            videoEl.style.setProperty('display', 'block', 'important');
            imgEl.style.setProperty('display', 'none', 'important');
        }

        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }
    // Preview Feed Media Before Posting
    function previewFeedMedia(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const previewBox = document.getElementById('feedMediaPreviewBox');
            const nameEl     = document.getElementById('feedMediaFileName');
            const sizeEl     = document.getElementById('feedMediaFileSize');
            const thumbEl    = document.getElementById('feedMediaThumbnailWrapper');

            nameEl.textContent = file.name;
            sizeEl.textContent = (file.size / 1024 > 1024) 
                ? (file.size / (1024 * 1024)).toFixed(1) + ' MB' 
                : (file.size / 1024).toFixed(0) + ' KB';

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    thumbEl.innerHTML = `<img src="${e.target.result}" class="rounded-2 object-fit-cover border border-secondary border-opacity-50 shadow-sm" style="width: 50px; height: 50px;" />`;
                };
                reader.readAsDataURL(file);
            } else {
                thumbEl.innerHTML = `<div class="rounded-2 bg-secondary bg-opacity-25 text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;"><i class="fa-solid fa-file-video fs-5"></i></div>`;
            }

            previewBox.classList.remove('d-none');
            previewBox.classList.add('d-flex');
        }
    }
    // Cancel Feed Media
    function cancelFeedMedia() {
        const input = document.getElementById('feedMediaInput');
        if (input) input.value = '';
        const previewBox = document.getElementById('feedMediaPreviewBox');
        if (previewBox) {
            previewBox.classList.remove('d-flex');
            previewBox.classList.add('d-none');
        }
    }

    // AJAX Toggle Like (Seamless without refresh)
    function toggleLikePost(postId, btn, e) {
        if (e) e.preventDefault();
        if (!postId || btn.disabled) return;

        const heartIcon = btn.querySelector('i');
        const countSpan = btn.querySelector('.like-count');
        let currentCount = parseInt(countSpan.textContent) || 0;
        const isCurrentlyLiked = heartIcon.classList.contains('fa-solid');

        // Optimistic UI update (Instant feedback)
        if (isCurrentlyLiked) {
            btn.classList.remove('text-danger', 'fw-bold');
            btn.classList.add('text-secondary');
            heartIcon.classList.remove('fa-solid', 'text-danger');
            heartIcon.classList.add('fa-regular');
            countSpan.textContent = Math.max(0, currentCount - 1);
        } else {
            btn.classList.add('text-danger', 'fw-bold');
            btn.classList.remove('text-secondary');
            heartIcon.classList.remove('fa-regular');
            heartIcon.classList.add('fa-solid', 'text-danger');
            countSpan.textContent = currentCount + 1;
        }

        fetch('<?= base_url('feed/like/') ?>' + postId, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success' && data.data) {
                countSpan.textContent = data.data.likes_count;
                if (data.data.is_liked) {
                    btn.classList.add('text-danger', 'fw-bold');
                    btn.classList.remove('text-secondary');
                    heartIcon.classList.remove('fa-regular');
                    heartIcon.classList.add('fa-solid', 'text-danger');
                } else {
                    btn.classList.remove('text-danger', 'fw-bold');
                    btn.classList.add('text-secondary');
                    heartIcon.classList.remove('fa-solid', 'text-danger');
                    heartIcon.classList.add('fa-regular');
                }
            }
        })
        .catch(err => {
            console.error('Like error:', err);
        });
    }

    // AJAX Toggle Follow User (Updates ALL follow buttons on the page for this user)
    function toggleFollowUser(targetUserId, btn) {
        if (!targetUserId) return;
        
        // Temporarily disable clicked button to prevent duplicate clicks
        if (btn) btn.disabled = true;

        fetch('<?= base_url('feed/follow/') ?>' + targetUserId, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (btn) btn.disabled = false;

            if (data.status === 'success' && data.data) {
                const isFollowing = data.data.is_following;

                // Find ALL follow buttons targeting this targetUserId across the entire page
                const allButtons = document.querySelectorAll(`button[onclick*="toggleFollowUser(${targetUserId}"]`);
                allButtons.forEach(b => {
                    const icon = b.querySelector('i');
                    const textSpan = b.querySelector('span');

                    if (isFollowing) {
                        b.classList.remove('btn-outline-info');
                        b.classList.add('btn-saas-dark', 'text-secondary');
                        if (icon) icon.className = 'fa-solid fa-user-check me-1';
                        if (textSpan) textSpan.textContent = 'Diikuti';
                    } else {
                        b.classList.remove('btn-saas-dark', 'text-secondary');
                        b.classList.add('btn-outline-info');
                        if (icon) icon.className = 'fa-solid fa-user-plus me-1';
                        if (textSpan) textSpan.textContent = 'Ikuti';
                    }
                });

                // Show toast or alert message if available
                if (data.message) {
                    alert(data.message);
                }
            } else {
                alert(data.message || 'Gagal mengubah status ikuti.');
            }
        })
        .catch(err => {
            if (btn) btn.disabled = false;
            console.error('Follow error:', err);
            alert('Terjadi kesalahan saat memproses status ikuti.');
        });
    }

    // Focus comment input
    function focusCommentInput(postId) {
        const input = document.querySelector('#comment-form-' + postId + ' input[name="comment"]');
        if (input) input.focus();
    }

    // Repost Handler
    function repostPost(postId) {
        if (!confirm('Unggah ulang (repost) status ini ke beranda Anda?')) return;
        fetch('<?= base_url('feed/repost/') ?>' + postId, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            }
        })
        .then(r => r.json())
        .then(data => {
            alert(data.message || 'Status berhasil di-repost!');
            if (data.status === 'success') {
                window.location.reload();
            }
        });
    }

    // Copy Post Link to Clipboard
    function copyPostLink(url) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url).then(() => {
                alert('📋 Tautan postingan berhasil disalin ke papan klip!');
            });
        } else {
            const input = document.createElement('input');
            input.value = url;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            alert('📋 Tautan postingan berhasil disalin!');
        }
    }

    // AJAX Toggle Bookmark Post (Persisted to Database)
    function toggleBookmarkPost(postId, btn) {
        if (!postId) return;
        fetch('<?= base_url('feed/save/') ?>' + postId, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                const icon = btn.querySelector('i');
                if (data.data.is_bookmarked) {
                    btn.classList.add('text-warning');
                    btn.classList.remove('text-secondary');
                    icon.classList.remove('fa-regular');
                    icon.classList.add('fa-solid', 'text-warning');
                    alert('📌 Status berhasil disimpan ke markah (bookmark) Anda!');
                } else {
                    btn.classList.remove('text-warning');
                    btn.classList.add('text-secondary');
                    icon.classList.remove('fa-solid', 'text-warning');
                    icon.classList.add('fa-regular');
                    alert('Batal menyimpan status.');
                }
            } else {
                alert(data.message || 'Gagal menyimpan status.');
            }
        })
        .catch(() => {
            alert('Terjadi kesalahan jaringan.');
        });
    }

    // Open Share to Chat Modal
    let activeSharePostId = null;
    function openShareToChatModal(postId) {
        activeSharePostId = postId;
        const modalEl = document.getElementById('shareToChatModal');
        if (!modalEl) return;
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }

    function sendShareToChat(targetType, targetId) {
        if (!activeSharePostId) return;
        const formData = new FormData();
        formData.append('target_type', targetType);
        formData.append('target_id', targetId);

        fetch('<?= base_url('feed/share-chat/') ?>' + activeSharePostId, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            }
        })
        .then(r => r.json())
        .then(data => {
            const modalEl = document.getElementById('shareToChatModal');
            if (modalEl) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }
            alert(data.message || 'Status berhasil dikirim ke obrolan!');
        });
    }

    // Show Followers / Following List Modal
    function showFollowsListModal(userId, type, userName) {
        const titleEl = document.getElementById('userFollowsListModalTitle');
        const container = document.getElementById('followsListContainer');

        titleEl.innerHTML = (type === 'followers') 
            ? `<i class="fa-solid fa-users text-info me-1.5"></i> Pengikut ${userName}`
            : `<i class="fa-solid fa-user-group text-warning me-1.5"></i> ${userName} Mengikuti`;

        container.innerHTML = `<div class="text-center py-4 text-secondary style-tiny"><span class="spinner-border spinner-border-sm me-1 text-danger"></span> Memuat data...</div>`;

        const modal = new bootstrap.Modal(document.getElementById('userFollowsListModal'));
        modal.show();

        fetch(`<?= base_url('feed/') ?>${type}/${userId}`)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success' && data.data.length > 0) {
                let html = '';
                data.data.forEach(u => {
                    const avatarHtml = u.avatar 
                        ? `<img src="<?= base_url() ?>${u.avatar}" class="rounded-circle object-fit-cover border border-secondary border-opacity-50" style="width: 38px; height: 38px;" />`
                        : `<div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold style-tiny" style="width: 38px; height: 38px;">${(u.full_name || 'U').charAt(0).toUpperCase()}</div>`;

                    const verifiedBadge = u.is_verified ? `<i class="fa-solid fa-circle-check text-primary style-tiny ms-1" title="Akun Terverifikasi"></i>` : '';

                    let followBtnHtml = '';
                    if (!u.is_self) {
                        followBtnHtml = u.is_following 
                            ? `<button class="btn btn-sm btn-saas-dark text-secondary rounded-pill style-tiny py-0.5 px-2.5" onclick="toggleFollowUser(${u.id}, this)"><i class="fa-solid fa-user-check me-1"></i> <span>Diikuti</span></button>`
                            : `<button class="btn btn-sm btn-outline-info rounded-pill style-tiny py-0.5 px-2.5" onclick="toggleFollowUser(${u.id}, this)"><i class="fa-solid fa-user-plus me-1"></i> <span>Ikuti</span></button>`;
                    }

                    html += `
                        <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-body-secondary border border-secondary border-opacity-25">
                            <div class="d-flex align-items-center gap-2.5">
                                <a href="<?= base_url('feed/user/') ?>${u.id}">${avatarHtml}</a>
                                <div>
                                    <div class="d-flex align-items-center gap-1">
                                        <a href="<?= base_url('feed/user/') ?>${u.id}" class="text-body style-tiny fw-bold text-decoration-none hover-text-danger">${u.full_name}</a>
                                        ${verifiedBadge}
                                    </div>
                                    <small class="text-secondary style-tiny d-block opacity-75" style="font-size: 0.65rem;">${u.class_dept || u.role_name}</small>
                                </div>
                            </div>
                            <div>${followBtnHtml}</div>
                        </div>
                    `;
                });
                container.innerHTML = html;
            } else {
                container.innerHTML = `<div class="text-center py-4 text-secondary style-tiny">Belum ada daftar anggota.</div>`;
            }
        });
    }

    // Show All Members Directory Modal
    function showAllMembersDirectoryModal() {
        const container = document.getElementById('directoryMembersContainer');
        container.innerHTML = `<div class="text-center py-4 text-secondary style-tiny"><span class="spinner-border spinner-border-sm me-1 text-danger"></span> Memuat data semua anggota...</div>`;

        const modal = new bootstrap.Modal(document.getElementById('allMembersDirectoryModal'));
        modal.show();

        fetch(`${baseUrl}feed/search-users?q=`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                renderDirectoryMembers(data.data || []);
            } else {
                container.innerHTML = `<div class="text-center py-4 text-danger style-tiny">${escapeHtml(data.message || 'Gagal memuat direktori anggota.')}</div>`;
            }
        })
        .catch(err => {
            console.error('Directory Fetch Error:', err);
            container.innerHTML = `<div class="text-center py-4 text-danger style-tiny">Gagal memuat direktori anggota.</div>`;
        });
    }

    function renderDirectoryMembers(members) {
        const container = document.getElementById('directoryMembersContainer');
        if (!members || members.length === 0) {
            container.innerHTML = `<div class="text-center py-4 text-secondary style-tiny">Belum ada anggota ditemukan.</div>`;
            return;
        }

        let html = '';
        members.forEach(u => {
            let avatarUrl = '';
            if (u.avatar) {
                avatarUrl = u.avatar.startsWith('http') ? u.avatar : baseUrl + u.avatar.replace(/^\//, '');
            }
            const avatarHtml = avatarUrl 
                ? `<img src="${avatarUrl}" class="rounded-circle object-fit-cover flex-shrink-0" style="width: 40px; height: 40px;" onerror="this.onerror=null; this.src='${baseUrl}assets/logo-mm-2023.png';">`
                : `<div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold style-tiny flex-shrink-0" style="width: 40px; height: 40px;">${(u.full_name || 'U').charAt(0).toUpperCase()}</div>`;

            const verifiedBadge = u.is_verified ? `<i class="fa-solid fa-circle-check text-primary style-tiny flex-shrink-0" title="Akun Terverifikasi"></i>` : '';

            let followBtnHtml = '';
            if (!u.is_self) {
                followBtnHtml = u.is_following 
                    ? `<button class="btn btn-sm btn-saas-dark text-secondary rounded-pill style-tiny py-1 px-2.5" onclick="toggleFollowUser(${u.id}, this)"><i class="fa-solid fa-user-check me-1"></i> <span>Diikuti</span></button>`
                    : `<button class="btn btn-sm btn-outline-info rounded-pill style-tiny py-1 px-2.5" onclick="toggleFollowUser(${u.id}, this)"><i class="fa-solid fa-user-plus me-1"></i> <span>Ikuti</span></button>`;
            }

            const userKeyword = ((u.full_name || '') + ' ' + (u.username || '') + ' ' + (u.nis_nip || '') + ' ' + (u.class_dept || '') + ' ' + (u.role_name || '')).toLowerCase();
            html += `
                <div class="col-12 col-md-6 directory-member-card" data-name="${escapeHtml((u.full_name || '').toLowerCase())}" data-class="${escapeHtml((u.class_dept || u.role_name || '').toLowerCase())}" data-search="${escapeHtml(userKeyword)}">
                    <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-body-secondary border border-secondary border-opacity-25 gap-2" style="min-width: 0;">
                        <div class="d-flex align-items-center gap-2.5 overflow-hidden" style="min-width: 0; flex: 1 1 0%;">
                            <a href="${baseUrl}feed/user/${u.id}">${avatarHtml}</a>
                            <div class="overflow-hidden" style="min-width: 0; flex: 1 1 0%;">
                                <div class="d-flex align-items-center gap-1">
                                    <a href="${baseUrl}feed/user/${u.id}" class="text-body style-tiny fw-bold text-decoration-none hover-text-danger text-truncate">${escapeHtml(u.full_name)}</a>
                                    ${verifiedBadge}
                                </div>
                                <small class="text-secondary style-tiny d-block opacity-75 text-truncate" style="font-size: 0.65rem;">${escapeHtml((u.username ? '@' + u.username + ' • ' : '') + (u.class_dept || u.role_name))}</small>
                            </div>
                        </div>
                        <div class="flex-shrink-0">${followBtnHtml}</div>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
    }

    function filterDirectoryMembers() {
        const input = document.getElementById('directorySearchInput');
        if (!input) return;
        let filter = input.value.toLowerCase().trim();
        if (filter.startsWith('@')) {
            filter = filter.substring(1).trim();
        }
        const cards = document.querySelectorAll('.directory-member-card');

        cards.forEach(card => {
            const searchData = card.getAttribute('data-search') || '';
            const name = card.getAttribute('data-name') || '';
            const classDept = card.getAttribute('data-class') || '';
            if (searchData.includes(filter) || name.includes(filter) || classDept.includes(filter)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    /* =============================
       FEED REFRESH & LOAD MORE
    ============================= */
    let feedNextOffset   = <?= $perPage ?>;
    let feedFilter       = '<?= $activeFilter ?>';
    let feedLatestTime   = '<?= date('Y-m-d H:i:s') ?>';
    let feedPollingTimer = null;
    const baseUrl        = '<?= rtrim(base_url(), '/') . '/' ?>';

    // --- Load More Posts ---
    function loadMorePosts() {
        const btn     = document.getElementById('feedLoadMoreBtn');
        const spinner = document.getElementById('feedLoadMoreSpinner');
        const wrapper = document.getElementById('feedLoadMoreWrapper');
        const container = document.getElementById('feedPostsContainer');

        if (!container || btn.disabled) return;
        btn.disabled = true;
        spinner.classList.remove('d-none');
        btn.classList.add('d-none');

        fetch(`${baseUrl}feed/load-more?filter=${feedFilter}&offset=${feedNextOffset}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            spinner.classList.add('d-none');
            if (data.status === 'success') {
                container.insertAdjacentHTML('beforeend', data.html);
                feedNextOffset = data.next_offset;
                if (data.has_more) {
                    btn.disabled = false;
                    btn.classList.remove('d-none');
                    const remaining = data.total - feedNextOffset;
                    const badge = document.getElementById('feedRemainingCount');
                    if (badge) badge.textContent = remaining > 0 ? remaining : '';
                } else {
                    wrapper.innerHTML = '<p class="text-secondary style-tiny text-center mt-2 font-monospace">✅ Semua postingan sudah ditampilkan.</p>';
                }
            }
        })
        .catch(() => {
            spinner.classList.add('d-none');
            btn.disabled = false;
            btn.classList.remove('d-none');
        });
    }

    // Auto polling turned off as requested
    // document.addEventListener('DOMContentLoaded', startFeedPolling);

    /* =============================
       LIVE FLOATING SEARCH MEMBER HANDLER
    ============================= */
    const searchInput = document.getElementById('feedSearchMemberInput');
    const searchDropdown = document.getElementById('feedSearchDropdown');

    if (searchInput && searchDropdown) {
        function filterFeedSearchMembers() {
            let query = searchInput.value.toLowerCase().trim();
            if (query.startsWith('@')) {
                query = query.substring(1).trim();
            }

            const items = searchDropdown.querySelectorAll('.feed-search-member-item');
            const noResults = document.getElementById('feedSearchNoResults');

            if (query.length > 0) {
                searchDropdown.classList.remove('d-none');
            } else {
                searchDropdown.classList.add('d-none');
                return;
            }

            let found = 0;
            items.forEach(item => {
                const searchData = (item.getAttribute('data-search') || '').toLowerCase();
                const name = (item.querySelector('.member-name')?.textContent || '').toLowerCase();
                const info = (item.querySelector('.member-info')?.textContent || '').toLowerCase();

                if (searchData.includes(query) || name.includes(query) || info.includes(query)) {
                    item.style.setProperty('display', 'flex', 'important');
                    found++;
                } else {
                    item.style.setProperty('display', 'none', 'important');
                }
            });

            if (noResults) {
                if (found === 0) {
                    noResults.classList.remove('d-none');
                } else {
                    noResults.classList.add('d-none');
                }
            }
        }

        searchInput.addEventListener('input', filterFeedSearchMembers);
        searchInput.addEventListener('keyup', filterFeedSearchMembers);
        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length > 0) {
                filterFeedSearchMembers();
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
                searchDropdown.classList.add('d-none');
            }
        });
    }
</script>

<?= $this->endSection() ?>
