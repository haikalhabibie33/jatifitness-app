<div class="modal fade" id="rejectRequestModal" tabindex="-1" aria-labelledby="rejectRequestModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <form id="rejectRequestForm" action="" method="POST">
        <?=  csrf_field() ?>
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="rejectRequestModalLabel" data-testid="reject-modal-title">
            <i class="bi bi-x-circle me-1"></i>
            Reject Request
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">

          <input type="hidden" name="request_id" id="rejectRequestId" data-testid="reject-request-id">

          <p class="mb-2">
            Are you sure you want to reject this request?
          </p>

          <div class="alert alert-danger mb-3">
            <div id="rejectCustomerName" data-testid="reject-customer-name">-</div>
            <div class="small" id="rejectRequestType" data-testid="reject-request-type">-</div>
          </div>

          <div class="mb-3">
            <label class="form-label">
              Reject Reason <span class="text-danger">*</span>
            </label>
            <textarea
              name="reject_reason"
              data-testid="reject-reason-note"
              class="form-control"
              rows="3"
              placeholder="Example: Student document is invalid..."
              required></textarea>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" 
                  class="btn btn-secondary" 
                  data-bs-dismiss="modal"
                  data-testid="reject-cancel-btn"
                  >
            Cancel
          </button>
          <button type="submit" 
                  class="btn btn-danger"
                  data-testid="reject-submit-btn"
                  >
            <i class="bi bi-x-circle me-1"></i>
            Reject
          </button>
        </div>

      </form>

    </div>
  </div>
</div>