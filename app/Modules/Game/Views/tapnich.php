<?= $this->extend('layouts/master_public') ?>

<?= $this->section('content') ?>
<style>
/* =========================================================
   TAPNICH: DRONE AERIAL - RESPONSIVE STYLES
   ========================================================= */
.tapnich-wrapper {
    background: #080d1a;
    border: 1px solid rgba(56, 189, 248, 0.2);
    border-radius: 20px;
    box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.8), 0 0 40px rgba(56, 189, 248, 0.12);
    overflow: hidden;
    position: relative;
}

.tapnich-topbar {
    background: #0d1527;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    padding: 8px 14px;
}

.game-canvas-container {
    position: relative;
    width: 100%;
    max-width: 800px;
    margin: 0 auto;
    aspect-ratio: 16 / 10;
    max-height: 520px;
    background: #050811;
    border-radius: 12px;
    overflow: hidden;
    user-select: none;
    -webkit-user-select: none;
    touch-action: none; /* Crucial for preventing pinch-zoom and scroll on mobile */
    box-shadow: inset 0 0 40px rgba(0, 0, 0, 0.9);
}

#tapnichCanvas {
    width: 100%;
    height: 100%;
    display: block;
    cursor: pointer;
}

/* In-Game HUD Overlay */
.game-hud-overlay {
    position: absolute;
    inset: 0;
    pointer-events: none;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 10px 12px;
    z-index: 10;
}

.hud-pill {
    background: rgba(10, 17, 34, 0.85);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(56, 189, 248, 0.3);
    padding: 5px 12px;
    border-radius: 999px;
    font-family: var(--bs-font-monospace, monospace);
    font-size: 0.82rem;
    font-weight: 700;
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
}

.hud-pill.gold {
    border-color: rgba(245, 158, 11, 0.45);
    color: #fbbf24;
}

.hud-pill.danger {
    border-color: rgba(239, 68, 68, 0.45);
    color: #ef4444;
}

/* Pre-Game & Game Over Glassmorphic Overlay */
.game-screen-overlay {
    position: absolute;
    inset: 0;
    background: rgba(6, 11, 24, 0.92);
    backdrop-filter: blur(12px);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 14px 16px;
    z-index: 20;
    text-align: center;
    overflow-y: auto;
    animation: fadeInOverlay 0.2s ease forwards;
}

@keyframes fadeInOverlay {
    from { opacity: 0; transform: scale(0.98); }
    to { opacity: 1; transform: scale(1); }
}

/* Control Mode Selector Pills */
.control-mode-pill {
    background: rgba(15, 23, 42, 0.9);
    border: 1.5px solid rgba(255, 255, 255, 0.12);
    color: #94a3b8;
    border-radius: 10px;
    padding: 6px 12px;
    font-size: 0.78rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    user-select: none;
}
.control-mode-pill:hover {
    border-color: rgba(56, 189, 248, 0.4);
    color: #ffffff;
}
.control-mode-pill.active {
    background: rgba(2, 132, 199, 0.25);
    border-color: #38bdf8;
    color: #38bdf8;
    box-shadow: 0 0 14px rgba(56, 189, 248, 0.3);
}

/* Character Drone Card Selection */
.drone-card-option {
    background: rgba(13, 22, 42, 0.8);
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: 14px;
    padding: 10px 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
}
.drone-card-option:hover {
    border-color: rgba(56, 189, 248, 0.5);
    transform: translateY(-2px);
}
.drone-card-option.active {
    border-color: #38bdf8;
    background: rgba(56, 189, 248, 0.15);
    box-shadow: 0 0 20px rgba(56, 189, 248, 0.3);
}

/* Mobile Quick Thrust Touch Pad Button */
.mobile-thrust-btn {
    width: 100%;
    padding: 12px;
    font-size: 1rem;
    font-weight: 800;
    border-radius: 12px;
    background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 16px rgba(2, 132, 199, 0.4);
    touch-action: manipulation;
    user-select: none;
    -webkit-user-select: none;
    transition: transform 0.08s ease, background 0.1s ease;
}
.mobile-thrust-btn:active, .mobile-thrust-btn.holding {
    transform: scale(0.97);
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    box-shadow: 0 0 20px rgba(245, 158, 11, 0.6);
}

/* Responsive Overrides for Mobile */
@media (max-width: 768px) {
    .game-canvas-container {
        aspect-ratio: 4 / 4.5;
        max-height: 420px;
        min-height: 320px;
    }
    .hud-pill {
        padding: 3px 8px;
        font-size: 0.70rem;
    }
    .game-screen-overlay {
        padding: 10px 12px;
    }
}
</style>

