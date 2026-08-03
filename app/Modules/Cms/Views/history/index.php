<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Manajemen Sejarah Klub & Timeline</h4>
        <p class="text-secondary small m-0">Kelola narasi sejarah pendirian, Visi, Misi (repeatable items), dan garis waktu peristiwa per tahun</p>
    </div>
</div>

<div class="row g-4 justify-content-center">
    <!-- Left Column: History Narrative & Vision -->
    <div class="col-lg-6">
        <div class="saas-card p-4 h-100 border border-secondary border-opacity-25">
            <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-book-open text-danger me-2"></i> Narasi Sejarah & Visi</h5>
            <form action="<?= base_url('admin/cms/history/save') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label text-secondary small">Judul Halaman Sejarah</label>
                    <input type="text" name="title" class="form-control" value="<?= esc($history['title'] ?? 'Sejarah Perjalanan Multimedia Club SMAN 1 Tamansari') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small">Visi Klub</label>
                    <textarea name="vision" class="form-control" rows="3"><?= esc($history['vision'] ?? 'Menjadi wadah pengembangan bakat teknologi media terdepan yang menghasilkan karya berkualitas tinggi dan berdaya saing.') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small">Konten Narasi Pendirian Klub</label>
                    <textarea name="content" class="form-control" rows="6"><?= esc($history['content'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn btn-red px-4">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Sejarah & Visi
                </button>
            </form>

            <hr class="border-secondary border-opacity-25 my-4">

            <!-- Mission List (Repeatable Unlimited Items) -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="text-white font-heading m-0"><i class="fa-solid fa-bullseye text-warning me-2"></i> Misi Klub (Repeatable List)</h6>
                <button type="button" class="btn btn-sm btn-saas-dark" data-bs-toggle="modal" data-bs-target="#addMissionModal">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Misi
                </button>
            </div>

            <div class="d-flex flex-column gap-2">
                <?php foreach ($missions as $m): ?>
                    <div class="p-2.5 rounded-3 bg-dark border border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
                        <div class="text-white small"><i class="fa-solid fa-check text-success me-2"></i> <?= esc($m['mission_text']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Right Column: Historical Timelines -->
    <div class="col-lg-6">
        <div class="saas-card p-4 h-100 border border-secondary border-opacity-25">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="text-white font-heading m-0"><i class="fa-solid fa-timeline text-info me-2"></i> Timeline Sejarah Per Tahun</h5>
                <button type="button" class="btn btn-sm btn-red" data-bs-toggle="modal" data-bs-target="#addTimelineModal">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Event
                </button>
            </div>

            <div class="d-flex flex-column gap-2">
                <?php foreach ($timelines as $t): ?>
                    <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 d-flex align-items-start gap-3">
                        <span class="badge bg-danger font-monospace px-2.5 py-1 fs-6"><?= esc($t['year']) ?></span>
                        <div>
                            <div class="fw-bold text-white font-heading"><?= esc($t['title']) ?></div>
                            <p class="text-secondary style-tiny m-0"><?= esc($t['description']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Misi -->
<div class="modal fade" id="addMissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title font-heading"><i class="fa-solid fa-bullseye text-warning me-2"></i> Tambah Butir Misi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/cms/history/missions/save') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Pernyataan Misi <span class="text-danger">*</span></label>
                        <textarea name="mission_text" class="form-control" rows="3" placeholder="Contoh: Menyelenggarakan pelatihan rutin videografi dan desain grafis secara terstruktur..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25">
                    <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-red">Simpan Misi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Timeline -->
<div class="modal fade" id="addTimelineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title font-heading"><i class="fa-solid fa-timeline text-info me-2"></i> Tambah Timeline Peristiwa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/cms/history/timelines/save') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Tahun Peristiwa <span class="text-danger">*</span></label>
                        <input type="text" name="year" class="form-control font-monospace" placeholder="Contoh: 2017" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Judul Tonggak Sejarah <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: Pembentukan Resmi Ekstrakurikuler" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Deskripsi Peristiwa</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Penjelasan momen bersejarah..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25">
                    <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-red">Simpan Timeline</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
