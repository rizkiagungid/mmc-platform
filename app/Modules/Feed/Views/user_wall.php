<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="container-fluid p-0">

    <!-- Back Button & Title Bar -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <a href="<?= base_url('feed') ?>" class="btn btn-sm btn-saas-dark text-secondary hover-white border border-secondary border-opacity-25 rounded-pill px-3 style-tiny">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Beranda Feed
        </a>
    </div>

    <!-- User Profile Header Banner -->
    <div class="saas-card mb-4 p-4 position-relative overflow-hidden border border-secondary border-opacity-25 bg-body-tertiary">
        <div class="row align-items-center g-4">
            <div class="col-auto">
                <?php if (!empty($profileData['user']['avatar'])): ?>
                    <img src="<?= base_url($profileData['user']['avatar']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-danger border-opacity-50 shadow" style="width: 84px; height: 84px;">
                <?php else: ?>
                    <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold display-5 shadow" style="width: 84px; height: 84px;">
                        <?= strtoupper(substr($profileData['user']['full_name'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col style-tiny">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <h3 class="text-body font-heading m-0 fw-bold d-flex align-items-center gap-2">
                        <?= esc($profileData['user']['full_name']) ?>
                    </h3>
                    
                    <!-- Verified Blue Checkmark Badge for Superadmin/Pembina/BPH -->
                    <?php if (!empty($profileData['user']['is_verified'])): ?>
                        <i class="fa-solid fa-circle-check text-primary fs-5" title="Akun Terverifikasi (Pengurus / Pembina MMC)"></i>
                    <?php endif; ?>

                    <span class="badge bg-danger bg-opacity-25 text-danger font-monospace py-1 px-2.5 rounded-pill style-tiny ms-1">
                        <?= esc($profileData['user']['role_name'] ?: 'Anggota Club') ?>
                    </span>
                </div>

                <p class="text-secondary style-tiny m-0 mt-1">
                    <?= esc($profileData['user']['class_dept'] ?: 'Anggota Multimedia Club MMC') ?> • Email: <?= esc($profileData['user']['email']) ?>
                </p>

                <!-- Social Stats & Follow Action Button -->
                <div class="d-flex align-items-center gap-4 mt-3 flex-wrap font-monospace">
                    <div class="cursor-pointer hover-opacity-100" onclick="showFollowsListModal(<?= $profileData['user']['id'] ?>, 'followers', '<?= esc(addslashes($profileData['user']['full_name'])) ?>')">
                        <strong class="text-body fs-6 me-1"><?= $profileData['follower_count'] ?></strong>
                        <span class="text-secondary opacity-75 style-tiny">Pengikut</span>
                    </div>
                    
                    <div class="cursor-pointer hover-opacity-100" onclick="showFollowsListModal(<?= $profileData['user']['id'] ?>, 'following', '<?= esc(addslashes($profileData['user']['full_name'])) ?>')">
                        <strong class="text-body fs-6 me-1"><?= $profileData['following_count'] ?></strong>
                        <span class="text-secondary opacity-75 style-tiny">Diikuti</span>
                    </div>
                    
                    <div>
                        <strong class="text-body fs-6 me-1"><?= $profileData['posts_count'] ?></strong>
                        <span class="text-secondary opacity-75 style-tiny">Status</span>
                    </div>

                    <?php if ((int)$profileData['user']['id'] !== session()->get('user_id')): ?>
                        <button type="button" class="btn btn-sm <?= $profileData['is_following'] ? 'btn-saas-dark text-secondary' : 'btn-info text-dark font-weight-bold' ?> rounded-pill style-tiny px-3 ms-auto" onclick="toggleFollowUser(<?= $profileData['user']['id'] ?>, this)">
                            <i class="fa-solid <?= $profileData['is_following'] ? 'fa-user-check' : 'fa-user-plus' ?> me-1"></i>
                            <span><?= $profileData['is_following'] ? 'Diikuti' : 'Ikuti' ?></span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Content Navigation Tabs (Instagram Style) -->
    <div class="saas-card p-2 mb-4 border border-secondary border-opacity-25 bg-body-tertiary">
        <ul class="nav nav-pills nav-fill gap-2" id="profileFeedTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-3 py-2 px-3 style-tiny fw-bold d-flex align-items-center justify-content-center gap-2" id="posts-tab" data-bs-toggle="tab" data-bs-target="#posts-tab-pane" type="button" role="tab" aria-controls="posts-tab-pane" aria-selected="true">
                    <i class="fa-solid fa-grid-2 text-danger fs-6"></i>
                    <span>Status Dipublikasikan</span>
                    <span class="badge bg-danger bg-opacity-25 text-danger rounded-pill px-2 font-monospace style-tiny"><?= count($profileData['posts'] ?? []) ?></span>
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-3 py-2 px-3 style-tiny fw-bold text-secondary d-flex align-items-center justify-content-center gap-2" id="bookmarks-tab" data-bs-toggle="tab" data-bs-target="#bookmarks-tab-pane" type="button" role="tab" aria-controls="bookmarks-tab-pane" aria-selected="false">
                    <i class="fa-solid fa-bookmark text-warning fs-6"></i>
                    <span>Status Disimpan (Bookmark)</span>
                    <span class="badge bg-warning bg-opacity-25 text-warning rounded-pill px-2 font-monospace style-tiny"><?= count($profileData['bookmarked_posts'] ?? []) ?></span>
                </button>
            </li>
        </ul>
    </div>

    <!-- Tab Content -->
    <div class="tab-content" id="profileFeedTabsContent">
        <!-- Tab 1: Published Posts -->
        <div class="tab-pane fade show active" id="posts-tab-pane" role="tabpanel" aria-labelledby="posts-tab" tabindex="0">
            <?php if (empty($profileData['posts'])): ?>
                <div class="saas-card text-center py-5 border border-secondary border-opacity-25 bg-body-tertiary">
                    <i class="fa-solid fa-comments text-secondary opacity-25 display-3 mb-3"></i>
                    <h5 class="text-body font-heading">Belum Ada Status</h5>
                    <p class="text-secondary style-tiny">Anggota ini belum mempublikasikan status apa pun di Beranda Feed MMC.</p>
                </div>
            <?php else: ?>
                <div class="d-flex flex-column gap-4 max-w-3xl">
                    <?php foreach ($profileData['posts'] as $post): ?>
                        <?= view('App\Modules\Feed\Views\_post_card', ['post' => $post]) ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Tab 2: Bookmarked / Saved Posts -->
        <div class="tab-pane fade" id="bookmarks-tab-pane" role="tabpanel" aria-labelledby="bookmarks-tab" tabindex="0">
            <?php if (empty($profileData['bookmarked_posts'])): ?>
                <div class="saas-card text-center py-5 border border-secondary border-opacity-25 bg-body-tertiary">
                    <i class="fa-regular fa-bookmark text-warning opacity-50 display-3 mb-3"></i>
                    <h5 class="text-body font-heading">Belum Ada Status Tersimpan</h5>
                    <p class="text-secondary style-tiny">Status yang Anda simpan dengan tombol bookmark akan muncul di sini.</p>
                </div>
            <?php else: ?>
                <div class="d-flex flex-column gap-4 max-w-3xl">
                    <?php foreach ($profileData['bookmarked_posts'] as $post): ?>
                        <?= view('App\Modules\Feed\Views\_post_card', ['post' => $post]) ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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
                        <span class="spinner-border spinner-border-sm me-1 text-danger"></span> Memuat...
                    </div>
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
                <span class="style-tiny font-monospace text-secondary">
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
    function toggleLikePost(postId, btn) {
        fetch('<?= base_url('feed/like/') ?>' + postId, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const heartIcon = btn.querySelector('i');
                const countSpan = btn.querySelector('.like-count');
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
        });
    }

    function toggleFollowUser(targetUserId, btn) {
        fetch('<?= base_url('feed/follow/') ?>' + targetUserId, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload();
            }
        });
    }

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

    // Seamless AJAX Like on User Wall
    function toggleLikePost(postId, btn, e) {
        if (e) e.preventDefault();
        if (!postId) return;

        const heartIcon = btn.querySelector('i');
        const countSpan = btn.querySelector('.like-count');
        let currentCount = parseInt(countSpan.textContent) || 0;
        const isCurrentlyLiked = heartIcon.classList.contains('fa-solid');

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
            }
        });
    }

    // Auto active bookmarks tab if requested via URL Hash #bookmarks-tab-pane
    document.addEventListener('DOMContentLoaded', () => {
        if (window.location.hash === '#bookmarks-tab-pane') {
            const bookmarkTabBtn = document.getElementById('bookmarks-tab');
            if (bookmarkTabBtn) {
                const tab = new bootstrap.Tab(bookmarkTabBtn);
                tab.show();
            }
        }
    });
</script>

<?= $this->endSection() ?>
