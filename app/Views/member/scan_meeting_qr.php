<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <h4 class="text-white font-heading m-0">Presensi Mandiri: Meeting QR & PIN</h4>
    <p class="text-secondary small">Arahkan kamera ke QR Code Poster di depan ruangan atau masukkan 4-digit PIN Pertemuan</p>
</div>

<?php if (!$activeMeeting): ?>
    <div class="saas-card p-5 text-center col-lg-8 mx-auto">
        <i class="fa-solid fa-calendar-xmark text-secondary display-1 mb-3"></i>
        <h4 class="text-white font-heading mb-2">Belum Ada Sesi Pertemuan Aktif</h4>
        <p class="text-secondary small mb-0">Presensi hanya dapat dilakukan saat Pembina / BPH telah membuka sesi pertemuan.</p>
    </div>
<?php else: ?>

    <div class="row g-4 justify-content-center">
        <!-- Live Camera QR Scanner -->
        <div class="col-lg-6">
            <div class="saas-card p-4 text-center h-100">
                <div class="badge bg-danger mb-3 font-monospace px-3 py-1">
                    <i class="fa-solid fa-camera me-1"></i> METHOD 1: SCAN MEETING QR
                </div>

                <div class="text-secondary small mb-3">
                    Sesi: <strong class="text-white"><?= esc($activeMeeting['title']) ?></strong>
                </div>

                <div id="interactive-scanner" class="mb-3"></div>
                <div id="scan-status-alert"></div>
            </div>
        </div>

        <!-- 4-Digit PIN Method -->
        <div class="col-lg-5">
            <div class="saas-card p-4 h-100">
                <div class="badge bg-warning text-dark mb-3 font-monospace px-3 py-1">
                    <i class="fa-solid fa-key me-1"></i> METHOD 2: INPUT 4-DIGIT PIN
                </div>

                <h5 class="text-white font-heading mb-2">Masukkan PIN Pertemuan</h5>
                <p class="text-secondary small mb-4">Jika kamera tidak tersedia, minta 4-digit PIN yang ditampilkan pada layar instruktur.</p>

                <form action="<?= base_url('attendance/process-pin') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-4 text-center">
                        <input type="text" name="pin_code" class="form-control form-control-lg font-monospace text-center fs-2 tracking-widest text-warning" placeholder="0000" maxlength="4" required autocomplete="off">
                    </div>

                    <button type="submit" class="btn btn-red w-100 py-3 fw-semibold">
                        <i class="fa-solid fa-check-circle me-2"></i> Verifikasi Presensi PIN
                    </button>
                </form>
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

            $('#scan-status-alert').html('<div class="alert alert-info border-0 bg-info bg-opacity-25 text-info py-2">Memproses QR Pertemuan...</div>');

            $.ajax({
                url: '<?= base_url('attendance/process-scan') ?>',
                type: 'POST',
                data: {
                    csrf_test_name: '<?= csrf_hash() ?>',
                    scan_type: 'meeting_qr',
                    qr_code: decodedText
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Presensi Berhasil!',
                            text: res.message + ' (' + res.meeting_title + ')',
                            background: '#121218',
                            color: '#fff'
                        }).then(() => {
                            window.location.href = '<?= base_url('attendance/history') ?>';
                        });
                    } else {
                        Swal.fire({
                            icon: res.status === 'warning' ? 'warning' : 'error',
                            title: 'Status Presensi',
                            text: res.message,
                            background: '#121218',
                            color: '#fff'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memproses ke server.',
                        background: '#121218',
                        color: '#fff'
                    });
                },
                complete: function() {
                    setTimeout(function() { isProcessing = false; }, 2000);
                }
            });
        }

        const html5QrcodeScanner = new Html5QrcodeScanner("interactive-scanner", { fps: 10, qrbox: { width: 250, height: 250 } }, false);
        html5QrcodeScanner.render(onScanSuccess);
    });
</script>
<?php endif; ?>
<?= $this->endSection() ?>
