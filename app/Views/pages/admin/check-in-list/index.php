<?= $this->extend('pages/admin/layout/template') ?>

<?= $this->section('content') ?>

<div class="container py-4">

  <h2 class="fw-bold mb-4">Check-In/Out History</h2>

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
                placeholder="Name / phone..."
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
          <!-- METHOD -->
          <div class="col-md-2">
            <label class="form-label fw-bold">Type</label>
            <select name="type" class="form-select">
              <option value="">All</option>
              <option value="MEMBER" <?= ($_GET['type'] ?? '') == 'MEMBER' ? 'selected' : '' ?>>Member</option>
              <option value="CASUAL" <?= ($_GET['type'] ?? '') == 'CASUAL' ? 'selected' : '' ?>>Casual</option>
            </select>
          </div>

          <!-- DATE FROM -->
          <div class="col-md-2">
            <label class="form-label fw-bold">From</label>
            <input
              type="text"
              name="from"
              class="form-control"
              placeholder="Start Date"              
              onfocus="this.type='date'"
              onblur="if(!this.value)this.type='text'"
              value="<?= esc($_GET['from'] ?? '') ?>">
          </div>

          <!-- DATE TO -->
          <div class="col-md-2">
            <label class="form-label fw-bold">To</label>
            <input
              type="text"
              name="to"
              class="form-control"
              placeholder="End Date"
              onfocus="this.type='date'"
              onblur="if(!this.value)this.type='text'"
              value="<?= esc($_GET['to'] ?? '') ?>">
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

          <!-- ACTION -->
          <div class="col-md-1 d-grid">
            <button class="btn btn-primary">Filter</button>
          </div>

          <div class="col-md-1 d-grid">
            <a class="btn btn-danger"
                href="<?= site_url('admin/checkin-list') ?>"
                >
                Reset
            </a>
          </div>

        </div>

      </div>
    </div>
  </form>

  <!-- ✅ TABLE -->
  <div class="card shadow-sm">
    <div class="card-body p-0">

      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0">

          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Type</th>
              <th>Access Token</th>
              <th>Check-In At</th>
              <th>Check-Out At</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <?php if (empty($data)): ?>
                <tr>
                    <td colspan="7" class="text-center">No check-in/out history found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($data as $i => $item): ?>
                    <tr>
                        <td><?= esc((($page - 1) * $limit) + $i + 1) ?></td>
                        <td><?= esc($item['name'] ?? '-') ?></td>
                        
                        <td>
                          <?php
                            $accessType = match($item['access_type']) {
                              'CASUAL' => 'bg-secondary',
                              'MEMBER' => 'bg-danger',
                              default => 'bg-primary'
                            };
                            ?>
                            <span class="badge <?= $accessType ?>"><?= esc($item['access_type'] ?? '-') ?></span><br>
                          <?php
                            $memberType = match($item['member_type']) {
                              'REGULAR' => 'bg-success',
                              'STUDENT' => 'bg-warning',
                              default => 'bg-primary'
                            };
                            ?>
                            <span class="badge <?= $memberType ?>"><?= esc($item['member_type'] ?? '-') ?></span>
                        </td>
                        <td><?= esc($item['access_token']) ?></td>
                        <td><?= esc($item['check_in_at'] ?? '-') ?></td>
                        <td><?= esc($item['check_out_at'] ?? '-') ?></td>
                        <td class="text-center">
                            <button
                                class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>

        </table>

        

      </div>

    </div>
  </div>

   <!-- ✅ PAGINATION -->
  <div class="d-flex align-items-baseline mt-3 px-3 gap-3">
      <div class="text-muted small">
          Showing page <?= esc($page ?? 1) ?>
      </div>

      <div>
          <?= $pager->links('default', 'bootstrap_custom') ?>
      </div>
  </div>


</div>

<?= $this->endSection() ?>
