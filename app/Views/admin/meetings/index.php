<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Manajemen Pertemuan & Workshop</h4>
        <p class="text-secondary small m-0">Atur jadwal latihan rutin, materi pembelajaran, mentor, dan QR presensi</p>
    </div>
    <a href="<?= base_url('admin/meetings/create') ?>" class="btn btn-red">
        <i class="fa-solid fa-plus me-1"></i> Buat Pertemuan Baru
    </a>
</div>

<div class="saas-card p-4">
    <div class="table-responsive">
        <table class="table table-dark-saas datatable-saas align-middle">
            <thead>
                <tr>
                    <th>Pertemuan / Workshop</th>
                    <th>Tanggal & Waktu</th>
                    <th>Mentor & Lokasi</th>
                    <th>PIN Presensi</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($meetings as $m): ?>
                    <tr>
                        <td>
                            <div class="fw-bold text-white font-heading"><?= esc($m['title']) ?></div>
                            <small class="text-secondary d-block text-truncate" style="max-width: 280px;"><?= esc($m['description']) ?></small>
                            <?php if (!empty($m['learning_material'])): ?>
                                <a href="<?= esc($m['learning_material']) ?>" target="_blank" class="small text-danger"><i class="fa-solid fa-link me-1"></i> Materi Pembelajaran</a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="text-white font-monospace small"><i class="fa-solid fa-calendar me-1 text-danger"></i> <?= date('d M Y', strtotime($m['meeting_date'])) ?></div>
                            <small class="text-secondary font-monospace"><i class="fa-solid fa-clock me-1"></i> <?= esc($m['start_time']) ?> - <?= esc($m['end_time']) ?></small>
                        </td>
                        <td class="small text-secondary">
                            <div><i class="fa-solid fa-user-graduate me-1 text-info"></i> <?= esc($m['mentor'] ?: 'Mentor Internal') ?></div>
                            <div><i class="fa-solid fa-location-dot me-1 text-danger"></i> <?= esc($m['location'] ?: 'Multimedia Lab') ?></div>
                        </td>
                        <td class="font-monospace text-center">
                            <span class="badge bg-dark border border-warning text-warning fs-6 px-2.5 py-1"><?= esc($m['pin_code'] ?: '----') ?></span>
                        </td>
                        <td>
                            <?php if ($m['status'] === 'active'): ?>
                                <span class="badge bg-danger">AKTIF SEKARANG</span>
                            <?php elseif ($m['status'] === 'completed'): ?>
                                <span class="badge bg-success bg-opacity-25 text-success border border-success">SELESAI</span>
                            <?php elseif ($m['status'] === 'draft'): ?>
                                <span class="badge bg-secondary">DRAFT</span>
                            <?php else: ?>
                                <span class="badge bg-dark text-secondary">DIBATALKAN</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="d-flex align-items-center justify-content-end gap-1">
                                <?php if ($m['status'] !== 'active'): ?>
                                    <a href="<?= base_url('admin/meetings/activate/' . $m['id']) ?>" class="btn btn-sm btn-outline-danger" title="Aktifkan Presensi">
                                        <i class="fa-solid fa-power-off"></i> Aktifkan
                                    </a>
                                <?php else: ?>
                                    <a href="<?= base_url('admin/meetings/qr/' . $m['id']) ?>" class="btn btn-sm btn-red" title="Tampilkan QR Poster">
                                        <i class="fa-solid fa-qrcode me-1"></i> QR Poster
                                    </a>
                                <?php endif; ?>

                                <a href="<?= base_url('admin/meetings/edit/' . $m['id']) ?>" class="btn btn-sm btn-saas-dark"><i class="fa-solid fa-pen"></i></a>
                                <a href="<?= base_url('admin/meetings/delete/' . $m['id']) ?>" onclick="return confirm('Hapus pertemuan ini?')" class="btn btn-sm btn-saas-dark text-danger"><i class="fa-solid fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
