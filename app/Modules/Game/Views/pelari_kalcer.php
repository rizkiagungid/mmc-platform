<?= $this->extend('layouts/master_public') ?>

<?= $this->section('content') ?>
<style>
/* =========================================================
   PELARI KALCER: SMANIT SKENA RUNNER - ARCADE NEON STYLES
   ========================================================= */
.runner-wrapper {
    background: radial-gradient(circle at 50% 15%, #0f172a 0%, #020617 100%);
    border: 1px solid rgba(16, 185, 129, 0.35);
    border-radius: 24px;
    box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.9), 0 0 45px rgba(16, 185, 129, 0.15);
    overflow: hidden;
    position: relative;
    user-select: none;
    -webkit-user-select: none;
}

/* Neon Glow Accents */
.neon-glow-emerald {
    box-shadow: 0 0 25px rgba(16, 185, 129, 0.45);
}
.neon-glow-cyan {
    box-shadow: 0 0 25px rgba(6, 182, 212, 0.45);
}
.neon-glow-amber {
    box-shadow: 0 0 25px rgba(245, 158, 11, 0.45);
}

/* Top Status Bar */
.runner-topbar {
    background: rgba(15, 23, 42, 0.85);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    padding: 10px 16px;
}

/* Canvas Display */
.runner-canvas-container {
    position: relative;
    width: 100%;
    background: #090d16;
    overflow: hidden;
    line-height: 0;
}
#runnerCanvas {
    width: 100%;
    height: auto;
    display: block;
    aspect-ratio: 900 / 340;
    cursor: pointer;
    touch-action: manipulation;
}

