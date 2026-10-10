<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<style>
/* Tools Editor Hub Custom Styles */
.tools-hero {
    background: radial-gradient(120% 120% at 50% 10%, rgba(220, 38, 38, 0.15) 0%, rgba(15, 23, 42, 0) 80%), #0b0f19;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 20px;
    padding: 2.25rem 2rem;
    position: relative;
    overflow: hidden;
}

.tools-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 380px;
    height: 380px;
    background: radial-gradient(circle, rgba(239, 68, 68, 0.12), transparent 70%);
    pointer-events: none;
}

.tool-card {
    background: #0f172a;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    display: flex;
    flex-direction: column;
    height: 100%;
    text-decoration: none !important;
    position: relative;
    overflow: hidden;
}

.tool-card:hover {
    transform: translateY(-4px);
    border-color: rgba(239, 68, 68, 0.4);
    box-shadow: 0 16px 32px -8px rgba(0, 0, 0, 0.5), 0 0 24px rgba(239, 68, 68, 0.15);
}

.tool-icon-box {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    transition: transform 0.25s ease;
}

.tool-card:hover .tool-icon-box {
    transform: scale(1.1);
}

.filter-pill {
    padding: 0.5rem 1.15rem;
    border-radius: 9999px;
    font-size: 0.85rem;
    font-weight: 500;
    color: #94a3b8;
    background: #111827;
    border: 1px solid rgba(255, 255, 255, 0.08);
    cursor: pointer;
    transition: all 0.2s ease;
    user-select: none;
}

.filter-pill:hover {
    color: #f8fafc;
    border-color: rgba(255, 255, 255, 0.2);
    background: #1f2937;
}

.filter-pill.active {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: #ffffff;
    border-color: #dc2626;
    box-shadow: 0 4px 14px rgba(220, 38, 38, 0.35);
}

.tool-badge-pill {
    font-size: 0.68rem;
    font-weight: 700;
    padding: 0.25rem 0.55rem;
    border-radius: 6px;
    letter-spacing: 0.04em;
    background: rgba(220, 38, 38, 0.15);
    color: #f87171 !important;
    border: 1px solid rgba(220, 38, 38, 0.35);
    display: inline-flex;
    align-items: center;
    line-height: 1;
}

.search-tools-input {
    background: #0f172a;
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #fff;
    border-radius: 12px;
    padding: 0.65rem 1rem 0.65rem 2.75rem;
    font-size: 0.9rem;
    transition: border-color 0.2s ease;
}

.search-tools-input:focus {
    background: #0f172a;
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.2);
    color: #fff;
}
</style>

