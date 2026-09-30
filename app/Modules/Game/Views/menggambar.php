<?= $this->extend('layouts/master_public') ?>

<?= $this->section('content') ?>
<style>
/* =========================================================
   STUDIO GAMBAR & MEWARNAI MMC - ULTRA CLEAN & RESPONSIVE
   ========================================================= */
.draw-studio-wrapper {
    background: radial-gradient(circle at 50% 10%, #1e1035 0%, #0a0614 100%);
    border: 1px solid rgba(236, 72, 153, 0.25);
    border-radius: 20px;
    box-shadow: 0 20px 50px -15px rgba(0, 0, 0, 0.9), 0 0 30px rgba(236, 72, 153, 0.12);
    overflow: hidden;
    position: relative;
}

/* Compact Studio Top Bar */
.draw-topbar {
    background: rgba(18, 10, 36, 0.92);
    backdrop-filter: blur(14px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    padding: 8px 12px;
}

/* Duel Header Pods */
.duel-mini-pod {
    background: rgba(30, 16, 53, 0.7);
    border: 1.5px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 4px 8px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.76rem;
}
.duel-mini-pod.active-turn {
    border-color: #ec4899;
    background: rgba(236, 72, 153, 0.25);
    box-shadow: 0 0 12px rgba(236, 72, 153, 0.5);
}

/* Action Icon Buttons */
.draw-action-btn {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #e2e8f0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.82rem;
    transition: all 0.15s ease;
    cursor: pointer;
    padding: 0;
}
.draw-action-btn:hover {
    background: rgba(236, 72, 153, 0.2);
    border-color: #ec4899;
    color: #ffffff;
    transform: translateY(-1px);
}
.draw-action-btn:disabled {
    opacity: 0.35;
    pointer-events: none;
}

/* Tool Buttons */
.tool-btn {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #cbd5e1;
    border-radius: 10px;
    padding: 6px 10px;
    font-size: 0.78rem;
    font-weight: 600;
    transition: all 0.15s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    user-select: none;
}
.tool-btn:hover {
    background: rgba(236, 72, 153, 0.15);
    border-color: rgba(236, 72, 153, 0.4);
    color: #ffffff;
}
.tool-btn.active {
    background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);
    border-color: #f472b6;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(236, 72, 153, 0.4);
}

/* Color Swatches Circle */
.color-swatch-circle {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    cursor: pointer;
    transition: transform 0.15s ease;
    border: 2px solid rgba(255, 255, 255, 0.2);
    position: relative;
    flex-shrink: 0;
}
.color-swatch-circle:hover {
    transform: scale(1.15);
    border-color: #ffffff;
}
.color-swatch-circle.active {
    transform: scale(1.2);
    border-color: #ffffff;
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
}
.color-swatch-circle.active::after {
    content: '✓';
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 900;
    color: #ffffff;
    text-shadow: 0 1px 2px #000;
}

/* Canvas Area */
.canvas-drawing-area {
    position: relative;
    width: 100%;
    background: #0d0818;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    overflow: hidden;
    touch-action: none;
}
@media (min-width: 992px) {
    .canvas-drawing-area {
        min-height: 540px;
        height: calc(100vh - 270px);
        max-height: 680px;
    }
}
@media (max-width: 991px) {
    .canvas-drawing-area {
        padding: 6px;
        min-height: auto;
    }
}

.canvas-viewport-card {
    position: relative;
    border-radius: 14px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8);
    background: #ffffff;
    overflow: hidden;
    cursor: crosshair;
    display: inline-block;
    touch-action: none;
    user-select: none;
    -webkit-user-select: none;
    max-width: 100%;
}

/* Dual Canvas Overlays */
#mainDrawCanvas {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
    display: block;
    touch-action: none;
    width: 100%;
    height: auto;
}
#overlayTemplateCanvas {
    position: relative;
    z-index: 2;
    display: block;
    pointer-events: none;
    width: 100%;
    height: auto;
}

/* Clipart Stamp Buttons */
.stamp-btn {
    font-size: 1.25rem;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.12);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s ease;
    flex-shrink: 0;
}
.stamp-btn:hover {
    transform: scale(1.12);
    background: rgba(236, 72, 153, 0.2);
    border-color: #ec4899;
}
.stamp-btn.active {
    background: #ec4899;
    border-color: #f472b6;
    box-shadow: 0 0 10px rgba(236, 72, 153, 0.6);
}

/* Sketch Template Cards */
.sketch-template-card {
    background: rgba(30, 16, 53, 0.7);
    border: 1.5px solid rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    padding: 6px 8px;
    cursor: pointer;
    transition: all 0.15s ease;
    text-align: center;
}
.sketch-template-card:hover {
    border-color: rgba(236, 72, 153, 0.5);
    background: rgba(236, 72, 153, 0.15);
}
.sketch-template-card.active {
    border-color: #ec4899;
    background: rgba(236, 72, 153, 0.25);
    box-shadow: 0 0 12px rgba(236, 72, 153, 0.35);
}

/* Background Patterns */
.bg-paper-white {
    background-color: #ffffff;
}
.bg-paper-grid {
    background-color: #ffffff;
    background-image: linear-gradient(rgba(203, 213, 225, 0.5) 1px, transparent 1px),
                      linear-gradient(90deg, rgba(203, 213, 225, 0.5) 1px, transparent 1px);
    background-size: 20px 20px;
}
.bg-paper-dots {
    background-color: #ffffff;
    background-image: radial-gradient(rgba(148, 163, 184, 0.6) 1.5px, transparent 1.5px);
    background-size: 20px 20px;
}
.bg-paper-dark {
    background-color: #09090b !important;
}

