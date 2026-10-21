<!-- Edit Member Modal -->
<div class="modal fade" id="editMemberModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <form
        id="editMemberForm"
        method="post"
        action="">
        <?= csrf_field() ?>

        <!-- hidden ID -->
        <input type="hidden" name="id" id="eMemberId">

        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title" data-testid="edit-member-modal-title">
            <i class="bi bi-pencil-square me-2"></i>
            Edit Member
          </h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">

            <!-- NAME -->
            <div class="col-md-6">
              <label class="form-label">Name</label>
              <input
                type="text"
                name="name"
                data-testid="edit-member-name"
                id="eName"
                class="form-control"
                required>
            </div>

            <!-- GENDER -->
            <div class="col-md-6">
              <label class="form-label">Gender</label>
              <select
                name="gender"
                id="eGender"
                data-testid="edit-member-gender"
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
                data-testid="edit-member-dob"
                id="eDob"
                class="form-control">
            </div>

            <!-- MEMBER TYPE -->
            <div class="col-md-6">
              <label class="form-label">Member Type</label>
              <select
                name="member_type"
                id="eMemberType"
                data-testid="edit-member-member-type"
                class="form-select">
                <option value="REGULAR">REGULAR</option>
                <option value="STUDENT">STUDENT</option>
              </select>
            </div>

            <!-- PHONE -->
            <div class="col-md-6">
              <label class="form-label">Phone</label>
              <input
                type="tel"
                name="phone"
                data-testid="edit-member-phone"
                id="ePhone"
                class="form-control"
                maxlength="30">
            </div>

            <!-- EMAIL -->
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input
                type="email"
                name="email"
                data-testid="edit-member-email"
                id="eEmail"
                class="form-control">
            </div>

            <!-- ADDRESS -->
            <div class="col-12">
              <label class="form-label">Address</label>
              <textarea
                name="address"
                id="eAddress"
                data-testid="edit-member-address"
                class="form-control"
                rows="2"></textarea>
            </div>

            <!-- ACTIVE STATUS -->
            <div class="col-12">
              <div class="form-check form-switch mt-2">
                <input
                  class="form-check-input"
                  type="checkbox"
                  name="is_active"
                  data-testid="edit-member-is-active"
                  id="eIsActive"
                  value="1">
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
            data-testid="edit-member-cancel-btn"
            >
            Cancel
          </button>

          <button
            type="submit"
            class="btn btn-danger"
            data-testid="edit-member-submit-btn"
            >
            <i class="bi bi-save me-1"></i>
            Save Changes
          </button>
        </div>

      </form>

    </div>
  </div>
</div>
