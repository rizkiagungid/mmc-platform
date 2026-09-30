<?= $this->extend('layouts/master_public') ?>

<?= $this->section('content') ?>
<style>
/* =========================================================
   QUIZ TRIVIA & WNI SATIR - ARCADE NEON STYLES
   ========================================================= */
.quiz-wrapper {
    background: radial-gradient(circle at 50% 20%, #1e1035 0%, #0c0717 100%);
    border: 1px solid rgba(168, 85, 247, 0.3);
    border-radius: 24px;
    box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.85), 0 0 40px rgba(168, 85, 247, 0.15);
    overflow: hidden;
    position: relative;
}

/* Neon Glow Accents */
.neon-glow-purple {
    box-shadow: 0 0 25px rgba(168, 85, 247, 0.45);
}
.neon-glow-cyan {
    box-shadow: 0 0 25px rgba(6, 182, 212, 0.45);
}
.neon-glow-amber {
    box-shadow: 0 0 25px rgba(245, 158, 11, 0.45);
}

/* Top Status & Controls Header */
.quiz-topbar {
    background: rgba(18, 10, 36, 0.85);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    padding: 10px 16px;
}

/* Duel Player Avatar Pods */
.player-pod {
    background: rgba(30, 16, 53, 0.7);
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: 18px;
    padding: 8px 14px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    gap: 12px;
}
.player-pod.active-turn {
    border-color: #a855f7;
    background: rgba(168, 85, 247, 0.2);
    box-shadow: 0 0 20px rgba(168, 85, 247, 0.45);
    transform: translateY(-2px);
}
.player-pod.active-turn-p2 {
    border-color: #06b6d4;
    background: rgba(6, 182, 212, 0.2);
    box-shadow: 0 0 20px rgba(6, 182, 212, 0.45);
    transform: translateY(-2px);
}

.player-avatar-badge {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    background: #2e1065;
    border: 2px solid rgba(255, 255, 255, 0.2);
    flex-shrink: 0;
}

/* Health Hearts */
.heart-icon {
    color: #ef4444;
    filter: drop-shadow(0 0 6px rgba(239, 68, 68, 0.7));
    font-size: 1.1rem;
    transition: transform 0.2s ease, opacity 0.2s ease;
}
.heart-icon.lost {
    color: #4b5563;
    filter: none;
    opacity: 0.35;
    transform: scale(0.85);
}

/* Question Bubble Pod */
.question-bubble-pod {
    background: rgba(26, 14, 48, 0.9);
    border: 2px solid rgba(168, 85, 247, 0.35);
    border-radius: 20px;
    padding: 22px 20px;
    position: relative;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5), inset 0 0 20px rgba(168, 85, 247, 0.1);
    min-height: 120px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

/* Answer Option Buttons */
.quiz-choice-btn {
    width: 100%;
    background: rgba(22, 12, 40, 0.85);
    border: 1.5px solid rgba(255, 255, 255, 0.12);
    color: #f1f5f9;
    border-radius: 14px;
    padding: 14px 18px;
    font-size: 0.98rem;
    font-weight: 600;
    text-align: left;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    user-select: none;
    -webkit-user-select: none;
}
.quiz-choice-btn:hover:not(:disabled) {
    border-color: #a855f7;
    background: rgba(168, 85, 247, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(168, 85, 247, 0.25);
    color: #ffffff;
}
.quiz-choice-btn:active:not(:disabled) {
    transform: scale(0.98);
}
.quiz-choice-btn .choice-letter {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.08);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-family: var(--bs-font-monospace, monospace);
    font-weight: 800;
    font-size: 0.9rem;
    flex-shrink: 0;
    transition: all 0.2s ease;
}

/* Choice States */
.quiz-choice-btn.correct {
    background: rgba(34, 197, 94, 0.25) !important;
    border-color: #22c55e !important;
    color: #4ade80 !important;
    box-shadow: 0 0 25px rgba(34, 197, 94, 0.5) !important;
    animation: pulseCorrect 0.4s ease;
}
.quiz-choice-btn.correct .choice-letter {
    background: #22c55e;
    color: #000000;
}

.quiz-choice-btn.wrong {
    background: rgba(239, 68, 68, 0.25) !important;
    border-color: #ef4444 !important;
    color: #f87171 !important;
    box-shadow: 0 0 25px rgba(239, 68, 68, 0.5) !important;
    animation: shakeWrong 0.4s ease;
}
.quiz-choice-btn.wrong .choice-letter {
    background: #ef4444;
    color: #ffffff;
}

.quiz-choice-btn.eliminated {
    opacity: 0.25;
    pointer-events: none;
    text-decoration: line-through;
    border-color: rgba(255, 255, 255, 0.05);
}

@keyframes pulseCorrect {
    0% { transform: scale(1); }
    50% { transform: scale(1.03); }
    100% { transform: scale(1); }
}

@keyframes shakeWrong {
    0%, 100% { transform: translateX(0); }
    20%, 60% { transform: translateX(-6px); }
    40%, 80% { transform: translateX(6px); }
}

/* Glassmorphic Overlays (Start / Victory / Game Over) */
.quiz-screen-overlay {
    position: absolute;
    inset: 0;
    background: rgba(10, 5, 20, 0.94);
    backdrop-filter: blur(14px);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 16px;
    z-index: 30;
    text-align: center;
    overflow-y: auto;
    animation: fadeInOverlay 0.25s ease forwards;
}

@keyframes fadeInOverlay {
    from { opacity: 0; transform: scale(0.98); }
    to { opacity: 1; transform: scale(1); }
}

/* Lifeline 50:50 Button */
.lifeline-btn {
    background: linear-gradient(135deg, #a855f7 0%, #7e22ce 100%);
    border: 1.5px solid rgba(255, 255, 255, 0.2);
    color: #ffffff;
    border-radius: 999px;
    padding: 6px 16px;
    font-size: 0.84rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 15px rgba(168, 85, 247, 0.35);
}
.lifeline-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(168, 85, 247, 0.55);
}
.lifeline-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
    filter: grayscale(1);
    box-shadow: none;
}
.lifeline-btn.lifeline-btn-cyan {
    background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
    box-shadow: 0 4px 15px rgba(6, 182, 212, 0.35);
}
.lifeline-btn.lifeline-btn-cyan:hover:not(:disabled) {
    box-shadow: 0 6px 20px rgba(6, 182, 212, 0.55);
}