<section class="py-2 py-lg-4">
    <div class="container-fluid px-2 px-sm-3 px-lg-4 px-xl-5">
        
        <!-- Breadcrumb & Top Navigation -->
        <div class="d-flex align-items-center justify-content-between mb-2 mb-md-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>" class="text-secondary text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('mini-game') ?>" class="text-secondary text-decoration-none">Mini Game</a></li>
                    <li class="breadcrumb-item active text-warning fw-bold" aria-current="page">Tapnich Drone</li>
                </ol>
            </nav>
            <div class="d-flex gap-2">
                <a href="<?= base_url('mini-game') ?>" class="btn btn-sm btn-outline-secondary px-2.5 py-1">
                    <i class="fa-solid fa-arrow-left me-1"></i> <span class="d-none d-sm-inline">Katalog Game</span>
                </a>
            </div>
        </div>

        <!-- Header Section -->
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 mb-3">
            <div>
                <div class="d-flex align-items-center gap-1.5 mb-1 flex-wrap">
                    <span class="badge bg-warning text-dark font-monospace style-tiny fw-bold"><i class="fa-solid fa-helicopter me-1"></i> MINI GAME #3</span>
                    <span class="badge bg-body-secondary text-secondary border border-secondary border-opacity-50 style-tiny font-monospace">Videografi & Drone</span>
                    <span class="badge bg-info bg-opacity-20 text-info border border-info border-opacity-25 style-tiny font-monospace" id="headerModeBadge">Mode: TAP-TAP</span>
                </div>
                <h1 class="h4 h3-md fw-bold text-body font-heading mb-0">Tapnich: Drone Aerial</h1>
            </div>

            <!-- Header Action Controls -->
            <div class="d-flex align-items-center gap-1.5 w-100 w-md-auto justify-content-end">
                <button type="button" class="btn btn-sm btn-outline-info flex-grow-1 flex-md-grow-0 py-1" data-bs-toggle="modal" data-bs-target="#settingsModal">
                    <i class="fa-solid fa-gear me-1"></i> Pengaturan
                </button>
                <button type="button" class="btn btn-sm btn-outline-warning flex-grow-1 flex-md-grow-0 py-1" data-bs-toggle="modal" data-bs-target="#leaderboardModal">
                    <i class="fa-solid fa-trophy me-1"></i> Papan Skor
                </button>
            </div>
        </div>

        <!-- Main Game Shell Container -->
        <div class="tapnich-wrapper mb-4">
            
            <!-- Game Top Status Bar -->
            <div class="tapnich-topbar d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-info bg-opacity-20 text-info border border-info border-opacity-30 font-monospace style-tiny">
                        <i class="fa-solid fa-user me-1"></i> <strong class="text-white" id="topbarPilotName">Pilot MM</strong>
                    </span>
                    <span class="badge bg-secondary bg-opacity-25 text-white font-monospace style-tiny d-none d-sm-inline" id="topbarDifficultyBadge">
                        NORMAL (1.0x)
                    </span>
                    <span class="badge bg-primary bg-opacity-25 text-info font-monospace style-tiny" id="topbarControlBadge">
                        <i class="fa-solid fa-hand-pointer me-1"></i> TAP-TAP
                    </span>
                </div>

                <div class="d-flex align-items-center gap-1.5">
                    <!-- Sound Toggle Button -->
                    <button type="button" class="btn btn-sm btn-dark border border-secondary border-opacity-25 text-secondary p-1 px-2.5" id="soundToggleBtn" onclick="toggleAudio()" title="Mute / Unmute Suara">
                        <i class="fa-solid fa-volume-high text-info" id="soundIcon"></i>
                    </button>
                    <!-- Quick Restart Button -->
                    <button type="button" class="btn btn-sm btn-dark border border-secondary border-opacity-25 text-secondary p-1 px-2.5" onclick="resetAndStartGame()" title="Ulangi Permainan">
                        <i class="fa-solid fa-rotate-right text-warning"></i>
                    </button>
                </div>
            </div>

            <!-- Canvas View Area -->
            <div class="p-1.5 p-sm-2 p-md-3 bg-black d-flex justify-content-center">
                <div class="game-canvas-container" id="canvasContainer">
                    <canvas id="tapnichCanvas"></canvas>

                    <!-- Live HUD Elements Overlay -->
                    <div class="game-hud-overlay">
                        <!-- Top HUD: Live Score, Nyawa (Hearts) & Collectibles -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="hud-pill">
                                <i class="fa-solid fa-gauge-high text-info"></i>
                                <span>SKOR: <strong class="text-white" id="hudScore">0</strong></span>
                            </div>
                            <div class="hud-pill danger" id="hudHeartsPill" title="Nyawa Drone (5x Kesempatan)">
                                <span id="hudHearts" style="font-size: 0.82rem; letter-spacing: 1px;">❤️❤️❤️❤️❤️</span>
                            </div>
                            <div class="hud-pill gold">
                                <i class="fa-solid fa-film"></i>
                                <span>FOOTAGE: <strong id="hudFootage">0</strong></span>
                            </div>
                        </div>

                        <!-- Bottom HUD: Flight Distance & High Score -->
                        <div class="d-flex justify-content-between align-items-end style-tiny">
                            <div class="hud-pill">
                                <i class="fa-solid fa-route text-secondary"></i>
                                <span id="hudDistance">0 m</span>
                            </div>
                            <div class="hud-pill gold">
                                <i class="fa-solid fa-crown"></i>
                                <span>BEST: <strong id="hudBestScore">0</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- ==========================================
                         START MENU SCREEN OVERLAY (PRE-GAME)
                         ========================================== -->
                    <div class="game-screen-overlay" id="startScreenOverlay">
                        <div class="mb-2">
                            <div class="p-2 rounded-circle bg-warning text-dark fs-4 d-inline-flex mb-1 shadow">
                                <i class="fa-solid fa-helicopter"></i>
                            </div>
                            <h2 class="text-white font-heading fw-bold fs-5 mb-0">TAPNICH</h2>
                            <p class="text-secondary style-tiny mb-1">Terbangkan drone liputan Multimedia Club!</p>
                            <div class="d-inline-flex align-items-center gap-1.5 mb-1.5">
                                <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-40 style-tiny px-2 py-0.5">
                                    <i class="fa-solid fa-heart me-1"></i> 5x Nyawa
                                </span>
                                <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-40 style-tiny px-2 py-0.5">
                                    <i class="fa-solid fa-shield-halved me-1"></i> Grace Shield
                                </span>
                            </div>
                        </div>

                        <!-- Quick Pre-Flight Pilot Profile & Drone -->
                        <div class="bg-dark bg-opacity-75 p-2.5 rounded-3 border border-secondary border-opacity-30 max-w-md w-100 mb-2.5 text-start">
                            <!-- Control Mode Switcher -->
                            <div class="mb-2">
                                <label class="form-label style-tiny text-secondary font-monospace mb-1 d-flex align-items-center justify-content-between">
                                    <span>MODE KONTROL TERBANG:</span>
                                    <span class="text-info fw-bold" id="startModeLabel">Tap-Tap (Flappy)</span>
                                </label>
                                <div class="d-flex gap-2">
                                    <button type="button" class="control-mode-pill active flex-fill justify-content-center" id="modeBtn_tap" onclick="setControlMode('tap')">
                                        <i class="fa-solid fa-hand-pointer"></i> <span>Tap-Tap</span>
                                    </button>
                                    <button type="button" class="control-mode-pill flex-fill justify-content-center" id="modeBtn_hold" onclick="setControlMode('hold')">
                                        <i class="fa-solid fa-fingerprint"></i> <span>Hold (Tahan)</span>
                                    </button>
                                </div>
                            </div>

                            <div class="row g-2 align-items-center mb-1.5">
                                <div class="col-5">
                                    <label class="form-label style-tiny text-secondary font-monospace mb-0.5">NAMA PILOT:</label>
                                    <input type="text" id="inputPilotName" class="form-control form-control-sm bg-black text-white border-secondary border-opacity-50 font-monospace fw-bold py-1" value="Pilot MM" maxlength="16" placeholder="Nama...">
                                </div>
                                <div class="col-7">
                                    <label class="form-label style-tiny text-secondary font-monospace mb-0.5">PILIH DRONE:</label>
                                    <select id="selectDroneSkin" class="form-select form-select-sm bg-black text-info border-secondary border-opacity-50 font-monospace fw-semibold py-1" onchange="updateSelectedDroneSkin(this.value)">
                                        <option value="mavic" selected>🛸 DJI Mavic 4K</option>
                                        <option value="fpv">🚁 FPV Racing Flame</option>
                                        <option value="cinecam">📷 CineCam Gold</option>
                                        <option value="cyber">⚡ Cyber Drone</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-2 align-items-center mb-1.5">
                                <div class="col-6">
                                    <label class="form-label style-tiny text-secondary font-monospace mb-0.5">TINGKAT KESULITAN:</label>
                                    <select id="selectPregameDiff" class="form-select form-select-sm bg-black text-warning border-secondary border-opacity-50 font-monospace fw-semibold py-1" onchange="updatePregameDiff(this.value)">
                                        <option value="easy">🟢 Mudah / Santai</option>
                                        <option value="normal" selected>🟡 Normal (Standar)</option>
                                        <option value="hard">🔴 Hard / Pro</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label style-tiny text-secondary font-monospace mb-0.5">KECEPATAN (SPEED):</label>
                                    <select id="selectPregameSpeed" class="form-select form-select-sm bg-black text-info border-secondary border-opacity-50 font-monospace fw-semibold py-1" onchange="updatePregameSpeed(this.value)">
                                        <option value="0.75">🐢 0.75x (Super Lambat)</option>
                                        <option value="0.9">🍃 0.9x (Santai)</option>
                                        <option value="1.0" selected>🟢 1.0x (Normal)</option>
                                        <option value="1.25">⚡ 1.25x (Cepat)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Launch Button -->
                        <button type="button" class="btn btn-warning text-dark font-heading fw-bold px-4 py-2 shadow d-inline-flex align-items-center gap-2 mb-1.5" onclick="startGameSession()">
                            <i class="fa-solid fa-play"></i> TERBANGKAN SEKARANG
                        </button>
                        <div class="text-secondary style-tiny font-monospace" id="startControlHint">
                            Tap layar / Klik / <kbd class="bg-dark text-warning border border-secondary px-1">Spasi</kbd>
                        </div>
                    </div>

                    <!-- ==========================================
                         GAME OVER SCREEN OVERLAY (NO ALERT)
                         ========================================== -->
                    <div class="game-screen-overlay d-none" id="gameOverOverlay">
                        <div class="mb-2">
                            <div class="p-2 rounded-circle bg-danger bg-opacity-20 text-danger fs-3 d-inline-flex mb-1 border border-danger border-opacity-30">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <h3 class="text-white font-heading fw-bold fs-5 mb-0">SIGNAL LOST / CRASHED</h3>
                            <p class="text-secondary style-tiny mb-2">Drone menabrak rintangan studio liputan!</p>
                        </div>

                        <!-- Game Stats Card -->
                        <div class="bg-dark bg-opacity-85 p-2.5 rounded-3 border border-secondary border-opacity-30 max-w-md w-100 mb-2.5">
                            <div class="d-flex justify-content-between align-items-center mb-1.5 pb-1.5 border-bottom border-secondary border-opacity-25">
                                <span class="text-secondary style-tiny">Pilot: <strong class="text-white" id="overPilotName">Pilot MM</strong></span>
                                <span class="badge bg-warning text-dark font-monospace fw-bold style-tiny" id="overRankTitle">Broadcast Cameraman</span>
                            </div>

                            <div class="row g-1.5 text-center my-1">
                                <div class="col-4">
                                    <div class="p-1.5 rounded-2 bg-black border border-secondary border-opacity-25">
                                        <div class="style-tiny text-secondary">SKOR</div>
                                        <div class="fs-5 font-heading fw-bold text-white" id="overFinalScore">0</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-1.5 rounded-2 bg-black border border-secondary border-opacity-25">
                                        <div class="style-tiny text-warning">FOOTAGE</div>
                                        <div class="fs-5 font-heading fw-bold text-warning" id="overFootageCount">0</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-1.5 rounded-2 bg-black border border-secondary border-opacity-25">
                                        <div class="style-tiny text-info">JARAK</div>
                                        <div class="fs-5 font-heading fw-bold text-info" id="overDistance">0m</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-1.5 style-tiny text-secondary d-flex justify-content-between align-items-center">
                                <span>Rekor Terbaik:</span>
                                <span class="font-monospace text-warning fw-bold" id="overBestScore">0 Pts</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            <button type="button" class="btn btn-sm btn-warning text-dark font-heading fw-bold px-3 py-2 shadow d-inline-flex align-items-center gap-1.5" onclick="resetAndStartGame()">
                                <i class="fa-solid fa-rotate-right"></i> Main Lagi
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary text-white px-3 py-2" onclick="showStartScreen()">
                                <i class="fa-solid fa-gear me-1"></i> Pengaturan
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Mobile Quick Tap / Hold Thrust Control Button -->
            <div class="p-2.5 bg-dark border-top border-secondary border-opacity-25 d-block d-md-none">
                <button type="button" class="mobile-thrust-btn d-flex align-items-center justify-content-center gap-2" id="mobileThrustBtn">
                    <i class="fa-solid fa-arrow-up-from-bracket" id="mobileThrustIcon"></i>
                    <span id="mobileThrustLabel">TAP UNTUK NAIK (THRUST)</span>
                </button>
            </div>

        </div>

        <!-- =========================================================
             EDUCATIONAL SECTION: DRONE PILOT & AERIAL CINEMATOGRAPHY
             ========================================================= -->
        <div class="saas-card saas-card-glow border border-secondary border-opacity-25 p-3 p-md-4 mb-4 rounded-4">
            
            <div class="text-center max-w-3xl mx-auto mb-3 mb-md-4">
                <span class="badge bg-warning text-dark font-monospace px-3 py-1 mb-2 fw-bold">MATERI DIVISI VIDEOGRAFI & DRONE</span>
                <h2 class="h3 fw-bold text-body font-heading mb-1">Panduan Pengoperasian Drone & Sinematografi</h2>
                <p class="text-secondary small mb-0">
                    Memahami manuver spasial, manajemen ketinggian, sudut kamera (*gimbal pitch*), dan keamanan terbang saat liputan event Multimedia Club.
                </p>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="p-3 rounded-4 bg-body-secondary h-100 border border-secondary border-opacity-25">
                        <div class="guide-icon-avatar bg-gradient-amber mb-2">
                            <i class="fa-solid fa-helicopter"></i>
                        </div>
                        <h6 class="text-body font-heading fw-bold mb-1">1. Kontrol Tap vs Hold</h6>
                        <p class="text-secondary small mb-0">
                            <strong>Tap-Tap:</strong> Memberikan dorongan impuls instan (gaya Flappy).<br>
                            <strong>Hold:</strong> Memberikan dorongan baling-baling kontinu saat ditahan untuk ketinggian yang lebih presisi dan stabil.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-3 rounded-4 bg-body-secondary h-100 border border-secondary border-opacity-25">
                        <div class="guide-icon-avatar bg-gradient-blue mb-2">
                            <i class="fa-solid fa-film"></i>
                        </div>
                        <h6 class="text-body font-heading fw-bold mb-1">2. Pengambilan Footage Udara</h6>
                        <p class="text-secondary small mb-0">
                            Item rol film dan SD Card merepresentasikan footage liputan (*B-Roll / Master Shot*) yang wajib direkam pilot drone untuk memperkaya video dokumentasi.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-3 rounded-4 bg-body-secondary h-100 border border-secondary border-opacity-25">
                        <div class="guide-icon-avatar bg-gradient-green mb-2">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <h6 class="text-body font-heading fw-bold mb-1">3. Protokol Keamanan (Safety)</h6>
                        <p class="text-secondary small mb-0">
                            Pilot dilarang menerbangkan drone di luar batas aman. Selalu perhatikan jarak bebas minimal 2-3 meter dari struktur tiang panggung & rigging lighting.
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- =========================================================
     MODAL 1: GAME SETTINGS & CUSTOMIZATION
     ========================================================= -->