/* HUD Overlay Badges */
.hud-stat-pill {
    background: rgba(15, 23, 42, 0.75);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 12px;
    padding: 4px 10px;
    font-family: var(--bs-font-monospace, monospace);
    font-size: 0.82rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* Overlays (Start, Game Over, Pause) */
.runner-screen-overlay {
    position: absolute;
    inset: 0;
    background: rgba(4, 8, 18, 0.94);
    backdrop-filter: blur(14px);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 16px;
    z-index: 30;
    text-align: center;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    animation: fadeInOverlay 0.25s ease forwards;
}

@keyframes fadeInOverlay {
    from { opacity: 0; transform: scale(0.98); }
    to { opacity: 1; transform: scale(1); }
}

/* Modal Dialog Box inside Overlay */
.runner-modal-dialog {
    width: 100%;
    max-width: 660px;
    margin: auto;
    background: rgba(15, 23, 42, 0.92);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(16, 185, 129, 0.25);
    border-radius: 20px;
    padding: 22px 20px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7), 0 0 30px rgba(16, 185, 129, 0.12);
    animation: scaleUpModal 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
@media (max-width: 575px) {
    .runner-modal-dialog {
        padding: 16px 12px;
        border-radius: 16px;
        max-width: 100%;
    }
}
@keyframes scaleUpModal {
    from { transform: scale(0.95); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

/* 5 Character Select Grid (Desktop: 5 Columns, Mobile: Horizontal Scroll) */
.runner-char-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 8px;
    margin-bottom: 12px;
    width: 100%;
}
@media (max-width: 767px) {
    .runner-char-grid {
        display: flex !important;
        flex-direction: row !important;
        flex-wrap: nowrap !important;
        gap: 10px !important;
        overflow-x: auto !important;
        overflow-y: hidden !important;
        -webkit-overflow-scrolling: touch !important;
        touch-action: pan-x !important;
        cursor: grab;
        padding: 4px 2px 10px 2px;
        scrollbar-width: thin;
        scrollbar-color: rgba(16, 185, 129, 0.6) rgba(15, 23, 42, 0.6);
    }
    .runner-char-grid:active {
        cursor: grabbing;
    }
    .runner-char-grid .character-select-card {
        flex: 0 0 114px !important;
        width: 114px !important;
        min-width: 114px !important;
        max-width: 114px !important;
    }
}

/* Character Card Selectors */
.character-select-card {
    background: rgba(30, 41, 59, 0.7);
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: 14px;
    padding: 10px 4px;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    text-align: center;
    position: relative;
    user-select: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    min-height: 104px;
}
.character-select-card:hover {
    border-color: rgba(16, 185, 129, 0.6);
    background: rgba(30, 41, 59, 0.95);
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.35);
}
.character-select-card.active {
    border-color: #10b981;
    background: rgba(16, 185, 129, 0.2);
    box-shadow: 0 0 18px rgba(16, 185, 129, 0.4);
}
.character-select-card.active::after {
    content: '✓';
    position: absolute;
    top: 4px;
    right: 5px;
    font-size: 0.62rem;
    font-weight: 900;
    background: #10b981;
    color: #fff;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 6px rgba(16, 185, 129, 0.8);
}

.char-avatar-box {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    margin-bottom: 4px;
    background: #0f172a;
    border: 1px solid rgba(255, 255, 255, 0.15);
    transition: transform 0.2s ease;
}
.character-select-card:hover .char-avatar-box,
.character-select-card.active .char-avatar-box {
    transform: scale(1.1);
}
.char-title-text {
    color: #f8fafc;
    font-size: 0.78rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 3px;
    white-space: nowrap;
}
.char-badge-tag {
    font-size: 0.65rem;
    font-family: var(--bs-font-monospace, monospace);
    padding: 2px 6px;
    border-radius: 6px;
    white-space: nowrap;
    display: inline-block;
}

/* Mobile Virtual Touch Controls */
.mobile-controls-bar {
    display: none;
    background: rgba(15, 23, 42, 0.95);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding: 12px 16px;
}
@media (max-width: 991px) {
    .mobile-controls-bar {
        display: flex;
    }
}

.touch-control-btn {
    flex: 1;
    padding: 14px 10px;
    border-radius: 16px;
    border: 2px solid rgba(255, 255, 255, 0.15);
    font-family: var(--bs-font-heading, sans-serif);
    font-weight: 800;
    font-size: 1.05rem;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.1s ease;
    touch-action: manipulation;
}
.touch-control-btn:active {
    transform: scale(0.96);
    filter: brightness(1.2);
}
.touch-btn-jump {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}
.touch-btn-duck {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
}

/* Powerup Status Indicator */
.powerup-active-badge {
    animation: pulseBadge 1s infinite alternate;
}
@keyframes pulseBadge {
    0% { transform: scale(1); filter: drop-shadow(0 0 2px rgba(245, 158, 11, 0.5)); }
    100% { transform: scale(1.05); filter: drop-shadow(0 0 10px rgba(245, 158, 11, 0.9)); }
}

/* Guide List Styles */
.guide-list-item {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
.guide-list-item:hover {
    border-color: rgba(16, 185, 129, 0.45) !important;
    transform: translateX(4px);
    box-shadow: 0 8px 24px -6px rgba(0, 0, 0, 0.25);
}
.guide-icon-avatar {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
}
.bg-gradient-emerald {
    background: linear-gradient(135deg, #10b981 0%, #047857 100%);
}
.bg-gradient-amber {
    background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%);
}
.bg-gradient-cyan {
    background: linear-gradient(135deg, #06b6d4 0%, #0e7490 100%);
}

/* Guide Pill Badges for Items & Power-ups */
.guide-pill-badge {
    background: rgba(15, 23, 42, 0.65);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 12px;
    padding: 8px 12px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    width: 100%;
    transition: all 0.2s ease;
}
.guide-pill-badge:hover {
    border-color: rgba(16, 185, 129, 0.4);
    background: rgba(16, 185, 129, 0.08);
}

/* Responsive adjustments for Mobile Screens */
@media (max-width: 768px) {
    .runner-wrapper {
        border-radius: 16px;
        margin-bottom: 1.25rem;
    }
    .runner-topbar {
        padding: 8px 10px;
    }
    .hud-stat-pill {
        padding: 3px 7px;
        font-size: 0.72rem;
        border-radius: 8px;
    }
    #hudTimeAtmosphere {
        font-size: 0.68rem;
    }
    .char-avatar-box {
        width: 38px;
        height: 38px;
        font-size: 1.35rem;
        border-radius: 10px;
        margin-bottom: 4px;
    }
    .character-select-card {
        padding: 6px 3px;
        border-radius: 12px;
    }
    .touch-control-btn {
        padding: 12px 8px;
        font-size: 0.92rem;
        border-radius: 12px;
    }
    .guide-icon-avatar {
        width: 36px;
        height: 36px;
        font-size: 1rem;
        border-radius: 10px;
    }
    .runner-screen-overlay {
        padding: 14px 10px;
    }
    .guide-list-item {
        padding: 12px 10px !important;
    }
}
</style>

<section class="py-2 py-lg-4">
    <div class="container-fluid px-2 px-sm-3 px-lg-4 px-xl-5">
        
        <!-- Breadcrumb Navigation -->
        <div class="d-flex align-items-center justify-content-between mb-2 mb-md-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>" class="text-secondary text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('mini-game') ?>" class="text-secondary text-decoration-none">Mini Game</a></li>
                    <li class="breadcrumb-item active text-success fw-bold" aria-current="page">Pelari Kalcer</li>
                </ol>
            </nav>
            <div class="d-flex gap-2">
                <a href="<?= base_url('mini-game') ?>" class="btn btn-sm btn-outline-secondary px-2.5 py-1">
                    <i class="fa-solid fa-arrow-left me-1"></i> <span class="d-none d-sm-inline">Katalog Game</span>
                </a>
            </div>
        </div>

        <!-- Title & Action Bar -->
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 mb-3">
            <div>
                <div class="d-flex align-items-center gap-1.5 mb-1 flex-wrap">
                    <span class="badge bg-success text-white font-monospace style-tiny fw-bold"><i class="fa-solid fa-person-running me-1"></i> MINI GAME #5</span>
                    <span class="badge bg-body-secondary text-secondary border border-secondary border-opacity-50 style-tiny font-monospace">Endless Skena Runner MMC</span>
                    <span class="badge bg-dark text-success border border-success border-opacity-40 style-tiny font-monospace"><i class="fa-solid fa-school me-1"></i>SMAN 1 Tamansari</span>
                </div>
                <h1 class="h4 h3-md fw-bold text-body font-heading mb-0">Pelari Kalcer: SMANIT Skena Runner</h1>
            </div>

            <!-- Action Controls -->
            <div class="d-flex align-items-center gap-1.5 w-100 w-md-auto justify-content-end">
                <button type="button" class="btn btn-sm btn-outline-success flex-grow-1 flex-md-grow-0 py-1 text-white border-success" data-bs-toggle="modal" data-bs-target="#runnerSettingsModal">
                    <i class="fa-solid fa-sliders me-1"></i> Pengaturan
                </button>
                <button type="button" class="btn btn-sm btn-outline-warning flex-grow-1 flex-md-grow-0 py-1" data-bs-toggle="modal" data-bs-target="#runnerLeaderboardModal">
                    <i class="fa-solid fa-trophy me-1"></i> Rekor Jarak
                </button>
            </div>
        </div>

        <!-- Main Arcade Shell -->
        <div class="runner-wrapper mb-4 position-relative">
            
            <!-- Top Status Bar -->
            <div class="runner-topbar d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="hud-stat-pill text-white">
                        <i class="fa-solid fa-route text-success"></i>
                        <span id="hudDistance">0 m</span>
                    </span>
                    <span class="hud-stat-pill text-warning">
                        <i class="fa-solid fa-floppy-disk text-warning"></i>
                        <span id="hudSDCards">0 SD</span>
                    </span>
                    <span class="hud-stat-pill text-info">
                        <i class="fa-solid fa-star text-info"></i>
                        <span id="hudScore">0 Pts</span>
                    </span>
                    <span class="hud-stat-pill text-light d-none" id="hudPowerupBadge">
                        <i class="fa-solid fa-bolt text-warning"></i>
                        <span id="hudPowerupText">2X SKOR (5s)</span>
                    </span>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-dark border border-secondary border-opacity-30 text-secondary font-monospace style-tiny" id="hudTimeAtmosphere">
                        🌅 SORE SKENA
                    </span>
                    <!-- Audio Toggle -->
                    <button type="button" class="btn btn-sm btn-dark border border-secondary border-opacity-25 text-secondary p-1 px-2.5" id="runnerSoundBtn" onclick="toggleRunnerAudio()" title="Mute / Unmute Suara">
                        <i class="fa-solid fa-volume-high text-success" id="runnerSoundIcon"></i>
                    </button>
                    <!-- Pause Button -->
                    <button type="button" class="btn btn-sm btn-dark border border-secondary border-opacity-25 text-secondary p-1 px-2.5" id="runnerPauseBtn" onclick="toggleRunnerPause()" title="Jeda Permainan (P)">
                        <i class="fa-solid fa-pause text-info"></i>
                    </button>
                    <!-- Restart Button -->
                    <button type="button" class="btn btn-sm btn-dark border border-secondary border-opacity-25 text-secondary p-1 px-2.5" onclick="showRunnerStartScreen()" title="Menu Awal">
                        <i class="fa-solid fa-rotate-right text-warning"></i>
                    </button>
                </div>
            </div>

            <!-- Canvas Gaming Area -->
            <div class="runner-canvas-container">
                <canvas id="runnerCanvas" width="900" height="340"></canvas>
            </div>

            <!-- Mobile Touch Controls -->
            <div class="mobile-controls-bar d-flex gap-2">
                <button type="button" class="touch-control-btn touch-btn-duck" id="touchDuckBtn">
                    <i class="fa-solid fa-arrow-down fs-5"></i>
                    <span>NUNDUK / SLIDE</span>
                </button>
                <button type="button" class="touch-control-btn touch-btn-jump" id="touchJumpBtn">
                    <i class="fa-solid fa-arrow-up fs-5"></i>
                    <span>LOMPAT (2X JUMP)</span>
                </button>
            </div>

            <!-- ==========================================
                 1. PRE-GAME / START CONFIG SCREEN OVERLAY
                 ========================================== -->
            <div class="runner-screen-overlay" id="runnerStartOverlay">
                <div class="runner-modal-dialog text-center">
                    <!-- Title Header -->
                    <div class="mb-3">
                        <div class="p-2.5 rounded-circle bg-gradient-emerald text-white fs-3 d-inline-flex mb-1.5 shadow-lg border border-success border-opacity-40">
                            <i class="fa-solid fa-person-running"></i>
                        </div>
                        <h2 class="text-white font-heading fw-bold fs-4 mb-1">PELARI KALCER MMC</h2>
                        <p class="text-secondary style-tiny mb-0">Lari keliling lorong SMANIT & skena Tamansari, hindari rintangan, dan kumpulkan footage!</p>
                    </div>

                    <!-- Config Card Inner Box -->
                    <div class="bg-black bg-opacity-60 p-3 rounded-4 border border-secondary border-opacity-25 text-start mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="form-label style-tiny text-secondary font-monospace mb-0 fw-bold">
                                <i class="fa-solid fa-user-astronaut text-success me-1"></i>PILIH KARAKTER PELARI:
                            </label>
                            <div class="d-flex align-items-center gap-1">
                                <button type="button" class="btn btn-sm btn-dark border border-secondary border-opacity-40 text-success p-0 px-2 py-0.5" onclick="scrollCharGrid(-1)" title="Geser Kiri">
                                    <i class="fa-solid fa-chevron-left style-tiny"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-dark border border-secondary border-opacity-40 text-success p-0 px-2 py-0.5" onclick="scrollCharGrid(1)" title="Geser Kanan">
                                    <i class="fa-solid fa-chevron-right style-tiny"></i>
                                </button>
                            </div>
                        </div>

                        <!-- 5 Character Select Grid -->
                        <div class="runner-char-grid" id="characterSelectorGrid">
                            <!-- Character 1: Fotografer -->
                            <div class="character-select-card active" onclick="selectRunnerChar('fotografer', this)">
                                <div class="char-avatar-box">📸</div>
                                <span class="char-title-text">Fotografer</span>
                                <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-30 char-badge-tag">Canon DSLR</span>
                            </div>
                            <!-- Character 2: Mbak Editor -->
                            <div class="character-select-card" onclick="selectRunnerChar('editor', this)">
                                <div class="char-avatar-box">🎬</div>
                                <span class="char-title-text">Mbak Editor</span>
                                <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-30 char-badge-tag">Headphone</span>
                            </div>
                            <!-- Character 3: Podcaster Skena -->
                            <div class="character-select-card" onclick="selectRunnerChar('podcaster', this)">
                                <div class="char-avatar-box">🎙️</div>
                                <span class="char-title-text">Podcaster</span>
                                <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-30 char-badge-tag">Mic Skena</span>
                            </div>
                            <!-- Character 4: Drone Pilot -->
                            <div class="character-select-card" onclick="selectRunnerChar('drone', this)">
                                <div class="char-avatar-box">🚁</div>
                                <span class="char-title-text">Pilot FPV</span>
                                <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-30 char-badge-tag">FPV Goggles</span>
                            </div>
                            <!-- Character 5: Skater MMC -->
                            <div class="character-select-card" onclick="selectRunnerChar('skater', this)">
                                <div class="char-avatar-box">🛹</div>
                                <span class="char-title-text">Skater Boy</span>
                                <span class="badge bg-purple bg-opacity-25 text-purple border border-purple border-opacity-30 char-badge-tag">Board Roll</span>
                            </div>
                        </div>

                        <!-- Player Name & Speed Inputs -->
                        <div class="row g-2">
                            <div class="col-7">
                                <label class="form-label style-tiny text-secondary font-monospace mb-1">NAMA PELARI:</label>
                                <input type="text" id="runnerPlayerName" class="form-control form-control-sm bg-black text-white border-secondary border-opacity-50 font-monospace fw-bold py-1.5" value="Skena Runner" maxlength="15">
                            </div>
                            <div class="col-5">
                                <label class="form-label style-tiny text-secondary font-monospace mb-1">SPEED AWAL:</label>
                                <select id="runnerSpeedSelect" class="form-select form-select-sm bg-black text-warning border-secondary border-opacity-50 font-monospace fw-bold py-1.5">
                                    <option value="slow">🐢 Santai / Slow</option>
                                    <option value="normal" selected>🟢 Normal (Standar)</option>
                                    <option value="fast">⚡ Cepat (Tantangan)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Start Button -->
                    <button type="button" class="btn btn-success text-white font-heading fw-bold px-4 py-2.5 shadow-lg d-inline-flex align-items-center gap-2 justify-content-center w-100 rounded-3 mb-2 fs-6" onclick="startRunnerGame()">
                        <i class="fa-solid fa-play"></i> MULAI LARI SEKARANG
                    </button>
                    <div class="style-tiny text-secondary font-monospace">
                        <i class="fa-solid fa-keyboard text-success me-1"></i> Kontrol: <strong>Spasi / ↑</strong> (Lompat / Double Jump) &bull; <strong>↓ / S</strong> (Nunduk/Slide)
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 2. GAME OVER SCREEN OVERLAY
                 ========================================== -->
            <div class="runner-screen-overlay d-none" id="runnerGameOverOverlay">
                <div class="runner-modal-dialog text-center">
                    <div class="p-2.5 rounded-circle bg-danger bg-opacity-20 text-danger fs-2 d-inline-flex mb-1.5 border border-danger border-opacity-30">
                        <i class="fa-solid fa-person-falling-burst"></i>
                    </div>
                    <h3 class="text-white font-heading fw-bold fs-4 mb-1">TERSANDUNG / GAME OVER!</h3>
                    <p class="text-secondary style-tiny mb-3" id="gameOverReasonText">Kamu menabrak rintangan saat berlari kencang.</p>

                    <!-- Stats Card -->
                    <div class="bg-black bg-opacity-60 p-3 rounded-4 border border-secondary border-opacity-25 mb-3 text-start">
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-secondary border-opacity-25">
                            <span class="text-secondary small">Pelari: <strong class="text-white" id="overPlayerName">Skena Runner</strong></span>
                            <span class="badge bg-success text-white font-monospace" id="overRankTitle">Pelari Skena Tamansari</span>
                        </div>

                        <div class="row g-2 text-center my-1">
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-dark border border-secondary border-opacity-25">
                                    <div class="style-tiny text-secondary">JARAK</div>
                                    <div class="fs-4 font-heading fw-bold text-success" id="overDistance">0m</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-dark border border-secondary border-opacity-25">
                                    <div class="style-tiny text-warning">SD CARD</div>
                                    <div class="fs-4 font-heading fw-bold text-warning" id="overSDCards">0</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-dark border border-secondary border-opacity-25">
                                    <div class="style-tiny text-info">TOTAL SKOR</div>
                                    <div class="fs-4 font-heading fw-bold text-info" id="overScore">0</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                        <button type="button" class="btn btn-success text-white font-heading fw-bold px-4 py-2 shadow-lg d-inline-flex align-items-center gap-1.5 flex-grow-1 justify-content-center rounded-3" onclick="startRunnerGame()">
                            <i class="fa-solid fa-rotate-right me-1"></i> Lari Lagi (Spasi)
                        </button>
                        <button type="button" class="btn btn-outline-secondary text-white px-3 py-2 rounded-3" onclick="showRunnerStartScreen()">
                            <i class="fa-solid fa-gear me-1"></i> Ganti Karakter
                        </button>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 3. PAUSE SCREEN OVERLAY
                 ========================================== -->
            <div class="runner-screen-overlay d-none" id="runnerPauseOverlay">
                <div class="max-w-md w-100 my-auto">
                    <div class="p-3 rounded-circle bg-info bg-opacity-20 text-info fs-1 d-inline-flex mb-2 border border-info border-opacity-30">
                        <i class="fa-solid fa-pause"></i>
                    </div>
                    <h3 class="text-white font-heading fw-bold fs-4 mb-1">PERMAINAN DIJEDA</h3>
                    <p class="text-secondary style-tiny mb-3">Tarik napas sejenak sebelum lanjut lari mengejar deadline!</p>

                    <button type="button" class="btn btn-info text-dark font-heading fw-bold px-4 py-2 shadow" onclick="toggleRunnerPause()">
                        <i class="fa-solid fa-play me-1"></i> Lanjutkan Permainan
                    </button>
                </div>
            </div>

        </div>

        <!-- =========================================================
             TUTORIAL SECTION: PANDUAN CARA BERMAIN & RINTANGAN LENGKAP
             ========================================================= -->
        <div class="saas-card saas-card-glow border border-secondary border-opacity-25 p-3 p-md-4 mb-4 rounded-4">
            <div class="mb-3 mb-md-4">
                <span class="badge bg-success text-white font-monospace px-3 py-1 mb-2 fw-bold"><i class="fa-solid fa-gamepad me-1"></i> PANDUAN GAMEPLAY</span>
                <h2 class="h4 fw-bold text-body font-heading mb-1">Panduan & Cara Bermain Pelari Kalcer</h2>
                <p class="text-secondary small mb-0">
                    Kuasai teknik lompatan akurat, double jump di udara, dan timing nunduk untuk melintasi rintangan di lorong sekolah SMAN 1 Tamansari!
                </p>
            </div>

            <div class="d-flex flex-column gap-3">
                <!-- Item 1: Kontrol Gerak & Double Jump -->
                <div class="guide-list-item p-3 p-md-3.5 rounded-4 bg-body-secondary border border-secondary border-opacity-20 d-flex flex-column flex-md-row align-items-start gap-3">
                    <div class="guide-icon-avatar bg-gradient-emerald flex-shrink-0">
                        <i class="fa-solid fa-person-running"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-1.5 flex-wrap">
                            <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 font-monospace style-tiny px-2 py-0.5">KONTROL 01</span>
                            <h6 class="text-body font-heading fw-bold mb-0">Lompat Biasa, Double Jump & Nunduk (Slide)</h6>
                        </div>
                        <p class="text-secondary small mb-0 lh-base">
                            <strong>⬆️ Lompat (Spasi / W / Tombol Hijau):</strong> Melompati rintangan tanah (tripod, kabel roll, kucing oren, kardus snack).<br>
                            <strong>✨ Double Jump:</strong> Tekan lompat sekali lagi saat berada di udara untuk melompat lebih tinggi melintasi rintangan bertingkat!<br>
                            <strong>⬇️ Nunduk / Slide (Panah Bawah / S / Tombol Kuning):</strong> Menunduk meluncur untuk menghindari rintangan udara (Drone terbang rendah & Boom Mic kru film).
                        </p>
                    </div>
                </div>

                <!-- Item 2: Power-Up & Collectibles -->
                <div class="guide-list-item p-3 p-md-3.5 rounded-4 bg-body-secondary border border-secondary border-opacity-20 d-flex flex-column flex-md-row align-items-start gap-3">
                    <div class="guide-icon-avatar bg-gradient-amber flex-shrink-0">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>
                    <div class="flex-grow-1 w-100 min-w-0">
                        <div class="d-flex align-items-center gap-2 mb-1.5 flex-wrap">
                            <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25 font-monospace style-tiny px-2 py-0.5">ITEM & POWER-UP</span>
                            <h6 class="text-body font-heading fw-bold mb-0">Item Kalcer Multimedia & Efek Khusus</h6>
                        </div>
                        <p class="text-secondary small mb-2 lh-base">
                            Kumpulkan item spesial di sepanjang jalur lari:
                        </p>
                        <div class="row g-2">
                            <div class="col-12 col-sm-6">
                                <div class="guide-pill-badge">
                                    <span class="fs-5 flex-shrink-0">💾</span>
                                    <div>
                                        <strong class="text-body d-block small">SD Card 128GB</strong>
                                        <span class="text-secondary style-tiny">+50 Poin Ekstra & Tambahan Footage</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="guide-pill-badge">
                                    <span class="fs-5 flex-shrink-0">☕</span>
                                    <div>
                                        <strong class="text-body d-block small">Kopi Susu Gula Aren</strong>
                                        <span class="text-secondary style-tiny">Mode 2X Poin selama 8 detik</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="guide-pill-badge">
                                    <span class="fs-5 flex-shrink-0">🔋</span>
                                    <div>
                                        <strong class="text-body d-block small">Baterai Dummy Shield</strong>
                                        <span class="text-secondary style-tiny">Kebal 1x benturan rintangan</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="guide-pill-badge">
                                    <span class="fs-5 flex-shrink-0">📸</span>
                                    <div>
                                        <strong class="text-body d-block small">Lensa Fix 50mm</strong>
                                        <span class="text-secondary style-tiny">Flash Bomb penghancur rintangan</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item 3: Transisi Suasana Waktu SMANIT -->
                <div class="guide-list-item p-3 p-md-3.5 rounded-4 bg-body-secondary border border-secondary border-opacity-20 d-flex flex-column flex-md-row align-items-start gap-3">
                    <div class="guide-icon-avatar bg-gradient-cyan flex-shrink-0">
                        <i class="fa-solid fa-cloud-sun"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-1.5 flex-wrap">
                            <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-25 font-monospace style-tiny px-2 py-0.5">ATMOSFER</span>
                            <h6 class="text-body font-heading fw-bold mb-0">Siklus Suasana Langit SMANIT Tamansari</h6>
                        </div>
                        <p class="text-secondary small mb-0 lh-base">
                            Semakin jauh kamu berlari, suasana langit berganti secara sinematik: <strong>🌅 Golden Hour Sunset Tamansari</strong> (0-400m) &rarr; <strong>🌆 Cyber Skena Night</strong> (400-900m) &rarr; <strong>☀️ Pagi Lapangan Upacara</strong> (900m+). Kecepatan berlari akan bertambah secara bertahap!
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- =========================================================
     MODAL 1: SETTINGS & CONTROLS
     ========================================================= -->
<div class="modal fade" id="runnerSettingsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border border-success border-opacity-50 text-white rounded-4 shadow-2xl p-3 p-md-4">
            <div class="modal-header border-bottom border-secondary border-opacity-25 pb-2.5">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-sliders text-success fs-5"></i>
                    <div>
                        <h5 class="modal-title font-heading fw-bold fs-6">Pengaturan Pelari Kalcer</h5>
                        <div class="text-secondary style-tiny">Sesuaikan suara SFX dan sensitivitas tombol</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body py-3">
                <div class="mb-3">
                    <label class="form-label small text-secondary font-monospace mb-1">EFEK SUARA & SFX:</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="modalRunnerAudioSwitch" checked>
                        <label class="form-check-label small text-white" for="modalRunnerAudioSwitch">Aktifkan efek audio retro sintetis (Web Audio API)</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small text-secondary font-monospace mb-1">PARTIKEL & VISUAL EFFECTS:</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="modalRunnerParticlesSwitch" checked>
                        <label class="form-check-label small text-white" for="modalRunnerParticlesSwitch">Aktifkan efek debu lari & spark item</label>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-top border-secondary border-opacity-25 pt-2.5">
                <button type="button" class="btn btn-sm btn-outline-secondary px-3 text-white" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-sm btn-success text-white font-heading fw-bold px-4" onclick="saveRunnerModalSettings()">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     MODAL 2: RUNNER LEADERBOARD
     ========================================================= -->
<div class="modal fade" id="runnerLeaderboardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border border-warning border-opacity-50 text-white rounded-4 shadow-2xl p-3 p-md-4">
            <div class="modal-header border-bottom border-secondary border-opacity-25 pb-2.5">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-trophy text-warning fs-5"></i>
                    <div>
                        <h5 class="modal-title font-heading fw-bold fs-6">Papan Rekor Pelari Kalcer</h5>
                        <div class="text-secondary style-tiny">Rekor jarak tempuh terjauh yang tersimpan di perangkat ini</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body py-3" id="runnerLeaderboardBody">
                <div class="text-center text-secondary py-3">Memuat rekor...</div>
            </div>

            <div class="modal-footer border-top border-secondary border-opacity-25 pt-2.5">
                <button type="button" class="btn btn-sm btn-outline-danger me-auto" onclick="clearRunnerLeaderboard()">
                    <i class="fa-solid fa-trash me-1"></i> Reset Rekor
                </button>
                <button type="button" class="btn btn-sm btn-warning text-dark font-heading fw-bold px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     JAVASCRIPT: 60 FPS HTML5 CANVAS RUNNER ENGINE
     ========================================================= -->
<script>
// --- Game State Constants & Configuration ---
const CANVAS_WIDTH = 900;
const CANVAS_HEIGHT = 340;
const GROUND_Y = 275;

let runnerState = {
    isRunning: false,
    isPaused: false,
    audioEnabled: true,
    particlesEnabled: true,
    
    // Player Stats
    playerName: 'Skena Runner',
    selectedChar: 'fotografer', // 'fotografer' | 'editor' | 'podcaster' | 'drone' | 'skater'
    speedMode: 'normal',
    
    // In-game Metrics
    distance: 0,
    score: 0,
    sdCards: 0,
    speed: 3.8,
    baseSpeed: 3.8,
    gravity: 0.54,
    
    // Player Physics
    player: {
        x: 100,
        y: GROUND_Y - 48,
        width: 38,
        height: 48,
        vy: 0,
        isJumping: false,
        jumpCount: 0,
        maxJumps: 2, // Double jump capability!
        isDucking: false,
        duckTimer: 0,
        animFrame: 0,
        hasShield: false,
        hasDoubleScore: false,
        doubleScoreTimer: 0
    },
    
    // Obstacles & Items
    obstacles: [],
    collectibles: [],
    particles: [],
    nextObstacleDist: 90,
    nextCollectibleDist: 140,
    
    // Background Scenery
    bgOffset: 0,
    clouds: [],
    decorations: [],
    
    // Animation Loop
    lastTimestamp: 0,
    animId: null
};

// --- Web Audio API Synth SFX Engine ---
let runnerAudioCtx = null;

function initRunnerAudio() {
    if (!runnerAudioCtx) {
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        runnerAudioCtx = new AudioContext();
    }
    if (runnerAudioCtx.state === 'suspended') {
        runnerAudioCtx.resume();
    }
}

function playRunnerSound(type) {
    if (!runnerState.audioEnabled) return;
    try {
        initRunnerAudio();
        const now = runnerAudioCtx.currentTime;

        if (type === 'jump') {
            const osc = runnerAudioCtx.createOscillator();
            const gain = runnerAudioCtx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(260, now);
            osc.frequency.exponentialRampToValueAtTime(600, now + 0.15);
            gain.gain.setValueAtTime(0.2, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.15);
            osc.connect(gain);
            gain.connect(runnerAudioCtx.destination);
            osc.start(now);
            osc.stop(now + 0.15);
        } else if (type === 'double_jump') {
            [440, 700].forEach((freq, i) => {
                const osc = runnerAudioCtx.createOscillator();
                const gain = runnerAudioCtx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(freq, now + i * 0.05);
                osc.frequency.exponentialRampToValueAtTime(freq * 1.5, now + i * 0.05 + 0.12);
                gain.gain.setValueAtTime(0.18, now + i * 0.05);
                gain.gain.exponentialRampToValueAtTime(0.001, now + i * 0.05 + 0.12);
                osc.connect(gain);
                gain.connect(runnerAudioCtx.destination);
                osc.start(now + i * 0.05);
                osc.stop(now + i * 0.05 + 0.12);
            });
        } else if (type === 'duck') {
            const osc = runnerAudioCtx.createOscillator();
            const gain = runnerAudioCtx.createGain();
            osc.type = 'sawtooth';
            osc.frequency.setValueAtTime(220, now);
            osc.frequency.linearRampToValueAtTime(120, now + 0.12);
            gain.gain.setValueAtTime(0.12, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.12);
            osc.connect(gain);
            gain.connect(runnerAudioCtx.destination);
            osc.start(now);
            osc.stop(now + 0.12);
        } else if (type === 'pickup') {
            [587.33, 880, 1174.66].forEach((freq, i) => {
                const osc = runnerAudioCtx.createOscillator();
                const gain = runnerAudioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, now + i * 0.04);
                gain.gain.setValueAtTime(0.15, now + i * 0.04);
                gain.gain.exponentialRampToValueAtTime(0.001, now + i * 0.04 + 0.12);
                osc.connect(gain);
                gain.connect(runnerAudioCtx.destination);
                osc.start(now + i * 0.04);
                osc.stop(now + i * 0.04 + 0.12);
            });
        } else if (type === 'powerup') {
            [523.25, 659.25, 783.99, 1046.50].forEach((freq, i) => {
                const osc = runnerAudioCtx.createOscillator();
                const gain = runnerAudioCtx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(freq, now + i * 0.06);
                gain.gain.setValueAtTime(0.2, now + i * 0.06);
                gain.gain.exponentialRampToValueAtTime(0.001, now + i * 0.06 + 0.2);
                osc.connect(gain);
                gain.connect(runnerAudioCtx.destination);
                osc.start(now + i * 0.06);
                osc.stop(now + i * 0.06 + 0.2);
            });
        } else if (type === 'crash') {
            const osc = runnerAudioCtx.createOscillator();
            const gain = runnerAudioCtx.createGain();
            osc.type = 'sawtooth';
            osc.frequency.setValueAtTime(160, now);
            osc.frequency.linearRampToValueAtTime(40, now + 0.35);
            gain.gain.setValueAtTime(0.3, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.35);
            osc.connect(gain);
            gain.connect(runnerAudioCtx.destination);
            osc.start(now);
            osc.stop(now + 0.35);
        } else if (type === 'milestone') {
            [523.25, 659.25, 1046.50].forEach((freq, i) => {
                const osc = runnerAudioCtx.createOscillator();
                const gain = runnerAudioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, now + i * 0.08);
                gain.gain.setValueAtTime(0.18, now + i * 0.08);
                gain.gain.exponentialRampToValueAtTime(0.001, now + i * 0.08 + 0.25);
                osc.connect(gain);
                gain.connect(runnerAudioCtx.destination);
                osc.start(now + i * 0.08);
                osc.stop(now + i * 0.08 + 0.25);
            });
        }
    } catch (e) {}
}

