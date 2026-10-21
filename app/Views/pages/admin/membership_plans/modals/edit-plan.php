<!-- Edit Membership Plan Modal -->
<div class="modal fade" id="editPlanModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <form
        class="membership-plan-form"
        method="post"
        action="<?= site_url('admin/membership-package/update') ?>">
        <?= csrf_field() ?>

        <!-- hidden ID -->
        <input type="hidden" name="id" id="ePlanId">

        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title" data-testid="edit-membership-plan-modal-title">
            <i class="bi bi-pencil-square me-2"></i>
            Edit Membership Plan
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">

            <!-- CODE -->
            <div class="col-md-6">
              <label class="form-label">Plan Code</label>
              <input
                type="text"
                class="form-control"
                id="eCode"
                name="code"
                data-testid="edit-membership-plan-code"
                disabled>
              <div class="form-text">
                <span class="text-danger bold fw-bold">Modifying Plan Code is not recommended!</span>
              </div>
            </div>

            <!-- NAME -->
            <div class="col-md-6">
              <label class="form-label">Plan Name</label>
              <input
                type="text"
                class="form-control"
                id="eName"
                name="name"
                data-testid="edit-membership-plan-name"
                required>
            </div>

            <!-- TARGET MEMBER TYPE -->
            <div class="col-md-6">
              <label class="form-label">Target Member</label>
              <select
                class="form-select"
                id="eTarget"
                name="target_member_type"
                data-testid="edit-membership-plan-target-member"
                >
                <option value="REGULAR">REGULAR</option>
                <option value="STUDENT">STUDENT</option>
                <option value="ALL">ALL</option>
              </select>
            </div>

            <!-- AUDIENCE SCOPE -->
            <div class="col-md-6">
              <label class="form-label">Audience Scope</label>
              <select
                class="form-select"
                id="eAudience"
                name="audience_scope"
                data-testid="edit-membership-plan-audience-scope"
                >
                <option value="MEMBER_ONLY">MEMBER_ONLY</option>
                <option value="CASUAL_ONLY">CASUAL_ONLY</option>
                <option value="BOTH">BOTH</option>
              </select>
            </div>

            <!-- PRICE -->
            <div class="col-md-6">
              <label class="form-label">Price</label>
              <input
                type="number"
                class="form-control"
                id="ePrice"
                name="price"
                data-testid="edit-membership-plan-price"
                required>
            </div>

            <!-- DURATION -->
            <div class="col-md-6">
              <label class="form-label">Duration (days)</label>
              <input
                type="number"
                class="form-control"
                id="eDuration"
                name="duration_days"
                data-testid="edit-membership-plan-duration"
                required>
            </div>

            <!-- DAILY LIMIT -->
            <div class="col-md-6">
              <label class="form-label">Daily Limit</label>
              <input
                type="number"
                class="form-control"
                id="eLimit"
                name="daily_limit"
                data-testid="edit-membership-plan-daily-limit"
                required>
            </div>

            <!-- DESCRIPTION -->
            <div class="col-md-6">
              <label class="form-label">Description</label>
              <input
                class="form-control"
                type="text"
                id="eDescription"
                name="description"
                data-testid="edit-membership-plan-description"
                rows="2">
            </div>

            <!-- STATUS -->
            <div class="col-12">
              <div class="form-check form-switch mt-2">
                <input
                  class="form-check-input"
                  type="checkbox"
                  id="eIsActive"
                  name="is_active"
                  value="1"
                  data-testid="edit-membership-plan-isactive"
                  >
                <label class="form-check-label" for="eIsActive">
                  Plan Active (can be sold)
                </label>
              </div>
            </div>

          </div>
        </div>

        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-outline-secondary"
            data-bs-dismiss="modal"
            data-testid="edit-membership-plan-cancel-button"
            >
            Cancel
          </button>
          <button
            type="submit"
            class="btn btn-danger"
            data-testid="edit-membership-plan-save-button"
            >
            <i class="bi bi-save me-1"></i>
            Save Changes
          </button>
        </div>

      </form>

    </div>
  </div>
</div>