<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="container-fluid p-0">

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show bg-success bg-opacity-25 border-success text-white style-tiny mb-3" role="alert">
            <i class="fa-solid fa-circle-check me-1.5"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close btn-close-white style-tiny" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show bg-danger bg-opacity-25 border-danger text-white style-tiny mb-3" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-1.5"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close btn-close-white style-tiny" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Header Banner -->
    <div class="saas-card mb-4 p-4 position-relative border border-secondary border-opacity-25 bg-body-tertiary">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 py-1 px-2.5 rounded-pill font-monospace mb-2">
                    <i class="fa-solid fa-user-shield me-1"></i> ADMIN MANAGEMENT
                </span>
                <h3 class="text-body font-heading fw-bold m-0 d-flex align-items-center gap-2">
                    Kelola Informasi Klub MMC
                </h3>
                <p class="text-secondary style-tiny m-0 mt-1 max-w-xl">
                    Buat, ubah, hapus, dan atur mode Popup pengumuman resmi untuk seluruh anggota klub MMC.
                </p>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="<?= base_url('informasi') ?>" class="btn btn-sm btn-saas-dark text-secondary border border-secondary border-opacity-25 px-3 rounded-pill">
                    <i class="fa-solid fa-eye me-1"></i> Tampilan Anggota
                </a>
                <a href="<?= base_url('admin/informasi/create') ?>" class="btn btn-sm btn-red px-3 rounded-pill">
                    <i class="fa-solid fa-plus me-1"></i> Buat Informasi Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Information Data Table -->
    <div class="saas-card p-4 border border-secondary border-opacity-25 bg-body-tertiary">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 style-tiny text-body">
                <thead>
                    <tr class="text-secondary border-bottom border-secondary border-opacity-25">
                        <th style="width: 50px;">#</th>
                        <th>Judul Informasi</th>
                        <th>Jenis / Kategori</th>
                        <th>Waktu / Tgl</th>
                        <th>Dibuat Oleh</th>
                        <th class="text-center" style="width: 130px;">Mode Popup</th>
                        <th class="text-center" style="width: 100px;">Status</th>
                        <th class="text-end" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($informations)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-secondary">
                                Belum ada data informasi dibuat. Klik tombol <strong>+ Buat Informasi Baru</strong> di atas.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($informations as $idx => $info): ?>
                            <tr>
                                <td class="text-secondary font-monospace"><?= $idx + 1 ?></td>
                                <td>
                                    <strong class="text-body d-block"><?= esc($info['title']) ?></strong>
                                    <small class="text-secondary line-clamp-1 opacity-75" style="max-width: 320px;"><?= esc($info['description']) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-25 text-body border border-secondary py-1 px-2 rounded-pill font-monospace">
                                        <?= esc($info['category']) ?>
                                    </span>
                                </td>
                                <td class="font-monospace text-secondary">
                                    <?= date('d M Y, H:i', strtotime($info['date_time'])) ?> WIB
                                </td>
                                <td>
                                    <span class="text-body font-weight-semibold"><?= esc($info['author_name']) ?></span>
                                    <small class="text-secondary d-block font-monospace" style="font-size: 0.68rem;"><?= esc($info['author_role']) ?></small>
                                </td>
                                <td class="text-center">
                                    <div class="form-check form-switch d-flex justify-content-center m-0">
                                        <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="toggle-popup-<?= $info['id'] ?>" <?= $info['is_popup'] ? 'checked' : '' ?> onchange="togglePopupStatus(<?= $info['id'] ?>, this)">
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php if ($info['status'] === 'active'): ?>
                                        <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 py-1 px-2.5 rounded-pill font-monospace">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-25 text-secondary border border-secondary py-1 px-2.5 rounded-pill font-monospace">Arsip</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-1">
                                        <a href="<?= base_url('admin/informasi/edit/' . $info['id']) ?>" class="btn btn-sm btn-saas-dark text-info border border-secondary border-opacity-25 rounded-circle p-1.5" title="Edit Informasi">
                                            <i class="fa-solid fa-pen-to-square style-tiny"></i>
                                        </a>
                                        <a href="<?= base_url('admin/informasi/delete/' . $info['id']) ?>" class="btn btn-sm btn-saas-dark text-danger border border-secondary border-opacity-25 rounded-circle p-1.5" onclick="return confirm('Apakah Anda yakin ingin menghapus informasi ini?');" title="Hapus Informasi">
                                            <i class="fa-solid fa-trash style-tiny"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function togglePopupStatus(infoId, switchEl) {
        if (!infoId) return;

        fetch('<?= base_url('admin/informasi/toggle-popup/') ?>' + infoId, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                switchEl.checked = (data.is_popup == 1);
            } else {
                switchEl.checked = !switchEl.checked;
                alert(data.message || 'Gagal mengubah mode popup.');
            }
        })
        .catch(() => {
            switchEl.checked = !switchEl.checked;
            alert('Terjadi kesalahan jaringan.');
        });
    }
</script>
<?= $this->endSection() ?>
