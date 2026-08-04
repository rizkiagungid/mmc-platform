<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Manajemen Pengguna & Anggota</h4>
        <p class="text-secondary small m-0">Kelola akun Super Admin, Pembina, BPH, dan Anggota Club</p>
    </div>
    <a href="<?= base_url('admin/users/create') ?>" class="btn btn-red">
        <i class="fa-solid fa-user-plus me-1"></i> Tambah Anggota Baru
    </a>
</div>

<!-- Search & Filter Card -->
<div class="saas-card p-3 mb-4">
    <form action="<?= current_url() ?>" method="GET" class="row g-2 align-items-center">
        <div class="col-md-5 col-lg-5">
            <div class="input-group">
                <span class="input-group-text bg-black border-secondary border-opacity-25 text-secondary">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" id="custom-user-search" name="keyword" class="form-control bg-black text-white border-secondary border-opacity-25" placeholder="Cari nama, username, email, NIS/NIP, kelas/divisi..." value="<?= esc($keyword ?? '') ?>">
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <select name="role_id" id="role-filter-select" class="form-select bg-black text-white border-secondary border-opacity-25">
                <option value="">-- Semua Role --</option>
                <?php foreach ($roles as $r): ?>
                    <option value="<?= $r['id'] ?>" <?= (isset($roleId) && $roleId == $r['id']) ? 'selected' : '' ?>>
                        <?= esc($r['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3 col-lg-3 d-flex gap-2">
            <button type="submit" class="btn btn-red w-100">
                <i class="fa-solid fa-filter me-1"></i> Filter
            </button>
            <?php if (!empty($keyword) || !empty($roleId)): ?>
                <a href="<?= base_url('admin/users') ?>" class="btn btn-saas-dark" title="Reset Filter">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Floating Bulk Actions Bar -->
<div id="bulk-actions-bar" class="saas-card p-3 mb-3 border border-warning border-opacity-50 bg-black bg-opacity-75 d-none animate__animated animate__fadeIn">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-warning text-dark fs-6 px-3 py-2 fw-bold" id="selected-count">0 Anggota Dipilih</span>
            <small class="text-secondary">Pilih aksi massal yang ingin diterapkan pada anggota terpilih:</small>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button type="button" class="btn btn-warning btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#bulkEditModal">
                <i class="fa-solid fa-pen-to-square me-1"></i> Edit Data Massal
            </button>
            <button type="button" class="btn btn-info btn-sm text-white fw-bold" id="btn-bulk-qr">
                <i class="fa-solid fa-qrcode me-1"></i> Regenerasi QR Massal
            </button>
            <button type="button" class="btn btn-success btn-sm fw-bold" id="btn-bulk-activate">
                <i class="fa-solid fa-user-check me-1"></i> Aktifkan Massal
            </button>
            <button type="button" class="btn btn-outline-danger btn-sm fw-semibold" id="btn-bulk-delete">
                <i class="fa-solid fa-trash me-1"></i> Hapus Massal
            </button>
        </div>
    </div>
</div>

<div class="saas-card p-4">
    <div class="table-responsive">
        <table id="users-table" class="table table-dark-saas datatable-saas align-middle">
            <thead>
                <tr>
                    <th style="width: 40px;" class="text-center">
                        <input type="checkbox" id="check-all-users" class="form-check-input">
                    </th>
                    <th>Member Info</th>
                    <th>Role</th>
                    <th>NIS / NIP</th>
                    <th>Kelas / Divisi</th>
                    <th>QR Version</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" value="<?= $u['id'] ?>" class="form-check-input user-select-chk">
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <?php if (!empty($u['avatar'])): ?>
                                    <img src="<?= base_url($u['avatar']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-danger border-opacity-50 shadow-sm" style="width: 40px; height: 40px; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform='scale(1)'" data-bs-toggle="modal" data-bs-target="#userAvatarModal-<?= $u['id'] ?>" title="Klik untuk lihat foto full">
                                <?php else: ?>
                                    <div class="rounded-circle bg-danger bg-opacity-25 text-danger fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <?= strtoupper(substr($u['full_name'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <div class="fw-semibold text-white"><?= esc($u['full_name']) ?></div>
                                    <small class="text-secondary font-monospace">@<?= esc($u['username']) ?> | <?= esc($u['email']) ?></small>
                                </div>
                            </div>

                            <?php if (!empty($u['avatar'])): ?>
                                <!-- Modal Preview Foto Full User ID <?= $u['id'] ?> -->
                                <div class="modal fade" id="userAvatarModal-<?= $u['id'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
                                            <div class="modal-header border-bottom border-secondary border-opacity-25">
                                                <h5 class="modal-title font-heading"><i class="fa-solid fa-image text-danger me-2"></i> Foto Profil Full - <?= esc($u['full_name']) ?></h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center p-4">
                                                <img src="<?= base_url($u['avatar']) ?>" alt="Foto Full <?= esc($u['full_name']) ?>" class="img-fluid rounded-3 shadow-lg border border-secondary border-opacity-50" style="max-height: 75vh; object-fit: contain;">
                                            </div>
                                            <div class="modal-footer border-top border-secondary border-opacity-25 justify-content-between">
                                                <a href="<?= base_url($u['avatar']) ?>" target="_blank" class="btn btn-sm btn-outline-light">
                                                    <i class="fa-solid fa-external-link me-1"></i> Buka File Asli
                                                </a>
                                                <button type="button" class="btn btn-saas-dark btn-sm" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 px-2.5 py-1">
                                <?= esc($u['role_name']) ?>
                            </span>
                        </td>
                        <td class="font-monospace small text-secondary"><?= esc($u['nis_nip'] ?: '-') ?></td>
                        <td class="small text-secondary"><?= esc($u['class_dept'] ?: '-') ?></td>
                        <td class="font-monospace small text-center">
                            <span class="badge bg-dark border border-secondary text-secondary">v<?= esc($u['qr_version']) ?></span>
                        </td>
                        <td>
                            <?php if ($u['status'] === 'active'): ?>
                                <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25"><i class="fa-solid fa-circle-check me-1"></i> AKTIF</span>
                            <?php elseif ($u['status'] === 'inactive'): ?>
                                <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-50"><i class="fa-solid fa-clock me-1"></i> MENUNGGU KONFIRMASI</span>
                            <?php elseif ($u['status'] === 'left' || $u['status'] === 'keluar'): ?>
                                <span class="badge bg-secondary bg-opacity-25 text-secondary border border-secondary border-opacity-25">KELUAR EKSKUL</span>
                            <?php elseif ($u['status'] === 'suspended'): ?>
                                <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25">SUSPENDED</span>
                            <?php else: ?>
                                <span class="badge bg-secondary bg-opacity-25 text-secondary border border-secondary">NONAKTIF</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end align-items-center gap-1">
                                <?php if ($u['status'] === 'inactive'): ?>
                                    <a href="<?= base_url('admin/users/activate/' . $u['id']) ?>" class="btn btn-sm btn-success px-2 py-1 style-tiny font-monospace" onclick="return confirm('Konfirmasi dan aktifkan akun pendaftar <?= esc($u['full_name']) ?>?')" title="Konfirmasi & Aktifkan Akun">
                                        <i class="fa-solid fa-user-check me-1"></i> Aktifkan
                                    </a>
                                <?php endif; ?>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-saas-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Pilihan
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-dark">
                                        <?php if ($u['status'] === 'inactive'): ?>
                                            <li><a class="dropdown-item text-success fw-bold" href="<?= base_url('admin/users/activate/' . $u['id']) ?>" onclick="return confirm('Konfirmasi dan aktifkan akun pendaftar <?= esc($u['full_name']) ?>?')"><i class="fa-solid fa-user-check me-2"></i> Konfirmasi & Aktifkan Akun</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                        <?php endif; ?>
                                        <li><a class="dropdown-item" href="<?= base_url('admin/users/qr/' . $u['member_uuid']) ?>"><i class="fa-solid fa-qrcode me-2 text-warning"></i> Lihat ID Card & QR</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('admin/users/regenerate-qr/' . $u['id']) ?>" onclick="return confirm('Regenerasi QR akan membatalkan QR lama. Lanjutkan?')"><i class="fa-solid fa-arrows-rotate me-2 text-info"></i> Regenerasi Member QR</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="<?= base_url('admin/users/edit/' . $u['id']) ?>"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Edit Profile</a></li>
                                        <li><a class="dropdown-item text-danger" href="<?= base_url('admin/users/delete/' . $u['id']) ?>" onclick="return confirm('Hapus pengguna ini?')"><i class="fa-solid fa-trash me-2"></i> Hapus</a></li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Edit Massal Anggota -->
<div class="modal fade" id="bulkEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title font-heading">
                    <i class="fa-solid fa-user-pen text-warning me-2"></i> Edit Data Anggota Secara Massal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/users/bulk-update') ?>" method="POST" id="bulkEditForm">
                <?= csrf_field() ?>
                <div id="bulk-edit-hidden-ids"></div>
                
                <div class="modal-body">
                    <div class="alert alert-dark border border-secondary border-opacity-25 mb-4">
                        <i class="fa-solid fa-circle-info text-info me-2"></i>
                        Centang bidang data yang ingin Anda perbarui untuk <strong class="text-warning" id="bulk-edit-count-label">0 anggota</strong> terpilih. Bidang yang tidak dicentang tidak akan diubah.
                    </div>

                    <div class="row g-3">
                        <!-- Option 1: Status Akun -->
                        <div class="col-12">
                            <div class="saas-card p-3 bg-black bg-opacity-25 border border-secondary border-opacity-25">
                                <div class="form-check mb-2">
                                    <input class="form-check-input bulk-field-chk" type="checkbox" name="change_status" value="1" id="chk_change_status" data-target="#select_bulk_status">
                                    <label class="form-check-label fw-bold text-white" for="chk_change_status">
                                        <i class="fa-solid fa-circle-notch text-warning me-1"></i> Ubah Status Akun Massal
                                    </label>
                                </div>
                                <select name="status" id="select_bulk_status" class="form-select bg-dark text-white border-secondary border-opacity-50" disabled>
                                    <option value="active">Aktif (Active)</option>
                                    <option value="inactive">Menunggu Konfirmasi (Inactive)</option>
                                    <option value="left">Keluar Ekskul (Left)</option>
                                    <option value="suspended">Suspended (Ditangguhkan)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Option 2: Role / Jabatan -->
                        <div class="col-12">
                            <div class="saas-card p-3 bg-black bg-opacity-25 border border-secondary border-opacity-25">
                                <div class="form-check mb-2">
                                    <input class="form-check-input bulk-field-chk" type="checkbox" name="change_role" value="1" id="chk_change_role" data-target="#select_bulk_role">
                                    <label class="form-check-label fw-bold text-white" for="chk_change_role">
                                        <i class="fa-solid fa-user-shield text-info me-1"></i> Ubah Role / Jabatan Massal
                                    </label>
                                </div>
                                <select name="role_id" id="select_bulk_role" class="form-select bg-dark text-white border-secondary border-opacity-50" disabled>
                                    <?php foreach ($roles as $r): ?>
                                        <option value="<?= $r['id'] ?>"><?= esc($r['name']) ?> (<?= esc($r['slug']) ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Option 3: Kelas / Divisi -->
                        <div class="col-12">
                            <div class="saas-card p-3 bg-black bg-opacity-25 border border-secondary border-opacity-25">
                                <div class="form-check mb-2">
                                    <input class="form-check-input bulk-field-chk" type="checkbox" name="change_class" value="1" id="chk_change_class" data-target="#input_bulk_class">
                                    <label class="form-check-label fw-bold text-white" for="chk_change_class">
                                        <i class="fa-solid fa-graduation-cap text-success me-1"></i> Ubah Kelas / Divisi Massal
                                    </label>
                                </div>
                                <input type="text" name="class_dept" id="input_bulk_class" class="form-control bg-dark text-white border-secondary border-opacity-50" placeholder="Contoh: XII Multimedia 1 / Divisi Videography" disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top border-secondary border-opacity-25">
                    <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold">
                        <i class="fa-solid fa-check-double me-1"></i> Simpan Perubahan Massal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        const table = $('#users-table').DataTable({
            order: [],
            columnDefs: [
                { orderable: false, targets: 0 }
            ],
            language: {
                search: "Cari Cepat:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ pengguna",
                paginate: { first: "Awal", last: "Akhir", next: "▶", previous: "◀" },
                zeroRecords: "Tidak ada pengguna yang sesuai"
            }
        });

        $('#custom-user-search').on('keyup input', function() {
            table.search(this.value).draw();
        });

        // Store selected user IDs
        let selectedUserIds = [];

        function updateSelectionUI() {
            const count = selectedUserIds.length;
            if (count > 0) {
                $('#bulk-actions-bar').removeClass('d-none');
                $('#selected-count').text(`${count} Anggota Dipilih`);
                $('#bulk-edit-count-label').text(`${count} anggota`);
            } else {
                $('#bulk-actions-bar').addClass('d-none');
                $('#check-all-users').prop('checked', false);
            }
        }

        // Handle Check All
        $('#check-all-users').on('change', function() {
            const isChecked = $(this).is(':checked');
            selectedUserIds = [];
            $('.user-select-chk').each(function() {
                $(this).prop('checked', isChecked);
                if (isChecked) {
                    selectedUserIds.push($(this).val());
                }
            });
            updateSelectionUI();
        });

        // Handle Individual Checkbox
        $(document).on('change', '.user-select-chk', function() {
            const val = $(this).val();
            if ($(this).is(':checked')) {
                if (!selectedUserIds.includes(val)) {
                    selectedUserIds.push(val);
                }
            } else {
                selectedUserIds = selectedUserIds.filter(id => id !== val);
            }
            updateSelectionUI();
        });

        // Enable/Disable form inputs inside Bulk Edit Modal
        $('.bulk-field-chk').on('change', function() {
            const target = $(this).data('target');
            $(target).prop('disabled', !$(this).is(':checked'));
        });

        // Populate hidden inputs when Bulk Edit Modal opens
        $('#bulkEditModal').on('show.bs.modal', function() {
            let hiddenContainer = $('#bulk-edit-hidden-ids');
            hiddenContainer.empty();
            selectedUserIds.forEach(id => {
                hiddenContainer.append(`<input type="hidden" name="user_ids[]" value="${id}">`);
            });
        });

        // Helper function to submit bulk actions
        function submitBulkAction(action, confirmTitle, confirmText, confirmBtnText, btnColor) {
            if (selectedUserIds.length === 0) return;

            Swal.fire({
                title: confirmTitle,
                text: confirmText.replace('{count}', selectedUserIds.length),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: btnColor,
                cancelButtonColor: '#27272a',
                confirmButtonText: confirmBtnText,
                cancelButtonText: 'Batal',
                background: '#121218',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = $('<form action="<?= base_url("admin/users/bulk-action") ?>" method="POST"></form>');
                    form.append('<?= csrf_field() ?>');
                    form.append(`<input type="hidden" name="action" value="${action}">`);
                    selectedUserIds.forEach(id => {
                        form.append(`<input type="hidden" name="user_ids[]" value="${id}">`);
                    });
                    $('body').append(form);
                    form.submit();
                }
            });
        }

        // Bulk Action Handlers
        $('#btn-bulk-activate').on('click', function() {
            submitBulkAction('activate', 'Aktifkan Akun Massal?', 'Apakah Anda yakin ingin mengkonfirmasi dan mengaktifkan {count} akun anggota terpilih?', 'Ya, Aktifkan Semua', '#16a34a');
        });

        $('#btn-bulk-qr').on('click', function() {
            submitBulkAction('regenerate_qr', 'Regenerasi QR Massal?', 'Regenerasi Member QR Code untuk {count} anggota akan membatalkan kartu QR lama mereka. Lanjutkan?', 'Ya, Regenerasi QR', '#0891b2');
        });

        $('#btn-bulk-delete').on('click', function() {
            submitBulkAction('delete', 'Hapus Massal Anggota?', 'Apakah Anda yakin ingin menghapus {count} anggota terpilih dari sistem?', 'Ya, Hapus Semua', '#dc2626');
        });
    });
</script>
<?= $this->endSection() ?>
