<!-- Add Member Modal -->
<div class="modal fade" id="addMemberModal" data-testid="add-member-modal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <form method="post" action="<?= base_url('admin/member/store') ?>">
        <?= csrf_field() ?>

        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title" data-testid="add-member-modal-title">
            <i class="bi bi-person-plus me-2"></i>
            Add New Member
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">

            <!-- NAME -->
            <div class="col-md-6">
              <label class="form-label">Name</label>
              <input
                type="text"
                name="name"
                class="form-control"
                data-testid="add-member-name"
                required>
            </div>

            <!-- GENDER -->
            <div class="col-md-6">
              <label class="form-label">Gender</label>
              <select
                name="gender"
                data-testid="add-member-gender"
                class="form-select">
                <option value="">Choose…</option>
                <option value="MALE">Male</option>
                <option value="FEMALE">Female</option>
              </select>
            </div>

            <!-- DATE OF BIRTH -->
            <div class="col-md-6">
              <label class="form-label">Date of Birth</label>
              <input
                type="date"
                name="date_of_birth"
                data-testid="add-member-dob"
                class="form-control">
            </div>

            <!-- MEMBER TYPE -->
            <div class="col-md-6">
              <label class="form-label">Member Type</label>
              <select
                name="member_type"
                data-testid="add-member-member-type"
                class="form-select">
                <option value="REGULAR" selected>REGULAR</option>
                <option value="STUDENT">STUDENT</option>
              </select>
            </div>

            <!-- PHONE -->
            <div class="col-md-6">
              <label class="form-label">Phone</label>
              <input
                type="tel"
                name="phone"
                data-testid="add-member-phone"
                class="form-control"
                maxlength="30">
            </div>

            <!-- EMAIL -->
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input
                type="email"
                name="email"
                data-testid="add-member-email"
                class="form-control">
            </div>

            <!-- ADDRESS -->
            <div class="col-12">
              <label class="form-label">Address</label>
              <textarea
                name="address"
                class="form-control"
                data-testid="add-member-address"
                rows="2"></textarea>
            </div>

            <!-- ACTIVE STATUS -->
            <div class="col-12">
              <div class="form-check form-switch mt-2">
                <input
                  class="form-check-input"
                  type="checkbox"
                  name="is_active"
                  data-testid="add-member-member-active-status"
                  value="1"
                  checked>
                <label class="form-check-label">
                  Member Active
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
            data-testid="add-member-cancel-btn">
            Cancel
          </button>

          <button
            type="submit"
            class="btn btn-danger"
            data-testid="add-member-save-btn">
            <i class="bi bi-save me-1"></i>
            Save Member
          </button>
        </div>

      </form>

    </div>
  </div>
</div>