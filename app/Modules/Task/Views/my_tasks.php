<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <h4 class="text-body font-heading m-0 fw-bold">Daftar Tugas Saya</h4>
        <p class="text-secondary small m-0">Tugas dan proyek kegiatan multimedia yang ditugaskan kepada Anda</p>
    </div>
</div>

<!-- Filter Bar Card -->
<div class="saas-card p-3 p-md-4 mb-4 border border-secondary border-opacity-25 shadow-sm">
    <form action="<?= base_url('member/tasks') ?>" method="GET" class="row g-2.5 align-items-center">
        <div class="col-12 col-md-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-body-secondary border-secondary border-opacity-50 text-secondary"><i class="fa-solid fa-search"></i></span>
                <input type="text" name="keyword" class="form-control form-control-sm bg-body-secondary text-body border-secondary border-opacity-50 style-tiny" placeholder="Cari judul tugas..." value="<?= esc($selectedFilter['keyword'] ?? '') ?>">
            </div>
        </div>

        <div class="col-6 col-md-3">
            <select name="status_id" class="form-select form-select-sm bg-body-secondary text-body border-secondary border-opacity-50 style-tiny">
                <option value="">-- Semua Status --</option>
                <?php foreach ($statuses as $st): ?>
                    <option value="<?= $st['id'] ?>" <?= (isset($selectedFilter['status_id']) && $selectedFilter['status_id'] == $st['id']) ? 'selected' : '' ?>>
                        Status: <?= esc($st['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-6 col-md-3">
            <select name="priority_id" class="form-select form-select-sm bg-body-secondary text-body border-secondary border-opacity-50 style-tiny">
                <option value="">-- Semua Prioritas --</option>
                <?php foreach ($priorities as $pr): ?>
                    <option value="<?= $pr['id'] ?>" <?= (isset($selectedFilter['priority_id']) && $selectedFilter['priority_id'] == $pr['id']) ? 'selected' : '' ?>>
                        Prioritas: <?= esc($pr['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-12 col-md-2 d-flex gap-1.5 justify-content-end">
            <button type="submit" class="btn btn-red btn-sm flex-grow-1 style-tiny font-monospace py-1.5 shadow-sm" title="Cari & Filter">
                <i class="fa-solid fa-filter me-1"></i> Filter
            </button>
            <?php if (!empty($selectedFilter['keyword']) || !empty($selectedFilter['status_id']) || !empty($selectedFilter['priority_id'])): ?>
                <a href="<?= base_url('member/tasks') ?>" class="btn btn-saas-dark btn-sm text-body border border-secondary border-opacity-25 style-tiny px-2.5" title="Reset">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Task Cards Grid -->
<?php if (empty($tasks)): ?>
    <div class="saas-card p-5 text-center border border-secondary border-opacity-25 shadow-sm rounded-4">
        <i class="fa-solid fa-clipboard-check display-1 mb-3 text-secondary opacity-50"></i>
        <h5 class="text-body font-heading fw-bold">Belum Ada Tugas Ditugaskan</h5>
        <p class="text-secondary small mb-0">Semua tugas Anda telah selesai atau belum ada penugasan baru dari Pembina / BPH.</p>
    </div>
<?php else: ?>
    <div class="row g-3 g-md-4">
        <?php foreach ($tasks as $t): ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="saas-card p-4 rounded-4 border border-secondary border-opacity-25 h-100 d-flex flex-column justify-content-between shadow-sm">
                    <div>
                        <!-- Header Status & Badges -->
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3.5">
                            <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                <span class="badge rounded-pill style-tiny font-monospace px-3 py-1" style="background-color: <?= $t['priority_color'] ?>;">
                                    <?= esc($t['priority_name']) ?>
                                </span>
                                <span class="badge rounded-pill style-tiny font-monospace px-3 py-1" style="background-color: <?= $t['my_status_color'] ?? '#6c757d' ?>;">
                                    <?= esc($t['my_status_name'] ?? 'Belum dikerjakan') ?>
                                </span>
                            </div>

                            <?php if (!empty($t['is_submitted'])): ?>
                                <span class="badge rounded-pill bg-success bg-opacity-25 text-success border border-success border-opacity-25 px-2.5 py-1 style-tiny font-monospace">
                                    <i class="fa-solid fa-circle-check me-1"></i> Terkirim
                                </span>
                            <?php else: ?>
                                <span class="badge rounded-pill bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 px-2.5 py-1 style-tiny font-monospace">
                                    <i class="fa-solid fa-circle-xmark me-1"></i> Belum Kirim
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Title & Description -->
                        <h5 class="text-body font-heading fw-bold fs-6 mb-2.5 lh-sm">
                            <a href="<?= base_url('member/tasks/submit/' . $t['id']) ?>" class="text-body text-decoration-none hover-text-danger d-inline-flex align-items-center" title="Buka Tugas: <?= esc($t['title']) ?>">
                                <span><?= esc($t['title']) ?></span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-danger opacity-75 ms-2" style="font-size: 0.72rem;"></i>
                            </a>
                        </h5>
                        
                        <?php $descLength = strlen($t['description']); ?>
                        <div class="text-secondary small lh-base mb-3" style="word-break: break-word;">
                            <?php if ($descLength > 120): ?>
                                <span><?= esc(substr($t['description'], 0, 120)) ?>...</span>
                                <div class="collapse mt-1" id="taskDescCollapse_<?= $t['id'] ?>">
                                    <?= nl2br(esc($t['description'])) ?>
                                </div>
                                <a class="text-danger text-decoration-none style-tiny fw-bold d-inline-block mt-1" data-bs-toggle="collapse" href="#taskDescCollapse_<?= $t['id'] ?>" role="button" aria-expanded="false" onclick="const isExp = this.getAttribute('aria-expanded') === 'true'; this.innerHTML = isExp ? 'Sembunyikan' : 'Selengkapnya';">
                                    Selengkapnya
                                </a>
                            <?php else: ?>
                                <?= nl2br(esc($t['description'])) ?>
                            <?php endif; ?>
                        </div>

                        <!-- Assignees List Section -->
                        <div class="mb-3.5 pt-2.5 border-top border-secondary border-opacity-15">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-secondary style-tiny font-monospace fw-semibold">
                                    <i class="fa-solid fa-users text-danger me-1"></i> Tim Ditugaskan:
                                </span>
                                <span class="badge bg-body-secondary border border-secondary border-opacity-25 text-secondary style-tiny font-monospace">
                                    <?= count($t['assignees'] ?? []) ?> Anggota
                                </span>
                            </div>
                            
                            <?php if (!empty($t['assignees'])): ?>
                                <div class="d-flex align-items-center flex-wrap gap-1.5">
                                    <?php 
                                        $maxDisplay = 4;
                                        $currentUserId = session()->get('user_id');
                                        $displayAssignees = array_slice($t['assignees'], 0, $maxDisplay);
                                        $remainingCount = count($t['assignees']) - $maxDisplay;
                                    ?>
                                    <?php foreach ($displayAssignees as $ass): ?>
                                        <?php $isMe = ($ass['id'] == $currentUserId); ?>
                                        <div class="d-inline-flex align-items-center gap-1.5 px-2 py-1 rounded-pill bg-body-secondary border <?= $isMe ? 'border-danger border-opacity-50 bg-danger bg-opacity-10' : 'border-secondary border-opacity-25' ?> style-tiny" title="<?= esc($ass['full_name']) ?> (Status: <?= esc($ass['status_name'] ?? 'Belum dikerjakan') ?>)">
                                            <?php if (!empty($ass['avatar'])): ?>
                                                <img src="<?= base_url($ass['avatar']) ?>" alt="Avatar" class="rounded-circle object-fit-cover" style="width: 18px; height: 18px;">
                                            <?php else: ?>
                                                <div class="rounded-circle bg-danger bg-opacity-25 text-danger d-flex align-items-center justify-content-center fw-bold" style="width: 18px; height: 18px; font-size: 9px;">
                                                    <?= strtoupper(substr($ass['full_name'], 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                            <span class="text-body text-truncate fw-medium" style="max-width: 90px; font-size: 11px;">
                                                <?= esc(explode(' ', trim($ass['full_name']))[0]) ?><?= $isMe ? ' <strong class="text-danger">(Saya)</strong>' : '' ?>
                                            </span>
                                            <span class="rounded-circle" style="width: 6px; height: 6px; background-color: <?= $ass['status_color'] ?? '#6c757d' ?>;" title="Status: <?= esc($ass['status_name'] ?? 'Belum dikerjakan') ?>"></span>
                                        </div>
                                    <?php endforeach; ?>

                                    <?php if ($remainingCount > 0): ?>
                                        <span class="badge rounded-pill bg-body-secondary border border-secondary border-opacity-25 text-secondary style-tiny px-2 py-1" title="<?= $remainingCount ?> anggota lainnya">
                                            +<?= $remainingCount ?> lainnya
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <span class="text-secondary style-tiny fst-italic">Belum ada anggota yang ditugaskan</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Footer: Deadline & Action Button -->
                    <div>
                        <div class="pt-3 border-top border-secondary border-opacity-15 mb-3 d-flex align-items-center justify-content-between">
                            <span class="text-danger font-monospace style-tiny">
                                <i class="fa-regular fa-clock me-1 text-danger"></i> <?= $t['deadline'] ? date('d M Y, H:i', strtotime($t['deadline'])) . ' WIB' : 'Tanpa Batas' ?>
                            </span>
                        </div>

                        <a href="<?= base_url('member/tasks/submit/' . $t['id']) ?>" class="btn <?= !empty($t['is_submitted']) ? 'btn-saas-dark text-success border-success border-opacity-50' : 'btn-red' ?> w-100 py-2.5 font-heading style-tiny fw-bold shadow-sm rounded-3">
                            <i class="fa-solid <?= !empty($t['is_submitted']) ? 'fa-pen-to-square' : 'fa-paper-plane' ?> me-1.5"></i> <?= !empty($t['is_submitted']) ? 'Lihat / Edit Pengiriman' : 'Kirim Tugas Sekarang' ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

