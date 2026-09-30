<?= $this->extend('layouts/master_public') ?>

<?= $this->section('content') ?>
<style>
/* =========================================================
   AKU HACKER: CYBER HACKER SIMULATOR - RETRO CRT MATRIX THEME
   ========================================================= */
:root {
    --hacker-green: #00ff66;
    --hacker-green-bright: #39ff14;
    --hacker-green-dim: #008f39;
    --hacker-green-glow: rgba(0, 255, 102, 0.45);
    --hacker-dark: #050b06;
    --hacker-panel: rgba(8, 18, 10, 0.95);
    --hacker-border: #00ff66;
    --hacker-border-dim: rgba(0, 255, 102, 0.25);
    --hacker-text-muted: #4ade80;
    --hacker-amber: #f59e0b;
    --hacker-red: #ef4444;
    --hacker-blue: #00e5ff;
}

.cyber-body {
    background-color: #020603;
    background-image: 
        linear-gradient(rgba(0, 255, 102, 0.035) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0, 255, 102, 0.035) 1px, transparent 1px);
    background-size: 24px 24px;
    color: var(--hacker-green);
    font-family: 'JetBrains Mono', 'Courier New', Consolas, monospace;
    position: relative;
    overflow-x: hidden;
}

/* CRT Scanline & Screen Vignette Overlay */
.crt-overlay {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
}
.crt-overlay::before {
    content: " ";
    display: block;
    position: absolute;
    top: 0; left: 0; bottom: 0; right: 0;
    background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.22) 50%), linear-gradient(90deg, rgba(255, 0, 0, 0.02), rgba(0, 255, 0, 0.01), rgba(0, 255, 0, 0.02));
    z-index: 1;
    background-size: 100% 3px, 6px 100%;
    pointer-events: none !important;
    opacity: 0.6;
}

/* Cyber Box & Borders */
.cyber-box {
    background: var(--hacker-panel);
    border: 1px solid var(--hacker-border);
    box-shadow: 0 0 15px rgba(0, 255, 102, 0.15), inset 0 0 15px rgba(0, 255, 102, 0.04);
    position: relative;
    z-index: 2;
}

.cyber-box-dim {
    background: rgba(6, 14, 8, 0.92);
    border: 1px solid var(--hacker-border-dim);
}

