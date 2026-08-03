<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Manajemen Frequently Asked Questions (FAQ)</h4>
        <p class="text-secondary small m-0">Pengaturan daftar pertanyaan umum dan jawaban untuk ditampilkan secara dinamis di website publik</p>
    </div>

    <button type="button" class="btn btn-red" data-bs-toggle="modal" data-bs-target="#addFaqModal">
        <i class="fa-solid fa-plus me-1"></i> Tambah FAQ Baru
    </button>
</div>

<div class="saas-card p-4">
    <div class="table-responsive">
        <table class="table table-dark table-dark-saas align-middle datatable-saas">
            <thead>
                <tr>
                    <th style="width: 80px;">Urutan</th>
                    <th>Pertanyaan (Question)</th>
                    <th>Jawaban (Answer)</th>
                    <th style="width: 100px;">Status</th>
                    <th class="text-end" style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($faqs)): ?>
                    <?php foreach ($faqs as $fq): ?>
                        <tr>
                            <td>
                                <span class="badge bg-secondary font-monospace">#<?= esc($fq['sort_order']) ?></span>
                            </td>
                            <td>
                                <div class="fw-semibold text-white">
                                    <i class="fa-solid fa-circle-question text-danger me-1"></i> <?= esc($fq['question']) ?>
                                </div>
                            </td>
                            <td>
                                <div class="text-secondary small text-truncate" style="max-width: 350px;">
                                    <?= esc($fq['answer']) ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($fq['status'] === 'active'): ?>
                                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 font-monospace">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 font-monospace">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-info me-1" data-bs-toggle="modal" data-bs-target="#editFaqModal<?= $fq['id'] ?>">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </button>
                                <a href="<?= base_url('admin/cms/faqs/delete/' . $fq['id']) ?>" onclick="return confirm('Hapus FAQ ini?')" class="btn btn-sm btn-outline-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Modal Edit FAQ -->
                        <div class="modal fade" id="editFaqModal<?= $fq['id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
                                    <div class="modal-header border-bottom border-secondary border-opacity-25">
                                        <h5 class="modal-title font-heading"><i class="fa-solid fa-pen text-warning me-2"></i> Edit Pertanyaan & Jawaban FAQ</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="<?= base_url('admin/cms/faqs/update/' . $fq['id']) ?>" method="POST">
                                        <?= csrf_field() ?>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label text-secondary small">Pertanyaan <span class="text-danger">*</span></label>
                                                <input type="text" name="question" class="form-control" value="<?= esc($fq['question']) ?>" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label text-secondary small">Jawaban Lengkap <span class="text-danger">*</span></label>
                                                <textarea name="answer" class="form-control" rows="4" required><?= esc($fq['answer']) ?></textarea>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label text-secondary small">Nomor Urutan Tampil (Sort Order)</label>
                                                    <input type="number" name="sort_order" class="form-control" value="<?= esc($fq['sort_order']) ?>">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-secondary small">Status Penayangan</label>
                                                    <select name="status" class="form-select">
                                                        <option value="active" <?= $fq['status'] === 'active' ? 'selected' : '' ?>>Aktif (Tampilkan di Public)</option>
                                                        <option value="inactive" <?= $fq['status'] === 'inactive' ? 'selected' : '' ?>>Nonaktif (Sembunyikan)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top border-secondary border-opacity-25">
                                            <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-red">Update FAQ</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">Belum ada pertanyaan FAQ yang ditambahkan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah FAQ -->
<div class="modal fade" id="addFaqModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title font-heading"><i class="fa-solid fa-circle-question text-warning me-2"></i> Tambah Pertanyaan FAQ Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/cms/faqs/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Pertanyaan <span class="text-danger">*</span></label>
                        <input type="text" name="question" class="form-control" placeholder="Contoh: Kapan pendaftaran pendaftaran anggota baru dibuka?" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Jawaban Lengkap <span class="text-danger">*</span></label>
                        <textarea name="answer" class="form-control" rows="4" placeholder="Tuliskan jawaban yang rinci dan informatif..." required></textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-secondary small">Nomor Urutan Tampil (Sort Order)</label>
                            <input type="number" name="sort_order" class="form-control" value="1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small">Status Penayangan</label>
                            <select name="status" class="form-select">
                                <option value="active">Aktif (Tampilkan di Public)</option>
                                <option value="inactive">Nonaktif (Sembunyikan)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25">
                    <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-red">Simpan FAQ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
