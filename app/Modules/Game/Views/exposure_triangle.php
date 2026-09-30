<?= $this->extend('layouts/master_public') ?>

<?= $this->section('content') ?>
<style>
/* Custom Simulator Theme & Camera Viewfinder Styles */
.simulator-container {
    background: #090d16;
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 24px;
    box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.7), 0 0 40px rgba(220, 38, 38, 0.1);
}

.viewfinder-wrapper {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 9;
    max-height: 480px;
    background: #050811;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: inset 0 0 30px rgba(0,0,0,0.8);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

#simCanvas {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
}

.viewfinder-hud {
    position: absolute;
    inset: 0;
    pointer-events: none;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 16px;
    z-index: 10;
}

.hud-badge {
    background: rgba(15, 23, 42, 0.85);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.18);
    padding: 5px 12px;
    border-radius: 999px;
    font-family: var(--bs-font-monospace, monospace);
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 0.02em;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    transition: all 0.2s ease;
    white-space: nowrap;
}

.hud-badge.aperture {
    color: #60a5fa;
    border-color: rgba(96, 165, 250, 0.4);
}

.hud-badge.shutter {
    color: #4ade80;
    border-color: rgba(74, 222, 128, 0.4);
}

.hud-badge.iso {
    color: #fb923c;
    border-color: rgba(251, 146, 60, 0.4);
}

/* Range Slider Custom Styling */
.cam-range {
    -webkit-appearance: none;
    appearance: none;
    width: 100%;
    height: 8px;
    border-radius: 6px;
    background: #1e293b;
    outline: none;
    transition: background 0.2s;
    touch-action: pan-y;
}
.cam-range::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #ffffff;
    cursor: pointer;
    box-shadow: 0 0 10px rgba(0,0,0,0.5), 0 0 0 3px rgba(239, 68, 68, 0.5);
    transition: transform 0.1s, box-shadow 0.2s;
}
.cam-range::-webkit-slider-thumb:hover {
    transform: scale(1.15);
    box-shadow: 0 0 15px rgba(239, 68, 68, 0.8), 0 0 0 4px rgba(239, 68, 68, 0.7);
}

.val-pill {
    background: #131b2e;
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 12px;
    padding: 6px 14px;
    font-family: var(--bs-font-monospace, monospace);
    font-size: 0.95rem;
    font-weight: 700;
    min-width: 80px;
    text-align: center;
    color: #f8fafc;
    white-space: nowrap;
}

.shutter-btn-main {
    background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: #ffffff;
    border-radius: 999px;
    font-weight: 700;
    padding: 12px 24px;
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4), inset 0 2px 4px rgba(255, 255, 255, 0.3);
    transition: all 0.2s ease;
    cursor: pointer;
}
.shutter-btn-main:hover {
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 12px 30px rgba(239, 68, 68, 0.6);
}
.shutter-btn-main:active {
    transform: translateY(1px) scale(0.98);
}

/* Camera Shutter Flash Animation */
@keyframes cameraFlash {
    0% { opacity: 0; }
    30% { opacity: 0.95; }
    100% { opacity: 0; }
}
.flash-effect {
    animation: cameraFlash 0.35s ease-out;
}

/* Grid Overlay */
.viewfinder-grid {
    position: absolute;
    inset: 0;
    background-image: 
        linear-gradient(to right, rgba(255,255,255,0.15) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(255,255,255,0.15) 1px, transparent 1px);
    background-size: 33.333% 33.333%;
    pointer-events: none;
    transition: opacity 0.2s;
}

/* Aperture Blades Visualizer */
.aperture-visualizer {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: 2px solid rgba(96, 165, 250, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    background: #0b1120;
    overflow: hidden;
    flex-shrink: 0;
}

.aperture-iris {
    border-radius: 50%;
    background: #60a5fa;
    box-shadow: 0 0 12px rgba(96, 165, 250, 0.8);
    transition: width 0.15s ease, height 0.15s ease;
}

/* Polaroid Snapshot Card */
.polaroid-card {
    background: #ffffff;
    color: #0f172a;
    padding: 10px 10px 14px 10px;
    border-radius: 8px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.5);
    transform: rotate(-1deg);
    transition: transform 0.2s ease;
}
.polaroid-card:hover {
    transform: rotate(0deg) scale(1.03);
}

/* Mobile Responsiveness Rules */
@media (max-width: 576px) {
    .simulator-container {
        padding: 12px 14px !important;
        border-radius: 18px !important;
        margin-bottom: 1.5rem !important;
    }
    .viewfinder-wrapper {
        border-radius: 12px;
    }
    .viewfinder-hud {
        padding: 8px 10px !important;
    }
    .hud-badge {
        padding: 3px 7px !important;
        font-size: 0.68rem !important;
        border-radius: 6px !important;
    }
    .val-pill {
        min-width: 60px !important;
        padding: 4px 8px !important;
        font-size: 0.88rem !important;
        border-radius: 8px !important;
    }
    .aperture-visualizer {
        width: 36px !important;
        height: 36px !important;
    }
    .cam-range::-webkit-slider-thumb {
        width: 22px;
        height: 22px;
    }
    .shutter-btn-main {
        padding: 12px 18px !important;
        font-size: 0.92rem !important;
    }
    .mode-tab-btn {
        font-size: 0.8rem !important;
        padding: 8px 6px !important;
    }
}

