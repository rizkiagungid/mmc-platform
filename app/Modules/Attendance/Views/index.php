<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Rekap & Kelola Presensi</h4>
        <p class="text-secondary small m-0">Monitor kehadiran anggota, scanner operator, dan presensi manual</p>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exportFilterModal">
            <i class="fa-solid fa-file-excel me-2"></i> Export CSV / Excel
        </button>
        <?php if (session()->get('role_slug') !== 'superadmin'): ?>
            <a href="<?= base_url('attendance/scan') ?>" class="btn btn-warning">
                <i class="fa-solid fa-camera me-2"></i> Presensi Saya (QR / PIN)
            </a>
        <?php endif; ?>
        <a href="<?= base_url('admin/attendance/scan-member') ?>" class="btn btn-red">
            <i class="fa-solid fa-qrcode me-2"></i> Buka Scanner Operator (Member QR)
        </a>
        <button type="button" class="btn btn-saas-dark" data-bs-toggle="modal" data-bs-target="#manualAttendanceModal">
            <i class="fa-solid fa-pen-to-square me-2"></i> Presensi Manual
        </button>
    </div>
</div>

<!-- Meeting Filter Card -->
<div class="saas-card p-3 mb-4">
    <form method="GET" action="<?= base_url('admin/attendance') ?>" class="row g-3 align-items-center">
        <div class="col-md-8">
            <label class="form-label text-secondary small fw-medium">Filter Sesi Pertemuan / Workshop:</label>
            <select name="meeting_id" class="form-select" onchange="this.form.submit()">
                <option value="all" <?= ($selectedMeetingId === 'all') ? 'selected' : '' ?>>-- Semua Sesi Pertemuan / Rekap Total Presensi --</option>
                <?php foreach ($meetings as $m): ?>
                    <option value="<?= $m['id'] ?>" <?= ($selectedMeetingId == $m['id']) ? 'selected' : '' ?>>
                        <?= esc($m['title']) ?> (<?= date('d M Y', strtotime($m['meeting_date'])) ?>) <?= $m['status'] === 'active' ? '[SESI AKTIF]' : '' ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4 text-md-end pt-md-4">
            <?php if ($currentMeeting): ?>
                <a href="<?= base_url('admin/meetings/qr/' . $currentMeeting['id']) ?>" target="_blank" class="btn btn-outline-danger btn-sm">
                    <i class="fa-solid fa-display me-1"></i> Tampilkan Poster QR Meeting
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Attendance Summary Stats -->
<div class="row g-3 mb-4">
    <?php
        $totalPresent = count(array_filter($attendances, fn($a) => $a['status'] === 'present'));
        $totalLate    = count(array_filter($attendances, fn($a) => $a['status'] === 'late'));
        $totalPermit  = count(array_filter($attendances, fn($a) => $a['status'] === 'permitted' || $a['status'] === 'sick'));
    ?>
    <div class="col-md-4">
        <div class="saas-card p-3 d-flex align-items-center gap-3">
            <div class="bg-success bg-opacity-10 text-success p-3 rounded-3 fs-3">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div>
                <div class="text-secondary small font-monospace">Hadir Tepat Waktu</div>
                <div class="fs-4 fw-bold text-white font-heading"><?= $totalPresent ?> Siswa</div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="saas-card p-3 d-flex align-items-center gap-3">
            <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-3 fs-3">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <div class="text-secondary small font-monospace">Hadir Terlambat</div>
                <div class="fs-4 fw-bold text-white font-heading"><?= $totalLate ?> Siswa</div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="saas-card p-3 d-flex align-items-center gap-3">
            <div class="bg-info bg-opacity-10 text-info p-3 rounded-3 fs-3">
                <i class="fa-solid fa-notes-medical"></i>
            </div>
            <div>
                <div class="text-secondary small font-monospace">Izin / Sakit</div>
                <div class="fs-4 fw-bold text-white font-heading"><?= $totalPermit ?> Siswa</div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Table -->
