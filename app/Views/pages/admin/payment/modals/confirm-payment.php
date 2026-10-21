<div class="modal fade" id="confirmModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" data-testid="confirm-payment-modal-title">
          Confirm Payment
        </h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <strong id="modalName" data-testid="confirm-customer-name"></strong><br>
        <span id="modalPhone" data-testid="confirm-customer-phone"></span><br>

        <hr>

        <div class="mb-2">
          Payment Method:
          <strong>
            <span id="modalPaymentMethodLabel" class="badge bg-secondary">
              -
            </span>
          </strong>
        </div>

        <div class="mb-2">
          Total:
          <strong>
            Rp <span id="modalTotal" data-testid="confirm-total-price"></span>
          </strong>
        </div>

        <div id="cashConfirmInfo" class="alert alert-info mt-3 d-none">
          Cash payment will be confirmed immediately.
        </div>

        <div id="transferConfirmInfo" class="alert alert-warning mt-3 d-none">
          Please make sure transfer proof has been checked before confirming this payment.
        </div>

        <div id="qrisConfirmInfo" class="alert alert-secondary mt-3 d-none">
          QRIS payment confirmation via gateway is not active yet.
        </div>
      </div>

      <div class="modal-footer">
    <form
      method="POST"
      action="<?= base_url('/admin/payment/confirm') ?>"
    >
      <?= csrf_field() ?>

      <input type="hidden" name="token" id="modalToken">
      <input type="hidden" name="source" value="OFFLINE">
      <input type="hidden" name="payment_method" id="modalPaymentMethod" value="CASH">

      <button class="btn btn-danger" data-testid="confirm-yes-button" id="confirmPaymentSubmitBtn">
        <i class="bi bi-check-square me-2"></i>
        YES
      </button>
    </form>

    <button class="btn btn-secondary" data-bs-dismiss="modal" data-testid="confirm-cancel-button">
      Cancel
    </button>
  </div>

    </div>
  </div>
</div>