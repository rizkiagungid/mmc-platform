<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Peninjauan & Evaluasi Tugas</h4>
        <p class="text-secondary small m-0">Detail instruksi proyek dan daftar hasil karya yang telah dikirimkan oleh anggota</p>
    </div>

    <div class="d-flex gap-2">
        <a href="<?= base_url('admin/tasks/edit/' . $task['id']) ?>" class="btn btn-outline-warning btn-sm">
            <i class="fa-solid fa-pen me-1"></i> Edit Tugas
        </a>
        <a href="<?= base_url('admin/tasks') ?>" class="btn btn-saas-dark btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Task Meta Card -->
    <div class="col-lg-8">
        <div class="saas-card p-4 h-100">
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="badge" style="background-color: <?= $task['priority_color'] ?>;">
                    Prioritas: <?= esc($task['priority_name']) ?>
                </span>
                <span class="text-secondary small font-monospace ms-auto">
                    <i class="fa-solid fa-clock text-danger me-1"></i> Deadline: <?= $task['deadline'] ? date('d M Y, H:i', strtotime($task['deadline'])) : 'Tanpa Batas' ?>
                </span>
            </div>

            <h4 class="text-white font-heading mb-3"><?= esc($task['title']) ?></h4>
            <p class="text-secondary leading-relaxed mb-4"><?= nl2br(esc($task['description'])) ?></p>

            <div class="border-top border-secondary border-opacity-25 pt-3">
                <label class="text-white small fw-semibold mb-2"><i class="fa-solid fa-users text-danger me-2"></i> Anggota Assignee Terdaftar:</label>
                <div class="d-flex flex-wrap gap-2">
                    <?php foreach ($task['assignees'] as $a): ?>
                        <div class="p-2 rounded-3 bg-dark border border-secondary border-opacity-25 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-user-circle text-danger fs-5"></i>
                            <div>
                                <div class="text-white small fw-semibold"><?= esc($a['full_name']) ?></div>
                                <div class="text-secondary style-tiny"><?= esc($a['class_dept'] ?: 'Siswa') ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-lg-4">
        <div class="saas-card p-4 h-100 d-flex flex-column justify-content-between">
            <h6 class="text-white font-heading mb-3"><i class="fa-solid fa-chart-pie text-danger me-2"></i> Progres Pengumpulan</h6>

            <?php
                $totalAssignees = count($task['assignees']);
                $totalSubmitted = count($submissions);
            ?>

            <div class="text-center py-3">
                <div class="display-3 font-heading fw-bold text-white mb-1"><?= $totalSubmitted ?> / <?= $totalAssignees ?></div>
                <div class="text-secondary small">Anggota telah mengumpulkan karya</div>
            </div>

            <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 text-secondary small">
                <i class="fa-solid fa-circle-info text-info me-1"></i> Klik tombol **Evaluasi** di bawah untuk memberikan nilai dan feedback ulasan karya.
            </div>
        </div>
    </div>
</div>