/* Guess Chat Stream */
.guess-chat-box {
    height: 110px;
    overflow-y: auto;
    background: rgba(10, 6, 20, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    padding: 6px 8px;
    font-family: var(--bs-font-monospace, monospace);
    font-size: 0.74rem;
}
.guess-chat-item {
    margin-bottom: 3px;
    padding: 2px 5px;
    border-radius: 4px;
}
.guess-chat-item.wrong {
    color: #f87171;
    background: rgba(239, 68, 68, 0.1);
}
.guess-chat-item.close {
    color: #fde047;
    background: rgba(234, 179, 8, 0.15);
}
.guess-chat-item.correct {
    color: #4ade80;
    background: rgba(34, 197, 94, 0.2);
    font-weight: bold;
}
.guess-chat-item.system {
    color: #a5b4fc;
    font-style: italic;
}

/* Fullscreen Overlays */
.game-screen-overlay {
    position: absolute;
    inset: 0;
    background: rgba(8, 4, 18, 0.94);
    backdrop-filter: blur(14px);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 16px;
    z-index: 30;
    text-align: center;
    overflow-y: auto;
}

/* Mobile Bottom Accordion / Dock Settings */
@media (max-width: 991px) {
    .studio-sidebar-panel {
        background: rgba(15, 10, 28, 0.95);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding: 12px;
    }
}
</style>

<div class="container-fluid px-2 px-sm-3 px-md-4 py-2 py-md-3 max-w-7xl">
    
    <!-- Top Nav Header -->
    <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-1.5">
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('mini-game') ?>" class="btn btn-xs btn-dark border border-secondary border-opacity-25 text-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Hub
            </a>
            <span class="badge bg-pink text-white font-monospace px-2 py-1" id="gameModeBadgeTop">
                <i class="fa-solid fa-paintbrush me-1"></i> SOLO STUDIO
            </span>
        </div>

        <div class="d-flex align-items-center gap-1.5">
            <button type="button" class="btn btn-xs btn-outline-purple text-white font-heading fw-bold" onclick="openGameModeModal()">
                <i class="fa-solid fa-gamepad me-1"></i> <span class="d-none d-sm-inline">Ganti </span>Mode
            </button>
            <button type="button" class="btn btn-xs btn-pink text-white font-heading fw-bold" onclick="exportArtworkModal()">
                <i class="fa-solid fa-download me-1"></i> Simpan
            </button>
        </div>
    </div>

    <!-- Main Drawing Studio Wrapper -->
    <div class="draw-studio-wrapper mb-3">

        <!-- =========================================================
             1. COMPACT TOPBAR (ACTIONS & DUEL STATUS)
             ========================================================= -->
        <div class="draw-topbar d-flex align-items-center justify-content-between flex-wrap gap-2">
            
            <!-- Left Brand Title / Multiplayer Pods -->
            <div class="d-flex align-items-center gap-2">
                <div id="singleModeHeaderTitle" class="d-flex align-items-center gap-2">
                    <span class="fs-5 text-pink"><i class="fa-solid fa-palette"></i></span>
                    <div>
                        <div class="fw-bold font-heading text-white style-tiny lh-1">MMC DRAW</div>
                        <div class="text-secondary" style="font-size: 0.68rem;" id="activeToolBadge">Kuas Halus</div>
                    </div>
                </div>

                <!-- Multiplayer Pods Header (visible in duel mode) -->
                <div id="multiplayerStatusHeader" class="d-none d-flex align-items-center gap-1.5">
                    <div class="duel-mini-pod active-turn" id="podPlayer1">
                        <span id="podP1Avatar">🎨</span>
                        <span class="fw-bold text-white text-truncate" style="max-width: 70px;" id="podP1Name">P1</span>
                        <span class="text-warning font-monospace fw-bold" id="podP1Score">0</span>
                    </div>

                    <div class="px-2 py-0.5 rounded-2 bg-black border border-secondary border-opacity-30 text-center font-monospace" style="font-size: 0.72rem;">
                        <span class="text-warning fw-bold"><i class="fa-solid fa-stopwatch me-0.5"></i><span id="turnTimerText">45s</span></span>
                        <span class="text-secondary ms-1 d-none d-sm-inline" id="roundStatusLabel">R1/6</span>
                    </div>

                    <div class="duel-mini-pod" id="podPlayer2">
                        <span id="podP2Avatar">🧐</span>
                        <span class="fw-bold text-white text-truncate" style="max-width: 70px;" id="podP2Name">P2</span>
                        <span class="text-info font-monospace fw-bold" id="podP2Score">0</span>
                    </div>
                </div>
            </div>

            <!-- Right Quick Control Action Icons -->
            <div class="d-flex align-items-center gap-1">
                <button type="button" class="draw-action-btn" id="btnUndo" onclick="undoCanvas()" title="Undo (Ctrl+Z)" disabled>
                    <i class="fa-solid fa-rotate-left text-info"></i>
                </button>
                <button type="button" class="draw-action-btn" id="btnRedo" onclick="redoCanvas()" title="Redo (Ctrl+Y)" disabled>
                    <i class="fa-solid fa-rotate-right text-info"></i>
                </button>
                <button type="button" class="draw-action-btn text-danger" onclick="confirmClearCanvas()" title="Hapus Kanvas">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
                <button type="button" class="draw-action-btn text-warning" onclick="openGalleryModal()" title="Buka Galeri">
                    <i class="fa-solid fa-images"></i>
                </button>
                <button type="button" class="draw-action-btn text-secondary" onclick="toggleDrawAudio()" title="Suara FX">
                    <i class="fa-solid fa-volume-high text-pink" id="drawSoundIcon"></i>
                </button>
            </div>

        </div>

        <!-- =========================================================
             2. MAIN WORKSPACE (CANVAS FIRST ON MOBILE!)
             ========================================================= -->
        <div class="row g-0 flex-column flex-lg-row">
            
            <!-- CANVAS VIEWPORT (Hero area - Order 1 on mobile) -->
            <div class="col-lg-8 col-xl-9 p-0 order-1 order-lg-2 d-flex flex-column bg-black position-relative">
                
                <!-- Canvas Area Viewport -->
                <div class="canvas-drawing-area" id="canvasAreaContainer">
                    <div class="canvas-viewport-card bg-paper-white" id="canvasViewportCard">
                        <canvas id="mainDrawCanvas" width="900" height="600"></canvas>
                        <canvas id="overlayTemplateCanvas" width="900" height="600"></canvas>
                    </div>
                </div>

                <!-- Canvas Sub-Bar (BG Selector, Cursor Coord, Tip) -->
                <div class="p-1.5 px-3 bg-dark bg-opacity-75 border-top border-secondary border-opacity-25 d-flex justify-content-between align-items-center style-tiny text-secondary flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="font-monospace"><i class="fa-solid fa-image me-1"></i> Kanvas:</span>
                        <select id="selectCanvasBg" class="form-select form-select-sm bg-black text-white border-secondary border-opacity-50 font-monospace style-tiny py-0" style="width: auto; height: 24px; font-size: 0.72rem;" onchange="changeCanvasBg(this.value)">
                            <option value="white" selected>Putih</option>
                            <option value="grid">Grid Kotak</option>
                            <option value="dots">Titik (Dots)</option>
                            <option value="dark">Gelap</option>
                        </select>
                    </div>
                    <div class="font-monospace style-tiny">
                        <span class="d-none d-sm-inline"><i class="fa-solid fa-crosshairs text-pink me-1"></i> <span id="cursorCoordLabel">0, 0</span> | </span>
                        <span class="text-warning"><i class="fa-solid fa-circle-check me-0.5"></i> Auto-Save</span>
                    </div>
                </div>

                <!-- ==========================================
                     OVERLAY 1: MULTIPLAYER TURN SECRET WORD
                     ========================================== -->
                <div class="game-screen-overlay d-none" id="turnSecretOverlay">
                    <div class="mb-2">
                        <div class="p-2 rounded-circle bg-purple bg-opacity-20 text-purple fs-2 d-inline-flex mb-1 border border-purple border-opacity-30">
                            <i class="fa-solid fa-user-ninja"></i>
                        </div>
                        <h4 class="text-white font-heading fw-bold fs-6 mb-0" id="turnSecretTitle">GILIRAN MENGGAMBAR</h4>
                        <p class="text-warning style-tiny mb-2" id="turnSecretWarning">Tutup mata Penebak! Kata rahasia hanya untuk Penggambar.</p>
                    </div>

                    <div class="p-2.5 rounded-3 bg-dark bg-opacity-90 border border-purple border-opacity-40 max-w-sm w-100 mb-2 text-center">
                        <div class="style-tiny font-monospace text-secondary mb-1">KATEGORI: <strong class="text-info" id="revealWordCategory">UMUM</strong></div>
                        <div class="d-none my-1.5 p-2 rounded-2 bg-black border border-warning" id="secretWordValueBox">
                            <div class="fs-3 font-heading fw-bold text-warning letter-spacing-2" id="revealSecretWordText">DRONE</div>
                        </div>
                        <button type="button" class="btn btn-warning text-dark font-heading fw-bold px-3 py-1.5 w-100 shadow style-tiny" id="btnRevealSecretWord" onclick="revealAndStartDrawingTurn()">
                            <i class="fa-solid fa-eye me-1"></i> LIHAT KATA RAHASIA & MULAI
                        </button>
                    </div>
                </div>

                <!-- ==========================================
                     OVERLAY 2: ROUND RESULT POPUP
                     ========================================== -->
                <div class="game-screen-overlay d-none" id="roundResultOverlay">
                    <div class="mb-2">
                        <div class="p-2 rounded-circle bg-success bg-opacity-20 text-success fs-2 d-inline-flex mb-1 border border-success border-opacity-30" id="roundResultIcon">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <h4 class="text-white font-heading fw-bold fs-5 mb-0" id="roundResultTitle">TEBAKAN TEPAT!</h4>
                    </div>

                    <div class="p-2.5 rounded-3 bg-dark bg-opacity-90 border border-secondary border-opacity-30 max-w-sm w-100 mb-2 text-center">
                        <div class="style-tiny font-monospace text-secondary">KATA YANG BENAR:</div>
                        <div class="fs-4 font-heading fw-bold text-warning mb-1" id="roundResultWord">KAMERA</div>
                        <div class="d-flex justify-content-around py-1 border-top border-secondary border-opacity-25 font-monospace style-tiny">
                            <div class="text-white" id="roundP1Points">+100 PTS</div>
                            <div class="text-secondary">|</div>
                            <div class="text-info" id="roundP2Points">+150 PTS</div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-pink text-white font-heading fw-bold px-3 py-1.5 shadow style-tiny" onclick="nextMultiplayerRound()">
                        <i class="fa-solid fa-forward-step me-1"></i> Ronde Berikutnya
                    </button>
                </div>

                <!-- ==========================================
                     OVERLAY 3: VICTORY PODIUM
                     ========================================== -->
                <div class="game-screen-overlay d-none" id="multiplayerVictoryOverlay">
                    <div class="mb-2">
                        <div class="p-2 rounded-circle bg-warning bg-opacity-20 text-warning fs-2 d-inline-flex mb-1 border border-warning border-opacity-30">
                            <i class="fa-solid fa-crown"></i>
                        </div>
                        <h3 class="text-white font-heading fw-bold fs-5 mb-0" id="victoryPodiumTitle">PLAYER 1 MENANG!</h3>
                    </div>

                    <div class="p-2.5 rounded-3 bg-dark bg-opacity-90 border border-secondary border-opacity-30 max-w-sm w-100 mb-2">
                        <div class="row g-2 text-center">
                            <div class="col-6">
                                <div class="p-1.5 rounded-2 bg-black border border-secondary border-opacity-25">
                                    <div class="fs-4" id="finalP1Avatar">🎨</div>
                                    <div class="fw-bold text-white style-tiny text-truncate" id="finalP1Name">P1</div>
                                    <div class="fs-5 font-heading fw-bold text-warning" id="finalP1Score">0</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-1.5 rounded-2 bg-black border border-secondary border-opacity-25">
                                    <div class="fs-4" id="finalP2Avatar">🧐</div>
                                    <div class="fw-bold text-white style-tiny text-truncate" id="finalP2Name">P2</div>
                                    <div class="fs-5 font-heading fw-bold text-info" id="finalP2Score">0</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-pink text-white font-heading fw-bold px-3 py-1.5 style-tiny" onclick="startMultiplayerDuelSession()">
                            <i class="fa-solid fa-rotate-right me-1"></i> Main Lagi
                        </button>
                        <button type="button" class="btn btn-outline-secondary text-white px-3 py-1.5 style-tiny" onclick="setMasterGameMode('single')">
                            <i class="fa-solid fa-palette me-1"></i> Solo Studio
                        </button>
                    </div>
                </div>

            </div>

            <!-- SIDEBAR / BOTTOM DOCK TOOLS (Order 2 on mobile) -->
            <div class="col-lg-4 col-xl-3 bg-dark bg-opacity-90 border-end border-secondary border-opacity-25 p-2.5 p-md-3 studio-sidebar-panel order-2 order-lg-1">
                
                <!-- MULTIPLAYER GUESS BOX & CLUE (Displayed in Duel Mode) -->
                <div id="multiplayerGuessPanel" class="d-none mb-2.5 p-2 rounded-3 bg-black bg-opacity-70 border border-purple border-opacity-40">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="style-tiny font-monospace text-warning fw-bold"><i class="fa-solid fa-eye me-1"></i> KATA TEBAKAN:</span>
                        <span class="badge bg-purple style-tiny font-monospace" id="secretCategoryBadge">UMUM</span>
                    </div>

                    <div class="p-1.5 bg-dark rounded-2 border border-secondary border-opacity-30 text-center mb-1.5">
                        <div class="fs-4 font-heading fw-bold font-monospace text-white" style="letter-spacing: 3px;" id="secretWordClueDisplay">_ _ _ _ _</div>
                        <div class="style-tiny text-secondary" id="secretWordClueSubtitle">5 Huruf</div>
                    </div>

                    <div id="guesserInputBox" class="mb-1.5">
                        <div class="input-group input-group-sm">
                            <input type="text" id="inputGuessWord" class="form-control bg-black text-white border-secondary border-opacity-50 font-monospace" placeholder="Ketik tebakanmu..." maxlength="30" onkeydown="if(event.key==='Enter') submitPlayerGuess()">
                            <button class="btn btn-purple text-white font-heading fw-bold" type="button" onclick="submitPlayerGuess()">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>

                    <button type="button" class="btn btn-xs btn-outline-warning style-tiny font-monospace w-100 py-1 mb-1.5" id="btnRequestHint" onclick="requestLetterHint()">
                        <i class="fa-solid fa-lightbulb me-1"></i> Buka 1 Huruf Petunjuk (-50 Pts)
                    </button>

                    <div class="guess-chat-box" id="guessChatStream">
                        <div class="guess-chat-item system">Duel 2-Player Tebak Gambar aktif!</div>
                    </div>
                </div>

                <!-- SINGLE PLAYER SUB-MODE SWITCH (Bebas vs Mewarnai) -->
                <div id="singleModeSwitchSection" class="mb-2.5">
                    <div class="d-flex gap-1.5">
                        <button type="button" class="tool-btn active flex-fill justify-content-center py-1.5" id="modeBtn_free" onclick="switchSingleSubMode('free')">
                            <i class="fa-solid fa-pencil"></i> <span>Gambar Bebas</span>
                        </button>
                        <button type="button" class="tool-btn flex-fill justify-content-center py-1.5" id="modeBtn_color" onclick="switchSingleSubMode('coloring')">
                            <i class="fa-solid fa-book-open"></i> <span>Buku Mewarnai</span>
                        </button>
                    </div>
                </div>

                <!-- TEMPLATE SELECTOR FOR COLORING BOOK -->
                <div id="coloringTemplatesSection" class="d-none mb-2.5 p-2 rounded-3 bg-black bg-opacity-50 border border-pink border-opacity-30">
                    <span class="style-tiny font-monospace text-pink fw-bold d-block mb-1.5"><i class="fa-solid fa-layer-group me-1"></i> PILIH SKETSA:</span>
                    <div class="row g-1.5 text-center" id="templateListGrid"></div>
                </div>

                <!-- DRAWING TOOLS GRID -->
                <div class="mb-2.5">
                    <div class="row g-1">
                        <div class="col-4">
                            <button type="button" class="tool-btn w-100 justify-content-center active" id="tool_brush" onclick="selectTool('brush')" title="Kuas Halus">
                                <i class="fa-solid fa-paintbrush text-pink"></i> <span>Kuas</span>
                            </button>
                        </div>
                        <div class="col-4">
                            <button type="button" class="tool-btn w-100 justify-content-center" id="tool_pencil" onclick="selectTool('pencil')" title="Pensil Tajam">
                                <i class="fa-solid fa-pencil text-info"></i> <span>Pensil</span>
                            </button>
                        </div>
                        <div class="col-4">
                            <button type="button" class="tool-btn w-100 justify-content-center" id="tool_neon" onclick="selectTool('neon')" title="Kuas Neon Glow">
                                <i class="fa-solid fa-wand-magic-sparkles text-warning"></i> <span>Neon</span>
                            </button>
                        </div>
                        <div class="col-4">
                            <button type="button" class="tool-btn w-100 justify-content-center" id="tool_spray" onclick="selectTool('spray')" title="Cat Semprot">
                                <i class="fa-solid fa-spray-can text-success"></i> <span>Spray</span>
                            </button>
                        </div>
                        <div class="col-4">
                            <button type="button" class="tool-btn w-100 justify-content-center" id="tool_fill" onclick="selectTool('fill')" title="Ember Cat Tumpah">
                                <i class="fa-solid fa-fill-drip text-purple"></i> <span>Fill</span>
                            </button>
                        </div>
                        <div class="col-4">
                            <button type="button" class="tool-btn w-100 justify-content-center" id="tool_eraser" onclick="selectTool('eraser')" title="Penghapus">
                                <i class="fa-solid fa-eraser text-danger"></i> <span>Hapus</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- SHAPES & STEMPEL -->
                <div class="mb-2.5">
                    <div class="d-flex gap-1 flex-wrap mb-1.5">
                        <button type="button" class="tool-btn px-2 py-1" id="shape_line" onclick="selectShape('line')" title="Garis"><i class="fa-solid fa-minus"></i></button>
                        <button type="button" class="tool-btn px-2 py-1" id="shape_rect" onclick="selectShape('rect')" title="Kotak"><i class="fa-regular fa-square"></i></button>
                        <button type="button" class="tool-btn px-2 py-1" id="shape_circle" onclick="selectShape('circle')" title="Lingkaran"><i class="fa-regular fa-circle"></i></button>
                        <button type="button" class="tool-btn px-2 py-1" id="shape_star" onclick="selectShape('star')" title="Bintang"><i class="fa-regular fa-star"></i></button>
                        <button type="button" class="tool-btn px-2 py-1" id="shape_triangle" onclick="selectShape('triangle')" title="Segitiga"><i class="fa-solid fa-play fa-rotate-270"></i></button>
                    </div>

                    <!-- Clipart Stamp Row -->
                    <div class="p-1.5 rounded-2 bg-black bg-opacity-40 border border-secondary border-opacity-25 d-flex gap-1 overflow-x-auto" id="stampListRow">
                        <button type="button" class="stamp-btn" onclick="selectStamp('📷')">📷</button>
                        <button type="button" class="stamp-btn" onclick="selectStamp('🛸')">🛸</button>
                        <button type="button" class="stamp-btn" onclick="selectStamp('🎬')">🎬</button>
                        <button type="button" class="stamp-btn" onclick="selectStamp('💻')">💻</button>
                        <button type="button" class="stamp-btn" onclick="selectStamp('☕')">☕</button>
                        <button type="button" class="stamp-btn" onclick="selectStamp('🔥')">🔥</button>
                        <button type="button" class="stamp-btn" onclick="selectStamp('⭐')">⭐</button>
                        <button type="button" class="stamp-btn" onclick="selectStamp('❤️')">❤️</button>
                        <button type="button" class="stamp-btn" onclick="selectStamp('🐱')">🐱</button>
                        <button type="button" class="stamp-btn" onclick="selectStamp('⚡')">⚡</button>
                        <button type="button" class="stamp-btn" onclick="selectStamp('👑')">👑</button>
                    </div>
                </div>

                <!-- SIZE & OPACITY SLIDERS -->
                <div class="mb-2.5 p-2 rounded-2 bg-black bg-opacity-40 border border-secondary border-opacity-25">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="style-tiny font-monospace text-secondary">Ukuran: <strong class="text-white" id="brushSizeLabel">12px</strong></span>
                        <div class="brush-preview-circle" id="brushSizeDot" style="width: 12px; height: 12px; background: #ec4899;"></div>
                    </div>
                    <input type="range" class="form-range py-0 mb-1" id="inputBrushSize" min="1" max="60" value="12" oninput="updateBrushSize(this.value)">

                    <div class="d-flex justify-content-between align-items-center mb-0.5">
                        <span class="style-tiny font-monospace text-secondary">Opasitas: <strong class="text-white" id="brushOpacityLabel">100%</strong></span>
                    </div>
                    <input type="range" class="form-range py-0" id="inputBrushOpacity" min="10" max="100" value="100" oninput="updateBrushOpacity(this.value)">
                </div>

                <!-- COLOR PALETTE & PICKER -->
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-1.5">
                        <div class="d-flex gap-1">
                            <button type="button" class="tool-tab-pill active px-2 py-0.5" id="paletteTab_pop" onclick="switchPaletteTheme('pop')">Pop</button>
                            <button type="button" class="tool-tab-pill px-2 py-0.5" id="paletteTab_neon" onclick="switchPaletteTheme('neon')">Neon</button>
                            <button type="button" class="tool-tab-pill px-2 py-0.5" id="paletteTab_pastel" onclick="switchPaletteTheme('pastel')">Pastel</button>
                            <button type="button" class="tool-tab-pill px-2 py-0.5" id="paletteTab_earth" onclick="switchPaletteTheme('earth')">Alam</button>
                        </div>
                        <input type="color" id="customColorPicker" value="#ec4899" class="form-control form-control-color bg-transparent border-0 p-0" style="width: 22px; height: 22px; cursor: pointer;" onchange="selectCustomColor(this.value)">
                    </div>

                    <div class="d-flex flex-wrap gap-1.5" id="colorSwatchesContainer"></div>
                </div>

            </div>

        </div>

    </div>

</div>

<!-- =========================================================
     MODAL 0: GAME MODE & DUEL SETUP
     ========================================================= -->
<div class="modal fade" id="gameModeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content bg-dark border border-purple border-opacity-50 text-white rounded-4 shadow-2xl p-2">
            <div class="modal-header border-bottom border-secondary border-opacity-25 pb-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-gamepad text-purple fs-5"></i>
                    <h6 class="modal-title font-heading fw-bold mb-0">Pilih Mode Permainan</h6>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body py-2.5">
                <div class="d-flex gap-2 mb-2.5">
                    <button type="button" class="tool-btn flex-fill justify-content-center active py-2" id="modalBtn_single" onclick="setModalGameMode('single')">
                        <i class="fa-solid fa-paintbrush"></i> <span>Solo Studio</span>
                    </button>
                    <button type="button" class="tool-btn flex-fill justify-content-center py-2" id="modalBtn_multi" onclick="setModalGameMode('multiplayer')">
                        <i class="fa-solid fa-user-group"></i> <span>2-Player Duel</span>
                    </button>
                </div>

                <div id="modalSingleInfo" class="p-2 rounded-2 bg-black bg-opacity-50 border border-secondary border-opacity-25 style-tiny text-secondary">
                    Kanvas bebas & buku mewarnai sketsa dengan cat tumpah, kuas neon, dan simpan PNG.
                </div>

                <div id="modalMultiConfig" class="d-none p-2 rounded-2 bg-black bg-opacity-50 border border-purple border-opacity-30 style-tiny">
                    <div class="mb-2">
                        <label class="form-label style-tiny text-secondary font-monospace mb-0.5">PLAYER 1 & 2:</label>
                        <div class="row g-1">
                            <div class="col-6">
                                <input type="text" id="setupP1Name" class="form-control form-control-sm bg-black text-white border-secondary border-opacity-50 font-monospace" value="Player 1" maxlength="10">
                            </div>
                            <div class="col-6">
                                <input type="text" id="setupP2Name" class="form-control form-control-sm bg-black text-white border-secondary border-opacity-50 font-monospace" value="Player 2" maxlength="10">
                            </div>
                        </div>
                    </div>
                    <div class="row g-1">
                        <div class="col-6">
                            <label class="form-label style-tiny text-secondary font-monospace mb-0.5">RONDE:</label>
                            <select id="setupRoundsCount" class="form-select form-select-sm bg-black text-warning border-secondary border-opacity-50 font-monospace py-0">
                                <option value="4">4 Ronde</option>
                                <option value="6" selected>6 Ronde</option>
                                <option value="8">8 Ronde</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label style-tiny text-secondary font-monospace mb-0.5">TIMER:</label>
                            <select id="setupTurnDuration" class="form-select form-select-sm bg-black text-info border-secondary border-opacity-50 font-monospace py-0">
                                <option value="30">30 Detik</option>
                                <option value="45" selected>45 Detik</option>
                                <option value="60">60 Detik</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-top border-secondary border-opacity-25 pt-2">
                <button type="button" class="btn btn-sm btn-pink text-white font-heading fw-bold w-100" onclick="applyGameModeSelection()" data-bs-dismiss="modal">
                    <i class="fa-solid fa-play me-1"></i> Terapkan Mode
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     MODAL 1: EXPORT & DOWNLOAD ARTWORK PREVIEW
     ========================================================= -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content bg-dark border border-pink border-opacity-50 text-white rounded-4 shadow-2xl p-2">
            <div class="modal-header border-bottom border-secondary border-opacity-25 pb-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-file-arrow-down text-pink fs-5"></i>
                    <h6 class="modal-title font-heading fw-bold mb-0">Unduh Hasil Lukisan</h6>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body py-2.5 text-center">
                <div class="p-1.5 rounded-2 bg-black border border-secondary border-opacity-30 mb-2">
                    <img id="exportPreviewImg" src="" alt="Preview" class="img-fluid rounded shadow" style="max-height: 180px;">
                </div>
                <div class="text-start mb-2">
                    <label class="form-label style-tiny text-secondary font-monospace mb-0.5">JUDUL KARYA:</label>
                    <input type="text" id="inputArtTitle" class="form-control form-control-sm bg-black text-white border-secondary border-opacity-50 font-monospace fw-bold" value="Karya Kreatif MMC" maxlength="30">
                </div>
                <button type="button" class="btn btn-sm btn-pink text-white font-heading fw-bold w-100 py-2 shadow" onclick="processDownloadPNG()">
                    <i class="fa-solid fa-download me-1"></i> Unduh File PNG (+5 Pts)
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     MODAL 2: LOCAL GALLERY
     ========================================================= -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
        <div class="modal-content bg-dark border border-warning border-opacity-50 text-white rounded-4 shadow-2xl p-2">
            <div class="modal-header border-bottom border-secondary border-opacity-25 pb-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-images text-warning fs-5"></i>
                    <h6 class="modal-title font-heading fw-bold mb-0">Galeri Karya Tersimpan</h6>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body py-2.5" id="galleryModalBody"></div>

            <div class="modal-footer border-top border-secondary border-opacity-25 pt-2">
                <button type="button" class="btn btn-xs btn-outline-danger me-auto" onclick="clearLocalGallery()">
                    <i class="fa-solid fa-trash me-1"></i> Hapus Semua
                </button>
                <button type="button" class="btn btn-xs btn-warning text-dark font-heading fw-bold px-3" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     MODAL 3: CONFIRM CLEAR CANVAS
     ========================================================= -->
<div class="modal fade" id="clearConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content bg-dark border border-danger border-opacity-50 text-white rounded-4 text-center p-3">
            <div class="p-2 rounded-circle bg-danger bg-opacity-20 text-danger fs-3 d-inline-flex mb-2">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            <h6 class="font-heading fw-bold mb-1">Bersihkan Kanvas?</h6>
            <p class="text-secondary style-tiny mb-3">Seluruh lukisan di kanvas saat ini akan dihapus.</p>
            <div class="d-flex gap-2 justify-content-center">
                <button type="button" class="btn btn-sm btn-outline-secondary text-white px-3" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-danger px-3 font-heading fw-bold" onclick="executeClearCanvas()" data-bs-dismiss="modal">Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     MMC DRAW HTML5 CANVAS & MULTIPLAYER JAVASCRIPT ENGINE
     ========================================================= -->
<script>
// --- Canvas Elements & Contexts ---
const drawCanvas = document.getElementById('mainDrawCanvas');
const drawCtx = drawCanvas.getContext('2d', { willReadFrequently: true });
const overlayCanvas = document.getElementById('overlayTemplateCanvas');
const overlayCtx = overlayCanvas.getContext('2d');
const viewportCard = document.getElementById('canvasViewportCard');

// --- Creative Studio State ---
let studioState = {
    masterMode: 'single', // 'single' | 'multiplayer'
    singleSubMode: 'free', // 'free' | 'coloring'
    tool: 'brush', // 'brush', 'pencil', 'neon', 'spray', 'fill', 'eraser', 'shape', 'stamp'
    shapeType: 'line', // 'line', 'rect', 'circle', 'star', 'triangle'
    stampEmoji: '📷',
    brushSize: 12,
    brushOpacity: 1.0,
    activeColor: '#ec4899',
    canvasBg: 'white',
    activeTemplateId: null,
    audioEnabled: true,

    // Drawing Interaction
    isDrawing: false,
    startX: 0,
    startY: 0,
    lastX: 0,
    lastY: 0,

    // Undo / Redo History Stacks
    history: [],
    historyIndex: -1,
    maxHistory: 20
};

// --- Multiplayer Pictionary Duel State ---
let duelState = {
    p1Name: 'Player 1',
    p1Avatar: '🎨',
    p1Score: 0,
    p2Name: 'Player 2',
    p2Avatar: '🧐',
    p2Score: 0,

    totalRounds: 6,
    currentRound: 1,
    drawerIndex: 1,
    
    currentSecretWord: null,
    revealedHintCount: 0,
    turnDuration: 45,
    timerRemaining: 45,
    timerInterval: null,
    isTurnActive: false,
    usedWords: []
};

// --- Comprehensive Pictionary Word Bank ---
const PICTIONARY_WORDS = [
    { word: 'KAMERA', category: 'Multimedia & IT' },
    { word: 'DRONE', category: 'Multimedia & IT' },
    { word: 'KOMPUTER', category: 'Multimedia & IT' },
    { word: 'KEYBOARD', category: 'Multimedia & IT' },
    { word: 'TRIPOD', category: 'Multimedia & IT' },
    { word: 'LENSA', category: 'Multimedia & IT' },
    { word: 'MOUSE', category: 'Multimedia & IT' },
    { word: 'MIKROFON', category: 'Multimedia & IT' },
    { word: 'HEADPHONE', category: 'Multimedia & IT' },
    { word: 'PROYEKTOR', category: 'Multimedia & IT' },
    { word: 'ROBOT', category: 'Multimedia & IT' },
    { word: 'FLASH', category: 'Multimedia & IT' },
    { word: 'BAKSO', category: 'Makanan & Minuman' },
    { word: 'MARTABAK', category: 'Makanan & Minuman' },
    { word: 'KOPI', category: 'Makanan & Minuman' },
    { word: 'ES KRIM', category: 'Makanan & Minuman' },
    { word: 'PIZZA', category: 'Makanan & Minuman' },
    { word: 'DONAT', category: 'Makanan & Minuman' },
    { word: 'SEMANGKA', category: 'Makanan & Minuman' },
    { word: 'PISANG', category: 'Makanan & Minuman' },
    { word: 'BURGER', category: 'Makanan & Minuman' },
    { word: 'KUCING', category: 'Hewan & Alam' },
    { word: 'GAJAH', category: 'Hewan & Alam' },
    { word: 'KUPU KUPU', category: 'Hewan & Alam' },
    { word: 'IKAN', category: 'Hewan & Alam' },
    { word: 'SINGA', category: 'Hewan & Alam' },
    { word: 'BURUNG', category: 'Hewan & Alam' },
    { word: 'GUNUNG', category: 'Hewan & Alam' },
    { word: 'MATAHARI', category: 'Hewan & Alam' },
    { word: 'PELANGI', category: 'Hewan & Alam' },
    { word: 'POHON', category: 'Hewan & Alam' },
    { word: 'MOBIL', category: 'Kendaraan & Benda' },
    { word: 'PESAWAT', category: 'Kendaraan & Benda' },
    { word: 'SEPEDA', category: 'Kendaraan & Benda' },
    { word: 'KAPAL', category: 'Kendaraan & Benda' },
    { word: 'ROKET', category: 'Kendaraan & Benda' },
    { word: 'JAM', category: 'Kendaraan & Benda' },
    { word: 'PAYUNG', category: 'Kendaraan & Benda' },
    { word: 'GITAR', category: 'Kendaraan & Benda' },
    { word: 'MAHKOTA', category: 'Kendaraan & Benda' },
    { word: 'RUMAH', category: 'Kendaraan & Benda' }
];

// --- Color Palette Collections ---
const PALETTE_THEMES = {
    pop: ['#000000', '#ffffff', '#ef4444', '#f97316', '#fbbf24', '#10b981', '#06b6d4', '#3b82f6', '#8b5cf6', '#ec4899', '#78350f', '#64748b'],
    neon: ['#ff007f', '#00f0ff', '#39ff14', '#ffff00', '#ff073a', '#bc13fe', '#00e5ff', '#ffaa00', '#00ffcc', '#ff1493'],
    pastel: ['#fbcfe8', '#fed7aa', '#fef08a', '#bbf7d0', '#a5f3fc', '#bfdbfe', '#ddd6fe', '#fecdd3', '#e2e8f0', '#f5d0fe'],
    earth: ['#292524', '#78350f', '#92400e', '#b45309', '#047857', '#065f46', '#1e3a8a', '#1e293b', '#475569', '#d97706']
};

// --- Coloring Book Templates Outline Generator ---
const COLORING_TEMPLATES = [
    {
        id: 'camera',
        title: '📷 Kamera 35mm',
        drawOutline: function(ctx, w, h) {
            ctx.save();
            ctx.strokeStyle = '#0f172a';
            ctx.lineWidth = 4;
            ctx.lineJoin = 'round';
            ctx.lineCap = 'round';
            ctx.beginPath();
            ctx.roundRect(w * 0.2, h * 0.28, w * 0.6, h * 0.48, 24);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(w * 0.38, h * 0.28);
            ctx.lineTo(w * 0.42, h * 0.18);
            ctx.lineTo(w * 0.58, h * 0.18);
            ctx.lineTo(w * 0.62, h * 0.28);
            ctx.stroke();
            ctx.beginPath();
            ctx.arc(w * 0.5, h * 0.52, h * 0.18, 0, Math.PI * 2);
            ctx.stroke();
            ctx.beginPath();
            ctx.arc(w * 0.5, h * 0.52, h * 0.13, 0, Math.PI * 2);
            ctx.stroke();
            ctx.beginPath();
            ctx.arc(w * 0.5, h * 0.52, h * 0.07, 0, Math.PI * 2);
            ctx.stroke();
            ctx.beginPath();
            ctx.rect(w * 0.26, h * 0.22, w * 0.08, h * 0.06);
            ctx.stroke();
            ctx.beginPath();
            ctx.rect(w * 0.68, h * 0.22, w * 0.09, h * 0.06);
            ctx.stroke();
            ctx.beginPath();
            ctx.roundRect(w * 0.66, h * 0.34, w * 0.10, h * 0.08, 6);
            ctx.stroke();
            ctx.restore();
        }
    },
    {
        id: 'drone',
        title: '🛸 Drone Aerial',
        drawOutline: function(ctx, w, h) {
            ctx.save();
            ctx.strokeStyle = '#0f172a';
            ctx.lineWidth = 4;
            ctx.lineJoin = 'round';
            ctx.lineCap = 'round';
            ctx.beginPath();
            ctx.ellipse(w * 0.5, h * 0.5, w * 0.14, h * 0.16, 0, 0, Math.PI * 2);
            ctx.stroke();
            ctx.beginPath();
            ctx.arc(w * 0.5, h * 0.52, h * 0.06, 0, Math.PI * 2);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(w * 0.40, h * 0.40);
            ctx.lineTo(w * 0.25, h * 0.24);
            ctx.moveTo(w * 0.60, h * 0.40);
            ctx.lineTo(w * 0.75, h * 0.24);
            ctx.moveTo(w * 0.40, h * 0.60);
            ctx.lineTo(w * 0.25, h * 0.76);
            ctx.moveTo(w * 0.60, h * 0.60);
            ctx.lineTo(w * 0.75, h * 0.76);
            ctx.stroke();
            ctx.beginPath();
            ctx.arc(w * 0.25, h * 0.24, h * 0.07, 0, Math.PI * 2);
            ctx.arc(w * 0.75, h * 0.24, h * 0.07, 0, Math.PI * 2);
            ctx.arc(w * 0.25, h * 0.76, h * 0.07, 0, Math.PI * 2);
            ctx.arc(w * 0.75, h * 0.76, h * 0.07, 0, Math.PI * 2);
            ctx.stroke();
            ctx.restore();
        }
    },
    {
        id: 'cat',
        title: '🐱 Kucing Maskot',
        drawOutline: function(ctx, w, h) {
            ctx.save();
            ctx.strokeStyle = '#0f172a';
            ctx.lineWidth = 4;
            ctx.lineJoin = 'round';
            ctx.lineCap = 'round';
            ctx.beginPath();
            ctx.arc(w * 0.5, h * 0.42, h * 0.20, 0, Math.PI * 2);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(w * 0.38, h * 0.28);
            ctx.lineTo(w * 0.34, h * 0.14);
            ctx.lineTo(w * 0.45, h * 0.23);
            ctx.moveTo(w * 0.62, h * 0.28);
            ctx.lineTo(w * 0.66, h * 0.14);
            ctx.lineTo(w * 0.55, h * 0.23);
            ctx.stroke();
            ctx.beginPath();
            ctx.arc(w * 0.43, h * 0.39, 10, 0, Math.PI * 2);
            ctx.arc(w * 0.57, h * 0.39, 10, 0, Math.PI * 2);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(w * 0.50, h * 0.49);
            ctx.lineTo(w * 0.46, h * 0.53);
            ctx.moveTo(w * 0.50, h * 0.49);
            ctx.lineTo(w * 0.54, h * 0.53);
            ctx.stroke();
            ctx.beginPath();
            ctx.roundRect(w * 0.38, h * 0.58, w * 0.24, h * 0.28, 20);
            ctx.stroke();
            ctx.restore();
        }
    },
    {
        id: 'computer',
        title: '💻 Komputer 90s',
        drawOutline: function(ctx, w, h) {
            ctx.save();
            ctx.strokeStyle = '#0f172a';
            ctx.lineWidth = 4;
            ctx.lineJoin = 'round';
            ctx.lineCap = 'round';
            ctx.beginPath();
            ctx.roundRect(w * 0.28, h * 0.16, w * 0.44, h * 0.44, 18);
            ctx.stroke();
            ctx.beginPath();
            ctx.roundRect(w * 0.32, h * 0.20, w * 0.36, h * 0.34, 12);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(w * 0.22, h * 0.72);
            ctx.lineTo(w * 0.78, h * 0.72);
            ctx.lineTo(w * 0.74, h * 0.86);
            ctx.lineTo(w * 0.26, h * 0.86);
            ctx.closePath();
            ctx.stroke();
            ctx.restore();
        }
    },
    {
        id: 'landscape',
        title: '🌄 Sunset Gunung',
        drawOutline: function(ctx, w, h) {
            ctx.save();
            ctx.strokeStyle = '#0f172a';
            ctx.lineWidth = 4;
            ctx.lineJoin = 'round';
            ctx.lineCap = 'round';
            ctx.beginPath();
            ctx.arc(w * 0.5, h * 0.38, h * 0.14, 0, Math.PI * 2);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(w * 0.1, h * 0.64);
            ctx.lineTo(w * 0.35, h * 0.32);
            ctx.lineTo(w * 0.55, h * 0.56);
            ctx.lineTo(w * 0.72, h * 0.36);
            ctx.lineTo(w * 0.90, h * 0.64);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(w * 0.1, h * 0.64);
            ctx.lineTo(w * 0.9, h * 0.64);
            ctx.stroke();
            ctx.restore();
        }
    },
    {
        id: 'badge',
        title: '⭐ Logo MMC',
        drawOutline: function(ctx, w, h) {
            ctx.save();
            ctx.strokeStyle = '#0f172a';
            ctx.lineWidth = 4;
            ctx.lineJoin = 'round';
            ctx.lineCap = 'round';
            ctx.beginPath();
            ctx.moveTo(w * 0.5, h * 0.16);
            ctx.lineTo(w * 0.75, h * 0.25);
            ctx.lineTo(w * 0.72, h * 0.64);
            ctx.lineTo(w * 0.5, h * 0.84);
            ctx.lineTo(w * 0.28, h * 0.64);
            ctx.lineTo(w * 0.25, h * 0.25);
            ctx.closePath();
            ctx.stroke();
            drawStarShape(ctx, w * 0.5, h * 0.44, 5, h * 0.14, h * 0.07);
            ctx.stroke();
            ctx.restore();
        }
    }
];

function drawStarShape(ctx, cx, cy, spikes, outerRadius, innerRadius) {
    let rot = Math.PI / 2 * 3;
    let x = cx;
    let y = cy;
    let step = Math.PI / spikes;

    ctx.beginPath();
    ctx.moveTo(cx, cy - outerRadius);
    for (let i = 0; i < spikes; i++) {
        x = cx + Math.cos(rot) * outerRadius;
        y = cy + Math.sin(rot) * outerRadius;
        ctx.lineTo(x, y);
        rot += step;
        x = cx + Math.cos(rot) * innerRadius;
        y = cy + Math.sin(rot) * innerRadius;
        ctx.lineTo(x, y);
        rot += step;
    }
    ctx.lineTo(cx, cy - outerRadius);
    ctx.closePath();
}

// --- Synthesized Web Audio API FX ---
let drawAudioCtx = null;

function initDrawAudio() {
    if (!drawAudioCtx) {
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        drawAudioCtx = new AudioContext();
    }
    if (drawAudioCtx.state === 'suspended') {
        drawAudioCtx.resume();
    }
}

function playDrawSound(type) {
    if (!studioState.audioEnabled) return;
    try {
        initDrawAudio();
        const now = drawAudioCtx.currentTime;

        if (type === 'stroke') {
            const osc = drawAudioCtx.createOscillator();
            const gain = drawAudioCtx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(320, now);
            osc.frequency.exponentialRampToValueAtTime(450, now + 0.06);
            gain.gain.setValueAtTime(0.04, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.06);
            osc.connect(gain);
            gain.connect(drawAudioCtx.destination);
            osc.start(now);
            osc.stop(now + 0.06);
        } else if (type === 'fill') {
            [300, 440, 587.33, 880].forEach((freq, i) => {
                const osc = drawAudioCtx.createOscillator();
                const gain = drawAudioCtx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(freq, now + i * 0.04);
                gain.gain.setValueAtTime(0.12, now + i * 0.04);
                gain.gain.exponentialRampToValueAtTime(0.001, now + i * 0.04 + 0.15);
                osc.connect(gain);
                gain.connect(drawAudioCtx.destination);
                osc.start(now + i * 0.04);
                osc.stop(now + i * 0.04 + 0.15);
            });
        } else if (type === 'stamp') {
            const osc = drawAudioCtx.createOscillator();
            const gain = drawAudioCtx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(520, now);
            osc.frequency.exponentialRampToValueAtTime(880, now + 0.09);
            gain.gain.setValueAtTime(0.15, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.09);
            osc.connect(gain);
            gain.connect(drawAudioCtx.destination);
            osc.start(now);
            osc.stop(now + 0.09);
        } else if (type === 'undo') {
            const osc = drawAudioCtx.createOscillator();
            const gain = drawAudioCtx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(400, now);
            osc.frequency.exponentialRampToValueAtTime(200, now + 0.12);
            gain.gain.setValueAtTime(0.12, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.12);
            osc.connect(gain);
            gain.connect(drawAudioCtx.destination);
            osc.start(now);
            osc.stop(now + 0.12);
        } else if (type === 'save' || type === 'correct') {
            [523.25, 659.25, 783.99, 1046.50].forEach((freq, i) => {
                const osc = drawAudioCtx.createOscillator();
                const gain = drawAudioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, now + i * 0.06);
                gain.gain.setValueAtTime(0.15, now + i * 0.06);
                gain.gain.exponentialRampToValueAtTime(0.001, now + i * 0.06 + 0.25);
                osc.connect(gain);
                gain.connect(drawAudioCtx.destination);
                osc.start(now + i * 0.06);
                osc.stop(now + i * 0.06 + 0.25);
            });
        }
    } catch (e) {}
}

