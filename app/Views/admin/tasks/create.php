<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <a href="<?= base_url('admin/tasks') ?>" class="btn btn-sm btn-saas-dark mb-2">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Tugas
    </a>
    <h4 class="text-white font-heading m-0">Buat Tugas / Proyek Multi-Assignee Baru</h4>
</div>

<div class="saas-card p-4 col-lg-9">
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger border-0 bg-danger bg-opacity-25 text-danger small p-3 mb-4 rounded-3">
            <ul class="mb-0 ps-3">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/tasks/store') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label text-secondary small fw-medium">Judul Tugas / Proyek</label>
                <input type="text" name="title" class="form-control" placeholder="Contoh: Aftermovie MPLS SMAN 1 Tamansari" value="<?= old('title') ?>" required>
            </div>

            <div class="col-md-12">
                <label class="form-label text-secondary small fw-medium">Deskripsi & Instruksi Tugas</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Jelaskan kebutuhan, acuan link, dan instruksi penugasan..."><?= old('description') ?></textarea>
            </div>

            <div class="col-md-4">
                <label class="form-label text-secondary small fw-medium">Status Awal</label>
                <select name="status_id" class="form-select" required>
                    <?php foreach ($statuses as $st): ?>
                        <option value="<?= $st['id'] ?>"><?= esc($st['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label text-secondary small fw-medium">Tingkat Prioritas</label>
                <select name="priority_id" class="form-select" required>
                    <?php foreach ($priorities as $pr): ?>
                        <option value="<?= $pr['id'] ?>"><?= esc($pr['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label text-secondary small fw-medium">Tenggat Waktu (Deadline)</label>
                <input type="datetime-local" name="deadline" class="form-control" value="<?= old('deadline') ?>">
            </div>

            <!-- Multi Assignee Select -->
            <div class="col-md-12">
                <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                    <label class="form-label text-secondary small fw-medium m-0">Tugaskan Kepada Anggota (Bisa Pilih Banyak)</label>

                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 btn-filter-division" data-division="programming" style="font-size: 0.75rem;">
                                <i class="fa-solid fa-code me-1"></i> + Assign Divisi Programming
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 btn-filter-division" data-division="broadcasting" style="font-size: 0.75rem;">
                                <i class="fa-solid fa-tower-cell me-1"></i> + Assign Divisi Broadcasting
                            </button>
                        </div>

                        <div class="btn-group btn-group-sm">
                            <button type="button" id="btn-select-all" class="btn btn-sm btn-outline-secondary py-0" style="font-size: 0.75rem;">
                                Pilih Semua
                            </button>
                            <button type="button" id="btn-deselect-all" class="btn btn-sm btn-outline-secondary py-0" style="font-size: 0.75rem;">
                                Hapus Pilihan
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25" style="max-height: 250px; overflow-y: auto;">
                    <div class="row g-2">
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
                            <div class="col-md-6 assignee-item" data-division="<?= $division ?>">
                                <div class="form-check p-2 rounded-2 border border-secondary border-opacity-10 bg-black h-100">
                                    <input class="form-check-input ms-1 assignee-checkbox" type="checkbox" name="assignees[]" value="<?= $m['id'] ?>" id="asgn_<?= $m['id'] ?>">
                                    <label class="form-check-label text-white small ms-2 cursor-pointer w-100 pe-1" for="asgn_<?= $m['id'] ?>">
                                        <div class="d-flex align-items-center justify-content-between me-1 mb-1">
                                            <strong class="text-truncate me-1"><?= esc($m['full_name']) ?></strong>
                                            <span class="badge <?= $badgeClass ?> font-monospace style-tiny"><?= esc($divisionBadge) ?></span>
                                        </div>
                                        <div class="text-secondary style-tiny text-truncate"><?= esc($m['class_dept'] ?: $m['username']) ?></div>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Dynamic Labels Select -->
            <div class="col-md-12">
                <label class="form-label text-secondary small fw-medium d-block">Label / Tag Kategori</label>
                <div class="d-flex flex-wrap gap-2">
                    <?php foreach ($labels as $lbl): ?>
                        <input type="checkbox" class="btn-check" name="labels[]" value="<?= $lbl['id'] ?>" id="lbl_<?= $lbl['id'] ?>" autocomplete="off">
                        <label class="btn btn-sm btn-outline-secondary text-white rounded-pill px-3" for="lbl_<?= $lbl['id'] ?>">
                            <i class="fa-solid fa-tag me-1" style="color: <?= $lbl['color'] ?>;"></i> <?= esc($lbl['name']) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top border-secondary border-opacity-25 d-flex gap-2">
            <button type="submit" class="btn btn-red px-4">
                <i class="fa-solid fa-save me-1"></i> Buat & Ditugaskan
            </button>
            <a href="<?= base_url('admin/tasks') ?>" class="btn btn-saas-dark">Batal</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('.btn-filter-division').on('click', function() {
            const targetDivision = $(this).data('division');
            $(`.assignee-item[data-division="${targetDivision}"] .assignee-checkbox`).prop('checked', true);
        });

        $('#btn-select-all').on('click', function() {
            $('.assignee-checkbox').prop('checked', true);
        });

        $('#btn-deselect-all').on('click', function() {
            $('.assignee-checkbox').prop('checked', false);
        });
    });
</script>
<?= $this->endSection() ?>
