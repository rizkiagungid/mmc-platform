<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <h4 class="text-body font-heading m-0 fw-bold">Peninjauan &amp; Evaluasi Tugas</h4>
        <p class="text-secondary small m-0">Detail instruksi dan daftar jawaban tugas yang telah dikirimkan oleh anggota</p>
    </div>

    <div class="d-flex gap-2">
        <a href="<?= base_url('admin/tasks/edit/' . $task['id']) ?>" class="btn btn-outline-warning btn-sm style-tiny font-monospace">
            <i class="fa-solid fa-pen me-1"></i> Edit Tugas
        </a>
        <a href="<?= base_url('admin/tasks') ?>" class="btn btn-saas-dark btn-sm text-body border border-secondary border-opacity-25 style-tiny font-monospace">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Task Meta Card -->
    <div class="col-lg-8">
        <div class="saas-card p-4 h-100 border border-secondary border-opacity-25">
            <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                <span class="badge" style="background-color: <?= $task['priority_color'] ?>;">
                    Prioritas: <?= esc($task['priority_name']) ?>
                </span>
                <span class="text-secondary small font-monospace ms-auto">
                    <i class="fa-solid fa-clock text-danger me-1"></i> Deadline: <?= $task['deadline'] ? date('d M Y, H:i', strtotime($task['deadline'])) . ' WIB' : 'Tanpa Batas' ?>
                </span>
            </div>

            <h4 class="text-body font-heading fw-bold mb-3"><?= esc($task['title']) ?></h4>
            <div class="text-secondary small leading-relaxed mb-4" style="word-break: break-word;"><?= nl2br(esc($task['description'])) ?></div>

            <div class="border-top border-secondary border-opacity-25 pt-3">
                <label class="text-body small fw-semibold mb-2"><i class="fa-solid fa-users text-danger me-2"></i> Anggota Assignee Terdaftar (<?= count($task['assignees']) ?>):</label>
                <div class="d-flex flex-wrap gap-2" id="taskAssigneesGrid">
                    <?php foreach ($task['assignees'] as $idx => $a): ?>
                        <div class="p-2 rounded-3 bg-body-secondary border border-secondary border-opacity-25 d-flex align-items-center gap-2 <?= $idx >= 3 ? 'extra-task-assignee d-none' : '' ?>" 
                             style="cursor: pointer; transition: all 0.2s ease;"
                             onclick='showUserProfileModal(<?= json_encode([
                                 "id" => $a["id"],
                                 "full_name" => $a["full_name"],
                                 "username" => $a["username"] ?? "-",
                                 "nis_nip" => $a["nis_nip"] ?? "-",
                                 "class_dept" => $a["class_dept"] ?? "-",
                                 "email" => $a["email"] ?? "-",
                                 "phone" => $a["phone"] ?? "-",
                                 "role_name" => $a["role_name"] ?? "Anggota",
                                 "status" => $a["status"] ?? "active",
                                 "avatar" => $a["avatar"] ?? "",
                                 "member_uuid" => $a["member_uuid"] ?? ""
                             ], JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'
                             title="Klik untuk lihat detail profil <?= esc($a['full_name']) ?>">
                            <img src="<?= avatar_url($a['avatar'] ?? null, $a['full_name']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-danger border-opacity-50" style="width: 30px; height: 30px; min-width: 30px;" onerror="this.onerror=null; this.src='<?= base_url('media/avatar?name=' . urlencode($a['full_name'])) ?>';">
                            <div>
                                <div class="text-body small fw-semibold text-truncate" style="max-width: 140px;"><?= esc($a['full_name']) ?></div>
                                <div class="text-secondary style-tiny"><?= esc($a['class_dept'] ?: 'Siswa') ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if (count($task['assignees']) > 3): ?>
                    <button class="btn btn-sm btn-saas-dark style-tiny text-body border border-secondary border-opacity-25 mt-2.5 py-1 px-3" type="button" id="btnToggleAssignees" onclick="toggleTaskAssignees(this, <?= count($task['assignees']) - 3 ?>)">
                        <i class="fa-solid fa-chevron-down me-1"></i> Tampilkan <?= count($task['assignees']) - 3 ?> Anggota Lainnya
                    </button>
                    <script>
                        function toggleTaskAssignees(btn, remainingCount) {
                            const extraItems = document.querySelectorAll('.extra-task-assignee');
                            let isExpanded = false;
                            extraItems.forEach(el => {
                                el.classList.toggle('d-none');
                                if (!el.classList.contains('d-none')) {
                                    isExpanded = true;
                                }
                            });
                            btn.innerHTML = isExpanded ? '<i class="fa-solid fa-chevron-up me-1"></i> Sembunyikan' : `<i class="fa-solid fa-chevron-down me-1"></i> Tampilkan ${remainingCount} Anggota Lainnya`;
                        }
                    </script>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-lg-4">
        <div class="saas-card p-4 h-100 border border-secondary border-opacity-25 d-flex flex-column justify-content-between">
            <h6 class="text-body font-heading fw-bold mb-3"><i class="fa-solid fa-chart-pie text-danger me-2"></i> Progres Pengumpulan</h6>

            <?php
                $totalAssignees = count($task['assignees']);
                $totalSubmitted = count($submissions);
            ?>

            <div class="text-center py-3">
                <div class="display-3 font-heading fw-bold text-body mb-1"><?= $totalSubmitted ?> / <?= $totalAssignees ?></div>
                <div class="text-secondary small">Anggota telah mengumpulkan tugas</div>
            </div>

            <div class="p-3 rounded-3 bg-body-secondary border border-secondary border-opacity-25 text-secondary style-tiny">
                <i class="fa-solid fa-circle-info text-info me-1"></i> Klik tombol <strong>Cek Jawaban</strong> pada tabel di bawah untuk melihat rincian tugas dan memberikan nilai/evaluasi.
            </div>
        </div>
    </div>
