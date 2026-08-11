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

    <!-- User Status Posts List Feed -->
    <h5 class="text-body font-heading fw-bold mb-3 style-tiny">
        <i class="fa-solid fa-newspaper text-danger me-1.5"></i> Status Dipublikasikan oleh <?= esc($profileData['user']['full_name']) ?>
    </h5>

    <?php if (empty($profileData['posts'])): ?>
        <div class="saas-card text-center py-5 border border-secondary border-opacity-25 bg-body-tertiary">
            <i class="fa-solid fa-comments text-secondary opacity-25 display-3 mb-3"></i>
            <h5 class="text-body font-heading">Belum Ada Status</h5>
            <p class="text-secondary style-tiny">Anggota ini belum mempublikasikan status apa pun di Beranda Feed MMC.</p>
        </div>
    <?php else: ?>
        <div class="d-flex flex-column gap-4 max-w-3xl">
            <?php foreach ($profileData['posts'] as $post): ?>
                <div class="saas-card p-3 p-md-4 border border-secondary border-opacity-25 bg-body-tertiary" id="post-<?= $post['id'] ?>">
                    
                    <!-- Post Header -->
                    <div class="d-flex align-items-center justify-content-between mb-3 gap-2" style="min-width: 0;">
                        <div class="d-flex align-items-center gap-2.5 overflow-hidden" style="min-width: 0; flex-grow: 1;">
                            <?php if (!empty($post['author_avatar'])): ?>
                                <img src="<?= base_url($post['author_avatar']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-secondary border-opacity-50 flex-shrink-0" style="width: 42px; height: 42px;" onerror="this.onerror=null; this.src='<?= base_url('assets/logo-mm-2023.png') ?>';">
                            <?php else: ?>
                                <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold fs-6 flex-shrink-0" style="width: 42px; height: 42px;">
                                    <?= strtoupper(substr($post['author_name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>

                            <div style="min-width: 0; flex-grow: 1;" class="overflow-hidden">
                                <div class="d-flex align-items-center gap-1.5 overflow-hidden">
                                    <strong class="text-body style-tiny fw-bold text-truncate d-inline-block" style="max-width: 100%;"><?= esc($post['author_name']) ?></strong>
                                    <?php if (!empty($post['author_verified'])): ?>
                                        <i class="fa-solid fa-circle-check text-primary style-tiny flex-shrink-0" title="Akun Terverifikasi"></i>
                                    <?php endif; ?>
                                </div>
                                <small class="text-secondary style-tiny opacity-75 font-monospace d-block text-truncate" style="font-size: 0.65rem;">
                                    <?= esc($post['time_ago']) ?>
                                </small>
                            </div>
                        </div>

                        <?php if ($post['is_own_post'] || in_array(session()->get('role_slug'), ['superadmin', 'pembina', 'bph'])): ?>
                            <a href="<?= base_url('feed/delete/' . $post['id']) ?>" onclick="return confirm('Hapus postingan ini?')" class="btn btn-sm btn-saas-dark text-danger border-0 p-1 rounded-circle">
                                <i class="fa-solid fa-trash-can style-tiny"></i>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Post Text Content -->
                    <?php if (!empty($post['content'])): ?>
                        <p class="text-body style-tiny mb-3 lh-base" style="white-space: pre-line;"><?= esc($post['content']) ?></p>
                    <?php endif; ?>

                    <!-- Post Media Attachment -->
                    <?php if (!empty($post['media_url'])): ?>
                        <div class="mb-3 rounded-3 overflow-hidden bg-black border border-secondary border-opacity-25 text-center">
                            <?php if ($post['media_type'] === 'image'): ?>
                                <div class="position-relative overflow-hidden cursor-pointer" onclick="openMediaLightbox('<?= base_url($post['media_url']) ?>', 'image')" title="Klik untuk lihat gambar penuh (Fullscreen)">
                                    <img src="<?= base_url($post['media_url']) ?>" alt="Media" class="img-fluid object-fit-contain transition-all hover-scale" style="max-height: 450px; width: 100%;">
                                    <div class="position-absolute bottom-0 end-0 m-2.5 badge bg-black bg-opacity-75 text-white style-tiny py-1 px-2.5 rounded-2 border border-secondary border-opacity-50">
                                        <i class="fa-solid fa-expand me-1 text-info"></i> Perbesar
                                    </div>
                                </div>
                            <?php elseif ($post['media_type'] === 'video'): ?>
                                <video src="<?= base_url($post['media_url']) ?>" controls class="w-100 rounded-3" style="max-height: 450px;"></video>
                            <?php else: ?>
                                <div class="p-3 d-flex align-items-center justify-content-between bg-black">
                                    <span class="text-white style-tiny fw-bold"><?= esc(basename($post['media_url'])) ?></span>
                                    <a href="<?= base_url($post['media_url']) ?>" target="_blank" class="btn btn-sm btn-danger rounded-pill style-tiny px-3">Unduh Berkas</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Action Bar -->
                    <div class="d-flex align-items-center gap-4 pt-2 border-top border-secondary border-opacity-25 style-tiny">
                        <button type="button" class="btn btn-link text-decoration-none p-0 border-0 d-flex align-items-center gap-1.5 <?= $post['is_liked'] ? 'text-danger fw-bold' : 'text-secondary hover-white' ?>" onclick="toggleLikePost(<?= $post['id'] ?>, this)">
                            <i class="<?= $post['is_liked'] ? 'fa-solid text-danger' : 'fa-regular' ?> fa-heart fs-6"></i>
                            <span class="like-count"><?= $post['likes_count'] ?></span> Suka
                        </button>
                        <span class="text-secondary style-tiny"><i class="fa-regular fa-comment fs-6 me-1"></i> <?= $post['comments_count'] ?> Komentar</span>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

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
</script>

<?= $this->endSection() ?>
