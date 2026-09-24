<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <h4 class="text-body font-heading m-0 fw-bold">Kirim Tugas: <?= esc($task['title']) ?></h4>
        <p class="text-secondary small m-0">kirim tugas dan upload berkas/link bila ada.</p>
    </div>

    <a href="<?= base_url('member/tasks') ?>" class="btn btn-saas-dark btn-sm text-body border border-secondary border-opacity-25">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Tugas
    </a>
</div>

<div class="row g-4">
    <!-- Task Description -->
    <div class="col-lg-5">
        <div class="saas-card p-4 h-100 border border-secondary border-opacity-25">
            <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                <span class="badge" style="background-color: <?= $task['priority_color'] ?>;">
                    Prioritas: <?= esc($task['priority_name']) ?>
                </span>
                <span class="badge" style="background-color: <?= $myStatusColor ?? '#6c757d' ?>;">
                    Status Saya: <?= esc($myStatusName ?? 'Belum dikerjakan') ?>
                </span>
            </div>

            <h5 class="text-body font-heading fw-bold mb-3"><?= esc($task['title']) ?></h5>
            <div class="text-secondary small leading-relaxed mb-4" style="word-break: break-word;"><?= nl2br(esc($task['description'])) ?></div>

            <div class="p-3 rounded-3 bg-body-secondary border border-secondary border-opacity-25 mb-3 font-monospace small">
                <div class="text-danger"><i class="fa-solid fa-clock me-1"></i> Deadline: <?= $task['deadline'] ? date('d M Y, H:i', strtotime($task['deadline'])) . ' WIB' : 'Tanpa Batas' ?></div>
            </div>

            <?php if ($submission && $submission['grade'] !== null): ?>
                <div class="p-3 rounded-3 bg-success bg-opacity-10 border border-success border-opacity-25 mb-3">
                    <h6 class="text-success font-heading mb-1"><i class="fa-solid fa-medal me-1"></i> Hasil Evaluasi Pembina / BPH</h6>
                    <div class="display-6 font-heading fw-bold text-body mb-2"><?= esc($submission['grade']) ?> / 100</div>
                    <?php if (!empty($submission['feedback'])): ?>
                        <div class="text-secondary small"><strong>Catatan Feedback:</strong> <?= esc($submission['feedback']) ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Penerima Tugas (Assignees List) -->
            <div class="p-3 rounded-3 bg-body-secondary border border-secondary border-opacity-25 mb-2">
                <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom border-secondary border-opacity-15">
                    <h6 class="text-body font-heading fw-bold mb-0 style-tiny">
                        <i class="fa-solid fa-users text-danger me-1.5"></i> Penerima Tugas (Assignees)
                    </h6>
                    <span class="badge bg-body border border-secondary border-opacity-25 text-secondary style-tiny font-monospace">
                        <?= count($task['assignees'] ?? []) ?> Anggota
                    </span>
                </div>

                <div class="d-flex flex-column gap-2" style="max-height: 240px; overflow-y: auto;">
                    <?php 
                        $currentUserId = session()->get('user_id');
                        $assignees = $task['assignees'] ?? [];
                    ?>
                    <?php if (empty($assignees)): ?>
                        <div class="text-secondary style-tiny fst-italic py-1">Belum ada anggota yang ditugaskan.</div>
                    <?php else: ?>
                        <?php foreach ($assignees as $ass): ?>
                            <?php $isMe = ($ass['id'] == $currentUserId); ?>
                            <div class="d-flex align-items-center justify-content-between p-2 rounded-2 <?= $isMe ? 'bg-danger bg-opacity-10 border border-danger border-opacity-35' : 'bg-body border border-secondary border-opacity-15' ?>">
                                <div class="d-flex align-items-center gap-2 min-w-0">
                                    <?php if (!empty($ass['avatar'])): ?>
                                        <img src="<?= base_url($ass['avatar']) ?>" alt="Avatar" class="rounded-circle object-fit-cover flex-shrink-0" style="width: 26px; height: 26px;">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-danger bg-opacity-25 text-danger d-flex align-items-center justify-content-center fw-bold flex-shrink-0 style-tiny" style="width: 26px; height: 26px;">
                                            <?= strtoupper(substr($ass['full_name'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="min-w-0">
                                        <div class="text-body fw-semibold text-truncate style-tiny" title="<?= esc($ass['full_name']) ?>">
                                            <?= esc($ass['full_name']) ?>
                                            <?php if ($isMe): ?>
                                                <span class="badge bg-danger text-white style-tiny ms-1" style="font-size: 9px;">Anda</span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($ass['class_dept'])): ?>
                                            <div class="text-secondary font-monospace style-tiny" style="font-size: 10px;"><?= esc($ass['class_dept']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <span class="badge rounded-pill style-tiny font-monospace flex-shrink-0 ms-2" style="background-color: <?= $ass['status_color'] ?? '#6c757d' ?>;">
                                    <?= esc($ass['status_name'] ?? 'Belum dikerjakan') ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Submission Form -->
    <div class="col-lg-7">
        <div class="saas-card p-4 border border-secondary border-opacity-25">
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom border-secondary border-opacity-25 pb-3">
                <h5 class="text-body font-heading m-0 fw-bold">
                    <i class="fa-solid fa-upload text-danger me-2"></i> Form Pengiriman Tugas
                </h5>
                <?php if ($submission): ?>
                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 style-tiny font-monospace">
                        <i class="fa-solid fa-check me-1"></i> Pernah Dikirim
                    </span>
                <?php endif; ?>
            </div>

            <form action="<?= base_url('member/tasks/submit/' . $task['id']) ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label text-body small fw-semibold">Catatan</label>
                    <textarea name="submission_text" class="form-control bg-body-secondary text-body border-secondary border-opacity-50" rows="3" placeholder="Tuliskan catatan atau jawaban tugas kamu disini"><?= esc($submission['submission_text'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label text-body small fw-semibold">Link tugas (Opsional)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-body-secondary text-secondary border-secondary border-opacity-50"><i class="fa-solid fa-link"></i></span>
                        <input type="url" name="attachment_url" class="form-control bg-body-secondary text-body border-secondary border-opacity-50" placeholder="https://drive.google.com/..." value="<?= (!empty($submission['attachment_url']) && strpos($submission['attachment_url'], 'uploads/tasks/') === false) ? esc($submission['attachment_url']) : '' ?>">
                    </div>
                </div>

                <?php if (!empty($submission['attachment_url'])): ?>
                    <?php $fileInfo = media_info($submission['attachment_url']); ?>
                    <div class="mb-3.5 p-3 rounded-3 bg-body-secondary border border-secondary border-opacity-25 shadow-sm">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                            <span class="text-secondary style-tiny fw-bold font-monospace">
                                <i class="fa-solid fa-paperclip me-1 text-danger"></i> BERKAS TERLAMPIR
                            </span>
                            <a href="<?= base_url('member/tasks/delete-attachment/' . $task['id']) ?>" 
                               class="btn btn-outline-danger btn-sm py-0.5 px-2.5 rounded-pill style-tiny font-monospace"
                               onclick="return confirm('Apakah Anda yakin ingin menghapus berkas lampiran tugas ini?');"
                               title="Hapus berkas lampiran">
                                <i class="fa-solid fa-trash-can me-1"></i> Hapus Berkas
                            </a>
                        </div>

                        <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-2.5 pt-2 border-top border-secondary border-opacity-15">
                            <div class="d-flex align-items-center gap-2 min-w-0 flex-grow-1">
                                <i class="<?= $fileInfo['icon'] ?> fs-4 flex-shrink-0"></i>
                                <div class="min-w-0 flex-grow-1">
                                    <div class="text-body fw-semibold small text-truncate" title="<?= esc($fileInfo['file_name']) ?>">
                                        <?= esc($fileInfo['file_name']) ?>
                                    </div>
                                    <small class="text-secondary style-tiny font-monospace d-block">
                                        <?= $fileInfo['is_external'] ? 'Tautan URL Eksternal' : ($fileInfo['size_formatted'] ? "{$fileInfo['label']} &bull; {$fileInfo['size_formatted']}" : 'File Berkas Upload') ?>
                                    </small>
                                </div>
                            </div>
                            <a href="<?= esc($fileInfo['url']) ?>" target="_blank" class="btn btn-saas-dark btn-sm text-body border border-secondary border-opacity-50 style-tiny font-monospace px-3 py-1 text-center flex-shrink-0">
                                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka / Unduh
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="mb-4">
                    <label class="form-label text-body small fw-semibold">
                        <?= !empty($submission['attachment_url']) ? 'Ganti / Unggah Berkas Baru (Opsional)' : 'Upload berkas (Opsional)' ?>
                    </label>
                    <input type="file" name="attachment_file" class="form-control bg-body-secondary text-body border-secondary border-opacity-50">
                    <div class="form-text text-secondary style-tiny">Format: ZIP, RAR, PDF, PNG, JPG, MP4 (Max: 20MB)</div>
                </div>

                <button type="submit" class="btn btn-red w-100 py-2.5 font-heading fw-bold shadow-sm">
                    <i class="fa-solid fa-paper-plane me-2"></i> Kirim Tugas
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Diskusi & Catatan Revisi Tugas (WhatsApp-style Chat Feed) -->
<div class="saas-card p-4 mt-4 border border-secondary border-opacity-25">
    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom border-secondary border-opacity-25 pb-3">
        <h5 class="text-body font-heading m-0 fw-bold">
            <i class="fa-solid fa-comments text-danger me-2"></i> Diskusi &amp; Catatan Revisi Tugas
        </h5>
        <span class="badge bg-body-secondary border border-secondary text-body font-monospace">
            <?= count($comments ?? []) ?> Pesan
        </span>
    </div>

    <!-- Feed Messages Container -->
    <div class="d-flex flex-column gap-3 mb-4" style="max-height: 400px; overflow-y: auto;" id="comments-container">
        <?php if (empty($comments)): ?>
            <div class="text-center py-4 text-secondary small">
                <i class="fa-regular fa-comment-dots fs-3 d-block mb-2 text-opacity-50"></i>
                Belum ada diskusi atau catatan revisi pada tugas ini. Tulis pesan di bawah ini!
            </div>
        <?php else: ?>
            <?php foreach ($comments as $c): ?>
                <div class="p-3 rounded-3 bg-body-secondary border border-secondary border-opacity-25">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-danger bg-opacity-25 text-danger fw-bold d-flex align-items-center justify-content-center style-tiny" style="width: 28px; height: 28px;">
                                <?= strtoupper(substr($c['full_name'], 0, 1)) ?>
                            </div>
                            <div>
                                <span class="text-body small fw-bold"><?= esc($c['full_name']) ?></span>
                                <span class="badge bg-body border border-secondary text-secondary style-tiny ms-1"><?= esc($c['role_name'] ?: 'Member') ?></span>
                            </div>
                        </div>
                        <span class="text-secondary font-monospace style-tiny">
                            <?= date('d M Y, H:i', strtotime($c['created_at'])) ?>
                        </span>
                    </div>

                    <div class="text-body small leading-relaxed ps-1">
                        <?= nl2br(esc($c['comment'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- WhatsApp-Style Message Input Bar -->
    <form action="<?= base_url('member/tasks/comment/' . $task['id']) ?>" method="POST" id="comment-form">
        <?= csrf_field() ?>

        <div class="p-1.5 px-3 rounded-pill bg-body-secondary border border-secondary border-opacity-50 d-flex align-items-center gap-2 shadow-sm">
            <input type="text" name="comment" id="comment-input" class="form-control bg-transparent border-0 text-body p-1 style-tiny shadow-none" placeholder="Ketik pesan atau pertanyaan revisi..." required autocomplete="off">
            <button type="submit" class="btn btn-red rounded-circle d-flex align-items-center justify-content-center p-0 flex-shrink-0" style="width: 38px; height: 38px;" title="Kirim Pesan">
                <i class="fa-solid fa-paper-plane text-white style-tiny"></i>
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

