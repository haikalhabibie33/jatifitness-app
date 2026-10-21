<div class="modal fade" id="detailRequestModal" tabindex="-1" aria-labelledby="detailRequestModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="detailRequestModalLabel" data-testid="detail-modal-title">
          <i class="bi bi-eye me-1"></i>
          Request Detail
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">

        <div class="row g-3">

          <div class="col-md-6">
            <div class="border rounded p-3 h-100">
              <h5 class="fw-bold mb-3">Customer Information</h5>

              <div class="mb-3">
                <div class="fw-semibold">Customer Name</div>
                <div class="text-muted small" id="detailName" data-testid="detail-customer-name">-</div>
              </div>

              <div class="mb-3">
                <div class="fw-semibold">Gender</div>
                <div class="text-muted small" id="detailGender" data-testid="detail-gender">-</div>
              </div>

              <div class="mb-3">
                <div class="fw-semibold">Date of Birth</div>
                <div class="text-muted small" id="detailDob" data-testid="detail-dob">-</div>
              </div>

              <div class="mb-3">
                <div class="fw-semibold">Phone</div>
                <div class="text-muted small" id="detailPhone" data-testid="detail-phone">-</div>
              </div>

              <div class="mb-3">
                <div class="fw-semibold">Email</div>
                <div class="text-muted small" id="detailEmail" data-testid="detail-email">-</div>
              </div>

              <div class="mb-3">
                <div class="fw-semibold">Request Type</div>
                <div class="fw-bold text-danger" id="detailType" data-testid="detail-request-type">-</div>
              </div>

              <div class="mb-0">
                <div class="fw-semibold">Member Type</div>
                <div class="fw-bold text-danger" id="detailMemberType" data-testid="detail-member-type">-</div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="border rounded p-3 h-100">
              <h5 class="fw-bold mb-3">Membership Details</h5>

              <div class="mb-3">
                <div class="fw-semibold">Membership Plan</div>
                <div class="text-muted small" id="detailPlanName" data-testid="detail-membership-plan">-</div>
              </div>

              <div class="mb-3">
                <div class="fw-semibold">Duration</div>
                <div class="text-muted small" id="detailDuration" data-testid="detail-duration">-</div>
              </div>

              <div class="mb-3">
                <div class="fw-semibold">Quantity</div>
                <div class="text-muted small" id="detailQty" data-testid="detail-quantity">-</div>
              </div>

              <div class="mb-3">
                <div class="fw-semibold">Total Price</div>
                <div class="fw-bold text-danger" id="detailPrice" data-testid="detail-total-price">-</div>
              </div>

              <div class="mb-3">
                <div class="fw-semibold">Payment Method</div>
                <div class="fw-bold text-danger" id="detailPaymentMethod" data-testid="detail-payment-method">-</div>
              </div>

              <div class="mb-0">
                <div class="fw-semibold">Payment Status</div>
                <div class="fw-bold text-danger" id="detailPaymentStatus" data-testid="detail-payment-status">-</div>
              </div>
            </div>
          </div>

        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-testid="detail-close-button">
          Close
        </button>
      </div>

    </div>
  </div>
</div>