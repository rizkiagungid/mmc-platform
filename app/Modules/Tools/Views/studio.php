<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<!-- External Libraries for Advanced PDF Rendering & Batch ZIP Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<style>
/* Tools Studio Custom Styles */
.studio-container {
    background: #0b0f19;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 20px;
    padding: 1.5rem;
    min-height: 650px;
    position: relative;
}

.dropzone-box {
    border: 2px dashed rgba(239, 68, 68, 0.35);
    background: radial-gradient(circle at center, rgba(239, 68, 68, 0.05) 0%, rgba(15, 23, 42, 0.6) 100%);
    border-radius: 18px;
    padding: 3.5rem 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.25s ease;
}

.dropzone-box:hover, .dropzone-box.drag-over {
    border-color: #ef4444;
    background: radial-gradient(circle at center, rgba(239, 68, 68, 0.12) 0%, rgba(15, 23, 42, 0.8) 100%);
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(239, 68, 68, 0.15);
}

.preview-viewport {
    background: #060a12;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    min-height: 420px;
    max-height: 680px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: auto;
    padding: 1.5rem;
}

.checkerboard-bg {
    background-image: linear-gradient(45deg, #161f30 25%, transparent 25%),
                      linear-gradient(-45deg, #161f30 25%, transparent 25%),
                      linear-gradient(45deg, transparent 75%, #161f30 75%),
                      linear-gradient(-45deg, transparent 75%, #161f30 75%);
    background-size: 20px 20px;
    background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
    background-color: #0b0f19;
}

.control-panel-card {
    background: #0f172a;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    padding: 1.5rem;
}

.preset-chip {
    padding: 0.4rem 0.85rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 500;
    color: #94a3b8;
    background: #1e293b;
    border: 1px solid rgba(255, 255, 255, 0.08);
    cursor: pointer;
    transition: all 0.15s ease;
}

.preset-chip:hover {
    color: #fff;
    border-color: rgba(255, 255, 255, 0.2);
}

.preset-chip.active {
    background: #dc2626;
    color: #fff;
    border-color: #dc2626;
    box-shadow: 0 4px 10px rgba(220, 38, 38, 0.3);
}

.stat-badge {
    background: #1e293b;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 10px;
    padding: 0.65rem 1rem;
}

/* Crop box handles */
.crop-overlay {
    position: absolute;
    box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.65);
    border: 2px dashed #ef4444;
    cursor: move;
}

.crop-handle {
    position: absolute;
    width: 12px;
    height: 12px;
    background: #ffffff;
    border: 2px solid #ef4444;
    border-radius: 2px;
}
.crop-handle.tl { top: -6px; left: -6px; cursor: nwse-resize; }
.crop-handle.tr { top: -6px; right: -6px; cursor: nesw-resize; }
.crop-handle.bl { bottom: -6px; left: -6px; cursor: nesw-resize; }
.crop-handle.br { bottom: -6px; right: -6px; cursor: nwse-resize; }

/* Custom Range Input */
.custom-range::-webkit-slider-thumb {
    background: #ef4444;
}

/* PDF Page Card */
.pdf-page-card {
    background: #0f172a;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 0.75rem;
    transition: transform 0.2s;
}
.pdf-page-card:hover {
    transform: translateY(-2px);
    border-color: rgba(239, 68, 68, 0.4);
}
</style>

<div class="mb-4">
    <!-- Top Breadcrumb & Tool Header -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="<?= base_url('tools-editor') ?>" class="btn btn-xs btn-outline-secondary px-2 py-1 text-secondary text-decoration-none">
                    <i class="fa-solid fa-arrow-left me-1"></i> Semua Tools
                </a>
                <span class="text-secondary small">/</span>
                <span class="text-white small fw-bold font-monospace"><?= esc($activeTool['name']) ?></span>
            </div>
            <h4 class="text-white font-heading fw-bold m-0 d-flex align-items-center gap-2">
                <i class="<?= esc($activeTool['icon']) ?>" style="color: <?= esc($activeTool['icon_color']) ?>;"></i>
                <?= esc($activeTool['title']) ?>
            </h4>
            <p class="text-secondary small m-0 mt-1"><?= esc($activeTool['description']) ?></p>
        </div>

        <!-- Quick Switcher Dropdown -->
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2 text-white" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-sliders text-danger"></i> Ganti Tool Lain
            </button>
            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow-lg" style="max-height: 380px; overflow-y: auto;">
                <?php foreach ($allTools as $t): ?>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 <?= ($t['id'] === $activeTool['id']) ? 'active bg-danger text-white' : '' ?>" href="<?= base_url('tools-editor/' . $t['id']) ?>">
                            <i class="<?= esc($t['icon']) ?>" style="width: 18px; color: <?= ($t['id'] === $activeTool['id']) ? '#fff' : esc($t['icon_color']) ?>;"></i>
                            <span><?= esc($t['name']) ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Main Studio Box -->
    <div class="studio-container">

        <!-- STEP 1: Upload / Dropzone (Visible when no file is loaded) -->
        <div id="uploadStage" class="dropzone-box" onclick="document.getElementById('fileInput').click()">
            <input type="file" id="fileInput" class="d-none" accept="<?= esc($activeTool['accept']) ?>" <?= ($activeTool['id'] === 'pdf-to-jpg') ? '' : 'accept="image/*"' ?>>
            <div class="d-inline-flex p-4 rounded-circle bg-danger bg-opacity-10 text-danger mb-3" style="font-size: 2.5rem;">
                <i class="<?= esc($activeTool['icon']) ?>"></i>
            </div>
            <h4 class="text-white font-heading fw-bold mb-2">Pilih File atau Seret & Lepas (Drag & Drop)</h4>
            <p class="text-secondary small mb-3">
                Format yang didukung: <span class="text-white font-monospace fw-semibold"><?= esc($activeTool['formats']) ?></span>.
            </p>
            <div class="d-inline-flex align-items-center gap-2">
                <button type="button" class="btn btn-red px-4 py-2 font-heading shadow">
                    <i class="fa-solid fa-cloud-arrow-up me-1.5"></i> Pilih File Dari Komputer
                </button>
            </div>
            <div class="mt-3 text-secondary" style="font-size: 0.75rem;">
                <i class="fa-solid fa-paste me-1"></i> Tip: Anda juga dapat menempelkan gambar langsung dari clipboard (<kbd class="text-white bg-dark px-1.5 py-0.5 rounded">Ctrl + V</kbd>).
            </div>
        </div>

        <!-- STEP 2: Interactive Workspace (Visible after file is selected) -->
        <div id="workspaceStage" class="d-none">
            <div class="row g-4">
                <!-- Left: Viewport Preview Canvas -->
                <div class="col-lg-8">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2 text-secondary small">
                            <span class="text-white fw-bold" id="fileNameDisplay">file.jpg</span>
                            <span class="badge bg-dark border border-secondary text-secondary font-monospace" id="fileMetaDisplay">0 KB • 0x0 px</span>
                        </div>
                        <div class="d-flex align-items-center gap-1.5">
                            <button type="button" class="btn btn-xs btn-outline-secondary text-secondary" id="btnZoomOut" title="Zoom Out">
                                <i class="fa-solid fa-magnifying-glass-minus"></i>
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-secondary text-secondary" id="btnZoomReset" title="Reset Zoom">
                                100%
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-secondary text-secondary" id="btnZoomIn" title="Zoom In">
                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-danger ms-2" id="btnChangeFile" title="Ganti File">
                                <i class="fa-solid fa-arrow-rotate-left me-1"></i> Ganti File
                            </button>
                        </div>
                    </div>

                    <!-- Canvas Viewport -->
                    <div class="preview-viewport <?= in_array($activeTool['id'], ['remove-bg', 'crop']) ? 'checkerboard-bg' : '' ?>" id="viewportContainer">
                        <!-- Standard Canvas for Image Editing -->
                        <div id="canvasWrapper" class="position-relative d-inline-block">
                            <canvas id="mainCanvas" style="max-width: 100%; height: auto; display: block; border-radius: 4px; box-shadow: 0 10px 25px rgba(0,0,0,0.5);"></canvas>
                            <!-- Crop Overlay Box (Only active in Crop mode) -->
                            <div id="cropBox" class="crop-overlay d-none">
                                <div class="crop-handle tl" data-handle="tl"></div>
                                <div class="crop-handle tr" data-handle="tr"></div>
                                <div class="crop-handle bl" data-handle="bl"></div>
                                <div class="crop-handle br" data-handle="br"></div>
                            </div>
                        </div>

                        <!-- PDF Multi-Page Grid (Only active for PDF to JPG) -->
                        <div id="pdfPagesContainer" class="w-100 d-none">
                            <div class="row g-3" id="pdfPagesList"></div>
                        </div>
                    </div>
                </div>

                <!-- Right: Tool Controls & Action Panel -->
                <div class="col-lg-4">
                    <div class="control-panel-card">
                        <h5 class="text-white font-heading fw-bold mb-3 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-sliders text-danger"></i> Opsi Pengaturan
                        </h5>

                        <!-- TOOL SPECIFIC CONTROLS -->
                        <div id="toolControlsContainer" class="mb-4">

                            <!-- 1. COMPRESS -->
                            <?php if ($activeTool['id'] === 'compress'): ?>
                                <div class="mb-3">
                                    <label class="form-label text-secondary small d-flex justify-content-between">
                                        <span>Tingkat Kualitas Kompresi</span>
                                        <span class="text-white font-monospace fw-bold" id="compressQualityLabel">75%</span>
                                    </label>
                                    <input type="range" class="form-range custom-range" id="compressQuality" min="10" max="95" value="75">
                                    <div class="d-flex justify-content-between text-secondary" style="font-size: 0.7rem;">
                                        <span>Ukuran Minimum (10%)</span>
                                        <span>Rekomendasi (75%)</span>
                                        <span>Maksimum (95%)</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-secondary small">Batasi Resolusi Lebar Maksimum</label>
                                    <select class="form-select form-select-sm bg-dark text-white border-secondary" id="compressMaxDim">
                                        <option value="original" selected>Tetap Asli (Tidak Mengubah Piksel)</option>
                                        <option value="1920">Full HD (Max 1920px)</option>
                                        <option value="1280">HD (Max 1280px)</option>
                                        <option value="800">Web Kompak (Max 800px)</option>
                                    </select>
                                </div>
                                <div class="stat-badge mb-3">
                                    <div class="row text-center g-2">
                                        <div class="col-6 border-end border-white border-opacity-10">
                                            <span class="text-secondary style-tiny d-block">Ukuran Asli</span>
                                            <span class="text-white fw-bold font-monospace" id="origSizeLabel">0 KB</span>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-secondary style-tiny d-block">Hasil Estimasi</span>
                                            <span class="text-success fw-bold font-monospace" id="compSizeLabel">0 KB</span>
                                        </div>
                                    </div>
                                </div>

                            <!-- 2. CROP -->
                            <?php elseif ($activeTool['id'] === 'crop'): ?>
                                <div class="mb-3">
                                    <label class="form-label text-secondary small mb-2">Preset Rasio Aspek</label>
                                    <div class="d-flex flex-wrap gap-1.5" id="cropAspectGroup">
                                        <button type="button" class="preset-chip active" data-ratio="free">Bebas</button>
                                        <button type="button" class="preset-chip" data-ratio="1:1">1:1 (Persegi/Avatar)</button>
                                        <button type="button" class="preset-chip" data-ratio="16:9">16:9 (YouTube)</button>
                                        <button type="button" class="preset-chip" data-ratio="9:16">9:16 (Story/Reels)</button>
                                        <button type="button" class="preset-chip" data-ratio="4:3">4:3 (Standar)</button>
                                    </div>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label text-secondary style-tiny">Lebar Seleksi (px)</label>
                                        <input type="number" class="form-control form-control-sm bg-dark text-white border-secondary" id="cropWInput" readonly>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-secondary style-tiny">Tinggi Seleksi (px)</label>
                                        <input type="number" class="form-control form-control-sm bg-dark text-white border-secondary" id="cropHInput" readonly>
                                    </div>
                                </div>

                            <!-- 3. RESIZE -->
                            <?php elseif ($activeTool['id'] === 'resize'): ?>
                                <div class="mb-3">
                                    <label class="form-label text-secondary small mb-2">Preset Persentase</label>
                                    <div class="d-flex flex-wrap gap-1.5">
                                        <button type="button" class="preset-chip" onclick="setResizePercent(25)">25%</button>
                                        <button type="button" class="preset-chip" onclick="setResizePercent(50)">50%</button>
                                        <button type="button" class="preset-chip" onclick="setResizePercent(75)">75%</button>
                                        <button type="button" class="preset-chip" onclick="setResizePercent(100)">100%</button>
                                        <button type="button" class="preset-chip" onclick="setResizePercent(150)">150%</button>
                                    </div>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label text-secondary style-tiny">Lebar (px)</label>
                                        <input type="number" class="form-control form-control-sm bg-dark text-white border-secondary" id="resizeWInput">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-secondary style-tiny">Tinggi (px)</label>
                                        <input type="number" class="form-control form-control-sm bg-dark text-white border-secondary" id="resizeHInput">
                                    </div>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="lockAspectCheckbox" checked>
                                    <label class="form-check-label text-secondary small" for="lockAspectCheckbox">Kunci Rasio Aspek (Proporsional)</label>
                                </div>

                            <!-- 4. CONVERT TO JPG -->
                            <?php elseif ($activeTool['id'] === 'convert-to-jpg'): ?>
                                <div class="mb-3">
                                    <label class="form-label text-secondary small">Warna Latar Transparan</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" class="form-control form-control-color bg-dark border-secondary p-1" id="convertJpgBgColor" value="#ffffff" title="Pilih warna pengganti transparan">
                                        <span class="text-secondary small">Putih (#ffffff) disarankan</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-secondary small d-flex justify-content-between">
                                        <span>Kualitas Output JPG</span>
                                        <span class="text-white font-monospace fw-bold" id="convertJpgQualityLabel">90%</span>
                                    </label>
                                    <input type="range" class="form-range custom-range" id="convertJpgQuality" min="40" max="100" value="90">
                                </div>

                            <!-- 5. CONVERT FROM JPG -->
                            <?php elseif ($activeTool['id'] === 'convert-from-jpg'): ?>
                                <div class="mb-3">
                                    <label class="form-label text-secondary small mb-2">Pilih Format Tujuan</label>
                                    <div class="d-flex flex-wrap gap-2" id="targetFormatGroup">
                                        <button type="button" class="preset-chip active" data-fmt="image/png">PNG (.png)</button>
                                        <button type="button" class="preset-chip" data-fmt="image/webp">WEBP (.webp)</button>
                                        <button type="button" class="preset-chip" data-fmt="image/gif">GIF (.gif)</button>
                                    </div>
                                </div>
                                <p class="text-secondary style-tiny">
                                    PNG mendukung transparansi pixel lossless. WEBP menghasilkan ukuran file hingga 40% lebih kecil dibanding JPEG biasa.
                                </p>

                            <!-- 6. ROTATE -->
                            <?php elseif ($activeTool['id'] === 'rotate'): ?>
                                <div class="mb-3">
                                    <label class="form-label text-secondary small mb-2">Putar 90 Derajat</label>
                                    <div class="d-flex gap-2 mb-3">
                                        <button type="button" class="btn btn-sm btn-outline-secondary flex-grow-1" onclick="rotateCanvas(-90)">
                                            <i class="fa-solid fa-rotate-left me-1"></i> -90° Kiri
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary flex-grow-1" onclick="rotateCanvas(90)">
                                            <i class="fa-solid fa-rotate-right me-1"></i> +90° Kanan
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-secondary small mb-2">Cermin (Flip)</label>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary flex-grow-1" onclick="flipCanvas('h')">
                                            <i class="fa-solid fa-arrows-left-right me-1"></i> Flip Horizontal
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary flex-grow-1" onclick="flipCanvas('v')">
                                            <i class="fa-solid fa-arrows-up-down me-1"></i> Flip Vertikal
                                        </button>
                                    </div>
                                </div>

                            <!-- 7. UPSCALE -->
                            <?php elseif ($activeTool['id'] === 'upscale'): ?>
                                <div class="mb-3">
                                    <label class="form-label text-secondary small mb-2">Faktor Perbesaran (Scale Factor)</label>
                                    <div class="d-flex flex-wrap gap-2" id="upscaleFactorGroup">
                                        <button type="button" class="preset-chip active" data-scale="2">2x (200% Super-Res)</button>
                                        <button type="button" class="preset-chip" data-scale="3">3x (300%)</button>
                                        <button type="button" class="preset-chip" data-scale="4">4x (400% Ultra-Res)</button>
                                    </div>
                                </div>
                                <div class="stat-badge mb-3">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span class="text-secondary">Resolusi Asli:</span>
                                        <span class="text-white font-monospace" id="upscaleOrigRes">-</span>
                                    </div>
                                    <div class="d-flex justify-content-between small">
                                        <span class="text-secondary">Resolusi Target:</span>
                                        <span class="text-danger fw-bold font-monospace" id="upscaleTargetRes">-</span>
                                    </div>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="upscaleSharpen" checked>
                                    <label class="form-check-label text-secondary small" for="upscaleSharpen">Auto-Sharpening Detail Filter</label>
                                </div>

                            <!-- 8. REMOVE BACKGROUND -->
                            <?php elseif ($activeTool['id'] === 'remove-bg'): ?>
                                <div class="alert alert-dark border-secondary p-2.5 small mb-3">
                                    <i class="fa-solid fa-wand-magic-sparkles text-warning me-1"></i>
                                    <strong>Tip:</strong> Klik pada area latar foto di canvas untuk memilih warna background yang ingin dihapus.
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-secondary small d-flex justify-content-between">
                                        <span>Toleransi Warna (Tolerance)</span>
                                        <span class="text-white font-monospace fw-bold" id="bgToleranceLabel">25%</span>
                                    </label>
                                    <input type="range" class="form-range custom-range" id="bgTolerance" min="1" max="80" value="25">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-secondary small d-flex justify-content-between">
                                        <span>Haluskan Tepi (Feather)</span>
                                        <span class="text-white font-monospace fw-bold" id="bgFeatherLabel">1 px</span>
                                    </label>
                                    <input type="range" class="form-range custom-range" id="bgFeather" min="0" max="5" value="1">
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary w-100 mb-3" onclick="sampleAutoBg()">
                                    <i class="fa-solid fa-eye-dropper me-1"></i> Deteksi Otomatis Latar Sudut
                                </button>

                            <!-- 9. PDF TO JPG -->
                            <?php elseif ($activeTool['id'] === 'pdf-to-jpg'): ?>
                                <div class="mb-3">
                                    <label class="form-label text-secondary small">Kualitas Gambar Rendering</label>
                                    <select class="form-select form-select-sm bg-dark text-white border-secondary" id="pdfDpiScale">
                                        <option value="1.5">Standar Web (150 DPI)</option>
                                        <option value="2.0" selected>Tajam High-Res (200 DPI - Rekomendasi)</option>
                                        <option value="3.0">Ultra Tajam (300 DPI Cetak)</option>
                                    </select>
                                </div>
                                <div class="stat-badge mb-3">
                                    <div class="d-flex justify-content-between small">
                                        <span class="text-secondary">Jumlah Halaman:</span>
                                        <span class="text-white fw-bold font-monospace" id="pdfTotalPagesLabel">0 Halaman</span>
                                    </div>
                                </div>

                            <!-- 10. WATERMARK -->
                            <?php elseif ($activeTool['id'] === 'watermark'): ?>
                                <div class="mb-3">
                                    <label class="form-label text-secondary small">Teks Watermark</label>
                                    <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary" id="wmText" value="MMC SMAN 1 Tamansari">
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label text-secondary style-tiny">Warna Teks</label>
                                        <input type="color" class="form-control form-control-color bg-dark border-secondary p-1 w-100" id="wmColor" value="#ffffff">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-secondary style-tiny">Ukuran Font</label>
                                        <input type="number" class="form-control form-control-sm bg-dark text-white border-secondary" id="wmSize" value="36" min="12" max="150">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-secondary small d-flex justify-content-between">
                                        <span>Transparansi (Opacity)</span>
                                        <span class="text-white font-monospace fw-bold" id="wmOpacityLabel">50%</span>
                                    </label>
                                    <input type="range" class="form-range custom-range" id="wmOpacity" min="10" max="100" value="50">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-secondary small">Posisi Penempatan</label>
                                    <select class="form-select form-select-sm bg-dark text-white border-secondary" id="wmPos">
                                        <option value="br" selected>Pojok Kanan Bawah</option>
                                        <option value="bl">Pojok Kiri Bawah</option>
                                        <option value="center">Tengah (Center)</option>
                                        <option value="repeat">Ulang Seluruh Gambar (Tile Pattern)</option>
                                    </select>
                                </div>

                            <!-- 11. MEME GENERATOR -->
                            <?php elseif ($activeTool['id'] === 'meme'): ?>
                                <div class="mb-3">
                                    <label class="form-label text-secondary small">Teks Bagian Atas</label>
                                    <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary text-uppercase" id="memeTopText" placeholder="KETIK TEKS ATAS...">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-secondary small">Teks Bagian Bawah</label>
                                    <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary text-uppercase" id="memeBottomText" placeholder="KETIK TEKS BAWAH...">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-secondary small d-flex justify-content-between">
                                        <span>Ukuran Font Meme</span>
                                        <span class="text-white font-monospace fw-bold" id="memeSizeLabel">48 px</span>
                                    </label>
                                    <input type="range" class="form-range custom-range" id="memeSize" min="20" max="100" value="48">
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- ACTION BUTTONS -->
                        <div class="d-grid gap-2">
                            <?php if ($activeTool['id'] === 'pdf-to-jpg'): ?>
                                <button type="button" class="btn btn-red py-2.5 font-heading fw-bold shadow" id="btnDownloadAllPdf">
                                    <i class="fa-solid fa-file-zipper me-1.5"></i> Unduh Semua Halaman (ZIP)
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn btn-red py-2.5 font-heading fw-bold shadow" id="btnDownloadResult">
                                    <i class="fa-solid fa-download me-1.5"></i> Proses & Unduh Gambar
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm text-secondary" id="btnCopyClipboard">
                                    <i class="fa-regular fa-copy me-1"></i> Salin Gambar ke Clipboard
                                </button>
                            <?php endif; ?>
                        </div>

                        <!-- Tool Security Footer Badge -->
                        <div class="mt-4 pt-3 border-top border-white border-opacity-10 text-center">
                            <span class="text-secondary style-tiny d-flex align-items-center justify-content-center gap-1">
                                <i class="fa-solid fa-lock text-success"></i> Diproses lokal di browser dengan HTML5 Engine
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Core Studio JavaScript -->
<script>
window.TOOL_TYPE = "<?= esc($activeTool['id']) ?>";

(function() {
    let sourceImage = null;
    let sourceFile = null;
    let sourceFileName = 'image';
    let currentScale = 1.0;

    // Canvas elements
    const mainCanvas = document.getElementById('mainCanvas');
    const ctx = mainCanvas ? mainCanvas.getContext('2d', { willReadFrequently: true }) : null;

    // Crop state
    let cropState = {
        ratio: 'free',
        x: 50,
        y: 50,
        w: 200,
        h: 200,
        isDragging: false,
        dragType: null, // 'move' or handle
        startX: 0,
        startY: 0
    };

    // Rotation & Flip state
    let rotationAngle = 0;
    let flipH = 1;
    let flipV = 1;

    // Elements
    const uploadStage = document.getElementById('uploadStage');
    const workspaceStage = document.getElementById('workspaceStage');
    const fileInput = document.getElementById('fileInput');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const fileMetaDisplay = document.getElementById('fileMetaDisplay');
    const btnDownloadResult = document.getElementById('btnDownloadResult');
    const btnCopyClipboard = document.getElementById('btnCopyClipboard');
    const btnChangeFile = document.getElementById('btnChangeFile');
    const cropBox = document.getElementById('cropBox');

    // PDF Pages storage
    let pdfDocGlobal = null;
    let renderedPdfCanvases = [];

    // Helper: Format Bytes
    function formatBytes(bytes, decimals = 1) {
        if (!+bytes) return '0 B';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
    }

    // DRAG AND DROP SETUP
    uploadStage.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadStage.classList.add('drag-over');
    });

    uploadStage.addEventListener('dragleave', () => {
        uploadStage.classList.remove('drag-over');
    });

    uploadStage.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadStage.classList.remove('drag-over');
        if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
            handleSelectedFile(e.dataTransfer.files[0]);
        }
    });

    fileInput.addEventListener('change', (e) => {
        if (e.target.files && e.target.files.length > 0) {
            handleSelectedFile(e.target.files[0]);
        }
    });

    // CLIPBOARD PASTE LISTENER (Ctrl + V)
    window.addEventListener('paste', (e) => {
        if (e.clipboardData && e.clipboardData.files.length > 0) {
            const item = e.clipboardData.files[0];
            if (item.type.startsWith('image/')) {
                handleSelectedFile(item);
            }
        }
    });

    btnChangeFile.addEventListener('click', () => {
        fileInput.value = '';
        uploadStage.classList.remove('d-none');
        workspaceStage.classList.add('d-none');
        sourceImage = null;
        sourceFile = null;
    });

    // FILE DISPATCHER
    function handleSelectedFile(file) {
        sourceFile = file;
        sourceFileName = file.name.replace(/\.[^/.]+$/, "");
        fileNameDisplay.textContent = file.name;

        if (window.TOOL_TYPE === 'pdf-to-jpg') {
            if (file.type !== 'application/pdf' && !file.name.endsWith('.pdf')) {
                alert('Mohon pilih berkas dokumen berformat PDF.');
                return;
            }
            loadPdfDocument(file);
        } else {
            if (!file.type.startsWith('image/')) {
                alert('Mohon pilih berkas gambar (JPG, PNG, WEBP, GIF, SVG).');
                return;
            }
            loadImageDocument(file);
        }
    }

    // 1. IMAGE LOADER
    function loadImageDocument(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                sourceImage = img;
                uploadStage.classList.add('d-none');
                workspaceStage.classList.remove('d-none');

                fileMetaDisplay.textContent = `${formatBytes(file.size)} • ${img.naturalWidth}x${img.naturalHeight} px`;

                // Setup tool-specific initial parameters
                initToolParameters(img, file);
                renderToolCanvas();
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    // TOOL INIT PARAMETERS
    function initToolParameters(img, file) {
        rotationAngle = 0;
        flipH = 1;
        flipV = 1;

        if (window.TOOL_TYPE === 'compress') {
            document.getElementById('origSizeLabel').textContent = formatBytes(file.size);
            updateCompressEstimate();
        } else if (window.TOOL_TYPE === 'crop') {
            initCropBox(img.naturalWidth, img.naturalHeight);
        } else if (window.TOOL_TYPE === 'resize') {
            const wInput = document.getElementById('resizeWInput');
            const hInput = document.getElementById('resizeHInput');
            wInput.value = img.naturalWidth;
            hInput.value = img.naturalHeight;
        } else if (window.TOOL_TYPE === 'upscale') {
            document.getElementById('upscaleOrigRes').textContent = `${img.naturalWidth} x ${img.naturalHeight} px`;
            updateUpscaleLabels();
        }
    }

    // MAIN CANVAS RENDERER
    function renderToolCanvas() {
        if (!sourceImage || !ctx) return;

        const w = sourceImage.naturalWidth;
        const h = sourceImage.naturalHeight;

        mainCanvas.width = w;
        mainCanvas.height = h;

        ctx.clearRect(0, 0, w, h);
        ctx.save();

        if (window.TOOL_TYPE === 'rotate') {
            // Translate to center for rotation
            ctx.translate(w / 2, h / 2);
            ctx.rotate((rotationAngle * Math.PI) / 180);
            ctx.scale(flipH, flipV);
            ctx.drawImage(sourceImage, -w / 2, -h / 2);
        } else if (window.TOOL_TYPE === 'convert-to-jpg') {
            const bgColor = document.getElementById('convertJpgBgColor').value || '#ffffff';
            ctx.fillStyle = bgColor;
            ctx.fillRect(0, 0, w, h);
            ctx.drawImage(sourceImage, 0, 0);
        } else if (window.TOOL_TYPE === 'watermark') {
            ctx.drawImage(sourceImage, 0, 0);
            renderWatermarkOverlay();
        } else if (window.TOOL_TYPE === 'meme') {
            ctx.drawImage(sourceImage, 0, 0);
            renderMemeOverlay();
        } else {
            ctx.drawImage(sourceImage, 0, 0);
        }

        ctx.restore();

        // Update live size or crop handles if needed
        if (window.TOOL_TYPE === 'compress') {
            updateCompressEstimate();
        } else if (window.TOOL_TYPE === 'crop') {
            updateCropOverlayPosition();
        }
    }

    // ==========================================
    // TOOL 1: COMPRESS IMAGE LOGIC
    // ==========================================
    const compressQuality = document.getElementById('compressQuality');
    const compressQualityLabel = document.getElementById('compressQualityLabel');
    const compressMaxDim = document.getElementById('compressMaxDim');

    if (compressQuality) {
        compressQuality.addEventListener('input', function() {
            compressQualityLabel.textContent = this.value + '%';
            updateCompressEstimate();
        });
    }
    if (compressMaxDim) {
        compressMaxDim.addEventListener('change', updateCompressEstimate);
    }

    function updateCompressEstimate() {
        if (!sourceImage || !mainCanvas) return;
        const quality = (compressQuality ? parseInt(compressQuality.value) : 75) / 100;
        const mime = (sourceFile && sourceFile.type === 'image/png') ? 'image/jpeg' : (sourceFile ? sourceFile.type : 'image/jpeg');

        mainCanvas.toBlob(blob => {
            if (blob) {
                const compLabel = document.getElementById('compSizeLabel');
                if (compLabel) {
                    const ratio = Math.round(((sourceFile.size - blob.size) / sourceFile.size) * 100);
                    const tag = ratio > 0 ? `(-${ratio}%)` : '';
                    compLabel.textContent = `${formatBytes(blob.size)} ${tag}`;
                }
            }
        }, mime, quality);
    }

    // ==========================================
    // TOOL 2: CROP IMAGE LOGIC
    // ==========================================
    function initCropBox(imgW, imgH) {
        cropBox.classList.remove('d-none');
        cropState.w = Math.round(imgW * 0.7);
        cropState.h = Math.round(imgH * 0.7);
        cropState.x = Math.round((imgW - cropState.w) / 2);
        cropState.y = Math.round((imgH - cropState.h) / 2);
        updateCropInputs();
        updateCropOverlayPosition();
    }

    function updateCropInputs() {
        const wIn = document.getElementById('cropWInput');
        const hIn = document.getElementById('cropHInput');
        if (wIn) wIn.value = Math.round(cropState.w);
        if (hIn) hIn.value = Math.round(cropState.h);
    }

    function updateCropOverlayPosition() {
        if (!mainCanvas || cropBox.classList.contains('d-none')) return;
        const rect = mainCanvas.getBoundingClientRect();
        const scaleX = rect.width / mainCanvas.width;
        const scaleY = rect.height / mainCanvas.height;

        cropBox.style.left = (cropState.x * scaleX) + 'px';
        cropBox.style.top = (cropState.y * scaleY) + 'px';
        cropBox.style.width = (cropState.w * scaleX) + 'px';
        cropBox.style.height = (cropState.h * scaleY) + 'px';
    }

    // Dragging crop handles and box
    let activeHandle = null;
    cropBox?.addEventListener('mousedown', function(e) {
        e.preventDefault();
        if (e.target.dataset.handle) {
            activeHandle = e.target.dataset.handle;
            cropState.dragType = 'resize';
        } else {
            cropState.dragType = 'move';
        }
        cropState.isDragging = true;
        cropState.startX = e.clientX;
        cropState.startY = e.clientY;
    });

    window.addEventListener('mousemove', function(e) {
        if (!cropState.isDragging || !mainCanvas) return;
        const rect = mainCanvas.getBoundingClientRect();
        const scaleX = mainCanvas.width / rect.width;
        const scaleY = mainCanvas.height / rect.height;

        const dx = (e.clientX - cropState.startX) * scaleX;
        const dy = (e.clientY - cropState.startY) * scaleY;
        cropState.startX = e.clientX;
        cropState.startY = e.clientY;

        if (cropState.dragType === 'move') {
            cropState.x = Math.max(0, Math.min(mainCanvas.width - cropState.w, cropState.x + dx));
            cropState.y = Math.max(0, Math.min(mainCanvas.height - cropState.h, cropState.y + dy));
        } else if (cropState.dragType === 'resize') {
            if (activeHandle === 'br') {
                cropState.w = Math.max(30, Math.min(mainCanvas.width - cropState.x, cropState.w + dx));
                if (cropState.ratio !== 'free') {
                    const r = getCropRatioValue();
                    cropState.h = cropState.w / r;
                } else {
                    cropState.h = Math.max(30, Math.min(mainCanvas.height - cropState.y, cropState.h + dy));
                }
            }
        }

        updateCropInputs();
        updateCropOverlayPosition();
    });

    window.addEventListener('mouseup', function() {
        cropState.isDragging = false;
        activeHandle = null;
    });

    const cropAspectChips = document.querySelectorAll('#cropAspectGroup .preset-chip');
    cropAspectChips.forEach(chip => {
        chip.addEventListener('click', function() {
            cropAspectChips.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            cropState.ratio = this.dataset.ratio;
            applyCropAspectRatio();
        });
    });

    function getCropRatioValue() {
        if (cropState.ratio === '1:1') return 1;
        if (cropState.ratio === '16:9') return 16 / 9;
        if (cropState.ratio === '9:16') return 9 / 16;
        if (cropState.ratio === '4:3') return 4 / 3;
        return 1;
    }

    function applyCropAspectRatio() {
        if (cropState.ratio === 'free') return;
        const r = getCropRatioValue();
        cropState.h = cropState.w / r;
        if (cropState.y + cropState.h > mainCanvas.height) {
            cropState.h = mainCanvas.height - cropState.y;
            cropState.w = cropState.h * r;
        }
        updateCropInputs();
        updateCropOverlayPosition();
    }

    // ==========================================
    // TOOL 3: RESIZE IMAGE LOGIC
    // ==========================================
    const resizeWInput = document.getElementById('resizeWInput');
    const resizeHInput = document.getElementById('resizeHInput');
    const lockAspectCheckbox = document.getElementById('lockAspectCheckbox');

    if (resizeWInput && resizeHInput) {
        resizeWInput.addEventListener('input', function() {
            if (lockAspectCheckbox.checked && sourceImage) {
                const ratio = sourceImage.naturalHeight / sourceImage.naturalWidth;
                resizeHInput.value = Math.round(this.value * ratio);
            }
        });

        resizeHInput.addEventListener('input', function() {
            if (lockAspectCheckbox.checked && sourceImage) {
                const ratio = sourceImage.naturalWidth / sourceImage.naturalHeight;
                resizeWInput.value = Math.round(this.value * ratio);
            }
        });
    }

    window.setResizePercent = function(pct) {
        if (!sourceImage) return;
        const scale = pct / 100;
        resizeWInput.value = Math.round(sourceImage.naturalWidth * scale);
        resizeHInput.value = Math.round(sourceImage.naturalHeight * scale);
    };

    // ==========================================
    // TOOL 4: CONVERT TO JPG
    // ==========================================
    const convertJpgBgColor = document.getElementById('convertJpgBgColor');
    const convertJpgQuality = document.getElementById('convertJpgQuality');
    const convertJpgQualityLabel = document.getElementById('convertJpgQualityLabel');

    if (convertJpgBgColor) {
        convertJpgBgColor.addEventListener('input', renderToolCanvas);
    }
    if (convertJpgQuality) {
        convertJpgQuality.addEventListener('input', function() {
            convertJpgQualityLabel.textContent = this.value + '%';
        });
    }

    // ==========================================
    // TOOL 5: CONVERT FROM JPG
    // ==========================================
    let targetFormatMime = 'image/png';
    const targetFormatChips = document.querySelectorAll('#targetFormatGroup .preset-chip');
    targetFormatChips.forEach(chip => {
        chip.addEventListener('click', function() {
            targetFormatChips.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            targetFormatMime = this.dataset.fmt;
        });
    });

    // ==========================================
    // TOOL 6: ROTATE LOGIC
    // ==========================================
    window.rotateCanvas = function(deg) {
        rotationAngle = (rotationAngle + deg) % 360;
        renderToolCanvas();
    };

    window.flipCanvas = function(dir) {
        if (dir === 'h') flipH *= -1;
        if (dir === 'v') flipV *= -1;
        renderToolCanvas();
    };

    // ==========================================
    // TOOL 7: UPSCALE LOGIC
    // ==========================================
    let upscaleFactor = 2;
    const upscaleFactorChips = document.querySelectorAll('#upscaleFactorGroup .preset-chip');
    upscaleFactorChips.forEach(chip => {
        chip.addEventListener('click', function() {
            upscaleFactorChips.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            upscaleFactor = parseInt(this.dataset.scale);
            updateUpscaleLabels();
        });
    });

    function updateUpscaleLabels() {
        if (!sourceImage) return;
        const targetRes = document.getElementById('upscaleTargetRes');
        if (targetRes) {
            targetRes.textContent = `${sourceImage.naturalWidth * upscaleFactor} x ${sourceImage.naturalHeight * upscaleFactor} px`;
        }
    }

    // ==========================================
    // TOOL 8: REMOVE BACKGROUND LOGIC
    // ==========================================
    const bgTolerance = document.getElementById('bgTolerance');
    const bgToleranceLabel = document.getElementById('bgToleranceLabel');
    const bgFeather = document.getElementById('bgFeather');
    const bgFeatherLabel = document.getElementById('bgFeatherLabel');

    let sampledColor = { r: 255, g: 255, b: 255 };

    if (bgTolerance) {
        bgTolerance.addEventListener('input', function() {
            bgToleranceLabel.textContent = this.value + '%';
            applyBgRemoval();
        });
    }
    if (bgFeather) {
        bgFeather.addEventListener('input', function() {
            bgFeatherLabel.textContent = this.value + ' px';
            applyBgRemoval();
        });
    }

    // Eyedropper click on canvas to sample color
    if (mainCanvas && window.TOOL_TYPE === 'remove-bg') {
        mainCanvas.addEventListener('click', function(e) {
            const rect = mainCanvas.getBoundingClientRect();
            const x = Math.floor((e.clientX - rect.left) * (mainCanvas.width / rect.width));
            const y = Math.floor((e.clientY - rect.top) * (mainCanvas.height / rect.height));

            // Sample from source image
            const tempC = document.createElement('canvas');
            tempC.width = sourceImage.naturalWidth;
            tempC.height = sourceImage.naturalHeight;
            const tctx = tempC.getContext('2d');
            tctx.drawImage(sourceImage, 0, 0);
            const pixel = tctx.getImageData(x, y, 1, 1).data;
            sampledColor = { r: pixel[0], g: pixel[1], b: pixel[2] };

            applyBgRemoval();
        });
    }

    window.sampleAutoBg = function() {
        if (!sourceImage) return;
        const tempC = document.createElement('canvas');
        tempC.width = sourceImage.naturalWidth;
        tempC.height = sourceImage.naturalHeight;
        const tctx = tempC.getContext('2d');
        tctx.drawImage(sourceImage, 0, 0);
        // sample corner 5x5
        const pixel = tctx.getImageData(5, 5, 1, 1).data;
        sampledColor = { r: pixel[0], g: pixel[1], b: pixel[2] };
        applyBgRemoval();
    };

    function applyBgRemoval() {
        if (!sourceImage || !ctx) return;
        const w = sourceImage.naturalWidth;
        const h = sourceImage.naturalHeight;

        mainCanvas.width = w;
        mainCanvas.height = h;
        ctx.clearRect(0, 0, w, h);
        ctx.drawImage(sourceImage, 0, 0);

        const imgData = ctx.getImageData(0, 0, w, h);
        const data = imgData.data;
        const tol = (bgTolerance ? parseInt(bgTolerance.value) : 25) * 2.55;

        for (let i = 0; i < data.length; i += 4) {
            const r = data[i];
            const g = data[i + 1];
            const b = data[i + 2];

            const diff = Math.sqrt(
                Math.pow(r - sampledColor.r, 2) +
                Math.pow(g - sampledColor.g, 2) +
                Math.pow(b - sampledColor.b, 2)
            );

            if (diff <= tol) {
                data[i + 3] = 0; // Transparent
            }
        }
        ctx.putImageData(imgData, 0, 0);
    }

    // ==========================================
    // TOOL 9: PDF TO JPG RENDERING
    // ==========================================
    function loadPdfDocument(file) {
        const fileReader = new FileReader();
        fileReader.onload = function() {
            const typedarray = new Uint8Array(this.result);
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

            pdfjsLib.getDocument(typedarray).promise.then(function(pdfDoc) {
                pdfDocGlobal = pdfDoc;
                uploadStage.classList.add('d-none');
                workspaceStage.classList.remove('d-none');

                document.getElementById('canvasWrapper').classList.add('d-none');
                document.getElementById('pdfPagesContainer').classList.remove('d-none');

                document.getElementById('pdfTotalPagesLabel').textContent = `${pdfDoc.numPages} Halaman`;
                fileMetaDisplay.textContent = `${formatBytes(file.size)} • ${pdfDoc.numPages} Halaman PDF`;

                renderPdfPages(pdfDoc);
            }).catch(err => {
                alert('Gagal membaca PDF: ' + err.message);
            });
        };
        fileReader.readAsArrayBuffer(file);
    }

    function renderPdfPages(pdfDoc) {
        const list = document.getElementById('pdfPagesList');
        list.innerHTML = '';
        renderedPdfCanvases = [];

        const scale = parseFloat(document.getElementById('pdfDpiScale')?.value || 2.0);

        for (let pageNum = 1; pageNum <= pdfDoc.numPages; pageNum++) {
            (function(num) {
                pdfDoc.getPage(num).then(function(page) {
                    const viewport = page.getViewport({ scale: scale });

                    const col = document.createElement('div');
                    col.className = 'col-12 col-md-6 col-xl-4';

                    const card = document.createElement('div');
                    card.className = 'pdf-page-card';

                    const canvas = document.createElement('canvas');
                    canvas.width = viewport.width;
                    canvas.height = viewport.height;
                    canvas.style.width = '100%';
                    canvas.style.height = 'auto';
                    canvas.style.display = 'block';
                    canvas.style.borderRadius = '8px';

                    const pCtx = canvas.getContext('2d');
                    page.render({ canvasContext: pCtx, viewport: viewport }).promise.then(function() {
                        renderedPdfCanvases.push({ pageNum: num, canvas: canvas });
                    });

                    const footer = document.createElement('div');
                    footer.className = 'd-flex align-items-center justify-content-between mt-2 pt-2 border-top border-white border-opacity-10';
                    footer.innerHTML = `
                        <span class="text-secondary small font-monospace">Halaman ${num}</span>
                        <button type="button" class="btn btn-xs btn-outline-danger" onclick="downloadSinglePdfPage(${num})">
                            <i class="fa-solid fa-download me-1"></i> Unduh JPG
                        </button>
                    `;

                    card.appendChild(canvas);
                    card.appendChild(footer);
                    col.appendChild(card);
                    list.appendChild(col);
                });
            })(pageNum);
        }
    }

    window.downloadSinglePdfPage = function(pageNum) {
        const item = renderedPdfCanvases.find(c => c.pageNum === pageNum);
        if (!item) return;
        const link = document.createElement('a');
        link.download = `${sourceFileName}-page-${pageNum}.jpg`;
        link.href = item.canvas.toDataURL('image/jpeg', 0.95);
        link.click();
    };

    const btnDownloadAllPdf = document.getElementById('btnDownloadAllPdf');
    if (btnDownloadAllPdf) {
        btnDownloadAllPdf.addEventListener('click', function() {
            if (renderedPdfCanvases.length === 0) return;
            const zip = new JSZip();

            btnDownloadAllPdf.disabled = true;
            btnDownloadAllPdf.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1.5"></i> Mengompres ZIP...';

            renderedPdfCanvases.forEach(item => {
                const dataUrl = item.canvas.toDataURL('image/jpeg', 0.95);
                const base64Data = dataUrl.replace(/^data:image\/jpeg;base64,/, "");
                zip.file(`${sourceFileName}-page-${item.pageNum}.jpg`, base64Data, { base64: true });
            });

            zip.generateAsync({ type: "blob" }).then(function(content) {
                const link = document.createElement('a');
                link.download = `${sourceFileName}-pages.zip`;
                link.href = URL.createObjectURL(content);
                link.click();

                btnDownloadAllPdf.disabled = false;
                btnDownloadAllPdf.innerHTML = '<i class="fa-solid fa-file-zipper me-1.5"></i> Unduh Semua Halaman (ZIP)';
            });
        });
    }

    // ==========================================
    // TOOL 10: WATERMARK OVERLAY
    // ==========================================
    function renderWatermarkOverlay() {
        const text = document.getElementById('wmText')?.value || '';
        if (!text) return;

        const color = document.getElementById('wmColor')?.value || '#ffffff';
        const size = parseInt(document.getElementById('wmSize')?.value || 36);
        const opacity = (parseInt(document.getElementById('wmOpacity')?.value || 50)) / 100;
        const pos = document.getElementById('wmPos')?.value || 'br';

        ctx.save();
        ctx.globalAlpha = opacity;
        ctx.fillStyle = color;
        ctx.font = `bold ${size}px 'Outfit', Inter, sans-serif`;

        if (pos === 'br') {
            ctx.textAlign = 'right';
            ctx.textBaseline = 'bottom';
            ctx.fillText(text, mainCanvas.width - 20, mainCanvas.height - 20);
        } else if (pos === 'bl') {
            ctx.textAlign = 'left';
            ctx.textBaseline = 'bottom';
            ctx.fillText(text, 20, mainCanvas.height - 20);
        } else if (pos === 'center') {
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(text, mainCanvas.width / 2, mainCanvas.height / 2);
        } else if (pos === 'repeat') {
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            const stepX = size * 8;
            const stepY = size * 4;
            for (let x = stepX / 2; x < mainCanvas.width; x += stepX) {
                for (let y = stepY / 2; y < mainCanvas.height; y += stepY) {
                    ctx.save();
                    ctx.translate(x, y);
                    ctx.rotate(-Math.PI / 6);
                    ctx.fillText(text, 0, 0);
                    ctx.restore();
                }
            }
        }
        ctx.restore();
    }

    ['wmText', 'wmColor', 'wmSize', 'wmOpacity', 'wmPos'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', function() {
                if (id === 'wmOpacity') {
                    document.getElementById('wmOpacityLabel').textContent = this.value + '%';
                }
                renderToolCanvas();
            });
        }
    });

    // ==========================================
    // TOOL 11: MEME OVERLAY
    // ==========================================
    function renderMemeOverlay() {
        const topText = (document.getElementById('memeTopText')?.value || '').toUpperCase();
        const bottomText = (document.getElementById('memeBottomText')?.value || '').toUpperCase();
        const size = parseInt(document.getElementById('memeSize')?.value || 48);

        ctx.save();
        ctx.font = `900 ${size}px Impact, sans-serif`;
        ctx.textAlign = 'center';
        ctx.fillStyle = '#ffffff';
        ctx.strokeStyle = '#000000';
        ctx.lineWidth = Math.max(3, size / 10);
        ctx.lineJoin = 'round';

        if (topText) {
            ctx.textBaseline = 'top';
            ctx.strokeText(topText, mainCanvas.width / 2, 20);
            ctx.fillText(topText, mainCanvas.width / 2, 20);
        }

        if (bottomText) {
            ctx.textBaseline = 'bottom';
            ctx.strokeText(bottomText, mainCanvas.width / 2, mainCanvas.height - 20);
            ctx.fillText(bottomText, mainCanvas.width / 2, mainCanvas.height - 20);
        }
        ctx.restore();
    }

    ['memeTopText', 'memeBottomText', 'memeSize'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', function() {
                if (id === 'memeSize') {
                    document.getElementById('memeSizeLabel').textContent = this.value + ' px';
                }
                renderToolCanvas();
            });
        }
    });

    // ==========================================
    // EXPORT & DOWNLOAD DISPATCHER
    // ==========================================
    if (btnDownloadResult) {
        btnDownloadResult.addEventListener('click', function() {
            if (!sourceImage && window.TOOL_TYPE !== 'pdf-to-jpg') return;

            let exportCanvas = mainCanvas;
            let exportMime = 'image/jpeg';
            let exportExt = 'jpg';
            let exportQuality = 0.92;
            let filePrefix = window.TOOL_TYPE;

            // 1. COMPRESS
            if (window.TOOL_TYPE === 'compress') {
                exportQuality = (compressQuality ? parseInt(compressQuality.value) : 75) / 100;
                exportMime = (sourceFile && sourceFile.type === 'image/png') ? 'image/jpeg' : (sourceFile.type || 'image/jpeg');
                exportExt = exportMime === 'image/png' ? 'png' : 'jpg';

                const maxDim = compressMaxDim ? compressMaxDim.value : 'original';
                if (maxDim !== 'original') {
                    const max = parseInt(maxDim);
                    const scale = Math.min(max / sourceImage.naturalWidth, max / sourceImage.naturalHeight);
                    if (scale < 1) {
                        exportCanvas = document.createElement('canvas');
                        exportCanvas.width = Math.round(sourceImage.naturalWidth * scale);
                        exportCanvas.height = Math.round(sourceImage.naturalHeight * scale);
                        const c = exportCanvas.getContext('2d');
                        c.drawImage(mainCanvas, 0, 0, exportCanvas.width, exportCanvas.height);
                    }
                }
            }

            // 2. CROP
            else if (window.TOOL_TYPE === 'crop') {
                exportCanvas = document.createElement('canvas');
                exportCanvas.width = Math.round(cropState.w);
                exportCanvas.height = Math.round(cropState.h);
                const c = exportCanvas.getContext('2d');
                c.drawImage(
                    mainCanvas,
                    cropState.x, cropState.y, cropState.w, cropState.h,
                    0, 0, exportCanvas.width, exportCanvas.height
                );
                exportMime = sourceFile.type || 'image/png';
                exportExt = exportMime === 'image/jpeg' ? 'jpg' : 'png';
            }

            // 3. RESIZE
            else if (window.TOOL_TYPE === 'resize') {
                const targetW = parseInt(resizeWInput.value) || sourceImage.naturalWidth;
                const targetH = parseInt(resizeHInput.value) || sourceImage.naturalHeight;
                exportCanvas = document.createElement('canvas');
                exportCanvas.width = targetW;
                exportCanvas.height = targetH;
                const c = exportCanvas.getContext('2d');
                c.imageSmoothingEnabled = true;
                c.imageSmoothingQuality = 'high';
                c.drawImage(mainCanvas, 0, 0, targetW, targetH);
                exportMime = sourceFile.type || 'image/jpeg';
                exportExt = exportMime === 'image/png' ? 'png' : 'jpg';
            }

            // 4. CONVERT TO JPG
            else if (window.TOOL_TYPE === 'convert-to-jpg') {
                exportMime = 'image/jpeg';
                exportExt = 'jpg';
                exportQuality = (parseInt(convertJpgQuality.value) || 90) / 100;
            }

            // 5. CONVERT FROM JPG
            else if (window.TOOL_TYPE === 'convert-from-jpg') {
                exportMime = targetFormatMime;
                exportExt = targetFormatMime === 'image/png' ? 'png' : (targetFormatMime === 'image/webp' ? 'webp' : 'gif');
            }

            // 7. UPSCALE
            else if (window.TOOL_TYPE === 'upscale') {
                const scale = upscaleFactor;
                exportCanvas = document.createElement('canvas');
                exportCanvas.width = sourceImage.naturalWidth * scale;
                exportCanvas.height = sourceImage.naturalHeight * scale;
                const c = exportCanvas.getContext('2d');
                c.imageSmoothingEnabled = true;
                c.imageSmoothingQuality = 'high';
                c.drawImage(mainCanvas, 0, 0, exportCanvas.width, exportCanvas.height);

                // Unsharp masking filter convolution if enabled
                if (document.getElementById('upscaleSharpen')?.checked) {
                    try {
                        const imgData = c.getImageData(0, 0, exportCanvas.width, exportCanvas.height);
                        // Lightweight edge enhance
                        const d = imgData.data;
                        const w = exportCanvas.width;
                        for (let i = 0; i < d.length - 4; i += 4) {
                            d[i] = Math.min(255, Math.max(0, d[i] + (d[i] - d[i + 4]) * 0.3));
                            d[i+1] = Math.min(255, Math.max(0, d[i+1] + (d[i+1] - d[i+5]) * 0.3));
                            d[i+2] = Math.min(255, Math.max(0, d[i+2] + (d[i+2] - d[i+6]) * 0.3));
                        }
                        c.putImageData(imgData, 0, 0);
                    } catch(e) {}
                }
                exportMime = 'image/png';
                exportExt = 'png';
            }

            // 8. REMOVE BACKGROUND
            else if (window.TOOL_TYPE === 'remove-bg') {
                exportMime = 'image/png';
                exportExt = 'png';
            }

            // Download Trigger
            const link = document.createElement('a');
            link.download = `${sourceFileName}-${filePrefix}.${exportExt}`;
            link.href = exportCanvas.toDataURL(exportMime, exportQuality);
            link.click();
        });
    }

    // COPY TO CLIPBOARD
    if (btnCopyClipboard) {
        btnCopyClipboard.addEventListener('click', function() {
            if (!mainCanvas) return;
            mainCanvas.toBlob(blob => {
                if (blob) {
                    try {
                        const item = new ClipboardItem({ 'image/png': blob });
                        navigator.clipboard.write([item]).then(() => {
                            btnCopyClipboard.innerHTML = '<i class="fa-solid fa-check text-success me-1"></i> Tersalin!';
                            setTimeout(() => {
                                btnCopyClipboard.innerHTML = '<i class="fa-regular fa-copy me-1"></i> Salin Gambar ke Clipboard';
                            }, 2000);
                        });
                    } catch(err) {
                        alert('Browser tidak mendukung penyalinan clipboard gambar langsung.');
                    }
                }
            }, 'image/png');
        });
    }

    // ZOOM CONTROLS
    document.getElementById('btnZoomIn')?.addEventListener('click', () => {
        currentScale = Math.min(3.0, currentScale + 0.2);
        applyCanvasScale();
    });
    document.getElementById('btnZoomOut')?.addEventListener('click', () => {
        currentScale = Math.max(0.3, currentScale - 0.2);
        applyCanvasScale();
    });
    document.getElementById('btnZoomReset')?.addEventListener('click', () => {
        currentScale = 1.0;
        applyCanvasScale();
    });

    function applyCanvasScale() {
        if (!mainCanvas) return;
        mainCanvas.style.transform = `scale(${currentScale})`;
        mainCanvas.style.transformOrigin = 'center center';
        if (window.TOOL_TYPE === 'crop') {
            updateCropOverlayPosition();
        }
    }

})();
</script>

<?= $this->endSection() ?>