function toggleDrawAudio() {
    studioState.audioEnabled = !studioState.audioEnabled;
    const icon = document.getElementById('drawSoundIcon');
    if (studioState.audioEnabled) {
        icon.className = 'fa-solid fa-volume-high text-pink';
    } else {
        icon.className = 'fa-solid fa-volume-xmark text-danger';
    }
}

// --- Initialization Engine ---
function initDrawingStudio() {
    renderColorSwatches('pop');
    renderTemplateCards();
    changeCanvasBg('white');
    saveCanvasState();
    setupEventListeners();
}

// --- Game Mode Switcher Logic ---
function openGameModeModal() {
    setModalGameMode(studioState.masterMode);
    const modal = new bootstrap.Modal(document.getElementById('gameModeModal'));
    modal.show();
}

function setModalGameMode(mode) {
    const isMulti = mode === 'multiplayer';
    document.getElementById('modalBtn_single').classList.toggle('active', !isMulti);
    document.getElementById('modalBtn_single').classList.toggle('btn-pink', !isMulti);

    document.getElementById('modalBtn_multi').classList.toggle('active', isMulti);
    document.getElementById('modalBtn_multi').classList.toggle('btn-purple', isMulti);

    document.getElementById('modalSingleInfo').classList.toggle('d-none', isMulti);
    document.getElementById('modalMultiConfig').classList.toggle('d-none', !isMulti);
}

