<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">

    <!-- Header Navigation Back Bar -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <a href="<?= base_url('feed') ?>" class="btn btn-saas-dark rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" title="Kembali ke Beranda Feed">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="text-white font-heading m-0">Detail Postingan Status</h4>
                <p class="text-secondary style-tiny m-0">Melihat status dan percakapan komentar dari <?= esc($post['author_name']) ?></p>
            </div>
        </div>

        <a href="<?= base_url('feed') ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3">
            <i class="fa-solid fa-square-rss me-1.5"></i> Beranda Feed MM
        </a>
    </div>

    <div class="row g-4">
        <!-- Main Single Post Content Area -->
        <div class="col-12 col-lg-8">
            <?= view('App\Modules\Feed\Views\_post_card', ['post' => $post]) ?>
        </div>

        <!-- Right Sidebar Info -->
        <div class="col-12 col-lg-4">
            <!-- Author Card -->
            <div class="saas-card p-4 mb-4 border border-secondary border-opacity-25 bg-body-tertiary">
                <h6 class="text-white font-heading mb-3 pb-2 border-bottom border-secondary border-opacity-25 style-tiny fw-bold text-uppercase tracking-wider">
                    <i class="fa-solid fa-user-gear text-danger me-1.5"></i> Pembuat Postingan
                </h6>

                <div class="d-flex align-items-center gap-3">
                    <a href="<?= base_url('feed/user/' . $post['user_id']) ?>">
                        <img src="<?= avatar_url($post['author_avatar'], $post['author_name']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-danger border-opacity-50" style="width: 52px; height: 52px;" onerror="this.onerror=null; this.src='<?= base_url('media/avatar?name=' . urlencode($post['author_name'])) ?>';">
                    </a>

                    <div class="overflow-hidden" style="min-width: 0;">
                        <div class="d-flex align-items-center gap-1.5 mb-0.5">
                            <a href="<?= base_url('feed/user/' . $post['user_id']) ?>" class="text-body fw-bold text-decoration-none hover-text-danger text-truncate">
                                <?= esc($post['author_name']) ?>
                            </a>
                            <?php if (!empty($post['author_verified'])): ?>
                                <i class="fa-solid fa-circle-check text-primary style-tiny" title="Akun Terverifikasi"></i>
                            <?php endif; ?>
                        </div>
                        <span class="badge bg-secondary bg-opacity-25 text-secondary style-tiny font-monospace"><?= esc($post['author_role_name']) ?></span>
                        <small class="text-secondary style-tiny d-block opacity-75 mt-1"><?= esc($post['author_class'] ?: 'Anggota MMC') ?></small>
                    </div>
                </div>

                <div class="mt-3 pt-3 border-top border-secondary border-opacity-25 d-grid">
                    <a href="<?= base_url('feed/user/' . $post['user_id']) ?>" class="btn btn-sm btn-saas-dark rounded-pill style-tiny">
                        <i class="fa-solid fa-id-card me-1.5"></i> Lihat Dinding Profil
                    </a>
                </div>
            </div>

            <!-- Recommendation Who to Follow Card -->
            <?php if (!empty($whoToFollow)): ?>
                <div class="saas-card p-4 border border-secondary border-opacity-25 bg-body-tertiary">
                    <h6 class="text-white font-heading mb-3 pb-2 border-bottom border-secondary border-opacity-25 style-tiny fw-bold text-uppercase tracking-wider">
                        <i class="fa-solid fa-user-plus text-info me-1.5"></i> Rekomendasi Anggota
                    </h6>

                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($whoToFollow as $member): ?>
                            <div class="d-flex align-items-center justify-content-between gap-2 style-tiny">
                                <div class="d-flex align-items-center gap-2 overflow-hidden" style="min-width: 0;">
                                    <a href="<?= base_url('feed/user/' . $member['id']) ?>" class="flex-shrink-0">
                                        <img src="<?= avatar_url($member['avatar'], $member['full_name']) ?>" alt="Avatar" class="rounded-circle object-fit-cover" style="width: 36px; height: 36px;" onerror="this.onerror=null; this.src='<?= base_url('media/avatar?name=' . urlencode($member['full_name'])) ?>';">
                                    </a>
                                    <div class="overflow-hidden" style="min-width: 0;">
                                        <a href="<?= base_url('feed/user/' . $member['id']) ?>" class="text-body fw-bold text-decoration-none hover-text-danger text-truncate d-block">
                                            <?= esc($member['full_name']) ?>
                                        </a>
                                        <small class="text-secondary style-tiny opacity-75 text-truncate d-block"><?= esc($member['class_dept'] ?: $member['role_name']) ?></small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
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

    function focusCommentInput(postId) {
        const input = document.querySelector('#comment-form-' + postId + ' input[name="comment"]');
        if (input) input.focus();
    }

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
</script>
<?= $this->endSection() ?>
