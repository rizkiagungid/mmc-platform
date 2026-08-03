<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-white font-heading m-0">Pesan & Live Chat Inbox Kontak Website</h4>
        <p class="text-secondary small m-0">Daftar percakapan dan pesan masuk dari pengunjung website publik yang dapat dibalas langsung secara real-time</p>
    </div>
</div>

<div class="saas-card p-4">
    <?php if (empty($messages)): ?>
        <div class="text-center py-5 text-secondary">
            <i class="fa-solid fa-comments display-1 mb-3 opacity-25"></i>
            <h5 class="text-white font-heading">Kotak Masuk Chat Kosong</h5>
            <p class="small mb-0">Belum ada pesan masuk dari pengunjung website.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-dark table-dark-saas align-middle datatable-saas">
                <thead>
                    <tr>
                        <th>Pengirim</th>
                        <th>Subjek & Pesan Awal</th>
                        <th>Status Balasan</th>
                        <th>Waktu Masuk</th>
                        <th class="text-end">Aksi Chat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $msg): ?>
                        <tr>
                            <td>
                                <div class="fw-bold text-white"><?= esc($msg['sender_name']) ?></div>
                                <span class="text-secondary style-tiny font-monospace"><?= esc($msg['sender_email']) ?></span>
                            </td>
                            <td>
                                <div class="fw-semibold text-danger"><?= esc($msg['subject']) ?></div>
                                <p class="text-secondary small m-0 leading-relaxed"><?= esc(mb_strimwidth($msg['message'], 0, 80, '...')) ?></p>
                            </td>
                            <td>
                                <?php if ($msg['status'] === 'replied'): ?>
                                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 font-monospace">TERBALAS (REPLIED)</span>
                                <?php else: ?>
                                    <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25 font-monospace">BELUM DIBALAS</span>
                                <?php endif; ?>
                            </td>
                            <td><span class="font-monospace text-secondary"><?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?></span></td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-red" data-bs-toggle="modal" data-bs-target="#chatModal<?= $msg['id'] ?>">
                                    <i class="fa-solid fa-comments me-1"></i> Buka Chat Thread
                                </button>
                            </td>
                        </tr>

                        <!-- Live Chat Modal -->
                        <div class="modal fade" id="chatModal<?= $msg['id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content bg-dark text-white border border-secondary border-opacity-25">
                                    <div class="modal-header border-bottom border-secondary border-opacity-25">
                                        <div>
                                            <h5 class="modal-title font-heading"><i class="fa-solid fa-comments text-danger me-2"></i> Percakapan dengan <?= esc($msg['sender_name']) ?></h5>
                                            <small class="text-secondary font-monospace"><?= esc($msg['sender_email']) ?> | Subjek: <?= esc($msg['subject']) ?></small>
                                        </div>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    
                                    <div class="modal-body bg-black p-3">
                                        <!-- Original Visitor Message -->
                                        <div class="mb-3 text-start">
                                            <div class="d-inline-block p-3 rounded-3 bg-secondary bg-opacity-25 text-white max-w-75 border border-secondary border-opacity-25">
                                                <div class="fw-bold small text-danger mb-1"><?= esc($msg['sender_name']) ?> (Pengunjung)</div>
                                                <div><?= esc($msg['message']) ?></div>
                                                <div class="text-secondary style-tiny mt-1 font-monospace"><?= date('H:i', strtotime($msg['created_at'])) ?></div>
                                            </div>
                                        </div>

                                        <!-- Chat Replies Thread -->
                                        <?php if (!empty($msg['replies'])): ?>
                                            <?php foreach ($msg['replies'] as $rep): ?>
                                                <div class="mb-3 <?= $rep['sender_type'] === 'admin' ? 'text-end' : 'text-start' ?>">
                                                    <div class="d-inline-block p-3 rounded-3 <?= $rep['sender_type'] === 'admin' ? 'bg-danger text-white' : 'bg-secondary bg-opacity-25 text-white border border-secondary border-opacity-25' ?> max-w-75">
                                                        <div class="fw-bold small mb-1"><?= esc($rep['sender_name']) ?> (<?= ucfirst($rep['sender_type']) ?>)</div>
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
                                            <input type="text" name="reply_text" class="form-control" placeholder="Tulis balasan pesan untuk pengunjung..." required>
                                            <button type="submit" class="btn btn-red px-4 flex-shrink-0">
                                                <i class="fa-solid fa-paper-plane me-1"></i> Balas Chat
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