function applyGameModeSelection() {
    const isMulti = document.getElementById('modalBtn_multi').classList.contains('active');
    setMasterGameMode(isMulti ? 'multiplayer' : 'single');
}

function setMasterGameMode(mode) {
    studioState.masterMode = mode;
    const isMulti = mode === 'multiplayer';

    document.getElementById('gameModeBadgeTop').innerHTML = isMulti 
        ? '<i class="fa-solid fa-user-group me-1"></i> DUEL TEBAK GAMBAR' 
        : '<i class="fa-solid fa-paintbrush me-1"></i> SOLO STUDIO';
    document.getElementById('gameModeBadgeTop').className = `badge font-monospace px-2 py-1 ${isMulti ? 'bg-purple text-white' : 'bg-pink text-white'}`;

    document.getElementById('singleModeHeaderTitle').classList.toggle('d-none', isMulti);
    document.getElementById('multiplayerStatusHeader').classList.toggle('d-none', !isMulti);

    document.getElementById('singleModeSwitchSection').classList.toggle('d-none', isMulti);
    document.getElementById('multiplayerGuessPanel').classList.toggle('d-none', !isMulti);

    if (isMulti) {
        duelState.p1Name = document.getElementById('setupP1Name').value.trim() || 'Player 1';
        duelState.p2Name = document.getElementById('setupP2Name').value.trim() || 'Player 2';
        duelState.totalRounds = parseInt(document.getElementById('setupRoundsCount').value) || 6;
        duelState.turnDuration = parseInt(document.getElementById('setupTurnDuration').value) || 45;

        startMultiplayerDuelSession();
    } else {
        clearInterval(duelState.timerInterval);
        document.getElementById('turnSecretOverlay').classList.add('d-none');
        document.getElementById('roundResultOverlay').classList.add('d-none');
        document.getElementById('multiplayerVictoryOverlay').classList.add('d-none');
        switchSingleSubMode(studioState.singleSubMode);
    }
}