/* Guide Card Avatar Icons with Gradients */
.guide-icon-avatar {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    color: #ffffff !important;
    flex-shrink: 0;
}
.bg-gradient-blue   { background: linear-gradient(135deg, #3b82f6, #1d4ed8); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }
.bg-gradient-green  { background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); }
.bg-gradient-amber  { background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3); }
.bg-gradient-red    { background: linear-gradient(135deg, #ef4444, #dc2626); box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); }
</style>

<section class="py-3 py-lg-5">
    <div class="container">
        
        <!-- Breadcrumb & Back Navigation -->
        <div class="d-flex align-items-center justify-content-between mb-3 mb-md-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>" class="text-secondary text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('mini-game') ?>" class="text-secondary text-decoration-none">Mini Game</a></li>
                    <li class="breadcrumb-item active text-danger fw-bold" aria-current="page">Exposure Triangle</li>
                </ol>
            </nav>
            <div class="d-flex gap-2">
                <a href="<?= base_url('mini-game') ?>" class="btn btn-sm btn-outline-secondary px-2.5">
                    <i class="fa-solid fa-arrow-left me-1"></i> <span class="d-none d-sm-inline">Kembali</span>
                </a>
            </div>
        </div>

        <!-- Simulator Header -->
        <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-end justify-content-between gap-3 mb-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1.5">
                    <span class="badge bg-danger font-monospace style-tiny"><i class="fa-solid fa-camera me-1"></i> MINI GAME #1</span>
                    <span class="badge bg-body-secondary text-secondary border border-secondary border-opacity-50 style-tiny font-monospace">Fotografi & Videografi</span>
                </div>
                <h1 class="h3 h2-md fw-bold text-body font-heading mb-1">Exposure Triangle Simulator</h1>
                <p class="text-secondary small mb-0">Eksperimen virtual interaktif memahami Aperture, Shutter Speed, dan ISO secara visual.</p>
            </div>

            <!-- Mode Selector Tabs -->
            <div class="bg-body-secondary p-1 rounded-3 border border-secondary border-opacity-25 d-flex gap-1 w-100 w-md-auto flex-shrink-0">
                <button type="button" class="btn btn-sm btn-red px-2 px-sm-3 flex-fill font-heading fw-semibold mode-tab-btn text-nowrap" id="tabSandboxBtn" onclick="switchGameMode('sandbox')">
                    <i class="fa-solid fa-sliders me-1"></i> Mode Bebas
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary px-2 px-sm-3 flex-fill font-heading fw-semibold border-0 text-body mode-tab-btn text-nowrap" id="tabChallengeBtn" onclick="switchGameMode('challenge')">
                    <i class="fa-solid fa-trophy text-warning me-1"></i> Mode Misi
                </button>
            </div>
        </div>

        <!-- Main Interactive Simulator Card -->
        <div class="simulator-container p-3 p-md-4 p-lg-5 mb-4 mb-md-5">
            
            <!-- Top Status Bar & Environment Light Switcher (Responsive Stack on Mobile) -->
            <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center justify-content-between gap-2.5 pb-3 mb-3 border-bottom border-secondary border-opacity-25">
                
                <!-- Scene Preset Selection -->
                <div class="d-flex align-items-center gap-2 flex-grow-1">
                    <span class="text-secondary small fw-semibold text-nowrap d-none d-sm-inline"><i class="fa-solid fa-sun text-warning me-1"></i> Lokasi:</span>
                    <select id="scenePresetSelect" class="form-select form-select-sm bg-dark text-white border-secondary border-opacity-50 font-monospace w-100" onchange="changeSceneLighting(this.value)">
                        <option value="sunny">☀️ Siang Terik (EV 15)</option>
                        <option value="golden">🌅 Golden Hour / Sore (EV 12)</option>
                        <option value="studio" selected>💡 Studio / Indoor Terang (EV 10)</option>
                        <option value="night">🌙 Malam Redup / City Night (EV 5)</option>
                    </select>
                </div>

                <!-- Quick Action Buttons -->
                <div class="d-flex align-items-center gap-1.5 justify-content-between justify-content-md-end w-100 w-md-auto">
                    <button type="button" class="btn btn-sm btn-outline-secondary text-white border-secondary border-opacity-25 flex-fill flex-md-grow-0 text-nowrap" id="toggleGridBtn" onclick="toggleViewfinderGrid()" title="Tampilkan/Sembunyikan Grid">
                        <i class="fa-solid fa-table-cells me-1"></i> Grid
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary text-white border-secondary border-opacity-25 flex-fill flex-md-grow-0 text-nowrap" id="toggleSoundBtn" onclick="toggleAudio()" title="Aktifkan/Matikan Efek Suara">
                        <i class="fa-solid fa-volume-high me-1" id="soundIcon"></i> Sound
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-warning border-warning border-opacity-50 flex-fill flex-md-grow-0 text-nowrap" onclick="resetDefaults()">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset
                    </button>
                </div>
            </div>

            <!-- Challenge Mission Banner (Visible only in challenge mode) -->
            <div id="challengeBanner" class="p-3 mb-4 rounded-3 border border-warning border-opacity-50 bg-warning bg-opacity-10 d-none">
                <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center justify-content-between gap-3">
                    <div class="d-flex align-items-start align-items-md-center gap-3">
                        <div class="p-2 rounded-circle bg-warning text-dark fs-5 flex-shrink-0">
                            <i class="fa-solid fa-flag-checkered"></i>
                        </div>
                        <div>
                            <div class="text-warning style-tiny font-monospace fw-bold" id="missionLevelText">TANTANGAN LEVEL 1 / 5</div>
                            <h6 class="text-white font-heading fw-bold mb-1" id="missionTitleText">Bekukan Gerakan Sepeda di Siang Hari</h6>
                            <p class="text-secondary style-tiny mb-0" id="missionDescText">Gunakan Shutter Speed cepat (minimal 1/1000s) agar sepeda tidak blur, dan seimbangkan Aperture/ISO agar exposure tepat 0.0 EV!</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between justify-content-md-end gap-2 pt-2 pt-md-0 border-top border-md-0 border-warning border-opacity-25">
                        <button class="btn btn-sm btn-outline-secondary text-white" onclick="prevMission()"><i class="fa-solid fa-chevron-left"></i></button>
                        <span class="font-monospace text-warning fw-bold px-2 small" id="missionCounter">1 / 5</span>
                        <button class="btn btn-sm btn-outline-secondary text-white" onclick="nextMission()"><i class="fa-solid fa-chevron-right"></i></button>
                        <button class="btn btn-sm btn-warning text-dark font-heading fw-bold px-3 ms-2" onclick="evaluateChallenge()">
                            <i class="fa-solid fa-circle-check me-1"></i> Cek Hasil
                        </button>
                    </div>
                </div>
            </div>

            <!-- Live Camera Viewfinder Screen -->
            <div class="viewfinder-wrapper position-relative mb-3 mb-md-4">
                <!-- HTML5 Canvas for Real-time Simulation -->
                <canvas id="simCanvas" width="960" height="540"></canvas>

                <!-- Rule of Thirds Grid Overlay -->
                <div class="viewfinder-grid" id="gridOverlay"></div>

                <!-- Flash Overlay for Camera Shutter -->
                <div id="flashOverlay" class="position-absolute inset-0 bg-white opacity-0 pointer-events-none" style="z-index: 30;"></div>

                <!-- HUD Info Elements -->
                <div class="viewfinder-hud">
                    <!-- Top Badges matching the user's mockup -->
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex gap-1 gap-sm-2">
                            <div class="hud-badge aperture" id="hudAperture">f/5.6</div>
                            <div class="hud-badge shutter" id="hudShutter">1/100s</div>
                            <div class="hud-badge iso" id="hudIso">ISO 400</div>
                        </div>

                        <div class="d-flex align-items-center gap-1.5">
                            <span class="badge bg-dark bg-opacity-75 text-white font-monospace border border-secondary border-opacity-50 style-tiny p-1 px-1.5 px-sm-2">
                                <i class="fa-solid fa-battery-three-quarters text-success me-1"></i> 88%
                            </span>
                            <span class="badge bg-dark bg-opacity-75 text-white font-monospace border border-secondary border-opacity-50 style-tiny p-1 px-2 d-none d-sm-inline-block">
                                RAW+JPG
                            </span>
                        </div>
                    </div>

                    <!-- Center Focus Point / AF Reticle -->
                    <div class="position-absolute top-50 start-50 translate-middle pointer-events-none text-center">
                        <div style="width: 36px; height: 36px; border: 1px dashed rgba(74, 222, 128, 0.7); border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                            <div style="width: 5px; height: 5px; background: rgba(74, 222, 128, 0.9); border-radius: 50%;"></div>
                        </div>
                    </div>

                    <!-- Bottom HUD Bar -->
                    <div class="d-flex justify-content-between align-items-end w-100">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-danger text-white font-monospace style-tiny p-1 px-2" id="liveRecBadge">
                                <i class="fa-solid fa-circle me-1" style="font-size: 0.5rem;"></i> LIVE
                            </span>
                        </div>

                        <!-- Live Exposure Light Meter Bar -->
                        <div class="bg-dark bg-opacity-85 p-1.5 px-2 rounded-2 border border-secondary border-opacity-25 font-monospace text-center ms-2" style="min-width: 140px; max-width: 200px;">
                            <div class="d-flex justify-content-between text-secondary style-tiny mb-0.5" style="font-size: 0.65rem;">
                                <span>-3</span>
                                <span>-2</span>
                                <span>-1</span>
                                <span class="text-white fw-bold">0</span>
                                <span>+1</span>
                                <span>+2</span>
                                <span>+3</span>
                            </div>
                            <!-- EV Meter Needle Track -->
                            <div class="position-relative bg-secondary bg-opacity-25 rounded-pill" style="height: 5px;">
                                <div class="position-absolute top-0 start-50 translate-middle-x bg-white" style="width: 2px; height: 8px; margin-top: -1.5px;"></div>
                                <div id="evMeterIndicator" class="position-absolute top-0 rounded-pill bg-success" style="width: 8px; height: 8px; margin-top: -1.5px; left: 50%; transform: translateX(-50%); transition: left 0.15s ease, background 0.15s ease;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metric Exposure Readout (Polished 2-column Box for Mobile & Desktop) -->
            <div class="row g-2 g-sm-3 text-center mb-4 py-2.5 py-sm-3 rounded-4 bg-dark bg-opacity-50 border border-secondary border-opacity-25 align-items-center">
                <div class="col-6 border-end border-secondary border-opacity-25">
                    <div class="text-secondary text-uppercase style-tiny font-monospace letter-spacing-1 mb-1">EXPOSURE VALUE</div>
                    <div id="exposureValueText" class="font-monospace">
                        <div class="fs-5 fs-sm-4 fw-bold text-success">0.0 EV</div>
                        <div class="style-tiny text-success opacity-75">(Ideal)</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="text-secondary text-uppercase style-tiny font-monospace letter-spacing-1 mb-1">BRIGHTNESS</div>
                    <div id="brightnessValueText" class="font-monospace">
                        <div class="fs-5 fs-sm-4 fw-bold text-info">100%</div>
                        <div class="style-tiny text-info opacity-75">(Normal)</div>
                    </div>
                </div>
            </div>

            <!-- Controls Panel: Sliders & Values -->
            <div class="row g-3 g-md-4 align-items-center mb-4">
                
                <!-- Left Column: Aperture & ISO -->
                <div class="col-lg-6">
                    <!-- Aperture Slider -->
                    <div class="mb-3 mb-md-4">
                        <div class="d-flex align-items-center justify-content-between mb-1.5">
                            <div class="d-flex align-items-center gap-2">
                                <div class="aperture-visualizer">
                                    <div class="aperture-iris" id="apertureIrisVisual" style="width: 24px; height: 24px;"></div>
                                </div>
                                <div>
                                    <label class="text-white font-heading fw-bold mb-0 small">Aperture (Bukaan f/)</label>
                                    <div class="text-secondary style-tiny">Depth of Field (Bokeh / Blur)</div>
                                </div>
                            </div>
                            <div class="val-pill" id="valAperture">f/5.6</div>
                        </div>
                        <input type="range" class="cam-range" id="sliderAperture" min="0" max="8" step="1" value="4" oninput="updateControls()">
                        <div class="d-flex justify-content-between text-secondary style-tiny font-monospace mt-1">
                            <span class="text-info">f/1.4 (Bokeh)</span>
                            <span>f/5.6</span>
                            <span class="text-warning">f/22 (Tajam)</span>
                        </div>
                    </div>

                    <!-- ISO Slider -->
                    <div class="mb-3 mb-lg-0">
                        <div class="d-flex align-items-center justify-content-between mb-1.5">
                            <div class="d-flex align-items-center gap-2">
                                <div class="p-2 rounded-3 bg-warning bg-opacity-10 text-warning fs-6 flex-shrink-0" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid fa-bolt"></i>
                                </div>
                                <div>
                                    <label class="text-white font-heading fw-bold mb-0 small">ISO (Sensitivitas)</label>
                                    <div class="text-secondary style-tiny">Kepekaan Cahaya & Noise</div>
                                </div>
                            </div>
                            <div class="val-pill" id="valIso">400</div>
                        </div>
                        <input type="range" class="cam-range" id="sliderIso" min="0" max="7" step="1" value="2" oninput="updateControls()">
                        <div class="d-flex justify-content-between text-secondary style-tiny font-monospace mt-1">
                            <span class="text-success">ISO 100 (Jernih)</span>
                            <span>ISO 800</span>
                            <span class="text-danger">ISO 12800 (Noise)</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Shutter Speed & Actions -->
                <div class="col-lg-6">
                    <!-- Shutter Speed Slider -->
                    <div class="mb-3 mb-md-4">
                        <div class="d-flex align-items-center justify-content-between mb-1.5">
                            <div class="d-flex align-items-center gap-2">
                                <div class="p-2 rounded-3 bg-success bg-opacity-10 text-success fs-6 flex-shrink-0" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid fa-stopwatch"></i>
                                </div>
                                <div>
                                    <label class="text-white font-heading fw-bold mb-0 small">Shutter Speed (Rana)</label>
                                    <div class="text-secondary style-tiny">Freeze vs Motion Blur</div>
                                </div>
                            </div>
                            <div class="val-pill" id="valShutter">1/100s</div>
                        </div>
                        <input type="range" class="cam-range" id="sliderShutter" min="0" max="11" step="1" value="6" oninput="updateControls()">
                        <div class="d-flex justify-content-between text-secondary style-tiny font-monospace mt-1">
                            <span class="text-info">1/4000s (Freeze)</span>
                            <span>1/125s</span>
                            <span class="text-warning">1s (Blur Jejak)</span>
                        </div>
                    </div>

                    <!-- Action Buttons: Shutter Shoot & Reset (Full Width Mobile Friendly) -->
                    <div class="d-flex flex-row gap-2 gap-sm-3">
                        <button type="button" class="shutter-btn-main flex-grow-1 d-flex align-items-center justify-content-center gap-2 text-nowrap" onclick="takeSnapshot()">
                            <i class="fa-solid fa-camera"></i>
                            <span>Ambil Foto</span>
                        </button>
                        <button type="button" class="btn btn-dark px-3 px-sm-4 py-2.5 py-sm-3 rounded-pill border border-secondary border-opacity-50 text-white font-heading d-flex align-items-center justify-content-center gap-1.5" onclick="resetDefaults()">
                            <i class="fa-solid fa-rotate-left"></i>
                            <span class="d-none d-sm-inline">Reset</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Live Educational Feedback Diagnosis Card -->
            <div class="p-3 rounded-4 bg-dark border border-secondary border-opacity-25" id="feedbackDiagnosisBox">
                <div class="d-flex align-items-start gap-2.5">
                    <div class="p-2 rounded-circle bg-info bg-opacity-10 text-info fs-5 flex-shrink-0" id="diagnosisIcon">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <div>
                        <h6 class="text-white font-heading fw-bold mb-1 small" id="diagnosisTitle">Analisis Hasil Pengaturan Kamera:</h6>
                        <p class="text-secondary style-tiny mb-0" id="diagnosisBody">
                            Pengaturan saat ini menghasilkan exposure yang seimbang. Background memiliki depth-of-field natural, gerakan pengendara sepeda tajam, dan tingkat noise sangat minim.
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Snapshots / Captured Photos Gallery Section -->
        <div class="mb-5" id="snapshotGallerySection" style="display: none;">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h3 class="text-body font-heading fw-bold mb-0"><i class="fa-solid fa-images text-danger me-2"></i> Galeri Jepretan Foto Anda</h3>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearSnapshots()">
                    <i class="fa-solid fa-trash me-1"></i> Hapus Semua Foto
                </button>
            </div>
            <div class="row g-4" id="snapshotList"></div>
        </div>

        <!-- Comprehensive Educational Guide: "The Holy Trinity": Segitiga Exposure -->
        <div class="saas-card saas-card-glow border border-secondary border-opacity-25 p-4 p-md-5 mb-5" id="teori-segitiga">
            <div class="text-center max-w-3xl mx-auto mb-5">
                <span class="badge bg-danger bg-opacity-20 text-danger border border-danger border-opacity-25 font-monospace px-3 py-1 mb-2">PANDUAN LENGKAP</span>
                <h2 class="display-6 fw-bold text-body font-heading mb-2">"The Holy Trinity": Segitiga Exposure</h2>
                <p class="text-secondary lead fs-6">
                    Materi paling penting dalam fotografi dan videografi adalah <strong>Segitiga Exposure</strong>. Ini adalah kombinasi tiga elemen dasar yang mengatur seberapa terang atau gelap (exposure) gambar kalian.
                </p>
            </div>

            <div class="row g-4 mb-5">
                <!-- 1. Aperture -->
                <div class="col-lg-4">
                    <div class="p-4 rounded-4 bg-body-secondary h-100 border border-secondary border-opacity-25">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="guide-icon-avatar bg-gradient-blue">
                                <i class="fa-solid fa-circle-dot"></i>
                            </div>
                            <div>
                                <h5 class="text-body font-heading fw-bold mb-0">Aperture</h5>
                                <span class="text-primary small font-monospace">Bukaan Lensa (f-stop)</span>
                            </div>
                        </div>
                        <p class="text-body small mb-3">
                            <strong>Definisi:</strong> Seberapa lebar lubang lensa terbuka saat mengambil gambar.
                        </p>
                        <div class="p-3 rounded-3 bg-dark bg-opacity-50 border border-primary border-opacity-25 text-secondary small">
                            <strong class="text-primary d-block mb-1"><i class="fa-solid fa-eye me-1"></i> Efek Visual:</strong>
                            Mengontrol <em>Depth of Field</em> (bokeh/blur). Bukaan besar (angka f/ kecil, misal <code>f/1.8</code>) akan membuat background blur. Bukaan kecil (angka f/ besar, misal <code>f/11</code>) membuat seluruh gambar tajam dari depan hingga belakang.
                        </div>
                    </div>
                </div>

                <!-- 2. Shutter Speed -->
                <div class="col-lg-4">
                    <div class="p-4 rounded-4 bg-body-secondary h-100 border border-secondary border-opacity-25">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="guide-icon-avatar bg-gradient-green">
                                <i class="fa-solid fa-stopwatch"></i>
                            </div>
                            <div>
                                <h5 class="text-body font-heading fw-bold mb-0">Shutter Speed</h5>
                                <span class="text-success small font-monospace">Kecepatan Rana (Detik)</span>
                            </div>
                        </div>
                        <p class="text-body small mb-3">
                            <strong>Definisi:</strong> Seberapa lama sensor kamera terbuka untuk menangkap cahaya yang masuk.
                        </p>
                        <div class="p-3 rounded-3 bg-dark bg-opacity-50 border border-success border-opacity-25 text-secondary small">
                            <strong class="text-success d-block mb-1"><i class="fa-solid fa-person-running me-1"></i> Efek Visual:</strong>
                            Mengontrol efek gerak. Shutter cepat (misal <code>1/1000s</code>) membekukan gerakan (<em>freeze motion</em>). Shutter lambat (misal <code>1/5s</code>) membuat objek yang bergerak terlihat kabur/berbayang (<em>motion blur</em>).
                        </div>
                    </div>
                </div>

                <!-- 3. ISO -->
                <div class="col-lg-4">
                    <div class="p-4 rounded-4 bg-body-secondary h-100 border border-secondary border-opacity-25">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="guide-icon-avatar bg-gradient-amber">
                                <i class="fa-solid fa-bolt"></i>
                            </div>
                            <div>
                                <h5 class="text-body font-heading fw-bold mb-0">ISO</h5>
                                <span class="text-warning small font-monospace">Sensitivitas Sensor</span>
                            </div>
                        </div>
                        <p class="text-body small mb-3">
                            <strong>Definisi:</strong> Tingkat kepekaan sensor kamera terhadap cahaya di lingkungan sekitar.
                        </p>
                        <div class="p-3 rounded-3 bg-dark bg-opacity-50 border border-warning border-opacity-25 text-secondary small">
                            <strong class="text-warning d-block mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Efek Visual:</strong>
                            Semakin tinggi ISO (misal <code>3200</code>), foto makin terang di tempat gelap. Namun risikonya, gambar akan dipenuhi <strong>noise</strong> (bintik-bintik kasar/pecah).
                        </div>
                    </div>
                </div>
            </div>

            <!-- Interactive Summary Cheat Sheet Table -->
            <div class="table-responsive rounded-4 border border-secondary border-opacity-25 overflow-hidden">
                <table class="table table-dark table-hover mb-0 align-middle small">
                    <thead class="bg-black text-secondary font-monospace">
                        <tr>
                            <th class="py-3 px-4">PENGATURAN</th>
                            <th class="py-3 px-4 text-info">NILAI RENDAH / BESAR</th>
                            <th class="py-3 px-4 text-warning">NILAI TINGGI / KECIL</th>
                            <th class="py-3 px-4">TIPS PENGGUNAAN KLUB MM</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 fw-bold text-primary"><i class="fa-solid fa-circle-dot me-2"></i> Aperture (f/)</td>
                            <td class="px-4"><strong>f/1.4 - f/2.8</strong> (Bukaan Lebar): Cahaya banyak, background bokeh/blur.</td>
                            <td class="px-4"><strong>f/8 - f/22</strong> (Bukaan Sempit): Cahaya sedikit, semua objek tajam.</td>
                            <td class="px-4 text-secondary">Gunakan f/1.8 untuk foto portrait member & video sinematik; f/8 untuk foto grup & landscape.</td>
                        </tr>
                        <tr>
                            <td class="px-4 fw-bold text-success"><i class="fa-solid fa-stopwatch me-2"></i> Shutter Speed</td>
                            <td class="px-4"><strong>1/1000s - 1/4000s</strong>: Bekukan aksi cepat tanpa blur.</td>
                            <td class="px-4"><strong>1/15s - 1s+</strong>: Efek jejak cahaya / air terjun halus (butuh tripod).</td>
                            <td class="px-4 text-secondary">Saat rekam video 24/30fps, gunakan <em>180 degree rule</em> (Shutter 1/50s atau 1/60s).</td>
                        </tr>
                        <tr>
                            <td class="px-4 fw-bold text-warning"><i class="fa-solid fa-bolt me-2"></i> ISO</td>
                            <td class="px-4"><strong>ISO 100 - 400</strong>: Kualitas gambar paling bersih, bebas bintik noise.</td>
                            <td class="px-4"><strong>ISO 3200 - 12800</strong>: Terang di tempat gelap, timbul bintik digital noise.</td>
                            <td class="px-4 text-secondary">Jaga ISO serendah mungkin; maksimalkan bukaan lensa atau lampu lighting terlebih dahulu.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>

<!-- Challenge Success Modal -->
<div class="modal fade" id="challengeSuccessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border border-warning text-white p-4 text-center rounded-4 shadow-2xl">
            <div class="mb-3">
                <div class="d-inline-flex p-3 rounded-circle bg-warning text-dark fs-2 mb-2">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <h3 class="font-heading fw-bold text-warning" id="modalResultTitle">Tantangan Berhasil!</h3>
                <div class="text-warning fs-4 mb-2" id="modalStarRating">⭐⭐⭐</div>
                <p class="text-secondary small" id="modalResultDesc">Selamat! Kamu berhasil menyeimbangkan ketiga pilar Exposure Triangle sesuai kriteria misi.</p>
            </div>
            <div class="d-flex justify-content-center gap-3">
                <button type="button" class="btn btn-outline-secondary px-4 text-white" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-warning text-dark font-heading fw-bold px-4" onclick="nextMission(); bootstrap.Modal.getInstance(document.getElementById('challengeSuccessModal')).hide();">
                    Lanjut Misi Berikutnya <i class="fa-solid fa-arrow-right ms-1"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Simulator Engine -->
<script>
// --- Simulator State Data & Constants ---
const APERTURES = [
    { label: 'f/1.4', val: 1.4, fStop: 1.4, blurPx: 22, lightFactor: 4.0 },
    { label: 'f/2.0', val: 2.0, fStop: 2.0, blurPx: 16, lightFactor: 2.0 },
    { label: 'f/2.8', val: 2.8, fStop: 2.8, blurPx: 12, lightFactor: 1.0 },
    { label: 'f/4.0', val: 4.0, fStop: 4.0, blurPx: 7,  lightFactor: 0.5 },
    { label: 'f/5.6', val: 5.6, fStop: 5.6, blurPx: 3,  lightFactor: 0.25 },
    { label: 'f/8.0', val: 8.0, fStop: 8.0, blurPx: 1,  lightFactor: 0.125 },
    { label: 'f/11',  val: 11,  fStop: 11,  blurPx: 0,  lightFactor: 0.0625 },
    { label: 'f/16',  val: 16,  fStop: 16,  blurPx: 0,  lightFactor: 0.03125 },
    { label: 'f/22',  val: 22,  fStop: 22,  blurPx: 0,  lightFactor: 0.0156 }
];

const SHUTTERS = [
    { label: '1/4000s', sec: 1/4000, motionBlur: 0,   lightFactor: 0.025 },
    { label: '1/2000s', sec: 1/2000, motionBlur: 0,   lightFactor: 0.05 },
    { label: '1/1000s', sec: 1/1000, motionBlur: 0,   lightFactor: 0.1 },
    { label: '1/500s',  sec: 1/500,  motionBlur: 0.5, lightFactor: 0.2 },
    { label: '1/250s',  sec: 1/250,  motionBlur: 1,   lightFactor: 0.4 },
    { label: '1/125s',  sec: 1/125,  motionBlur: 2,   lightFactor: 0.8 },
    { label: '1/60s',   sec: 1/60,   motionBlur: 5,   lightFactor: 1.6 },
    { label: '1/30s',   sec: 1/30,   motionBlur: 10,  lightFactor: 3.2 },
    { label: '1/15s',   sec: 1/15,   motionBlur: 20,  lightFactor: 6.4 },
    { label: '1/8s',    sec: 1/8,    motionBlur: 35,  lightFactor: 12.8 },
    { label: '1/4s',    sec: 1/4,    motionBlur: 55,  lightFactor: 25.6 },
    { label: '1s',      sec: 1,      motionBlur: 90,  lightFactor: 100.0 }
];

const ISOS = [
    { label: '100',   val: 100,   noiseAmp: 0,    lightFactor: 0.25 },
    { label: '200',   val: 200,   noiseAmp: 0.02, lightFactor: 0.5 },
    { label: '400',   val: 400,   noiseAmp: 0.05, lightFactor: 1.0 },
    { label: '800',   val: 800,   noiseAmp: 0.12, lightFactor: 2.0 },
    { label: '1600',  val: 1600,  noiseAmp: 0.25, lightFactor: 4.0 },
    { label: '3200',  val: 3200,  noiseAmp: 0.45, lightFactor: 8.0 },
    { label: '6400',  val: 6400,  noiseAmp: 0.70, lightFactor: 16.0 },
    { label: '12800', val: 12800, noiseAmp: 0.95, lightFactor: 32.0 }
];

const SCENES = {
    sunny:  { name: 'Siang Terik', ambientEV: 15, baseLight: 0.0035, skyTop: '#38bdf8', skyBottom: '#bae6fd', sunColor: '#fef08a', isNight: false },
    golden: { name: 'Golden Hour / Sore', ambientEV: 12, baseLight: 0.012, skyTop: '#f97316', skyBottom: '#fde047', sunColor: '#ffedd5', isNight: false },
    studio: { name: 'Studio / Indoor', ambientEV: 10, baseLight: 0.045, skyTop: '#334155', skyBottom: '#64748b', sunColor: '#ffffff', isNight: false },
    night:  { name: 'Malam Redup', ambientEV: 5,  baseLight: 0.45,  skyTop: '#020617', skyBottom: '#0f172a', sunColor: '#e2e8f0', isNight: true }
};

// Challenge Missions Catalog
const CHALLENGES = [
    {
        level: 1,
        title: "Action Freeze: Sepeda Siang Hari",
        scene: "sunny",
        desc: "Bekukan gerakan sepeda balap di siang terik tanpa blur sedikitpun (Shutter min. 1/1000s) dengan exposure seimbang (0.0 EV).",
        validate: (ap, sh, iso, ev) => (sh.sec <= 1/1000 && Math.abs(ev) <= 0.6)
    },
    {
        level: 2,
        title: "Portrait Bokeh Estetik: Golden Hour",
        scene: "golden",
        desc: "Ciptakan foto sore estetik dengan background bokeh maksimal (Aperture f/1.4 - f/2.8) tanpa overexposure (EV ideal).",
        validate: (ap, sh, iso, ev) => (ap.fStop <= 2.8 && Math.abs(ev) <= 0.6)
    },
    {
        level: 3,
        title: "Night Photography: Bersih Bebas Noise",
        scene: "night",
        desc: "Ambil foto malam hari yang tetap terang namun menjaga noise serendah mungkin (ISO maks. 800) dan exposure seimbang.",
        validate: (ap, sh, iso, ev) => (iso.val <= 800 && Math.abs(ev) <= 0.7)
    },
    {
        level: 4,
        title: "Cinematic Light Trail: Gerak Dinamis",
        scene: "night",
        desc: "Ciptakan efek jejak gerak dramatis (Shutter 1/15s atau lebih lambat) dengan exposure tetap terkontrol.",
        validate: (ap, sh, iso, ev) => (sh.sec >= 1/15 && Math.abs(ev) <= 0.7)
    },
    {
        level: 5,
        title: "Landscape Tajam Semesta: Siang Hari",
        scene: "sunny",
        desc: "Ambil pemandangan dengan ketajaman dari depan hingga gunung belakang (Aperture f/11 - f/22) dan ISO 100.",
        validate: (ap, sh, iso, ev) => (ap.fStop >= 11 && iso.val === 100 && Math.abs(ev) <= 0.6)
    }
];

// Current State
let currentApertureIdx = 4; // f/5.6
let currentShutterIdx  = 5; // 1/125s
let currentIsoIdx      = 2; // ISO 400
let currentSceneKey    = 'studio';
let currentMode        = 'sandbox'; // 'sandbox' or 'challenge'
let currentChallengeIdx = 0;
let isAudioEnabled     = true;
let isGridVisible      = true;
let snapshots          = [];

// Animation State
let cyclistX = 150;
let cyclistSpeed = 140; // px per sec
let wheelAngle = 0;
let animFrameId = null;
let lastTimestamp = performance.now();

// --- Web Audio API Camera Sound Synthesizer ---
let audioCtx = null;
function playShutterSound() {
    if (!isAudioEnabled) return;
    try {
        if (!audioCtx) {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
        if (audioCtx.state === 'suspended') {
            audioCtx.resume();
        }

        const now = audioCtx.currentTime;
        
        // 1. Shutter Mirror Flip / Click
        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.type = 'triangle';
        osc.frequency.setValueAtTime(320, now);
        osc.frequency.exponentialRampToValueAtTime(80, now + 0.08);
        gain.gain.setValueAtTime(0.6, now);
        gain.gain.exponentialRampToValueAtTime(0.01, now + 0.08);
        osc.connect(gain);
        gain.connect(audioCtx.destination);
        osc.start(now);
        osc.stop(now + 0.09);

        // 2. Camera Motor Whir (After Shutter)
        const osc2 = audioCtx.createOscillator();
        const gain2 = audioCtx.createGain();
        osc2.type = 'sawtooth';
        osc2.frequency.setValueAtTime(140, now + 0.1);
        osc2.frequency.linearRampToValueAtTime(180, now + 0.22);
        gain2.gain.setValueAtTime(0.2, now + 0.1);
        gain2.gain.exponentialRampToValueAtTime(0.01, now + 0.22);
        osc2.connect(gain2);
        gain2.connect(audioCtx.destination);
        osc2.start(now + 0.1);
        osc2.stop(now + 0.23);
    } catch(e) {
        console.warn('Audio synthesis disabled/blocked:', e);
    }
}

function toggleAudio() {
    isAudioEnabled = !isAudioEnabled;
    const icon = document.getElementById('soundIcon');
    if (isAudioEnabled) {
        icon.className = 'fa-solid fa-volume-high me-1';
    } else {
        icon.className = 'fa-solid fa-volume-xmark me-1 text-danger';
    }
}

function toggleViewfinderGrid() {
    isGridVisible = !isGridVisible;
    const grid = document.getElementById('gridOverlay');
    grid.style.opacity = isGridVisible ? '1' : '0';
}

// --- Exposure Calculations ---
function calculateExposure() {
    const ap = APERTURES[currentApertureIdx];
    const sh = SHUTTERS[currentShutterIdx];
    const iso = ISOS[currentIsoIdx];
    const sc = SCENES[currentSceneKey];

    // Total light captured = (ISO * Shutter / (Aperture^2)) * SceneBaseLight
    const totalExposure = (iso.lightFactor * sh.lightFactor * ap.lightFactor * sc.baseLight * 100);
    
    // EV (Exposure Value difference relative to ideal 1.0)
    const evDiff = Math.log2(totalExposure);
    const clampedEv = Math.max(-3.5, Math.min(3.5, evDiff));
    
    // Brightness % (100% is standard)
    const brightnessPct = Math.round(Math.max(10, Math.min(260, totalExposure * 100)));

    return {
        ev: clampedEv,
        brightnessPct: brightnessPct,
        ap: ap,
        sh: sh,
        iso: iso,
        scene: sc
    };
}

// --- Update UI & HUD ---
function updateControls() {
    currentApertureIdx = parseInt(document.getElementById('sliderAperture').value);
    currentShutterIdx  = parseInt(document.getElementById('sliderShutter').value);
    currentIsoIdx      = parseInt(document.getElementById('sliderIso').value);

    const exp = calculateExposure();

    // Update Slider Value Pills
    document.getElementById('valAperture').innerText = exp.ap.label;
    document.getElementById('valShutter').innerText  = exp.sh.label;
    document.getElementById('valIso').innerText      = exp.iso.label;

    // Update HUD Badges
    document.getElementById('hudAperture').innerText = exp.ap.label;
    document.getElementById('hudShutter').innerText  = exp.sh.label;
    document.getElementById('hudIso').innerText      = 'ISO ' + exp.iso.label;

    // Update Aperture Iris Graphic
    const iris = document.getElementById('apertureIrisVisual');
    const irisSize = Math.max(6, Math.min(50, 52 - (currentApertureIdx * 5.2)));
    iris.style.width = `${irisSize}px`;
    iris.style.height = `${irisSize}px`;

    // Update Exposure Readouts
    const evText = document.getElementById('exposureValueText');
    const evFormatted = (exp.ev >= 0 ? '+' : '') + exp.ev.toFixed(1) + ' EV';
    
    let evColor = 'text-success';
    let evStatus = '(Ideal)';
    if (exp.ev < -0.8) {
        evColor = 'text-warning';
        evStatus = '(Underexposed / Gelap)';
    } else if (exp.ev > 0.8) {
        evColor = 'text-danger';
        evStatus = '(Overexposed / Terang)';
    }
    evText.innerHTML = `<div class="fs-5 fs-sm-4 fw-bold ${evColor}">${evFormatted}</div><div class="style-tiny ${evColor} opacity-75">${evStatus}</div>`;

    // Update Brightness %
    document.getElementById('brightnessValueText').innerHTML = `<div class="fs-5 fs-sm-4 fw-bold text-info">${exp.brightnessPct}%</div><div class="style-tiny text-info opacity-75">(Kecerahan)</div>`;

    // Update Live EV Light Meter Indicator
    const meterIndicator = document.getElementById('evMeterIndicator');
    // Map -3 to +3 into 0% to 100%
    const meterPct = Math.max(5, Math.min(95, ((exp.ev + 3) / 6) * 100));
    meterIndicator.style.left = `${meterPct}%`;
    if (Math.abs(exp.ev) <= 0.6) {
        meterIndicator.className = 'position-absolute top-0 rounded-pill bg-success';
    } else if (Math.abs(exp.ev) <= 1.5) {
        meterIndicator.className = 'position-absolute top-0 rounded-pill bg-warning';
    } else {
        meterIndicator.className = 'position-absolute top-0 rounded-pill bg-danger';
    }

    // Update Feedback Diagnosis Text
    updateDiagnosis(exp);
}

function updateDiagnosis(exp) {
    const title = document.getElementById('diagnosisTitle');
    const body  = document.getElementById('diagnosisBody');
    const icon  = document.getElementById('diagnosisIcon');

    let notes = [];

    // Aperture diagnosis
    if (exp.ap.fStop <= 2.0) {
        notes.push("Bukaan lensa sangat lebar (<strong>bokeh creamy maksimal</strong>, kedalaman ruang sangat sempit).");
    } else if (exp.ap.fStop >= 11) {
        notes.push("Bukaan lensa sempit (<strong>seluruh background dan foreground tajam merata</strong>).");
    } else {
        notes.push("Bukaan lensa seimbang (depth of field natural).");
    }

    // Shutter diagnosis
    if (exp.sh.sec <= 1/1000) {
        notes.push("Shutter cepat membekukan gerakan sepeda secara instan (<strong>Freeze Motion</strong>).");
    } else if (exp.sh.sec >= 1/15) {
        notes.push("Shutter lambat menciptakan efek <strong>Motion Blur</strong> atau jejak pergerakan.");
    }

    // ISO diagnosis
    if (exp.iso.val >= 3200) {
        notes.push("ISO tinggi menimbulkan <strong>digital noise/grain</strong> pada gambar.");
    } else if (exp.iso.val <= 400) {
        notes.push("ISO rendah menjaga gambar <strong>sangat bersih tanpa noise</strong>.");
    }

    // Exposure conclusion
    if (exp.ev < -1.5) {
        title.innerText = "Foto Terlalu Gelap (Underexposed)";
        icon.className = "p-2 rounded-circle bg-warning bg-opacity-20 text-warning fs-4";
        body.innerHTML = `Pencahayaan kurang. Naikkan ISO, perlambat shutter speed, atau buka aperture lebih lebar agar foto lebih terang. <br><span class="text-secondary style-tiny mt-1 d-block">• ` + notes.join('<br>• ') + `</span>`;
    } else if (exp.ev > 1.5) {
        title.innerText = "Foto Terlalu Terang (Overexposed / Blown Out)";
        icon.className = "p-2 rounded-circle bg-danger bg-opacity-20 text-danger fs-4";
        body.innerHTML = `Pencahayaan berlebih hingga detail putih pecah. Kecilkan aperture (f/ besar), percepat shutter speed, atau turunkan ISO ke 100. <br><span class="text-secondary style-tiny mt-1 d-block">• ` + notes.join('<br>• ') + `</span>`;
    } else {
        title.innerText = "Pencahayaan Sempurna (Ideal Exposure)";
        icon.className = "p-2 rounded-circle bg-success bg-opacity-20 text-success fs-4";
        body.innerHTML = `Kombinasi Segitiga Exposure seimbang! <br><span class="text-secondary style-tiny mt-1 d-block">• ` + notes.join('<br>• ') + `</span>`;
    }
}

function changeSceneLighting(sceneKey) {
    currentSceneKey = sceneKey;
    updateControls();
}

function resetDefaults() {
    document.getElementById('sliderAperture').value = 4; // f/5.6
    document.getElementById('sliderShutter').value  = 5; // 1/125s
    document.getElementById('sliderIso').value      = 2; // ISO 400
    updateControls();
}

// --- Canvas Scene Render Loop ---
const canvas = document.getElementById('simCanvas');
const ctx = canvas.getContext('2d');

function renderScene(timestamp) {
    const deltaSec = (timestamp - lastTimestamp) / 1000;
    lastTimestamp = timestamp;

    const exp = calculateExposure();
    const w = canvas.width;
    const h = canvas.height;

    // Update Cyclist Position
    cyclistX += cyclistSpeed * deltaSec;
    if (cyclistX > w + 80) cyclistX = -80;
    wheelAngle += (cyclistSpeed * deltaSec) / 18;

    // Clear Canvas
    ctx.clearRect(0, 0, w, h);

    // 1. Render Sky & Background (Apply Aperture Bokeh Blur)
    ctx.save();
    if (exp.ap.blurPx > 0) {
        ctx.filter = `blur(${exp.ap.blurPx * 0.4}px)`;
    }

    // Sky Gradient
    const skyGrad = ctx.createLinearGradient(0, 0, 0, h * 0.7);
    skyGrad.addColorStop(0, exp.scene.skyTop);
    skyGrad.addColorStop(1, exp.scene.skyBottom);
    ctx.fillStyle = skyGrad;
    ctx.fillRect(0, 0, w, h);

    // Sun / Moon / Celestial Orb
    const sunGrad = ctx.createRadialGradient(w * 0.75, h * 0.25, 5, w * 0.75, h * 0.25, 75);
    sunGrad.addColorStop(0, exp.scene.sunColor);
    sunGrad.addColorStop(0.4, 'rgba(254, 240, 138, 0.4)');
    sunGrad.addColorStop(1, 'rgba(254, 240, 138, 0)');
    ctx.fillStyle = sunGrad;
    ctx.beginPath();
    ctx.arc(w * 0.75, h * 0.25, 75, 0, Math.PI * 2);
    ctx.fill();

    // Distant Mountains
    ctx.fillStyle = exp.scene.isNight ? '#0b1329' : (currentSceneKey === 'golden' ? '#7c2d12' : '#334155');
    ctx.beginPath();
    ctx.moveTo(0, h * 0.65);
    ctx.lineTo(w * 0.25, h * 0.35);
    ctx.lineTo(w * 0.55, h * 0.65);
    ctx.lineTo(w * 0.85, h * 0.30);
    ctx.lineTo(w, h * 0.65);
    ctx.lineTo(w, h);
    ctx.lineTo(0, h);
    ctx.closePath();
    ctx.fill();

    // Bokeh Light Circles if Wide Aperture
    if (exp.ap.fStop <= 2.8) {
        for (let i = 0; i < 6; i++) {
            const bx = (w * 0.15 * (i + 1) + 40) % w;
            const by = (h * 0.25 + (i * 25)) % (h * 0.55);
            ctx.fillStyle = 'rgba(255, 255, 255, 0.15)';
            ctx.beginPath();
            ctx.arc(bx, by, exp.ap.blurPx * 1.5, 0, Math.PI * 2);
            ctx.fill();
        }
    }
    ctx.restore();

    // 2. Render Ground & Road
    ctx.fillStyle = exp.scene.isNight ? '#020617' : '#1e293b';
    ctx.fillRect(0, h * 0.65, w, h * 0.35);

    // Road Divider Dashes
    ctx.strokeStyle = '#64748b';
    ctx.setLineDash([20, 20]);
    ctx.lineWidth = 4;
    ctx.beginPath();
    ctx.moveTo(0, h * 0.88);
    ctx.lineTo(w, h * 0.88);
    ctx.stroke();
    ctx.setLineDash([]); // Reset line dash

    // 3. Render Subject (Cyclist & Bicycle) with Motion Blur
    const mbAlpha = Math.min(0.8, exp.sh.motionBlur * 0.03);
    const mbTrails = Math.min(8, Math.floor(exp.sh.motionBlur * 0.35));

    // If slow shutter, draw ghost motion blur trails
    if (mbTrails > 0) {
        for (let t = mbTrails; t > 0; t--) {
            const trailX = cyclistX - (t * 6);
            ctx.save();
            ctx.globalAlpha = mbAlpha / (t * 0.8);
            drawCyclist(trailX, h * 0.68, wheelAngle - (t * 0.3));
            ctx.restore();
        }
    }

    // Main Sharp Cyclist Subject
    ctx.save();
    drawCyclist(cyclistX, h * 0.68, wheelAngle);
    ctx.restore();

    // 4. Apply Exposure & ISO Noise Post-Processing Layer
    applyExposureAndNoise(w, h, exp);

    animFrameId = requestAnimationFrame(renderScene);
}

// Draw Animated Bicycle & Rider (Matching User Mockup)
function drawCyclist(x, y, angle) {
    const wheelRadius = 22;
    const rearWheelX = x - 32;
    const frontWheelX = x + 32;
    const wheelY = y;

    // Wheels
    ctx.strokeStyle = '#0f172a';
    ctx.lineWidth = 5;
    ctx.beginPath();
    ctx.arc(rearWheelX, wheelY, wheelRadius, 0, Math.PI * 2);
    ctx.stroke();

    ctx.beginPath();
    ctx.arc(frontWheelX, wheelY, wheelRadius, 0, Math.PI * 2);
    ctx.stroke();

    // Spokes
    ctx.lineWidth = 1.5;
    ctx.strokeStyle = '#475569';
    for (let i = 0; i < 4; i++) {
        const rad = angle + (i * Math.PI / 2);
        ctx.beginPath();
        ctx.moveTo(rearWheelX, wheelY);
        ctx.lineTo(rearWheelX + Math.cos(rad) * wheelRadius, wheelY + Math.sin(rad) * wheelRadius);
        ctx.stroke();

        ctx.beginPath();
        ctx.moveTo(frontWheelX, wheelY);
        ctx.lineTo(frontWheelX + Math.cos(rad) * wheelRadius, wheelY + Math.sin(rad) * wheelRadius);
        ctx.stroke();
    }

    // Bike Frame (Triangular matching user mockup blue geometry)
    const bottomBracketX = x - 5;
    const bottomBracketY = y - 4;
    const saddleX = x - 14;
    const saddleY = y - 28;
    const handlebarX = x + 24;
    const handlebarY = y - 30;

    ctx.strokeStyle = '#3b82f6';
    ctx.lineWidth = 4;
    ctx.lineCap = 'round';
    ctx.beginPath();
    ctx.moveTo(rearWheelX, wheelY);
    ctx.lineTo(saddleX, saddleY);
    ctx.lineTo(bottomBracketX, bottomBracketY);
    ctx.lineTo(rearWheelX, wheelY);
    ctx.lineTo(bottomBracketX, bottomBracketY);
    ctx.lineTo(handlebarX, handlebarY);
    ctx.lineTo(frontWheelX, wheelY);
    ctx.moveTo(saddleX, saddleY);
    ctx.lineTo(handlebarX, handlebarY);
    ctx.stroke();

    // Rider Character (Head orange circle, Body green circle matching mockup)
    // Torso / Body (Green)
    ctx.fillStyle = '#4ade80';
    ctx.beginPath();
    ctx.arc(x + 2, y - 40, 12, 0, Math.PI * 2);
    ctx.fill();

    // Head (Orange)
    ctx.fillStyle = '#f97316';
    ctx.beginPath();
    ctx.arc(x + 5, y - 56, 8, 0, Math.PI * 2);
    ctx.fill();
}

// Digital Exposure Compensation & ISO Noise Shader
function applyExposureAndNoise(w, h, exp) {
    // 1. Overall Exposure Light Tint Overlay
    if (exp.ev < 0) {
        // Darken for Underexposure
        const darkAlpha = Math.min(0.92, Math.abs(exp.ev) * 0.28);
        ctx.fillStyle = `rgba(0, 0, 0, ${darkAlpha})`;
        ctx.fillRect(0, 0, w, h);
    } else if (exp.ev > 0) {
        // Brighten / Washout for Overexposure
        const lightAlpha = Math.min(0.92, exp.ev * 0.28);
        ctx.fillStyle = `rgba(255, 255, 255, ${lightAlpha})`;
        ctx.fillRect(0, 0, w, h);
    }

    // 2. High ISO Digital Sensor Grain / Noise Simulation
    if (exp.iso.noiseAmp > 0.05) {
        const noiseCount = Math.floor(w * h * 0.0006 * exp.iso.noiseAmp * 15);
        ctx.fillStyle = 'rgba(255, 255, 255, 0.4)';
        for (let i = 0; i < noiseCount; i++) {
            const rx = Math.random() * w;
            const ry = Math.random() * h;
            const sz = Math.random() < 0.2 ? 2 : 1;
            ctx.fillRect(rx, ry, sz, sz);
        }
        
        // Color chroma noise for ISO 3200+
        if (exp.iso.val >= 3200) {
            ctx.fillStyle = 'rgba(239, 68, 68, 0.35)'; // Red speckles
            for (let i = 0; i < noiseCount * 0.4; i++) {
                ctx.fillRect(Math.random() * w, Math.random() * h, 1, 1);
            }
            ctx.fillStyle = 'rgba(59, 130, 246, 0.35)'; // Blue speckles
            for (let i = 0; i < noiseCount * 0.4; i++) {
                ctx.fillRect(Math.random() * w, Math.random() * h, 1, 1);
            }
        }
    }
}

// --- Snapshot Camera Shutter Snap Feature ---
function takeSnapshot() {
    playShutterSound();

    // Trigger Visual Flash
    const flash = document.getElementById('flashOverlay');
    flash.classList.add('flash-effect');
    setTimeout(() => {
        flash.classList.remove('flash-effect');
    }, 400);

    // Capture Image Data URL from Canvas
    const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
    const exp = calculateExposure();

    const snapshot = {
        id: Date.now(),
        image: dataUrl,
        aperture: exp.ap.label,
        shutter: exp.sh.label,
        iso: exp.iso.label,
        ev: (exp.ev >= 0 ? '+' : '') + exp.ev.toFixed(1) + ' EV',
        scene: exp.scene.name,
        time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' })
    };

    snapshots.unshift(snapshot);
    renderSnapshots();

    // Check if in Challenge Mode
    if (currentMode === 'challenge') {
        evaluateChallenge();
    }
}

function renderSnapshots() {
    const gallerySection = document.getElementById('snapshotGallerySection');
    const list = document.getElementById('snapshotList');

    if (snapshots.length === 0) {
        gallerySection.style.display = 'none';
        return;
    }

    gallerySection.style.display = 'block';
    list.innerHTML = '';

    snapshots.slice(0, 4).forEach((snap) => {
        const col = document.createElement('div');
        col.className = 'col-sm-6 col-lg-3';
        col.innerHTML = `
            <div class="polaroid-card">
                <div class="overflow-hidden rounded-2 mb-2 bg-dark" style="aspect-ratio: 16/9;">
                    <img src="${snap.image}" alt="Snapshot" class="w-100 h-100 object-fit-cover">
                </div>
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="badge bg-danger font-monospace style-tiny">${snap.aperture}</span>
                    <span class="badge bg-success font-monospace style-tiny">${snap.shutter}</span>
                    <span class="badge bg-warning text-dark font-monospace style-tiny">ISO ${snap.iso}</span>
                </div>
                <div class="d-flex justify-content-between text-muted style-tiny font-monospace mt-1">
                    <span>${snap.ev}</span>
                    <span>${snap.time}</span>
                </div>
            </div>
        `;
        list.appendChild(col);
    });
}

function clearSnapshots() {
    snapshots = [];
    renderSnapshots();
}

// --- Challenge Mode Logic ---
function switchGameMode(mode) {
    currentMode = mode;
    const banner = document.getElementById('challengeBanner');
    const tabSand = document.getElementById('tabSandboxBtn');
    const tabChal = document.getElementById('tabChallengeBtn');

    if (mode === 'challenge') {
        banner.classList.remove('d-none');
        tabChal.className = 'btn btn-sm btn-warning text-dark px-3 font-heading fw-semibold';
        tabSand.className = 'btn btn-sm btn-outline-secondary px-3 font-heading fw-semibold border-0 text-body';
        loadChallenge(currentChallengeIdx);
    } else {
        banner.classList.add('d-none');
        tabSand.className = 'btn btn-sm btn-red px-3 font-heading fw-semibold';
        tabChal.className = 'btn btn-sm btn-outline-secondary px-3 font-heading fw-semibold border-0 text-body';
    }
}

function loadChallenge(idx) {
    currentChallengeIdx = idx;
    const ch = CHALLENGES[idx];

    document.getElementById('missionLevelText').innerText = `TANTANGAN LEVEL ${ch.level} / ${CHALLENGES.length}`;
    document.getElementById('missionTitleText').innerText = ch.title;
    document.getElementById('missionDescText').innerText  = ch.desc;
    document.getElementById('missionCounter').innerText   = `${idx + 1} / ${CHALLENGES.length}`;

    // Set Scene Preset for Challenge
    document.getElementById('scenePresetSelect').value = ch.scene;
    changeSceneLighting(ch.scene);
}

function nextMission() {
    if (currentChallengeIdx < CHALLENGES.length - 1) {
        currentChallengeIdx++;
        loadChallenge(currentChallengeIdx);
    } else {
        currentChallengeIdx = 0;
        loadChallenge(0);
    }
}

function prevMission() {
    if (currentChallengeIdx > 0) {
        currentChallengeIdx--;
        loadChallenge(currentChallengeIdx);
    }
}

function evaluateChallenge() {
    const ch = CHALLENGES[currentChallengeIdx];
    const exp = calculateExposure();
    const isSuccess = ch.validate(exp.ap, exp.sh, exp.iso, exp.ev);

    const modal = new bootstrap.Modal(document.getElementById('challengeSuccessModal'));
    const modalTitle = document.getElementById('modalResultTitle');
    const modalStars = document.getElementById('modalStarRating');
    const modalDesc  = document.getElementById('modalResultDesc');

    if (isSuccess) {
        modalTitle.className = 'font-heading fw-bold text-success';
        modalTitle.innerHTML = `<i class="fa-solid fa-circle-check me-2"></i> Misi ${ch.level} Berhasil!`;
        modalStars.innerText = '⭐⭐⭐';
        modalDesc.innerHTML = `Luar biasa! Pengaturan kamera Anda tepat sasaran. Exposure seimbang (${(exp.ev >= 0 ? '+' : '') + exp.ev.toFixed(1)} EV) dan efek visual sesuai kriteria misi.`;
        
        // Send AJAX score recording
        fetch('<?= base_url('mini-game/api/record-score') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                game_id: 'exposure-triangle',
                level: ch.level,
                score: 100,
                stars: 3
            })
        }).catch(err => console.log(err));

    } else {
        modalTitle.className = 'font-heading fw-bold text-warning';
        modalTitle.innerHTML = `<i class="fa-solid fa-triangle-exclamation me-2"></i> Belum Memenuhi Kriteria`;
        modalStars.innerText = '⭐';
        modalDesc.innerHTML = `Periksa kembali petunjuk misi! Pastikan exposure tidak terlalu terang/gelap dan pengaturan pilar segitiga exposure sesuai instruksi.`;
    }

    modal.show();
}

// Initialize on Window Load
window.addEventListener('DOMContentLoaded', () => {
    updateControls();
    lastTimestamp = performance.now();
    animFrameId = requestAnimationFrame(renderScene);
});
</script>
<?= $this->endSection() ?>
