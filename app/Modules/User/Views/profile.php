<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="row g-4 justify-content-center">
    <div class="col-lg-5">
        <!-- Permanent QR Card -->
        <div class="saas-card p-4 text-center border border-danger border-opacity-50 shadow-lg">
            <h5 class="text-white font-heading mb-0"><?= esc($user['full_name']) ?></h5>
            <div class="text-danger font-monospace fw-bold mb-2">@<?= esc($user['username']) ?></div>
            <p class="text-secondary small mb-3">Tunjukkan Permanent Member QR ini ke Operator saat absensi sesi pertemuan</p>

            <div class="my-3 text-center">
                <canvas id="profile-qr-canvas" class="bg-white p-3 rounded-4 shadow-sm"></canvas>
            </div>

            <div class="text-secondary font-monospace small">
                <div>Version: <span class="badge bg-dark border border-secondary text-secondary">v<?= esc($user['qr_version']) ?></span></div>
                <div class="mt-1" style="font-size: 0.7rem;">UUID: <span class="text-danger"><?= esc($user['member_uuid']) ?></span></div>
            </div>

            <!-- Download Options -->
            <div class="mt-3 pt-3 border-top border-secondary border-opacity-25 d-flex flex-column gap-2">
                <button type="button" class="btn btn-sm btn-outline-light w-100 font-monospace style-tiny" onclick="downloadOnlyQR()">
                    <i class="fa-solid fa-qrcode me-1 text-info"></i> 1. Download Hanya QR Code (PNG)
                </button>
                <button type="button" class="btn btn-sm btn-red w-100 font-monospace style-tiny fw-bold shadow-sm" onclick="downloadIDCard()">
                    <i class="fa-solid fa-id-card me-1"></i> 2. Download ID Card Digital Anggota MMC (PNG)
                </button>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="saas-card p-4">
            <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-user-gear text-danger me-2"></i> Pengaturan Profil Akun</h5>

            <form action="<?= base_url('profile') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <!-- Foto Profil Avatar Header -->
                <div class="d-flex align-items-start gap-3 mb-4 pb-3 border-bottom border-secondary border-opacity-25 flex-wrap flex-sm-nowrap">
                    <div class="position-relative cursor-pointer flex-shrink-0" id="avatarPreviewContainer">
                        <img id="currentAvatarPreview" src="<?= avatar_url($user['avatar'], $user['full_name']) ?>" alt="Avatar" class="rounded-circle object-fit-cover border border-danger border-2 shadow-sm" style="width: 80px; height: 80px; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'" onerror="this.onerror=null; this.src='<?= base_url('media/avatar?name=' . urlencode($user['full_name'])) ?>';" <?php if (!empty($user['avatar'])): ?>data-bs-toggle="modal" data-bs-target="#avatarFullModal" title="Klik untuk lihat foto ukuran penuh"<?php endif; ?>>
                        <?php if (!empty($user['avatar'])): ?>
                            <span class="position-absolute bottom-0 end-0 bg-danger text-white rounded-circle p-1 d-flex align-items-center justify-content-center shadow" style="width: 22px; height: 22px; font-size: 0.65rem;" title="Lihat Foto Full" data-bs-toggle="modal" data-bs-target="#avatarFullModal">
                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                            <label class="form-label text-white small fw-bold mb-0">
                                <i class="fa-solid fa-camera text-danger me-1"></i> Foto Profil (Avatar)
                            </label>
                            <?php if (!empty($user['avatar'])): ?>
                                <button type="button" class="btn btn-sm btn-outline-light py-0 px-2 style-tiny" data-bs-toggle="modal" data-bs-target="#avatarFullModal">
                                    <i class="fa-solid fa-expand me-1"></i> Lihat Foto Full
                                </button>
                            <?php endif; ?>
                        </div>

                        <!-- Hidden Cropped Base64 Payload -->
                        <input type="hidden" name="avatar_cropped_base64" id="avatarCroppedBase64" value="">

                        <input type="file" id="avatarFileInput" name="avatar" class="form-control form-control-sm bg-dark text-white border-secondary mb-1" accept="image/png, image/jpeg, image/jpg, image/webp" onchange="handleAvatarFileSelect(this)">
                        
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-1">
                            <div class="form-text text-secondary style-tiny m-0">Format: JPG, PNG, WEBP (Maksimal 20MB). Anda dapat memotong (crop) & pratinjau sebelum menyimpan.</div>
                            
                            <div id="avatarStagedStatus" style="display: none;">
                                <span class="badge bg-success font-monospace style-tiny py-1 px-2.5 rounded-pill shadow-sm">
                                    <i class="fa-solid fa-circle-check me-1"></i> Foto Baru Siap Disimpan
                                </span>
                                <button type="button" class="btn btn-sm btn-outline-warning py-0 px-2 style-tiny ms-1 font-monospace" onclick="reopenCropModal()">
                                    <i class="fa-solid fa-crop me-1"></i> Crop Ulang
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 style-tiny ms-1 font-monospace" onclick="cancelStagedAvatar()">
                                    <i class="fa-solid fa-xmark me-1"></i> Batal
                                </button>
                            </div>
                        </div>

                        <?php if (!empty($user['avatar'])): ?>
                            <div class="form-check mt-2" id="removeAvatarWrapper">
                                <input class="form-check-input" type="checkbox" name="remove_avatar" value="1" id="removeAvatarCheck" onchange="handleRemoveAvatarToggle(this)">
                                <label class="form-check-label text-danger style-tiny" for="removeAvatarCheck">
                                    <i class="fa-solid fa-trash me-1"></i> Hapus foto profil ini (kembalikan ke default)
                                </label>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" class="form-control" value="<?= esc($user['full_name']) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium">Username <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-danger border-secondary border-opacity-25 font-monospace">@</span>
                            <input type="text" name="username" class="form-control font-monospace" value="<?= esc(old('username', $user['username'])) ?>" placeholder="Username unik Anda" required>
                        </div>
                        <small class="text-secondary style-tiny">Username unik Anda untuk login dan profil (dapat diubah mandiri)</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="<?= esc($user['email']) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium">NIS / NIP</label>
                        <input type="text" name="nis_nip" class="form-control" value="<?= esc($user['nis_nip'] ?? '') ?>" placeholder="Nomor Induk Siswa / NIP">
                        <small class="text-secondary style-tiny">Nomor Induk Siswa atau NIP (dapat diubah mandiri)</small>
                    </div>

                    <?php
                    $profParsedGrade = '';
                    $profParsedRoom = '';
                    $profParsedDivision = '';
                    $profClassDeptVal = $user['class_dept'] ?? '';
                    if (!empty($profClassDeptVal)) {
                        if (preg_match('/^(X|XI|XII)\s+([1-9]|10)\s*[-|\/]?\s*(Broadcasting|Programming)$/i', trim($profClassDeptVal), $pm)) {
                            $profParsedGrade = strtoupper($pm[1]);
                            $profParsedRoom = $pm[2];
                            $profParsedDivision = (strtolower($pm[3]) === 'broadcasting') ? 'Broadcasting' : 'Programming';
                        }
                    }
                    $profSelectedGrade = old('class_grade', $profParsedGrade);
                    $profSelectedRoom = old('class_room', $profParsedRoom);
                    $profSelectedDivision = old('division', $profParsedDivision);
                    ?>

                    <div class="col-md-12">
                        <label class="form-label text-secondary small fw-medium">Kelas, Ruang & Divisi</label>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <select name="class_grade" class="form-select bg-dark text-white border-secondary border-opacity-50">
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php foreach (['X', 'XI', 'XII'] as $grade): ?>
                                        <option value="<?= $grade ?>" <?= $profSelectedGrade === $grade ? 'selected' : '' ?>>Kelas <?= $grade ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="class_room" class="form-select bg-dark text-white border-secondary border-opacity-50">
                                    <option value="">-- Pilih Ruang --</option>
                                    <?php for ($r = 1; $r <= 10; $r++): ?>
                                        <option value="<?= $r ?>" <?= $profSelectedRoom == $r ? 'selected' : '' ?>>Ruang <?= $r ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="division" class="form-select bg-dark text-white border-secondary border-opacity-50">
                                    <option value="">-- Pilih Divisi --</option>
                                    <?php foreach (['Broadcasting', 'Programming'] as $div): ?>
                                        <option value="<?= $div ?>" <?= $profSelectedDivision === $div ? 'selected' : '' ?>><?= $div ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium">Nomor WhatsApp / HP</label>
                        <input type="text" name="phone" class="form-control" value="<?= esc($user['phone']) ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium"><i class="fa-solid fa-cake-candles text-warning me-1"></i> Tanggal Lahir <span class="text-secondary opacity-75 style-tiny"></span></label>
                        <input type="date" name="birth_date" class="form-control bg-dark text-white border-secondary border-opacity-50" value="<?= esc($user['birth_date'] ?? '') ?>">
                        <small class="text-secondary style-tiny d-block mt-0.5">Dapatkan ucapan selamat ultah otomatis di Beranda & Dashboard!</small>
                    </div>

                    <div class="col-12">
                        <label class="form-label text-secondary small fw-medium"><i class="fa-solid fa-location-dot text-danger me-1"></i> Alamat Tempat Tinggal <span class="text-secondary opacity-75 style-tiny"></span></label>
                        <input type="text" name="address" class="form-control" value="<?= esc($user['address'] ?? '') ?>" placeholder="Contoh: Jl. Raya Ciapus No. 12, Tamansari, Bogor">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-medium"><i class="fa-brands fa-instagram text-danger me-1"></i> Instagram <span class="text-secondary opacity-75 style-tiny">(Opsional)</span></label>
                        <input type="text" name="social_instagram" class="form-control" value="<?= esc($user['social_instagram'] ?? '') ?>" placeholder="@username">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-medium"><i class="fa-brands fa-tiktok text-light me-1"></i> TikTok <span class="text-secondary opacity-75 style-tiny">(Opsional)</span></label>
                        <input type="text" name="social_tiktok" class="form-control" value="<?= esc($user['social_tiktok'] ?? '') ?>" placeholder="@username TikTok">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-medium"><i class="fa-brands fa-facebook text-primary me-1"></i> Facebook <span class="text-secondary opacity-75 style-tiny">(Opsional)</span></label>
                        <input type="text" name="social_facebook" class="form-control" value="<?= esc($user['social_facebook'] ?? '') ?>" placeholder="Nama Akun / URL Facebook">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label text-secondary small fw-medium">Password Baru (Opsional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ubah">
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top border-secondary border-opacity-25">
                    <button type="submit" class="btn btn-red px-4">
                        <i class="fa-solid fa-save me-1"></i> Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if (!empty($user['avatar'])): ?>
    <!-- Modal Preview Foto Profil Full -->
    <div class="modal fade" id="avatarFullModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
                <div class="modal-header border-bottom border-secondary border-opacity-25">
                    <h5 class="modal-title font-heading"><i class="fa-solid fa-image text-danger me-2"></i> Foto Profil Full - <?= esc($user['full_name']) ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <img src="<?= base_url($user['avatar']) ?>" alt="Foto Full" class="img-fluid rounded-3 shadow-lg border border-secondary border-opacity-50" style="max-height: 75vh; object-fit: contain;">
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25 justify-content-between">
                    <a href="<?= base_url($user['avatar']) ?>" target="_blank" class="btn btn-sm btn-outline-light">
                        <i class="fa-solid fa-external-link me-1"></i> Buka File Asli
                    </a>
                    <button type="button" class="btn btn-saas-dark btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Modal Pratinjau & Potong (Crop) Foto Profil -->
