<?= $this->extend('pages/admin/layout/template') ?>

<?= $this->section('content') ?>

<div class="container py-4">

  <h2 class="fw-bold mb-4">Transaction Report</h2>

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
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="row g-3 align-items-end">
          <!-- METHOD -->
          <div class="col-md-2">
            <label class="form-label fw-bold">Method</label>
            <select name="method" class="form-select">
              <option value="">All</option>
              <option value="CASH" <?= ($_GET['method'] ?? '') == 'CASH' ? 'selected' : '' ?>>Cash</option>
              <option value="QRIS" <?= ($_GET['method'] ?? '') == 'QRIS' ? 'selected' : '' ?>>QRIS</option>
              <option value="TRANSFER" <?= ($_GET['method'] ?? '') == 'TRANSFER' ? 'selected' : '' ?>>Transfer</option>
            </select>
          </div>

          <!-- STATUS -->
          <div class="col-md-2">
            <label class="form-label fw-bold">Status</label>
            <select name="status" class="form-select">
              <option value="">All</option>
              <option value="SUCCESS" <?= ($_GET['status'] ?? '') == 'SUCCESS' ? 'selected' : '' ?>>Success</option>
              <option value="FAILED" <?= ($_GET['status'] ?? '') == 'FAILED' ? 'selected' : '' ?>>Failed</option>
              <option value="REFUNDED" <?= ($_GET['status'] ?? '') == 'REFUNDED' ? 'selected' : '' ?>>Refunded</option>
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
              <option value="50" <?= ($_GET['limit'] ?? '') == '100' ? 'selected' : '' ?>>100</option>
            </select>
          </div>

          <!-- ACTIONS -->
          <div class="col-md-1 d-grid">
            <button class="btn btn-primary">Filter</button>
          </div>

          <div class="col-md-1 d-grid">
            <a class="btn btn-danger" 
               href="<?= site_url('admin/transaction') ?>">
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
              <th>Phone</th>
              <th>Total</th>
              <th>Method</th>
              <th>Status</th>
              <th>Date</th>
            </tr>
          </thead>

          <tbody>
            <?php if (empty($transactions)): ?>
              <tr>
                <td colspan="7" class="text-center p-4 text-muted">
                  No transaction data found
                </td>
              </tr>
            <?php else: ?>

              <?php foreach ($transactions as $i => $t): ?>
                <tr>

                  <td><?= esc((($page - 1) * $limit) + $i + 1) ?></td>

                  <td class="fw-semibold">
                    <?= esc($t['name'] ?? '-') ?>
                  </td>

                  <td>
                    <?= esc($t['phone'] ?? '-') ?>
                  </td>

                  <td class="fw-bold">
                    Rp <?= number_format($t['total_amount'], 0, ',', '.') ?>
                  </td>

                  <td>
                    <span class="badge bg-secondary">
                      <?= esc($t['payment_method'] ?? '-') ?>
                    </span>
                  </td>

                  <td>
                    <?php
                      $status = $t['payment_status'] ?? '-';

                      $badge = match ($status) {
                        'SUCCESS'  => 'bg-success',
                        'FAILED'   => 'bg-danger',
                        'REFUNDED' => 'bg-warning text-dark',
                        default    => 'bg-secondary',
                      };
                    ?>
                    <span class="badge <?= $badge ?>">
                      <?= esc($status) ?>
                    </span>
                  </td>

                  <td>
                    <?= !empty($t['paid_at'])
                      ? date('d M Y H:i', strtotime($t['paid_at']))
                      : '-' ?>
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
   <?php if (!empty($pager)): ?>
  <div class="d-flex align-items-baseline mt-3 gap-3">
    <div class="text-muted small">
      Showing page <?= $pager->getCurrentPage() ?>
    </div>
    <div>
      <?= $pager->links('default', 'bootstrap_custom') ?>
    </div>
  </div>

</div>
<?php endif; ?>

<?= $this->endSection() ?>