<div class="mb-4">
    <!-- Hero Banner -->
    <div class="tools-hero mb-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <div class="d-inline-flex align-items-center gap-2 px-2.5 py-1 rounded-pill bg-danger bg-opacity-20 border border-danger border-opacity-30 text-danger text-uppercase fw-bold mb-3" style="font-size: 0.72rem; letter-spacing: 0.08em;">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Multimedia Creative Studio Suite
                </div>
                <h2 class="text-white font-heading fw-bold m-0 mb-2" style="letter-spacing: -0.02em;">
                    Tools Editor & Image Utilities
                </h2>
                <p class="text-secondary m-0" style="font-size: 0.95rem; max-width: 650px; line-height: 1.6;">
                    Semua alat praktis untuk mengompres, memotong, mengubah ukuran, memutar, menghapus background, hingga mengonversi gambar dan dokumen PDF secara instan, aman, dan tanpa watermark.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="d-inline-flex flex-column align-items-lg-end gap-1 p-3 rounded-3 bg-dark bg-opacity-50 border border-white border-opacity-10">
                    <span class="text-white small fw-bold d-flex align-items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-success"></i> 100% Client-Side Privacy
                    </span>
                    <span class="text-secondary" style="font-size: 0.75rem;">
                        File diproses langsung di browser Anda tanpa antrean server.
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div class="d-flex align-items-center gap-2 flex-wrap" id="toolsFilterGroup">
            <button type="button" class="filter-pill active" data-category="all">
                <i class="fa-solid fa-shapes me-1"></i> Semua Tools
            </button>
            <button type="button" class="filter-pill" data-category="optimize">
                <i class="fa-solid fa-gauge-high me-1"></i> Optimasi
            </button>
            <button type="button" class="filter-pill" data-category="edit">
                <i class="fa-solid fa-pen-nib me-1"></i> Edit & Potong
            </button>
            <button type="button" class="filter-pill" data-category="convert">
                <i class="fa-solid fa-repeat me-1"></i> Konversi & PDF
            </button>
            <button type="button" class="filter-pill" data-category="create">
                <i class="fa-solid fa-palette me-1"></i> Kreatif & Meme
            </button>
        </div>

        <div class="position-relative" style="min-width: 260px;">
            <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary" style="font-size: 0.85rem;"></i>
            <input type="text" id="toolSearchInput" class="form-control search-tools-input" placeholder="Cari tool (contoh: crop, compress, pdf)...">
        </div>
    </div>

    <!-- Tools Grid -->
    <div class="row g-3 g-xl-4" id="toolsGrid">
        <?php foreach ($tools as $tool): ?>
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3 tool-item" data-category="<?= esc($tool['category']) ?>" data-keywords="<?= esc(strtolower($tool['name'] . ' ' . $tool['title'] . ' ' . $tool['description'] . ' ' . $tool['formats'])) ?>">
                <a href="<?= base_url('tools-editor/' . $tool['id']) ?>" class="tool-card p-3 p-xl-4">
                    <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
                        <div class="tool-icon-box" style="background: <?= esc($tool['icon_color']) ?>15; color: <?= esc($tool['icon_color']) ?>; border: 1px solid <?= esc($tool['icon_color']) ?>30;">
                            <i class="<?= esc($tool['icon']) ?>"></i>
                        </div>
                        <?php if (!empty($tool['badge'])): ?>
                            <span class="tool-badge-pill">
                                <?= esc($tool['badge']) ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <h5 class="text-white font-heading fw-bold mb-1" style="font-size: 1.1rem;">
                        <?= esc($tool['name']) ?>
                    </h5>
                    
                    <p class="text-secondary small mb-3 flex-grow-1" style="font-size: 0.825rem; line-height: 1.5;">
                        <?= esc($tool['description']) ?>
                    </p>

                    <div class="pt-2 border-top border-white border-opacity-10 d-flex align-items-center justify-content-between text-secondary" style="font-size: 0.75rem;">
                        <span class="text-truncate me-2 font-monospace text-muted">
                            <i class="fa-regular fa-file-image me-1"></i><?= esc($tool['formats']) ?>
                        </span>
                        <span class="text-danger fw-semibold text-nowrap">
                            Buka Tool <i class="fa-solid fa-arrow-right ms-1"></i>
                        </span>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Empty State for Search -->
    <div id="noToolsFound" class="text-center py-5 d-none">
        <div class="d-inline-flex p-4 rounded-circle bg-dark border border-white border-opacity-10 text-secondary mb-3">
            <i class="fa-solid fa-magnifying-glass fs-2"></i>
        </div>
        <h5 class="text-white font-heading">Tool Tidak Ditemukan</h5>
        <p class="text-secondary small">Coba cari dengan kata kunci lain seperti <em>crop, resize, compress, atau pdf</em>.</p>
        <button type="button" class="btn btn-sm btn-outline-danger" onclick="resetFilters()">Reset Pencarian</button>
    </div>

    <!-- Privacy & Feature Notes Bottom Banner -->
    <div class="mt-5 p-4 rounded-4 bg-dark bg-opacity-40 border border-white border-opacity-05">
        <div class="row g-4 align-items-center">
            <div class="col-md-4 d-flex align-items-center gap-3">
                <div class="p-3 rounded-3 bg-danger bg-opacity-10 text-danger fs-4 flex-shrink-0">
                    <i class="fa-solid fa-bolt-lightning"></i>
                </div>
                <div>
                    <h6 class="text-white fw-bold mb-1">Performa Instan</h6>
                    <p class="text-secondary small m-0">Proses pemotongan, kompresi, dan rendering berlangsung seketika tanpa antrean unggah server.</p>
                </div>
            </div>
            <div class="col-md-4 d-flex align-items-center gap-3">
                <div class="p-3 rounded-3 bg-info bg-opacity-10 text-info fs-4 flex-shrink-0">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div>
                    <h6 class="text-white fw-bold mb-1">Privasi Terjaga</h6>
                    <p class="text-secondary small m-0">Dokumen dan foto Anda tidak pernah diunggah atau disimpan di server luar. Sepenuhnya aman di perangkat Anda.</p>
                </div>
            </div>
            <div class="col-md-4 d-flex align-items-center gap-3">
                <div class="p-3 rounded-3 bg-success bg-opacity-10 text-success fs-4 flex-shrink-0">
                    <i class="fa-solid fa-infinity"></i>
                </div>
                <div>
                    <h6 class="text-white fw-bold mb-1">Tanpa Batasan Kuota</h6>
                    <p class="text-secondary small m-0">Gunakan sepuasnya untuk kebutuhan tugas, project organisasi ekskul Multimedia Club.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('#toolsFilterGroup .filter-pill');
    const searchInput = document.getElementById('toolSearchInput');
    const toolItems = document.querySelectorAll('.tool-item');
    const noToolsFound = document.getElementById('noToolsFound');

    let currentCategory = 'all';
    let searchQuery = '';

    function applyFilter() {
        let visibleCount = 0;
        toolItems.forEach(item => {
            const cat = item.getAttribute('data-category');
            const keywords = item.getAttribute('data-keywords') || '';

            const matchCat = (currentCategory === 'all' || cat === currentCategory);
            const matchSearch = (!searchQuery || keywords.includes(searchQuery));

            if (matchCat && matchSearch) {
                item.classList.remove('d-none');
                visibleCount++;
            } else {
                item.classList.add('d-none');
            }
        });

        if (visibleCount === 0) {
            noToolsFound.classList.remove('d-none');
        } else {
            noToolsFound.classList.add('d-none');
        }
    }

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentCategory = this.getAttribute('data-category');
            applyFilter();
        });
    });

    searchInput.addEventListener('input', function() {
        searchQuery = this.value.trim().toLowerCase();
        applyFilter();
    });

    window.resetFilters = function() {
        searchInput.value = '';
        searchQuery = '';
        currentCategory = 'all';
        filterButtons.forEach(b => b.classList.remove('active'));
        filterButtons[0].classList.add('active');
        applyFilter();
    };
});
</script>

<?= $this->endSection() ?>
