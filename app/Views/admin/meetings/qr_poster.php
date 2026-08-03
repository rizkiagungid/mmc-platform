<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting QR Poster - <?= esc($meeting['title']) ?></title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body {
            background-color: #09090b;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .poster-card {
            background: #121218;
            border: 2px solid rgba(220, 38, 38, 0.4);
            border-radius: 1.5rem;
            box-shadow: 0 0 50px rgba(220, 38, 38, 0.3);
            max-width: 600px;
            width: 100%;
        }
    </style>
</head>
<body class="p-3">

    <div class="poster-card p-4 p-md-5 text-center position-relative">
        
        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom border-secondary border-opacity-25">
            <div class="d-flex align-items-center gap-3">
            <img src="<?= base_url('assets/logo-mm-2023.png') ?>" alt="MMC Logo" style="height: 48px;" class="rounded-3 p-1.5 bg-white shadow">
            <div class="text-start">
                <div class="fw-bold text-white font-heading lh-1 fs-5">MULTIMEDIA CLUB</div>
                <small class="text-secondary font-monospace" style="font-size: 0.75rem;">SMAN 1 TAMANSARI</small>
            </div>
        </div>
            <span class="badge bg-danger px-3 py-2 font-monospace fs-6">SESI AKTIF</span>
        </div>

        <h2 class="font-heading fw-bold text-white mb-2"><?= esc($meeting['title']) ?></h2>
        <p class="text-secondary small mb-4">
            <i class="fa-solid fa-location-dot text-danger me-1"></i> <?= esc($meeting['location']) ?> | 
            <i class="fa-solid fa-clock text-danger me-1"></i> <?= esc($meeting['start_time']) ?> - <?= esc($meeting['end_time']) ?> WIB
        </p>

        <!-- Meeting QR Container -->
        <div class="my-4">
            <div id="meeting-qr-canvas" class="bg-white p-4 rounded-4 d-inline-block shadow-lg border border-danger border-4"></div>
        </div>

        <div class="bg-dark p-3 rounded-3 border border-secondary border-opacity-25 mb-4">
            <span class="text-secondary small d-block mb-1">ATAU MASUKKAN 4-DIGIT KODE PIN:</span>
            <div class="display-4 font-monospace fw-bold text-warning tracking-widest"><?= esc($meeting['pin_code']) ?></div>
        </div>

        <div class="d-flex justify-content-center gap-2">
            <button onclick="window.print()" class="btn btn-danger px-4">
                <i class="fa-solid fa-print me-1"></i> Cetak Poster
            </button>
            <a href="<?= base_url('admin/meetings') ?>" class="btn btn-outline-light px-4">Kembali</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        $(document).ready(function() {
            const token = "<?= esc($meeting['qr_token']) ?>";
            new QRCode(document.getElementById("meeting-qr-canvas"), {
                text: token,
                width: 240,
                height: 240,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        });
    </script>
</body>
</html>