<div class="modal fade" id="settingsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border border-info border-opacity-50 text-white rounded-4 shadow-2xl p-3 p-md-4">
            <div class="modal-header border-bottom border-secondary border-opacity-25 pb-2.5">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-sliders text-info fs-5"></i>
                    <div>
                        <h5 class="modal-title font-heading fw-bold fs-6">Pengaturan Game Tapnich</h5>
                        <div class="text-secondary style-tiny">Sesuaikan mode kontrol, kesulitan, dan karakter drone</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body py-3">
                <!-- Control Mode Selector in Modal -->
                <div class="mb-3">
                    <label class="form-label small text-secondary font-monospace mb-1">MODE KONTROL TERBANG:</label>
                    <div class="d-flex gap-2">
                        <button type="button" class="control-mode-pill active flex-fill justify-content-center" id="modalMode_tap" onclick="setControlMode('tap')">
                            <i class="fa-solid fa-hand-pointer"></i> <span>Tap-Tap (Impulse)</span>
                        </button>
                        <button type="button" class="control-mode-pill flex-fill justify-content-center" id="modalMode_hold" onclick="setControlMode('hold')">
                            <i class="fa-solid fa-fingerprint"></i> <span>Hold (Continuous Thrust)</span>
                        </button>
                    </div>
                </div>

                <!-- Pilot Name -->
                <div class="mb-3">
                    <label class="form-label small text-secondary font-monospace mb-1">NAMA PILOT:</label>
                    <input type="text" id="modalPilotName" class="form-control form-control-sm bg-black text-white border-secondary border-opacity-50 font-monospace fw-bold" maxlength="16" value="Pilot MM">
                </div>

                <!-- Difficulty Selector -->
                <div class="mb-3">
                    <label class="form-label small text-secondary font-monospace mb-1">TINGKAT KESULITAN (DIFFICULTY):</label>
                    <select id="modalDifficulty" class="form-select form-select-sm bg-black text-warning border-secondary border-opacity-50 font-monospace fw-bold">
                        <option value="easy">🟢 Easy (Celah Luas, Gravitasi Santai)</option>
                        <option value="normal" selected>🟡 Normal (Standar Siaran Broadcast)</option>
                        <option value="hard">🔴 Hard / Pro (Celah Sempit, Pilar Bergerak)</option>
                    </select>
                </div>

                <!-- Speed Multiplier -->
                <div class="mb-3">
                    <label class="form-label small text-secondary font-monospace mb-1">KECEPATAN GAME (SPEED):</label>
                    <select id="modalSpeed" class="form-select form-select-sm bg-black text-info border-secondary border-opacity-50 font-monospace fw-bold">
                        <option value="0.75">🐢 0.75x - Super Lambat & Ramah</option>
                        <option value="0.9">🍃 0.9x - Casual Relaxed</option>
                        <option value="1.0" selected>🟢 1.0x - Normal Speed</option>
                        <option value="1.25">⚡ 1.25x - Fast Reflex</option>
                        <option value="1.5">🔥 1.5x - Hyper Pro</option>
                    </select>
                </div>

                <!-- Drone Skin -->
                <div class="mb-2">
                    <label class="form-label small text-secondary font-monospace mb-1">KARAKTER DRONE:</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="drone-card-option active" id="skinOpt_mavic" onclick="selectSkinFromModal('mavic')">
                                <div class="fs-5 mb-0.5">🛸</div>
                                <strong class="small d-block text-white">DJI Mavic 4K</strong>
                                <span class="style-tiny text-secondary">Balanced Pilot</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="drone-card-option" id="skinOpt_fpv" onclick="selectSkinFromModal('fpv')">
                                <div class="fs-5 mb-0.5">🚁</div>
                                <strong class="small d-block text-white">FPV Racing</strong>
                                <span class="style-tiny text-warning">Acro Flame</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="drone-card-option" id="skinOpt_cinecam" onclick="selectSkinFromModal('cinecam')">
                                <div class="fs-5 mb-0.5">📷</div>
                                <strong class="small d-block text-white">CineCam Pod</strong>
                                <span class="style-tiny text-info">Gold Lens</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="drone-card-option" id="skinOpt_cyber" onclick="selectSkinFromModal('cyber')">
                                <div class="fs-5 mb-0.5">⚡</div>
                                <strong class="small d-block text-white">Cyber Bot</strong>
                                <span class="style-tiny text-purple">Electric Aura</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-top border-secondary border-opacity-25 pt-2.5">
                <button type="button" class="btn btn-sm btn-outline-secondary px-3 text-white" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-sm btn-primary font-heading fw-bold px-4" onclick="saveSettingsModal()">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan & Terapkan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     MODAL 2: LOCAL LEADERBOARD & RECAP
     ========================================================= -->
