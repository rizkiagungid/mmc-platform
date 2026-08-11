<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-white font-heading mb-1">
                <i class="fa-solid fa-hard-drive text-danger me-2"></i> Asset & Storage Disk Server
            </h4>
            <p class="text-secondary small mb-0">
                Pantau dan hapus file hasil unggahan (assets/uploads) dari semua role pengguna secara permanen.
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= current_url() ?>" class="btn btn-saas-dark btn-sm">
                <i class="fa-solid fa-rotate me-1"></i> Refresh Storage
            </a>
        </div>
    </div>

    <!-- Alert Danger Warning -->
    <div class="alert bg-danger bg-opacity-10 border border-danger border-opacity-25 text-danger rounded-3 mb-4 d-flex align-items-center gap-3">
        <i class="fa-solid fa-triangle-exclamation fs-3 flex-shrink-0"></i>
        <div class="small">
            <strong>Peringatan Akses Superadmin:</strong> Penghapusan file melalui menu ini bersifat <strong>PERMANEN</strong> fisik dari disk server. File yang terhapus tidak dapat dipulihkan. Pastikan file yang dihapus bukan file penting sistem.
        </div>
    </div>

    <!-- Storage Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-danger bg-opacity-25 text-danger fs-4">
                    <i class="fa-solid fa-database"></i>
                </div>
                <div>
                    <div class="text-secondary style-tiny text-uppercase font-monospace">TOTAL UPLOAD DISK</div>
                    <h4 class="fw-bold text-white font-heading m-0"><?= esc($overview['total_size_fmt']) ?></h4>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-info bg-opacity-25 text-info fs-4">
                    <i class="fa-solid fa-file-stack"></i>
                </div>
                <div>
                    <div class="text-secondary style-tiny text-uppercase font-monospace">TOTAL FILE UPLOADED</div>
                    <h4 class="fw-bold text-white font-heading m-0"><?= number_format($overview['total_files']) ?> File</h4>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-warning bg-opacity-25 text-warning fs-4">
                    <i class="fa-solid fa-folder-tree"></i>
                </div>
                <div>
                    <div class="text-secondary style-tiny text-uppercase font-monospace">KATEGORI FOLDER</div>
                    <h4 class="fw-bold text-white font-heading m-0"><?= count($overview['categories']) ?> Direktori</h4>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-success bg-opacity-25 text-success fs-4">
                    <i class="fa-solid fa-server"></i>
                </div>
                <div>
                    <div class="text-secondary style-tiny text-uppercase font-monospace">PATH ROOT UPLOADS</div>
                    <span class="badge bg-dark border border-secondary text-success font-monospace style-tiny text-truncate" style="max-width: 140px;" title="public/uploads">public/uploads</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main File Manager Card -->
    <div class="card bg-dark border border-secondary border-opacity-25 shadow-lg rounded-3">
        <!-- Filter Tabs & Bulk Actions -->
        <div class="card-header border-bottom border-secondary border-opacity-25 bg-black p-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="text-secondary style-tiny font-monospace text-uppercase me-1"><i class="fa-solid fa-filter me-1"></i> Folder:</span>
                <a href="<?= base_url('admin/storage?category=all') ?>" class="btn btn-sm <?= ($selectedCategory === 'all') ? 'btn-red' : 'btn-saas-dark' ?> style-tiny">
                    Semua (<?= $overview['total_files'] ?>)
                </a>
                <?php foreach ($overview['categories'] as $catName => $catInfo): ?>
                    <a href="<?= base_url('admin/storage?category=' . esc($catName)) ?>" class="btn btn-sm <?= ($selectedCategory === $catName) ? 'btn-red' : 'btn-saas-dark' ?> style-tiny">
                        <?= esc($catName) ?> (<?= $catInfo['file_count'] ?> - <?= $catInfo['size_formatted'] ?>)
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Bulk Delete Form Trigger -->
            <div>
                <button type="button" id="btnBulkDelete" class="btn btn-sm btn-outline-danger style-tiny font-monospace d-none" onclick="confirmBulkDelete()">
                    <i class="fa-solid fa-trash-can me-1"></i> Hapus Terpilih (<span id="selectedCount">0</span>)
                </button>
            </div>
        </div>

        <div class="card-body p-3">
            <form id="bulkDeleteForm" action="<?= base_url('admin/storage/bulk-delete') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle style-tiny w-100" id="storageDataTable">
                        <thead>
                            <tr class="text-secondary text-uppercase font-monospace border-secondary border-opacity-25">
                                <th width="35" class="text-center">
                                    <input type="checkbox" class="form-check-input" id="selectAllFiles">
                                </th>
                                <th>Preview / Nama File</th>
                                <th>Kategori / Folder</th>
                                <th>Ukuran File</th>
                                <th>Terakhir Dimodifikasi</th>
                                <th class="text-end" width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($files as $file): ?>
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="file_paths[]" value="<?= esc($file['relative_path']) ?>" class="form-check-input file-checkbox">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <?php if ($file['is_image']): ?>
                                                <img src="<?= esc($file['url']) ?>" alt="Preview" class="rounded border border-secondary border-opacity-25 object-fit-cover flex-shrink-0" style="width: 42px; height: 42px;">
                                            <?php else: ?>
                                                <div class="rounded bg-secondary bg-opacity-25 border border-secondary border-opacity-25 d-flex align-items-center justify-content-center text-info flex-shrink-0" style="width: 42px; height: 42px;">
                                                    <i class="fa-solid fa-file-lines fs-5"></i>
                                                </div>
                                            <?php endif; ?>

                                            <div class="d-flex flex-column text-truncate" style="max-width: 320px;">
                                                <a href="<?= esc($file['url']) ?>" target="_blank" class="fw-bold text-white text-decoration-none text-truncate hover-danger" title="<?= esc($file['file_name']) ?>">
                                                    <?= esc($file['file_name']) ?>
                                                </a>
                                                <span class="text-secondary font-monospace style-tiny text-truncate">
                                                    <?= esc($file['relative_path']) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary bg-opacity-25 border border-secondary border-opacity-25 text-info font-monospace">
                                            <?= esc($file['category']) ?>
                                        </span>
                                    </td>
                                    <td class="font-monospace text-warning fw-bold">
                                        <?= esc($file['size_formatted']) ?>
                                    </td>
                                    <td class="font-monospace text-secondary">
                                        <?= esc($file['modified_at']) ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1">
                                            <a href="<?= esc($file['url']) ?>" target="_blank" class="btn btn-sm btn-saas-dark px-2" title="Buka / Download">
                                                <i class="fa-solid fa-up-right-from-square"></i>
                                            </a>

                                            <button type="button" class="btn btn-sm btn-outline-danger px-2" title="Hapus Permanen" onclick="confirmSingleDelete('<?= esc($file['relative_path']) ?>', '<?= esc($file['file_name']) ?>')">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Single Delete Form (Hidden) -->
