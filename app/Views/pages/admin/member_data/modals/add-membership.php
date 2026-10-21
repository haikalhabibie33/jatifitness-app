<div class="modal fade" id="addMembershipModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <form id="addMembershipForm" method="post" action="">
        <?= csrf_field() ?>

        <input type="hidden" name="member_id" id="mMemberId">

        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title" data-testid="add-membership-modal-title">
            <i class="bi bi-plus-circle me-2"></i>
            Add Membership
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">

            <div class="col-md-6">
              <label class="form-label fw-bold">Membership Plan</label>

              <select name="membership_plan_id" 
                      id="mPlan" 
                      data-testid="add-membership-plan"
                      class="form-select" 
                      required
                      >

                <option value="">Choose plan...</option>
                <?php foreach ($plans as $p): ?>
                  <option
                    value="<?= esc($p['id']) ?>"
                    data-duration="<?= esc($p['duration_days']) ?>"
                    data-target="<?= strtoupper(trim($p['target_member_type'])) ?>"
                    data-audience="<?= strtoupper(trim($p['audience_scope'])) ?>"
                    >
                    <?= esc($p['name']) ?>
                    (<?= number_format($p['price'], 0, ',', '.') ?>)
                  </option>
                <?php endforeach ?>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">Payment</label>
              <select class="form-select" 
                      name="payment_method" 
                      id="mPaymentMethod"
                      required>
                <option value="">Choose Payment...</option>
                <option value="CASH">CASH</option>
                <option value="TRANSFER">TRANSFER</option>
                <option value="QRIS" disabled>QRIS</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">Member</label>
              <input type="text" 
                     id="mMemberName" 
                     data-testid="add-membership-name"
                     class="form-control" 
                     disabled
                     >
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">Target Type</label>
              <input type="text" 
                     id="mTargetType" 
                     data-testid="add-membership-target-type"
                     class="form-control" 
                     disabled
                     >
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">Start Date</label>
              <input type="text" 
                     id="mStartDate" 
                     data-testid="add-membership-target-type"
                     class="form-control" 
                     disabled
                     >
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">End Date</label>
              <input type="text" 
                     id="mEndDate" 
                     data-testid="add-membership-target-type"
                     class="form-control" 
                     disabled
                     >
            </div>

          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-outline-secondary" 
                  type="button"
                  data-bs-dismiss="modal"
                  data-testid="add-membership-cancel-btn"
                  >
                  Cancel
          </button>

          <button class="btn btn-danger" 
                  type="submit"
                  data-testid="add-membership-create-btn"
                  >
            <i class="bi bi-save me-1"></i> Create Membership
          </button>
        </div>

      </form>
    </div>
  </div>
</div>