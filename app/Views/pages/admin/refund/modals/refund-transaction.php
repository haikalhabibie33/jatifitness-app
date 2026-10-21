<!-- ✅ REFUND MODAL -->
<div class="modal fade" id="refundModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <form id="refundForm" method="post" action="">
        <?= csrf_field() ?>
        <input type="hidden" id="refundTransactionId" name="transaction_id">

        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Confirm Refund</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="transaction_id" id="refundTransactionId">

          <div class="mb-3">
            <div class="fw-semibold">Customer</div>
            <div id="refundName" class="text-muted small">-</div>
          </div>

          <div class="mb-3">
            <div class="fw-semibold">Phone</div>
            <div id="refundPhone" class="text-muted small">-</div>
          </div>

          <div class="mb-3">
            <div class="fw-semibold">Plan</div>
            <div id="refundPlan" class="text-muted small">-</div>
          </div>

          <div class="mb-3">
            <div class="fw-semibold">Total</div>
            <div id="refundTotal" class="fw-bold text-danger">-</div>
          </div>

          <div class="mb-3">
            <label class="form-label">Refund Reason</label>
            <textarea
              name="refund_reason"
              class="form-control"
              rows="3"
              placeholder="Write refund reason..."
              required></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cancel
          </button>
          <button type="submit" class="btn btn-danger">
            Confirm Refund
          </button>
        </div>

      </form>

    </div>
  </div>
</div>