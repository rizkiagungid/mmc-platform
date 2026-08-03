<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>
<!-- Reading Progress Bar (Fixed Top) -->
<div id="readingProgressBar" style="position: fixed; top: 0; left: 0; height: 4px; background: linear-gradient(90deg, #ef4444, #f97316); width: 0%; z-index: 9999; transition: width 0.1s ease;"></div>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb font-monospace style-tiny m-0">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>" class="text-secondary text-decoration-none"><i class="fa-solid fa-gauge me-1"></i> Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('member/learning') ?>" class="text-secondary text-decoration-none">Materi Pembelajaran</a></li>
                <li class="breadcrumb-item active text-danger text-truncate" style="max-width: 250px;"><?= esc($material['title']) ?></li>
            </ol>
        </nav>
    </div>
    <a href="<?= base_url('member/learning') ?>" class="btn btn-saas-dark btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Materi
    </a>
</div>

<!-- Main Article Card -->
<div class="saas-card overflow-hidden p-4 p-md-5 mb-5">
    <!-- Banner Image (If Any) -->
    <?php if (!empty($material['banner'])): ?>
        <div class="rounded-3 overflow-hidden mb-4 bg-dark text-center cursor-pointer" onclick="openLightbox('<?= (strpos($material['banner'], 'http') === 0) ? esc($material['banner']) : base_url($material['banner']) ?>')">
            <img src="<?= (strpos($material['banner'], 'http') === 0) ? esc($material['banner']) : base_url($material['banner']) ?>" alt="Banner" class="w-100 object-fit-cover" style="max-height: 380px;">
        </div>
    <?php endif; ?>

    <!-- Title & Metadata Header -->
    <div class="mb-4 pb-3 border-bottom border-secondary border-opacity-25">
        <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
            <span class="badge bg-danger font-monospace"><?= esc($material['division_name'] ?: 'Umum') ?></span>
            <span class="badge bg-secondary font-monospace"><?= esc($material['category']) ?></span>
            <?php if ($material['visibility'] === 'member'): ?>
                <span class="badge bg-warning text-dark font-monospace"><i class="fa-solid fa-lock me-1"></i> Khusus Anggota MMC</span>
            <?php endif; ?>
        </div>

        <h2 class="fw-bold text-white font-heading mb-3"><?= esc($material['title']) ?></h2>

        <!-- Author Avatar & Info -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 pt-2">
            <div class="d-flex align-items-center gap-3">
                <?php if (!empty($material['author_avatar'])): ?>
                    <img src="<?= base_url($material['author_avatar']) ?>" alt="Author" class="rounded-circle object-fit-cover border border-danger border-2" style="width: 44px; height: 44px;">
                <?php else: ?>
                    <div class="rounded-circle bg-danger bg-opacity-25 text-danger fw-bold d-flex align-items-center justify-content-center border border-danger border-opacity-50" style="width: 44px; height: 44px;">
                        <?= strtoupper(substr($material['author_name'] ?: 'A', 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div>
                    <div class="text-white fw-bold small"><?= esc($material['author_name'] ?: 'Super Admin') ?></div>
                    <div class="text-secondary style-tiny font-monospace">Dipublikasikan pada <?= date('d M Y H:i', strtotime($material['published_at'] ?: $material['created_at'])) ?></div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 text-secondary style-tiny font-monospace">
                <div><i class="fa-solid fa-clock text-danger me-1"></i> <?= $material['reading_time'] ?> min baca</div>
                <div><i class="fa-solid fa-eye text-info me-1"></i> <?= number_format($material['views_count']) ?> dibaca</div>
            </div>
        </div>
    </div>

    <!-- Excerpt Box -->
    <?php if (!empty($material['excerpt'])): ?>
        <div class="p-3 rounded-3 bg-dark border-start border-danger border-4 text-secondary small fst-italic mb-4">
            "<?= esc($material['excerpt']) ?>"
        </div>
    <?php endif; ?>

    <!-- Auto-Generated Table of Contents (TOC) -->
    <div id="tocContainer" class="p-3 rounded-3 bg-black border border-secondary border-opacity-25 mb-4 style-tiny" style="display: none;">
        <div class="fw-bold text-white font-monospace mb-2 text-uppercase d-flex align-items-center justify-content-between">
            <span><i class="fa-solid fa-list-ol text-danger me-2"></i> DAFTAR ISI MATERI (TOC)</span>
            <span class="badge bg-secondary style-tiny cursor-pointer" onclick="toggleToc()"><i class="fa-solid fa-chevron-up" id="tocToggleIcon"></i></span>
        </div>
        <div id="tocList" class="d-flex flex-column gap-1"></div>
    </div>

    <!-- Rich Text Content Body -->
    <div class="text-white style-learning-content mb-5 leading-relaxed" id="learningContent">
        <?= $material['content'] ?>
    </div>

    <!-- Downloadable Attachments Section (If Any) -->
    <?php
        $attachments = !empty($material['attachments']) ? (is_string($material['attachments']) ? json_decode($material['attachments'], true) : $material['attachments']) : [];
    ?>
    <?php if (!empty($attachments) && is_array($attachments)): ?>
        <div class="p-4 rounded-3 bg-black border border-secondary border-opacity-25 mb-5">
            <h6 class="text-white font-heading fw-bold mb-3 d-flex align-items-center justify-content-between">
                <span><i class="fa-solid fa-paperclip text-warning me-2"></i> Lampiran File Pembelajaran (Attachments)</span>
                <span class="badge bg-secondary style-tiny font-monospace"><?= count($attachments) ?> File Terlampir</span>
            </h6>
            <div class="row g-2">
                <?php foreach ($attachments as $att): ?>
                    <?php
                        $type = strtolower($att['type'] ?? 'other');
                        $iconClass = 'fa-file text-secondary';
                        $labelType = 'File Document';
                        $isExternal = false;

                        if ($type === 'pdf') { $iconClass = 'fa-file-pdf text-danger'; $labelType = 'PDF Document'; }
                        elseif ($type === 'zip') { $iconClass = 'fa-file-zipper text-warning'; $labelType = 'Archive ZIP/RAR'; }
                        elseif ($type === 'docx') { $iconClass = 'fa-file-word text-info'; $labelType = 'DOCX Document'; }
                        elseif ($type === 'pptx') { $iconClass = 'fa-file-powerpoint text-warning'; $labelType = 'PPTX Presentation'; }
                        elseif ($type === 'code') { $iconClass = 'fa-file-code text-success'; $labelType = 'Source Code'; }
                        elseif ($type === 'youtube') { $iconClass = 'fa-youtube text-danger'; $labelType = 'YouTube Video'; $isExternal = true; }
                        elseif ($type === 'instagram') { $iconClass = 'fa-instagram text-danger'; $labelType = 'Instagram Post/Reel'; $isExternal = true; }
                        elseif ($type === 'tiktok') { $iconClass = 'fa-tiktok text-light'; $labelType = 'TikTok Video'; $isExternal = true; }
                        elseif ($type === 'x_twitter') { $iconClass = 'fa-x-twitter text-light'; $labelType = 'X (Twitter) Post'; $isExternal = true; }
                        elseif ($type === 'external_link') { $iconClass = 'fa-arrow-up-right-from-square text-info'; $labelType = 'External URL'; $isExternal = true; }

                        $isBrandIcon = in_array($type, ['youtube', 'instagram', 'tiktok', 'x_twitter']);
                    ?>
                    <div class="col-md-6">
                        <div class="p-3 rounded-2 bg-dark border border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2 overflow-hidden me-2">
                                <i class="<?= $isBrandIcon ? 'fa-brands' : 'fa-solid' ?> <?= $iconClass ?> fs-4"></i>
                                <div class="overflow-hidden">
                                    <div class="text-white small fw-bold text-truncate"><?= esc($att['name'] ?: 'Tautan Lampiran') ?></div>
                                    <div class="text-secondary style-tiny font-monospace text-uppercase"><?= esc($labelType) ?></div>
                                </div>
                            </div>

                            <?php if ($isExternal): ?>
                                <a href="<?= (strpos($att['url'], 'http') === 0) ? esc($att['url']) : base_url($att['url']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-warning px-3 py-1 text-nowrap">
                                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka Link
                                </a>
                            <?php else: ?>
                                <a href="<?= (strpos($att['url'], 'http') === 0) ? esc($att['url']) : base_url($att['url']) ?>" download class="btn btn-sm btn-outline-danger px-3 py-1 text-nowrap">
                                    <i class="fa-solid fa-download me-1"></i> Unduh
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Tags & Action Share Footer -->
    <div class="pt-4 border-top border-secondary border-opacity-25 d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="text-secondary style-tiny font-monospace fw-bold me-1"><i class="fa-solid fa-tags text-danger me-1"></i> Tags:</span>
            <?php if (!empty($material['tags'])): ?>
                <?php foreach ($material['tags'] as $t): ?>
                    <a href="<?= base_url('member/learning?tag=' . $t['slug']) ?>" class="badge bg-dark border border-secondary text-secondary text-decoration-none font-monospace style-tiny">#<?= esc($t['name']) ?></a>
                <?php endforeach; ?>
            <?php else: ?>
                <span class="text-secondary style-tiny fst-italic">Tanpa tag</span>
            <?php endif; ?>
        </div>

        <!-- Copy Link Button & Social Share -->
        <div class="d-flex align-items-center gap-2">
            <?php $currentFullUrl = base_url('materi/' . $material['slug']); ?>
            <button type="button" class="btn btn-sm btn-red px-3" onclick="copyToClipboard('<?= esc($currentFullUrl) ?>')">
                <i class="fa-solid fa-copy me-1"></i> Copy Link Materi
            </button>
        </div>
    </div>
</div>

<!-- Related Materials Section -->
<?php if (!empty($related)): ?>
    <div class="mt-4">
        <h5 class="text-white font-heading fw-bold mb-3"><i class="fa-solid fa-layer-group text-danger me-2"></i> Materi Pembelajaran Terkait</h5>
        <div class="row g-3">
            <?php foreach ($related as $rel): ?>
                <div class="col-md-6">
                    <div class="saas-card saas-card-glow h-100 p-3 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-danger font-monospace style-tiny"><?= esc($rel['division_name'] ?: 'Umum') ?></span>
                                <span class="badge bg-secondary font-monospace style-tiny"><?= esc($rel['category']) ?></span>
                            </div>
                            <h6 class="text-white font-heading mb-2">
                                <a href="<?= base_url('member/learning/' . $rel['slug']) ?>" class="text-white text-decoration-none hover-danger"><?= esc($rel['title']) ?></a>
                            </h6>
                            <p class="text-secondary style-tiny mb-2 line-clamp-2"><?= esc(mb_strimwidth($rel['excerpt'] ?? '', 0, 90, '...')) ?></p>
                        </div>
                        <div class="pt-2 border-top border-secondary border-opacity-10 d-flex align-items-center justify-content-between text-secondary style-tiny font-monospace">
                            <div><i class="fa-solid fa-clock me-1 text-danger"></i> <?= $rel['reading_time'] ?> min</div>
                            <a href="<?= base_url('member/learning/' . $rel['slug']) ?>" class="text-danger fw-bold text-decoration-none">Pelajari <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Lightbox Modal Fullscreen Image View -->
<div class="modal fade" id="imageLightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0 text-center">
            <div class="modal-body p-0 position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 shadow" data-bs-dismiss="modal"></button>
                <img src="" id="lightboxTargetImg" class="img-fluid rounded-3 shadow-lg border border-secondary border-opacity-50" style="max-height: 85vh;">
            </div>
        </div>
    </div>
</div>

<script>
    // 1. Reading Progress Bar on Scroll
    window.onscroll = function() {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        const pb = document.getElementById('readingProgressBar');
        if (pb) pb.style.width = scrolled + '%';
    };

    // 2. Auto-Generate Table of Contents (TOC) from H2 & H3
    document.addEventListener('DOMContentLoaded', function() {
        const contentContainer = document.getElementById('learningContent');
        const tocContainer = document.getElementById('tocContainer');
        const tocList = document.getElementById('tocList');

        if (contentContainer && tocContainer && tocList) {
            const headings = contentContainer.querySelectorAll('h2, h3');
            if (headings.length > 0) {
                tocContainer.style.display = 'block';
                headings.forEach((h, index) => {
                    const id = 'heading-' + index;
                    h.id = id;
                    const isH3 = h.tagName.toLowerCase() === 'h3';
                    
                    const link = document.createElement('a');
                    link.href = '#' + id;
                    link.className = 'text-decoration-none ' + (isH3 ? 'ms-3 text-secondary style-tiny' : 'text-danger fw-semibold small');
                    link.innerHTML = (isH3 ? '<i class="fa-solid fa-angle-right me-1"></i> ' : '<i class="fa-solid fa-bookmark me-1"></i> ') + h.textContent;
                    link.onclick = function(e) {
                        e.preventDefault();
                        h.scrollIntoView({ behavior: 'smooth' });
                    };
                    tocList.appendChild(link);
                });
            }
        }

        // 3. Beautify Code Blocks (Dark Theme + Copy Code Button + Scroll)
        const codeBlocks = document.querySelectorAll('#learningContent pre');
        codeBlocks.forEach(pre => {
            const wrapper = document.createElement('div');
            wrapper.className = 'my-3 rounded-3 bg-black border border-secondary border-opacity-25 overflow-hidden';
            
            const header = document.createElement('div');
            header.className = 'd-flex align-items-center justify-content-between px-3 py-1 border-bottom border-secondary border-opacity-25 bg-dark text-secondary style-tiny font-monospace';
            header.innerHTML = `<span><i class="fa-solid fa-code text-danger me-1"></i> Code Block</span>`;
            
            const copyBtn = document.createElement('button');
            copyBtn.className = 'btn btn-sm btn-outline-light py-0 px-2 style-tiny font-monospace';
            copyBtn.innerHTML = '<i class="fa-solid fa-copy me-1"></i> Copy';
            copyBtn.onclick = function() {
                const codeText = pre.querySelector('code')?.innerText || pre.innerText;
                navigator.clipboard.writeText(codeText).then(() => {
                    copyBtn.innerHTML = '<i class="fa-solid fa-check text-success me-1"></i> Copied!';
                    setTimeout(() => copyBtn.innerHTML = '<i class="fa-solid fa-copy me-1"></i> Copy', 2000);
                });
            };
            header.appendChild(copyBtn);

            pre.parentNode.insertBefore(wrapper, pre);
            wrapper.appendChild(header);
            
            pre.classList.add('p-3', 'm-0', 'text-light', 'font-monospace', 'style-tiny');
            pre.style.overflowX = 'auto';
            pre.style.whiteSpace = 'pre';
            wrapper.appendChild(pre);
        });

        // 4. Clickable Image Lightbox Binding
        const contentImages = document.querySelectorAll('#learningContent img');
        contentImages.forEach(img => {
            img.classList.add('rounded-3', 'shadow', 'cursor-pointer', 'my-3', 'img-fluid');
            img.title = "Klik untuk memperbesar gambar";
            img.onclick = function() {
                openLightbox(img.src);
            };
        });
    });

    function toggleToc() {
        const list = document.getElementById('tocList');
        const icon = document.getElementById('tocToggleIcon');
        if (list.style.display === 'none') {
            list.style.display = 'flex';
            icon.className = 'fa-solid fa-chevron-up';
        } else {
            list.style.display = 'none';
            icon.className = 'fa-solid fa-chevron-down';
        }
    }

    function openLightbox(src) {
        document.getElementById('lightboxTargetImg').src = src;
        const modal = new bootstrap.Modal(document.getElementById('imageLightboxModal'));
        modal.show();
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('URL Materi berhasil disalin ke clipboard:\n' + text);
        }, function(err) {
            prompt('Salin link materi ini:', text);
        });
    }
</script>
<?= $this->endSection() ?>
