<?= $this->extend('layouts/master_public') ?>

<?= $this->section('content') ?>
<style>
/* =========================================================
   CUSTOM IDE SIMULATOR & CODE LAB RESPONSIVE STYLES
   ========================================================= */
.ide-container {
    background: #0d1117;
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 20px;
    box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.7), 0 0 40px rgba(59, 130, 246, 0.1);
    overflow: hidden;
    color: #e6edf3;
}

.ide-topbar {
    background: #161b22;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    padding: 12px 16px;
}

.ide-editor-pane {
    background: #0d1117;
    border-right: 1px solid rgba(255, 255, 255, 0.08);
    min-height: 520px;
    display: flex;
    flex-direction: column;
}

.editor-wrapper {
    position: relative;
    flex-grow: 1;
    display: flex;
    font-family: 'JetBrains Mono', 'Fira Code', 'Cascadia Code', Consolas, monospace;
    font-size: 0.92rem;
    line-height: 1.6;
    background: #0d1117;
    overflow: hidden;
}

.editor-gutter {
    width: 44px;
    padding: 14px 6px;
    background: #090d13;
    color: #484f58;
    text-align: right;
    user-select: none;
    font-size: 0.82rem;
    border-right: 1px solid rgba(255, 255, 255, 0.06);
    white-space: pre;
    overflow: hidden;
    flex-shrink: 0;
}

.editor-textarea {
    flex-grow: 1;
    background: transparent;
    color: #e6edf3;
    border: none;
    outline: none;
    resize: none;
    padding: 14px 16px;
    font-family: inherit;
    font-size: inherit;
    line-height: inherit;
    tab-size: 4;
    white-space: pre;
    overflow: auto;
    word-break: normal;
    min-width: 0;
}

.ide-output-pane {
    background: #090d13;
    min-height: 520px;
    display: flex;
    flex-direction: column;
}

.output-terminal {
    background: #05080c;
    color: #7ee787;
    font-family: 'JetBrains Mono', Consolas, monospace;
    font-size: 0.88rem;
    padding: 16px;
    flex-grow: 1;
    overflow-y: auto;
    white-space: pre-wrap;
    word-break: break-all;
    min-height: 250px;
}

.output-terminal.error {
    color: #f85149;
}

.output-preview-frame {
    width: 100%;
    height: 100%;
    min-height: 460px;
    border: none;
    background: #ffffff;
    border-radius: 0 0 12px 0;
}

.ide-tab-btn {
    background: transparent;
    border: none;
    color: #8b949e;
    padding: 9px 16px;
    font-family: var(--bs-font-monospace, monospace);
    font-size: 0.85rem;
    font-weight: 600;
    border-top: 2px solid transparent;
    transition: all 0.15s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    flex-shrink: 0;
}
.ide-tab-btn:hover {
    color: #e6edf3;
    background: rgba(255, 255, 255, 0.03);
}
.ide-tab-btn.active {
    color: #58a6ff;
    background: #0d1117;
    border-top-color: #58a6ff;
}