// --- Multiplayer Duel Gameplay Engine ---
function startMultiplayerDuelSession() {
    clearInterval(duelState.timerInterval);
    duelState.p1Score = 0;
    duelState.p2Score = 0;
    duelState.currentRound = 1;
    duelState.drawerIndex = 1;
    duelState.usedWords = [];

    document.getElementById('podP1Name').innerText = duelState.p1Name;
    document.getElementById('podP2Name').innerText = duelState.p2Name;
    document.getElementById('guessChatStream').innerHTML = `<div class="guess-chat-item system">Duel 2-Player dimulai! Total ${duelState.totalRounds} Ronde.</div>`;

    updateMultiplayerScoreboard();
    prepareNextDuelTurn();
}

function updateMultiplayerScoreboard() {
    document.getElementById('podP1Score').innerText = duelState.p1Score;
    document.getElementById('podP2Score').innerText = duelState.p2Score;
    document.getElementById('roundStatusLabel').innerText = `R${duelState.currentRound}/${duelState.totalRounds}`;

    const isP1Drawing = duelState.drawerIndex === 1;
    document.getElementById('podPlayer1').classList.toggle('active-turn', isP1Drawing);
    document.getElementById('podPlayer2').classList.toggle('active-turn', !isP1Drawing);
}

function prepareNextDuelTurn() {
    clearInterval(duelState.timerInterval);
    executeClearCanvas();

    const availableWords = PICTIONARY_WORDS.filter(w => !duelState.usedWords.includes(w.word));
    const pool = availableWords.length > 0 ? availableWords : PICTIONARY_WORDS;
    const selectedObj = pool[Math.floor(Math.random() * pool.length)];
    duelState.currentSecretWord = selectedObj;
    duelState.usedWords.push(selectedObj.word);
    duelState.revealedHintCount = 0;

    const drawerName = duelState.drawerIndex === 1 ? duelState.p1Name : duelState.p2Name;
    const guesserName = duelState.drawerIndex === 1 ? duelState.p2Name : duelState.p1Name;

    document.getElementById('turnSecretTitle').innerText = `GILIRAN: ${drawerName.toUpperCase()}`;
    document.getElementById('turnSecretWarning').innerText = `Tutup mata ${guesserName}! Kata hanya untuk ${drawerName}.`;
    document.getElementById('revealWordCategory').innerText = selectedObj.category.toUpperCase();
    document.getElementById('revealSecretWordText').innerText = selectedObj.word;

    document.getElementById('secretWordValueBox').classList.add('d-none');
    document.getElementById('btnRevealSecretWord').classList.remove('d-none');
    document.getElementById('turnSecretOverlay').classList.remove('d-none');

    document.getElementById('secretCategoryBadge').innerText = selectedObj.category;
    updateDashesClueDisplay();
}

