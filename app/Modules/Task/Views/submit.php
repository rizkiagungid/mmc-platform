<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Kirim Karya Tugas: <?= esc($task['title']) ?></h4>
        <p class="text-secondary small m-0">Unggah hasil karya proyek Anda atau sertakan tautan Google Drive / Figma / YouTube</p>
    </div>

    <a href="<?= base_url('member/tasks') ?>" class="btn btn-saas-dark">
        <i class="fa-solid fa-arrow-left me-1"></i> Batal & Kembali
    </a>
</div>

<div class="row g-4">
    <!-- Task Description -->
    <div class="col-lg-5">
        <div class="saas-card p-4 h-100">
            <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                <span class="badge" style="background-color: <?= $task['priority_color'] ?>;">
                    Prioritas: <?= esc($task['priority_name']) ?>
                </span>
                <span class="badge" style="background-color: <?= $myStatusColor ?? '#3b82f6' ?>;">
                    Status Saya: <?= esc($myStatusName ?? 'Todo') ?>
                </span>
            </div>

            <h5 class="text-white font-heading mb-3"><?= esc($task['title']) ?></h5>
            <p class="text-secondary small leading-relaxed mb-4"><?= nl2br(esc($task['description'])) ?></p>

            <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 mb-3 font-monospace small">
                <div class="text-danger"><i class="fa-solid fa-clock me-1"></i> Deadline: <?= $task['deadline'] ? date('d M Y, H:i', strtotime($task['deadline'])) : 'Tanpa Batas' ?></div>
            </div>

            <?php if ($submission && $submission['grade'] !== null): ?>
                <div class="p-3 rounded-3 bg-success bg-opacity-10 border border-success border-opacity-25 mb-3">
                    <h6 class="text-success font-heading mb-1"><i class="fa-solid fa-medal me-1"></i> Hasil Evaluasi Pembina</h6>
                    <div class="display-6 font-heading fw-bold text-white mb-2"><?= esc($submission['grade']) ?> / 100</div>
                    <?php if (!empty($submission['feedback'])): ?>
                        <div class="text-secondary small"><strong>Catatan Feedback:</strong> <?= esc($submission['feedback']) ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Submission Form -->
    <div class="col-lg-7">
        <div class="saas-card p-4">
            <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-upload text-danger me-2"></i> Form Pengiriman & Update Status Karya</h5>

            <form action="<?= base_url('member/tasks/submit/' . $task['id']) ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label text-white small fw-bold">
                        <i class="fa-solid fa-bars-progress text-danger me-1"></i> Status Pengerjaan Saya
                    </label>
                    <select name="my_status_id" class="form-select bg-dark text-white border-secondary border-opacity-50">
                        <?php foreach ($statuses as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= (isset($myStatusId) && $myStatusId == $s['id']) ? 'selected' : '' ?>>
                                Status: <?= esc($s['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text text-secondary style-tiny">Ubah status pengerjaan Anda sendiri sesuai progres pengerjaan Anda.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-medium">Catatan / Deskripsi Karya</label>
                    <textarea name="submission_text" class="form-control" rows="4" placeholder="Tuliskan catatan pengerjaan, software yang digunakan, atau ucapan pengantar..."><?= esc($submission['submission_text'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-medium">Tautan Link Karya (Google Drive / YouTube / Figma / GitHub)</label>
                    <input type="url" name="attachment_url" id="attachment_url_input" class="form-control" placeholder="https://drive.google.com/..." value="<?= esc($submission['attachment_url'] ?? '') ?>">
                    
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="no_link_check" name="no_link" value="1">
                        <label class="form-check-label text-secondary small" for="no_link_check">
                            Centang jika tidak menggunakan tautan link (Kosongkan Tautan Link)
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-secondary small fw-medium">Atau Unggah Berkas Karya (Opsional)</label>
                    <input type="file" name="attachment_file" class="form-control">
                    <div class="form-text text-secondary style-tiny">Format yang didukung: ZIP, RAR, PDF, PNG, JPG, MP4 (Max: 20MB)</div>
                </div>

                <button type="submit" class="btn btn-red w-100 py-3 font-heading fw-semibold">
                    <i class="fa-solid fa-paper-plane me-2"></i> Simpan Status & Kirim Karya
                </button>
            </form>
        </div>
    </div>
</div>

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
                Belum ada diskusi atau catatan revisi pada tugas ini. Tulis pesan atau tanya sesuatu di bawah ini!
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
    <form action="<?= base_url('member/tasks/comment/' . $task['id']) ?>" method="POST" id="comment-form">
        <?= csrf_field() ?>

        <div class="input-group">
            <textarea name="comment" id="comment-textarea" class="form-control bg-black text-white border-secondary border-opacity-50" rows="2" placeholder="Tuliskan pesan, pertanyaan revisi, atau balasan..." required></textarea>
            <button type="submit" class="btn btn-red px-4 font-heading fw-semibold">
                <i class="fa-solid fa-paper-plane me-1"></i> Kirim Pesan
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        function toggleLinkInput() {
            const isChecked = $('#no_link_check').is(':checked');
            const $input = $('#attachment_url_input');
            if (isChecked) {
                $input.val('').prop('readonly', true).addClass('opacity-50 bg-black').attr('placeholder', 'Tautan link dikosongkan');
            } else {
                $input.prop('readonly', false).removeClass('opacity-50 bg-black').attr('placeholder', 'https://drive.google.com/...');
            }
        }

        $('#no_link_check').on('change', toggleLinkInput);

        document.querySelectorAll('.tag-user-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const username = this.getAttribute('data-username');
                const textarea = document.getElementById('comment-textarea');
                textarea.value += ' @' + username + ' ';
                textarea.focus();
            });
        });
    });
</script>
<?= $this->endSection() ?>
