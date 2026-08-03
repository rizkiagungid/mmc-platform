<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <h4 class="text-white font-heading m-0">Portal Tugas & Pengumpulan Berkas Saya</h4>
    <p class="text-secondary small">Kirimkan hasil pengerjaan proyek dan pantau catatan evaluasi dari BPH / Pembina</p>
</div>

<!-- Filter Bar Card -->
<div class="saas-card p-3 mb-4">
    <form action="<?= base_url('member/tasks') ?>" method="GET" class="row g-2 align-items-center">
        <div class="col-md-3">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fa-solid fa-search"></i></span>
                <input type="text" name="keyword" class="form-control form-control-sm bg-dark text-white border-secondary" placeholder="Cari judul atau deskripsi..." value="<?= esc($selectedFilter['keyword'] ?? '') ?>">
            </div>
        </div>

        <div class="col-md-3">
            <select name="status_id" class="form-select form-select-sm bg-dark text-white border-secondary">
                <option value="">-- Semua Status Pengerjaan --</option>
                <?php if (!empty($statuses)): ?>
                    <?php foreach ($statuses as $st): ?>
                        <option value="<?= $st['id'] ?>" <?= (isset($selectedFilter['status_id']) && $selectedFilter['status_id'] == $st['id']) ? 'selected' : '' ?>>
                            Status: <?= esc($st['name']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="col-md-3">
            <select name="priority_id" class="form-select form-select-sm bg-dark text-white border-secondary">
                <option value="">-- Semua Prioritas --</option>
                <?php if (!empty($priorities)): ?>
                    <?php foreach ($priorities as $pr): ?>
                        <option value="<?= $pr['id'] ?>" <?= (isset($selectedFilter['priority_id']) && $selectedFilter['priority_id'] == $pr['id']) ? 'selected' : '' ?>>
                            Prioritas: <?= esc($pr['name']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="col-md-2">
            <select name="deadline_filter" class="form-select form-select-sm bg-dark text-white border-secondary">
                <option value="">-- Semua Deadline --</option>
                <option value="today" <?= (isset($selectedFilter['deadline_filter']) && $selectedFilter['deadline_filter'] === 'today') ? 'selected' : '' ?>>Hari Ini</option>
                <option value="upcoming" <?= (isset($selectedFilter['deadline_filter']) && $selectedFilter['deadline_filter'] === 'upcoming') ? 'selected' : '' ?>>Akan Datang</option>
                <option value="overdue" <?= (isset($selectedFilter['deadline_filter']) && $selectedFilter['deadline_filter'] === 'overdue') ? 'selected' : '' ?>>Terlewat (Overdue)</option>
            </select>
        </div>

        <div class="col-md-1 d-flex gap-1">
            <button type="submit" class="btn btn-red btn-sm w-100" title="Cari & Filter">
                <i class="fa-solid fa-filter"></i>
            </button>
            <a href="<?= base_url('member/tasks') ?>" class="btn btn-saas-dark btn-sm" title="Reset Filter">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        </div>
    </form>
</div>

<div class="d-flex flex-column gap-4">
    <?php foreach ($myTasks as $t): ?>
        <div class="saas-card p-4 border border-secondary border-opacity-25">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-3">
                <div>
                    <span class="badge me-1" style="background-color: <?= $t['priority_color'] ?>;"><?= esc($t['priority_name']) ?></span>
                    <span class="badge me-1" style="background-color: <?= $t['my_status_color'] ?? '#3b82f6' ?>;">Status Saya: <?= esc($t['my_status_name'] ?? 'Todo') ?></span>
                    <?php if (!empty($t['my_submission']) || !empty($t['is_submitted'])): ?>
                        <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 px-2 py-1"><i class="fa-solid fa-circle-check me-1"></i> sudah dikirim</span>
                    <?php else: ?>
                        <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 px-2 py-1"><i class="fa-solid fa-circle-xmark me-1"></i> belum dikirim</span>
                    <?php endif; ?>
                    <h4 class="text-white font-heading mt-2 mb-0"><?= esc($t['title']) ?></h4>
                </div>
                <div class="text-danger font-monospace small">
                    <i class="fa-solid fa-clock me-1"></i> Deadline: <?= $t['deadline'] ? date('d M Y, H:i', strtotime($t['deadline'])) : 'Tanpa Batas' ?>
                </div>
            </div>

            <p class="text-secondary small leading-relaxed mb-4"><?= nl2br(esc($t['description'])) ?></p>

            <!-- Status Submission Box if already submitted -->
            <?php if ($t['my_submission']): ?>
                <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-50 mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i> SUDAH DIKIRIM</span>
                        <small class="text-secondary font-monospace"><?= date('H:i:s d/m/Y', strtotime($t['my_submission']['submitted_at'])) ?></small>
                    </div>
                    <p class="text-white small mb-2"><strong>Catatan Pengumpulan:</strong> <?= esc($t['my_submission']['submission_text'] ?: '-') ?></p>

                    <?php if ($t['my_submission']['attachment_url']): ?>
                        <div class="mb-2">
                            <a href="<?= esc($t['my_submission']['attachment_url']) ?>" target="_blank" class="btn btn-sm btn-outline-danger font-monospace">
                                <i class="fa-solid fa-link me-1"></i> Buka Tautan Berkas Saya
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if ($t['my_submission']['feedback']): ?>
                        <div class="p-2 rounded bg-black text-warning small mt-2">
                            <i class="fa-solid fa-comment-dots me-1"></i> <strong>Feedback Evaluator:</strong> <?= esc($t['my_submission']['feedback']) ?>
                            <?php if ($t['my_submission']['grade']): ?>
                                <span class="badge bg-success ms-2">Nilai: <?= esc($t['my_submission']['grade']) ?>/100</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Submission Form -->
            <button class="btn btn-red" data-bs-toggle="collapse" data-bs-target="#submitForm_<?= $t['id'] ?>">
                <i class="fa-solid fa-paper-plane me-1"></i> <?= $t['my_submission'] ? 'Kirim Pembaruan / Revisi' : 'Kirimkan Berkas Tugas' ?>
            </button>

            <div class="collapse mt-3" id="submitForm_<?= $t['id'] ?>">
                <form action="<?= base_url('member/tasks/submit') ?>" method="POST" class="p-3 rounded-3 bg-dark border border-danger border-opacity-25">
                    <?= csrf_field() ?>
                    <input type="hidden" name="task_id" value="<?= $t['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Tautan / Link Berkas Hasil Karya (Google Drive, Figma, GitHub, Premiere project)</label>
                        <input type="url" name="attachment_url" class="form-control" placeholder="https://drive.google.com/..." value="<?= esc($t['my_submission']['attachment_url'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Catatan Penjelasan Pengerjaan</label>
                        <textarea name="submission_text" class="form-control" rows="3" placeholder="Jelaskan versi hasil karya yang dikumpulkan..."><?= esc($t['my_submission']['submission_text'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-red px-4">Kirim Pengumpulan</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>