function toggleRunnerAudio() {
    runnerState.audioEnabled = !runnerState.audioEnabled;
    const icon = document.getElementById('runnerSoundIcon');
    if (runnerState.audioEnabled) {
        icon.className = 'fa-solid fa-volume-high text-success';
    } else {
        icon.className = 'fa-solid fa-volume-xmark text-danger';
    }
}

// --- Character Selection & Grid Scrolling Helper ---
function scrollCharGrid(direction) {
    const grid = document.getElementById('characterSelectorGrid');
    if (grid) {
        grid.scrollBy({ left: direction * 125, behavior: 'smooth' });
    }
}

function selectRunnerChar(charId, el) {
    runnerState.selectedChar = charId;
    document.querySelectorAll('.character-select-card').forEach(c => c.classList.remove('active'));
    if (el) el.classList.add('active');
}

// --- Game Initialization ---
function startRunnerGame() {
    initRunnerAudio();

    const nameInput = document.getElementById('runnerPlayerName').value.trim();
    runnerState.playerName = nameInput || 'Skena Runner';
    runnerState.speedMode = document.getElementById('runnerSpeedSelect').value;

    let initSpeed = 3.8;
    if (runnerState.speedMode === 'slow') initSpeed = 3.0;
    if (runnerState.speedMode === 'fast') initSpeed = 4.8;

    runnerState.baseSpeed = initSpeed;
    runnerState.speed = initSpeed;
    runnerState.distance = 0;
    runnerState.score = 0;
    runnerState.sdCards = 0;
    runnerState.obstacles = [];
    runnerState.collectibles = [];
    runnerState.particles = [];
    runnerState.nextObstacleDist = 60;
    runnerState.nextCollectibleDist = 100;

    // Reset Player Physics
    runnerState.player.y = GROUND_Y - 48;
    runnerState.player.vy = 0;
    runnerState.player.isJumping = false;
    runnerState.player.jumpCount = 0;
    runnerState.player.isDucking = false;
    runnerState.player.duckTimer = 0;
    runnerState.player.hasShield = false;
    runnerState.player.hasDoubleScore = false;
    runnerState.player.doubleScoreTimer = 0;

    // Initialize Clouds & Scenery
    initScenery();

    runnerState.isRunning = true;
    runnerState.isPaused = false;

    // Hide overlays
    document.getElementById('runnerStartOverlay').classList.add('d-none');
    document.getElementById('runnerGameOverOverlay').classList.add('d-none');
    document.getElementById('runnerPauseOverlay').classList.add('d-none');
    document.getElementById('hudPowerupBadge').classList.add('d-none');

    updateHUD();

    if (runnerState.animId) cancelAnimationFrame(runnerState.animId);
    runnerState.lastTimestamp = performance.now();
    runnerState.animId = requestAnimationFrame(gameLoop);
}