.run-btn-glow {
    background: linear-gradient(135deg, #238636 0%, #2ea043 100%);
    color: #ffffff;
    font-weight: 700;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 14px rgba(46, 160, 67, 0.4);
    transition: all 0.2s ease;
}
.run-btn-glow:hover {
    background: linear-gradient(135deg, #2ea043 0%, #3fb950 100%);
    box-shadow: 0 6px 20px rgba(46, 160, 67, 0.6);
    transform: translateY(-1px);
    color: #ffffff;
}

/* Autocomplete IntelliSense Popup Styles */
.ide-autocomplete-popup {
    position: absolute;
    background: #161b22;
    border: 1px solid rgba(88, 166, 255, 0.45);
    border-radius: 10px;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.85), 0 0 25px rgba(56, 189, 248, 0.2);
    max-height: 280px;
    width: 350px;
    max-width: calc(100% - 30px);
    z-index: 1050;
    overflow: hidden;
    font-family: 'JetBrains Mono', Consolas, monospace;
    display: flex;
    flex-direction: column;
    backdrop-filter: blur(16px);
}
.suggestion-scroll-area {
    overflow-y: auto;
    max-height: 230px;
    padding: 4px;
}
.suggestion-item {
    padding: 6px 10px;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    color: #e6edf3;
    font-size: 0.84rem;
    transition: background 0.1s, color 0.1s;
}
.suggestion-item:hover, .suggestion-item.selected {
    background: #1f6feb;
    color: #ffffff !important;
}
.suggestion-badge-type {
    font-size: 0.70rem;
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: 700;
}

/* Quest Card Step */
.quest-step-box {
    background: #161b22;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 10px;
    padding: 12px 16px;
    margin-bottom: 10px;
    transition: border 0.2s;
}
.quest-step-box:hover {
    border-color: rgba(88, 166, 255, 0.4);
}

/* High-Contrast Status Pills (Compatible with both Light & Dark Theme) */
.ide-status-pill {
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    line-height: 1.4;
    white-space: nowrap;
    text-decoration: none;
}
.ide-status-pill.pill-dark {
    background: #1c2128 !important;
    color: #e6edf3 !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
}
.ide-status-pill.pill-success {
    background: rgba(46, 160, 67, 0.25) !important;
    color: #3fb950 !important;
    border: 1px solid rgba(46, 160, 67, 0.45) !important;
}
.ide-status-pill.pill-danger {
    background: rgba(248, 81, 73, 0.25) !important;
    color: #f85149 !important;
    border: 1px solid rgba(248, 81, 73, 0.45) !important;
}
.ide-status-pill.pill-primary {
    background: rgba(56, 189, 248, 0.25) !important;
    color: #38bdf8 !important;
    border: 1px solid rgba(56, 189, 248, 0.45) !important;
}
.ide-status-pill.pill-warning {
    background: rgba(234, 179, 8, 0.25) !important;
    color: #eab308 !important;
    border: 1px solid rgba(234, 179, 8, 0.45) !important;
}

/* Test case badges */
.test-pill-pass {
    background: rgba(46, 160, 67, 0.2) !important;
    color: #3fb950 !important;
    border: 1px solid rgba(46, 160, 67, 0.4) !important;
}
.test-pill-fail {
    background: rgba(248, 81, 73, 0.2) !important;
    color: #f85149 !important;
    border: 1px solid rgba(248, 81, 73, 0.4) !important;
}

/* Status bar responsive */
.ide-statusbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 6px 14px;
    background: #05080c;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    font-family: 'JetBrains Mono', Consolas, monospace;
    font-size: 0.75rem;
    color: #8b949e;
    overflow-x: auto;
    white-space: nowrap;
    scrollbar-width: none;
}
.ide-statusbar::-webkit-scrollbar {
    display: none;
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
.bg-gradient-cyan   { background: linear-gradient(135deg, #06b6d4, #0891b2); box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3); }
.bg-gradient-amber  { background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3); }
.bg-gradient-green  { background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); }
.bg-gradient-red    { background: linear-gradient(135deg, #ef4444, #dc2626); box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); }
.bg-gradient-purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3); }

/* Mobile & Tablet Specific Optimizations */
@media (max-width: 991px) {
    .ide-editor-pane {
        border-right: none;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        min-height: 400px;
    }
    .ide-output-pane {
        min-height: 400px;
    }
    .output-preview-frame {
        min-height: 380px;
    }
}

@media (max-width: 576px) {
    .ide-container {
        border-radius: 16px !important;
    }
    .ide-topbar {
        padding: 10px 12px;
    }
    .editor-gutter {
        width: 32px;
        padding: 12px 3px;
        font-size: 0.72rem;
    }
    .editor-textarea {
        padding: 12px 10px;
        font-size: 0.82rem;
        line-height: 1.5;
    }
    .ide-tab-btn {
        padding: 7px 11px;
        font-size: 0.78rem;
    }
    .ide-autocomplete-popup {
        width: 290px;
        max-width: calc(100vw - 40px);
    }
}
</style>

<section class="py-3 py-lg-5">
    <div class="container-fluid px-3 px-lg-4 px-xl-5">
        
        <!-- Breadcrumb & Back Navigation -->
        <div class="d-flex align-items-center justify-content-between mb-3 mb-md-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>" class="text-secondary text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('mini-game') ?>" class="text-secondary text-decoration-none">Mini Game</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">IDE Simulator & Code Lab</li>
                </ol>
            </nav>
            <div class="d-flex gap-2">
                <a href="<?= base_url('mini-game') ?>" class="btn btn-sm btn-outline-secondary px-2.5">
                    <i class="fa-solid fa-arrow-left me-1"></i> <span class="d-none d-sm-inline">Kembali</span>
                </a>
            </div>
        </div>

        <!-- Header Section with Mode Switcher -->
        <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-end justify-content-between gap-3 mb-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1.5 flex-wrap">
                    <span class="badge bg-primary font-monospace style-tiny"><i class="fa-solid fa-code me-1"></i> MINI GAME #2</span>
                    <span class="badge bg-body-secondary text-secondary border border-secondary border-opacity-50 style-tiny font-monospace">Divisi Programming & Web</span>
                </div>
                <h1 class="h3 h2-md fw-bold text-body font-heading mb-1">IDE Simulator & Code Lab</h1>
                <p class="text-secondary small mb-0">Eksplorasi coding berbagai bahasa, eksekusi live output, auto-suggest cerdas, dan selesaikan misi tantangan interaktif.</p>
            </div>

            <!-- Mode Selector Tabs: Free Code Sandbox vs Challenge Quests -->
            <div class="bg-body-secondary p-1 rounded-3 border border-secondary border-opacity-25 d-flex flex-column flex-sm-row gap-1 w-100 w-md-auto flex-shrink-0">
                <button type="button" class="btn btn-sm btn-primary px-3 py-2 flex-fill font-heading fw-semibold text-nowrap d-flex align-items-center justify-content-center gap-2" id="tabSandboxModeBtn" onclick="switchIdeMode('sandbox')">
                    <i class="fa-solid fa-laptop-code"></i> Mode Coding Bebas
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-2 flex-fill font-heading fw-semibold border-0 text-body text-nowrap d-flex align-items-center justify-content-center gap-2" id="tabQuestModeBtn" onclick="switchIdeMode('quest')">
                    <i class="fa-solid fa-trophy text-warning"></i> Tantangan Quest (5 Level)
                </button>
            </div>
        </div>

        <!-- Quest Challenge Banner (Visible when Quest Mode is active) -->
        <div id="questBannerBox" class="p-3 p-md-4 mb-4 rounded-4 border border-warning border-opacity-50 bg-warning bg-opacity-10 d-none">
            <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center justify-content-between gap-3">
                <div class="d-flex align-items-start align-items-md-center gap-3">
                    <div class="p-2.5 rounded-circle bg-warning text-dark fs-4 flex-shrink-0 shadow-sm">
                        <i class="fa-solid fa-award"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <span class="badge bg-warning text-dark font-monospace style-tiny fw-bold" id="questLevelBadge">QUEST LEVEL 1 / 5</span>
                            <span class="badge bg-dark text-warning border border-warning border-opacity-50 font-monospace style-tiny" id="questPointsBadge">+100 Pts</span>
                        </div>
                        <h5 class="text-white font-heading fw-bold mb-1" id="questTitleText">Halo Programmer MM</h5>
                        <p class="text-secondary small mb-0" id="questDescText">Buat fungsi yang mencetak format sapaan resmi anggota Multimedia Club.</p>
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-center justify-content-between justify-content-lg-end gap-2 pt-2 pt-lg-0 border-top border-lg-0 border-warning border-opacity-25">
                    <div class="d-flex align-items-center gap-1">
                        <button class="btn btn-sm btn-outline-secondary text-white" onclick="prevQuest()" title="Misi Sebelumnya"><i class="fa-solid fa-chevron-left"></i></button>
                        <span class="font-monospace text-warning fw-bold px-2 small" id="questCounter">1 / 5</span>
                        <button class="btn btn-sm btn-outline-secondary text-white" onclick="nextQuest()" title="Misi Berikutnya"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                    
                    <button class="btn btn-sm btn-outline-info px-3" data-bs-toggle="modal" data-bs-target="#questGuideModal">
                        <i class="fa-solid fa-lightbulb me-1"></i> Panduan Step-by-Step
                    </button>
                    <button class="btn btn-sm btn-warning text-dark font-heading fw-bold px-3 shadow-sm" onclick="runQuestTests()">
                        <i class="fa-solid fa-vial-circle-check me-1"></i> Uji Solusi (Run Tests)
                    </button>
                </div>
            </div>
        </div>

        <!-- Main IDE Simulator Container -->
        <div class="ide-container mb-5">
            
            <!-- IDE Top Bar: Language & Template Selectors + Action Controls -->
            <div class="ide-topbar">
                <div class="row g-2 align-items-center">
                    
                    <!-- Left: Language Selector -->
                    <div class="col-12 col-md-4 col-lg-3">
                        <div class="d-flex align-items-center gap-1.5 w-100">
                            <span class="text-secondary style-tiny font-monospace d-none d-xl-inline flex-shrink-0">BAHASA:</span>
                            <select id="langSelect" class="form-select form-select-sm bg-dark text-white border-secondary border-opacity-50 font-monospace fw-semibold w-100" onchange="changeLanguage(this.value)">
                                <option value="web" selected>🌐 HTML + CSS + JS (Live Web)</option>
                                <option value="javascript">⚡ JavaScript (Node.js Logic)</option>
                                <option value="python">🐍 Python 3</option>
                                <option value="php">🐘 PHP 8.2</option>
                                <option value="cpp">⚙️ C / C++</option>
                                <option value="java">☕ Java</option>
                                <option value="sql">🗄️ SQL Database</option>
                            </select>
                        </div>
                    </div>

                    <!-- Middle: Template Starter Selector -->
                    <div class="col-12 col-md-4 col-lg-4">
                        <select id="templateSelect" class="form-select form-select-sm bg-dark text-info border-info border-opacity-30 font-monospace w-100" onchange="loadTemplate(this.value)">
                            <option value="" disabled selected>📂 Pilih Template Kode / Kasus...</option>
                            <option value="web_starter">🌐 Web Card Interaktif (HTML+CSS+JS)</option>
                            <option value="datatypes">📊 Tipe Data & Array Objek</option>
                            <option value="conditions">🔀 Logika Percabangan (If / Else)</option>
                            <option value="loops">🔄 Perulangan (For & forEach Array)</option>
                            <option value="functions">🧮 Fungsi & Arrow Functions</option>
                            <option value="fizzbuzz">🧩 Algoritma FizzBuzz & Modulo</option>
                            <option value="sql_demo">🗄️ SQL Query Anggota Multimedia</option>
                        </select>
                    </div>

                    <!-- Right: Editor Action Buttons -->
                    <div class="col-12 col-md-4 col-lg-5 text-md-end">
                        <div class="d-flex align-items-center justify-content-between justify-content-md-end gap-1.5 flex-wrap">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-secondary text-white border-secondary border-opacity-25" onclick="formatCode()" title="Rapikan Format & Indentasi Kode">
                                    <i class="fa-solid fa-align-left"></i> <span class="d-none d-sm-inline ms-1">Format</span>
                                </button>
                                <button type="button" class="btn btn-outline-secondary text-white border-secondary border-opacity-25" onclick="copyCode()" title="Salin Kode ke Clipboard">
                                    <i class="fa-regular fa-copy"></i> <span class="d-none d-sm-inline ms-1">Copy</span>
                                </button>
                                <button type="button" class="btn btn-outline-warning border-warning border-opacity-50" onclick="resetEditorCode()" title="Kembalikan Kode ke Default">
                                    <i class="fa-solid fa-rotate-left"></i> <span class="d-none d-sm-inline ms-1">Reset</span>
                                </button>
                            </div>
                            <!-- Run Code Action Button -->
                            <button type="button" class="btn btn-sm run-btn-glow px-3 py-1.5 font-heading d-inline-flex align-items-center gap-1.5 flex-fill flex-md-grow-0 justify-content-center" onclick="runCode()">
                                <i class="fa-solid fa-play"></i>
                                <span>Run Code</span>
                                <span class="style-tiny opacity-75 font-monospace d-none d-lg-inline">[Ctrl+↵]</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- IDE Split Panes -->
            <div class="row g-0">
                
                <!-- Left Pane: Code Editor Area -->
                <div class="col-lg-6 ide-editor-pane">
                    
                    <!-- Web Multi-File Tabs (HTML, CSS, JS) -->
                    <div id="webTabsHeader" class="d-flex bg-dark border-bottom border-secondary border-opacity-25 px-2 overflow-x-auto" style="scrollbar-width: thin;">
                        <button class="ide-tab-btn active" id="tabHtmlBtn" onclick="switchWebTab('html')">
                            <i class="fa-brands fa-html5 text-danger"></i> index.html
                        </button>
                        <button class="ide-tab-btn" id="tabCssBtn" onclick="switchWebTab('css')">
                            <i class="fa-brands fa-css3-alt text-primary"></i> style.css
                        </button>
                        <button class="ide-tab-btn" id="tabJsBtn" onclick="switchWebTab('js')">
                            <i class="fa-brands fa-js text-warning"></i> script.js
                        </button>
                    </div>

                    <!-- Single File Tab Bar (Python / PHP / C++ / Java / SQL) -->
                    <div id="singleTabHeader" class="d-none bg-dark border-bottom border-secondary border-opacity-25 px-2">
                        <div class="ide-tab-btn active text-white">
                            <i class="fa-solid fa-file-code text-info" id="singleTabIcon"></i> <span id="singleTabLabel">main.js</span>
                        </div>
                    </div>

                    <!-- Code Textarea & Gutter -->
                    <div class="editor-wrapper position-relative">
                        <div class="editor-gutter" id="editorGutter">1</div>
                        <textarea class="editor-textarea" id="codeEditor" spellcheck="false" placeholder="// Tulis atau edit kode kamu di sini..." oninput="onEditorInput()" onkeydown="onEditorKeyDown(event)" onclick="hideSuggestions()" onblur="setTimeout(hideSuggestions, 200)"></textarea>

                        <!-- Floating IntelliSense Autocomplete Popup -->
                        <div id="autocompletePopup" class="ide-autocomplete-popup d-none" style="top: 50px; left: 50px;">
                            <div class="p-1.5 px-2 bg-dark border-bottom border-secondary border-opacity-25 d-flex justify-content-between align-items-center style-tiny text-secondary font-monospace">
                                <span><i class="fa-solid fa-wand-magic-sparkles text-info me-1"></i> INTELLISENSE</span>
                                <span class="d-none d-sm-inline text-muted">Tab / ↵ Enter</span>
                            </div>
                            <div id="suggestionList" class="suggestion-scroll-area"></div>
                        </div>
                    </div>

                    <!-- Editor Bottom Status Bar (Clean High-Contrast Non-Wrapping Pills) -->
                    <div class="ide-statusbar">
                        <div class="d-flex align-items-center gap-2">
                            <span class="ide-status-pill pill-dark" id="editorStatsPos">Ln 1, Col 1</span>
                            <span class="ide-status-pill pill-dark" id="editorStatsLen">0 Karakter</span>
                            <span class="ide-status-pill pill-success d-none d-sm-inline">
                                <i class="fa-solid fa-bolt me-1"></i> IntelliSense ON
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-secondary small">UTF-8</span>
                            <span class="ide-status-pill pill-primary" id="editorStatsLang">HTML5 / Web</span>
                        </div>
                    </div>
                </div>

                <!-- Right Pane: Output (Preview / Terminal / Tests) -->
                <div class="col-lg-6 ide-output-pane">
                    
                    <!-- Output Header Tabs -->
                    <div class="d-flex align-items-center justify-content-between bg-dark border-bottom border-secondary border-opacity-25 px-2 overflow-x-auto" style="scrollbar-width: thin;">
                        <div class="d-flex">
                            <button class="ide-tab-btn active" id="tabOutputPreviewBtn" onclick="switchOutputTab('preview')">
                                <i class="fa-solid fa-globe text-primary"></i> <span class="d-none d-sm-inline">Visual</span> Preview
                            </button>
                            <button class="ide-tab-btn" id="tabOutputConsoleBtn" onclick="switchOutputTab('console')">
                                <i class="fa-solid fa-terminal text-success"></i> Console
                            </button>
                            <button class="ide-tab-btn" id="tabOutputTestsBtn" onclick="switchOutputTab('tests')">
                                <i class="fa-solid fa-vial-circle-check text-warning"></i> Uji Test <span class="badge bg-secondary style-tiny rounded-pill ms-1" id="testCasesBadgeCount">0/2</span>
                            </button>
                        </div>
                        
                        <!-- Console Quick Actions -->
                        <div class="d-flex align-items-center gap-1 pe-2 flex-shrink-0">
                            <button class="btn btn-sm text-secondary p-1" onclick="clearConsole()" title="Bersihkan Output Console">
                                <i class="fa-solid fa-ban"></i>
                            </button>
                            <button class="btn btn-sm text-secondary p-1" onclick="refreshPreview()" title="Muat Ulang Web Preview">
                                <i class="fa-solid fa-arrows-rotate"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Output View 1: Live Web Preview Iframe -->
                    <div id="outputPreviewContainer" class="flex-grow-1 position-relative d-flex flex-column">
                        <iframe id="previewIframe" class="output-preview-frame flex-grow-1" sandbox="allow-scripts allow-modals allow-same-origin"></iframe>
                    </div>

                    <!-- Output View 2: Terminal Console -->
                    <div id="outputConsoleContainer" class="d-none flex-grow-1 flex-column p-0">
                        <!-- Console Status & Metrics Bar -->
                        <div class="p-2 px-3 bg-dark border-bottom border-secondary border-opacity-25 d-flex flex-wrap align-items-center justify-content-between gap-2 style-tiny font-monospace" id="terminalStatusBar">
                            <div class="d-flex align-items-center gap-2">
                                <span class="ide-status-pill pill-success" id="terminalStatusBadge">
                                    <i class="fa-solid fa-circle-check me-1"></i> Successfully Executed
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-3 text-secondary">
                                <span>Time: <strong class="text-white" id="terminalExecTime">0.0120s</strong></span>
                                <span>Memory: <strong class="text-white" id="terminalExecMem">12.4 MB</strong></span>
                            </div>
                        </div>

                        <!-- Terminal Output Text Stream -->
                        <div class="output-terminal" id="terminalOutput">/* Klik tombol "Run Code" atau tekan [Ctrl + Enter] untuk mengeksekusi program. */</div>
                    </div>

                    <!-- Output View 3: Automated Test Cases -->
                    <div id="outputTestsContainer" class="d-none flex-grow-1 flex-column p-3 p-md-4 overflow-y-auto">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="text-white font-heading fw-bold mb-0"><i class="fa-solid fa-vial-circle-check text-warning me-1"></i> Evaluasi Kasus Uji (Test Cases)</h6>
                            <span class="badge bg-primary font-monospace" id="testsScoreBadge">Skor: 0 Pts</span>
                        </div>
                        <div id="testCasesList" class="d-flex flex-column gap-2.5">
                            <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 text-secondary small">
                                Silakan beralih ke <strong>Mode Tantangan Quest</strong> di bagian atas dan klik <strong>"Uji Solusi (Run Tests)"</strong> untuk menguji kebenaran algoritma Anda.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- =========================================================
             EDUCATIONAL & COMPREHENSIVE USER GUIDE (KNOWLEDGE BASE)
             ========================================================= -->
        <div class="saas-card saas-card-glow border border-secondary border-opacity-25 p-3 p-md-5 mb-5 rounded-4">
            
            <div class="text-center max-w-3xl mx-auto mb-4 mb-md-5">
                <span class="badge bg-primary bg-opacity-20 text-primary border border-primary border-opacity-25 font-monospace px-3 py-1 mb-2">PANDUAN LENGKAP & KNOWLEDGE BASE</span>
                <h2 class="h2 fw-bold text-body font-heading mb-2">Panduan Penggunaan & Anatomi Fitur IDE</h2>
                <p class="text-secondary small fs-md-6 mb-0">
                    Pelajari setiap tombol, fitur cerdas auto-complete, bahasa pemrograman yang didukung, dan cara menyelesaikan tantangan coding di Multimedia Club.
                </p>
            </div>

            <!-- 1. Interactive Cards: Anatomi & Fungsi Alat -->
            <div class="row g-3 g-md-4 mb-5">
                
                <!-- Card 1: Mode Coding -->
                <div class="col-md-6 col-lg-4">
                    <div class="p-3.5 p-md-4 rounded-4 bg-body-secondary h-100 border border-secondary border-opacity-25">
                        <div class="guide-icon-avatar bg-gradient-blue mb-3">
                            <i class="fa-solid fa-laptop-code"></i>
                        </div>
                        <h5 class="text-body font-heading fw-bold mb-2">1. Mode Sandbox vs Quest</h5>
                        <ul class="text-secondary small ps-3 mb-0 d-flex flex-column gap-1.5">
                            <li><strong>Mode Coding Bebas:</strong> Ruang eksperimen bebas untuk menulis kode apapun tanpa batasan aturan.</li>
                            <li><strong>Mode Quest:</strong> 5 level misi coding interaktif dengan instruksi bertahap dan sistem penilaian otomatis.</li>
                        </ul>
                    </div>
                </div>

                <!-- Card 2: Multi-Language Engine -->
                <div class="col-md-6 col-lg-4">
                    <div class="p-3.5 p-md-4 rounded-4 bg-body-secondary h-100 border border-secondary border-opacity-25">
                        <div class="guide-icon-avatar bg-gradient-cyan mb-3">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <h5 class="text-body font-heading fw-bold mb-2">2. Dukungan 7 Bahasa</h5>
                        <ul class="text-secondary small ps-3 mb-0 d-flex flex-column gap-1.5">
                            <li><strong>Live Web:</strong> Tab HTML, CSS, JS terintegrasi secara instan di iframe preview.</li>
                            <li><strong>Logic & Backend:</strong> JS Node.js, Python 3, PHP 8.2, C++, Java, dan SQL Query dengan console runner.</li>
                        </ul>
                    </div>
                </div>

                <!-- Card 3: IntelliSense & Auto-Suggest -->
                <div class="col-md-6 col-lg-4">
                    <div class="p-3.5 p-md-4 rounded-4 bg-body-secondary h-100 border border-secondary border-opacity-25">
                        <div class="guide-icon-avatar bg-gradient-amber mb-3">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                        </div>
                        <h5 class="text-body font-heading fw-bold mb-2">3. IntelliSense Cerdas</h5>
                        <ul class="text-secondary small ps-3 mb-0 d-flex flex-column gap-1.5">
                            <li>Muncul otomatis saat mengetik kata kunci tag, method, atau properti styling.</li>
                            <li>Tekan <kbd class="bg-dark text-warning px-1.5 py-0.5 rounded border border-warning border-opacity-50">Tab</kbd> atau <kbd class="bg-dark text-warning px-1.5 py-0.5 rounded border border-warning border-opacity-50">Enter</kbd> untuk melengkapi kode secara instan.</li>
                        </ul>
                    </div>
                </div>

                <!-- Card 4: Action Toolbar -->
                <div class="col-md-6 col-lg-4">
                    <div class="p-3.5 p-md-4 rounded-4 bg-body-secondary h-100 border border-secondary border-opacity-25">
                        <div class="guide-icon-avatar bg-gradient-green mb-3">
                            <i class="fa-solid fa-play"></i>
                        </div>
                        <h5 class="text-body font-heading fw-bold mb-2">4. Tombol Kontrol Cepat</h5>
                        <ul class="text-secondary small ps-3 mb-0 d-flex flex-column gap-1.5">
                            <li><strong>Run Code:</strong> Eksekusi kode ke visual preview atau terminal (<kbd class="bg-dark text-success px-1">Ctrl+Enter</kbd>).</li>
                            <li><strong>Format:</strong> Merapikan spasi & indentasi baris kode secara otomatis.</li>
                            <li><strong>Copy & Reset:</strong> Salin kode atau kembalikan ke kode awal template.</li>
                        </ul>
                    </div>
                </div>

                <!-- Card 5: Output Panel Trio -->
                <div class="col-md-6 col-lg-4">
                    <div class="p-3.5 p-md-4 rounded-4 bg-body-secondary h-100 border border-secondary border-opacity-25">
                        <div class="guide-icon-avatar bg-gradient-red mb-3">
                            <i class="fa-solid fa-tv"></i>
                        </div>
                        <h5 class="text-body font-heading fw-bold mb-2">5. Tiga Panel Output</h5>
                        <ul class="text-secondary small ps-3 mb-0 d-flex flex-column gap-1.5">
                            <li><strong>Visual Preview:</strong> Menampilkan tampilan render visual web yang interaktif.</li>
                            <li><strong>Terminal Console:</strong> Menampilkan teks hasil <code>console.log()</code>, print output, dan pesan error jika ada bug.</li>
                        </ul>
                    </div>
                </div>

                <!-- Card 6: Automated Test Cases -->
                <div class="col-md-6 col-lg-4">
                    <div class="p-3.5 p-md-4 rounded-4 bg-body-secondary h-100 border border-secondary border-opacity-25">
                        <div class="guide-icon-avatar bg-gradient-purple mb-3">
                            <i class="fa-solid fa-vial-circle-check"></i>
                        </div>
                        <h5 class="text-body font-heading fw-bold mb-2">6. Unit Test & Scoring</h5>
                        <ul class="text-secondary small ps-3 mb-0 d-flex flex-column gap-1.5">
                            <li>Sistem pengujian otomatis yang mengecek kode siswa terhadap berbagai skenario input.</li>
                            <li>Jika seluruh test case <span class="text-success fw-bold">PASSED</span>, skor & bintang 3 akan otomatis dicatat!</li>
                        </ul>
                    </div>
                </div>

            </div>

            <!-- 2. Tabel Panduan Badge IntelliSense Auto-Suggest -->
            <div class="p-3 p-md-4 rounded-4 bg-body-secondary border border-secondary border-opacity-25 mb-4 mb-md-5">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="fa-solid fa-tags text-primary fs-5"></i>
                    <h5 class="text-body font-heading fw-bold mb-0">Arti Badge Tipe pada Sugesti IntelliSense</h5>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-dark table-hover table-bordered mb-0 small font-monospace align-middle">
                        <thead class="table-secondary text-white">
                            <tr>
                                <th style="width: 100px;">Badge</th>
                                <th>Kategori</th>
                                <th>Penjelasan & Contoh Kegunaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge bg-danger text-white">TAG</span></td>
                                <td class="text-info">HTML Element Tag</td>
                                <td class="text-secondary font-sans-serif">Elemen kerangka HTML seperti <code>&lt;div&gt;</code>, <code>&lt;button&gt;</code>, <code>&lt;h1&gt;</code>, <code>&lt;form&gt;</code>.</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-info text-dark">ATTR</span></td>
                                <td class="text-info">HTML Attribute</td>
                                <td class="text-secondary font-sans-serif">Atribut pendukung tag HTML seperti <code>class=""</code>, <code>id=""</code>, <code>onclick=""</code>.</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-primary text-white">CSS</span></td>
                                <td class="text-info">Styling Property</td>
                                <td class="text-secondary font-sans-serif">Properti visual CSS seperti <code>background</code>, <code>color</code>, <code>border-radius</code>, <code>box-shadow</code>.</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-warning text-dark">ƒ()</span></td>
                                <td class="text-info">JavaScript Function</td>
                                <td class="text-secondary font-sans-serif">Fungsi siap pakai seperti <code>console.log()</code>, <code>.map()</code>, <code>.filter()</code>, <code>.forEach()</code>.</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-success text-white">DOM</span></td>
                                <td class="text-info">Document Object Model</td>
                                <td class="text-secondary font-sans-serif">Metode pencari elemen dokumen seperti <code>document.getElementById()</code> & <code>querySelector()</code>.</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-secondary text-white">KEYW</span></td>
                                <td class="text-info">Language Keyword</td>
                                <td class="text-secondary font-sans-serif">Kata kunci sintaks bahasa seperti <code>function</code>, <code>const</code>, <code>let</code>, <code>if / else</code>, <code>def</code>.</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-purple text-white" style="background: #a855f7 !important;">SNIP</span></td>
                                <td class="text-info">Code Snippet Template</td>
                                <td class="text-secondary font-sans-serif">Potongan blok template lengkap siap pakai, contoh: <code>display: flex</code> tengah atau struktur tabel.</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-warning text-dark">SQL</span></td>
                                <td class="text-info">Database Query</td>
                                <td class="text-secondary font-sans-serif">Klausa manipulasi basis data seperti <code>SELECT</code>, <code>WHERE</code>, <code>JOIN</code>, <code>ORDER BY</code>.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 3. Fondasi 3 Pilar Utama Web -->
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="p-3.5 p-md-4 rounded-4 bg-dark bg-opacity-50 h-100 border border-danger border-opacity-30">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fa-brands fa-html5 text-danger fs-4"></i>
                            <h6 class="text-white font-heading fw-bold mb-0">HTML5 (Struktur)</h6>
                        </div>
                        <p class="text-secondary small mb-0">
                            Sebagai rangka atau tulang punggung website. Menggunakan tag untuk menyusun judul, paragraf, tombol, formulir input, dan kartu konten.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-3.5 p-md-4 rounded-4 bg-dark bg-opacity-50 h-100 border border-primary border-opacity-30">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fa-brands fa-css3-alt text-primary fs-4"></i>
                            <h6 class="text-white font-heading fw-bold mb-0">CSS3 (Gaya & Desain)</h6>
                        </div>
                        <p class="text-secondary small mb-0">
                            Sebagai riasan visual. Mengatur warna, tata letak responsif (Flexbox & CSS Grid), animasi halus, font tipografi, dan efek glassmorphism.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-3.5 p-md-4 rounded-4 bg-dark bg-opacity-50 h-100 border border-warning border-opacity-30">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fa-brands fa-js text-warning fs-4"></i>
                            <h6 class="text-white font-heading fw-bold mb-0">JavaScript (Logika Otak)</h6>
                        </div>
                        <p class="text-secondary small mb-0">
                            Sebagai otak yang memberikan aksi. Menghitung data, merespons interaksi klik tombol, validasi formulir, dan memanipulasi tampilan secara dinamis.
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- Quest Step-by-Step Guide Modal -->
<div class="modal fade" id="questGuideModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark border border-info border-opacity-50 text-white rounded-4 shadow-2xl p-3 p-md-4">
            <div class="modal-header border-bottom border-secondary border-opacity-25 pb-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-lightbulb text-warning fs-4"></i>
                    <div>
                        <h5 class="modal-title font-heading fw-bold" id="guideModalTitle">Panduan Step-by-Step Tantangan</h5>
                        <div class="text-secondary style-tiny" id="guideModalSubtitle">Langkah demi langkah cara menyelesaikan misi coding</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body py-4" id="guideModalBody">
                <!-- Dynamic steps will be injected here -->
            </div>

            <div class="modal-footer border-top border-secondary border-opacity-25 pt-3 flex-wrap gap-2">
                <button type="button" class="btn btn-outline-secondary px-3 px-md-4 text-white" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary font-heading fw-bold px-3 px-md-4" onclick="applyQuestTemplate(); bootstrap.Modal.getInstance(document.getElementById('questGuideModal')).hide();">
                    <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Terapkan Template Misi Ini
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Quest Success Congratulation Modal -->
<div class="modal fade" id="questSuccessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border border-success text-white p-4 text-center rounded-4 shadow-2xl">
            <div class="mb-3">
                <div class="d-inline-flex p-3 rounded-circle bg-success bg-opacity-20 text-success fs-1 mb-2">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <h3 class="font-heading fw-bold text-success" id="questSuccessTitle">Tantangan Quest Berhasil!</h3>
                <div class="text-warning fs-3 mb-2">⭐⭐⭐</div>
                <p class="text-secondary small" id="questSuccessDesc">Luar biasa! Seluruh kasus uji (test cases) berhasil dilewati dengan sempurna (100% Passed).</p>
            </div>
            <div class="d-flex justify-content-center gap-3">
                <button type="button" class="btn btn-outline-secondary px-4 text-white" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success font-heading fw-bold px-4" onclick="nextQuest(); bootstrap.Modal.getInstance(document.getElementById('questSuccessModal')).hide();">
                    Lanjut Quest Berikutnya <i class="fa-solid fa-arrow-right ms-1"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- IDE Simulator JavaScript Engine -->
<script>
// --- State Management ---
let currentLang = 'web'; // 'web', 'javascript', 'python', 'php', 'cpp', 'java', 'sql'
let currentWebTab = 'html'; // 'html', 'css', 'js'
let currentOutputTab = 'preview'; // 'preview', 'console', 'tests'
let currentMode = 'sandbox'; // 'sandbox', 'quest'
let currentQuestIdx = 0;

// Multi-file Web Code Buffers
let webCode = {
    html: `<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halo Multimedia Club</title>
</head>
<body>
    <div class="card">
        <div class="badge">DIVISI PROGRAMMING</div>
        <h1>Halo Dunia Kreatif! 🚀</h1>
        <p id="deskripsi">Selamat datang di IDE Simulator Multimedia Club SMAN 1 Tamansari.</p>
        <button id="btnKlik" onclick="ubahTeks()">Klik Saya</button>
        <div id="counterText">Jumlah Klik: <strong>0</strong></div>
    </div>
</body>
</html>`,
    css: `body {
    margin: 0;
    padding: 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
    color: #ffffff;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 80vh;
}

.card {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 16px;
    padding: 30px;
    text-align: center;
    max-width: 420px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
}

.badge {
    background: #ef4444;
    color: white;
    font-size: 11px;
    font-weight: bold;
    padding: 4px 12px;
    border-radius: 999px;
    display: inline-block;
    margin-bottom: 12px;
    letter-spacing: 1px;
}

h1 {
    font-size: 24px;
    margin: 0 0 10px 0;
}

p {
    color: #94a3b8;
    font-size: 14px;
    line-height: 1.5;
}

button {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    transition: transform 0.2s, background 0.2s;
    margin: 15px 0 10px 0;
}

button:hover {
    background: #2563eb;
    transform: scale(1.05);
}

#counterText {
    font-size: 13px;
    color: #38bdf8;
    margin-top: 8px;
}`,
    js: `let totalKlik = 0;

function ubahTeks() {
    totalKlik++;
    document.getElementById('counterText').innerHTML = 'Jumlah Klik: <strong>' + totalKlik + '</strong>';
    document.getElementById('deskripsi').innerText = 'Keren! JavaScript berhasil merespons klik tombol kamu!';
    console.log('Tombol diklik! Total klik saat ini:', totalKlik);
}`
};

// Single-file Code Buffers per Language
let singleCodeBuffers = {
    javascript: `// ==========================================
// JavaScript (Node.js Logic Runner)
// Multimedia Club SMAN 1 Tamansari
// ==========================================

function sapaMember(nama, divisi) {
    return \`Halo \${nama}, selamat berkarya di Divisi \${divisi} Multimedia!\`;
}

const siswa = [
    { nama: "Rizki", divisi: "Programming", skor: 95 },
    { nama: "Aulia", divisi: "Fotografi", skor: 90 },
    { nama: "Dimas", divisi: "Videografi", skor: 88 }
];

console.log("=== DAFTAR ANGGOTA TERBAIK ===");
siswa.forEach((item, index) => {
    console.log(\`\${index + 1}. \${sapaMember(item.nama, item.divisi)} (Skor: \${item.skor})\`);
});

const totalSkor = siswa.reduce((acc, curr) => acc + curr.skor, 0);
console.log("\\nRata-rata Skor Divisi:", (totalSkor / siswa.length).toFixed(1));`,

    python: `# ==========================================
# Python 3 Code Simulator
# Multimedia Club SMAN 1 Tamansari
// ==========================================

def hitung_nilai_akhir(kehadiran, tugas, proyek):
    # Bobot: Kehadiran 20%, Tugas 30%, Proyek 50%
    nilai_akhir = (kehadiran * 0.2) + (tugas * 0.3) + (proyek * 0.5)
    return round(nilai_akhir, 2)

anggota = [
    {"nama": "Ahmad", "kehadiran": 90, "tugas": 85, "proyek": 95},
    {"nama": "Siti",  "kehadiran": 80, "tugas": 90, "proyek": 88},
    {"nama": "Budi",  "kehadiran": 75, "tugas": 70, "proyek": 80}
]

print("=== REKAP NILAI WORKSHOP MULTIMEDIA ===")
for data in anggota:
    skor = hitung_nilai_akhir(data["kehadiran"], data["tugas"], data["proyek"])
    status = "LULUS (Grade A)" if skor >= 85 else "LULUS (Grade B)"
    print(f"- {data['nama']}: Skor {skor} -> {status}")`,

    php: `<?php
// ==========================================
// PHP Code Simulator
// Multimedia Club SMAN 1 Tamansari
// ==========================================

class Member {
    public $nama;
    public $divisi;
    public $karya = [];

    public function __construct($nama, $divisi) {
        $this->nama = $nama;
        $this->divisi = $divisi;
    }

    public function tambahKarya($judul) {
        $this->karya[] = $judul;
    }

    public function getInfo() {
        return "Member: {$this->nama} | Divisi: {$this->divisi} | Total Karya: " . count($this->karya);
    }
}

$m1 = new Member("Rifki", "Programming");
$m1->tambahKarya("Website Portofolio Sekolah");
$m1->tambahKarya("Aplikasi Presensi QR");

echo "=== DATA ANGGOTA MULTIMEDIA CLUB ===\\n";
echo $m1->getInfo() . "\\n";
print_r($m1->karya);
?>`,

    cpp: `// ==========================================
// C++ Simulator (Algoritma Dasar)
// ==========================================
#include <iostream>
#include <vector>
#include <string>

using namespace std;

struct Siswa {
    string nama;
    int nilai;
};

int main() {
    cout << "=== LAB PEMROGRAMAN C++ MULTIMEDIA ===" << endl;
    
    vector<Siswa> daftar = {
        {"Dimas", 88},
        {"Rifki", 95},
        {"Siti", 91}
    };
    
    int total = 0;
    for (const auto& s : daftar) {
        cout << "- " << s.nama << " : Nilai " << s.nilai << endl;
        total += s.nilai;
    }
    
    double rata = (double)total / daftar.size();
    cout << "-----------------------------------" << endl;
    cout << "Rata-rata Kelas: " << rata << endl;
    
    return 0;
}`,

    java: `// ==========================================
// Java Virtual Machine Lab
// ==========================================
import java.util.ArrayList;

class Main {
    public static void main(String[] args) {
        System.out.println("=== MULTIMEDIA CLUB JAVA LAB ===");
        
        ArrayList<String> divisi = new ArrayList<>();
        divisi.add("Fotografi");
        divisi.add("Videografi");
        divisi.add("Desain Grafis");
        divisi.add("Programming");
        
        System.out.println("Daftar Divisi Aktif (" + divisi.size() + "):");
        for (int i = 0; i < divisi.size(); i++) {
            System.out.println((i + 1) + ". Divisi " + divisi.get(i));
        }
    }
}`,

    sql: `-- ==========================================
-- SQL Database Query Lab
-- ==========================================
SELECT 
    m.id,
    m.nama_lengkap,
    d.nama_divisi,
    COUNT(k.id) AS total_karya,
    COALESCE(SUM(p.poin), 0) AS total_poin
FROM members m
JOIN divisions d ON d.id = m.divisi_id
LEFT JOIN works k ON k.member_id = m.id
LEFT JOIN points p ON p.member_id = m.id
WHERE m.status = 'active'
GROUP BY m.id, m.nama_lengkap, d.nama_divisi
ORDER BY total_poin DESC
LIMIT 10;`
};

// --- Preset Code Templates ---
const CODE_TEMPLATES = {
    web_starter: {
        lang: 'web',
        html: webCode.html,
        css: webCode.css,
        js: webCode.js
    },
    datatypes: {
        lang: 'javascript',
        code: `// TIPE DATA & STRUKTUR ARRAY OBJEK DI JAVASCRIPT
const namaKlub = "Multimedia Club SMAN 1 Tamansari"; // String
const tahunBerdiri = 2024;                           // Number
const isActive = true;                               // Boolean

// Array Objek Koleksi Data
const anggota = [
    { id: 1, nama: "Rizki", nilai: 92, status: "Aktif" },
    { id: 2, nama: "Aulia", nilai: 88, status: "Aktif" },
    { id: 3, nama: "Budi",  nilai: 74, status: "Cuti" }
];

console.log("Nama Klub :", namaKlub);
console.log("Tahun     :", tahunBerdiri);
console.log("Total Siswa:", anggota.length);
console.log("Detail Siswa Pertama:", anggota[0].nama, "dengan nilai", anggota[0].nilai);`
    },
    conditions: {
        lang: 'javascript',
        code: `// LOGIKA PERCABANGAN (IF / ELSE IF / ELSE)
const skorPresensi = 85;
const tugasSelesai = true;

console.log("Mengecek kriteria kelulusan workshop...");

if (skorPresensi >= 80 && tugasSelesai) {
    console.log("Status: Anggota Berprestasi (Sertifikat Utama)");
} else if (skorPresensi >= 60) {
    console.log("Status: Anggota Aktif Standar");
} else {
    console.log("Status: Perlu Pembinaan Tambahan");
}`
    },
    loops: {
        lang: 'javascript',
        code: `// PERULANGAN (FOR & MAP)
const materiBelajar = ["Dasar Kamera", "Color Grading", "Flexbox CSS", "REST API"];

console.log("--- Perulangan Standar FOR ---");
for (let i = 0; i < materiBelajar.length; i++) {
    console.log((i + 1) + ". Modul: " + materiBelajar[i]);
}

console.log("\\n--- Perulangan Array forEach ---");
materiBelajar.forEach((m, idx) => console.log(\`[\${idx + 1}] \${m}\`));`
    },
    functions: {
        lang: 'javascript',
        code: `// FUNGSI & ARROW FUNCTIONS
// 1. Regular Function
function hitungTotal(a, b) {
    return a + b;
}

// 2. Arrow Function
const kuadratkan = (n) => n * n;

console.log("Hasil Penjumlahan (15 + 25):", hitungTotal(15, 25));
console.log("Hasil Kuadrat (8 x 8)      :", kuadratkan(8));`
    },
    fizzbuzz: {
        lang: 'javascript',
        code: `// ALGORITMA FIZZBUZZ KLASIK (Angka 1 s/d 15)
function jalankanFizzBuzz(max) {
    for (let i = 1; i <= max; i++) {
        if (i % 15 === 0) console.log(i, "-> FizzBuzz");
        else if (i % 3 === 0) console.log(i, "-> Fizz");
        else if (i % 5 === 0) console.log(i, "-> Buzz");
        else console.log(i);
    }
}

jalankanFizzBuzz(15);`
    },
    sql_demo: {
        lang: 'sql',
        code: `SELECT 
    members.id,
    members.nama,
    members.kelas,
    divisions.nama_divisi,
    SUM(points.jumlah) AS total_skor
FROM members
JOIN divisions ON members.divisi_id = divisions.id
JOIN points ON points.member_id = members.id
GROUP BY members.id
ORDER BY total_skor DESC;`
    }
};

// --- Interactive Quests / Challenges (5 Levels) ---
const QUESTS = [
    {
        level: 1,
        title: "Halo Programmer MM",
        lang: "javascript",
        desc: "Buat fungsi bernama <code>sapaMember(nama)</code> yang mengembalikan teks dengan format: <code>\"Halo [nama], selamat berkarya di Multimedia Club!\"</code>",
        starterCode: `function sapaMember(nama) {
    // Tulis kode kamu di bawah ini:
    
}`,
        steps: [
            "Gunakan parameter <code>nama</code> yang diterima oleh fungsi.",
            "Gunakan string concatenation (<code>\"Halo \" + nama + ...</code>) atau template literals (<code>\`Halo \${nama}...\`</code>).",
            "Kembalikan string dengan keyword <code>return</code>."
        ],
        tests: [
            {
                name: "Test 1: Sapa 'Rizki'",
                run: (code) => {
                    const fn = new Function(code + "; return sapaMember('Rizki');");
                    return { actual: fn(), expected: "Halo Rizki, selamat berkarya di Multimedia Club!" };
                }
            },
            {
                name: "Test 2: Sapa 'Aulia'",
                run: (code) => {
                    const fn = new Function(code + "; return sapaMember('Aulia');");
                    return { actual: fn(), expected: "Halo Aulia, selamat berkarya di Multimedia Club!" };
                }
            }
        ]
    },
    {
        level: 2,
        title: "Kalkulator Nilai Rata-rata",
        lang: "javascript",
        desc: "Buat fungsi <code>hitungRataRata(angkaList)</code> yang menerima array angka dan mengembalikan nilai rata-ratanya (number).",
        starterCode: `function hitungRataRata(angkaList) {
    // 1. Buat variabel total = 0
    // 2. Loop dan jumlahkan semua angka
    // 3. Bagi dengan panjang array (angkaList.length)
    
}`,
        steps: [
            "Inisialisasi variabel total: <code>let total = 0;</code>",
            "Gunakan loop <code>for (let i = 0; i < angkaList.length; i++) { total += angkaList[i]; }</code>",
            "Kembalikan <code>total / angkaList.length;</code>"
        ],
        tests: [
            {
                name: "Test 1: Array [80, 90, 100]",
                run: (code) => {
                    const fn = new Function(code + "; return hitungRataRata([80, 90, 100]);");
                    return { actual: fn(), expected: 90 };
                }
            },
            {
                name: "Test 2: Array [70, 75, 80, 85, 90]",
                run: (code) => {
                    const fn = new Function(code + "; return hitungRataRata([70, 75, 80, 85, 90]);");
                    return { actual: fn(), expected: 80 };
                }
            }
        ]
    },
    {
        level: 3,
        title: "Sistem Status Kelulusan",
        lang: "javascript",
        desc: "Buat fungsi <code>cekKelulusan(kehadiran, nilaiTugas)</code>. Jika <code>kehadiran >= 75</code> DAN <code>nilaiTugas >= 70</code> return <code>\"LULUS\"</code>, selain itu return <code>\"REMEDIAL\"</code>.",
        starterCode: `function cekKelulusan(kehadiran, nilaiTugas) {
    // Gunakan if / else dengan operator && (AND)
    
}`,
        steps: [
            "Gunakan struktur <code>if (kehadiran >= 75 && nilaiTugas >= 70)</code>.",
            "Di dalam blok if, kembalikan string <code>\"LULUS\"</code>.",
            "Di blok <code>else</code>, kembalikan string <code>\"REMEDIAL\"</code>."
        ],
        tests: [
            {
                name: "Test 1: Kehadiran 80 & Tugas 85",
                run: (code) => {
                    const fn = new Function(code + "; return cekKelulusan(80, 85);");
                    return { actual: fn(), expected: "LULUS" };
                }
            },
            {
                name: "Test 2: Kehadiran 60 & Tugas 90",
                run: (code) => {
                    const fn = new Function(code + "; return cekKelulusan(60, 90);");
                    return { actual: fn(), expected: "REMEDIAL" };
                }
            }
        ]
    },
    {
        level: 4,
        title: "Logika FizzBuzz Modulo",
        lang: "javascript",
        desc: "Buat fungsi <code>fizzBuzz(n)</code>. Jika kelipatan 3 dan 5 return <code>\"FizzBuzz\"</code>, jika kelipatan 3 return <code>\"Fizz\"</code>, jika kelipatan 5 return <code>\"Buzz\"</code>, selain itu return angkanya dalam bentuk string.",
        starterCode: `function fizzBuzz(n) {
    // Gunakan operator modulo %
    
}`,
        steps: [
            "Penting: Periksa kondisi kelipatan 15 terlebih dahulu: <code>if (n % 15 === 0) return \"FizzBuzz\";</code>",
            "Lalu periksa: <code>else if (n % 3 === 0) return \"Fizz\";</code>",
            "Lalu periksa: <code>else if (n % 5 === 0) return \"Buzz\";</code>",
            "Terakhir: <code>else return String(n);</code>"
        ],
        tests: [
            {
                name: "Test 1: fizzBuzz(15)",
                run: (code) => {
                    const fn = new Function(code + "; return fizzBuzz(15);");
                    return { actual: fn(), expected: "FizzBuzz" };
                }
            },
            {
                name: "Test 2: fizzBuzz(9)",
                run: (code) => {
                    const fn = new Function(code + "; return fizzBuzz(9);");
                    return { actual: fn(), expected: "Fizz" };
                }
            },
            {
                name: "Test 3: fizzBuzz(10)",
                run: (code) => {
                    const fn = new Function(code + "; return fizzBuzz(10);");
                    return { actual: fn(), expected: "Buzz" };
                }
            },
            {
                name: "Test 4: fizzBuzz(7)",
                run: (code) => {
                    const fn = new Function(code + "; return String(fizzBuzz(7));");
                    return { actual: fn(), expected: "7" };
                }
            }
        ]
    },
    {
        level: 5,
        title: "Pengecek Kata Palindrome",
        lang: "javascript",
        desc: "Buat fungsi <code>isPalindrome(kata)</code> yang mengembalikan <code>true</code> jika kata dibaca sama dari depan maupun belakang (misal: 'katak', 'radar'), dan <code>false</code> jika tidak.",
        starterCode: `function isPalindrome(kata) {
    // Ubah ke lowercase dan balikkan string kata
    
}`,
        steps: [
            "Ubah kata menjadi huruf kecil: <code>const str = kata.toLowerCase();</code>",
            "Balikkan kata dengan: <code>const reversed = str.split('').reverse().join('');</code>",
            "Bandingkan: <code>return str === reversed;</code>"
        ],
        tests: [
            {
                name: "Test 1: isPalindrome('katak')",
                run: (code) => {
                    const fn = new Function(code + "; return isPalindrome('katak');");
                    return { actual: fn(), expected: true };
                }
            },
            {
                name: "Test 2: isPalindrome('multimedia')",
                run: (code) => {
                    const fn = new Function(code + "; return isPalindrome('multimedia');");
                    return { actual: fn(), expected: false };
                }
            }
        ]
    }
];

// --- Comprehensive Autocomplete & IntelliSense Dictionaries ---
const SUGGESTION_DICTIONARIES = {
    html: [
        { label: 'div', insert: '<div>\n    \n</div>', cursorOffset: -7, type: 'tag', badge: 'TAG', desc: 'Kontainer blok HTML' },
        { label: 'button', insert: '<button class="btn">\n    \n</button>', cursorOffset: -10, type: 'tag', badge: 'TAG', desc: 'Tombol aksi interaktif' },
        { label: 'h1', insert: '<h1></h1>', cursorOffset: -5, type: 'tag', badge: 'TAG', desc: 'Heading tingkat 1' },
        { label: 'h2', insert: '<h2></h2>', cursorOffset: -5, type: 'tag', badge: 'TAG', desc: 'Heading tingkat 2' },
        { label: 'h3', insert: '<h3></h3>', cursorOffset: -5, type: 'tag', badge: 'TAG', desc: 'Heading tingkat 3' },
        { label: 'p', insert: '<p></p>', cursorOffset: -4, type: 'tag', badge: 'TAG', desc: 'Paragraf teks' },
        { label: 'span', insert: '<span></span>', cursorOffset: -7, type: 'tag', badge: 'TAG', desc: 'Elemen inline teks' },
        { label: 'a', insert: '<a href="#"></a>', cursorOffset: -4, type: 'tag', badge: 'TAG', desc: 'Tautan hyperlink' },
        { label: 'img', insert: '<img src="" alt="">', cursorOffset: -10, type: 'tag', badge: 'TAG', desc: 'Gambar media' },
        { label: 'input', insert: '<input type="text" placeholder="">', cursorOffset: -2, type: 'tag', badge: 'TAG', desc: 'Form input teks' },
        { label: 'form', insert: '<form action="" method="POST">\n    \n</form>', cursorOffset: -8, type: 'tag', badge: 'TAG', desc: 'Formulir data' },
        { label: 'table', insert: '<table>\n    <tr>\n        <th>Judul</th>\n    </tr>\n    <tr>\n        <td>Data</td>\n    </tr>\n</table>', cursorOffset: -1, type: 'snippet', badge: 'SNIP', desc: 'Tabel data HTML' },
        { label: 'ul', insert: '<ul>\n    <li>Item 1</li>\n    <li>Item 2</li>\n</ul>', cursorOffset: -1, type: 'snippet', badge: 'SNIP', desc: 'Daftar bullet list' },
        { label: 'class', insert: 'class=""', cursorOffset: -1, type: 'property', badge: 'ATTR', desc: 'Atribut CSS class' },
        { label: 'id', insert: 'id=""', cursorOffset: -1, type: 'property', badge: 'ATTR', desc: 'Atribut ID unik' },
        { label: 'onclick', insert: 'onclick=""', cursorOffset: -1, type: 'property', badge: 'ATTR', desc: 'Event handler klik' },
        { label: 'style', insert: 'style=""', cursorOffset: -1, type: 'property', badge: 'ATTR', desc: 'Inline CSS styling' },
        { label: 'script', insert: '<script>\n    \n<\/script>', cursorOffset: -10, type: 'tag', badge: 'TAG', desc: 'Blok script JavaScript' },
        { label: 'DOCTYPE', insert: '<!DOCTYPE html>', cursorOffset: 0, type: 'keyword', badge: 'KEYW', desc: 'Deklarasi HTML5' }
    ],

    css: [
        { label: 'background', insert: 'background: ;', cursorOffset: -1, type: 'property', badge: 'CSS', desc: 'Warna latar / gradasi' },
        { label: 'color', insert: 'color: ;', cursorOffset: -1, type: 'property', badge: 'CSS', desc: 'Warna teks' },
        { label: 'display: flex', insert: 'display: flex;\njustify-content: center;\nalign-items: center;', cursorOffset: 0, type: 'snippet', badge: 'SNIP', desc: 'Tata letak Flexbox tengah' },
        { label: 'display: grid', insert: 'display: grid;\nplace-items: center;', cursorOffset: 0, type: 'snippet', badge: 'SNIP', desc: 'Tata letak Grid tengah' },
        { label: 'padding', insert: 'padding: 16px;', cursorOffset: -3, type: 'property', badge: 'CSS', desc: 'Jarak dalam elemen' },
        { label: 'margin', insert: 'margin: 16px;', cursorOffset: -3, type: 'property', badge: 'CSS', desc: 'Jarak luar elemen' },
        { label: 'border-radius', insert: 'border-radius: 12px;', cursorOffset: -3, type: 'property', badge: 'CSS', desc: 'Sudut melengkung' },
        { label: 'border', insert: 'border: 1px solid rgba(255, 255, 255, 0.2);', cursorOffset: 0, type: 'property', badge: 'CSS', desc: 'Garis tepi elemen' },
        { label: 'font-size', insert: 'font-size: 16px;', cursorOffset: -3, type: 'property', badge: 'CSS', desc: 'Ukuran huruf' },
        { label: 'font-weight', insert: 'font-weight: bold;', cursorOffset: 0, type: 'property', badge: 'CSS', desc: 'Ketebalan teks' },
        { label: 'font-family', insert: 'font-family: sans-serif;', cursorOffset: 0, type: 'property', badge: 'CSS', desc: 'Jenis font teks' },
        { label: 'box-shadow', insert: 'box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);', cursorOffset: 0, type: 'property', badge: 'CSS', desc: 'Bayangan efek glow' },
        { label: 'transition', insert: 'transition: all 0.3s ease;', cursorOffset: 0, type: 'property', badge: 'CSS', desc: 'Animasi transisi halus' },
        { label: 'transform', insert: 'transform: scale(1.05);', cursorOffset: 0, type: 'property', badge: 'CSS', desc: 'Transformasi posisi / skala' },
        { label: 'cursor: pointer', insert: 'cursor: pointer;', cursorOffset: 0, type: 'property', badge: 'CSS', desc: 'Kursor bentuk jari' },
        { label: 'width', insert: 'width: 100%;', cursorOffset: 0, type: 'property', badge: 'CSS', desc: 'Lebar elemen' },
        { label: 'height', insert: 'height: 100%;', cursorOffset: 0, type: 'property', badge: 'CSS', desc: 'Tinggi elemen' },
        { label: 'text-align', insert: 'text-align: center;', cursorOffset: 0, type: 'property', badge: 'CSS', desc: 'Perataan teks' },
        { label: 'opacity', insert: 'opacity: 0.9;', cursorOffset: 0, type: 'property', badge: 'CSS', desc: 'Tingkat transparansi' }
    ],

    javascript: [
        { label: 'console.log', insert: 'console.log();', cursorOffset: -2, type: 'function', badge: 'ƒ()', desc: 'Cetak output ke console' },
        { label: 'console.warn', insert: 'console.warn();', cursorOffset: -2, type: 'function', badge: 'ƒ()', desc: 'Peringatan warning console' },
        { label: 'console.error', insert: 'console.error();', cursorOffset: -2, type: 'function', badge: 'ƒ()', desc: 'Pesan error console' },
        { label: 'function', insert: 'function name() {\n    \n}', cursorOffset: -12, type: 'keyword', badge: 'KEYW', desc: 'Deklarasi fungsi baru' },
        { label: 'const', insert: 'const name = ;', cursorOffset: -1, type: 'keyword', badge: 'KEYW', desc: 'Variabel konstan' },
        { label: 'let', insert: 'let name = ;', cursorOffset: -1, type: 'keyword', badge: 'KEYW', desc: 'Variabel dinamis' },
        { label: 'return', insert: 'return ;', cursorOffset: -1, type: 'keyword', badge: 'KEYW', desc: 'Mengembalikan nilai' },
        { label: 'document.getElementById', insert: "document.getElementById('')", cursorOffset: -2, type: 'function', badge: 'DOM', desc: 'Cari elemen HTML by ID' },
        { label: 'document.querySelector', insert: "document.querySelector('')", cursorOffset: -2, type: 'function', badge: 'DOM', desc: 'Cari elemen HTML by CSS selector' },
        { label: 'addEventListener', insert: "addEventListener('click', () => {\n    \n});", cursorOffset: -4, type: 'function', badge: 'ƒ()', desc: 'Dengarkan event user' },
        { label: 'if', insert: 'if (condition) {\n    \n}', cursorOffset: -14, type: 'keyword', badge: 'KEYW', desc: 'Percabangan logika' },
        { label: 'else if', insert: 'else if (condition) {\n    \n}', cursorOffset: -14, type: 'keyword', badge: 'KEYW', desc: 'Percabangan kondisi lanjut' },
        { label: 'else', insert: 'else {\n    \n}', cursorOffset: -2, type: 'keyword', badge: 'KEYW', desc: 'Kondisi default else' },
        { label: 'for loop', insert: 'for (let i = 0; i < length; i++) {\n    \n}', cursorOffset: -18, type: 'snippet', badge: 'SNIP', desc: 'Perulangan for index' },
        { label: 'forEach', insert: '.forEach((item, index) => {\n    \n});', cursorOffset: -4, type: 'function', badge: 'ƒ()', desc: 'Perulangan tiap elemen array' },
        { label: 'map', insert: '.map(item => item)', cursorOffset: -1, type: 'function', badge: 'ƒ()', desc: 'Transformasi array baru' },
        { label: 'filter', insert: '.filter(item => item !== null)', cursorOffset: -1, type: 'function', badge: 'ƒ()', desc: 'Filter elemen array' },
        { label: 'reduce', insert: '.reduce((acc, curr) => acc + curr, 0)', cursorOffset: 0, type: 'function', badge: 'ƒ()', desc: 'Akumulasi nilai array' },
        { label: 'JSON.stringify', insert: 'JSON.stringify()', cursorOffset: -1, type: 'function', badge: 'JSON', desc: 'Objek ke JSON string' },
        { label: 'JSON.parse', insert: 'JSON.parse()', cursorOffset: -1, type: 'function', badge: 'JSON', desc: 'JSON string ke objek' },
        { label: 'Math.floor', insert: 'Math.floor()', cursorOffset: -1, type: 'function', badge: 'MATH', desc: 'Pembulatan ke bawah' },
        { label: 'Math.random', insert: 'Math.random()', cursorOffset: 0, type: 'function', badge: 'MATH', desc: 'Angka acak 0 s/d 1' },
        { label: 'setTimeout', insert: 'setTimeout(() => {\n    \n}, 1000);', cursorOffset: -9, type: 'function', badge: 'ƒ()', desc: 'Timer tunda eksekusi' }
    ],

    python: [
        { label: 'print', insert: 'print()', cursorOffset: -1, type: 'function', badge: 'ƒ()', desc: 'Cetak output console Python' },
        { label: 'def', insert: 'def function_name():\n    pass', cursorOffset: -21, type: 'keyword', badge: 'KEYW', desc: 'Definisi fungsi baru' },
        { label: 'return', insert: 'return ', cursorOffset: 0, type: 'keyword', badge: 'KEYW', desc: 'Kembalikan nilai fungsi' },
        { label: 'if / else', insert: 'if condition:\n    pass\nelse:\n    pass', cursorOffset: -27, type: 'snippet', badge: 'SNIP', desc: 'Percabangan if else' },
        { label: 'for in', insert: 'for item in items:\n    pass', cursorOffset: -8, type: 'snippet', badge: 'SNIP', desc: 'Perulangan list' },
        { label: 'while', insert: 'while condition:\n    pass', cursorOffset: -8, type: 'keyword', badge: 'KEYW', desc: 'Perulangan while' },
        { label: 'len', insert: 'len()', cursorOffset: -1, type: 'function', badge: 'ƒ()', desc: 'Hitung panjang list/string' },
        { label: 'append', insert: '.append()', cursorOffset: -1, type: 'function', badge: 'ƒ()', desc: 'Tambah item ke list' },
        { label: 'range', insert: 'range()', cursorOffset: -1, type: 'function', badge: 'ƒ()', desc: 'Rentang angka perulangan' },
        { label: 'import', insert: 'import ', cursorOffset: 0, type: 'keyword', badge: 'KEYW', desc: 'Impor modul Python' }
    ],

    php: [
        { label: 'echo', insert: 'echo "";', cursorOffset: -2, type: 'keyword', badge: 'PHP', desc: 'Cetak output teks' },
        { label: 'print_r', insert: 'print_r();', cursorOffset: -2, type: 'function', badge: 'ƒ()', desc: 'Cetak struktur array' },
        { label: 'var_dump', insert: 'var_dump();', cursorOffset: -2, type: 'function', badge: 'ƒ()', desc: 'Detail tipe & nilai variabel' },
        { label: 'function', insert: 'function name() {\n    \n}', cursorOffset: -12, type: 'keyword', badge: 'KEYW', desc: 'Fungsi PHP baru' },
        { label: 'class', insert: 'class Name {\n    public $prop;\n}', cursorOffset: -17, type: 'keyword', badge: 'KEYW', desc: 'Deklarasi class OOP' },
        { label: 'foreach', insert: 'foreach ($array as $item) {\n    \n}', cursorOffset: -4, type: 'snippet', badge: 'SNIP', desc: 'Perulangan array PHP' },
        { label: 'count', insert: 'count()', cursorOffset: -1, type: 'function', badge: 'ƒ()', desc: 'Hitung total elemen' },
        { label: 'return', insert: 'return ;', cursorOffset: -1, type: 'keyword', badge: 'KEYW', desc: 'Kembalikan nilai' }
    ],

    cpp: [
        { label: 'cout <<', insert: 'cout << "" << endl;', cursorOffset: -10, type: 'keyword', badge: 'C++', desc: 'Output stream konsol C++' },
        { label: 'cin >>', insert: 'cin >> ;', cursorOffset: -1, type: 'keyword', badge: 'C++', desc: 'Input stream konsol C++' },
        { label: 'vector', insert: 'vector<int> ', cursorOffset: 0, type: 'keyword', badge: 'TYPE', desc: 'Daftar dinamis vector C++' },
        { label: 'string', insert: 'string ', cursorOffset: 0, type: 'keyword', badge: 'TYPE', desc: 'Tipe data teks C++' },
        { label: 'int', insert: 'int ', cursorOffset: 0, type: 'keyword', badge: 'TYPE', desc: 'Tipe data bilangan bulat' },
        { label: 'return', insert: 'return 0;', cursorOffset: 0, type: 'keyword', badge: 'KEYW', desc: 'Kembalikan exit code' }
    ],

    java: [
        { label: 'System.out.println', insert: 'System.out.println();', cursorOffset: -2, type: 'function', badge: 'JAVA', desc: 'Cetak baris teks konsol Java' },
        { label: 'public static void main', insert: 'public static void main(String[] args) {\n    \n}', cursorOffset: -4, type: 'snippet', badge: 'SNIP', desc: 'Fungsi utama program Java' },
        { label: 'String', insert: 'String ', cursorOffset: 0, type: 'keyword', badge: 'TYPE', desc: 'Tipe data objek String' },
        { label: 'int', insert: 'int ', cursorOffset: 0, type: 'keyword', badge: 'TYPE', desc: 'Tipe data integer' },
        { label: 'ArrayList', insert: 'ArrayList<String> ', cursorOffset: 0, type: 'keyword', badge: 'TYPE', desc: 'Koleksi ArrayList Java' }
    ],

    sql: [
        { label: 'SELECT', insert: 'SELECT * FROM table_name;', cursorOffset: -12, type: 'keyword', badge: 'SQL', desc: 'Ambil kolom data tabel' },
        { label: 'WHERE', insert: 'WHERE status = "active"', cursorOffset: 0, type: 'keyword', badge: 'SQL', desc: 'Klausa filter kondisi' },
        { label: 'JOIN', insert: 'JOIN other_table ON other_table.id = this_table.foreign_id', cursorOffset: 0, type: 'keyword', badge: 'SQL', desc: 'Gabungkan data antar tabel' },
        { label: 'ORDER BY', insert: 'ORDER BY column_name DESC;', cursorOffset: -6, type: 'keyword', badge: 'SQL', desc: 'Urutkan hasil query' },
        { label: 'GROUP BY', insert: 'GROUP BY column_name', cursorOffset: 0, type: 'keyword', badge: 'SQL', desc: 'Kelompokkan agregasi' },
        { label: 'INSERT INTO', insert: 'INSERT INTO table_name (col1, col2) VALUES (val1, val2);', cursorOffset: -16, type: 'keyword', badge: 'SQL', desc: 'Tambah baris data baru' }
    ]
};

// --- Autocomplete State ---
let currentSuggestions = [];
let activeSuggestionIdx = 0;
let suggestionPrefix = '';

const autocompletePopup = document.getElementById('autocompletePopup');
const suggestionList = document.getElementById('suggestionList');

// --- Editor Event Handlers & Initialization ---
const editorTextarea = document.getElementById('codeEditor');
const editorGutter = document.getElementById('editorGutter');

function onEditorInput() {
    const code = editorTextarea.value;
    
    // Save to active buffer
    if (currentLang === 'web') {
        webCode[currentWebTab] = code;
    } else {
        singleCodeBuffers[currentLang] = code;
    }

    updateGutter();
    updateEditorStats();
    handleAutocompleteTrigger();
}

function handleAutocompleteTrigger() {
    const cursor = editorTextarea.selectionStart;
    const textBefore = editorTextarea.value.substring(0, cursor);
    
    // Extract current word/token before cursor
    const match = textBefore.match(/[a-zA-Z0-9_:\-.<$]+$/);
    if (!match || match[0].length < 1) {
        hideSuggestions();
        return;
    }

    const word = match[0].toLowerCase();
    suggestionPrefix = match[0];

    // Determine target dictionary
    let dictKey = currentLang;
    if (currentLang === 'web') {
        dictKey = currentWebTab === 'html' ? 'html' : (currentWebTab === 'css' ? 'css' : 'javascript');
    }

    const dict = SUGGESTION_DICTIONARIES[dictKey] || [];
    currentSuggestions = dict.filter(item => item.label.toLowerCase().includes(word) || item.label.toLowerCase().startsWith(word));

    if (currentSuggestions.length > 0) {
        renderSuggestions();
        showSuggestionsNearCursor();
    } else {
        hideSuggestions();
    }
}

function renderSuggestions() {
    suggestionList.innerHTML = '';
    activeSuggestionIdx = 0;

    const badgeColorMap = {
        TAG: 'bg-danger text-white',
        ATTR: 'bg-info text-dark',
        CSS: 'bg-primary text-white',
        'ƒ()': 'bg-warning text-dark',
        DOM: 'bg-success text-white',
        KEYW: 'bg-secondary text-white',
        SNIP: 'bg-purple text-white',
        JSON: 'bg-info text-dark',
        MATH: 'bg-danger text-white',
        PHP: 'bg-primary text-white',
        'C++': 'bg-secondary text-white',
        JAVA: 'bg-danger text-white',
        SQL: 'bg-warning text-dark',
        TYPE: 'bg-info text-dark'
    };

    currentSuggestions.slice(0, 8).forEach((item, idx) => {
        const row = document.createElement('div');
        row.className = 'suggestion-item' + (idx === 0 ? ' selected' : '');
        row.onmousedown = (e) => {
            e.preventDefault();
            applySuggestion(idx);
        };

        const badgeClass = badgeColorMap[item.badge] || 'bg-secondary text-white';

        row.innerHTML = `
            <div class="d-flex align-items-center gap-2 text-truncate">
                <span class="badge ${badgeClass} suggestion-badge-type">${item.badge}</span>
                <span class="fw-semibold text-white font-monospace">${item.label}</span>
            </div>
            <span class="style-tiny text-secondary text-truncate ms-2" style="max-width: 140px;">${item.desc || ''}</span>
        `;
        suggestionList.appendChild(row);
    });
}

function showSuggestionsNearCursor() {
    autocompletePopup.classList.remove('d-none');
    
    // Position near the current line in editor
    const cursor = editorTextarea.selectionStart;
    const lines = editorTextarea.value.substring(0, cursor).split('\n');
    const lineIndex = lines.length;
    const colIndex = lines[lines.length - 1].length;

    const topPx = Math.min(editorTextarea.clientHeight - 180, Math.max(16, (lineIndex * 22) - editorTextarea.scrollTop + 18));
    const leftPx = Math.min(editorTextarea.clientWidth - 280, Math.max(44, (colIndex * 8.5) + 44));

    autocompletePopup.style.top = `${Math.max(10, topPx)}px`;
    autocompletePopup.style.left = `${Math.max(10, leftPx)}px`;
}

function hideSuggestions() {
    autocompletePopup.classList.add('d-none');
    currentSuggestions = [];
}

function updateSuggestionSelection() {
    const items = suggestionList.querySelectorAll('.suggestion-item');
    items.forEach((it, idx) => {
        if (idx === activeSuggestionIdx) {
            it.classList.add('selected');
            it.scrollIntoView({ block: 'nearest' });
        } else {
            it.classList.remove('selected');
        }
    });
}

function applySuggestion(idx) {
    const item = currentSuggestions[idx];
    if (!item) return;

    const cursor = editorTextarea.selectionStart;
    const text = editorTextarea.value;
    
    // Replace the prefix with the full suggestion insertion
    const startPos = cursor - suggestionPrefix.length;
    const endPos = cursor;

    const newText = text.substring(0, startPos) + item.insert + text.substring(endPos);
    editorTextarea.value = newText;

    // Set cursor position smoothly
    const newCursor = startPos + item.insert.length + (item.cursorOffset || 0);
    editorTextarea.selectionStart = editorTextarea.selectionEnd = newCursor;

    onEditorInput();
    hideSuggestions();
    editorTextarea.focus();
}

function updateGutter() {
    const lines = editorTextarea.value.split('\n').length;
    let gutterText = '';
    for (let i = 1; i <= Math.max(1, lines); i++) {
        gutterText += i + '\n';
    }
    editorGutter.innerText = gutterText;
}

function updateEditorStats() {
    const text = editorTextarea.value;
    const selStart = editorTextarea.selectionStart;
    const linesUpToCursor = text.substring(0, selStart).split('\n');
    const line = linesUpToCursor.length;
    const col = linesUpToCursor[linesUpToCursor.length - 1].length + 1;

    document.getElementById('editorStatsPos').innerText = `Ln ${line}, Col ${col}`;
    document.getElementById('editorStatsLen').innerText = `${text.length} Karakter`;
}

function onEditorKeyDown(e) {
    // Autocomplete Navigation
    if (!autocompletePopup.classList.contains('d-none') && currentSuggestions.length > 0) {
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            activeSuggestionIdx = (activeSuggestionIdx + 1) % currentSuggestions.length;
            updateSuggestionSelection();
            return;
        }
        if (e.key === 'ArrowUp') {
            e.preventDefault();
            activeSuggestionIdx = (activeSuggestionIdx - 1 + currentSuggestions.length) % currentSuggestions.length;
            updateSuggestionSelection();
            return;
        }
        if (e.key === 'Enter' || e.key === 'Tab') {
            e.preventDefault();
            applySuggestion(activeSuggestionIdx);
            return;
        }
        if (e.key === 'Escape') {
            e.preventDefault();
            hideSuggestions();
            return;
        }
    }

    // Allow Tab key indent when popup is not active
    if (e.key === 'Tab') {
        e.preventDefault();
        const start = editorTextarea.selectionStart;
        const end = editorTextarea.selectionEnd;
        editorTextarea.value = editorTextarea.value.substring(0, start) + '    ' + editorTextarea.value.substring(end);
        editorTextarea.selectionStart = editorTextarea.selectionEnd = start + 4;
        onEditorInput();
    }

    // Ctrl+Enter or Cmd+Enter to Run Code
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        e.preventDefault();
        runCode();
    }
}

