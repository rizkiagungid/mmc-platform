<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <h4 class="text-white font-heading m-0">Riwayat Presensi Saya</h4>
    <p class="text-secondary small">Daftar kehadiran Anda pada seluruh sesi pertemuan Multimedia Club</p>
</div>

<div class="saas-card p-4">
    <div class="table-responsive">
        <table class="table table-dark-saas datatable-saas align-middle">
            <thead>
                <tr>
                    <th>Pertemuan / Workshop</th>
                    <th>Tanggal Pertemuan</th>
                    <th>Waktu Presensi</th>
                    <th>Metode</th>
                    <th>Status Kehadiran</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendances as $att): ?>
                    <tr>
                        <td>
                            <div class="fw-semibold text-white font-heading"><?= esc($att['meeting_title']) ?></div>
                            <small class="text-secondary"><i class="fa-solid fa-location-dot me-1 text-danger"></i> <?= esc($att['location']) ?></small>
                        </td>
                        <td class="font-monospace small text-secondary">
                            <?= date('d M Y', strtotime($att['meeting_date'])) ?> (<?= esc($att['start_time']) ?> WIB)
                        </td>
                        <td class="font-monospace small text-white">
                            <i class="fa-solid fa-clock me-1 text-danger"></i> <?= date('H:i:s, d/m/Y', strtotime($att['scan_time'])) ?>
                        </td>
                        <td>
                            <span class="badge bg-dark border border-secondary text-secondary">
                                <?= esc(str_replace('_', ' ', strtoupper($att['method']))) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-<?= esc($att['status']) ?>">
                                <?= strtoupper(esc($att['status'])) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