function showRunnerStartScreen() {
    runnerState.isRunning = false;
    runnerState.isPaused = false;
    if (runnerState.animId) cancelAnimationFrame(runnerState.animId);
    document.getElementById('runnerGameOverOverlay').classList.add('d-none');
    document.getElementById('runnerPauseOverlay').classList.add('d-none');
    document.getElementById('runnerStartOverlay').classList.remove('d-none');
}

function toggleRunnerPause() {
    if (!runnerState.isRunning) return;
    runnerState.isPaused = !runnerState.isPaused;
    document.getElementById('runnerPauseOverlay').classList.toggle('d-none', !runnerState.isPaused);
    
    if (!runnerState.isPaused) {
        runnerState.lastTimestamp = performance.now();
        runnerState.animId = requestAnimationFrame(gameLoop);
    }
}

// --- Player Action Handlers ---
function playerJump() {
    if (!runnerState.isRunning || runnerState.isPaused) return;

    if (runnerState.player.jumpCount < runnerState.player.maxJumps) {
        // First jump or Double Jump
        if (runnerState.player.jumpCount === 0) {
            runnerState.player.vy = -10.5;
            runnerState.player.isJumping = true;
            playRunnerSound('jump');
            createJumpParticles();
        } else {
            // Double Jump
            runnerState.player.vy = -9.5;
            playRunnerSound('double_jump');
            createDoubleJumpParticles();
        }
        runnerState.player.jumpCount++;
        runnerState.player.isDucking = false;
    }
}

function playerDuckStart() {
    if (!runnerState.isRunning || runnerState.isPaused) return;
    if (!runnerState.player.isJumping) {
        runnerState.player.isDucking = true;
        runnerState.player.duckTimer = 18; // Frames duration
        playRunnerSound('duck');
        createSlideParticles();
    } else {
        // Fast dive down from jump
        runnerState.player.vy = Math.max(runnerState.player.vy, 10);
        runnerState.player.isDucking = true;
    }
}

function playerDuckEnd() {
    if (runnerState.player.isDucking && !runnerState.player.isJumping) {
        runnerState.player.isDucking = false;
    }
}

// --- Scenery Initializer ---
function initScenery() {
    runnerState.clouds = [
        { x: 120, y: 50, speed: 0.6, scale: 1.0 },
        { x: 380, y: 75, speed: 0.8, scale: 1.3 },
        { x: 650, y: 40, speed: 0.5, scale: 0.9 },
        { x: 880, y: 65, speed: 0.7, scale: 1.1 }
    ];
}

// --- Main 60 FPS Game Loop ---
function gameLoop(timestamp) {
    if (!runnerState.isRunning || runnerState.isPaused) return;

    const dt = Math.min((timestamp - runnerState.lastTimestamp) / 1000, 0.1);
    runnerState.lastTimestamp = timestamp;

    updateGame(dt);
    renderGame();

    runnerState.animId = requestAnimationFrame(gameLoop);
}

