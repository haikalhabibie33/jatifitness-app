<!-- View Membership Modal -->
<div class="modal fade" id="viewMembershipModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" data-testid="view-membership-modal-title">
          <i class="bi bi-card-list me-2"></i>
          Membership
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <div><strong>Member Name:</strong> <span id="vmMemberName">-</span></div>
          <div><strong>Member Type:</strong> <span id="vmMemberType">-</span></div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle mb-0" data-testid="view-membership-member-table">
            <thead>
              <tr>
                <th width="60">#</th>
                <th>Plan</th>
                <th>Status</th>
                <th>Start Date</th>
                <th>End Date</th>
              </tr>
            </thead>
            <tbody id="vmMembershipTableBody">
              <tr>
                <td colspan="4" class="text-center text-muted">
                  No membership data available
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-outline-secondary"
          data-bs-dismiss="modal"
          data-testid="view-membership-cancel-btn"
          >
          Close
        </button>
      </div>

    </div>
  </div>
</div>