// Synchronize gutter scrolling with editor textarea
editorTextarea.addEventListener('scroll', () => {
    editorGutter.scrollTop = editorTextarea.scrollTop;
    if (!autocompletePopup.classList.contains('d-none')) {
        showSuggestionsNearCursor();
    }
});

// --- Language & Tab Switching ---
function changeLanguage(langKey) {
    currentLang = langKey;
    const webTabsHeader = document.getElementById('webTabsHeader');
    const singleTabHeader = document.getElementById('singleTabHeader');
    const singleTabLabel = document.getElementById('singleTabLabel');
    const singleTabIcon = document.getElementById('singleTabIcon');
    const statsLang = document.getElementById('editorStatsLang');

    if (langKey === 'web') {
        webTabsHeader.classList.remove('d-none');
        singleTabHeader.classList.add('d-none');
        switchWebTab(currentWebTab);
        statsLang.innerText = 'HTML5 / Web';
        switchOutputTab('preview');
    } else {
        webTabsHeader.classList.add('d-none');
        singleTabHeader.classList.remove('d-none');

        const labelMap = {
            javascript: { label: 'main.js', icon: 'fa-brands fa-js text-warning', name: 'JavaScript (Node.js)' },
            python:     { label: 'main.py', icon: 'fa-brands fa-python text-info', name: 'Python 3' },
            php:        { label: 'index.php', icon: 'fa-brands fa-php text-primary', name: 'PHP 8.2' },
            cpp:        { label: 'main.cpp', icon: 'fa-solid fa-gear text-secondary', name: 'C++' },
            java:       { label: 'Main.java', icon: 'fa-brands fa-java text-danger', name: 'Java' },
            sql:        { label: 'query.sql', icon: 'fa-solid fa-database text-warning', name: 'SQL Query' }
        };

        const info = labelMap[langKey] || { label: 'code.txt', icon: 'fa-solid fa-code', name: langKey };
        singleTabLabel.innerText = info.label;
        singleTabIcon.className = info.icon;
        statsLang.innerText = info.name;

        editorTextarea.value = singleCodeBuffers[langKey] || '';
        updateGutter();
        updateEditorStats();
        switchOutputTab('console');
    }
}

