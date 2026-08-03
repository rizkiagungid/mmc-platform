<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Manajemen Prestasi & Tim Juara</h4>
        <p class="text-secondary small m-0">Pencatatan rekor kejuaraan lomba, penyelenggara, dan daftar anggota tim pemenang</p>
    </div>

    <button type="button" class="btn btn-red" data-bs-toggle="modal" data-bs-target="#addAchievementModal">
        <i class="fa-solid fa-plus me-1"></i> Tambah Prestasi Baru
    </button>
</div>

<div class="saas-card p-4">
    <div class="table-responsive">
        <table class="table table-dark table-dark-saas align-middle datatable-saas">
            <thead>
                <tr>
                    <th>Penghargaan & Karya</th>
                    <th>Kompetisi & Penyelenggara</th>
                    <th>Tingkat / Kategori</th>
                    <th>Anggota Tim Juara (Multi-Member Team)</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($achievements as $ach): ?>
                    <tr>
                        <td>
                            <span class="badge bg-warning text-dark font-monospace mb-1"><?= esc($ach['award']) ?></span>
                            <div class="fw-semibold text-white"><?= esc($ach['title']) ?></div>
                        </td>
                        <td>
                            <div class="text-white font-heading small"><?= esc($ach['competition']) ?></div>
                            <span class="text-secondary style-tiny"><?= esc($ach['organizer'] ?: 'Penyelenggara Umum') ?></span>
                        </td>
                        <td><span class="badge bg-secondary font-monospace"><?= esc($ach['category']) ?></span></td>
                        <td>
                            <?php if (empty($ach['team_members'])): ?>
                                <span class="text-secondary style-tiny fst-italic">Kategori Perorangan / Individu</span>
                            <?php else: ?>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php foreach ($ach['team_members'] as $tm): ?>
                                        <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 font-monospace">
                                            <i class="fa-solid fa-trophy me-1"></i> <?= esc($tm['full_name']) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-info me-1" data-bs-toggle="modal" data-bs-target="#editAchievementModal<?= $ach['id'] ?>">
                                <i class="fa-solid fa-pen"></i> Edit
                            </button>
                            <a href="<?= base_url('admin/cms/achievements/delete/' . $ach['id']) ?>" onclick="return confirm('Hapus prestasi ini?')" class="btn btn-sm btn-outline-danger">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- Modal Edit Prestasi -->
                    <div class="modal fade" id="editAchievementModal<?= $ach['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
                                <div class="modal-header border-bottom border-secondary border-opacity-25">
                                    <h5 class="modal-title font-heading"><i class="fa-solid fa-pen text-warning me-2"></i> Edit Rekor Prestasi Juara</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="<?= base_url('admin/cms/achievements/update/' . $ach['id']) ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <div class="modal-body">
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-8">
                                                <label class="form-label text-secondary small">Judul Karya / Lomba <span class="text-danger">*</span></label>
                                                <input type="text" name="title" class="form-control" value="<?= esc($ach['title']) ?>" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-secondary small">Penghargaan (Award)</label>
                                                <input type="text" name="award" class="form-control" value="<?= esc($ach['award']) ?>" required>
                                            </div>
                                        </div>

                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label text-secondary small">Nama Kompetisi <span class="text-danger">*</span></label>
                                                <input type="text" name="competition" class="form-control" value="<?= esc($ach['competition']) ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-secondary small">Penyelenggara Acara</label>
                                                <input type="text" name="organizer" class="form-control" value="<?= esc($ach['organizer']) ?>">
                                            </div>
                                        </div>

                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label text-secondary small">Tingkat Kompetisi</label>
                                                <select name="category" class="form-select">
                                                    <option value="Tingkat Kabupaten/Kota" <?= $ach['category'] === 'Tingkat Kabupaten/Kota' ? 'selected' : '' ?>>Tingkat Kabupaten/Kota</option>
                                                    <option value="Tingkat Provinsi" <?= $ach['category'] === 'Tingkat Provinsi' ? 'selected' : '' ?>>Tingkat Provinsi</option>
                                                    <option value="Tingkat Nasional" <?= $ach['category'] === 'Tingkat Nasional' ? 'selected' : '' ?>>Tingkat Nasional</option>
                                                    <option value="Tingkat Internasional" <?= $ach['category'] === 'Tingkat Internasional' ? 'selected' : '' ?>>Tingkat Internasional</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-secondary small">Tanggal Kemenangan</label>
                                                <input type="date" name="event_date" class="form-control" value="<?= esc($ach['event_date']) ?>">
                                            </div>
                                        </div>

                                        <!-- Multi-Member Team Selection -->
                                        <?php $activeTeamIds = array_column($ach['team_members'] ?? [], 'user_id'); ?>
                                        <div class="mb-3">
                                            <label class="form-label text-white small fw-bold"><i class="fa-solid fa-users-line text-warning me-1"></i> Anggota Tim Pemenang (Multi-Member Team):</label>
                                            <div class="p-3 rounded-3 bg-black border border-secondary border-opacity-25" style="max-height: 180px; overflow-y: auto;">
                                                <div class="row g-2">
                                                    <?php foreach ($members as $m): ?>
                                                        <div class="col-md-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="team_members[]" value="<?= $m['id'] ?>" id="edit_tm_<?= $ach['id'] ?>_<?= $m['id'] ?>" <?= in_array($m['id'], $activeTeamIds) ? 'checked' : '' ?>>
                                                                <label class="form-check-label text-secondary small" for="edit_tm_<?= $ach['id'] ?>_<?= $m['id'] ?>">
                                                                    <strong class="text-white"><?= esc($m['full_name']) ?></strong> (@<?= esc($m['username']) ?>)
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top border-secondary border-opacity-25">
                                        <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-red">Update Prestasi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Prestasi -->
