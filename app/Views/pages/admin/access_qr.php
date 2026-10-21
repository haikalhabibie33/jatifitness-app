<?= $this->extend('pages/web/layout/payment_template') ?>
<?= $this->section('content') ?>

<main class="container py-5 text-center">

  <h2 class="fw-bold mb-2">Admin Access QR</h2>
  <p class="text-muted mb-4">Share or print this QR code for customer access.</p>

  <?php if (!empty($access) && !empty($request)): ?>

    <?php
      $phone = preg_replace('/^0/', '62', $request['phone']);

      $waMessage = urlencode(
          "Halo {$request['name']},\n\n" .
          "Pembayaran Anda sudah berhasil ✅\n\n" .
          "Silakan gunakan QR berikut:\n" .
          base_url('/payment/complete?token=' . $access['access_token']) .
          "\n\nTerima kasih 🙏"
      );
    ?>

    <!-- ✅ WRAP UNTUK DOWNLOAD -->
    <div id="qrCard">

      <div class="mb-3">
        <h1><?= esc($request['name']) ?></h1>
        <h2><?= esc($request['phone']) ?></h2>
      </div>

      <div id="qrWrapper" class="d-flex justify-content-center my-4">
        <div id="qrCode"></div>
      </div>

      <div class="text-muted small mb-3">
        <h3><?= esc(substr($access['access_token'], 0, 20)) ?>...</h3>
      </div>

      <div class="mt-4 text-muted small">
        <div>
          <h4><?= esc($request['plan_name_snapshot']) ?></h4>
        </div>
        <div>
          <h5><?= esc($request['duration_snapshot'] * ($request['quantity'] ?? 1)) ?> days</h5>
        </div>
      </div>

    </div>

    <!-- ✅ ACTION (DI LUAR CARD biar gak ikut gambar kalau mau) -->
    <div class="d-flex flex-column justify-content-center mt-4">
      <div class="mb-30">
        <button id="downloadQr" class="btn">
          Download QR
        </button>
      </div>
      <div>
        <a 
           href="<?= 'https://wa.me/' . $phone . '?text=' . $waMessage ?>"
           target="_blank"
           class="btn"
        >
          Send to WhatsApp
        </a>
      </div>


    </div>

  <?php else: ?>

    <div class="alert alert-danger mt-4">
      Access code not found
    </div>

  <?php endif; ?>

</main>

<!-- ✅ QR LIB -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script> -->
<script src="<?= base_url('assets/js/qrcode.min.js') ?>"></script>

<!-- ✅ HTML TO IMAGE -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<?php if (!empty($access)): ?>

<script>
  window.ACCESS_TOKEN = "<?= esc($access['access_token']) ?>";
</script>

<script src="<?= base_url('assets/js/admin-generate-qr-access.js') ?>"></script>

<?php endif; ?>

<?= $this->endSection() ?>