<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Rekapitulasi Presensi & Absensi</h4>
        <p class="text-secondary small m-0">Kelola dan pantau presensi anggota secara real-time</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="<?= base_url('admin/attendance/scan-member') ?>" class="btn btn-red">
            <i class="fa-solid fa-camera me-1"></i> Operator Scan Member QR
        </a>
        <button class="btn btn-saas-dark" data-bs-toggle="modal" data-bs-target="#manualAttModal">
            <i class="fa-solid fa-user-check me-1 text-success"></i> Input Presensi Manual
        </button>
    </div>
</div>

<!-- Meeting Filter Dropdown -->
<div class="saas-card p-3 mb-4">
    <form action="<?= base_url('admin/attendance') ?>" method="GET" class="row g-2 align-items-center">
        <div class="col-md-8">
            <select name="meeting_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Pilih Pertemuan --</option>
                <?php foreach ($meetings as $m): ?>
                    <option value="<?= $m['id'] ?>" <?= ($currentMeeting && $currentMeeting['id'] == $m['id']) ? 'selected' : '' ?>>
                        [<?= date('d/m/Y', strtotime($m['meeting_date'])) ?>] <?= esc($m['title']) ?> (<?= strtoupper($m['status']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4 text-secondary small">
            <?php if ($currentMeeting): ?>
                <span>Sesi Sesuai: <strong class="text-white"><?= esc($currentMeeting['title']) ?></strong></span>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Attendance Table -->
<div class="saas-card p-4">
    <div class="table-responsive">
        <table class="table table-dark-saas datatable-saas align-middle">
            <thead>
                <tr>
                    <th>Anggota</th>
                    <th>NIS / NIP</th>
                    <th>Kelas / Divisi</th>
                    <th>Waktu Presensi</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Catatan / Admin</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendances as $a): ?>
                    <tr>
                        <td>
                            <div class="fw-semibold text-white"><?= esc($a['full_name']) ?></div>
                            <small class="text-secondary">@<?= esc($a['username']) ?></small>
                        </td>
                        <td class="font-monospace small text-secondary"><?= esc($a['nis_nip'] ?: '-') ?></td>
                        <td class="small text-secondary"><?= esc($a['class_dept'] ?: '-') ?></td>
                        <td class="font-monospace small text-white">
                            <i class="fa-solid fa-clock me-1 text-danger"></i> <?= date('H:i:s d/m/Y', strtotime($a['scan_time'])) ?>
                        </td>
                        <td>
                            <span class="badge bg-dark border border-secondary text-secondary">
                                <?= esc(str_replace('_', ' ', strtoupper($a['method']))) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-<?= esc($a['status']) ?>">
                                <?= strtoupper(esc($a['status'])) ?>
                            </span>
                        </td>
                        <td class="small text-secondary">
                            <div><?= esc($a['notes'] ?: '-') ?></div>
                            <?php if ($a['admin_name']): ?>
                                <small class="text-info font-monospace"><i class="fa-solid fa-user-shield me-1"></i> <?= esc($a['admin_name']) ?></small>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Manual Check-In Modal -->
<div class="modal fade" id="manualAttModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border border-secondary text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title font-heading"><i class="fa-solid fa-user-check text-danger me-2"></i> Input Presensi Manual</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/attendance/manual-store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Pilih Sesi Pertemuan</label>
                        <select name="meeting_id" class="form-select" required>
                            <?php foreach ($meetings as $m): ?>
                                <option value="<?= $m['id'] ?>" <?= ($currentMeeting && $currentMeeting['id'] == $m['id']) ? 'selected' : '' ?>>
                                    <?= esc($m['title']) ?> (<?= date('d/m/Y', strtotime($m['meeting_date'])) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Pilih Anggota</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Pilih Anggota --</option>
                            <?php foreach ($allUsers as $u): ?>
                                <option value="<?= $u['id'] ?>"><?= esc($u['full_name']) ?> (<?= esc($u['nis_nip'] ?: $u['username']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Status Absensi</label>
                        <select name="status" class="form-select" required>
                            <option value="present">HADIR (PRESENT)</option>
                            <option value="late">TERLAMBAT (LATE)</option>
                            <option value="sick">SAKIT (SICK)</option>
                            <option value="permitted">IZIN (PERMITTED)</option>
                            <option value="alpha">ALPHA</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Keterangan / Catatan</label>
                        <input type="text" name="notes" class="form-control" placeholder="Contoh: Izin terlambat 15 menit">
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-red px-4">Simpan Presensi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
