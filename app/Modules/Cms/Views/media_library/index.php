<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="text-white font-heading m-0 d-flex align-items-center gap-2">
            <i class="fa-solid fa-photo-film text-danger"></i> Centralized Media Library
        </h4>
        <p class="text-secondary small m-0 mt-1">Pusat penyimpanan berkas gambar, logo, banner, video, dan dokumen platform dengan resolusi URL produksi otomatis</p>
    </div>

    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-red shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#uploadMediaModal">
            <i class="fa-solid fa-cloud-arrow-up me-1.5"></i> Unggah Berkas Baru
        </button>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success bg-success bg-opacity-25 border-0 text-success small mb-4 rounded-3 p-3 d-flex align-items-center gap-2">
        <i class="fa-solid fa-circle-check fs-5"></i>
        <span><?= session()->getFlashdata('success') ?></span>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger bg-danger bg-opacity-25 border-0 text-danger small mb-4 rounded-3 p-3 d-flex align-items-center gap-2">
        <i class="fa-solid fa-triangle-exclamation fs-5"></i>
        <span><?= session()->getFlashdata('error') ?></span>
    </div>
<?php endif; ?>

<!-- Filter & Search Toolbar -->
<div class="saas-card p-3 mb-4 border border-secondary border-opacity-25">
    <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center justify-content-between gap-3">
        <!-- Folder Pills -->
        <div class="d-flex flex-wrap gap-1.5">
            <?php
            $folders = [
                'all'          => 'Semua',
                'general'      => 'General',
                'hero'         => 'Hero & Banner',
                'portfolio'    => 'Portofolio',
                'gallery'      => 'Galeri',
                'divisions'    => 'Divisi',
                'achievements' => 'Prestasi',
            ];
            $currentFolder = $selectedFolder ?? 'all';
            ?>
            <?php foreach ($folders as $key => $label): ?>
                <?php
                $isActive = ($currentFolder === $key);
                $filterUrl = base_url('admin/cms/media') . ($key === 'all' ? '' : '?folder=' . $key);
                if (!empty($searchQuery)) {
                    $filterUrl .= ($key === 'all' ? '?' : '&') . 'search=' . urlencode($searchQuery);
                }
                ?>
                <a href="<?= $filterUrl ?>" class="btn btn-sm <?= $isActive ? 'btn-red' : 'btn-saas-dark text-secondary' ?> px-3 py-1.5 style-tiny rounded-pill">
                    <?= esc($label) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Search Form -->
        <form action="<?= base_url('admin/cms/media') ?>" method="GET" class="d-flex align-items-center gap-2">
            <?php if ($currentFolder !== 'all'): ?>
                <input type="hidden" name="folder" value="<?= esc($currentFolder) ?>">
            <?php endif; ?>
            <div class="input-group input-group-sm" style="min-width: 240px;">
                <span class="input-group-text bg-black border-secondary border-opacity-25 text-secondary">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" class="form-control bg-black text-white border-secondary border-opacity-25" placeholder="Cari nama berkas / alt..." value="<?= esc($searchQuery ?? '') ?>">
                <?php if (!empty($searchQuery)): ?>
                    <a href="<?= base_url('admin/cms/media' . ($currentFolder !== 'all' ? '?folder=' . $currentFolder : '')) ?>" class="btn btn-outline-secondary border-opacity-25 text-secondary" title="Reset pencarian">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-sm btn-saas-dark text-white border border-secondary border-opacity-25 px-2.5">
                Cari
            </button>
        </form>
    </div>
</div>

