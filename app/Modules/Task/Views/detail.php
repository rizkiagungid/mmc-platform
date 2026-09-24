<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<!-- Mobile Responsiveness & Scroll Styling -->
<style>
    .table-responsive {
        -webkit-overflow-scrolling: touch;
        overflow-x: auto;
    }
    .custom-scroll-container {
        -webkit-overflow-scrolling: touch;
        overscroll-behavior: contain;
        scrollbar-width: thin;
        scrollbar-color: rgba(239, 68, 68, 0.4) rgba(0, 0, 0, 0.2);
    }
    .custom-scroll-container::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scroll-container::-webkit-scrollbar-thumb {
        background: rgba(239, 68, 68, 0.4);
        border-radius: 4px;
    }
    #floatingBulkToolbar {
        animation: slideUpFloating 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 10px 25px -5px rgba(239, 68, 68, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.5);
    }
    @keyframes slideUpFloating {
        from {
            opacity: 0;
            transform: translate(-50%, 20px);
        }
        to {
            opacity: 1;
            transform: translate(-50%, 0);
        }
    }
    .assignee-check:checked {
        background-color: #ef4444;
        border-color: #ef4444;
    }
    @media (max-width: 767.98px) {
        .saas-card {
            padding: 1rem !important;
        }
        .header-actions {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            gap: 0.35rem;
        }
        .header-actions .btn {
            flex: 1 1 auto;
            text-align: center;
        }
        #floatingBulkToolbar {
            bottom: 12px !important;
            width: 94vw !important;
            justify-content: center;
        }
    }
</style>

<div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between flex-wrap gap-3 mb-4">
    <div>
        <h4 class="text-body font-heading m-0 fw-bold">Peninjauan &amp; Evaluasi Tugas</h4>
        <p class="text-secondary small m-0">Detail instruksi dan daftar jawaban tugas yang telah dikirimkan oleh anggota</p>
    </div>

    <div class="header-actions d-flex gap-2 flex-wrap w-100 w-sm-auto">
        <a href="<?= base_url('admin/tasks/duplicate/' . $task['id']) ?>" onclick="return confirm('Duplikasi tugas \'<?= esc(addslashes($task['title'])) ?>\' beserta seluruh penerima tugas?')" class="btn btn-outline-success btn-sm style-tiny font-monospace" title="Duplikasi / Copy Tugas">
            <i class="fa-solid fa-copy me-1"></i> Duplikasi
        </a>
        <a href="<?= base_url('admin/tasks/edit/' . $task['id']) ?>" class="btn btn-outline-warning btn-sm style-tiny font-monospace">
            <i class="fa-solid fa-pen me-1"></i> Edit
        </a>
        <a href="<?= base_url('admin/tasks') ?>" class="btn btn-saas-dark btn-sm text-body border border-secondary border-opacity-25 style-tiny font-monospace">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Task Meta Card -->
    <div class="col-lg-8">
        <?php
        $detailAssignees = $task['assignees'] ?? [];
        $detailAssigneesCount = count($detailAssignees);
        $detailCompletedCount = 0;
        if ($detailAssigneesCount > 0) {
            foreach ($detailAssignees as $da) {
                $dsId = (int)($da['status_id'] ?? 1);
                $dsName = strtolower(trim($da['status_name'] ?? ''));
                if ($dsId === 5 || $dsName === 'selesai') {
                    $detailCompletedCount++;
                }
            }
        }
        $isDetailAllCompleted = ($detailAssigneesCount > 0 && $detailCompletedCount === $detailAssigneesCount);
        ?>
        <div class="saas-card p-4 h-100 border border-secondary border-opacity-25">
            <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                <span class="badge" style="background-color: <?= $task['priority_color'] ?>;">
                    Prioritas: <?= esc($task['priority_name']) ?>
                </span>
                <?php if ($isDetailAllCompleted): ?>
                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 font-monospace d-inline-flex align-items-center gap-1">
                        <i class="fa-solid fa-circle-check text-success"></i> Seluruh Anggota Selesai
                    </span>
                <?php endif; ?>
                <span class="text-secondary small font-monospace ms-auto">
                    <i class="fa-solid fa-clock text-danger me-1"></i> Deadline: <?= $task['deadline'] ? date('d M Y, H:i', strtotime($task['deadline'])) . ' WIB' : 'Tanpa Batas' ?>
                </span>
            </div>

            <h4 class="text-body font-heading fw-bold mb-3"><?= esc($task['title']) ?></h4>
            <div class="text-secondary small leading-relaxed mb-4" style="word-break: break-word;"><?= nl2br(esc($task['description'])) ?></div>

            <div class="border-top border-secondary border-opacity-25 pt-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <label class="text-body small fw-semibold mb-0"><i class="fa-solid fa-users text-danger me-2"></i> Anggota Assignee Terdaftar (<?= count($task['assignees']) ?>):</label>
                    <?php if ($isDetailAllCompleted): ?>
                        <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 font-monospace style-tiny">
                            <i class="fa-solid fa-circle-check text-success me-1"></i> 100% Selesai
                        </span>
                    <?php endif; ?>
                </div>
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
            <h6 class="text-body font-heading fw-bold mb-3"><i class="fa-solid fa-chart-pie text-danger me-2"></i> Progres Evaluasi &amp; Nilai</h6>

            <?php
                $totalAssignees = count($task['assignees']);
                $totalSubmitted = 0;
                $totalGraded    = 0;
                foreach ($submissions as $sub) {
                    if (!empty($sub['has_submitted']) || !empty($sub['submission_id']) || ($sub['status_id'] >= 3)) {
                        $totalSubmitted++;
                    }
                    if ($sub['grade'] !== null) {
                        $totalGraded++;
                    }
                }
            ?>

            <div class="d-flex align-items-center justify-content-around py-3">
                <div class="text-center">
                    <div class="display-5 font-heading fw-bold text-body mb-0"><?= $totalSubmitted ?> <span class="fs-6 text-secondary font-monospace">/ <?= $totalAssignees ?></span></div>
                    <div class="text-secondary style-tiny mt-1">Mengumpulkan / Ditandai</div>
                </div>
                <div class="border-end border-secondary border-opacity-25" style="height: 40px;"></div>
                <div class="text-center">
                    <div class="display-5 font-heading fw-bold text-success mb-0"><?= $totalGraded ?> <span class="fs-6 text-secondary font-monospace">/ <?= $totalAssignees ?></span></div>
                    <div class="text-secondary style-tiny mt-1">Telah Dinilai</div>
                </div>
            </div>

            <div class="p-2.5 rounded-3 bg-body-secondary border border-secondary border-opacity-25 text-secondary style-tiny">
                <i class="fa-solid fa-bolt text-warning me-1"></i> Anda dapat langsung klik <strong>Beri Nilai</strong> atau ubah <strong>Status</strong> anggota secara langsung tanpa harus menunggu kiriman berkas.
            </div>
        </div>
    </div>