<div class="modal fade" id="leaderboardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border border-warning border-opacity-50 text-white rounded-4 shadow-2xl p-3 p-md-4">
            <div class="modal-header border-bottom border-secondary border-opacity-25 pb-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-trophy text-warning fs-4"></i>
                    <div>
                        <h5 class="modal-title font-heading fw-bold">Papan Rekor Pilot Tapnich</h5>
                        <div class="text-secondary style-tiny">Top rekor penerbangan drone terbaik di perangkat ini</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body py-3" id="leaderboardBody">
                <div class="text-center text-secondary py-3">Memuat skor...</div>
            </div>

            <div class="modal-footer border-top border-secondary border-opacity-25 pt-3">
                <button type="button" class="btn btn-sm btn-outline-danger me-auto" onclick="clearLeaderboard()">
                    <i class="fa-solid fa-trash me-1"></i> Reset Rekor
                </button>
                <button type="button" class="btn btn-warning text-dark font-heading fw-bold px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     TAPNICH HTML5 CANVAS JAVASCRIPT GAME ENGINE
     ========================================================= -->
<script>
// --- Game State & Configurations ---
const canvas = document.getElementById('tapnichCanvas');
const ctx = canvas.getContext('2d');
const container = document.getElementById('canvasContainer');
const mobileThrustBtn = document.getElementById('mobileThrustBtn');

let gameConfig = {
    pilotName: localStorage.getItem('mmc_tapnich_name') || 'Pilot MM',
    droneSkin: localStorage.getItem('mmc_tapnich_skin') || 'mavic',
    difficulty: localStorage.getItem('mmc_tapnich_diff') || 'normal',
    controlMode: localStorage.getItem('mmc_tapnich_mode') || 'tap', // 'tap' | 'hold'
    speedMult: parseFloat(localStorage.getItem('mmc_tapnich_speed')) || 1.0,
    audioEnabled: localStorage.getItem('mmc_tapnich_audio') !== 'false'
};

// Physics parameters based on difficulty & mode (tuned for smooth, enjoyable, casual gameplay)
const DIFF_SETTINGS = {
    easy:   { gravity: 0.20, jumpForce: -5.2, holdThrust: -0.50, maxClimb: -4.5, gap: 215, obstacleSpeed: 1.4, spawnInterval: 165 },
    normal: { gravity: 0.26, jumpForce: -5.9, holdThrust: -0.62, maxClimb: -5.2, gap: 185, obstacleSpeed: 1.8, spawnInterval: 140 },
    hard:   { gravity: 0.34, jumpForce: -6.8, holdThrust: -0.78, maxClimb: -6.2, gap: 160, obstacleSpeed: 2.3, spawnInterval: 115 }
};

let gameState = 'START'; // 'START', 'PLAYING', 'GAMEOVER', 'PAUSED'
let isThrusting = false;
let animationFrameId = null;
let lastTime = 0;
let frameCount = 0;

// Game Metrics
let maxHearts = 5;
let hearts = 5;
let invincibilityTimer = 0;
let score = 0;
let footageCount = 0;
let distanceMeters = 0;
let bestScore = parseInt(localStorage.getItem('mmc_tapnich_highscore') || '0');

// Entity collections
let drone = {
    x: 100,
    y: 200,
    width: 44,
    height: 30,
    vy: 0,
    rotation: 0,
    rotorAngle: 0
};

let obstacles = [];
let collectibles = [];
let particles = [];
let floatingTexts = [];

// Parallax City & Studio Background Layers
let bgOffset1 = 0;
let bgOffset2 = 0;
let bgOffset3 = 0;

// --- Synthesized Web Audio API Engine ---
let audioCtx = null;

function initAudioContext() {
    if (!audioCtx) {
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        audioCtx = new AudioContext();
    }
    if (audioCtx.state === 'suspended') {
        audioCtx.resume();
    }
}

function playSound(type) {
    if (!gameConfig.audioEnabled) return;
    try {
        initAudioContext();
        const now = audioCtx.currentTime;

        if (type === 'thrust') {
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = 'sawtooth';
            osc.frequency.setValueAtTime(140, now);
            osc.frequency.exponentialRampToValueAtTime(260, now + 0.10);
            gain.gain.setValueAtTime(0.10, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.10);
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start(now);
            osc.stop(now + 0.10);
        } else if (type === 'hit') {
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = 'sawtooth';
            osc.frequency.setValueAtTime(260, now);
            osc.frequency.exponentialRampToValueAtTime(70, now + 0.22);
            gain.gain.setValueAtTime(0.28, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.22);
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start(now);
            osc.stop(now + 0.22);
        } else if (type === 'collect') {
            // Arpeggio chime
            [523.25, 659.25, 783.99, 1046.50].forEach((freq, i) => {
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, now + i * 0.04);
                gain.gain.setValueAtTime(0.14, now + i * 0.04);
                gain.gain.exponentialRampToValueAtTime(0.001, now + i * 0.04 + 0.18);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start(now + i * 0.04);
                osc.stop(now + i * 0.04 + 0.18);
            });
        } else if (type === 'crash') {
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(180, now);
            osc.frequency.exponentialRampToValueAtTime(40, now + 0.35);
            gain.gain.setValueAtTime(0.3, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.35);
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start(now);
            osc.stop(now + 0.35);
        } else if (type === 'milestone') {
            [440, 554.37, 659.25].forEach((freq, i) => {
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, now + i * 0.08);
                gain.gain.setValueAtTime(0.2, now + i * 0.08);
                gain.gain.exponentialRampToValueAtTime(0.001, now + i * 0.08 + 0.3);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start(now + i * 0.08);
                osc.stop(now + i * 0.08 + 0.3);
            });
        }
    } catch (e) {
        // Audio policy ignore
    }
}

function toggleAudio() {
    gameConfig.audioEnabled = !gameConfig.audioEnabled;
    localStorage.setItem('mmc_tapnich_audio', gameConfig.audioEnabled);
    updateAudioUI();
}

function updateAudioUI() {
    const icon = document.getElementById('soundIcon');
    if (gameConfig.audioEnabled) {
        icon.className = 'fa-solid fa-volume-high text-info';
    } else {
        icon.className = 'fa-solid fa-volume-xmark text-danger';
    }
}

// --- Control Mode Setting (Tap-Tap vs Hold) ---
function setControlMode(mode) {
    gameConfig.controlMode = mode === 'hold' ? 'hold' : 'tap';
    localStorage.setItem('mmc_tapnich_mode', gameConfig.controlMode);

    // Update active pill styling on Start Screen & Modal
    const isHold = gameConfig.controlMode === 'hold';
    document.getElementById('modeBtn_tap')?.classList.toggle('active', !isHold);
    document.getElementById('modeBtn_hold')?.classList.toggle('active', isHold);
    document.getElementById('modalMode_tap')?.classList.toggle('active', !isHold);
    document.getElementById('modalMode_hold')?.classList.toggle('active', isHold);

    // Update UI Badges & Hints
    const modeLabelText = isHold ? 'Hold (Continuous Thrust)' : 'Tap-Tap (Flappy)';
    const modeBadgeText = isHold ? 'HOLD' : 'TAP-TAP';
    const controlHint = isHold 
        ? 'Tahan layar / Tahan Klik / Tahan <kbd class="bg-dark text-warning border border-secondary px-1">Spasi</kbd>'
        : 'Tap layar / Klik / <kbd class="bg-dark text-warning border border-secondary px-1">Spasi</kbd>';
    const mobileBtnLabel = isHold ? 'TAHAN UNTUK NAIK (HOLD)' : 'TAP UNTUK NAIK (JUMP)';
    const mobileBtnIcon = isHold ? 'fa-solid fa-fingerprint' : 'fa-solid fa-arrow-up-from-bracket';

    const startModeLabel = document.getElementById('startModeLabel');
    if (startModeLabel) startModeLabel.innerText = modeLabelText;

    const startControlHint = document.getElementById('startControlHint');
    if (startControlHint) startControlHint.innerHTML = controlHint;

    const headerModeBadge = document.getElementById('headerModeBadge');
    if (headerModeBadge) headerModeBadge.innerText = `Mode: ${modeBadgeText}`;

    const topbarControlBadge = document.getElementById('topbarControlBadge');
    if (topbarControlBadge) {
        topbarControlBadge.innerHTML = `<i class="${isHold ? 'fa-solid fa-fingerprint' : 'fa-solid fa-hand-pointer'} me-1"></i> ${modeBadgeText}`;
    }

    const thrustLabel = document.getElementById('mobileThrustLabel');
    if (thrustLabel) thrustLabel.innerText = mobileBtnLabel;

    const thrustIcon = document.getElementById('mobileThrustIcon');
    if (thrustIcon) thrustIcon.className = `${mobileBtnIcon} me-1`;
}

// --- Canvas Resizing & High-DPI Crispness ---
function resizeCanvas() {
    const rect = container.getBoundingClientRect();
    const dpr = window.devicePixelRatio || 1;
    canvas.width = rect.width * dpr;
    canvas.height = rect.height * dpr;
    ctx.scale(dpr, dpr);
}

window.addEventListener('resize', resizeCanvas);