/* Progress bar timer */
.quiz-timer-bar {
    height: 6px;
    background: linear-gradient(90deg, #22c55e 0%, #eab308 60%, #ef4444 100%);
    transition: width 1s linear;
    border-radius: 3px;
}

/* Mode Badge in Header */
.header-mode-badge {
    background: rgba(168, 85, 247, 0.2) !important;
    color: #e9d5ff !important;
    border: 1px solid rgba(168, 85, 247, 0.45) !important;
    font-family: var(--bs-font-monospace, monospace);
    font-size: 0.74rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
}

/* Tutorial & Guide List Layout */
.guide-list-item {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
.guide-list-item:hover {
    border-color: rgba(168, 85, 247, 0.45) !important;
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
.bg-gradient-purple {
    background: linear-gradient(135deg, #a855f7 0%, #6b21a8 100%);
}
.bg-gradient-red {
    background: linear-gradient(135deg, #ef4444 0%, #991b1b 100%);
}
.bg-gradient-amber {
    background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%);
}

/* Explanation Review Modal Card Styles */
.exp-card {
    background: rgba(18, 10, 36, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 14px;
    padding: 14px 16px;
    transition: all 0.2s ease;
}
.exp-card:hover {
    border-color: rgba(6, 182, 212, 0.45);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
}
.exp-callout {
    background: rgba(245, 158, 11, 0.12);
    border: 1px solid rgba(245, 158, 11, 0.3);
    border-radius: 10px;
    padding: 10px 14px;
    color: #fef08a;
    font-size: 0.86rem;
    line-height: 1.45;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .quiz-wrapper {
        border-radius: 18px;
        margin-bottom: 1.25rem;
    }
    .quiz-topbar {
        padding: 8px 12px;
    }
    #quizPlayingArea {
        padding: 12px 10px !important;
    }
    .question-bubble-pod {
        padding: 14px 12px;
        min-height: 85px;
        border-radius: 14px;
        margin-bottom: 12px !important;
    }
    .question-bubble-pod h3 {
        font-size: 0.98rem !important;
        line-height: 1.35;
    }
    .quiz-choice-btn {
        padding: 10px 12px;
        font-size: 0.86rem;
        border-radius: 12px;
        gap: 8px;
    }
    .quiz-choice-btn .choice-letter {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }
    .player-pod {
        padding: 5px 8px;
        gap: 6px;
        border-radius: 12px;
    }
    .player-avatar-badge {
        width: 32px;
        height: 32px;
        font-size: 1rem;
    }
    .heart-icon {
        font-size: 0.85rem;
    }
    .quiz-screen-overlay {
        padding: 12px 10px;
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
                    <li class="breadcrumb-item active text-purple fw-bold" aria-current="page">Quiz MMC</li>
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
                    <span class="badge bg-purple text-white font-monospace style-tiny fw-bold"><i class="fa-solid fa-brain me-1"></i> MINI GAME #4</span>
                    <span class="badge bg-body-secondary text-secondary border border-secondary border-opacity-50 style-tiny font-monospace">Trivia & Sejarah MMC</span>
                    <span class="header-mode-badge" id="headerGameModeBadge"><i class="fa-solid fa-user me-1"></i> Mode: SINGLE PLAYER</span>
                </div>
                <h1 class="h4 h3-md fw-bold text-body font-heading mb-0">Quiz MMC</h1>
            </div>

            <!-- Action Controls -->
            <div class="d-flex align-items-center gap-1.5 w-100 w-md-auto justify-content-end">
                <button type="button" class="btn btn-sm btn-outline-purple flex-grow-1 flex-md-grow-0 py-1 text-white border-purple" data-bs-toggle="modal" data-bs-target="#quizSettingsModal">
                    <i class="fa-solid fa-gear me-1"></i> Pengaturan
                </button>
                <button type="button" class="btn btn-sm btn-outline-warning flex-grow-1 flex-md-grow-0 py-1" data-bs-toggle="modal" data-bs-target="#quizLeaderboardModal">
                    <i class="fa-solid fa-trophy me-1"></i> Rekor Kuis
                </button>
            </div>
        </div>

        <!-- Main Quiz Shell Container -->
        <div class="quiz-wrapper mb-4 position-relative" style="min-height: 540px;">
            
            <!-- Quiz Top Status Bar -->
            <div class="quiz-topbar d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-purple bg-opacity-30 text-white border border-purple border-opacity-40 font-monospace style-tiny" id="topbarDiffBadge">
                        🟡 MUDAH (10 SOAL)
                    </span>
                    <span class="badge bg-secondary bg-opacity-25 text-secondary font-monospace style-tiny" id="topbarCategoryBadge">
                        🏛️ WNI Satir
                    </span>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <!-- Audio Toggle Button -->
                    <button type="button" class="btn btn-sm btn-dark border border-secondary border-opacity-25 text-secondary p-1 px-2.5" id="quizSoundBtn" onclick="toggleQuizAudio()" title="Mute / Unmute Suara">
                        <i class="fa-solid fa-volume-high text-purple" id="quizSoundIcon"></i>
                    </button>
                    <!-- Restart Button -->
                    <button type="button" class="btn btn-sm btn-dark border border-secondary border-opacity-25 text-secondary p-1 px-2.5" onclick="showStartScreen()" title="Kembali ke Menu Awal">
                        <i class="fa-solid fa-rotate-right text-warning"></i>
                    </button>
                </div>
            </div>

            <!-- Quiz Timer Bar Indicator -->
            <div class="w-100 bg-black" style="height: 6px;">
                <div class="quiz-timer-bar" id="quizTimerBar" style="width: 100%;"></div>
            </div>

            <!-- Quiz In-Game Playing Arena -->
            <div class="p-3 p-md-4" id="quizPlayingArea">
                
                <!-- Player Header Stats (Single or Duel Mode) -->
                <div class="row g-2 align-items-center mb-3 mb-md-4">
                    <!-- Player 1 Pod -->
                    <div class="col-6" id="player1Col">
                        <div class="player-pod active-turn" id="player1Pod">
                            <div class="player-avatar-badge" id="p1Avatar">😎</div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex align-items-center justify-content-between gap-1">
                                    <strong class="text-white small text-truncate d-block font-heading" id="p1NameLabel">Player 1</strong>
                                    <span class="badge bg-purple text-white font-monospace style-tiny" id="p1TurnBadge">TURN</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-0.5">
                                    <span class="font-monospace text-warning fw-bold small" id="p1ScoreLabel">0 Pts</span>
                                    <div class="d-flex gap-1" id="p1HeartsContainer">
                                        <i class="fa-solid fa-heart heart-icon"></i>
                                        <i class="fa-solid fa-heart heart-icon"></i>
                                        <i class="fa-solid fa-heart heart-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Player 2 Pod (Shown in 2-Player Duel Mode) -->
                    <div class="col-6 d-none" id="player2Col">
                        <div class="player-pod" id="player2Pod">
                            <div class="player-avatar-badge" id="p2Avatar">🤖</div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex align-items-center justify-content-between gap-1">
                                    <strong class="text-white small text-truncate d-block font-heading" id="p2NameLabel">Player 2</strong>
                                    <span class="badge bg-secondary text-white font-monospace style-tiny d-none" id="p2TurnBadge">WAIT</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-0.5">
                                    <span class="font-monospace text-info fw-bold small" id="p2ScoreLabel">0 Pts</span>
                                    <div class="d-flex gap-1" id="p2HeartsContainer">
                                        <i class="fa-solid fa-heart heart-icon"></i>
                                        <i class="fa-solid fa-heart heart-icon"></i>
                                        <i class="fa-solid fa-heart heart-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Single Player Extra Metrics (when not duel) -->
                    <div class="col-6 text-end" id="singlePlayerStatsCol">
                        <div class="d-inline-flex align-items-center gap-2">
                            <div class="text-end font-monospace">
                                <div class="style-tiny text-secondary">QUESTION</div>
                                <strong class="text-white fs-6" id="singleQuestionCount">1 / 10</strong>
                            </div>
                            <div class="text-end font-monospace ms-2">
                                <div class="style-tiny text-secondary">TIME</div>
                                <strong class="text-warning fs-6" id="singleTimerSeconds">15s</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lifeline & Quick Info Row -->
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <button type="button" class="lifeline-btn" id="lifeline50Btn" onclick="useLifeline50()">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            <span>50:50 BANTUAN</span>
                            <span class="badge bg-black bg-opacity-50 text-white rounded-pill ms-1" id="lifelineBadge">5x</span>
                        </button>
                        <button type="button" class="lifeline-btn lifeline-btn-cyan" id="lifelineSkipBtn" onclick="useLifelineSkip()">
                            <i class="fa-solid fa-forward-step"></i>
                            <span>GANTI SOAL</span>
                            <span class="badge bg-black bg-opacity-50 text-white rounded-pill ms-1" id="lifelineSkipBadge">5x</span>
                        </button>
                    </div>

                    <div class="d-flex align-items-center gap-2 font-monospace style-tiny text-secondary">
                        <span>Streak: <strong class="text-warning" id="streakLabel">0🔥</strong></span>
                    </div>
                </div>

                <!-- Question Card Pod -->
                <div class="question-bubble-pod mb-3 mb-md-4 text-center">
                    <span class="badge bg-purple bg-opacity-25 text-purple border border-purple border-opacity-30 style-tiny font-monospace mb-2" id="questionCategoryPill">
                        🏛️ PEMERINTAHAN INDONESIA LUCU
                    </span>
                    <h3 class="h5 h4-md fw-bold text-white font-heading mb-0 px-2" id="questionText">
                        Memuat pertanyaan kuis...
                    </h3>
                </div>

                <!-- 4 Answer Choices Grid -->
                <div class="row g-2 g-md-3" id="choicesGrid">
                    <div class="col-12 col-md-6">
                        <button type="button" class="quiz-choice-btn" id="choiceBtn_0" onclick="handleChoiceClick(0)">
                            <span class="choice-letter">A</span>
                            <span class="choice-text" id="choiceText_0">Pilihan A</span>
                        </button>
                    </div>
                    <div class="col-12 col-md-6">
                        <button type="button" class="quiz-choice-btn" id="choiceBtn_1" onclick="handleChoiceClick(1)">
                            <span class="choice-letter">B</span>
                            <span class="choice-text" id="choiceText_1">Pilihan B</span>
                        </button>
                    </div>
                    <div class="col-12 col-md-6">
                        <button type="button" class="quiz-choice-btn" id="choiceBtn_2" onclick="handleChoiceClick(2)">
                            <span class="choice-letter">C</span>
                            <span class="choice-text" id="choiceText_2">Pilihan C</span>
                        </button>
                    </div>
                    <div class="col-12 col-md-6">
                        <button type="button" class="quiz-choice-btn" id="choiceBtn_3" onclick="handleChoiceClick(3)">
                            <span class="choice-letter">D</span>
                            <span class="choice-text" id="choiceText_3">Pilihan D</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- ==========================================
                 1. PRE-GAME / START CONFIG SCREEN OVERLAY
                 ========================================== -->
            <div class="quiz-screen-overlay" id="quizStartOverlay">
                <div class="max-w-md w-100 my-auto">
                    <div class="mb-2">
                        <div class="p-2.5 rounded-circle bg-gradient-purple text-white fs-3 d-inline-flex mb-1.5 shadow-lg border border-purple border-opacity-40">
                            <i class="fa-solid fa-brain"></i>
                        </div>
                        <h2 class="text-white font-heading fw-bold fs-4 mb-0">QUIZ MMC</h2>
                        <p class="text-secondary style-tiny mb-3">Tantang wawasan receh, kasus satir Indonesia, & sejarah Multimedia!</p>
                    </div>

                    <!-- Config Card -->
                    <div class="bg-dark bg-opacity-80 p-3 rounded-4 border border-secondary border-opacity-30 text-start mb-3">
                        
                        <!-- Mode Selector (Single vs 2-Player Duel) -->
                        <div class="mb-2.5">
                            <label class="form-label style-tiny text-secondary font-monospace mb-1">PILIH MODE GAME:</label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-purple flex-fill fw-bold active text-white" id="modeBtn_single" onclick="setQuizGameMode('single')">
                                    <i class="fa-solid fa-user me-1"></i> Single Player
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-info flex-fill fw-bold text-white" id="modeBtn_duel" onclick="setQuizGameMode('duel')">
                                    <i class="fa-solid fa-user-group me-1"></i> 2-Player Duel
                                </button>
                            </div>
                        </div>

                        <!-- Difficulty Selector -->
                        <div class="mb-2.5">
                            <label class="form-label style-tiny text-secondary font-monospace mb-1">TINGKAT KESULITAN & JUMLAH SOAL:</label>
                            <select id="startDifficultySelect" class="form-select form-select-sm bg-black text-warning border-secondary border-opacity-50 font-monospace fw-bold" onchange="updateDifficultyPreview(this.value)">
                                <option value="easy" selected>🟢 MUDAH (20 Soal • ❤️ 3 Nyawa • 5x 50:50 • 5x Ganti Soal)</option>
                                <option value="medium">🟡 SEDANG (35 Soal • ❤️ 5 Nyawa • 5x 50:50 • 5x Ganti Soal)</option>
                                <option value="hard">🔴 SUSAH (70 Soal • ❤️ 7 Nyawa • 5x 50:50 • 5x Ganti Soal)</option>
                                <option value="wni">🔥 WNI MODE (150 Soal • ❤️ 15 Nyawa • 5x 50:50 • 5x Ganti Soal)</option>
                            </select>
                        </div>

                        <!-- Player Profile Names & Avatars -->
                        <div class="row g-2 mb-1.5" id="p1InputRow">
                            <div class="col-7 col-sm-8">
                                <label class="form-label style-tiny text-secondary font-monospace mb-0.5">NAMA PLAYER 1:</label>
                                <input type="text" id="startP1Name" class="form-control form-control-sm bg-black text-white border-secondary border-opacity-50 font-monospace fw-bold" value="Player 1" maxlength="15">
                            </div>
                            <div class="col-5 col-sm-4">
                                <label class="form-label style-tiny text-secondary font-monospace mb-0.5">AVATAR WAJAH:</label>
                                <select id="startP1Avatar" class="form-select form-select-sm bg-black text-white border-secondary border-opacity-50 font-monospace">
                                    <optgroup label="😀 Ekspresi Wajah">
                                        <option value="😎" selected>😎 Kacamata Keren</option>
                                        <option value="🤓">🤓 Kutu Buku Jenius</option>
                                        <option value="🧐">🧐 Detektif Kritis</option>
                                        <option value="🤠">🤠 Koboi Santai</option>
                                        <option value="🥳">🥳 Pesta Hore</option>
                                        <option value="🤯">🤯 Kena Mental</option>
                                        <option value="🤪">🤪 Gokil Random</option>
                                        <option value="🥺">🥺 Pasrah Imut</option>
                                        <option value="😴">😴 Kurang Tidur</option>
                                        <option value="🤫">🤫 Intel Rahasia</option>
                                        <option value="😈">😈 Jahil Evil</option>
                                        <option value="🤩">🤩 Bintang Idola</option>
                                        <option value="😇">😇 Alim & Polos</option>
                                    </optgroup>
                                    <optgroup label="👤 Karakter & Profesi">
                                        <option value="👨‍💻">👨‍💻 Mas Programmer</option>
                                        <option value="👩‍💻">👩‍💻 Mbak Coder</option>
                                        <option value="👨‍💼">👨‍💼 Eksekutif Kantor</option>
                                        <option value="👩‍💼">👩‍💼 Karir SCBD</option>
                                        <option value="👨‍🎤">👨‍🎤 Vokalis Skena</option>
                                        <option value="👩‍🎤">👩‍🎤 Idol Panggung</option>
                                        <option value="👨‍🎓">👨‍🎓 Sarjana Pejuang</option>
                                        <option value="👩‍🎓">👩‍🎓 Mahasiswi Ambis</option>
                                        <option value="🧕">🧕 Ukhti Hijabers</option>
                                        <option value="🧔">🧔 Mas Brewokan</option>
                                        <option value="🧑‍🎨">🧑‍🎨 Desainer Kreatif</option>
                                        <option value="🕵️">🕵️ Hacker Cyber</option>
                                    </optgroup>
                                    <optgroup label="🎮 Karakter & Fantasy">
                                        <option value="🤖">🤖 Robot AI</option>
                                        <option value="🥷">🥷 Ninja Bayangan</option>
                                        <option value="🧙‍♂️">🧙‍♂️ Wizard Sakti</option>
                                        <option value="👸">👸 Ratu / Princess</option>
                                        <option value="🤴">🤴 Pangeran Sultan</option>
                                        <option value="👽">👽 Alien Mars</option>
                                        <option value="🧟">🧟 Pasien Begadang</option>
                                        <option value="🧛">🧛 Vampir Malam</option>
                                        <option value="👻">👻 Hantu Ramah</option>
                                        <option value="🐱">🐱 Kucing Oren</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <!-- Player 2 inputs (Shown in Duel mode) -->
                        <div class="row g-2 mb-1.5 d-none" id="p2InputRow">
                            <div class="col-7 col-sm-8">
                                <label class="form-label style-tiny text-info font-monospace mb-0.5">NAMA PLAYER 2:</label>
                                <input type="text" id="startP2Name" class="form-control form-control-sm bg-black text-info border-secondary border-opacity-50 font-monospace fw-bold" value="Player 2" maxlength="15">
                            </div>
                            <div class="col-5 col-sm-4">
                                <label class="form-label style-tiny text-info font-monospace mb-0.5">AVATAR WAJAH:</label>
                                <select id="startP2Avatar" class="form-select form-select-sm bg-black text-info border-secondary border-opacity-50 font-monospace">
                                    <optgroup label="😀 Ekspresi Wajah">
                                        <option value="🤖" selected>🤖 Robot AI</option>
                                        <option value="🤓">🤓 Kutu Buku Jenius</option>
                                        <option value="😎">😎 Kacamata Keren</option>
                                        <option value="🧐">🧐 Detektif Kritis</option>
                                        <option value="🤠">🤠 Koboi Santai</option>
                                        <option value="🥳">🥳 Pesta Hore</option>
                                        <option value="🤯">🤯 Kena Mental</option>
                                        <option value="🤪">🤪 Gokil Random</option>
                                        <option value="🥺">🥺 Pasrah Imut</option>
                                        <option value="😴">😴 Kurang Tidur</option>
                                        <option value="🤫">🤫 Intel Rahasia</option>
                                        <option value="😈">😈 Jahil Evil</option>
                                        <option value="🤩">🤩 Bintang Idola</option>
                                        <option value="😇">😇 Alim & Polos</option>
                                    </optgroup>
                                    <optgroup label="👤 Karakter & Profesi">
                                        <option value="👩‍💻">👩‍💻 Mbak Coder</option>
                                        <option value="👨‍💻">👨‍💻 Mas Programmer</option>
                                        <option value="👩‍💼">👩‍💼 Karir SCBD</option>
                                        <option value="👨‍💼">👨‍💼 Eksekutif Kantor</option>
                                        <option value="👩‍🎤">👩‍🎤 Idol Panggung</option>
                                        <option value="👨‍🎤">👨‍🎤 Vokalis Skena</option>
                                        <option value="👩‍🎓">👩‍🎓 Mahasiswi Ambis</option>
                                        <option value="👨‍🎓">👨‍🎓 Sarjana Pejuang</option>
                                        <option value="🧕">🧕 Ukhti Hijabers</option>
                                        <option value="🧔">🧔 Mas Brewokan</option>
                                        <option value="🧑‍🎨">🧑‍🎨 Desainer Kreatif</option>
                                        <option value="🕵️">🕵️ Hacker Cyber</option>
                                    </optgroup>
                                    <optgroup label="🎮 Karakter & Fantasy">
                                        <option value="🥷">🥷 Ninja Bayangan</option>
                                        <option value="🧙‍♂️">🧙‍♂️ Wizard Sakti</option>
                                        <option value="👸">👸 Ratu / Princess</option>
                                        <option value="🤴">🤴 Pangeran Sultan</option>
                                        <option value="👽">👽 Alien Mars</option>
                                        <option value="🧟">🧟 Pasien Begadang</option>
                                        <option value="🧛">🧛 Vampir Malam</option>
                                        <option value="👻">👻 Hantu Ramah</option>
                                        <option value="🐱">🐱 Kucing Oren</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                    </div>

                    <!-- Start Button -->
                    <button type="button" class="btn btn-lg btn-purple text-white font-heading fw-bold px-5 py-2.5 shadow-lg d-inline-flex align-items-center gap-2 mb-2 w-100 justify-content-center" onclick="startQuizGame()">
                        <i class="fa-solid fa-play"></i> MULAI KUIS SEKARANG
                    </button>
                    <div class="style-tiny text-secondary font-monospace">
                        <i class="fa-solid fa-shield-halved text-success me-1"></i> Soal & Pilihan di-acak otomatis setiap sesi permainan!
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 2. GAME OVER SCREEN OVERLAY (HEALTH RUN OUT)
                 ========================================== -->
            <div class="quiz-screen-overlay d-none" id="quizGameOverOverlay">
                <div class="max-w-md w-100 my-auto">
                    <div class="p-3 rounded-circle bg-danger bg-opacity-20 text-danger fs-1 d-inline-flex mb-2 border border-danger border-opacity-30">
                        <i class="fa-solid fa-heart-crack"></i>
                    </div>
                    <h3 class="text-white font-heading fw-bold fs-4 mb-0">NYAWA HABIS / GAME OVER!</h3>
                    <p class="text-secondary style-tiny mb-3">Darah kamu habis karena salah menjawab melebihi batas nyawa.</p>

                    <!-- Stats Card -->
                    <div class="bg-dark bg-opacity-85 p-3 rounded-4 border border-secondary border-opacity-30 mb-3 text-start">
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-secondary border-opacity-25">
                            <span class="text-secondary small">Player: <strong class="text-white" id="overP1Name">Player 1</strong></span>
                            <span class="badge bg-danger text-white font-monospace" id="overRankTitle">Gugur di Tengah Jalan</span>
                        </div>

                        <div class="row g-2 text-center my-1">
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-black border border-secondary border-opacity-25">
                                    <div class="style-tiny text-secondary">SKOR</div>
                                    <div class="fs-4 font-heading fw-bold text-white" id="overScore">0</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-black border border-secondary border-opacity-25">
                                    <div class="style-tiny text-success">BENAR</div>
                                    <div class="fs-4 font-heading fw-bold text-success" id="overCorrect">0</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-black border border-secondary border-opacity-25">
                                    <div class="style-tiny text-warning">STREAK</div>
                                    <div class="fs-4 font-heading fw-bold text-warning" id="overStreak">0🔥</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                        <button type="button" class="btn btn-purple text-white font-heading fw-bold px-3.5 py-2 shadow" onclick="startQuizGame()">
                            <i class="fa-solid fa-rotate-right me-1"></i> Coba Lagi
                        </button>
                        <button type="button" class="btn btn-outline-info text-white font-heading fw-bold px-3.5 py-2" onclick="openExplanationModal()">
                            <i class="fa-solid fa-book-open me-1"></i> Pembahasan Soal
                        </button>
                        <button type="button" class="btn btn-outline-secondary text-white px-3 py-2" onclick="showStartScreen()">
                            <i class="fa-solid fa-gear me-1"></i> Menu Awal
                        </button>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 3. VICTORY / COMPLETE SCREEN OVERLAY
                 ========================================== -->
            <div class="quiz-screen-overlay d-none" id="quizVictoryOverlay">
                <div class="max-w-md w-100 my-auto">
                    <div class="p-3 rounded-circle bg-warning bg-opacity-20 text-warning fs-1 d-inline-flex mb-2 border border-warning border-opacity-40 animate-bounce">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                    <h3 class="text-white font-heading fw-bold fs-4 mb-0" id="victoryTitle">KUIS SELESAI / VICTORY!</h3>
                    <p class="text-secondary style-tiny mb-3" id="victorySubtitle">Selamat! Kamu berhasil menjawab semua soal kuis!</p>

                    <!-- Stats Card -->
                    <div class="bg-dark bg-opacity-85 p-3 rounded-4 border border-secondary border-opacity-30 mb-3 text-start">
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-secondary border-opacity-25">
                            <span class="text-secondary small">Gelar: <strong class="text-warning font-heading" id="victoryRankBadge">Master WNI Bersertifikat</strong></span>
                            <span class="badge bg-success font-monospace" id="victoryAccuracyBadge">100% Akurat</span>
                        </div>

                        <div class="row g-2 text-center my-1">
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-black border border-secondary border-opacity-25">
                                    <div class="style-tiny text-secondary">TOTAL SKOR</div>
                                    <div class="fs-4 font-heading fw-bold text-warning" id="victoryScore">0</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-black border border-secondary border-opacity-25">
                                    <div class="style-tiny text-success">BENAR</div>
                                    <div class="fs-4 font-heading fw-bold text-success" id="victoryCorrect">0/10</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-black border border-secondary border-opacity-25">
                                    <div class="style-tiny text-info">MAX STREAK</div>
                                    <div class="fs-4 font-heading fw-bold text-info" id="victoryStreak">0🔥</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                        <button type="button" class="btn btn-warning text-dark font-heading fw-bold px-3.5 py-2 shadow" onclick="startQuizGame()">
                            <i class="fa-solid fa-rotate-right me-1"></i> Main Lagi
                        </button>
                        <button type="button" class="btn btn-outline-info text-white font-heading fw-bold px-3.5 py-2" onclick="openExplanationModal()">
                            <i class="fa-solid fa-book-open me-1"></i> Pembahasan Soal
                        </button>
                        <button type="button" class="btn btn-outline-secondary text-white px-3 py-2" onclick="showStartScreen()">
                            <i class="fa-solid fa-gear me-1"></i> Ganti Level
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <!-- =========================================================
             TUTORIAL SECTION: PANDUAN CARA BERMAIN & TOPIK SOAL (VERTICAL LIST)
             ========================================================= -->
        <div class="saas-card saas-card-glow border border-secondary border-opacity-25 p-3 p-md-4 mb-4 rounded-4">
            <div class="mb-3 mb-md-4">
                <span class="badge bg-purple text-white font-monospace px-3 py-1 mb-2 fw-bold"><i class="fa-solid fa-gamepad me-1"></i> PANDUAN GAMEPLAY</span>
                <h2 class="h4 fw-bold text-body font-heading mb-1">Panduan & Cara Bermain Quiz MMC</h2>
                <p class="text-secondary small mb-0">
                    Kuis interaktif berbasis waktu yang menggabungkan wawasan sejarah ekstrakurikuler Multimedia Club, satir kasus Indonesia, tren Gen-Z, IT/kamera, dan tebak-tebakan receh!
                </p>
            </div>

            <div class="d-flex flex-column gap-3">
                <!-- Item 1: Mode Permainan -->
                <div class="guide-list-item p-3 p-md-3.5 rounded-4 bg-body-secondary border border-secondary border-opacity-20 d-flex flex-column flex-md-row align-items-start gap-3">
                    <div class="guide-icon-avatar bg-gradient-purple flex-shrink-0">
                        <i class="fa-solid fa-user-group"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-1.5 flex-wrap">
                            <span class="badge bg-purple bg-opacity-25 text-purple border border-purple border-opacity-25 font-monospace style-tiny px-2 py-0.5">STEP 01</span>
                            <h6 class="text-body font-heading fw-bold mb-0">Pilihan Mode: Single Player vs 2-Player Duel</h6>
                        </div>
                        <p class="text-secondary small mb-0 lh-base">
                            <strong>Single Player:</strong> Selesaikan seluruh rangkaian soal sendiri untuk menguji akurasi dan streak kecepatanmu.<br>
                            <strong>2-Player Duel (Pass & Play):</strong> Player 1 dan Player 2 bergantian menjawab per soal pada 1 perangkat secara bergantian. Bersainglah mengumpulkan skor tertinggi!
                        </p>
                    </div>
                </div>

                <!-- Item 2: Nyawa & Bantuan -->
                <div class="guide-list-item p-3 p-md-3.5 rounded-4 bg-body-secondary border border-secondary border-opacity-20 d-flex flex-column flex-md-row align-items-start gap-3">
                    <div class="guide-icon-avatar bg-gradient-red flex-shrink-0">
                        <i class="fa-solid fa-heart-pulse"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-1.5 flex-wrap">
                            <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 font-monospace style-tiny px-2 py-0.5">STEP 02</span>
                            <h6 class="text-body font-heading fw-bold mb-0">Sistem Nyawa (❤️) & Bantuan (50:50 & Ganti Soal)</h6>
                        </div>
                        <p class="text-secondary small mb-0 lh-base">
                            <strong>❤️ Nyawa (Heal):</strong> Tersedia <strong>3x Nyawa</strong> (Mudah - 20 soal), <strong>5x Nyawa</strong> (Sedang - 35 soal), <strong>7x Nyawa</strong> (Susah - 70 soal), dan <strong>15x Nyawa</strong> (WNI Mode - 150 soal). Darah berkurang jika salah menjawab atau waktu habis.<br>
                            <strong>✨ Bantuan 50:50 & ⏭️ Ganti Soal:</strong> Kuota <strong>5x Bantuan 50:50</strong> untuk membuang 2 pilihan salah dan <strong>5x Opsi Ganti Soal</strong> untuk melewati pertanyaan sulit secara gratis tanpa resiko kehilangan darah.
                        </p>
                    </div>
                </div>

                <!-- Item 3: Cakupan Kategori Soal Lengkap -->
                <div class="guide-list-item p-3 p-md-3.5 rounded-4 bg-body-secondary border border-secondary border-opacity-20 d-flex flex-column flex-md-row align-items-start gap-3">
                    <div class="guide-icon-avatar bg-gradient-amber flex-shrink-0">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-1.5 flex-wrap">
                            <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25 font-monospace style-tiny px-2 py-0.5">STEP 03</span>
                            <h6 class="text-body font-heading fw-bold mb-0">Cakupan Topik Soal Lengkap (225+ Soal)</h6>
                        </div>
                        <p class="text-secondary small mb-2 lh-base">
                            Pertanyaan selalu diacak secara dinamis mencakup tema-tema seru dan mendalam:
                        </p>
                        <div class="d-flex flex-wrap gap-1.5">
                            <span class="badge bg-body text-body border border-secondary border-opacity-25 font-normal py-1 px-2.5 small"><i class="fa-solid fa-camera-retro text-purple me-1"></i> Ekskul Multimedia SMANIT</span>
                            <span class="badge bg-body text-body border border-secondary border-opacity-25 font-normal py-1 px-2.5 small"><i class="fa-solid fa-heart-crack text-danger me-1"></i> Asmara, HTS & Bucin</span>
                            <span class="badge bg-body text-body border border-secondary border-opacity-25 font-normal py-1 px-2.5 small"><i class="fa-solid fa-heart text-success me-1"></i> Percintaan Sehat (Green Flag)</span>
                            <span class="badge bg-body text-body border border-secondary border-opacity-25 font-normal py-1 px-2.5 small"><i class="fa-solid fa-person-running text-warning me-1"></i> Kelakuan Ajaib WNI</span>
                            <span class="badge bg-body text-body border border-secondary border-opacity-25 font-normal py-1 px-2.5 small"><i class="fa-solid fa-utensils text-warning me-1"></i> MBG & Koperasi Desa</span>
                            <span class="badge bg-body text-body border border-secondary border-opacity-25 font-normal py-1 px-2.5 small"><i class="fa-solid fa-fire text-danger me-1"></i> Karhutla & Isu Lingkungan</span>
                            <span class="badge bg-body text-body border border-secondary border-opacity-25 font-normal py-1 px-2.5 small"><i class="fa-solid fa-landmark-flag text-info me-1"></i> Fakta Unik & WNI Satir</span>
                            <span class="badge bg-body text-body border border-secondary border-opacity-25 font-normal py-1 px-2.5 small"><i class="fa-solid fa-ghost text-purple me-1"></i> Konspirasi & Mitos Nusantara</span>
                            <span class="badge bg-body text-body border border-secondary border-opacity-25 font-normal py-1 px-2.5 small"><i class="fa-solid fa-headphones text-primary me-1"></i> Skena Musik Gen-Z 2026</span>
                            <span class="badge bg-body text-body border border-secondary border-opacity-25 font-normal py-1 px-2.5 small"><i class="fa-solid fa-video text-danger me-1"></i> Video Editing & Fotografi</span>
                            <span class="badge bg-body text-body border border-secondary border-opacity-25 font-normal py-1 px-2.5 small"><i class="fa-solid fa-code text-success me-1"></i> IT & Derita Programmer</span>
                            <span class="badge bg-body text-body border border-secondary border-opacity-25 font-normal py-1 px-2.5 small"><i class="fa-solid fa-clapperboard text-info me-1"></i> Sejarah MMC & Tebakan Receh</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- =========================================================
     MODAL 1: QUIZ SETTINGS & CUSTOMIZATION
     ========================================================= -->
<div class="modal fade" id="quizSettingsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border border-purple border-opacity-50 text-white rounded-4 shadow-2xl p-3 p-md-4">
            <div class="modal-header border-bottom border-secondary border-opacity-25 pb-2.5">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-sliders text-purple fs-5"></i>
                    <div>
                        <h5 class="modal-title font-heading fw-bold fs-6">Pengaturan Quiz MMC</h5>
                        <div class="text-secondary style-tiny">Sesuaikan durasi timer, mode suara, dan tingkat kesulitan</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body py-3">
                <div class="mb-3">
                    <label class="form-label small text-secondary font-monospace mb-1">DURASI TIMER PER SOAL:</label>
                    <select id="modalTimerSelect" class="form-select form-select-sm bg-black text-warning border-secondary border-opacity-50 font-monospace fw-bold">
                        <option value="15" selected>15 Detik (Standar Arcade Cepat)</option>
                        <option value="20">20 Detik (Santai & Berpikir)</option>
                        <option value="10">10 Detik (Hardcore Fast Reflex)</option>
                        <option value="0">Tanpa Timer (Santai Banget)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small text-secondary font-monospace mb-1">EFEK SUARA & SFX:</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="modalAudioSwitch" checked>
                        <label class="form-check-label small text-white" for="modalAudioSwitch">Aktifkan efek suara sintetis (Web Audio API)</label>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-top border-secondary border-opacity-25 pt-2.5">
                <button type="button" class="btn btn-sm btn-outline-secondary px-3 text-white" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-sm btn-purple text-white font-heading fw-bold px-4" onclick="saveQuizModalSettings()">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Pengaturan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     MODAL 2: QUIZ LEADERBOARD
     ========================================================= -->
<div class="modal fade" id="quizLeaderboardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border border-warning border-opacity-50 text-white rounded-4 shadow-2xl p-3 p-md-4">
            <div class="modal-header border-bottom border-secondary border-opacity-25 pb-2.5">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-trophy text-warning fs-5"></i>
                    <div>
                        <h5 class="modal-title font-heading fw-bold fs-6">Papan Rekor Quiz MMC</h5>
                        <div class="text-secondary style-tiny">Skor tertinggi yang berhasil dicapai di perangkat ini</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body py-3" id="quizLeaderboardBody">
                <div class="text-center text-secondary py-3">Memuat rekor...</div>
            </div>

            <div class="modal-footer border-top border-secondary border-opacity-25 pt-2.5">
                <button type="button" class="btn btn-sm btn-outline-danger me-auto" onclick="clearQuizLeaderboard()">
                    <i class="fa-solid fa-trash me-1"></i> Reset Rekor
                </button>
                <button type="button" class="btn btn-sm btn-warning text-dark font-heading fw-bold px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     MODAL 3: QUIZ EXPLANATIONS & REVIEW (PEMBAHASAN SOAL)
     ========================================================= -->
<div class="modal fade" id="quizExplanationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content bg-dark border border-info border-opacity-50 text-white rounded-4 shadow-2xl">
            <div class="modal-header border-bottom border-secondary border-opacity-25 pb-2.5">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-book-open text-info fs-5"></i>
                    <div>
                        <h5 class="modal-title font-heading fw-bold fs-6">Pembahasan & Penjelasan Soal</h5>
                        <div class="text-secondary style-tiny" id="explanationModalSubtitle">Rangkuman kunci jawaban dan penjelasan lengkap kuis</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-3 p-md-4">
                <!-- Filter Tabs -->
                <div class="d-flex gap-1.5 mb-3 flex-wrap" id="explanationFilterGroup">
                    <button type="button" class="btn btn-sm btn-info text-dark font-monospace fw-bold active" id="expFilterAllBtn" onclick="filterExplanationList('all', this)">Semua Soal (<span id="expCountAll">0</span>)</button>
                    <button type="button" class="btn btn-sm btn-outline-success font-monospace fw-bold" id="expFilterCorrectBtn" onclick="filterExplanationList('correct', this)">Jawaban Benar (<span id="expCountCorrect">0</span>)</button>
                    <button type="button" class="btn btn-sm btn-outline-danger font-monospace fw-bold" id="expFilterWrongBtn" onclick="filterExplanationList('wrong', this)">Jawaban Salah (<span id="expCountWrong">0</span>)</button>
                </div>

                <!-- Explanation Cards Container -->
                <div class="d-flex flex-column gap-3" id="explanationCardsContainer">
                    <div class="text-center text-secondary py-4">Belum ada soal yang dijawab.</div>
                </div>
            </div>

            <div class="modal-footer border-top border-secondary border-opacity-25 pt-2.5">
                <button type="button" class="btn btn-sm btn-outline-secondary px-3 text-white" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-sm btn-purple text-white font-heading fw-bold px-4" onclick="startQuizGame()" data-bs-dismiss="modal">
                    <i class="fa-solid fa-rotate-right me-1"></i> Main Lagi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     COMPREHENSIVE 120+ QUESTION BANK (WNI SATIR, RECEH, MMC)
     ========================================================= -->
<script>
const QUIZ_QUESTION_BANK = [
    // --- 1. SEJARAH & FAKTA MULTIMEDIA CLUB SMAN 1 TAMANSARI ---
    {
        category: '🎬 Sejarah Multimedia Club',
        question: 'Kapan tanggal resmi berdirinya Ekstrakurikuler Multimedia Club SMA Negeri 1 Tamansari?',
        options: ['23 Oktober 2017', '17 Agustus 2018', '2 Mei 2016', '10 November 2019'],
        correct: 0,
        explanation: 'Didirikan pada 23 Oktober 2017 setelah presentasi di hadapan pihak sekolah.'
    },
    {
        category: '🎬 Sejarah Multimedia Club',
        question: 'Siapakah Ketua Umum Pertama sekaligus pendiri Ekstrakurikuler Multimedia Club?',
        options: ['Rizki Agung Sentosa', 'Reka Bayu', 'Alhalim', 'M. Zidan'],
        correct: 0,
        explanation: 'Rizki Agung Sentosa adalah pendiri dan ketua umum pertama Multimedia Club.'
    },
    {
        category: '🎬 Sejarah Multimedia Club',
        question: 'Siapakah Guru Pembina pertama saat Multimedia Club resmi didirikan tahun 2017?',
        options: ['Bu Gina', 'Pak Joko', 'Bu Sri', 'Pak Budi'],
        correct: 0,
        explanation: 'Bu Gina adalah pembina pertama kepengurusan Multimedia Club tahun 2017.'
    },
    {
        category: '🎬 Sejarah Multimedia Club',
        question: 'Mengapa logo ekstrakurikuler Multimedia Club berbentuk Laptop?',
        options: [
            'Terinspirasi dari kebiasaan mencatat ide film & aplikasi pakai laptop',
            'Karena sekolah memberi sumbangan 100 laptop',
            'Agar terlihat seperti hacker profesional',
            'Mengikuti logo vendor komputer sponsor'
        ],
        correct: 0,
        explanation: 'Dalam setiap pertemuan awal, laptop selalu menjadi alat utama mencatat ide trailer film & aplikasi sekolah.'
    },
    {
        category: '🎬 Sejarah Multimedia Club',
        question: 'Pada tahun 2019, Multimedia Club mencatatkan rekor anggota terbanyak sejumlah berapa orang?',
        options: ['118 Orang', '50 Orang', '85 Orang', '250 Orang'],
        correct: 0,
        explanation: 'Multimedia Club pernah menjadi ekskul dengan jumlah anggota terbanyak di SMAN 1 Tamansari, yakni 118 orang pada tahun 2019.'
    },
    {
        category: '🎬 Sejarah Multimedia Club',
        question: 'Siapakah yang menjabat sebagai Ketua Programmer pertama pada kepengurusan 2017?',
        options: ['Reka Bayu', 'Yudis Maulana', 'Zahwa', 'Denada'],
        correct: 0,
        explanation: 'Reka Bayu menjabat sebagai Ketua Divisi Programmer pertama tahun 2017.'
    },
    {
        category: '🎬 Sejarah Multimedia Club',
        question: 'Film karya Multimedia Club yang meraih Juara 1 FLS2N Film berjudul apa?',
        options: ['Gawang', 'Lawankarsa', 'Figur Puan', 'Bersenyawa'],
        correct: 0,
        explanation: 'Film "Gawang" berhasil meraih Juara 1 di ajang FLS2N Film.'
    },
    {
        category: '🎬 Sejarah Multimedia Club',
        question: 'Film karya Multimedia Club yang meraih Juara 3 di Dwiwarna Film Festival berjudul apa?',
        options: ['Lawankarsa', 'Gawang', 'Bersenyawa', 'Figur Puan'],
        correct: 0,
        explanation: 'Film "Lawankarsa" berhasil meraih Juara 3 di Dwiwarna Film Festival.'
    },
    {
        category: '🎬 Sejarah Multimedia Club',
        question: 'Dua film pendek karya produksi Multimedia Club yang dirilis pada tahun 2019 adalah...',
        options: ['Figur Puan & Bersenyawa', 'Gawang & Lawankarsa', 'Maju Terus & Pantang Mundur', 'Bukan Salah Laptop & Kamera'],
        correct: 0,
        explanation: 'Film "Figur Puan" (2019) dan "Bersenyawa" (2019) adalah dua film produksi tahun 2019.'
    },
    {
        category: '🎬 Sejarah Multimedia Club',
        question: 'Pada tahun 2018, apa saja 4 divisi resmi di dalam Multimedia Club?',
        options: [
            'Programming, Editing, Jurnalistik, Sinematografi',
            'Gaming, TikToker, YouTuber, Selebgram',
            'Hardware, Fotokopi, Kabel LAN, Sablon Kaos',
            'Kameramen, Sutradara, Aktor, Penonton'
        ],
        correct: 0,
        explanation: '4 Divisi tahun 2018: Programming, Editing, Jurnalistik, Sinematografi.'
    },

    // --- 2. PEMERINTAHAN INDONESIA LUCU & WNI SATIR ---
    {
        category: '🏛️ WNI Satir & Kebijakan',
        question: 'Nama aplikasi bansos milik salah satu Pemda di Jawa Barat yang sempat viral karena singkatan nyelenehnya adalah...',
        options: ['SiPepek', 'SiKasep', 'SiPinter', 'SiBansos'],
        correct: 0,
        explanation: 'SiPepek adalah singkatan dari Sistem Pelayanan Program Penanggulangan Kemiskinan di Pemkab Cirebon.'
    },
    {
        category: '🏛️ WNI Satir & Kebijakan',
        question: 'Apa fungsi utama sirine mobil patwal pengawal pejabat saat jalanan macet total?',
        options: ['Mempertegas kasta di jalan raya', 'Memanggil tukang tahu bulat', 'Membuka portal dimensi lain', 'Menyanyi karaoke darurat'],
        correct: 0,
        explanation: 'Biar rakyat minggir duluan menikmati kemacetan dengan penuh ketabahan.'
    },
    {
        category: '🏛️ WNI Satir & Kebijakan',
        question: 'Ketika situs instansi pemerintah kena retas (deface), alasan klasik pejabat humas adalah...',
        options: ['Sedang dalam pemeliharaan rutin', 'Server kena santet online', 'Admin lagi keluar beli seblak', 'Hacker hanya numpang parkir'],
        correct: 0,
        explanation: '"Tidak ada kebocoran data, hanya sedang maintenance sistem berkala."'
    },
    {
        category: '🏛️ WNI Satir & Kebijakan',
        question: 'Password paling aman dan legendaris untuk router server instansi di Indonesia adalah...',
        options: ['admin123', 'rahasianegara2024!@#', 'janganDiHekYa123', 'passwordkuatsekali'],
        correct: 0,
        explanation: 'Statistik membuktikan admin123 adalah warisan turun-temurun tak tergantikan.'
    },
    {
        category: '🏛️ WNI Satir & Kebijakan',
        question: 'Tindakan paling efektif pejabat saat rapat anggaran penting berlangsung di gedung dewan adalah...',
        options: ['Tidur pulas bertumpu pada mic', 'Mencatat aspirasi rakyat', 'Membaca laporan APBD 500 halaman', 'Membagikan kuota gratis'],
        correct: 0,
        explanation: 'Tidur adalah bentuk meditasi demi keselamatan keuangan negara.'
    },
    {
        category: '🏛️ WNI Satir & Kebijakan',
        question: 'Proyek renovasi trotoar ramah pejalan kaki di Indonesia biasanya selesai dengan bonus...',
        options: ['Tiang listrik persis di tengah jalur kuning disabilitas', 'Jalur karpet merah', 'Eskalator otomatis', 'Pijat refleksi gratis'],
        correct: 0,
        explanation: 'Sebuah ujian konsentrasi dan ketangkasan bagi pejalan kaki tuna netra.'
    },
    {
        category: '🏛️ WNI Satir & Kebijakan',
        question: 'Nama file dokumen negara yang paling valid dan tidak akan direvisi lagi adalah...',
        options: ['Laporan_Final_Fix_Beneran_Terbaru_V2_ACC_Bismillah.docx', 'Laporan.pdf', 'Document1.docx', 'Final.doc'],
        correct: 0,
        explanation: 'Semakin banyak kata "Fix" dan "Bismillah", semakin sakral dokumen tersebut.'
    },
    {
        category: '🏛️ WNI Satir & Kebijakan',
        question: 'Apa yang terjadi jika ada jalan rusak bertahun-tahun di daerah terpencil?',
        options: ['Baru diaspal 1 hari sebelum Presiden berkunjung', 'Langsung dibangun jalan tol', 'Ditanami pohon pisang oleh warga', 'Menjadi sirkuit motocross resmi'],
        correct: 0,
        explanation: 'Kunjungan RI-1 adalah katalisator pengaspalan tercepat di muka bumi (Roro Jonggrang style).'
    },
    {
        category: '🏛️ WNI Satir & Kebijakan',
        question: 'Ciri khas situs web proyek pemerintah seharga ratusan juta rupiah adalah...',
        options: ['Pakai template WordPress bajakan & running text berita 2018', 'Loading secepat kilat', 'Menggunakan AI super canggih', 'Bebas dari foto baliho kepala dinas'],
        correct: 0,
        explanation: 'Plus banner ucapan selamat hari raya Idul Fitri tahun lalu yang belum dihapus.'
    },
    {
        category: '🏛️ WNI Satir & Kebijakan',
        question: 'Reaksi warganet Indonesia paling cepat saat melihat ada bule mengkritik budaya lokal di medsos:',
        options: ['Menyerbu kolom komentar dengan "Silaturahmi Online"', 'Menerima kritik dengan lapang dada', 'Membaca jurnal ilmiah pembanding', 'Mengabaikan postingan tersebut'],
        correct: 0,
        explanation: 'Batalion jempol netizen +62 memiliki kecepatan respons di atas jet tempur supersonik.'
    },

    // --- 3. INDONESIA RECEH & TEBAK-TEBAKAN BAPAK-BAPAK ---
    {
        category: '🇮🇩 Tebak-tebakan Receh',
        question: 'Ban apa yang enak dimakan pakai nasi anget dan sambal?',
        options: ['Bandeng presto', 'Banteng merah', 'Bangau panggang', 'Bantal guling'],
        correct: 0,
        explanation: 'Ikan Bandeng presto duri lunak tiada tandingan!'
    },
    {
        category: '🇮🇩 Tebak-tebakan Receh',
        question: 'Kenapa nyamuk kalau terbang bunyinya nging-nging di telinga?',
        options: ['Karena minum darah. Kalau minum bensin bunyinya ngeng-ngeng', 'Karena tidak bisa nyanyi dangdut', 'Karena lagi latihan vokal', 'Karena lupa pasang knalpot brong'],
        correct: 0,
        explanation: 'Kalau minum solar bunyinya klepek-klepek ala mesin diesel!'
    },
    {
        category: '🇮🇩 Tebak-tebakan Receh',
        question: 'Pocong apa yang paling disukai oleh emak-emak pas belanja di mall?',
        options: ['Pocongan harga (Diskon)', 'Pocong mumun', 'Pocong terbang', 'Pocong berduit'],
        correct: 0,
        explanation: 'Pocongan harga 70% + 20% langsung diborong semua!'
    },
    {
        category: '🇮🇩 Tebak-tebakan Receh',
        question: 'Kota di Jawa Tengah yang namanya paling banyak dipanggil sama anak kecil?',
        options: ['Purwodadi (For what, Daddy?)', 'Semarang', 'Solo', 'Magelang'],
        correct: 0,
        explanation: '"For what, Daddy?" -> Purwodadi!'
    },
    {
        category: '🇮🇩 Tebak-tebakan Receh',
        question: 'Hewan apa yang bersaudara dan selalu kompak?',
        options: ['Katak beradik (Kakak beradik)', 'Ular tangga', 'Gajah terbang', 'Semut merah'],
        correct: 0,
        explanation: 'Katak beradik (Kakak-beradik) hidup rukun sentosa.'
    },
    {
        category: '🇮🇩 Tebak-tebakan Receh',
        question: 'Penyanyi luar negeri yang suka buang angin sembarangan?',
        options: ['Justin Kentut (Bieber)', 'Bruno Mars', 'Ed Sheeran', 'Michael Jackson'],
        correct: 0,
        explanation: 'Justin Kentut (Justin Bieber versi bapak-bapak pos ronda).'
    },
    {
        category: '🇮🇩 Tebak-tebakan Receh',
        question: 'Kue apa yang bikin orang selalu kaget dan teriak?',
        options: ['Kue-t (Kaget / DOR!)', 'Kue cucur', 'Kue putu', 'Kue bolu'],
        correct: 0,
        explanation: 'Kue-jut (Kejut / Kaget)!'
    },
    {
        category: '🇮🇩 Tebak-tebakan Receh',
        question: 'Tahu apa yang ukurannya paling besar di seluruh dunia?',
        options: ['Tahu isi... semesta!', 'Tahu sumedang', 'Tahu gimbal', 'Tahu gejrot'],
        correct: 0,
        explanation: 'Tahu isi alam semesta tak terhingga luasnya!'
    },
    {
        category: '🇮🇩 Tebak-tebakan Receh',
        question: 'Daun apa yang tidak bisa disentuh dan tidak pernah gugur ke tanah?',
        options: ['Daun touch me (Don\'t touch me)', 'Daun singkong', 'Daun pisang', 'Daun salam'],
        correct: 0,
        explanation: '"Daun touch me" plesetan bahasa inggris Don\'t Touch Me!'
    },
    {
        category: '🇮🇩 Tebak-tebakan Receh',
        question: 'Kenapa pohon kelapa di depan rumah harus ditebang?',
        options: ['Karena kalau dicabut berat banget!', 'Karena takut kena petir', 'Biar tetangga tidak numpang teduh', 'Karena mau diganti pohon toge'],
        correct: 0,
        explanation: 'Coba aja cabut sendiri kalau kuat!'
    },

    // --- 4. GEN Z & INTERNET CULTURE ---
    {
        category: '⚡ Gen Z & Tren Internet',
        question: 'Istilah "Rizz" dalam bahasa gaul Gen Z memiliki arti...',
        options: ['Karisma memikat & merayu lawan jenis', 'Beras impor kualitas super', 'Rasa pedas level 10', 'Suara mesin motor bocor'],
        correct: 0,
        explanation: 'Rizz berasal dari kata Cha-RIZZ-ma (Charisma).'
    },
    {
        category: '⚡ Gen Z & Tren Internet',
        question: 'Apa tujuan utama gerakan "Mewing" yang viral di kalangan Gen Z?',
        options: ['Memperbaiki struktur rahang biar tajam ala Sigma', 'Menirukan suara anak kucing', 'Mengusir nyamuk di kamar', 'Menenangkan pikiran saat UTS'],
        correct: 0,
        explanation: 'Mewing adalah menempelkan lidah ke langit-langit mulut demi jawline tajam.'
    },
    {
        category: '⚡ Gen Z & Tren Internet',
        question: 'Fenomena takut ketinggalan tren atau berita viral di media sosial disebut...',
        options: ['FOMO (Fear Of Missing Out)', 'YOLO (You Only Live Once)', 'POV (Point Of View)', 'OOTD (Outfit Of The Day)'],
        correct: 0,
        explanation: 'FOMO membuat orang scrolling reels jam 3 pagi tiada henti.'
    },
    {
        category: '⚡ Gen Z & Tren Internet',
        question: 'Waktu resmi internasional bagi Gen Z untuk Overthinking masa depan dan jodoh:',
        options: ['Jam 02:00 - 03:30 Pagi', 'Jam 07:00 Pagi saat upacara', 'Jam 12:00 Siang pas makan', 'Jam 18:00 Sore pas magrib'],
        correct: 0,
        explanation: 'Diiringi lagu indie, lampu kamar remang-remang, dan scroll foto 5 tahun lalu.'
    },
    {
        category: '⚡ Gen Z & Tren Internet',
        question: 'Kata "Tubir" di Twitter / X adalah kebalikan dari kata...',
        options: ['Ribut', 'Tidur', 'Bibir', 'Libur'],
        correct: 0,
        explanation: 'Tubir = Ribut (adu argumen tanpa ujung di medsos).'
    },
    {
        category: '⚡ Gen Z & Tren Internet',
        question: 'Gaya busana aesthetic dengan pita pastel dan nuansa feminin vintage disebut...',
        options: ['Coquette aesthetic', 'Skena kalcer', 'Outfit mamba', 'Cewek kue'],
        correct: 0,
        explanation: 'Coquette identik dengan pita pink dan gaya anggun feminin.'
    },
    {
        category: '⚡ Gen Z & Tren Internet',
        question: 'Istilah "Skena" di kalangan anak nongkrong kopi awalnya singkatan dari...',
        options: ['Sua, Cangkruk, Kelana', 'Senang Kena Pajak', 'Sekolah Kena Denda', 'Semua Kena Tipu'],
        correct: 0,
        explanation: 'Skena sering diasosiasikan dengan komunitas penikmat musik/kopi independen.'
    },

    // --- 5. KAMERA, KOMPUTER & IT PROGRAMMING ---
    {
        category: '💻 Kamera & Komputer',
        question: 'Fenomena mistis colokan USB Tipe-A di komputer adalah...',
        options: [
            'Selalu salah di percobaan ke-1 dan ke-2, baru masuk di percobaan ke-3',
            'Bisa menghasilkan uang digital',
            'Otomatis terhubung ke satelit NASA',
            'Mengeluarkan aroma pandan saat panas'
        ],
        correct: 0,
        explanation: 'Hukum fisika kuantum USB Tipe-A: selalu butuh 3x putaran bolak-balik.'
    },
    {
        category: '💻 Kamera & Komputer',
        question: 'Penyebab utama programmer menangis darah selama 4 jam berturut-turut:',
        options: [
            'Lupa titik koma (;) di baris 42',
            'Layar monitor kurang besar',
            'Keyboard tidak ada lampu RGB',
            'Kopi tumpah ke celana'
        ],
        correct: 0,
        explanation: 'SyntaxError: Unexpected token. Hanya gara-gara 1 titik koma nyempil!'
    },
    {
        category: '💻 Kamera & Komputer',
        question: 'Apa yang terjadi jika kamu memotret malam hari dengan settingan ISO 51.200?',
        options: [
            'Foto dipenuhi noise dan semut digital tawuran',
            'Kamera mengeluarkan kilat petir',
            'Lensa kamera langsung pecah',
            'Hasil foto otomatis jadi lukisan Monalisa'
        ],
        correct: 0,
        explanation: 'Grain dan chromatic noise akan menutupi seluruh objek foto.'
    },
    {
        category: '💻 Kamera & Komputer',
        question: 'Kombinasi tombol keyboard paling keramat penyelamat karir saat mengedit:',
        options: ['Ctrl + S (Save tiap 3 detik)', 'Alt + F4', 'Ctrl + Alt + Del', 'Caps Lock berulang kali'],
        correct: 0,
        explanation: 'Refleks jempol menekan Ctrl + S terbentuk dari trauma aplikasi force close.'
    },
    {
        category: '💻 Kamera & Komputer',
        question: 'Bunyi "Bip-Bip-Bip" panjang saat komputer PC dinyalakan menandakan...',
        options: ['RAM kendor atau berdebu (waktunya gosok penghapus)', 'Komputer lagi main tebak lagu', 'Hardisk minta makan mie instan', 'Kipas processor minta naik gaji'],
        correct: 0,
        explanation: 'Solusi teknisi sejati: copot RAM, tiup slotnya, gosok pin emas pakai penghapus pensil!'
    },
    {
        category: '💻 Kamera & Komputer',
        question: 'Kamera jenis apa yang layarnya bisa diputar 180 derajat untuk vlogging?',
        options: ['Articulating / Flip Screen Mirrorless', 'Kamera Analog Lubang Jarum', 'Kamera CCTV Jalan Tol', 'Kamera Tilang Elektronik'],
        correct: 0,
        explanation: 'Flip screen memudahkan kreator konten memonitor framing saat merekam diri sendiri.'
    },

    // --- 6. FAKTA MENARIK DUNIA & SAINS ---
    {
        category: '🌍 Fakta Unik Dunia',
        question: 'Berapa jumlah jantung yang dimiliki oleh seekor Gurita (Octopus)?',
        options: ['3 Jantung', '1 Jantung', '2 Jantung', 'Tidak punya jantung'],
        correct: 0,
        explanation: 'Gurita memiliki 3 jantung dan darah berwarna biru karena tembaga (hemocyanin).'
    },
    {
        category: '🌍 Fakta Unik Dunia',
        question: 'Bahan makanan alami apa yang tidak pernah basi bahkan setelah ribuan tahun?',
        options: ['Madu murni', 'Minyak kelapa', 'Beras ketan', 'Gula merah'],
        correct: 0,
        explanation: 'Madu murni yang ditemukan di makam piramida Mesir kuno berusia 3.000 tahun masih layak makan.'
    },
    {
        category: '🌍 Fakta Unik Dunia',
        question: 'Mamalia satu-satunya di dunia yang bisa terbang dengan sayap aslinya adalah...',
        options: ['Kelelawar', 'Tupai terbang', 'Burung unta', 'Lemur terbang'],
        correct: 0,
        explanation: 'Tupai hanya melayang (gliding), sedangkan kelelawar memiliki sayap dan terbang sejati.'
    },
    {
        category: '🌍 Fakta Unik Dunia',
        question: 'Bagian tubuh manusia yang tidak memiliki suplai pembuluh darah sama sekali adalah...',
        options: ['Kornea mata', 'Ujung lidah', 'Daun telinga', 'Kuku jempol'],
        correct: 0,
        explanation: 'Kornea mata mengambil oksigen langsung dari udara bebas di sekitarnya.'
    },

    // --- 7. PERTANYAAN EKSTRA (SEJARAH MMC, WNI & IT RECEH) ---
    {
        category: '🎬 Sejarah Multimedia Club',
        question: 'Siapakah yang menjabat sebagai Wakil Ketua pertama pada kepengurusan Multimedia Club 2017?',
        options: ['Alhalim', 'Reka Bayu', 'M. Zidan', 'Yudis Maulana'],
        correct: 0,
        explanation: 'Alhalim adalah Wakil Ketua kepengurusan pertama Multimedia Club mendampingi Rizki Agung Sentosa.'
    },
    {
        category: '🎬 Sejarah Multimedia Club',
        question: 'Siapakah Bendahara pertama kepengurusan Multimedia Club tahun 2017?',
        options: ['Denada', 'Zahwa', 'Bu Gina', 'Siti Nurhaliza'],
        correct: 0,
        explanation: 'Denada adalah Bendahara pertama Multimedia Club tahun 2017.'
    },
    {
        category: '🎬 Sejarah Multimedia Club',
        question: 'Siapakah Ketua Divisi Sinematografi pertama kepengurusan Multimedia Club tahun 2017?',
        options: ['Yudis Maulana', 'Alhalim', 'Reka Bayu', 'Rizki Agung'],
        correct: 0,
        explanation: 'Yudis Maulana memimpin Divisi Sinematografi pertama pada tahun 2017.'
    },
    {
        category: '🎬 Sejarah Multimedia Club',
        question: 'Siapakah Ketua Divisi Office pertama kepengurusan Multimedia Club tahun 2017?',
        options: ['Zahwa', 'Denada', 'Bu Gina', 'M. Zidan'],
        correct: 0,
        explanation: 'Zahwa memimpin Divisi Office pertama pada tahun 2017.'
    },
    {
        category: '🎬 Sejarah Multimedia Club',
        question: 'Siapakah Ketua Divisi Editing pertama kepengurusan Multimedia Club tahun 2017?',
        options: ['M. Zidan', 'Yudis Maulana', 'Alhalim', 'Reka Bayu'],
        correct: 0,
        explanation: 'M. Zidan memimpin Divisi Editing pertama pada kepengurusan 2017.'
    },
    {
        category: '🏛️ WNI Satir & Kebijakan',
        question: 'Tindakan tercepat admin medsos kementerian saat ada netizen membongkar data bocor adalah...',
        options: ['Membatasi kolom komentar dan pasang mode privat', 'Mengakui kesalahan secara jantan', 'Memperbaiki celah keamanan dalam 5 menit', 'Membagikan saldo e-wallet ganti rugi'],
        correct: 0,
        explanation: 'Jurus sakti: batasi komentar, tutup akun, dan tunggu isu baru yang viral menggantikannya!'
    },
    {
        category: '🏛️ WNI Satir & Kebijakan',
        question: 'Solusi paling instan pejabat daerah saat banjir tahunan melanda permukiman warga:',
        options: ['Bagi-bagi mie instan sambil foto dokumentasi di depan baliho', 'Membangun kanal pengendali banjir canggih', 'Membersihkan seluruh saluran gorong-gorong', 'Meminta warga bermigrasi ke planet Mars'],
        correct: 0,
        explanation: 'Foto dokumentasi menyerahkan 1 kardus mie instan adalah puncak diplomasi kepedulian.'
    },
    {
        category: '🇮🇩 Tebak-tebakan Receh',
        question: 'Kue apa yang paling gemar berolahraga dan membentuk otot dada?',
        options: ['Kue Bantal (Barbell)', 'Kue Klepon', 'Kue Cucur', 'Kue Lapis Legit'],
        correct: 0,
        explanation: 'Kue Bantal (plesetan Barbell buat angkat beban gym)!'
    },
    {
        category: '⚡ Gen Z & Tren Internet',
        question: 'Ritual wajib Gen Z sebelum menyantap makanan yang baru disajikan di kafe aesthetic:',
        options: ['Foto estetik minimal 20 sudut buat Instastory', 'Langsung makan selagi hangat', 'Menimbang gramasi karbohidrat', 'Membaca doa makan selama 30 menit'],
        correct: 0,
        explanation: '"Kamera makan duluan, perut belakangan" adalah asas fundamental kaum kalcer.'
    },
    {
        category: '💻 Kamera & Komputer',
        question: 'Apa yang terjadi jika kamu me-render video 4K berdurasi 2 jam di laptop kentang RAM 2GB?',
        options: [
            'Laptop bertransformasi menjadi wajan penggorengan telur ceplok',
            'Video selesai dirender dalam waktu 3 detik',
            'Laptop otomatis terhubung ke superkomputer NASA',
            'Kipas laptop mengeluarkan semburan angin es kutub'
        ],
        correct: 0,
        explanation: 'Suhu processor tembus 99°C, kipas meraung seperti mesin jet lepas landas!'
    },

    // --- 8. MAKAN BERGIZI GRATIS (MBG) & KOPERASI DESA ---
    {
        category: '🍽️ Makan Bergizi Gratis (MBG)',
        question: 'Kalau anggaran Makan Bergizi Gratis dipotong habis-habisan sampai Rp 7.500 per porsi, menu apa yang paling realistis tersaji di meja anak sekolah?',
        options: ['Nasi Wagyu A5', 'Nasi putih dengan lauk hikmahnya', 'Telur orak-arik campur harapan palsu', 'Ayam penyet dengan sambal truffle'],
        correct: 2,
        explanation: 'Dengan 7.500 rupiah di tengah inflasi, lauk utamanya adalah karbohidrat ekstrak dan kesabaran yang digoreng garing.'
    },
    {
        category: '🍽️ Makan Bergizi Gratis (MBG)',
        question: 'Apa inovasi paling "mind-blowing" pemerintah untuk mengganti susu sapi impor dalam program MBG?',
        options: ['Susu kecoa', 'Susu ikan (ekstrak protein ikan)', 'Susu beruang beneran', 'Susu nabati dari rumput tetangga'],
        correct: 1,
        explanation: 'Susu ikan jadi wacana agar hemat anggaran, walau netizen sejagat raya bingung membayangkan bagaimana cara memerah susu lele.'
    },
    {
        category: '🍽️ Makan Bergizi Gratis (MBG)',
        question: 'Siapa yang diam-diam paling untung besar dan senyum-senyum sendiri dari proyek katering jutaan porsi MBG?',
        options: ['Anak sekolah yang butuh gizi', 'Ahli gizi independen', 'Vendor katering titipan "Orang Dalam"', 'Petani lokal yang mandiri'],
        correct: 2,
        explanation: 'Di Indonesia, tiap ada proyek pengadaan masif, biasanya selalu ada vendor ajaib yang tiba-tiba menang tender entah dari mana asalnya.'
    },
    {
        category: '🍽️ Makan Bergizi Gratis (MBG)',
        question: 'Apa tantangan logistik paling epik saat harus mengirim Makan Bergizi Gratis ke sekolah di pelosok negeri?',
        options: ['Makanannya keburu jadi fosil karena jalanan hancur', 'Disita guru BP karena dikira bawa bekal terlarang', 'Dimakan beruang di tengah hutan', 'Anak-anaknya milih jajan seblak prasmanan'],
        correct: 0,
        explanation: 'Infrastruktur jalanan pelosok yang rusak parah bikin durasi pengiriman kadang bisa ngalahin waktu fermentasi tape.'
    },
    {
        category: '🍽️ Makan Bergizi Gratis (MBG)',
        question: 'Kenapa nama program ini diubah dari "Makan Siang Gratis" menjadi "Makan Bergizi Gratis"?',
        options: ['Biar anak SD gak makan jam 9 pagi lalu minta porsi lagi jam 12', 'Mengikuti kaidah bahasa Sansekerta Kuno', 'Buat bahan revisi skripsi pejabat', 'Karena gizi lebih penting dari waktu'],
        correct: 0,
        explanation: 'Kalau namanya Makan Siang, nanti anak yang sekolahnya masuk pagi protes kelaparan karena harus nunggu jam 12.'
    },
    {
        category: '🍽️ Makan Bergizi Gratis (MBG)',
        question: 'Kalau susu sapi kemahalan dan susu ikan amis, minuman apa yang paling cocok dengan kearifan lokal buat pendamping MBG?',
        options: ['Matcha Latte Oat Milk', 'Es Teh Manis di plastik segitiga sedotan merah', 'Kopi sachet racikan warkop', 'Tolak Angin cair rasa mint'],
        correct: 1,
        explanation: 'Es teh manis di plastik adalah minuman pamungkas segala umat, gizi itu nomor dua, yang penting manis dan segar!'
    },
    {
        category: '🍽️ Makan Bergizi Gratis (MBG)',
        question: 'Menurut standar kearifan lokal bapak-bapak di Indonesia, apa syarat mutlak agar makanan bisa disebut "Bergizi"?',
        options: ['Harus ada karbohidrat, protein, dan serat', 'Yang penting lauknya digoreng *deep fried*', 'Kalau belum kena nasi putih, ya dihitungnya belum makan', 'Harus ada salmon dari Norwegia'],
        correct: 2,
        explanation: 'Sebanyak apapun protein hewani yang masuk, kalau belum ketemu nasi putih sebakul, sistem pencernaan orang Indonesia meresponnya sebagai "cuma ngemil".'
    },
    {
        category: '🏘️ Koperasi Desa (Kopdes)',
        question: 'Apa fungsi utama Koperasi Unit Desa (KUD) di era modern menurut kacamata warga?',
        options: ['Memajukan ekonomi kerakyatan secara masif', 'Menjadi pajangan papan nama berkarat di samping balai desa', 'Menyaingi dominasi Wall Street', 'Pusat inkubasi startup AI'],
        correct: 1,
        explanation: 'Banyak KUD yang kini cuma tinggal kenangan dan papan nama usang karena digilas kedigdayaan minimarket dan pinjol.'
    },
    {
        category: '🏘️ Koperasi Desa (Kopdes)',
        question: 'Siapa musuh bebuyutan Koperasi Desa yang bikin warga lebih milih minjam uang di tempat mereka?',
        options: ['Bank Dunia (World Bank)', 'Rentenir keliling (Bank Emok) dan Pinjol ilegal', 'IMF', 'Koperasi luar angkasa'],
        correct: 1,
        explanation: 'Pinjol cair 5 menit walau bunga mencekik, sedangkan Koperasi harus isi form pendaftaran, nunggu rapat anggota, dan fotokopi KTP 10 lembar.'
    },
    {
        category: '🏘️ Koperasi Desa (Kopdes)',
        question: 'Apa agenda yang paling dinanti-nanti anggota saat Rapat Anggota Tahunan (RAT) Koperasi Desa?',
        options: ['Evaluasi laporan keuangan dengan teliti', 'Pemilihan pengurus baru yang lebih kompeten', 'Pembagian SHU (Sisa Hasil Usaha) dan snack kotak gratis', 'Membahas strategi geopolitik desa'],
        correct: 2,
        explanation: 'SHU dan lemper gratis di dalam snack kotak adalah alasan satu-satunya kenapa anggota rela datang dan duduk rapat selama 3 jam.'
    },
    {
        category: '🏘️ Koperasi Desa (Kopdes)',
        question: 'Siapa yang biasanya menjabat sebagai Ketua Koperasi Desa sejak zaman baheula hingga sekarang?',
        options: ['Pemuda desa yang visioner', 'Mantan Pak Kades atau tokoh sepuh desa yang itu-itu aja', 'Lulusan S2 Ekonomi Harvard', 'Influencer TikTok jalur joget'],
        correct: 1,
        explanation: 'Regenerasi Koperasi Desa kadang mandek karena posisi ketua atau pengurus adalah "jatah VIP" para sesepuh desa yang enggan pensiun.'
    },
    {
        category: '🏘️ Koperasi Desa (Kopdes)',
        question: 'Barang apa yang selalu tiba-tiba gaib dan *sold out* di Koperasi Desa saat musim tanam tiba?',
        options: ['Pupuk bersubsidi', 'Tiket konser Coldplay', 'Skin care anti-aging', 'Saham Tesla'],
        correct: 0,
        explanation: 'Pupuk subsidi selalu jadi barang gaib di desa. Giliran lagi butuh barangnya hilang, giliran panen harganya anjlok.'
    },
    {
        category: '🏘️ Koperasi Desa (Kopdes)',
        question: 'Cara paling mutakhir Koperasi Desa untuk bertahan hidup dan bersaing dengan minimarket biru dan merah di kecamatan adalah?',
        options: ['Bikin promo beli traktor gratis cangkul', 'Jual LPG 3kg dengan embel-embel "Orang Kaya Dilarang Beli"', 'Menyerah dan menyewakan terasnya buat gerobak seblak atau gorengan', 'Bikin aplikasi Web3'],
        correct: 2,
        explanation: 'Ujung-ujungnya, teras depan gedung Koperasi jauh lebih produktif dan menghasilkan cuan saat disewakan ke tukang gorengan daripada koperasinya sendiri.'
    },

    // --- 9. FAKTA UNIK & PAHIT INDONESIA ---
    {
        category: '🇮🇩 Fakta Unik & Pahit Indonesia',
        question: 'Apa *trigger* paling ampuh yang bisa membuat jalan rusak bertahun-tahun di suatu daerah tiba-tiba mulus dalam semalam?',
        options: ['Warga patuh bayar pajak tepat waktu', 'Protes warga lewat petisi online', 'Presiden RI dijadwalkan lewat daerah tersebut besok pagi', 'Berdoa dan puasa mutih 7 hari'],
        correct: 2,
        explanation: '"Sindrom Sangkuriang" infrastruktur lokal: jalanan hancur bertahun-tahun bisa langsung diaspal mulus H-1 begitu dengar kabar RI 1 mau kunjungan kerja.'
    },
    {
        category: '🇮🇩 Fakta Unik & Pahit Indonesia',
        question: 'Predikat apa yang disandang netizen Indonesia menurut laporan Microsoft tahun 2021, yang langsung dibalas dengan amukan brutal?',
        options: ['Netizen paling ramah se-Asia Tenggara', 'Netizen paling tidak sopan se-Asia Tenggara', 'Paling suka ngasih tip ke ojol', 'Netizen dengan literasi membaca tertinggi'],
        correct: 1,
        explanation: 'Dinobatkan sebagai paling tidak sopan, netizen Indonesia bukannya instrospeksi, malah merundung akun Microsoft berjamaah sampai kolom komentarnya harus dikunci. Ironi tingkat dewa.'
    },
    {
        category: '🇮🇩 Fakta Unik & Pahit Indonesia',
        question: 'Melihat seringnya hacker (seperti Bjorka) membocorkan data negara, apa fungsi sebenarnya dari KTP, KK, dan NPWP warga Indonesia?',
        options: ['Untuk meningkatkan keamanan nasional', 'Supaya kita ingat tanggal lahir kalau lupa', 'Sebagai data *open source* alias bisa di-download gratis oleh warga dunia', 'Sebagai syarat pendaftaran beasiswa ke Mars'],
        correct: 2,
        explanation: 'Di Indonesia, privasi itu ilusi. Data kependudukan kita rasanya sudah seperti pamflet sedot WC, tersebar bebas di mana-mana dan bisa diakses siapa saja.'
    },
    {
        category: '🇮🇩 Fakta Unik & Pahit Indonesia',
        question: 'Menurut budaya tak tertulis, jika diundang ke sebuah acara jam 09.00 pagi, maka tamu yang ideal (dan normal) akan tiba pada jam...',
        options: ['08.45 pagi untuk *networking*', '09.00 tepat waktu layaknya orang Jepang', '10.30 dengan alasan "macet" padahal jam 9 baru bangun tidur', '07.00 pagi buat bantuin panitia nyapu tenda'],
        correct: 2,
        explanation: 'Jam Karet adalah warisan budaya tak benda. Datang tepat waktu malah berisiko disuruh panitia bantuin nyusun kursi lipat karena acaranya belum siap.'
    },
    {
        category: '🇮🇩 Fakta Unik & Pahit Indonesia',
        question: 'Apa syarat kualifikasi paling absolut dan tak terkalahkan saat melamar pekerjaan di banyak institusi di Indonesia?',
        options: ['Sertifikat TOEFL di atas 600', 'Portofolio berstandar internasional yang menembus NASA', 'Jalur "Orang Dalam" alias keponakan petinggi kantor', 'Menguasai 5 bahasa termasuk bahasa kalbu'],
        correct: 2,
        explanation: 'Skill, IPK 4.0, dan pengalaman internasional akan selalu kalah telak dengan satu kalimat sakti: "Dia ponakannya bapak Direktur".'
    },
    {
        category: '🇮🇩 Fakta Unik & Pahit Indonesia',
        question: 'Penyakit misterius sejuta umat di Indonesia yang ajaibnya bisa disembuhkan cukup dengan koin Rp 500, balsem, dan gesekan brutal adalah...',
        options: ['Masuk Angin', 'Radang Otak', 'Defisit APBN', 'Asam Lambung tingkat akhir'],
        correct: 0,
        explanation: 'Masuk angin adalah penyakit yang tidak diakui di buku medis barat tapi diyakini 270 juta warga kita. Semakin merah dan seram hasil kerokannya, semakin bangga si pasien.'
    },
    {
        category: '🇮🇩 Fakta Unik & Pahit Indonesia',
        question: 'Bagi mayoritas pengendara motor di Indonesia, apa makna terselubung dari rambu "Dilarang Putar Balik" yang dicoret?',
        options: ['Jangan putar balik nanti ditilang dan membahayakan', 'Silakan putar balik asalkan tidak ada Polisi yang jaga', 'Berhenti sejenak untuk meratapi masa lalu', 'Putar balik hanya berlaku untuk mobil, motor bebas'],
        correct: 1,
        explanation: 'Di jalanan Indonesia, sebagian besar rambu lalu lintas dianggap sebagai "saran/himbauan ramah" alih-alih aturan mutlak—selama tidak ada penegak hukum di radius 1 kilometer.'
    },

    // --- 10. KARHUTLA: FAKTA, MITOS & ASAP ---
    {
        category: '🔥 Karhutla: Fakta, Mitos & Asap',
        question: 'Menurut klarifikasi resmi dari korporasi besar, apa penyebab utama puluhan ribu hektar lahan mereka terbakar secara misterius?',
        options: ['Sengaja dibakar biar hemat biaya *clearing* lahan', 'Gesekan ranting kering yang memicu percikan api secara alami', 'Disembur naga Indosiar', 'Invasi alien dari planet Mars'],
        correct: 1,
        explanation: 'Mitos konyol terfavorit: "Gesekan ranting kering". Padahal secara logika, ranting bergesek butuh kecepatan setara mobil F1 untuk bisa keluar api, tapi ya namanya juga alasan.'
    },
    {
        category: '🔥 Karhutla: Fakta, Mitos & Asap',
        question: 'Selain batu bara dan nikel, komoditas "ekspor" tahunan apa yang rutin dikirim Indonesia ke Singapura dan Malaysia secara gratis setiap musim kemarau?',
        options: ['Budaya Pop', 'Film Horor', 'Kabut Asap berkualitas Premium', 'Tenaga Kerja IT'],
        correct: 2,
        explanation: 'Faktanya, kabut asap adalah "ekspor tak kasat mata" yang sukses bikin tetangga sebelah rajin ngirim nota protes diplomatik tiap tahun.'
    },
    {
        category: '🔥 Karhutla: Fakta, Mitos & Asap',
        question: 'Apa keajaiban alam yang biasanya terjadi beberapa bulan setelah sebuah hutan diklaim "terbakar tanpa sengaja"?',
        options: ['Hutannya tumbuh kembali menjadi hutan lindung', 'Muncul spesies dinosaurus baru', 'Tiba-tiba berubah wujud jadi perkebunan kelapa sawit yang rapi dan estetik', 'Tanahnya jadi tambang emas'],
        correct: 2,
        explanation: 'Fakta pahit: Seringkali lahan yang katanya "tidak sengaja" terbakar itu, tahu-tahu sudah ditanami bibit sawit yang berbaris rapi layaknya tentara upacara.'
    },
    {
        category: '🔥 Karhutla: Fakta, Mitos & Asap',
        question: 'Ketika indeks kualitas udara (ISPU) di daerah terdampak karhutla menunjukkan status "Berbahaya" dengan warna hitam pekat, apa yang biasanya dilakukan warga +62?',
        options: ['Mengurung diri di ruang hampa udara', 'Pindah negara', 'Memakai *hazmat suit* layaknya astronot', 'Tetap *Sunmori* dan sepedaan santai di hari Minggu sambil batuk-batuk'],
        correct: 3,
        explanation: 'Faktanya, paru-paru warga Indonesia sudah berevolusi. Udara beracun pun tetap diterjang demi validasi instastory olahraga di hari libur.'
    },
    {
        category: '🔥 Karhutla: Fakta, Mitos & Asap',
        question: 'Siapa tokoh *superhero* lokal dengan kearifan budaya yang sering diam-diam diharapkan turun tangan saat helikopter *water bombing* sudah kewalahan?',
        options: ['Gundala Putra Petir', 'Pawang Hujan VIP berbekal mangkok emas', 'Ultraman Taro', 'Avengers cabang Depok'],
        correct: 1,
        explanation: 'Di saat teknologi canggih dan anggaran triliunan tak sanggup memadamkan api, klenik dan *magic* dari pawang hujan adalah *plan B* andalan bangsa kita.'
    },
    {
        category: '🔥 Karhutla: Fakta, Mitos & Asap',
        question: 'Kalau ada 10.000 hektar lahan perusahaan besar yang terbakar, siapa yang paling sering berakhir memakai baju tahanan oranye di TV?',
        options: ['CEO Perusahaannya', 'Pemegang Saham Mayoritas dari luar negeri', 'Mang Udin, petani lokal yang apes dituduh buang puntung rokok sembarangan', 'Pohonnya sendiri karena gagal melindungi diri'],
        correct: 2,
        explanation: 'Hukum seringkali tumpul ke atas dan tajam ke Mang Udin. Ajaibnya, puntung rokok bisa membakar lahan seluas satu provinsi secara merata.'
    },
    {
        category: '🔥 Karhutla: Fakta, Mitos & Asap',
        question: 'Strategi pemadaman Karhutla paling pamungkas dan paling ditunggu-tunggu oleh pemerintah daerah adalah...',
        options: ['Mengerahkan robot pemadam AI', 'Meminta negara tetangga meniup asapnya balik', 'Berdoa, pasrah, dan menunggu datangnya musim hujan bulan November', 'Menyiram pakai galon air mineral'],
        correct: 2,
        explanation: 'Sebanyak apapun anggaran pemadaman darat dan udara, solusi final dan paling efektif mengatasi karhutla di Indonesia adalah: *nunggu musim hujan tiba*.'
    },
    {
        category: '🔥 Karhutla: Fakta, Mitos & Asap',
        question: 'Apa argumen "balasan" paling legendaris (dan nyeleneh) dari pejabat kita saat dikomplain negara tetangga soal kiriman kabut asap?',
        options: ['"Kami akan bayar ganti rugi 100%"', '"Terima kasih kembali untuk 11 bulan udara segar yang tidak pernah kalian syukuri!"', '"Silakan bawa pulang asapnya kalau tidak suka"', '"Itu bukan asap, itu dry ice buat konser"'],
        correct: 1,
        explanation: 'Fakta kocak namun nyata, pernah ada pejabat tinggi yang membalas komplain tetangga dengan menyuruh mereka bersyukur atas 11 bulan oksigen gratis dari hutan kita.'
    },
    {
        category: '🔥 Karhutla: Fakta, Mitos & Asap',
        question: 'Mitos: Pembukaan lahan tanpa bakar (PLTB) itu mustahil dan mahal. Fakta yang sebenarnya adalah?',
        options: ['Bisa pakai alat berat, tapi perusahaan lebih suka metode "modal korek api Rp 2.000" biar cuan maksimal', 'Memang butuh sihir klan Uchiha', 'Bumi akan menolak jika tidak dibakar', 'Alat beratnya alergi tanah gambut'],
        correct: 0,
        explanation: 'PLTB itu sangat bisa dan ramah lingkungan, tapi bagi oknum perusahaan pelit, membakar lahan adalah jalan pintas diskon 99% dari biaya sewa ekskavator.'
    },
    {
        category: '🔥 Karhutla: Fakta, Mitos & Asap',
        question: 'Saat pemerintah membagikan masker untuk warga terdampak asap pekat, masker jenis apa yang seringkali secara salah kaprah dibagikan padahal tidak mempan menahan partikel asap (PM 2.5)?',
        options: ['Masker gas air mata standar militer', 'Masker Scuba tipis gambar bibir senyum atau masker bedah biasa', 'Masker N95 dengan filter karbon', 'Topeng Las'],
        correct: 1,
        explanation: 'Faktanya, masker scuba dan masker bedah biasa itu tidak bisa menyaring partikel mematikan Karhutla (PM 2.5), tapi tetap dibagikan karena murah dan... yang penting kelihatan kerja.'
    },

    // --- 11. KONSPIRASI VIRAL & ABSURD GLOBAL ---
    {
        category: '👽 Konspirasi Viral & Absurd',
        question: 'Menurut penganut Bumi Datar (Flat Earth), apa alasan paling "logis" kenapa air laut tidak tumpah ke luar angkasa?',
        options: ['Karena ada gravitasi magis dari bawah', 'Bumi dikelilingi tembok es Antartika yang dijaga tentara elit rahasia', 'Ujung bumi disumbat pakai lakban raksasa', 'Airnya emang pinter dan gak mau tumpah'],
        correct: 1,
        explanation: 'Mereka percaya Antartika bukanlah benua, melainkan "tembok es" raksasa yang menahan air laut, dan dijaga ketat oleh tentara elit PBB biar orang gak jatuh ke pinggir bumi.'
    },
    {
        category: '👽 Konspirasi Viral & Absurd',
        question: 'Dalam teori konspirasi Gen Z "Birds Aren\'t Real", apa alasan sebenarnya burung suka bertengger berjejer di kabel listrik?',
        options: ['Lomba paduan suara nyanyi pagi', 'Ngecas baterai, karena burung sebenarnya adalah drone mata-mata pemerintah', 'Ngehindarin kucing garong', 'Menghangatkan kaki yang kedinginan'],
        correct: 1,
        explanation: 'Gerakan satir ini mengklaim pemerintah AS sudah mengganti semua burung dengan drone pengintai, dan kabel listrik adalah colokan *charger* massal mereka.'
    },
    {
        category: '👽 Konspirasi Viral & Absurd',
        question: 'Saat pandemi, beredar pesan berantai di WhatsApp bahwa vaksin Covid-19 diam-diam disusupi *microchip* 5G. Jika ini benar, apa untungnya buat warga?',
        options: ['Bisa kebal dari segala jenis santet', 'Bisa internetan kencang 5G tanpa perlu bayar kuota bulanan', 'Bisa telepati langsung sama Bill Gates', 'Otomatis jago bahasa alien'],
        correct: 1,
        explanation: 'Kalau emang beneran disuntik chip 5G gratis, Provider telekomunikasi pasti udah demo besar-besaran karena warga nggak perlu beli paket data lagi.'
    },
    {
        category: '👽 Konspirasi Viral & Absurd',
        question: 'Konspirasi pop-culture paling legendaris menyebutkan bahwa penyanyi Avril Lavigne yang asli sudah meninggal tahun 2003 dan digantikan oleh...',
        options: ['Hologram canggih buatan Sony Music', 'Kembaran kloningannya yang bernama Melissa', 'Robot AI dengan *voice changer*', 'Agnez Mo yang lagi cosplay'],
        correct: 1,
        explanation: 'Cocoklogi netizen membandingkan letak tahi lalat, bentuk hidung, dan gaya *fashion* Avril dulu vs sekarang, menyimpulkan dia diganti oleh aktris bernama Melissa.'
    },
    {
        category: '👽 Konspirasi Viral & Absurd',
        question: 'Kenapa penganut konspirasi elit global sangat yakin kalau Mark Zuckerberg (CEO Meta) itu sebenarnya "Manusia Reptil" (Reptilian Elite)?',
        options: ['Karena Facebook warna biru kayak darah reptil', 'Cara dia minum air saat sidang kongres sangat kaku dan matanya jarang berkedip', 'Karena dia suka makan nyamuk dan lalat', 'Karena dia diam-diam suka berjemur di atas batu'],
        correct: 1,
        explanation: 'Ekspresi Zuckerberg yang super kaku bak robot/kadal tanpa emosi saat disidang kongres AS bikin netizen yakin dia lagi pakai kostum manusia.'
    },
    {
        category: '👽 Konspirasi Viral & Absurd',
        question: 'Apa "bukti terkuat" (menurut penganut konspirasi) bahwa pendaratan manusia di Bulan (Apollo 11) hanyalah syuting film di studio Hollywood?',
        options: ['Neil Armstrong pakai sepatu merk Adidas', 'Benderanya terlihat berkibar padahal di bulan tidak ada angin', 'Ada suara tukang bakso lewat di rekaman aslinya', 'Bulan kelihatan terbuat dari keju'],
        correct: 1,
        explanation: 'Bendera berkibar jadi senjata utama kaum konspirasi, padahal NASA sudah menjelaskan bendera itu dipasangi kawat horizontal agar tetap membentang, bukan karena ditiup angin studio.'
    },
    {
        category: '👽 Konspirasi Viral & Absurd',
        question: 'Menurut teori "Chemtrails", jejak asap putih panjang yang ditinggalkan pesawat terbang di langit sebenarnya adalah...',
        options: ['Knalpot pesawat yang belum lolos uji emisi', 'Bahan kimia/virus yang sengaja disebar untuk melemahkan populasi', 'Seni melukis awan (*skywriting*) gratisan', 'Obat nyamuk semprot skala global'],
        correct: 1,
        explanation: 'Jejak kondensasi (kondensasi uap air biasa dari mesin) dipercaya sebagai racun biologis pembodohan massal racikan elit global.'
    },
    {
        category: '👽 Konspirasi Viral & Absurd',
        question: 'Di Indonesia, makhluk halus spesialis pencuri uang adalah Tuyul. Lalu, kenapa Tuyul tidak pernah merampok uang di mesin ATM atau nge-hack M-Banking?',
        options: ['Karena Tuyul gaptek, belum melek digitalisasi, dan gak punya sidik jari', 'Karena di setiap mesin ATM ada penunggu jin berseragam', 'Karena AC di ruang ATM terlalu dingin buat Tuyul yang cuma pakai popok', 'Karena Tuyul gak tahu nomor PIN'],
        correct: 0,
        explanation: 'Ilmu pesugihan Nusantara rupanya belum *update patch* ke Web3. Tuyul masih manual nyari uang selembar demi selembar, sangat tidak efisien di era *cashless*.'
    },
    {
        category: '👽 Konspirasi Viral & Absurd',
        question: 'Pada 2019, jutaan orang di internet membuat event konyol bersiap menyerbu pangkalan rahasia "Area 51" sambil lari ala Naruto. Apa tujuan utama mereka?',
        options: ['Minta sumbangan untuk bangun desa', 'Membebaskan dan membawa pulang alien untuk dipelihara di kosan', 'Numpang numpang cari sinyal WiFi extraterrestrial', 'Demo menolak kenaikan harga skincare'],
        correct: 1,
        explanation: 'Meme "Storm Area 51, They Can\'t Stop All of Us" viral parah, tujuannya cuma satu: mau lihat alien (dan kabur bawa aliennya) pakai lari gaya ninja biar peluru tentara meleset.'
    },
    {
        category: '👽 Konspirasi Viral & Absurd',
        question: 'Situs Megalitikum Gunung Padang di Cianjur sering dikait-kaitkan dengan teori konspirasi "Sains Alternatif" luar biasa, yaitu bahwa di bawahnya terdapat...',
        options: ['Markas Power Rangers', 'Piramida tertua di dunia yang menyimpan reaktor nuklir kuno peradaban Atlantis', 'Harta karun peninggalan VOC yang masih utuh', 'Fosil Godzilla'],
        correct: 1,
        explanation: 'Gunung Padang dipercaya sebagian kelompok sebagai sisa peradaban super maju Atlantis yang udah kenal energi nuklir, jauh sebelum firaun main pasir bangun piramida.'
    },
    {
        category: '👽 Konspirasi Viral & Absurd',
        question: 'Konspirasi bernuansa religi-lokal yang sering muncul di grup WA keluarga adalah klaim mencengangkan bahwa Candi Borobudur sebenarnya...',
        options: ['Peninggalan Nabi Sulaiman yang dibangun oleh pasukan jin', 'Landasan pacu piring terbang (UFO)', 'Cetak biru (*blueprint*) dari kapal Titanic', 'Dibangun dalam semalam oleh Bandung Bondowoso'],
        correct: 0,
        explanation: 'Cocoklogi linguistik "Borobudur" dengan nama-nama nabi dan klaim reliefnya menggambarkan kisah Nabi Sulaiman sempat bikin ilmuwan sejarah geleng-geleng kepala.'
    },
    {
        category: '👽 Konspirasi Viral & Absurd',
        question: 'Apa konspirasi lokal yang paling sering dipakai emak-emak untuk menakuti anak yang suka main maghrib-maghrib di daerah Segitiga Bermuda versi kearifan lokal?',
        options: ['Nanti diculik piring terbang', 'Nanti dimakan siluman buaya putih atau Wewe Gombel', 'Nanti tersesat ke markas Dajjal', 'Nanti disuruh ngerjain PR Matematika sama setan'],
        correct: 1,
        explanation: 'Segitiga Bermuda punya alien, Indonesia punya Wewe Gombel. Sebuah kearifan lokal yang terbukti 100% efektif bikin bocah lari masuk rumah saat adzan Maghrib.'
    },
    {
        category: '👽 Konspirasi Viral & Absurd',
        question: 'Fasilitas HAARP milik AS di Alaska yang aslinya untuk riset atmosfer, sangat sering dituduh oleh kaum konspirasi sebagai alat canggih untuk...',
        options: ['Memancarkan siaran radio dangdut ke seluruh dunia', 'Memanggil alien dari Galaksi Bima Sakti', 'Remote control pembuat gempa bumi dan pengendali cuaca ekstrim', 'Mesin waktu untuk balik ke masa lalu'],
        correct: 2,
        explanation: 'Tiap kali ada gempa bumi besar atau badai aneh di belahan dunia mana pun, *elite global* dan mesin antena HAARP selalu jadi *kambing hitam* di YouTube.'
    },
    {
        category: '👽 Konspirasi Viral & Absurd',
        question: 'Uang pecahan Rupiah kertas berapakah yang sempat bikin geger nasional karena dituduh netizen menyelipkan lambang Iluminati (Segitiga Mata Satu / Dajjal)?',
        options: ['Rp 100.000', 'Rp 50.000', 'Rp 10.000 (edisi lama)', 'Rp 2.000 (lecek)'],
        correct: 2,
        explanation: 'Uang Rp 10.000 keluaran lama kalau diterawang bagian logo BI-nya (rectoverso) dianggap membentuk segitiga mata satu. Fix, Dajjal mainnya recehan.'
    },
    {
        category: '👽 Konspirasi Viral & Absurd',
        question: 'Penganut teori "Hollow Earth" (Bumi Berongga) tidak percaya kalau isi perut bumi itu magma panas. Mereka percaya di dalam bumi ada...',
        options: ['Dunia bawah tanah "Agartha" yang dihuni alien raksasa dan peradaban super canggih', 'Buronan pinjol yang kabur dari *debt collector*', 'Gudang penyimpanan vaksin elit global', 'Tempat *camping* dinosaurus'],
        correct: 0,
        explanation: 'Menurut mereka, bumi itu kopong dan punya matahari kecil di dalamnya. Pintu masuknya ada di Kutub Utara. Godzilla vs Kong sangat terinspirasi dari konspirasi ini.'
    },
    {
        category: '👽 Konspirasi Viral & Absurd',
        question: 'Seorang ilmuwan Brazil (Arysio Santos) pernah bikin orang Indonesia bangga mendadak dengan buku konspirasinya yang menyebut bahwa benua legendaris Atlantis yang tenggelam adalah...',
        options: ['Pulau Bali', 'Kepulauan Indonesia (Paparan Sunda)', 'Bawah Danau Toba', 'Pantai Selatan Jawa'],
        correct: 1,
        explanation: 'Ia mengklaim kondisi geografis, vulkanologi, dan kekayaan alam Paparan Sunda (Indonesia) zaman es sangat cocok dengan ciri-ciri Atlantis karangan Plato.'
    },
    {
        category: '👽 Konspirasi Viral & Absurd',
        question: 'Proyek pelepasan nyamuk Wolbachia di Indonesia sempat ditolak mentah-mentah karena dituduh sebagai konspirasi Bill Gates untuk...',
        options: ['Menyebar virus genetik buat depopulasi manusia', 'Mengganti suara nyamuk jadi lagu dangdut', 'Bikin nyamuknya ada kamera pengintai', 'Biar nyamuknya jago nyedot kuota internet'],
        correct: 0,
        explanation: 'Padahal nyamuk ber-Wolbachia itu buat menekan kasus Demam Berdarah (DBD), tapi gara-gara ada nama yayasan Bill Gates sebagai sponsor pendanaan, langsung di-cap *biological weapon*.'
    },
    {
        category: '👽 Konspirasi Viral & Absurd',
        question: 'Menurut teori konspirasi selebritis yang menolak *move on*, Michael Jackson (Raja Pop) sebenarnya memalsukan kematiannya karena...',
        options: ['Capek jadi artis dan pengen fokus buka usaha angkringan', 'Dia alien yang masa tugasnya di bumi udah habis', 'Ingin hidup damai tanpa hutang di sebuah pulau terpencil', 'Kalah main judi slot'],
        correct: 2,
        explanation: 'Para *die-hard fans* selalu mencocokkan kemiripan wajah orang asing di internet atau tamu di pemakamannya, meyakini MJ cuma pura-pura mati buat kabur dari kebangkrutan dan media.'
    },
    {
        category: '👽 Konspirasi Viral & Absurd',
        question: 'Konspirasi "Hollow Moon" atau Bulan Kopong meyakini bahwa satelit alami Bumi (Bulan) sebenarnya adalah...',
        options: ['Stasiun ruang angkasa raksasa buatan alien (Death Star)', 'Potongan keju purba', 'Pantulan lampu senter dari proyeksi bumi', 'Balon udara super besar milik NASA'],
        correct: 0,
        explanation: 'Teori ini bilang bulan sengaja di-"parkir" oleh alien di orbit bumi buat ngawasin manusia. Buktinya? Bekas kawah meteor di bulan kedalamannya diklaim tidak proporsional.'
    },
    {
        category: '👽 Konspirasi Viral & Absurd',
        question: 'Selain *chip* 5G, konspirasi medis paling tak masuk akal di grup WA bapak-bapak soal vaksin adalah klaim bahwa bekas suntikan di lengan dapat...',
        options: ['Mengubah golongan darah jadi O semua', 'Menarik sendok besi, garpu, koin, layaknya kekuatan Magneto', 'Membuat orang yang disuntik bisa bersin mengeluarkan api', 'Bercahaya dalam gelap (Glow in the dark)'],
        correct: 1,
        explanation: 'Banyak bapak-bapak dan emak-emak yang menempelkan koin Rp 500 dan sendok ke lengannya karena percaya vaksin mengandung magnet chip cair. Padahal itu cuma lengket kena keringat.'
    },

    // --- 12. KONSPIRASI & MITOS LOKAL NUSANTARA ---
    {
        category: '🇮🇩 Konspirasi & Mitos Lokal',
        question: 'Konspirasi finansial paling abadi di Indonesia: Di manakah konon "Harta Amanah" milik Presiden Soekarno yang jumlahnya diklaim bisa melunasi seluruh utang negara disimpan?',
        options: ['Di dalam ruang rahasia Monas', 'Di Bank Swiss dalam bentuk batangan emas tak terbatas', 'Di brankas Koperasi Desa', 'Di bawah kasur keramat'],
        correct: 1,
        explanation: 'Menurut konspirasi ini, Soekarno punya emas berton-ton di Bank Swiss. Sayangnya, sampai sekarang utang negara tetap naik dan emasnya gak pernah cair buat bayar BPJS.'
    },
    {
        category: '🇮🇩 Konspirasi & Mitos Lokal',
        question: 'Bagaimana sistem keamanan tingkat tinggi dari operasi peretasan finansial berbasis mistis bernama "Babi Ngepet"?',
        options: ['Menggunakan enkripsi ujung-ke-ujung (End-to-End)', 'Harus ada istri atau kerabat yang menjaga lilin agar tidak mati', 'Memakai password alfanumerik 12 karakter', 'Wajib menggunakan VPN Premium'],
        correct: 1,
        explanation: 'Teknologi pesugihan Nusantara sangat bergantung pada lilin. Kalau apinya goyang, artinya si babi ketahuan warga. Kalau lilinnya mati, babi kembali jadi manusia.'
    },
    {
        category: '🇮🇩 Konspirasi & Mitos Lokal',
        question: 'Menurut klaim bersejarah dari mendiang Lord Rangga (Petinggi Sunda Empire), sistem tatanan dunia dan PBB (Perserikatan Bangsa-Bangsa) aslinya bermula dari kota mana?',
        options: ['New York', 'Den Haag', 'Bandung', 'Atlantis'],
        correct: 2,
        explanation: 'Klaim paling gokil dari Sunda Empire adalah bahwa NATO dan PBB lahir di Bandung, dan seluruh dunia harus tunduk pada kekaisaran Sunda.'
    },
    {
        category: '🇮🇩 Konspirasi & Mitos Lokal',
        question: 'Apa rumor hoaks paling legendaris dari grup WhatsApp emak-emak tentang beras impor yang bikin ibu-ibu se-Nusantara mendadak jadi peneliti dadakan?',
        options: ['Berasnya mengandung microchip 5G', 'Itu sebenarnya adalah Beras Plastik yang kalau dimasak jadi karet', 'Berasnya berasal dari planet Namek', 'Berasnya kalau dimakan bikin otomatis jago bahasa Mandarin'],
        correct: 1,
        explanation: 'Hoaks "beras plastik" bikin emak-emak se-Indonesia ramai-ramai mengepal nasi dan melemparnya ke meja dapur untuk ngetes apakah nasinya memantul layaknya bola bekel.'
    },
    {
        category: '🇮🇩 Konspirasi & Mitos Lokal',
        question: 'Urban legend paling ditakuti generasi 90-an di toilet sekolah dasar: Siapa sosok misterius yang konon akan muncul jika kita mengetuk pintu toilet 3 kali?',
        options: ['Kepala Sekolah yang marah', 'Ronald McDonald', 'Mister Gepeng', 'Sales asuransi'],
        correct: 2,
        explanation: 'Mister Gepeng adalah konspirasi horor SD yang menyebutkan ada pria terjepit lift/buldozer hingga gepeng dan menghantui toilet. Tidak ada yang tahu kenapa hantu Gepeng nongkrongnya di toilet sekolah.'
    },
    {
        category: '🇮🇩 Konspirasi & Mitos Lokal',
        question: 'Menurut Mitos Pantai Selatan, apa alasan sebenarnya pengunjung dilarang memakai baju berwarna hijau saat berenang?',
        options: ['Biar warna bajunya tidak bentrok dengan warna air laut', 'Nanti dikira lumut oleh ikan hiu', 'Nanti diculik Nyi Roro Kidul untuk dijadikan prajurit kerajaan gaibnya', 'Karena warna hijau kurang *aesthetic* untuk di-post di Instagram'],
        correct: 2,
        explanation: 'Mitosnya, hijau adalah warna kesukaan Nyi Roro Kidul. Secara logis, baju hijau menyatu dengan warna air laut, bikin tim SAR susah nyari kalau kamu keseret ombak.'
    },
    {
        category: '🇮🇩 Konspirasi & Mitos Lokal',
        question: 'Kenapa uang koin pecahan Rp 1.000 bergambar Kelapa Sawit keluaran tahun 90-an sempat dijual seharga puluhan juta rupiah di toko online?',
        options: ['Karena dipercaya kandungan cincin kuningnya adalah Emas Murni 24 Karat', 'Karena bisa dipakai buat pesugihan', 'Karena kalau ditanam bisa tumbuh pohon sawit', 'Karena itu satu-satunya koin yang tahan banting'],
        correct: 0,
        explanation: 'Konspirasi ini bikin bapak-bapak ngumpulin koin sawit dengan harapan bisa tajir mendadak, padahal Bank Indonesia udah klarifikasi itu cuma campuran tembaga dan nikel, bukan emas.'
    },
    {
        category: '🇮🇩 Konspirasi & Mitos Lokal',
        question: 'Apa metode *upgrade* fisik paling ekstrem dalam mitologi gaib Nusantara untuk mengubah sesosok Kuntilanak menjadi perempuan cantik?',
        options: ['Mendaftarkan dia ke klinik kecantikan di Korea', 'Menancapkan paku berkarat tepat di ubun-ubunnya', 'Memberikan dia *skincare* organik', 'Menyajikannya dengan kopi senja dan lagu *indie*'],
        correct: 1,
        explanation: 'Menurut mitos, paku di ubun-ubun adalah *cheat code* untuk me-reset Kuntilanak jadi manusia biasa. Jangan tanya gimana cara nancapin pakunya pas dia lagi ketawa.'
    },
    {
        category: '🇮🇩 Konspirasi & Mitos Lokal',
        question: 'Pada tahun 2009, pengobatan alternatif paling viral sejagat Indonesia berpusat pada sebuah fenomena air celup yang dikenal dengan nama...',
        options: ['Air zam-zam campuran madu', 'Air doa dari gunung', 'Air rendaman Batu Ajaib Ponari (Ponari Sweat)', 'Air cucian pusaka keraton'],
        correct: 2,
        explanation: 'Ponari, bocah dari Jombang, konon menemukan batu petir yang bisa menyembuhkan segala penyakit cukup dengan dicelupkan ke air bawaan pasien. Omzetnya ngalahin klinik spesialis.'
    },
    {
        category: '🇮🇩 Konspirasi & Mitos Lokal',
        question: 'Makhluk gaib bernama "Kolor Ijo" sempat memicu kepanikan massal di awal tahun 2000-an. Apa sebenarnya motif operasional dari Kolor Ijo?',
        options: ['Menjadi *brand ambassador* celana dalam lokal', 'Melakukan teror pesugihan/ilmu hitam dengan mencuri pakaian dalam wanita', 'Memberantas kejahatan di malam hari', 'Menagih tunggakan pinjol'],
        correct: 1,
        explanation: 'Kepanikan ini begitu masif sampai warga di berbagai daerah ramai-ramai menaruh bambu kuning dan daun kelor di pintu rumah untuk menangkal maling sakti bervisual hijau ini.'
    },
    {
        category: '🇮🇩 Konspirasi & Mitos Lokal',
        question: 'Teori konspirasi sejarah: Kenapa naskah asli Supersemar (Surat Perintah Sebelas Maret) tidak pernah ditemukan wujud aslinya sampai detik ini?',
        options: ['Kertasnya tidak sengaja dipakai bungkus gorengan', 'Konon isinya "berbeda" dengan narasi sejarah yang diajarkan di sekolah, jadi sengaja dihilangkan', 'Disimpan di museum luar angkasa', 'Masih nyelip di sela-sela sofa Istana Negara'],
        correct: 1,
        explanation: 'Hilangnya naskah asli Supersemar jadi misteri terbesar sejarah Orde Baru. Banyak yang percaya teks aslinya bukan perintah penyerahan kekuasaan dari Soekarno ke Soeharto.'
    },
    {
        category: '🇮🇩 Konspirasi & Mitos Lokal',
        question: 'Mitos Jalan Tol: Apa ritual "wajib" (dan tidak masuk akal) yang sering dilakukan sopir saat melintasi Tol Cipularang KM 97 atau jembatan angker lainnya?',
        options: ['Membunyikan klakson 3 kali dan buang uang receh untuk "permisi" ke penunggu gaib', 'Turun dari mobil dan joget TikTok', 'Mematikan lampu mobil agar hantu tidak silau', 'Menyetel lagu dangdut pantura dengan volume maksimal'],
        correct: 0,
        explanation: 'Klakson 3 kali dan lempar koin adalah bentuk "tol gaib" kearifan lokal. Kalau kecelakaan, yang disalahkan pasti Gunung Hejo atau siluman penunggu KM 97.'
    },
    {
        category: '🇮🇩 Konspirasi & Mitos Lokal',
        question: 'Konspirasi medis jalanan: Bagaimana cara paling "akurat" versi netizen untuk mengetes apakah semangkuk Bakso urat di pinggir jalan mengandung Boraks/Bleng?',
        options: ['Diuji di laboratorium forensik BPOM', 'Dilempar keras-keras ke lantai atau tembok, kalau memantul tinggi berarti positif boraks', 'Dilihat dari bentuk uratnya yang menyerupai wajah alien', 'Ditanyakan langsung ke abang baksonya dengan tatapan mengintimidasi'],
        correct: 1,
        explanation: 'Logika jalanan menyebutkan boraks bikin daging jadi kenyal kayak karet. Padahal bakso sapi asli yang *full* daging dan bagus teksturnya juga pasti memantul kalau dilempar (tapi sayangnya mubazir).'
    },
    {
        category: '🇮🇩 Konspirasi & Mitos Lokal',
        question: 'Dalam mitologi Jawa (Ramalan Jayabaya), tokoh misterius yang katanya akan datang menyelamatkan dan membawa kejayaan bagi Nusantara disebut...',
        options: ['Satria Piningit / Ratu Adil', 'Gatotkaca', 'Sangkuriang Reborn', 'Ksatria Baja Hitam RX'],
        correct: 0,
        explanation: 'Tiap kali mau Pemilu atau saat negara lagi kacau balau, teori konspirasi pencarian "Satria Piningit" selalu muncul. Semua calon pemimpin biasanya di-cocok-cocokkan sama ramalan ini.'
    },
    {
        category: '🇮🇩 Konspirasi & Mitos Lokal',
        question: 'Apa bukti (palsu) yang sering disebarkan di Facebook bahwa ada konspirasi Yahudi dan Iluminati tersembunyi di Indonesia?',
        options: ['Bentuk atap Gedung DPR/MPR yang katanya melambangkan sayap Dajjal', 'Kipas angin Cosmos yang putarannya berlawanan jarum jam', 'Burung Garuda ternyata burung dari Timur Tengah', 'Bentuk jajanan klepon yang bulat berwarna hijau'],
        correct: 0,
        explanation: 'Atap kura-kura Gedung DPR MPR sering dibikin cocoklogi konspirasi berbentuk sayap/mata satu oleh akun-akun misterius ber-profile picture bunga teratai atau singa mengaum di Facebook.'
    },
    {
        category: '🇮🇩 Konspirasi & Mitos Lokal',
        question: 'Menurut legenda, Candi Prambanan adalah mega-proyek yang gagal karena Bandung Bondowoso kurang satu candi dari target 1000 candi. Apa penyebab utamanya?',
        options: ['Jin kontraktornya demo minta naik gaji', 'Roro Jonggrang *nge-cheat* dengan membakar jerami agar ayam berkokok sebelum pagi', 'Kehabisan semen dan batu bata merah', 'Di-sidak oleh Dinas Tata Kota'],
        correct: 1,
        explanation: 'Roro Jonggrang adalah pionir *sabotase proyek* Nusantara. Menggunakan ibu-ibu menumbuk padi dan membakar jerami untuk menipu jin pekerja. Ujung-ujungnya, dia sendiri yang dikutuk jadi arca terakhir.'
    },
    {
        category: '🇮🇩 Konspirasi & Mitos Lokal',
        question: 'Jenglot adalah makhluk mistis kecil berambut panjang dan bertaring yang sering dipamerkan dukun. Secara konspirasi (dan sains), Jenglot itu aslinya terbuat dari apa?',
        options: ['Fosil alien purba yang mendarat di Majapahit', 'Mayat orang sakti yang ditolak bumi', 'Hasil kerajinan tangan dari hewan yang diawetkan (kulit/tulang hewan liar)', 'Mainan anak-anak produksi Tiongkok'],
        correct: 2,
        explanation: 'Faktanya, dokter forensik di RSCM pernah meneliti DNA jenglot, dan hasilnya menunjukkan itu cuma kerajinan tangan yang dibuat dari tempelan tulang dan kulit berbagai hewan (dan rambut manusia sungguhan).'
    },
    {
        category: '🇮🇩 Konspirasi & Mitos Lokal',
        question: 'Konspirasi transportasi: Mitos Mobil Ambulans di Jalan Bahureksa, Bandung, mengklaim bahwa mobil tersebut...',
        options: ['Bisa berubah jadi *Autobot*', 'Bisa menyalakan sirine dan pindah parkir sendiri di malam hari', 'Mesinnya menggunakan bahan bakar darah manusia', 'Hanya bisa disetir oleh hantu Belanda'],
        correct: 1,
        explanation: 'Urban legend Bandung tahun 2000-an. Katanya ambulans ini bekas mengangkut korban kecelakaan dan suka "jalan sendiri". Sampai akhirnya ambulans itu dibeli dan pindah tempat, barulah mitosnya mereda.'
    },
    {
        category: '🇮🇩 Konspirasi & Mitos Lokal',
        question: 'Menurut mitos penculikan era 2000-an, kalau ada mobil van/Kijang kapsul gelap kaca film 100% keliling kampung, konon mereka sedang mencari...',
        options: ['Mencari sinyal radio yang hilang', 'Mencari anak kecil untuk diculik dan dijadikan tumbal pembangunan jembatan/gedung pencakar langit', 'Mencari alamat palsu Ayu Ting Ting', 'Mencari penumpang yang mau naik Travel ilegal'],
        correct: 1,
        explanation: 'Konspirasi menakutkan yang bikin bocah zaman dulu langsung lari ngibrit masuk rumah kalau lihat mobil gelap masuk gang. Katanya anak kecil diculik buat tumbal "tumis" pondasi pilar jembatan.'
    },
    {
        category: '🇮🇩 Konspirasi & Mitos Lokal',
        question: 'Pesugihan Sate Gagak dikenal sebagai transaksi gaib untuk cepat kaya. Syarat terberat (dan paling kocak) saat melakukan ritual ini adalah...',
        options: ['Harus bisa bahasa Mandarin dengan lancar', 'Membakar sate gagak di kuburan lalu berjualan sate tanpa boleh melihat wajah pembelinya (yang adalah dedemit)', 'Harus nyanyi lagu koplo sampai pagi', 'Daging satenya harus matang sempurna alias *Medium Well*'],
        correct: 1,
        explanation: 'Konon, pelakunya harus jualan sate gagak di kuburan angker. Hantu dan jin akan datang membeli sate tersebut bayar pakai uang asli. Syaratnya: gak boleh noleh ke wajah si pembeli kalau gak mau nyawanya melayang.'
    },

    // --- 13. MULTIMEDIA, EDITING, FOTOGRAFI & PROGRAMMING DERITA ---
    {
        category: '🎬 Video Editing & Derita Render',
        question: 'Momen paling horor yang bisa memicu serangan jantung mendadak bagi seorang video editor saat lagi asyik ngedit di Premiere Pro adalah...',
        options: ['Klien minta tambah durasi video', 'Layar tiba-tiba *freeze*, muncul tulisan "Not Responding", dan baru sadar belum Ctrl+S', 'Kopi tumpah ke keyboard', 'Diminta bikin transisi jedag-jedug pusing'],
        correct: 1,
        explanation: 'Premiere Pro *crash* sebelum sempat *Save Project* adalah teguran alam semesta bahwa manusia tidak boleh sombong dan harus senantiasa mengingat Tuhan lewat tombol Ctrl+S.'
    },
    {
        category: '🎬 Video Editing & Derita Render',
        question: 'Klien minta video acaranya diedit dengan *color grading* bernuansa "Cinematic Warm Golden Tones" ala film Hollywood. Padahal realita *lighting* di lokasi syutingnya cuma pakai...',
        options: ['Lampu sorot panggung konser internasional', 'Cahaya matahari terbenam di Santorini', 'Lampu neon putih puskesmas 5 Watt yang kedap-kedip', 'Cahaya *flash* dari HP penonton'],
        correct: 2,
        explanation: 'Editor sering kali dituntut jadi pesulap visual. *Shooting* di aula remang-remang pakai lampu neon murah, tapi menuntut hasil videonya se-estetik *golden hour* di bioskop.'
    },
    {
        category: '🎬 Video Editing & Derita Render',
        question: 'Apa nama file yang melambangkan kebohongan dan kepalsuan terbesar dalam sejarah peradaban seorang *freelance video editor*?',
        options: ['Project_Akhir_Tahun.prproj', 'Video_Wedding_Bagus.mp4', 'FINAL_BANGET_FIX_TERAKHIR_UDAH_GA_ADA_REVISI_v23.mp4', 'Draft_1_Revisi.mp4'],
        correct: 2,
        explanation: 'Kata "Final" dalam penamaan file *render* video adalah ilusi belaka. Selama klien masih bisa bernapas, revisi minor yang ngeselin itu akan selalu datang.'
    },
    {
        category: '📸 Fotografi & Tragedi Lensa',
        question: 'Saat jadi pilot *drone* di acara kepanitiaan Agustusan atau acara desa, *request* paling tidak masuk akal yang sering diteriakkan bapak-bapak panitia adalah...',
        options: ['"Mas, tolong rekam dari angle *bird-eye* 45 derajat!"', '"Mas, drone-nya bisa diterbangin lebih tinggi lagi nggak sampai nembus awan biar kelihatan satu kabupaten?"', '"Mas, tolong terbangnya lebih stabil biar nggak *shaky*!"', '"Mas, awas nabrak tiang bendera!"'],
        correct: 1,
        explanation: 'Ekspektasi panitia lokal terhadap sebuah *drone* seringkali setara dengan satelit NASA. Padahal baru naik 100 meter saja sinyal remotnya sudah kembang-kempis nyangkut di pohon kelapa.'
    },
    {
        category: '📸 Fotografi & Tragedi Lensa',
        question: 'Ketika seorang editor/fotografer nekat membuka Adobe After Effects untuk bikin *motion graphic map* desa atau animasi *speed ramping*, hal pertama yang biasanya terjadi pada komputernya adalah...',
        options: ['Animasi selesai dalam 5 menit tanpa *lag* sama sekali', 'Keluarga menjadi lebih harmonis', 'Klien langsung transfer DP dan bonus', 'Suara kipas PC/Laptop tiba-tiba menderu sekencang mesin pesawat tempur lepas landas'],
        correct: 3,
        explanation: 'After Effects itu monster pemakan RAM. Bikin animasi grafis peta yang rumit sedikit saja, laptop langsung *cosplay* jadi mesin *jet Boeing 737* yang siap meledak.'
    },
    {
        category: '📸 Fotografi & Tragedi Lensa',
        question: 'Kalimat dari tamu undangan yang paling bisa meruntuhkan harga diri seorang fotografer profesional yang sedang memegang kamera *mirrorless* dan lensa puluhan juta adalah...',
        options: ['"Wah, kameranya gahar, pasti hasilnya jernih!"', '"Fokusnya kurang tajam nih mas, coba atur *shutter speed*."', '"Mas, fotokan pakai iPhone saya aja dong, kameranya lebih bagus ada boba tiganya!"', '"Bisa tolong editin lengan saya biar kelihatan kurus?"'],
        correct: 2,
        explanation: 'Punya keahlian komposisi dan lensa *aperture f/1.4* seharga motor kopling akan tetap dianggap remeh oleh kaum *mendang-mending* pemuja "iPhone boba" di acara pernikahan.'
    },
    {
        category: '💻 Programming & Penderitaan IT',
        question: 'Lulusan S1 Informatika dan punya sertifikat bergengsi BNSP *Software Development*. Namun, saat kumpul keluarga besar, tugas utama yang akan langsung dilimpahkan kepadanya adalah...',
        options: ['Menganalisa *Big Data* pengeluaran bulanan keluarga', 'Bikin arsitektur *microservices* buat bisnis keluarga', 'Disuruh nge-hack akun Facebook mantan atau benerin *printer* macet', 'Presentasi tentang *Machine Learning* di depan nenek'],
        correct: 2,
        explanation: 'Gelar sarjana komputer dan sertifikat koding nasional langsung hilang *value*-nya di mata keluarga. Pokoknya, semua anak IT dimata keluarga adalah tukang servis elektronik dan *hacker* kelas teri.'
    },
    {
        category: '💻 Programming & Penderitaan IT',
        question: 'Klien meminta Anda membangun sistem layanan *digital panel*, *website hosting*, plus aplikasi *mobile native*. Lalu dengan wajah tanpa dosa, ia menawar dengan harga...',
        options: ['Rp 50 Juta sesuai dengan *rate* standar industri IT', 'Rp 100 Juta beserta pembagian bonus saham perusahaan', 'Rp 500.000 dengan kalimat sakti "Ayolah, ini kan kerjanya cuma ngetik-ngetik doang di depan komputer"', 'Menawarkan kerja sama jangka panjang dengan gaji bulanan'],
        correct: 2,
        explanation: 'Banyak klien di luar sana yang mengira *coding* sistem server dan web *hosting* itu semudah main gim *The Sims*. "Cuma ngetik kode aja kok mahal?" adalah *red flag* terbesar di dunia *freelance developer*.'
    },
    {
        category: '💻 Programming & Penderitaan IT',
        question: 'Saat program *website* tiba-tiba *error* parah (*White Screen of Death*) dan si *developer* buntu tidak tahu apa penyebabnya, ritual purba apa yang akan dilakukan untuk mengecek *bug*?',
        options: ['Membaca ulang dokumentasi resmi (*documentation*) dari awal sampai akhir', 'Menulis `console.log("masuk sini bang")` atau `print("tes 123")` di setiap baris *source code*', 'Berdoa ke patung dewa koding', 'Langsung matikan laptop dan menyerahkan surat *resign*'],
        correct: 1,
        explanation: 'Sistem *debugging* paling sakti, paling merakyat, dan paling banyak dipakai *programmer* sebelum kena *panic attack* adalah menyebar `console.log` di seluruh penjuru fungsi program.'
    },
    {
        category: '💻 Programming & Penderitaan IT',
        question: 'Ke manakah tempat "ziarah suci" bagi seluruh *programmer* sedunia ketika mereka pusing kepentok *error code* berjam-jam dan butuh bantuan gaib dari internet?',
        options: ['Website resmi dokumentasi Microsoft atau Google', 'Perpustakaan Nasional', 'StackOverflow', 'Grup Telegram Pinjol'],
        correct: 2,
        explanation: 'StackOverflow adalah penyelamat nyawa *programmer*. Ironisnya, kadang kita tinggal *copy-paste* jawaban dari forum itu, programnya tiba-tiba jalan, tapi kita sendiri nggak paham *kenapa* kode itu bisa jalan.'
    },

    // --- 14. SKENA MUSIK GEN Z 2026 ---
    {
        category: '🎧 Skena Musik Gen Z 2026',
        question: 'Band Perunggu sering dijuluki oleh penggemarnya sebagai band "Rock Pulang Kerja". Kenapa Gen Z dan Milenial korporat sangat *relate* sampai sering nangis di KRL dengerin lagu mereka?',
        options: ['Karena liriknya penuh teori konspirasi', 'Karena liriknya mewakili rasa capek dimarahi bos, revisian PPT, dan krisis identitas saat gajian cuma numpang lewat', 'Karena vokalisnya selalu pakai seragam PNS', 'Karena lagunya dibikin khusus buat alarm pagi'],
        correct: 1,
        explanation: 'Perunggu adalah obat pelipur lara para budak korporat. Musiknya cadas, tapi liriknya meratapi nasib masuk jam 8 pagi pulang jam 8 malam.'
    },
    {
        category: '🎧 Skena Musik Gen Z 2026',
        question: 'Indahkus adalah musisi Indonesia yang punya visual ala *idol K-Pop* dan bahkan pernah ikut *survival show* (E-Pop) di Tiongkok. Apa *plot twist* terbesar dari perjalanan karirnya di mata netizen?',
        options: ['Ternyata dia aslinya agen rahasia pemerintah', 'Dia aslinya adalah seorang Dokter Medis betulan yang lebih milih mikirin *choreo* panggung daripada nulis resep Paracetamol', 'Dia tidak bisa bahasa Indonesia sama sekali', 'Dia keturunan langsung kerajaan Majapahit'],
        correct: 1,
        explanation: 'Banyak fans baru yang syok saat tahu kalau Indahkus adalah lulusan Kedokteran dan punya gelar Dokter (dr. Indah Kusumaningrum). Cantik, pintar, jago nyanyi, bikin netizen *insecure* maksimal.'
    },
    {
        category: '🎧 Skena Musik Gen Z 2026',
        question: 'Jika seorang Gen Z memutar lagu Bernadya berulang-ulang di Spotify pada jam 2 pagi, diagnosis psikologis paling akurat untuknya adalah...',
        options: ['Menderita Insomnia akut', 'Gagal *move on* kronis disertai halusinasi bahwa sang mantan akan tiba-tiba nge-chat ngajak balikan', 'Sedang latihan paduan suara kecamatan', 'Kerasukan roh pujangga'],
        correct: 1,
        explanation: 'Bernadya adalah Ratu Galau Gen Z. Dengerin lagunya di tengah malam adalah bentuk penyiksaan diri secara sadar karena gagal merelakan masa lalu.'
    },
    {
        category: '🎧 Skena Musik Gen Z 2026',
        question: 'Apa fenomena alamiah yang HUKUMNYA WAJIB terjadi pada sebuah lagu *indie-pop* super sedih dan bermakna dalam, sesaat setelah lagunya viral masuk FYP TikTok?',
        options: ['Mendapat penghargaan Grammy', 'Disetel di Istana Negara', 'Dalam kurun waktu 2x24 jam pasti sudah muncul versi remix "Jedag Jedug Koplo" atau versi *Sped-Up* (dipercepat) ala suara *chipmunk*', 'Vokalisnya diundang ke podcast Deddy Corbuzier'],
        correct: 2,
        explanation: 'Tidak ada lagu sedih yang aman dari *beat* jedag-jedug di TikTok. Lagu tentang kematian atau putus cinta pun tetap bisa dipakai buat *background sound* joget-joget pargoy.'
    },
    {
        category: '🎧 Skena Musik Gen Z 2026',
        question: 'Bagi Gen Z penganut aliran *overthinking* tiap malam, datang ke konser Hindia (Baskara Putra) dan ikut nyanyi lagu "Evaluasi" ibarat sedang melakukan...',
        options: ['Sesi senam aerobik massal', 'Sesi terapi psikolog berkedok konser musik di tengah kerumunan anak *senja*', 'Ujian Nasional susulan', 'Ritual memanggil hujan'],
        correct: 1,
        explanation: 'Konser Hindia adalah tempat di mana ribuan anak muda kumpul buat nangis bareng meratapi beban hidup, tuntutan orang tua, dan cicilan yang belum lunas.'
    },
    {
        category: '🎧 Skena Musik Gen Z 2026',
        question: 'Lirik lagu Sal Priadi selalu teaterikal dan sangat puitis (contoh: Gala Bunga Matahari). Reaksi paling umum orang awam saat membaca lirik aslinya tanpa alunan nada adalah...',
        options: ['"Ini lagu atau contekan naskah ujian praktek Bahasa Indonesia bab Puisi Kontemporer?"', '"Bahasanya kasar banget"', '"Kok liriknya kayak resep seblak?"', '"Pasti ini terjemahan Google Translate"'],
        correct: 0,
        explanation: 'Sal Priadi itu ibarat penyair Pujangga Baru yang kesasar di era Spotify. Liriknya sangat sastra tingkat dewa, bikin yang dengar berasa jadi *Lord Byron* dari Jaksel.'
    },
    {
        category: '🎧 Skena Musik Gen Z 2026',
        question: 'Dalam hierarki ekosistem musik masa kini, ada kasta tertinggi yang dijuluki "Anak Skena". Apa syarat mutlak kelakuan dari kelompok elit ini saat ditanya soal *taste* musik mereka?',
        options: ['Selalu memutar lagu K-Pop di volume maksimal', 'Hanya mau mendengarkan lagu ciptaan sendiri', 'Saat ditanya, mereka akan menyebutkan nama band yang 99% manusia bumi belum pernah dengar, biar dibilang *underground* dan *edgy*', 'Hafal semua lagu Wali dan Radja'],
        correct: 2,
        explanation: 'Bagi anak skena *hardcore*, menyukai band yang *followers*-nya masih di bawah 1.000 adalah sebuah medali kehormatan sejati.'
    },
    {
        category: '🎧 Skena Musik Gen Z 2026',
        question: 'Saat Iqbaal Ramadhan tiba-tiba *comeback* merilis proyek solo bernama BAALE dengan video klip gaya 90-an yang super absurd, bagaimana respons standar penikmat musik Gen Z?',
        options: ['Langsung dilaporkan ke KPI', 'Dimaafkan dan dibilang "Seni/Art" karena Iqbaal ganteng. Coba kalau yang nyanyi bapak-bapak pos ronda, pasti dikira kurang waras', 'Diboikot karena kurang jedag-jedug', 'Dicalonkan jadi lagu kebangsaan baru'],
        correct: 1,
        explanation: '*Pretty privilege* is real! Visual klip yang sengaja dibikin *cringe* dan *low-budget* ala tahun 90-an langsung dianggap karya jenius *avant-garde* hanya karena yang memerankannya adalah Dilan.'
    },
    {
        category: '🎧 Skena Musik Gen Z 2026',
        question: 'Dulu musik *Ambyar* (Campursari/Koplo) identik dengan bapak-bapak di pantura. Kini, alasan Gen Z elit SCBD rela nyanyi lagu Guyon Waton, Gilga Sahid, atau NDX AKA sambil nangis adalah...',
        options: ['Disuruh oleh dosen pembimbing', 'Karena putus cinta lalu meratap pakai bahasa Jawa *vibes*-nya jauh lebih perih dan hancur lebur dibanding pakai bahasa Inggris', 'Agar dapat diskon di warteg', 'Karena bahasa Inggris mereka pas-pasan'],
        correct: 1,
        explanation: 'Kombinasi *beat* koplo yang asyik buat joget tapi liriknya ngomongin diselingkuhin pakai bahasa Jawa halus adalah puncak komedi tragis yang *relate* banget sama Gen Z.'
    },
    {
        category: '🎧 Skena Musik Gen Z 2026',
        question: 'Reality Club punya musik sinematik dan lirik berbahasa Inggris dengan aksen super fasih. Kesalahpahaman terbesar (*culture shock*) bagi pendengar baru mereka adalah...',
        options: ['Mengira vokalisnya adalah alien', 'Mengira lagunya hasil *generate* AI', 'Mengira mereka band Indie Rock asal Inggris (UK) tongkrongan Arctic Monkeys, padahal aslinya tongkrongan Jakarta', 'Mengira itu adalah grup lawak'],
        correct: 2,
        explanation: 'Banyak bule dan pendengar lokal yang syok berat saat tahu band berkelas internasional dengan lagu *catchy* seperti "Anything You Want" ini asalnya dari Jakarta, bukan dari London.'
    },
    {
        category: '🎧 Skena Musik Gen Z 2026',
        question: 'Apa fungsi *dark system* di balik tradisi tahunan pamer "Spotify Wrapped" di Instagram Story bagi mayoritas Gen Z?',
        options: ['Untuk pamer ke mertua kalau mereka kaya raya bisa langganan premium', 'Kedok untuk melakukan *trauma dumping* secara estetik biar *followers* tahu betapa hancur mental mereka setahun ke belakang', 'Agar dapat centang biru dari Spotify', 'Daftar tunggu masuk surga'],
        correct: 1,
        explanation: 'Memamerkan *Top Song* yang isinya lagu depresi dan *Top Artist* Bernadya/Hindia selama ratusan jam adalah cara halus untuk teriak "TOLONG, MENTAL SAYA SEDANG TIDAK BAIK-BAIK SAJA!".'
    },
    {
        category: '🎧 Skena Musik Gen Z 2026',
        question: 'Konser Tulus selalu penuh sesak dan romantis. Namun, ada satu mitos/resiko "Kutukan" paling ngeri kalau kamu membawa pacar baru ke konsernya, yaitu...',
        options: ['Tiba-tiba berubah wujud jadi Gajah', 'Pulang konser besoknya tiba-tiba minta putus karena ngerasa *relate* banget meresapi lirik lagu "Hati-Hati di Jalan"', 'Dilarang makan daging selama seminggu', 'Ketahuan kalau pacarmu aslinya hologram'],
        correct: 1,
        explanation: 'Banyak kasus di mana lagu-lagu Tulus yang terlalu magis dan *deep* membuat seseorang tiba-tiba tersadar kalau hubungannya saat ini *toxic* atau memang tidak bisa dilanjutkan.'
    },
    {
        category: '🎧 Skena Musik Gen Z 2026',
        question: 'Fenomena viralnya musisi cowok *acoustic* (seperti Nadhif Basalamah) membuktikan bahwa cowok main gitar kopong masih bisa rajai Top 50 Spotify. Syarat vokalnya adalah...',
        options: ['Harus bisa teriak ala *death metal*', 'Suaranya harus terdengar sedikit bindeng (sengau) *aesthetic*, seolah-olah lagi nahan nangis atau kena flu ringan di pinggir pantai', 'Harus bisa nyanyi 8 oktaf ala Mariah Carey', 'Tidak boleh ada suaranya sama sekali'],
        correct: 1,
        explanation: 'Suara bindeng-bindeng *soft boy* yang empuk dan terkesan rapuh adalah kunci sukses meluluhkan hati pendengar wanita Gen Z di era sekarang.'
    },
    {
        category: '🎧 Skena Musik Gen Z 2026',
        question: 'Album Kunto Aji (Mantra Mantra) diakui luas sebagai mahakarya *healing*. Jika teman nongkrong Gen Z kamu tiba-tiba *looping* album ini seharian penuh, tindakan darurat apa yang harus kamu lakukan?',
        options: ['Ajak mabar Mobile Legends', 'Cepat tanyakan kabar mentalnya, traktir kopi, dan dengerin curhatannya karena dia kemungkinan besar lagi *burnout* atau hancur lebur', 'Lapor polisi', 'Ikut nyanyi pakai Toa Masjid'],
        correct: 1,
        explanation: 'Mendengarkan Kunto Aji seharian adalah *Red Flag* (tanda bahaya) psikologis. Temanmu sedang mencoba merawat kewarasannya dari kerasnya kehidupan nyata.'
    },
    {
        category: '🎧 Skena Musik Gen Z 2026',
        question: 'Apa *starter-pack* mutlak dan *dresscode* tak tertulis yang wajib dipakai anak skena saat nonton festival musik di Jakarta (seperti Synchronize/Pestapora) pada bulan-bulan musim hujan berbadai?',
        options: ['Payung pantai ukuran XL', 'Setelan jas hujan plastik Alfamart warna-warni Rp 15.000, sepatu *boots* belepotan lumpur, dan *tote bag* sablonan yang basah kuyup', 'Baju renang *scuba diving*', 'Baju zirah abad pertengahan'],
        correct: 1,
        explanation: 'Mau sekeren apapun *outfit thrifting* anak skena, pada akhirnya semua akan tunduk, rata, dan seragam dibungkus jas hujan kelelawar murah meriah saat badai lumpur festival menyerang.'
    },

    // --- 15. REALITA ASMARA, HTS & BUCIN ---
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Kasta tertinggi dari penderitaan HTS (Hubungan Tanpa Status) di Indonesia adalah ketika...',
        options: ['Lupa ngucapin selamat pagi', 'Cemburu buta ngeliat dia jalan sama orang lain, mau ngamuk tapi sadar diri nggak punya hak buat marah', 'Nggak dibayarin makan pas jalan bareng', 'Chat cuma di-read doang'],
        correct: 1,
        explanation: 'Namanya juga HTS, hakikatnya cuma teman. Giliran dia jalan sama gebetan baru, dada sesak napas, mau marah tapi pasrah karena ditampar realita: "Kan kita belum jadian".'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Tingkat kebucinan (Budak Cinta) paling konyol yang rela dilakukan cowok demi ayangnya di tengah malam adalah...',
        options: ['Rela nerobos hujan badai jam 2 pagi cuma buat beliin seblak ceker level 5', 'Nulisin puisi cinta 3 lembar folio', 'Nyanyiin lagu nina bobo pakai gitar kopong', 'Ngerjain tugas kuliah ayang sampai begadang'],
        correct: 0,
        explanation: 'Logika cowok bucin itu di luar nalar. Hujan badai, jalanan banjir, dan angin ribut akan tetap diterjang tanpa jas hujan demi seporsi seblak pesanan paduka ratu.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Kelakuan bucin paling absurd saat lagi kangen-kangenan di malam hari yang bikin teman-teman tongkrongan pada *ilfeel* ngelihatnya adalah...',
        options: ['Kirim-kiriman pantun romantis', '*Sleep call* (telponan sampai ketiduran) dan dibiarin nyala sampai pagi buat dengerin suara ngorok ayang', 'Pamer saldo rekening biar ayang seneng', 'Nonton Netflix bareng secara *online*'],
        correct: 1,
        explanation: '*Sleep call* adalah budaya bucin yang bikin bingung ilmuwan sains. Buat apa HP dibiarin nyala nyedot kuota berjam-jam cuma buat dengerin desahan napas dan dengkuran orang tidur?'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Apa bukti pengorbanan digital tertinggi seorang cowok bucin demi menjaga suasana hati pacarnya yang super *insecure*?',
        options: ['Hapus aplikasi Mobile Legends tanpa paksaan', 'Unfollow massal semua akun cewek cantik, selebgram, dan mutualan lawan jenis di Instagram (Following jadi 0 atau 1 doang)', 'Ganti foto profil jadi warna hitam legam', 'Menyerahkan sertifikat rumah'],
        correct: 1,
        explanation: 'Daftar *Following* Instagram yang isinya cuma 1 orang (yaitu pacarnya) adalah kasta tertinggi dari validasi kesetiaan—sekaligus bukti nyata adanya tekanan batin dari sang pacar.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Perbedaan paling mendasar antara HTS (Hubungan Tanpa Status) dengan Pacaran resmi sebenarnya cuma terletak pada...',
        options: ['Biaya nongkrong yang lebih murah', 'Restu orang tua dan keluarga besar', 'Status yang legal dan kemampuan untuk "ngambek" secara sah di mata hukum asmara', 'Boleh pegangan tangan atau nggak'],
        correct: 2,
        explanation: 'Di fase HTS, semua perlakuan udah *plek-ketiplek* kayak orang pacaran (chat 24 jam, jalan tiap minggu, manggil sayang). Bedanya cuma satu: kamu nggak punya legalitas buat cemburu dan ngambek!'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Dalam kamus percintaan Gen Z, apa kepanjangan paling akurat dan realistis dari HTS (Hubungan Tanpa Status)?',
        options: ['Hubungan Teman Saja', 'Harapan Tidak Sampai', 'Hancur Tanpa Sisa', 'Hanya Teman Sharing'],
        correct: 2,
        explanation: 'HTS adalah jebakan Batman masa kini. Haknya seperti pacar (bisa cemburu), tapi pas ditinggalin nggak bisa marah karena sadar diri "cuma temen". Ujung-ujungnya Hancur Tanpa Sisa.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Alasan paling klasik dan 99% *bullshit* dari cowok saat tiba-tiba ngilang (Ghosting) pas lagi sayang-sayangnya adalah...',
        options: ['"Aku diculik alien"', '"Aku lagi mau fokus karir dan perbaiki diri dulu"', '"HP aku kecemplung laut"', '"Aku amnesia"'],
        correct: 1,
        explanation: 'Kalimat "fokus karir" biasanya cuma tameng. Realitanya, karir nggak maju-maju, tapi besoknya dia udah *update* Story jalan bareng cewek lain.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Jika seseorang melakukan *Love Bombing* (menghujani perhatian ekstrem) di minggu pertama kenal, apa yang biasanya terjadi di minggu ketiga?',
        options: ['Langsung diajak ke KUA', 'Dia berubah dingin bak kulkas dua pintu dan pelan-pelan ngilang', 'Membelikan rumah KPR', 'Menjadi donatur tetap panti asuhan'],
        correct: 1,
        explanation: 'Pelaku *Love Bombing* itu energinya kayak baterai bocor. Awalnya di-gas pol dipanggil "Ayah-Bunda", masuk minggu ketiga baterainya habis lalu ngilang tanpa jejak.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Zodiak apa yang selalu jadi "kambing hitam" dan sasaran *bully* nasional kalau ngomongin cowok/cewek *red flag* yang manipulatif?',
        options: ['Taurus', 'Gemini', 'Virgo', 'Pisces'],
        correct: 1,
        explanation: 'Entah kenapa, kalau ada cerita diselingkuhin atau di-ghosting di Twitter/X, ujung-ujungnya pasti ada netizen yang nanya: "Pasti zodiaknya Gemini ya?"'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Apa fungsi utama dan rahasia terbesar dari "Second Account" (akun kedua) Instagram bagi orang yang baru putus cinta?',
        options: ['Buat jualan *thrift* baju bekas', 'Buat nge-post kata-kata motivasi Islami', 'Buat nge-stalking mantan dan gebetan barunya tanpa ketahuan', 'Buat nge-like postingan artis Korea'],
        correct: 2,
        explanation: 'Akun kedua tanpa *profile picture* dengan *followers* 0 adalah CCTV paling canggih di dunia maya untuk memantau apakah mantan hidupnya menderita atau malah makin bahagia.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Kalimat pamungkas yang menandakan kamu resmi ditendang dan terperosok ke dalam jurang "Friendzone" yang tak berdasar adalah...',
        options: ['"Kamu tuh udah aku anggap kayak kakak/adik aku sendiri"', '"Aku benci sama kamu"', '"Besok kita nonton yuk"', '"Kamu bau bawang"'],
        correct: 0,
        explanation: 'Begitu kata "kayak kakak sendiri" keluar dari mulutnya, silakan mundur teratur. Tidak ada sejarahnya kakak-adikan beda KK (Kartu Keluarga) berujung ke pelaminan.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Bagi pasangan LDR (Long Distance Relationship), bagaimana cara mereka menyalurkan *Love Language* berupa "Physical Touch"?',
        options: ['Nge-print foto pacar lalu dielus-elus', 'Pegang HP sambil *video call* sampai baterai panas meledak', 'Berpelukan lewat telepati', 'Titip peluk lewat tukang paket JNE'],
        correct: 1,
        explanation: 'LDR adalah ujian kesabaran tingkat dewa. *Physical touch*-nya cuma sebatas nyium layar HP yang kebetulan kotor penuh sidik jari.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Apa terjemahan paling jujur dari cewek yang membalas chat panjang lebar pakai kalimat sakti: "Yaudah, Terserah."?',
        options: ['Dia membebaskan kamu memilih keputusan', 'Dia setuju dengan pendapatmu', 'Kamu baru saja memencet tombol bom nuklir, bersiaplah untuk perang dunia', 'Dia lagi ngantuk'],
        correct: 2,
        explanation: 'Kata "Terserah" dari mulut wanita memiliki 1.000 makna tersembunyi, dan 999 di antaranya berarti "Kalau kamu lakuin itu, kelar hidup lo!"'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Apa *outfit* (pakaian) cowok paling standar, aman, sekaligus ngebosenin yang hampir pasti dipakai saat *First Date* nonton bioskop?',
        options: ['Kemeja Flanel kotak-kotak, kaos daleman hitam, dan celana chino', 'Setelan Jas Tuxedo ala James Bond', 'Baju Koko dan Sarung', 'Kaos Partai'],
        correct: 0,
        explanation: 'Kemeja flanel adalah *starter pack* kencan pertama cowok-cowok Indonesia. Kalau di bioskop ada 10 cowok lagi *first date*, 8 di antaranya pasti pakai seragam ini.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Apa taktik "Tarik Ulur" paling manipulatif dan ngeselin yang sering dipakai cowok/cewek di fase awal PDKT?',
        options: ['Sengaja balas chat 3 jam kemudian biar dikira sibuk dan mahal, padahal dari tadi cuma *scroll* TikTok', 'Langsung nelpon jam 3 pagi', 'Blokir WA lalu di-unblok lagi tiap 5 menit', 'Ngasih kode morse lewat Story IG'],
        correct: 0,
        explanation: 'Sok sibuk adalah kunci terlihat elegan di masa PDKT. Padahal notif chat-nya udah dibaca dari tadi lewat *pop-up* atas layar biar nggak centang biru.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Pertanyaan mematikan dari cewek yang tidak memiliki jawaban benar dan dirancang khusus untuk mencari masalah adalah...',
        options: ['"Makan di mana kita hari ini?"', '"Menurut kamu, aku gendutan nggak sih?"', '"Kamu suka warna apa?"', '"Jam berapa sekarang?"'],
        correct: 1,
        explanation: 'Jawab "Iya" = Kiamat. Jawab "Nggak kok" = Dibilang bohong. Jawab "Biasa aja" = Dibilang nggak perhatian. *You just can\'t win this game, bro.*'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Apa *love language* (bahasa cinta) "Acts of Service" yang paling relevan dengan realita perbudakan asmara zaman *now*?',
        options: ['Membukakan pintu mobil', 'Membantu ngerjain skripsi', 'Membayarkan tagihan PayLater dan *checkout* keranjang Shopee ayang', 'Menyapu halaman rumah'],
        correct: 2,
        explanation: 'Di era kapitalis ini, romantis itu tidak lagi diukur dari puisi, tapi dari seberapa ikhlas kamu melunasi paylater SpayLater pasanganmu.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Ciri-ciri *Pick-Me Girl* (cewek caper) saat ngobrol sama cowok gebetannya biasanya dimulai dengan kalimat andalan...',
        options: ['"Aku tuh nggak bisa hidup tanpa seblak"', '"Aku tuh beda dari cewek lain, aku nggak suka dandan dan nongkrongnya sama cowok-cowok soalnya *no drama*"', '"Aku cita-citanya mau jadi astronot"', '"Aku suka banget baca buku filsafat"'],
        correct: 1,
        explanation: 'Merendahkan wanita lain demi terlihat menonjol dan santai di mata cowok adalah *core value* dari seorang *Pick-Me Girl* sejati.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Saat cowok bilang "Semalam aku ketiduran, maaf ya sayang", probabilitas tertinggi kegiatan asli yang sedang dia lakukan semalam adalah...',
        options: ['Benar-benar lelah habis kerja lembur', 'Tidur nyenyak 8 jam', 'Push rank Mobile Legends atau Valorant sama *circle*-nya sambil *open mic*', 'Nangis di pojokan kamar'],
        correct: 2,
        explanation: '"Ketiduran" adalah *cheat code* universal kaum pria untuk menghindari pertengkaran karena asyik *mabar* dan lupa balas *chat* ayang.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Apa inovasi paling mutakhir dan licik dari kaum peselingkuh untuk menyembunyikan *chat* gelap agar tidak ketahuan pacar?',
        options: ['Menghapus pesan satu per satu', 'Pindah *chat* via fitur pesan di aplikasi Shopee, Gojek, atau *Google Docs*', 'Pakai telepati', 'Kirim surat via merpati pos'],
        correct: 1,
        explanation: 'Siapa yang bakal curiga cek kotak masuk aplikasi Gojek? Peselingkuh zaman sekarang *effort*-nya menembus batas kewajaran teknologi.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Di era *dating app* (Tinder/Bumble/Omi), tipe *typing* (gaya ketik) cowok yang bikin cewek *ilfeel* tingkat dewa dalam 3 detik pertama adalah...',
        options: ['Menulis dengan EYD yang sempurna', 'Typing jamet: "p", "hy", "blh knln?", "lG aP?"', 'Mengetik dengan bahasa Inggris *British*', 'Kirim pantun'],
        correct: 1,
        explanation: 'Tampang seganteng apapun di *profile picture*, kalau *typing*-nya pakai huruf besar-kecil disingkat-singkat ala *keyboard* alay 2010, auto *swipe left*.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Apa bukti paling valid bahwa seseorang sudah mencapai level "Bucin (Budak Cinta)" stadium akhir yang sulit diselamatkan?',
        options: ['Saling tukar *password* Instagram dan bikin bio isi tanggal jadian berserta inisial gembok 🔒', 'Membuatkan puisi cinta tiap pagi', 'Menjadikan foto pacar sebagai *wallpaper* HP', 'Mengantar jemput pacar tiap hari'],
        correct: 0,
        explanation: 'Tukar *password* sosmed adalah bentuk "penjajahan privasi" berkedok cinta suci. Dan biasanya, hubungan yang pakai bio gembok begini umurnya nggak nyampe sebulan.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Hal "Bare Minimum" (kewajiban standar dasar banget) dari seorang cowok yang entah kenapa sering diglorifikasi dan dipuji habis-habisan oleh cewek adalah...',
        options: ['Membelikan pulau pribadi', 'Bisa membalas pesan (*fast respon*) dan tidak selingkuh', 'Punya gelar S3 dari Oxford', 'Jago masak masakan Prancis'],
        correct: 1,
        explanation: 'Saking banyaknya cowok *red flag*, cowok yang cuma bales *chat* cepet dan nggak selingkuh langsung dipuja-puja bak malaikat turun dari langit. Padahal itu emang standar dasar manusia normal.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Fase Gamon (Gagal Move On) paling memalukan yang biasanya dirahasiakan oleh semua orang dari teman-temannya adalah...',
        options: ['Nangis di bawah *shower* pakai lagu Adele', 'Nge-stalk mantan pakai akun *fake*, kepencet "Like" di foto mantan yang di-post 2 tahun lalu', 'Nulis puisi sedih di buku *diary*', 'Makan es krim sekotak sendirian'],
        correct: 1,
        explanation: 'Jantung langsung berhenti berdetak saat jempol gemukmu nggak sengaja kepencet *Like* di postingan lama mantan. Tidak ada obatnya selain pura-pura mati.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Saat mentok ide, kurang dana, dan kehabisan tempat nongkrong, destinasi kencan *default* pasangan muda Indonesia biasanya akan berlabuh ke...',
        options: ['Fine dining di hotel bintang 5', 'Piknik di kebun raya bawa bekal', 'Muter-muter keliling kota nggak jelas ujung-ujungnya beli es krim Mixue atau makan McD', 'Liburan ke Maldives'],
        correct: 2,
        explanation: 'Debat "Makan di mana?" yang durasinya 2 jam di atas motor, biasanya akan selalu berakhir di maskot boneka salju merah pembawa kedamaian: Mixue.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Alasan paling nggak masuk akal dari cewek saat menjustifikasi dirinya balikan lagi sama mantan yang *toxic* parah adalah...',
        options: ['"Aku dipelet"', '"Tapi dia aslinya baik banget kok, kemaren khilaf aja mukul meja"', '"Aku diancam yakuza"', '"Dia mirip aktor Korea"'],
        correct: 1,
        explanation: 'Kalimat "tapi dia aslinya baik" adalah tameng kebodohan hakiki. Teman-teman yang udah capek dengerin curhatannya nangis-nangis biasanya langsung pengen *resign* jadi *bestie*.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Bagaimana cara PDKT elegan ala anak IT, *Software Developer*, atau cowok Editor Video yang sering tidak disadari oleh gebetannya?',
        options: ['Mengirimkan karangan bunga mawar', 'Nawarin jasa *install* ulang Windows, benerin laptop lemot, atau ngeditin tugas video pakai Premiere Pro gratisan', 'Menyanyikan lagu cinta pakai gitar', 'Mengirim puisi lewat email'],
        correct: 1,
        explanation: 'Anak IT dan *Editor* itu romantisnya beda. Ketika kata-kata gagal merayu, *flashdisk* isi *installer* Windows 11 dan *crack* Adobe Premiere lah yang berbicara.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Apa fungsi sebenarnya dari cewek yang tiba-tiba nge-post foto *selfie* mata sembab habis nangis di fitur *Close Friends* Instagram?',
        options: ['Sebagai rekam medis untuk dokter mata', 'Butuh validasi dan caper nunggu di-reply "Kamu kenapa? Are you okay?" sama cowok incarannya', 'Latihan akting menangis', 'Sengaja pamer kalau air matanya sebening kristal'],
        correct: 1,
        explanation: 'Nangis itu harusnya ke *psikolog*, tapi Gen Z lebih memilih nangis di *Close Friends*. Kalau sang gebetan nggak nge-reply, foto nangisnya langsung dihapus.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Janji palsu (*sweet lies*) dari mulut buaya darat yang paling sering diucapkan saat awal jadian tapi 100% *scam* adalah...',
        options: ['"Aku janji bakal cicilin KPR rumah kita"', '"Aku nggak bakal pernah ninggalin kamu apa pun yang terjadi"', '"Aku janji beliin kamu helikopter"', '"Aku akan diet besok"'],
        correct: 1,
        explanation: 'Kalimat "nggak akan ninggalin" biasanya memiliki masa berlaku maksimal 3 bulan. Begitu nemu yang lebih bening di tongkrongan, janji itu expired secara otomatis.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Jam berapakah yang diakui secara internasional oleh kaum muda sebagai "Jam Rawan Overthinking Berujung Kangen Mantan"?',
        options: ['Pukul 12.00 Siang pas makan siang', 'Pukul 02.00 - 03.00 Pagi', 'Pukul 18.00 Sore pas azan Maghrib', 'Pukul 07.00 Pagi pas upacara'],
        correct: 1,
        explanation: 'Di jam 2 pagi, otak tidak bisa membedakan mana realita dan mana halusinasi. Tiba-tiba semua memori indah teringat, padahal dulu putusnya gara-gara diselingkuhin.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Salah satu ciri utama cowok *Red Flag* berkedok cowok mapan idaman mertua di era sekarang adalah...',
        options: ['Sering pakai baju koko', 'Gaya *hedon* nongkrong di *cafe* Jaksel pakai iPhone Promax, tapi *debt collector* pinjol neror ke nomor kontak darurat pacarnya', 'Suka main kucing', 'Kerja kantoran jam 8 sampai jam 5'],
        correct: 1,
        explanation: 'Banyak cowok sok elit bergaya sultan di luar, tapi bayar kopi aja ngutang SpayLater, bahkan pacarnya sendiri dijadikan kontak darurat pinjol (Pinjaman Online).'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Jalur *Move On* paling instan dan ampuh tanpa perlu ke psikolog untuk melupakan mantan adalah...',
        options: ['Mendengarkan ceramah agama 24 jam', 'Pindah ke luar negeri', 'Jalur "Ilfeel": Gak sengaja lihat mantan nge-post joget TikTok *cringe* abis atau dandan jamet', 'Makan seblak level 10'],
        correct: 2,
        explanation: 'Tidak ada yang lebih cepat menyembuhkan patah hati selain rasa *ilfeel*. Sekali lihat mantan joget pargoy nggak jelas di FYP, rasa cinta langsung berubah jadi rasa syukur karena udah putus.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Apa kebohongan terbesar cowok saat lagi kumpul nongkrong bareng teman-temannya ngomongin masalah percintaannya?',
        options: ['"Gue yang mutusin dia duluan bro, dia yang nangis-nangis ngejar gue"', '"Gue beliin dia berlian kemarin"', '"Gue jomblo karena pilihan"', '"Gue sebenarnya alergi cewek"'],
        correct: 0,
        explanation: 'Demi menjaga ego dan harga diri di depan *circle*-nya, cowok yang nangis di pojokan kamar habis diputusin akan selalu *claim* bahwa dialah bosnya dalam hubungan tersebut.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Di era *modern dating*, apa kepanjangan dari FWB yang realitanya sering berujung pada penderitaan salah satu pihak?',
        options: ['Forum Warga Betawi', 'Friends With Benefits', 'Fokus Waktu Belajar', 'Festival Warna Bersama'],
        correct: 1,
        explanation: 'FWB (Friends With Benefits) katanya sih modern dan "no baper". Praktiknya? Minggu pertama FWB, minggu kedua nangis cemburu lihat *partner*-nya jalan sama orang lain.'
    },
    {
        category: '💔 Realita Asmara & PDKT',
        question: 'Apa alasan utama banyak Gen Z maksa pacaran padahal dompet lagi tipis dan mentalku belum stabil?',
        options: ['Ingin membangun keluarga sakinah mawaddah warahmah sejak dini', 'FOMO (Fear Of Missing Out): Biar ada yang ngucapin "Good Morning" dan gak plonga-plongo sendirian pas datang ke kondangan temen', 'Mencari partner bisnis', 'Biar ada yang bayarin pajak motor'],
        correct: 1,
        explanation: 'Di usia 20-an, pacar seringkali cuma berfungsi sebagai tameng sosial agar tidak ditanya "Kapan nikah? Mana pasangannya?" saat hadir di acara pernikahan teman.'
    },

    // --- 16. PERCINTAAN SEHAT & LOGIS (GREEN FLAG) ---
    {
        category: '💖 Percintaan Sehat & Logis (Green Flag)',
        question: 'Bagaimana cara meminta maaf yang benar dan dewasa saat kamu berbuat salah ke pasangan?',
        options: ['"Maaf ya kalau kamu ngerasa gitu..." (Gaslighting tipis-tipis)', 'Pura-pura amnesia dan tiba-tiba ngajak makan seblak biar dia lupa', 'Mengakui kesalahan spesifik, minta maaf tanpa *tapi*, dan tanya cara memperbaikinya', 'Bikin *thread* klarifikasi di Twitter'],
        correct: 2,
        explanation: 'Minta maaf versi dewasa itu butuh kebesaran hati. Kalimat "Maaf kalau kamu baper" bukanlah permintaan maaf, melainkan deklarasi ngajak ribut jilid dua.'
    },
    {
        category: '💖 Percintaan Sehat & Logis (Green Flag)',
        question: 'Ketika pacarmu bilang, "Sayang, *weekend* ini aku mau *Me Time* dulu ya," respons paling sehat yang harus kamu berikan adalah...',
        options: ['Langsung nuduh dia mau jalan sama selingkuhan', 'Menghargai keputusannya karena sadar tiap orang butuh ruang pribadi (energi sosial habis)', 'Nangis guling-guling sambil nge-post quotes galau di WA', 'Mengirimkan intelijen negara buat ngawasin dia'],
        correct: 1,
        explanation: '*Me time* bukan berarti dia nggak cinta lagi. Terkadang, manusia butuh waktu rebahan seharian mandangin kipas angin tanpa harus mikirin balasan *chat* siapa pun.'
    },
    {
        category: '💖 Percintaan Sehat & Logis (Green Flag)',
        question: 'Apa bukti paling nyata dari sebuah "Green Flag" (tanda positif) saat kencan pertama (*first date*)?',
        options: ['Dia pamer isi saldo rekening tabungannya', 'Dia bisa mendengarkan ceritamu dengan antusias tanpa memotong dan nggak sibuk main HP', 'Dia langsung ngajak nikah besoknya', 'Dia hafal seluruh silsilah keluargamu (padahal belum dikasih tau)'],
        correct: 1,
        explanation: 'Kehadiran penuh (*presence*) itu mahal harganya. Cowok/cewek yang bisa nge-jauhin HP saat ngobrol di kencan pertama adalah berlian yang wajib diamankan.'
    },
    {
        category: '💖 Percintaan Sehat & Logis (Green Flag)',
        question: 'Bagaimana cara *couple* (pasangan) yang dewasa menyelesaikan tagihan makanan saat nge-*date* agar hubungan tetap sehat?',
        options: ['Cowok wajib bayar semuanya sampai kiamat karena itu harga diri', 'Pura-pura sakit perut ke toilet pas *bill* tagihan datang', 'Terapkan *Split Bill* (bayar masing-masing) atau gantian traktir tanpa perhitungan', 'Bayar pakai daun layaknya di dunia peri'],
        correct: 2,
        explanation: 'Hubungan sehat itu tentang *partnership*. Gantian traktir atau bagi dua tagihan bikin hubungan jauh dari rasa "berutang budi" dan beban finansial sepihak.'
    },
    {
        category: '💖 Percintaan Sehat & Logis (Green Flag)',
        question: 'Saat kalian sedang bertengkar hebat dan emosi memuncak, tindakan paling cerdas dan sehat untuk menyelamatkan hubungan adalah...',
        options: ['Banting pintu dan memblokir WA (*Silent Treatment*) seminggu penuh', 'Minta *time-out* sebentar buat tenangin kepala, lalu bahas lagi masalahnya dengan kepala dingin', 'Adu *khodam* dan ilmu santet', 'Langsung teriak "Yaudah kita PUTUS!" (padahal besoknya nyesel)'],
        correct: 1,
        explanation: 'Orang dewasa sadar kalau marah-marah saat emosi cuma bakal keluarin kata-kata nyakitin. *Time-out* sebentar buat napas itu jalan ninja terbaik sebelum diskusi.'
    },
    {
        category: '💖 Percintaan Sehat & Logis (Green Flag)',
        question: 'Bagaimana cara menghadapi rasa cemburu yang "elegan" tanpa terlihat seperti orang gila posesif?',
        options: ['Komunikasikan jujur rasa tidak nyamanmu ke pasangan secara baik-baik tanpa menuduh', 'Bikin *Fake Account* Instagram buat neror orang yang dicemburui', 'Balas dendam dengan cara sengaja jalan sama lawan jenis lain biar pacar ikutan panas', 'Menyindir lewat lirik lagu *Ambyar* di Story'],
        correct: 0,
        explanation: 'Cemburu itu manusiawi, tapi cara menyampaikannya menentukan kadar kedewasaanmu. Bilang "Aku jujur agak kurang nyaman nih," jauh lebih elegan daripada perang dingin berminggu-minggu.'
    },
    {
        category: '💖 Percintaan Sehat & Logis (Green Flag)',
        question: 'Realita paling pahit yang harus diterima oleh semua pasangan ketika hubungan sudah berjalan bertahun-tahun (Long-term Relationship) adalah...',
        options: ['Hati akan terus deg-degan kayak naik *rollercoaster* setiap hari', 'Tidak akan pernah ada pertengkaran sama sekali', 'Fase "Bosan" itu sangat wajar, dan cinta sejati adalah memilih untuk bertahan dan berkomitmen setiap hari', 'Wajah pasangan akan makin mirip dengan kita'],
        correct: 2,
        explanation: 'Ekspektasi cinta ala Disney itu palsu. Di dunia nyata, bakal ada fase di mana kamu bosan. Kedewasaan diuji saat kamu sadar bahwa cinta adalah soal komitmen, bukan sekadar perasaan *butterflies* (deg-degan) sesaat.'
    },
    {
        category: '💖 Percintaan Sehat & Logis (Green Flag)',
        question: 'Sikap paling berkelas ketika orang yang kamu PDKT-in bilang, "Maaf ya, aku rasa kita lebih cocok jadi teman"?',
        options: ['Tiba-tiba ngatain dia jelek di tongkrongan (padahal kemaren dipuji-puji setinggi langit)', 'Nge-spam *chat*: "Kurangku apa?! Aku bisa berubah!"', 'Tersenyum, menghargai keputusannya, berterima kasih atas waktunya, lalu mundur teratur', 'Nyari dukun pelet yang lagi *flash sale*'],
        correct: 2,
        explanation: 'Ditolak memang sakit, tapi mundur dengan *gentleman*/*classy* akan membuat harga dirimu tetap utuh. Nggak semua orang yang kita suka harus suka balik ke kita.'
    },
    {
        category: '💖 Percintaan Sehat & Logis (Green Flag)',
        question: 'Dalam hubungan jarak jauh (LDR) yang sehat, apa kunci utama agar hubungan tidak hancur lebur oleh rasa *overthinking*?',
        options: ['Memaksa pacar untuk *share location* 24 jam *real-time*', 'Saling percaya, punya kesibukan masing-masing, dan *update* secukupnya tanpa mengekang', 'Memasang CCTV di kamar pacar', 'Harus teleponan 12 jam sehari sampai kuping panas'],
        correct: 1,
        explanation: 'Hubungan LDR yang *survive* adalah hubungan milik dua orang yang mandiri, sibuk, dan saling percaya. Mengekang cuma bikin pacarmu merasa pacaran dengan satpam kompleks.'
    },
    {
        category: '💖 Percintaan Sehat & Logis (Green Flag)',
        question: 'Cara *Move On* dari mantan yang paling sehat, rasional, dan dijamin *works 100%* oleh para psikolog adalah...',
        options: ['Mencari pelampiasan (Rebound) dalam waktu 24 jam', 'Mengurung diri di kamar sampai kiamat tiba', 'Menerima kesedihannya (*acceptance*), beri waktu untuk *grieving* (menangis), lalu pelan-pelan sibukkan diri untuk *upgrade* *value* dirimu', 'Sengaja *posting* pamer kebahagiaan palsu biar mantan kepanasan'],
        correct: 2,
        explanation: 'Menangis dan hancur pasca-putus itu legal. Nggak usah pura-pura kuat atau buru-buru cari pelampiasan. Nikmati sedihnya sampai habis, setelah itu bangkit jadi versi dirimu yang jauh lebih keren!'
    },

    // --- 17. KELAKUAN AJAIB WNI ---
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Dalam kasta ilmu medis WNI, apa prosedur penanganan pertama dan paling absolut untuk segala jenis penyakit dari pusing, pegal, sampai patah hati?',
        options: ['Langsung UGD', 'Operasi bedah sesar', 'Kerokan pakai koin seribuan dan balsem sampai punggungnya mirip zebra cross', 'Minum obat resep dokter'],
        correct: 2,
        explanation: 'Dokter lulusan Harvard pun akan menyerah menghadapi WNI yang percaya bahwa warna merah kehitaman pada kerokan adalah bukti otentik "angin" keluar dari tubuh.'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Misteri terbesar saat Lebaran: Apa isi sebenarnya dari kaleng biskuit Khong Guan yang tergeletak di meja ruang tamu?',
        options: ['Biskuit Khong Guan asli rasa vanilla', 'Emas batangan peninggalan VOC', 'Rengginang, kembang goyang, atau kerupuk seblak', 'Surat wasiat rahasia'],
        correct: 2,
        explanation: 'Kaleng Khong Guan adalah *prank* tertua di Indonesia. Ekspektasi makan biskuit elit, realita mengunyah rengginang sisa bulan lalu yang udah sedikit alot.'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Menurut standar gizi WNI, semangkuk Indomie Goreng yang dimasak pakai telur, sosis, kornet, dan sayur itu belum bisa disebut "Makan" jika...',
        options: ['Belum di-post di Instagram', 'Belum dicampur dengan dua centong Nasi Putih anget', 'Mienya belum diaduk', 'Belum dikasih saos sambal botolan'],
        correct: 1,
        explanation: 'Agama apapun di Indonesia sepakat: Mau karbohidratnya setinggi gunung, kalau belum nyentuh Nasi Putih, itu statusnya masih "Ngemil".'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Siapakah entitas gaib di Indonesia yang saat kita datang tidak ada wujudnya, tapi saat kita mau memundurkan motor, ia tiba-tiba *spawn* meniup peluit?',
        options: ['Tukang parkir minimarket', 'Malaikat Izrail', 'Ninja Konoha', 'Intel menyamar'],
        correct: 0,
        explanation: 'Tukang parkir minimarket punya jurus teleportasi tingkat dewa. Pas belanja mereka menghilang, pas motor udah nyala, tahu-tahu narik jok motor belakang sambil teriak "Terus, trus, yak balas!"'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Apa arti dari kode sein kiri yang dinyalakan oleh emak-emak yang sedang mengendarai motor matic di jalan raya?',
        options: ['Dia akan belok ke kiri secara perlahan', 'Dia akan belok ke kanan, lurus terus, putar balik, atau terbang. Hanya Tuhan dan dia yang tahu', 'Dia sedang nge-test lampu sein', 'Dia mau berhenti beli sayur'],
        correct: 1,
        explanation: 'Emak-emak bawa motor matic adalah penguasa jalan raya sejati. Sein kiri belok kanan adalah teknik *mind-game* untuk mengecoh pengendara di belakangnya.'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Metode perbaikan barang elektronik paling primitif yang selalu dilakukan bapak-bapak WNI saat remote TV tidak bisa dipencet adalah...',
        options: ['Langsung beli baru di toko elektronik', 'Mengganti baterainya dengan merek Alkaline', 'Remote-nya dipukul-pukul ke telapak tangan atau baterainya digigit sedikit biar nyala lagi', 'Diservice ke tukang TV'],
        correct: 2,
        explanation: 'Ilmu fisika tidak bisa menjelaskan kenapa memukul remote TV ke telapak tangan bisa memperlancar aliran listrik baterai yang udah mati.'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Siklus daur ulang baju paling *sustainable* (ramah lingkungan) di setiap keluarga Indonesia adalah...',
        options: ['Baju pergi -> Baju tidur -> Kain pel/keset', 'Baju pergi -> Langsung disumbangkan', 'Baju baru -> Dimasukkan lemari sampai berjamur', 'Baju baru -> Dijual lagi di Carousell'],
        correct: 0,
        explanation: 'Di Indonesia, sebuah kaos distro tidak akan pensiun sampai ia diinjak-injak di depan pintu kamar mandi sebagai keset.'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Apa makna sebenarnya dari kata "OTW (On The Way)" jika diucapkan oleh teman setongkronganmu?',
        options: ['Sedang di jalan dan hampir sampai', 'Masih di kasur, baru mau bangun, dan belum mandi', 'Sedang memanaskan mesin motor', 'Sudah ada di parkiran'],
        correct: 1,
        explanation: 'Kamus Bahasa Tongkrongan: OTW = Oke Tunggu Wae (masih rebahan). Kalau bilang "Udah di depan nih" = Baru pakai sepatu.'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Apa fungsi dari "Plastik Kresek Gede" yang selalu digantung di dapur atau di belakang pintu oleh ibu-ibu WNI?',
        options: ['Untuk menyimpan emas batangan', 'Sebagai wadah sampah organik', 'Sebagai "Induk Kresek" yang di dalamnya berisi ratusan plastik kresek lain dari berbagai ukuran hasil belanja bertahun-tahun', 'Untuk parasut kalau ada gempa'],
        correct: 2,
        explanation: 'Ini adalah fenomena "Kresekception". WNI menolak membuang kresek bekas Indomaret, akhirnya dikumpulkan dalam satu kresek raksasa sampai beranak pinak.'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Apa *trigger* (pemicu) yang membuat jalan raya atau kolong jembatan (*underpass*) tiba-tiba macet total tak bergerak saat musim hujan?',
        options: ['Ada razia gabungan polisi', 'Ada ratusan pemotor yang berteduh di bawah jembatan sambil nutupin 90% jalan, padahal udah bawa jas hujan', 'Ada konser dadakan', 'Ada syuting film Fast & Furious'],
        correct: 1,
        explanation: 'Pemotor WNI itu aneh. Udah beli jas hujan Rp 100 ribu, tapi milih berteduh di kolong jembatan nutupin jalan orang. Jas hujannya cuma disimpen di jok biar nggak kebasahan.'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Sistem pengamanan anti-maling tingkat tinggi apa yang diaplikasikan pada Sandal Jepit Swallow saat dibawa Jumatan ke Masjid?',
        options: ['Dilengkapi GPS *Tracker*', 'Diukir pakai paku panas, ditandai spidol permanen, atau sengaja ditukar kiri-kanan beda warna', 'Dijaga oleh anjing pelacak', 'Disimpan di loker bank Swiss'],
        correct: 1,
        explanation: 'Sandal Swallow hijau itu harganya cuma 15 ribu, tapi pas hilang pasca-Jumatan, rasa sakit hatinya setara kehilangan motor CBR.'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Jika kamu tersesat di kampung dan bertanya arah ke warga lokal, panduan arah apa yang 90% pasti menyesatkan?',
        options: ['"Ikuti Google Maps mas, akurat kok"', '"Lurus aja mas, mentok belok kanan, deket kok dari sini tinggal ngesot" (Padahal aslinya masih 10 Kilometer lewatin 3 gunung)', '"Wah saya kurang tau mas, saya bukan orang sini"', '"Coba telpon polisi mas"'],
        correct: 1,
        explanation: 'Definisi "Deket kok" bagi warga lokal adalah standar jarak tempuh pelari maraton. Buat orang kota, itu jarak yang bisa bikin tipes.'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Reaksi otomatis WNI saat melihat kecelakaan lalu lintas atau orang berantem di pinggir jalan raya adalah...',
        options: ['Langsung menelpon ambulans dan polisi', 'Minggir dengan rapi untuk membantu korban', 'Berhenti di tengah jalan, nontonin pelan-pelan tanpa bantu, bikin macet panjang, lalu memvideokan untuk masuk grup WA', 'Pura-pura tidak melihat karena sibuk'],
        correct: 2,
        explanation: 'WNI punya jiwa *Jurnalisme Warga* tingkat dewa. Naluri pertama bukan nolongin korban, tapi ngeluarin *smartphone* sambil teriak "Ya Allah, Ya Allah!" direkam potrait.'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Ritual wajib anak kecil WNI era 90-an hingga 2000-an ketika melihat pesawat terbang melintas di atas rumah adalah...',
        options: ['Melakukan hormat militer', 'Berteriak "Pesawat, minta uang!" sambil dadah-dadah ke langit', 'Menghitung jumlah baling-balingnya', 'Membaca doa selamat'],
        correct: 1,
        explanation: 'Mitos paling absurd: anak-anak percaya kalau mereka teriak minta uang ke pesawat penumpang di ketinggian 30.000 kaki, pilotnya bakal lempar koin ke atap rumah mereka.'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Bukti kekuasaan tertinggi di sistem sosial kasta WNI, di mana mereka bisa menutup jalan raya kabupaten, mengalihkan rute angkot, dan mendirikan tenda biru raksasa adalah saat...',
        options: ['Presiden lewat', 'Lagi perbaikan aspal jalan', 'Warga lagi gelar acara Hajatan Nikahan/Sunatan', 'Ada serangan Godzilla'],
        correct: 2,
        explanation: 'Bodo amat sama jalan nasional. Kalau anak pak RT lagi sunatan, tenda biru dengan panggung dangdut akan mengambil alih jalan raya. Pengendara silakan putar balik lewat hutan.'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Indikator utama bahwa seseorang adalah ibu-ibu WNI yang gak mau rugi saat beli barang elektronik atau kasur baru adalah...',
        options: ['Disimpan di dalam brankas', 'Plastik pelindung dari pabriknya TIDAK PERNAH DICOPOT sampai bertahun-tahun, bahkan sampai plastiknya kuning dan robek sendiri', 'Langsung dijual lagi', 'Disembah setiap pagi'],
        correct: 1,
        explanation: 'TV baru, sofa baru, kasur baru—selama plastiknya masih nempel, barang itu akan selalu terasa "baru" dan "sayang kalau kotor", walau didudukinya jadi panas bunyinya kresek-kresek.'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Apa sebutan universal kearifan lokal untuk memanggil Bule (Orang Kaukasia) di jalanan tempat wisata, tidak peduli apakah bulenya laki-laki atau perempuan?',
        options: ['"Excuse me, Sir/Madam!"', '"Hello Bule!"', '"Hello, Mister!"', '"Woi, Londho!"'],
        correct: 2,
        explanation: '"Mister" adalah kata ganti netral gender versi kearifan lokal. Turis cewek pirang pakai bikini pun akan tetap disapa, "Hello Mister, want massage?" oleh ibu-ibu pantai.'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Parfum kebangsaan, wewangian aromaterapi, sekaligus obat penenang jiwa raga bagi sebagian besar WNI saat sedang *traveling* atau naik bis ber-AC adalah...',
        options: ['Parfum Baccarat Rouge', 'Minyak Telon, Minyak Kayu Putih, atau Balsem Geliga', 'Kopi hitam', 'Lotion nyamuk Autan'],
        correct: 1,
        explanation: 'Begitu AC bis malam dinyalakan, aroma *eau de* Kayu Putih akan langsung menguasai seluruh kabin, menandakan ada penumpang yang bentar lagi mau mabuk darat.'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Alarm horor paling menakutkan yang bisa bikin bapak-bapak loncat dari kasur dan lari keluar rumah di tengah malam adalah...',
        options: ['Suara harimau mengaum', 'Suara hantu Kuntilanak tertawa', 'Suara meteran listrik token PLN bunyi "Tit.. Tit.. Tit.." tanda kuota mau habis', 'Suara knalpot brong anak racing'],
        correct: 2,
        explanation: 'Bunyi token listrik PLN adalah *jumpscare* nyata di Indonesia. Apalagi kalau bunyinya jam 1 pagi saat gerai minimarket udah pada tutup.'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Cara paling efektif untuk memanfaatkan sisa sampo di botol yang sudah mau habis (sudah dipencet gak keluar) ala WNI adalah...',
        options: ['Dibuang ke tempat sampah', 'Botolnya digunting jadi dua untuk dikerok isinya pakai tangan', 'Diisi air sedikit, dikocok-kocok, lalu diguyur ke kepala busanya yang encer', 'Dijual ke tukang rongsok'],
        correct: 2,
        explanation: 'WNI pantang membuang botol sampo sebelum dibilas pakai air kocokan. Itu adalah upaya pemerasan maksimal kaum mendang-mending.'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Budaya pengemasan minuman *take away* (bawa pulang) paling *iconic* khas warkop dan warteg Indonesia, di mana minuman tidak pakai *cup* plastik estetik melainkan...',
        options: ['Pakai botol kaca beling yang disita kalau dibawa', 'Dimasukkan ke dalam plastik bening sekilo, diikat karet gelang sebelah pinggir, dan ditusuk sedotan', 'Dimasukkan ke dalam termos es', 'Dibungkus pakai daun pisang'],
        correct: 1,
        explanation: 'Es teh manis di dalam plastik es kiloan yang ditali karet gantung adalah *tumbler* sejati rakyat Indonesia. Gampang dicantelin di stang motor, dan murah meriah.'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Ketika sepasang pengantin baru membuka kado pernikahan dari teman-teman kantornya, benda *mainstream* apa yang dipastikan ada hingga mencapai 10 kotak?',
        options: ['Voucher liburan ke Bali', 'Perhiasan emas', 'Sprei bedcover, selimut, atau set cangkir beling', 'Saham BBCA'],
        correct: 2,
        explanation: 'Tradisi kado nikah Indonesia: Sprei gambar bunga atau selimut tebal. Saking banyaknya, kado itu akhirnya nggak dipakai tapi disimpan buat di-kadoin lagi ke nikahan orang lain.'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Apa jurus membalas dendam paling memuaskan saat ada nyamuk nakal yang berhasil ditangkap hidup-hidup pakai tangan kosong?',
        options: ['Dilepasin ke alam liar', 'Dikasih makan darah sapi', 'Sayap atau kakinya dicabutin satu-satu, lalu dibiarin jalan kaki di atas kasur', 'Dibawa ke kantor polisi'],
        correct: 2,
        explanation: 'WNI kalau punya dendam sama nyamuk itu psikopat abis. Kalau dapet nyamuk utuh, bukannya langsung dipites, malah dimutilasi kakinya lalu disuruh *cosplay* jadi semut.'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Jika seseorang mengeluh "Gue kena asam lambung nih", probabilitas penyakit yang sebenarnya sedang dia alami menurut ilmu medis adalah...',
        options: ['GERD kronis yang harus dioperasi', 'Kanker pencernaan', 'Cuma mual biasa karena kebanyakan minum kopi susu gula aren tapi telat makan nasi', 'Usus buntu'],
        correct: 2,
        explanation: 'Kata "Asam Lambung" udah jadi istilah kekinian buat Gen Z dan Milenial WNI yang aslinya cuma masuk angin atau mual gara-gara sok-sokan minum kopi pahit padahal perut kosong.'
    },
    {
        category: '🇮🇩 Kelakuan Ajaib WNI',
        question: 'Kenapa orang Indonesia rata-rata bisa kebal dari penyakit tipes atau kolera meski sering jajan di pinggir jalan tol yang berdebu?',
        options: ['Karena sudah divaksin dari lahir', 'Karena debu knalpot angkot, minyak goreng hitam yang dipakai 20 kali, dan saus sambal curah telah membentuk antibodi mutan di dalam usus', 'Karena pedagangnya higienis pakai *hairnet*', 'Karena porsi makannya sedikit'],
        correct: 1,
        explanation: 'Sistem pencernaan WNI telah dilatih dengan keras oleh abang cilok, abang batagor, dan minyak goreng yang warnanya udah sehitam oli bekas. Virus dan bakteri pun auto sungkem.'
    },

    // --- 18. EKSKUL MULTIMEDIA SMANIT ---
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Apa niat terselubung paling umum dari sebagian besar siswa cowok saat memutuskan mendaftar Ekskul Multimedia di SMANIT?',
        options: ['Ingin mendalami ilmu sinematografi ala Christopher Nolan', 'Ingin memajukan industri perfilman Indonesia', 'Biar bisa gaya-gayaan bawa kamera DSLR keliling sekolah buat *hunting* adik kelas incaran', 'Biar bisa diangkat jadi sutradara Hollywood'],
        correct: 2,
        explanation: 'Faktanya, kalungin *strap* kamera DSLR Canon/Nikon sekolah di leher adalah *cheat code* paling instan untuk menaikkan level ketampanan 50% di mata adik kelas saat jam istirahat.'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Barang inventaris gaib apakah milik Ekskul Multimedia SMANIT yang selalu hilang entah ke mana setelah dipakai liputan acara sekolah?',
        options: ['Kamera DSLR-nya', 'Tutup lensa (*Lens Cap*) dan satu buah baut *plate* Tripod', 'Laptop buat ngedit', 'Lampu kilat (*Flash*)'],
        correct: 1,
        explanation: 'Tutup lensa dan baut tripod adalah barang yang punya kaki. Baru ditaruh 5 detik di kantong, pas dicari udah pindah dimensi ke alam gaib.'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Ketika Liputan acara pensi atau *Class Meeting* SMANIT, apa keistimewaan mutlak dari kartu "ID CARD PDD/Dokumentasi" yang dikalungkan di leher anak Multimedia?',
        options: ['Dapat jatah makan KFC gratis', 'Kartu sakti (*Golden Ticket*) buat bebas keluar-masuk kelas, nongkrong di kantin, atau bolos pelajaran dengan alasan "Lagi ngambil *footage* Pak!"', 'Bisa ditukar dengan nilai rapot A', 'Dapat diskon SPP bulanan'],
        correct: 1,
        explanation: 'ID Card Panitia Dokumentasi adalah kasta tertinggi kemerdekaan siswa. Mau jalan-jalan keliling sekolah pas jam Matematika pun aman, asal bawa kamera (walau kameranya *off*).'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Pertanyaan paling memancing keributan yang sering dilontarkan bapak/ibu guru atau panitia OSIS HANYA 10 menit setelah acara sekolah selesai adalah...',
        options: ['"Mas, kameranya udah dimatiin belum?"', '"Mas, videonya kapan tayang di bioskop?"', '"Mas, hasil fotonya udah bisa dikirim ke grup WA sekarang kan? Videonya udah jadi belum?"', '"Mas, mau makan apa?"'],
        correct: 2,
        explanation: 'Dikira proses *mindahin* data ber-Giga-giga, milihin foto yang gak merem, ngedit warna, dan *render* video itu prosesnya bisa dilakukan pakai ilmu sihir Bandung Bondowoso dalam semalam.'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Penyakit abadi dan tak tersembuhkan dari komputer Lab atau laptop inventaris sekolah saat anak Multimedia nekat membuka *Adobe After Effects* adalah...',
        options: ['Layar tiba-tiba berubah jadi layar tancap', 'Kursor *loading* muter-muter tanpa henti (*Blue Screen of Death*) diiringi suara kipas CPU yang menderu kayak pesawat mau *take-off*', 'Mouse-nya tiba-tiba bisa jalan sendiri', 'Tiba-tiba ngeluarin uang kembalian'],
        correct: 1,
        explanation: 'Nekat buka *software* dewa di PC sekolah yang RAM-nya cuma 4GB adalah bentuk penyiksaan *hardware*. Sekali klik, langsung *Not Responding* sampai jam pulang sekolah.'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Apa isi dari 90% memori SD Card kamera sekolah yang sering bikin editor emosi saat *import data*?',
        options: ['Footage *cinematic* yang sangat estetik', 'Wawancara eksklusif Kepala Sekolah', '80% isinya foto *selfie* atau gaya *peace* anggota panitia lain yang minjem kamera diem-diem', 'Film dokumenter alam liar'],
        correct: 2,
        explanation: 'Kamera dokumentasi sekolah itu hak milik bersama. Pas dicek buat ngedit *after movie*, isinya malah penuh *selfie* alay teman-teman tongkrongan yang nge-bajak kamera.'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Saat anak Ekskul Multimedia SMANIT membikin video profil epik "THE MAGIC OF SMANIT", adegan (*B-Roll*) apa yang wajib dimasukin biar kerasa unsur sinematiknya?',
        options: ['Video murid lagi ujian matematika sambil nangis', 'Rekaman tukang cilok depan sekolah', 'Adegan daun gugur di-*slow motion* 120fps, *shoot* sepatu dari bawah, dan *shoot* lorong sekolah yang di-stabilizer', 'Video guru lagi senam pinguin'],
        correct: 2,
        explanation: 'Tidak ada video sekolahan yang sah tanpa adegan *slow-mo* kaki orang berjalan atau daun yang tertiup angin di depan kelas. Itu adalah hukum fisika *cinematic* kearifan lokal.'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Mitos paling menakutkan saat meminjam *Flashdisk* dari guru untuk memindahkan file foto kegiatan adalah...',
        options: ['Flashdisk-nya bisa meledak', 'Flashdisk-nya akan menyedot kuota internet', 'Colok 5 detik, laptop editor auto terinfeksi Virus *Shortcut* yang mengubah semua folder jadi *file .exe*', 'Flashdisk-nya akan berubah jadi es batu'],
        correct: 2,
        explanation: 'Flashdisk guru adalah sarang berkumpulnya virus *Trojan* dan *Shortcut* paling mutakhir dari zaman Majapahit. Sekali colok, tamatlah riwayat file skripsi dan data berhargamu.'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Kelakuan "Sok Pro" paling memalukan yang pernah dilakukan anggota baru (Junior) saat disuruh motoin upacara pakai kamera DSLR/Mirrorless adalah...',
        options: ['Sengaja pakai *Flash* padahal matahari lagi terik banget (jam 12 siang)', 'Mengganti lensa dengan lensa mikroskop', 'Gaya jongkok-jongkok muter lensa fokus, *cekrek-cekrek* banyak gaya, padahal tutup lensanya belum dibuka', 'Memfoto menggunakan telepati'],
        correct: 2,
        explanation: 'Akting memutar ring fokus dengan alis berkerut adalah wajib biar dikira fotografer profesional. Masalahnya, layar monitor hitam gelap karena *lens cap* masih nempel.'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Apa pembagian tugas (Jobdesk) paling realita saat divisi Multimedia SMANIT harus mengedit Video Dokumenter atau *After Movie*?',
        options: ['5 orang mengedit secara bergantian dengan *shift* yang adil', '1 orang pusing nyari lagu, 1 orang ngerjain efek, 3 orang bantuin *color grading*', '1 orang editor menderita di depan laptop sampai tipes, 7 orang sisanya duduk di belakang ngeliatin sambil nyemil gorengan dan komentar "Keren cuy!"', 'Semua orang *resign*'],
        correct: 2,
        explanation: 'Di dunia ekstrakurikuler, "Ngedit Bareng" artinya satu orang tumbal *overtime* nahan encok, sementara yang lain fungsinya cuma sebagai penonton bayaran penyemangat moral.'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Saat menjadi Pilot Drone untuk memetakan lapangan sekolah atau acara Agustusan, apa ketakutan terbesar sang Pilot selain kehilangan sinyal?',
        options: ['Drone-nya diculik alien', 'Dikejar-kejar anjing kampung atau drone-nya nyangkut di tiang bendera / pohon beringin keramat sekolah', 'Drone-nya mogok minta ganti oli', 'Baling-balingnya berubah jadi kipas sate'],
        correct: 1,
        explanation: 'Nyawa seorang pilot drone lokal selalu terancam oleh dua hal: anak kecil yang ngelemparin batu karena dikira burung ajaib, atau pohon beringin lebat yang siap menelan *drone* tanpa sisa.'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Berapa bayaran / *fee* profesional yang biasanya diterima oleh anak Multimedia SMANIT setelah sukses membuat mahakarya film profil sekolah yang durasinya 15 menit?',
        options: ['Rp 5 Juta Tunai + Royalti', 'Sertifikat penghargaan dari Oscar', 'Ucapan "Makasih ya, ini buat pengalaman dan amal jariyah", plus nasi kotak sisa prasmanan yang lauknya tinggal tahu tempe', 'Beasiswa S1 Sinematografi'],
        correct: 2,
        explanation: 'Bayaran tertinggi di ranah dokumentasi sekolah adalah pahala. Kalau beruntung, bisa dapet es teh manis dan ayam bakar yang udah dingin di ruang panitia.'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Gaya andalan bapak/ibu guru saat difoto secara *candid* (diam-diam) oleh anak dokumentasi yang akhirnya malah terkesan sangat kaku adalah...',
        options: ['Gaya *Peace* (✌️) sambil merem', 'Berdiri tegak lurus mengacungkan jempol ke depan dengan senyum *template* pas foto KTP', 'Gaya kayang di tengah lapangan', 'Gaya ala model *Vogue*'],
        correct: 1,
        explanation: 'Apapun situasinya, sedramatis apapun *angle* kemeranya, *pose* jempol andalan bapak-bapak guru adalah *barrier* yang tidak bisa ditembus oleh nilai seni fotografer mana pun.'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Di dalam *circle* Ekskul Multimedia, kasta terendah dalam perdebatan *software editing* biasanya dihuni oleh mereka yang...',
        options: ['Mengedit pakai Adobe Premiere Pro orisinil', 'Mengedit video *After Movie* durasi 5 menit MENGGUNAKAN CAPCUT DI HP sambil dicolok ke *powerbank*', 'Pakai DaVinci Resolve', 'Pakai Final Cut Pro'],
        correct: 1,
        explanation: 'Di saat editor PC sombong dengan *shortcut* Premiere-nya, sang pengguna CapCut HP tersenyum miring karena cuma butuh 10 menit untuk *export* video dengan transisi *template* instan.'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Tantangan logistik tersulit saat memfoto bareng (*Group Photo*) per-kelas untuk buku tahunan (BTS) adalah...',
        options: ['Ngelobi Kepala Sekolah biar ngasih izin', 'Nyari *setting* *shutter speed* yang pas', 'Nungguin 35 anak satu kelas ngumpul semua, nggak ada yang kedip pas difoto, dan ngatur siswa yang cowok biar gak baris di belakang gaya nutupin muka', 'Mencari lokasi di Mars'],
        correct: 2,
        explanation: 'Memfoto 35 remaja labil itu butuh kesabaran nabi. Selalu ada satu cowok yang sengaja nutupin muka pakai jaket, dan satu cewek yang minta di-foto ulang gara-gara poninya lepek.'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Ketika hasil foto dan *video grading* udah dibikin sangat sinematik dengan *tone* hangat (Warm Golden Hour), apa keluhan paling klasik dari siswa yang di-foto?',
        options: ['"Wah *tone* warnanya *film-look* banget!"', '"Warnanya terlalu *retro*, mas."', '"Kok muka aku jadi kelihatan kuning-kuning gelap gitu sih? Tolong editin dibikin putih terang dong kayak pakai filter Instagram!"', '"Keren banget, *shadow*-nya *deep*!"'],
        correct: 2,
        explanation: 'Mata orang awam tidak peduli dengan seni *color grading*. Bagi mereka, foto bagus adalah foto di mana kulit wajah mereka diedit seterang lampu neon Philips 10 Watt.'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Tradisi pamer (*flexing*) paling hakiki dari anggota inti Ekskul Multimedia yang menjabat sebagai PDD (Publikasi, Dekorasi, Dokumentasi) di sekolah adalah...',
        options: ['Memamerkan nilai ujian Fisika', 'Selalu pakai Pakaian Dinas Harian (PDH) / Korsa Multimedia atau Kaos Panitia ke mana-mana padahal cuaca lagi panas 35 derajat celcius', 'Membawa buku tebal ke kantin', 'Ngomong pakai bahasa Inggris *British*'],
        correct: 1,
        explanation: 'PDH Ekskul atau kaos kepanitiaan bagian Dokumentasi adalah simbol kasta bangsawan di sekolah. Panas dan keringat tak jadi masalah demi validasi eksistensi diri di mata *crush*.'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Font wajib "Sejuta Umat" yang hampir selalu dipakai oleh anak *design graphic* pemula di SMANIT untuk bikin *flyer*, poster, dan *thumbnail* YouTube adalah...',
        options: ['Times New Roman', 'Bebas Neue atau Montserrat', 'Comic Sans MS', 'Wingdings'],
        correct: 1,
        explanation: 'Bebas Neue buat judul biar keliatan tegas dan *bold*, Montserrat buat isi teks biar kelihatan modern. Kalau ada anak desain yang pakai Comic Sans untuk poster resmi sekolah, ia akan dikucilkan dari pergaulan.'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Apa fungsi dari stiker "*Press* / *Documentation*" yang ditempel di kamera atau ID Card panitia?',
        options: ['Agar kameranya tahan air', 'Buat menangkal sihir santet', 'Alibi paling kuat untuk bisa menerobos barisan depan penonton saat ada band *guest star* manggung, walau aslinya cuma ikut jingkrak-jingkrak', 'Sebagai syarat ikut Ujian Nasional'],
        correct: 2,
        explanation: 'Stiker "Dokumentasi" adalah tiket VVIP. Bisa masuk barikade depan panggung dengan alasan "Mau ambil *angle* bagus", padahal kameranya udah dimatiin dan ikutan konser di barisan terdepan.'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Momen "Jantung Turun ke Ginjal" yang dirasakan sang pengurus Data (Tukang Backup) Ekskul Multimedia adalah ketika...',
        options: ['Lupa bawa uang saku', 'Tiba-tiba *Hardisk* Eksternal minta di-"Format Disk" begitu dicolok ke laptop, padahal file foto *Event* 3 hari berturut-turut ada di situ semua', 'Pacar minta putus', 'Lupa naruh kunci motor'],
        correct: 1,
        explanation: 'Layar *popup* "You need to format the disk in drive E: before you can use it" adalah kiamat kecil bagi seorang penyimpan data dokumentasi. Rasanya pengen langsung pindah warga negara.'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Ciri-ciri hasil karya adik kelas/anggota junior Ekskul Multimedia yang baru pertama kali pegang *Premiere Pro* adalah...',
        options: ['Transisi halus yang tidak terlihat', 'Setiap pergantian klip dipenuhi dengan transisi *Wipe, Cross Zoom, Glitch,* dan layar kedap-kedip bergetar hebat sampai bikin penontonnya epilepsi', 'Audio *mixing* yang standar internasional', 'Warna hitam putih tanpa suara'],
        correct: 1,
        explanation: 'Sindrom editor pemula: Kalau semua efek dan transisi yang ada di panel *Effects* nggak dicobain semua dalam satu video, rasanya kurang afdal dan kurang "Keren".'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Kalimat pamungkas untuk menghindari tagihan janji dari teman-teman yang maksa minta hasil foto candid-nya segera dikirim adalah...',
        options: ['"Kameranya lagi disita guru."', '"Maaf ya, semalam file-nya *corrupt* kena virus (padahal aslinya males ngedit/milih foto karena mukanya jelek semua)."', '"Kameranya meledak."', '"Aku amnesia."'],
        correct: 1,
        explanation: '"File-nya *corrupt*" adalah tameng terkuat dari tukang foto untuk lari dari tanggung jawab ngirimin ratusan foto narsis temen-temen kelas yang minta dikirim via *Google Drive*.'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Ketimpangan sosial tertinggi di dalam tim Multimedia SMANIT saat bertugas meliput acara di luar sekolah (misalnya acara Desa Cibanteng atau Pasir Eurih) adalah...',
        options: ['Beda menu makanan', 'Satu orang bertugas jadi Pilot Drone keren sambil pakai kacamata hitam di tengah lapangan, sementara temannya disuruh bawain tas ransel peralatan berat kayak kuli panggul', 'Gaji yang beda jauh', 'Beda warna seragam'],
        correct: 1,
        explanation: 'Sang pilot drone akan terlihat seperti agen FBI yang sedang menjalankan misi rahasia. Sementara asistennya yang bawa tas drone dan baterai cadangan lebih terlihat seperti mau jualan asongan.'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Apa indikator paling kuat bahwa sebuah video tugas sekolah / ujian praktek itu 100% dikerjakan (dijokiin) oleh anak Ekskul Multimedia?',
        options: ['Durasinya cuma 5 detik', 'Ada *Opening Logo* Universal Studio 3D palsu, *Cinematic Black Bars* (Garis hitam atas bawah), dan *Backsound* Lo-Fi yang terlalu estetik buat tugas PPKn', 'Format videonya 3gp buram', 'Dibikin pakai *Stop Motion* tanah liat'],
        correct: 1,
        explanation: 'Anak Multimedia tidak bisa membuat video tugas sekolah dengan gaya normal. Mereka akan mengubah tugas Sejarah atau PPKn menjadi film layar lebar dengan sinematografi kelas festival.'
    },
    {
        category: '🎥 Ekskul Multimedia SMANIT',
        question: 'Setelah lulus dari SMANIT, takdir yang umumnya menanti para anggota inti Ekskul Multimedia yang udah jago *Software Development* atau *Video Editing* adalah...',
        options: ['Langsung direkrut jadi *Lead Editor* Marvel Studios', 'Dijadikan tempat reparasi *Printer* macet, HP *bootloop*, dan kang edit *video wedding* dadakan oleh seluruh keluarga besarnya secara gratis', 'Berubah jadi *hacker* kelas dunia', 'Membuka praktek dukun IT'],
        correct: 1,
        explanation: 'Ini adalah kutukan anak IT dan Multimedia. Punya sertifikat BNSP atau jago main *After Effects* tetap tidak ada artinya di mata keluarga besar. Gelar sejatimu adalah: "Tukang Servis *Printer* dan Pemulih Akun FB Lupa *Password*".'
    },

    // --- 13. IT & PENDERITAAN TEKNOLOGI ---
    {
        category: '💻 IT & Penderitaan Teknologi',
        question: 'Metode perbaikan (*troubleshooting*) tingkat dewa yang selalu diteriakkan oleh divisi IT Support kantoran ketika ada karyawan yang mengeluh printernya macet adalah...',
        options: ['"Coba bongkar *motherboard*-nya pak!"', '"Udah di-restart belum komputernya, pak/bu?"', '"Bapak harus ganti IP Address sekarang juga!"', '"Wah, itu printernya kena kutukan *Ransomware* pak."'],
        correct: 1,
        explanation: 'Kalimat "Udah di-restart belum?" adalah mantra suci IT Support. Ajaibnya, 90% masalah elektronik di muka bumi memang selesai cuma dengan jurus matiin-lalu-nyalain-lagi.'
    },
    {
        category: '💻 IT & Penderitaan Teknologi',
        question: 'Kombinasi *password* paling "Aman Sejagad Raya" (menurut bapak-bapak) yang selalu dipakai untuk mengamankan akun Facebook atau M-Banking mereka adalah...',
        options: ['Kombinasi huruf besar, kecil, angka, dan simbol kuno Mesir', 'Algoritma enkripsi RSA 2048-bit', 'Tanggal lahir sendiri digabung sama nama anak pertama (contoh: budi1975)', 'Menggunakan sidik jari kaki'],
        correct: 2,
        explanation: 'Nama anak + tanggal lahir adalah sistem keamanan siber kearifan lokal. Sekali di-*hack*, yang disalahin pasti *hacker* Rusia, padahal *password*-nya gampang ditebak satpam kompleks.'
    },
    {
        category: '💻 IT & Penderitaan Teknologi',
        question: 'Apa indikator utama dari keluarga besar yang bikin kamu langsung dicap sebagai "Hacker Profesional sekelas Bjorka"?',
        options: ['Kamu bisa meretas server Pentagon 5 menit', 'Kamu berhasil benerin TV yang remotnya gak nyala, atau berhasil ganti *password* WiFi rumah', 'Kamu punya sertifikat *Cyber Security* dari Google', 'Kamu pakai topeng V for Vendetta tiap hari'],
        correct: 1,
        explanation: 'Begitu kamu ngerti cara *login* ke *router* Indihome pakai IP 192.168.1.1, di mata keluarga besarmu kamu udah setara dengan *hacker* anonim pembobol bank dunia.'
    },
    {
        category: '💻 IT & Penderitaan Teknologi',
        question: 'Tindakan paling barbar dan dilarang keras secara syariat IT, namun 99% manusia tetap melakukannya saat mencabut *Flashdisk* dari laptop adalah...',
        options: ['Membungkusnya dengan tisu basah sebelum dicabut', 'Mengklik "Safely Remove Hardware" dengan penuh kesabaran lalu menunggunya aman', 'Langsung main tarik aja sekuat tenaga layaknya nyabut singkong', 'Meniup *port* USB-nya'],
        correct: 2,
        explanation: '"Safely Remove Hardware" adalah fitur yang paling diabaikan dalam sejarah teknologi peradaban manusia. Selama belum meledak, tarik aja terus bos!'
    },
    {
        category: '💻 IT & Penderitaan Teknologi',
        question: 'Aplikasi *software* apa yang diam-diam menyangga sistem perekonomian, administrasi, dan kewarasan karyawan seluruh dunia, namun sering dianggap sepele?',
        options: ['Microsoft Word', 'Microsoft Excel beserta rumus VLOOKUP-nya', 'Notepad', 'Adobe Photoshop'],
        correct: 1,
        explanation: 'Dunia ini tidak digerakkan oleh Iluminati, tapi digerakkan oleh Microsoft Excel. Kalau Excel dihapus dari muka bumi, sistem ekonomi global langsung *collapse* hari itu juga.'
    },
    {
        category: '💻 IT & Penderitaan Teknologi',
        question: 'Apa fungsi sebenarnya dari *Browser* Google Chrome di laptop yang spesifikasinya kentang (RAM 4GB)?',
        options: ['Untuk berselancar internet dengan super cepat', 'Sebagai emulator *game* berat', 'Sebagai simulator pemanas ruangan dan alat uji nyali seberapa cepat laptopmu bisa *Blue Screen* setelah buka 10 *tab* Shopee', 'Untuk membuat desain 3D'],
        correct: 2,
        explanation: 'Google Chrome adalah monster pemakan RAM yang tidak pernah kenyang. Buka dua *tab* YouTube dan satu tab Siakad aja udah bikin kipas laptop menderu kayak helikopter.'
    },
    {
        category: '💻 IT & Penderitaan Teknologi',
        question: 'Solusi paling instan dari abang-abang tukang servis komputer kalau laptopmu cuma kena virus iklan (Adware) yang gampang dihapus adalah...',
        options: ['Di-scan pakai *Windows Defender*', '"Wah ini mah harus di-Install Ulang Windows-nya dek, kena Rp 150 ribu ya, datanya ilang semua."', 'Disemprot pakai cairan *disinfektan*', 'Di-ruqyah'],
        correct: 1,
        explanation: 'Bagi oknum kang servis, *Install Ulang* adalah jawaban atas segala *error*. Entah laptopnya kena virus, speakernya kresek, atau layarnya kotor, solusinya tetap: "Install Ulang Windows!"'
    },
    {
        category: '💻 IT & Penderitaan Teknologi',
        question: 'Benda teknologi apa di kantor yang dipercaya memiliki "Sensor Panik" (sengaja macet tiap kali bos butuh dokumennya dalam waktu 5 menit)?',
        options: ['Mesin Fotokopi dan Printer', 'Dispenser Air', 'Mouse Wireless', 'Proyektor'],
        correct: 0,
        explanation: 'Printer punya indera keenam. Kalau kita santai, dia nge-print mulus. Tapi giliran kita panik mau *meeting*, dia tiba-tiba *Paper Jam*, *Tinta Habis*, atau minta *Update Driver*.'
    },

    // --- 14. MATEMATIKA & KEHIDUPAN ---
    {
        category: '🧮 Matematika & Kehidupan',
        question: 'Misteri terbesar dalam buku paket LKS Matematika SD yang tidak pernah bisa diterima oleh akal sehat anak-anak adalah...',
        options: ['Kenapa luas segitiga harus dibagi dua', 'Kenapa si Budi beli semangka sampai 50 buah tapi nanya sisa buahnya ke anak SD, bukan nanya ke penjualnya', 'Kenapa 0 ditambah 0 hasilnya 0', 'Kenapa angka 8 bentuknya melengkung'],
        correct: 1,
        explanation: 'Si Budi adalah *villain* (penjahat) matematika paling manipulatif. Beli melon 40 buah, ngasih ke Andi 12 buah, terus nyuruh anak SD se-Indonesia yang ngitung sisanya.'
    },
    {
        category: '🧮 Matematika & Kehidupan',
        question: 'Apa rumus matematika kasta tertinggi yang otomatis dikuasai emak-emak saat tawar-menawar sayur di pasar tradisional?',
        options: ['Teorema Pythagoras', 'Algoritma Kriptografi tingkat tinggi', 'Kemampuan menghitung kembalian receh bawang merah dengan akurasi 100 perak tanpa kalkulator dalam waktu 2 detik', 'Rumus Integral Kalkulus'],
        correct: 2,
        explanation: 'Otak emak-emak di pasar kalau lagi ngitung diskonan dan uang kembalian sayur itu kecepatannya ngalahin prosesor Intel Core i9. Tukang sayur salah ngasih kembalian 500 perak aja pasti ketahuan.'
    },
    {
        category: '🧮 Matematika & Kehidupan',
        question: 'Konsep "Pembagian Pecahan" (Fractions) paling menegangkan dan penuh intrik di dunia nyata orang dewasa terjadi saat...',
        options: ['Ngitung warisan', 'Ngitung *Split Bill* (patungan) di kafe pas ada teman yang cuma pesen Es Teh manis tapi ikutan disuruh bayar pajak pelayanan (Tax & Service) 10%', 'Membagi kue ulang tahun', 'Menghitung diskon baju'],
        correct: 1,
        explanation: '*Split Bill* adalah arena pertempuran mental orang dewasa. Apalagi kalau teman mesen *Steak Wagyu* sementara kamu cuma pesen Kentang Goreng, tapi patungannya dibagi rata.'
    },
    {
        category: '🧮 Matematika & Kehidupan',
        question: 'Teori matematika Trigonometri (Sin, Cos, Tan) yang pas SMA bikin siswa sampai botak sariawan mikirinnya, ternyata di dunia kerja fungsinya cuma buat...',
        options: ['Bikin gedung pencakar langit', 'Menghitung sudut lemparan kertas ke tempat sampah', 'Gak ada fungsinya sama sekali bagi 90% umat manusia, cuma buat kenang-kenangan penderitaan batin masa sekolah aja', 'Menentukan arah kiblat'],
        correct: 2,
        explanation: 'Kecuali kamu Insinyur atau Arsitek, mencari nilai x dari Sin 30° + Cos 60° sama sekali gak membantu kamu saat *interview* kerja atau nyusun laporan keuangan kantor.'
    },
    {
        category: '🧮 Matematika & Kehidupan',
        question: 'Rumus matematika paling menjebak dan mematikan yang diaplikasikan oleh *e-commerce* (*marketplace*) saat *Flash Sale* tanggal kembar adalah...',
        options: ['Diskon 100% barang gratis semuanya', 'Beli 1 gratis ongkir seluruh galaksi', 'Diskon Gede 99%, TAPI MAKSIMAL POTONGAN CUMA Rp 10.000 (dan Ongkirnya Rp 50.000)', 'Voucher bisa ditukar dengan sembako'],
        correct: 2,
        explanation: 'Tulisan "Diskon 99%" di banner gede banget, tapi ada tulisan mikroskopis di bawahnya: *(S&K Berlaku, Maks. Potongan 10rb)*. Penipuan matematika yang di-sahkan.'
    },
    {
        category: '🧮 Matematika & Kehidupan',
        question: 'Dalam materi Peluang (Probabilitas), berapakah persentase peluang kamu bakal di-chat duluan sama gebetan yang *story* WhatsApp-nya aja sengaja di-*hide* dari kamu?',
        options: ['99% karena dia pemalu', '50% tergantung amal ibadah', '0% alias Mustahil Mutlak, mending sadar diri dan cari yang lain', '100% pasti di-chat'],
        correct: 2,
        explanation: 'Secara matematis dan realitas kehidupan, kalau kamu udah masuk daftar *Hide Story*, nilai peluang kamu bersanding dengannya adalah mutlak nol. Jangan maksa.'
    },
    {
        category: '🧮 Matematika & Kehidupan',
        question: 'Kalau 1 + 1 = 2, maka hasil dari rumus "Aku + Kamu" menurut logika kalkulator anak *Sadboy* / Gen Z galau zaman sekarang adalah...',
        options: ['Jadi keluarga bahagia abadi', 'HTS-an (Hubungan Tanpa Status) selama 4 bulan, di-*ghosting*, lalu berujung saling *block* WA dan IG', 'Jadi teman sehidup semati', 'Menghasilkan 3 anak lucu'],
        correct: 1,
        explanation: 'Rumus matematika asmara Gen Z tidak sesederhana 1+1=2. Banyak variabel tersembunyi seperti *insecure*, *red flag*, mantan yang belum kelar, sampai akhirnya saling blokir.'
    },

    // --- 15. FISIKA, KIMIA & MITOS ---
    {
        category: '🔬 Fisika, Kimia & Mitos',
        question: 'Hukum Newton tentang "Gaya Gesek" (Friction) paling nyata, merakyat, dan mematikan dalam kehidupan sehari-hari WNI adalah peristiwa...',
        options: ['Mobil F1 ngerem mendadak', 'Sandal jepit Swallow hijau yang bawahnya udah tipis rata (botak), lalu dipakai jalan di lantai keramik masjid yang habis dipel', 'Gesekan biola', 'Menggesek kartu ATM'],
        correct: 1,
        explanation: 'Koefisien gaya gesek statis pada sandal jepit botak vs lantai masjid basah adalah 0. Begitu kamu jalan, auto *sliding* gaya bebas mengundang tawa jamaah lain.'
    },
    {
        category: '🔬 Fisika, Kimia & Mitos',
        question: 'Teori gravitasi Isaac Newton yang nemu ide karena ada apel jatuh dari pohon ke kepalanya, tidak akan pernah terjadi di Indonesia karena...',
        options: ['Pohon apelnya langsung ditebang buat bangun perumahan', 'Di Indonesia gravitasinya ke atas', 'Apelnya belum sempat jatuh ke tanah udah dicolong duluan sama bocah-bocah kampung yang lagi main layangan', 'Apelnya dimakan codot'],
        correct: 2,
        explanation: 'Jangankan apel, buah mangga di pekarangan rumah tetangga yang baru setengah matang aja udah habis di-rujak sama bocah-bocah sebelum sempat jatuh ngikutin hukum gravitasi.'
    },
    {
        category: '🔬 Fisika, Kimia & Mitos',
        question: 'Hukum Termodinamika tentang perpindahan kalor (panas-dingin) tidak akan berfungsi di ruang kelas sekolah saat cuaca terik karena...',
        options: ['Bumi semakin memanas gara-gara alien', 'AC kelas disetel mentok 16 derajat, TAPI pintu dan jendelanya dibiarin kebuka lebar-lebar sama murid-murid yang habis main bola dan keringetan', 'AC-nya buatan planet Mars', 'Dilarang oleh Kepala Sekolah'],
        correct: 1,
        explanation: 'AC 16 derajat + Pintu kelas kebuka lebar + Aroma keringat 20 siswa cowok habis olahraga = Eksperimen Termodinamika dan Biohazard paling mematikan di sekolah.'
    },
    {
        category: '🔬 Fisika, Kimia & Mitos',
        question: 'Reaksi kimia emulsi paling "Merakyat" (yang mengubah zat cair tak berguna jadi berbusa lagi) yang sering dilakukan anak kos di akhir bulan adalah...',
        options: ['Mencampur uranium dengan deterjen', 'Mencampur H2O (Air Keran) ke dalam botol sampo atau sabun cair yang udah mau habis lalu dikocok brutal biar ada busanya lagi', 'Membuat bom molotov dari bensin ketengan', 'Menyuling air mata jadi air mineral'],
        correct: 1,
        explanation: 'Anak kos adalah ahli kimia sejati. Selama botol sampo masih bisa dituangin air keran dan dikocok sampai berbusa cair, maka sampo itu pantang dibuang ke tong sampah!'
    },
    {
        category: '🔬 Fisika, Kimia & Mitos',
        question: 'Unsur / Tabel Kimia paling mematikan dan bikin trauma masal anak SMA jurusan IPA saat disuruh maju ke depan kelas oleh guru adalah...',
        options: ['Emas (Au) dan Perak (Ag)', 'Oksigen (O)', 'Tabel Periodik Golongan IA sampai VIIIA yang harus dihafal pakai jembatan keledai aneh kayak "Beli Mangga Campur Sirup Bagi Rata"', 'Karbon Dioksida'],
        correct: 2,
        explanation: 'Setiap anak IPA pasti punya hafalan Tabel Periodik golongan IIA yang diplesetin jadi: "Bebek Mangan Cacing Seret Banget Rasane". Jembatan keledai legendaris.'
    },
    {
        category: '🔬 Fisika, Kimia & Mitos',
        question: 'Pertanyaan Fisika klasik: Kalau Kapas 1 Kg dan Besi 1 Kg dijatuhkan dari balkon lantai 2 kosan pada waktu bersamaan, mana yang lebih dulu sampai ke tanah?',
        options: ['Barengan karena massa dan gaya gravitasinya sama di ruang hampa', 'Besinya duluan, karena kapasnya nyangkut di tali jemuran emak kos atau terbang ketiup angin', 'Kapasnya duluan karena lebih ringan', 'Tidak ada yang jatuh, keduanya melayang'],
        correct: 1,
        explanation: 'Teori fisika ruang hampa udara Galileo itu bagus di buku. Tapi di realita kos-kosan Indonesia, kapas 1 Kg bakal nyangkut di jemuran, atau malah dipakai buat bersihin *make-up* sama tetangga.'
    },
    {
        category: '🔬 Fisika, Kimia & Mitos',
        question: 'Reaksi Kimia/Radiasi apa yang sangat diyakini oleh emak-emak Indonesia sebagai biang kerok dan penyebab utama dari segala jenis penderitaan di bumi?',
        options: ['Radiasi nuklir Chernobyl', 'Radiasi dari Sinyal HP (Smartphone) yang dituduh jadi penyebab sakit kepala, demam berdarah, sampai penyebab nilai rapot jelek', 'Radiasi sinar UV dari matahari terik', 'Bocoran gas elpiji 3 Kg'],
        correct: 1,
        explanation: 'Menurut diagnosis medis ala emak-emak: "Makanya, main hape terooos! Kan jadi sakit ulu hatinya!". Apapun penyakitmu, HP adalah tersangka utamanya.'
    },
    {
        category: '🔬 Fisika, Kimia & Mitos',
        question: 'Hukum Kekekalan Kalor (Panas) paling mutakhir di Indonesia hanya dikuasai oleh abang-abang bakso pinggir jalan. Bukti nyatanya adalah...',
        options: ['Mereka jualan pakai jaket kulit di siang bolong', 'Mampu menahan napas dalam kuah bakso', 'Mengambil mie kuning dan sayur dari panci kuah mendidih cuma pakai jari tangan kosong tanpa melepuh sedikitpun', 'Bisa mendinginkan kuah bakso pakai tatapan mata'],
        correct: 2,
        explanation: 'Sel-sel kulit mati di tangan abang tukang bakso, mie ayam, dan seblak telah berevolusi menjadi pelindung anti-termal yang tahan air mendidih bersuhu 100 derajat celcius.'
    },

    // --- 16. SEJARAH INDONESIA: TAWA & LUKA ---
    {
        category: '📜 Sejarah Indonesia: Tawa & Luka',
        question: 'Menurut sejarah, VOC (Kompeni Belanda) aslinya cuma perusahaan dagang kecil. Tapi kenapa mereka bisa betah menjajah dan meraup untung ratusan tahun di Nusantara?',
        options: ['Karena mereka bawa naga terbang', 'Karena VOC punya teknologi laser alien', 'Karena bangsa kita saat itu terlalu gampang kena tipu taktik "Adu Domba" (Devide et Impera) alias gampang banget di-kompor-komporin', 'Karena mereka nawarin Wi-Fi gratis'],
        correct: 2,
        explanation: 'Sejarah membuktikan bahwa netizen Nusantara sejak zaman kerajaan emang gampang kepancing emosinya. VOC tinggal kasih gosip dikit, raja-raja lokal langsung pada perang saudara.'
    },
    {
        category: '📜 Sejarah Indonesia: Tawa & Luka',
        question: 'Rekor *Guinness World Records* versi mitologi untuk "Proyek Infrastruktur Tercepat Tanpa Tender, Tanpa APBN, namun Gagal H-1" dipegang oleh...',
        options: ['Proyek Kereta Cepat', 'Pembangunan Monas', 'Proyek Meikarta', 'Bandung Bondowoso yang bangun 999 Candi Prambanan tapi gagal gara-gara Roro Jonggrang nipu pakai *sound system* ibu-ibu numbuk padi'],
        correct: 3,
        explanation: 'Roro Jonggrang adalah pionir "Sabotase Proyek" di Indonesia. Pakai *cheat* ibu-ibu bakar jerami biar ayam berkokok, *project manager* jin langsung panik dan kabur ninggalin proyek.'
    },
    {
        category: '📜 Sejarah Indonesia: Tawa & Luka',
        question: 'Bukti kuat bahwa Kerja Rodi (Jalan Anyer-Panarukan) era Daendels masih menyisakan trauma genetik berkepanjangan bagi warga +62 sampai detik ini adalah...',
        options: ['Jalannya jadi angker', 'Herman Daendels jadi *vampire*', 'Sampai sekarang, kalau Pak RT nyuruh gotong royong kerja bakti bersihin selokan hari Minggu, 80% warga mendadak alasan "Sakit Perut" atau "Ada Acara Keluarga"', 'Semua orang menolak naik mobil'],
        correct: 2,
        explanation: 'Kerja bakti hari Minggu pagi di kompleks perumahan adalah wujud kerja rodi modern. Selalu ada bapak-bapak yang tiba-tiba pura-pura tidur nyenyak pas rumahnya diketok panitia RT.'
    },
    {
        category: '📜 Sejarah Indonesia: Tawa & Luka',
        question: 'Peristiwa Rengasdengklok mengajarkan kita bahwa pemuda zaman dulu kalau gak sabaran dan beda pendapat sama orang tua (Bung Karno dkk), solusinya adalah...',
        options: ['Berdebat di kolom komentar TikTok pakai akun *fake*', 'Bikin petisi *online* di Change.org', 'Menculik golongan tua ke luar kota (Rengasdengklok) biar dijauhin dari *circle* Jepang dan dipaksa proklamasi besoknya juga', 'Demo bawa spanduk lucu'],
        correct: 2,
        explanation: 'Pemuda tahun 1945 itu *action*-nya nyata. Kurang sreg sama keputusan orang tua? Langsung culik Soekarno-Hatta ke Karawang subuh-subuh. Gen Z sekarang kalau beda pendapat cuma berani nyindir di *story* IG.'
    },
    {
        category: '📜 Sejarah Indonesia: Tawa & Luka',
        question: 'Apa kengerian kultural terselubung bagi anak sekolah era 90-an setiap menjelang tanggal 30 September malam tiba?',
        options: ['Razia rambut panjang oleh guru BP', 'Wajib nonton tayangan film G30S/PKI di TVRI yang *backsound* horor dan visual berdarahnya bikin anak SD nggak berani ke kamar mandi sampai subuh', 'Takut dikerjain hantu sumur', 'Pemadaman listrik bergilir'],
        correct: 1,
        explanation: 'Musik *scoring* film G30S/PKI itu jauh lebih menyeramkan dari film The Conjuring. Cukup denger *backsound*-nya aja udah bikin anak 90-an merinding parno mau pipis malam-malam.'
    },
    {
        category: '📜 Sejarah Indonesia: Tawa & Luka',
        question: 'Naskah asli Supersemar (Surat Perintah Sebelas Maret) adalah dokumen paling gaib dan misterius di Indonesia, menyaingi misteri dari...',
        options: ['Segitiga Bermuda', 'Ke mana hilangnya Kotak Bekal Tupperware emak yang dipinjam temen / tetangga dan gak pernah balik lagi', 'Harta karun Atlantis', 'Siapa penemu Bitcoin'],
        correct: 1,
        explanation: 'Sampai sekarang ANRI (Arsip Nasional) belum nemu naskah otentik Supersemar. Sama halnya dengan Tupperware emak yang kalau udah dibawa anak ke sekolah, *chance* untuk kembali dengan selamat adalah 0.1%.'
    },
    {
        category: '📜 Sejarah Indonesia: Tawa & Luka',
        question: 'Menurut Sumpah Palapa, Patih Gajah Mada bersumpah puasa nggak akan memakan "Palapa" sebelum bisa menyatukan Nusantara. Apa kegiatan setara "Sumpah Palapa" bagi mahasiswa tingkat akhir zaman sekarang?',
        options: ['Puasa nge-*scroll* TikTok, Instagram Reels, dan Twitter / X sebelum skripsi bab 1 sampai 5 dapet acc dari Dosen Pembimbing (TAPI BIASANYA GAGAL DI HARI PERTAMA)', 'Puasa makan daging', 'Tidak mandi 40 hari 40 malam bertapa di gunung', 'Menyatukan semua *circle* pertemanan yang lagi berantem'],
        correct: 0,
        explanation: 'Sumpah Gajah Mada berhasil karena dia disiplin. Mahasiswa zaman *now* niatnya "Sumpah puasa TikTok sampe skripsi kelar", tapi baru buka MS Word 5 menit, tangan udah gatel buka FYP liat orang joget.'
    },

    // --- 17. BAHASA GAUL, MEME & BRAIN ROT ---
    {
        category: '🧠 Bahasa Gaul, Meme & Brain Rot',
        question: 'Ketika audio viral "Tutung tung sahur... Tutung tung sahur" diputar di FYP TikTok, biasanya video apa yang sedang ditampilkan di layar?',
        options: ['Vlog bangunin orang sahur beneran pas bulan puasa', 'Video shitposting / kejadian absurd di luar nalar tanpa konteks (misal: kucing kecebur got, atau temen nyungsep dari motor)', 'Ceramah agama', 'Tutorial masak sahur yang estetik'],
        correct: 1,
        explanation: 'Audio legendaris "Tutung tung sahur" udah kehilangan makna religiusnya. Sekarang audio itu adalah *backsound* resmi untuk segala jenis kejadian bodoh, absurd, dan musibah kocak kearifan lokal.'
    },
    {
        category: '🧠 Bahasa Gaul, Meme & Brain Rot',
        question: 'Menurut sistem kasta Gen Alpha, apa hukuman sosial yang langsung terjadi jika kamu secara tidak sengaja tersandung di tempat umum dan dilihat oleh banyak orang?',
        options: ['Ditertawakan lalu ditolongin', 'Minus 10.000 Aura Points', 'Disuruh push-up', 'Diusir dari tongkrongan'],
        correct: 1,
        explanation: 'Di mata Gen Alpha, harga dirimu diukur pakai "Aura Points" layaknya main *game* RPG. Kesandung? Minus Aura! Lupa bawa uang pas di kasir? Minus 100.000 Aura! Tamat sudah riwayatmu.'
    },
    {
        category: '🧠 Bahasa Gaul, Meme & Brain Rot',
        question: 'Kalau teman tongkronganmu terus-terusan menceramahi, curhat panjang lebar, atau ngomong hal gak penting yang bikin kuping panas, istilah gaul paling pas buat nyuruh dia diam adalah...',
        options: ['"Stop Yapping, bro!"', '"Tolong jangan berpuisi"', '"Silakan Mewing aja mendingan"', '"Kamu sangat retorikal"'],
        correct: 0,
        explanation: '"Yapping" adalah istilah gaul luar negeri yang di-impor bocil lokal. Artinya nge-bacot atau ngoceh tanpa henti layaknya anjing kecil yang menggonggong gak karuan.'
    },
    {
        category: '🧠 Bahasa Gaul, Meme & Brain Rot',
        question: 'Kalimat *template* kasta tertinggi apa yang WAJIB diketik oleh kaum laki-laki di kolom komentar saat temannya *posting* foto main futsal atau beli motor baru?',
        options: ['"Wah selamat ya kawan, kamu luar biasa!"', '"Menyala abangku 🔥, tetap ilmu padi 🌾"', '"Semoga cepet sembuh dan banyak rezeki"', '"Subhanallah, sungguh indah ciptaan Tuhan"'],
        correct: 1,
        explanation: '"Menyala Abangku 🔥" disandingkan dengan "Ilmu Padi 🌾" (merendah) adalah *starter pack* mutlak pertemanan cowok / abang-abangan futsal zaman *now*. Gak ada komentar ini, pertemanan belum sah.'
    },
    {
        category: '🧠 Bahasa Gaul, Meme & Brain Rot',
        question: 'Di era percintaan yang membingungkan ini, cewek yang hobi *stalking* HP pacar sampai akar-akarnya, gampang tantrum, dan kelewat posesif justru bangga melabeli dirinya dengan julukan...',
        options: ['Independent Woman', 'Wonder Woman', 'Cegil (Cewek Gila)', 'Putri Keraton'],
        correct: 2,
        explanation: 'Dulu dibilang "Gila" itu hinaan mematikan. Sekarang, cewek-cewek justru bangga mendeklarasikan diri sebagai "Cegil" demi menormalisasi kelakuan *toxic* dan posesif mereka.'
    },
    {
        category: '🧠 Bahasa Gaul, Meme & Brain Rot',
        question: 'Apa arti dari kalimat "Mundur Wir, saingan lo spek bidadari/anak sultan" yang sering digunakan untuk menampar realita kaum *Sadboy*?',
        options: ['Menyuruh teman mundur secara fisik saat main tarik tambang', 'Nasihat pedas agar sadar diri karena gebetan yang diincar udah punya pacar yang jauh lebih cakep/kaya', 'Suruh supir angkot mundur (Wir = Kuwir/Sopir)', 'Mantra mengusir mantan'],
        correct: 1,
        explanation: '"Wir" asalnya dari plesetan kata Jawir (Jawa) tapi berevolusi jadi sapaan akrab kayak "Bro". "Mundur wir" adalah *warning* keras biar kamu sadar diri ngeliat isi dompet dan tampang sebelum deketin anak orang.'
    },
    {
        category: '🧠 Bahasa Gaul, Meme & Brain Rot',
        question: 'Format curhat massal paling *aesthetic* dan mendayu-dayu yang dilakukan cewek-cewek patah hati di TikTok selalu diawali dengan memanggil nama...',
        options: ['"Halo Bapak Presiden..."', '"Dear Diary..."', '"Lapor Ndan!"', '"Taylor, kali ini aku gagal lagi..."'],
        correct: 3,
        explanation: 'Taylor Swift sekarang beralih profesi dari penyanyi pop internasional menjadi psikolog dan tempat penitipan curhat massal Gen Z di TikTok. Semua penderitaan cinta pasti dilaporkan ke "Mbak Taylor".'
    },
    {
        category: '🧠 Bahasa Gaul, Meme & Brain Rot',
        question: 'Ketika ada kompilasi video kelakuan aneh, ajaib, atau di luar nalar sehat dari warga daerah tertentu (misalnya warga Depok), netizen akan menyebut fenomena itu sebagai...',
        options: ['Fenomena Alam', 'Kutukan Daerah', 'Depok Core', 'Kejadian Gaib'],
        correct: 2,
        explanation: 'Akhiran kata "Core" sekarang dipakai untuk merangkum esensi atau *vibes* absurd suatu daerah/hal. "Depok Core" isinya bisa orang nyanyi di lampu merah bareng alien, atau bapak-bapak bonceng TV tabung pakai sepeda.'
    },
    {
        category: '🧠 Bahasa Gaul, Meme & Brain Rot',
        question: 'Audio *"Gwenchana... Gwenchanayooo..."* (yang artinya: Aku gapapa) sambil pura-pura tegar dan menahan tangis, biasanya selalu dipadukan dengan filter wajah hewan apa di TikTok?',
        options: ['Kucing yang sedang menangis tersedu-sedu', 'Singa mengaum', 'Kuda lumping', 'Bebek berenang'],
        correct: 0,
        explanation: 'Meme kucing menangis dipadukan audio "Gwenchana" adalah puncak komedi tragis Gen Z untuk mengekspresikan hancurnya mental saat harus ngerjain revisi skripsi atau ngeliat saldo rekening tinggal Rp 2.000.'
    },
    {
        category: '🧠 Bahasa Gaul, Meme & Brain Rot',
        question: 'Cara ngeles (*defense mechanism*) paling nyebelin yang viral diucapkan saat seseorang sadar kata-katanya udah bikin orang lain baper atau emosi adalah dengan teriak...',
        options: ['"Eh maaf kepencet!"', '"Bercyandyaaa... Bercyandyaaa!"', '"Halah gitu aja marah, baper lo!"', '"Itu tadi bukan aku, aku di-hack"'],
        correct: 1,
        explanation: 'Dengan nada melengking, centil, dan sok asik "Bercyandyaaa", segala jenis hinaan, ledekan, dan omongan nyakitin seolah punya tameng agar tidak dimasukkan ke dalam hati (padahal yang denger pengen nampol).'
    },
    {
        category: '🧠 Bahasa Gaul, Meme & Brain Rot',
        question: 'Saat ada video bocil SMP yang sukses pamer pacar cantik di TikTok, sementara yang *scroll* adalah abang-abang jomblo ngenes usia 20-an, kalimat sakti apa yang akan mereka ketik?',
        options: ['"Masih kecil fokus belajar dek!"', '"Jangan pacaran dosa!"', '"Pasti itu ilmu pelet"', '"Tutor dek... Ampun Suhu!"'],
        correct: 3,
        explanation: 'Kata "Suhu" (Master) dan "Tutor dek" (minta diajarin/tutorial) adalah bentuk kekalahan telak, kena mental, sekaligus pengakuan kaum jomblo tua terhadap kelakuan asmara para bocil zaman *now*.'
    },
    {
        category: '🧠 Bahasa Gaul, Meme & Brain Rot',
        question: 'Ketika temanmu lagi nekat melakukan sesuatu yang kelihatannya bodoh tapi dia sangat percaya diri, fokus, dan penuh ambisi, istilah dukungan gaul apa yang harus kamu ucapkan?',
        options: ['"Telpon RSJ sekarang!"', '"Jangan ditiru adegan berbahaya!"', '"Let him cook (Biarin dia masak/beraksi)"', '"Stop ngebodohin diri sendiri"'],
        correct: 2,
        explanation: '"Let him cook" gak ada hubungannya sama oseng-oseng sayur di dapur. Artinya adalah: "Diemin aja, biarin dia bereksperimen dengan kelakuan anehnya, siapa tahu hasilnya epik (atau malah lucu karena gagal total)".'
    },
    {
        category: '🧠 Bahasa Gaul, Meme & Brain Rot',
        question: 'Kalimat memelas kearifan lokal yang sangat sarkas, digunakan untuk "merendah agar disanjung" (merendah untuk meroket) saat masuk tongkrongan orang jago adalah...',
        options: ['"Halo semuanya, aku pendatang baru"', '"Minggir kalian semua, aku yang terhebat!"', '"Ampun Puh, Sepuh, ajarin dong, aku mah masih pemula"', '"Ada lawan?"'],
        correct: 2,
        explanation: '"Ampun Puh, Sepuh" adalah sarkasme abadi. Orang yang komen "aku mah masih pemula" biasanya *skill*-nya udah setingkat dewa dan diam-diam bersiap untuk membantai se-tongkrongan tanpa sisa.'
    },
    {
        category: '🧠 Bahasa Gaul, Meme & Brain Rot',
        question: 'Ketika seorang cewek sangat yakin dan berkhayal bahwa idol K-Pop atau aktor tampan yang baru dia tonton di drakor adalah jodohnya kelak, kamu harus mendiagnosanya mengidap...',
        options: ['Flu burung tipe C', 'Delulu (Delusional) tingkat kronis', 'Rabun jauh', 'Bipolar'],
        correct: 1,
        explanation: '"Delulu is the solulu" (Delusi adalah solusi) merupakan pedoman hidup Gen Z buat kabur sejenak dari kerasnya realita. Nggak apa-apa aslinya jomblo ngenes, yang penting dalam khayalan udah nikah sama Cha Eun-woo.'
    },
    {
        category: '🧠 Bahasa Gaul, Meme & Brain Rot',
        question: 'Ketika seorang netizen melihat kelakuan ajaib (misal ngelihat sekumpulan bapak-bapak lomba balap karung tapi pakai helm *full face*), istilah gaul apa yang diucapkan saat mereka kehilangan kata-kata?',
        options: ['"Aku sampai ber-word-word!" (Speechless)', '"No comment aja deh"', '"Wah, biasa aja sih"', '"Keren banget!"'],
        correct: 0,
        explanation: 'Netizen +62 kalau mau bilang "Kehabisan kata-kata" atau "Speechless" malah menciptakan paradoks istilah baru yang kontradiktif, yaitu: "Aku sampai ber-word-word (berkata-kata) saking herannya".'
    }
];
</script>

<!-- =========================================================
     QUIZ ENGINE CONTROLLER JAVASCRIPT
     ========================================================= -->
<script>
// --- Game State Variables ---
let quizState = {
    mode: 'single', // 'single' | 'duel'
    difficulty: 'easy', // 'easy' (10) | 'medium' (25) | 'hard' (50) | 'wni' (100)
    timerDuration: 15,
    audioEnabled: true,
    
    // Players
    p1Name: 'Player 1',
    p1Avatar: '😎',
    p1Score: 0,
    p1Hearts: 3,
    p1Correct: 0,

    p2Name: 'Player 2',
    p2Avatar: '🤖',
    p2Score: 0,
    p2Hearts: 3,
    p2Correct: 0,

    currentTurn: 1, // 1 | 2 (for duel mode)
    currentIndex: 0,
    activeQuestionList: [],
    currentStreak: 0,
    maxStreak: 0,
    lifelinesLeft: 5,
    skipsLeft: 5,
    
    // Timer
    timerRemaining: 15,
    timerInterval: null,
    isAnswered: false,
    answeredHistory: []
};

// --- Web Audio API Synth Engine ---
let quizAudioCtx = null;

function initQuizAudio() {
    if (!quizAudioCtx) {
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        quizAudioCtx = new AudioContext();
    }
    if (quizAudioCtx.state === 'suspended') {
        quizAudioCtx.resume();
    }
}

function playQuizSound(type) {
    if (!quizState.audioEnabled) return;
    try {
        initQuizAudio();
        const now = quizAudioCtx.currentTime;

        if (type === 'correct') {
            [523.25, 659.25, 783.99, 1046.50].forEach((freq, i) => {
                const osc = quizAudioCtx.createOscillator();
                const gain = quizAudioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, now + i * 0.05);
                gain.gain.setValueAtTime(0.18, now + i * 0.05);
                gain.gain.exponentialRampToValueAtTime(0.001, now + i * 0.05 + 0.2);
                osc.connect(gain);
                gain.connect(quizAudioCtx.destination);
                osc.start(now + i * 0.05);
                osc.stop(now + i * 0.05 + 0.2);
            });
        } else if (type === 'wrong') {
            const osc = quizAudioCtx.createOscillator();
            const gain = quizAudioCtx.createGain();
            osc.type = 'sawtooth';
            osc.frequency.setValueAtTime(140, now);
            osc.frequency.linearRampToValueAtTime(80, now + 0.3);
            gain.gain.setValueAtTime(0.25, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.3);
            osc.connect(gain);
            gain.connect(quizAudioCtx.destination);
            osc.start(now);
            osc.stop(now + 0.3);
        } else if (type === 'lifeline') {
            [880, 1174.66, 1760].forEach((freq, i) => {
                const osc = quizAudioCtx.createOscillator();
                const gain = quizAudioCtx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(freq, now + i * 0.06);
                gain.gain.setValueAtTime(0.15, now + i * 0.06);
                gain.gain.exponentialRampToValueAtTime(0.001, now + i * 0.06 + 0.18);
                osc.connect(gain);
                gain.connect(quizAudioCtx.destination);
                osc.start(now + i * 0.06);
                osc.stop(now + i * 0.06 + 0.18);
            });
        }
    } catch (e) {}
}

function toggleQuizAudio() {
    quizState.audioEnabled = !quizState.audioEnabled;
    const icon = document.getElementById('quizSoundIcon');
    if (quizState.audioEnabled) {
        icon.className = 'fa-solid fa-volume-high text-purple';
    } else {
        icon.className = 'fa-solid fa-volume-xmark text-danger';
    }
}

// --- Mode & Difficulty Setup ---
function setQuizGameMode(mode) {
    quizState.mode = mode;
    const isDuel = mode === 'duel';
    
    document.getElementById('modeBtn_single').classList.toggle('active', !isDuel);
    document.getElementById('modeBtn_single').classList.toggle('btn-purple', !isDuel);
    document.getElementById('modeBtn_single').classList.toggle('btn-outline-purple', isDuel);

    document.getElementById('modeBtn_duel').classList.toggle('active', isDuel);
    document.getElementById('modeBtn_duel').classList.toggle('btn-info', isDuel);
    document.getElementById('modeBtn_duel').classList.toggle('btn-outline-info', !isDuel);

    document.getElementById('p2InputRow').classList.toggle('d-none', !isDuel);
    document.getElementById('headerGameModeBadge').innerHTML = `<i class="${isDuel ? 'fa-solid fa-user-group' : 'fa-solid fa-user'} me-1"></i> Mode: ${isDuel ? '2-PLAYER DUEL' : 'SINGLE PLAYER'}`;
}

function updateDifficultyPreview(diff) {
    quizState.difficulty = diff;
}

// --- Start Game Initializer ---
function startQuizGame() {
    initQuizAudio();

    // Read form inputs
    quizState.p1Name = document.getElementById('startP1Name').value.trim() || 'Player 1';
    quizState.p1Avatar = document.getElementById('startP1Avatar').value;
    quizState.p2Name = document.getElementById('startP2Name').value.trim() || 'Player 2';
    quizState.p2Avatar = document.getElementById('startP2Avatar').value;
    quizState.difficulty = document.getElementById('startDifficultySelect').value;

    // Determine question count & max hearts
    let targetCount = 20;
    let maxHearts = 3;

    if (quizState.difficulty === 'medium') {
        targetCount = 35;
        maxHearts = 5;
    } else if (quizState.difficulty === 'hard') {
        targetCount = 70;
        maxHearts = 7;
    } else if (quizState.difficulty === 'wni') {
        targetCount = 150;
        maxHearts = 15;
    }

    // Reset game metrics
    quizState.p1Score = 0;
    quizState.p1Hearts = maxHearts;
    quizState.p1Correct = 0;

    quizState.p2Score = 0;
    quizState.p2Hearts = maxHearts;
    quizState.p2Correct = 0;

    quizState.currentTurn = 1;
    quizState.currentIndex = 0;
    quizState.currentStreak = 0;
    quizState.maxStreak = 0;
    quizState.lifelinesLeft = 5;
    quizState.skipsLeft = 5;
    quizState.isAnswered = false;
    quizState.answeredHistory = [];

    // Generate & shuffle question list from question bank
    quizState.activeQuestionList = generateShuffledQuestions(targetCount);

    // Update UI elements
    document.getElementById('p1NameLabel').innerText = quizState.p1Name;
    document.getElementById('p1Avatar').innerText = quizState.p1Avatar;
    document.getElementById('p2NameLabel').innerText = quizState.p2Name;
    document.getElementById('p2Avatar').innerText = quizState.p2Avatar;

    const isDuel = quizState.mode === 'duel';
    document.getElementById('player2Col').classList.toggle('d-none', !isDuel);
    document.getElementById('singlePlayerStatsCol').classList.toggle('d-none', isDuel);

    const diffNames = { easy: '🟢 MUDAH (20)', medium: '🟡 SEDANG (35)', hard: '🔴 SUSAH (70)', wni: '🇮🇩 WNI MODE (150)' };
    document.getElementById('topbarDiffBadge').innerText = diffNames[quizState.difficulty] || 'KUIS';

    renderHearts();
    updateScoreUI();
    updateLifelineUI();

    // Hide overlays & render 1st question
    document.getElementById('quizStartOverlay').classList.add('d-none');
    document.getElementById('quizGameOverOverlay').classList.add('d-none');
    document.getElementById('quizVictoryOverlay').classList.add('d-none');

    renderCurrentQuestion();
}

function showStartScreen() {
    clearInterval(quizState.timerInterval);
    document.getElementById('quizGameOverOverlay').classList.add('d-none');
    document.getElementById('quizVictoryOverlay').classList.add('d-none');
    document.getElementById('quizStartOverlay').classList.remove('d-none');
}

// --- Question Shuffling Engine (Fisher-Yates) ---
function generateShuffledQuestions(count) {
    // Clone full pool and shuffle
    let pool = [...QUIZ_QUESTION_BANK];
    for (let i = pool.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [pool[i], pool[j]] = [pool[j], pool[i]];
    }

    // Repeat if pool length is less than requested count (e.g. for 100 questions)
    let selected = [];
    while (selected.length < count) {
        for (let item of pool) {
            if (selected.length >= count) break;
            // Shuffle choices inside item
            const originalCorrectText = item.options[item.correct];
            let shuffledOptions = [...item.options];
            for (let x = shuffledOptions.length - 1; x > 0; x--) {
                const y = Math.floor(Math.random() * (x + 1));
                [shuffledOptions[x], shuffledOptions[y]] = [shuffledOptions[y], shuffledOptions[x]];
            }
            const newCorrectIndex = shuffledOptions.indexOf(originalCorrectText);

            selected.push({
                category: item.category,
                question: item.question,
                options: shuffledOptions,
                correct: newCorrectIndex,
                explanation: item.explanation
            });
        }
    }

    return selected;
}

// --- Render Question & Choices ---
function renderCurrentQuestion() {
    if (quizState.currentIndex >= quizState.activeQuestionList.length) {
        triggerVictory();
        return;
    }

    quizState.isAnswered = false;
    const q = quizState.activeQuestionList[quizState.currentIndex];

    // Update Header Category & Badges
    document.getElementById('questionCategoryPill').innerText = q.category;
    document.getElementById('topbarCategoryBadge').innerText = q.category;
    document.getElementById('questionText').innerText = q.question;
    document.getElementById('singleQuestionCount').innerText = `${quizState.currentIndex + 1} / ${quizState.activeQuestionList.length}`;

    // Update Turn Indicator (for duel)
    if (quizState.mode === 'duel') {
        const isP1 = quizState.currentTurn === 1;
        document.getElementById('player1Pod').classList.toggle('active-turn', isP1);
        document.getElementById('player2Pod').classList.toggle('active-turn-p2', !isP1);
        document.getElementById('p1TurnBadge').className = isP1 ? 'badge bg-purple text-white font-monospace style-tiny' : 'badge bg-secondary text-white font-monospace style-tiny d-none';
        document.getElementById('p2TurnBadge').className = !isP1 ? 'badge bg-info text-dark font-monospace style-tiny' : 'badge bg-secondary text-white font-monospace style-tiny d-none';
    }

    // Reset Choices UI
    for (let i = 0; i < 4; i++) {
        const btn = document.getElementById(`choiceBtn_${i}`);
        const txt = document.getElementById(`choiceText_${i}`);
        btn.className = 'quiz-choice-btn';
        btn.disabled = false;
        txt.innerText = q.options[i] || '-';
    }

    // Start Question Timer
    startQuestionTimer();
}

// --- Timer System ---
function startQuestionTimer() {
    clearInterval(quizState.timerInterval);
    const duration = parseInt(document.getElementById('modalTimerSelect').value) || 15;
    
    if (duration === 0) {
        document.getElementById('quizTimerBar').style.width = '100%';
        document.getElementById('singleTimerSeconds').innerText = '∞';
        return;
    }

    quizState.timerRemaining = duration;
    updateTimerBar(duration, duration);

    quizState.timerInterval = setInterval(() => {
        quizState.timerRemaining--;
        updateTimerBar(quizState.timerRemaining, duration);
        document.getElementById('singleTimerSeconds').innerText = `${quizState.timerRemaining}s`;

        if (quizState.timerRemaining <= 0) {
            clearInterval(quizState.timerInterval);
            handleTimeOut();
        }
    }, 1000);
}

function updateTimerBar(current, max) {
    const pct = Math.max(0, (current / max) * 100);
    const bar = document.getElementById('quizTimerBar');
    bar.style.width = `${pct}%`;
}

// --- Handle Answer Selection ---
function handleChoiceClick(selectedIndex) {
    if (quizState.isAnswered) return;
    quizState.isAnswered = true;
    clearInterval(quizState.timerInterval);

    const q = quizState.activeQuestionList[quizState.currentIndex];
    const isCorrect = selectedIndex === q.correct;
    const clickedBtn = document.getElementById(`choiceBtn_${selectedIndex}`);
    const correctBtn = document.getElementById(`choiceBtn_${q.correct}`);

    // Save answer data & explanation to history
    quizState.answeredHistory.push({
        number: quizState.currentIndex + 1,
        category: q.category,
        question: q.question,
        options: [...q.options],
        userAnswerIndex: selectedIndex,
        correctIndex: q.correct,
        isCorrect: isCorrect,
        playerTurn: quizState.mode === 'duel' ? (quizState.currentTurn === 1 ? quizState.p1Name : quizState.p2Name) : quizState.p1Name,
        playerAvatar: quizState.mode === 'duel' ? (quizState.currentTurn === 1 ? quizState.p1Avatar : quizState.p2Avatar) : quizState.p1Avatar,
        explanation: q.explanation || 'Tidak ada penjelasan khusus untuk soal ini.'
    });

    // Disable all buttons
    for (let i = 0; i < 4; i++) {
        document.getElementById(`choiceBtn_${i}`).disabled = true;
    }

    if (isCorrect) {
        clickedBtn.classList.add('correct');
        playQuizSound('correct');

        quizState.currentStreak++;
        if (quizState.currentStreak > quizState.maxStreak) {
            quizState.maxStreak = quizState.currentStreak;
        }

        const streakBonus = Math.min(quizState.currentStreak * 20, 100);
        const pts = 100 + streakBonus;

        if (quizState.mode === 'duel') {
            if (quizState.currentTurn === 1) {
                quizState.p1Score += pts;
                quizState.p1Correct++;
            } else {
                quizState.p2Score += pts;
                quizState.p2Correct++;
            }
        } else {
            quizState.p1Score += pts;
            quizState.p1Correct++;
        }
    } else {
        clickedBtn.classList.add('wrong');
        correctBtn.classList.add('correct');
        playQuizSound('wrong');

        quizState.currentStreak = 0;

        // Deduct Heart
        if (quizState.mode === 'duel') {
            if (quizState.currentTurn === 1) quizState.p1Hearts--;
            else quizState.p2Hearts--;
        } else {
            quizState.p1Hearts--;
        }
        renderHearts();
    }

    document.getElementById('streakLabel').innerText = `${quizState.currentStreak}🔥`;
    updateScoreUI();

    // Check for Game Over (Hearts <= 0)
    const activeHearts = quizState.mode === 'duel' 
        ? (quizState.currentTurn === 1 ? quizState.p1Hearts : quizState.p2Hearts)
        : quizState.p1Hearts;

    if (activeHearts <= 0) {
        setTimeout(() => {
            triggerGameOver();
        }, 1100);
        return;
    }

    // Transition to next question
    setTimeout(() => {
        quizState.currentIndex++;
        if (quizState.mode === 'duel') {
            quizState.currentTurn = quizState.currentTurn === 1 ? 2 : 1;
        }
        renderCurrentQuestion();
    }, 1100);
}

function handleTimeOut() {
    if (quizState.isAnswered) return;
    quizState.isAnswered = true;

    const q = quizState.activeQuestionList[quizState.currentIndex];
    const correctBtn = document.getElementById(`choiceBtn_${q.correct}`);
    correctBtn.classList.add('correct');
    playQuizSound('wrong');

    // Save timeout data to history
    quizState.answeredHistory.push({
        number: quizState.currentIndex + 1,
        category: q.category,
        question: q.question,
        options: [...q.options],
        userAnswerIndex: -1,
        correctIndex: q.correct,
        isCorrect: false,
        playerTurn: quizState.mode === 'duel' ? (quizState.currentTurn === 1 ? quizState.p1Name : quizState.p2Name) : quizState.p1Name,
        playerAvatar: quizState.mode === 'duel' ? (quizState.currentTurn === 1 ? quizState.p1Avatar : quizState.p2Avatar) : quizState.p1Avatar,
        explanation: q.explanation || 'Waktu menjawab telah habis sebelum memilih opsi jawaban.'
    });

    quizState.currentStreak = 0;
    document.getElementById('streakLabel').innerText = '0🔥';

    if (quizState.mode === 'duel') {
        if (quizState.currentTurn === 1) quizState.p1Hearts--;
        else quizState.p2Hearts--;
    } else {
        quizState.p1Hearts--;
    }
    renderHearts();

    const activeHearts = quizState.mode === 'duel' 
        ? (quizState.currentTurn === 1 ? quizState.p1Hearts : quizState.p2Hearts)
        : quizState.p1Hearts;

    if (activeHearts <= 0) {
        setTimeout(() => { triggerGameOver(); }, 1100);
        return;
    }

    setTimeout(() => {
        quizState.currentIndex++;
        if (quizState.mode === 'duel') {
            quizState.currentTurn = quizState.currentTurn === 1 ? 2 : 1;
        }
        renderCurrentQuestion();
    }, 1100);
}

// --- 50:50 Lifeline Helper ---
function useLifeline50() {
    if (quizState.lifelinesLeft <= 0 || quizState.isAnswered) return;
    
    quizState.lifelinesLeft--;
    updateLifelineUI();
    playQuizSound('lifeline');

    const q = quizState.activeQuestionList[quizState.currentIndex];
    const wrongIndices = [0, 1, 2, 3].filter(idx => idx !== q.correct);
    
    // Pick 2 wrong indices randomly to eliminate
    for (let i = wrongIndices.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [wrongIndices[i], wrongIndices[j]] = [wrongIndices[j], wrongIndices[i]];
    }

    const toEliminate = wrongIndices.slice(0, 2);
    toEliminate.forEach(idx => {
        document.getElementById(`choiceBtn_${idx}`).classList.add('eliminated');
    });
}

// --- Skip / Ganti Soal Lifeline Helper ---
function useLifelineSkip() {
    if (quizState.skipsLeft <= 0 || quizState.isAnswered) return;
    
    quizState.skipsLeft--;
    updateLifelineUI();
    playQuizSound('lifeline');

    // Find a question from question bank not currently in active list
    const currentQuestions = quizState.activeQuestionList.map(q => q.question);
    const availablePool = QUIZ_QUESTION_BANK.filter(q => !currentQuestions.includes(q.question));
    
    let newRawItem;
    if (availablePool.length > 0) {
        newRawItem = availablePool[Math.floor(Math.random() * availablePool.length)];
    } else {
        const currentQText = quizState.activeQuestionList[quizState.currentIndex].question;
        const otherPool = QUIZ_QUESTION_BANK.filter(q => q.question !== currentQText);
        newRawItem = otherPool[Math.floor(Math.random() * otherPool.length)] || QUIZ_QUESTION_BANK[0];
    }

    // Shuffle options for the replacement question
    const originalCorrectText = newRawItem.options[newRawItem.correct];
    let shuffledOptions = [...newRawItem.options];
    for (let x = shuffledOptions.length - 1; x > 0; x--) {
        const y = Math.floor(Math.random() * (x + 1));
        [shuffledOptions[x], shuffledOptions[y]] = [shuffledOptions[y], shuffledOptions[x]];
    }
    const newCorrectIndex = shuffledOptions.indexOf(originalCorrectText);

    // Replace current question and re-render
    quizState.activeQuestionList[quizState.currentIndex] = {
        category: newRawItem.category,
        question: newRawItem.question,
        options: shuffledOptions,
        correct: newCorrectIndex,
        explanation: newRawItem.explanation
    };

    renderCurrentQuestion();
}

function updateLifelineUI() {
    const badge50 = document.getElementById('lifelineBadge');
    const btn50 = document.getElementById('lifeline50Btn');
    if (badge50) badge50.innerText = `${quizState.lifelinesLeft}x`;
    if (btn50) btn50.disabled = quizState.lifelinesLeft <= 0;

    const badgeSkip = document.getElementById('lifelineSkipBadge');
    const btnSkip = document.getElementById('lifelineSkipBtn');
    if (badgeSkip) badgeSkip.innerText = `${quizState.skipsLeft}x`;
    if (btnSkip) btnSkip.disabled = quizState.skipsLeft <= 0;
}

function getDifficultyMaxHearts(diff) {
    if (diff === 'wni') return 15;
    if (diff === 'hard') return 7;
    if (diff === 'medium') return 5;
    return 3;
}

// --- Hearts Rendering ---
function renderHearts() {
    const maxHearts = getDifficultyMaxHearts(quizState.difficulty);

    // Render P1 Hearts
    let p1Html = '';
    for (let i = 0; i < maxHearts; i++) {
        const isLost = i >= quizState.p1Hearts;
        p1Html += `<i class="fa-solid fa-heart heart-icon ${isLost ? 'lost' : ''}"></i>`;
    }
    document.getElementById('p1HeartsContainer').innerHTML = p1Html;

    // Render P2 Hearts
    if (quizState.mode === 'duel') {
        let p2Html = '';
        for (let i = 0; i < maxHearts; i++) {
            const isLost = i >= quizState.p2Hearts;
            p2Html += `<i class="fa-solid fa-heart heart-icon ${isLost ? 'lost' : ''}"></i>`;
        }
        document.getElementById('p2HeartsContainer').innerHTML = p2Html;
    }
}

function updateScoreUI() {
    document.getElementById('p1ScoreLabel').innerText = `${quizState.p1Score} Pts`;
    document.getElementById('p2ScoreLabel').innerText = `${quizState.p2Score} Pts`;
}

// --- Game Over Screen ---
function triggerGameOver() {
    clearInterval(quizState.timerInterval);
    const isDuel = quizState.mode === 'duel';

    document.getElementById('overP1Name').innerText = isDuel 
        ? `${quizState.currentTurn === 1 ? quizState.p1Name : quizState.p2Name}`
        : quizState.p1Name;

    const finalScore = isDuel 
        ? Math.max(quizState.p1Score, quizState.p2Score)
        : quizState.p1Score;

    const totalCorrect = isDuel 
        ? quizState.p1Correct + quizState.p2Correct
        : quizState.p1Correct;

    document.getElementById('overScore').innerText = finalScore;
    document.getElementById('overCorrect').innerText = totalCorrect;
    document.getElementById('overStreak').innerText = `${quizState.maxStreak}🔥`;

    document.getElementById('quizGameOverOverlay').classList.remove('d-none');

    saveScoreToQuizLeaderboard(quizState.p1Name, finalScore, totalCorrect, quizState.difficulty);
}

// --- Victory Screen ---
function triggerVictory() {
    clearInterval(quizState.timerInterval);
    const isDuel = quizState.mode === 'duel';

    let rankTitle = 'WNI Bersertifikat ⭐⭐⭐';
    if (quizState.p1Correct >= 80) rankTitle = 'Mahaguru Trivia Nusantara 👑';
    else if (quizState.p1Correct >= 40) rankTitle = 'Alumni Kehormatan MMC 2017 🎓';
    else if (quizState.p1Correct >= 20) rankTitle = 'Penjelajah Skena Tamansari 🚀';

    if (isDuel) {
        const winnerName = quizState.p1Score > quizState.p2Score 
            ? quizState.p1Name 
            : (quizState.p2Score > quizState.p1Score ? quizState.p2Name : 'Draw (Seri)');
        document.getElementById('victoryTitle').innerText = `🏆 PEMENANG: ${winnerName}!`;
        document.getElementById('victorySubtitle').innerText = `Duel sengit telah berakhir dengan rekor skor luar biasa!`;
    } else {
        document.getElementById('victoryTitle').innerText = '🎉 KUIS SELESAI / VICTORY!';
        document.getElementById('victorySubtitle').innerText = 'Selamat! Kamu berhasil menuntaskan seluruh tantangan soal!';
    }

    const totalQuestions = quizState.activeQuestionList.length;
    const accuracy = Math.round((quizState.p1Correct / totalQuestions) * 100);

    document.getElementById('victoryRankBadge').innerText = rankTitle;
    document.getElementById('victoryAccuracyBadge').innerText = `${accuracy}% Akurat`;
    document.getElementById('victoryScore').innerText = isDuel ? `${quizState.p1Score} vs ${quizState.p2Score}` : quizState.p1Score;
    document.getElementById('victoryCorrect').innerText = `${quizState.p1Correct}/${totalQuestions}`;
    document.getElementById('victoryStreak').innerText = `${quizState.maxStreak}🔥`;

    document.getElementById('quizVictoryOverlay').classList.remove('d-none');

    saveScoreToQuizLeaderboard(quizState.p1Name, quizState.p1Score, quizState.p1Correct, quizState.difficulty);

    // Save AJAX score to backend
    fetch('<?= base_url('mini-game/api/record-score') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            game_id: 'quiz',
            level: quizState.difficulty === 'wni' ? 4 : (quizState.difficulty === 'hard' ? 3 : (quizState.difficulty === 'medium' ? 2 : 1)),
            score: quizState.p1Score,
            stars: accuracy >= 80 ? 3 : (accuracy >= 50 ? 2 : 1)
        })
    }).catch(err => console.log(err));
}