</div>

<!-- Submissions & Assignee Evaluation List -->
<div class="saas-card p-4 border border-secondary border-opacity-25">
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-3">
        <div>
            <h5 class="text-body font-heading m-0 fw-bold"><i class="fa-solid fa-folder-open text-danger me-2"></i> Daftar Pengumpulan &amp; Penilaian Tugas Anggota</h5>
            <p class="text-secondary small m-0">Semua penerima tugas terdaftar disini. Anda bisa langsung memberikan nilai per individu atau <strong>secara massal</strong>.</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-saas-dark text-body border border-secondary border-opacity-50 style-tiny font-monospace" onclick="selectAllAssignees(true)" title="Centang semua anggota">
                    <i class="fa-solid fa-check-square me-1"></i> Pilih Semua
                </button>
                <button type="button" class="btn btn-saas-dark text-body border border-secondary border-opacity-50 style-tiny font-monospace" onclick="selectOnlyUngraded()" title="Centang hanya yang belum ada nilai">
                    <i class="fa-solid fa-hourglass-half text-warning me-1"></i> Belum Dinilai
                </button>
                <button type="button" class="btn btn-saas-dark text-body border border-secondary border-opacity-50 style-tiny font-monospace" onclick="selectAllAssignees(false)" title="Hapus semua centang">
                    <i class="fa-solid fa-square-xmark me-1"></i> Reset
                </button>
            </div>

            <button type="button" class="btn btn-red btn-sm style-tiny font-monospace fw-bold shadow-sm d-inline-flex align-items-center gap-1.5" onclick="openBulkEvaluateModal()">
                <i class="fa-solid fa-layer-group"></i> Nilai Massal
                <span id="headerSelectedBadge" class="badge bg-white text-danger px-1.5 py-0.5 rounded-pill d-none">0</span>
            </button>
        </div>
    </div>

    <?php if (empty($submissions)): ?>
        <div class="text-center py-5 text-secondary">
            <i class="fa-solid fa-users-slash display-1 mb-3 text-secondary opacity-50"></i>
            <h5 class="text-body font-heading fw-bold">Belum Ada Anggota Ditugaskan</h5>
            <p class="small mb-0">Tugas ini belum memiliki assignee anggota aktif.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-dark table-dark-saas w-100 align-middle" id="assigneesEvaluationTable">
                <thead>
                    <tr>
                        <th style="width: 38px;" class="text-center">
                            <input type="checkbox" id="masterSelectCheckbox" class="form-check-input" onchange="toggleMasterSelect(this)" title="Pilih Semua / Batal">
                        </th>
                        <th style="width: 40px;">No</th>
                        <th>Nama Anggota</th>
                        <th>Status Tugas</th>
                        <th>Waktu Pengiriman</th>
                        <th>Catatan / Lampiran</th>
                        <th>Nilai / Skor</th>
                        <th class="text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($submissions as $i => $sub): ?>
                        <?php
                            $subJson = json_encode([
                                'user_id'       => $sub['user_id'],
                                'full_name'     => $sub['full_name'],
                                'avatar'        => $sub['avatar'] ?? '',
                                'nis_nip'       => $sub['nis_nip'] ?? '-',
                                'class_dept'    => $sub['class_dept'] ?? '-',
                                'grade'         => $sub['grade'],
                                'status_id'     => $sub['status_id'],
                                'feedback'      => $sub['feedback'] ?? '',
                                'submission_id' => $sub['submission_id'] ?? 0,
                            ], JSON_HEX_APOS | JSON_HEX_QUOT);
                        ?>
                        <tr id="row-assignee-<?= $sub['user_id'] ?>">
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input assignee-check" 
                                       value="<?= $sub['user_id'] ?>" 
                                       data-name="<?= esc($sub['full_name']) ?>" 
                                       data-graded="<?= $sub['grade'] !== null ? '1' : '0' ?>" 
                                       data-submitted="<?= (!empty($sub['has_submitted']) || !empty($sub['submission_id'])) ? '1' : '0' ?>"
                                       onchange="onAssigneeCheckChange()">
                            </td>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="<?= avatar_url($sub['avatar'] ?? null, $sub['full_name']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-danger border-opacity-50 flex-shrink-0" style="width: 32px; height: 32px; min-width: 32px;" onerror="this.onerror=null; this.src='<?= base_url('media/avatar?name=' . urlencode($sub['full_name'])) ?>';">
                                    <div class="min-w-0">
                                        <div class="fw-semibold text-body d-inline-flex align-items-center gap-1 text-truncate"
                                             style="cursor: pointer; max-width: 170px;"
                                             onclick='showUserProfileModal(<?= json_encode([
                                                 "id" => $sub["user_id"] ?? $sub["id"],
                                                 "full_name" => $sub["full_name"],
                                                 "username" => $sub["username"] ?? "-",
                                                 "nis_nip" => $sub["nis_nip"] ?? "-",
                                                 "class_dept" => $sub["class_dept"] ?? "-",
                                                 "email" => $sub["email"] ?? "-",
                                                 "phone" => $sub["phone"] ?? "-",
                                                 "role_name" => $sub["role_name"] ?? "Anggota",
                                                 "status" => $sub["user_status"] ?? ($sub["status"] ?? "active"),
                                                 "avatar" => $sub["avatar"] ?? "",
                                                 "member_uuid" => $sub["member_uuid"] ?? ""
                                             ], JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'
                                             title="Klik untuk lihat profil <?= esc($sub['full_name']) ?>">
                                            <span class="text-decoration-underline text-truncate"><?= esc($sub['full_name']) ?></span>
                                        </div>
                                        <div class="text-secondary style-tiny font-monospace"><?= esc($sub['nis_nip'] ?: '-') ?> &bull; <?= esc($sub['class_dept'] ?: 'Siswa') ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <form action="<?= base_url('admin/tasks/update-assignee-status/' . $task['id']) ?>" method="POST" class="m-0 d-inline-block">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="user_id" value="<?= $sub['user_id'] ?>">
                                    <select name="status_id" class="form-select form-select-sm bg-body-secondary text-body border-secondary border-opacity-50 py-1 px-2 style-tiny fw-semibold" onchange="this.form.submit()" style="min-width: 130px; font-size: 0.75rem;">
                                        <?php foreach ($statuses as $st): ?>
                                            <option value="<?= $st['id'] ?>" <?= ($sub['status_id'] == $st['id']) ? 'selected' : '' ?>>
                                                <?= esc($st['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            </td>
                            <td class="font-monospace style-tiny">
                                <?php if (!empty($sub['submitted_at'])): ?>
                                    <span class="text-body"><i class="fa-solid fa-clock text-danger me-1"></i><?= date('d M Y, H:i', strtotime($sub['submitted_at'])) ?></span>
                                <?php else: ?>
                                    <span class="badge bg-body-secondary text-secondary border border-secondary border-opacity-25 font-monospace style-tiny">
                                        <i class="fa-solid fa-minus me-1"></i> Belum Mengirim
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($sub['submission_text']) && $sub['submission_text'] !== 'Ditandai oleh Pembina / Admin' && $sub['submission_text'] !== 'Dinilai langsung oleh Pembina / Admin' && $sub['submission_text'] !== 'Selesai / Dinilai langsung oleh Admin' && $sub['submission_text'] !== 'Dinilai secara massal oleh Pembina / Admin' && $sub['submission_text'] !== 'Selesai / Dinilai massal oleh Admin'): ?>
                                    <div class="text-body small text-truncate mb-1" style="max-width: 180px;" title="<?= esc($sub['submission_text']) ?>">
                                        <?= esc($sub['submission_text']) ?>
                                    </div>
                                <?php elseif (empty($sub['attachment_url'])): ?>
                                    <span class="text-secondary fst-italic style-tiny d-inline-flex align-items-center gap-1">
                                        <i class="fa-regular fa-file-lines opacity-50"></i> Kosongan (Tanpa berkas)
                                    </span>
                                <?php endif; ?>

                                <?php if (!empty($sub['attachment_url'])): ?>
                                    <a href="<?= media_url($sub['attachment_url']) ?>" target="_blank" class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-50 text-decoration-none style-tiny font-monospace d-inline-flex align-items-center gap-1 mt-1" title="Lihat Lampiran Berkas">
                                        <i class="fa-solid fa-paperclip"></i> Lampiran Berkas
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($sub['grade'] !== null): ?>
                                    <button type="button" class="btn btn-sm btn-outline-success style-tiny font-monospace py-1 px-2.5 d-inline-flex align-items-center gap-1.5 shadow-sm" onclick='openEvaluateModal(<?= $subJson ?>)' title="Klik untuk ubah nilai/catatan">
                                        <i class="fa-solid fa-medal text-warning"></i>
                                        <strong class="fs-6"><?= esc($sub['grade']) ?></strong><span class="text-secondary style-tiny">/100</span>
                                        <i class="fa-solid fa-pen style-tiny opacity-75 ms-1"></i>
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-sm btn-outline-warning style-tiny font-monospace py-1 px-2.5 d-inline-flex align-items-center gap-1 shadow-sm" onclick='openEvaluateModal(<?= $subJson ?>)' title="Beri nilai langsung">
                                        <i class="fa-solid fa-plus text-warning"></i>
                                        <span>Beri Nilai</span>
                                    </button>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-sm btn-saas-dark text-body border border-secondary border-opacity-50 style-tiny font-monospace px-2.5" onclick='openEvaluateModal(<?= $subJson ?>)' title="Evaluasi &amp; Catatan Feedback">
                                        <i class="fa-solid fa-star text-warning me-1"></i> Nilai
                                    </button>
                                    <?php if (!empty($sub['submission_id'])): ?>
                                        <a href="<?= base_url('admin/tasks/submission/' . $sub['submission_id']) ?>" class="btn btn-sm btn-red style-tiny font-monospace px-2.5" title="Cek Jawaban Lengkap">
                                            <i class="fa-solid fa-file-signature"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
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

<!-- Modal Quick Evaluate & Direct Grading (ClickUp Style Single) -->
<div class="modal fade" id="evaluateMemberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25 shadow-lg">
            <div class="modal-header border-bottom border-secondary border-opacity-25 py-2.5">
                <h5 class="modal-title font-heading fs-6 d-flex align-items-center gap-2 m-0">
                    <span class="p-1.5 rounded-2 bg-danger bg-opacity-25 text-danger fs-6">
                        <i class="fa-solid fa-star"></i>
                    </span>
                    <span>Evaluasi &amp; Beri Nilai Anggota</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="<?= base_url('admin/tasks/evaluate-assignee/' . $task['id']) ?>" method="POST" id="evaluateMemberForm">
                <?= csrf_field() ?>
                <input type="hidden" name="user_id" id="evalUserId" value="0">

                <div class="modal-body p-4">
                    <!-- Member Card Preview -->
                    <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-body-secondary border border-secondary border-opacity-25 mb-3">
                        <div id="evalMemberAvatarWrapper">
                            <!-- Populated by JS -->
                        </div>
                        <div class="min-w-0">
                            <h6 id="evalMemberName" class="text-white font-heading fw-bold mb-0.5 text-truncate">Nama Anggota</h6>
                            <div id="evalMemberMeta" class="text-secondary style-tiny font-monospace text-truncate">NIS: - &bull; Siswa</div>
                        </div>
                    </div>

                    <!-- Status Select -->
                    <div class="mb-3">
                        <label class="form-label text-body small fw-semibold">Status Pengerjaan Tugas <span class="text-danger">*</span></label>
                        <select name="status_id" id="evalStatusId" class="form-select bg-body-secondary text-body border-secondary border-opacity-50" required>
                            <?php foreach ($statuses as $st): ?>
                                <option value="<?= $st['id'] ?>">
                                    Status: <?= esc($st['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Nilai / Grade (0-100) -->
                    <div class="mb-3">
                        <label class="form-label text-body small fw-semibold d-flex justify-content-between align-items-center">
                            <span>Nilai Angka (0 - 100)</span>
                            <span class="text-secondary style-tiny font-monospace">Bisa langsung diisi</span>
                        </label>
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-body-secondary text-warning border-secondary border-opacity-50"><i class="fa-solid fa-award"></i></span>
                            <input type="number" name="grade" id="evalGrade" class="form-control bg-body-secondary text-body border-secondary border-opacity-50 font-monospace fs-5 fw-bold" min="0" max="100" placeholder="85" required>
                            <span class="input-group-text bg-body-secondary text-secondary border-secondary border-opacity-50 font-monospace">/ 100</span>
                        </div>

                        <!-- Quick Score Presets (ClickUp Style) -->
                        <div class="d-flex flex-wrap gap-1.5 align-items-center mt-1.5">
                            <span class="text-secondary style-tiny me-1">Pilihan Cepat:</span>
                            <?php foreach ([60, 70, 75, 80, 85, 90, 95, 100] as $presetScore): ?>
                                <button type="button" class="btn btn-sm btn-saas-dark border border-secondary border-opacity-50 py-0.5 px-2 style-tiny font-monospace text-body" onclick="setEvalGradePreset(<?= $presetScore ?>)">
                                    <?= $presetScore ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Feedback / Catatan Revisi -->
                    <div class="mb-2">
                        <label class="form-label text-body small fw-semibold">Catatan Feedback / Masukan (Opsional)</label>
                        <textarea name="feedback" id="evalFeedback" class="form-control bg-body-secondary text-body border-secondary border-opacity-50" rows="3" placeholder="Tuliskan masukan evaluasi atau catatan revisi..."></textarea>
                    </div>
                </div>

                <div class="modal-footer border-top border-secondary border-opacity-25 py-2.5 d-flex justify-content-between">
                    <button type="button" class="btn btn-saas-dark btn-sm text-body border border-secondary border-opacity-25" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-red btn-sm px-3.5 py-1.5 font-heading fw-bold shadow-sm">
                        <i class="fa-solid fa-check-circle me-1"></i> Simpan Nilai &amp; Evaluasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Bulk Evaluate / Nilai Massal (Google Classroom / ClickUp Style) -->
<div class="modal fade" id="bulkEvaluateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25 shadow-lg">
            <div class="modal-header border-bottom border-secondary border-opacity-25 py-3">
                <h5 class="modal-title font-heading fs-5 d-flex align-items-center gap-2 m-0">
                    <span class="p-2 rounded-3 bg-danger bg-opacity-25 text-danger fs-5">
                        <i class="fa-solid fa-layer-group"></i>
                    </span>
                    <span>Penilaian Massal Tugas Anggota</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="<?= base_url('admin/tasks/bulk-evaluate/' . $task['id']) ?>" method="POST" id="bulkEvaluateForm">
                <?= csrf_field() ?>
                <div id="bulkHiddenUserIdsContainer">
                    <!-- Hidden inputs populated by JS -->
                </div>

                <div class="modal-body p-4">
                    <!-- Header Banner -->
                    <div class="p-3 rounded-3 bg-body-secondary border border-secondary border-opacity-25 mb-3 d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2">
                        <div>
                            <div class="fw-bold text-white font-heading d-flex align-items-center gap-2">
                                <i class="fa-solid fa-users text-danger"></i>
                                <span id="bulkSelectedCountText">0</span> Anggota Dipilih untuk Dinilai
                            </div>
                            <small class="text-secondary style-tiny">Nilai dan status yang diatur di bawah ini akan diterapkan secara serentak.</small>
                        </div>
                        <div class="d-flex gap-1.5 flex-wrap">
                            <button type="button" class="btn btn-sm btn-saas-dark border border-secondary border-opacity-50 style-tiny font-monospace text-body py-1 px-2.5" onclick="selectAllAssignees(true)">
                                <i class="fa-solid fa-check-double me-1"></i> Pilih Semua (<?= count($submissions) ?>)
                            </button>
                            <button type="button" class="btn btn-sm btn-saas-dark border border-secondary border-opacity-50 style-tiny font-monospace text-body py-1 px-2.5" onclick="selectOnlyUngraded()">
                                <i class="fa-solid fa-hourglass-half text-warning me-1"></i> Belum Dinilai
                            </button>
                        </div>
                    </div>

                    <!-- Selected Members Chips / Badges Container -->
                    <div class="mb-3">
                        <label class="form-label text-body small fw-semibold mb-1.5">Daftar Anggota yang Terpilih:</label>
                        <div id="bulkSelectedBadgesWrapper" class="p-2.5 rounded-3 bg-black bg-opacity-50 border border-secondary border-opacity-25 d-flex flex-wrap gap-1.5 custom-scroll-container" style="max-height: 120px; overflow-y: auto;">
                            <!-- Populated by JS -->
                        </div>
                    </div>

                    <div class="row g-3">
                        <!-- Nilai Massal -->
                        <div class="col-md-6">
                            <label class="form-label text-body small fw-semibold d-flex justify-content-between align-items-center">
                                <span>Nilai Massal (0 - 100) <span class="text-danger">*</span></span>
                            </label>
                            <div class="input-group mb-2">
                                <span class="input-group-text bg-body-secondary text-warning border-secondary border-opacity-50"><i class="fa-solid fa-award"></i></span>
                                <input type="number" name="grade" id="bulkGradeInput" class="form-control bg-body-secondary text-body border-secondary border-opacity-50 font-monospace fs-5 fw-bold" min="0" max="100" value="85" required>
                                <span class="input-group-text bg-body-secondary text-secondary border-secondary border-opacity-50 font-monospace">/ 100</span>
                            </div>

                            <!-- Score Presets -->
                            <div class="d-flex flex-wrap gap-1 align-items-center">
                                <span class="text-secondary style-tiny me-1">Pilihan:</span>
                                <?php foreach ([60, 70, 75, 80, 85, 90, 95, 100] as $scoreP): ?>
                                    <button type="button" class="btn btn-sm btn-saas-dark border border-secondary border-opacity-50 py-0.5 px-2 style-tiny font-monospace text-body" onclick="document.getElementById('bulkGradeInput').value=<?= $scoreP ?>">
                                        <?= $scoreP ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Status Pengerjaan Massal -->
                        <div class="col-md-6">
                            <label class="form-label text-body small fw-semibold">Status Pengerjaan Baru <span class="text-danger">*</span></label>
                            <select name="status_id" id="bulkStatusId" class="form-select bg-body-secondary text-body border-secondary border-opacity-50 mb-2" required>
                                <?php foreach ($statuses as $st): ?>
                                    <option value="<?= $st['id'] ?>" <?= $st['id'] == 5 ? 'selected' : '' ?>>
                                        Status: <?= esc($st['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-secondary style-tiny d-block"><i class="fa-solid fa-info-circle me-1 text-info"></i> Rekomendasi: <strong>Selesai</strong> jika tugas tuntas.</small>
                        </div>
                    </div>

                    <!-- Feedback Massal -->
                    <div class="mt-3">
                        <label class="form-label text-body small fw-semibold">Catatan Evaluasi / Feedback Massal (Opsional)</label>
                        <textarea name="feedback" class="form-control bg-body-secondary text-body border-secondary border-opacity-50" rows="3" placeholder="Contoh: Tugas telah diperiksa dan dinilai dengan baik."></textarea>
                    </div>

                    <!-- Overwrite Option Checkbox -->
                    <div class="mt-3 pt-3 border-top border-secondary border-opacity-15">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="overwrite_graded" value="1" id="bulkOverwriteGraded" checked>
                            <label class="form-check-label text-body small" for="bulkOverwriteGraded">
                                <strong>Timpa nilai anggota yang sudah pernah dinilai</strong> <span class="text-secondary style-tiny">(Jika tidak dicentang, hanya anggota yang belum memiliki nilai yang akan dinilai).</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top border-secondary border-opacity-25 py-2.5 d-flex justify-content-between">
                    <button type="button" class="btn btn-saas-dark btn-sm text-body border border-secondary border-opacity-25" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-red btn-sm px-4 py-2 font-heading fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-check-double"></i> Terapkan Nilai Massal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Floating Bulk Action Toolbar (appears when 1+ rows are checked) -->
<div id="floatingBulkToolbar" class="position-fixed bottom-0 start-50 translate-middle-x mb-4 shadow-lg p-2.5 px-3.5 rounded-pill bg-dark border border-danger border-opacity-50 d-none align-items-center gap-2.5 flex-wrap" style="z-index: 1040; backdrop-filter: blur(12px); max-width: 95vw;">
    <div class="d-flex align-items-center gap-2">
        <span class="badge bg-danger rounded-pill px-2.5 py-1.5 font-monospace fw-bold style-tiny">
            <i class="fa-solid fa-check-double me-1"></i> <span id="floatingSelectedCount">0</span> Terpilih
        </span>
    </div>
    
    <div class="border-end border-secondary border-opacity-25 d-none d-sm-block" style="height: 24px;"></div>

    <!-- Quick Grade Buttons -->
    <div class="d-flex align-items-center gap-1.5">
        <button type="button" class="btn btn-sm btn-outline-success rounded-pill style-tiny font-monospace py-1 px-2.5" onclick="quickBulkGradeWithScore(100)" title="Beri nilai 100 langsung ke anggota terpilih">
            ⚡ Nilai 100
        </button>
        <button type="button" class="btn btn-sm btn-outline-warning rounded-pill style-tiny font-monospace py-1 px-2.5" onclick="quickBulkGradeWithScore(85)" title="Beri nilai 85 langsung ke anggota terpilih">
            ⚡ Nilai 85
        </button>
        <button type="button" class="btn btn-red btn-sm rounded-pill style-tiny font-monospace fw-bold py-1 px-3 shadow-sm" onclick="openBulkEvaluateModal()">
            <i class="fa-solid fa-layer-group me-1"></i> Atur Nilai &amp; Status...
        </button>
        <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" onclick="selectAllAssignees(false)" title="Batalkan Centang">
            <i class="fa-solid fa-xmark"></i>
        </button>
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
// --- Bulk Grading & Checkbox Selection Logic ---
function getSelectedAssigneeCheckboxes() {
    return Array.from(document.querySelectorAll('.assignee-check:checked'));
}

function updateBulkActionState() {
    const selected = getSelectedAssigneeCheckboxes();
    const count = selected.length;
    const total = document.querySelectorAll('.assignee-check').length;

    // Master Checkbox
    const masterCheckbox = document.getElementById('masterSelectCheckbox');
    if (masterCheckbox) {
        masterCheckbox.checked = (count > 0 && count === total);
        masterCheckbox.indeterminate = (count > 0 && count < total);
    }

    // Header Badge
    const headerBadge = document.getElementById('headerSelectedBadge');
    if (headerBadge) {
        headerBadge.textContent = count;
        headerBadge.classList.toggle('d-none', count === 0);
    }

    // Floating Toolbar
    const floatingToolbar = document.getElementById('floatingBulkToolbar');
    const floatingCount = document.getElementById('floatingSelectedCount');
    if (floatingToolbar && floatingCount) {
        floatingCount.textContent = count;
        if (count > 0) {
            floatingToolbar.classList.remove('d-none');
            floatingToolbar.classList.add('d-flex');
        } else {
            floatingToolbar.classList.add('d-none');
            floatingToolbar.classList.remove('d-flex');
        }
    }
}

function toggleMasterSelect(master) {
    document.querySelectorAll('.assignee-check').forEach(chk => {
        chk.checked = master.checked;
    });
    updateBulkActionState();
}

function selectAllAssignees(status) {
    document.querySelectorAll('.assignee-check').forEach(chk => {
        chk.checked = status;
    });
    const master = document.getElementById('masterSelectCheckbox');
    if (master) master.checked = status;
    updateBulkActionState();
}

function selectOnlyUngraded() {
    document.querySelectorAll('.assignee-check').forEach(chk => {
        chk.checked = (chk.getAttribute('data-graded') === '0');
    });
    updateBulkActionState();
}

function onAssigneeCheckChange() {
    updateBulkActionState();
}

function openBulkEvaluateModal() {
    let selected = getSelectedAssigneeCheckboxes();
    
    // If none checked, automatically select all
    if (selected.length === 0) {
        selectAllAssignees(true);
        selected = getSelectedAssigneeCheckboxes();
    }

    document.getElementById('bulkSelectedCountText').textContent = selected.length;

    // Populate hidden inputs & badges
    const hiddenContainer = document.getElementById('bulkHiddenUserIdsContainer');
    const badgesWrapper   = document.getElementById('bulkSelectedBadgesWrapper');
    hiddenContainer.innerHTML = '';
    badgesWrapper.innerHTML   = '';

    selected.forEach(chk => {
        const uid = chk.value;
        const name = chk.getAttribute('data-name') || 'Anggota';
        
        // Hidden input
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'user_ids[]';
        hiddenInput.value = uid;
        hiddenContainer.appendChild(hiddenInput);

        // Chip Badge
        const badge = document.createElement('span');
        badge.className = 'badge bg-dark border border-secondary text-white style-tiny font-monospace py-1 px-2 d-inline-flex align-items-center gap-1';
        badge.innerHTML = `<i class="fa-solid fa-user text-danger style-tiny"></i> ${name}`;
        badgesWrapper.appendChild(badge);
    });

    const modal = new bootstrap.Modal(document.getElementById('bulkEvaluateModal'));
    modal.show();
}

function quickBulkGradeWithScore(score) {
    const selected = getSelectedAssigneeCheckboxes();
    if (selected.length === 0) {
        alert('Pilih setidaknya satu anggota terlebih dahulu.');
        return;
    }

    document.getElementById('bulkGradeInput').value = score;
    openBulkEvaluateModal();
}

// --- Single Evaluation Modal ---
function openEvaluateModal(sub) {
    document.getElementById('evalUserId').value = sub.user_id;
    document.getElementById('evalMemberName').textContent = sub.full_name || 'Anggota';
    document.getElementById('evalMemberMeta').textContent = `NIS/NIP: ${sub.nis_nip || '-'} • ${sub.class_dept || 'Siswa'}`;
    
    // Avatar
    const avatarWrapper = document.getElementById('evalMemberAvatarWrapper');
    if (sub.avatar && sub.avatar.trim() !== '') {
        const baseUrl = '<?= base_url() ?>';
        const imgUrl = sub.avatar.startsWith('http') ? sub.avatar : baseUrl + (sub.avatar.startsWith('/') ? sub.avatar.substring(1) : sub.avatar);
        avatarWrapper.innerHTML = `<img src="${imgUrl}" alt="Avatar" class="rounded-circle object-fit-cover border border-danger border-opacity-50" style="width: 42px; height: 42px; min-width: 42px;">`;
    } else {
        const initial = sub.full_name ? sub.full_name.charAt(0).toUpperCase() : 'U';
        avatarWrapper.innerHTML = `<div class="rounded-circle bg-danger bg-opacity-25 text-danger fw-bold d-flex align-items-center justify-content-center border border-danger border-opacity-50 fs-5" style="width: 42px; height: 42px; min-width: 42px;">${initial}</div>`;
    }

    // Status: default to current status_id or 5 (Done/Selesai) if currently <= 2
    const statusSelect = document.getElementById('evalStatusId');
    if (sub.status_id) {
        statusSelect.value = (sub.status_id <= 2) ? 5 : sub.status_id;
    } else {
        statusSelect.value = 5;
    }

    // Grade: if already graded, prefill. If not, default to 85.
    const gradeInput = document.getElementById('evalGrade');
    gradeInput.value = (sub.grade !== null && sub.grade !== undefined && sub.grade !== '') ? sub.grade : 85;

    // Feedback
    document.getElementById('evalFeedback').value = sub.feedback || '';

    const modal = new bootstrap.Modal(document.getElementById('evaluateMemberModal'));
    modal.show();
}

function setEvalGradePreset(score) {
    const gradeInput = document.getElementById('evalGrade');
    gradeInput.value = score;
    gradeInput.focus();
}

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