// --- Game Loop Engine ---
function initGame() {
    resizeCanvas();
    updateAudioUI();
    setControlMode(gameConfig.controlMode);
    document.getElementById('hudBestScore').innerText = bestScore;

    // Load saved settings to input fields
    document.getElementById('inputPilotName').value = gameConfig.pilotName;
    document.getElementById('modalPilotName').value = gameConfig.pilotName;
    document.getElementById('selectDroneSkin').value = gameConfig.droneSkin;
    document.getElementById('modalDifficulty').value = gameConfig.difficulty;
    document.getElementById('modalSpeed').value = gameConfig.speedMult.toString();
    
    if (document.getElementById('selectPregameDiff')) {
        document.getElementById('selectPregameDiff').value = gameConfig.difficulty;
    }
    if (document.getElementById('selectPregameSpeed')) {
        document.getElementById('selectPregameSpeed').value = gameConfig.speedMult.toString();
    }

    document.getElementById('topbarPilotName').innerText = gameConfig.pilotName;

    updatePregameInfo();
    renderLeaderboardModal();

    // Initial render background
    drawScene();
}

function updatePregameInfo() {
    const diffMap = { easy: 'Mudah', normal: 'Normal', hard: 'Hard / Pro' };
    const diffLabel = diffMap[gameConfig.difficulty] || 'Normal';
    
    if (document.getElementById('selectPregameDiff')) {
        document.getElementById('selectPregameDiff').value = gameConfig.difficulty;
    }
    if (document.getElementById('selectPregameSpeed')) {
        document.getElementById('selectPregameSpeed').value = gameConfig.speedMult.toString();
    }
    if (document.getElementById('modalDifficulty')) {
        document.getElementById('modalDifficulty').value = gameConfig.difficulty;
    }
    if (document.getElementById('modalSpeed')) {
        document.getElementById('modalSpeed').value = gameConfig.speedMult.toString();
    }

    document.getElementById('topbarDifficultyBadge').innerText = `${(gameConfig.difficulty).toUpperCase()} (${gameConfig.speedMult}x)`;
}

function startGameSession() {
    initAudioContext();
    const nameInput = document.getElementById('inputPilotName').value.trim();
    if (nameInput) {
        gameConfig.pilotName = nameInput;
        localStorage.setItem('mmc_tapnich_name', gameConfig.pilotName);
        document.getElementById('topbarPilotName').innerText = gameConfig.pilotName;
    }

    document.getElementById('startScreenOverlay').classList.add('d-none');
    document.getElementById('gameOverOverlay').classList.add('d-none');

    resetGameState();
    gameState = 'PLAYING';
    lastTime = performance.now();
    if (!animationFrameId) {
        animationFrameId = requestAnimationFrame(gameLoop);
    }
}

function showStartScreen() {
    gameState = 'START';
    isThrusting = false;
    document.getElementById('gameOverOverlay').classList.add('d-none');
    document.getElementById('startScreenOverlay').classList.remove('d-none');
    drawScene();
}

function resetGameState() {
    const rect = container.getBoundingClientRect();
    drone.x = rect.width * 0.22;
    drone.y = rect.height * 0.45;
    drone.vy = 0;
    drone.rotation = 0;
    drone.rotorAngle = 0;

    hearts = maxHearts;
    invincibilityTimer = 0;

    isThrusting = false;
    obstacles = [];
    collectibles = [];
    particles = [];
    floatingTexts = [];
    frameCount = 0;

    score = 0;
    footageCount = 0;
    distanceMeters = 0;

    updateHUD();
}

function resetAndStartGame() {
    startGameSession();
}

// --- Thrust Inputs Handling (Tap vs Hold) ---
function handleThrustStart(e) {
    if (e && e.cancelable) e.preventDefault();

    if (gameState === 'START') {
        startGameSession();
        return;
    }

    if (gameState === 'GAMEOVER') {
        resetAndStartGame();
        return;
    }

    if (gameState === 'PLAYING') {
        isThrusting = true;
        mobileThrustBtn?.classList.add('holding');
        const diff = DIFF_SETTINGS[gameConfig.difficulty] || DIFF_SETTINGS.normal;

        if (gameConfig.controlMode === 'tap') {
            // Tap-tap impulse
            drone.vy = diff.jumpForce;
            playSound('thrust');
            createThrustParticles();
        } else {
            // Hold mode initial burst
            drone.vy = Math.min(drone.vy, 0) + (diff.holdThrust * 2.5);
            playSound('thrust');
            createThrustParticles();
        }
    }
}

function handleThrustEnd(e) {
    if (e && e.cancelable) e.preventDefault();
    isThrusting = false;
    mobileThrustBtn?.classList.remove('holding');
}

// Input Event Listeners for Space, W, and Arrow Keys
window.addEventListener('keydown', (e) => {
    if (e.code === 'Space' || e.code === 'ArrowUp' || e.code === 'KeyW') {
        if (!e.repeat) {
            handleThrustStart(e);
        }
    }
});

window.addEventListener('keyup', (e) => {
    if (e.code === 'Space' || e.code === 'ArrowUp' || e.code === 'KeyW') {
        handleThrustEnd(e);
    }
});

// Canvas Direct Touch / Click Listeners
canvas.addEventListener('pointerdown', (e) => {
    handleThrustStart(e);
});
canvas.addEventListener('pointerup', (e) => {
    handleThrustEnd(e);
});
canvas.addEventListener('pointercancel', (e) => {
    handleThrustEnd(e);
});
canvas.addEventListener('pointerleave', (e) => {
    handleThrustEnd(e);
});

// Mobile Bottom Button Touch Listeners
if (mobileThrustBtn) {
    mobileThrustBtn.addEventListener('pointerdown', (e) => {
        handleThrustStart(e);
    });
    mobileThrustBtn.addEventListener('pointerup', (e) => {
        handleThrustEnd(e);
    });
    mobileThrustBtn.addEventListener('pointercancel', (e) => {
        handleThrustEnd(e);
    });
    mobileThrustBtn.addEventListener('pointerleave', (e) => {
        handleThrustEnd(e);
    });
}

// --- Particle Systems ---
function createThrustParticles() {
    const colors = {
        mavic: ['#38bdf8', '#0284c7', '#e0f2fe'],
        fpv: ['#f97316', '#ef4444', '#fbbf24'],
        cinecam: ['#fbbf24', '#f59e0b', '#ffffff'],
        cyber: ['#a855f7', '#06b6d4', '#e879f9']
    };
    const palette = colors[gameConfig.droneSkin] || colors.mavic;

    for (let i = 0; i < 5; i++) {
        particles.push({
            x: drone.x - 8,
            y: drone.y + (Math.random() * 8 - 4),
            vx: -2.5 - Math.random() * 3,
            vy: (Math.random() - 0.5) * 2,
            size: Math.random() * 4 + 2,
            color: palette[Math.floor(Math.random() * palette.length)],
            alpha: 1,
            life: 0.9
        });
    }
}

function createCollectParticles(x, y) {
    for (let i = 0; i < 16; i++) {
        const angle = (Math.PI * 2 * i) / 16;
        const speed = Math.random() * 3 + 2;
        particles.push({
            x: x,
            y: y,
            vx: Math.cos(angle) * speed,
            vy: Math.sin(angle) * speed,
            size: Math.random() * 3 + 2,
            color: '#fbbf24',
            alpha: 1,
            life: 0.95
        });
    }
}

function addFloatingScore(x, y, text, color = '#fbbf24') {
    floatingTexts.push({
        x: x,
        y: y,
        text: text,
        color: color,
        alpha: 1,
        vy: -1.2
    });
}

// --- Spawning Obstacles & Collectibles ---
function spawnObstacle() {
    const rect = container.getBoundingClientRect();
    const diff = DIFF_SETTINGS[gameConfig.difficulty] || DIFF_SETTINGS.normal;
    
    const minHeight = 50;
    const maxAvailable = rect.height - diff.gap - (minHeight * 2);
    const topHeight = minHeight + Math.random() * maxAvailable;
    const bottomY = topHeight + diff.gap;
    const bottomHeight = rect.height - bottomY;

    // Obstacle object
    obstacles.push({
        x: rect.width + 20,
        topHeight: topHeight,
        bottomY: bottomY,
        bottomHeight: bottomHeight,
        width: 50,
        passed: false,
        isMoving: gameConfig.difficulty === 'hard' && Math.random() > 0.4,
        moveDir: 1,
        initialTop: topHeight
    });

    // 65% chance to spawn a collectible item (Film Reel / SD Card) between pillars
    if (Math.random() < 0.65) {
        collectibles.push({
            x: rect.width + 20 + 25, // Center between obstacle width
            y: topHeight + (diff.gap / 2) + (Math.random() * 26 - 13),
            size: 13,
            type: Math.random() > 0.3 ? 'film' : 'sdcard',
            rotation: 0,
            collected: false
        });
    }
}

