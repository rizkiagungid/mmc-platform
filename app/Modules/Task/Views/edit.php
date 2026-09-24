<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<style>
    .custom-scroll-container {
        -webkit-overflow-scrolling: touch;
        overscroll-behavior: contain;
        scrollbar-width: thin;
        scrollbar-color: rgba(239, 68, 68, 0.4) rgba(0, 0, 0, 0.2);
    }
    .custom-scroll-container::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scroll-container::-webkit-scrollbar-thumb {
        background: rgba(239, 68, 68, 0.4);
        border-radius: 4px;
    }
    @media (max-width: 767.98px) {
        .saas-card {
            padding: 1rem !important;
        }
    }
</style>

<div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Edit Tugas: <?= esc($task['title']) ?></h4>
        <p class="text-secondary small m-0">Perbarui rincian instruksi, status, prioritas, atau daftar anggota assignee</p>
    </div>

    <a href="<?= base_url('admin/tasks') ?>" class="btn btn-saas-dark w-100 w-sm-auto text-nowrap">
        <i class="fa-solid fa-arrow-left me-1"></i> Batal & Kembali
    </a>
</div>

<div class="saas-card p-3 p-md-4 col-12 col-lg-9 mx-auto">
    <form action="<?= base_url('admin/tasks/update/' . $task['id']) ?>" method="POST">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label text-secondary small fw-medium">Judul Tugas / Proyek <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control form-control-lg bg-black text-white border-secondary border-opacity-50" required value="<?= esc($task['title']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label text-secondary small fw-medium">Deskripsi & Instruksi Pengerjaan</label>
            <textarea name="description" class="form-control bg-black text-white border-secondary border-opacity-50" rows="4"><?= esc($task['description']) ?></textarea>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-label text-secondary small fw-medium">Prioritas Tugas <span class="text-danger">*</span></label>
                <select name="priority_id" class="form-select bg-black text-white border-secondary border-opacity-50" required>
                    <?php foreach ($priorities as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= $task['priority_id'] == $p['id'] ? 'selected' : '' ?>>
                            <?= esc($p['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-label text-secondary small fw-medium">Status Tugas <span class="text-danger">*</span></label>
                <select name="status_id" class="form-select bg-black text-white border-secondary border-opacity-50" required>
                    <?php foreach ($statuses as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= $task['status_id'] == $s['id'] ? 'selected' : '' ?>>
                            <?= esc($s['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label text-secondary small fw-medium">Batas Waktu (Deadline)</label>
                <input type="datetime-local" name="deadline" class="form-control bg-black text-white border-secondary border-opacity-50" value="<?= $task['deadline'] ? date('Y-m-d\TH:i', strtotime($task['deadline'])) : '' ?>">
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
            <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <label class="form-label text-white fw-semibold m-0">
                        <i class="fa-solid fa-users text-danger me-2"></i> Pilih Anggota Assignee &amp; Status Masing-masing <span class="text-secondary style-tiny fw-normal">(Opsional)</span>
                    </label>
                    <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50 font-monospace py-1 px-2" id="assignee-count-badge">
                        <i class="fa-solid fa-user-check me-1"></i> <span id="assignee-count-num">0</span> Terpilih
                    </span>
                    <button type="button" class="btn btn-sm btn-outline-info py-0.5 px-2.5 style-tiny rounded-pill" id="btn-toggle-preview" title="Tampilkan / Sembunyikan daftar anggota yang dipilih">
                        <i class="fa-solid fa-eye me-1"></i> <span id="toggle-preview-text">Lihat Siapa Saja</span>
                    </button>
                </div>
                <div class="btn-group btn-group-sm">
                    <button type="button" id="btn-select-all" class="btn btn-sm btn-outline-secondary py-1" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-check-double me-1"></i> Semua
                    </button>
                    <button type="button" id="btn-deselect-all" class="btn btn-sm btn-outline-secondary py-1" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-xmark me-1"></i> Hapus
                    </button>
                </div>
            </div>

            <!-- Expandable Selected Assignees Preview Box -->
            <div id="selected-assignees-box" class="p-2.5 rounded-3 bg-body-secondary border border-secondary border-opacity-25 mb-3 d-none">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-white small fw-bold">
                        <i class="fa-solid fa-clipboard-user text-danger me-1"></i> Anggota yang Ditugaskan (<span id="preview-count-num">0</span> orang):
                    </span>
                    <button type="button" class="btn btn-link text-secondary text-decoration-none p-0 style-tiny" id="btn-close-preview">
                        <i class="fa-solid fa-xmark me-1"></i> Tutup
                    </button>
                </div>
                <div class="d-flex flex-wrap gap-1.5" id="selected-chips-container" style="max-height: 150px; overflow-y: auto;">
                    <!-- Chips inserted dynamically via JS -->
                </div>
            </div>

            <div class="mb-2">
                <input type="text" id="search-assignee-input" class="form-control form-control-sm bg-black text-white border-secondary border-opacity-50" placeholder="🔍 Cari nama anggota, NIS/NIP, atau kelas/divisi...">
            </div>

            <div class="p-2 p-sm-3 rounded-3 bg-dark border border-secondary border-opacity-25 custom-scroll-container" style="max-height: 320px; overflow-y: auto;">
                <div class="row g-2" id="assignee-list-container">
                    <?php foreach ($members as $m): ?>
                        <?php 
                            $isAssigned = isset($currentAssigneeMap[$m['id']]); 
                            $memberStatusId = $isAssigned ? ($currentAssigneeMap[$m['id']]['status_id'] ?? 1) : 1;
                        ?>
                        <div class="col-12 col-md-6 assignee-item" data-search="<?= esc(strtolower($m['full_name'] . ' ' . $m['nis_nip'] . ' ' . $m['class_dept'])) ?>">
                            <div class="p-2 rounded-2 border border-secondary border-opacity-10 bg-black h-100 d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2">
                                <div class="form-check m-0 flex-grow-1 w-100">
                                    <input class="form-check-input ms-1 assignee-checkbox" type="checkbox" name="assignees[]" value="<?= $m['id'] ?>" id="assignee_<?= $m['id'] ?>" <?= $isAssigned ? 'checked' : '' ?> data-name="<?= esc($m['full_name']) ?>" data-dept="<?= esc($m['class_dept'] ?: 'Anggota') ?>">
                                    <label class="form-check-label text-white small ms-2 cursor-pointer w-100" for="assignee_<?= $m['id'] ?>">
                                        <strong class="d-block text-truncate"><?= esc($m['full_name']) ?></strong>
                                        <div class="text-secondary style-tiny"><?= esc($m['class_dept'] ?: 'Anggota') ?> (<?= esc($m['nis_nip'] ?: '-') ?>)</div>
                                    </label>
                                </div>
                                <div class="w-100 w-sm-auto" style="min-width: 140px;">
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

        <div class="d-flex flex-column-reverse flex-sm-row justify-content-end gap-2 border-top border-secondary border-opacity-25 pt-3">
            <a href="<?= base_url('admin/tasks') ?>" class="btn btn-saas-dark w-100 w-sm-auto text-center">Batal</a>
            <button type="submit" class="btn btn-red px-4 w-100 w-sm-auto">
                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function escapeHtml(text) {
        if (!text) return '';
        return $('<div>').text(text).html();
    }

    $(document).ready(function() {
        function updateSelectedAssignees() {
            const checkedBoxes = $('.assignee-checkbox:checked');
            const count = checkedBoxes.length;
            $('#assignee-count-num').text(count);
            $('#preview-count-num').text(count);

            const container = $('#selected-chips-container');
            container.empty();

            if (count === 0) {
                container.html('<span class="text-secondary style-tiny fst-italic py-1"><i class="fa-solid fa-circle-info me-1"></i> Belum ada anggota yang dipilih. Centang anggota di bawah untuk menambahkan.</span>');
            } else {
                checkedBoxes.each(function() {
                    const id = $(this).val();
                    const name = $(this).data('name') || $(this).closest('.assignee-item').find('strong').text().trim();
                    const dept = $(this).data('dept') || '';

                    const chip = $(`
                        <span class="badge bg-dark border border-secondary border-opacity-50 text-white font-monospace d-inline-flex align-items-center gap-1.5 py-1 px-2.5 style-tiny rounded-pill">
                            <i class="fa-solid fa-user text-danger"></i>
                            <span class="fw-semibold">${escapeHtml(name)}</span>
                            ${dept ? `<small class="text-secondary">(${escapeHtml(dept)})</small>` : ''}
                            <button type="button" class="btn-close btn-close-white p-0 ms-1 btn-remove-assignee-chip" style="font-size: 0.55rem; width: 10px; height: 10px;" data-id="${id}" title="Hapus"></button>
                        </span>
                    `);
                    container.append(chip);
                });
            }
        }

        // Remove chip handler
        $(document).on('click', '.btn-remove-assignee-chip', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            $('#assignee_' + id).prop('checked', false).trigger('change');
        });

        // Toggle preview container
        $('#btn-toggle-preview').on('click', function() {
            const box = $('#selected-assignees-box');
            box.toggleClass('d-none');
            const isHidden = box.hasClass('d-none');
            $('#toggle-preview-text').text(isHidden ? 'Lihat Siapa Saja' : 'Sembunyikan');
        });

        $('#btn-close-preview').on('click', function() {
            $('#selected-assignees-box').addClass('d-none');
            $('#toggle-preview-text').text('Lihat Siapa Saja');
        });

        // Checkbox change handler
        $(document).on('change', '.assignee-checkbox', function() {
            updateSelectedAssignees();
        });

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
            $('.assignee-item:not(.d-none) .assignee-checkbox').prop('checked', true).trigger('change');
        });

        $('#btn-deselect-all').on('click', function() {
            $('.assignee-checkbox').prop('checked', false).trigger('change');
        });

        // Initial count and chip population on page load
        updateSelectedAssignees();
    });
</script>
<?= $this->endSection() ?>
