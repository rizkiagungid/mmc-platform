<?php
/** Partial: single post card. Variable: $post */
$roleSlug = session()->get('role_slug');
$isAdmin  = in_array($roleSlug, ['superadmin', 'pembina', 'bph']);
?>
                        <div class="saas-card p-3 p-md-4 border border-secondary border-opacity-25 bg-body-tertiary" id="post-<?= $post['id'] ?>">
                            
                            <!-- Post Author Header -->
                            <div class="d-flex align-items-start justify-content-between mb-3 gap-2" style="min-width: 0;">
                                <div class="d-flex align-items-center gap-2.5 overflow-hidden" style="min-width: 0; flex-grow: 1;">
                                    <a href="<?= base_url('feed/user/' . $post['user_id']) ?>" class="flex-shrink-0">
                                        <?php if (!empty($post['author_avatar'])): ?>
                                            <img src="<?= base_url($post['author_avatar']) ?>" alt="Avatar" class="rounded-circle object-fit-cover img-fluid border border-secondary border-opacity-50" style="width: 42px; height: 42px;" onerror="this.onerror=null; this.src='<?= base_url('assets/logo-mm-2023.png') ?>';">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold fs-6" style="width: 42px; height: 42px;">
                                                <?= strtoupper(substr($post['author_name'], 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                    </a>

                                    <div style="min-width: 0; flex-grow: 1;" class="overflow-hidden">
                                        <div class="d-flex align-items-center flex-wrap gap-1 style-tiny">
                                            <a href="<?= base_url('feed/user/' . $post['user_id']) ?>" class="text-body fw-bold text-decoration-none hover-text-danger text-truncate d-inline-block" style="max-width: 100%;">
                                                <?= esc($post['author_name']) ?>
                                            </a>
                                            
                                            <!-- Verified Blue Checkmark Badge for Superadmin/Pembina/BPH -->
                                            <?php if (!empty($post['author_verified'])): ?>
                                                <i class="fa-solid fa-circle-check text-primary flex-shrink-0" style="font-size: 0.75rem;" title="Akun Terverifikasi (Pengurus / Pembina MMC)"></i>
                                            <?php endif; ?>

                                            <span class="badge bg-secondary bg-opacity-25 text-secondary style-tiny flex-shrink-0" style="font-size: 0.65rem;"><?= esc($post['author_role_name']) ?></span>
                                        </div>
                                        
                                        <small class="text-secondary style-tiny opacity-75 font-monospace d-block text-truncate" style="font-size: 0.65rem;">
                                            <?= esc($post['author_class'] ?: 'Anggota Klub MMC') ?> • <?= esc($post['time_ago']) ?>
                                        </small>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center gap-1.5 flex-shrink-0 ms-auto">
                                    <!-- Follow Button if not self -->
                                    <?php if (!$post['is_own_post']): ?>
                                        <button type="button" class="btn btn-sm <?= $post['is_following_author'] ? 'btn-saas-dark text-secondary' : 'btn-outline-info' ?> rounded-pill style-tiny py-1 px-2.5 text-nowrap" onclick="toggleFollowUser(<?= $post['user_id'] ?>, this)">
                                            <i class="fa-solid <?= $post['is_following_author'] ? 'fa-user-check' : 'fa-user-plus' ?> me-1"></i>
                                            <span><?= $post['is_following_author'] ? 'Diikuti' : 'Ikuti' ?></span>
                                        </button>
                                    <?php endif; ?>

                                    <!-- Delete Post Button -->
                                    <?php if ($post['is_own_post'] || in_array(session()->get('role_slug'), ['superadmin', 'pembina', 'bph'])): ?>
                                        <a href="<?= base_url('feed/delete/' . $post['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus postingan status ini?')" class="btn btn-sm btn-saas-dark text-danger border-0 p-1.5 rounded-circle flex-shrink-0" title="Hapus Status">
                                            <i class="fa-solid fa-trash-can style-tiny"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
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
                                            <img src="<?= base_url($post['media_url']) ?>" alt="Post Media" class="img-fluid object-fit-contain transition-all hover-scale" style="max-height: 450px; width: 100%;">
                                            <div class="position-absolute bottom-0 end-0 m-2.5 badge bg-black bg-opacity-75 text-white style-tiny py-1 px-2.5 rounded-2 border border-secondary border-opacity-50">
                                                <i class="fa-solid fa-expand me-1 text-info"></i> Perbesar
                                            </div>
                                        </div>
                                    <?php elseif ($post['media_type'] === 'video'): ?>
                                        <video src="<?= base_url($post['media_url']) ?>" controls class="w-100 rounded-3" style="max-height: 450px;"></video>
                                    <?php else: ?>
                                        <div class="p-3 d-flex align-items-center justify-content-between bg-black">
                                            <div class="d-flex align-items-center gap-2.5 overflow-hidden">
                                                <i class="fa-solid fa-file-pdf fs-3 text-danger"></i>
                                                <span class="text-white style-tiny fw-bold text-truncate"><?= esc(basename($post['media_url'])) ?></span>
                                            </div>
                                            <a href="<?= base_url($post['media_url']) ?>" target="_blank" class="btn btn-sm btn-danger rounded-pill style-tiny px-3">
                                                <i class="fa-solid fa-download me-1"></i> Unduh Berkas
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Interaction Stats & Actions Bar -->
                            <div class="d-flex align-items-center justify-content-between pt-2 border-top border-secondary border-opacity-25 style-tiny">
                                <div class="d-flex align-items-center gap-4">
                                    <!-- Like Button -->
                                    <button type="button" class="btn btn-link text-decoration-none p-0 border-0 d-flex align-items-center gap-1.5 <?= $post['is_liked'] ? 'text-danger fw-bold' : 'text-secondary hover-white' ?>" onclick="toggleLikePost(<?= $post['id'] ?>, this)">
                                        <i class="<?= $post['is_liked'] ? 'fa-solid text-danger' : 'fa-regular' ?> fa-heart fs-6 transition-all"></i>
                                        <span class="like-count"><?= $post['likes_count'] ?></span> Suka
                                    </button>

                                    <!-- Comment Count Button -->
                                    <button type="button" class="btn btn-link text-decoration-none p-0 border-0 text-secondary hover-white d-flex align-items-center gap-1.5" onclick="focusCommentInput(<?= $post['id'] ?>)">
                                        <i class="fa-regular fa-comment fs-6"></i>
                                        <span><?= $post['comments_count'] ?></span> Komentar
                                    </button>
                                </div>
                            </div>

                            <!-- Comment Section Accordion / Container -->
                            <div class="mt-3 pt-3 border-top border-secondary border-opacity-10">
                                
                                <!-- Existing Comments Feed -->
                                <div class="d-flex flex-column gap-2 mb-3" id="comments-list-<?= $post['id'] ?>">
                                    <?php foreach ($post['comments'] as $comment): ?>
                                        <div class="d-flex align-items-start gap-2.5 p-2.5 rounded-3 bg-body-secondary border border-secondary border-opacity-25" id="comment-<?= $comment['id'] ?>">
                                            <a href="<?= base_url('feed/user/' . $comment['user_id']) ?>">
                                                <?php if (!empty($comment['commenter_avatar'])): ?>
                                                    <img src="<?= base_url($comment['commenter_avatar']) ?>" alt="Avatar" class="rounded-circle object-fit-cover" style="width: 30px; height: 30px;">
                                                <?php else: ?>
                                                    <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold style-tiny" style="width: 30px; height: 30px;">
                                                        <?= strtoupper(substr($comment['commenter_name'], 0, 1)) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </a>

                                            <div class="flex-grow-1 style-tiny">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center gap-1">
                                                        <a href="<?= base_url('feed/user/' . $comment['user_id']) ?>" class="text-body fw-bold text-decoration-none hover-text-danger">
                                                            <?= esc($comment['commenter_name']) ?>
                                                        </a>
                                                        <?php if (!empty($comment['commenter_verified'])): ?>
                                                            <i class="fa-solid fa-circle-check text-primary" style="font-size: 0.65rem;" title="Akun Terverifikasi (Pengurus / Pembina MMC)"></i>
                                                        <?php endif; ?>
                                                        <small class="text-secondary opacity-75 ms-1" style="font-size: 0.65rem;"><?= esc($comment['time_ago']) ?></small>
                                                    </div>

                                                    <?php if ($comment['is_own_comment'] || $post['is_own_post'] || in_array(session()->get('role_slug'), ['superadmin', 'pembina', 'bph'])): ?>
                                                        <a href="<?= base_url('feed/delete-comment/' . $comment['id']) ?>" onclick="return confirm('Hapus komentar ini?')" class="text-danger style-tiny p-0 border-0 opacity-75 hover-opacity-100" title="Hapus Komentar">
                                                            <i class="fa-solid fa-xmark"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="text-body m-0 mt-0.5" style="font-size: 0.825rem;"><?= esc($comment['comment']) ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Add Comment Form -->
                                <form action="<?= base_url('feed/comment/' . $post['id']) ?>" method="POST" class="d-flex align-items-center gap-2" id="comment-form-<?= $post['id'] ?>">
                                    <?= csrf_field() ?>
                                    <input type="text" name="comment" class="form-control bg-body border-secondary border-opacity-50 text-body rounded-pill px-3 py-1.5 style-tiny" placeholder="Tulis komentar..." required autocomplete="off">
                                    <button type="submit" class="btn btn-sm btn-red rounded-circle p-2 flex-shrink-0" style="width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;">
                                        <i class="fa-solid fa-paper-plane style-tiny"></i>
                                    </button>
                                </form>

                            </div>

                        </div>
