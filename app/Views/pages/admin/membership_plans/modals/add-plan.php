<div class="modal fade" id="addPlanModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <form method="post" action="<?= site_url('admin/membership-package/store') ?>">
        <?= csrf_field() ?>

        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title" data-testid="add-membership-plan-modal-title">
            <i class="bi bi-plus-circle me-2"></i>
            Add Membership Plan
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">

            <div class="col-md-6">
              <label class="form-label">Code</label>
              <input type="text" 
                     name="code" 
                     class="form-control" 
                     data-testid="add-membership-plan-code"
                     required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Plan Name</label>
              <input type="text" 
                     name="name" 
                     class="form-control" 
                     data-testid="add-membership-plan-plan-name"
                     required>
            </div>

            <!-- TARGET -->
            <div class="col-md-6">
              <label class="form-label">Target Member</label>
              <select name="target_member_type" class="form-select" data-testid="add-membership-plan-target-member">
                <option value="REGULAR">REGULAR</option>
                <option value="STUDENT">STUDENT</option>
                <option value="ALL">ALL</option>
              </select>
            </div>

            <!-- AUDIENCE -->
            <div class="col-md-6">
              <label class="form-label">Audience Scope</label>
              <select name="audience_scope" class="form-select" data-testid="add-membership-plan-audience-scope">
                <option value="MEMBER_ONLY">MEMBER_ONLY</option>
                <option value="CASUAL_ONLY">CASUAL_ONLY</option>
                <option value="BOTH">BOTH</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Duration (days)</label>
              <input type="number" 
                     name="duration_days" 
                     class="form-control" 
                     data-testid="add-membership-plan-duration"
                     required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Price</label>
              <input type="number" 
                     name="price" 
                     class="form-control" 
                     data-testid="add-membership-plan-price"
                     required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Daily Limit</label>
              <input type="number" 
                     name="daily_limit" 
                     class="form-control" 
                     data-testid="add-membership-plan-daily-limit"
                     required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Description (optional)</label>
              <input type="text" 
                     class="form-control" 
                     name="description"
                     data-testid="add-membership-plan-description"
                     >
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-outline-secondary" data-bs-dismiss="modal" data-testid="add-membership-plan-cancel-button">
            Cancel
          </button>
          <button class="btn btn-danger" type="submit" data-testid="add-membership-plan-save-button">
            Save Plan
          </button>
        </div>

      </form>

    </div>
  </div>
</div>