function switchWebTab(tabKey) {
    currentWebTab = tabKey;
    document.getElementById('tabHtmlBtn').className = 'ide-tab-btn' + (tabKey === 'html' ? ' active' : '');
    document.getElementById('tabCssBtn').className  = 'ide-tab-btn' + (tabKey === 'css' ? ' active' : '');
    document.getElementById('tabJsBtn').className   = 'ide-tab-btn' + (tabKey === 'js' ? ' active' : '');

    editorTextarea.value = webCode[tabKey] || '';
    updateGutter();
    updateEditorStats();
}

function switchOutputTab(tabKey) {
    currentOutputTab = tabKey;
    const previewContainer = document.getElementById('outputPreviewContainer');
    const consoleContainer = document.getElementById('outputConsoleContainer');
    const testsContainer   = document.getElementById('outputTestsContainer');

    const previewBtn = document.getElementById('tabOutputPreviewBtn');
    const consoleBtn = document.getElementById('tabOutputConsoleBtn');
    const testsBtn   = document.getElementById('tabOutputTestsBtn');

    previewBtn.className = 'ide-tab-btn' + (tabKey === 'preview' ? ' active' : '');
    consoleBtn.className = 'ide-tab-btn' + (tabKey === 'console' ? ' active' : '');
    testsBtn.className   = 'ide-tab-btn' + (tabKey === 'tests' ? ' active' : '');

    previewContainer.classList.toggle('d-none', tabKey !== 'preview');
    previewContainer.classList.toggle('d-flex', tabKey === 'preview');

    consoleContainer.classList.toggle('d-none', tabKey !== 'console');
    consoleContainer.classList.toggle('d-flex', tabKey === 'console');

    testsContainer.classList.toggle('d-none', tabKey !== 'tests');
    testsContainer.classList.toggle('d-flex', tabKey === 'tests');
}

