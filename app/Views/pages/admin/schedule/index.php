<?= $this->extend('pages/admin/layout/template') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-3 py-md-4">
  <div class="mx-auto" style="max-width: 1200px;">

    <!-- PAGE HEADER -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
      <div>
        <h2 class="fw-bold mb-1">Access Scheduling</h2>
        <p class="text-muted mb-0">
          Manage access hours, restrictions, and gym capacity.
        </p>
      </div>

      <button
        type="button"
        class="btn btn-danger fw-semibold"
        data-bs-toggle="modal"
        data-bs-target="#addScheduleModal">
        <i class="bi bi-calendar-plus me-2"></i>
        Add Schedule
      </button>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="row g-3 mb-4">

      <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 schedule-summary-card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <div class="small text-muted mb-1">
                  Active Schedules
                </div>

                <div class="fs-3 fw-bold">
                  <?= esc($summary['active'] ?? 0) ?>
                </div>
              </div>

              <div class="schedule-icon schedule-icon-green">
                <i class="bi bi-calendar-check"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 schedule-summary-card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <div class="small text-muted mb-1">
                  Women Sessions
                </div>

                <div class="fs-3 fw-bold">
                  <?= esc($summary['female'] ?? 0) ?>
                </div>
              </div>

              <div class="schedule-icon schedule-icon-red">
                <i class="bi bi-shield-check"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 schedule-summary-card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <div class="small text-muted mb-1">
                  Member Sessions
                </div>

                <div class="fs-3 fw-bold">
                  <?= esc($summary['member'] ?? 0) ?>
                </div>
              </div>

              <div class="schedule-icon schedule-icon-blue">
                <i class="bi bi-person-badge"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 schedule-summary-card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <div class="small text-muted mb-1">
                  Unlimited Capacity
                </div>

                <div class="fs-3 fw-bold">
                  <?= esc($summary['unlimited'] ?? 0) ?>
                </div>
              </div>

              <div class="schedule-icon schedule-icon-yellow">
                <i class="bi bi-infinity"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- FILTER CARD -->
    <form method="GET" class="card shadow-sm bg-light mb-4">
      <div class="card-body">

        <div class="row g-3 align-items-end">

          <div class="col-12 col-md-4">
            <label class="form-label fw-semibold">Search</label>

            <div class="input-group">
              <span class="input-group-text">
                <i class="bi bi-search"></i>
              </span>

              <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search schedule..."
                value="<?= esc($_GET['search'] ?? '') ?>">
            </div>
          </div>

          <div class="col-6 col-md-2">
            <label class="form-label fw-semibold">Day</label>

            <select name="day" class="form-select">
              <option value="">All Days</option>

              <?php
              $days = [
                  'MONDAY',
                  'TUESDAY',
                  'WEDNESDAY',
                  'THURSDAY',
                  'FRIDAY',
                  'SATURDAY',
                  'SUNDAY',
              ];
              ?>

              <?php foreach ($days as $day): ?>
                <option
                  value="<?= esc($day, 'attr') ?>"
                  <?= ($_GET['day'] ?? '') === $day ? 'selected' : '' ?>>
                  <?= esc(ucfirst(strtolower($day))) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-6 col-md-2">
            <label class="form-label fw-semibold">Gender</label>

            <select name="gender" class="form-select">
              <option value="">All</option>

              <option
                value="MALE"
                <?= ($_GET['gender'] ?? '') === 'MALE' ? 'selected' : '' ?>>
                Male
              </option>

              <option
                value="FEMALE"
                <?= ($_GET['gender'] ?? '') === 'FEMALE' ? 'selected' : '' ?>>
                Female
              </option>
            </select>
          </div>

          <div class="col-6 col-md-2">
            <label class="form-label fw-semibold">Access Type</label>

            <select name="access_type" class="form-select">
              <option value="">All</option>

              <option
                value="MEMBER"
                <?= ($_GET['access_type'] ?? '') === 'MEMBER' ? 'selected' : '' ?>>
                Member
              </option>

              <option
                value="CASUAL"
                <?= ($_GET['access_type'] ?? '') === 'CASUAL' ? 'selected' : '' ?>>
                Casual
              </option>
            </select>
          </div>

          <div class="col-6 col-md-2">
            <label class="form-label fw-semibold">Status</label>

            <select name="status" class="form-select">
              <option value="">All</option>

              <option
                value="ACTIVE"
                <?= ($_GET['status'] ?? '') === 'ACTIVE' ? 'selected' : '' ?>>
                Active
              </option>

              <option
                value="INACTIVE"
                <?= ($_GET['status'] ?? '') === 'INACTIVE' ? 'selected' : '' ?>>
                Inactive
              </option>
            </select>
          </div>

          <div class="col-6 col-md-2">
          <label class="form-label fw-semibold">Limit</label>

          <select name="limit" class="form-select">
            <?php foreach ([10, 25, 50, 100] as $option): ?>
              <option
                value="<?= esc($option, 'attr') ?>"
                <?= (int) ($_GET['limit'] ?? 10) === $option ? 'selected' : '' ?>>
                <?= esc($option) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

          <div class="col-6 col-md-2 d-grid">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-funnel me-1"></i>
              Filter
            </button>
          </div>

          <div class="col-6 col-md-2 d-grid">
             <a
              class="btn btn-outline-danger"
              href="<?= site_url('admin/schedule') ?>">
              <i class="bi bi-arrow-counterclockwise me-1"></i>
              Reset
            </a>
          </div>

        </div>
      </div>
    </form>

    <!-- SCHEDULE TABLE -->
    <div class="card border-0 shadow-sm">

      <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
          <h6 class="fw-bold mb-0">
            <i class="bi bi-calendar-week me-2"></i>
            Schedule List
          </h6>

          <span class="badge bg-secondary">
            <?= count($schedules ?? []) ?> displayed
          </span>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">

          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Schedule</th>
              <th>Day</th>
              <th>Access Time</th>
              <th>Restriction</th>
              <th>Capacity</th>
              <th>Priority</th>
              <th>Status</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>

          <tbody>
            <?php if (empty($schedules ?? [])): ?>

              <tr>
                <td colspan="9" class="text-center py-5 text-muted">
                  <i class="bi bi-calendar-x d-block fs-1 mb-2"></i>

                  <div class="fw-semibold">
                    No access schedules found
                  </div>

                  <small>
                    Create a schedule to begin controlling gym access.
                  </small>
                </td>
              </tr>

            <?php else: ?>

              <?php foreach ($schedules as $index => $schedule): ?>
                <?php
                $gender = strtoupper(
                    (string) ($schedule['allowed_gender'] ?? 'ALL')
                );

                $accessType = strtoupper(
                    (string) ($schedule['access_type'] ?? 'ALL')
                );

                $isActive = (int) ($schedule['is_active'] ?? 0) === 1;

                $genderBadge = match ($gender) {
                    'FEMALE' => 'bg-danger',
                    'MALE'   => 'bg-primary',
                    default  => 'bg-success',
                };

                $accessBadge = match ($accessType) {
                    'MEMBER' => 'bg-dark',
                    'CASUAL' => 'bg-warning text-dark',
                    default  => 'bg-secondary',
                };
                ?>

                <tr>
                  <td class="text-muted">
                    <?= esc(
                        ((int) ($page ?? 1) - 1)
                        * (int) ($limit ?? 10)
                        + $index
                        + 1
                    ) ?>
                  </td>

                  <td>
                    <div class="fw-bold">
                      <?= esc($schedule['name'] ?? '-') ?>
                    </div>

                    <small class="text-muted">
                      Schedule ID: <?= esc($schedule['id'] ?? '-') ?>
                    </small>
                  </td>

                  <td>
                    <span class="badge bg-light text-dark border">
                      <i class="bi bi-calendar3 me-1"></i>

                      <?= esc(
                          ucfirst(
                              strtolower($schedule['day_of_week'] ?? '-')
                          )
                      ) ?>
                    </span>
                  </td>

                  <td>
                    <div class="schedule-time">
                      <i class="bi bi-clock me-1"></i>

                      <?= esc(substr(
                          $schedule['start_time'] ?? '-',
                          0,
                          5
                      )) ?>

                      <span class="text-muted mx-1">to</span>

                      <?= esc(substr(
                          $schedule['end_time'] ?? '-',
                          0,
                          5
                      )) ?>
                    </div>
                  </td>

                  <td>
                    <div class="d-flex flex-wrap gap-1">
                      <span class="badge <?= esc($genderBadge) ?>">
                        <?= esc($gender) ?>
                      </span>

                      <span class="badge <?= esc($accessBadge) ?>">
                        <?= esc($accessType) ?>
                      </span>
                    </div>
                  </td>

                  <td>
                    <?php if ((int) ($schedule['capacity_limit'] ?? 0) === 0): ?>

                      <span class="badge schedule-unlimited-badge">
                        <i class="bi bi-infinity me-1"></i>
                        Unlimited
                      </span>

                    <?php else: ?>

                      <span class="fw-semibold">
                        <i class="bi bi-people me-1"></i>
                        <?= esc($schedule['capacity_limit']) ?>
                      </span>

                    <?php endif; ?>
                  </td>

                  <td>
                    <span class="badge bg-light text-dark border">
                      <?= esc($schedule['priority'] ?? 0) ?>
                    </span>
                  </td>

                  <td>
                    <?php if ($isActive): ?>

                      <span class="badge bg-success">
                        <i class="bi bi-check-circle me-1"></i>
                        Active
                      </span>

                    <?php else: ?>

                      <span class="badge bg-secondary">
                        <i class="bi bi-pause-circle me-1"></i>
                        Inactive
                      </span>

                    <?php endif; ?>
                  </td>

                  <td class="text-center">
                    <div class="d-inline-flex gap-1">

                      <button
                        type="button"
                        class="btn btn-sm btn-outline-warning"
                        title="Edit schedule"
                        data-bs-toggle="modal"
                        data-bs-target="#editScheduleModal"
                        data-id="<?= esc($schedule['id'] ?? '', 'attr') ?>"
                        data-name="<?= esc($schedule['name'] ?? '', 'attr') ?>"
                        data-day="<?= esc($schedule['day_of_week'] ?? '', 'attr') ?>"
                        data-start="<?= esc($schedule['start_time'] ?? '', 'attr') ?>"
                        data-end="<?= esc($schedule['end_time'] ?? '', 'attr') ?>"
                        data-gender="<?= esc($schedule['allowed_gender'] ?? 'ALL', 'attr') ?>"
                        data-access-type="<?= esc($schedule['access_type'] ?? 'ALL', 'attr') ?>"
                        data-capacity="<?= esc($schedule['capacity_limit'] ?? 0, 'attr') ?>"
                        data-priority="<?= esc($schedule['priority'] ?? 0, 'attr') ?>"
                        data-active="<?= esc($schedule['is_active'] ?? 0, 'attr') ?>"
                        >
                        <i class="bi bi-pencil"></i>
                      </button>

                      <form 
                        method="POST"
                        action="<?= base_url('admin/schedule/status/' . $schedule['id']) ?>"
                        class="d-inline"
                        onsubmit="return confirm(
                        `<?= $isActive
                              ? 'Are you sure want to disaple this schedule?'
                              : 'Are you sure want to enable this schedule?' ?>`)">
                        <?= csrf_field() ?>
                        <input 
                          type="hidden"
                          name="is_active"
                          value="<?= $isActive ? '0' : '1' ?>">
                          <button
                            type="submit"
                            class="btn btn-sm <?= $isActive
                              ? 'btn-outline-danger'
                              : 'btn-outline-success' ?>"
                            title="<?= $isActive ? 'Disable schedule' : 'Enable schedule' ?>"
                            data-id="<?= esc($schedule['id'] ?? '', 'attr') ?>">
                            <i class="bi<?= $isActive 
                                          ? ' bi-power'
                                          : ' bi-play-circle' ?>"></i>
                          </button>
                      </form>

                    </div>
                  </td>
                </tr>

              <?php endforeach; ?>

            <?php endif; ?>
          </tbody>

        </table>
      </div>

      <!-- PAGINATION -->
      <?php if (!empty($pager)): ?>
        <div class="card-footer bg-white">
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

            <div class="small text-muted">
              Showing page <?= esc($pager->getCurrentPage()) ?>
            </div>

            <div>
              <?= $pager->links('default', 'bootstrap_custom') ?>
            </div>

          </div>
        </div>
      <?php endif; ?>

    </div>

  </div>
