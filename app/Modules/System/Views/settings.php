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
                <button class="nav-link fw-semibold" id="footer-tab" data-bs-toggle="tab" data-bs-target="#footer-pane" type="button">
                    <i class="fa-solid fa-window-maximize text-primary me-1"></i> Tampilan Footer & Sosmed
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
            <li class="nav-item">
                <button class="nav-link fw-semibold" id="storage-tab" data-bs-toggle="tab" data-bs-target="#storage-pane" type="button">
                    <i class="fa-solid fa-hard-drive text-warning me-1"></i> Pembersihan Storage & Cache
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

            <!-- 2. Pengaturan Footer & Sosmed -->
            <div class="tab-pane fade" id="footer-pane" role="tabpanel">
                <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-window-maximize text-primary me-2"></i> Pengaturan Konten Footer & Media Sosial</h5>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium">Nama Brand Footer</label>
                        <input type="text" name="footer_brand_name" class="form-control" value="<?= esc($settings['footer_brand_name'] ?? 'MMC SMAN 1 Tamansari') ?>" placeholder="MMC SMAN 1 Tamansari">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium">Judul Kolom Navigasi</label>
                        <input type="text" name="footer_nav_title" class="form-control" value="<?= esc($settings['footer_nav_title'] ?? 'Navigasi Cepat') ?>">
                    </div>

                    <div class="col-12">
                        <label class="form-label text-secondary small fw-medium">Deskripsi Singkat Footer</label>
                        <textarea name="footer_about" class="form-control" rows="3" placeholder="Wadah kreativitas siswa SMAN 1 Tamansari..."><?= esc($settings['footer_about'] ?? 'Wadah kreativitas siswa SMAN 1 Tamansari dalam bidang videografi, fotografi, desain grafis, pemrograman web, dan penyiaran media digital.') ?></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium">Judul Kolom Kontak</label>
                        <input type="text" name="footer_contact_title" class="form-control" value="<?= esc($settings['footer_contact_title'] ?? 'Kontak & Lokasi') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium"><i class="fa-solid fa-location-dot text-danger me-1"></i> Teks Alamat / Lokasi Footer</label>
                        <input type="text" name="footer_address" class="form-control" value="<?= esc($settings['footer_address'] ?? 'SMAN 1 Tamansari, Kab. Bogor') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium"><i class="fa-solid fa-envelope text-danger me-1"></i> Email Kontak Footer</label>
                        <input type="email" name="footer_email" class="form-control" value="<?= esc($settings['footer_email'] ?? 'multimediasman1t@gmail.com') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium"><i class="fa-solid fa-phone text-danger me-1"></i> Telepon / WhatsApp Footer</label>
                        <input type="text" name="footer_phone" class="form-control" value="<?= esc($settings['footer_phone'] ?? '+62 812-3456-7890') ?>">
                    </div>

                    <!-- Tautan Media Sosial -->
                    <div class="col-12 pt-2">
                        <h6 class="text-white small fw-bold mb-3 border-bottom border-secondary border-opacity-25 pb-2"><i class="fa-solid fa-share-nodes text-info me-2"></i> Tautan Akun Media Sosial</h6>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium"><i class="fab fa-instagram text-danger me-1"></i> URL Instagram</label>
                        <input type="text" name="social_instagram" class="form-control font-monospace style-tiny" value="<?= esc($settings['social_instagram'] ?? '#') ?>" placeholder="https://instagram.com/multimedia_sman1t">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium"><i class="fab fa-youtube text-danger me-1"></i> URL YouTube</label>
                        <input type="text" name="social_youtube" class="form-control font-monospace style-tiny" value="<?= esc($settings['social_youtube'] ?? '#') ?>" placeholder="https://youtube.com/@multimediasman1t">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium"><i class="fab fa-tiktok text-light me-1"></i> URL TikTok</label>
                        <input type="text" name="social_tiktok" class="form-control font-monospace style-tiny" value="<?= esc($settings['social_tiktok'] ?? '#') ?>" placeholder="https://tiktok.com/@multimediasman1t">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-medium"><i class="fab fa-github text-light me-1"></i> URL GitHub</label>
                        <input type="text" name="social_github" class="form-control font-monospace style-tiny" value="<?= esc($settings['social_github'] ?? '#') ?>" placeholder="https://github.com/multimediasman1t">
                    </div>

                    <!-- Copyright Text -->
                    <div class="col-12 pt-2">
                        <label class="form-label text-secondary small fw-medium">Teks Hak Cipta / Copyright Footer</label>
                        <input type="text" name="footer_copyright" class="form-control" value="<?= esc($settings['footer_copyright'] ?? '&copy; {year} Multimedia Club SMAN 1 Tamansari. Built with CodeIgniter 4 & Dark SaaS UI.') ?>">
                        <div class="form-text text-secondary style-tiny">Gunakan kode <code>{year}</code> untuk secara otomatis menampilkan tahun saat ini secara dinamis.</div>
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

                </div>
            </div>

            <!-- 6. Pembersihan Storage & Cache -->
            <div class="tab-pane fade" id="storage-pane" role="tabpanel">
                <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-hard-drive text-warning me-2"></i> Pembersihan File Storage & Cache Sistem</h5>
                <p class="text-secondary small mb-4">Pantau kapasitas penyimpanan sementara (temporary files) dan bersihkan cache atau log sistem agar tidak membebani kapasitas server storage.</p>

                <div class="row g-3 mb-4">
                    <!-- Cache Card -->
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-secondary small font-monospace text-uppercase">Cache System</span>
                                    <i class="fa-solid fa-bolt text-warning fs-5"></i>
                                </div>
                                <h3 class="text-white font-heading mb-1"><?= esc($cacheStats['formatted_size'] ?? '0 B') ?></h3>
                                <p class="text-secondary style-tiny m-0"><?= esc($cacheStats['file_count'] ?? 0) ?> File Cache fisik terdeteksi</p>
                            </div>
                            <form action="<?= base_url('admin/system/clear-cache') ?>" method="POST" class="mt-3 form-clear-confirm" data-title="Bersihkan Cache Sistem?" data-text="Aksi ini akan menghapus file cache sementara aplikasi CodeIgniter.">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-outline-warning w-100 btn-sm font-monospace">
                                    <i class="fa-solid fa-broom me-1"></i> Bersihkan Cache
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Logs Card -->
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-secondary small font-monospace text-uppercase">Log Files System</span>
                                    <i class="fa-solid fa-file-lines text-info fs-5"></i>
                                </div>
                                <h3 class="text-white font-heading mb-1"><?= esc($logStats['formatted_size'] ?? '0 B') ?></h3>
                                <p class="text-secondary style-tiny m-0"><?= esc($logStats['file_count'] ?? 0) ?> File Log terdeteksi</p>
                            </div>
                            <form action="<?= base_url('admin/system/clear-logs') ?>" method="POST" class="mt-3 form-clear-confirm" data-title="Hapus File Logs System?" data-text="Aksi ini akan menghapus seluruh catatan log aktivitas error/system di folder writable/logs.">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-outline-info w-100 btn-sm font-monospace">
                                    <i class="fa-solid fa-trash-can me-1"></i> Hapus File Logs
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Total Temp Storage Card -->
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 bg-dark border border-danger border-opacity-25 h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-secondary small font-monospace text-uppercase">Total Temp Storage</span>
                                    <i class="fa-solid fa-box-archive text-danger fs-5"></i>
                                </div>
                                <h3 class="text-white font-heading mb-1"><?= esc($totalStats['formatted_size'] ?? '0 B') ?></h3>
                                <p class="text-secondary style-tiny m-0"><?= esc($totalStats['file_count'] ?? 0) ?> Total File Temp (Cache + Logs)</p>
                            </div>
                            <form action="<?= base_url('admin/system/clear-all-storage') ?>" method="POST" class="mt-3 form-clear-confirm" data-title="Pembersihan Total Storage Temp?" data-text="Aksi ini akan menghapus sekaligus seluruh file cache dan log sistem.">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-red w-100 btn-sm font-monospace">
                                    <i class="fa-solid fa-dumpster-fire me-1"></i> Bersihkan Seluruh Temp
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="alert alert-dark border border-secondary border-opacity-25 style-tiny mb-0">
                    <i class="fa-solid fa-circle-info text-info me-1"></i> Pembersihan cache dan log aman dilakukan secara berkala untuk membebaskan ruang penyimpanan server tanpa mempengaruhi database maupun berkas unggahan publik.
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

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('.form-clear-confirm').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            const title = $(form).data('title') || 'Bersihkan Storage?';
            const text = $(form).data('text') || 'Apakah Anda yakin ingin melanjutkan?';

            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#27272a',
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                background: '#121218',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>