// --- Game Logic & Physics Update ---
function updateGame(dt) {
    // 1. Distance & Score Progression (Relaxed and readable)
    runnerState.distance += runnerState.speed * 0.05;
    const scoreMultiplier = runnerState.player.hasDoubleScore ? 2 : 1;
    runnerState.score += Math.round(runnerState.speed * 0.15 * scoreMultiplier);

    // Increase speed very gradually over long distances
    runnerState.speed = runnerState.baseSpeed + Math.min(runnerState.distance / 350, 3.5);

    // Milestone Chime every 250m
    if (Math.floor(runnerState.distance) > 0 && Math.floor(runnerState.distance) % 250 === 0 && Math.floor(runnerState.distance) % 5 === 0) {
        playRunnerSound('milestone');
    }

    // 2. Player Physics Update
    const p = runnerState.player;
    p.animFrame++;

    if (p.isDucking) {
        p.duckTimer--;
        if (p.duckTimer <= 0 && !keysPressed['ArrowDown'] && !keysPressed['KeyS'] && !isTouchDucking) {
            p.isDucking = false;
        }
    }

    // Gravity & Vertical Motion
    if (p.isJumping || p.y < GROUND_Y - 48) {
        p.vy += runnerState.gravity;
        p.y += p.vy;

        // Hit Ground
        if (p.y >= GROUND_Y - 48) {
            p.y = GROUND_Y - 48;
            p.vy = 0;
            p.isJumping = false;
            p.jumpCount = 0;
        }
    }

    // Double Score Power-Up Timer
    if (p.hasDoubleScore) {
        p.doubleScoreTimer -= dt;
        if (p.doubleScoreTimer <= 0) {
            p.hasDoubleScore = false;
            document.getElementById('hudPowerupBadge').classList.add('d-none');
        } else {
            document.getElementById('hudPowerupText').innerText = `2X SKOR (${Math.ceil(p.doubleScoreTimer)}s)`;
        }
    }

    // 3. Clouds & Scenery Update
    runnerState.clouds.forEach(cloud => {
        cloud.x -= cloud.speed * (runnerState.speed / 4);
        if (cloud.x < -100) {
            cloud.x = CANVAS_WIDTH + 50 + Math.random() * 100;
            cloud.y = 35 + Math.random() * 60;
        }
    });

    // 4. Spawning Obstacles with comfortable reaction spacing
    runnerState.nextObstacleDist -= runnerState.speed * 0.05;
    if (runnerState.nextObstacleDist <= 0) {
        spawnObstacle();
        runnerState.nextObstacleDist = 55 + Math.random() * 45;
    }

    // 5. Spawning Collectibles
    runnerState.nextCollectibleDist -= runnerState.speed * 0.05;
    if (runnerState.nextCollectibleDist <= 0) {
        spawnCollectible();
        runnerState.nextCollectibleDist = 75 + Math.random() * 60;
    }

    // 6. Update Obstacles
    for (let i = runnerState.obstacles.length - 1; i >= 0; i--) {
        const obs = runnerState.obstacles[i];
        obs.x -= runnerState.speed;

        // Collision Detection with Player
        if (checkCollision(p, obs)) {
            if (p.hasShield) {
                // Break shield and destroy obstacle
                p.hasShield = false;
                playRunnerSound('pickup');
                createExplosionParticles(obs.x + obs.width / 2, obs.y + obs.height / 2, '#06b6d4');
                runnerState.obstacles.splice(i, 1);
                continue;
            } else {
                triggerGameOver(obs.name);
                return;
            }
        }

        // Remove out-of-screen obstacles
        if (obs.x + obs.width < -50) {
            runnerState.obstacles.splice(i, 1);
        }
    }

    // 7. Update Collectibles
    for (let i = runnerState.collectibles.length - 1; i >= 0; i--) {
        const item = runnerState.collectibles[i];
        item.x -= runnerState.speed;

        // Check pickup collision
        if (checkItemCollision(p, item)) {
            applyCollectible(item);
            createExplosionParticles(item.x + 12, item.y + 12, item.color);
            runnerState.collectibles.splice(i, 1);
            continue;
        }

        if (item.x < -30) {
            runnerState.collectibles.splice(i, 1);
        }
    }

    // 8. Update Particles
    if (runnerState.particlesEnabled) {
        for (let i = runnerState.particles.length - 1; i >= 0; i--) {
            const part = runnerState.particles[i];
            part.x += part.vx;
            part.y += part.vy;
            part.life -= dt * 2.5;
            part.size *= 0.96;
            if (part.life <= 0 || part.size < 0.5) {
                runnerState.particles.splice(i, 1);
            }
        }
    }

    // Running dust particles
    if (p.y >= GROUND_Y - 48 && p.animFrame % 6 === 0 && runnerState.particlesEnabled) {
        runnerState.particles.push({
            x: p.x + 6,
            y: GROUND_Y - 4,
            vx: -(2 + Math.random() * 2),
            vy: -(0.5 + Math.random()),
            size: 3 + Math.random() * 3,
            color: 'rgba(200, 200, 200, 0.4)',
            life: 0.6
        });
    }

    updateHUD();
}

// --- Spawn Obstacle Types ---
function spawnObstacle() {
    // Determine obstacle type based on distance and variety
    const types = [
        { type: 'tripod', name: 'Tripod Kamera SMANIT', width: 28, height: 46, y: GROUND_Y - 46, needDuck: false },
        { type: 'cable_roll', name: 'Kabel Roll Audio Kusut', width: 34, height: 26, y: GROUND_Y - 26, needDuck: false },
        { type: 'kucing_oren', name: 'Kucing Oren Lab Multimedia', width: 36, height: 24, y: GROUND_Y - 24, needDuck: false },
        { type: 'snack_box', name: 'Tumpukan Dus Snack OSIS', width: 32, height: 50, y: GROUND_Y - 50, needDuck: false },
        { type: 'drone_low', name: 'Drone Liputan Terbang Rendah', width: 44, height: 28, y: GROUND_Y - 78, needDuck: true },
        { type: 'boom_mic', name: 'Boom Pole Mic Kru Film', width: 56, height: 24, y: GROUND_Y - 74, needDuck: true }
    ];

    // Pick random obstacle
    const chosen = types[Math.floor(Math.random() * types.length)];
    
    runnerState.obstacles.push({
        ...chosen,
        x: CANVAS_WIDTH + 30
    });
}

// --- Spawn Collectible / Power-Up Types ---
function spawnCollectible() {
    const items = [
        { type: 'sd_card', name: 'SD Card 128GB', color: '#10b981', pts: 50, isPower: false },
        { type: 'kopi_skena', name: 'Kopi Susu Skena (2X Score)', color: '#f59e0b', pts: 100, isPower: true, power: 'double' },
        { type: 'battery_shield', name: 'Baterai Sony Shield', color: '#06b6d4', pts: 30, isPower: true, power: 'shield' },
        { type: 'flash_bomb', name: 'Lensa 50mm Flash Bomb', color: '#a855f7', pts: 40, isPower: true, power: 'flash' }
    ];

    const pick = items[Math.floor(Math.random() * items.length)];
    // Random height (low or air)
    const yPos = Math.random() > 0.4 ? GROUND_Y - 42 : GROUND_Y - 95;

    runnerState.collectibles.push({
        ...pick,
        x: CANVAS_WIDTH + 30,
        y: yPos,
        width: 24,
        height: 24,
        floatOffset: Math.random() * Math.PI
    });
}

// --- Apply Item Effects ---
function applyCollectible(item) {
    if (item.type === 'sd_card') {
        runnerState.sdCards++;
        runnerState.score += item.pts;
        playRunnerSound('pickup');
    } else if (item.power === 'double') {
        runnerState.player.hasDoubleScore = true;
        runnerState.player.doubleScoreTimer = 8.0; // 8 seconds
        runnerState.score += item.pts;
        playRunnerSound('powerup');
        document.getElementById('hudPowerupBadge').classList.remove('d-none');
    } else if (item.power === 'shield') {
        runnerState.player.hasShield = true;
        runnerState.score += item.pts;
        playRunnerSound('powerup');
    } else if (item.power === 'flash') {
        // Clear all obstacles on screen
        runnerState.obstacles.forEach(obs => {
            createExplosionParticles(obs.x + obs.width / 2, obs.y + obs.height / 2, '#a855f7');
        });
        runnerState.obstacles = [];
        runnerState.score += item.pts;
        playRunnerSound('powerup');
    }
}

// --- Collision Checks (Hitbox with slight forgiveness) ---
function checkCollision(player, obs) {
    let pHeight = player.isDucking ? 20 : 44;
    let pY = player.isDucking ? GROUND_Y - 20 : player.y;
    let pWidth = player.isDucking ? 40 : 28;
    let pX = player.x + 6;

    // Inset padding for fairness and smooth dodging
    const pad = 6;
    return (
        pX + pWidth - pad > obs.x + pad &&
        pX + pad < obs.x + obs.width - pad &&
        pY + pHeight - pad > obs.y + pad &&
        pY + pad < obs.y + obs.height - pad
    );
}

function checkItemCollision(player, item) {
    let pHeight = player.isDucking ? 26 : 48;
    let pY = player.isDucking ? GROUND_Y - 26 : player.y;
    return (
        player.x + 36 > item.x &&
        player.x < item.x + item.width &&
        pY + pHeight > item.y &&
        pY < item.y + item.height
    );
}

// --- Particle Generators ---
function createJumpParticles() {
    if (!runnerState.particlesEnabled) return;
    for (let i = 0; i < 6; i++) {
        runnerState.particles.push({
            x: runnerState.player.x + 12 + Math.random() * 10,
            y: GROUND_Y - 4,
            vx: (Math.random() - 0.5) * 4 - 2,
            vy: -Math.random() * 2,
            size: 3 + Math.random() * 3,
            color: 'rgba(16, 185, 129, 0.7)',
            life: 0.5
        });
    }
}

function createDoubleJumpParticles() {
    if (!runnerState.particlesEnabled) return;
    for (let i = 0; i < 10; i++) {
        runnerState.particles.push({
            x: runnerState.player.x + 16,
            y: runnerState.player.y + 35,
            vx: (Math.random() - 0.5) * 5,
            vy: Math.random() * 2 + 1,
            size: 3 + Math.random() * 4,
            color: 'rgba(6, 182, 212, 0.85)',
            life: 0.6
        });
    }
}