function revealAndStartDrawingTurn() {
    document.getElementById('secretWordValueBox').classList.remove('d-none');
    document.getElementById('btnRevealSecretWord').classList.add('d-none');

    setTimeout(() => {
        document.getElementById('turnSecretOverlay').classList.add('d-none');
        startTurnTimer();
    }, 1200);
}

function updateDashesClueDisplay() {
    const word = duelState.currentSecretWord.word;
    let clue = '';

    for (let i = 0; i < word.length; i++) {
        const char = word[i];
        if (char === ' ') {
            clue += '  ';
        } else if (i < duelState.revealedHintCount) {
            clue += `${char} `;
        } else {
            clue += '_ ';
        }
    }

    document.getElementById('secretWordClueDisplay').innerText = clue.trim();
    document.getElementById('secretWordClueSubtitle').innerText = `${word.replace(/\s+/g, '').length} Huruf • ${duelState.currentSecretWord.category}`;
}

function startTurnTimer() {
    duelState.timerRemaining = duelState.turnDuration;
    duelState.isTurnActive = true;
    updateTimerDisplay();

    duelState.timerInterval = setInterval(() => {
        duelState.timerRemaining--;
        updateTimerDisplay();

        if (duelState.timerRemaining <= 0) {
            clearInterval(duelState.timerInterval);
            handleTurnTimeUp();
        }
    }, 1000);
}

function updateTimerDisplay() {
    document.getElementById('turnTimerText').innerText = `${duelState.timerRemaining}s`;
}

function requestLetterHint() {
    if (!duelState.isTurnActive) return;
    const word = duelState.currentSecretWord.word;

    if (duelState.revealedHintCount < word.length - 1) {
        duelState.revealedHintCount++;
        updateDashesClueDisplay();
        addGuessChatMessage(`💡 Hint: Huruf ke-${duelState.revealedHintCount} adalah "${word[duelState.revealedHintCount - 1]}"`, 'close');
        playDrawSound('stroke');
    }
}

function submitPlayerGuess() {
    if (!duelState.isTurnActive) return;

    const input = document.getElementById('inputGuessWord');
    const guess = input.value.trim().toUpperCase();
    if (!guess) return;
    input.value = '';

    const secret = duelState.currentSecretWord.word.toUpperCase();
    const guesserName = duelState.drawerIndex === 1 ? duelState.p2Name : duelState.p1Name;

    if (guess === secret) {
        handleTurnCorrectGuess(guesserName);
    } else {
        const isClose = isGuessClose(guess, secret);
        if (isClose) {
            addGuessChatMessage(`🟡 [${guesserName}]: ${guess} (Hampir!)`, 'close');
        } else {
            addGuessChatMessage(`❌ [${guesserName}]: ${guess}`, 'wrong');
        }
        playDrawSound('stroke');
    }
}

function isGuessClose(guess, target) {
    if (Math.abs(guess.length - target.length) > 2) return false;
    let matches = 0;
    for (let char of guess) {
        if (target.includes(char)) matches++;
    }
    return matches >= target.length - 2;
}

function addGuessChatMessage(msg, type = 'system') {
    const box = document.getElementById('guessChatStream');
    const item = document.createElement('div');
    item.className = `guess-chat-item ${type}`;
    item.innerText = msg;
    box.appendChild(item);
    box.scrollTop = box.scrollHeight;
}

function handleTurnCorrectGuess(guesserName) {
    clearInterval(duelState.timerInterval);
    duelState.isTurnActive = false;
    playDrawSound('correct');

    const guesserPoints = 100 + (duelState.timerRemaining * 2);
    const drawerPoints = 80 + Math.round(duelState.timerRemaining * 1.5);

    if (duelState.drawerIndex === 1) {
        duelState.p1Score += drawerPoints;
        duelState.p2Score += guesserPoints;
    } else {
        duelState.p2Score += drawerPoints;
        duelState.p1Score += guesserPoints;
    }

    updateMultiplayerScoreboard();
    addGuessChatMessage(`🎉 [${guesserName}] MENEBAK BENAR: ${duelState.currentSecretWord.word}!`, 'correct');

    document.getElementById('roundResultIcon').className = 'p-2 rounded-circle bg-success bg-opacity-20 text-success fs-2 d-inline-flex mb-1 border border-success border-opacity-30';
    document.getElementById('roundResultTitle').innerText = `TEPAT OLEH ${guesserName.toUpperCase()}!`;
    document.getElementById('roundResultWord').innerText = duelState.currentSecretWord.word;
    document.getElementById('roundP1Points').innerText = `+${duelState.drawerIndex === 1 ? drawerPoints : guesserPoints} PTS`;
    document.getElementById('roundP2Points').innerText = `+${duelState.drawerIndex === 2 ? drawerPoints : guesserPoints} PTS`;

    document.getElementById('roundResultOverlay').classList.remove('d-none');
}

function handleTurnTimeUp() {
    duelState.isTurnActive = false;
    playDrawSound('undo');

    addGuessChatMessage(`⏰ WAKTU HABIS! Kata: "${duelState.currentSecretWord.word}"`, 'system');

    document.getElementById('roundResultIcon').className = 'p-2 rounded-circle bg-danger bg-opacity-20 text-danger fs-2 d-inline-flex mb-1 border border-danger border-opacity-30';
    document.getElementById('roundResultTitle').innerText = 'WAKTU HABIS!';
    document.getElementById('roundResultWord').innerText = duelState.currentSecretWord.word;
    document.getElementById('roundP1Points').innerText = '+0 PTS';
    document.getElementById('roundP2Points').innerText = '+0 PTS';

    document.getElementById('roundResultOverlay').classList.remove('d-none');
}

function nextMultiplayerRound() {
    document.getElementById('roundResultOverlay').classList.add('d-none');

    if (duelState.drawerIndex === 1) {
        duelState.drawerIndex = 2;
    } else {
        duelState.drawerIndex = 1;
        duelState.currentRound++;
    }

    if (duelState.currentRound > duelState.totalRounds) {
        finishMultiplayerDuel();
    } else {
        updateMultiplayerScoreboard();
        prepareNextDuelTurn();
    }
}

function finishMultiplayerDuel() {
    playDrawSound('correct');

    let winnerTitle = '🤝 HASIL IMBANG!';
    if (duelState.p1Score > duelState.p2Score) {
        winnerTitle = `👑 ${duelState.p1Name.toUpperCase()} MENANG!`;
    } else if (duelState.p2Score > duelState.p1Score) {
        winnerTitle = `👑 ${duelState.p2Name.toUpperCase()} MENANG!`;
    }

    document.getElementById('victoryPodiumTitle').innerText = winnerTitle;
    document.getElementById('finalP1Name').innerText = duelState.p1Name;
    document.getElementById('finalP1Score').innerText = duelState.p1Score;

    document.getElementById('finalP2Name').innerText = duelState.p2Name;
    document.getElementById('finalP2Score').innerText = duelState.p2Score;

    document.getElementById('multiplayerVictoryOverlay').classList.remove('d-none');

    fetch('<?= base_url('mini-game/api/record-score') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            game_id: 'menggambar',
            level: 1,
            score: Math.max(duelState.p1Score, duelState.p2Score),
            stars: 3
        })
    }).catch(e => console.log(e));
}

// --- Single Player Sub-Mode Switcher ---
function switchSingleSubMode(subMode) {
    studioState.singleSubMode = subMode;
    const isColoring = subMode === 'coloring';

    document.getElementById('modeBtn_free').classList.toggle('active', !isColoring);
    document.getElementById('modeBtn_color').classList.toggle('active', isColoring);
    document.getElementById('coloringTemplatesSection').classList.toggle('d-none', !isColoring);

    if (isColoring && !studioState.activeTemplateId) {
        applyColoringTemplate(COLORING_TEMPLATES[0].id);
    } else if (!isColoring) {
        clearOverlayTemplate();
        studioState.activeTemplateId = null;
    }
}

// --- Render Color Swatches ---
function renderColorSwatches(themeKey) {
    const container = document.getElementById('colorSwatchesContainer');
    container.innerHTML = '';
    const colors = PALETTE_THEMES[themeKey] || PALETTE_THEMES.pop;

    colors.forEach(hex => {
        const div = document.createElement('div');
        div.className = `color-swatch-circle ${hex.toLowerCase() === studioState.activeColor.toLowerCase() ? 'active' : ''}`;
        div.style.backgroundColor = hex;
        div.title = hex;
        div.onclick = () => selectColor(hex);
        container.appendChild(div);
    });
}

function switchPaletteTheme(themeKey) {
    ['pop', 'neon', 'pastel', 'earth'].forEach(k => {
        document.getElementById(`paletteTab_${k}`)?.classList.remove('active');
    });
    document.getElementById(`paletteTab_${themeKey}`)?.classList.add('active');
    renderColorSwatches(themeKey);
}

function selectColor(hex) {
    studioState.activeColor = hex;
    document.getElementById('customColorPicker').value = hex;
    document.getElementById('brushSizeDot').style.backgroundColor = hex;

    document.querySelectorAll('.color-swatch-circle').forEach(el => {
        el.classList.toggle('active', el.style.backgroundColor === hex || el.title.toLowerCase() === hex.toLowerCase());
    });
}

function selectCustomColor(hex) {
    selectColor(hex);
}

// --- Render Coloring Templates ---
function renderTemplateCards() {
    const grid = document.getElementById('templateListGrid');
    grid.innerHTML = '';

    COLORING_TEMPLATES.forEach(tpl => {
        const col = document.createElement('div');
        col.className = 'col-4';
        col.innerHTML = `
            <div class="sketch-template-card ${studioState.activeTemplateId === tpl.id ? 'active' : ''}" onclick="applyColoringTemplate('${tpl.id}')" title="${tpl.title}">
                <div class="style-tiny fw-bold text-truncate" style="font-size: 0.70rem;">${tpl.title}</div>
            </div>
        `;
        grid.appendChild(col);
    });
}