// --- Leaderboard & Local Persistence ---
function saveScoreToQuizLeaderboard(name, score, correct, diff) {
    let board = JSON.parse(localStorage.getItem('mmc_quiz_leaderboard') || '[]');
    board.push({
        name: name,
        score: score,
        correct: correct,
        difficulty: diff.toUpperCase(),
        date: new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
    });

    board.sort((a, b) => b.score - a.score);
    board = board.slice(0, 5);

    localStorage.setItem('mmc_quiz_leaderboard', JSON.stringify(board));
    renderQuizLeaderboard();
}

function renderQuizLeaderboard() {
    const board = JSON.parse(localStorage.getItem('mmc_quiz_leaderboard') || '[]');
    const container = document.getElementById('quizLeaderboardBody');

    if (board.length === 0) {
        container.innerHTML = `
            <div class="text-center text-secondary py-4">
                <i class="fa-solid fa-brain fs-1 mb-2 text-muted"></i>
                <p class="small mb-0">Belum ada rekor kuis tersimpan. Mulai mainkan dan catat skormu!</p>
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
                        <span class="style-tiny text-secondary font-monospace">${item.date} • ${item.difficulty}</span>
                    </div>
                </div>
                <div class="text-end font-monospace">
                    <span class="fs-5 fw-bold text-warning">${item.score}</span>
                    <span class="style-tiny text-secondary d-block">${item.correct} Benar</span>
                </div>
            </div>
        `;
    });

    html += '</div>';
    container.innerHTML = html;
}

