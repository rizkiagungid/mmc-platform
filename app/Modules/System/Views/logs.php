<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Audit Logs System</h4>
        <p class="text-secondary small m-0">Rekam jejak aktivitas pengguna, perubahan data, dan keamanan platform</p>
    </div>

    <span class="badge bg-dark border border-secondary text-secondary font-monospace">
        <i class="fa-solid fa-shield-halved text-danger me-1"></i> Security Audit Active
    </span>
</div>

<div class="saas-card p-4">
    <div class="table-responsive">
        <table id="audit-logs-table" class="table table-dark table-dark-saas w-100 align-middle">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Waktu (Timestamp)</th>
                    <th>Pengguna / Aktor</th>
                    <th>Tindakan (Action)</th>
                    <th>Rincian Keterangan</th>
                    <th>Alamat IP</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $i => $l): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td class="font-monospace small text-secondary">
                            <?= date('H:i:s, d M Y', strtotime($l['created_at'])) ?>
                        </td>
                        <td>
                            <div class="fw-semibold text-white"><?= esc($l['full_name'] ?: 'System / Tamu') ?></div>
                            <div class="text-secondary style-tiny font-monospace"><?= esc($l['username'] ?: '-') ?></div>
                        </td>
                        <td>
                            <span class="badge bg-danger bg-opacity-25 border border-danger text-danger font-monospace">
                                <?= esc($l['action']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="text-white small"><?= esc($l['description']) ?></div>
                        </td>
                        <td class="font-monospace style-tiny text-info">
                            <?= esc($l['ip_address'] ?: '127.0.0.1') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#audit-logs-table').DataTable({
            order: [[1, 'desc']],
            language: {
                search: "Cari Log:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ log",
                paginate: { first: "Awal", last: "Akhir", next: "▶", previous: "◀" }
            }
        });
    });
</script>
<?= $this->endSection() ?>
