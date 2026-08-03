<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <h4 class="text-white font-heading m-0">Tugas Saya</h4>
    <p class="text-secondary small m-0">Daftar penugasan proyek multimedia yang ditugaskan kepada Anda</p>
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
                <?php foreach ($statuses as $st): ?>
                    <option value="<?= $st['id'] ?>" <?= (isset($selectedFilter['status_id']) && $selectedFilter['status_id'] == $st['id']) ? 'selected' : '' ?>>
                        Status: <?= esc($st['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-3">
            <select name="priority_id" class="form-select form-select-sm bg-dark text-white border-secondary">
                <option value="">-- Semua Prioritas --</option>
                <?php foreach ($priorities as $pr): ?>
                    <option value="<?= $pr['id'] ?>" <?= (isset($selectedFilter['priority_id']) && $selectedFilter['priority_id'] == $pr['id']) ? 'selected' : '' ?>>
                        Prioritas: <?= esc($pr['name']) ?>
                    </option>
                <?php endforeach; ?>
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

<div class="saas-card p-4">
    <?php if (empty($tasks)): ?>
        <div class="text-center py-5 text-secondary">
            <i class="fa-solid fa-clipboard-check display-1 mb-3 text-secondary opacity-50"></i>
            <h5 class="text-white font-heading">Belum Ada Tugas Ditugaskan</h5>
            <p class="small mb-0">Semua tugas Anda telah selesai atau belum ada penugasan baru dari Pembina / BPH.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($tasks as $t): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="saas-card p-4 h-100 border border-secondary border-opacity-25 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                <div class="d-flex align-items-center gap-1 flex-wrap">
                                    <span class="badge" style="background-color: <?= $t['priority_color'] ?>;">
                                        <?= esc($t['priority_name']) ?>
                                    </span>
                                    <span class="badge" style="background-color: <?= $t['my_status_color'] ?? '#3b82f6' ?>;">
                                        Status Saya: <?= esc($t['my_status_name'] ?? 'Todo') ?>
                                    </span>
                                </div>

                                <?php if (!empty($t['is_submitted'])): ?>
                                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 px-2 py-1">
                                        <i class="fa-solid fa-circle-check me-1"></i> sudah dikirim
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 px-2 py-1">
                                        <i class="fa-solid fa-circle-xmark me-1"></i> belum dikirim
                                    </span>
                                <?php endif; ?>
                            </div>

                            <h5 class="text-white font-heading mb-2"><?= esc($t['title']) ?></h5>
                            <p class="text-secondary small leading-relaxed mb-3 line-clamp-3"><?= esc($t['description']) ?></p>
                        </div>

                        <div>
                            <div class="pt-3 border-top border-secondary border-opacity-10 mb-3">
                                <small class="text-danger font-monospace">
                                    <i class="fa-solid fa-clock me-1"></i> Deadline: <?= $t['deadline'] ? date('d M Y, H:i', strtotime($t['deadline'])) : 'Tanpa Batas' ?>
                                </small>
                            </div>

                            <a href="<?= base_url('member/tasks/submit/' . $t['id']) ?>" class="btn <?= !empty($t['is_submitted']) ? 'btn-saas-dark text-success border-success border-opacity-50' : 'btn-red' ?> w-100">
                                <i class="fa-solid <?= !empty($t['is_submitted']) ? 'fa-pen-to-square' : 'fa-paper-plane' ?> me-1"></i> <?= !empty($t['is_submitted']) ? 'Lihat / Edit Pengiriman' : 'Kirim Karya Sekarang' ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