// --- Main Game Update Logic ---
function gameLoop(timestamp) {
    const dt = (timestamp - lastTime) / 1000;
    lastTime = timestamp;

    if (gameState === 'PLAYING') {
        updateGamePhysics();
    }

    drawScene();
    animationFrameId = requestAnimationFrame(gameLoop);
}

function updateGamePhysics() {
    const rect = container.getBoundingClientRect();
    const diff = DIFF_SETTINGS[gameConfig.difficulty] || DIFF_SETTINGS.normal;
    const speed = diff.obstacleSpeed * gameConfig.speedMult;

    frameCount++;

    if (invincibilityTimer > 0) {
        invincibilityTimer--;
    }

    // 1. Update Drone Flight Mechanics (Tap vs Hold)
    if (gameConfig.controlMode === 'hold' && isThrusting) {
        // Continuous upward thrust
        drone.vy += diff.holdThrust;
        drone.vy = Math.max(drone.vy, diff.maxClimb);

        if (frameCount % 3 === 0) {
            createThrustParticles();
        }
        if (frameCount % 8 === 0) {
            playSound('thrust');
        }
    } else {
        // Natural downward gravity
        drone.vy += diff.gravity;
    }

    drone.y += drone.vy;
    drone.rotorAngle += isThrusting ? 0.75 : 0.45;

    // Target rotation based on velocity
    const targetRot = Math.min(Math.max(drone.vy * 0.05, -0.42), 0.65);
    drone.rotation += (targetRot - drone.rotation) * 0.18;

    // Background parallax scrolling
    bgOffset1 = (bgOffset1 + speed * 0.2) % rect.width;
    bgOffset2 = (bgOffset2 + speed * 0.5) % rect.width;
    bgOffset3 = (bgOffset3 + speed * 0.9) % rect.width;

    // Distance & metric increment
    distanceMeters += Math.round(speed * 0.8);
    if (frameCount % Math.round(diff.spawnInterval / gameConfig.speedMult) === 0) {
        spawnObstacle();
    }

    // Boundary check (Ceiling & Floor crash)
    if (drone.y < 8) {
        drone.y = 8;
        drone.vy = 0;
    }
    if (drone.y + drone.height > rect.height - 10) {
        drone.y = rect.height - 10 - drone.height;
        takeDamage('floor');
        if (gameState !== 'PLAYING') return;
    }

    // 2. Update & Check Obstacles
    for (let i = obstacles.length - 1; i >= 0; i--) {
        const obs = obstacles[i];
        obs.x -= speed;

        // Dynamic hard mode movement
        if (obs.isMoving) {
            obs.topHeight += obs.moveDir * 0.8;
            obs.bottomY = obs.topHeight + diff.gap;
            if (obs.topHeight > obs.initialTop + 35 || obs.topHeight < obs.initialTop - 35) {
                obs.moveDir *= -1;
            }
        }

        // Score passed obstacle
        if (!obs.passed && obs.x + obs.width < drone.x) {
            obs.passed = true;
            score += 10;
            updateHUD();
            if (score % 100 === 0) playSound('milestone');
        }

        // Collision detection (AABB with circle margin - forgiving buffer)
        const hitMargin = 9;
        const droneBox = {
            left: drone.x + hitMargin,
            right: drone.x + drone.width - hitMargin,
            top: drone.y + hitMargin,
            bottom: drone.y + drone.height - hitMargin
        };

        // Top pillar collision
        if (droneBox.right > obs.x && droneBox.left < obs.x + obs.width && droneBox.top < obs.topHeight) {
            takeDamage('top_obstacle');
            if (gameState !== 'PLAYING') return;
        }

        // Bottom pillar collision
        if (droneBox.right > obs.x && droneBox.left < obs.x + obs.width && droneBox.bottom > obs.bottomY) {
            takeDamage('bottom_obstacle');
            if (gameState !== 'PLAYING') return;
        }

        // Remove off-screen obstacle
        if (obs.x + obs.width < -20) {
            obstacles.splice(i, 1);
        }
    }

    // 3. Update & Collect Items
    for (let i = collectibles.length - 1; i >= 0; i--) {
        const c = collectibles[i];
        c.x -= speed;
        c.rotation += 0.05;

        // Distance check for collision with drone center
        const dx = (drone.x + drone.width / 2) - c.x;
        const dy = (drone.y + drone.height / 2) - c.y;
        const dist = Math.sqrt(dx * dx + dy * dy);

        if (dist < c.size + 16 && !c.collected) {
            c.collected = true;
            const pts = c.type === 'film' ? 50 : 100;
            score += pts;
            footageCount++;
            playSound('collect');
            createCollectParticles(c.x, c.y);
            addFloatingScore(c.x, c.y, `+${pts}`, c.type === 'film' ? '#fbbf24' : '#38bdf8');
            updateHUD();
            collectibles.splice(i, 1);
            continue;
        }

        if (c.x < -20) {
            collectibles.splice(i, 1);
        }
    }

    // 4. Update Particles
    for (let i = particles.length - 1; i >= 0; i--) {
        const p = particles[i];
        p.x += p.vx;
        p.y += p.vy;
        p.alpha *= p.life;
        if (p.alpha <= 0.05) {
            particles.splice(i, 1);
        }
    }

    // 5. Update Floating Texts
    for (let i = floatingTexts.length - 1; i >= 0; i--) {
        const ft = floatingTexts[i];
        ft.y += ft.vy;
        ft.alpha -= 0.025;
        if (ft.alpha <= 0) {
            floatingTexts.splice(i, 1);
        }
    }

    updateHUD();
}

function takeDamage(reason = 'collision') {
    if (invincibilityTimer > 0 || gameState !== 'PLAYING') return;

    hearts--;
    updateHUD();

    // Damage particles
    for (let i = 0; i < 14; i++) {
        const angle = Math.random() * Math.PI * 2;
        const speed = Math.random() * 4 + 2;
        particles.push({
            x: drone.x + drone.width / 2,
            y: drone.y + drone.height / 2,
            vx: Math.cos(angle) * speed,
            vy: Math.sin(angle) * speed,
            size: Math.random() * 4 + 2,
            color: '#ef4444',
            alpha: 1,
            life: 0.90
        });
    }

    addFloatingScore(drone.x + drone.width / 2, drone.y - 12, '-1 ❤️', '#ef4444');

    if (hearts <= 0) {
        triggerGameOver();
    } else {
        playSound('hit');
        invincibilityTimer = 90; // ~1.5 seconds grace period

        // Responsive bounce away from obstacles
        if (reason === 'floor') {
            drone.vy = -4.2;
            drone.y -= 10;
        } else if (reason === 'top_obstacle') {
            drone.vy = 2.5;
            drone.y += 12;
        } else if (reason === 'bottom_obstacle') {
            drone.vy = -4.5;
            drone.y -= 12;
        }
    }
}

function triggerGameOver() {
    gameState = 'GAMEOVER';
    isThrusting = false;
    mobileThrustBtn?.classList.remove('holding');
    playSound('crash');

    // Update High Score
    if (score > bestScore) {
        bestScore = score;
        localStorage.setItem('mmc_tapnich_highscore', bestScore.toString());
    }

    // Determine Rank Title
    let rankTitle = 'Rookie Drone Pilot';
    if (score >= 400) rankTitle = 'Master Aerial Cinematographer ⭐⭐⭐';
    else if (score >= 250) rankTitle = 'Broadcast Director 🎥';
    else if (score >= 100) rankTitle = 'Studio Cameraman 🛸';

    // Populate Game Over Overlay
    document.getElementById('overPilotName').innerText = gameConfig.pilotName;
    document.getElementById('overRankTitle').innerText = rankTitle;
    document.getElementById('overFinalScore').innerText = score;
    document.getElementById('overFootageCount').innerText = footageCount;
    document.getElementById('overDistance').innerText = `${distanceMeters}m`;
    document.getElementById('overBestScore').innerText = `${bestScore} Pts`;

    document.getElementById('gameOverOverlay').classList.remove('d-none');

    // Save record to local leaderboard
    saveScoreToLeaderboard(gameConfig.pilotName, score, footageCount, distanceMeters, gameConfig.difficulty);

    // Save AJAX score to backend
    fetch('<?= base_url('mini-game/api/record-score') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            game_id: 'tapnich',
            level: 1,
            score: score,
            stars: score >= 250 ? 3 : (score >= 100 ? 2 : 1)
        })
    }).catch(err => console.log(err));
}