function applyColoringTemplate(tplId) {
    studioState.activeTemplateId = tplId;
    renderTemplateCards();

    const tpl = COLORING_TEMPLATES.find(t => t.id === tplId);
    if (!tpl) return;

    overlayCtx.clearRect(0, 0, overlayCanvas.width, overlayCanvas.height);
    tpl.drawOutline(overlayCtx, overlayCanvas.width, overlayCanvas.height);

    tpl.drawOutline(drawCtx, drawCanvas.width, drawCanvas.height);
    saveCanvasState();
    playDrawSound('stamp');
}

function clearOverlayTemplate() {
    overlayCtx.clearRect(0, 0, overlayCanvas.width, overlayCanvas.height);
}

// --- Tool Selection ---
function selectTool(toolName) {
    studioState.tool = toolName;
    ['brush', 'pencil', 'neon', 'spray', 'fill', 'eraser'].forEach(t => {
        document.getElementById(`tool_${t}`)?.classList.remove('active');
    });
    document.getElementById(`tool_${toolName}`)?.classList.add('active');

    ['line', 'rect', 'circle', 'star', 'triangle'].forEach(s => {
        document.getElementById(`shape_${s}`)?.classList.remove('active');
    });
    document.querySelectorAll('.stamp-btn').forEach(b => b.classList.remove('active'));

    const toolNames = {
        brush: 'Kuas Halus',
        pencil: 'Pensil',
        neon: 'Kuas Neon',
        spray: 'Cat Semprot',
        fill: 'Ember Cat',
        eraser: 'Penghapus'
    };
    document.getElementById('activeToolBadge').innerText = toolNames[toolName] || toolName;
}

function selectShape(shapeName) {
    studioState.tool = 'shape';
    studioState.shapeType = shapeName;

    ['brush', 'pencil', 'neon', 'spray', 'fill', 'eraser'].forEach(t => {
        document.getElementById(`tool_${t}`)?.classList.remove('active');
    });
    ['line', 'rect', 'circle', 'star', 'triangle'].forEach(s => {
        document.getElementById(`shape_${s}`)?.classList.toggle('active', s === shapeName);
    });
    document.querySelectorAll('.stamp-btn').forEach(b => b.classList.remove('active'));

    document.getElementById('activeToolBadge').innerText = `Bentuk: ${shapeName.toUpperCase()}`;
}

function selectStamp(emoji) {
    studioState.tool = 'stamp';
    studioState.stampEmoji = emoji;

    ['brush', 'pencil', 'neon', 'spray', 'fill', 'eraser'].forEach(t => {
        document.getElementById(`tool_${t}`)?.classList.remove('active');
    });
    ['line', 'rect', 'circle', 'star', 'triangle'].forEach(s => {
        document.getElementById(`shape_${s}`)?.classList.remove('active');
    });

    document.querySelectorAll('.stamp-btn').forEach(b => {
        b.classList.toggle('active', b.innerText === emoji);
    });

    document.getElementById('activeToolBadge').innerText = `Stempel: ${emoji}`;
}

function updateBrushSize(val) {
    const size = parseInt(val);
    studioState.brushSize = size;
    document.getElementById('brushSizeLabel').innerText = `${size}px`;
    document.getElementById('brushSizeDot').style.width = `${Math.min(size, 20)}px`;
    document.getElementById('brushSizeDot').style.height = `${Math.min(size, 20)}px`;
}

function updateBrushOpacity(val) {
    const opacity = parseInt(val) / 100;
    studioState.brushOpacity = opacity;
    document.getElementById('brushOpacityLabel').innerText = `${val}%`;
}

function changeCanvasBg(bgType) {
    studioState.canvasBg = bgType;
    viewportCard.className = `canvas-viewport-card bg-paper-${bgType}`;
}

// --- Coordinates Helper ---
function getCanvasCoordinates(e) {
    const rect = drawCanvas.getBoundingClientRect();
    const scaleX = drawCanvas.width / rect.width;
    const scaleY = drawCanvas.height / rect.height;

    let clientX = e.clientX;
    let clientY = e.clientY;

    if (e.touches && e.touches.length > 0) {
        clientX = e.touches[0].clientX;
        clientY = e.touches[0].clientY;
    }

    return {
        x: (clientX - rect.left) * scaleX,
        y: (clientY - rect.top) * scaleY
    };
}

// --- Drawing Engine Listeners ---
function setupEventListeners() {
    viewportCard.addEventListener('pointerdown', handlePointerDown);
    window.addEventListener('pointermove', handlePointerMove);
    window.addEventListener('pointerup', handlePointerUp);
    window.addEventListener('pointercancel', handlePointerUp);

    window.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key === 'z') {
            e.preventDefault();
            undoCanvas();
        } else if ((e.ctrlKey || e.metaKey) && (e.key === 'y' || (e.shiftKey && e.key === 'Z'))) {
            e.preventDefault();
            redoCanvas();
        }
    });
}

function handlePointerDown(e) {
    if (e.button !== 0 && e.pointerType === 'mouse') return;
    initDrawAudio();

    const coords = getCanvasCoordinates(e);
    studioState.isDrawing = true;
    studioState.startX = coords.x;
    studioState.startY = coords.y;
    studioState.lastX = coords.x;
    studioState.lastY = coords.y;

    if (studioState.tool === 'fill') {
        floodFill(Math.round(coords.x), Math.round(coords.y), studioState.activeColor);
        playDrawSound('fill');
        saveCanvasState();
        studioState.isDrawing = false;
        return;
    }

    if (studioState.tool === 'stamp') {
        drawStamp(coords.x, coords.y, studioState.stampEmoji, studioState.brushSize * 3);
        playDrawSound('stamp');
        saveCanvasState();
        studioState.isDrawing = false;
        return;
    }

    if (['brush', 'pencil', 'neon', 'spray', 'eraser'].includes(studioState.tool)) {
        drawDot(coords.x, coords.y);
        playDrawSound('stroke');
    }
}

function handlePointerMove(e) {
    const coords = getCanvasCoordinates(e);
    document.getElementById('cursorCoordLabel').innerText = `${Math.round(coords.x)}, ${Math.round(coords.y)}`;

    if (!studioState.isDrawing) return;

    if (['brush', 'pencil', 'neon', 'spray', 'eraser'].includes(studioState.tool)) {
        drawLineSegment(studioState.lastX, studioState.lastY, coords.x, coords.y);
        studioState.lastX = coords.x;
        studioState.lastY = coords.y;
    } else if (studioState.tool === 'shape') {
        renderShapePreview(studioState.startX, studioState.startY, coords.x, coords.y, studioState.shapeType);
    }
}

function handlePointerUp(e) {
    if (!studioState.isDrawing) return;
    studioState.isDrawing = false;

    if (studioState.tool === 'shape') {
        const coords = getCanvasCoordinates(e);
        if (!studioState.activeTemplateId) {
            clearOverlayTemplate();
        } else {
            const tpl = COLORING_TEMPLATES.find(t => t.id === studioState.activeTemplateId);
            if (tpl) {
                clearOverlayTemplate();
                tpl.drawOutline(overlayCtx, overlayCanvas.width, overlayCanvas.height);
            }
        }
        drawShape(drawCtx, studioState.startX, studioState.startY, coords.x, coords.y, studioState.shapeType);
        playDrawSound('stroke');
    }

    saveCanvasState();
}

// --- Canvas Drawing Subroutines ---
function drawDot(x, y) {
    drawCtx.save();
    drawCtx.globalAlpha = studioState.brushOpacity;

    if (studioState.tool === 'eraser') {
        drawCtx.globalCompositeOperation = 'destination-out';
        drawCtx.beginPath();
        drawCtx.arc(x, y, studioState.brushSize / 2, 0, Math.PI * 2);
        drawCtx.fill();
    } else if (studioState.tool === 'spray') {
        drawSpray(x, y);
    } else if (studioState.tool === 'neon') {
        drawCtx.strokeStyle = studioState.activeColor;
        drawCtx.fillStyle = studioState.activeColor;
        drawCtx.shadowColor = studioState.activeColor;
        drawCtx.shadowBlur = studioState.brushSize * 1.5;
        drawCtx.beginPath();
        drawCtx.arc(x, y, studioState.brushSize / 2, 0, Math.PI * 2);
        drawCtx.fill();
    } else {
        drawCtx.fillStyle = studioState.activeColor;
        drawCtx.beginPath();
        drawCtx.arc(x, y, studioState.brushSize / 2, 0, Math.PI * 2);
        drawCtx.fill();
    }

    drawCtx.restore();
}

function drawLineSegment(x1, y1, x2, y2) {
    drawCtx.save();
    drawCtx.globalAlpha = studioState.brushOpacity;
    drawCtx.lineCap = 'round';
    drawCtx.lineJoin = 'round';

    if (studioState.tool === 'eraser') {
        drawCtx.globalCompositeOperation = 'destination-out';
        drawCtx.lineWidth = studioState.brushSize;
        drawCtx.beginPath();
        drawCtx.moveTo(x1, y1);
        drawCtx.lineTo(x2, y2);
        drawCtx.stroke();
    } else if (studioState.tool === 'spray') {
        drawSpray(x2, y2);
    } else if (studioState.tool === 'neon') {
        drawCtx.strokeStyle = studioState.activeColor;
        drawCtx.lineWidth = studioState.brushSize;
        drawCtx.shadowColor = studioState.activeColor;
        drawCtx.shadowBlur = studioState.brushSize * 1.5;
        drawCtx.beginPath();
        drawCtx.moveTo(x1, y1);
        drawCtx.lineTo(x2, y2);
        drawCtx.stroke();
    } else if (studioState.tool === 'pencil') {
        drawCtx.strokeStyle = studioState.activeColor;
        drawCtx.lineWidth = Math.max(1, studioState.brushSize / 2.5);
        drawCtx.beginPath();
        drawCtx.moveTo(x1, y1);
        drawCtx.lineTo(x2, y2);
        drawCtx.stroke();
    } else {
        drawCtx.strokeStyle = studioState.activeColor;
        drawCtx.lineWidth = studioState.brushSize;
        drawCtx.beginPath();
        drawCtx.moveTo(x1, y1);
        drawCtx.lineTo(x2, y2);
        drawCtx.stroke();
    }

    drawCtx.restore();
}

function drawSpray(x, y) {
    const density = studioState.brushSize * 2;
    const radius = studioState.brushSize * 1.2;
    drawCtx.fillStyle = studioState.activeColor;

    for (let i = 0; i < density; i++) {
        const offsetAngle = Math.random() * Math.PI * 2;
        const offsetRadius = Math.random() * radius;
        const pX = x + Math.cos(offsetAngle) * offsetRadius;
        const pY = y + Math.sin(offsetAngle) * offsetRadius;
        drawCtx.fillRect(pX, pY, 1.5, 1.5);
    }
}

function drawStamp(x, y, emoji, size) {
    drawCtx.save();
    drawCtx.font = `${size}px sans-serif`;
    drawCtx.textAlign = 'center';
    drawCtx.textBaseline = 'middle';
    drawCtx.fillText(emoji, x, y);
    drawCtx.restore();
}

