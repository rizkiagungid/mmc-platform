<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>
<?php $isEdit = !empty($material['id']); ?>

<!-- CKEditor Dark SaaS Custom Styling -->
<style>
    .ck.ck-editor__main > .ck-editor__editable {
        background-color: #0d1117 !important;
        color: #f0f6fc !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
        min-height: 350px;
    }
    .ck.ck-toolbar {
        background-color: #161b22 !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
    }
    .ck.ck-toolbar .ck-button, 
    .ck.ck-toolbar .ck-dropdown__button {
        color: #c9d1d9 !important;
    }
    .ck.ck-toolbar .ck-button:hover, 
    .ck.ck-toolbar .ck-button.ck-on,
    .ck.ck-dropdown__button:hover {
        background-color: #21262d !important;
        color: #ffffff !important;
    }
    .ck.ck-dropdown__panel {
        background-color: #161b22 !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
    }
    .ck.ck-list__item .ck-button {
        color: #c9d1d9 !important;
    }
    .ck.ck-list__item .ck-button:hover {
        background-color: #21262d !important;
        color: #ffffff !important;
    }
    .ck.ck-input {
        background-color: #0d1117 !important;
        color: #ffffff !important;
        border-color: rgba(255, 255, 255, 0.2) !important;
    }
    .ck.ck-balloon-panel {
        background-color: #161b22 !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
    }
    .ck.ck-labeled-field-view__input-wrapper input {
        color: #ffffff !important;
    }
</style>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h4 class="text-white font-heading m-0">
            <i class="fa-solid <?= $isEdit ? 'fa-pen-to-square text-warning' : 'fa-plus text-danger' ?> me-2"></i>
            <?= $isEdit ? 'Edit Materi Pembelajaran' : 'Tambah Materi Pembelajaran Baru' ?>
        </h4>
        <p class="text-secondary small m-0">Gunakan Rich Text Editor untuk mempublikasikan materi kurikulum dan tutorial berkualitas</p>
    </div>
    <a href="<?= base_url('admin/learning') ?>" class="btn btn-saas-dark btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar
    </a>
</div>