</div>

<style>
  .schedule-summary-card {
    transition:
      transform 0.2s ease,
      box-shadow 0.2s ease;
  }

  .schedule-summary-card:hover {
    transform: translateY(-2px);
  }

  .schedule-icon {
    width: 44px;
    height: 44px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: 20px;
  }

  .schedule-icon-green {
    color: #198754;
    background-color: #d1e7dd;
  }

  .schedule-icon-red {
    color: #dc3545;
    background-color: #f8d7da;
  }

  .schedule-icon-blue {
    color: #0d6efd;
    background-color: #cfe2ff;
  }

  .schedule-icon-yellow {
    color: #997404;
    background-color: #fff3cd;
  }

  .schedule-time {
    white-space: nowrap;
    font-weight: 600;
  }

  .schedule-unlimited-badge {
    color: #146c43;
    background-color: #d1e7dd;
    border: 1px solid #75b798;
  }

  @media (max-width: 767.98px) {
    .schedule-icon {
      width: 38px;
      height: 38px;
      font-size: 17px;
    }
  }
</style>


<script>window.updateScheduleBaseUrl ="<?= base_url('admin/schedule/update') ?>";</script>

<script src="<?= base_url('assets/js/access-schedules/edit-schedule.js') ?>"></script>

<?= $this->include('pages/admin/schedule/modals/add-schedule') ?>
<?= $this->include('pages/admin/schedule/modals/edit-schedule') ?>

<?= $this->endSection() ?>