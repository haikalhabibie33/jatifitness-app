<?= $this->include('pages/web/partials/head') ?>
<body>
<?= $this->include('pages/web/partials/preloader') ?>

<?= $this->include('pages/web/partials/navbar') ?>

<?= $this->renderSection('content') ?>


<?= $this->include('pages/web/partials/footer') ?>
<?= $this->include('pages/web/partials/back_to_top') ?>

<?= $this->include('pages/web/partials/scripts') ?>

<!-- CSS Asli Template (Tetap utuh) -->
<link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

<!-- CSS Warna Magenta-Teal -->
<link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">