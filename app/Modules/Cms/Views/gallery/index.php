<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<!-- Header & Quick Actions -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-danger bg-opacity-25 text-danger font-monospace px-2 py-1">CMS GALERI</span>
            <span class="text-secondary small font-monospace">Multimedia Club SMAN 1 Tamansari</span>
        </div>
        <h3 class="text-white font-heading m-0 fw-bold">Manajemen Galeri & Dokumentasi Kegiatan</h3>
        <p class="text-secondary small m-0">Upload foto & video dokumentasi kegiatan, latihan, liputan event, dan bagikan tautan eksternal.</p>
    </div>

    <div class="d-flex align-items-center gap-2">
        <a href="<?= base_url('gallery') ?>" target="_blank" class="btn btn-outline-secondary btn-sm px-3">
            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Lihat Halaman Galeri
        </a>
        <button type="button" class="btn btn-red btn-sm px-3 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#addGalleryModal">
            <i class="fa-solid fa-plus me-1"></i> Tambah Dokumentasi
        </button>
    </div>
</div>

<!-- Quick Statistics Summary -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="saas-card p-3 d-flex align-items-center gap-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(220, 38, 38, 0.15); color: #ef4444;">
                <i class="fa-solid fa-calendar-days fs-4"></i>
            </div>
            <div>
                <span class="text-secondary small font-monospace d-block">TOTAL KEGIATAN</span>
                <span class="text-white fs-4 fw-bold font-heading"><?= number_format($totalAlbums) ?></span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="saas-card p-3 d-flex align-items-center gap-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(34, 197, 94, 0.15); color: #22c55e;">
                <i class="fa-solid fa-images fs-4"></i>
            </div>
            <div>
                <span class="text-secondary small font-monospace d-block">TOTAL FOTO</span>
                <span class="text-white fs-4 fw-bold font-heading"><?= number_format($totalPhotos) ?></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="saas-card p-3 d-flex align-items-center gap-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(6, 182, 212, 0.15); color: #06b6d4;">
                <i class="fa-solid fa-film fs-4"></i>
            </div>
            <div>
                <span class="text-secondary small font-monospace d-block">TOTAL VIDEO</span>
                <span class="text-white fs-4 fw-bold font-heading"><?= number_format($totalVideos) ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Main Table of Albums / Activities -->