<form action="<?= $isEdit ? base_url('admin/learning/update/' . $material['id']) : base_url('admin/learning/store') ?>" method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row g-4">
        <!-- Main Form Left (Title, Excerpt, CKEditor Content) -->
        <div class="col-lg-8">
            <div class="saas-card p-4 mb-4">
                <div class="mb-3">
                    <label class="form-label text-white small fw-bold">Judul Materi Pembelajaran <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="materialTitle" class="form-control form-control-lg bg-dark text-white border-secondary" placeholder="Contoh: Fundamental Python & Structuring Code untuk Pemula" value="<?= esc($material['title'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Slug URL SEO Friendly</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-dark text-secondary border-secondary font-monospace"><?= base_url('materi') ?>/</span>
                        <input type="text" name="slug" id="materialSlug" class="form-control bg-dark text-white border-secondary font-monospace" placeholder="python-dasar-pemula" value="<?= esc($material['slug'] ?? '') ?>">
                    </div>
                    <span class="text-secondary style-tiny">Kosongkan untuk membuat slug otomatis dari judul. Duplikat akan otomatis diberi imbuhan `-2`, `-3`.</span>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Deskripsi Ringkas (Excerpt) <span class="text-danger">*</span></label>
                    <textarea name="excerpt" class="form-control bg-dark text-white border-secondary" rows="3" placeholder="Ringkasan 2-3 kalimat mengenai poin utama yang dipelajari pada materi ini..." required><?= esc($material['excerpt'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <label class="form-label text-white small fw-bold mb-0">Isi Konten Materi Lengkap (Rich Text Content) <span class="text-danger">*</span></label>
                        <button type="button" class="btn btn-sm btn-outline-warning py-0 px-2 style-tiny" data-bs-toggle="modal" data-bs-target="#mediaLibraryModal">
                            <i class="fa-solid fa-photo-film me-1"></i> Pilih dari Media Library
                        </button>
                    </div>
                    <!-- CKEditor Container -->
                    <textarea name="content" id="editor" class="form-control" rows="15"><?= esc($material['content'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Sidebar Options Right -->
        <div class="col-lg-4">
            <!-- Publishing & Status Card -->
            <div class="saas-card p-4 mb-4">
                <h6 class="text-white font-heading fw-bold mb-3 border-bottom border-secondary border-opacity-25 pb-2">
                    <i class="fa-solid fa-paper-plane text-danger me-2"></i> Publikasi & Akses
                </h6>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-medium">Status Publikasi</label>
                    <select name="status" id="statusSelect" class="form-select bg-dark text-white border-secondary">
                        <option value="draft" <?= ($material['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Draft (Belum Tayang)</option>
                        <option value="published" <?= ($material['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published (Terbitkan)</option>
                        <option value="archived" <?= ($material['status'] ?? '') === 'archived' ? 'selected' : '' ?>>Archived (Diarsipkan)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-medium">Jadwal Tanggal Terbit (Scheduled)</label>
                    <input type="datetime-local" name="published_at" class="form-control bg-dark text-white border-secondary font-monospace" value="<?= !empty($material['published_at']) ? date('Y-m-d\TH:i', strtotime($material['published_at'])) : '' ?>">
                    <span class="text-secondary style-tiny">Jika diset di masa depan, materi otomatis tersembunyi hingga tanggalnya tiba.</span>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-medium">Hak Akses Pembaca (Visibility)</label>
                    <div class="p-2 rounded-2 bg-black border border-secondary border-opacity-25">
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="radio" name="visibility" id="visPublic" value="public" <?= ($material['visibility'] ?? 'public') === 'public' ? 'checked' : '' ?>>
                            <label class="form-check-label text-white small" for="visPublic">
                                <i class="fa-solid fa-globe text-success me-1"></i> Publik (Semua Pengunjung)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="visibility" id="visMember" value="member" <?= ($material['visibility'] ?? '') === 'member' ? 'checked' : '' ?>>
                            <label class="form-check-label text-warning small" for="visMember">
                                <i class="fa-solid fa-lock text-warning me-1"></i> Khusus Anggota MMC (Wajib Login)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featuredCheck" <?= !empty($material['is_featured']) ? 'checked' : '' ?>>
                    <label class="form-check-label text-warning small fw-semibold" for="featuredCheck">
                        <i class="fa-solid fa-star me-1"></i> Jadikan Materi Unggulan (Featured)
                    </label>
                </div>

                <button type="submit" class="btn btn-red w-100 py-2 font-heading fw-bold">
                    <i class="fa-solid fa-save me-1"></i> Simpan Materi Pembelajaran
                </button>
            </div>

            <!-- Taxonomies & Media Card -->
            <div class="saas-card p-4">
                <h6 class="text-white font-heading fw-bold mb-3 border-bottom border-secondary border-opacity-25 pb-2">
                    <i class="fa-solid fa-tags text-info me-2"></i> Kategori, Divisi & Media
                </h6>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-medium">Kategori Materi</label>
                    <select name="category" class="form-select bg-dark text-white border-secondary">
                        <option value="Tutorial" <?= ($material['category'] ?? '') === 'Tutorial' ? 'selected' : '' ?>>Tutorial</option>
                        <option value="Kurikulum" <?= ($material['category'] ?? '') === 'Kurikulum' ? 'selected' : '' ?>>Kurikulum</option>
                        <option value="Fundamental" <?= ($material['category'] ?? '') === 'Fundamental' ? 'selected' : '' ?>>Fundamental</option>
                        <option value="Best Practice" <?= ($material['category'] ?? '') === 'Best Practice' ? 'selected' : '' ?>>Best Practice</option>
                        <option value="Guide" <?= ($material['category'] ?? '') === 'Guide' ? 'selected' : '' ?>>Guide</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-medium">Divisi Terkait</label>
                    <select name="division_id" class="form-select bg-dark text-white border-secondary">
                        <option value="">-- Semua / General --</option>
                        <?php foreach ($divisions as $d): ?>
                            <option value="<?= $d['id'] ?>" <?= ($material['division_id'] ?? '') == $d['id'] ? 'selected' : '' ?>><?= esc($d['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-medium">Label Tag (Pisahkan dengan koma)</label>
                    <?php
                        $tagString = !empty($material['tags']) ? implode(', ', array_column($material['tags'], 'name')) : '';
                    ?>
                    <input type="text" name="tags" class="form-control bg-dark text-white border-secondary" placeholder="python, dasar, backend, web" value="<?= esc($tagString) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-medium">Thumbnail Card Image</label>
                    <input type="file" name="thumbnail_file" class="form-control form-control-sm bg-dark text-white border-secondary mb-1" accept="image/*">
                    <input type="text" name="thumbnail" id="thumbnailUrl" class="form-control form-control-sm bg-dark text-white border-secondary font-monospace" placeholder="Atau paste URL / Media Library..." value="<?= esc($material['thumbnail'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-medium">Banner Header Image (Opsional)</label>
                    <input type="file" name="banner_file" class="form-control form-control-sm bg-dark text-white border-secondary mb-1" accept="image/*">
                    <input type="text" name="banner" id="bannerUrl" class="form-control form-control-sm bg-dark text-white border-secondary font-monospace" placeholder="Atau paste URL / Media Library..." value="<?= esc($material['banner'] ?? '') ?>">
                </div>

                <!-- Material Downloadable Attachments Section -->
                <div class="mb-3 pt-3 border-top border-secondary border-opacity-25">
                    <label class="form-label text-white small fw-bold mb-1"><i class="fa-solid fa-paperclip text-warning me-1"></i> Lampiran File Terlampir (Attachments)</label>
                    <p class="text-secondary style-tiny mb-2">Tambahkan file pendukung yang dapat diunduh siswa (PDF, DOCX, ZIP, PPTX, Source Code).</p>
                    
                    <?php
                        $existingAttachments = !empty($material['attachments']) ? (is_string($material['attachments']) ? json_decode($material['attachments'], true) : $material['attachments']) : [];
                        if (!is_array($existingAttachments)) $existingAttachments = [];
                    ?>
                    
                    <div id="attachmentsWrapper">
                        <?php foreach ($existingAttachments as $idx => $att): ?>
                            <div class="p-2 rounded-2 bg-black border border-secondary border-opacity-25 mb-2 attachment-row">
                                <div class="row g-2">
                                    <div class="col-7">
                                        <input type="text" name="attachments[<?= $idx ?>][name]" class="form-control form-control-sm bg-dark text-white border-secondary" placeholder="Nama File (Contoh: Modul_Python.pdf)" value="<?= esc($att['name'] ?? '') ?>">
                                    </div>
                                    <div class="col-5">
                                        <select name="attachments[<?= $idx ?>][type]" class="form-select form-select-sm bg-dark text-white border-secondary style-tiny font-monospace">
                                            <optgroup label="File Unduhan">
                                                <option value="pdf" <?= ($att['type'] ?? '') === 'pdf' ? 'selected' : '' ?>>📄 File PDF</option>
                                                <option value="zip" <?= ($att['type'] ?? '') === 'zip' ? 'selected' : '' ?>>📦 Archive ZIP / RAR</option>
                                                <option value="docx" <?= ($att['type'] ?? '') === 'docx' ? 'selected' : '' ?>>📝 Dokumen DOCX / DOC</option>
                                                <option value="pptx" <?= ($att['type'] ?? '') === 'pptx' ? 'selected' : '' ?>>📊 Presentasi PPTX / PPT</option>
                                                <option value="code" <?= ($att['type'] ?? '') === 'code' ? 'selected' : '' ?>>💻 Source Code</option>
                                                <option value="other" <?= ($att['type'] ?? '') === 'other' ? 'selected' : '' ?>>📁 File Lainnya</option>
                                            </optgroup>
                                            <optgroup label="Tautan Media & Sosmed">
                                                <option value="youtube" <?= ($att['type'] ?? '') === 'youtube' ? 'selected' : '' ?>>▶️ YouTube Video / Channel</option>
                                                <option value="instagram" <?= ($att['type'] ?? '') === 'instagram' ? 'selected' : '' ?>>📸 Instagram Post / Reel</option>
                                                <option value="tiktok" <?= ($att['type'] ?? '') === 'tiktok' ? 'selected' : '' ?>>🎵 TikTok Video</option>
                                                <option value="x_twitter" <?= ($att['type'] ?? '') === 'x_twitter' ? 'selected' : '' ?>>𝕏 Post / Thread (Twitter)</option>
                                                <option value="external_link" <?= ($att['type'] ?? '') === 'external_link' ? 'selected' : '' ?>>🌐 Tautan Luar / External URL</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="col-10">
                                        <input type="text" name="attachments[<?= $idx ?>][url]" class="form-control form-control-sm bg-dark text-white border-secondary font-monospace style-tiny" placeholder="URL File / uploads/..." value="<?= esc($att['url'] ?? '') ?>">
                                    </div>
                                    <div class="col-2 text-end">
                                        <button type="button" class="btn btn-sm btn-outline-danger w-100 py-1" onclick="this.closest('.attachment-row').remove()"><i class="fa-solid fa-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-warning w-100 mt-1 style-tiny font-monospace" onclick="addAttachmentRow()">
                        <i class="fa-solid fa-plus me-1"></i> Tambah Lampiran File
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal Picker Media Library -->
<div class="modal fade" id="mediaLibraryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title font-heading"><i class="fa-solid fa-photo-film text-warning me-2"></i> Media Library Selector</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <?php foreach ($mediaList as $m): ?>
                        <div class="col-6 col-md-3 col-lg-2">
                            <div class="p-2 rounded-3 bg-black border border-secondary border-opacity-25 text-center cursor-pointer media-item-card" onclick="selectMediaUrl('<?= esc($m['file_path']) ?>')">
                                <img src="<?= esc($m['file_path']) ?>" alt="<?= esc($m['original_name']) ?>" class="w-100 object-fit-cover rounded-2 mb-2" style="height: 100px;">
                                <div class="text-white style-tiny text-truncate"><?= esc($m['original_name']) ?></div>
                                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 style-tiny mt-1">Pilih URL</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer border-top border-secondary border-opacity-25">
                <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Include CKEditor 5 CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    let editorInstance;
    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'todoList', '|', 'outdent', 'indent', '|', 'blockQuote', 'insertTable', 'codeBlock', 'mediaEmbed', 'undo', 'redo']
        })
        .then(editor => {
            editorInstance = editor;
        })
        .catch(error => {
            console.error(error);
        });

    // Auto generate slug from title
    document.getElementById('materialTitle')?.addEventListener('input', function() {
        const title = this.value;
        const slugInput = document.getElementById('materialSlug');
        if (slugInput && !slugInput.dataset.userEdited) {
            slugInput.value = title.toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }
    });

    document.getElementById('materialSlug')?.addEventListener('input', function() {
        this.dataset.userEdited = "true";
    });

    function selectMediaUrl(url) {
        if (editorInstance) {
            editorInstance.model.change(writer => {
                const insertPosition = editorInstance.model.document.selection.getFirstPosition();
                writer.insertText(url, insertPosition);
            });
        }
        alert('URL File Media disalin: ' + url);
        const modal = bootstrap.Modal.getInstance(document.getElementById('mediaLibraryModal'));
        if (modal) modal.hide();
    }

    let attachmentIndex = <?= count($existingAttachments ?? []) ?>;
    function addAttachmentRow() {
        const wrapper = document.getElementById('attachmentsWrapper');
        if (!wrapper) return;
        const div = document.createElement('div');
        div.className = 'p-2 rounded-2 bg-black border border-secondary border-opacity-25 mb-2 attachment-row';
        div.innerHTML = `
            <div class="row g-2">
                <div class="col-7">
                    <input type="text" name="attachments[${attachmentIndex}][name]" class="form-control form-control-sm bg-dark text-white border-secondary" placeholder="Nama File (Contoh: Modul_Python.pdf)">
                </div>
                <div class="col-5">
                    <select name="attachments[${attachmentIndex}][type]" class="form-select form-select-sm bg-dark text-white border-secondary style-tiny font-monospace">
                        <optgroup label="File Unduhan">
                            <option value="pdf">📄 File PDF</option>
                            <option value="zip">📦 Archive ZIP / RAR</option>
                            <option value="docx">📝 Dokumen DOCX / DOC</option>
                            <option value="pptx">📊 Presentasi PPTX / PPT</option>
                            <option value="code">💻 Source Code</option>
                            <option value="other">📁 File Lainnya</option>
                        </optgroup>
                        <optgroup label="Tautan Media & Sosmed">
                            <option value="youtube">▶️ YouTube Video / Channel</option>
                            <option value="instagram">📸 Instagram Post / Reel</option>
                            <option value="tiktok">🎵 TikTok Video</option>
                            <option value="x_twitter">𝕏 Post / Thread (Twitter)</option>
                            <option value="external_link">🌐 Tautan Luar / External URL</option>
                        </optgroup>
                    </select>
                </div>
                <div class="col-10">
                    <input type="text" name="attachments[${attachmentIndex}][url]" class="form-control form-control-sm bg-dark text-white border-secondary font-monospace style-tiny" placeholder="URL File / uploads/...">
                </div>
                <div class="col-2 text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger w-100 py-1" onclick="this.closest('.attachment-row').remove()"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
        `;
        wrapper.appendChild(div);
        attachmentIndex++;
    }
</script>
<?= $this->endSection() ?>