// --- Live Execution & Runner Engine ---
function runCode() {
    const startTime = performance.now();

    if (currentLang === 'web') {
        // Render to Web Preview Iframe
        refreshPreview();
        switchOutputTab('preview');
    } else if (currentLang === 'javascript') {
        // Execute JavaScript logic & capture console logs
        switchOutputTab('console');
        executeJavaScriptLogic(editorTextarea.value, startTime);
    } else {
        // Execute simulated Python / PHP / C++ / Java / SQL runner
        switchOutputTab('console');
        simulateScriptExecution(currentLang, editorTextarea.value, startTime);
    }
}

function refreshPreview() {
    const iframe = document.getElementById('previewIframe');
    const combinedDoc = `
        <!DOCTYPE html>
        <html>
        <head>
            <style>${webCode.css}</style>
        </head>
        <body>
            ${webCode.html}
            <script>
                try {
                    ${webCode.js}
                } catch(e) {
                    console.error('JS Runtime Error:', e);
                }
            <\/script>
        </body>
        </html>
    `;
    iframe.srcdoc = combinedDoc;
}

function executeJavaScriptLogic(code, startTime) {
    const terminal = document.getElementById('terminalOutput');
    const statusBadge = document.getElementById('terminalStatusBadge');
    let logs = [];

    // Custom console wrapper
    const customConsole = {
        log: (...args) => logs.push(args.map(a => typeof a === 'object' ? JSON.stringify(a, null, 2) : String(a)).join(' ')),
        warn: (...args) => logs.push('[WARN] ' + args.join(' ')),
        error: (...args) => logs.push('[ERROR] ' + args.join(' '))
    };

    try {
        const runFn = new Function('console', code);
        runFn(customConsole);

        const endTime = performance.now();
        const durationSec = ((endTime - startTime) / 1000).toFixed(4);

        statusBadge.className = 'ide-status-pill pill-success';
        statusBadge.innerHTML = '<i class="fa-solid fa-circle-check me-1"></i> Successfully Executed';

        document.getElementById('terminalExecTime').innerText = `${durationSec}s`;
        document.getElementById('terminalExecMem').innerText = `${(Math.random() * 5 + 10).toFixed(2)} MB`;

        terminal.className = 'output-terminal';
        terminal.innerText = logs.length > 0 ? logs.join('\n') : '/* Program selesai dieksekusi tanpa output console.log(). */';
    } catch (err) {
        const endTime = performance.now();
        const durationSec = ((endTime - startTime) / 1000).toFixed(4);

        statusBadge.className = 'ide-status-pill pill-danger';
        statusBadge.innerHTML = '<i class="fa-solid fa-circle-xmark me-1"></i> Runtime Error';

        document.getElementById('terminalExecTime').innerText = `${durationSec}s`;

        terminal.className = 'output-terminal error';
        terminal.innerText = `${err.name}: ${err.message}\n${err.stack || ''}`;
    }
}

