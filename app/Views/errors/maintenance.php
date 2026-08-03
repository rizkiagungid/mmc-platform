<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situs Dalam Pemeliharaan — <?= esc($siteTitle ?? 'Multimedia Club SMAN 1 Tamansari') ?></title>
    <meta name="theme-color" content="#0d1117">
    <link rel="shortcut icon" href="<?= base_url('assets/icons/favicon.png') ?>" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #0d1117;
            color: #ffffff;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 1.5rem;
        }
        .maintenance-card {
            background-color: #161b22;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 1rem;
            padding: 2.5rem;
            max-width: 520px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
        }
        .btn-red {
            background-color: #dc2626;
            color: #ffffff;
            border: none;
            font-weight: 600;
        }
        .btn-red:hover {
            background-color: #b91c1c;
            color: #ffffff;
        }
    </style>
</head>
<body>

    <div class="maintenance-card">
        <img src="<?= (strpos($logo, 'http') === 0) ? esc($logo) : base_url($logo) ?>" alt="Logo" style="height: 60px;" class="mb-4 bg-white p-1 rounded-3">
        
        <div class="mb-4">
            <i class="fa-solid fa-screwdriver-wrench text-warning" style="font-size: 3.5rem;"></i>
        </div>

        <h3 class="fw-bold mb-2 font-heading text-white">Situs Dalam Pemeliharaan</h3>
        <p class="text-secondary small mb-4 leading-relaxed"><?= nl2br(esc($message)) ?></p>

        <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 mb-4 text-start style-tiny">
            <div class="fw-bold text-white mb-1"><i class="fa-solid fa-circle-info text-info me-1"></i> Informasi Pengurus:</div>
            <div class="text-secondary">Administrator atau pengurus club tetap dapat masuk ke dashboard melalui portal login.</div>
        </div>

        <div class="d-grid gap-2">
            <a href="<?= base_url('login') ?>" class="btn btn-red py-2">
                <i class="fa-solid fa-right-to-bracket me-1"></i> Login Pengurus / Admin
            </a>
            <button onclick="window.location.reload()" class="btn btn-outline-light py-2">
                <i class="fa-solid fa-rotate-right me-1"></i> Coba Cek Lagi
            </button>
        </div>
    </div>

</body>
</html>