function updateHUD() {
    document.getElementById('hudScore').innerText = score;
    document.getElementById('hudFootage').innerText = footageCount;
    document.getElementById('hudDistance').innerText = `${distanceMeters} m`;
    document.getElementById('hudBestScore').innerText = bestScore;

    const heartsEl = document.getElementById('hudHearts');
    if (heartsEl) {
        let heartsStr = '';
        for (let i = 0; i < maxHearts; i++) {
            heartsStr += (i < hearts) ? '❤️' : '🖤';
        }
        heartsEl.innerText = heartsStr;
    }
}

// --- Canvas Drawing Routines ---
function drawScene() {
    const rect = container.getBoundingClientRect();
    const w = rect.width;
    const h = rect.height;

    // Clear Canvas
    ctx.clearRect(0, 0, w, h);

    // 1. Sky Gradient Background
    const skyGrad = ctx.createLinearGradient(0, 0, 0, h);
    skyGrad.addColorStop(0, '#040814');
    skyGrad.addColorStop(0.65, '#0b162c');
    skyGrad.addColorStop(1, '#020617');
    ctx.fillStyle = skyGrad;
    ctx.fillRect(0, 0, w, h);

    // Distant Studio Spotlights (Subtle ambient cones)
    ctx.save();
    ctx.globalAlpha = 0.12;
    const spot1 = ctx.createRadialGradient(w * 0.3, h * 0.2, 10, w * 0.3, h * 0.2, 180);
    spot1.addColorStop(0, '#38bdf8');
    spot1.addColorStop(1, 'transparent');
    ctx.fillStyle = spot1;
    ctx.fillRect(0, 0, w, h);
    ctx.restore();

    // 2. Parallax Studio Skyline Silhouettes
    drawSkyline(w, h);

    // 3. Grid Ground Runway
    drawGridGround(w, h);

    // 4. Draw Obstacles (Studio Lighting Trusses & Poles)
    obstacles.forEach(obs => {
        drawObstaclePillar(obs, h);
    });

    // 5. Draw Collectibles (Film Reel / SD Card)
    collectibles.forEach(c => {
        drawCollectibleItem(c);
    });

    // 6. Draw Particles
    particles.forEach(p => {
        ctx.save();
        ctx.globalAlpha = p.alpha;
        ctx.fillStyle = p.color;
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
        ctx.fill();
        ctx.restore();
    });

    // 7. Draw Drone Character
    drawDrone(drone);

    // 8. Draw Floating Score Text
    floatingTexts.forEach(ft => {
        ctx.save();
        ctx.globalAlpha = ft.alpha;
        ctx.fillStyle = ft.color;
        ctx.font = 'bold 15px "JetBrains Mono", Consolas, monospace';
        ctx.textAlign = 'center';
        ctx.shadowColor = ft.color;
        ctx.shadowBlur = 8;
        ctx.fillText(ft.text, ft.x, ft.y);
        ctx.restore();
    });
}

function drawSkyline(w, h) {
    ctx.save();
    ctx.fillStyle = '#060e20';
    // Distant building silhouettes
    const bWidth = 45;
    const count = Math.ceil(w / bWidth) + 2;
    for (let i = 0; i < count; i++) {
        const x = (i * bWidth) - (bgOffset1 % bWidth);
        const bHeight = 60 + ((i * 37) % 70);
        ctx.fillRect(x, h - 30 - bHeight, bWidth - 4, bHeight);
        
        // Window dots
        ctx.fillStyle = 'rgba(56, 189, 248, 0.15)';
        for (let r = 0; r < 3; r++) {
            ctx.fillRect(x + 8, h - 26 - bHeight + (r * 15), 5, 5);
            ctx.fillRect(x + 22, h - 26 - bHeight + (r * 15), 5, 5);
        }
        ctx.fillStyle = '#060e20';
    }
    ctx.restore();
}

function drawGridGround(w, h) {
    // Ground Runway Strip
    const gHeight = 28;
    const gGrad = ctx.createLinearGradient(0, h - gHeight, 0, h);
    gGrad.addColorStop(0, '#0f172a');
    gGrad.addColorStop(1, '#020617');
    ctx.fillStyle = gGrad;
    ctx.fillRect(0, h - gHeight, w, gHeight);

    // Glowing Cyan Runway Line
    ctx.strokeStyle = '#0284c7';
    ctx.lineWidth = 2;
    ctx.shadowColor = '#38bdf8';
    ctx.shadowBlur = 6;
    ctx.beginPath();
    ctx.moveTo(0, h - gHeight);
    ctx.lineTo(w, h - gHeight);
    ctx.stroke();
    ctx.shadowBlur = 0;

    // Moving ground dashes
    ctx.strokeStyle = 'rgba(56, 189, 248, 0.4)';
    ctx.lineWidth = 2;
    ctx.setLineDash([14, 14]);
    ctx.lineDashOffset = bgOffset3;
    ctx.beginPath();
    ctx.moveTo(0, h - gHeight / 2);
    ctx.lineTo(w, h - gHeight / 2);
    ctx.stroke();
    ctx.setLineDash([]);
}

function drawObstaclePillar(obs, canvasHeight) {
    ctx.save();
    
    // Top Pillar
    const gradTop = ctx.createLinearGradient(obs.x, 0, obs.x + obs.width, 0);
    gradTop.addColorStop(0, '#1e293b');
    gradTop.addColorStop(0.5, '#334155');
    gradTop.addColorStop(1, '#0f172a');
    ctx.fillStyle = gradTop;
    ctx.fillRect(obs.x, 0, obs.width, obs.topHeight);

    // Top Pillar Border & Neon Accent
    ctx.strokeStyle = '#38bdf8';
    ctx.lineWidth = 1.5;
    ctx.strokeRect(obs.x, 0, obs.width, obs.topHeight);

    // Top Pillar Cap Hazard Light
    ctx.fillStyle = '#ef4444';
    ctx.shadowColor = '#ef4444';
    ctx.shadowBlur = 8;
    ctx.fillRect(obs.x + (obs.width / 2) - 4, obs.topHeight - 8, 8, 8);
    ctx.shadowBlur = 0;

    // Bottom Pillar
    const gradBot = ctx.createLinearGradient(obs.x, 0, obs.x + obs.width, 0);
    gradBot.addColorStop(0, '#1e293b');
    gradBot.addColorStop(0.5, '#334155');
    gradBot.addColorStop(1, '#0f172a');
    ctx.fillStyle = gradBot;
    ctx.fillRect(obs.x, obs.bottomY, obs.width, obs.bottomHeight);

    ctx.strokeStyle = '#38bdf8';
    ctx.lineWidth = 1.5;
    ctx.strokeRect(obs.x, obs.bottomY, obs.width, obs.bottomHeight);

    // Bottom Pillar Cap Hazard Light
    ctx.fillStyle = '#ef4444';
    ctx.shadowColor = '#ef4444';
    ctx.shadowBlur = 8;
    ctx.fillRect(obs.x + (obs.width / 2) - 4, obs.bottomY, 8, 8);
    ctx.shadowBlur = 0;

    // Truss Pattern Grid Line Details
    ctx.strokeStyle = 'rgba(255, 255, 255, 0.12)';
    ctx.lineWidth = 1;
    for (let y = 14; y < obs.topHeight - 14; y += 20) {
        ctx.beginPath();
        ctx.moveTo(obs.x, y);
        ctx.lineTo(obs.x + obs.width, y + 10);
        ctx.stroke();
    }
    for (let y = obs.bottomY + 14; y < canvasHeight - 14; y += 20) {
        ctx.beginPath();
        ctx.moveTo(obs.x, y);
        ctx.lineTo(obs.x + obs.width, y + 10);
        ctx.stroke();
    }

    ctx.restore();
}

function drawCollectibleItem(c) {
    ctx.save();
    ctx.translate(c.x, c.y);
    ctx.rotate(c.rotation);

    if (c.type === 'film') {
        // Golden Film Reel
        ctx.shadowColor = '#fbbf24';
        ctx.shadowBlur = 10;

        // Outer Ring
        ctx.fillStyle = '#f59e0b';
        ctx.beginPath();
        ctx.arc(0, 0, c.size, 0, Math.PI * 2);
        ctx.fill();

        // Inner core
        ctx.fillStyle = '#0f172a';
        ctx.beginPath();
        ctx.arc(0, 0, c.size * 0.75, 0, Math.PI * 2);
        ctx.fill();

        // Film Sprocket holes
        ctx.fillStyle = '#fbbf24';
        for (let i = 0; i < 4; i++) {
            const rad = (Math.PI / 2) * i;
            ctx.beginPath();
            ctx.arc(Math.cos(rad) * 5.5, Math.sin(rad) * 5.5, 2, 0, Math.PI * 2);
            ctx.fill();
        }
    } else {
        // Blue SD Card Token
        ctx.shadowColor = '#38bdf8';
        ctx.shadowBlur = 10;

        ctx.fillStyle = '#0284c7';
        ctx.fillRect(-c.size * 0.8, -c.size, c.size * 1.6, c.size * 2);

        // Gold Pins
        ctx.fillStyle = '#fbbf24';
        for (let i = -2; i <= 2; i++) {
            ctx.fillRect(i * 3 - 1, -c.size + 2, 2, 3.5);
        }
    }

    ctx.restore();
}

