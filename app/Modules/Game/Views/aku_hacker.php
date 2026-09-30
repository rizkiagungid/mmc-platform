<?= $this->extend('layouts/master_public') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/cyber_simulator.css?v=5.0') ?>">

<div id="cyberAppRoot" class="cyber-body py-3 py-lg-4">
    <div class="d-flex justify-content-center align-items-center py-5">
        <div class="spinner-border text-success" role="status">
            <span class="visually-hidden">Loading Cyber Simulator...</span>
        </div>
    </div>
</div>

<script>
    window.MMC_API_RECORD_SCORE = "<?= base_url('mini-game/api/record-score') ?>";
    window.MMC_GAME_HUB_URL = "<?= base_url('mini-game') ?>";
</script>
<script src="<?= base_url('assets/js/cyber_simulator.js?v=5.0') ?>"></script>
<?= $this->endSection() ?>
