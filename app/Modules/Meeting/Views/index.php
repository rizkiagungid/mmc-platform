<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Manajemen Pertemuan & Workshop</h4>
        <p class="text-secondary small m-0">Kelola jadwal workshop, QR presensi digital, dan 4-digit PIN absensi</p>
    </div>
    <a href="<?= base_url('admin/meetings/create') ?>" class="btn btn-red">
        <i class="fa-solid fa-calendar-plus me-1"></i> Buat Pertemuan Baru
    </a>
</div>

<div class="saas-card p-4">
    <div class="table-responsive">
        <table class="table table-dark-saas datatable-saas align-middle">
            <thead>
                <tr>
                    <th>Judul Sesi</th>
                    <th>Mentor</th>
                    <th>Tanggal & Waktu</th>
                    <th>Lokasi</th>
                    <th>PIN Absensi</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($meetings as $m): ?>
                    <tr>
                        <td>
                            <div class="fw-semibold text-white"><?= esc($m['title']) ?></div>
                            <?php if ($m['learning_material']): ?>
                                <small><a href="<?= esc($m['learning_material']) ?>" target="_blank" class="text-danger"><i class="fa-solid fa-link me-1"></i> Materi Pembelajaran</a></small>
                            <?php endif; ?>
                        </td>
                        <td class="small text-secondary"><?= esc($m['mentor'] ?: '-') ?></td>
                        <td>
                            <div class="small text-white fw-medium"><?= date('d/m/Y', strtotime($m['meeting_date'])) ?></div>
                            <small class="text-secondary font-monospace"><?= date('H:i', strtotime($m['start_time'])) ?> - <?= date('H:i', strtotime($m['end_time'])) ?> WIB</small>
                        </td>
                        <td class="small text-secondary"><?= esc($m['location'] ?: '-') ?></td>
                        <td class="font-monospace fw-bold text-warning text-center">
                            <?= esc($m['pin_code'] ?: '-') ?>
                        </td>
                        <td>
                            <?php if ($m['status'] === 'active'): ?>
                                <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 animate-pulse">SESI AKTIF</span>
                            <?php elseif ($m['status'] === 'completed'): ?>
                                <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25">SELESAI</span>
                            <?php else: ?>
                                <span class="badge bg-secondary bg-opacity-25 text-secondary border border-secondary">DRAFT</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-saas-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Pilihan
                                </button>
                                <ul class="dropdown-menu dropdown-menu-dark">
                                    <?php if ($m['status'] !== 'active'): ?>
                                        <li><a class="dropdown-item text-success" href="<?= base_url('admin/meetings/activate/' . $m['id']) ?>" onclick="return confirm('Aktifkan sesi ini? Sesi aktif sebelumnya akan diselesaikan.')"><i class="fa-solid fa-play me-2"></i> Aktifkan Sesi Ini</a></li>
                                    <?php endif; ?>
                                    <li><a class="dropdown-item" href="<?= base_url('admin/meetings/qr/' . $m['id']) ?>"><i class="fa-solid fa-qrcode me-2 text-warning"></i> Tampilkan Poster QR</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?= base_url('admin/meetings/edit/' . $m['id']) ?>"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Edit Data</a></li>
                                    <li><a class="dropdown-item text-danger" href="<?= base_url('admin/meetings/delete/' . $m['id']) ?>" onclick="return confirm('Hapus sesi pertemuan ini?')"><i class="fa-solid fa-trash me-2"></i> Hapus</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
