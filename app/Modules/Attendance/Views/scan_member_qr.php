<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Operator Scanner: Member Permanent QR</h4>
        <p class="text-secondary small m-0">Scan QR Code pada ID Card Digital Anggota untuk presensi otomatis</p>
    </div>

    <a href="<?= base_url('admin/attendance') ?>" class="btn btn-saas-dark">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Rekap Presensi
    </a>
</div>

<?php if (!$activeMeeting): ?>
    <div class="saas-card p-5 text-center col-lg-8 mx-auto">
        <i class="fa-solid fa-calendar-xmark text-secondary display-1 mb-3"></i>
        <h4 class="text-white font-heading mb-2">Tidak Ada Sesi Pertemuan Aktif</h4>
        <p class="text-secondary small mb-0">Buka / Aktifkan sesi pertemuan terlebih dahulu di CMS Pertemuan sebelum menggunakan scanner operator.</p>
    </div>
<?php else: ?>

    <div class="row g-4 justify-content-center">
        <div class="col-lg-6">
            <div class="saas-card p-4 text-center">
                <div class="badge bg-danger mb-3 font-monospace px-3 py-1">
                    <i class="fa-solid fa-qrcode me-1"></i> OPERATOR CAMERA SCANNER
                </div>

                <div class="text-secondary small mb-3">
                    Sesi Aktif: <strong class="text-white"><?= esc($activeMeeting['title']) ?></strong>
                </div>

                <div id="operator-scanner" class="mb-3 rounded-3 overflow-hidden border border-secondary border-opacity-25 bg-black" style="min-height: 300px;"></div>

                <div class="text-secondary small font-monospace">
                    <i class="fa-solid fa-circle-info text-info me-1"></i> Arahkan QR ID Card Anggota ke kotak kamera di atas.
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="saas-card p-4">
                <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-list-check text-danger me-2"></i> Log Scan Operator Terakhir</h5>

                <div id="recent-scan-log" class="d-flex flex-column gap-2" style="max-height: 380px; overflow-y: auto;">
                    <div class="text-secondary small text-center py-4">Belum ada scan pada sesi ini.</div>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if ($activeMeeting): ?>
<script>
    $(document).ready(function() {
        let isProcessing = false;

        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return;
            isProcessing = true;

            $.ajax({
                url: '<?= base_url("attendance/process-scan") ?>',
                type: 'POST',
                data: {
                    scan_type: 'member_qr',
                    qr_code: decodedText,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                success: function(res) {
                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Presensi Dicatat!',
                            text: res.message,
                            timer: 2000,
                            showConfirmButton: false,
                            background: '#121218',
                            color: '#fff'
                        });

                        const logHtml = `
                            <div class="p-3 rounded-3 bg-dark border border-success border-opacity-50">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <strong class="text-white">${res.data ? res.data.user : 'Anggota'}</strong>
                                    <span class="badge bg-success font-monospace">HADIR</span>
                                </div>
                                <div class="text-secondary small font-monospace">${res.data ? res.data.time : ''}</div>
                            </div>
                        `;
                        $('#recent-scan-log').prepend(logHtml);
                    } else {
                        Swal.fire({
                            icon: res.status === 'warning' ? 'warning' : 'error',
                            title: res.status === 'warning' ? 'Sudah Presensi' : 'Gagal',
                            text: res.message,
                            background: '#121218',
                            color: '#fff'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Server',
                        text: 'Gagal memproses scanner.',
                        background: '#121218',
                        color: '#fff'
                    });
                },
                complete: function() {
                    setTimeout(function() { isProcessing = false; }, 2000);
                }
            });
        }

        if (typeof Html5QrcodeScanner !== 'undefined') {
            const scanner = new Html5QrcodeScanner("operator-scanner", { fps: 10, qrbox: { width: 250, height: 250 } }, false);
            scanner.render(onScanSuccess);
        }
    });
</script>
<?php endif; ?>
<?= $this->endSection() ?>
