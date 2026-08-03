<?= $this->extend('layouts/master_admin') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <a href="<?= base_url('admin/meetings') ?>" class="btn btn-sm btn-saas-dark mb-2">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Pertemuan
    </a>
    <h4 class="text-white font-heading m-0">Buat Jadwal Pertemuan / Workshop Baru</h4>
</div>

<div class="saas-card p-4 col-lg-8">
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger border-0 bg-danger bg-opacity-25 text-danger small p-3 mb-4 rounded-3">
            <ul class="mb-0 ps-3">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/meetings/store') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label text-secondary small fw-medium">Judul Pertemuan / Agenda Workshop</label>
                <input type="text" name="title" class="form-control" placeholder="Contoh: Workshop Videography & Color Grading" value="<?= old('title') ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">Mentor / Pemateri</label>
                <input type="text" name="mentor" class="form-control" placeholder="Contoh: Muhammad Rizky Pratama" value="<?= old('mentor') ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label text-secondary small fw-medium">Lokasi Ruangan</label>
                <input type="text" name="location" class="form-control" placeholder="Contoh: Lab Komputer 2 SMAN 1 Tamansari" value="<?= old('location') ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label text-secondary small fw-medium">Tanggal Pertemuan</label>
                <input type="date" name="meeting_date" class="form-control" value="<?= old('meeting_date', date('Y-m-d')) ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label text-secondary small fw-medium">Jam Mulai</label>
                <input type="time" name="start_time" class="form-control" value="<?= old('start_time', '14:00') ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label text-secondary small fw-medium">Jam Selesai</label>
                <input type="time" name="end_time" class="form-control" value="<?= old('end_time', '16:30') ?>" required>
            </div>

            <div class="col-md-12">
                <label class="form-label text-secondary small fw-medium">Link Materi / Modul <span class="text-danger">*</span></label>
                <input type="url" name="learning_material" id="learning_material_input" class="form-control" placeholder="https://drive.google.com/..." value="<?= old('learning_material') ?>" required>
                
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="no_material_check" name="no_material" value="1" <?= old('no_material') ? 'checked' : '' ?>>
                    <label class="form-check-label text-secondary small" for="no_material_check">
                        Centang jika ingin mengosongkan link materi (Tanpa Modul / Link)
                    </label>
                </div>
            </div>

            <div class="col-md-12">
                <label class="form-label text-secondary small fw-medium">Deskripsi Agenda & Rincian Pembahasan</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Detail silabus dan hal yang perlu disiapkan siswa..."><?= old('description') ?></textarea>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top border-secondary border-opacity-25 d-flex gap-2">
            <button type="submit" class="btn btn-red px-4">
                <i class="fa-solid fa-save me-1"></i> Simpan Pertemuan
            </button>
            <a href="<?= base_url('admin/meetings') ?>" class="btn btn-saas-dark">Batal</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        function toggleMaterialInput() {
            const isChecked = $('#no_material_check').is(':checked');
            const $input = $('#learning_material_input');
            if (isChecked) {
                $input.val('').prop('required', false).prop('readonly', true).addClass('opacity-50 bg-black').attr('placeholder', 'Link materi dikosongkan');
            } else {
                $input.prop('required', true).prop('readonly', false).removeClass('opacity-50 bg-black').attr('placeholder', 'https://drive.google.com/...');
            }
        }

        $('#no_material_check').on('change', toggleMaterialInput);
        toggleMaterialInput();
    });
</script>
<?= $this->endSection() ?>
