<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="row g-4 justify-content-center">
    <div class="col-lg-5">
        <!-- Permanent QR Card -->
        <div class="saas-card p-4 text-center border border-danger border-opacity-50 shadow-lg">
            <h5 class="text-white font-heading mb-1">Permanent Member QR Code</h5>
            <p class="text-secondary small mb-3">Tunjukkan QR ini ke Operator saat absensi sesi pertemuan</p>

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

            <form action="<?= base_url('profile') ?>" method="POST">
                <?= csrf_field() ?>

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
                    $mProfParsedGrade = '';
                    $mProfParsedRoom = '';
                    $mProfParsedDivision = '';
                    $mProfClassDeptVal = $user['class_dept'] ?? '';
                    if (!empty($mProfClassDeptVal)) {
                        if (preg_match('/^(X|XI|XII)\s+([1-9]|10)\s*[-|\/]?\s*(Broadcasting|Programming)$/i', trim($mProfClassDeptVal), $pm)) {
                            $mProfParsedGrade = strtoupper($pm[1]);
                            $mProfParsedRoom = $pm[2];
                            $mProfParsedDivision = (strtolower($pm[3]) === 'broadcasting') ? 'Broadcasting' : 'Programming';
                        }
                    }
                    $mProfSelectedGrade = old('class_grade', $mProfParsedGrade);
                    $mProfSelectedRoom = old('class_room', $mProfParsedRoom);
                    $mProfSelectedDivision = old('division', $mProfParsedDivision);
                    ?>

                    <div class="col-md-12">
                        <label class="form-label text-secondary small fw-medium">Kelas, Ruang & Divisi</label>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <select name="class_grade" class="form-select bg-dark text-white border-secondary border-opacity-50">
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php foreach (['X', 'XI', 'XII'] as $grade): ?>
                                        <option value="<?= $grade ?>" <?= $mProfSelectedGrade === $grade ? 'selected' : '' ?>>Kelas <?= $grade ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="class_room" class="form-select bg-dark text-white border-secondary border-opacity-50">
                                    <option value="">-- Pilih Ruang --</option>
                                    <?php for ($r = 1; $r <= 10; $r++): ?>
                                        <option value="<?= $r ?>" <?= $mProfSelectedRoom == $r ? 'selected' : '' ?>>Ruang <?= $r ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="division" class="form-select bg-dark text-white border-secondary border-opacity-50">
                                    <option value="">-- Pilih Divisi --</option>
                                    <?php foreach (['Broadcasting', 'Programming'] as $div): ?>
                                        <option value="<?= $div ?>" <?= $mProfSelectedDivision === $div ? 'selected' : '' ?>><?= $div ?></option>
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        const uuid = "<?= esc($user['member_uuid']) ?>";
        const canvasEl = document.getElementById("profile-qr-canvas");
        if (canvasEl && typeof QRious !== 'undefined') {
            new QRious({
                element: canvasEl,
                value: uuid,
                size: 160,
                level: 'H'
            });
        } else if (canvasEl && typeof QRCode !== 'undefined') {
            new QRCode(canvasEl, {
                text: uuid,
                width: 160,
                height: 160,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
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
</script>
<?= $this->endSection() ?>
