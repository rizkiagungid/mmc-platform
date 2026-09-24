<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<!-- Mobile Responsiveness & Scroll Styling -->
<style>
    .table-responsive {
        -webkit-overflow-scrolling: touch;
        overflow-x: auto;
        position: relative;
    }
    #tasks-table {
        min-width: 720px;
    }
    .custom-scroll-container {
        -webkit-overflow-scrolling: touch;
        overscroll-behavior: contain;
        scrollbar-width: thin;
        scrollbar-color: rgba(239, 68, 68, 0.4) rgba(0, 0, 0, 0.2);
    }
    .custom-scroll-container::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .custom-scroll-container::-webkit-scrollbar-thumb {
        background: rgba(239, 68, 68, 0.4);
        border-radius: 4px;
    }
    .custom-scroll-container::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.2);
    }

    @media (max-width: 767.98px) {
        .saas-card {
            padding: 1rem !important;
        }
        .dataTables_wrapper .dataTables_length, 
        .dataTables_wrapper .dataTables_filter {
            text-align: left !important;
            margin-bottom: 0.75rem;
        }
        .dataTables_wrapper .dataTables_filter input {
            width: 100% !important;
            margin-left: 0 !important;
            margin-top: 0.25rem;
        }
        .dataTables_wrapper .dataTables_paginate {
            text-align: center !important;
            margin-top: 1rem;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 0.25rem;
        }
        .dataTables_wrapper .dataTables_info {
            text-align: center !important;
            font-size: 0.8rem;
        }
        .modal-dialog {
            margin: 0.5rem;
        }
        .btn-group-responsive {
            display: flex;
            flex-wrap: wrap;
            gap: 0.35rem;
        }
        .btn-group-responsive .btn {
            border-radius: 0.375rem !important;
            flex: 1 1 auto;
        }
    }
</style>

<div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Manajemen Tugas & Proyek MMC</h4>
        <p class="text-secondary small m-0">Kelola penugasan multi-assignee, status per anggota, prioritas, dan deadline tugas</p>
    </div>

    <a href="<?= base_url('admin/tasks/create') ?>" class="btn btn-red w-100 w-sm-auto text-nowrap">
        <i class="fa-solid fa-plus me-2"></i> Buat Tugas Baru
    </a>
</div>