<!-- Submissions List -->
<div class="saas-card p-4">
    <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-folder-open text-danger me-2"></i> Daftar Pengumpulan Karya Anggota</h5>

    <?php if (empty($submissions)): ?>
        <div class="text-center py-5 text-secondary">
            <i class="fa-solid fa-inbox display-1 mb-3 text-secondary opacity-50"></i>
            <h5 class="text-white font-heading">Belum Ada Pengumpulan Karya</h5>
            <p class="small mb-0">Anggota assignee belum mengirimkan tautan atau berkas hasil pekerjaan.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-dark table-dark-saas w-100 align-middle">
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Nama Anggota</th>
                        <th>Waktu Pengiriman</th>
                        <th>Catatan / Tautan Karya</th>
                        <th>Nilai (Grade)</th>
                        <th>Status Review</th>
                        <th class="text-center" style="width: 130px;">Aksi Evaluasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($submissions as $i => $sub): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <div class="fw-semibold text-white"><?= esc($sub['full_name']) ?></div>
                                <div class="text-secondary small font-monospace"><?= esc($sub['nis_nip'] ?: '-') ?></div>
                            </td>
                            <td class="font-monospace small"><?= date('H:i:s, d M Y', strtotime($sub['submitted_at'])) ?></td>
                            <td>
                                <div class="text-white small mb-1"><?= nl2br(esc($sub['submission_text'])) ?></div>
                                <?php if (!empty($sub['attachment_url'])): ?>
                                    <a href="<?= esc($sub['attachment_url']) ?>" target="_blank" class="btn btn-sm btn-outline-danger py-0 px-2 font-monospace" style="font-size: 0.75rem;">
                                        <i class="fa-solid fa-external-link me-1"></i> Buka Tautan / File Karya
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-danger fs-6 font-monospace">
                                    <?= $sub['grade'] !== null ? esc($sub['grade']) . ' / 100' : 'Belum Dinilai' ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge" style="background-color: <?= $sub['status_color'] ?? '#6c757d' ?>;">
                                    <?= esc($sub['status_name'] ?? 'Review') ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-red" data-bs-toggle="modal" data-bs-target="#evaluateModal_<?= $sub['id'] ?>">
                                    <i class="fa-solid fa-star me-1"></i> Evaluasi
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Evaluasi Submission -->
                        <div class="modal fade" id="evaluateModal_<?= $sub['id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
                                    <div class="modal-header border-bottom border-secondary border-opacity-25">
                                        <h5 class="modal-title font-heading"><i class="fa-solid fa-star text-warning me-2"></i> Evaluasi Karya: <?= esc($sub['full_name']) ?></h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="<?= base_url('admin/tasks/evaluate/' . $sub['id']) ?>" method="POST">
                                        <?= csrf_field() ?>

                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label text-secondary small">Status Hasil Peninjauan <span class="text-danger">*</span></label>
                                                <select name="status_id" class="form-select" required>
                                                    <?php foreach ($statuses as $st): ?>
                                                        <option value="<?= $st['id'] ?>" <?= $sub['status_id'] == $st['id'] ? 'selected' : '' ?>>
                                                            <?= esc($st['name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label text-secondary small">Nilai Angka (0 - 100) <span class="text-danger">*</span></label>
                                                <input type="number" name="grade" class="form-control" min="0" max="100" required value="<?= $sub['grade'] !== null ? esc($sub['grade']) : 85 ?>">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label text-secondary small">Feedback & Catatan Revisi (Opsional)</label>
                                                <textarea name="feedback" class="form-control" rows="3" placeholder="Tuliskan masukan untuk peningkatan karya atau catatan revisi..."><?= esc($sub['feedback']) ?></textarea>
                                            </div>
                                        </div>

                                        <div class="modal-footer border-top border-secondary border-opacity-25">
                                            <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-red">Simpan Evaluasi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- Diskusi & Catatan Revisi (ClickUp Activity Feed) -->
    <div class="saas-card p-4 mt-4">
        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom border-secondary border-opacity-25 pb-3">
            <h5 class="text-white font-heading m-0">
                <i class="fa-solid fa-comments text-danger me-2"></i> Diskusi & Catatan Revisi Tugas
            </h5>
            <span class="badge bg-dark border border-secondary text-white font-monospace">
                <?= count($comments ?? []) ?> Pesan
            </span>
        </div>

        <!-- Feed Messages -->
        <div class="d-flex flex-column gap-3 mb-4" style="max-height: 400px; overflow-y: auto;" id="comments-container">
            <?php if (empty($comments)): ?>
                <div class="text-center py-4 text-secondary small">
                    <i class="fa-regular fa-comment-dots fs-3 d-block mb-2 text-opacity-50"></i>
                    Belum ada diskusi atau catatan revisi pada tugas ini. Tulis pesan pertama di bawah ini!
                </div>
            <?php else: ?>
                <?php foreach ($comments as $c): ?>
                    <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-danger bg-opacity-25 text-danger fw-bold d-flex align-items-center justify-content-center style-tiny" style="width: 32px; height: 32px;">
                                    <?= strtoupper(substr($c['full_name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <span class="text-white small fw-bold"><?= esc($c['full_name']) ?></span>
                                    <span class="badge bg-black border border-secondary text-secondary style-tiny ms-1"><?= esc($c['role_name'] ?: 'Member') ?></span>
                                </div>
                            </div>
                            <span class="text-secondary font-monospace style-tiny">
                                <?= date('d M Y, H:i', strtotime($c['created_at'])) ?>
                            </span>
                        </div>

                        <?php
                            $formattedComment = preg_replace(
                                '/@([a-zA-Z0-9_\.\-]+)/', 
                                '<span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 px-2 py-1"><i class="fa-solid fa-at me-1"></i>$1</span>', 
                                esc($c['comment'])
                            );
                        ?>
                        <div class="text-white small leading-relaxed ps-1">
                            <?= nl2br($formattedComment) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Post Comment Form -->
        <form action="<?= base_url('admin/tasks/comment/' . $task['id']) ?>" method="POST" id="comment-form">
            <?= csrf_field() ?>

            <div class="input-group">
                <textarea name="comment" id="comment-textarea" class="form-control bg-black text-white border-secondary border-opacity-50" rows="2" placeholder="Tuliskan masukan, catatan revisi, atau balasan..." required></textarea>
                <button type="submit" class="btn btn-red px-4 font-heading fw-semibold">
                    <i class="fa-solid fa-paper-plane me-1"></i> Kirim
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.querySelectorAll('.tag-user-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const username = this.getAttribute('data-username');
        const textarea = document.getElementById('comment-textarea');
        textarea.value += ' @' + username + ' ';
        textarea.focus();
    });
});
</script>
<?= $this->endSection() ?>