function clearQuizLeaderboard() {
    if (confirm('Hapus seluruh riwayat rekor kuis di perangkat ini?')) {
        localStorage.removeItem('mmc_quiz_leaderboard');
        renderQuizLeaderboard();
    }
}

function saveQuizModalSettings() {
    const audioSwitch = document.getElementById('modalAudioSwitch').checked;
    quizState.audioEnabled = audioSwitch;
    const icon = document.getElementById('quizSoundIcon');
    icon.className = audioSwitch ? 'fa-solid fa-volume-high text-purple' : 'fa-solid fa-volume-xmark text-danger';
    bootstrap.Modal.getInstance(document.getElementById('quizSettingsModal')).hide();
}

// --- Review & Explanation System ---
let currentExpFilter = 'all';

function openExplanationModal() {
    currentExpFilter = 'all';
    
    // Update filter counters
    const history = quizState.answeredHistory || [];
    const correctCount = history.filter(h => h.isCorrect).length;
    const wrongCount = history.filter(h => !h.isCorrect).length;

    const countAllEl = document.getElementById('expCountAll');
    const countCorrectEl = document.getElementById('expCountCorrect');
    const countWrongEl = document.getElementById('expCountWrong');
    if (countAllEl) countAllEl.innerText = history.length;
    if (countCorrectEl) countCorrectEl.innerText = correctCount;
    if (countWrongEl) countWrongEl.innerText = wrongCount;

    // Reset filter buttons active state
    const allBtn = document.getElementById('expFilterAllBtn');
    const correctBtn = document.getElementById('expFilterCorrectBtn');
    const wrongBtn = document.getElementById('expFilterWrongBtn');
    if (allBtn) {
        allBtn.className = 'btn btn-sm btn-info text-dark font-monospace fw-bold active';
    }
    if (correctBtn) {
        correctBtn.className = 'btn btn-sm btn-outline-success font-monospace fw-bold';
    }
    if (wrongBtn) {
        wrongBtn.className = 'btn btn-sm btn-outline-danger font-monospace fw-bold';
    }

    renderExplanationCards('all');

    const modalEl = document.getElementById('quizExplanationModal');
    if (modalEl) {
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }
}

