<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="text-white font-heading m-0"><i class="fa-solid fa-comments text-info me-2"></i>Kritik, Saran & Aspirasi (Anonim)</h4>
        <p class="text-secondary small m-0">Wadah penyampaian kritik, saran, dan masukan secara anonim baik dari member maupun pengurus Multimedia Club</p>
    </div>
    <button type="button" class="btn btn-red px-3" data-bs-toggle="modal" data-bs-target="#createFeedbackModal">
        <i class="fa-solid fa-paper-plane me-1"></i> Kirim Kritik & Saran Baru
    </button>
</div>

<!-- Modal Kirim Kritik & Saran Baru -->
<div class="modal fade" id="createFeedbackModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title font-heading"><i class="fa-solid fa-user-secret text-danger me-2"></i> Form Kritik & Saran Anonim</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/cms/messages/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="alert alert-dark border border-secondary border-opacity-25 py-2 px-3 small mb-3 text-info">
                        <i class="fa-solid fa-shield-halved me-1"></i> <strong>Pemberitahuan Kerahasiaan:</strong> Pesan ini terkirim secara 100% anonim. Identitas nama dan akun Anda tidak dipublikasikan.
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Kategori Masukan</label>
                        <select name="category" class="form-select bg-black text-white border-secondary border-opacity-25" required>
                            <option value="Kritik & Saran" selected>Kritik & Saran Umum</option>
                            <option value="Kegiatan & Program">Kegiatan & Program Kerja</option>
                            <option value="Fasilitas & Peralatan">Fasilitas & Peralatan Klub</option>
                            <option value="Pengurus & Evaluasi">Pengurus & Evaluasi</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Judul / Subjek (Opsional)</label>
                        <input type="text" name="subject" class="form-control bg-black text-white border-secondary border-opacity-25" placeholder="Contoh: Saran untuk perlengkapan kamera">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Isi Kritik & Saran <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control bg-black text-white border-secondary border-opacity-25" rows="5" placeholder="Tuliskan kritik, saran, atau ide kreatif Anda di sini secara jujur dan konstruktif..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-red btn-sm px-4">
                        <i class="fa-solid fa-paper-plane me-1"></i> Kirim Anonim
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="saas-card p-4">
    <?php if (empty($messages)): ?>
        <div class="text-center py-5 text-secondary">
            <i class="fa-solid fa-comments display-1 mb-3 opacity-25"></i>
            <h5 class="text-white font-heading">Belum Ada Kritik & Saran</h5>
            <p class="small mb-0">Belum ada kritik dan saran yang masuk. Klik tombol di atas untuk mengirim pesan anonim pertama Anda.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-dark table-dark-saas align-middle datatable-saas">
                <thead>
                    <tr>
                        <th>Pengirim</th>
                        <th>Kategori & Subjek</th>
                        <th>Status Balasan</th>
                        <th>Waktu Masuk</th>
                        <th class="text-end">Aksi Chat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $msg): ?>
                        <?php 
                            $isAnon = isset($msg['is_anonymous']) && $msg['is_anonymous'] == 1 || strtolower($msg['sender_name']) === 'anonim';
                        ?>
                        <tr>
                            <td>
                                <?php if ($isAnon): ?>
                                    <div class="fw-bold text-white d-flex align-items-center gap-1">
                                        <i class="fa-solid fa-user-secret text-info"></i> Anonim
                                    </div>
                                    <span class="badge bg-secondary bg-opacity-25 text-secondary font-monospace style-tiny">Dirahasiakan</span>
                                <?php else: ?>
                                    <div class="fw-bold text-white"><?= esc($msg['sender_name']) ?></div>
                                    <span class="text-secondary style-tiny font-monospace"><?= esc($msg['sender_email']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-25 font-monospace mb-1" style="font-size: 0.68rem;">
                                    <?= esc($msg['category'] ?? 'Kritik & Saran') ?>
                                </span>
                                <div class="fw-semibold text-danger"><?= esc($msg['subject']) ?></div>
                                <p class="text-secondary small m-0 leading-relaxed"><?= esc(mb_strimwidth($msg['message'], 0, 80, '...')) ?></p>
                            </td>
                            <td>
                                <?php if ($msg['status'] === 'replied'): ?>
                                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 font-monospace">TERBALAS</span>
                                <?php else: ?>
                                    <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25 font-monospace">BELUM DIBALAS</span>
                                <?php endif; ?>
                            </td>
                            <td><span class="font-monospace text-secondary"><?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?></span></td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-red" data-bs-toggle="modal" data-bs-target="#chatModal<?= $msg['id'] ?>">
                                    <i class="fa-solid fa-comments me-1"></i> Thread & Balasan
                                </button>
                            </td>
                        </tr>

                        <!-- Live Chat / Thread Modal -->
                        <div class="modal fade" id="chatModal<?= $msg['id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
                                    <div class="modal-header border-bottom border-secondary border-opacity-25">
                                        <div>
                                            <h5 class="modal-title font-heading"><i class="fa-solid fa-comments text-danger me-2"></i> Diskusi Kritik & Saran</h5>
                                            <small class="text-secondary font-monospace">
                                                Pengirim: <?= $isAnon ? 'Anonim (Dirahasiakan)' : esc($msg['sender_name']) ?> | Subjek: <?= esc($msg['subject']) ?>
                                            </small>
                                        </div>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    
                                    <div class="modal-body bg-black p-3">
                                        <!-- Original Message -->
                                        <div class="mb-3 text-start">
                                            <div class="d-inline-block p-3 rounded-3 bg-secondary bg-opacity-25 text-white max-w-75 border border-secondary border-opacity-25">
                                                <div class="fw-bold small text-danger mb-1"><i class="fa-solid fa-user-secret me-1"></i> <?= $isAnon ? 'Anonim' : esc($msg['sender_name']) ?></div>
                                                <div><?= esc($msg['message']) ?></div>
                                                <div class="text-secondary style-tiny mt-1 font-monospace"><?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?></div>
                                            </div>
                                        </div>

                                        <!-- Chat Replies Thread -->
                                        <?php if (!empty($msg['replies'])): ?>
                                            <?php foreach ($msg['replies'] as $rep): ?>
                                                <div class="mb-3 <?= $rep['sender_type'] === 'admin' ? 'text-end' : 'text-start' ?>">
                                                    <div class="d-inline-block p-3 rounded-3 <?= $rep['sender_type'] === 'admin' ? 'bg-danger text-white' : 'bg-secondary bg-opacity-25 text-white border border-secondary border-opacity-25' ?> max-w-75">
                                                        <div class="fw-bold small mb-1">
                                                            <?php if ($rep['sender_type'] === 'admin'): ?>
                                                                <i class="fa-solid fa-user-shield me-1"></i> <?= esc($rep['sender_name']) ?> (Pengurus)
                                                            <?php else: ?>
                                                                <i class="fa-solid fa-user-secret me-1"></i> Anonim
                                                            <?php endif; ?>
                                                        </div>
                                                        <div><?= esc($rep['message']) ?></div>
                                                        <div class="style-tiny mt-1 font-monospace <?= $rep['sender_type'] === 'admin' ? 'text-white-50' : 'text-secondary' ?>"><?= date('H:i', strtotime($rep['created_at'])) ?></div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Reply Form -->
                                    <div class="modal-footer border-top border-secondary border-opacity-25">
                                        <form action="<?= base_url('admin/cms/messages/reply/' . $msg['id']) ?>" method="POST" class="w-100 d-flex gap-2">
                                            <?= csrf_field() ?>
                                            <input type="text" name="reply_text" class="form-control bg-black text-white border-secondary border-opacity-25" placeholder="Tuliskan tanggapan atau balasan..." required>
                                            <button type="submit" class="btn btn-red px-4 flex-shrink-0">
                                                <i class="fa-solid fa-paper-plane me-1"></i> Kirim Tanggapan
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
