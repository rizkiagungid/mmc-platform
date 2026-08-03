<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <h4 class="text-white font-heading m-0">Riwayat Presensi Saya</h4>
    <p class="text-secondary small m-0">Daftar kehadiran Anda pada setiap kegiatan dan sesi pertemuan Multimedia Club</p>
</div>

<div class="saas-card p-4">
    <?php if (empty($attendances)): ?>
        <div class="text-center py-5 text-secondary">
            <i class="fa-solid fa-calendar-xmark display-1 mb-3 text-secondary opacity-50"></i>
            <h5 class="text-white font-heading">Belum Ada Catatan Presensi</h5>
            <p class="small mb-0">Lakukan presensi saat sesi pertemuan dibuka menggunakan scanner QR atau 4-digit PIN.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-dark-saas w-100 align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Sesi Pertemuan</th>
                        <th>Tanggal Meeting</th>
                        <th>Metode Presensi</th>
                        <th>Waktu Scan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attendances as $i => $a): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <div class="fw-semibold text-white"><?= esc($a['meeting_title']) ?></div>
                                <div class="text-secondary small"><i class="fa-solid fa-location-dot me-1 text-danger"></i> <?= esc($a['location'] ?: 'Laboratorium MMC') ?></div>
                            </td>
                            <td class="font-monospace small"><?= date('d M Y', strtotime($a['meeting_date'])) ?></td>
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
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