<!-- Media Grid -->
<div class="saas-card p-4 border border-secondary border-opacity-25">
    <?php if (empty($media)): ?>
        <div class="text-center py-5 text-secondary">
            <i class="fa-solid fa-photo-film display-2 mb-3 opacity-25 text-danger"></i>
            <h5 class="text-white font-heading">Tidak Ada Berkas Ditemukan</h5>
            <p class="small mb-3">Belum ada gambar atau berkas dalam kategori ini yang sesuai dengan pencarian Anda.</p>
            <button type="button" class="btn btn-red btn-sm" data-bs-toggle="modal" data-bs-target="#uploadMediaModal">
                <i class="fa-solid fa-cloud-arrow-up me-1"></i> Unggah Berkas Sekarang
            </button>
        </div>
    <?php else: ?>
        <div class="d-flex align-items-center justify-content-between mb-3 text-secondary style-tiny font-monospace">
            <span>Total: <?= count($media) ?> berkas</span>
            <span>Klik <i class="fa-solid fa-copy text-info"></i> untuk menyalin Full URL</span>
        </div>

        <div class="row g-3">
            <?php foreach ($media as $m): ?>
                <?php
                $fullUrl = media_url($m['file_path']);
                $isImage = (strpos($m['mime_type'], 'image') !== false || in_array(strtolower($m['extension']), ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'avif']));
                $isVideo = (strpos($m['mime_type'], 'video') !== false || in_array(strtolower($m['extension']), ['mp4', 'webm', 'mov', 'mkv']));
                $isAudio = (strpos($m['mime_type'], 'audio') !== false || in_array(strtolower($m['extension']), ['mp3', 'wav', 'ogg', 'm4a']));
                $isPdf   = (strtolower($m['extension']) === 'pdf');
                ?>
                <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                    <div class="saas-card p-2.5 h-100 border border-secondary border-opacity-25 position-relative d-flex flex-column justify-content-between rounded-3 shadow-sm hover-elevate">
                        
                        <!-- Thumbnail Preview Area -->
                        <div class="rounded-2 bg-black d-flex align-items-center justify-content-center overflow-hidden mb-2 position-relative" style="height: 130px;">
                            <?php if ($isImage): ?>
                                <img src="<?= esc($fullUrl) ?>" alt="<?= esc($m['alt_text'] ?: $m['original_name']) ?>" class="w-100 h-100 object-fit-cover" loading="lazy" onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\'fa-solid fa-image text-secondary fs-1\'></i>';">
                            <?php elseif ($isVideo): ?>
                                <div class="text-center">
                                    <i class="fa-solid fa-circle-play fs-1 text-primary mb-1"></i>
                                    <div class="style-tiny text-secondary font-monospace">VIDEO</div>
                                </div>
                            <?php elseif ($isAudio): ?>
                                <div class="text-center">
                                    <i class="fa-solid fa-file-audio fs-1 text-purple mb-1"></i>
                                    <div class="style-tiny text-secondary font-monospace">AUDIO</div>
                                </div>
                            <?php elseif ($isPdf): ?>
                                <div class="text-center">
                                    <i class="fa-solid fa-file-pdf fs-1 text-danger mb-1"></i>
                                    <div class="style-tiny text-secondary font-monospace">PDF</div>
                                </div>
                            <?php else: ?>
                                <div class="text-center">
                                    <i class="fa-solid fa-file-lines fs-1 text-warning mb-1"></i>
                                    <div class="style-tiny text-secondary font-monospace"><?= strtoupper(esc($m['extension'])) ?></div>
                                </div>
                            <?php endif; ?>

                            <!-- Folder Badge -->
                            <span class="position-absolute top-0 start-0 m-1.5 badge bg-dark bg-opacity-75 text-white style-tiny font-monospace border border-secondary border-opacity-25 py-0.5 px-1.5">
                                <?= esc($m['folder'] ?? 'general') ?>
                            </span>
                        </div>
                        
                        <!-- File Metadata -->
                        <div class="px-1 mb-2">
                            <div class="text-white small fw-semibold text-truncate" title="<?= esc($m['original_name']) ?>">
                                <?= esc($m['original_name']) ?>
                            </div>
                            <div class="text-secondary style-tiny font-monospace d-flex justify-content-between mt-1">
                                <span class="badge bg-secondary bg-opacity-25 text-body text-uppercase"><?= esc($m['extension']) ?></span>
                                <span><?= round($m['file_size'] / 1024, 1) ?> KB</span>
                            </div>
                            <?php if (!empty($m['width']) && !empty($m['height'])): ?>
                                <div class="text-secondary style-tiny font-monospace mt-0.5" style="font-size: 0.68rem;">
                                    <i class="fa-solid fa-vector-square me-1 opacity-50"></i><?= $m['width'] ?> &times; <?= $m['height'] ?> px
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Action Toolbar -->
                        <div class="pt-2 border-top border-secondary border-opacity-10 d-flex justify-content-between align-items-center gap-1">
                            <!-- Copy Full URL Button -->
                            <button type="button" 
                                    class="btn btn-sm btn-outline-info py-1 px-2 style-tiny rounded-2 flex-grow-1 text-truncate btn-copy-media" 
                                    data-url="<?= esc($fullUrl) ?>"
                                    title="Salin Full URL ke Clipboard">
                                <i class="fa-solid fa-copy me-1"></i> Copy URL
                            </button>

                            <!-- Open Direct Link -->
                            <a href="<?= esc($fullUrl) ?>" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="btn btn-sm btn-saas-dark text-secondary hover-text-white py-1 px-2 style-tiny rounded-2 border border-secondary border-opacity-25" 
                               title="Buka Berkas di Tab Baru">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>

                            <!-- Delete Button -->
                            <a href="<?= base_url('admin/cms/media/delete/' . $m['id']) ?>" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus file ini dari Media Library?')" 
                               class="btn btn-sm btn-outline-danger py-1 px-2 style-tiny rounded-2" 
                               title="Hapus File">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Upload Media -->
