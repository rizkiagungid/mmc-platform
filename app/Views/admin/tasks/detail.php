<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <a href="<?= base_url('admin/tasks') ?>" class="btn btn-sm btn-saas-dark mb-2">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Semua Tugas
        </a>
        <h3 class="text-white font-heading m-0"><?= esc($task['title']) ?></h3>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?= base_url('admin/tasks/edit/' . $task['id']) ?>" class="btn btn-sm btn-saas-dark">
            <i class="fa-solid fa-pen me-1"></i> Edit Detail
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Task Overview & Member Submissions -->
    <div class="col-lg-7">
        
        <!-- Status & Priority Banner -->
        <div class="saas-card p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge px-3 py-1.5 fs-6" style="background-color: <?= $task['priority_color'] ?>;">
                        Prioritas: <?= esc($task['priority_name']) ?>
                    </span>
                </div>

                <!-- Quick Status Change Form -->
                <form action="<?= base_url('admin/tasks/update-status/' . $task['id']) ?>" method="POST" class="d-flex align-items-center gap-2">
                    <?= csrf_field() ?>
                    <select name="status_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <?php foreach ($statuses as $st): ?>
                            <option value="<?= $st['id'] ?>" <?= $task['status_id'] == $st['id'] ? 'selected' : '' ?>>Ubah Status: <?= esc($st['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>

            <h5 class="text-white font-heading mb-2">Deskripsi & Instruksi</h5>
            <p class="text-secondary small leading-relaxed mb-4"><?= nl2br(esc($task['description'] ?: 'Tidak ada deskripsi rincian.')) ?></p>

            <div class="row g-2 text-secondary font-monospace small pt-3 border-top border-secondary border-opacity-25">
                <div class="col-6">Dibuat Oleh: <strong class="text-white"><?= esc($task['creator_name'] ?: 'Admin') ?></strong></div>
                <div class="col-6 text-end">Deadline: <strong class="text-danger"><?= $task['deadline'] ? date('d M Y, H:i', strtotime($task['deadline'])) : 'Tanpa Batas' ?></strong></div>
            </div>
        </div>

        <!-- Member Submissions Section -->
        <div class="saas-card p-4">
            <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-file-arrow-up text-danger me-2"></i> Hasil Pengumpulan Anggota</h5>

            <?php if (empty($submissions)): ?>
                <div class="text-center py-4 text-secondary small bg-dark rounded-3 border border-secondary border-opacity-25">
                    Belum ada anggota yang mengumpulkan berkas/tautan untuk tugas ini.
                </div>
            <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($submissions as $sub): ?>
                        <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2" 
                                     style="cursor: pointer;"
                                     onclick='showUserProfileModal(<?= json_encode([
                                         "id" => $sub["user_id"] ?? $sub["id"],
                                         "full_name" => $sub["full_name"],
                                         "username" => $sub["username"] ?? "-",
                                         "nis_nip" => $sub["nis_nip"] ?? "-",
                                         "class_dept" => $sub["class_dept"] ?? "-",
                                         "email" => $sub["email"] ?? "-",
                                         "phone" => $sub["phone"] ?? "-",
                                         "role_name" => $sub["role_name"] ?? "Anggota",
                                         "status" => $sub["status"] ?? "active",
                                         "avatar" => $sub["avatar"] ?? "",
                                         "member_uuid" => $sub["member_uuid"] ?? ""
                                     ], JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'
                                     title="Klik untuk lihat detail profil <?= esc($sub['full_name']) ?>">
                                    <?php if (!empty($sub['avatar'])): ?>
                                        <img src="<?= base_url($sub['avatar']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-danger border-opacity-50" style="width: 32px; height: 32px; min-width: 32px;">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-danger bg-opacity-25 text-danger fw-bold d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <?= strtoupper(substr($sub['full_name'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="fw-semibold text-white small text-decoration-underline"><?= esc($sub['full_name']) ?></div>
                                        <small class="text-secondary font-monospace" style="font-size: 0.7rem;"><?= date('H:i:s, d M Y', strtotime($sub['submitted_at'])) ?></small>
                                    </div>
                                </div>
                                <div>
                                    <?php if ($sub['status_name']): ?>
                                        <span class="badge" style="background-color: <?= $sub['status_color'] ?>;"><?= esc($sub['status_name']) ?></span>
                                    <?php endif; ?>
                                    <?php if ($sub['grade']): ?>
                                        <span class="badge bg-success font-monospace ms-1">Nilai: <?= esc($sub['grade']) ?>/100</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <p class="text-secondary small mb-2"><?= esc($sub['submission_text'] ?: '-') ?></p>

                            <?php if ($sub['attachment_url']): ?>
                                <div class="mb-3">
                                    <a href="<?= esc($sub['attachment_url']) ?>" target="_blank" class="btn btn-sm btn-outline-danger font-monospace">
                                        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka Tautan Berkas
                                    </a>
                                </div>
                            <?php endif; ?>

                            <?php if ($sub['feedback']): ?>
                                <div class="p-2 rounded bg-black border border-secondary border-opacity-25 text-info small mb-2">
                                    <i class="fa-solid fa-comment-dots me-1"></i> <strong>Catatan Reviewer:</strong> <?= esc($sub['feedback']) ?>
                                </div>
                            <?php endif; ?>

                            <!-- Evaluation Button Modal Trigger -->
                            <button class="btn btn-sm btn-saas-dark" data-bs-toggle="modal" data-bs-target="#evalModal_<?= $sub['id'] ?>">
                                <i class="fa-solid fa-clipboard-check text-warning me-1"></i> Beri Evaluasi & Nilai
                            </button>

                            <!-- Modal Evaluation -->
                            <div class="modal fade" id="evalModal_<?= $sub['id'] ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content bg-dark border border-secondary text-white">
                                        <div class="modal-header border-secondary">
                                            <h5 class="modal-title font-heading"><i class="fa-solid fa-pen-nib text-danger me-2"></i> Evaluasi Pengumpulan - <?= esc($sub['full_name']) ?></h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                         <form action="<?= base_url('admin/tasks/evaluate-submission') ?>" method="POST">
                                             <?= csrf_field() ?>
                                             <input type="hidden" name="submission_id" value="<?= $sub['id'] ?>">
                                             <div class="modal-body">
                                                 <div class="mb-3">
                                                     <label class="form-label text-secondary small fw-medium">Status Hasil Peninjauan <span class="text-danger">*</span></label>
                                                     <select name="status_id" class="form-select bg-dark text-white border-secondary" required>
                                                         <option value="5" <?= ($sub['status_id'] ?? 5) == 5 ? 'selected' : '' ?>>Done (Disetujui & Lulus)</option>
                                                         <option value="4" <?= ($sub['status_id'] ?? 5) == 4 ? 'selected' : '' ?>>Revision (Minta Revisi)</option>
                                                         <option value="3" <?= ($sub['status_id'] ?? 5) == 3 ? 'selected' : '' ?>>Review (Peninjauan)</option>
                                                     </select>
                                                 </div>

                                                 <div class="mb-3">
                                                     <label class="form-label text-secondary small fw-medium">Nilai Angka (0 - 100) <span class="text-danger">*</span></label>
                                                     <input type="number" name="grade" class="form-control font-monospace" min="0" max="100" value="<?= $sub['grade'] !== null ? esc($sub['grade']) : 85 ?>" required>
                                                 </div>

                                                 <div class="mb-3">
                                                     <label class="form-label text-secondary small fw-medium">Feedback & Catatan Revisi (Opsional)</label>
                                                     <textarea name="feedback" class="form-control" rows="3" placeholder="Masukkan masukan revisi atau apresiasi..."><?= esc($sub['feedback']) ?></textarea>
                                                 </div>
                                             </div>
                                             <div class="modal-footer border-secondary">
                                                 <button type="button" class="btn btn-saas-dark" data-bs-dismiss="modal">Batal</button>
                                                 <button type="submit" class="btn btn-red px-4">Simpan Evaluasi</button>
                                             </div>
                                         </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right Column: ClickUp Timeline & Assignees Sidebar -->
    <div class="col-lg-5">
        <!-- Assigned Members Card -->
        <div class="saas-card p-4 mb-4">
            <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-users text-danger me-2"></i> Anggota Ditugaskan</h5>
            <div class="d-flex flex-column gap-2">
                <?php if (empty($task['assignees'])): ?>
                    <span class="text-secondary small">Belum ada anggota ditugaskan</span>
                <?php else: ?>
                    <?php foreach ($task['assignees'] as $m): ?>
                        <div class="d-flex align-items-center gap-2 p-2 rounded bg-dark border border-secondary border-opacity-25"
                             style="cursor: pointer;"
                             onclick='showUserProfileModal(<?= json_encode([
                                 "id" => $m["id"],
                                 "full_name" => $m["full_name"],
                                 "username" => $m["username"] ?? "-",
                                 "nis_nip" => $m["nis_nip"] ?? "-",
                                 "class_dept" => $m["class_dept"] ?? "-",
                                 "email" => $m["email"] ?? "-",
                                 "phone" => $m["phone"] ?? "-",
                                 "role_name" => $m["role_name"] ?? "Anggota",
                                 "status" => $m["status"] ?? "active",
                                 "avatar" => $m["avatar"] ?? "",
                                 "member_uuid" => $m["member_uuid"] ?? ""
                             ], JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'
                             title="Klik untuk lihat detail profil <?= esc($m['full_name']) ?>">
                            <?php if (!empty($m['avatar'])): ?>
                                <img src="<?= base_url($m['avatar']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-danger border-opacity-50" style="width: 32px; height: 32px; min-width: 32px;">
                            <?php else: ?>
                                <div class="rounded-circle bg-danger text-white fw-bold d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <?= strtoupper(substr($m['full_name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <div class="d-flex flex-column">
                                <span class="text-white fw-semibold small text-decoration-underline"><?= esc($m['full_name']) ?></span>
                                <small class="text-secondary font-monospace" style="font-size: 0.7rem;"><?= esc($m['class_dept'] ?: $m['username']) ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- ClickUp-Style Activity Timeline -->
        <div class="saas-card p-4">
            <h5 class="text-white font-heading mb-4"><i class="fa-solid fa-clock-rotate-left text-danger me-2"></i> Timeline & Activity History</h5>

            <div class="timeline-wrapper ms-2">
                <?php foreach ($activities as $act): ?>
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="fw-semibold text-white small"><?= esc($act['action']) ?></div>
                        <p class="text-secondary small mb-1"><?= esc($act['description']) ?></p>
                        <small class="text-muted font-monospace" style="font-size: 0.68rem;"><?= date('H:i, d M Y', strtotime($act['created_at'])) ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal User Profile Detail Popup -->
<div class="modal fade" id="userProfileDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25 shadow-lg">
            <div class="modal-header border-bottom border-secondary border-opacity-25 py-2.5">
                <h5 class="modal-title font-heading fs-6 d-flex align-items-center gap-2 m-0">
                    <i class="fa-solid fa-id-card text-danger"></i> Detail Profil Anggota
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div id="modalUserAvatarWrapper" class="mb-3 d-flex justify-content-center">
                    <!-- Populated by JS -->
                </div>
                <h5 id="modalUserFullName" class="text-white font-heading mb-1 fw-bold">Nama Anggota</h5>
                <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                    <span id="modalUserRole" class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 px-2.5 py-1 font-monospace style-tiny">Role</span>
                    <span id="modalUserStatus" class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 px-2.5 py-1 font-monospace style-tiny">AKTIF</span>
                </div>

                <div class="saas-card p-3 text-start mb-3 style-tiny">
                    <div class="row g-2">
                        <div class="col-6">
                            <span class="text-secondary d-block style-tiny">NIS / NIP</span>
                            <strong id="modalUserNisNip" class="text-white font-monospace style-tiny">-</strong>
                        </div>
                        <div class="col-6">
                            <span class="text-secondary d-block style-tiny">Username</span>
                            <strong id="modalUserUsername" class="text-white font-monospace style-tiny">-</strong>
                        </div>
                        <div class="col-12 mt-2 pt-2 border-top border-secondary border-opacity-10">
                            <span class="text-secondary d-block style-tiny">Kelas / Jurusan</span>
                            <strong id="modalUserClassDept" class="text-white style-tiny">-</strong>
                        </div>
                        <div class="col-12 mt-2 pt-2 border-top border-secondary border-opacity-10">
                            <span class="text-secondary d-block style-tiny">Email</span>
                            <a id="modalUserEmail" href="#" class="text-info style-tiny text-break">-</a>
                        </div>
                        <div class="col-12 mt-2 pt-2 border-top border-secondary border-opacity-10">
                            <span class="text-secondary d-block style-tiny">No. Telepon / WhatsApp</span>
                            <a id="modalUserPhone" href="#" target="_blank" class="text-success style-tiny">-</a>
                        </div>
                    </div>
                </div>

                <div id="modalUserQrWrapper">
                    <a id="modalUserQrBtn" href="#" class="btn btn-sm btn-saas-dark w-100" target="_blank">
                        <i class="fa-solid fa-qrcode text-warning me-1"></i> Tampilkan ID Card & Member QR
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<script>
function showUserProfileModal(user) {
    const avatarWrapper = document.getElementById('modalUserAvatarWrapper');
    if (user.avatar && user.avatar.trim() !== '') {
        const baseUrl = '<?= base_url() ?>';
        const imgUrl = user.avatar.startsWith('http') ? user.avatar : baseUrl + (user.avatar.startsWith('/') ? user.avatar.substring(1) : user.avatar);
        avatarWrapper.innerHTML = `<img src="${imgUrl}" alt="Avatar" class="rounded-circle object-fit-cover border border-danger border-opacity-50" style="width: 72px; height: 72px; min-width: 72px;">`;
    } else {
        const initial = user.full_name ? user.full_name.charAt(0).toUpperCase() : 'U';
        avatarWrapper.innerHTML = `<div class="rounded-circle bg-danger bg-opacity-25 text-danger fw-bold d-flex align-items-center justify-content-center border border-danger border-opacity-50 fs-3" style="width: 72px; height: 72px;">${initial}</div>`;
    }

    document.getElementById('modalUserFullName').textContent = user.full_name || 'Anggota';
    document.getElementById('modalUserRole').textContent = user.role_name || 'Anggota';
    
    const statusSpan = document.getElementById('modalUserStatus');
    const isStatusActive = (user.status === 'active' || user.status === 'aktif');
    statusSpan.textContent = isStatusActive ? 'AKTIF' : (user.status || 'NONAKTIF').toUpperCase();
    statusSpan.className = isStatusActive 
        ? 'badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 px-2.5 py-1 font-monospace style-tiny'
        : 'badge bg-secondary bg-opacity-25 text-secondary border border-secondary border-opacity-25 px-2.5 py-1 font-monospace style-tiny';

    document.getElementById('modalUserNisNip').textContent = user.nis_nip || '-';
    document.getElementById('modalUserUsername').textContent = user.username || '-';
    document.getElementById('modalUserClassDept').textContent = user.class_dept || '-';
    
    const emailElem = document.getElementById('modalUserEmail');
    if (user.email && user.email !== '-') {
        emailElem.textContent = user.email;
        emailElem.href = 'mailto:' + user.email;
    } else {
        emailElem.textContent = '-';
        emailElem.removeAttribute('href');
    }

    const phoneElem = document.getElementById('modalUserPhone');
    if (user.phone && user.phone !== '-') {
        const cleanPhone = user.phone.replace(/[^0-9]/g, '');
        const waPhone = cleanPhone.startsWith('0') ? '62' + cleanPhone.substring(1) : cleanPhone;
        phoneElem.innerHTML = `<i class="fa-brands fa-whatsapp text-success me-1"></i> ${user.phone}`;
        phoneElem.href = 'https://wa.me/' + waPhone;
    } else {
        phoneElem.textContent = '-';
        phoneElem.removeAttribute('href');
    }

    const qrBtn = document.getElementById('modalUserQrBtn');
    if (user.member_uuid && user.member_uuid !== '-') {
        qrBtn.href = '<?= base_url("admin/users/qr/") ?>' + user.member_uuid;
        qrBtn.style.display = 'inline-flex';
    } else {
        qrBtn.style.display = 'none';
    }

    const modal = new bootstrap.Modal(document.getElementById('userProfileDetailModal'));
    modal.show();
}
</script>
