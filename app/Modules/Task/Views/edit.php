<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Edit Tugas: <?= esc($task['title']) ?></h4>
        <p class="text-secondary small m-0">Perbarui rincian instruksi, status, prioritas, atau daftar anggota assignee</p>
    </div>

    <a href="<?= base_url('admin/tasks') ?>" class="btn btn-saas-dark">
        <i class="fa-solid fa-arrow-left me-1"></i> Batal & Kembali
    </a>
</div>

<div class="saas-card p-4 col-lg-9 mx-auto">
    <form action="<?= base_url('admin/tasks/update/' . $task['id']) ?>" method="POST">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label text-secondary small fw-medium">Judul Tugas / Proyek <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control form-control-lg" required value="<?= esc($task['title']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label text-secondary small fw-medium">Deskripsi & Instruksi Pengerjaan</label>
            <textarea name="description" class="form-control" rows="4"><?= esc($task['description']) ?></textarea>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label text-secondary small fw-medium">Prioritas Tugas <span class="text-danger">*</span></label>
                <select name="priority_id" class="form-select" required>
                    <?php foreach ($priorities as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= $task['priority_id'] == $p['id'] ? 'selected' : '' ?>>
                            <?= esc($p['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label text-secondary small fw-medium">Status Tugas <span class="text-danger">*</span></label>
                <select name="status_id" class="form-select" required>
                    <?php foreach ($statuses as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= $task['status_id'] == $s['id'] ? 'selected' : '' ?>>
                            <?= esc($s['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label text-secondary small fw-medium">Batas Waktu (Deadline)</label>
                <input type="datetime-local" name="deadline" class="form-control" value="<?= $task['deadline'] ? date('Y-m-d\TH:i', strtotime($task['deadline'])) : '' ?>">
            </div>
        </div>

        <!-- Multi-Assignee & Per-Assignee Status Section -->
        <?php
            $currentAssigneeMap = [];
            foreach ($task['assignees'] as $ass) {
                $currentAssigneeMap[$ass['id']] = $ass;
            }
        ?>
        <div class="mb-4">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <label class="form-label text-white fw-semibold m-0">
                    <i class="fa-solid fa-users text-danger me-2"></i> Pilih Anggota Assignee & Status Masing-masing <span class="text-danger">*</span>
                </label>
                <div class="btn-group btn-group-sm">
                    <button type="button" id="btn-select-all" class="btn btn-sm btn-outline-secondary py-0" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-check-double me-1"></i> Pilih Semua
                    </button>
                    <button type="button" id="btn-deselect-all" class="btn btn-sm btn-outline-secondary py-0" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-xmark me-1"></i> Hapus Pilihan
                    </button>
                </div>
            </div>

            <div class="mb-2">
                <input type="text" id="search-assignee-input" class="form-control form-control-sm bg-black text-white border-secondary border-opacity-50" placeholder="🔍 Cari nama anggota, NIS/NIP, atau kelas/divisi...">
            </div>

            <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25" style="max-height: 320px; overflow-y: auto;">
                <div class="row g-2" id="assignee-list-container">
                    <?php foreach ($members as $m): ?>
                        <?php 
                            $isAssigned = isset($currentAssigneeMap[$m['id']]); 
                            $memberStatusId = $isAssigned ? ($currentAssigneeMap[$m['id']]['status_id'] ?? 1) : 1;
                        ?>
                        <div class="col-md-6 col-lg-6 assignee-item" data-search="<?= esc(strtolower($m['full_name'] . ' ' . $m['nis_nip'] . ' ' . $m['class_dept'])) ?>">
                            <div class="p-2 rounded-2 border border-secondary border-opacity-10 bg-black h-100 d-flex align-items-center justify-content-between gap-2">
                                <div class="form-check m-0 flex-grow-1">
                                    <input class="form-check-input ms-1 assignee-checkbox" type="checkbox" name="assignees[]" value="<?= $m['id'] ?>" id="assignee_<?= $m['id'] ?>" <?= $isAssigned ? 'checked' : '' ?>>
                                    <label class="form-check-label text-white small ms-2 cursor-pointer" for="assignee_<?= $m['id'] ?>">
                                        <strong class="d-block text-truncate"><?= esc($m['full_name']) ?></strong>
                                        <div class="text-secondary style-tiny"><?= esc($m['class_dept'] ?: 'Anggota') ?> (<?= esc($m['nis_nip'] ?: '-') ?>)</div>
                                    </label>
                                </div>
                                <div style="min-width: 140px;">
                                    <select name="assignee_status[<?= $m['id'] ?>]" class="form-select form-select-sm bg-dark text-white border-secondary border-opacity-50 style-tiny">
                                        <?php foreach ($statuses as $s): ?>
                                            <option value="<?= $s['id'] ?>" <?= $memberStatusId == $s['id'] ? 'selected' : '' ?>>
                                                Status: <?= esc($s['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div id="no-assignee-found" class="text-center py-3 text-secondary small d-none">
                    Tidak ada anggota yang cocok dengan pencarian.
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 border-top border-secondary border-opacity-25 pt-3">
            <a href="<?= base_url('admin/tasks') ?>" class="btn btn-saas-dark">Batal</a>
            <button type="submit" class="btn btn-red px-4">
                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Live search filter
        $('#search-assignee-input').on('input', function() {
            const query = $(this).val().toLowerCase().trim();
            let visibleCount = 0;

            $('.assignee-item').each(function() {
                const searchData = $(this).data('search');
                if (searchData.includes(query)) {
                    $(this).removeClass('d-none');
                    visibleCount++;
                } else {
                    $(this).addClass('d-none');
                }
            });

            if (visibleCount === 0) {
                $('#no-assignee-found').removeClass('d-none');
            } else {
                $('#no-assignee-found').addClass('d-none');
            }
        });

        // Quick select/deselect buttons
        $('#btn-select-all').on('click', function() {
            $('.assignee-item:not(.d-none) .assignee-checkbox').prop('checked', true);
        });

        $('#btn-deselect-all').on('click', function() {
            $('.assignee-checkbox').prop('checked', false);
        });
    });
</script>
<?= $this->endSection() ?>
