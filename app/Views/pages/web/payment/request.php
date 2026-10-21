<?= $this->extend('pages/web/layout/payment_template') ?>
<?= $this->section('content') ?>

<main>
  <!-- ================= HERO ================= -->
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-6">

        <!-- PAGE HEADER -->
        <div class="text-center mb-4">
          <h2 class="fw-bold">Complete Your Payment</h2>
          <p class="text-muted">
            Please scan the QR code below to complete your payment.
          </p>
        </div>

        <!-- ORDER SUMMARY -->
        <div class="card shadow-sm mb-4">
          <div class="card-body">

            <h5 class="mb-3">Order Summary</h5>

            <ul class="list-unstyled mb-0">

              <li class="d-flex justify-content-between mb-2">
                <span>Request type as</span>
                <strong><?= esc($request['request_type']) ?></strong>
              </li>

              <li class="d-flex justify-content-between mb-2">
                <span>Package</span>
                <strong><?= esc($request['plan_name_snapshot']) ?></strong>
              </li>

              <li class="d-flex justify-content-between mb-2">
                <span>Duration</span>
                <strong><?= esc($request['duration_snapshot']) ?> days</strong>
              </li>

              <?php if (!empty($request['quantity'])) : ?>
              <li class="d-flex justify-content-between mb-2">
                <span>Quantity</span>
                <strong><?= esc($request['quantity']) ?></strong>
              </li>
              <?php endif; ?>

              <li class="d-flex justify-content-between border-top pt-3 mt-3">
                <span>Total</span>
                <strong>IDR <?= number_format($request['price_snapshot']) ?></strong>
              </li>
            </ul>

          </div>
        </div>

        <!-- QRIS PAYMENT -->
        <div class="card shadow-sm mb-4 text-center">
          <div class="card-body">
            <h5 class="mb-3">QRIS Payment</h5>

            <img
              src="<?= base_url('assets/img/qris_dummy.png') ?>"
              alt="QRIS Payment"
              class="img-fluid mb-3"
              style="max-width: 260px;"
            />

            <p class="text-muted mb-0">
              Scan using your favourite e-wallet or banking application.<br>
              This payment will be processed automatically.
            </p>
          </div>
        </div>

        <!-- INFO FOOTER -->
        <div class="text-center mt-4 text-muted small">
          Once the payment is received, your access will be activated automatically.<br>
          You may safely close this page.
        </div>

      </div>
    </div>
  </div>
</main>

<?= $this->endSection('content') ?>