<div class="saas-card p-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="text-white font-heading m-0">
            <i class="fa-solid fa-clipboard-user text-danger me-2"></i>
            <?= $currentMeeting ? 'Data Presensi: ' . esc($currentMeeting['title']) : 'Rekap Riwayat Presensi Seluruh Anggota' ?>
        </h5>
        <span class="badge bg-dark border border-secondary text-secondary font-monospace">
            Total <?= count($attendances) ?> Data
        </span>
    </div>

    <div class="table-responsive">
        <table id="attendance-table" class="table table-dark-saas w-100 align-middle">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Anggota</th>
                    <th>Kelas / Divisi</th>
                    <th>Sesi Pertemuan</th>
                    <th>Metode Check-in</th>
                    <th>Waktu Scan</th>
                    <th>Status</th>
                    <th>Operator Scan</th>
                    <th style="width: 90px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($attendances)): ?>
                    <?php foreach ($attendances as $i => $a): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <div class="fw-semibold text-white"><?= esc($a['full_name']) ?></div>
                                <div class="text-secondary small font-monospace"><?= esc($a['nis_nip'] ?: '-') ?></div>
                            </td>
                            <td><?= esc($a['class_dept'] ?? $a['class_division'] ?? '-') ?></td>
                            <td>
                                <div class="text-white small fw-semibold"><?= esc($a['meeting_title'] ?? '-') ?></div>
                                <div class="text-secondary style-tiny font-monospace"><?= !empty($a['meeting_date']) ? date('d M Y', strtotime($a['meeting_date'])) : '-' ?></div>
                            </td>
                            <td>
                                <span class="badge bg-dark border border-secondary text-secondary font-monospace">
                                    <?= esc(strtoupper($a['method'])) ?>
                                </span>
                            </td>
                            <td class="font-monospace small"><?= date('H:i:s, d M Y', strtotime($a['scan_time'])) ?></td>
                            <td>
                                <span class="badge badge-<?= esc($a['status']) ?>">
                                    <?= strtoupper(esc($a['status'])) ?>
                                </span>
                            </td>
                            <td class="small text-secondary">
                                <?= esc($a['admin_name'] ?? 'Mandiri / System') ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-warning btn-edit-attendance" 
                                            data-id="<?= $a['id'] ?>"
                                            data-name="<?= esc($a['full_name']) ?>"
                                            data-meeting="<?= esc($a['meeting_title'] ?? '-') ?>"
                                            data-status="<?= esc($a['status']) ?>"
                                            data-notes="<?= esc($a['notes'] ?? '') ?>"
                                            title="Edit Status Presensi">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-delete-attendance" 
                                            data-id="<?= $a['id'] ?>"
                                            data-name="<?= esc($a['full_name']) ?>"
                                            title="Hapus Presensi">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Presensi Manual -->
