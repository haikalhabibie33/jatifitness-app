<div class="modal fade" id="approveRequestModal" tabindex="-1" aria-labelledby="approveRequestModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <form id="approveRequestForm" action="" method="POST">
        <?=  csrf_field() ?>
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="approveRequestModalLabel" data-testid="approve-modal-title">
            <i class="bi bi-check-circle me-1"></i>
            Approve Request
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">

          <input type="hidden" name="request_id" id="approveRequestId" data-testid="approve-request-id">

          <p class="mb-2">
            Are you sure you want to approve this request?
          </p>

          <div class="alert alert-success mb-3">
            <div id="approveCustomerName" data-testid="approve-customer-name">-</div>
            <div class="small" id="approveRequestType" data-testid="approve-request-type">-</div>
          </div>

          <div class="mb-3">
            <label class="form-label">Admin Notes</label>
            <textarea
              name="admin_notes"
              data-testid="approve-admin-note"
              class="form-control"
              rows="3"
              placeholder="Optional approval notes..."></textarea>
          </div>

          <div class="small text-muted">
            After approval, data flow should continue based on request type:
            <br>
            <strong>Member:</strong> transactions, history_transactions, memberships, member, member_access.
            <br>
            <strong>Casual:</strong> transactions, history_transactions, casual_passes.
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" 
                  class="btn btn-secondary" 
                  data-bs-dismiss="modal"
                  data-testid="approve-cancel-btn"
                  >
            Cancel
          </button>
          <button type="submit" 
                  class="btn btn-success"
                  data-testid="approve-approve-btn"
                  >
            <i class="bi bi-check-circle me-1"></i>
            Approve
          </button>
        </div>

      </form>

    </div>
  </div>
</div>