function simulateScriptExecution(lang, code, startTime) {
    const terminal = document.getElementById('terminalOutput');
    const statusBadge = document.getElementById('terminalStatusBadge');

    const durationSec = ((Math.random() * 0.05) + 0.01).toFixed(4);
    const memMb = (Math.random() * 15 + 20).toFixed(2);

    document.getElementById('terminalExecTime').innerText = `${durationSec}s`;
    document.getElementById('terminalExecMem').innerText = `${memMb} MB`;

    statusBadge.className = 'ide-status-pill pill-success';
    statusBadge.innerHTML = '<i class="fa-solid fa-circle-check me-1"></i> Successfully Executed';
    terminal.className = 'output-terminal';

    if (lang === 'python') {
        terminal.innerText = `=== REKAP NILAI WORKSHOP MULTIMEDIA ===\n- Ahmad: Skor 89.0 -> LULUS (Grade A)\n- Siti: Skor 87.0 -> LULUS (Grade A)\n- Budi: Skor 76.0 -> LULUS (Grade B)\n\n[Program Python 3 selesai dieksekusi]`;
    } else if (lang === 'php') {
        terminal.innerText = `=== DATA ANGGOTA MULTIMEDIA CLUB ===\nMember: Rifki | Divisi: Programming | Total Karya: 2\nArray\n(\n    [0] => Website Portofolio Sekolah\n    [1] => Aplikasi Presensi QR\n)\n\n[PHP 8.2 interpreter selesai dieksekusi]`;
    } else if (lang === 'cpp') {
        terminal.innerText = `=== PROGRAM PENJUMLAHAN ELEMEN VEKTOR ===\nNilai Tugas ke-1: 85\nNilai Tugas ke-2: 92\nNilai Tugas ke-3: 78\nNilai Tugas ke-4: 90\nNilai Tugas ke-5: 88\n-----------------------------------\nTotal Nilai: 433\nRata-rata  : 86.6\n\n[Process exited with return code 0]`;
    } else if (lang === 'java') {
        terminal.innerText = `=== MULTIMEDIA CLUB JAVA LAB ===\nDaftar Divisi Aktif:\n1. Divisi Fotografi\n2. Divisi Videografi\n3. Divisi Desain Grafis\n4. Divisi Programming\n\nTotal Anggota Terdaftar: 64 Siswa\n\n[Java Virtual Machine executed successfully]`;
    } else if (lang === 'sql') {
        terminal.innerText = `+----+----------------------+-------------------+------------+\n| ID | NAMA_LENGKAP         | NAMA_DIVISI       | TOTAL_POIN |\n+----+----------------------+-------------------+------------+\n|  1 | Rizki Maulana        | Programming & Web |        285 |\n|  2 | Aulia Rahmawati      | Fotografi         |        240 |\n|  3 | Dimas Arya Putra     | Videografi        |        215 |\n|  4 | Siti Nurhaliza       | Desain Grafis     |        190 |\n+----+----------------------+-------------------+------------+\n(4 rows returned in 0.0084 sec)`;
    }
}

