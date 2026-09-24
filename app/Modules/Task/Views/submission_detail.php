<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50 style-tiny font-monospace">
                <i class="fa-solid fa-file-circle-check me-1"></i> Pemeriksaan Jawaban
            </span>
            <span class="badge" style="background-color: <?= $submission['status_color'] ?? '#6c757d' ?>;">
                <?= esc($submission['status_name'] ?? 'In Review') ?>
            </span>
        </div>
        <h4 class="text-body font-heading m-0 fw-bold">Cek Hasil Jawaban: <?= esc($submission['full_name']) ?></h4>
        <p class="text-secondary small m-0">Tugas: <strong class="text-body"><?= esc($task['title']) ?></strong></p>
    </div>

    <div class="d-flex gap-2">
        <a href="<?= base_url('admin/tasks/detail/' . $task['id']) ?>" class="btn btn-saas-dark btn-sm text-body border border-secondary border-opacity-25">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Peninjauan Tugas
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Member Info & Submission Details -->
    <div class="col-lg-7">
        <!-- Member Profile & Submission Meta Card -->
        <div class="saas-card p-4 mb-4 border border-secondary border-opacity-25">
            <div class="d-flex align-items-center gap-3 mb-3">
                <?php if (!empty($submission['avatar'])): ?>
                    <img src="<?= base_url($submission['avatar']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-danger border-opacity-50" style="width: 48px; height: 48px; min-width: 48px;">
                <?php else: ?>
                    <div class="rounded-circle bg-danger bg-opacity-25 text-danger fw-bold d-flex align-items-center justify-content-center border border-danger border-opacity-25 fs-5" style="width: 48px; height: 48px; min-width: 48px;">
                        <?= strtoupper(substr($submission['full_name'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div>
                    <h5 class="text-body font-heading fw-bold m-0"><?= esc($submission['full_name']) ?></h5>
                    <div class="text-secondary small font-monospace">
                        NIS/NIP: <?= esc($submission['nis_nip'] ?: '-') ?> &bull; <?= esc($submission['class_dept'] ?: 'Siswa') ?>
                    </div>
                </div>
            </div>

            <div class="p-3 rounded-3 bg-body-secondary border border-secondary border-opacity-25 mb-4 font-monospace style-tiny">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-secondary"><i class="fa-solid fa-clock text-danger me-1"></i> Waktu Pengiriman:</span>
                    <strong class="text-body"><?= date('d F Y, H:i:s', strtotime($submission['submitted_at'])) ?> WIB</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-secondary"><i class="fa-solid fa-hourglass-half text-warning me-1"></i> Deadline Tugas:</span>
                    <span class="text-danger"><?= $task['deadline'] ? date('d F Y, H:i', strtotime($task['deadline'])) . ' WIB' : 'Tanpa Batas' ?></span>
                </div>
            </div>

            <div class="mb-4">
                <h6 class="text-body font-heading fw-bold mb-2.5 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-align-left text-danger"></i> Catatan / Jawaban Tugas:
                </h6>
                <div class="p-4 rounded-4 bg-body-secondary border border-secondary border-opacity-25 text-body shadow-sm" style="word-break: break-word; font-size: 0.925rem; line-height: 1.8; white-space: pre-wrap;"><?= !empty($submission['submission_text']) ? esc($submission['submission_text']) : '<span class="text-secondary fst-italic">Tidak ada catatan teks tambahan.</span>' ?></div>
            </div>

            <div class="mb-2">
                <h6 class="text-body font-heading fw-bold mb-2.5 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-paperclip text-info"></i> Tautan / Berkas Tugas:
                </h6>
                <?php if (!empty($submission['attachment_url'])): ?>
                    <?php $fileInfo = media_info($submission['attachment_url']); ?>
                    <div class="p-3.5 p-md-4 rounded-4 bg-body-secondary border border-secondary border-opacity-25 shadow-sm">
                        <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-2.5 min-w-0 flex-grow-1">
                                <i class="<?= $fileInfo['icon'] ?> fs-3 flex-shrink-0"></i>
                                <div class="min-w-0 flex-grow-1">
                                    <div class="text-body fw-bold small text-truncate" title="<?= esc($fileInfo['file_name']) ?>">
                                        <?= esc($fileInfo['file_name']) ?>
                                    </div>
                                    <small class="text-secondary style-tiny font-monospace d-block">
                                        <?= $fileInfo['is_external'] ? 'Tautan URL Eksternal' : ($fileInfo['size_formatted'] ? "{$fileInfo['label']} &bull; {$fileInfo['size_formatted']}" : 'File Berkas Upload') ?>
                                    </small>
                                </div>
                            </div>
                            <a href="<?= esc($fileInfo['url']) ?>" target="_blank" class="btn btn-red btn-sm px-3.5 py-2 font-monospace style-tiny fw-bold shadow-sm rounded-pill text-center flex-shrink-0">
                                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka / Unduh Berkas
                            </a>
                        </div>

                        <?php if ($fileInfo['type'] === 'image'): ?>
                            <div class="mt-3 pt-3 border-top border-secondary border-opacity-15 text-center">
                                <img src="<?= esc($fileInfo['url']) ?>" alt="Preview Tugas" class="img-fluid rounded-3 border border-secondary border-opacity-25 shadow-sm" style="max-height: 280px; object-fit: contain;" onerror="this.style.display='none';" />
                            </div>
                        <?php elseif ($fileInfo['type'] === 'video'): ?>
                            <div class="mt-3 pt-3 border-top border-secondary border-opacity-15">
                                <video src="<?= esc($fileInfo['url']) ?>" controls class="w-100 rounded-3 shadow-sm" style="max-height: 280px;"></video>
                            </div>
                        <?php elseif ($fileInfo['type'] === 'audio'): ?>
                            <div class="mt-3 pt-3 border-top border-secondary border-opacity-15">
                                <audio src="<?= esc($fileInfo['url']) ?>" controls class="w-100"></audio>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="p-3.5 rounded-4 bg-body-secondary border border-secondary border-opacity-25 text-secondary small">
                        <i class="fa-solid fa-circle-info text-secondary me-1"></i> Anggota tidak menyertakan tautan link / berkas upload.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Task Instruction Reference -->
        <div class="saas-card p-4 rounded-4 border border-secondary border-opacity-25 shadow-sm">
            <h6 class="text-body font-heading fw-bold mb-2.5 d-flex align-items-center gap-2">
                <i class="fa-solid fa-circle-info text-warning"></i> Instruksi Tugas Asli:
            </h6>
            <h6 class="text-body font-heading fw-bold mb-2"><?= esc($task['title']) ?></h6>
            <div class="text-secondary small" style="word-break: break-word; line-height: 1.75; white-space: pre-wrap;"><?= esc($task['description']) ?></div>
        </div>
    </div>

    <!-- Right Column: Evaluation & Scoring Form -->
    <div class="col-lg-5">
        <div class="saas-card p-4 border border-secondary border-opacity-25 h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom border-secondary border-opacity-25 pb-3">
                    <h5 class="text-body font-heading m-0 fw-bold">
                        <i class="fa-solid fa-star text-warning me-2"></i> Form Evaluasi &amp; Nilai
                    </h5>
                    <?php if ($submission['grade'] !== null): ?>
                        <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 px-2.5 py-1 font-monospace style-tiny">
                            Sudah Dinilai
                        </span>
                    <?php else: ?>
                        <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-50 px-2.5 py-1 font-monospace style-tiny">
                            Belum Dinilai
                        </span>
                    <?php endif; ?>
                </div>

                <form action="<?= base_url('admin/tasks/evaluate/' . $submission['id']) ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label text-body small fw-semibold">Status Hasil Review <span class="text-danger">*</span></label>
                        <select name="status_id" class="form-select bg-body-secondary text-body border-secondary border-opacity-50" required>
                            <?php foreach ($statuses as $st): ?>
                                <option value="<?= $st['id'] ?>" <?= $submission['status_id'] == $st['id'] ? 'selected' : '' ?>>
                                    Status: <?= esc($st['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-body small fw-semibold d-flex justify-content-between align-items-center">
                            <span>Nilai Angka (0 - 100) <span class="text-danger">*</span></span>
                            <span class="text-secondary style-tiny font-monospace">Bisa langsung diisi</span>
                        </label>
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-body-secondary text-warning border-secondary border-opacity-50"><i class="fa-solid fa-award"></i></span>
                            <input type="number" name="grade" id="detailGradeInput" class="form-control bg-body-secondary text-body border-secondary border-opacity-50 font-monospace fs-5 fw-bold" min="0" max="100" required value="<?= $submission['grade'] !== null ? esc($submission['grade']) : 85 ?>">
                            <span class="input-group-text bg-body-secondary text-secondary border-secondary border-opacity-50 font-monospace">/ 100</span>
                        </div>
                        <div class="d-flex flex-wrap gap-1.5 align-items-center mt-1.5">
                            <span class="text-secondary style-tiny me-1">Pilihan Cepat:</span>
                            <?php foreach ([60, 70, 75, 80, 85, 90, 95, 100] as $presetScore): ?>
                                <button type="button" class="btn btn-sm btn-saas-dark border border-secondary border-opacity-50 py-0.5 px-2 style-tiny font-monospace text-body" onclick="document.getElementById('detailGradeInput').value=<?= $presetScore ?>; document.getElementById('detailGradeInput').focus();">
                                    <?= $presetScore ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-body small fw-semibold">Catatan Evaluasi / Saran Feedback (Opsional)</label>
                        <textarea name="feedback" class="form-control bg-body-secondary text-body border-secondary border-opacity-50" rows="4" placeholder="Tuliskan masukan untuk evaluasi tugas atau catatan revisi bagi anggota..."><?= esc($submission['feedback']) ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-red w-100 py-2.5 font-heading fw-bold shadow-sm">
                        <i class="fa-solid fa-circle-check me-2"></i> Simpan Nilai &amp; Evaluasi
                    </button>
                </form>
            </div>

            <?php if (!empty($submission['evaluator_name'])): ?>
                <div class="pt-3 mt-4 border-top border-secondary border-opacity-10 style-tiny text-secondary font-monospace">
                    <i class="fa-solid fa-user-check text-success me-1"></i> Terakhir dievaluasi oleh: <strong class="text-body"><?= esc($submission['evaluator_name']) ?></strong>
                </div>
            <?php endif; ?>
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
    <form action="<?= base_url('admin/tasks/comment/' . $task['id']) ?>" method="POST" id="comment-form">
        <?= csrf_field() ?>

        <div class="p-1.5 px-3 rounded-pill bg-body-secondary border border-secondary border-opacity-50 d-flex align-items-center gap-2 shadow-sm">
            <input type="text" name="comment" id="comment-input" class="form-control bg-transparent border-0 text-body p-1 style-tiny shadow-none" placeholder="Ketik pesan" required autocomplete="off">
            <button type="submit" class="btn btn-red rounded-circle d-flex align-items-center justify-content-center p-0 flex-shrink-0" style="width: 38px; height: 38px;" title="Kirim Pesan">
                <i class="fa-solid fa-paper-plane text-white style-tiny"></i>
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