function createSlideParticles() {
    if (!runnerState.particlesEnabled) return;
    for (let i = 0; i < 5; i++) {
        runnerState.particles.push({
            x: runnerState.player.x + 5,
            y: GROUND_Y - 2,
            vx: -3 - Math.random() * 2,
            vy: -0.5 - Math.random(),
            size: 3 + Math.random() * 3,
            color: 'rgba(245, 158, 11, 0.6)',
            life: 0.5
        });
    }
}

function createExplosionParticles(x, y, color) {
    if (!runnerState.particlesEnabled) return;
    for (let i = 0; i < 14; i++) {
        const angle = Math.random() * Math.PI * 2;
        const spd = 2 + Math.random() * 4;
        runnerState.particles.push({
            x: x,
            y: y,
            vx: Math.cos(angle) * spd,
            vy: Math.sin(angle) * spd,
            size: 3 + Math.random() * 4,
            color: color,
            life: 0.7
        });
    }
}

// --- Canvas Rendering Pipeline ---
function renderGame() {
    const canvas = document.getElementById('runnerCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');

    // 1. Determine Atmosphere by Distance (Sunset -> Cyber Night -> Pagi)
    const dist = runnerState.distance;
    let skyGrad;
    let atmoText = '🌅 SORE SKENA';

    if (dist < 400) {
        // Sunset Golden Hour
        skyGrad = ctx.createLinearGradient(0, 0, 0, GROUND_Y);
        skyGrad.addColorStop(0, '#1e112a');
        skyGrad.addColorStop(0.5, '#4a154b');
        skyGrad.addColorStop(0.85, '#d97706');
        skyGrad.addColorStop(1, '#78350f');
        atmoText = '🌅 GOLDEN HOUR (SORE)';
    } else if (dist < 900) {
        // Cyber Skena Night
        skyGrad = ctx.createLinearGradient(0, 0, 0, GROUND_Y);
        skyGrad.addColorStop(0, '#030712');
        skyGrad.addColorStop(0.6, '#0f172a');
        skyGrad.addColorStop(1, '#1e1b4b');
        atmoText = '🌆 CYBER NIGHT (SKENA)';
    } else {
        // Pagi Lapangan Upacara
        skyGrad = ctx.createLinearGradient(0, 0, 0, GROUND_Y);
        skyGrad.addColorStop(0, '#0284c7');
        skyGrad.addColorStop(0.6, '#38bdf8');
        skyGrad.addColorStop(1, '#bae6fd');
        atmoText = '☀️ PAGI UPACARA SMANIT';
    }
    document.getElementById('hudTimeAtmosphere').innerText = atmoText;

    // Draw Sky Background
    ctx.fillStyle = skyGrad;
    ctx.fillRect(0, 0, CANVAS_WIDTH, GROUND_Y);

    // Draw Stars at night
    if (dist >= 400 && dist < 900) {
        ctx.fillStyle = '#ffffff';
        for (let i = 0; i < 25; i++) {
            const sx = (i * 37 + runnerState.animFrame * 0.1) % CANVAS_WIDTH;
            const sy = (i * 19) % (GROUND_Y - 80);
            ctx.fillRect(sx, sy, 1.5, 1.5);
        }
    }

    // Draw Distant Silhouette Mountains (Gunung Salak / Tamansari)
    ctx.fillStyle = dist >= 400 && dist < 900 ? '#0b0f19' : '#2d1436';
    ctx.beginPath();
    ctx.moveTo(0, GROUND_Y);
    ctx.lineTo(0, GROUND_Y - 70);
    ctx.lineTo(160, GROUND_Y - 120);
    ctx.lineTo(340, GROUND_Y - 60);
    ctx.lineTo(520, GROUND_Y - 135);
    ctx.lineTo(720, GROUND_Y - 75);
    ctx.lineTo(CANVAS_WIDTH, GROUND_Y - 110);
    ctx.lineTo(CANVAS_WIDTH, GROUND_Y);
    ctx.closePath();
    ctx.fill();

    // Draw School & Hallway Architecture Silhouettes
    ctx.fillStyle = dist >= 400 && dist < 900 ? 'rgba(15, 23, 42, 0.9)' : 'rgba(45, 20, 55, 0.85)';
    const bOffset = (runnerState.distance * 1.5) % 180;
    for (let bx = -bOffset; bx < CANVAS_WIDTH; bx += 180) {
        ctx.fillRect(bx + 10, GROUND_Y - 85, 75, 85);
        ctx.fillRect(bx + 95, GROUND_Y - 65, 60, 65);
        // School windows
        ctx.fillStyle = '#fef08a';
        ctx.fillRect(bx + 25, GROUND_Y - 70, 12, 16);
        ctx.fillRect(bx + 50, GROUND_Y - 70, 12, 16);
        ctx.fillRect(bx + 110, GROUND_Y - 50, 10, 14);
        ctx.fillStyle = dist >= 400 && dist < 900 ? 'rgba(15, 23, 42, 0.9)' : 'rgba(45, 20, 55, 0.85)';
    }

    // Draw Clouds
    ctx.fillStyle = 'rgba(255, 255, 255, 0.35)';
    runnerState.clouds.forEach(c => {
        drawCloud(ctx, c.x, c.y, c.scale);
    });

    // Draw Ground Platform (Running Track & Grass)
    ctx.fillStyle = '#0f172a';
    ctx.fillRect(0, GROUND_Y, CANVAS_WIDTH, CANVAS_HEIGHT - GROUND_Y);

    // Track Lines
    ctx.strokeStyle = '#10b981';
    ctx.lineWidth = 3;
    ctx.beginPath();
    ctx.moveTo(0, GROUND_Y);
    ctx.lineTo(CANVAS_WIDTH, GROUND_Y);
    ctx.stroke();

    // Ground Dash Lines (Moving effect)
    ctx.fillStyle = 'rgba(16, 185, 129, 0.35)';
    const dashOffset = (runnerState.distance * 12) % 60;
    for (let dx = -dashOffset; dx < CANVAS_WIDTH; dx += 60) {
        ctx.fillRect(dx, GROUND_Y + 18, 30, 4);
        ctx.fillRect(dx + 15, GROUND_Y + 38, 20, 3);
    }

    // Draw Obstacles
    runnerState.obstacles.forEach(obs => {
        drawObstacle(ctx, obs);
    });

    // Draw Collectibles
    runnerState.collectibles.forEach(item => {
        drawCollectible(ctx, item);
    });

    // Draw Particles
    if (runnerState.particlesEnabled) {
        runnerState.particles.forEach(p => {
            ctx.fillStyle = p.color;
            ctx.beginPath();
            ctx.arc(p.x, p.y, Math.max(0.5, p.size), 0, Math.PI * 2);
            ctx.fill();
        });
    }

    // Draw Player
    drawPlayer(ctx, runnerState.player);
}

// --- Vector Drawer Helpers ---
function drawCloud(ctx, x, y, scale) {
    ctx.save();
    ctx.translate(x, y);
    ctx.scale(scale, scale);
    ctx.beginPath();
    ctx.arc(0, 0, 16, 0, Math.PI * 2);
    ctx.arc(14, -8, 18, 0, Math.PI * 2);
    ctx.arc(32, 0, 15, 0, Math.PI * 2);
    ctx.arc(18, 8, 14, 0, Math.PI * 2);
    ctx.closePath();
    ctx.fill();
    ctx.restore();
}