<!-- Upload Modal (Multi-File Drag & Drop) -->
<div class="modal fade" id="uploadMediaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25 shadow-lg">
            <div class="modal-header border-bottom border-secondary border-opacity-25 py-2.5">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-danger text-white font-monospace style-tiny py-1 px-2.5 rounded-pill">
                        <i class="fa-solid fa-cloud-arrow-up me-1"></i> MULTI-UPLOAD
                    </span>
                    <h5 class="modal-title font-heading fs-6 m-0">
                        Unggah Berkas ke Media Library
                    </h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/cms/media/upload') ?>" method="POST" enctype="multipart/form-data" id="uploadMediaForm">
                <?= csrf_field() ?>
                <div class="modal-body p-3">
                    <!-- Dropzone Area -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-1.5">
                            <label class="form-label text-secondary small fw-semibold m-0">
                                Pilih atau Tarik Berkas <span class="text-danger">*</span>
                            </label>
                            <span class="badge bg-secondary bg-opacity-25 text-light font-monospace style-tiny">
                                Bisa banyak berkas sekaligus
                            </span>
                        </div>

                        <!-- Clickable & Drag-Drop Dropzone -->
                        <div class="border border-secondary border-opacity-50 rounded-3 p-4 text-center bg-black bg-opacity-30 position-relative transition" id="dropzoneArea" style="cursor: pointer; border-style: dashed !important; border-width: 2px !important;">
                            <input type="file" name="media_files[]" id="mediaFileInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" style="cursor: pointer; z-index: 5;" multiple required>
                            
                            <div id="dropzonePrompt" class="py-2" style="pointer-events: none;">
                                <i class="fa-solid fa-cloud-arrow-up text-danger fs-1 mb-2 d-block"></i>
                                <div class="fw-semibold text-white small">Klik untuk memilih berkas atau seret & jatuhkan ke sini</div>
                                <div class="text-secondary style-tiny mt-1">Pilih 1 atau banyak file langsung: Gambar, Video, Audio, Dokumen (Maks. 50MB per berkas)</div>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Files Queue Card (Shown when 1 or more files are selected) -->
                    <div id="fileQueueWrapper" class="mb-3 d-none">
                        <div class="d-flex align-items-center justify-content-between p-2 rounded bg-danger bg-opacity-10 border border-danger border-opacity-25 mb-2">
                            <span class="text-white small fw-bold d-flex align-items-center gap-1.5">
                                <i class="fa-solid fa-circle-check text-success"></i>
                                <span id="fileCountBadge">0 Berkas Dipilih</span>
                                <span class="text-secondary fw-normal style-tiny" id="totalSizeBadge">(0 MB)</span>
                            </span>
                            <div class="d-flex gap-1.5">
                                <button type="button" class="btn btn-sm btn-outline-light py-0.5 px-2 style-tiny" id="btnAddMoreFiles" title="Pilih berkas tambahan">
                                    <i class="fa-solid fa-plus me-1"></i> Tambah File
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger py-0.5 px-2 style-tiny" id="btnClearAllFiles" title="Hapus semua pilihan">
                                    <i class="fa-solid fa-trash me-1"></i> Kosongkan
                                </button>
                            </div>
                        </div>

                        <!-- Scrollable List of Files -->
                        <div id="fileQueueList" class="d-flex flex-column gap-1.5 p-2 rounded bg-black bg-opacity-40 border border-secondary border-opacity-25" style="max-height: 220px; overflow-y: auto;">
                            <!-- Dynamically generated file items -->
                        </div>
                    </div>

                    <!-- Folder Selector -->
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Simpan ke Folder Kategori</label>
                        <select name="folder" class="form-select bg-black text-white border-secondary border-opacity-50">
                            <option value="general" <?= ($selectedFolder ?? '') === 'general' ? 'selected' : '' ?>>General (Umum)</option>
                            <option value="hero" <?= ($selectedFolder ?? '') === 'hero' ? 'selected' : '' ?>>Hero & Banners</option>
                            <option value="portfolio" <?= ($selectedFolder ?? '') === 'portfolio' ? 'selected' : '' ?>>Portofolio Karya</option>
                            <option value="gallery" <?= ($selectedFolder ?? '') === 'gallery' ? 'selected' : '' ?>>Galeri Kegiatan</option>
                            <option value="divisions" <?= ($selectedFolder ?? '') === 'divisions' ? 'selected' : '' ?>>Divisi</option>
                            <option value="achievements" <?= ($selectedFolder ?? '') === 'achievements' ? 'selected' : '' ?>>Sertifikat & Prestasi</option>
                        </select>
                    </div>

                    <!-- Collapsible Optional Metadata -->
                    <div class="mb-2">
                        <a class="text-secondary style-tiny text-decoration-none d-inline-flex align-items-center gap-1" data-bs-toggle="collapse" href="#extraMetaCollapse" role="button" aria-expanded="false">
                            <i class="fa-solid fa-sliders text-danger"></i> Opsi Tambahan (Alt Text & Caption) <i class="fa-solid fa-chevron-down ms-1" style="font-size: 0.65rem;"></i>
                        </a>
                        <div class="collapse mt-2" id="extraMetaCollapse">
                            <div class="p-2.5 rounded bg-black bg-opacity-25 border border-secondary border-opacity-25">
                                <div class="mb-2">
                                    <label class="form-label text-secondary style-tiny mb-1">Teks Alt Gambar Default (SEO)</label>
                                    <input type="text" name="alt_text" class="form-control form-control-sm bg-black text-white border-secondary border-opacity-50" placeholder="Kosongkan jika ingin otomatis sesuai nama berkas masing-masing">
                                </div>
                                <div>
                                    <label class="form-label text-secondary style-tiny mb-1">Keterangan / Caption Default</label>
                                    <input type="text" name="caption" class="form-control form-control-sm bg-black text-white border-secondary border-opacity-50" placeholder="Deskripsi ringkas file...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25 py-2.5 justify-content-between">
                    <span class="text-secondary style-tiny" id="modalUploadHint">
                        <i class="fa-solid fa-bolt text-warning me-1"></i> Bisa seret banyak foto/file sekaligus ke area kotak.
                    </span>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-red px-4" id="btnSubmitUpload">
                            <i class="fa-solid fa-cloud-arrow-up me-1.5"></i> <span id="btnSubmitText">Unggah Berkas</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Floating Toast Container -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
    <div id="mediaToast" class="toast align-items-center text-bg-success border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2">
                <i class="fa-solid fa-circle-check fs-5"></i>
                <span id="mediaToastMessage">Full URL berhasil disalin ke clipboard!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toastEl = document.getElementById('mediaToast');
    const toast = toastEl ? new bootstrap.Toast(toastEl, { delay: 3000 }) : null;
    const toastMsg = document.getElementById('mediaToastMessage');

    // Clipboard Copy Handler
    document.querySelectorAll('.btn-copy-media').forEach(function(button) {
        button.addEventListener('click', function() {
            const urlToCopy = this.getAttribute('data-url');
            if (!urlToCopy) return;

            const originalHtml = this.innerHTML;
            const originalClasses = this.className;

            const copyAction = () => {
                this.innerHTML = '<i class="fa-solid fa-check me-1"></i> Disalin!';
                this.classList.remove('btn-outline-info');
                this.classList.add('btn-success');

                if (toast && toastMsg) {
                    toastMsg.innerText = 'Full URL berhasil disalin: ' + urlToCopy;
                    toast.show();
                }

                setTimeout(() => {
                    this.innerHTML = originalHtml;
                    this.className = originalClasses;
                }, 2000);
            };

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(urlToCopy).then(copyAction).catch(err => {
                    fallbackCopyText(urlToCopy, copyAction);
                });
            } else {
                fallbackCopyText(urlToCopy, copyAction);
            }
        });
    });

    function fallbackCopyText(text, callback) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
            if (callback) callback();
        } catch (err) {
            prompt('Salin URL manual:', text);
        }
        document.body.removeChild(textArea);
    }

    // Multi-File Drag & Drop Handler
    const dropzoneArea   = document.getElementById('dropzoneArea');
    const fileInput      = document.getElementById('mediaFileInput');
    const queueWrapper   = document.getElementById('fileQueueWrapper');
    const queueList      = document.getElementById('fileQueueList');
    const fileCountBadge = document.getElementById('fileCountBadge');
    const totalSizeBadge = document.getElementById('totalSizeBadge');
    const btnSubmitText  = document.getElementById('btnSubmitText');
    const btnSubmit      = document.getElementById('btnSubmitUpload');
    const uploadForm     = document.getElementById('uploadMediaForm');
    const btnAddMore     = document.getElementById('btnAddMoreFiles');
    const btnClearAll    = document.getElementById('btnClearAllFiles');

    // Using DataTransfer to manage selected files queue
    let dt = new DataTransfer();

    function formatBytes(bytes) {
        if (!bytes || bytes === 0) return '0 KB';
        const kb = bytes / 1024;
        if (kb < 1024) return kb.toFixed(1) + ' KB';
        return (kb / 1024).toFixed(2) + ' MB';
    }

    function renderFileList() {
        if (!queueList || !fileInput) return;
        
        fileInput.files = dt.files;
        queueList.innerHTML = '';

        if (dt.files.length === 0) {
            queueWrapper.classList.add('d-none');
            btnSubmitText.textContent = 'Unggah Berkas';
            btnSubmit.disabled = false;
            return;
        }

        queueWrapper.classList.remove('d-none');
        fileCountBadge.textContent = dt.files.length + ' Berkas Dipilih';
        
        let totalBytes = 0;
        Array.from(dt.files).forEach(f => totalBytes += f.size);
        totalSizeBadge.textContent = '(' + formatBytes(totalBytes) + ')';

        btnSubmitText.textContent = dt.files.length > 1 
            ? `Unggah ${dt.files.length} Berkas Sekaligus` 
            : 'Unggah 1 Berkas';

        Array.from(dt.files).forEach((file, index) => {
            const item = document.createElement('div');
            item.className = 'd-flex align-items-center justify-content-between p-2 rounded bg-black bg-opacity-75 border border-secondary border-opacity-25 gap-2';

            const isImg = file.type && file.type.startsWith('image/');
            const thumbSrc = isImg ? URL.createObjectURL(file) : '';

            item.innerHTML = `
                <div class="d-flex align-items-center gap-2.5 overflow-hidden flex-grow-1">
                    <div class="rounded overflow-hidden bg-dark d-flex align-items-center justify-content-center flex-shrink-0 border border-secondary border-opacity-50" style="width: 38px; height: 38px;">
                        ${isImg 
                            ? `<img src="${thumbSrc}" alt="thumb" class="w-100 h-100 object-fit-cover">` 
                            : `<i class="fa-solid fa-file text-secondary fs-5"></i>`}
                    </div>
                    <div class="overflow-hidden">
                        <div class="text-white style-tiny fw-bold text-truncate" title="${file.name}">${file.name}</div>
                        <div class="text-secondary" style="font-size: 0.7rem;">${formatBytes(file.size)}</div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-link text-danger p-1 text-decoration-none btn-remove-queue-file" data-index="${index}" title="Hapus dari daftar">
                    <i class="fa-solid fa-xmark fs-6"></i>
                </button>
            `;
            queueList.appendChild(item);
        });
    }

    function addFiles(newFiles) {
        if (!newFiles || !newFiles.length) return;
        Array.from(newFiles).forEach(file => {
            let exists = false;
            for (let i = 0; i < dt.files.length; i++) {
                if (dt.files[i].name === file.name && dt.files[i].size === file.size && dt.files[i].lastModified === file.lastModified) {
                    exists = true;
                    break;
                }
            }
            if (!exists) {
                dt.items.add(file);
            }
        });
        renderFileList();
    }

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            addFiles(this.files);
        });
    }

    // Drag and Drop Visual Feedback & Drop Handling
    if (dropzoneArea) {
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzoneArea.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzoneArea.classList.add('border-danger', 'bg-danger', 'bg-opacity-10');
            }, false);
        });

        ['dragleave', 'dragend'].forEach(eventName => {
            dropzoneArea.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzoneArea.classList.remove('border-danger', 'bg-danger', 'bg-opacity-10');
            }, false);
        });

        dropzoneArea.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropzoneArea.classList.remove('border-danger', 'bg-danger', 'bg-opacity-10');

            if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                addFiles(e.dataTransfer.files);
            }
        }, false);
    }

    // Remove single file from queue
    if (queueList) {
        queueList.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-remove-queue-file');
            if (!btn) return;
            const indexToRemove = parseInt(btn.getAttribute('data-index'), 10);
            
            const newDt = new DataTransfer();
            for (let i = 0; i < dt.files.length; i++) {
                if (i !== indexToRemove) {
                    newDt.items.add(dt.files[i]);
                }
            }
            dt = newDt;
            renderFileList();
        });
    }

    // Add more files button trigger
    if (btnAddMore && fileInput) {
        btnAddMore.addEventListener('click', function(e) {
            e.stopPropagation();
            fileInput.click();
        });
    }

    // Clear all files
    if (btnClearAll) {
        btnClearAll.addEventListener('click', function(e) {
            e.stopPropagation();
            dt = new DataTransfer();
            renderFileList();
        });
    }

    // Form submit loading
    if (uploadForm && btnSubmit) {
        uploadForm.addEventListener('submit', function(e) {
            if (dt.files.length === 0) {
                e.preventDefault();
                alert('Silakan pilih minimal satu berkas untuk diunggah.');
                return;
            }
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = `<i class="fa-solid fa-spinner fa-spin me-1.5"></i> Mengunggah ${dt.files.length} Berkas...`;
        });
    }
});
</script>

<?= $this->endSection() ?>
