<div
  class="modal fade"
  id="editScheduleModal"
  tabindex="-1"
  aria-labelledby="editScheduleModalLabel"
  aria-hidden="true">

  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form
        id="editScheduleForm"
        method="POST"
        action="">

        <?= csrf_field() ?>

        <input
          type="hidden"
          name="id"
          id="editScheduleId">

        <!-- HEADER -->
        <div class="modal-header bg-dark text-white">
          <h5
            class="modal-title"
            id="editScheduleModalLabel">

            <i class="bi bi-pencil-square me-2"></i>
            Edit Access Schedule
          </h5>

          <button
            type="button"
            class="btn-close btn-close-white"
            data-bs-dismiss="modal"
            aria-label="Close">
          </button>
        </div>

        <!-- BODY -->
        <div class="modal-body">

          <div class="alert alert-info d-flex align-items-start">
            <i class="bi bi-info-circle me-2 mt-1"></i>

            <div>
              Changes to this schedule will affect future check-ins.
              Open sessions can still check out normally.
            </div>
          </div>

          <div class="row g-3">

            <!-- SCHEDULE NAME -->
            <div class="col-12">
              <label
                for="editScheduleName"
                class="form-label fw-semibold">

                Schedule Name
              </label>

              <input
                type="text"
                name="name"
                id="editScheduleName"
                class="form-control"
                placeholder="Example: Women Monday Morning"
                maxlength="100"
                required>
            </div>

            <!-- DAY -->
            <div class="col-md-4">
              <label
                for="editScheduleDay"
                class="form-label fw-semibold">

                Day
              </label>

              <select
                name="day_of_week"
                id="editScheduleDay"
                class="form-select"
                required>

                <option value="">Choose day...</option>
                <option value="MONDAY">Monday</option>
                <option value="TUESDAY">Tuesday</option>
                <option value="WEDNESDAY">Wednesday</option>
                <option value="THURSDAY">Thursday</option>
                <option value="FRIDAY">Friday</option>
                <option value="SATURDAY">Saturday</option>
                <option value="SUNDAY">Sunday</option>
              </select>
            </div>

            <!-- START TIME -->
            <div class="col-md-4">
              <label
                for="editScheduleStartTime"
                class="form-label fw-semibold">

                Start Time
              </label>

              <input
                type="time"
                name="start_time"
                id="editScheduleStartTime"
                class="form-control"
                required>
            </div>

            <!-- END TIME -->
            <div class="col-md-4">
              <label
                for="editScheduleEndTime"
                class="form-label fw-semibold">

                End Time
              </label>

              <input
                type="time"
                name="end_time"
                id="editScheduleEndTime"
                class="form-control"
                required>
            </div>

            <!-- ALLOWED GENDER -->
            <div class="col-md-6">
              <label
                for="editScheduleGender"
                class="form-label fw-semibold">

                Allowed Gender
              </label>

              <select
                name="allowed_gender"
                id="editScheduleGender"
                class="form-select"
                required>

                <option value="ALL">All</option>
                <option value="MALE">Male</option>
                <option value="FEMALE">Female</option>
              </select>

              <div class="form-text">
                Select Female to create a women-only schedule.
              </div>
            </div>

            <!-- ACCESS TYPE -->
            <div class="col-md-6">
              <label
                for="editScheduleAccessType"
                class="form-label fw-semibold">

                Access Type
              </label>

              <select
                name="access_type"
                id="editScheduleAccessType"
                class="form-select"
                required>

                <option value="ALL">Member and Casual</option>
                <option value="MEMBER">Member Only</option>
                <option value="CASUAL">Casual Only</option>
              </select>
            </div>

            <!-- CAPACITY -->
            <div class="col-md-6">
              <label
                for="editScheduleCapacity"
                class="form-label fw-semibold">

                Capacity Limit
              </label>

              <div class="input-group">
                <span class="input-group-text">
                  <i class="bi bi-people"></i>
                </span>

                <input
                  type="number"
                  name="capacity_limit"
                  id="editScheduleCapacity"
                  class="form-control"
                  min="0"
                  step="1"
                  required>

                <span class="input-group-text">
                  people
                </span>
              </div>

              <div class="form-text">
                Use 0 for unlimited capacity.
              </div>
            </div>

            <!-- PRIORITY -->
            <div class="col-md-6">
              <label
                for="editSchedulePriority"
                class="form-label fw-semibold">

                Priority
              </label>

              <div class="input-group">
                <span class="input-group-text">
                  <i class="bi bi-sort-numeric-up"></i>
                </span>

                <input
                  type="number"
                  name="priority"
                  id="editSchedulePriority"
                  class="form-control"
                  min="0"
                  step="1"
                  required>
              </div>

              <div class="form-text">
                A higher priority takes precedence over another matching schedule.
              </div>
            </div>

            <!-- STATUS -->
            <div class="col-12">
              <div class="edit-schedule-status-card">

                <div class="form-check form-switch mb-0">
                  <input
                    type="checkbox"
                    name="is_active"
                    id="editScheduleIsActive"
                    class="form-check-input"
                    value="1">

                  <label
                    for="editScheduleIsActive"
                    class="form-check-label fw-semibold">

                    Schedule is active
                  </label>
                </div>

                <div class="small text-muted mt-1">
                  Inactive schedules remain stored but do not affect check-in.
                </div>

                              </div>
            </div>

          </div>
        </div>

        <!-- FOOTER -->
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-outline-secondary"
            data-bs-dismiss="modal">
            Cancel
          </button>

          <button
            type="submit"
            class="btn btn-warning fw-semibold">
            <i class="bi bi-save me-2"></i>
            Save Changes
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

<style>
  .edit-schedule-status-card {
    padding: 14px 16px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    background-color: #f8f9fa;
  }

  #editScheduleModal .form-label {
    margin-bottom: 6px;
  }

  #editScheduleModal .form-text {
    font-size: 12px;
  }
</style>
           