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
                                        <img src="<?= avatar_url($post['author_avatar'], $post['author_name']) ?>" alt="Avatar" class="rounded-circle object-fit-cover img-fluid border border-secondary border-opacity-50" style="width: 42px; height: 42px;" onerror="this.onerror=null; this.src='<?= base_url('media/avatar?name=' . urlencode($post['author_name'])) ?>';">
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
                                            <?= esc($post['author_class'] ?: 'Anggota Klub MMC') ?> • <a href="<?= base_url('feed/post/' . $post['id']) ?>" class="text-secondary hover-text-danger text-decoration-none"><?= esc($post['time_ago']) ?></a>
                                        </small>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center gap-1.5 flex-shrink-0 ms-auto">

                                    <!-- Follow Button if not self -->
                                    <?php if (!$post['is_own_post']): ?>
                                        <?php $isFollowingAuthor = !empty($post['is_following_author']); ?>
                                        <form action="<?= base_url('feed/follow/' . $post['user_id']) ?>" method="POST" class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                            <button type="submit" class="btn btn-sm <?= $isFollowingAuthor ? 'btn-saas-dark text-secondary' : 'btn-outline-info' ?> rounded-pill style-tiny py-1 px-2.5 text-nowrap">
                                                <i class="fa-solid <?= $isFollowingAuthor ? 'fa-user-check' : 'fa-user-plus' ?> me-1"></i>
                                                <span><?= $isFollowingAuthor ? 'Diikuti' : 'Ikuti' ?></span>
                                            </button>
                                        </form>
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
                                <a href="<?= base_url('feed/post/' . $post['id']) ?>" class="text-body style-tiny mb-3 lh-base d-block text-decoration-none hover-text-main" title="Buka Detail Postingan">
                                    <span style="white-space: pre-line;"><?= esc($post['content']) ?></span>
                                </a>
                            <?php endif; ?>

                            <!-- Post Media Attachment -->
                            <?php if (!empty($post['media_url'])): ?>
                                <div class="mb-3 rounded-3 overflow-hidden bg-black border border-secondary border-opacity-25 text-center">
                                    <?php if ($post['media_type'] === 'image'): ?>
                                        <div class="position-relative overflow-hidden cursor-pointer" onclick="openMediaLightbox('<?= media_url($post['media_url']) ?>', 'image')" title="Klik untuk lihat gambar penuh (Fullscreen)">
                                            <img src="<?= media_url($post['media_url']) ?>" alt="Post Media" class="img-fluid object-fit-contain transition-all hover-scale" style="max-height: 450px; width: 100%;" onerror="this.onerror=null; this.src='<?= base_url('media/placeholder?path=' . urlencode($post['media_url'])) ?>';">
                                            <div class="position-absolute bottom-0 end-0 m-2.5 badge bg-black bg-opacity-75 text-white style-tiny py-1 px-2.5 rounded-2 border border-secondary border-opacity-50">
                                                <i class="fa-solid fa-expand me-1 text-info"></i> Perbesar
                                            </div>
                                        </div>
                                    <?php elseif ($post['media_type'] === 'video'): ?>
                                        <video src="<?= media_url($post['media_url']) ?>" controls class="w-100 rounded-3" style="max-height: 450px;"></video>
                                    <?php else: ?>
                                        <div class="p-3 d-flex align-items-center justify-content-between bg-black">
                                            <div class="d-flex align-items-center gap-2.5 overflow-hidden">
                                                <i class="fa-solid fa-file-pdf fs-3 text-danger"></i>
                                                <span class="text-white style-tiny fw-bold text-truncate"><?= esc(basename($post['media_url'])) ?></span>
                                            </div>
                                            <a href="<?= media_url($post['media_url']) ?>" target="_blank" class="btn btn-sm btn-danger rounded-pill style-tiny px-3">
                                                <i class="fa-solid fa-download me-1"></i> Unduh Berkas
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Interaction Stats & Actions Bar (Instagram Style) -->
                            <div class="d-flex align-items-center justify-content-between pt-3 mt-2 border-top border-secondary border-opacity-25 style-tiny">
                                <div class="d-flex align-items-center gap-2 gap-sm-3">
                                    <!-- Like Button (Form POST Direct with Scroll Anchor #post-ID) -->
                                    <form action="<?= base_url('feed/like/' . $post['id']) ?>" method="POST" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-saas-dark text-decoration-none border border-secondary border-opacity-25 d-inline-flex align-items-center justify-content-center gap-1.5 rounded-circle px-2 <?= !empty($post['is_liked']) ? 'text-danger fw-bold' : 'text-secondary hover-white' ?>" style="height: 36px; min-width: 36px;" title="Suka Postingan">
                                            <i class="<?= !empty($post['is_liked']) ? 'fa-solid text-danger' : 'fa-regular' ?> fa-heart fs-6 transition-all"></i>
                                            <span class="like-count font-monospace fw-bold" style="font-size: 0.75rem;"><?= $post['likes_count'] ?></span>
                                        </button>
                                    </form>

                                    <!-- Comment Button (Instagram Style) -->
                                    <button type="button" class="btn btn-sm btn-saas-dark text-decoration-none border border-secondary border-opacity-25 text-secondary hover-white d-inline-flex align-items-center justify-content-center gap-1.5 rounded-circle px-2" style="height: 36px; min-width: 36px;" onclick="focusCommentInput(<?= $post['id'] ?>)" title="Tulis Komentar">
                                        <i class="fa-regular fa-comment fs-6"></i>
                                        <span class="font-monospace fw-bold" style="font-size: 0.75rem;"><?= $post['comments_count'] ?></span>
                                    </button>

                                    <!-- Repost Button (Form POST Direct & Instagram Style) -->
                                    <form action="<?= base_url('feed/repost/' . $post['id']) ?>" method="POST" class="d-inline" onsubmit="return confirm('Unggah ulang (repost) status ini ke beranda Anda?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-saas-dark text-decoration-none border border-secondary border-opacity-25 text-secondary hover-success d-inline-flex align-items-center justify-content-center rounded-circle p-0" style="width: 36px; height: 36px;" title="Unggah Ulang (Repost)">
                                            <i class="fa-solid fa-repeat fs-6"></i>
                                        </button>
                                    </form>

                                    <!-- Share Dropdown Button (Instagram Style Paper Plane) -->
                                    <div class="dropdown d-inline-block">
                                        <button type="button" class="btn btn-sm btn-saas-dark text-decoration-none border border-secondary border-opacity-25 text-secondary hover-info p-0 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" data-bs-toggle="dropdown" aria-expanded="false" title="Bagikan Status">
                                            <i class="fa-regular fa-paper-plane fs-6"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark shadow-lg border border-secondary border-opacity-50 style-tiny p-1">
                                            <li>
                                                <button type="button" class="dropdown-item rounded style-tiny py-1.5" onclick="navigator.clipboard.writeText('<?= base_url('feed/post/' . $post['id']) ?>'); alert('📋 Tautan postingan berhasil disalin ke papan klip!');">
                                                    <i class="fa-solid fa-link text-info me-2"></i> Salin Tautan Postingan
                                                </button>
                                            </li>
                                            <li>
                                                <a class="dropdown-item rounded style-tiny py-1.5" href="https://api.whatsapp.com/send?text=<?= urlencode('Lihat status MMC dari ' . $post['author_name'] . ': ' . base_url('feed/post/' . $post['id'])) ?>" target="_blank">
                                                    <i class="fa-brands fa-whatsapp text-success me-2"></i> Bagikan ke WhatsApp
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Bookmark / Save Button (Form POST Direct & Database Integrated) -->
                                    <form action="<?= base_url('feed/save/' . $post['id']) ?>" method="POST" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-saas-dark text-decoration-none border border-secondary border-opacity-25 d-inline-flex align-items-center justify-content-center rounded-circle p-0 <?= !empty($post['is_bookmarked']) ? 'text-warning' : 'text-secondary' ?> hover-warning" style="width: 36px; height: 36px;" title="Simpan Postingan (Bookmark Database)">
                                            <i class="<?= !empty($post['is_bookmarked']) ? 'fa-solid text-warning' : 'fa-regular' ?> fa-bookmark fs-6"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Direct Link Button to Single Post Page -->
                                <a href="<?= base_url('feed/post/' . $post['id']) ?>" class="btn btn-sm btn-saas-dark text-secondary hover-white border border-secondary border-opacity-25 rounded-circle p-0 style-tiny text-decoration-none d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" title="Buka Detail Postingan">
                                    <i class="fa-solid fa-arrow-up-right-from-square style-tiny"></i>
                                </a>
                            </div>

                            <!-- Comment Section Accordion / Container -->
                            <div class="mt-3 pt-3 border-top border-secondary border-opacity-10">
                                
                                <!-- Existing Comments Feed -->
                                <div class="d-flex flex-column gap-2 mb-3" id="comments-list-<?= $post['id'] ?>">
                                    <?php foreach ($post['comments'] as $comment): ?>
                                        <div class="d-flex align-items-start gap-2.5 p-2.5 rounded-3 bg-body-secondary border border-secondary border-opacity-25" id="comment-<?= $comment['id'] ?>">
                                            <a href="<?= base_url('feed/user/' . $comment['user_id']) ?>">
                                                <img src="<?= avatar_url($comment['commenter_avatar'], $comment['commenter_name']) ?>" alt="Avatar" class="rounded-circle object-fit-cover" style="width: 30px; height: 30px;" onerror="this.onerror=null; this.src='<?= base_url('media/avatar?name=' . urlencode($comment['commenter_name'])) ?>';">
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