function drawPlayer(ctx, p) {
    ctx.save();
    const px = p.x;
    const py = p.isDucking ? GROUND_Y - 26 : p.y;
    const isDuck = p.isDucking;
    const legPhase = Math.sin(p.animFrame * 0.4);

    // Shield Bubble Aura
    if (p.hasShield) {
        ctx.strokeStyle = 'rgba(6, 182, 212, 0.7)';
        ctx.fillStyle = 'rgba(6, 182, 212, 0.15)';
        ctx.lineWidth = 2.5;
        ctx.beginPath();
        ctx.arc(px + 18, py + (isDuck ? 14 : 24), isDuck ? 24 : 32, 0, Math.PI * 2);
        ctx.fill();
        ctx.stroke();
    }

    // Double Score Golden Aura
    if (p.hasDoubleScore) {
        ctx.strokeStyle = 'rgba(245, 158, 11, 0.6)';
        ctx.lineWidth = 2;
        ctx.setLineDash([4, 4]);
        ctx.beginPath();
        ctx.arc(px + 18, py + (isDuck ? 14 : 24), isDuck ? 26 : 34, 0, Math.PI * 2);
        ctx.stroke();
        ctx.setLineDash([]);
    }

    // Render Character by selected type
    if (runnerState.selectedChar === 'fotografer') {
        // 📸 FOTOGRAFER: Strap DSLR, Totebag, Topi Kalcer
        if (isDuck) {
            // Sliding Pose
            ctx.fillStyle = '#1e293b'; // Badan
            ctx.fillRect(px, py + 8, 38, 16);
            ctx.fillStyle = '#f87171'; // Strap Kamera
            ctx.fillRect(px + 10, py + 12, 14, 4);
            ctx.fillStyle = '#0f172a'; // Kamera DSLR
            ctx.fillRect(px + 24, py + 10, 10, 8);
            ctx.fillStyle = '#fef08a'; // Kepala
            ctx.beginPath(); ctx.arc(px + 36, py + 12, 8, 0, Math.PI * 2); ctx.fill();
        } else {
            // Running Pose
            // Head & Hat
            ctx.fillStyle = '#fef08a'; // Face
            ctx.beginPath(); ctx.arc(px + 18, py + 8, 9, 0, Math.PI * 2); ctx.fill();
            ctx.fillStyle = '#10b981'; // Beanie Hat
            ctx.fillRect(px + 8, py - 2, 20, 6);
            // Sunglasses
            ctx.fillStyle = '#000000';
            ctx.fillRect(px + 18, py + 6, 8, 4);

            // Torso (Corduroy Jacket)
            ctx.fillStyle = '#b45309';
            ctx.fillRect(px + 10, py + 17, 16, 18);

            // Totebag & DSLR on Neck
            ctx.fillStyle = '#334155'; // DSLR Body
            ctx.fillRect(px + 14, py + 22, 12, 9);
            ctx.fillStyle = '#38bdf8'; // Lens
            ctx.fillRect(px + 26, py + 24, 4, 5);

            // Strap Red/Yellow
            ctx.strokeStyle = '#ef4444';
            ctx.lineWidth = 2;
            ctx.beginPath(); ctx.moveTo(px + 14, py + 16); ctx.lineTo(px + 20, py + 22); ctx.stroke();

            // Legs (Running animation)
            ctx.strokeStyle = '#0284c7';
            ctx.lineWidth = 4;
            // Left leg
            ctx.beginPath();
            ctx.moveTo(px + 14, py + 35);
            ctx.lineTo(px + 14 - legPhase * 10, py + 46);
            ctx.stroke();
            // Right leg
            ctx.beginPath();
            ctx.moveTo(px + 22, py + 35);
            ctx.lineTo(px + 22 + legPhase * 10, py + 46);
            ctx.stroke();
        }
    } else if (runnerState.selectedChar === 'editor') {
        // 🎬 MBAK EDITOR: Headphone over-ear, jaket crop, kacamata
        if (isDuck) {
            ctx.fillStyle = '#a855f7';
            ctx.fillRect(px, py + 8, 38, 16);
            ctx.fillStyle = '#38bdf8'; // Headphone
            ctx.fillRect(px + 30, py + 6, 6, 12);
        } else {
            // Head & Hair
            ctx.fillStyle = '#3b0764'; // Rambut ungu gelap
            ctx.beginPath(); ctx.arc(px + 18, py + 8, 10, 0, Math.PI * 2); ctx.fill();
            ctx.fillStyle = '#fef08a'; // Face
            ctx.fillRect(px + 16, py + 4, 8, 8);
            // Over-ear Headphone
            ctx.fillStyle = '#06b6d4';
            ctx.fillRect(px + 8, py + 3, 4, 10);
            ctx.fillRect(px + 24, py + 3, 4, 10);
            ctx.fillRect(px + 10, py - 1, 16, 3);

            // Jacket
            ctx.fillStyle = '#9333ea';
            ctx.fillRect(px + 10, py + 18, 16, 16);

            // Legs
            ctx.strokeStyle = '#1e1b4b';
            ctx.lineWidth = 4;
            ctx.beginPath(); ctx.moveTo(px + 14, py + 34); ctx.lineTo(px + 14 - legPhase * 10, py + 46); ctx.stroke();
            ctx.beginPath(); ctx.moveTo(px + 22, py + 34); ctx.lineTo(px + 22 + legPhase * 10, py + 46); ctx.stroke();
        }
    } else if (runnerState.selectedChar === 'podcaster') {
        // 🎙️ PODCASTER: Varsity jacket, mic wireless di tangan, kopi cup
        if (isDuck) {
            ctx.fillStyle = '#f59e0b';
            ctx.fillRect(px, py + 8, 38, 16);
            ctx.fillStyle = '#ef4444'; // Mic
            ctx.fillRect(px + 32, py + 10, 8, 8);
        } else {
            // Head
            ctx.fillStyle = '#fef08a';
            ctx.beginPath(); ctx.arc(px + 18, py + 8, 9, 0, Math.PI * 2); ctx.fill();
            // Varsity Jacket Green/White
            ctx.fillStyle = '#047857';
            ctx.fillRect(px + 10, py + 17, 16, 17);
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(px + 8, py + 19, 4, 10);

            // Wireless Mic in hand
            ctx.fillStyle = '#ef4444';
            ctx.fillRect(px + 24, py + 20, 6, 6);
            ctx.fillStyle = '#0f172a';
            ctx.fillRect(px + 25, py + 26, 4, 8);

            // Legs
            ctx.strokeStyle = '#334155';
            ctx.lineWidth = 4;
            ctx.beginPath(); ctx.moveTo(px + 14, py + 34); ctx.lineTo(px + 14 - legPhase * 10, py + 46); ctx.stroke();
            ctx.beginPath(); ctx.moveTo(px + 22, py + 34); ctx.lineTo(px + 22 + legPhase * 10, py + 46); ctx.stroke();
        }
    } else if (runnerState.selectedChar === 'drone') {
        // 🚁 PILOT DRONE: Goggles FPV di dahi, Techwear
        if (isDuck) {
            ctx.fillStyle = '#0284c7';
            ctx.fillRect(px, py + 8, 38, 16);
        } else {
            // Head & FPV Goggles
            ctx.fillStyle = '#fef08a';
            ctx.beginPath(); ctx.arc(px + 18, py + 8, 9, 0, Math.PI * 2); ctx.fill();
            ctx.fillStyle = '#000000'; // FPV Goggles
            ctx.fillRect(px + 14, py + 2, 12, 6);
            ctx.fillStyle = '#06b6d4'; // Goggles Lens
            ctx.fillRect(px + 16, py + 4, 8, 2);

            // Techwear Hoodie
            ctx.fillStyle = '#0369a1';
            ctx.fillRect(px + 10, py + 17, 16, 17);

            // Legs
            ctx.strokeStyle = '#0f172a';
            ctx.lineWidth = 4;
            ctx.beginPath(); ctx.moveTo(px + 14, py + 34); ctx.lineTo(px + 14 - legPhase * 10, py + 46); ctx.stroke();
            ctx.beginPath(); ctx.moveTo(px + 22, py + 34); ctx.lineTo(px + 22 + legPhase * 10, py + 46); ctx.stroke();
        }
    } else {
        // 🛹 SKATER: Skateboard di kaki
        ctx.fillStyle = '#fef08a';
        ctx.beginPath(); ctx.arc(px + 18, py + 8, 9, 0, Math.PI * 2); ctx.fill();
        ctx.fillStyle = '#dc2626'; // Red Cap
        ctx.fillRect(px + 10, py - 1, 16, 5);
        ctx.fillStyle = '#475569'; // Hoodie
        ctx.fillRect(px + 10, py + 17, 16, 17);

        // Skateboard Deck
        ctx.fillStyle = '#f59e0b';
        ctx.fillRect(px + 2, py + 43, 34, 4);
        ctx.fillStyle = '#000000'; // Wheels
        ctx.fillRect(px + 6, py + 47, 6, 4);
        ctx.fillRect(px + 26, py + 47, 6, 4);
    }

    ctx.restore();
}

function drawObstacle(ctx, obs) {
    ctx.save();
    const ox = obs.x;
    const oy = obs.y;

    if (obs.type === 'tripod') {
        // 📷 Tripod Kamera Stand
        ctx.strokeStyle = '#94a3b8';
        ctx.lineWidth = 2.5;
        // Legs
        ctx.beginPath();
        ctx.moveTo(ox + 14, oy + 18);
        ctx.lineTo(ox + 2, oy + 46);
        ctx.moveTo(ox + 14, oy + 18);
        ctx.lineTo(ox + 26, oy + 46);
        ctx.moveTo(ox + 14, oy + 18);
        ctx.lineTo(ox + 14, oy + 46);
        ctx.stroke();
        // Camera on top
        ctx.fillStyle = '#0f172a';
        ctx.fillRect(ox + 6, oy + 4, 16, 14);
        ctx.fillStyle = '#ef4444'; // Red recording dot
        ctx.beginPath(); ctx.arc(ox + 10, oy + 8, 2, 0, Math.PI * 2); ctx.fill();
        ctx.fillStyle = '#38bdf8'; // Lens
        ctx.fillRect(ox + 18, oy + 7, 6, 8);
    } else if (obs.type === 'cable_roll') {
        // 🔌 Kabel Roll Kusut
        ctx.fillStyle = '#f59e0b'; // Roll reel
        ctx.beginPath(); ctx.arc(ox + 17, oy + 13, 12, 0, Math.PI * 2); ctx.fill();
        ctx.fillStyle = '#000000';
        ctx.beginPath(); ctx.arc(ox + 17, oy + 13, 5, 0, Math.PI * 2); ctx.fill();
        ctx.strokeStyle = '#ef4444'; // Dangling cable
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.moveTo(ox + 6, oy + 16);
        ctx.quadraticCurveTo(ox + 2, oy + 26, ox - 4, oy + 24);
        ctx.stroke();
    } else if (obs.type === 'kucing_oren') {
        // 🐱 Kucing Oren Lagi Tidur
        ctx.fillStyle = '#f97316';
        ctx.beginPath(); ctx.ellipse(ox + 18, oy + 14, 16, 10, 0, 0, Math.PI * 2); ctx.fill();
        // Head & Ears
        ctx.beginPath(); ctx.arc(ox + 8, oy + 10, 7, 0, Math.PI * 2); ctx.fill();
        ctx.beginPath(); ctx.moveTo(ox + 4, oy + 5); ctx.lineTo(ox + 7, oy); ctx.lineTo(ox + 10, oy + 5); ctx.fill();
        // Tail
        ctx.strokeStyle = '#ea580c'; ctx.lineWidth = 3;
        ctx.beginPath(); ctx.moveTo(ox + 32, oy + 14); ctx.quadraticCurveTo(ox + 38, oy + 8, ox + 36, oy + 4); ctx.stroke();
    } else if (obs.type === 'snack_box') {
        // 📦 Dus Snack Rapat OSIS
        ctx.fillStyle = '#b45309';
        ctx.fillRect(ox + 2, oy + 24, 28, 26);
        ctx.fillStyle = '#d97706';
        ctx.fillRect(ox + 4, oy + 4, 24, 20);
        ctx.strokeStyle = '#ffffff'; ctx.lineWidth = 1.5;
        ctx.strokeRect(ox + 4, oy + 4, 24, 20);
        ctx.strokeRect(ox + 2, oy + 24, 28, 26);
    } else if (obs.type === 'drone_low') {
        // 🚁 Drone Liputan Terbang Rendah
        ctx.fillStyle = '#0f172a'; // Body
        ctx.fillRect(ox + 10, oy + 10, 24, 10);
        // Rotors
        ctx.strokeStyle = '#94a3b8'; ctx.lineWidth = 2;
        ctx.beginPath(); ctx.moveTo(ox + 2, oy + 8); ctx.lineTo(ox + 42, oy + 8); ctx.stroke();
        // LED Lights
        ctx.fillStyle = runnerState.animFrame % 10 < 5 ? '#22c55e' : '#ef4444';
        ctx.fillRect(ox + 8, oy + 18, 4, 4);
        ctx.fillRect(ox + 32, oy + 18, 4, 4);
    } else if (obs.type === 'boom_mic') {
        // 🎙️ Boom Pole Mic
        ctx.strokeStyle = '#64748b'; ctx.lineWidth = 4;
        ctx.beginPath(); ctx.moveTo(ox, oy + 8); ctx.lineTo(ox + 45, oy + 8); ctx.stroke();
        // Blimp Mic Windshield
        ctx.fillStyle = '#334155';
        ctx.beginPath(); ctx.ellipse(ox + 46, oy + 8, 10, 6, 0, 0, Math.PI * 2); ctx.fill();
    }

    ctx.restore();
}