function clearConsole() {
    document.getElementById('terminalOutput').innerText = '/* Console dibersihkan. */';
}

function loadTemplate(templateKey) {
    const tpl = CODE_TEMPLATES[templateKey];
    if (!tpl) return;

    if (tpl.lang === 'web') {
        document.getElementById('langSelect').value = 'web';
        changeLanguage('web');
        webCode.html = tpl.html;
        webCode.css  = tpl.css;
        webCode.js   = tpl.js;
        switchWebTab(currentWebTab);
        refreshPreview();
    } else {
        document.getElementById('langSelect').value = tpl.lang;
        changeLanguage(tpl.lang);
        singleCodeBuffers[tpl.lang] = tpl.code;
        editorTextarea.value = tpl.code;
        updateGutter();
        updateEditorStats();
    }
}

function formatCode() {
    // Quick auto-indent cleanup
    const raw = editorTextarea.value;
    const lines = raw.split('\n').map(l => l.trimEnd());
    editorTextarea.value = lines.join('\n');
    onEditorInput();
}

function copyCode() {
    navigator.clipboard.writeText(editorTextarea.value).then(() => {
        alert('Kode berhasil disalin ke clipboard!');
    });
}

function resetEditorCode() {
    if (confirm('Kembalikan kode ke template awal?')) {
        if (currentMode === 'quest') {
            applyQuestTemplate();
        } else {
            loadTemplate('web_starter');
        }
    }
}

