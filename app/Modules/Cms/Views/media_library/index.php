<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Centralized Media Library</h4>
        <p class="text-secondary small m-0">Pusat penyimpanan berkas gambar, logo, banner, dan dokumen platform dengan metadata produksi lengkap</p>
    </div>

    <button type="button" class="btn btn-red" data-bs-toggle="modal" data-bs-target="#uploadMediaModal">
        <i class="fa-solid fa-cloud-arrow-up me-1"></i> Unggah Berkas Baru
    </button>
</div>

<div class="saas-card p-4">
    <!-- Media Grid -->
    <?php if (empty($media)): ?>
        <div class="text-center py-5 text-secondary">
            <i class="fa-solid fa-photo-film display-1 mb-3 opacity-25"></i>
            <h5 class="text-white font-heading">Media Library Kosong</h5>
            <p class="small mb-0">Belum ada gambar atau berkas yang diunggah ke perpustakaan media.</p>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($media as $m): ?>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="saas-card p-2 h-100 border border-secondary border-opacity-25 position-relative group-hover">
                        <div class="rounded-2 bg-black d-flex align-items-center justify-content-center overflow-hidden mb-2" style="height: 120px;">
                            <?php if (strpos($m['mime_type'], 'image') !== false): ?>
                                <img src="<?= esc($m['file_path']) ?>" alt="<?= esc($m['alt_text']) ?>" class="w-100 h-100 object-fit-cover" loading="lazy">
                            <?php else: ?>
                                <i class="fa-solid fa-file-lines fs-1 text-danger"></i>
                            <?php endif; ?>
                        </div>
                        
                        <div class="px-1">
                            <div class="text-white small fw-semibold text-truncate" title="<?= esc($m['original_name']) ?>">
                                <?= esc($m['original_name']) ?>
                            </div>
                            <div class="text-secondary style-tiny font-monospace d-flex justify-content-between">
                                <span><?= strtoupper(esc($m['extension'])) ?></span>
                                <span><?= round($m['file_size'] / 1024, 1) ?> KB</span>
                            </div>
                        </div>

                        <div class="mt-2 pt-2 border-top border-secondary border-opacity-10 d-flex justify-content-between align-items-center">
                            <button type="button" onclick="navigator.clipboard.writeText('<?= esc($m['file_path']) ?>'); alert('URL disalin ke clipboard!');" class="btn btn-sm btn-outline-info py-0 px-2 style-tiny">
                                <i class="fa-solid fa-copy me-1"></i> Copy URL
                            </button>
                            <a href="<?= base_url('admin/cms/media/delete/' . $m['id']) ?>" onclick="return confirm('Hapus file dari Media Library?')" class="btn btn-sm text-danger p-0" title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Upload Media -->
<div class="modal fade" id="uploadMediaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title font-heading"><i class="fa-solid fa-cloud-arrow-up text-danger me-2"></i> Unggah File ke Media Library</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/cms/media/upload') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Pilih File (Gambar/Dokumen) <span class="text-danger">*</span></label>
                        <input type="file" name="media_file" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Folder Kategori</label>
                        <select name="folder" class="form-select">
                            <option value="general">General (Umum)</option>
                            <option value="hero">Hero & Banners</option>
                            <option value="portfolio">Portofolio Karya</option>
                            <option value="gallery">Galeri Kegiatan</option>
                            <option value="divisions">Divisi</option>
                            <option value="achievements">Sertifikat & Prestasi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Teks Alt Gambar (Alt Text SEO)</label>
                        <input type="text" name="alt_text" class="form-control" placeholder="Deskripsi alternatif gambar...">
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25">
                    <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-red">Unggah File</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
