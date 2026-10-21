<?= $this->extend('pages/admin/layout/template') ?>

<?= $this->section('content') ?>

<div class="container py-4">

  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">Membership Plans</h2>
    <button
      class="btn btn-danger"
      data-bs-toggle="modal"
      data-bs-target="#addPlanModal"
      data-testid="add-plan-btn"
      >
      <i class="bi bi-plus-circle me-1"></i>
      Add Plan
    </button>
  </div>

<!-- ✅ FILTER CARD -->
  <form method="get" class="mb-4">
    <div class="card shadow-sm bg-light mb-3">
        <div class="card-body">
          <div class="row g-3 align-items-end">
            <!-- SEARCH -->
            <div class="col-md-3">
              <label class="form-label fw-bold">Search</label>
              <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Code / Membership Plan..."
                value="<?= esc($_GET['search'] ?? '') ?>">
            </div>

            <div class="col-md-3">
              <button class="btn btn-success">Export to CSV</button>
            </div>

          </div>
        </div>
    </div>
    <div class="card shadow-sm bg-light">
      <div class="card-body">
        <div class="row g-3 align-items-end">
          <!-- TARGET -->
          <div class="col-md-2">
            <label class="form-label fw-bold">Target</label>
            <select name="target" class="form-select">
              <option value="">All</option>
              <option value="REGULAR" <?= ($_GET['target'] ?? '') == 'REGULAR' ? 'selected' : '' ?>>Regular</option>
              <option value="STUDENT" <?= ($_GET['target'] ?? '') == 'STUDENT' ? 'selected' : '' ?>>Student</option>
            </select>
          </div>

          <!-- SCOPE -->
          <div class="col-md-2">
            <label class="form-label fw-bold">Scope</label>
            <select name="scope" class="form-select">
              <option value="">All</option>
              <option value="CASUAL_ONLY" <?= ($_GET['scope'] ?? '') == 'CASUAL_ONLY' ? 'selected' : '' ?>>Casual</option>
              <option value="MEMBER_ONLY" <?= ($_GET['scope'] ?? '') == 'MEMBER_ONLY' ? 'selected' : '' ?>>Member</option>
              <option value="BOTH" <?= ($_GET['scope'] ?? '') == 'BOTH' ? 'selected' : '' ?>>Both</option>
            </select>
          </div>

          <!-- STATUS -->
          <div class="col-md-2">
            <label class="form-label fw-bold">Status</label>
            <select name="status" class="form-select">
              <option value="">All</option>
              <option value="ACTIVE" <?= ($_GET['status'] ?? '') == 'ACTIVE' ? 'selected' : '' ?>>Active</option>
              <option value="INACTIVE" <?= ($_GET['status'] ?? '') == 'INACTIVE' ? 'selected' : '' ?>>Inactive</option>
            </select>
          </div>

          <div class="col-md-2">
            <label class="form-label fw-bold">Limit</label>
            <select name="limit" class="form-select">
              <option value="10" <?= ($_GET['limit'] ?? '10') == '10' ? 'selected' : '' ?>>10</option>
              <option value="25" <?= ($_GET['limit'] ?? '') == '25' ? 'selected' : '' ?>>25</option>
              <option value="50" <?= ($_GET['limit'] ?? '') == '50' ? 'selected' : '' ?>>50</option>
              <option value="100" <?= ($_GET['limit'] ?? '') == '100' ? 'selected' : '' ?>>100</option>
            </select>
          </div>

          <!-- ACTIONS -->
          <div class="col-md-1 d-grid">
            <button class="btn btn-primary">Filter</button>
          </div>

          <div class="col-md-1 d-grid">
            <a class="btn btn-danger" 
               href="<?= site_url('admin/membership-package') ?>">
               Reset
            </a>
          </div>

        </div>

      </div>
    </div>
  </form>


  <!-- Plans Table -->
  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0"
               data-testid="membership-plan-table">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Code</th>
              <th>Plan Name</th>
              <th>Target</th>
              <th>Scope</th>
              <th>Duration</th>
              <th>Price</th>
              <th>Limit</th>
              <th>Status</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>

          <tbody>
          <?php if (empty($plans ?? [])): ?>
            <tr data-testid="membership-plan-empty-state">
              <td colspan="10" 
                  class="text-center 
                  text-muted 
                  py-4"
                  data-testid="td-no-membership-info"
                  >
                No membership plans available.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($plans as $i => $p): ?>
              <tr data-testid="membership-plan-table-row">
                <td data-testid="td-number"><?= esc((($page - 1) * $limit) + $i + 1) ?></td>

                <td data-testid="td-plan-code">
                  <span class="badge bg-warning text-dark">
                    <?= esc($p['code']) ?>
                  </span>
                </td>

                <td data-testid="td-plan-name"><?= esc($p['name']) ?></td>

                <!-- TARGET MEMBER TYPE -->
                <td data-testid="td-target-type">
                  <?php
                    $targetBadge = match ($p['target_member_type']) {
                      'REGULAR'    => 'bg-dark',
                      'STUDENT' => 'bg-success',
                      'ALL'     => 'bg-primary',
                      default   => 'bg-dark'
                    };
                  ?>
                  <span class="badge <?= $targetBadge ?>">
                    <?= esc($p['target_member_type']) ?>
                  </span>
                </td>
                 <!-- AUDIENCE SCOPE TYPE -->
                <td data-testid="td-audience-scope">
                  <?php
                    $targetBadge = match ($p['audience_scope']) {
                      'MEMBER_ONLY'    => 'bg-primary',
                      'CASUAL_ONLY' => 'bg-danger',
                      'BOTH'     => 'bg-success',
                      default   => 'bg-dark'
                    };
                  ?>
                  <span class="badge <?= $targetBadge ?>">
                    <?= esc($p['audience_scope']) ?>
                  </span>
                </td>

                <td data-testid="td-duration"><?= esc($p['duration_days']) ?> days</td>

                <td data-testid="td-price">
                  Rp <?= number_format($p['price'], 0, ',', '.') ?>
                </td>

                <td data-testid="td-daily-limit"><?= esc($p['daily_limit']) ?> x day</td>
                
                <td data-testid="td-plan-status">
                  <?= $p['is_active']
                      ? '<span class="badge bg-success">Active</span>'
                      : '<span class="badge bg-secondary">Inactive</span>' ?>
                </td>

                <td class="text-center">
                  <button
                    class="btn btn-sm btn-outline-warning"
                    data-bs-toggle="modal"
                    data-bs-target="#editPlanModal"
                    data-id="<?= esc($p['id']) ?>"
                    data-code="<?= esc($p['code']) ?>"
                    data-name="<?= esc($p['name']) ?>"
                    data-target="<?= esc($p['target_member_type']) ?>"
                    data-audience="<?= esc($p['audience_scope']) ?>"
                    data-duration="<?= esc($p['duration_days']) ?>"
                    data-price="<?= esc($p['price']) ?>"
                    data-limit="<?= esc($p['daily_limit']) ?>"
                    data-description="<?= esc($p['description']) ?>"
                    data-active="<?= esc($p['is_active']) ?>"
                    data-testid="edit-plan-btn"
                    >
                    <i class="bi bi-pencil"></i>
                  </button>
                </td>

              </tr>
            <?php endforeach ?>
          <?php endif ?>
          </tbody>

        </table>
      </div>
    </div>
  </div>

<!-- ✅ PAGINATION -->
  <?php if (!empty($pager)): ?>
  <div class="d-flex align-items-baseline mt-3 gap-3">
    <div class="text-muted small">
      Showing page <?= $pager->getCurrentPage() ?>
    </div>
    <div>
      <?= $pager->links('default', 'bootstrap_custom') ?>
    </div>
  </div>
  <?php endif; ?>

</div>



<?= $this->include('pages/admin/membership_plans/modals/add-plan') ?>
<?= $this->include('pages/admin/membership_plans/modals/edit-plan') ?>


   

<?= $this->endSection() ?>