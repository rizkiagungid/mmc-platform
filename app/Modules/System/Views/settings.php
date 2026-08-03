<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h4 class="text-white font-heading m-0"><i class="fa-solid fa-sliders text-danger me-2"></i> Pengaturan Sistem & Platform</h4>
        <p class="text-secondary small m-0">Kelola identitas website, meta tags SEO, aset logo, tag tambahan HTML, dan status pemeliharaan situs</p>
    </div>
</div>

<div class="saas-card p-4 p-md-5 col-lg-10 mx-auto">
    <form action="<?= base_url('admin/settings') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <!-- Nav Tabs Sections -->
        <ul class="nav nav-tabs mb-4 border-secondary border-opacity-25" id="settingsTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active fw-semibold" id="general-tab" data-bs-toggle="tab" data-bs-target="#general-pane" type="button">
                    <i class="fa-solid fa-globe text-danger me-1"></i> Informasi & Branding
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-semibold" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo-pane" type="button">
                    <i class="fa-solid fa-magnifying-glass text-info me-1"></i> Meta Tags & SEO
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-semibold" id="custom-tags-tab" data-bs-toggle="tab" data-bs-target="#custom-tags-pane" type="button">
                    <i class="fa-solid fa-code text-warning me-1"></i> Tag Custom Head & Body
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-semibold" id="maintenance-tab" data-bs-toggle="tab" data-bs-target="#maintenance-pane" type="button">
                    <i class="fa-solid fa-screwdriver-wrench text-danger me-1"></i> Pemeliharaan (Maintenance)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-semibold" id="security-tab" data-bs-toggle="tab" data-bs-target="#security-pane" type="button">
                    <i class="fa-solid fa-lock text-success me-1"></i> Keamanan & Presensi
                </button>
            </li>
        </ul>

        <div class="tab-content" id="settingsTabContent">

            <!-- 1. Informasi Umum & Branding -->
            <div class="tab-pane fade show active" id="general-pane" role="tabpanel">
                <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-globe text-danger me-2"></i> Identitas Website & Organisasi</h5>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium">Nama Website (Site Title) <span class="text-danger">*</span></label>
                        <input type="text" name="site_title" class="form-control" value="<?= esc($settings['site_title'] ?? 'Multimedia Club SMAN 1 Tamansari') ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium">Nama Sekolah / Instansi <span class="text-danger">*</span></label>
                        <input type="text" name="school_name" class="form-control" value="<?= esc($settings['school_name'] ?? 'SMAN 1 Tamansari') ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium">Email Resmi Kontak <span class="text-danger">*</span></label>
                        <input type="email" name="contact_email" class="form-control" value="<?= esc($settings['contact_email'] ?? 'multimedia@sman1tamansari.sch.id') ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium">Alamat Sekolah / Sekretariat MMC</label>
                        <input type="text" name="school_address" class="form-control" value="<?= esc($settings['school_address'] ?? 'Jl. Raya Tamansari No. 1, Kab. Bogor, Jawa Barat') ?>">
                    </div>

                    <!-- Logo Upload & URL -->
                    <div class="col-md-6 pt-2">
                        <label class="form-label text-white small fw-bold"><i class="fa-solid fa-image text-danger me-1"></i> Logo Website</label>
                        <input type="file" name="site_logo_file" class="form-control form-control-sm mb-1" accept="image/*">
                        <input type="text" name="site_logo" class="form-control form-control-sm font-monospace" placeholder="Atau masukan URL Logo..." value="<?= esc($settings['site_logo'] ?? 'assets/logo-mm-2023.png') ?>">
                        <?php if (!empty($settings['site_logo'])): ?>
                            <div class="mt-2 p-2 rounded bg-dark border border-secondary border-opacity-25 d-inline-block">
                                <span class="text-secondary style-tiny d-block mb-1">Preview Logo:</span>
                                <img src="<?= (strpos($settings['site_logo'], 'http') === 0) ? esc($settings['site_logo']) : base_url($settings['site_logo']) ?>" style="height: 40px;" class="bg-white p-1 rounded">
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Favicon Upload & URL -->
                    <div class="col-md-6 pt-2">
                        <label class="form-label text-white small fw-bold"><i class="fa-solid fa-icons text-info me-1"></i> Favicon / App Icon</label>
                        <input type="file" name="site_favicon_file" class="form-control form-control-sm mb-1" accept="image/*">
                        <input type="text" name="site_favicon" class="form-control form-control-sm font-monospace" placeholder="Atau masukan URL Favicon..." value="<?= esc($settings['site_favicon'] ?? 'assets/icons/favicon.png') ?>">
                        <?php if (!empty($settings['site_favicon'])): ?>
                            <div class="mt-2 p-2 rounded bg-dark border border-secondary border-opacity-25 d-inline-block">
                                <span class="text-secondary style-tiny d-block mb-1">Preview Favicon:</span>
                                <img src="<?= (strpos($settings['site_favicon'], 'http') === 0) ? esc($settings['site_favicon']) : base_url($settings['site_favicon']) ?>" style="height: 32px;" class="bg-white p-1 rounded">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- 2. Meta Tags & SEO -->
            <div class="tab-pane fade" id="seo-pane" role="tabpanel">
                <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-magnifying-glass text-info me-2"></i> Pengaturan Meta Tags & Optimasi SEO</h5>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-medium">Meta Tag Description</label>
                    <textarea name="meta_description" class="form-control" rows="3" placeholder="Deskripsi singkat website untuk mesin pencari Google..."><?= esc($settings['meta_description'] ?? 'Official website & member platform of Multimedia Club SMAN 1 Tamansari. Photography, Videography, Graphic Design, Web Development & Broadcast.') ?></textarea>
                    <div class="form-text text-secondary style-tiny">Disarankan 150-160 karakter untuk hasil pencarian Google optimal.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-medium">Meta Tags Keywords (Pisahkan dengan koma)</label>
                    <input type="text" name="meta_keywords" class="form-control" placeholder="multimedia, sman 1 tamansari, club, fotografi, videografi, koding" value="<?= esc($settings['meta_keywords'] ?? 'multimedia club, sman 1 tamansari, fotografi, videografi, desain grafis, web development, broadcasting') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-medium">Meta Tags Author</label>
                    <input type="text" name="meta_author" class="form-control" value="<?= esc($settings['meta_author'] ?? 'Multimedia Club SMAN 1 Tamansari') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-medium">Meta Tag Image / OpenGraph Banner (URL Preview Sosmed)</label>
                    <input type="text" name="meta_image" class="form-control font-monospace" placeholder="https://domain.com/assets/banner-og.png" value="<?= esc($settings['meta_image'] ?? 'assets/icons/icon-512.png') ?>">
                    <div class="form-text text-secondary style-tiny">Gambar pratinjau yang tampil saat tautan website dibagikan ke WhatsApp, Facebook, X, atau Instagram.</div>
                </div>
            </div>

            <!-- 3. Custom Head & Body Code Injection -->
            <div class="tab-pane fade" id="custom-tags-pane" role="tabpanel">
                <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-code text-warning me-2"></i> Injeksi Custom Script & HTML Tags</h5>
                <p class="text-secondary style-tiny mb-3">Sisipkan tag statistik seperti Google Analytics, Meta Pixel, Custom CSS, atau Chat Widget langsung ke dalam template.</p>

                <div class="mb-4">
                    <label class="form-label text-white small fw-bold">Tag Tambahan Sebelum <code>&lt;/head&gt;</code></label>
                    <textarea name="custom_head_tags" class="form-control font-monospace style-tiny text-light bg-black" rows="5" placeholder="<!-- Masukan tag <script>, <link>, atau <style> di sini -->"><?= esc($settings['custom_head_tags'] ?? '') ?></textarea>
                    <div class="form-text text-secondary style-tiny">Kode di atas akan dimasukkan secara otomatis sebelum tag penutup <code>&lt;/head&gt;</code> di seluruh halaman publik.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white small fw-bold">Tag Tambahan Sebelum <code>&lt;/body&gt;</code></label>
                    <textarea name="custom_body_tags" class="form-control font-monospace style-tiny text-light bg-black" rows="5" placeholder="<!-- Masukan tag widget chat atau script eksternal di sini -->"><?= esc($settings['custom_body_tags'] ?? '') ?></textarea>
                    <div class="form-text text-secondary style-tiny">Kode di atas akan dimasukkan secara otomatis sebelum tag penutup <code>&lt;/body&gt;</code> di seluruh halaman publik.</div>
                </div>
            </div>

            <!-- 4. Mode Pemeliharaan (Maintenance Mode) -->
            <div class="tab-pane fade" id="maintenance-pane" role="tabpanel">
                <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-screwdriver-wrench text-danger me-2"></i> Status Pemeliharaan Website (Maintenance Mode)</h5>

                <div class="form-check form-switch p-3 rounded-3 bg-dark border border-danger border-opacity-50 mb-3">
                    <input class="form-check-input ms-0 me-3" type="checkbox" name="maintenance_mode" id="maintenance_mode" value="1" <?= ($settings['maintenance_mode'] ?? '0') === '1' ? 'checked' : '' ?>>
                    <label class="form-check-label text-white fw-bold" for="maintenance_mode">
                        Situs web user dalam pemeliharaan (Maintenance Mode ON)
                        <span class="d-block text-secondary small fw-normal mt-1">
                            Ketika switch ini diaktifkan (ON), seluruh pengunjung website publik akan secara otomatis diarahkan ke halaman <strong>Situs Dalam Pemeliharaan</strong>.
                        </span>
                    </label>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-medium">Pesan Pemeliharaan Situs untuk Pengunjung</label>
                    <textarea name="maintenance_message" class="form-control" rows="3" placeholder="Pesan pemberitahuan untuk pengunjung..."><?= esc($settings['maintenance_message'] ?? 'Situs web Multimedia Club SMAN 1 Tamansari saat ini sedang dalam pemeliharaan sistem. Silakan kembali beberapa saat lagi.') ?></textarea>
                </div>
            </div>

            <!-- 5. Keamanan & Pendaftaran -->
            <div class="tab-pane fade" id="security-pane" role="tabpanel">
                <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-lock text-success me-2"></i> Pendaftaran Akun & Presensi QR</h5>

                <div class="form-check form-switch p-3 rounded-3 bg-dark border border-secondary border-opacity-25 mb-4">
                    <input class="form-check-input ms-0 me-3" type="checkbox" name="enable_registration" id="enable_registration" value="1" <?= ($settings['enable_registration'] ?? '1') === '1' ? 'checked' : '' ?>>
                    <label class="form-check-label text-white fw-semibold" for="enable_registration">
                        Buka Pendaftaran Akun Anggota Baru
                        <span class="d-block text-secondary small fw-normal mt-1">
                            Jika switch ini dinonaktifkan (OFF), akses pendaftaran akun baru (`/register`) akan ditutup.
                        </span>
                    </label>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-medium">Durasi Masa Berlaku Token QR Pertemuan (Menit)</label>
                    <input type="number" name="qr_expiry_minutes" class="form-control" value="<?= esc($settings['qr_expiry_minutes'] ?? 15) ?>" min="1" max="120" required>
                </div>
            </div>

        </div>

        <div class="d-flex justify-content-end gap-2 border-top border-secondary border-opacity-25 pt-4 mt-4">
            <button type="submit" class="btn btn-red px-5 py-2">
                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Seluruh Pengaturan
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
