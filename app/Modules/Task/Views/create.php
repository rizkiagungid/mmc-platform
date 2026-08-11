<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Buat Tugas & Proyek Baru</h4>
        <p class="text-secondary small m-0">Tugaskan proyek multimedia ke satu atau beberapa anggota secara bersamaan</p>
    </div>

    <a href="<?= base_url('admin/tasks') ?>" class="btn btn-saas-dark">
        <i class="fa-solid fa-arrow-left me-1"></i> Batal & Kembali
    </a>
</div>

<div class="saas-card p-4 col-lg-9 mx-auto">
    <form action="<?= base_url('admin/tasks/store') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label text-secondary small fw-medium">Judul Tugas / Proyek <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control form-control-lg" placeholder="Contoh: Aftermovie MPLS SMAN 1 Tamansari 2026" required value="<?= old('title') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label text-secondary small fw-medium">Deskripsi & Instruksi Pengerjaan</label>
            <textarea name="description" class="form-control" rows="4" placeholder="Jelaskan detail instruksi, durasi video, spesifikasi karya, atau tautan aset..."><?= old('description') ?></textarea>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label text-secondary small fw-medium">Prioritas Tugas <span class="text-danger">*</span></label>
                <select name="priority_id" class="form-select" required>
                    <?php foreach ($priorities as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= old('priority_id') == $p['id'] ? 'selected' : '' ?>>
                            <?= esc($p['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label text-secondary small fw-medium">Status Awal <span class="text-danger">*</span></label>
                <select name="status_id" class="form-select" required>
                    <?php foreach ($statuses as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= old('status_id') == $s['id'] ? 'selected' : '' ?>>
                            <?= esc($s['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label text-secondary small fw-medium">Batas Waktu (Deadline)</label>
                <input type="datetime-local" name="deadline" class="form-control" value="<?= old('deadline') ?>">
            </div>
        </div>

        <!-- Multi-Assignee Section with Division Filtering -->
        <div class="mb-4">
            <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                <label class="form-label text-white fw-semibold m-0">
                    <i class="fa-solid fa-users text-danger me-2"></i> Pilih Anggota Assignee <span class="text-danger">*</span>
                </label>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2 btn-filter-division" data-division="programming" style="font-size: 0.75rem;">
                            <i class="fa-solid fa-code me-1"></i> + Assign Divisi Programming
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 btn-filter-division" data-division="broadcasting" style="font-size: 0.75rem;">
                            <i class="fa-solid fa-tower-cell me-1"></i> + Assign Divisi Broadcasting
                        </button>
                    </div>

                    <div class="btn-group btn-group-sm">
                        <button type="button" id="btn-select-all" class="btn btn-sm btn-outline-secondary py-1" style="font-size: 0.75rem;">
                            <i class="fa-solid fa-check-double me-1"></i> Pilih Semua
                        </button>
                        <button type="button" id="btn-deselect-all" class="btn btn-sm btn-outline-secondary py-1" style="font-size: 0.75rem;">
                            <i class="fa-solid fa-xmark me-1"></i> Hapus Pilihan
                        </button>
                    </div>
                </div>
            </div>

            <div class="mb-2">
                <input type="text" id="search-assignee-input" class="form-control form-control-sm bg-black text-white border-secondary border-opacity-50" placeholder="🔍 Cari nama anggota, NIS/NIP, atau kelas/divisi...">
            </div>

            <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25" style="max-height: 280px; overflow-y: auto;">
                <div class="row g-2" id="assignee-list-container">
                    <?php foreach ($members as $m): ?>
                        <?php
                            $classDept = strtolower($m['class_dept'] ?? '');
                            $division = 'other';
                            $divisionBadge = 'Anggota';
                            $badgeClass = 'bg-secondary bg-opacity-50 text-light';

                            if (str_contains($classDept, 'programming')) {
                                $division = 'programming';
                                $divisionBadge = 'Programming';
                                $badgeClass = 'bg-primary bg-opacity-25 text-primary border border-primary border-opacity-25';
                            } elseif (str_contains($classDept, 'broadcasting')) {
                                $division = 'broadcasting';
                                $divisionBadge = 'Broadcasting';
                                $badgeClass = 'bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25';
                            }
                        ?>
                        <div class="col-md-6 col-lg-4 assignee-item" data-division="<?= $division ?>" data-search="<?= esc(strtolower($m['full_name'] . ' ' . $m['nis_nip'] . ' ' . $m['class_dept'])) ?>">
                            <div class="form-check p-2 rounded-2 border border-secondary border-opacity-10 bg-black h-100">
                                <input class="form-check-input ms-1 assignee-checkbox" type="checkbox" name="assignees[]" value="<?= $m['id'] ?>" id="assignee_<?= $m['id'] ?>">
                                <label class="form-check-label text-white small ms-2 cursor-pointer w-100 pe-1" for="assignee_<?= $m['id'] ?>">
                                    <div class="d-flex align-items-center justify-content-between me-1 mb-1">
                                        <strong class="text-truncate me-1" style="max-width: 130px;"><?= esc($m['full_name']) ?></strong>
                                        <span class="badge <?= $badgeClass ?> font-monospace style-tiny"><?= esc($divisionBadge) ?></span>
                                    </div>
                                    <div class="text-secondary style-tiny text-truncate"><?= esc($m['class_dept'] ?: 'Anggota') ?> (<?= esc($m['nis_nip'] ?: '-') ?>)</div>
                                </label>
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
                <i class="fa-solid fa-paper-plane me-1"></i> Buat & Tugaskan Proyek
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Quick select by division buttons
        $('.btn-filter-division').on('click', function() {
            const targetDivision = $(this).data('division');
            $(`.assignee-item[data-division="${targetDivision}"] .assignee-checkbox`).prop('checked', true);
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
            $('.assignee-item:not(.d-none) .assignee-checkbox').prop('checked', true);
        });

        $('#btn-deselect-all').on('click', function() {
            $('.assignee-checkbox').prop('checked', false);
        });
    });
</script>
<?= $this->endSection() ?>
