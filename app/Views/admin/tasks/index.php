<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Manajemen Tugas & Proyek MMC</h4>
        <p class="text-secondary small m-0">Kelola penugasan multi-assignee, status per anggota, prioritas, dan deadline tugas</p>
    </div>

    <a href="<?= base_url('admin/tasks/create') ?>" class="btn btn-red">
        <i class="fa-solid fa-plus me-2"></i> Buat Tugas Baru
    </a>
</div>

<!-- Filter Bar -->
<div class="saas-card p-3 mb-4">
    <form action="<?= base_url('admin/tasks') ?>" method="GET" class="row g-2 align-items-center">
        <div class="col-md-3">
            <label class="form-label text-secondary style-tiny m-0 mb-1 fw-bold">Pencarian Judul / Deskripsi</label>
            <input type="text" name="keyword" class="form-control form-control-sm bg-black text-white border-secondary border-opacity-50" placeholder="🔍 Cari..." value="<?= esc($filters['keyword'] ?? '') ?>">
        </div>
        <div class="col-md-3">
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
        <div class="col-md-3">
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
        <div class="col-md-3">
            <label class="form-label text-secondary style-tiny m-0 mb-1 fw-bold">Filter Deadline</label>
            <select name="deadline_filter" class="form-select form-select-sm bg-black text-white border-secondary border-opacity-50">
                <option value="">🕒 Semua Deadline</option>
                <option value="today" <?= (isset($filters['deadline_filter']) && $filters['deadline_filter'] === 'today') ? 'selected' : '' ?>>Deadline Hari Ini</option>
                <option value="upcoming" <?= (isset($filters['deadline_filter']) && $filters['deadline_filter'] === 'upcoming') ? 'selected' : '' ?>>Deadline Mendatang</option>
                <option value="overdue" <?= (isset($filters['deadline_filter']) && $filters['deadline_filter'] === 'overdue') ? 'selected' : '' ?>>Deadline Terlewat (Overdue)</option>
            </select>
        </div>
        <div class="col-12 d-flex justify-content-end gap-2 mt-2">
            <button type="submit" class="btn btn-sm btn-red px-3">
                <i class="fa-solid fa-filter me-1"></i> Terapkan Filter
            </button>
            <?php if (!empty($filters['keyword']) || !empty($filters['priority_id']) || !empty($filters['status_id']) || !empty($filters['deadline_filter'])): ?>
                <a href="<?= base_url('admin/tasks') ?>" class="btn btn-sm btn-saas-dark">
                    <i class="fa-solid fa-rotate-left me-1"></i> Reset
                </a>
            <?php endif; ?>
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
                    <th>Prioritas (Ubah Langsung)</th>
                    <th>Assignees & Status Anggota (Ubah Langsung)</th>
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
                                <a href="<?= base_url('admin/tasks/detail/' . $t['id']) ?>" class="fw-semibold text-white text-decoration-none">
                                    <?= esc($t['title']) ?>
                                </a>
                                <?php if ($isAllCompleted): ?>
                                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 font-monospace ms-2 d-inline-flex align-items-center gap-1 py-0.5 px-2 mb-1 style-tiny" title="Semua <?= $assigneesCount ?> anggota yang di-assign sudah menyelesaikan tugas ini!">
                                        <i class="fa-solid fa-circle-check text-success"></i> Selesai Semua
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="text-secondary small text-truncate" style="max-width: 250px;"><?= esc($t['description']) ?></div>
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
                                <a href="<?= base_url('admin/tasks/duplicate/' . $t['id']) ?>" onclick="return confirm('Duplikasi tugas \'<?= esc(addslashes($t['title'])) ?>\' beserta seluruh penerima tugas (assignees)?')" class="btn btn-outline-success" title="Duplikasi / Copy Tugas">
                                    <i class="fa-solid fa-copy"></i>
                                </a>
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
    });
</script>
<?= $this->endSection() ?>
