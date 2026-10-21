<!-- Refund Detail Modal -->
<div class="modal fade" id="refundDetailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">
          <i class="bi bi-receipt-cutoff me-2"></i>
          Refund Transaction Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="row g-3">

          <!-- Customer Info -->
          <div class="col-12">
            <div class="border rounded p-3">
              <h6 class="fw-bold mb-3 text-danger">
                <i class="bi bi-person-vcard me-2"></i>
                Customer Information
              </h6>

              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label">Name</label>
                  <input type="text" id="detailName" class="form-control" readonly>
                </div>

                <div class="col-md-4">
                  <label class="form-label">Phone</label>
                  <input type="text" id="detailPhone" class="form-control" readonly>
                </div>

                <div class="col-md-4">
                  <label class="form-label">Email</label>
                  <input type="text" id="detailEmail" class="form-control" readonly>
                </div>
              </div>
            </div>
          </div>

          <!-- Package / Transaction Info -->
          <div class="col-12">
            <div class="border rounded p-3">
              <h6 class="fw-bold mb-3 text-danger">
                <i class="bi bi-box-seam me-2"></i>
                Package & Transaction
              </h6>

              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label">Purchase Type</label>
                  <input type="text" id="detailPurchaseType" class="form-control" readonly>
                </div>

                <div class="col-md-4">
                  <label class="form-label">Target Type</label>
                  <input type="text" id="detailTargetType" class="form-control" readonly>
                </div>

                <div class="col-md-4">
                  <label class="form-label">Plan</label>
                  <input type="text" id="detailPlan" class="form-control" readonly>
                </div>

                <div class="col-md-4">
                  <label class="form-label">Payment Method</label>
                  <input type="text" id="detailMethod" class="form-control" readonly>
                </div>

                <div class="col-md-4">
                  <label class="form-label">Payment Status</label>
                  <input type="text" id="detailStatus" class="form-control" readonly>
                </div>

                <div class="col-md-4">
                  <label class="form-label">Paid At</label>
                  <input type="text" id="detailPaidAt" class="form-control" readonly>
                </div>
              </div>
            </div>
          </div>

          <!-- Financial Summary -->
          <div class="col-12">
            <div class="border rounded p-3 bg-light">
              <h6 class="fw-bold mb-3 text-danger">
                <i class="bi bi-cash-stack me-2"></i>
                Financial Summary
              </h6>

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Total Amount</label>
                  <input type="text" id="detailTotal" class="form-control fw-bold text-danger" readonly>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Refund Eligibility</label>
                  <input type="text" id="detailEligibility" class="form-control" readonly>
                </div>
              </div>
            </div>
          </div>

          <!-- Refund Rule Info -->
          <div class="col-12">
            <div class="alert alert-warning mb-0">
              <i class="bi bi-exclamation-triangle me-2"></i>
              Refund can only be processed if this access has <strong>never appeared in access log</strong>.
            </div>
          </div>

        </div>
      </div>

      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-outline-secondary"
          data-bs-dismiss="modal">
          Close
        </button>

        <button
          type="button"
          class="btn btn-danger"
          id="openRefundFromDetailBtn"
          data-bs-dismiss="modal">
          <i class="bi bi-arrow-counterclockwise me-1"></i>
          Continue to Refund
        </button>
      </div>

    </div>
  </div>
</div>