<div class="modal fade" id="manualAttendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title font-heading"><i class="fa-solid fa-pen-to-square text-danger me-2"></i> Input Presensi Manual</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/attendance/manual') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Sesi Pertemuan</label>
                        <?php if ($currentMeeting): ?>
                            <input type="hidden" name="meeting_id" value="<?= $currentMeeting['id'] ?>">
                            <input type="text" class="form-control" value="<?= esc($currentMeeting['title']) ?>" readonly>
                        <?php else: ?>
                            <select name="meeting_id" class="form-select" required>
                                <option value="">-- Pilih Sesi Pertemuan --</option>
                                <?php foreach ($meetings as $m): ?>
                                    <option value="<?= $m['id'] ?>"><?= esc($m['title']) ?> (<?= date('d M Y', strtotime($m['meeting_date'])) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Pilih Pengguna / Anggota (Semua Role)</label>
                        <select name="user_id" class="form-select select2-searchable" required style="width: 100%;">
                            <option value="">-- Ketik Nama / NIS / Role Pengguna --</option>
                            <?php foreach ($allUsers as $u): ?>
                                <option value="<?= $u['id'] ?>">[<?= esc(strtoupper($u['role_slug'] ?? 'MEMBER')) ?>] <?= esc($u['full_name']) ?> (<?= esc($u['nis_nip'] ?: '-') ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Status Kehadiran</label>
                        <select name="status" class="form-select" required>
                            <option value="present">Present (Hadir)</option>
                            <option value="late">Late (Terlambat)</option>
                            <option value="permitted">Permitted (Izin)</option>
                            <option value="sick">Sick (Sakit)</option>
                            <option value="alpha">Alpha (Tanpa Keterangan)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Catatan / Alasan (Opsional)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Contoh: Izin kegiatan OSIS..."></textarea>
                    </div>
                </div>

                <div class="modal-footer border-top border-secondary border-opacity-25">
                    <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-red">Simpan Presensi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Presensi -->
<div class="modal fade" id="editAttendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title font-heading"><i class="fa-solid fa-pen-to-square text-warning me-2"></i> Edit Status Presensi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editAttendanceForm" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Anggota</label>
                        <input type="text" id="edit-member-name" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Sesi Pertemuan</label>
                        <input type="text" id="edit-meeting-title" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Status Kehadiran</label>
                        <select name="status" id="edit-status" class="form-select" required>
                            <option value="present">Present (Hadir)</option>
                            <option value="late">Late (Terlambat)</option>
                            <option value="permitted">Permitted (Izin)</option>
                            <option value="sick">Sick (Sakit)</option>
                            <option value="alpha">Alpha (Tanpa Keterangan)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Catatan / Alasan (Opsional)</label>
                        <textarea name="notes" id="edit-notes" class="form-control" rows="2" placeholder="Catatan perbaikan..."></textarea>
                    </div>
                </div>

                <div class="modal-footer border-top border-secondary border-opacity-25">
                    <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-semibold">Update Presensi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Filter & Export Excel / CSV -->
<div class="modal fade" id="exportFilterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title font-heading">
                    <i class="fa-solid fa-file-excel text-success me-2"></i> Filter & Opsi Export Presensi Excel / CSV
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/attendance/export') ?>" method="GET" target="_blank">
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Filter Sesi Pertemuan -->
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-medium">1. Sesi Pertemuan / Workshop</label>
                            <select name="meeting_id" class="form-select">
                                <option value="all" <?= ($selectedMeetingId === 'all') ? 'selected' : '' ?>>-- Semua Sesi Pertemuan --</option>
                                <?php foreach ($meetings as $m): ?>
                                    <option value="<?= $m['id'] ?>" <?= ($selectedMeetingId == $m['id']) ? 'selected' : '' ?>>
                                        <?= esc($m['title']) ?> (<?= date('d M Y', strtotime($m['meeting_date'])) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Filter Status Kehadiran -->
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-medium">2. Status Kehadiran</label>
                            <select name="status" class="form-select">
                                <option value="all">-- Semua Status Kehadiran --</option>
                                <option value="present">Hadir (Present)</option>
                                <option value="late">Terlambat (Late)</option>
                                <option value="permitted">Izin (Permitted)</option>
                                <option value="sick">Sakit (Sick)</option>
                                <option value="alpha">Alpha (Tanpa Keterangan)</option>
                            </select>
                        </div>

                        <!-- Filter Metode Presensi -->
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-medium">3. Metode Presensi</label>
                            <select name="method" class="form-select">
                                <option value="all">-- Semua Metode Presensi --</option>
                                <option value="meeting_qr">Scan QR Poster Sesi</option>
                                <option value="member_qr">Scan QR Member Operator</option>
                                <option value="pin">4-Digit PIN Absensi</option>
                                <option value="manual">Manual Input Admin</option>
                                <option value="system_auto">Otomatis Sistem (Auto-Alpha)</option>
                            </select>
                        </div>

                        <!-- Format Delimiter Excel -->
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-medium">4. Pemisah Kolom (Delimiter Excel)</label>
                            <select name="delimiter" class="form-select">
                                <option value=";" selected>Titik Koma ( ; ) - Standard Excel Indonesia (Rekomendasi)</option>
                                <option value=",">Koma ( , ) - Standard CSV International</option>
                            </select>
                        </div>

                        <!-- Rentang Tanggal Scan -->
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-medium">5. Tanggal Scan Mulai (Opsional)</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-medium">6. Tanggal Scan Selesai (Opsional)</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>

                        <!-- Pilih Kolom Mana Saja Yang Ingin Di-export -->
                        <div class="col-12 mt-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label class="form-label text-secondary small fw-bold m-0">7. Pilih Kolom Data Yang Ingin Di-export:</label>
                                <div>
                                    <button type="button" class="btn btn-link btn-sm text-info p-0 me-2" id="check-all-cols">Centang Semua</button>
                                    <button type="button" class="btn btn-link btn-sm text-secondary p-0" id="uncheck-all-cols">Hapus Semua</button>
                                </div>
                            </div>
                            <div class="saas-card p-3 bg-black bg-opacity-25 border border-secondary border-opacity-25">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input export-col-chk" type="checkbox" name="columns[]" value="no" id="col_no" checked>
                                            <label class="form-check-label small" for="col_no">No</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input export-col-chk" type="checkbox" name="columns[]" value="date" id="col_date" checked>
                                            <label class="form-check-label small" for="col_date">Tanggal Pertemuan</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input export-col-chk" type="checkbox" name="columns[]" value="meeting" id="col_meeting" checked>
                                            <label class="form-check-label small" for="col_meeting">Sesi Pertemuan</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input export-col-chk" type="checkbox" name="columns[]" value="nis_nip" id="col_nis_nip" checked>
                                            <label class="form-check-label small" for="col_nis_nip">NIS / NIP</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input export-col-chk" type="checkbox" name="columns[]" value="name" id="col_name" checked>
                                            <label class="form-check-label small" for="col_name">Nama Anggota</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input export-col-chk" type="checkbox" name="columns[]" value="class" id="col_class" checked>
                                            <label class="form-check-label small" for="col_class">Kelas / Departemen</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input export-col-chk" type="checkbox" name="columns[]" value="role" id="col_role" checked>
                                            <label class="form-check-label small" for="col_role">Role</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input export-col-chk" type="checkbox" name="columns[]" value="status" id="col_status" checked>
                                            <label class="form-check-label small" for="col_status">Status Presensi</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input export-col-chk" type="checkbox" name="columns[]" value="method" id="col_method" checked>
                                            <label class="form-check-label small" for="col_method">Metode Presensi</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input export-col-chk" type="checkbox" name="columns[]" value="scan_time" id="col_scan_time" checked>
                                            <label class="form-check-label small" for="col_scan_time">Waktu Scan</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input export-col-chk" type="checkbox" name="columns[]" value="notes" id="col_notes" checked>
                                            <label class="form-check-label small" for="col_notes">Catatan / Alasan</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top border-secondary border-opacity-25">
                    <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-bold">
                        <i class="fa-solid fa-download me-2"></i> Download File Excel / CSV
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
        // Initialize Select2 Searchable Dropdown inside Manual Attendance Modal
        $('#manualAttendanceModal').on('shown.bs.modal', function () {
            $('.select2-searchable').select2({
                dropdownParent: $('#manualAttendanceModal'),
                width: '100%',
                placeholder: '-- Ketik Nama / NIS / Role Pengguna --',
                allowClear: true
            });
        });

        $('.select2-searchable').select2({
            dropdownParent: $('#manualAttendanceModal'),
            width: '100%',
            placeholder: '-- Ketik Nama / NIS / Role Pengguna --',
            allowClear: true
        });

        $('#attendance-table').DataTable({
            language: {
                search: "Cari Presensi:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ presensi",
                emptyTable: "Belum ada rekapan data presensi untuk filter ini.",
                zeroRecords: "Tidak ada data presensi yang sesuai dengan pencarian.",
                paginate: { first: "Awal", last: "Akhir", next: "▶", previous: "◀" }
            }
        });

        // Handle Edit Attendance Button Click
        $(document).on('click', '.btn-edit-attendance', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const meeting = $(this).data('meeting');
            const status = $(this).data('status');
            const notes = $(this).data('notes');

            $('#editAttendanceForm').attr('action', '<?= base_url("admin/attendance/update") ?>/' + id);
            $('#edit-member-name').val(name);
            $('#edit-meeting-title').val(meeting);
            $('#edit-status').val(status);
            $('#edit-notes').val(notes);

            const editModal = new bootstrap.Modal(document.getElementById('editAttendanceModal'));
            editModal.show();
        });

        // Handle Delete Attendance Button Click
        $(document).on('click', '.btn-delete-attendance', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');

            Swal.fire({
                title: 'Hapus Presensi?',
                text: `Apakah Anda yakin ingin menghapus data presensi untuk ${name}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#27272a',
                confirmButtonText: 'Ya, Hapus Data',
                cancelButtonText: 'Batal',
                background: '#121218',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url("admin/attendance/delete") ?>/' + id;
                }
            });
        });
        // Handle Check / Uncheck All Export Columns
        $('#check-all-cols').on('click', function() {
            $('.export-col-chk').prop('checked', true);
        });
        $('#uncheck-all-cols').on('click', function() {
            $('.export-col-chk').prop('checked', false);
        });
    });
</script>
<?= $this->endSection() ?>
