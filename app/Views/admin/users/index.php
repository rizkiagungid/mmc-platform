<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Manajemen Pengguna & Anggota</h4>
        <p class="text-secondary small m-0">Kelola akun Super Admin, Pembina, BPH, dan Anggota Club</p>
    </div>
    <a href="<?= base_url('admin/users/create') ?>" class="btn btn-red">
        <i class="fa-solid fa-user-plus me-1"></i> Tambah Anggota Baru
    </a>
</div>

<!-- Rich Multi-Filter Card -->
<div class="saas-card p-4 mb-4">
    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom border-secondary border-opacity-25 pb-2">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-sliders text-danger"></i>
            <h6 class="text-white font-heading mb-0">Pencarian & Multi-Filtering Anggota</h6>
        </div>
        <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50 font-monospace">
            <?= count($users) ?> Data Ditemukan
        </span>
    </div>

    <form action="<?= current_url() ?>" method="GET" id="user-filter-form">
        <div class="row g-3">
            <!-- Search Keyword -->
            <div class="col-md-6 col-lg-4">
                <label class="form-label text-secondary style-tiny fw-medium mb-1"><i class="fa-solid fa-magnifying-glass me-1"></i> Kata Kunci Pencarian</label>
                <div class="input-group">
                    <span class="input-group-text bg-black border-secondary border-opacity-25 text-secondary">
                        <i class="fa-solid fa-search"></i>
                    </span>
                    <input type="text" id="custom-user-search" name="keyword" class="form-control bg-black text-white border-secondary border-opacity-25" placeholder="Nama, username, email, NIS/NIP, HP, alamat..." value="<?= esc($keyword ?? '') ?>">
                </div>
            </div>

            <!-- Filter Role -->
            <div class="col-md-6 col-lg-2">
                <label class="form-label text-secondary style-tiny fw-medium mb-1"><i class="fa-solid fa-user-shield me-1"></i> Role Pengguna</label>
                <select name="role_id" id="role-filter-select" class="form-select bg-black text-white border-secondary border-opacity-25">
                    <option value="">-- Semua Role --</option>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['id'] ?>" <?= (isset($roleId) && $roleId == $r['id']) ? 'selected' : '' ?>>
                            <?= esc($r['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Filter Tingkat Kelas -->
            <div class="col-md-4 col-lg-2">
                <label class="form-label text-secondary style-tiny fw-medium mb-1"><i class="fa-solid fa-graduation-cap me-1"></i> Tingkat Kelas</label>
                <select name="class_grade" class="form-select bg-black text-white border-secondary border-opacity-25">
                    <option value="">-- Semua Kelas --</option>
                    <?php foreach (['X', 'XI', 'XII'] as $grade): ?>
                        <option value="<?= $grade ?>" <?= (isset($classGrade) && $classGrade === $grade) ? 'selected' : '' ?>>Kelas <?= $grade ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Filter Ruang Kelas -->
            <div class="col-md-4 col-lg-2">
                <label class="form-label text-secondary style-tiny fw-medium mb-1"><i class="fa-solid fa-door-open me-1"></i> Ruang Kelas</label>
                <select name="class_room" class="form-select bg-black text-white border-secondary border-opacity-25">
                    <option value="">-- Semua Ruang --</option>
                    <?php for ($r = 1; $r <= 10; $r++): ?>
                        <option value="<?= $r ?>" <?= (isset($classRoom) && $classRoom == $r) ? 'selected' : '' ?>>Ruang <?= $r ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <!-- Filter Divisi -->
            <div class="col-md-4 col-lg-2">
                <label class="form-label text-secondary style-tiny fw-medium mb-1"><i class="fa-solid fa-laptop-code me-1"></i> Divisi Ekskul</label>
                <select name="division" class="form-select bg-black text-white border-secondary border-opacity-25">
                    <option value="">-- Semua Divisi --</option>
                    <?php foreach (['Broadcasting', 'Programming'] as $div): ?>
                        <option value="<?= $div ?>" <?= (isset($division) && $division === $div) ? 'selected' : '' ?>><?= $div ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Filter Status Akun -->
            <div class="col-md-6 col-lg-3">
                <label class="form-label text-secondary style-tiny fw-medium mb-1"><i class="fa-solid fa-circle-dot me-1"></i> Status Keanggotaan</label>
                <select name="status" class="form-select bg-black text-white border-secondary border-opacity-25">
                    <option value="">-- Semua Status --</option>
                    <option value="active" <?= (isset($status) && $status === 'active') ? 'selected' : '' ?>>🟢 Aktif</option>
                    <option value="inactive" <?= (isset($status) && $status === 'inactive') ? 'selected' : '' ?>>🟡 Belum Dikonfirmasi (Pending)</option>
                    <option value="suspended" <?= (isset($status) && $status === 'suspended') ? 'selected' : '' ?>>🟠 Ditangguhkan (Suspended)</option>
                    <option value="left" <?= (isset($status) && $status === 'left') ? 'selected' : '' ?>>🔴 Keluar Ekskul</option>
                </select>
            </div>

            <!-- Filter Avatar / Foto Profil -->
            <div class="col-md-6 col-lg-3">
                <label class="form-label text-secondary style-tiny fw-medium mb-1"><i class="fa-solid fa-image me-1"></i> Foto Profil</label>
                <select name="has_avatar" class="form-select bg-black text-white border-secondary border-opacity-25">
                    <option value="">-- Semua Foto --</option>
                    <option value="1" <?= (isset($hasAvatar) && $hasAvatar === '1') ? 'selected' : '' ?>>📷 Memiliki Foto Profil</option>
                    <option value="0" <?= (isset($hasAvatar) && $hasAvatar === '0') ? 'selected' : '' ?>>👤 Tanpa Foto Profil</option>
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="col-md-12 col-lg-6 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-red px-4 fw-semibold">
                    <i class="fa-solid fa-filter me-1"></i> Terapkan Filter
                </button>
                <?php if (!empty($keyword) || !empty($roleId) || !empty($classGrade) || !empty($classRoom) || !empty($division) || !empty($status) || (isset($hasAvatar) && $hasAvatar !== null && $hasAvatar !== '')): ?>
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-saas-dark px-3" title="Reset Semua Filter">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset Filter
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<div class="saas-card p-4">
    <div class="table-responsive">
        <table id="users-table" class="table table-dark-saas datatable-saas align-middle">
            <thead>
                <tr>
                    <th>Member Info</th>
                    <th>Role</th>
                    <th>NIS / NIP</th>
                    <th>Kelas / Divisi</th>
                    <th>QR Version</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <?php if (!empty($u['avatar'])): ?>
                                    <img src="<?= base_url($u['avatar']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-danger border-opacity-50 shadow-sm" style="width: 40px; height: 40px; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform='scale(1)'" data-bs-toggle="modal" data-bs-target="#userAvatarModal-<?= $u['id'] ?>" title="Klik untuk lihat foto full">
                                <?php else: ?>
                                    <div class="rounded-circle bg-danger bg-opacity-25 text-danger fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <?= strtoupper(substr($u['full_name'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <div class="fw-semibold text-white"><?= esc($u['full_name']) ?></div>
                                    <small class="text-secondary font-monospace">@<?= esc($u['username']) ?> | <?= esc($u['email']) ?></small>
                                </div>
                            </div>

                            <?php if (!empty($u['avatar'])): ?>
                                <!-- Modal Preview Foto Full User ID <?= $u['id'] ?> -->
                                <div class="modal fade" id="userAvatarModal-<?= $u['id'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
                                            <div class="modal-header border-bottom border-secondary border-opacity-25">
                                                <h5 class="modal-title font-heading"><i class="fa-solid fa-image text-danger me-2"></i> Foto Profil Full - <?= esc($u['full_name']) ?></h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center p-4">
                                                <img src="<?= base_url($u['avatar']) ?>" alt="Foto Full <?= esc($u['full_name']) ?>" class="img-fluid rounded-3 shadow-lg border border-secondary border-opacity-50" style="max-height: 75vh; object-fit: contain;">
                                            </div>
                                            <div class="modal-footer border-top border-secondary border-opacity-25 justify-content-between">
                                                <a href="<?= base_url($u['avatar']) ?>" target="_blank" class="btn btn-sm btn-outline-light">
                                                    <i class="fa-solid fa-external-link me-1"></i> Buka File Asli
                                                </a>
                                                <button type="button" class="btn btn-saas-dark btn-sm" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 px-2.5 py-1">
                                <?= esc($u['role_name']) ?>
                            </span>
                        </td>
                        <td class="font-monospace small text-secondary"><?= esc($u['nis_nip'] ?: '-') ?></td>
                        <td class="small text-secondary"><?= esc($u['class_dept'] ?: '-') ?></td>
                        <td class="font-monospace small text-center">
                            <span class="badge bg-dark border border-secondary text-secondary">v<?= esc($u['qr_version']) ?></span>
                        </td>
                        <td>
                            <?php if ($u['status'] === 'active'): ?>
                                <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25">AKTIF</span>
                            <?php elseif ($u['status'] === 'left' || $u['status'] === 'keluar'): ?>
                                <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25">KELUAR EKSKUL</span>
                            <?php elseif ($u['status'] === 'suspended'): ?>
                                <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25">SUSPENDED</span>
                            <?php else: ?>
                                <span class="badge bg-secondary bg-opacity-25 text-secondary border border-secondary">NONAKTIF</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-saas-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Pilihan
                                </button>
                                <ul class="dropdown-menu dropdown-menu-dark">
                                    <li><a class="dropdown-item" href="<?= base_url('admin/users/qr/' . $u['id']) ?>"><i class="fa-solid fa-qrcode me-2 text-warning"></i> Lihat ID Card & QR</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('admin/users/regenerate-qr/' . $u['id']) ?>" onclick="return confirm('Regenerasi QR akan membatalkan QR lama. Lanjutkan?')"><i class="fa-solid fa-arrows-rotate me-2 text-info"></i> Regenerasi Member QR</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?= base_url('admin/users/edit/' . $u['id']) ?>"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Edit Profile</a></li>
                                    <li><a class="dropdown-item text-danger" href="<?= base_url('admin/users/delete/' . $u['id']) ?>" onclick="return confirm('Hapus pengguna ini?')"><i class="fa-solid fa-trash me-2"></i> Hapus</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        const table = $('#users-table').DataTable({
            language: {
                search: "Cari Cepat:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ pengguna",
                paginate: { first: "Awal", last: "Akhir", next: "▶", previous: "◀" },
                zeroRecords: "Tidak ada pengguna yang sesuai"
            }
        });

        $('#custom-user-search').on('keyup input', function() {
            table.search(this.value).draw();
        });
    });
</script>
<?= $this->endSection() ?>
