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

    <!-- Header Banner -->
    <div class="saas-card mb-4 p-4 position-relative border border-secondary border-opacity-25 bg-body-tertiary">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 py-1 px-2.5 rounded-pill font-monospace mb-2">
                    <i class="fa-solid fa-bullhorn me-1"></i> PENGUMUMAN & INFORMASI
                </span>
                <h3 class="text-body font-heading fw-bold m-0 d-flex align-items-center gap-2">
                    Pusat Informasi MMC
                </h3>
                <p class="text-secondary style-tiny m-0 mt-1 max-w-xl">
                    Informasi resmi seputar kegiatan ekstrakurikuler, pengumuman sekolah, divisi klub, dan pemeliharaan sistem.
                </p>
            </div>

            <?php if (in_array(strtolower((string)session()->get('role_slug')), ['superadmin', 'pembina', 'bph'])): ?>
                <a href="<?= base_url('admin/informasi') ?>" class="btn btn-sm btn-red px-3 rounded-pill">
                    <i class="fa-solid fa-sliders me-1"></i> Kelola Informasi Admin
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Category Filter Tabs -->
    <div class="d-flex align-items-center gap-2 flex-wrap mb-4 font-monospace">
        <?php
            $catIcons = [
                'all'                   => 'fa-list',
                'Informasi Ekskul'      => 'fa-users-gear',
                'Informasi Sekolah'     => 'fa-school',
                'Divisi Programming'    => 'fa-code',
                'Divisi Broadcasting'   => 'fa-tower-cell',
                'Informasi Maintenance' => 'fa-screwdriver-wrench'
            ];
            $categories = [
                'all'                   => 'Semua Informasi',
                'Informasi Ekskul'      => 'Ekskul MMC',
                'Informasi Sekolah'     => 'Sekolah',
                'Divisi Programming'    => 'Programming',
                'Divisi Broadcasting'   => 'Broadcasting',
                'Informasi Maintenance' => 'Maintenance'
            ];
        ?>

        <?php foreach ($categories as $catKey => $catLabel): ?>
            <?php
                $isActive = ($activeCategory === $catKey);
                $btnClass = $isActive ? 'btn-red' : 'btn-saas-dark text-secondary';
                $iconClass = $catIcons[$catKey] ?? 'fa-circle-info';
            ?>
            <a href="<?= base_url('informasi?category=' . urlencode($catKey)) ?>" class="btn btn-sm <?= $btnClass ?> px-3 rounded-pill style-tiny">
                <i class="fa-solid <?= $iconClass ?> me-1"></i> <?= esc($catLabel) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Information Cards Grid -->
    <?php if (empty($informations)): ?>
        <div class="saas-card p-5 text-center text-secondary border border-secondary border-opacity-25 bg-body-tertiary">
            <i class="fa-solid fa-folder-open fs-1 opacity-50 mb-3 text-danger"></i>
            <h5 class="text-body font-heading m-0 fw-bold">Belum Ada Informasi</h5>
            <p class="style-tiny text-secondary m-0 mt-1">Tidak ada pengumuman atau informasi untuk kategori yang Anda pilih.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($informations as $info): ?>
                <?php
                    // Category Badge Custom Styling
                    $badgeStyle = 'bg-secondary bg-opacity-25 text-secondary border-secondary';
                    $catIcon = 'fa-circle-info';
                    switch ($info['category']) {
                        case 'Informasi Ekskul':
                            $badgeStyle = 'bg-danger bg-opacity-25 text-danger border-danger';
                            $catIcon = 'fa-users-gear';
                            break;
                        case 'Informasi Sekolah':
                            $badgeStyle = 'bg-primary bg-opacity-25 text-primary border-primary';
                            $catIcon = 'fa-school';
                            break;
                        case 'Divisi Programming':
                            $badgeStyle = 'bg-info bg-opacity-25 text-info border-info';
                            $catIcon = 'fa-code';
                            break;
                        case 'Divisi Broadcasting':
                            $badgeStyle = 'bg-warning bg-opacity-25 text-warning border-warning';
                            $catIcon = 'fa-tower-cell';
                            break;
                        case 'Informasi Maintenance':
                            $badgeStyle = 'bg-secondary bg-opacity-25 text-light border-secondary';
                            $catIcon = 'fa-screwdriver-wrench';
                            break;
                    }
                    $isRead = !empty($info['is_read']);
                ?>
                <div class="col-12 col-md-6 col-lg-4" id="info-card-<?= $info['id'] ?>">
                    <div class="saas-card p-4 h-100 d-flex flex-column border border-secondary border-opacity-25 bg-body-tertiary transition-all position-relative">
                        
                        <!-- Top Metadata Row -->
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                            <span class="badge border py-1 px-2.5 rounded-pill font-monospace style-tiny <?= $badgeStyle ?>">
                                <i class="fa-solid <?= $catIcon ?> me-1"></i> <?= esc($info['category']) ?>
                            </span>

                            <?php if ($info['is_popup']): ?>
                                <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 py-1 px-2 rounded-pill font-monospace style-tiny" title="Muncul sebagai Popup">
                                    <i class="fa-solid fa-window-restore me-1"></i> Popup
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Title -->
                        <h5 class="text-body font-heading fw-bold mb-2 line-clamp-2">
                            <?= esc($info['title']) ?>
                        </h5>

                        <!-- Description Snippet -->
                        <p class="text-secondary style-tiny mb-4 flex-grow-1 line-clamp-3" style="font-size: 0.8rem; line-height: 1.5;">
                            <?= esc($info['description']) ?>
                        </p>

                        <!-- Footer Details -->
                        <div class="pt-3 border-top border-secondary border-opacity-10 mt-auto">
                            <div class="d-flex align-items-center justify-content-between text-secondary style-tiny mb-3 font-monospace" style="font-size: 0.7rem;">
                                <div>
                                    <i class="fa-solid fa-calendar-day text-danger me-1"></i>
                                    <?= date('d M Y, H:i', strtotime($info['date_time'])) ?> WIB
                                </div>
                                <div class="text-truncate ms-2" style="max-width: 140px;" title="Dibuat oleh <?= esc($info['author_name']) ?>">
                                    <i class="fa-solid fa-user-pen text-info me-1"></i>
                                    <?= esc($info['author_name']) ?>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between gap-2">
                                <button type="button" class="btn btn-sm btn-saas-dark text-body border border-secondary border-opacity-25 rounded-pill style-tiny px-3 flex-grow-1" onclick="openInformationDetailModal(<?= esc(json_encode($info)) ?>)">
                                    <i class="fa-solid fa-eye me-1 text-info"></i> Baca Detail
                                </button>

                                <?php if ($isRead): ?>
                                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 py-1.5 px-3 rounded-pill font-monospace style-tiny" id="read-badge-<?= $info['id'] ?>">
                                        <i class="fa-solid fa-circle-check me-1"></i> Sudah Dibaca
                                    </span>
                                <?php else: ?>
                                    <button type="button" class="btn btn-sm btn-outline-success rounded-pill style-tiny px-3 text-nowrap" id="mark-read-btn-<?= $info['id'] ?>" onclick="markInfoAsRead(<?= $info['id'] ?>, this)">
                                        <i class="fa-solid fa-check me-1"></i> Tandai Dibaca
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<!-- Modal: Detail Informasi -->
<div class="modal fade" id="informationDetailModal" tabindex="-1" aria-labelledby="informationDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-body text-body border border-secondary border-opacity-50 shadow-lg">
            <div class="modal-header border-bottom border-secondary border-opacity-25 py-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 py-1 px-2.5 rounded-pill font-monospace style-tiny" id="modalCategoryBadge">
                        Informasi
                    </span>
                    <h5 class="modal-title font-heading style-tiny fw-bold m-0" id="informationDetailModalTitle">
                        Detail Informasi
                    </h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <h4 class="text-body font-heading fw-bold mb-3" id="modalTitleText"></h4>

                <div class="d-flex align-items-center gap-3 text-secondary style-tiny font-monospace mb-4 pb-3 border-bottom border-secondary border-opacity-25 flex-wrap">
                    <div>
                        <i class="fa-solid fa-clock text-danger me-1"></i>
                        <span id="modalDateTimeText"></span>
                    </div>
                    <div>
                        <i class="fa-solid fa-user-gear text-info me-1"></i>
                        <span id="modalAuthorText"></span>
                    </div>
                </div>

                <div class="text-body style-tiny leading-relaxed whitespace-pre-line" id="modalDescriptionText" style="font-size: 0.9rem; line-height: 1.7;"></div>
            </div>
            <div class="modal-footer border-top border-secondary border-opacity-25 py-2.5">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <span id="modalReadStatusBadge"></span>
                    <button type="button" class="btn btn-sm btn-saas-dark text-secondary border border-secondary border-opacity-25 rounded-pill px-4" data-bs-dismiss="modal">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function openInformationDetailModal(info) {
        if (!info) return;

        document.getElementById('modalTitleText').textContent = info.title || '';
        document.getElementById('modalCategoryBadge').textContent = info.category || 'Informasi';
        document.getElementById('modalDateTimeText').textContent = (info.date_time || '') + ' WIB';
        document.getElementById('modalAuthorText').textContent = (info.author_name || 'Admin') + (info.author_role ? ' (' + info.author_role + ')' : '');
        document.getElementById('modalDescriptionText').textContent = info.description || '';

        const readBadgeContainer = document.getElementById('modalReadStatusBadge');
        if (info.is_read) {
            readBadgeContainer.innerHTML = '<span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 py-1 px-2.5 rounded-pill font-monospace style-tiny"><i class="fa-solid fa-circle-check me-1"></i> Sudah Dibaca</span>';
        } else {
            readBadgeContainer.innerHTML = `<button type="button" class="btn btn-sm btn-success rounded-pill style-tiny px-3" onclick="markInfoAsRead(${info.id}, this, true)"><i class="fa-solid fa-check me-1"></i> Tandai Sudah Dibaca</button>`;
        }

        const modalEl = document.getElementById('informationDetailModal');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }

    function markInfoAsRead(infoId, btn, insideModal = false) {
        if (!infoId) return;
        if (btn) btn.disabled = true;

        fetch('<?= base_url('informasi/mark-read/') ?>' + infoId, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                const readBtn = document.getElementById('mark-read-btn-' + infoId);
                if (readBtn) {
                    readBtn.outerHTML = '<span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 py-1.5 px-3 rounded-pill font-monospace style-tiny"><i class="fa-solid fa-circle-check me-1"></i> Sudah Dibaca</span>';
                }

                if (insideModal) {
                    const modalReadBadge = document.getElementById('modalReadStatusBadge');
                    if (modalReadBadge) {
                        modalReadBadge.innerHTML = '<span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 py-1 px-2.5 rounded-pill font-monospace style-tiny"><i class="fa-solid fa-circle-check me-1"></i> Sudah Dibaca</span>';
                    }
                }
            } else {
                if (btn) btn.disabled = false;
                alert(data.message || 'Gagal menandai sudah dibaca.');
            }
        })
        .catch(() => {
            if (btn) btn.disabled = false;
            alert('Terjadi kesalahan jaringan.');
        });
    }
</script>
<?= $this->endSection() ?>