// --- Quest / Challenge Mode Logic ---
function switchIdeMode(mode) {
    currentMode = mode;
    const banner = document.getElementById('questBannerBox');
    const tabSand = document.getElementById('tabSandboxModeBtn');
    const tabQues = document.getElementById('tabQuestModeBtn');

    if (mode === 'quest') {
        banner.classList.remove('d-none');
        tabQues.className = 'btn btn-sm btn-warning text-dark px-3 py-2 flex-fill font-heading fw-semibold text-nowrap d-flex align-items-center justify-content-center gap-2';
        tabSand.className = 'btn btn-sm btn-outline-secondary px-3 py-2 flex-fill font-heading fw-semibold border-0 text-body text-nowrap d-flex align-items-center justify-content-center gap-2';
        loadQuest(currentQuestIdx);
    } else {
        banner.classList.add('d-none');
        tabSand.className = 'btn btn-sm btn-primary px-3 py-2 flex-fill font-heading fw-semibold text-nowrap d-flex align-items-center justify-content-center gap-2';
        tabQues.className = 'btn btn-sm btn-outline-secondary px-3 py-2 flex-fill font-heading fw-semibold border-0 text-body text-nowrap d-flex align-items-center justify-content-center gap-2';
    }
}

function loadQuest(idx) {
    currentQuestIdx = idx;
    const q = QUESTS[idx];

    document.getElementById('questLevelBadge').innerText = `QUEST LEVEL ${q.level} / ${QUESTS.length}`;
    document.getElementById('questTitleText').innerText = q.title;
    document.getElementById('questDescText').innerHTML  = q.desc;
    document.getElementById('questCounter').innerText   = `${idx + 1} / ${QUESTS.length}`;
    document.getElementById('testCasesBadgeCount').innerText = `0/${q.tests.length}`;

    // Switch Language to JavaScript for quests
    document.getElementById('langSelect').value = 'javascript';
    changeLanguage('javascript');

    // Populate Guide Modal
    document.getElementById('guideModalTitle').innerText = `Panduan Step-by-Step: ${q.title}`;
    let stepsHtml = '';
    q.steps.forEach((st, i) => {
        stepsHtml += `
            <div class="quest-step-box">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-primary rounded-circle" style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center;">${i + 1}</span>
                    <strong class="text-white font-heading">Langkah ${i + 1}</strong>
                </div>
                <div class="text-secondary small ps-4">${st}</div>
            </div>
        `;
    });
    document.getElementById('guideModalBody').innerHTML = stepsHtml;

    // Apply Starter Code
    applyQuestTemplate();
}

function applyQuestTemplate() {
    const q = QUESTS[currentQuestIdx];
    editorTextarea.value = q.starterCode;
    singleCodeBuffers['javascript'] = q.starterCode;
    updateGutter();
    updateEditorStats();
}

function nextQuest() {
    if (currentQuestIdx < QUESTS.length - 1) {
        currentQuestIdx++;
    } else {
        currentQuestIdx = 0;
    }
    loadQuest(currentQuestIdx);
}

function prevQuest() {
    if (currentQuestIdx > 0) {
        currentQuestIdx--;
        loadQuest(currentQuestIdx);
    }
}

function runQuestTests() {
    const q = QUESTS[currentQuestIdx];
    const userCode = editorTextarea.value;
    switchOutputTab('tests');

    const list = document.getElementById('testCasesList');
    list.innerHTML = '';
    let passCount = 0;

    q.tests.forEach((test, i) => {
        let isPass = false;
        let actualVal = '';
        let expectedVal = JSON.stringify(test.run(userCode).expected);

        try {
            const res = test.run(userCode);
            actualVal = JSON.stringify(res.actual);
            isPass = (actualVal === expectedVal);
        } catch (e) {
            actualVal = 'Error: ' + e.message;
        }

        if (isPass) passCount++;

        const item = document.createElement('div');
        item.className = 'p-3 rounded-3 bg-dark border ' + (isPass ? 'border-success border-opacity-50' : 'border-danger border-opacity-50');
        item.innerHTML = `
            <div class="d-flex align-items-center justify-content-between mb-1">
                <strong class="text-white small font-heading">${test.name}</strong>
                <span class="badge ${isPass ? 'test-pill-pass' : 'test-pill-fail'} font-monospace style-tiny">
                    <i class="fa-solid ${isPass ? 'fa-check' : 'fa-xmark'} me-1"></i> ${isPass ? 'PASSED' : 'FAILED'}
                </span>
            </div>
            <div class="d-flex flex-wrap gap-3 style-tiny font-monospace mt-2 text-secondary">
                <div>Expected: <code class="text-info">${expectedVal}</code></div>
                <div>Actual Output: <code class="${isPass ? 'text-success' : 'text-danger'}">${actualVal}</code></div>
            </div>
        `;
        list.appendChild(item);
    });

    document.getElementById('testCasesBadgeCount').innerText = `${passCount}/${q.tests.length}`;
    document.getElementById('testsScoreBadge').innerText = `Skor: ${Math.round((passCount / q.tests.length) * 100)} Pts`;

    if (passCount === q.tests.length) {
        // Send AJAX score recording
        fetch('<?= base_url('mini-game/api/record-score') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                game_id: 'ide-simulator',
                level: q.level,
                score: 100,
                stars: 3
            })
        }).catch(err => console.log(err));

        const modal = new bootstrap.Modal(document.getElementById('questSuccessModal'));
        document.getElementById('questSuccessTitle').innerText = `Quest Level ${q.level} Berhasil!`;
        modal.show();
    }
}

// Initialize on page load
window.addEventListener('DOMContentLoaded', () => {
    switchWebTab('html');
    refreshPreview();
    updateGutter();
    updateEditorStats();
});
</script>
<?= $this->endSection() ?>
