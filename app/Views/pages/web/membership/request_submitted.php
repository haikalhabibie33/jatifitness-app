<?= $this->extend('pages/web/layout/payment_template') ?>
<?= $this->section('content') ?>

<main>
  <div class="page-review">

    <section class="services-area pt-5 pb-150">

      <!-- HEADER -->
      <section class="wantToWork-area w-padding">
        <div class="container">
          <div class="section-tittle">
            <span>Request Status</span>
            <h2>Request Submitted</h2>
          </div>
        </div>
      </section>

      <?php
        $onlineEnabled = (new \App\Models\PageSettingsModel())
            ->getBool('system', 'online_payment_enabled', true);

        // ✅ QR sekarang langsung ke Payment Desk
        $qrData = base_url('/admin/payment?token=' . $request['access_token']);
      ?>

      <div class="container text-center">

        <!-- ================= APPROVAL ================= -->
        <?php if ($helper->hasWhatsAppApprovalCTA()): ?>

          <h1><span class="badge bg-warning mb-3" data-testid="waiting-for-approval">Waiting for Approval</span></h1>
          <p>Please send your student ID via WhatsApp.</p>

          <a href="<?= $helper->getWhatsAppApprovalLink() ?>" target="_blank" class="btn" data-testid="send-whatsapp-link">
            Send WhatsApp
          </a>

        <!-- ================= READY PAYMENT ================= -->
        <?php elseif ($helper->isReadyPayment()): ?>

          <?php if ($helper->isCashPayment()): ?>

            <!-- ================= CASH PAYMENT ================= -->
            <h1>
              <span class="badge bg-warning mb-3" data-testid="cash-awaiting-payment-span">
                Awaiting Cash Payment
              </span>
            </h1>

            <p>Please visit the front desk or show this QR code to admin.</p>

            <div style="margin:20px 0;">
              <img 
                src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=<?= urlencode($qrData) ?>"
                alt="QR Code"
                data-testid="cash-qr-image"
                style="border-radius:10px;"
              >
            </div>

            <p data-testid="cash-access-token">
              <strong><?= esc($request['access_token']) ?></strong>
            </p>

            <small>Provide this code to admin if QR scan fails.</small>

          <?php elseif ($helper->isTransferPayment()): ?>

            <!-- ================= TRANSFER PAYMENT ================= -->
            <h1>
              <span class="badge bg-info mb-3" data-testid="transfer-awaiting-payment-span">
                Awaiting Bank Transfer
              </span>
            </h1>

            <p>Please complete your bank transfer payment and confirm it with admin.</p>

            <div style="margin:20px 0;">
              <img 
                src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=<?= urlencode($qrData) ?>"
                alt="QR Code"
                data-testid="transfer-qr-image"
                style="border-radius:10px;"
              >
            </div>

            <p data-testid="transfer-access-token">
              <strong><?= esc($request['access_token']) ?></strong>
            </p>

            <small>
              Show this code to admin after completing the transfer payment.
            </small>

          <?php elseif ($helper->isQrisPayment()): ?>

            <!-- ================= QRIS PAYMENT ================= -->
            <?php if ($onlineEnabled): ?>

              <h1>
                <span class="badge bg-info mb-3" data-testid="qris-ready-payment-span">
                  Ready for QRIS Payment
                </span>
              </h1>

              <p>You can proceed to QRIS online payment.</p>

              <img 
                src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=<?= urlencode($qrData) ?>"
                class="btn mt-3"
                data-testid="qris-payment-link">
                Proceed to QRIS Payment
              </a>

            <?php else: ?>

              <h1>
                <span class="badge bg-secondary mb-3" data-testid="qris-disabled-span">
                  QRIS Payment Unavailable
                </span>
              </h1>

              <p>QRIS payment is currently unavailable. Please contact admin.</p>

            <?php endif; ?>

          <?php else: ?>

            <!-- ================= FALLBACK PAYMENT ================= -->
            <h1>
              <span class="badge bg-warning mb-3" data-testid="unknown-payment-method-span">
                Awaiting Payment
              </span>
            </h1>

            <p>Please contact admin to complete your payment.</p>

            <p>
              <strong><?= esc($request['access_token']) ?></strong>
            </p>

          <?php endif; ?>

        <!-- ================= PAID ================= -->
        <?php elseif ($helper->isPaid()): ?>

          <h1><span class="badge bg-success mb-3" data-testid="payment-received">Payment Received</span></h1>
          <p>Your request is being processed.</p>

        <!-- ================= COMPLETED ================= -->
        <?php elseif ($helper->hasAccessQr()): ?>

          <h1><span class="badge bg-success mb-3" data-testid="payment-completed-span">Completed</span></h1>
          <p>Your access is now active.</p>

          <a href="<?= base_url('/payment/complete?token=' . $request['access_token']) ?>" 
             class="btn btn-dark mt-3" data-testid="view-access-qr-button">
            View Access QR
          </a>

        <!-- ================= REJECTED ================= -->
        <?php elseif ($helper->isRejected()): ?>

          <h1><span class="badge bg-danger mb-3" data-testid="rejected-request">Rejected</span></h1>
          <p>Your request has been rejected. Contact admin.</p>

        <?php endif; ?>

      </div>

    </section>
  </div>
</main>

<?= $this->endSection() ?>