<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="container-fluid p-0">
    <div class="row g-3" style="min-height: calc(100vh - 120px);">

        <!-- Left Sidebar: Conversations List -->
        <div class="col-12 col-lg-4 col-xl-3 <?= ($activeConvId > 0) ? 'd-none d-lg-block' : 'd-block' ?>">
            <div class="saas-card h-100 d-flex flex-column p-0 overflow-hidden" style="max-height: calc(100vh - 120px);">
                
                <!-- Header Actions -->
                <div class="p-3 border-bottom border-secondary border-opacity-25 bg-dark bg-opacity-50">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h5 class="text-white font-heading m-0 fw-bold">
                            <i class="fa-solid fa-comments text-danger me-2"></i> Inbox Obrolan
                        </h5>
                        <button type="button" class="btn btn-sm btn-danger rounded-circle p-0 d-flex align-items-center justify-content-center shadow" style="width: 34px; height: 34px;" data-bs-toggle="modal" data-bs-target="#newChatModal" title="Buat Chat Baru">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-saas-dark w-50 style-tiny text-white border border-secondary border-opacity-25 py-1.5" data-bs-toggle="modal" data-bs-target="#newChatModal" data-bs-tab="#direct-tab">
                            <i class="fa-solid fa-user me-1 text-info"></i> Personal
                        </button>
                        <button type="button" class="btn btn-sm btn-saas-dark w-50 style-tiny text-white border border-secondary border-opacity-25 py-1.5" data-bs-toggle="modal" data-bs-target="#newChatModal" data-bs-tab="#group-tab">
                            <i class="fa-solid fa-users me-1 text-warning"></i> Buat Grup
                        </button>
                    </div>

                    <!-- Search Input -->
                    <div class="input-group input-group-sm mt-2">
                        <span class="input-group-text bg-black border-secondary border-opacity-25 text-secondary">
                            <i class="fa-solid fa-magnifying-glass style-tiny"></i>
                        </span>
                        <input type="text" id="chatSearchInput" class="form-control bg-black border-secondary border-opacity-25 text-white style-tiny" placeholder="Cari percakapan atau kontak...">
                    </div>
                </div>

                <!-- Conversation Items Scrollable Feed -->
                <div class="flex-grow-1 overflow-y-auto p-2" id="conversationListContainer">
                    <?php if (empty($conversations)): ?>
                        <div class="text-center py-5 text-secondary px-3">
                            <i class="fa-solid fa-comments-question fs-1 mb-2 opacity-50"></i>
                            <p class="style-tiny m-0">Belum ada obrolan. Klik <strong>+ Chat Baru</strong> untuk memulai percakapan.</p>
                        </div>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-1">
                            <?php foreach ($conversations as $c): ?>
                                <?php
                                    $isActive = ($activeConvId === (int)$c['id']);
                                    $itemClass = $isActive ? 'bg-danger bg-opacity-25 border border-danger border-opacity-50' : 'bg-dark bg-opacity-25 hover-bg-dark border border-transparent';
                                ?>
                                <a href="<?= base_url('inbox?conv=' . $c['id']) ?>" class="conv-item text-decoration-none p-2.5 rounded-3 <?= $itemClass ?> d-flex align-items-center gap-2.5 transition-all">
                                    <!-- Avatar -->
                                    <div class="position-relative flex-shrink-0">
                                        <?php if (!empty($c['display_avatar'])): ?>
                                            <img src="<?= base_url($c['display_avatar']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-secondary border-opacity-50" style="width: 42px; height: 42px;">
                                        <?php else: ?>
                                            <div class="rounded-circle <?= $c['type'] === 'group' ? 'bg-warning text-dark' : 'bg-danger text-white' ?> d-flex align-items-center justify-content-center fw-bold fs-6" style="width: 42px; height: 42px;">
                                                <?= $c['type'] === 'group' ? '<i class="fa-solid fa-users"></i>' : strtoupper(substr($c['display_name'], 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($c['type'] === 'group'): ?>
                                            <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-warning text-dark style-tiny py-0 px-1 border border-dark">Grup</span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-grow-1 text-truncate" style="min-width: 0;">
                                        <div class="d-flex align-items-center justify-content-between gap-1 mb-0.5">
                                            <h6 class="text-white style-tiny fw-bold m-0 text-truncate conv-name"><?= esc($c['display_name']) ?></h6>
                                            <small class="text-secondary style-tiny font-monospace opacity-75 flex-shrink-0" style="font-size: 0.65rem;">
                                                <?= date('H:i', strtotime($c['last_message_time'])) ?>
                                            </small>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between gap-1">
                                            <span class="text-secondary style-tiny text-truncate opacity-75 conv-last-msg" style="font-size: 0.75rem;">
                                                <?= esc($c['last_message']) ?>
                                            </span>
                                            <?php if (!empty($c['unread_count']) && $c['unread_count'] > 0): ?>
                                                <span class="badge rounded-pill bg-danger style-tiny flex-shrink-0 py-0.5 px-1.5"><?= $c['unread_count'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>

        <!-- Right Main Chat Workspace -->
        <div class="col-12 col-lg-8 col-xl-9 <?= ($activeConvId === 0) ? 'd-none d-lg-block' : 'd-block' ?>">
            <?php
                $activeConv = null;
                if (!empty($conversations)) {
                    foreach ($conversations as $convItem) {
                        if ((int)$convItem['id'] === $activeConvId) {
                            $activeConv = $convItem;
                            break;
                        }
                    }
                }
            ?>

            <?php if (!$activeConv): ?>
                <div class="saas-card h-100 d-flex flex-column align-items-center justify-content-center text-center p-5">
                    <div class="p-4 rounded-circle bg-dark border border-secondary border-opacity-25 mb-3">
                        <i class="fa-solid fa-comments display-3 text-secondary opacity-50"></i>
                    </div>
                    <h4 class="text-white font-heading">Selamat Datang di Inbox Obrolan MMC</h4>
                    <p class="text-secondary small max-w-md">Pilih percakapan di sebelah kiri atau buat obrolan baru untuk mulai berinteraksi dengan sesama anggota klub dan pengurus.</p>
                    <button type="button" class="btn btn-red px-4 mt-2" data-bs-toggle="modal" data-bs-target="#newChatModal">
                        <i class="fa-solid fa-paper-plane me-1"></i> Mulai Obrolan Baru
                    </button>
                </div>
            <?php else: ?>
                <div class="saas-card h-100 d-flex flex-column p-0 overflow-hidden" style="max-height: calc(100vh - 120px);">
                    
                    <!-- Active Chat Header Bar -->
                    <div class="p-3 border-bottom border-secondary border-opacity-25 bg-dark bg-opacity-75 d-flex align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-2.5">
                            <!-- Mobile Back Button to Conversation List -->
                            <a href="<?= base_url('inbox') ?>" class="btn btn-sm btn-saas-dark text-white rounded-circle p-0 d-flex align-items-center justify-content-center flex-shrink-0 d-lg-none" style="width: 36px; height: 36px;" title="Kembali ke Daftar Obrolan">
                                <i class="fa-solid fa-arrow-left"></i>
                            </a>

                            <?php if (!empty($activeConv['display_avatar'])): ?>
                                <img src="<?= base_url($activeConv['display_avatar']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-danger border-opacity-50 flex-shrink-0" style="width: 44px; height: 44px;">
                            <?php else: ?>
                                <div class="rounded-circle <?= $activeConv['type'] === 'group' ? 'bg-warning text-dark' : 'bg-danger text-white' ?> d-flex align-items-center justify-content-center fw-bold fs-5 flex-shrink-0" style="width: 44px; height: 44px;">
                                    <?= $activeConv['type'] === 'group' ? '<i class="fa-solid fa-users"></i>' : strtoupper(substr($activeConv['display_name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>

                            <div class="text-truncate">
                                <h6 class="text-white font-heading m-0 fw-bold d-flex align-items-center gap-2 text-truncate">
                                    <span class="text-truncate"><?= esc($activeConv['display_name']) ?></span>
                                    <?php if ($activeConv['type'] === 'group'): ?>
                                        <span class="badge bg-warning text-dark font-monospace style-tiny flex-shrink-0">Grup</span>
                                    <?php endif; ?>
                                </h6>
                                <small class="text-secondary style-tiny d-block opacity-75 text-truncate">
                                    <?= esc($activeConv['display_sub']) ?>
                                </small>
                            </div>
                        </div>

                        <!-- Header Action Buttons -->
                        <div class="d-flex align-items-center gap-2">
                            <?php if ($activeConv['type'] === 'group'): ?>
                                <button type="button" class="btn btn-sm btn-saas-dark text-warning border border-secondary border-opacity-25" data-bs-toggle="modal" data-bs-target="#groupInfoModal" title="Detail & Info Grup">
                                    <i class="fa-solid fa-circle-info me-1"></i> Info Grup
                                </button>
                                <a href="<?= base_url('inbox/leave-group/' . $activeConv['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin keluar dari grup obrolan ini?')" class="btn btn-sm btn-saas-dark text-warning border border-warning border-opacity-25" title="Keluar dari Grup Ini">
                                    <i class="fa-solid fa-right-from-bracket me-1"></i> Keluar
                                </a>
                            <?php endif; ?>

                            <?php if ($activeConv['type'] === 'direct' || ($activeConv['type'] === 'group' && !empty($activeConv['is_group_admin']))): ?>
                                <a href="<?= base_url('inbox/delete-conv/' . $activeConv['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus seluruh obrolan <?= $activeConv['type'] === 'group' ? 'grup' : '' ?> ini?')" class="btn btn-sm btn-saas-dark text-danger border border-danger border-opacity-25" title="Hapus Obrolan Ini">
                                    <i class="fa-solid fa-trash-can me-1"></i> Hapus Obrolan
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Messages Feed Container -->
                    <div class="flex-grow-1 overflow-y-auto p-3 p-md-4 d-flex flex-column gap-3" id="messagesFeedContainer" style="background-color: rgba(10, 10, 10, 0.4);">
                        <div class="text-center py-4 text-secondary style-tiny">
                            <span class="spinner-border spinner-border-sm me-1 text-danger"></span> Memuat pesan obrolan...
                        </div>
                    </div>

                    <!-- Message Input Form Bar -->
                    <div class="p-3 border-top border-secondary border-opacity-25 bg-dark">
                        <!-- Attachment Preview Box Indicator -->
                        <div id="attachmentPreviewBox" class="d-none mb-2.5 p-2.5 rounded-3 bg-black border border-warning border-opacity-50 align-items-center justify-content-between gap-3 shadow-lg">
                            <div class="d-flex align-items-center gap-3 overflow-hidden" style="min-width: 0;">
                                <div id="attachmentThumbnailWrapper" class="flex-shrink-0"></div>
                                <div class="text-truncate">
                                    <span class="badge bg-warning text-dark font-monospace style-tiny py-0.5 px-1.5 mb-1 d-inline-block">Lampiran Berkas / Gambar Siap Dikirim</span>
                                    <span id="attachmentFileName" class="text-white style-tiny fw-bold d-block text-truncate"></span>
                                    <small id="attachmentFileSize" class="text-secondary font-monospace style-tiny opacity-75 d-block" style="font-size: 0.65rem;"></small>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-saas-dark text-danger border border-danger border-opacity-25 p-1.5 rounded-circle flex-shrink-0 ms-auto" onclick="cancelAttachment()" title="Batal Lampiran File">
                                <i class="fa-solid fa-xmark fs-6"></i>
                            </button>
                        </div>

                        <form id="sendMessageForm" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
                            <input type="hidden" name="conversation_id" id="activeConvIdInput" value="<?= $activeConv['id'] ?>">
                            
                            <!-- File Attachment Button -->
                            <label class="btn btn-saas-dark border border-secondary border-opacity-25 text-secondary hover-white p-2.5 rounded-3 mb-0" style="cursor: pointer;" title="Lampirkan File / Gambar">
                                <i class="fa-solid fa-paperclip fs-6"></i>
                                <input type="file" name="attachment" id="attachmentInput" class="d-none" onchange="previewAttachment(this)">
                            </label>

                            <!-- Text Message Input -->
                            <input type="text" name="message" id="messageInput" class="form-control bg-black border-secondary border-opacity-50 text-white rounded-3 py-2 px-3" placeholder="Ketik pesan Anda di sini..." autocomplete="off">

                            <!-- Send Button -->
                            <button type="submit" class="btn btn-red px-3.5 rounded-3 d-flex align-items-center gap-1.5 flex-shrink-0" id="sendBtn">
                                <span>Kirim</span> <i class="fa-solid fa-paper-plane small"></i>
                            </button>
                        </form>
                    </div>

                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php if ($activeConv && $activeConv['type'] === 'group'): ?>
<!-- Modal: Group Info & Management -->
<div class="modal fade" id="groupInfoModal" tabindex="-1" aria-labelledby="groupInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-50">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title font-heading" id="groupInfoModalLabel">
                    <i class="fa-solid fa-users-gear text-warning me-2"></i> Informasi & Pengaturan Grup
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 p-md-4">
                
                <!-- Group Header Info -->
                <div class="text-center mb-4 p-3 rounded-3 bg-black bg-opacity-50 border border-secondary border-opacity-25">
                    <?php if (!empty($activeConv['display_avatar'])): ?>
                        <img src="<?= base_url($activeConv['display_avatar']) ?>" alt="Icon" class="rounded-circle object-fit-cover border border-warning shadow mb-2" style="width: 70px; height: 70px;">
                    <?php else: ?>
                        <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center fw-bold fs-2 mx-auto mb-2 shadow" style="width: 70px; height: 70px;">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    <?php endif; ?>

                    <h4 class="text-white font-heading m-0 fw-bold"><?= esc($activeConv['display_name']) ?></h4>
                    <p class="text-secondary style-tiny mt-1 mb-2 max-w-md mx-auto"><?= esc($activeConv['description'] ?: 'Tidak ada deskripsi grup.') ?></p>

                    <!-- Group Metadata Badge -->
                    <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap style-tiny font-monospace text-secondary pt-2 border-top border-secondary border-opacity-25">
                        <span><i class="fa-solid fa-crown text-warning me-1"></i> Dibuat oleh: <strong class="text-white"><?= esc($activeConv['creator_name']) ?></strong></span>
                        <span><i class="fa-solid fa-calendar-day text-info me-1"></i> Tanggal: <strong class="text-white"><?= esc($activeConv['created_at_formatted']) ?></strong></span>
                    </div>
                </div>

                <?php if (!empty($activeConv['is_group_admin'])): ?>
                <!-- Admin Tools Accordion -->
                <div class="accordion mb-4" id="groupAdminAccordion">
                    <!-- Edit Group Profile -->
                    <div class="accordion-item bg-black border border-secondary border-opacity-25 rounded-3 mb-2 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-black text-white style-tiny fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEditGroup">
                                <i class="fa-solid fa-pen-to-square text-warning me-2"></i> Edit Profil & Foto Grup
                            </button>
                        </h2>
                        <div id="collapseEditGroup" class="accordion-collapse collapse" data-bs-parent="#groupAdminAccordion">
                            <div class="accordion-body bg-dark">
                                <form action="<?= base_url('inbox/update-group/' . $activeConv['id']) ?>" method="POST" enctype="multipart/form-data">
                                    <?= csrf_field() ?>
                                    <div class="row g-2">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label text-secondary style-tiny font-monospace text-uppercase fw-semibold">Nama Grup Obrolan</label>
                                            <input type="text" name="group_name" class="form-control bg-black border-secondary text-white style-tiny" value="<?= esc($activeConv['name']) ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label text-secondary style-tiny font-monospace text-uppercase fw-semibold">Foto Profile Grup</label>
                                            <input type="file" name="group_icon" class="form-control bg-black border-secondary text-white style-tiny" accept="image/*">
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label class="form-label text-secondary style-tiny font-monospace text-uppercase fw-semibold">Deskripsi Grup</label>
                                            <textarea name="group_description" class="form-control bg-black border-secondary text-white style-tiny" rows="2"><?= esc($activeConv['description']) ?></textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-warning fw-bold px-3 style-tiny mt-1">
                                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan Grup
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Add New Members -->
                    <div class="accordion-item bg-black border border-secondary border-opacity-25 rounded-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-black text-white style-tiny fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAddMembers">
                                <i class="fa-solid fa-user-plus text-info me-2"></i> Tambah Anggota Baru ke Grup
                            </button>
                        </h2>
                        <div id="collapseAddMembers" class="accordion-collapse collapse" data-bs-parent="#groupAdminAccordion">
                            <div class="accordion-body bg-dark">
                                <form action="<?= base_url('inbox/group-add-members/' . $activeConv['id']) ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <?php
                                        $existingPartIds = array_column($activeConv['participants'], 'id');
                                        $nonMembers = array_filter($members, function($m) use ($existingPartIds) {
                                            return !in_array((int)$m['id'], $existingPartIds);
                                        });
                                    ?>
                                    <?php if (empty($nonMembers)): ?>
                                        <div class="text-secondary style-tiny">Seluruh anggota club sudah berada di grup ini.</div>
                                    <?php else: ?>
                                        <!-- Search Bar for Adding New Members -->
                                        <div class="input-group input-group-sm mb-2">
                                            <span class="input-group-text bg-black border-secondary border-opacity-25 text-secondary">
                                                <i class="fa-solid fa-magnifying-glass style-tiny"></i>
                                            </span>
                                            <input type="text" id="addNewMemberSearchInput" class="form-control bg-black border-secondary border-opacity-25 text-white style-tiny" placeholder="Cari nama atau kelas anggota..." onkeyup="filterAddNewMembers()">
                                        </div>

                                        <div class="overflow-y-auto pe-1 mb-2" id="addNewMemberList" style="max-height: 180px;">
                                            <?php foreach ($nonMembers as $nm): ?>
                                                <label class="add-new-member-item d-flex align-items-center justify-content-between p-2 rounded-3 bg-black border border-secondary border-opacity-25 mb-1 cursor-pointer">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <input type="checkbox" name="member_ids[]" value="<?= $nm['id'] ?>" class="form-check-input text-danger me-1">
                                                        <div>
                                                            <span class="text-white style-tiny fw-semibold d-block member-name"><?= esc($nm['full_name']) ?></span>
                                                            <small class="text-secondary style-tiny member-info" style="font-size: 0.65rem;"><?= esc($nm['class_dept'] ?: $nm['role_name']) ?></small>
                                                        </div>
                                                    </div>
                                                    <span class="badge bg-secondary bg-opacity-25 text-secondary style-tiny"><?= esc($nm['role_name']) ?></span>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-info text-dark fw-bold px-3 style-tiny">
                                            <i class="fa-solid fa-user-check me-1"></i> Tambahkan ke Grup
                                        </button>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Group Members List -->
                <!-- Group Members List Header with Search Filter -->
                <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                    <h6 class="text-secondary style-tiny font-monospace text-uppercase fw-semibold m-0">Daftar Anggota Grup (<?= count($activeConv['participants']) ?>):</h6>
                    <div class="input-group input-group-sm" style="max-width: 220px;">
                        <span class="input-group-text bg-black border-secondary border-opacity-25 text-secondary">
                            <i class="fa-solid fa-magnifying-glass style-tiny"></i>
                        </span>
                        <input type="text" id="groupMemberSearchInput" class="form-control bg-black border-secondary border-opacity-25 text-white style-tiny" placeholder="Cari anggota grup..." onkeyup="filterGroupMembers()">
                    </div>
                </div>

                <div class="d-flex flex-column gap-2 overflow-y-auto pe-1" id="groupMemberListContainer" style="max-height: 250px;">
                    <?php foreach ($activeConv['participants'] as $p): ?>
                        <?php
                            $isCreator = ((int)$p['id'] === (int)$activeConv['created_by']);
                            $isGroupAdmin = ($p['group_role'] === 'admin' || $isCreator);
                            $pPhone = esc($p['phone'] ?? '-');
                            $pEmail = esc($p['email'] ?? '-');
                            $pClass = esc($p['class_dept'] ?? '-');
                            $pDiv   = esc($p['division'] ?? '-');
                            $pRole  = esc($p['role_name'] ?? '-');
                            $pAvatar = esc($p['avatar'] ?? '');
                        ?>
                        <div class="group-member-item d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-black bg-opacity-50 border border-secondary border-opacity-25 hover-border-danger transition-all">
                            <div class="d-flex align-items-center gap-2.5 cursor-pointer flex-grow-1" onclick="showChatUserProfileModal('<?= esc(addslashes($p['full_name'])) ?>', '<?= $pRole ?>', '<?= $pAvatar ?>', '<?= $pEmail ?>', '<?= $pPhone ?>', '<?= $pClass ?>', '<?= $pDiv ?>')" title="Klik untuk lihat detail profil anggota">
                                <?php if (!empty($p['avatar'])): ?>
                                    <img src="<?= base_url($p['avatar']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-secondary border-opacity-50" style="width: 38px; height: 38px;">
                                <?php else: ?>
                                    <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold style-tiny" style="width: 38px; height: 38px;">
                                        <?= strtoupper(substr($p['full_name'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <span class="text-white style-tiny fw-bold d-block member-name hover-text-danger"><?= esc($p['full_name']) ?> <i class="fa-solid fa-circle-info text-info style-tiny ms-1 opacity-75"></i></span>
                                    <small class="text-secondary style-tiny member-info" style="font-size: 0.65rem;"><?= esc($p['class_dept'] ?: $p['role_name']) ?></small>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <!-- Role Badges -->
                                <?php if ($isCreator): ?>
                                    <span class="badge bg-warning text-dark font-monospace style-tiny"><i class="fa-solid fa-crown me-1"></i> Pembuat Grup</span>
                                <?php elseif ($isGroupAdmin): ?>
                                    <span class="badge bg-info text-dark font-monospace style-tiny"><i class="fa-solid fa-shield me-1"></i> Admin Grup</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary bg-opacity-25 text-secondary font-monospace style-tiny">Anggota</span>
                                <?php endif; ?>

                                <!-- Group Admin Management Dropdown -->
                                <?php if (!empty($activeConv['is_group_admin']) && !$isCreator && (int)$p['id'] !== session()->get('user_id')): ?>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-saas-dark text-secondary p-1 rounded-circle" type="button" data-bs-toggle="dropdown">
                                            <i class="fa-solid fa-ellipsis-vertical style-tiny"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end style-tiny shadow-lg border border-secondary border-opacity-50">
                                            <li>
                                                <a class="dropdown-item py-1.5" href="<?= base_url('inbox/group-toggle-admin/' . $activeConv['id'] . '/' . $p['id']) ?>">
                                                    <i class="fa-solid fa-shield-halved me-1.5 text-warning"></i> <?= $p['group_role'] === 'admin' ? 'Hapus Status Admin' : 'Jadikan Admin Grup' ?>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item py-1.5 text-danger" href="<?= base_url('inbox/group-remove-member/' . $activeConv['id'] . '/' . $p['id']) ?>" onclick="return confirm('Keluarkan <?= esc($p['full_name']) ?> dari grup?')">
                                                    <i class="fa-solid fa-user-minus me-1.5"></i> Keluarkan Anggota
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer border-top border-secondary border-opacity-25 justify-content-between">
                <a href="<?= base_url('inbox/leave-group/' . $activeConv['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin keluar dari grup obrolan ini?')" class="btn btn-sm btn-outline-warning">
                    <i class="fa-solid fa-right-from-bracket me-1"></i> Keluar dari Grup
                </a>

                <?php if (!empty($activeConv['is_group_admin'])): ?>
                    <a href="<?= base_url('inbox/delete-conv/' . $activeConv['id']) ?>" onclick="return confirm('PERINGATAN: Menghapus grup akan menghapus seluruh data & pesan grup secara permanen untuk SELURUH ANGGOTA. Lanjutkan?')" class="btn btn-sm btn-danger">
                        <i class="fa-solid fa-trash-can me-1"></i> Hapus Grup Permanen
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Modal: User Profile Detail Popup -->
<div class="modal fade" id="userProfileDetailModal" tabindex="-1" aria-labelledby="userProfileDetailModalLabel" aria-hidden="true" style="z-index: 1075;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-50 shadow-lg">
            <div class="modal-header border-bottom border-secondary border-opacity-25 py-2.5">
                <h5 class="modal-title font-heading style-tiny fw-bold" id="userProfileDetailModalLabel">
                    <i class="fa-solid fa-id-card text-danger me-1.5"></i> Detail Profil Anggota
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="position-relative d-inline-block mb-3">
                    <img id="chatProfileModalAvatar" src="" alt="Avatar" class="rounded-circle object-fit-cover border border-danger border-opacity-50 shadow-lg" style="width: 84px; height: 84px; display: none;">
                    <div id="chatProfileModalAvatarFallback" class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold display-6 mx-auto shadow-lg" style="width: 84px; height: 84px;">U</div>
                </div>

                <h5 class="text-white font-heading m-0 fw-bold" id="chatProfileModalName">-</h5>
                <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 py-1 px-3 rounded-pill font-monospace style-tiny mt-1" id="chatProfileModalRole">-</span>

                <div class="saas-card bg-black p-3 text-start mt-3.5 border border-secondary border-opacity-25">
                    <div class="row g-2.5 style-tiny">
                        <div class="col-12 border-bottom border-secondary border-opacity-10 pb-2">
                            <span class="text-secondary font-monospace d-block text-uppercase" style="font-size: 0.65rem;">Nomor HP / WhatsApp</span>
                            <strong class="text-white" id="chatProfileModalPhone">-</strong>
                        </div>
                        <div class="col-12 border-bottom border-secondary border-opacity-10 pb-2">
                            <span class="text-secondary font-monospace d-block text-uppercase" style="font-size: 0.65rem;">Email Resmi</span>
                            <strong class="text-white" id="chatProfileModalEmail">-</strong>
                        </div>
                        <div class="col-12">
                            <span class="text-secondary font-monospace d-block text-uppercase" style="font-size: 0.65rem;">Kelas / Dept</span>
                            <strong class="text-white" id="chatProfileModalClass">-</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top border-secondary border-opacity-25 justify-content-between">
                <a id="chatProfileModalWaBtn" href="#" target="_blank" class="btn btn-sm btn-success fw-bold px-3">
                    <i class="fa-brands fa-whatsapp me-1"></i> Chat WhatsApp
                </a>
                <button type="button" class="btn btn-sm btn-saas-dark text-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: New Chat / New Group -->
<div class="modal fade" id="newChatModal" tabindex="-1" aria-labelledby="newChatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-50">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title font-heading" id="newChatModalLabel">
                    <i class="fa-solid fa-comments text-danger me-2"></i> Buat Obrolan Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                
                <!-- Segmented Pill Nav Tabs for Mobile -->
                <div class="px-3 pt-3">
                    <ul class="nav nav-pills nav-fill p-1 bg-black bg-opacity-75 rounded-3 border border-secondary border-opacity-25" id="chatTypeTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active text-white fw-bold style-tiny py-2 rounded-2" id="direct-tab" data-bs-toggle="tab" data-bs-target="#direct-tab-pane" type="button" role="tab">
                                <i class="fa-solid fa-user me-1 text-info"></i> Chat Personal
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-white fw-bold style-tiny py-2 rounded-2" id="group-tab" data-bs-toggle="tab" data-bs-target="#group-tab-pane" type="button" role="tab">
                                <i class="fa-solid fa-users me-1 text-warning"></i> Buat Grup Baru
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="tab-content p-3" id="chatTypeTabContent">
                    
                    <!-- Tab 1: Chat Personal -->
                    <div class="tab-pane fade show active" id="direct-tab-pane" role="tabpanel">
                        <form action="<?= base_url('inbox/start-direct') ?>" method="POST">
                            <?= csrf_field() ?>
                            <label class="form-label text-secondary style-tiny font-monospace text-uppercase fw-semibold">Pilih Anggota / Pengurus Untuk Diajak Chat:</label>
                            
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text bg-black border-secondary border-opacity-25 text-secondary">
                                    <i class="fa-solid fa-magnifying-glass style-tiny"></i>
                                </span>
                                <input type="text" id="memberSearchInput" class="form-control bg-black border-secondary border-opacity-25 text-white style-tiny" placeholder="Cari nama atau kelas anggota..." onkeyup="filterMemberSelect()">
                            </div>

                            <div class="overflow-y-auto pe-1" style="max-height: 280px;" id="memberSelectList">
                                <?php foreach ($members as $m): ?>
                                    <label class="d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-black bg-opacity-50 border border-secondary border-opacity-25 mb-2 hover-bg-dark cursor-pointer user-select-none transition-all member-select-item">
                                        <div class="d-flex align-items-center gap-2.5 overflow-hidden me-2" style="min-width: 0;">
                                            <input type="radio" name="target_user_id" value="<?= $m['id'] ?>" class="form-check-input text-danger me-1 flex-shrink-0" style="width: 1.2em; height: 1.2em; cursor: pointer;" required>
                                            <?php if ($m['avatar']): ?>
                                                <img src="<?= base_url($m['avatar']) ?>" alt="Avatar" class="rounded-circle object-fit-cover flex-shrink-0" style="width: 38px; height: 38px;">
                                            <?php else: ?>
                                                <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 38px; height: 38px;">
                                                    <?= strtoupper(substr($m['full_name'], 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="text-truncate">
                                                <h6 class="text-white style-tiny fw-bold m-0 text-truncate member-name"><?= esc($m['full_name']) ?></h6>
                                                <small class="text-secondary style-tiny text-truncate d-block" style="font-size: 0.7rem;"><?= esc($m['class_dept'] ?: $m['role_name']) ?></small>
                                            </div>
                                        </div>
                                        <span class="badge bg-secondary bg-opacity-25 text-secondary font-monospace style-tiny flex-shrink-0"><?= esc($m['role_name']) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>

                            <div class="mt-3 text-end">
                                <button type="submit" class="btn btn-red w-100 w-sm-auto px-4 py-2">
                                    <i class="fa-solid fa-comments me-1"></i> Mulai Chat
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tab 2: Buat Grup Baru -->
                    <div class="tab-pane fade" id="group-tab-pane" role="tabpanel">
                        <form action="<?= base_url('inbox/create-group') ?>" method="POST">
                            <?= csrf_field() ?>
                            
                            <div class="mb-3">
                                <label class="form-label text-secondary style-tiny font-monospace text-uppercase fw-semibold">Nama Grup Obrolan <span class="text-danger">*</span></label>
                                <input type="text" name="group_name" class="form-control bg-black border-secondary border-opacity-50 text-white style-tiny" placeholder="Contoh: Divisi Fotografi & Videografi" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-secondary style-tiny font-monospace text-uppercase fw-semibold">Deskripsi Singkat Grup</label>
                                <textarea name="group_description" class="form-control bg-black border-secondary border-opacity-50 text-white style-tiny" rows="2" placeholder="Tujuan atau topik diskusi grup ini..."></textarea>
                            </div>

                            <label class="form-label text-secondary style-tiny font-monospace text-uppercase fw-semibold">Pilih Anggota Grup (Centang Minimal 1):</label>
                            
                            <!-- Search Bar for Selecting Members to Create Group -->
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text bg-black border-secondary border-opacity-25 text-secondary">
                                    <i class="fa-solid fa-magnifying-glass style-tiny"></i>
                                </span>
                                <input type="text" id="createGroupMemberSearchInput" class="form-control bg-black border-secondary border-opacity-25 text-white style-tiny" placeholder="Cari nama atau kelas anggota..." onkeyup="filterCreateGroupMembers()">
                            </div>

                            <div class="overflow-y-auto pe-1 mb-3" id="createGroupMemberList" style="max-height: 260px;">
                                <?php foreach ($members as $m): ?>
                                    <label class="create-group-member-item d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-black bg-opacity-50 border border-secondary border-opacity-25 mb-1.5 hover-bg-dark cursor-pointer user-select-none transition-all">
                                        <div class="d-flex align-items-center gap-2.5 overflow-hidden me-2" style="min-width: 0;">
                                            <input type="checkbox" name="member_ids[]" value="<?= $m['id'] ?>" class="form-check-input text-danger me-1 flex-shrink-0" style="width: 1.2em; height: 1.2em; cursor: pointer;">
                                            <?php if ($m['avatar']): ?>
                                                <img src="<?= base_url($m['avatar']) ?>" alt="Avatar" class="rounded-circle object-fit-cover flex-shrink-0" style="width: 36px; height: 36px;">
                                            <?php else: ?>
                                                <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center fw-bold style-tiny flex-shrink-0" style="width: 36px; height: 36px;">
                                                    <?= strtoupper(substr($m['full_name'], 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="text-truncate">
                                                <span class="text-white style-tiny fw-semibold d-block text-truncate member-name"><?= esc($m['full_name']) ?></span>
                                                <small class="text-secondary style-tiny text-truncate d-block member-info" style="font-size: 0.7rem;"><?= esc($m['class_dept'] ?: $m['role_name']) ?></small>
                                            </div>
                                        </div>
                                        <span class="badge bg-secondary bg-opacity-25 text-secondary font-monospace style-tiny flex-shrink-0"><?= esc($m['role_name']) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-red w-100 w-sm-auto px-4 py-2">
                                    <i class="fa-solid fa-users-gear me-1"></i> Buat Grup Obrolan
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($activeConv && $activeConv['type'] === 'group'): ?>
<!-- Modal: Group Info -->
<div class="modal fade" id="groupInfoModal" tabindex="-1" aria-labelledby="groupInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-50">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title font-heading" id="groupInfoModalLabel">
                    <i class="fa-solid fa-users text-warning me-2"></i> Informasi Grup Obrolan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center fw-bold fs-3 mx-auto mb-2" style="width: 60px; height: 60px;">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <h5 class="text-white font-heading m-0 fw-bold"><?= esc($activeConv['display_name']) ?></h5>
                    <p class="text-secondary style-tiny m-0"><?= esc($activeConv['description'] ?: 'Tidak ada deskripsi grup.') ?></p>
                </div>

                <h6 class="text-secondary style-tiny font-monospace text-uppercase fw-semibold mb-2">Daftar Anggota Grup (<?= count($activeConv['participants']) ?>):</h6>
                <div class="d-flex flex-column gap-2 overflow-y-auto" style="max-height: 250px;">
                    <?php foreach ($activeConv['participants'] as $p): ?>
                        <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-black bg-opacity-50 border border-secondary border-opacity-25">
                            <div class="d-flex align-items-center gap-2.5">
                                <?php if (!empty($p['avatar'])): ?>
                                    <img src="<?= base_url($p['avatar']) ?>" alt="Avatar" class="rounded-circle object-fit-cover" style="width: 34px; height: 34px;">
                                <?php else: ?>
                                    <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold style-tiny" style="width: 34px; height: 34px;">
                                        <?= strtoupper(substr($p['full_name'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <span class="text-white style-tiny fw-bold d-block"><?= esc($p['full_name']) ?></span>
                                    <small class="text-secondary style-tiny" style="font-size: 0.65rem;"><?= esc($p['class_dept'] ?: $p['role_name']) ?></small>
                                </div>
                            </div>
                            <span class="badge bg-secondary bg-opacity-25 text-secondary font-monospace style-tiny"><?= esc($p['role_name']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
    const currentUserId = <?= session()->get('user_id') ?>;
    const activeConvId  = <?= $activeConvId ?>;
    let lastLoadedMessageCount = 0;

    // Live Search Conversation List Filter (Left Sidebar)
    function filterConversations() {
        const input = document.getElementById('chatSearchInput');
        if (!input) return;
        const val = input.value.toLowerCase().trim();

        document.querySelectorAll('#conversationListContainer .conv-item').forEach(item => {
            const name = item.querySelector('.conv-name')?.textContent.toLowerCase() || '';
            const msg  = item.querySelector('.conv-last-msg')?.textContent.toLowerCase() || '';
            if (name.includes(val) || msg.includes(val)) {
                item.style.setProperty('display', 'flex', 'important');
            } else {
                item.style.setProperty('display', 'none', 'important');
            }
        });
    }

    document.getElementById('chatSearchInput')?.addEventListener('keyup', filterConversations);
    document.getElementById('chatSearchInput')?.addEventListener('input', filterConversations);

    // Live Search Group Members Filter (In Group Info Modal)
    function filterGroupMembers() {
        const input = document.getElementById('groupMemberSearchInput');
        if (!input) return;
        const val = input.value.toLowerCase().trim();

        document.querySelectorAll('#groupMemberListContainer .group-member-item').forEach(item => {
            const name = item.querySelector('.member-name')?.textContent.toLowerCase() || '';
            const info = item.querySelector('.member-info')?.textContent.toLowerCase() || '';
            if (name.includes(val) || info.includes(val)) {
                item.style.setProperty('display', 'flex', 'important');
            } else {
                item.style.setProperty('display', 'none', 'important');
            }
        });
    }

    // Filter Member Select in New Chat Modal (1-on-1)
    function filterMemberSelect() {
        const val = (document.getElementById('memberSearchInput')?.value || '').toLowerCase().trim();
        document.querySelectorAll('.member-select-item').forEach(item => {
            const name = item.querySelector('.member-name')?.textContent.toLowerCase() || '';
            if (name.includes(val)) {
                item.style.setProperty('display', 'flex', 'important');
            } else {
                item.style.setProperty('display', 'none', 'important');
            }
        });
    }

    // Filter Member list when Creating New Group
    function filterCreateGroupMembers() {
        const val = (document.getElementById('createGroupMemberSearchInput')?.value || '').toLowerCase().trim();
        document.querySelectorAll('#createGroupMemberList .create-group-member-item').forEach(item => {
            const name = item.querySelector('.member-name')?.textContent.toLowerCase() || '';
            const info = item.querySelector('.member-info')?.textContent.toLowerCase() || '';
            if (name.includes(val) || info.includes(val)) {
                item.style.setProperty('display', 'flex', 'important');
            } else {
                item.style.setProperty('display', 'none', 'important');
            }
        });
    }

    // Filter Non-Member list when Adding Members to Existing Group
    function filterAddNewMembers() {
        const val = (document.getElementById('addNewMemberSearchInput')?.value || '').toLowerCase().trim();
        document.querySelectorAll('#addNewMemberList .add-new-member-item').forEach(item => {
            const name = item.querySelector('.member-name')?.textContent.toLowerCase() || '';
            const info = item.querySelector('.member-info')?.textContent.toLowerCase() || '';
            if (name.includes(val) || info.includes(val)) {
                item.style.setProperty('display', 'flex', 'important');
            } else {
                item.style.setProperty('display', 'none', 'important');
            }
        });
    }

    // Show User Profile Detail Modal Popup
    function showChatUserProfileModal(name, role, avatar, email, phone, classDept) {
        document.getElementById('chatProfileModalName').textContent = name || '-';
        document.getElementById('chatProfileModalRole').textContent = role || '-';
        document.getElementById('chatProfileModalEmail').textContent = (email && email !== 'null' && email !== '-') ? email : '-';
        document.getElementById('chatProfileModalPhone').textContent = (phone && phone !== 'null' && phone !== '-') ? phone : '-';
        document.getElementById('chatProfileModalClass').textContent = (classDept && classDept !== 'null' && classDept !== '-') ? classDept : '-';

        const avatarEl   = document.getElementById('chatProfileModalAvatar');
        const fallbackEl = document.getElementById('chatProfileModalAvatarFallback');

        const cleanAvatar = (avatar && typeof avatar === 'string') ? avatar.trim() : '';
        const hasAvatar   = (cleanAvatar !== '' && cleanAvatar !== 'null' && cleanAvatar !== 'undefined');

        if (hasAvatar) {
            const fullUrl = cleanAvatar.startsWith('http') ? cleanAvatar : '<?= base_url() ?>' + (cleanAvatar.startsWith('/') ? cleanAvatar.substring(1) : cleanAvatar);
            avatarEl.src = fullUrl;
            avatarEl.style.setProperty('display', 'block', 'important');
            fallbackEl.style.setProperty('display', 'none', 'important');
        } else {
            avatarEl.src = '';
            avatarEl.style.setProperty('display', 'none', 'important');
            fallbackEl.textContent = (name || 'U').trim().charAt(0).toUpperCase();
            fallbackEl.style.setProperty('display', 'flex', 'important');
        }

        const waBtn = document.getElementById('chatProfileModalWaBtn');
        if (phone && phone !== '-' && phone !== 'null' && phone !== 'undefined') {
            let cleanPhone = phone.replace(/[^0-9]/g, '');
            if (cleanPhone.startsWith('0')) cleanPhone = '62' + cleanPhone.substring(1);
            waBtn.href = 'https://wa.me/' + cleanPhone;
            waBtn.style.setProperty('display', 'inline-flex', 'important');
        } else {
            waBtn.style.setProperty('display', 'none', 'important');
        }

        const modal = new bootstrap.Modal(document.getElementById('userProfileDetailModal'));
        modal.show();
    }

    function isImageUrl(url) {
        if (!url) return false;
        return (/\.(gif|jpe?g|tiff?|png|webp|bmp)$/i).test(url);
    }

    // Attachment Preview in Input Bar
    function previewAttachment(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const previewBox = document.getElementById('attachmentPreviewBox');
            const nameEl     = document.getElementById('attachmentFileName');
            const sizeEl     = document.getElementById('attachmentFileSize');
            const thumbEl    = document.getElementById('attachmentThumbnailWrapper');

            nameEl.textContent = file.name;
            sizeEl.textContent = (file.size / 1024 > 1024) 
                ? (file.size / (1024 * 1024)).toFixed(1) + ' MB' 
                : (file.size / 1024).toFixed(0) + ' KB';

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    thumbEl.innerHTML = `<img src="${e.target.result}" class="rounded-2 object-fit-cover border border-secondary border-opacity-50 shadow-sm" style="width: 50px; height: 50px;" />`;
                };
                reader.readAsDataURL(file);
            } else {
                thumbEl.innerHTML = `<div class="rounded-2 bg-secondary bg-opacity-25 text-warning d-flex align-items-center justify-content-center fw-bold" style="width: 50px; height: 50px;"><i class="fa-solid fa-file-lines fs-4"></i></div>`;
            }

            previewBox.classList.remove('d-none');
            previewBox.classList.add('d-flex');
        }
    }

    function cancelAttachment() {
        const input = document.getElementById('attachmentInput');
        if (input) input.value = '';
        const previewBox = document.getElementById('attachmentPreviewBox');
        if (previewBox) {
            previewBox.classList.remove('d-flex');
            previewBox.classList.add('d-none');
        }
    }

    // Fetch Messages Feed
    function loadMessages() {
        if (!activeConvId) return;

        fetch('<?= base_url("inbox/messages/") ?>' + activeConvId)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    renderMessages(data.messages);
                }
            })
            .catch(err => console.error('Failed loading messages:', err));
    }

    function renderMessages(messages) {
        const feed = document.getElementById('messagesFeedContainer');
        if (!feed) return;

        if (messages.length === 0) {
            feed.innerHTML = `
                <div class="text-center py-5 text-secondary">
                    <i class="fa-solid fa-paper-plane display-4 mb-2 text-secondary opacity-50"></i>
                    <p class="small mb-0">Belum ada pesan dalam obrolan ini. Mulailah menyapa!</p>
                </div>
            `;
            return;
        }

        const shouldScrollBottom = (messages.length !== lastLoadedMessageCount);
        lastLoadedMessageCount = messages.length;

        let html = '';
        messages.forEach(m => {
            const isMe = (parseInt(m.sender_id) === currentUserId);
            const bubbleBg = isMe ? 'bg-danger text-white align-self-end' : 'bg-dark text-white align-self-start border border-secondary border-opacity-25';
            const alignClass = isMe ? 'align-items-end' : 'align-items-start';

            let attachmentHtml = '';
            if (m.attachment_url) {
                if (isImageUrl(m.attachment_url)) {
                    attachmentHtml = `
                        <div class="mt-1.5 mb-1">
                            <a href="${m.attachment_url}" target="_blank" class="d-block overflow-hidden rounded-3 border border-secondary border-opacity-50 position-relative group" style="max-width: 280px;">
                                <img src="${m.attachment_url}" class="w-100 object-fit-cover transition-all" style="max-height: 240px; display: block;" alt="Gambar Obrolan" />
                                <div class="position-absolute bottom-0 end-0 bg-dark bg-opacity-75 text-white style-tiny px-2 py-0.5 m-1 rounded font-monospace" style="font-size: 0.65rem;">
                                    <i class="fa-solid fa-expand me-1 text-warning"></i> Lihat Gambar
                                </div>
                            </a>
                        </div>
                    `;
                } else {
                    const fileName = m.attachment_url.split('/').pop();
                    attachmentHtml = `
                        <div class="mt-1.5 mb-1">
                            <a href="${m.attachment_url}" target="_blank" class="d-flex align-items-center gap-2 p-2 rounded-3 bg-black bg-opacity-75 border border-secondary border-opacity-50 text-decoration-none text-white hover-border-danger transition-all" style="max-width: 280px;">
                                <div class="p-2 rounded bg-danger bg-opacity-25 text-danger flex-shrink-0">
                                    <i class="fa-solid fa-file-arrow-down fs-5"></i>
                                </div>
                                <div class="text-truncate flex-grow-1" style="min-width: 0;">
                                    <span class="text-white style-tiny fw-bold text-truncate d-block">${fileName}</span>
                                    <small class="text-secondary style-tiny d-block" style="font-size: 0.65rem;">Klik untuk unduh berkas</small>
                                </div>
                            </a>
                        </div>
                    `;
                }
            }

            html += `
                <div class="d-flex flex-column ${alignClass} max-w-lg mb-1 position-relative group-msg" style="max-width: 78%;">
                    ${!isMe ? `<small class="text-secondary style-tiny mb-1 fw-bold font-monospace">${escapeHtml(m.sender_name)} <span class="badge bg-secondary bg-opacity-25 text-secondary py-0 px-1 font-monospace" style="font-size: 0.60rem;">${escapeHtml(m.sender_role || '')}</span></small>` : ''}
                    
                    <div class="p-3 rounded-4 ${bubbleBg} shadow-sm position-relative">
                        ${attachmentHtml}
                        ${m.message ? `<div class="style-tiny leading-relaxed text-wrap">${escapeHtml(m.message)}</div>` : ''}

                        <div class="d-flex align-items-center justify-content-between gap-2 mt-1.5 opacity-75">
                            <small class="style-tiny font-monospace" style="font-size: 0.65rem;">
                                ${formatTime(m.created_at)}
                            </small>
                            ${isMe ? '<i class="fa-solid fa-check-double text-info style-tiny" style="font-size: 0.65rem;"></i>' : ''}
                        </div>
                    </div>

                    <!-- Delete Message Button -->
                    ${isMe ? `
                        <a href="<?= base_url('inbox/delete-message/') ?>${m.id}" onclick="return confirm('Hapus pesan ini?')" class="text-secondary hover-danger style-tiny mt-0.5 me-1 text-decoration-none" title="Hapus Pesan">
                            <i class="fa-solid fa-trash-can" style="font-size: 0.65rem;"></i>
                        </a>
                    ` : ''}
                </div>
            `;
        });

        feed.innerHTML = html;

        if (shouldScrollBottom) {
            feed.scrollTop = feed.scrollHeight;
        }
    }

    // Send Message AJAX Form Handler
    document.getElementById('sendMessageForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const sendBtn  = document.getElementById('sendBtn');
        const msgInput = document.getElementById('messageInput');

        sendBtn.disabled = true;

        fetch('<?= base_url("inbox/send/") ?>' + activeConvId, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            sendBtn.disabled = false;
            if (data.status === 'success') {
                msgInput.value = '';
                cancelAttachment();
                loadMessages();
            } else {
                alert(data.message || 'Gagal mengirim pesan.');
            }
        })
        .catch(err => {
            sendBtn.disabled = false;
            console.error(err);
        });
    });

    function escapeHtml(text) {
        if (!text) return '';
        return text.replace(/&/g, "&amp;")
                   .replace(/</g, "&lt;")
                   .replace(/>/g, "&gt;")
                   .replace(/"/g, "&quot;")
                   .replace(/'/g, "&#039;");
    }

    function formatTime(dateStr) {
        if (!dateStr) return '';
        const d = new Date(dateStr);
        return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    // Handle tab switching trigger when opening modal via button data-bs-tab
    const newChatModal = document.getElementById('newChatModal');
    if (newChatModal) {
        newChatModal.addEventListener('show.bs.modal', function (e) {
            const triggerBtn = e.relatedTarget;
            if (triggerBtn && triggerBtn.getAttribute('data-bs-tab')) {
                const targetTabId = triggerBtn.getAttribute('data-bs-tab');
                const tabEl = document.querySelector(targetTabId);
                if (tabEl) {
                    const tab = new bootstrap.Tab(tabEl);
                    tab.show();
                }
            }
        });
    }

    // Initial load and live polling
    document.addEventListener('DOMContentLoaded', function() {
        if (activeConvId) {
            loadMessages();
            // Poll for new messages every 3 seconds
            setInterval(loadMessages, 3000);
        }
    });
</script>

<?= $this->endSection() ?>