function renderShapePreview(x1, y1, x2, y2, shape) {
    overlayCtx.clearRect(0, 0, overlayCanvas.width, overlayCanvas.height);
    
    if (studioState.activeTemplateId) {
        const tpl = COLORING_TEMPLATES.find(t => t.id === studioState.activeTemplateId);
        if (tpl) tpl.drawOutline(overlayCtx, overlayCanvas.width, overlayCanvas.height);
    }

    drawShape(overlayCtx, x1, y1, x2, y2, shape);
}

function drawShape(ctx, x1, y1, x2, y2, shape) {
    ctx.save();
    ctx.globalAlpha = studioState.brushOpacity;
    ctx.strokeStyle = studioState.activeColor;
    ctx.lineWidth = studioState.brushSize;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';

    ctx.beginPath();
    if (shape === 'line') {
        ctx.moveTo(x1, y1);
        ctx.lineTo(x2, y2);
        ctx.stroke();
    } else if (shape === 'rect') {
        ctx.rect(x1, y1, x2 - x1, y2 - y1);
        ctx.stroke();
    } else if (shape === 'circle') {
        const radiusX = Math.abs(x2 - x1) / 2;
        const radiusY = Math.abs(y2 - y1) / 2;
        const centerX = Math.min(x1, x2) + radiusX;
        const centerY = Math.min(y1, y2) + radiusY;
        ctx.ellipse(centerX, centerY, radiusX, radiusY, 0, 0, Math.PI * 2);
        ctx.stroke();
    } else if (shape === 'triangle') {
        ctx.moveTo(x1 + (x2 - x1) / 2, y1);
        ctx.lineTo(x2, y2);
        ctx.lineTo(x1, y2);
        ctx.closePath();
        ctx.stroke();
    } else if (shape === 'star') {
        const radius = Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2));
        drawStarShape(ctx, x1, y1, 5, radius, radius * 0.5);
        ctx.stroke();
    }

    ctx.restore();
}

// --- Flood Fill Algorithm ---
function floodFill(startX, startY, fillColorHex) {
    const w = drawCanvas.width;
    const h = drawCanvas.height;
    if (startX < 0 || startX >= w || startY < 0 || startY >= h) return;

    const imgData = drawCtx.getImageData(0, 0, w, h);
    const data = imgData.data;

    const fillColor = hexToRgba(fillColorHex);
    const startPos = (startY * w + startX) * 4;
    const targetR = data[startPos];
    const targetG = data[startPos + 1];
    const targetB = data[startPos + 2];
    const targetA = data[startPos + 3];

    if (colorMatch(targetR, targetG, targetB, targetA, fillColor.r, fillColor.g, fillColor.b, fillColor.a, 5)) {
        return;
    }

    const queue = [startX, startY];
    const visited = new Uint8Array(w * h);

    while (queue.length > 0) {
        const y = queue.pop();
        const x = queue.pop();
        const idx = y * w + x;

        if (visited[idx]) continue;
        visited[idx] = 1;

        const p = idx * 4;
        if (colorMatch(data[p], data[p + 1], data[p + 2], data[p + 3], targetR, targetG, targetB, targetA, 32)) {
            data[p] = fillColor.r;
            data[p + 1] = fillColor.g;
            data[p + 2] = fillColor.b;
            data[p + 3] = fillColor.a;

            if (x > 0 && !visited[idx - 1]) { queue.push(x - 1, y); }
            if (x < w - 1 && !visited[idx + 1]) { queue.push(x + 1, y); }
            if (y > 0 && !visited[idx - w]) { queue.push(x, y - 1); }
            if (y < h - 1 && !visited[idx + w]) { queue.push(x, y + 1); }
        }
    }

    drawCtx.putImageData(imgData, 0, 0);
}

function colorMatch(r1, g1, b1, a1, r2, g2, b2, a2, tolerance) {
    return Math.abs(r1 - r2) <= tolerance &&
           Math.abs(g1 - g2) <= tolerance &&
           Math.abs(b1 - b2) <= tolerance &&
           Math.abs(a1 - a2) <= tolerance;
}

function hexToRgba(hex) {
    let c = hex.replace('#', '');
    if (c.length === 3) c = c.split('').map(x => x + x).join('');
    const num = parseInt(c, 16);
    return {
        r: (num >> 16) & 255,
        g: (num >> 8) & 255,
        b: num & 255,
        a: 255
    };
}

// --- History & Undo/Redo ---
function saveCanvasState() {
    if (studioState.historyIndex < studioState.history.length - 1) {
        studioState.history = studioState.history.slice(0, studioState.historyIndex + 1);
    }

    const state = drawCtx.getImageData(0, 0, drawCanvas.width, drawCanvas.height);
    studioState.history.push(state);

    if (studioState.history.length > studioState.maxHistory) {
        studioState.history.shift();
    } else {
        studioState.historyIndex++;
    }

    updateUndoRedoButtons();
}

function undoCanvas() {
    if (studioState.historyIndex > 0) {
        studioState.historyIndex--;
        const state = studioState.history[studioState.historyIndex];
        drawCtx.putImageData(state, 0, 0);
        updateUndoRedoButtons();
        playDrawSound('undo');
    }
}

function redoCanvas() {
    if (studioState.historyIndex < studioState.history.length - 1) {
        studioState.historyIndex++;
        const state = studioState.history[studioState.historyIndex];
        drawCtx.putImageData(state, 0, 0);
        updateUndoRedoButtons();
        playDrawSound('undo');
    }
}

function updateUndoRedoButtons() {
    document.getElementById('btnUndo').disabled = studioState.historyIndex <= 0;
    document.getElementById('btnRedo').disabled = studioState.historyIndex >= studioState.history.length - 1;
}

function confirmClearCanvas() {
    const modal = new bootstrap.Modal(document.getElementById('clearConfirmModal'));
    modal.show();
}

function executeClearCanvas() {
    drawCtx.clearRect(0, 0, drawCanvas.width, drawCanvas.height);
    clearOverlayTemplate();
    studioState.activeTemplateId = null;
    saveCanvasState();
    playDrawSound('undo');
}

// --- Export & Download High-Res PNG ---
function exportArtworkModal() {
    const exportCanvas = document.createElement('canvas');
    exportCanvas.width = drawCanvas.width;
    exportCanvas.height = drawCanvas.height;
    const expCtx = exportCanvas.getContext('2d');

    if (studioState.canvasBg === 'dark') {
        expCtx.fillStyle = '#09090b';
        expCtx.fillRect(0, 0, exportCanvas.width, exportCanvas.height);
    } else {
        expCtx.fillStyle = '#ffffff';
        expCtx.fillRect(0, 0, exportCanvas.width, exportCanvas.height);
    }

    expCtx.drawImage(drawCanvas, 0, 0);
    expCtx.drawImage(overlayCanvas, 0, 0);

    expCtx.save();
    expCtx.fillStyle = 'rgba(15, 23, 42, 0.65)';
    expCtx.font = 'bold 14px "JetBrains Mono", Consolas, monospace';
    expCtx.textAlign = 'right';
    expCtx.fillText('🎨 Multimedia Club Art Studio', exportCanvas.width - 20, exportCanvas.height - 20);
    expCtx.restore();

    const dataUrl = exportCanvas.toDataURL('image/png');
    document.getElementById('exportPreviewImg').src = dataUrl;

    const modal = new bootstrap.Modal(document.getElementById('exportModal'));
    modal.show();
}

function processDownloadPNG() {
    const previewImg = document.getElementById('exportPreviewImg');
    const title = document.getElementById('inputArtTitle').value.trim() || 'Karya-MMC';
    const cleanFilename = `${title.toLowerCase().replace(/[^a-z0-9]/g, '-')}-${Date.now()}.png`;

    const link = document.createElement('a');
    link.download = cleanFilename;
    link.href = previewImg.src;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    playDrawSound('save');
    saveToLocalGallery(previewImg.src, title);

    fetch('<?= base_url('mini-game/api/record-score') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            game_id: 'menggambar',
            level: 1,
            score: 100,
            stars: 3
        })
    }).catch(e => console.log(e));

    const exportModalEl = document.getElementById('exportModal');
    const modalInstance = bootstrap.Modal.getInstance(exportModalEl);
    modalInstance?.hide();
}

function saveToLocalGallery(dataUrl, title) {
    try {
        let gallery = JSON.parse(localStorage.getItem('mmc_art_gallery') || '[]');
        gallery.unshift({
            id: Date.now(),
            title: title,
            date: new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }),
            dataUrl: dataUrl
        });

        if (gallery.length > 12) gallery = gallery.slice(0, 12);
        localStorage.setItem('mmc_art_gallery', JSON.stringify(gallery));
    } catch (e) {}
}

function openGalleryModal() {
    const body = document.getElementById('galleryModalBody');
    const gallery = JSON.parse(localStorage.getItem('mmc_art_gallery') || '[]');

    if (gallery.length === 0) {
        body.innerHTML = `
            <div class="text-center text-secondary py-4">
                <i class="fa-solid fa-palette fs-2 text-secondary opacity-50 mb-1"></i>
                <div class="fw-bold style-tiny">Belum Ada Karya Tersimpan</div>
            </div>
        `;
    } else {
        let html = '<div class="row g-2">';
        gallery.forEach(art => {
            html += `
                <div class="col-6 col-sm-4">
                    <div class="p-1.5 rounded-2 bg-black border border-secondary border-opacity-30 h-100 d-flex flex-column justify-content-between">
                        <div class="text-center bg-white rounded-1 overflow-hidden mb-1" style="height: 90px; display: flex; align-items: center; justify-content: center;">
                            <img src="${art.dataUrl}" alt="${art.title}" class="img-fluid" style="max-height: 90px; object-fit: contain;">
                        </div>
                        <div class="style-tiny text-white fw-bold text-truncate">${art.title}</div>
                        <div class="d-flex gap-1 mt-1">
                            <button type="button" class="btn btn-xs btn-pink text-white w-100 font-heading style-tiny py-0" onclick="loadArtworkToCanvas('${art.id}')" data-bs-dismiss="modal">Buka</button>
                            <a href="${art.dataUrl}" download="${art.title}.png" class="btn btn-xs btn-outline-secondary text-white style-tiny px-1.5 py-0"><i class="fa-solid fa-download"></i></a>
                        </div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        body.innerHTML = html;
    }

    const modal = new bootstrap.Modal(document.getElementById('galleryModal'));
    modal.show();
}

function loadArtworkToCanvas(id) {
    const gallery = JSON.parse(localStorage.getItem('mmc_art_gallery') || '[]');
    const art = gallery.find(a => a.id.toString() === id.toString());
    if (!art) return;

    const img = new Image();
    img.onload = function() {
        drawCtx.clearRect(0, 0, drawCanvas.width, drawCanvas.height);
        drawCtx.drawImage(img, 0, 0, drawCanvas.width, drawCanvas.height);
        clearOverlayTemplate();
        saveCanvasState();
        playDrawSound('stamp');
    };
    img.src = art.dataUrl;
}

function clearLocalGallery() {
    localStorage.removeItem('mmc_art_gallery');
    openGalleryModal();
}

// Start Studio on DOM Loaded
document.addEventListener('DOMContentLoaded', initDrawingStudio);
</script>

<?= $this->endSection() ?>