<!-- Filter Bar -->
<div class="saas-card p-3 mb-4">
    <form action="<?= base_url('admin/tasks') ?>" method="GET" class="row g-2 align-items-center">
        <div class="col-12 col-sm-6 col-md-3">
            <label class="form-label text-secondary style-tiny m-0 mb-1 fw-bold">Pencarian Judul / Deskripsi</label>
            <input type="text" name="keyword" class="form-control form-control-sm bg-black text-white border-secondary border-opacity-50" placeholder="🔍 Cari..." value="<?= esc($filters['keyword'] ?? '') ?>">
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <label class="form-label text-secondary style-tiny m-0 mb-1 fw-bold">Filter Prioritas</label>
            <select name="priority_id" class="form-select form-select-sm bg-black text-white border-secondary border-opacity-50">
                <option value="">⚡ Semua Prioritas</option>
                <?php foreach ($priorities as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= (isset($filters['priority_id']) && $filters['priority_id'] == $p['id']) ? 'selected' : '' ?>>
                        Prioritas: <?= esc($p['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <label class="form-label text-secondary style-tiny m-0 mb-1 fw-bold">Filter Status Anggota</label>
            <select name="status_id" class="form-select form-select-sm bg-black text-white border-secondary border-opacity-50">
                <option value="">📊 Semua Status Anggota</option>
                <?php foreach ($statuses as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= (isset($filters['status_id']) && $filters['status_id'] == $s['id']) ? 'selected' : '' ?>>
                        Status: <?= esc($s['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <label class="form-label text-secondary style-tiny m-0 mb-1 fw-bold">Filter Deadline</label>
            <select name="deadline_filter" class="form-select form-select-sm bg-black text-white border-secondary border-opacity-50">
                <option value="">🕒 Semua Deadline</option>
                <option value="today" <?= (isset($filters['deadline_filter']) && $filters['deadline_filter'] === 'today') ? 'selected' : '' ?>>Deadline Hari Ini</option>
                <option value="upcoming" <?= (isset($filters['deadline_filter']) && $filters['deadline_filter'] === 'upcoming') ? 'selected' : '' ?>>Deadline Mendatang</option>
                <option value="overdue" <?= (isset($filters['deadline_filter']) && $filters['deadline_filter'] === 'overdue') ? 'selected' : '' ?>>Deadline Terlewat (Overdue)</option>
            </select>
        </div>
        <div class="col-12 d-flex flex-column flex-sm-row justify-content-end gap-2 mt-2">
            <?php if (!empty($filters['keyword']) || !empty($filters['priority_id']) || !empty($filters['status_id']) || !empty($filters['deadline_filter'])): ?>
                <a href="<?= base_url('admin/tasks') ?>" class="btn btn-sm btn-saas-dark w-100 w-sm-auto text-center">
                    <i class="fa-solid fa-rotate-left me-1"></i> Reset Filter
                </a>
            <?php endif; ?>
            <button type="submit" class="btn btn-sm btn-red px-3 w-100 w-sm-auto">
                <i class="fa-solid fa-filter me-1"></i> Terapkan Filter
            </button>
        </div>
    </form>
</div>

<!-- Task Table -->
<div class="saas-card p-4">
    <div class="table-responsive">
        <table id="tasks-table" class="table table-dark-saas w-100 align-middle">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Judul Tugas</th>
                    <th>Prioritas</th>
                    <th>Assignees & Status Anggota</th>
                    <th>Deadline</th>
                    <th class="text-center" style="width: 130px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $i => $t): ?>
                    <?php
                    $assignees = $t['assignees'] ?? [];
                    $assigneesCount = count($assignees);
                    $completedCount = 0;
                    if ($assigneesCount > 0) {
                        foreach ($assignees as $a) {
                            $statusId = (int)($a['status_id'] ?? 1);
                            $statusName = strtolower(trim($a['status_name'] ?? ''));
                            if ($statusId === 5 || $statusName === 'selesai') {
                                $completedCount++;
                            }
                        }
                    }
                    $isAllCompleted = ($assigneesCount > 0 && $completedCount === $assigneesCount);
                    ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                <a href="<?= base_url('admin/tasks/detail/' . $t['id']) ?>" class="fw-bold text-white text-decoration-none hover-text-danger d-inline-flex align-items-center mb-1" title="Lihat detail tugas: <?= esc($t['title']) ?>">
                                    <span><?= esc($t['title']) ?></span>
                                    <?php if ($isAllCompleted): ?>
                                        <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 font-monospace ms-2 d-inline-flex align-items-center gap-1 py-0.5 px-2 mb-1 style-tiny" title="Semua <?= $assigneesCount ?> anggota yang di-assign sudah menyelesaikan tugas ini!">
                                            <i class="fa-solid fa-circle-check text-success"></i> Selesai Semua
                                        </span>
                                    <?php endif; ?>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-danger opacity-75 ms-2" style="font-size: 0.72rem;"></i>
                                </a>
                            </div>
                            <a href="<?= base_url('admin/tasks/detail/' . $t['id']) ?>" class="text-secondary small text-truncate text-decoration-none d-block opacity-75 hover-text-danger" style="max-width: 250px;" title="<?= esc($t['description']) ?>">
                                <?= esc($t['description']) ?>
                            </a>
                        </td>
                        <td>
                            <form action="<?= base_url('admin/tasks/update-priority/' . $t['id']) ?>" method="POST" class="m-0">
                                <?= csrf_field() ?>
                                <select name="priority_id" class="form-select form-select-sm bg-dark text-white border-secondary border-opacity-50 style-tiny fw-semibold" onchange="this.form.submit()" style="min-width: 120px;">
                                    <?php foreach ($priorities as $p): ?>
                                        <option value="<?= $p['id'] ?>" <?= $t['priority_id'] == $p['id'] ? 'selected' : '' ?>>
                                            <?= esc($p['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>
                        <td>
                            <?php if (empty($assignees)): ?>
                                <span class="text-secondary small font-monospace">Belum ada assignee</span>
                            <?php else: ?>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if ($isAllCompleted): ?>
                                        <button class="btn btn-sm btn-outline-success py-1 px-2 style-tiny text-nowrap d-inline-flex align-items-center gap-1.5" type="button" data-bs-toggle="collapse" data-bs-target="#assignees-collapse-<?= $t['id'] ?>" aria-expanded="false" onclick="toggleAssigneeListBtn(this, <?= $assigneesCount ?>, true)">
                                            <i class="fa-solid fa-circle-check text-success"></i>
                                            <span>Semua Selesai (<?= $assigneesCount ?>)</span>
                                        </button>
                                        <i class="fa-solid fa-circle-check text-success fs-5" title="Semua anggota (<?= $assigneesCount ?> orang) sudah selesai mengerjakan tugas!"></i>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-saas-dark py-1 px-2 style-tiny text-white border-secondary border-opacity-50 text-nowrap" type="button" data-bs-toggle="collapse" data-bs-target="#assignees-collapse-<?= $t['id'] ?>" aria-expanded="false" onclick="toggleAssigneeListBtn(this, <?= $assigneesCount ?>, false)">
                                            <i class="fa-solid fa-users text-danger me-1"></i> Lihat Anggota (<?= $assigneesCount ?>)
                                        </button>
                                        <?php if ($completedCount > 0): ?>
                                            <span class="badge bg-secondary bg-opacity-25 text-success font-monospace style-tiny" title="<?= $completedCount ?> dari <?= $assigneesCount ?> anggota selesai">
                                                <i class="fa-solid fa-check me-0.5"></i> <?= $completedCount ?>/<?= $assigneesCount ?> Selesai
                                            </span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>

                                <div class="collapse flex-column gap-1 mt-2" id="assignees-collapse-<?= $t['id'] ?>">
                                    <?php foreach ($assignees as $a): ?>
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="badge bg-dark border border-secondary text-white font-monospace style-tiny text-truncate" style="max-width: 180px;" title="<?= esc($a['full_name']) ?>">
                                                <?php if ((int)($a['status_id'] ?? 1) === 5 || strtolower($a['status_name'] ?? '') === 'selesai'): ?>
                                                    <i class="fa-solid fa-circle-check me-1 text-success"></i>
                                                <?php else: ?>
                                                    <i class="fa-solid fa-user me-1 text-danger"></i>
                                                <?php endif; ?>
                                                <?= esc($a['full_name']) ?>
                                            </span>
                                            <form action="<?= base_url('admin/tasks/update-assignee-status/' . $t['id']) ?>" method="POST" class="m-0">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="user_id" value="<?= $a['id'] ?>">
                                                <select name="status_id" class="form-select form-select-sm bg-black text-white border-secondary border-opacity-50 py-0 px-1 style-tiny" onchange="this.form.submit()" style="font-size: 0.68rem; height: 22px;">
                                                    <?php foreach ($statuses as $s): ?>
                                                        <option value="<?= $s['id'] ?>" <?= ($a['status_id'] ?? 1) == $s['id'] ? 'selected' : '' ?>>
                                                            <?= esc($s['name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </form>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="font-monospace small text-danger">
                            <?= $t['deadline'] ? date('d M Y, H:i', strtotime($t['deadline'])) : 'Tanpa Batas' ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="<?= base_url('admin/tasks/detail/' . $t['id']) ?>" class="btn btn-outline-info" title="Detail & Peninjauan">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-outline-success btn-duplicate-task" 
                                        data-id="<?= $t['id'] ?>" 
                                        data-title="<?= esc($t['title']) ?>" 
                                        data-description="<?= esc($t['description'] ?? '') ?>"
                                        data-priority-id="<?= $t['priority_id'] ?>"
                                        data-status-id="<?= $t['status_id'] ?>"
                                        data-deadline="<?= $t['deadline'] ? date('Y-m-d\TH:i', strtotime($t['deadline'])) : '' ?>"
                                        data-assignees='<?= json_encode(array_map('intval', array_column($t['assignees'], 'id'))) ?>'
                                        title="Duplikasi / Copy Tugas (dengan Opsi Filter)">
                                    <i class="fa-solid fa-copy"></i>
                                </button>
                                <a href="<?= base_url('admin/tasks/edit/' . $t['id']) ?>" class="btn btn-outline-warning" title="Edit Tugas">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a href="<?= base_url('admin/tasks/delete/' . $t['id']) ?>" onclick="return confirm('Hapus tugas ini?')" class="btn btn-outline-danger" title="Hapus Tugas">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Filter & Opsi Duplikasi Tugas -->
<div class="modal fade" id="duplicateTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form id="duplicateTaskForm" method="POST" action="" class="modal-content bg-dark text-white border border-secondary border-opacity-25 shadow-lg" style="max-height: 90vh;">
            <?= csrf_field() ?>
            <div class="modal-header border-bottom border-secondary border-opacity-25 py-3 px-3 px-md-4 flex-shrink-0">
                <h5 class="modal-title font-heading d-flex align-items-center gap-2 m-0 fs-6 fs-md-5">
                    <span class="p-1.5 rounded-2 bg-success bg-opacity-10 text-success">
                        <i class="fa-solid fa-copy"></i>
                    </span>
                    <span>Filter &amp; Opsi Duplikasi Tugas</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-3 p-md-4 custom-scroll-container" style="overflow-y: auto; -webkit-overflow-scrolling: touch; flex: 1 1 auto; min-height: 0;">
                <div class="alert alert-dark border border-secondary border-opacity-25 p-2.5 p-md-3 mb-3 rounded-3 d-flex align-items-center gap-2.5">
                    <div class="fs-5 text-warning flex-shrink-0">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <div class="small">
                        <strong class="text-white">Sesuaikan Komponen Yang Ingin Disalin:</strong><br>
                        <span class="text-secondary style-tiny">Pilih bagian mana saja yang ingin ikut diduplikasi (Deskripsi, Prioritas, Deadline, hingga Anggota Penerima Tugas).</span>
                    </div>
                </div>

                    <!-- Judul Tugas Baru -->
                    <div class="mb-4">
                        <label class="form-label text-white small fw-bold mb-1">
                            <i class="fa-solid fa-heading text-danger me-1"></i> 1. Judul Tugas Baru <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="title" id="dup-title" class="form-control form-control-lg bg-black text-white border-secondary border-opacity-50" required placeholder="Judul tugas baru...">
                    </div>

                    <!-- Accordion / Grid Opsi -->
                    <div class="row g-3 mb-4">
                        <!-- Opsi Deskripsi -->
                        <div class="col-12">
                            <div class="p-3 rounded-3 bg-body-tertiary border border-secondary border-opacity-25">
                                <div class="form-check form-switch mb-2">
                                    <input type="hidden" name="copy_description" value="0">
                                    <input class="form-check-input" type="checkbox" name="copy_description" value="1" id="dup-copy-desc-chk" checked>
                                    <label class="form-check-label text-white fw-semibold small" for="dup-copy-desc-chk">
                                        <i class="fa-solid fa-align-left text-info me-1"></i> 2. Salin Deskripsi &amp; Instruksi Tugas
                                    </label>
                                </div>
                                <div id="dup-desc-container" class="mt-2">
                                    <textarea name="description" id="dup-description" class="form-control bg-black text-white border-secondary border-opacity-50 small" rows="3" placeholder="Deskripsi tugas..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Opsi Prioritas & Status -->
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-body-tertiary border border-secondary border-opacity-25 h-100">
                                <label class="form-label text-white fw-semibold small mb-2">
                                    <i class="fa-solid fa-bolt text-warning me-1"></i> 3. Prioritas Tugas Baru
                                </label>
                                <select name="priority_id" id="dup-priority" class="form-select bg-black text-white border-secondary border-opacity-50 small">
                                    <?php foreach ($priorities as $p): ?>
                                        <option value="<?= $p['id'] ?>">Prioritas: <?= esc($p['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Opsi Status Awal -->
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-body-tertiary border border-secondary border-opacity-25 h-100">
                                <label class="form-label text-white fw-semibold small mb-2">
                                    <i class="fa-solid fa-list-check text-success me-1"></i> 4. Status Awal Pengerjaan
                                </label>
                                <select name="status_id" id="dup-status" class="form-select bg-black text-white border-secondary border-opacity-50 small">
                                    <?php foreach ($statuses as $s): ?>
                                        <option value="<?= $s['id'] ?>"><?= esc($s['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Opsi Deadline -->
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-body-tertiary border border-secondary border-opacity-25 h-100">
                                <div class="form-check form-switch mb-2">
                                    <input type="hidden" name="copy_deadline" value="0">
                                    <input class="form-check-input" type="checkbox" name="copy_deadline" value="1" id="dup-copy-deadline-chk" checked>
                                    <label class="form-check-label text-white fw-semibold small" for="dup-copy-deadline-chk">
                                        <i class="fa-solid fa-clock text-danger me-1"></i> 5. Salin / Atur Batas Waktu
                                    </label>
                                </div>
                                <div id="dup-deadline-container">
                                    <input type="datetime-local" name="deadline" id="dup-deadline" class="form-control bg-black text-white border-secondary border-opacity-50 small">
                                </div>
                            </div>
                        </div>

                        <!-- Opsi Salin Label -->
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-body-tertiary border border-secondary border-opacity-25 h-100 d-flex flex-column justify-content-center">
                                <div class="form-check form-switch m-0">
                                    <input type="hidden" name="copy_labels" value="0">
                                    <input class="form-check-input" type="checkbox" name="copy_labels" value="1" id="dup-copy-labels-chk" checked>
                                    <label class="form-check-label text-white fw-semibold small" for="dup-copy-labels-chk">
                                        <i class="fa-solid fa-tags text-primary me-1"></i> 6. Salin Label &amp; Kategori Tugas
                                    </label>
                                </div>
                                <small class="text-secondary style-tiny mt-1 ms-4">Menyalin tag/label yang menempel pada tugas asli.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Opsi Filter Assignees (Anggota Penerima Tugas) -->
                    <div class="p-3 rounded-3 bg-body-tertiary border border-secondary border-opacity-25">
                        <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                            <div class="form-check form-switch m-0">
                                <input type="hidden" name="copy_assignees" value="0">
                                <input class="form-check-input" type="checkbox" name="copy_assignees" value="1" id="dup-copy-assignees-chk" checked>
                                <label class="form-check-label text-white fw-semibold small" for="dup-copy-assignees-chk">
                                    <i class="fa-solid fa-users text-danger me-1"></i> 7. Salin Penerima Tugas (Assignees)
                                </label>
                            </div>

                            <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50 font-monospace small" id="dup-selected-count-badge">
                                0 Anggota Terpilih
                            </span>
                        </div>

                        <div id="dup-assignees-wrapper" class="mt-3">
                            <!-- Quick Action Buttons -->
                            <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap mb-2">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-info btn-sm style-tiny py-1" id="btn-dup-select-original">
                                        <i class="fa-solid fa-clipboard-check me-1"></i> Sesuai Asli
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm style-tiny py-1 btn-dup-filter-div" data-division="programming">
                                        <i class="fa-solid fa-code me-1"></i> + Programming
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm style-tiny py-1 btn-dup-filter-div" data-division="broadcasting">
                                        <i class="fa-solid fa-tower-cell me-1"></i> + Broadcasting
                                    </button>
                                </div>

                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-secondary btn-sm style-tiny py-1" id="btn-dup-select-all">
                                        <i class="fa-solid fa-check-double me-1"></i> Pilih Semua
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm style-tiny py-1" id="btn-dup-deselect-all">
                                        <i class="fa-solid fa-xmark me-1"></i> Hapus Semua
                                    </button>
                                </div>
                            </div>

                            <!-- Search Input -->
                            <div class="mb-2">
                                <input type="text" id="dup-search-assignee" class="form-control form-control-sm bg-black text-white border-secondary border-opacity-50" placeholder="🔍 Cari nama anggota, NIS/NIP, atau divisi...">
                            </div>

                            <!-- Member List Scrollable -->
                            <div class="p-2 rounded-2 bg-dark border border-secondary border-opacity-25 custom-scroll-container" style="max-height: 240px; overflow-y: auto;">
                                <div class="row g-2" id="dup-assignee-list-container">
                                    <?php if (!empty($members)): ?>
                                        <?php foreach ($members as $m): ?>
                                            <?php
                                                $classDept = strtolower($m['class_dept'] ?? '');
                                                $division = 'other';
                                                $divisionBadge = 'Anggota';
                                                $badgeClass = 'bg-secondary bg-opacity-50 text-light';

                                                if (str_contains($classDept, 'programming')) {
                                                    $division = 'programming';
                                                    $divisionBadge = 'Programming';
                                                    $badgeClass = 'bg-primary bg-opacity-50 text-light border border-primary border-opacity-50';
                                                } elseif (str_contains($classDept, 'broadcasting')) {
                                                    $division = 'broadcasting';
                                                    $divisionBadge = 'Broadcasting';
                                                    $badgeClass = 'bg-danger bg-opacity-50 text-light border border-danger border-opacity-50';
                                                }
                                            ?>
                                            <div class="col-12 col-md-6 dup-assignee-item" data-division="<?= $division ?>" data-search="<?= strtolower(esc($m['full_name'] . ' ' . ($m['nis_nip'] ?? '') . ' ' . ($m['class_dept'] ?? ''))) ?>">
                                                <div class="form-check p-2 rounded-2 bg-body-secondary border border-secondary border-opacity-25 h-100 d-flex align-items-center gap-2 m-0">
                                                    <input class="form-check-input m-0 ms-1 flex-shrink-0 dup-assignee-checkbox" type="checkbox" name="assignees[]" value="<?= $m['id'] ?>" id="dup_user_<?= $m['id'] ?>">
                                                    <label class="form-check-label w-100 text-truncate cursor-pointer small m-0" for="dup_user_<?= $m['id'] ?>">
                                                        <div class="fw-semibold text-white text-truncate"><?= esc($m['full_name']) ?></div>
                                                        <div class="d-flex align-items-center gap-1 mt-1">
                                                            <span class="badge <?= $badgeClass ?>" style="font-size: 0.65rem; padding: 0.15rem 0.35rem;"><?= $divisionBadge ?></span>
                                                            <span class="text-secondary style-tiny font-monospace"><?= esc($m['nis_nip'] ?: '-') ?></span>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
            </div>

            <div class="modal-footer border-top border-secondary border-opacity-25 d-flex flex-column-reverse flex-sm-row justify-content-end gap-2 flex-shrink-0 bg-dark py-2.5 px-3 px-md-4">
                <button type="button" class="btn btn-saas-dark w-100 w-sm-auto" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success fw-bold px-4 w-100 w-sm-auto">
                    <i class="fa-solid fa-copy me-2"></i> Duplikasi Tugas Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function toggleAssigneeListBtn(btn, count, isCompleted = false) {
        setTimeout(function() {
            const isExpanded = btn.getAttribute('aria-expanded') === 'true';
            if (isExpanded) {
                btn.innerHTML = '<i class="fa-solid fa-users-slash text-warning me-1"></i> Sembunyikan (' + count + ')';
            } else {
                if (isCompleted) {
                    btn.innerHTML = '<i class="fa-solid fa-circle-check text-success me-1"></i> Semua Selesai (' + count + ')';
                } else {
                    btn.innerHTML = '<i class="fa-solid fa-users text-danger me-1"></i> Lihat Anggota (' + count + ')';
                }
            }
        }, 50);
    }

    $(document).ready(function() {
        $('#tasks-table').DataTable({
            language: {
                search: "Cari Cepat di Tabel:",
                lengthMenu: "Tampilkan _MENU_ tugas",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ tugas",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "Lanjut",
                    previous: "Mundur"
                }
            },
            order: [[0, 'asc']]
        });

        let originalAssigneeIds = [];

        function updateDupSelectedCount() {
            const count = $('.dup-assignee-checkbox:checked').length;
            $('#dup-selected-count-badge').text(count + ' Anggota Terpilih');
        }

        // Toggle visibility when checkbox switch is toggled
        $('#dup-copy-desc-chk').on('change', function() {
            if ($(this).is(':checked')) {
                $('#dup-desc-container').slideDown(150);
            } else {
                $('#dup-desc-container').slideUp(150);
            }
        });

        $('#dup-copy-deadline-chk').on('change', function() {
            if ($(this).is(':checked')) {
                $('#dup-deadline-container').slideDown(150);
            } else {
                $('#dup-deadline-container').slideUp(150);
            }
        });

        $('#dup-copy-assignees-chk').on('change', function() {
            if ($(this).is(':checked')) {
                $('#dup-assignees-wrapper').slideDown(150);
                if ($('.dup-assignee-checkbox:checked').length === 0 && Array.isArray(originalAssigneeIds)) {
                    originalAssigneeIds.forEach(function(userId) {
                        $('#dup_user_' + userId).prop('checked', true);
                    });
                }
                updateDupSelectedCount();
            } else {
                $('#dup-assignees-wrapper').slideUp(150);
                $('.dup-assignee-checkbox').prop('checked', false);
                updateDupSelectedCount();
            }
        });

        // Open Duplication Modal with pre-filled filters & options
        $(document).on('click', '.btn-duplicate-task', function() {
            const taskId       = $(this).data('id');
            const title        = $(this).data('title');
            const description  = $(this).data('description');
            const priorityId   = $(this).data('priority-id');
            const statusId     = $(this).data('status-id');
            const deadline     = $(this).data('deadline');
            const assignees    = $(this).data('assignees') || [];

            originalAssigneeIds = assignees;

            // Set Form action
            $('#duplicateTaskForm').attr('action', '<?= base_url("admin/tasks/duplicate") ?>/' + taskId);

            // Pre-fill Title
            $('#dup-title').val(title + ' (Copy)');

            // Pre-fill Description
            $('#dup-description').val(description);
            $('#dup-copy-desc-chk').prop('checked', true);
            $('#dup-desc-container').show();

            // Pre-fill Priority & Status
            $('#dup-priority').val(priorityId);
            $('#dup-status').val(1); // Default fresh 'Belum dikerjakan'

            // Pre-fill Deadline
            if (deadline) {
                $('#dup-deadline').val(deadline);
                $('#dup-copy-deadline-chk').prop('checked', true);
                $('#dup-deadline-container').show();
            } else {
                $('#dup-deadline').val('');
                $('#dup-copy-deadline-chk').prop('checked', false);
                $('#dup-deadline-container').hide();
            }

            // Reset Member Checkboxes & Check Original Assignees
            $('.dup-assignee-checkbox').prop('checked', false);
            $('#dup-copy-assignees-chk').prop('checked', true);
            $('#dup-assignees-wrapper').show();

            if (Array.isArray(assignees)) {
                assignees.forEach(function(userId) {
                    $('#dup_user_' + userId).prop('checked', true);
                });
            }

            updateDupSelectedCount();

            // Clear search filter
            $('#dup-search-assignee').val('').trigger('input');

            // Show Modal
            const dupModal = new bootstrap.Modal(document.getElementById('duplicateTaskModal'));
            dupModal.show();
        });

        // Search Assignees inside Duplicate Modal
        $('#dup-search-assignee').on('input', function() {
            const query = $(this).val().toLowerCase().trim();
            $('.dup-assignee-item').each(function() {
                const searchData = $(this).data('search') || '';
                if (!query || searchData.indexOf(query) !== -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Quick button: Sesuai Asli
        $('#btn-dup-select-original').on('click', function() {
            $('.dup-assignee-checkbox').prop('checked', false);
            if (Array.isArray(originalAssigneeIds)) {
                originalAssigneeIds.forEach(function(userId) {
                    $('#dup_user_' + userId).prop('checked', true);
                });
            }
            updateDupSelectedCount();
        });

        // Quick button: Filter by division
        $('.btn-dup-filter-div').on('click', function() {
            const targetDiv = $(this).data('division');
            $('.dup-assignee-item[data-division="' + targetDiv + '"] .dup-assignee-checkbox').prop('checked', true);
            updateDupSelectedCount();
        });

        // Quick button: Select All
        $('#btn-dup-select-all').on('click', function() {
            $('.dup-assignee-item:visible .dup-assignee-checkbox').prop('checked', true);
            updateDupSelectedCount();
        });

        // Quick button: Deselect All
        $('#btn-dup-deselect-all').on('click', function() {
            $('.dup-assignee-checkbox').prop('checked', false);
            updateDupSelectedCount();
        });

        // Checkbox change listener
        $(document).on('change', '.dup-assignee-checkbox', function() {
            updateDupSelectedCount();
        });
    });
</script>
<?= $this->endSection() ?>