<div class="saas-card p-3 p-md-4">
    <div class="table-responsive">
        <table class="table table-dark table-dark-saas align-middle datatable-saas">
            <thead>
                <tr>
                    <th style="min-width: 260px;">Judul Kegiatan & Dokumentasi</th>
                    <th>Kategori</th>
                    <th>Tanggal Pelaksanaan</th>
                    <th>Media Terunggah</th>
                    <th>Link Eksternal</th>
                    <th>Dibuat Oleh</th>
                    <th class="text-end" style="min-width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($albums)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-secondary">
                            <i class="fa-solid fa-photo-film fs-1 text-secondary opacity-50 mb-3 d-block"></i>
                            <h6 class="text-white font-heading">Belum Ada Dokumentasi Kegiatan</h6>
                            <p class="small text-secondary mb-3">Mulai abadikan momen kegiatan klub dengan menekan tombol Tambah Dokumentasi.</p>
                            <button type="button" class="btn btn-red btn-sm" data-bs-toggle="modal" data-bs-target="#addGalleryModal">
                                <i class="fa-solid fa-plus me-1"></i> Tambah Dokumentasi Sekarang
                            </button>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($albums as $album): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-2 overflow-hidden bg-secondary bg-opacity-25 flex-shrink-0" style="width: 60px; height: 46px; position: relative;">
                                        <?php if (!empty($album['cover_image'])): ?>
                                            <img src="<?= base_url($album['cover_image']) ?>" alt="Cover" class="w-100 h-100 object-fit-cover">
                                        <?php else: ?>
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-secondary">
                                                <i class="fa-solid fa-camera"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-white font-heading text-truncate" style="max-width: 240px;" title="<?= esc($album['title']) ?>">
                                            <?= esc($album['title']) ?>
                                        </div>
                                        <small class="text-secondary d-block text-truncate" style="max-width: 240px;">
                                            <?= esc(mb_strimwidth($album['description'] ?? 'Tidak ada deskripsi', 0, 50, '...')) ?>
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-25 text-white font-monospace px-2 py-1">
                                    <?= esc($album['category']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="text-light small font-monospace">
                                    <i class="fa-regular fa-calendar text-danger me-1"></i>
                                    <?= !empty($album['event_date']) ? date('d M Y', strtotime($album['event_date'])) : '-' ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1 flex-wrap">
                                    <span class="badge bg-dark border border-secondary border-opacity-25 text-white font-monospace">
                                        <i class="fa-solid fa-image text-success me-1"></i> <?= $album['photo_count'] ?> Foto
                                    </span>
                                    <?php if ($album['video_count'] > 0): ?>
                                        <span class="badge bg-dark border border-secondary border-opacity-25 text-info font-monospace">
                                            <i class="fa-solid fa-video me-1"></i> <?= $album['video_count'] ?> Video
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php if (!empty($album['external_link'])): ?>
                                    <a href="<?= esc($album['external_link']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-danger py-1 px-2 d-inline-flex align-items-center gap-1 font-monospace text-truncate" style="font-size: 0.75rem; max-width: 190px;" title="<?= esc($album['external_link_title'] ?: $album['external_link']) ?>">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        <span class="text-truncate"><?= esc($album['external_link_title'] ?: 'Buka Link') ?></span>
                                    </a>
                                <?php else: ?>
                                    <span class="text-secondary small font-monospace">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="text-secondary small">
                                    <?= esc($album['author_name'] ?? 'Admin') ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#editGalleryModal<?= $album['id'] ?>" title="Edit Dokumentasi">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </button>
                                    <a href="<?= base_url('admin/cms/gallery/delete/' . $album['id']) ?>" onclick="return confirm('Hapus dokumentasi ini beserta seluruh foto dan videonya?')" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Edit Galeri Kegiatan -->
                        <div class="modal fade" id="editGalleryModal<?= $album['id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
                                    <div class="modal-header border-bottom border-secondary border-opacity-25">
                                        <h5 class="modal-title font-heading fw-bold">
                                            <i class="fa-solid fa-pen text-info me-2"></i> Edit Dokumentasi Kegiatan
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="<?= base_url('admin/cms/gallery/update/' . $album['id']) ?>" method="POST" enctype="multipart/form-data">
                                        <?= csrf_field() ?>
                                        <div class="modal-body p-4">
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-8">
                                                    <label class="form-label text-secondary small fw-semibold">Judul Kegiatan / Album <span class="text-danger">*</span></label>
                                                    <input type="text" name="title" class="form-control bg-black text-white border-secondary" value="<?= esc($album['title']) ?>" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label text-secondary small fw-semibold">Kategori Kegiatan</label>
                                                    <select name="category" class="form-select bg-black text-white border-secondary">
                                                        <?php
                                                        $cats = ['Dokumentasi', 'Workshop', 'Liputan Acara', 'Latihan Rutin', 'Lomba & Ekskul', 'Lainnya'];
                                                        foreach ($cats as $c):
                                                        ?>
                                                            <option value="<?= $c ?>" <?= ($album['category'] === $c) ? 'selected' : '' ?>><?= $c ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row g-3 mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label text-secondary small fw-semibold">Tanggal Pelaksanaan</label>
                                                    <input type="date" name="event_date" class="form-control bg-black text-white border-secondary font-monospace" value="<?= esc($album['event_date']) ?>">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-secondary small fw-semibold">Ganti Foto Sampul / Cover (Opsional)</label>
                                                    <input type="file" name="cover_image_file" class="form-control bg-black text-white border-secondary" accept="image/*">
                                                    <?php if (!empty($album['cover_image'])): ?>
                                                        <div class="mt-2 d-flex align-items-center gap-2">
                                                            <img src="<?= base_url($album['cover_image']) ?>" alt="Current Cover" class="rounded border border-secondary" style="height: 38px; width: 60px; object-fit: cover;">
                                                            <span class="text-secondary style-tiny">Sampul saat ini</span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label text-secondary small fw-semibold">Deskripsi Kegiatan</label>
                                                <textarea name="description" rows="3" class="form-control bg-black text-white border-secondary" placeholder="Tuliskan cerita singkat, tujuan acara, atau catatan kegiatan..."><?= esc($album['description']) ?></textarea>
                                            </div>

                                            <div class="row g-3 mb-4">
                                                <div class="col-md-7">
                                                    <label class="form-label text-secondary small fw-semibold">
                                                        <i class="fa-solid fa-link text-danger me-1"></i> Link Eksternal (Opsional)
                                                    </label>
                                                    <input type="url" name="external_link" class="form-control bg-black text-white border-secondary" placeholder="https://drive.google.com/... atau https://youtu.be/..." value="<?= esc($album['external_link']) ?>">
                                                    <small class="text-secondary style-tiny">Tautan Google Drive full resolusi, video YouTube, atau tautan medsos.</small>
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="form-label text-secondary small fw-semibold">Judul / Label Tautan Eksternal</label>
                                                    <input type="text" name="external_link_title" class="form-control bg-black text-white border-secondary" placeholder="cth: Google Drive Dokumentasi" value="<?= esc($album['external_link_title']) ?>">
                                                </div>
                                            </div>

                                            <!-- Existing Media List Management -->
                                            <div class="mb-4">
                                                <label class="form-label text-white small fw-semibold d-flex align-items-center justify-content-between">
                                                    <span><i class="fa-solid fa-photo-film text-danger me-1"></i> Media Tersimpan Saat Ini (<?= count($album['media_list']) ?> file)</span>
                                                    <span class="text-secondary style-tiny">Klik tombol sampah untuk menghapus media individual</span>
                                                </label>
                                                <?php if (empty($album['media_list'])): ?>
                                                    <div class="p-3 rounded bg-black border border-secondary border-opacity-25 text-center text-secondary small">
                                                        Belum ada file foto / video tersimpan pada kegiatan ini.
                                                    </div>
                                                <?php else: ?>
                                                    <div class="row g-2 p-2 rounded bg-black border border-secondary border-opacity-25" style="max-height: 240px; overflow-y: auto;">
                                                        <?php foreach ($album['media_list'] as $mIdx => $mItem): ?>
                                                            <div class="col-4 col-sm-3 col-md-2" id="media-item-<?= $album['id'] ?>-<?= $mIdx ?>">
                                                                <div class="position-relative rounded overflow-hidden border border-secondary border-opacity-50" style="height: 75px; background: #111;">
                                                                    <?php if (($mItem['type'] ?? 'image') === 'video'): ?>
                                                                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-info">
                                                                            <i class="fa-solid fa-film fs-5 mb-1"></i>
                                                                            <span style="font-size: 0.6rem;">VIDEO</span>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <img src="<?= base_url($mItem['url']) ?>" alt="Media" class="w-100 h-100 object-fit-cover">
                                                                    <?php endif; ?>
                                                                    <button type="button" 
                                                                            class="btn btn-danger btn-sm p-0 position-absolute top-0 end-0 rounded-0 rounded-bottom-start d-flex align-items-center justify-content-center btn-delete-single-media" 
                                                                            style="width: 22px; height: 22px;"
                                                                            data-album-id="<?= $album['id'] ?>"
                                                                            data-media-url="<?= esc($mItem['url']) ?>"
                                                                            data-target-id="media-item-<?= $album['id'] ?>-<?= $mIdx ?>"
                                                                            title="Hapus media ini">
                                                                        <i class="fa-solid fa-xmark style-tiny"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Additional Uploads -->
                                            <div class="p-3 rounded-3 bg-secondary bg-opacity-10 border border-secondary border-opacity-25">
                                                <label class="form-label text-white small fw-bold mb-1">
                                                    <i class="fa-solid fa-cloud-arrow-up text-success me-1"></i> Tambah Foto & Video Baru ke Kegiatan Ini
                                                </label>
                                                <input type="file" name="media_files[]" class="form-control bg-black text-white border-secondary" accept="image/*,video/*" multiple>
                                                <small class="text-secondary style-tiny d-block mt-1">
                                                    Pilih beberapa file sekaligus (foto JPG/PNG/WebP atau video MP4/WebM). File baru akan otomatis ditambahkan ke galeri.
                                                </small>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top border-secondary border-opacity-25">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-red px-4 fw-semibold">
                                                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Galeri Kegiatan Baru -->
<div class="modal fade" id="addGalleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25 shadow-lg">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title font-heading fw-bold">
                    <i class="fa-solid fa-camera-retro text-danger me-2"></i> Tambah Dokumentasi Kegiatan Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/cms/gallery/store') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label text-secondary small fw-semibold">Judul Kegiatan / Album <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control bg-black text-white border-secondary" placeholder="cth: Workshop Sinematografi & Lighting 2026" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-semibold">Kategori Kegiatan</label>
                            <select name="category" class="form-select bg-black text-white border-secondary">
                                <option value="Dokumentasi" selected>Dokumentasi</option>
                                <option value="Workshop">Workshop</option>
                                <option value="Liputan Acara">Liputan Acara</option>
                                <option value="Latihan Rutin">Latihan Rutin</option>
                                <option value="Lomba & Ekskul">Lomba & Ekskul</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Tanggal Pelaksanaan</label>
                            <input type="date" name="event_date" class="form-control bg-black text-white border-secondary font-monospace" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Foto Sampul Utama (Opsional)</label>
                            <input type="file" name="cover_image_file" class="form-control bg-black text-white border-secondary" accept="image/*">
                            <small class="text-secondary style-tiny">Jika tidak diisi, foto pertama akan otomatis dijadikan sampul.</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Deskripsi Kegiatan</label>
                        <textarea name="description" rows="3" class="form-control bg-black text-white border-secondary" placeholder="Jelaskan mengenai kegiatan ini, lokasi, materi yang dipelajari, dsb..."></textarea>
                    </div>

                    <!-- Upload Multiple Photos and Videos -->
                    <div class="p-3 rounded-3 bg-secondary bg-opacity-10 border border-secondary border-opacity-25 mb-3">
                        <label class="form-label text-white small fw-bold mb-1">
                            <i class="fa-solid fa-cloud-arrow-up text-danger me-1"></i> Upload Foto & Video Kegiatan <span class="text-danger">*</span>
                        </label>
                        <input type="file" name="media_files[]" class="form-control bg-black text-white border-secondary" accept="image/*,video/*" multiple required>
                        <div class="d-flex align-items-center gap-2 mt-2 text-secondary style-tiny">
                            <i class="fa-solid fa-circle-info text-info"></i>
                            <span>Bisa pilih banyak file sekaligus. Format didukung: Gambar (JPG, PNG, WebP) dan Video (MP4, WebM).</span>
                        </div>
                    </div>

                    <!-- External Link Section -->
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="form-label text-secondary small fw-semibold">
                                <i class="fa-solid fa-link text-danger me-1"></i> Tautan / Link Eksternal (Opsional)
                            </label>
                            <input type="url" name="external_link" class="form-control bg-black text-white border-secondary" placeholder="https://drive.google.com/... atau https://youtu.be/...">
                            <small class="text-secondary style-tiny">Bisa diisi link Google Drive album lengkap, link YouTube, atau sosmed.</small>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label text-secondary small fw-semibold">Label Tombol Link Eksternal</label>
                            <input type="text" name="external_link_title" class="form-control bg-black text-white border-secondary" placeholder="cth: Google Drive Full Dokumentasi">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-red px-4 fw-semibold">
                        <i class="fa-solid fa-upload me-1"></i> Terbitkan ke Galeri
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete individual media item handler
    document.querySelectorAll('.btn-delete-single-media').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const albumId = this.dataset.albumId;
            const mediaUrl = this.dataset.mediaUrl;
            const targetId = this.dataset.targetId;

            if (!confirm('Hapus file media ini dari galeri?')) {
                return;
            }

            const formData = new FormData();
            formData.append('media_url', mediaUrl);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch('<?= base_url('admin/cms/gallery/delete-media/') ?>' + albumId, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const el = document.getElementById(targetId);
                    if (el) el.remove();
                } else {
                    alert(data.message || 'Gagal menghapus file.');
                }
            })
            .catch(err => {
                alert('Terjadi kesalahan jaringan.');
            });
        });
    });
});
</script>

<?= $this->endSection() ?>