<div class="modal fade" id="addAchievementModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title font-heading"><i class="fa-solid fa-trophy text-warning me-2"></i> Tambah Rekor Prestasi Juara</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/cms/achievements/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label text-secondary small">Judul Karya / Lomba <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="Contoh: Juara 1 Short Film Festival FLS2N" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-secondary small">Penghargaan (Award)</label>
                            <input type="text" name="award" class="form-control" value="Juara 1 / Emas" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-secondary small">Nama Kompetisi <span class="text-danger">*</span></label>
                            <input type="text" name="competition" class="form-control" placeholder="FLS2N Kabupaten Bogor 2026" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small">Penyelenggara Acara</label>
                            <input type="text" name="organizer" class="form-control" placeholder="Dinas Pendidikan / Kemendikbud">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-secondary small">Tingkat Kompetisi</label>
                            <select name="category" class="form-select">
                                <option value="Tingkat Kabupaten/Kota">Tingkat Kabupaten/Kota</option>
                                <option value="Tingkat Provinsi">Tingkat Provinsi</option>
                                <option value="Tingkat Nasional">Tingkat Nasional</option>
                                <option value="Tingkat Internasional">Tingkat Internasional</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small">Tanggal Kemenangan</label>
                            <input type="date" name="event_date" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>

                    <!-- Multi-Member Team Selection -->
                    <div class="mb-3">
                        <label class="form-label text-white small fw-bold"><i class="fa-solid fa-users-line text-warning me-1"></i> Anggota Tim Pemenang (Multi-Member Team):</label>
                        <div class="p-3 rounded-3 bg-black border border-secondary border-opacity-25" style="max-height: 180px; overflow-y: auto;">
                            <div class="row g-2">
                                <?php foreach ($members as $m): ?>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="team_members[]" value="<?= $m['id'] ?>" id="tm_<?= $m['id'] ?>">
                                            <label class="form-check-label text-secondary small" for="tm_<?= $m['id'] ?>">
                                                <strong class="text-white"><?= esc($m['full_name']) ?></strong> (@<?= esc($m['username']) ?>)
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25">
                    <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-red">Simpan Prestasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
