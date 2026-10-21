<div
  class="modal fade"
  id="addScheduleModal"
  tabindex="-1"
  aria-labelledby="addScheduleModalLabel"
  aria-hidden="true">

  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <form
        method="POST"
        action="<?= base_url('admin/schedule/store') ?>"
        id="addScheduleForm">

        <?= csrf_field() ?>

        <!-- HEADER -->
        <div class="modal-header bg-dark text-white" id="addScheduleModalLabel">

            <i class="bi bi-calendar-plus me-2"></i>
            Add Access Schedule
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
              The schedule only restricts check-in. Anyone with an open
              session can still check out after the schedule ends.
            </div>
          </div>

          <div class="row g-3">

            <!-- SCHEDULE NAME -->
            <div class="col-12">
              <label
                for="scheduleName"
                class="form-label fw-semibold">

                Schedule Name
              </label>

              <input
                type="text"
                name="name"
                id="scheduleName"
                class="form-control"
                placeholder="Example: Women Monday Morning"
                value="<?= old('name') ?>"
                maxlength="100"
                required>

              <div class="form-text">
                Use a descriptive name so the schedule is easy to identify.
              </div>
            </div>

            <!-- DAY -->
            <div class="col-md-4">
              <label
                for="scheduleDay"
                class="form-label fw-semibold">

                Day
              </label>

              <select
                name="day_of_week"
                id="scheduleDay"
                class="form-select"
                required>

                <option value="">Choose day...</option>

                <?php
                $scheduleDays = [
                    'MONDAY'    => 'Monday',
                    'TUESDAY'   => 'Tuesday',
                    'WEDNESDAY' => 'Wednesday',
                    'THURSDAY'  => 'Thursday',
                    'FRIDAY'    => 'Friday',
                    'SATURDAY'  => 'Saturday',
                    'SUNDAY'    => 'Sunday',
                ];
                ?>

                <?php foreach ($scheduleDays as $value => $label): ?>
                  <option
                    value="<?= esc($value, 'attr') ?>"
                    <?= old('day_of_week') === $value ? 'selected' : '' ?>>

                    <?= esc($label) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- START TIME -->
            <div class="col-md-4">
              <label
                for="scheduleStartTime"
                class="form-label fw-semibold">

                Start Time
              </label>

              <input
                type="time"
                name="start_time"
                id="scheduleStartTime"
                class="form-control"
                value="<?= esc(old('start_time') ?? '') ?>"
                required>
            </div>

            <!-- END TIME -->
            <div class="col-md-4">
              <label
                for="scheduleEndTime"
                class="form-label fw-semibold">

                End Time
              </label>

              <input
                type="time"
                name="end_time"
                id="scheduleEndTime"
                class="form-control"
                value="<?= esc(old('end_time') ?? '') ?>"
                required>
            </div>

            <!-- ALLOWED GENDER -->
            <div class="col-md-6">
              <label
                for="scheduleGender"
                class="form-label fw-semibold">

                Allowed Gender
              </label>

              <select
                name="allowed_gender"
                id="scheduleGender"
                class="form-select"
                required>

                <option
                  value="ALL"
                  <?= old('allowed_gender', 'ALL') === 'ALL' ? 'selected' : '' ?>>
                  All
                </option>

                <option
                  value="MALE"
                  <?= old('allowed_gender') === 'MALE' ? 'selected' : '' ?>>
                  Male
                </option>

                <option
                  value="FEMALE"
                  <?= old('allowed_gender') === 'FEMALE' ? 'selected' : '' ?>>
                  Female
                </option>
              </select>

              <div class="form-text">
                Select Female to create a women-only schedule.
              </div>
            </div>

            <!-- ACCESS TYPE -->
            <div class="col-md-6">
              <label
                for="scheduleAccessType"
                class="form-label fw-semibold">

                Access Type
              </label>

              <select
                name="access_type"
                id="scheduleAccessType"
                class="form-select"
                required>

                <option
                  value="ALL"
                  <?= old('access_type', 'ALL') === 'ALL' ? 'selected' : '' ?>>
                  Member and Casual
                </option>

                <option
                  value="MEMBER"
                  <?= old('access_type') === 'MEMBER' ? 'selected' : '' ?>>
                  Member Only
                </option>

                <option
                  value="CASUAL"
                  <?= old('access_type') === 'CASUAL' ? 'selected' : '' ?>>
                  Casual Only
                </option>
              </select>
            </div>

            <!-- CAPACITY -->
            <div class="col-md-6">
              <label
                for="scheduleCapacity"
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
                  id="scheduleCapacity"
                  class="form-control"
                  value="<?= esc(old('capacity_limit', '0')) ?>"
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
                for="schedulePriority"
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
                  id="schedulePriority"
                  class="form-control"
                  value="<?= esc(old('priority', '0')) ?>"
                  min="0"
                  step="1"
                  required>
              </div>

              <div class="form-text">
                A higher priority overrides another matching schedule.
              </div>
            </div>

            <!-- STATUS -->
            <div class="col-12">
              <div class="schedule-status-card">
                <div class="form-check form-switch mb-0">
                  <input
                    type="checkbox"
                    name="is_active"
                    id="scheduleIsActive"
                    class="form-check-input"
                    value="1"
                    <?= old('is_active', '1') === '1' ? 'checked' : '' ?>>

                  <label
                    for="scheduleIsActive"
                    class="form-check-label fw-semibold">

                    Activate this schedule immediately
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
            class="btn btn-danger fw-semibold">

            <i class="bi bi-save me-2"></i>
            Create Schedule
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

<style>
  .schedule-status-card {
    padding: 14px 16px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    background-color: #f8f9fa;
  }

  #addScheduleModal .form-label {
    margin-bottom: 6px;
  }

  #addScheduleModal .form-text {
    font-size: 12px;
  }
</style>