function drawCollectible(ctx, item) {
    ctx.save();
    const bob = Math.sin(runnerState.animFrame * 0.1 + item.floatOffset) * 4;
    const ix = item.x;
    const iy = item.y + bob;

    if (item.type === 'sd_card') {
        // 💾 SD Card 128GB
        ctx.fillStyle = '#10b981';
        ctx.fillRect(ix, iy, 18, 22);
        ctx.fillStyle = '#000000';
        ctx.fillRect(ix + 2, iy + 2, 14, 8);
        ctx.fillStyle = '#fef08a'; // Gold pins
        for (let p = 0; p < 4; p++) ctx.fillRect(ix + 3 + p * 3, iy + 16, 2, 4);
    } else if (item.type === 'kopi_skena') {
        // ☕ Kopi Susu Cup
        ctx.fillStyle = '#fef08a'; // Plastic cup
        ctx.beginPath(); ctx.moveTo(ix + 2, iy); ctx.lineTo(ix + 18, iy); ctx.lineTo(ix + 15, iy + 22); ctx.lineTo(ix + 5, iy + 22); ctx.closePath(); ctx.fill();
        ctx.fillStyle = '#78350f'; // Coffee liquid
        ctx.fillRect(ix + 4, iy + 6, 12, 14);
        ctx.fillStyle = '#22c55e'; // Green straw
        ctx.fillRect(ix + 9, iy - 6, 2, 8);
    } else if (item.type === 'battery_shield') {
        // 🔋 Baterai Shield
        ctx.fillStyle = '#06b6d4';
        ctx.fillRect(ix, iy + 2, 20, 18);
        ctx.fillRect(ix + 20, iy + 7, 3, 8);
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(ix + 4, iy + 6, 12, 10);
    } else if (item.type === 'flash_bomb') {
        // 📸 Lensa Flash Bomb
        ctx.fillStyle = '#a855f7';
        ctx.beginPath(); ctx.arc(ix + 11, iy + 11, 10, 0, Math.PI * 2); ctx.fill();
        ctx.fillStyle = '#38bdf8';
        ctx.beginPath(); ctx.arc(ix + 11, iy + 11, 5, 0, Math.PI * 2); ctx.fill();
    }

    ctx.restore();
}

// --- HUD Updates ---
function updateHUD() {
    document.getElementById('hudDistance').innerText = `${Math.floor(runnerState.distance)} m`;
    document.getElementById('hudSDCards').innerText = `${runnerState.sdCards} SD`;
    document.getElementById('hudScore').innerText = `${runnerState.score} Pts`;
}

// --- Game Over Trigger ---
function triggerGameOver(reason) {
    runnerState.isRunning = false;
    if (runnerState.animId) cancelAnimationFrame(runnerState.animId);
    playRunnerSound('crash');

    document.getElementById('gameOverReasonText').innerText = `Tersandung oleh ${reason}!`;
    document.getElementById('overPlayerName').innerText = runnerState.playerName;
    document.getElementById('overDistance').innerText = `${Math.floor(runnerState.distance)}m`;
    document.getElementById('overSDCards').innerText = `${runnerState.sdCards}`;
    document.getElementById('overScore').innerText = `${runnerState.score}`;

    let rank = 'Pelari Pemula SMANIT 👟';
    if (runnerState.distance >= 1000) rank = 'Mahaguru Skena Tamansari 👑';
    else if (runnerState.distance >= 500) rank = 'Senior Kalcer MMC 2017 🎓';
    else if (runnerState.distance >= 250) rank = 'Fotografer Deadline Cepat ⚡';
    document.getElementById('overRankTitle').innerText = rank;

    document.getElementById('runnerGameOverOverlay').classList.remove('d-none');

    // Save Score to Local Storage
    saveScoreToRunnerLeaderboard(runnerState.playerName, runnerState.score, Math.floor(runnerState.distance), runnerState.sdCards, runnerState.selectedChar);

    // Save score to Backend AJAX
    fetch('<?= base_url('mini-game/api/record-score') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            game_id: 'pelari-kalcer',
            level: runnerState.distance >= 500 ? 3 : (runnerState.distance >= 250 ? 2 : 1),
            score: runnerState.score,
            stars: runnerState.distance >= 500 ? 3 : 2
        })
    }).catch(err => console.log(err));
}

// --- Leaderboard & Local Persistence ---
function saveScoreToRunnerLeaderboard(name, score, dist, sd, charName) {
    let board = JSON.parse(localStorage.getItem('mmc_runner_leaderboard') || '[]');
    board.push({
        name: name,
        score: score,
        distance: dist,
        sdCards: sd,
        char: charName.toUpperCase(),
        date: new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
    });

    board.sort((a, b) => b.score - a.score);
    board = board.slice(0, 5);

    localStorage.setItem('mmc_runner_leaderboard', JSON.stringify(board));
    renderRunnerLeaderboard();
}

function renderRunnerLeaderboard() {
    const board = JSON.parse(localStorage.getItem('mmc_runner_leaderboard') || '[]');
    const container = document.getElementById('runnerLeaderboardBody');

    if (board.length === 0) {
        container.innerHTML = `
            <div class="text-center text-secondary py-4">
                <i class="fa-solid fa-person-running fs-1 mb-2 text-muted"></i>
                <p class="small mb-0">Belum ada rekor lari tersimpan. Mainkan sekarang dan catat rekor jarakmu!</p>
            </div>
        `;
        return;
    }

    const medals = ['🥇', '🥈', '🥉', '4.', '5.'];
    let html = '<div class="d-flex flex-column gap-2">';

    board.forEach((item, idx) => {
        html += `
            <div class="p-2.5 px-3 rounded-3 bg-black border border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2.5">
                    <span class="fs-5">${medals[idx] || (idx + 1) + '.'}</span>
                    <div>
                        <strong class="text-white font-heading d-block small">${item.name}</strong>
                        <span class="style-tiny text-secondary font-monospace">${item.date} • ${item.char}</span>
                    </div>
                </div>
                <div class="text-end font-monospace">
                    <span class="fs-5 fw-bold text-success">${item.score} Pts</span>
                    <span class="style-tiny text-secondary d-block">${item.distance}m • ${item.sdCards} SD</span>
                </div>
            </div>
        `;
    });

    html += '</div>';
    container.innerHTML = html;
}

function clearRunnerLeaderboard() {
    if (confirm('Hapus seluruh riwayat rekor pelari di perangkat ini?')) {
        localStorage.removeItem('mmc_runner_leaderboard');
        renderRunnerLeaderboard();
    }
}

function saveRunnerModalSettings() {
    const audioSwitch = document.getElementById('modalRunnerAudioSwitch').checked;
    const particleSwitch = document.getElementById('modalRunnerParticlesSwitch').checked;
    runnerState.audioEnabled = audioSwitch;
    runnerState.particlesEnabled = particleSwitch;
    
    const icon = document.getElementById('runnerSoundIcon');
    icon.className = audioSwitch ? 'fa-solid fa-volume-high text-success' : 'fa-solid fa-volume-xmark text-danger';
    
    bootstrap.Modal.getInstance(document.getElementById('runnerSettingsModal')).hide();
}

// --- Keyboard & Touch Event Listeners ---
const keysPressed = {};
let isTouchDucking = false;

window.addEventListener('keydown', (e) => {
    keysPressed[e.code] = true;

    // Jump keys
    if (e.code === 'Space' || e.code === 'ArrowUp' || e.code === 'KeyW') {
        e.preventDefault();
        if (!runnerState.isRunning) {
            startRunnerGame();
        } else {
            playerJump();
        }
    }

    // Duck keys
    if (e.code === 'ArrowDown' || e.code === 'KeyS') {
        e.preventDefault();
        playerDuckStart();
    }

    // Pause key (P)
    if (e.code === 'KeyP') {
        e.preventDefault();
        toggleRunnerPause();
    }
});

window.addEventListener('keyup', (e) => {
    keysPressed[e.code] = false;
    if (e.code === 'ArrowDown' || e.code === 'KeyS') {
        playerDuckEnd();
    }
});

// Canvas Tap to Jump
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('runnerCanvas');
    if (canvas) {
        canvas.addEventListener('pointerdown', (e) => {
            if (!runnerState.isRunning) {
                startRunnerGame();
            } else {
                playerJump();
            }
        });
    }

    // Touch Buttons
    const jumpBtn = document.getElementById('touchJumpBtn');
    const duckBtn = document.getElementById('touchDuckBtn');

    if (jumpBtn) {
        jumpBtn.addEventListener('touchstart', (e) => {
            e.preventDefault();
            if (!runnerState.isRunning) startRunnerGame();
            else playerJump();
        });
        jumpBtn.addEventListener('mousedown', (e) => {
            if (!runnerState.isRunning) startRunnerGame();
            else playerJump();
        });
    }

    if (duckBtn) {
        duckBtn.addEventListener('touchstart', (e) => {
            e.preventDefault();
            isTouchDucking = true;
            playerDuckStart();
        });
        duckBtn.addEventListener('touchend', (e) => {
            e.preventDefault();
            isTouchDucking = false;
            playerDuckEnd();
        });
        duckBtn.addEventListener('mousedown', () => {
            isTouchDucking = true;
            playerDuckStart();
        });
        duckBtn.addEventListener('mouseup', () => {
            isTouchDucking = false;
            playerDuckEnd();
        });
    }

    // Character Selector Drag to Scroll (Mouse & Touch)
    const charGrid = document.getElementById('characterSelectorGrid');
    if (charGrid) {
        let isDown = false;
        let startX, scrollLeft;
        charGrid.addEventListener('mousedown', (e) => {
            isDown = true;
            startX = e.pageX - charGrid.offsetLeft;
            scrollLeft = charGrid.scrollLeft;
        });
        charGrid.addEventListener('mouseleave', () => { isDown = false; });
        charGrid.addEventListener('mouseup', () => { isDown = false; });
        charGrid.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - charGrid.offsetLeft;
            const walk = (x - startX) * 1.5;
            charGrid.scrollLeft = scrollLeft - walk;
        });
    }

    renderRunnerLeaderboard();
});
</script>
<?= $this->endSection() ?>