function filterExplanationList(filterType, btnEl) {
    currentExpFilter = filterType;
    
    // Update active tab buttons styles
    const allBtn = document.getElementById('expFilterAllBtn');
    const correctBtn = document.getElementById('expFilterCorrectBtn');
    const wrongBtn = document.getElementById('expFilterWrongBtn');

    if (allBtn) allBtn.className = 'btn btn-sm btn-outline-info font-monospace fw-bold';
    if (correctBtn) correctBtn.className = 'btn btn-sm btn-outline-success font-monospace fw-bold';
    if (wrongBtn) wrongBtn.className = 'btn btn-sm btn-outline-danger font-monospace fw-bold';

    if (filterType === 'all' && allBtn) {
        allBtn.className = 'btn btn-sm btn-info text-dark font-monospace fw-bold active';
    } else if (filterType === 'correct' && correctBtn) {
        correctBtn.className = 'btn btn-sm btn-success text-white font-monospace fw-bold active';
    } else if (filterType === 'wrong' && wrongBtn) {
        wrongBtn.className = 'btn btn-sm btn-danger text-white font-monospace fw-bold active';
    }

    renderExplanationCards(filterType);
}

function renderExplanationCards(filterType) {
    const container = document.getElementById('explanationCardsContainer');
    if (!container) return;

    const history = quizState.answeredHistory || [];
    if (history.length === 0) {
        container.innerHTML = `
            <div class="text-center text-secondary py-4">
                <i class="fa-solid fa-clipboard-question fs-1 mb-2 text-muted"></i>
                <p class="small mb-0">Belum ada soal yang dijawab dalam sesi ini.</p>
            </div>
        `;
        return;
    }

    let filtered = history;
    if (filterType === 'correct') {
        filtered = history.filter(h => h.isCorrect);
    } else if (filterType === 'wrong') {
        filtered = history.filter(h => !h.isCorrect);
    }

    if (filtered.length === 0) {
        container.innerHTML = `
            <div class="text-center text-secondary py-4">
                <i class="fa-solid fa-circle-info fs-1 mb-2 text-muted"></i>
                <p class="small mb-0">Tidak ada soal dalam kategori filter ini.</p>
            </div>
        `;
        return;
    }

    const letters = ['A', 'B', 'C', 'D'];
    let html = '';

    filtered.forEach((item) => {
        const isUserCorrect = item.isCorrect;
        const statusBadge = isUserCorrect
            ? `<span class="badge bg-success text-white font-monospace style-tiny"><i class="fa-solid fa-check me-1"></i> BENAR</span>`
            : `<span class="badge bg-danger text-white font-monospace style-tiny"><i class="fa-solid fa-xmark me-1"></i> SALAH</span>`;

        const playerTag = quizState.mode === 'duel'
            ? `<span class="badge bg-purple bg-opacity-30 text-purple border border-purple border-opacity-30 font-monospace style-tiny">${item.playerAvatar} ${item.playerTurn}</span>`
            : '';

        let optionsListHtml = '<div class="d-flex flex-column gap-1.5 my-2.5">';
        item.options.forEach((optText, optIdx) => {
            const isCorrectOption = optIdx === item.correctIndex;
            const isSelectedOption = optIdx === item.userAnswerIndex;

            let optClass = 'bg-black border-secondary border-opacity-25 text-secondary';
            let badgeIcon = '';

            if (isCorrectOption) {
                optClass = 'bg-success bg-opacity-20 border-success text-success fw-bold';
                badgeIcon = '<span class="badge bg-success text-white ms-auto font-monospace style-tiny">Kunci Jawaban ✓</span>';
            } else if (isSelectedOption && !isUserCorrect) {
                optClass = 'bg-danger bg-opacity-20 border-danger text-danger fw-bold';
                badgeIcon = '<span class="badge bg-danger text-white ms-auto font-monospace style-tiny">Jawaban Kamu ✗</span>';
            }

            optionsListHtml += `
                <div class="p-2 px-2.5 rounded-3 border ${optClass} d-flex align-items-center gap-2 small">
                    <span class="fw-bold font-monospace">${letters[optIdx]}.</span>
                    <span class="flex-grow-1">${optText}</span>
                    ${badgeIcon}
                </div>
            `;
        });
        optionsListHtml += '</div>';

        html += `
            <div class="exp-card">
                <div class="d-flex align-items-start justify-content-between gap-2 mb-2 flex-wrap">
                    <div class="d-flex align-items-center gap-1.5 flex-wrap">
                        <span class="badge bg-purple font-monospace style-tiny">Soal #${item.number}</span>
                        <span class="badge bg-body-secondary text-secondary font-monospace style-tiny border border-secondary border-opacity-40">${item.category}</span>
                        ${playerTag}
                    </div>
                    <div>
                        ${statusBadge}
                    </div>
                </div>

                <div class="fw-bold text-white small mb-1.5 lh-base">
                    ${item.question}
                </div>

                ${optionsListHtml}

                <div class="exp-callout d-flex align-items-start gap-2 mt-2">
                    <i class="fa-solid fa-lightbulb text-warning mt-1 flex-shrink-0"></i>
                    <div>
                        <strong class="d-block text-warning small font-heading mb-0.5">Penjelasan & Fakta Satir:</strong>
                        <span class="small">${item.explanation}</span>
                    </div>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
}

// Initial loader on page load
window.addEventListener('DOMContentLoaded', () => {
    renderQuizLeaderboard();
});
</script>
<?= $this->endSection() ?>
