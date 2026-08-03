<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <a href="<?= base_url('admin/attendance') ?>" class="btn btn-sm btn-saas-dark mb-2">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Rekap Presensi
    </a>
    <h4 class="text-white font-heading m-0">Operator Scanner: Scan Permanent Member QR</h4>
    <p class="text-secondary small">Arahkan kamera ke QR Code Permanent milik anggota untuk verifikasi presensi</p>
</div>

<?php if (!$activeMeeting): ?>
    <div class="alert alert-warning border-0 bg-warning bg-opacity-25 text-warning p-4 rounded-3 col-lg-8">
        <h5 class="font-heading mb-1"><i class="fa-solid fa-triangle-exclamation me-2"></i> Tidak Ada Pertemuan Aktif!</h5>
        <p class="small mb-3">Silakan buka dan aktifkan salah satu jadwal pertemuan terlebih dahulu sebelum melakukan scan member.</p>
        <a href="<?= base_url('admin/meetings') ?>" class="btn btn-sm btn-warning">Buka Manajemen Pertemuan</a>
    </div>
<?php else: ?>

    <div class="row g-4 justify-content-center">
        <div class="col-lg-6">
            <div class="saas-card p-4 text-center">
                <div class="badge bg-danger mb-3 font-monospace px-3 py-1">
                    <i class="fa-solid fa-video me-1"></i> KAMERA LIVE SCANNER
                </div>

                <div class="mb-3 text-secondary small">
                    Sesi: <strong class="text-white"><?= esc($activeMeeting['title']) ?></strong>
                </div>

                <!-- Scanner Render Box -->
                <div id="interactive-scanner" class="mb-3"></div>

                <div id="scan-status-alert" class="mt-3"></div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="saas-card p-4">
                <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-circle-check text-success me-2"></i> Hasil Verifikasi Terakhir</h5>
                <div id="latest-result" class="p-4 rounded-3 bg-dark border border-secondary border-opacity-25 text-center text-secondary">
                    <i class="fa-solid fa-qrcode fs-1 d-block mb-2 text-secondary"></i>
                    Belum ada anggota yang discan. Arahkan QR Code anggota ke dalam bingkai kamera.
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

<?= $this->section('scripts') ?>
<?php if ($activeMeeting): ?>
<script>
    $(document).ready(function() {
        let isProcessing = false;

        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return;
            isProcessing = true;

            $('#scan-status-alert').html('<div class="alert alert-info border-0 bg-info bg-opacity-25 text-info py-2">Memproses data QR Member...</div>');

            $.ajax({
                url: '<?= base_url('attendance/process-scan') ?>',
                type: 'POST',
                data: {
                    csrf_test_name: '<?= csrf_hash() ?>',
                    scan_type: 'member_qr',
                    qr_code: decodedText
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Presensi Sukses!',
                            text: res.member_name + ' (' + res.nis_nip + ') - ' + res.att_status,
                            timer: 2000,
                            showConfirmButton: false,
                            background: '#121218',
                            color: '#fff'
                        });

                        $('#latest-result').html(`
                            <div class="text-start">
                                <span class="badge bg-success mb-2">VERIFIKASI BERHASIL</span>
                                <h4 class="text-white font-heading mb-1">${res.member_name}</h4>
                                <div class="text-secondary small font-monospace mb-2">NIS/NIP: ${res.nis_nip} | ${res.class_dept}</div>
                                <div class="p-2 rounded bg-dark border border-success text-success small font-monospace">
                                    Status: <strong>${res.att_status}</strong> | Scan: ${res.scan_time}
                                </div>
                            </div>
                        `);
                    } else {
                        Swal.fire({
                            icon: res.status === 'warning' ? 'warning' : 'error',
                            title: 'Peringatan',
                            text: res.message,
                            background: '#121218',
                            color: '#fff'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error System',
                        text: 'Gagal terhubung ke server.',
                        background: '#121218',
                        color: '#fff'
                    });
                },
                complete: function() {
                    setTimeout(function() {
                        isProcessing = false;
                        $('#scan-status-alert').empty();
                    }, 2000);
                }
            });
        }

        const html5QrcodeScanner = new Html5QrcodeScanner("interactive-scanner", { fps: 10, qrbox: { width: 250, height: 250 } }, false);
        html5QrcodeScanner.render(onScanSuccess);
    });
</script>
<?php endif; ?>
<?= $this->endSection() ?>