</div>

<!-- Submissions List -->
<div class="saas-card p-4 border border-secondary border-opacity-25">
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <h5 class="text-body font-heading m-0 fw-bold"><i class="fa-solid fa-folder-open text-danger me-2"></i> Daftar Pengumpulan Tugas Anggota</h5>
        <span class="badge bg-body-secondary border border-secondary text-body font-monospace">
            <?= count($submissions) ?> Terkumpul
        </span>
    </div>

    <?php if (empty($submissions)): ?>
        <div class="text-center py-5 text-secondary">
            <i class="fa-solid fa-inbox display-1 mb-3 text-secondary opacity-50"></i>
            <h5 class="text-body font-heading fw-bold">Belum Ada Pengumpulan Tugas</h5>
            <p class="small mb-0">Anggota assignee belum mengirimkan jawaban atau berkas tugas.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-dark table-dark-saas w-100 align-middle">
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Nama Anggota</th>
                        <th>Waktu Pengiriman</th>
                        <th>Catatan / Tautan</th>
                        <th>Nilai</th>
                        <th>Status Review</th>
                        <th class="text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($submissions as $i => $sub): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <div class="fw-semibold text-body d-inline-flex align-items-center gap-1"
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
                                    <i class="fa-solid fa-user-circle text-danger me-1"></i>
                                    <span class="text-decoration-underline"><?= esc($sub['full_name']) ?></span>
                                </div>
                                <div class="text-secondary style-tiny font-monospace"><?= esc($sub['nis_nip'] ?: '-') ?></div>
                            </td>
                            <td class="font-monospace style-tiny"><?= date('d M Y, H:i', strtotime($sub['submitted_at'])) ?></td>
                            <td>
                                <div class="text-body small text-truncate" style="max-width: 220px;" title="<?= esc($sub['submission_text']) ?>">
                                    <?= !empty($sub['submission_text']) ? esc($sub['submission_text']) : '<span class="text-secondary italic">-</span>' ?>
                                </div>
                                <?php if (!empty($sub['attachment_url'])): ?>
                                    <a href="<?= media_url($sub['attachment_url']) ?>" target="_blank" class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-50 text-decoration-none mt-1 style-tiny font-monospace d-inline-flex align-items-center gap-1">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Lampiran
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?= $sub['grade'] !== null ? 'bg-success bg-opacity-25 text-success border border-success border-opacity-50' : 'bg-body-secondary text-secondary' ?> font-monospace style-tiny">
                                    <?= $sub['grade'] !== null ? esc($sub['grade']) . ' / 100' : 'Belum Dinilai' ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge" style="background-color: <?= $sub['status_color'] ?? '#6c757d' ?>;">
                                    <?= esc($sub['status_name'] ?? 'Review') ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/tasks/submission/' . $sub['id']) ?>" class="btn btn-sm btn-red font-monospace style-tiny fw-bold text-nowrap shadow-sm">
                                    <i class="fa-solid fa-file-signature me-1"></i> Cek Jawaban
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- Diskusi & Catatan Revisi Tugas (WhatsApp-style Chat Feed) -->
    <div class="saas-card p-4 mt-4 border border-secondary border-opacity-25">
        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom border-secondary border-opacity-25 pb-3">
            <h5 class="text-body font-heading m-0 fw-bold">
                <i class="fa-solid fa-comments text-danger me-2"></i> Diskusi &amp; Catatan Revisi Tugas
            </h5>
            <span class="badge bg-body-secondary border border-secondary text-body font-monospace">
                <?= count($comments ?? []) ?> Pesan
            </span>
        </div>

        <!-- Feed Messages Container -->
        <div class="d-flex flex-column gap-3 mb-4" style="max-height: 400px; overflow-y: auto;" id="comments-container">
            <?php if (empty($comments)): ?>
                <div class="text-center py-4 text-secondary small">
                    <i class="fa-regular fa-comment-dots fs-3 d-block mb-2 text-opacity-50"></i>
                    Belum ada diskusi atau catatan revisi pada tugas ini. Tulis pesan pertama di bawah ini!
                </div>
            <?php else: ?>
                <?php foreach ($comments as $c): ?>
                    <div class="p-3 rounded-3 bg-body-secondary border border-secondary border-opacity-25">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-danger bg-opacity-25 text-danger fw-bold d-flex align-items-center justify-content-center style-tiny" style="width: 28px; height: 28px;">
                                    <?= strtoupper(substr($c['full_name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <span class="text-body small fw-bold"><?= esc($c['full_name']) ?></span>
                                    <span class="badge bg-body border border-secondary text-secondary style-tiny ms-1"><?= esc($c['role_name'] ?: 'Member') ?></span>
                                </div>
                            </div>
                            <span class="text-secondary font-monospace style-tiny">
                                <?= date('d M Y, H:i', strtotime($c['created_at'])) ?>
                            </span>
                        </div>

                        <div class="text-body small leading-relaxed ps-1">
                            <?= nl2br(esc($c['comment'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- WhatsApp-Style Message Input Bar -->
        <form action="<?= base_url('admin/tasks/comment/' . $task['id']) ?>" method="POST" id="comment-form">
            <?= csrf_field() ?>

            <div class="p-1.5 px-3 rounded-pill bg-body-secondary border border-secondary border-opacity-50 d-flex align-items-center gap-2 shadow-sm">
                <input type="text" name="comment" id="comment-input" class="form-control bg-transparent border-0 text-body p-1 style-tiny shadow-none" placeholder="Ketik pesan..." required autocomplete="off">
                <button type="submit" class="btn btn-red rounded-circle d-flex align-items-center justify-content-center p-0 flex-shrink-0" style="width: 38px; height: 38px;" title="Kirim">
                    <i class="fa-solid fa-paper-plane text-white style-tiny"></i>
                </button>
            </div>
        </form>
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
                    <!-- Avatar image or initial badge populated by JS -->
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

<?= $this->section('scripts') ?>
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

document.querySelectorAll('.tag-user-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const username = this.getAttribute('data-username');
        const textarea = document.getElementById('comment-textarea');
        textarea.value += ' @' + username + ' ';
        textarea.focus();
    });
});
</script>
<?= $this->endSection() ?>