<div class="modal fade" id="avatarCropModal" tabindex="-1" aria-labelledby="avatarCropModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-danger border-opacity-50 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-bottom border-secondary border-opacity-25 py-2.5 px-3 bg-danger bg-opacity-10">
                <h5 class="modal-title font-heading fs-6 fw-bold text-white d-flex align-items-center gap-2" id="avatarCropModalLabel">
                    <i class="fa-solid fa-crop-simple text-danger"></i> Pratinjau & Potong Foto Profil
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="cancelStagedAvatar()"></button>
            </div>
            <div class="modal-body p-3 text-center">
                <p class="text-secondary style-tiny mb-2">
                    Geser (drag), perbesar (zoom), atau putar foto Anda agar pas di dalam lingkaran profil.
                </p>

                <!-- Crop Canvas Container -->
                <div class="position-relative mx-auto bg-black rounded-4 overflow-hidden border border-secondary border-opacity-50" style="width: 280px; height: 280px; touch-action: none; cursor: grab;" id="cropCanvasWrapper">
                    <canvas id="cropCanvas" width="280" height="280" class="d-block w-100 h-100"></canvas>
                    <!-- Circular Overlay Mask with Red Dashed Border -->
                    <div class="position-absolute top-0 start-0 w-100 h-100 pointer-events-none" style="pointer-events: none; border-radius: 50%; box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.65); border: 2px dashed rgba(220, 38, 38, 0.9);"></div>
                </div>

                <!-- Controls: Zoom & Rotate -->
                <div class="mt-3 px-2">
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                        <button type="button" class="btn btn-sm btn-saas-dark text-white px-2.5 py-1" onclick="adjustCropZoom(-0.15)" title="Perkecil">
                            <i class="fa-solid fa-magnifying-glass-minus"></i>
                        </button>
                        <input type="range" class="form-range flex-grow-1" id="cropZoomSlider" min="0.2" max="3.5" step="0.05" value="1.0" oninput="onCropZoomChange(this.value)">
                        <button type="button" class="btn btn-sm btn-saas-dark text-white px-2.5 py-1" onclick="adjustCropZoom(0.15)" title="Perbesar">
                            <i class="fa-solid fa-magnifying-glass-plus"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-saas-dark text-warning px-2.5 py-1 font-monospace style-tiny" onclick="rotateCropImage()" title="Putar 90 Derajat">
                            <i class="fa-solid fa-rotate-right me-1"></i> Putar
                        </button>
                    </div>
                    <small class="text-secondary style-tiny d-block">
                        <i class="fa-solid fa-hand me-1"></i> Klik & seret mouse / usap layar untuk memindahkan posisi foto
                    </small>
                </div>
            </div>
            <div class="modal-footer border-top border-secondary border-opacity-25 py-2 px-3 justify-content-between">
                <button type="button" class="btn btn-sm btn-saas-dark text-secondary font-monospace" data-bs-dismiss="modal" onclick="cancelStagedAvatar()">
                    Batal
                </button>
                <button type="button" class="btn btn-sm btn-red font-monospace fw-bold px-3 shadow" onclick="applyCroppedAvatar()">
                    <i class="fa-solid fa-check me-1"></i> Terapkan & Gunakan Foto
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        const uuid = "<?= esc($user['member_uuid']) ?>";
        if (typeof QRious !== 'undefined') {
            new QRious({
                element: document.getElementById("profile-qr-canvas"),
                value: uuid,
                size: 160,
                level: 'H'
            });
        }
    });

    function downloadOnlyQR() {
        const qrCanvas = document.getElementById('profile-qr-canvas');
        if (!qrCanvas) {
            alert('QR Canvas belum siap.');
            return;
        }

        const outCanvas = document.createElement('canvas');
        outCanvas.width = 500;
        outCanvas.height = 580;
        const ctx = outCanvas.getContext('2d');

        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, 500, 580);

        ctx.strokeStyle = '#dc2626';
        ctx.lineWidth = 10;
        ctx.strokeRect(5, 5, 490, 570);

        ctx.fillStyle = '#0d0d12';
        ctx.font = 'bold 22px "Plus Jakarta Sans", sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('MULTIMEDIA CLUB', 250, 45);

        ctx.fillStyle = '#dc2626';
        ctx.font = 'bold 14px "Plus Jakarta Sans", sans-serif';
        ctx.fillText('SMAN 1 TAMANSARI • MEMBER QR', 250, 70);

        ctx.drawImage(qrCanvas, 75, 95, 350, 350);

        ctx.fillStyle = '#111827';
        ctx.font = 'bold 18px "Plus Jakarta Sans", sans-serif';
        ctx.fillText('<?= esc($user['full_name']) ?>', 250, 485);

        ctx.fillStyle = '#6b7280';
        ctx.font = '14px monospace';
        ctx.fillText('@<?= esc($user['username']) ?> • NIS: <?= esc($user['nis_nip'] ?: '-') ?>', 250, 515);

        ctx.fillStyle = '#dc2626';
        ctx.font = 'bold 11px monospace';
        ctx.fillText('v<?= esc($user['qr_version']) ?> • <?= esc($user['member_uuid']) ?>', 250, 545);

        const link = document.createElement('a');
        link.download = 'QR_MMC_<?= esc($user['username']) ?>.png';
        link.href = outCanvas.toDataURL('image/png');
        link.click();
    }

    function downloadIDCard() {
        const qrCanvas = document.getElementById('profile-qr-canvas');
        if (!qrCanvas) {
            alert('QR Canvas belum siap.');
            return;
        }

        const cardCanvas = document.createElement('canvas');
        cardCanvas.width = 600;
        cardCanvas.height = 960;
        const ctx = cardCanvas.getContext('2d');

        const bgGrad = ctx.createLinearGradient(0, 0, 600, 960);
        bgGrad.addColorStop(0, '#0f0f15');
        bgGrad.addColorStop(0.5, '#180507');
        bgGrad.addColorStop(1, '#0d0002');
        ctx.fillStyle = bgGrad;
        ctx.fillRect(0, 0, 600, 960);

        ctx.strokeStyle = 'rgba(220, 38, 38, 0.35)';
        ctx.lineWidth = 3;
        ctx.strokeRect(15, 15, 570, 930);

        const bannerGrad = ctx.createLinearGradient(0, 0, 600, 0);
        bannerGrad.addColorStop(0, '#b91c1c');
        bannerGrad.addColorStop(1, '#991b1b');
        ctx.fillStyle = bannerGrad;
        ctx.fillRect(15, 15, 570, 100);

        ctx.fillStyle = '#ffffff';
        ctx.font = '900 24px "Plus Jakarta Sans", sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('MULTIMEDIA CLUB', 300, 55);

        ctx.fillStyle = '#fecaca';
        ctx.font = '600 13px "Plus Jakarta Sans", sans-serif';
        ctx.fillText('SMAN 1 TAMANSARI • OFFICIAL MEMBER CARD', 300, 85);

        const avatarUrl = "<?= !empty($user['avatar']) ? base_url($user['avatar']) : '' ?>";
        const drawAvatarAndDetails = (imgObj) => {
            ctx.save();
            ctx.beginPath();
            ctx.arc(300, 220, 70, 0, Math.PI * 2, true);
            ctx.closePath();
            ctx.clip();

            if (imgObj) {
                let imgW = imgObj.width;
                let imgH = imgObj.height;
                let sWidth = imgW;
                let sHeight = imgH;
                let sx = 0;
                let sy = 0;

                if (imgW > imgH) {
                    sWidth = imgH;
                    sx = (imgW - imgH) / 2;
                } else if (imgH > imgW) {
                    sHeight = imgW;
                    sy = (imgH - imgW) / 2;
                }
                ctx.drawImage(imgObj, sx, sy, sWidth, sHeight, 230, 150, 140, 140);
            } else {
                ctx.fillStyle = '#dc2626';
                ctx.fillRect(230, 150, 140, 140);
                ctx.fillStyle = '#ffffff';
                ctx.font = 'bold 50px "Plus Jakarta Sans", sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText('<?= strtoupper(substr($user['full_name'], 0, 1)) ?>', 300, 235);
            }
            ctx.restore();

            ctx.strokeStyle = '#dc2626';
            ctx.lineWidth = 4;
            ctx.beginPath();
            ctx.arc(300, 220, 70, 0, Math.PI * 2, true);
            ctx.stroke();

            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 24px "Plus Jakarta Sans", sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('<?= esc($user['full_name']) ?>', 300, 325);

            ctx.fillStyle = '#ef4444';
            ctx.font = 'bold 15px monospace';
            ctx.fillText('@<?= esc($user['username']) ?>', 300, 352);

            ctx.fillStyle = '#161b22';
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.15)';
            ctx.lineWidth = 1;
            ctx.beginPath();
            if (ctx.roundRect) {
                ctx.roundRect(50, 375, 500, 130, 12);
            } else {
                ctx.rect(50, 375, 500, 130);
            }
            ctx.fill();
            ctx.stroke();

            ctx.textAlign = 'left';
            ctx.font = '14px "Plus Jakarta Sans", sans-serif';

            ctx.fillStyle = '#9ca3af';
            ctx.fillText('NIS / NIP', 80, 410);
            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 15px monospace';
            ctx.fillText(': <?= esc($user['nis_nip'] ?: '-') ?>', 220, 410);

            <?php $roleDisplayName = $user['role_name'] ?? session()->get('role_name') ?? 'Anggota'; ?>
            ctx.font = '14px "Plus Jakarta Sans", sans-serif';
            ctx.fillStyle = '#9ca3af';
            ctx.fillText('Kelas & Divisi', 80, 445);
            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 15px "Plus Jakarta Sans", sans-serif';
            ctx.fillText(': <?= esc($user['class_dept'] ?: $roleDisplayName) ?>', 220, 445);

            ctx.fillStyle = '#9ca3af';
            ctx.font = '14px "Plus Jakarta Sans", sans-serif';
            ctx.fillText('Status', 80, 480);
            ctx.fillStyle = '#ef4444';
            ctx.font = 'bold 15px monospace';
            ctx.fillText(': <?= strtoupper(esc($roleDisplayName)) ?>', 220, 480);

            ctx.fillStyle = '#ffffff';
            ctx.beginPath();
            if (ctx.roundRect) {
                ctx.roundRect(165, 525, 270, 290, 16);
            } else {
                ctx.rect(165, 525, 270, 290);
            }
            ctx.fill();

            ctx.fillStyle = '#1f2937';
            ctx.font = 'bold 13px "Plus Jakarta Sans", sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('SCAN FOR MEMBER ATTENDANCE', 300, 550);

            ctx.drawImage(qrCanvas, 195, 565, 210, 210);

            ctx.fillStyle = '#dc2626';
            ctx.font = 'bold 11px monospace';
            ctx.fillText('VER: v<?= esc($user['qr_version']) ?> • MMC MEMBER', 300, 798);

            const ftGrad = ctx.createLinearGradient(0, 0, 600, 0);
            ftGrad.addColorStop(0, '#7f1d1d');
            ftGrad.addColorStop(1, '#991b1b');
            ctx.fillStyle = ftGrad;
            ctx.fillRect(15, 845, 570, 100);

            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 14px "Plus Jakarta Sans", sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('SMAN 1 TAMANSARI • MULTIMEDIA CLUB', 300, 885);

            ctx.fillStyle = '#fca5a5';
            ctx.font = '11px monospace';
            ctx.fillText('UUID: <?= esc($user['member_uuid']) ?>', 300, 912);

            const link = document.createElement('a');
            link.download = 'ID_Card_MMC_<?= esc($user['username']) ?>.png';
            link.href = cardCanvas.toDataURL('image/png');
            link.click();
        };

        if (avatarUrl && avatarUrl.indexOf('http') === -1) {
            const fullAvatarUrl = "<?= base_url() ?>" + avatarUrl.replace(/^\//, '');
            const img = new Image();
            img.crossOrigin = 'Anonymous';
            img.onload = () => drawAvatarAndDetails(img);
            img.onerror = () => drawAvatarAndDetails(null);
            img.src = fullAvatarUrl;
        } else if (avatarUrl) {
            const img = new Image();
            img.crossOrigin = 'Anonymous';
            img.onload = () => drawAvatarAndDetails(img);
            img.onerror = () => drawAvatarAndDetails(null);
            img.src = avatarUrl;
        } else {
            drawAvatarAndDetails(null);
        }
    }

    /* ==========================================================
       INTERACTIVE AVATAR CROP & PREVIEW ENGINE (MAX 20MB)
    ========================================================== */
    const originalAvatarSrc = "<?= avatar_url($user['avatar'], $user['full_name']) ?>";
    let cropImg = null;
    let cropZoom = 1.0;
    let cropRotation = 0; // 0, 90, 180, 270
    let cropOffsetX = 0;
    let cropOffsetY = 0;
    let isCropDragging = false;
    let dragStartX = 0;
    let dragStartY = 0;
    let cropModalInstance = null;

    function handleAvatarFileSelect(input) {
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];

        // Validate max 20MB
        const maxSizeBytes = 20 * 1024 * 1024;
        if (file.size > maxSizeBytes) {
            alert('Ukuran foto terlalu besar! Maksimal ukuran file foto adalah 20MB.');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            cropImg = new Image();
            cropImg.onload = function () {
                // Initialize default crop settings
                cropZoom = 1.0;
                cropRotation = 0;
                cropOffsetX = 0;
                cropOffsetY = 0;

                const slider = document.getElementById('cropZoomSlider');
                if (slider) slider.value = '1.0';

                // Setup and show modal
                const modalEl = document.getElementById('avatarCropModal');
                if (modalEl) {
                    cropModalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
                    cropModalInstance.show();
                    setTimeout(initCropCanvasInteractions, 250);
                }
            };
            cropImg.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    function initCropCanvasInteractions() {
        const canvas = document.getElementById('cropCanvas');
        if (!canvas) return;

        renderCropCanvas();

        // Mouse Drag Handlers
        canvas.onmousedown = function (e) {
            isCropDragging = true;
            dragStartX = e.clientX - cropOffsetX;
            dragStartY = e.clientY - cropOffsetY;
            canvas.style.cursor = 'grabbing';
        };

        window.onmousemove = function (e) {
            if (!isCropDragging) return;
            cropOffsetX = e.clientX - dragStartX;
            cropOffsetY = e.clientY - dragStartY;
            renderCropCanvas();
        };

        window.onmouseup = function () {
            if (isCropDragging) {
                isCropDragging = false;
                if (canvas) canvas.style.cursor = 'grab';
            }
        };

        // Touch Drag Handlers for Smartphones & Tablets
        canvas.ontouchstart = function (e) {
            if (e.touches && e.touches.length === 1) {
                isCropDragging = true;
                dragStartX = e.touches[0].clientX - cropOffsetX;
                dragStartY = e.touches[0].clientY - cropOffsetY;
            }
        };

        canvas.ontouchmove = function (e) {
            if (!isCropDragging || !e.touches || e.touches.length !== 1) return;
            e.preventDefault();
            cropOffsetX = e.touches[0].clientX - dragStartX;
            cropOffsetY = e.touches[0].clientY - dragStartY;
            renderCropCanvas();
        };

        canvas.ontouchend = function () {
            isCropDragging = false;
        };
    }

    function renderCropCanvas() {
        const canvas = document.getElementById('cropCanvas');
        if (!canvas || !cropImg) return;
        const ctx = canvas.getContext('2d');
        const cWidth = canvas.width;
        const cHeight = canvas.height;

        ctx.clearRect(0, 0, cWidth, cHeight);
        ctx.save();

        // Translate to center of canvas for rotation & zoom
        ctx.translate(cWidth / 2 + cropOffsetX, cHeight / 2 + cropOffsetY);
        ctx.rotate((cropRotation * Math.PI) / 180);

        // Calculate base scale to fit minimum dimension inside 280x280
        const minDim = Math.min(cropImg.width, cropImg.height);
        const baseScale = (280 / minDim) * cropZoom;

        const drawW = cropImg.width * baseScale;
        const drawH = cropImg.height * baseScale;

        ctx.drawImage(cropImg, -drawW / 2, -drawH / 2, drawW, drawH);
        ctx.restore();
    }

    function onCropZoomChange(val) {
        cropZoom = parseFloat(val) || 1.0;
        renderCropCanvas();
    }

    function adjustCropZoom(delta) {
        const slider = document.getElementById('cropZoomSlider');
        let current = parseFloat(slider ? slider.value : cropZoom) || 1.0;
        current = Math.min(3.5, Math.max(0.2, current + delta));
        if (slider) slider.value = current.toFixed(2);
        cropZoom = current;
        renderCropCanvas();
    }

    function rotateCropImage() {
        cropRotation = (cropRotation + 90) % 360;
        renderCropCanvas();
    }

    function applyCroppedAvatar() {
        if (!cropImg) return;

        // Render high-res 500x500 output canvas
        const outCanvas = document.createElement('canvas');
        outCanvas.width = 500;
        outCanvas.height = 500;
        const outCtx = outCanvas.getContext('2d');

        outCtx.save();
        // Scale ratio from 280 preview canvas to 500 export canvas
        const ratio = 500 / 280;

        outCtx.translate(250 + cropOffsetX * ratio, 250 + cropOffsetY * ratio);
        outCtx.rotate((cropRotation * Math.PI) / 180);

        const minDim = Math.min(cropImg.width, cropImg.height);
        const baseScale = (280 / minDim) * cropZoom * ratio;

        const drawW = cropImg.width * baseScale;
        const drawH = cropImg.height * baseScale;

        outCtx.drawImage(cropImg, -drawW / 2, -drawH / 2, drawW, drawH);
        outCtx.restore();

        const croppedDataUrl = outCanvas.toDataURL('image/png', 0.95);

        // Update hidden form payload & thumbnail image preview
        const hiddenInput = document.getElementById('avatarCroppedBase64');
        const previewImg = document.getElementById('currentAvatarPreview');
        const stagedStatus = document.getElementById('avatarStagedStatus');
        const removeCheck = document.getElementById('removeAvatarCheck');

        if (hiddenInput) hiddenInput.value = croppedDataUrl;
        if (previewImg) previewImg.src = croppedDataUrl;
        if (stagedStatus) stagedStatus.style.display = 'inline-block';
        if (removeCheck) removeCheck.checked = false;

        // Hide crop modal
        if (cropModalInstance) {
            cropModalInstance.hide();
        } else {
            const modalEl = document.getElementById('avatarCropModal');
            if (modalEl) bootstrap.Modal.getInstance(modalEl)?.hide();
        }
    }

    function reopenCropModal() {
        if (!cropImg) {
            const fileInput = document.getElementById('avatarFileInput');
            if (fileInput) fileInput.click();
            return;
        }
        const modalEl = document.getElementById('avatarCropModal');
        if (modalEl) {
            cropModalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            cropModalInstance.show();
            setTimeout(initCropCanvasInteractions, 250);
        }
    }

    function cancelStagedAvatar() {
        const hiddenInput = document.getElementById('avatarCroppedBase64');
        const fileInput = document.getElementById('avatarFileInput');
        const previewImg = document.getElementById('currentAvatarPreview');
        const stagedStatus = document.getElementById('avatarStagedStatus');

        if (hiddenInput) hiddenInput.value = '';
        if (fileInput) fileInput.value = '';
        if (previewImg) previewImg.src = originalAvatarSrc;
        if (stagedStatus) stagedStatus.style.display = 'none';

        if (cropModalInstance) {
            cropModalInstance.hide();
        } else {
            const modalEl = document.getElementById('avatarCropModal');
            if (modalEl) bootstrap.Modal.getInstance(modalEl)?.hide();
        }
    }

    function handleRemoveAvatarToggle(checkbox) {
        if (checkbox && checkbox.checked) {
            cancelStagedAvatar();
        }
    }
</script>
<?= $this->endSection() ?>