function drawDrone(d) {
    ctx.save();
    ctx.translate(d.x + d.width / 2, d.y + d.height / 2);
    ctx.rotate(d.rotation);

    // Blinking effect during invincibility grace period
    if (invincibilityTimer > 0) {
        if (Math.floor(frameCount / 4) % 2 === 0) {
            ctx.globalAlpha = 0.35;
        }
    }

    const skin = gameConfig.droneSkin;

    // Body Theme Palettes
    let bodyColor = '#f8fafc';
    let accentColor = '#0284c7';
    let glowColor = '#38bdf8';

    if (skin === 'fpv') {
        bodyColor = '#1e293b';
        accentColor = '#f97316';
        glowColor = '#ea580c';
    } else if (skin === 'cinecam') {
        bodyColor = '#0f172a';
        accentColor = '#fbbf24';
        glowColor = '#f59e0b';
    } else if (skin === 'cyber') {
        bodyColor = '#09090b';
        accentColor = '#a855f7';
        glowColor = '#c084fc';
    }

    // Shield Aura during Invulnerability
    if (invincibilityTimer > 0) {
        ctx.save();
        ctx.strokeStyle = 'rgba(56, 189, 248, 0.8)';
        ctx.lineWidth = 1.5;
        ctx.setLineDash([5, 5]);
        ctx.lineDashOffset = frameCount * 2.5;
        ctx.beginPath();
        ctx.arc(0, 0, 27, 0, Math.PI * 2);
        ctx.stroke();
        ctx.restore();
    }

    // 1. Central Camera Gimbal & Lens
    ctx.fillStyle = '#0f172a';
    ctx.beginPath();
    ctx.arc(14, 4, 7, 0, Math.PI * 2);
    ctx.fill();

    // Glowing Lens Eye
    ctx.fillStyle = glowColor;
    ctx.shadowColor = glowColor;
    ctx.shadowBlur = 10;
    ctx.beginPath();
    ctx.arc(15, 4, 3.5, 0, Math.PI * 2);
    ctx.fill();
    ctx.shadowBlur = 0;

    // 2. Drone Fuselage Body
    ctx.fillStyle = bodyColor;
    ctx.beginPath();
    ctx.roundRect(-16, -7, 30, 14, 6);
    ctx.fill();

    // Body Accent Stripe
    ctx.fillStyle = accentColor;
    ctx.fillRect(-12, -2, 22, 4);

    // 3. Drone Landing Skid Arms
    ctx.strokeStyle = '#64748b';
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(-10, 7);
    ctx.lineTo(-8, 12);
    ctx.lineTo(8, 12);
    ctx.lineTo(10, 7);
    ctx.stroke();

    // 4. Rotor Motor Arms
    ctx.strokeStyle = '#475569';
    ctx.lineWidth = 2.5;
    ctx.beginPath();
    ctx.moveTo(-16, -3);
    ctx.lineTo(-20, -11); // Rear rotor mast
    ctx.moveTo(10, -3);
    ctx.lineTo(14, -11);  // Front rotor mast
    ctx.stroke();

    // 5. Spinning Rotor Blades with Motion Blur
    const rotorWidth = 18 * Math.cos(d.rotorAngle);
    ctx.strokeStyle = 'rgba(255, 255, 255, 0.85)';
    ctx.lineWidth = 2;

    // Front Rotor Blade
    ctx.beginPath();
    ctx.moveTo(14 - rotorWidth, -11);
    ctx.lineTo(14 + rotorWidth, -11);
    ctx.stroke();

    // Rear Rotor Blade
    ctx.beginPath();
    ctx.moveTo(-20 - rotorWidth, -11);
    ctx.lineTo(-20 + rotorWidth, -11);
    ctx.stroke();

    // Rotor Hub Centers
    ctx.fillStyle = glowColor;
    ctx.beginPath();
    ctx.arc(14, -11, 2, 0, Math.PI * 2);
    ctx.arc(-20, -11, 2, 0, Math.PI * 2);
    ctx.fill();

    ctx.restore();
}

// --- Settings & Modal Logic ---
function updateSelectedDroneSkin(val) {
    gameConfig.droneSkin = val;
    localStorage.setItem('mmc_tapnich_skin', val);
    selectSkinFromModal(val);
}

function updatePregameDiff(val) {
    gameConfig.difficulty = val;
    localStorage.setItem('mmc_tapnich_diff', val);
    updatePregameInfo();
}

function updatePregameSpeed(val) {
    const spd = parseFloat(val) || 1.0;
    gameConfig.speedMult = spd;
    localStorage.setItem('mmc_tapnich_speed', spd.toString());
    updatePregameInfo();
}

function selectSkinFromModal(skinKey) {
    ['mavic', 'fpv', 'cinecam', 'cyber'].forEach(k => {
        document.getElementById(`skinOpt_${k}`)?.classList.remove('active');
    });
    document.getElementById(`skinOpt_${skinKey}`)?.classList.add('active');
    gameConfig.droneSkin = skinKey;
}

function saveSettingsModal() {
    const name = document.getElementById('modalPilotName').value.trim() || 'Pilot MM';
    const diff = document.getElementById('modalDifficulty').value;
    const spd = parseFloat(document.getElementById('modalSpeed').value) || 1.0;

    gameConfig.pilotName = name;
    gameConfig.difficulty = diff;
    gameConfig.speedMult = spd;

    localStorage.setItem('mmc_tapnich_name', name);
    localStorage.setItem('mmc_tapnich_diff', diff);
    localStorage.setItem('mmc_tapnich_speed', spd.toString());
    localStorage.setItem('mmc_tapnich_skin', gameConfig.droneSkin);
    localStorage.setItem('mmc_tapnich_mode', gameConfig.controlMode);

    document.getElementById('inputPilotName').value = name;
    document.getElementById('selectDroneSkin').value = gameConfig.droneSkin;
    document.getElementById('topbarPilotName').innerText = name;

    setControlMode(gameConfig.controlMode);
    updatePregameInfo();
    bootstrap.Modal.getInstance(document.getElementById('settingsModal')).hide();
}

// --- Local High Score & Leaderboard System ---
function saveScoreToLeaderboard(name, sc, footage, dist, diff) {
    let board = JSON.parse(localStorage.getItem('mmc_tapnich_leaderboard') || '[]');
    board.push({
        name: name,
        score: sc,
        footage: footage,
        distance: dist,
        difficulty: diff,
        date: new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
    });

    // Sort descending by score & keep top 5
    board.sort((a, b) => b.score - a.score);
    board = board.slice(0, 5);

    localStorage.setItem('mmc_tapnich_leaderboard', JSON.stringify(board));
    renderLeaderboardModal();
}

function renderLeaderboardModal() {
    const board = JSON.parse(localStorage.getItem('mmc_tapnich_leaderboard') || '[]');
    const container = document.getElementById('leaderboardBody');

    if (board.length === 0) {
        container.innerHTML = `
            <div class="text-center text-secondary py-4">
                <i class="fa-solid fa-wind fs-1 mb-2 text-muted"></i>
                <p class="small mb-0">Belum ada rekor penerbangan tersimpan. Mulai mainkan dan raih skor tertinggi!</p>
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
                        <span class="style-tiny text-secondary font-monospace">${item.date} • ${item.difficulty.toUpperCase()}</span>
                    </div>
                </div>
                <div class="text-end font-monospace">
                    <span class="fs-5 fw-bold text-warning">${item.score}</span>
                    <span class="style-tiny text-secondary d-block">${item.footage} footage • ${item.distance}m</span>
                </div>
            </div>
        `;
    });

    html += '</div>';
    container.innerHTML = html;
}

function clearLeaderboard() {
    if (confirm('Hapus seluruh riwayat rekor skor di perangkat ini?')) {
        localStorage.removeItem('mmc_tapnich_leaderboard');
        localStorage.removeItem('mmc_tapnich_highscore');
        bestScore = 0;
        document.getElementById('hudBestScore').innerText = '0';
        renderLeaderboardModal();
    }
}

// Initialize on page load
window.addEventListener('DOMContentLoaded', () => {
    initGame();
});
</script>
<?= $this->endSection() ?>
