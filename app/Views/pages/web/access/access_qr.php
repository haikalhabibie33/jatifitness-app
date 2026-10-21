<?= $this->extend('pages/web/layout/payment_template') ?>
<?= $this->section('content') ?>

<main class="container py-5 text-center">

  <!-- HEADER -->
  <h2 class="fw-bold mb-3" data-testid="access-mode-name">
    <?= $accessMode === 'CASUAL'
        ? 'Your Gym Access QR'
        : 'Membership Access QR'; ?>
  </h2>

  <p class="text-muted" data-testid="access-mode-information">
    <?= $accessMode === 'CASUAL'
        ? 'Present this QR code at the gym entrance to activate your access.'
        : 'Use this QR code to check in at the gym entrance.'; ?>
  </p>

  <?php if (!empty($access) && !empty($request)): ?>

    <!-- QR CODE -->
    <div class="d-flex justify-content-center my-4">
      <div id="qrCode" data-testid="qrCode"></div>
    </div>

    <!-- MANUAL TOKEN -->
    <div class="alert alert-secondary d-inline-block">
      <small>Manual Access Token</small><br>
      <strong data-testid="access-token"><?= esc($access['access_token']) ?></strong>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="row justify-content-center mt-3">
      <div class="col-12">
        <button id="downloadQr" class="btn" data-testid="download-qr-btn">
          Download QR Code
        </button>
      </div>
      <div class="col-12">
        <a href="/" class="btn mt-3" data-testid="back-to-home">
          Back to home
        </a>
      </div>
    </div>

    <!-- PACKAGE INFO -->
    <p class="text-muted mt-4">
      Package:
      <strong data-testid="membership-plan-name"><?= esc($request['plan_name_snapshot']) ?></strong><br>
      Duration:
      <strong data-testid="membership-duration">
        <?= esc($request['duration_snapshot'] * ($request['quantity'] ?? 1)) ?>
        day(s)
      </strong>
    </p>

  <?php else: ?>

    <!-- DEFENSIVE FALLBACK -->
    <div class="alert alert-danger mt-4" data-testid="access-code-not-found">
      Access code not found. Please contact support.
    </div>

  <?php endif; ?>

</main>

<!-- QR CODE LIB -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<?php if (!empty($access)): ?>
<script>
(function () {

  const accessToken = "<?= esc($access['access_token']) ?>";
  const qrContainer = document.getElementById('qrCode');

  if (!qrContainer || !accessToken) return;

  new QRCode(qrContainer, {
    text: accessToken,
    width: 256,
    height: 256,
    correctLevel: QRCode.CorrectLevel.H
  });

  const downloadBtn = document.getElementById('downloadQr');
  if (!downloadBtn) return;

  downloadBtn.addEventListener('click', () => {
    const img = qrContainer.querySelector('img');
    if (!img) return;

    const link = document.createElement('a');
    link.href = img.src;
    link.download = 'gym-access-qr.png';
    link.click();
  });

})();
</script>
<?php endif; ?>

<?= $this->endSection() ?>