<form id="singleDeleteForm" action="<?= base_url('admin/storage/delete') ?>" method="POST" class="d-none">
    <?= csrf_field() ?>
    <input type="hidden" name="file_path" id="singleDeleteFilePath">
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#storageDataTable').DataTable({
        language: {
            search: "Cari File:",
            lengthMenu: "Tampilkan _MENU_ file",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ file",
            infoEmpty: "Tidak ada file ditemukan",
            zeroRecords: "Tidak ada file yang cocok dengan pencarian",
            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "<i class='fa-solid fa-chevron-right'></i>",
                previous: "<i class='fa-solid fa-chevron-left'></i>"
            }
        },
        pageLength: 25,
        order: [[4, 'desc']], // Sort by modified date
        columnDefs: [
            { orderable: false, targets: [0, 5] }
        ]
    });

    // Checkbox select all handler
    $('#selectAllFiles').on('change', function() {
        $('.file-checkbox').prop('checked', this.checked);
        updateBulkDeleteButton();
    });

    $(document).on('change', '.file-checkbox', function() {
        updateBulkDeleteButton();
    });

    function updateBulkDeleteButton() {
        const checkedCount = $('.file-checkbox:checked').length;
        $('#selectedCount').text(checkedCount);
        if (checkedCount > 0) {
            $('#btnBulkDelete').removeClass('d-none');
        } else {
            $('#btnBulkDelete').addClass('d-none');
        }
    }
});

function confirmSingleDelete(path, fileName) {
    Swal.fire({
        title: 'Hapus File Permanen?',
        html: `Apakah Anda yakin ingin menghapus <strong>${fileName}</strong>?<br><br><span class="text-danger small font-monospace">Path: ${path}</span><br><small class="text-secondary">File ini akan terhapus fisik dari disk server dan tidak bisa dikembalikan!</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fa-solid fa-trash-can me-1"></i> Ya, Hapus Permanen',
        cancelButtonText: 'Batal',
        background: '#121218',
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#singleDeleteFilePath').val(path);
            $('#singleDeleteForm').submit();
        }
    });
}

function confirmBulkDelete() {
    const checkedCount = $('.file-checkbox:checked').length;
    if (checkedCount === 0) return;

    Swal.fire({
        title: `Hapus ${checkedCount} File Terpilih?`,
        html: `Seluruh <strong>${checkedCount} file</strong> yang dipilih akan terhapus secara <strong>PERMANEN</strong> dari disk server!<br><small class="text-secondary">Tindakan ini tidak dapat dibatalkan.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `<i class="fa-solid fa-trash-can me-1"></i> Ya, Hapus ${checkedCount} File`,
        cancelButtonText: 'Batal',
        background: '#121218',
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#bulkDeleteForm').submit();
        }
    });
}
</script>
<?= $this->endSection() ?>
