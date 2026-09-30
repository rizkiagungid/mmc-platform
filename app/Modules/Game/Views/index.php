<?= $this->extend('layouts/master_public') ?>

<?= $this->section('content') ?>
<style>
/* Game Card Icon Boxes with High-Contrast Gradients & Shadows */
.game-icon-avatar {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.65rem;
    color: #ffffff !important;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    flex-shrink: 0;
}
.game-card-hover:hover .game-icon-avatar {
    transform: scale(1.08) rotate(-3deg);
}

.game-icon-exposure-triangle {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
.game-icon-ide-simulator {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
.game-icon-tapnich {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
.game-icon-quiz {
    background: linear-gradient(135deg, #a855f7 0%, #7e22ce 100%);
    box-shadow: 0 8px 20px rgba(168, 85, 247, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
.game-icon-pelari-kalcer {
    background: linear-gradient(135deg, #10b981 0%, #047857 100%);
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.45);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
.game-icon-menggambar {
    background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);
    box-shadow: 0 8px 20px rgba(236, 72, 153, 0.45);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
.game-icon-aku-hacker {
    background: linear-gradient(135deg, #059669 0%, #064e3b 100%);
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.5);
    border: 1px solid #10b981;
}
.btn-pink {
    background: linear-gradient(135deg, #ec4899 0%, #db2777 100%) !important;
    border: 1px solid #f472b6 !important;
    color: #ffffff !important;
    box-shadow: 0 4px 14px rgba(236, 72, 153, 0.45);
}
.btn-pink:hover {
    background: linear-gradient(135deg, #f472b6 0%, #ec4899 100%) !important;
    border: 1px solid #fbcfe8 !important;
    color: #ffffff !important;
    box-shadow: 0 6px 18px rgba(236, 72, 153, 0.65);
    transform: translateY(-2px);
}
.btn-terminal {
    background: #00ff66 !important;
    border: 1px solid #00ff66 !important;
    color: #050d08 !important;
    box-shadow: 0 0 15px rgba(0, 255, 102, 0.4);
    font-weight: 700;
}
.btn-terminal:hover {
    background: #33ff88 !important;
    border: 1px solid #66ffaa !important;
    color: #000000 !important;
    box-shadow: 0 0 25px rgba(0, 255, 102, 0.7);
    transform: translateY(-2px);
}
.game-icon-default {
    background: linear-gradient(135deg, #64748b 0%, #475569 100%);
    box-shadow: 0 8px 20px rgba(100, 116, 139, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.15);
}
</style>

<section class="py-5">
    <div class="container py-4">
        <!-- Hero Header -->
        <div class="text-center mb-5">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-danger bg-opacity-10 border border-danger border-opacity-25 text-danger font-monospace small mb-3">
                <i class="fa-solid fa-gamepad"></i> INTERACTIVE LAB & MINI GAMES
            </div>
            <h1 class="display-5 fw-bold text-body font-heading mt-1">Multimedia Interactive Mini Games</h1>
            <p class="text-secondary col-lg-8 mx-auto lead fs-6">
                Eksplorasi simulator dan game interaktif untuk mengasah kemampuan teknis fotografi, videografi, pencahayaan, dan programming secara virtual tanpa risiko!
            </p>
        </div>

               <!-- Catalog of All Mini Games -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="text-body font-heading fw-bold mb-1"><i class="fa-solid fa-shapes text-danger me-2"></i> Daftar Koleksi Game & Simulator</h3>
                <p class="text-secondary small mb-0">Pilih simulator interaktif yang ingin kamu mainkan dan pelajari.</p>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <?php foreach ($games as $game): 
                $iconClass = 'game-icon-' . esc($game['id']);
            ?>
                <div class="col-md-6 col-lg-6">
                    <div class="saas-card saas-card-glow game-card-hover h-100 d-flex flex-column justify-content-between border border-secondary border-opacity-25 p-4 position-relative">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="game-icon-avatar <?= $iconClass ?>">
                                    <i class="fa-solid <?= esc($game['icon']) ?>"></i>
                                </div>
                                <div>
                                    <span class="badge bg-<?= esc($game['badge_color']) ?> font-monospace"><?= esc($game['badge']) ?></span>
                                </div>
                            </div>

                            <span class="text-danger style-tiny font-monospace text-uppercase fw-bold"><?= esc($game['category']) ?></span>
                            <h4 class="text-body font-heading fw-bold mt-1 mb-1"><?= esc($game['title']) ?></h4>
                            <div class="text-secondary small mb-3 fst-italic"><?= esc($game['subtitle']) ?></div>
                            <p class="text-secondary small mb-3"><?= esc($game['description']) ?></p>

                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <?php foreach ($game['features'] as $f): ?>
                                    <span class="badge bg-body-secondary text-secondary border border-secondary border-opacity-25 style-tiny font-monospace">
                                        <i class="fa-solid fa-check text-success me-1"></i><?= esc($f) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="pt-3 border-top border-secondary border-opacity-10 d-flex align-items-center justify-content-between">
                            <div class="small text-secondary">
                                <i class="fa-solid fa-signal text-warning me-1"></i> Level: <strong class="text-body"><?= esc($game['difficulty']) ?></strong>
                            </div>
                            <?php if ($game['status'] === 'active'): 
                                $btnClass = 'btn-red';
                                if ($game['id'] === 'ide-simulator') $btnClass = 'btn-primary';
                                if ($game['id'] === 'tapnich') $btnClass = 'btn-warning text-dark';
                                if ($game['id'] === 'quiz') $btnClass = 'btn-info text-dark font-heading fw-bold';
                                if ($game['id'] === 'pelari-kalcer') $btnClass = 'btn-success text-white font-heading fw-bold';
                                if ($game['id'] === 'menggambar') $btnClass = 'btn-pink text-white font-heading fw-bold';
                                if ($game['id'] === 'aku-hacker') $btnClass = 'btn-terminal';
                            ?>
                                <a href="<?= esc($game['play_url']) ?>" class="btn <?= $btnClass ?> px-4 font-heading fw-bold d-inline-flex align-items-center gap-2">
                                    <i class="fa-solid fa-play"></i> Mainkan
                                </a>
                            <?php else: ?>
                                <button class="btn btn-outline-secondary px-3 btn-sm" disabled>
                                    <i class="fa-solid fa-lock me-1"></i> Segera Hadir
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Educational Benefit Callout -->
        <div class="p-4 p-lg-5 rounded-4 border border-secondary border-opacity-25 bg-saas-alt">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <h4 class="text-body font-heading fw-bold mb-2"><i class="fa-solid fa-lightbulb text-warning me-2"></i> Mengapa Belajar Melalui Simulator Interaktif?</h4>
                    <p class="text-secondary mb-0">
                        Kamera DSLR/Mirrorless memiliki harga yang mahal dan risiko kesalahan setting saat liputan acara sekolah. Melalui mini game simulator ini, calon fotografer, videografer, dan programmer Multimedia Club dapat memahami hubungan ISO, Shutter, Aperture, dan logika kode secara langsung, menyenangkan, dan tanpa batas percobaan!
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="<?= base_url('mini-game/exposure-triangle') ?>" class="btn btn-outline-red px-4 py-2 font-heading">
                        <i class="fa-solid fa-camera me-2"></i> Coba Simulator Kamera
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