.cyber-header-bar {
    border-bottom: 1px solid var(--hacker-border);
    padding: 10px 16px;
    background: rgba(0, 255, 102, 0.07);
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.cyber-title {
    color: var(--hacker-green);
    font-size: 1.15rem;
    font-weight: 800;
    text-shadow: 0 0 8px var(--hacker-green-glow);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Status Badges & LED */
.led-live {
    width: 9px;
    height: 9px;
    background: #00ff66;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 8px #00ff66, 0 0 12px #00ff66;
    animation: blink-led 1.2s infinite ease-in-out;
}
@keyframes blink-led {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.3; transform: scale(0.85); }
}

.cyber-tag {
    font-size: 0.78rem;
    padding: 3px 8px;
    border: 1px solid var(--hacker-green);
    color: var(--hacker-green);
    background: rgba(0, 255, 102, 0.08);
    display: inline-flex;
    align-items: center;
    gap: 5px;
    letter-spacing: 0.5px;
}

.cyber-tag.warning {
    border-color: var(--hacker-amber);
    color: var(--hacker-amber);
    background: rgba(245, 158, 11, 0.08);
}
.cyber-tag.danger {
    border-color: var(--hacker-red);
    color: var(--hacker-red);
    background: rgba(239, 68, 68, 0.08);
}
.cyber-tag.info {
    border-color: var(--hacker-blue);
    color: var(--hacker-blue);
    background: rgba(0, 229, 255, 0.08);
}

/* Glowing Pulsing Button for Auto Guided Hack */
.btn-auto-guided {
    background: linear-gradient(135deg, #00ff66 0%, #00b347 100%) !important;
    color: #030a04 !important;
    border: 1px solid #39ff14 !important;
    box-shadow: 0 0 18px rgba(0, 255, 102, 0.65), 0 0 30px rgba(0, 255, 102, 0.35);
    font-weight: 900 !important;
    animation: pulse-auto 1.8s infinite ease-in-out;
    letter-spacing: 0.5px;
}
@keyframes pulse-auto {
    0%, 100% { transform: scale(1); box-shadow: 0 0 15px rgba(0, 255, 102, 0.6); }
    50% { transform: scale(1.03); box-shadow: 0 0 25px rgba(57, 255, 20, 0.9), 0 0 40px rgba(0, 255, 102, 0.5); }
}

/* 8-Stage Progress Tracker Breadcrumbs */
.stage-stepper-container {
    display: flex;
    flex-wrap: nowrap;
    overflow-x: auto;
    gap: 4px;
    padding: 6px 2px;
    scrollbar-width: thin;
    scrollbar-color: var(--hacker-green-dim) #020803;
}
.stage-step-item {
    flex: 1;
    min-width: 105px;
    padding: 6px 8px;
    background: rgba(0, 255, 102, 0.04);
    border: 1px solid var(--hacker-border-dim);
    font-size: 0.72rem;
    text-align: center;
    position: relative;
    transition: all 0.2s ease;
    user-select: none;
}
.stage-step-item.active {
    background: rgba(0, 255, 102, 0.2);
    border-color: var(--hacker-green);
    color: #ffffff;
    box-shadow: 0 0 10px rgba(0, 255, 102, 0.4);
    font-weight: bold;
}
.stage-step-item.done {
    background: rgba(0, 255, 102, 0.12);
    border-color: #00ff66;
    color: var(--hacker-green);
}
.stage-step-item.done::after {
    content: " ✓";
    color: #39ff14;
    font-weight: bold;
}

/* Interactive Navigation Tabs */
.cyber-nav-btn {
    background: transparent;
    border: 1px solid var(--hacker-border-dim);
    color: #4ade80;
    font-family: inherit;
    font-size: 0.82rem;
    font-weight: 700;
    padding: 8px 14px;
    letter-spacing: 1px;
    transition: all 0.2s ease;
    cursor: pointer !important;
    pointer-events: auto !important;
    position: relative;
    z-index: 15;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-transform: uppercase;
}
.cyber-nav-btn:hover {
    background: rgba(0, 255, 102, 0.15);
    border-color: var(--hacker-green);
    color: #fff;
    box-shadow: 0 0 10px var(--hacker-green-glow);
}
.cyber-nav-btn.active {
    background: var(--hacker-green);
    border-color: var(--hacker-green);
    color: #030a04 !important;
    font-weight: 900;
    box-shadow: 0 0 15px rgba(0, 255, 102, 0.5);
}

/* Interactive Cyber Buttons */
.btn-cyber {
    background: rgba(0, 255, 102, 0.1);
    border: 1px solid var(--hacker-green);
    color: var(--hacker-green);
    font-family: inherit;
    font-weight: 700;
    font-size: 0.82rem;
    padding: 6px 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.15s ease;
    cursor: pointer !important;
    pointer-events: auto !important;
    position: relative;
    z-index: 15;
    border-radius: 0px;
}
.btn-cyber:hover:not(:disabled) {
    background: var(--hacker-green);
    color: #050a06;
    box-shadow: 0 0 12px var(--hacker-green-glow);
}
.btn-cyber.active {
    background: var(--hacker-green);
    color: #030804;
    font-weight: 800;
}
.btn-cyber:disabled {
    opacity: 0.35;
    cursor: not-allowed;
    border-color: #335533;
    color: #447744;
}

.btn-cyber-amber {
    border-color: var(--hacker-amber);
    color: var(--hacker-amber);
    background: rgba(245, 158, 11, 0.1);
}
.btn-cyber-amber:hover:not(:disabled), .btn-cyber-amber.active {
    background: var(--hacker-amber);
    color: #000;
    box-shadow: 0 0 12px rgba(245, 158, 11, 0.5);
}

.btn-cyber-red {
    border-color: var(--hacker-red);
    color: var(--hacker-red);
    background: rgba(239, 68, 68, 0.1);
}
.btn-cyber-red:hover:not(:disabled) {
    background: var(--hacker-red);
    color: #fff;
    box-shadow: 0 0 12px rgba(239, 68, 68, 0.5);
}

.btn-cyber-blue {
    border-color: var(--hacker-blue);
    color: var(--hacker-blue);
    background: rgba(0, 229, 255, 0.1);
}
.btn-cyber-blue:hover:not(:disabled) {
    background: var(--hacker-blue);
    color: #000;
    box-shadow: 0 0 12px rgba(0, 229, 255, 0.5);
}

/* Form Controls / Terminal Inputs */
.cyber-input {
    background: #020803;
    border: 1px solid var(--hacker-border-dim);
    color: var(--hacker-green-bright);
    font-family: inherit;
    font-size: 0.85rem;
    padding: 8px 12px;
    width: 100%;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.cyber-input:focus {
    border-color: var(--hacker-green);
    box-shadow: 0 0 10px rgba(0, 255, 102, 0.3);
    color: #ffffff;
}
.cyber-input::placeholder {
    color: rgba(0, 255, 102, 0.35);
}

/* Terminal Log Display Window */
.cyber-log-window {
    background: #010602;
    border: 1px solid var(--hacker-border-dim);
    color: var(--hacker-green);
    font-size: 0.8rem;
    line-height: 1.45;
    padding: 10px 14px;
    height: 150px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: var(--hacker-green-dim) #020803;
}
.cyber-log-window::-webkit-scrollbar {
    width: 6px;
}
.cyber-log-window::-webkit-scrollbar-track {
    background: #020803;
}
.cyber-log-window::-webkit-scrollbar-thumb {
    background: var(--hacker-green-dim);
}

/* Password Cracker Progress Bar */
.cyber-progress-track {
    background: #020a04;
    border: 1px solid var(--hacker-border-dim);
    height: 22px;
    position: relative;
    overflow: hidden;
}
.cyber-progress-fill {
    background: linear-gradient(90deg, #008f39 0%, #00ff66 100%);
    height: 100%;
    width: 0%;
    transition: width 0.1s linear;
    box-shadow: 0 0 10px var(--hacker-green-glow);
}
.cyber-progress-label {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.72rem;
    font-weight: 700;
    color: #ffffff;
    text-shadow: 0 0 4px #000;
    letter-spacing: 1px;
}

/* Mission Cards */
.mission-card {
    background: rgba(0, 255, 102, 0.03);
    border: 1px solid var(--hacker-border-dim);
    padding: 10px 12px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.mission-card:hover {
    background: rgba(0, 255, 102, 0.1);
    border-color: var(--hacker-green);
    transform: translateX(3px);
}
.mission-card.active {
    background: rgba(0, 255, 102, 0.18);
    border-color: var(--hacker-green);
    border-left: 5px solid var(--hacker-green);
    box-shadow: inset 0 0 10px rgba(0, 255, 102, 0.1);
}

/* Step Guide Card */
.guide-step-card {
    background: rgba(0, 255, 102, 0.04);
    border: 1px solid var(--hacker-border-dim);
    padding: 12px;
    border-radius: 6px;
    height: 100%;
    text-align: left;
}
.guide-step-card .step-num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    background: var(--hacker-green);
    color: #000;
    font-weight: bold;
    border-radius: 50%;
    font-size: 0.75rem;
    margin-bottom: 6px;
}

/* Simulated Browser Preview in Deface Studio */
.sim-browser {
    background: #0f172a;
    border: 2px solid #334155;
    border-radius: 8px;
    overflow: hidden;
}
.sim-browser-topbar {
    background: #1e293b;
    padding: 6px 12px;
    display: flex;
    align-items: center;
    gap: 8px;
    border-bottom: 1px solid #334155;
}
.sim-dot {
    width: 10px; height: 10px; border-radius: 50%; display: inline-block;
}
.sim-url-bar {
    background: #090d16;
    color: #94a3b8;
    font-size: 0.75rem;
    padding: 3px 12px;
    border-radius: 4px;
    flex-grow: 1;
}

/* Defaced Glitch State */
.deface-glitch-text {
    text-shadow: 2px 2px #ef4444, -2px -2px #00e5ff;
    animation: glitch 1.5s infinite;
}
@keyframes glitch {
    0%, 100% { transform: translate(0); }
    20% { transform: translate(-2px, 2px); }
    40% { transform: translate(-1px, -1px); }
    60% { transform: translate(2px, 1px); }
    80% { transform: translate(1px, -2px); }
}

/* Interactive Network Canvas */
#networkCanvas {
    background: #020603;
    display: block;
    width: 100%;
    height: 380px;
    cursor: crosshair;
}

/* Command Shell Terminal */
.shell-prompt {
    color: var(--hacker-green-bright);
    font-weight: 800;
}
.shell-cursor {
    display: inline-block;
    width: 8px;
    height: 14px;
    background: var(--hacker-green);
    animation: blink-cursor 0.9s infinite;
    vertical-align: middle;
    margin-left: 2px;
}
@keyframes blink-cursor {
    0%, 100% { opacity: 1; }
    50% { opacity: 0; }
}

/* Mobile Quick Bar for commands */
.quick-chip {
    background: rgba(0, 255, 102, 0.08);
    border: 1px solid var(--hacker-border-dim);
    color: var(--hacker-green);
    font-size: 0.75rem;
    padding: 4px 8px;
    cursor: pointer;
    transition: all 0.15s ease;
    user-select: none;
    white-space: nowrap;
}
.quick-chip:hover {
    background: var(--hacker-green);
    color: #020603;
}
</style>

<div class="cyber-body py-3 py-lg-4">
    <div class="container-fluid px-2 px-md-4 max-w-1400">

        <!-- ==========================================
             TOP HUD / OPERATIONAL STATUS HEADER
             ========================================== -->
        <div class="cyber-box crt-overlay mb-3">
            <div class="cyber-header-bar">
                <div class="d-flex align-items-center gap-2">
                    <span class="led-live"></span>
                    <h2 class="cyber-title">
                        <i class="fa-solid fa-user-secret"></i> CYBERHACK // SIMULATOR V4.5
                    </h2>
                </div>

                <!-- Status Elements -->
                <div class="d-flex flex-wrap align-items-center gap-2 gap-md-3 small">
                    <div class="cyber-tag">
                        <i class="fa-solid fa-signal"></i> STATUS: <span id="hudStatus" class="fw-bold text-white">CONNECTED</span>
                    </div>
                    <div class="cyber-tag">
                        <i class="fa-solid fa-network-wired"></i> TARGET: <span id="hudTargetIP" class="fw-bold text-white">192.168.1.1</span>
                    </div>
                    <div class="cyber-tag warning">
                        <i class="fa-solid fa-shield-halved"></i> SECURITY: <span id="hudSecLevel" class="fw-bold text-warning">LEVEL 1</span>
                    </div>
                    <div class="cyber-tag">
                        <i class="fa-solid fa-trophy"></i> XP: <span id="hudScore" class="fw-bold text-white">0</span>
                    </div>

                    <!-- 1-Click Auto Infiltration -->
                    <button class="btn-cyber btn-auto-guided py-1 px-3" onclick="HackerApp.triggerFullAutoHack()" title="Mode Otomatis 8 Tahap Realistis">
                        <i class="fa-solid fa-bolt me-1"></i> FULL AUTO HACK (8 TAHAP)
                    </button>

                    <!-- Tutorial Button -->
                    <button class="btn-cyber-blue py-1 px-2" onclick="HackerApp.openTutorialModal()" title="Tutorial & Panduan Main">
                        <i class="fa-solid fa-circle-question me-1"></i> PANDUAN & EDUKASI
                    </button>

                    <!-- Audio Toggle -->
                    <button id="btnAudioToggle" class="btn-cyber py-1 px-2" title="Sound Effects">
                        <i class="fa-solid fa-volume-high" id="audioIcon"></i>
                    </button>

                    <!-- Back to Hub -->
                    <a href="<?= base_url('mini-game') ?>" class="btn-cyber-red py-1 px-2 text-decoration-none">
                        <i class="fa-solid fa-right-from-bracket"></i> EXIT
                    </a>
                </div>
            </div>

            <!-- MODULE NAVIGATION TABS (Ultra Responsive) -->
            <div class="p-2 border-top border-secondary border-opacity-25 d-flex flex-wrap gap-2 justify-content-between align-items-center bg-black bg-opacity-40">
                <div class="d-flex flex-wrap gap-1 gap-md-2" id="moduleNavTabs">
                    <button class="cyber-nav-btn active" data-tab="dashboard">
                        <i class="fa-solid fa-gauge-high"></i> Dashboard (20 Misi)
                    </button>
                    <button class="cyber-nav-btn" data-tab="decryptor">
                        <i class="fa-solid fa-unlock-keyhole"></i> Decrypt & Cracker
                    </button>
                    <button class="cyber-nav-btn" data-tab="defacestudio">
                        <i class="fa-solid fa-paintbrush"></i> Deface & Recovery Studio
                    </button>
                    <button class="cyber-nav-btn" data-tab="terminal">
                        <i class="fa-solid fa-terminal"></i> Terminal CLI
                    </button>
                    <button class="cyber-nav-btn" data-tab="network">
                        <i class="fa-solid fa-diagram-project"></i> Network Map
                    </button>
                    <button class="cyber-nav-btn" data-tab="quickgame">
                        <i class="fa-solid fa-bolt"></i> Quick Hack
                    </button>
                </div>

                <div class="d-flex align-items-center gap-2 small text-secondary">
                    <span>Hacker Rank:</span>
                    <span id="rankBadge" class="cyber-tag text-white font-monospace">Script Kiddie</span>
                </div>
            </div>
        </div>

        <!-- ==========================================
             REALISTIC 8-STAGE INFILTRATION KILL-CHAIN
             ========================================== -->
        <div class="cyber-box p-3 mb-3 bg-black bg-opacity-80 border-success border-opacity-40">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-2">
                <div>
                    <div class="d-flex align-items-center gap-2 small mb-1">
                        <span class="badge bg-warning text-dark fw-bold font-monospace"><i class="fa-solid fa-shield-virus"></i> CYBER INFILTRATION PIPELINE:</span>
                        <span id="guidedStepDesc" class="text-white fw-bold">Tahap 1: Jalankan Network Recon & Nmap Scan pada target IP.</span>
                    </div>
                    <div class="small text-secondary" id="missionLiveHint">
                        Target: 192.168.1.1 (Recon &rarr; WAF Bypass &rarr; CVE Exploit &rarr; Decrypt &rarr; Hash Analysis &rarr; Cracking &rarr; Root Escalation)
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <!-- One-Click Guided Next Step Button -->
                    <button id="btnGuidedNextStep" class="btn-cyber btn-auto-guided py-2 px-3 fs-6 d-flex align-items-center gap-2" onclick="HackerApp.execGuidedNextStep()">
                        <i class="fa-solid fa-wand-magic-sparkles"></i> <span id="guidedStepBtnText">1. Jalankan Recon & Nmap</span>
                    </button>

                    <!-- Instant Complete Auto-Hack -->
                    <button class="btn-cyber-amber py-2 px-3" onclick="HackerApp.triggerFullAutoHack()" title="Otomatiskan seluruh 8 tahap sekaligus">
                        <i class="fa-solid fa-forward-fast"></i> Auto-Hack All
                    </button>
                </div>
            </div>

            <!-- 8-Stage Progress Breadcrumb Stepper -->
            <div class="stage-stepper-container pt-2 border-top border-success border-opacity-20" id="stageStepper">
                <div class="stage-step-item active" data-step-id="0">1. Recon (Nmap)</div>
                <div class="stage-step-item" data-step-id="1">2. WAF Bypass</div>
                <div class="stage-step-item" data-step-id="2">3. CVE Exploit</div>
                <div class="stage-step-item" data-step-id="3">4. Decrypt Key</div>
                <div class="stage-step-item" data-step-id="4">5. Hash Profile</div>
                <div class="stage-step-item" data-step-id="5">6. Password Crack</div>
                <div class="stage-step-item" data-step-id="6">7. Root Privilege</div>
                <div class="stage-step-item" data-step-id="7">8. Exfiltration</div>
            </div>
        </div>

        <!-- ==========================================
             TAB 1: DASHBOARD & 20 ACTIVE MISSIONS
             ========================================== -->
        <div id="tab-dashboard" class="tab-pane-content">
            <div class="row g-3">
                <!-- Left Column: Active Missions (ALL 20 UNLOCKED & FILTERABLE) -->
                <div class="col-lg-7">
                    <div class="cyber-box h-100 p-3">
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 pb-2 border-bottom border-success border-opacity-25 gap-2">
                            <div>
                                <h4 class="m-0 fs-6 fw-bold text-uppercase d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-list-check"></i> Cyber Operations & 20 Missions
                                    <span class="badge bg-success bg-opacity-25 text-success font-monospace ms-1" id="missionProgressBadge">Misi: 1/20</span>
                                </h4>
                                <small class="text-secondary style-tiny">20 Misi simulasi (Red Team Infiltration & White Hat Cyber Defense Recovery).</small>
                            </div>
                            
                            <!-- Role Filter Buttons -->
                            <div class="d-flex gap-1">
                                <button type="button" class="btn-cyber py-0 px-2 active small" data-filter="all" onclick="HackerApp.filterMissions('all')">All (20)</button>
                                <button type="button" class="btn-cyber-red py-0 px-2 small" data-filter="red" onclick="HackerApp.filterMissions('red')">🔴 Red Team</button>
                                <button type="button" class="btn-cyber-blue py-0 px-2 small" data-filter="white" onclick="HackerApp.filterMissions('white')">🛡️ White Hat</button>
                            </div>
                        </div>

                        <!-- Missions List (Scrollable 20 Missions) -->
                        <div class="d-flex flex-column gap-2" id="missionsContainer" style="max-height: 290px; overflow-y: auto;">
                            <!-- Populated dynamically by JS -->
                        </div>

                        <!-- Current Mission Detail & Edu Card -->
                        <div class="mt-3 p-3 cyber-box-dim border border-success border-opacity-50">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="badge bg-success bg-opacity-25 text-success font-monospace" id="currentMissionTag">MISSION #1</span>
                                    <span class="badge bg-body-secondary text-white font-monospace ms-1" id="currentMissionCategory">WiFi Infiltration</span>
                                    <span class="badge font-monospace ms-1" id="currentMissionRole">🔴 RED TEAM</span>
                                </div>
                                <span class="small text-warning" id="currentMissionReward"><i class="fa-solid fa-coins"></i> +50 XP</span>
                            </div>
                            <h5 class="fw-bold text-white fs-6 mb-1" id="currentMissionTitle">Operation Backdoor (WiFi SMANIT)</h5>
                            <p class="small text-secondary mb-2" id="currentMissionDesc">
                                Dekripsi token otentikasi target untuk mendapatkan Target Hash, lalu pecahkan password administrator menggunakan modul Password Cracker!
                            </p>

                            <!-- Cyber Edu Insight Box -->
                            <div class="p-2 mb-3 bg-black bg-opacity-60 border border-info border-opacity-25 rounded small">
                                <div class="text-info fw-bold style-tiny mb-1"><i class="fa-solid fa-graduation-cap"></i> EDUKASI & CARA PENCEGAHAN SIBER:</div>
                                <div class="text-secondary style-tiny" id="currentMissionEdu">
                                    Router yang menggunakan enkripsi usang rentan disadap. Di dunia nyata, selalu gunakan WPA3 Enterprise dan ganti kredensial default admin!
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="btn-cyber" onclick="HackerApp.switchTab('decryptor')">
                                        <i class="fa-solid fa-unlock"></i> Cracker & Decryptor
                                    </button>
                                    <button class="btn-cyber" onclick="HackerApp.switchTab('defacestudio')">
                                        <i class="fa-solid fa-paintbrush"></i> Deface Studio
                                    </button>
                                    <button class="btn-cyber" onclick="HackerApp.switchTab('terminal')">
                                        <i class="fa-solid fa-terminal"></i> Terminal CLI
                                    </button>
                                    <button type="button" class="btn-cyber-red btn-auto-guided py-1 px-2.5 small fw-bold d-none" id="btnMissionDefaceShortcut" onclick="HackerApp.triggerAutoDeface()">
                                        <i class="fa-solid fa-bolt text-warning me-1"></i> ⚡ Auto-Deface Target
                                    </button>
                                    <button type="button" class="btn-cyber-blue btn-auto-guided py-1 px-2.5 small fw-bold d-none" id="btnMissionRecoveryShortcut" onclick="HackerApp.triggerAutoRecovery()">
                                        <i class="fa-solid fa-bolt text-warning me-1"></i> ⚡ Auto-Recovery Target
                                    </button>
                                </div>
                                <button class="btn-cyber-amber" id="btnNextMission" onclick="HackerApp.completeCurrentMission()" disabled>
                                    <i class="fa-solid fa-shield-virus"></i> Selesaikan Misi Ini
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: System Status & Activity Log -->
                <div class="col-lg-5">
                    <div class="cyber-box p-3 mb-3">
                        <h4 class="fs-6 fw-bold text-uppercase mb-3 pb-2 border-bottom border-success border-opacity-25 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-microchip"></i> System Status & Hardware
                        </h4>
                        <div class="row g-2 text-center mb-3">
                            <div class="col-6">
                                <div class="p-2 cyber-box-dim">
                                    <div class="small text-secondary">CPU USAGE</div>
                                    <div class="fs-5 fw-bold text-white" id="statCpu">38%</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 cyber-box-dim">
                                    <div class="small text-secondary">MEMORY</div>
                                    <div class="fs-5 fw-bold text-white" id="statMem">1.4 GB / 8 GB</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 cyber-box-dim">
                                    <div class="small text-secondary">NETWORK TRAFFIC</div>
                                    <div class="fs-5 fw-bold text-success" id="statTraffic">6.8 MB/s</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 cyber-box-dim">
                                    <div class="small text-secondary">ACTIVE CONNECTIONS</div>
                                    <div class="fs-5 fw-bold text-info" id="statConnections">14 Sockets</div>
                                </div>
                            </div>
                        </div>

                        <!-- System Health Bar -->
                        <div class="mb-2">
                            <div class="d-flex justify-content-between small text-secondary mb-1">
                                <span>SYSTEM HEALTH & ANONYMITY</span>
                                <span class="text-success fw-bold" id="statHealth">96%</span>
                            </div>
                            <div class="cyber-progress-track">
                                <div class="cyber-progress-fill" id="healthFill" style="width: 96%;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Activity Log -->
                    <div class="cyber-box p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h4 class="fs-6 fw-bold text-uppercase m-0 d-flex align-items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left"></i> Realtime Activity Log
                            </h4>
                            <button class="btn-cyber py-0 px-2 small" onclick="HackerApp.clearLog('dashboardLog')">Clear</button>
                        </div>
                        <div class="cyber-log-window" id="dashboardLog" style="height: 140px;">
                            <div>[SYS_INIT] Cyber Hacker Simulator kernel loaded.</div>
                            <div>[CONN] Target IP assigned: 192.168.1.1 (Level 1 Defense).</div>
                            <div>[INFO] Cryptographic engines ready for payload injection.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==========================================
             TAB 2: DECRYPT & PASSWORD CRACKER (MAIN SPLIT)
             ========================================== -->
        <div id="tab-decryptor" class="tab-pane-content d-none">
            <div class="row g-3">
                <!-- Panel Kiri: Password Cracker -->
                <div class="col-lg-6">
                    <div class="cyber-box p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-success border-opacity-25">
                            <h4 class="fs-6 fw-bold text-uppercase m-0 d-flex align-items-center gap-2">
                                <i class="fa-solid fa-key"></i> Password Cracker
                            </h4>
                            <span class="cyber-tag" id="crackerSpeedBadge">Speed: 185k h/s</span>
                        </div>

                        <!-- Target Hash Input -->
                        <div class="mb-3">
                            <label class="small text-secondary mb-1 d-flex justify-content-between">
                                <span>TARGET HASH:</span>
                                <a href="javascript:void(0)" class="text-success small text-decoration-none" onclick="HackerApp.loadMissionHash()">
                                    <i class="fa-solid fa-paste"></i> Ambil dari Misi Aktif
                                </a>
                            </label>
                            <input type="text" id="inputTargetHash" class="cyber-input" value="5f4dcc3b5aa765d61d8327deb882cf99" placeholder="Enter alphanumeric hash string...">
                        </div>

                        <!-- Hash Type Selector -->
                        <div class="mb-3">
                            <label class="small text-secondary mb-1">HASH TYPE (ALGORITMA):</label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn-cyber flex-fill active" data-hash-type="MD5" onclick="HackerApp.setHashType('MD5')">MD5 (32-hex)</button>
                                <button type="button" class="btn-cyber flex-fill" data-hash-type="SHA1" onclick="HackerApp.setHashType('SHA1')">SHA1 (40-hex)</button>
                                <button type="button" class="btn-cyber flex-fill" data-hash-type="SHA256" onclick="HackerApp.setHashType('SHA256')">SHA256 (64-hex)</button>
                            </div>
                        </div>

                        <!-- Attack Method Selector -->
                        <div class="mb-3">
                            <label class="small text-secondary mb-1">ATTACK METHOD:</label>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn-cyber flex-fill active" data-attack-method="Dictionary" onclick="HackerApp.setAttackMethod('Dictionary')">
                                    <i class="fa-solid fa-book"></i> Dictionary
                                </button>
                                <button type="button" class="btn-cyber flex-fill" data-attack-method="Brute Force" onclick="HackerApp.setAttackMethod('Brute Force')">
                                    <i class="fa-solid fa-hammer"></i> Brute Force
                                </button>
                                <button type="button" class="btn-cyber flex-fill" data-attack-method="Rainbow Table" onclick="HackerApp.setAttackMethod('Rainbow Table')">
                                    <i class="fa-solid fa-rainbow"></i> Rainbow Table
                                </button>
                            </div>
                        </div>

                        <!-- Cracking Action Buttons -->
                        <div class="d-flex gap-2 mb-3">
                            <button class="btn-cyber flex-fill py-2 fw-bold" id="btnStartCrack" onclick="HackerApp.startCracking()">
                                <i class="fa-solid fa-play"></i> Start Cracking
                            </button>
                            <button class="btn-cyber-amber py-2 px-3 fw-bold" id="btnStopCrack" onclick="HackerApp.stopCracking()" disabled>
                                <i class="fa-solid fa-stop"></i> Stop
                            </button>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-3">
                            <div class="cyber-progress-track">
                                <div class="cyber-progress-fill" id="crackProgressFill"></div>
                                <div class="cyber-progress-label" id="crackProgressLabel">Ready to crack (0%)</div>
                            </div>
                        </div>

                        <!-- Cracker Log Output -->
                        <div>
                            <div class="cyber-log-window" id="crackerLog" style="height: 160px;">
                                <div>Password cracker initialized. Enter a hash to begin.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel Kanan: Encryption & Decryption Tools -->
                <div class="col-lg-6">
                    <div class="cyber-box p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-success border-opacity-25">
                            <h4 class="fs-6 fw-bold text-uppercase m-0 d-flex align-items-center gap-2">
                                <i class="fa-solid fa-shield-halved"></i> Encryption & Decrypt Tools
                            </h4>
                            <span class="cyber-tag">Ciphers Active</span>
                        </div>

                        <!-- Input Text Area -->
                        <div class="mb-3">
                            <label class="small text-secondary mb-1 d-flex justify-content-between">
                                <span>INPUT TEXT / CIPHERTEXT:</span>
                                <a href="javascript:void(0)" class="text-success small text-decoration-none" onclick="HackerApp.loadInterceptedCipher()">
                                    <i class="fa-solid fa-satellite-dish"></i> Muat Sandi Sadapan
                                </a>
                            </label>
                            <textarea id="cipherInputText" rows="3" class="cyber-input" placeholder="Enter text to encrypt/decrypt or paste intercepted packets..."></textarea>
                        </div>

                        <!-- Encryption Method Selector -->
                        <div class="mb-3">
                            <label class="small text-secondary mb-1">ENCRYPTION METHOD:</label>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn-cyber flex-fill active" data-cipher-method="Base64" onclick="HackerApp.setCipherMethod('Base64')">Base64</button>
                                <button type="button" class="btn-cyber flex-fill" data-cipher-method="Caesar" onclick="HackerApp.setCipherMethod('Caesar')">Caesar (ROT)</button>
                                <button type="button" class="btn-cyber flex-fill" data-cipher-method="AES" onclick="HackerApp.setCipherMethod('AES')">AES</button>
                                <button type="button" class="btn-cyber flex-fill" data-cipher-method="RSA" onclick="HackerApp.setCipherMethod('RSA')">RSA</button>
                            </div>
                        </div>

                        <!-- Key Input -->
                        <div class="mb-3">
                            <label class="small text-secondary mb-1" id="cipherKeyLabel">KEY (IF REQUIRED):</label>
                            <input type="text" id="cipherKeyInput" class="cyber-input" placeholder="Encryption key or shift number (e.g. 7 or secret_key)..." value="3">
                        </div>

                        <!-- Actions: Encrypt & Decrypt -->
                        <div class="d-flex gap-2 mb-3">
                            <button class="btn-cyber flex-fill py-2" onclick="HackerApp.processCipher('encrypt')">
                                <i class="fa-solid fa-lock"></i> Encrypt
                            </button>
                            <button class="btn-cyber flex-fill py-2" onclick="HackerApp.processCipher('decrypt')">
                                <i class="fa-solid fa-lock-open"></i> Decrypt
                            </button>
                        </div>

                        <!-- Output Terminal Window -->
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small text-secondary">DECRYPTED / ENCRYPTED RESULT:</span>
                                <button class="btn-cyber py-0 px-2 small" onclick="HackerApp.sendResultToCracker()">
                                    <i class="fa-solid fa-arrow-left"></i> Send to Cracker
                                </button>
                            </div>
                            <div class="cyber-log-window" id="cipherOutputLog" style="height: 160px;">
                                <div>Encryption tool ready. Select a method and operation.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==========================================
             TAB 3: WEBSITE DEFACEMENT & RECOVERY STUDIO
             ========================================== -->
        <div id="tab-defacestudio" class="tab-pane-content d-none">
            <div class="row g-3">
                <!-- Left Controls Panel -->
                <div class="col-lg-5">
                    <div class="cyber-box p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-success border-opacity-25">
                            <h4 class="fs-6 fw-bold text-uppercase m-0 d-flex align-items-center gap-2">
                                <i class="fa-solid fa-paintbrush"></i> Web Deface & Recovery Studio
                            </h4>
                            <span class="cyber-tag danger" id="studioModeTag">Mode: Red Team</span>
                        </div>

                        <!-- Studio Mode Switcher -->
                        <div class="d-flex gap-2 mb-3">
                            <button class="btn-cyber-red flex-fill active py-2 fw-bold" id="btnStudioDeface" onclick="HackerApp.setStudioMode('deface')">
                                <i class="fa-solid fa-skull"></i> 🔴 Mode Deface (Red Team)
                            </button>
                            <button class="btn-cyber-blue flex-fill py-2 fw-bold" id="btnStudioRecovery" onclick="HackerApp.setStudioMode('recovery')">
                                <i class="fa-solid fa-shield-heart"></i> 🛡️ Mode Recovery (White Hat)
                            </button>
                        </div>

                        <!-- ==========================================
                             A. DEFACE CONTROLS (RED TEAM OFFENSIVE)
                             ========================================== -->
                        <div id="defaceControlBox">
                            <div class="p-2 mb-2 bg-danger bg-opacity-10 border border-danger border-opacity-30 rounded small text-danger">
                                <i class="fa-solid fa-triangle-exclamation me-1"></i> Selesaikan <strong>4 Rintangan Penetrasi</strong> berikut secara berurutan untuk meluncurkan defacement ke server target!
                            </div>

                            <!-- 1-Click Guided Auto Deface Button -->
                            <button type="button" class="btn-cyber-red btn-auto-guided w-100 mb-3 py-2 fw-bold d-flex align-items-center justify-content-center gap-2" id="btnAutoDeface" onclick="HackerApp.triggerAutoDeface()">
                                <i class="fa-solid fa-bolt text-warning"></i> ⚡ AUTO-SOLVE DEFACE INFILTRATION (4 TAHAP)
                            </button>

                            <!-- Deface Stage Accordion / Cards -->
                            <div class="d-flex flex-column gap-2 mb-3">
                                
                                <!-- Step 1: Port Checksum Math -->
                                <div class="p-2 cyber-box-dim border border-secondary border-opacity-30 rounded" id="defaceStepBox1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong class="text-white small"><i class="fa-solid fa-calculator text-warning me-1"></i> 1. Bypass Port Checksum (Matematika)</strong>
                                        <span class="badge bg-secondary" id="defaceBadge1">Pending</span>
                                    </div>
                                    <p class="text-secondary style-tiny mb-2">
                                        Firewall memfilter port target. Hitung nilai offset $X: <br>
                                        <span class="text-warning font-monospace">Port HTTP (80) + SSH (22) - Offset ($X) = 75</span>
                                    </p>
                                    <div class="d-flex gap-2">
                                        <input type="number" id="defaceMathAnswer" class="cyber-input py-1 text-center font-monospace" placeholder="Nilai X?" style="max-width: 120px;">
                                        <button type="button" class="btn-cyber py-1 px-3 small flex-fill" id="btnDefaceStep1" onclick="HackerApp.defaceVerifyStep1()">
                                            <i class="fa-solid fa-key me-1"></i> Verifikasi Checksum
                                        </button>
                                    </div>
                                </div>

                                <!-- Step 2: SQL Injection Authentication Bypass -->
                                <div class="p-2 cyber-box-dim border border-secondary border-opacity-30 rounded opacity-60" id="defaceStepBox2">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong class="text-white small"><i class="fa-solid fa-database text-info me-1"></i> 2. Eksploitasi SQL Injection Login</strong>
                                        <span class="badge bg-secondary" id="defaceBadge2">Terkunci</span>
                                    </div>
                                    <p class="text-secondary style-tiny mb-2">Pilih query SQL injection yang tepat untuk membobol login form <code>/admin/login.php</code>:</p>
                                    <div class="small mb-2 d-flex flex-column gap-1">
                                        <label class="d-flex align-items-center gap-2 style-tiny text-secondary">
                                            <input type="radio" name="defaceSqliOption" value="sqli" disabled>
                                            <span class="font-monospace text-warning">' OR '1'='1' -- - (Auth Bypass)</span>
                                        </label>
                                        <label class="d-flex align-items-center gap-2 style-tiny text-secondary">
                                            <input type="radio" name="defaceSqliOption" value="xss" disabled>
                                            <span class="font-monospace">&lt;script&gt;alert(1)&lt;/script&gt; (XSS Vector)</span>
                                        </label>
                                        <label class="d-flex align-items-center gap-2 style-tiny text-secondary">
                                            <input type="radio" name="defaceSqliOption" value="select" disabled>
                                            <span class="font-monospace">SELECT * FROM public_posts; (Read Only)</span>
                                        </label>
                                    </div>
                                    <button type="button" class="btn-cyber py-1 px-3 small w-100" id="btnDefaceStep2" onclick="HackerApp.defaceVerifyStep2()" disabled>
                                        <i class="fa-solid fa-syringe me-1"></i> Injeksi Query SQL
                                    </button>
                                </div>

                                <!-- Step 3: Web Shell MIME Header Bypass -->
                                <div class="p-2 cyber-box-dim border border-secondary border-opacity-30 rounded opacity-60" id="defaceStepBox3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong class="text-white small"><i class="fa-solid fa-file-code text-danger me-1"></i> 3. Bypass Security Filter Upload (Agent)</strong>
                                        <span class="badge bg-secondary" id="defaceBadge3">Terkunci</span>
                                    </div>
                                    <p class="text-secondary style-tiny mb-2">Server menolak ekstensi <code>.php</code>. Trik apa yang dipakai untuk mengelabui filter MIME?</p>
                                    <div class="small mb-2 d-flex flex-column gap-1">
                                        <label class="d-flex align-items-center gap-2 style-tiny text-secondary">
                                            <input type="radio" name="defaceMimeOption" value="magic" disabled>
                                            <span class="font-monospace text-warning">Magic Bytes 'GIF89a;' + shell.php.jpg</span>
                                        </label>
                                        <label class="d-flex align-items-center gap-2 style-tiny text-secondary">
                                            <input type="radio" name="defaceMimeOption" value="txt" disabled>
                                            <span class="font-monospace">Ganti ekstensi ke shell.txt biasa</span>
                                        </label>
                                    </div>
                                    <button type="button" class="btn-cyber py-1 px-3 small w-100" id="btnDefaceStep3" onclick="HackerApp.defaceVerifyStep3()" disabled>
                                        <i class="fa-solid fa-upload me-1"></i> Upload Agent Payload
                                    </button>
                                </div>

                                <!-- Step 4: Visual Script Designer & Overwrite -->
                                <div class="p-2 cyber-box-dim border border-secondary border-opacity-30 rounded opacity-60" id="defaceStepBox4">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong class="text-white small"><i class="fa-solid fa-palette text-success me-1"></i> 4. Desain & Injeksi Halaman Deface</strong>
                                        <span class="badge bg-secondary" id="defaceBadge4">Terkunci</span>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <label class="style-tiny text-secondary mb-1">HACKER MONIKER / NICKNAME:</label>
                                        <input type="text" id="defaceHackerName" class="cyber-input py-1" value="x-Shadow_MMC" placeholder="Your hacker name...">
                                    </div>
                                    <div class="mb-2">
                                        <label class="style-tiny text-secondary mb-1">DEFACE HEADLINE TITLE:</label>
                                        <input type="text" id="defaceTitle" class="cyber-input py-1" value="HACKED BY MMC CYBER SQUAD" placeholder="Headline title...">
                                    </div>
                                    <div class="mb-2">
                                        <label class="style-tiny text-secondary mb-1">PILIHAN TEMA TAMPILAN:</label>
                                        <select id="defaceVisualTheme" class="cyber-input form-select bg-black text-warning border-warning border-opacity-50 py-1 style-tiny">
                                            <option value="matrix">🟢 Neon Matrix Glitch (Cyber Retro)</option>
                                            <option value="blood">🔴 Blood Red Chaos (Dark Skull)</option>
                                            <option value="synthwave">🟣 Synthwave Neon (Deep Purple)</option>
                                            <option value="gold">🟡 Golden Anon Syndicate</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="style-tiny text-secondary mb-1">DEFACE MEMO / GREETZ:</label>
                                        <textarea id="defaceMessage" rows="2" class="cyber-input style-tiny" placeholder="Enter deface memo...">Security is an illusion. Your defenses have fallen before the Multimedia Cyber Team! Fix your security before we return.</textarea>
                                    </div>

                                    <button class="btn-cyber-red w-100 py-2 fw-bold" id="btnExecuteDeface" onclick="HackerApp.injectDefacePayload()" disabled>
                                        <i class="fa-solid fa-skull-crossbones me-1"></i> ☠️ INJEKSI & OVERWRITE ROOT INDEX.HTML (+100 XP)
                                    </button>
                                </div>

                            </div>
                        </div>

                        <!-- ==========================================
                             B. RECOVERY CONTROLS (WHITE HAT DEFENSIVE)
                             ========================================== -->
                        <div id="recoveryControlBox" class="d-none">
                            <div class="p-2 mb-2 bg-info bg-opacity-10 border border-info border-opacity-30 rounded small text-info">
                                <i class="fa-solid fa-shield-virus me-1"></i> Website target telah disusupi! Selesaikan <strong>4 Tahap Incident Response</strong> berikut untuk memulihkan portal.
                            </div>

                            <!-- 1-Click Guided Auto Recovery Button -->
                            <button type="button" class="btn-cyber-blue btn-auto-guided w-100 mb-3 py-2 fw-bold d-flex align-items-center justify-content-center gap-2" id="btnAutoRecovery" onclick="HackerApp.triggerAutoRecovery()">
                                <i class="fa-solid fa-bolt text-warning"></i> ⚡ AUTO-SOLVE INCIDENT RECOVERY (5 TAHAP)
                            </button>

                            <div class="d-flex flex-column gap-2 mb-3">
                                
                                <!-- Step 1: Forensic Log Analysis & IP Isolasi -->
                                <div class="p-2 cyber-box-dim border border-secondary border-opacity-30 rounded" id="recStepBox1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong class="text-white small"><i class="fa-solid fa-magnifying-glass text-info me-1"></i> 1. Analisis Log & Isolasi IP Penyerang</strong>
                                        <span class="badge bg-secondary" id="recBadge1">Pending</span>
                                    </div>
                                    <div class="p-1 mb-2 bg-black rounded font-monospace style-tiny text-secondary border border-secondary border-opacity-20" style="font-size: 0.7rem; line-height: 1.3;">
                                        [13:42:01] 192.168.1.55 GET /index.php 200<br>
                                        [13:42:09] <span class="text-danger">185.220.101.99</span> POST /login.php?user=admin'OR'1'='1 302<br>
                                        [13:42:15] <span class="text-danger">185.220.101.99</span> POST /uploads/agent_sync.json?action=sync 200
                                    </div>
                                    <p class="text-secondary style-tiny mb-2">Ketik IP penyerang yang mengeksekusi webshell untuk di-drop di firewall:</p>
                                    <div class="d-flex gap-2">
                                        <input type="text" id="recAttackerIP" class="cyber-input py-1 font-monospace" placeholder="185.220.xxx.xx">
                                        <button type="button" class="btn-cyber py-1 px-3 small flex-fill" id="btnRecStep1" onclick="HackerApp.recVerifyStep1()">
                                            <i class="fa-solid fa-ban me-1"></i> Blokir IP
                                        </button>
                                    </div>
                                </div>

                                <!-- Step 2: Deteksi & Hapus Web Shell Backdoor -->
                                <div class="p-2 cyber-box-dim border border-secondary border-opacity-30 rounded opacity-60" id="recStepBox2">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong class="text-white small"><i class="fa-solid fa-trash-can text-danger me-1"></i> 2. Deteksi & Hapus Web Shell Backdoor</strong>
                                        <span class="badge bg-secondary" id="recBadge2">Terkunci</span>
                                    </div>
                                    <p class="text-secondary style-tiny mb-2">Pilih file mencurigakan yang harus dihapus (centang file berbahaya):</p>
                                    <div class="small mb-2 d-flex flex-column gap-1">
                                        <label class="d-flex align-items-center gap-2 style-tiny text-secondary">
                                            <input type="checkbox" id="recFileAgent" disabled>
                                            <span class="font-monospace text-danger">/var/www/html/uploads/agent_relay.dat (Suspicious Script Object)</span>
                                        </label>
                                        <label class="d-flex align-items-center gap-2 style-tiny text-secondary">
                                            <input type="checkbox" id="recFileCss" disabled>
                                            <span class="font-monospace text-success">/var/www/html/assets/bootstrap.min.css (Clean CSS)</span>
                                        </label>
                                        <label class="d-flex align-items-center gap-2 style-tiny text-secondary">
                                            <input type="checkbox" id="recFileDaemon" disabled>
                                            <span class="font-monospace text-danger">/var/www/html/temp_daemon.log (Unauthorized Service Relay)</span>
                                        </label>
                                    </div>
                                    <button type="button" class="btn-cyber py-1 px-3 small w-100" id="btnRecStep2" onclick="HackerApp.recVerifyStep2()" disabled>
                                        <i class="fa-solid fa-broom me-1"></i> Bersihkan Malware File
                                    </button>
                                </div>

                                <!-- Step 3: Dekripsi Database Config (Hitungan Matematika) -->
                                <div class="p-2 cyber-box-dim border border-secondary border-opacity-30 rounded opacity-60" id="recStepBox3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong class="text-white small"><i class="fa-solid fa-unlock-keyhole text-warning me-1"></i> 3. Pulihkan Kunci Database (Matematika)</strong>
                                        <span class="badge bg-secondary" id="recBadge3">Terkunci</span>
                                    </div>
                                    <p class="text-secondary style-tiny mb-2">
                                        Konfigurasi <code>.env</code> terkunci sandi rotasi. Hitung kunci pemulihan:<br>
                                        <span class="text-warning font-monospace">Kunci DB = (Base 12 &times; 8) + 4 = ?</span>
                                    </p>
                                    <div class="d-flex gap-2">
                                        <input type="number" id="recMathAnswer" class="cyber-input py-1 text-center font-monospace" placeholder="Hasil?" style="max-width: 120px;" disabled>
                                        <button type="button" class="btn-cyber py-1 px-3 small flex-fill" id="btnRecStep3" onclick="HackerApp.recVerifyStep3()" disabled>
                                            <i class="fa-solid fa-key me-1"></i> Buka Kunci DB
                                        </button>
                                    </div>
                                </div>

                                <!-- Step 4: Patching Celah SQL Injection -->
                                <div class="p-2 cyber-box-dim border border-secondary border-opacity-30 rounded opacity-60" id="recStepBox4">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong class="text-white small"><i class="fa-solid fa-shield-halved text-success me-1"></i> 4. Patching Celah SQL Injection</strong>
                                        <span class="badge bg-secondary" id="recBadge4">Terkunci</span>
                                    </div>
                                    <p class="text-secondary style-tiny mb-2">Pilih perbaikan kode PHP yang aman untuk menggantikan query rentan:</p>
                                    <div class="small mb-2 d-flex flex-column gap-1">
                                        <label class="d-flex align-items-center gap-2 style-tiny text-secondary">
                                            <input type="radio" name="recPatchOption" value="prepared" disabled>
                                            <span class="font-monospace text-success">$stmt = $pdo-&gt;prepare(...); $stmt-&gt;execute(['id'=&gt;$id]); (Aman)</span>
                                        </label>
                                        <label class="d-flex align-items-center gap-2 style-tiny text-secondary">
                                            <input type="radio" name="recPatchOption" value="concat" disabled>
                                            <span class="font-monospace">$sql = "SELECT * FROM users WHERE id = " . $_GET['id']; (Rentan)</span>
                                        </label>
                                    </div>
                                    <button type="button" class="btn-cyber py-1 px-3 small w-100" id="btnRecStep4" onclick="HackerApp.recVerifyStep4()" disabled>
                                        <i class="fa-solid fa-screwdriver-wrench me-1"></i> Terapkan Security Patch
                                    </button>
                                </div>

                                <!-- Step 5: Final Clean Restore Deployment -->
                                <div class="p-2 cyber-box-dim border border-secondary border-opacity-30 rounded opacity-60" id="recStepBox5">
                                    <button class="btn-cyber-blue w-100 py-2 fw-bold" id="btnCompleteRestore" onclick="HackerApp.completeEmergencyRestore()" disabled>
                                        <i class="fa-solid fa-shield-check me-1"></i> 🛡️ EKSEKUSI PEMULIHAN SISTEM & DEPLOY VERSI BERSIH (+120 XP)
                                    </button>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                <!-- Right Live Browser Sandbox Window -->
                <div class="col-lg-7">
                    <div class="cyber-box p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small text-secondary"><i class="fa-solid fa-globe"></i> LIVE TARGET BROWSER SIMULATOR:</span>
                            <span class="cyber-tag info" id="livePortalStatus">STATUS: 200 OK (PORTAL AKTIF)</span>
                        </div>

                        <!-- Browser Container Window -->
                        <div class="sim-browser shadow-lg" style="height: 440px;">
                            <div class="sim-browser-topbar">
                                <span class="sim-dot bg-danger"></span>
                                <span class="sim-dot bg-warning"></span>
                                <span class="sim-dot bg-success"></span>
                                <div class="sim-url-bar" id="browserUrl">https://smanit-portal.sch.id/index.php</div>
                            </div>
                            
                            <!-- Browser Screen Body -->
                            <div id="simulatedWebBody" class="p-4 text-center d-flex flex-column justify-content-center align-items-center h-100" style="background: #0b1120; color: #f8fafc; min-height: 380px;">
                                <!-- Default Normal Website View -->
                                <div id="normalWebView">
                                    <div class="fs-1 text-primary mb-2"><i class="fa-solid fa-school"></i></div>
                                    <h4 class="fw-bold text-white mb-1">PORTAL RESMI SMAN 1 TAMANSARI</h4>
                                    <p class="text-secondary small mb-3">Selamat datang di Portal Informasi & Akademik Multimedia Club.</p>
                                    <div class="p-3 rounded border border-secondary border-opacity-25 bg-black bg-opacity-40 text-start small text-secondary mx-auto" style="max-width: 420px;">
                                        <div><i class="fa-solid fa-check text-success me-2"></i> Status Server: Normal</div>
                                        <div><i class="fa-solid fa-check text-success me-2"></i> Firewall WAF: Active</div>
                                        <div><i class="fa-solid fa-check text-success me-2"></i> Database SQL: Connected</div>
                                        <div><i class="fa-solid fa-check text-success me-2"></i> Integrity: Clean</div>
                                    </div>
                                </div>

                                <!-- Defaced Hacked State (Hidden by default) -->
                                <div id="defacedWebView" class="d-none w-100 text-center p-3 rounded" style="transition: all 0.3s ease;">
                                    <div class="fs-1 text-danger mb-2 deface-glitch-text" id="liveDefaceIcon"><i class="fa-solid fa-skull-crossbones"></i></div>
                                    <h3 class="fw-bold text-danger deface-glitch-text mb-1" id="liveDefaceTitle">HACKED BY MMC CYBER SQUAD</h3>
                                    <p class="text-success font-monospace mb-2" id="liveDefaceName">Greetz from: x-Shadow_MMC</p>
                                    <div class="p-3 bg-black border border-danger border-opacity-50 text-white font-monospace small mx-auto mb-3" style="max-width: 480px;" id="liveDefaceMessage">
                                        Security is an illusion. Your defenses have fallen before the Multimedia Cyber Team! Fix your security before we return.
                                    </div>
                                    <div class="style-tiny text-secondary font-monospace" id="liveDefaceFooter">[ MMC RED TEAM CYBER OPERATIONS 2026 ]</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==========================================
             TAB 4: INTERACTIVE TERMINAL CLI
             ========================================== -->
        <div id="tab-terminal" class="tab-pane-content d-none">
            <div class="cyber-box p-3">
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-success border-opacity-25">
                    <h4 class="fs-6 fw-bold text-uppercase m-0 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-terminal"></i> Interactive Linux Hacker Shell
                    </h4>
                    <span class="cyber-tag">Session: root@cyberhack:~#</span>
                </div>

                <!-- Quick Command Mobile Chips -->
                <div class="d-flex flex-wrap gap-1 mb-2 pb-2 border-bottom border-success border-opacity-10">
                    <span class="small text-secondary align-self-center me-1"><i class="fa-solid fa-hand-pointer"></i> Quick Tap:</span>
                    <button class="quick-chip" onclick="HackerApp.runQuickCommand('help')">help</button>
                    <button class="quick-chip" onclick="HackerApp.runQuickCommand('scan')">scan</button>
                    <button class="quick-chip" onclick="HackerApp.runQuickCommand('nmap 192.168.1.1')">nmap</button>
                    <button class="quick-chip" onclick="HackerApp.runQuickCommand('cat secret.txt')">cat secret.txt</button>
                    <button class="quick-chip" onclick="HackerApp.runQuickCommand('cat /etc/shadow')">cat shadow</button>
                    <button class="quick-chip" onclick="HackerApp.runQuickCommand('inject --sql')">inject --sql</button>
                    <button class="quick-chip" onclick="HackerApp.runQuickCommand('whoami')">whoami</button>
                    <button class="quick-chip" onclick="HackerApp.runQuickCommand('matrix')">matrix</button>
                    <button class="quick-chip" onclick="HackerApp.runQuickCommand('clear')">clear</button>
                </div>

                <!-- Shell Output Window -->
                <div class="cyber-log-window" id="shellLog" style="height: 320px; font-size: 0.85rem;">
                    <div>Welcome to CyberHack Interactive Linux Shell v4.2.0 (x86_64-root)</div>
                    <div>Type <span class="text-white fw-bold">help</span> to view all available commands or tap the quick buttons above.</div>
                    <div class="text-secondary mt-1">--------------------------------------------------</div>
                </div>

                <!-- Shell Input Bar -->
                <div class="d-flex align-items-center gap-2 mt-2">
                    <span class="shell-prompt">root@cyber:~#</span>
                    <input type="text" id="shellInput" class="cyber-input py-1" placeholder="Enter shell command (e.g. scan, crack, cat, inject)..." autocomplete="off">
                    <button class="btn-cyber py-1 px-3" onclick="HackerApp.execShellCommand()">
                        <i class="fa-solid fa-paper-plane"></i> RUN
                    </button>
                </div>
            </div>
        </div>

        <!-- ==========================================
             TAB 5: INTERACTIVE NETWORK VISUALIZER MAP
             ========================================== -->
        <div id="tab-network" class="tab-pane-content d-none">
            <div class="cyber-box p-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 pb-2 border-bottom border-success border-opacity-25 gap-2">
                    <div>
                        <h4 class="fs-6 fw-bold text-uppercase m-0 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-diagram-project"></i> Cyber Network Infiltration Map
                        </h4>
                        <small class="text-secondary style-tiny">Pindai subnet misi, geser posisi node (drag & drop), dan sambung/putus jalur koneksi.</small>
                    </div>
                    
                    <div class="d-flex flex-wrap gap-1 gap-md-2 align-items-center">
                        <!-- 1. Scan Subnet -->
                        <button type="button" class="btn-cyber py-1 px-2 btn-auto-guided" onclick="HackerApp.scanNetworkNodes()" title="Pindai & deteksi arsitektur subnet target misi">
                            <i class="fa-solid fa-radar me-1"></i> Scan Subnet
                        </button>
                        
                        <!-- 2. Toggle Link Mode -->
                        <button type="button" class="btn-cyber py-1 px-2" id="btnToggleLinkMode" onclick="HackerApp.toggleLinkMode()" title="Mode Sambung atau Putus Jalur Node">
                            <i class="fa-solid fa-link me-1"></i> Sambung / Putus Jalur
                        </button>

                        <!-- 3. Add Custom Node -->
                        <button type="button" class="btn-cyber py-1 px-2" onclick="HackerApp.openAddNodeModal()" title="Tambahkan host, server, atau proxy baru dengan nama kustom">
                            <i class="fa-solid fa-plus me-1"></i> Tambah Node
                        </button>

                        <!-- 4. Toggle Delete Node Mode -->
                        <button type="button" class="btn-cyber py-1 px-2" id="btnToggleDeleteMode" onclick="HackerApp.toggleDeleteMode()" title="Mode Hapus: Klik node untuk menghapusnya satu per satu">
                            <i class="fa-solid fa-trash-can me-1"></i> Hapus Node (Mode)
                        </button>

                        <!-- 5. Launch Attack / Infiltration -->
                        <button type="button" class="btn-cyber-red py-1 px-2" onclick="HackerApp.launchNodeAttack()" title="Injeksi paket eksploitasi ke seluruh jalur">
                            <i class="fa-solid fa-crosshairs me-1"></i> Injeksi Exploit
                        </button>

                        <!-- 6. Sniff Packets -->
                        <button type="button" class="btn-cyber-blue py-1 px-2" onclick="HackerApp.interceptPackets()" title="Sadap paket data subnet">
                            <i class="fa-solid fa-satellite-dish me-1"></i> Sniff Paket
                        </button>

                        <!-- 7. Reset / Clear Map -->
                        <button type="button" class="btn-cyber py-1 px-2" onclick="HackerApp.resetNetworkMap()" title="Kosongkan kembali peta jaringan">
                            <i class="fa-solid fa-rotate-left"></i>
                        </button>
                    </div>
                </div>

                <!-- Live Mode & Control Instructions Banner -->
                <div class="d-flex flex-wrap justify-content-between align-items-center px-2 py-1 mb-2 bg-black bg-opacity-60 border border-success border-opacity-20 rounded style-tiny">
                    <span id="networkModeHint" class="text-success">
                        <i class="fa-solid fa-circle-info text-info me-1"></i> <strong>Mode Geser (Drag):</strong> Klik/tahan node untuk geser. <strong>Klik Ganda (Double-Click) node untuk EDIT nama/status</strong>, atau tombol <strong>[+ Tambah Node]</strong> untuk node baru.
                    </span>
                    <span id="networkTopologyBadge" class="cyber-tag">Topologi: Belum Dipindai</span>
                </div>

                <!-- Network Interactive Canvas Container -->
                <div class="position-relative border border-success border-opacity-25 crt-overlay" style="min-height: 400px; background: #020603;">
                    <canvas id="networkCanvas"></canvas>
                </div>

                <!-- Node Status Feed -->
                <div class="mt-2 cyber-log-window" id="networkLog" style="height: 115px;">
                    <div>[NET_INIT] Modul Network Topology dimuat. Klik [SCAN SUBNET] untuk mendeteksi arsitektur subnet target misi aktif.</div>
                </div>
            </div>
        </div>

        <!-- ==========================================
             TAB 6: QUICK HACK / CYBER CODE BREAKER
             ========================================== -->
        <div id="tab-quickgame" class="tab-pane-content d-none">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="cyber-box p-4 text-center">
                        <div class="mb-3">
                            <span class="cyber-tag warning mb-2"><i class="fa-solid fa-bolt"></i> RAPID FIREWALL BYPASS</span>
                            <h3 class="cyber-title justify-content-center fs-4 mt-1">CYBER PORT MATRIX BREAKER</h3>
                            <p class="small text-secondary">
                                Cocokkan urutan port heksadesimal sebelum waktu habis untuk membobol firewall target!
                            </p>
                        </div>

                        <!-- Game Stats -->
                        <div class="d-flex justify-content-center gap-3 gap-md-4 mb-4">
                            <div class="p-2 cyber-box-dim px-3 px-md-4">
                                <div class="small text-secondary">TIMER</div>
                                <div class="fs-4 fw-bold text-danger" id="quickTimer">30s</div>
                            </div>
                            <div class="p-2 cyber-box-dim px-3 px-md-4">
                                <div class="small text-secondary">SCORE</div>
                                <div class="fs-4 fw-bold text-white" id="quickScore">0</div>
                            </div>
                            <div class="p-2 cyber-box-dim px-3 px-md-4">
                                <div class="small text-secondary">ROUND</div>
                                <div class="fs-4 fw-bold text-success" id="quickRound">1/5</div>
                            </div>
                        </div>

                        <!-- Target Sequence -->
                        <div class="mb-4">
                            <div class="small text-secondary mb-1">TARGET HEX CODE:</div>
                            <div class="fs-3 fw-bold text-warning letter-spacing-2 font-monospace" id="quickTargetSeq">
                                7A : FF : 09 : 3C
                            </div>
                        </div>

                        <!-- Hex Code Buttons Matrix -->
                        <div class="d-flex flex-wrap justify-content-center gap-2 mb-4" id="quickHexMatrix" style="max-width: 480px; margin: 0 auto;">
                            <!-- Populated dynamically -->
                        </div>

                        <!-- Current Input Sequence -->
                        <div class="mb-4">
                            <div class="small text-secondary mb-1">YOUR BUFFER:</div>
                            <div class="fs-4 text-white font-monospace" id="quickUserBuffer">_ _ _ _</div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-center gap-3">
                            <button class="btn-cyber px-4 py-2" id="btnStartQuickGame" onclick="HackerApp.startQuickGame()">
                                <i class="fa-solid fa-play"></i> Start Rapid Hack
                            </button>
                            <button class="btn-cyber-amber px-3 py-2" onclick="HackerApp.resetQuickBuffer()">
                                <i class="fa-solid fa-rotate-left"></i> Reset Buffer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==========================================
             BOTTOM GLOBAL CONSOLE LOG
             ========================================== -->
        <div class="cyber-box mt-3 p-2">
            <div class="d-flex justify-content-between align-items-center px-2 py-1 small border-bottom border-success border-opacity-10">
                <span class="text-secondary"><i class="fa-solid fa-terminal"></i> GLOBAL FEED LOG</span>
                <span class="text-secondary style-tiny">PORT: 443 [ENCRYPTED]</span>
            </div>
            <div class="cyber-log-window" id="globalLog" style="height: 65px; font-size: 0.76rem;">
                <div>[SYSTEM] Decrypt module loaded. Select an operation to begin.</div>
                <div>[SYSTEM] Establishing secure connection to target server node...</div>
            </div>
        </div>

    </div>
</div>

<!-- ==========================================
     MODAL: TUTORIAL & 8-STAGE KILL-CHAIN
     ========================================== -->
<div class="modal fade" id="modalTutorial" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content cyber-box border-success p-3">
            <div class="modal-header border-bottom border-success border-opacity-25 pb-2">
                <h4 class="cyber-title fs-5 m-0">
                    <i class="fa-solid fa-graduation-cap"></i> 20 MISI SIBER, DEFACING, RECOVERY & EDUKASI
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3 p-md-4">
                <p class="text-secondary small mb-3">
                    Game ini mensimulasikan alur penetrasi siber dunia nyata dalam <strong>20 Misi Variatif</strong> (Red Team Offensive & White Hat Cyber Defense Recovery):
                </p>

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <div class="guide-step-card border-danger border-opacity-40">
                            <span class="badge bg-danger mb-1">🔴 RED TEAM (PENETRATION & DEFACING)</span>
                            <p class="text-secondary style-tiny m-0">Menembus celah keamanan, menguji ketahanan server sekolah/instansi, dan mempraktikkan simulasi defacing website untuk edukasi audit celah.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="guide-step-card border-info border-opacity-40">
                            <span class="badge bg-info text-dark mb-1">🛡️ WHITE HAT (INCIDENT RESPONSE)</span>
                            <p class="text-secondary style-tiny m-0">Memulihkan website yang dideface, membersihkan file injeksi mencurigakan, mendekripsi ransomware, memitigasi serangan DDoS, dan menambal celah SQL Injection.</p>
                        </div>
                    </div>
                </div>

                <div class="p-3 cyber-box-dim rounded small mb-2">
                    <div class="text-warning fw-bold mb-1"><i class="fa-solid fa-scale-balanced"></i> CATATAN ETIKA & HUKUM SIBER:</div>
                    <p class="text-secondary style-tiny m-0">
                        Peretasan tanpa izin terhadap sistem orang lain adalah tindakan ilegal yang melanggar hukum (UU ITE). Jadilah <strong>Ethical White Hat Hacker</strong> yang membantu mengamankan sistem melalui pelaporan celah (*Responsible Disclosure*)!
                    </p>
                </div>
            </div>
            <div class="modal-footer border-top border-success border-opacity-25 pt-2">
                <button type="button" class="btn-cyber px-4 py-2" data-bs-dismiss="modal">
                    <i class="fa-solid fa-check"></i> Siap, Mengerti!
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================
     MISSION COMPLETE / RANK UP MODAL
     ========================================== -->
<div class="modal fade" id="modalMissionSuccess" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content cyber-box border-success p-3 text-center">
            <div class="modal-body p-4">
                <div class="fs-1 text-success mb-2">
                    <i class="fa-solid fa-shield-virus"></i>
                </div>
                <h3 class="cyber-title justify-content-center fs-4 text-white mb-2">TARGET BREACHED & ROOT GRANTED!</h3>
                <p class="text-success mb-3" id="modalSuccessMessage">
                    Misi Infiltrasi Berhasil! Anda mendapatkan akses penuh ke root server target.
                </p>
                
                <div class="p-3 cyber-box-dim mb-4">
                    <div class="d-flex justify-content-between small text-secondary mb-1">
                        <span>XP REWARD:</span>
                        <strong class="text-warning" id="modalRewardXP">+50 XP</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-secondary mb-1">
                        <span>RANKING POINTS:</span>
                        <strong class="text-success">+5 Poin Ranking MM</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-secondary">
                        <span>INFILTRATION LEVEL:</span>
                        <strong class="text-white">FULL ROOT ESCALATION (100%)</strong>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn-cyber py-2 px-4" data-bs-dismiss="modal">
                        <i class="fa-solid fa-check"></i> Tutup & Lanjut Target Lain
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================
     MODAL: NODE CONFIG & NAME EDITOR
     ========================================== -->
<div class="modal fade" id="modalNodeEditor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content cyber-box border-success p-3">
            <div class="modal-header border-bottom border-success border-opacity-25 pb-2">
                <h4 class="cyber-title fs-5 m-0" id="nodeEditorTitle">
                    <i class="fa-solid fa-diagram-project"></i> EDIT NODE JARINGAN
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <input type="hidden" id="nodeEditId" value="">
                
                <div class="mb-3">
                    <label class="small text-secondary mb-1">NAMA NODE / HOSTNAME:</label>
                    <div class="input-group">
                        <span class="input-group-text bg-black border-success border-opacity-50 text-success"><i class="fa-solid fa-server"></i></span>
                        <input type="text" id="nodeEditLabel" class="cyber-input form-control" placeholder="Contoh: Main Router, Web Server Alpha, Admin PC" required>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-7">
                        <label class="small text-secondary mb-1">IP ADDRESS / HOST:</label>
                        <div class="input-group">
                            <span class="input-group-text bg-black border-success border-opacity-50 text-success"><i class="fa-solid fa-network-wired"></i></span>
                            <input type="text" id="nodeEditIP" class="cyber-input form-control" placeholder="192.168.1.50">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="small text-secondary mb-1">PORT / PROTOKOL:</label>
                        <div class="input-group">
                            <span class="input-group-text bg-black border-success border-opacity-50 text-success"><i class="fa-solid fa-plug"></i></span>
                            <input type="text" id="nodeEditPort" class="cyber-input form-control" placeholder="80/443 atau 22/SSH">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="small text-secondary mb-1">STATUS & WARNA HALO NODE:</label>
                    <select id="nodeEditStatus" class="cyber-input form-select bg-black text-success border-success border-opacity-50">
                        <option value="Hacked|#00ff66">🟢 Infiltrated / Hacked (Hijau Neon - #00ff66)</option>
                        <option value="Target|#f59e0b">🟡 Target Objective (Amber - #f59e0b)</option>
                        <option value="Vulnerable|#00e5ff">🔵 Vulnerable / Exploit Point (Cyan - #00e5ff)</option>
                        <option value="Infiltrated|#a855f7">🟣 Pivot Relay / Proxy (Ungu - #a855f7)</option>
                        <option value="Secured|#ef4444">🔴 Hardened / Secured (Merah - #ef4444)</option>
                        <option value="Active|#39ff14">🟢 Active Host (Hijau Terang - #39ff14)</option>
                    </select>
                </div>

                <div class="p-2 cyber-box-dim rounded small style-tiny text-secondary">
                    <i class="fa-solid fa-lightbulb text-warning me-1"></i> Tip: Anda dapat menggeser posisi node kapan saja dengan drag & drop di canvas, atau klik 'Sambung / Putus Jalur' untuk menghubungkannya.
                </div>
            </div>
            <div class="modal-footer border-top border-success border-opacity-25 pt-2 d-flex justify-content-between">
                <button type="button" class="btn-cyber-red py-1 px-3 d-none" id="btnDeleteEditingNode" onclick="HackerApp.deleteCurrentEditingNode()">
                    <i class="fa-solid fa-trash-can me-1"></i> Hapus Node
                </button>
                <div class="d-flex gap-2 ms-auto">
                    <button type="button" class="btn-cyber-dim py-1 px-3" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="button" class="btn-cyber py-1 px-4 fw-bold" onclick="HackerApp.saveNodeEditor()">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Node
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    window.MMC_API_RECORD_SCORE = "<?= base_url('mini-game/api/record-score') ?>";
</script>
<script src="<?= base_url('assets/js/games/cyber_simulator.js?v=4.5.1') ?>"></script>

<?= $this->endSection() ?>
