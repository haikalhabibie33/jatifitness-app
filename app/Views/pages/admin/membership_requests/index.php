<?= $this->extend('pages/admin/layout/template') ?>

<?= $this->section('content') ?>

<?php
$totalPending = count(array_filter($requests, fn($r) => $r['status'] === 'SUBMITTED'));
$totalApproved = count(array_filter($requests, function($r){
    return in_array($r['status'], ['APPROVED', 'PAID', 'COMPLETED']);
}));
    
$totalRejected = count(array_filter($requests, fn($r) => $r['status'] === 'REJECTED'));
$totalMember = count(array_filter($requests, fn($r) => $r['request_type'] === 'MEMBER'));
$totalCasual = count(array_filter($requests, fn($r) => $r['request_type'] === 'CASUAL'));
?>

<div class="container py-4">

  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="fw-bold mb-1">Membership Requests</h2>
      <div class="text-muted small">
        Manage student membership requests / casual passes.
      </div>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="row g-3 mb-4">

    <div class="col-md-4 col-lg-2">
      <div class="card shadow-sm bg-light">
        <div class="card-body">
          <div class="text-muted small mb-1">Pending</div>
          <div class="d-flex align-items-center justify-content-between">
            <h4 class="fw-bold mb-0" data-testid="pending-card"><?= esc($totalPending) ?></h4>
            <span class="badge bg-warning text-dark">Waiting</span>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4 col-lg-2">
      <div class="card shadow-sm bg-light">
        <div class="card-body">
          <div class="text-muted small mb-1">Approved</div>
          <div class="d-flex align-items-center justify-content-between">
            <h4 class="fw-bold mb-0" data-testid="approved-card"><?= esc($totalApproved) ?></h4>
            <span class="badge bg-success">Done</span>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4 col-lg-2">
      <div class="card shadow-sm bg-light">
        <div class="card-body">
          <div class="text-muted small mb-1">Rejected</div>
          <div class="d-flex align-items-center justify-content-between">
            <h4 class="fw-bold mb-0" data-testid="rejected-card"><?= esc($totalRejected) ?></h4>
            <span class="badge bg-danger">Rejected</span>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4 col-lg-2">
      <div class="card shadow-sm bg-light">
        <div class="card-body">
          <div class="text-muted small mb-1">Member</div>
          <div class="d-flex align-items-center justify-content-between">
            <h4 class="fw-bold mb-0" data-testid="member-card"><?= esc($totalMember) ?></h4>
            <span class="badge bg-primary">Member</span>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4 col-lg-2">
      <div class="card shadow-sm bg-light">
        <div class="card-body">
          <div class="text-muted small mb-1">Casual</div>
          <div class="d-flex align-items-center justify-content-between">
            <h4 class="fw-bold mb-0" data-testid="casual-card"><?= esc($totalCasual) ?></h4>
            <span class="badge bg-secondary">Casual</span>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Filter Section -->
  <div class="card shadow-sm mb-4 bg-light">
    <div class="card-body">
      <div class="row g-3 align-items-end">

        <div class="col-md-3">
          <label class="form-label small text-muted fw-bold">Search</label>
          <input
            type="text"
            id="searchInput"
            data-testid="search-input"
            class="form-control"
            placeholder="Search name/code/phone...">
        </div>

        <div class="col-md-2">
          <label class="form-label small text-muted fw-bold">Request Type</label>
          <select id="typeFilter" class="form-select" data-testid="select-request-type">
            <option value="">All Type</option>
            <option value="MEMBER">Member</option>
            <option value="CASUAL">Casual</option>
          </select>
        </div>

        <div class="col-md-2 d-grid">
          <button id="applyFilterBtn" class="btn btn-dark" data-testid="apply-filter">
            <i class="bi bi-funnel me-1"></i>
            Apply Filter
          </button>

        </div>

        <div class="col-md-2 d-grid">
          <button class="btn btn-danger" 
            onclick="window.location.reload()"
            data-testid="refresh-button">
            <i class="bi bi-arrow-clockwise me-1"></i>
            Reset
          </button>
        </div>

      </div>
    </div>
  </div>

  <!-- Requests Table -->
  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">

        <table class="table table-hover align-middle mb-0" data-testid="membership-request-table">
          <thead class="table-dark">
            <tr>
              <th class="d-none">Token</th>
              <th>#</th>
              <th>Customer</th>
              <th>Type</th>
              <th>Plan</th>
              <th>Status</th>
              <th>Created</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>

          <tbody>
            <?php if (empty($requests ?? [])): ?>
              <tr data-testid="membership-request-empty">
                <td colspan="11" class="text-center text-muted py-4">
                  No membership requests available.
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($requests as $index => $r): ?>

                <?php
                  $typeBadge = match ($r['request_type']) {
                    'MEMBER' => 'bg-primary',
                    'CASUAL' => 'bg-warning text-dark',
                    default => 'bg-dark',
                  };

                  $paymentBadge = match ($r['payment_reference']) {
                    'ONLINE' => 'bg-info text-dark',
                    'OFFLINE' => 'bg-secondary',
                    'ADMIN-SYSTEM' => 'bg-warning text-dark',
                    default => 'bg-dark',
                  };

                  $paymentStatusBadge = match ($r['status']) {
                    'COMPLETED' => 'bg-success',
                    'UNPAID' => 'bg-danger',
                    'SUBMITTED' => 'bg-warning text-dark',
                    'REJECTED' => 'bg-danger',
                    default => 'bg-secondary',
                  };

                  $requestStatusBadge = match ($r['status']) {
                    'SUBMITTED' => 'bg-warning text-dark',
                    'APPROVED' => 'bg-primary',
                    'COMPLETED' => 'bg-success',
                    'REJECTED' => 'bg-danger',
                    default => 'bg-secondary',
                  };

                  $totalPrice = $r['price_snapshot'];
                ?>

                <tr 
                  data-name="<?= esc(strtolower($r['name'] ?? ''), 'attr') ?>"
                  data-phone="<?= esc(strtolower($r['phone'] ?? ''), 'attr') ?>"
                  data-type="<?= esc($r['request_type'] ?? '', 'attr') ?>"
                  data-plan="<?= esc(strtolower(($r['plan_code_snapshot'] ?? '') . ' ' . ($r['plan_name_snapshot'] ?? '')), 'attr') ?>"
                  data-testid="membership-request-rows"
                  >

                  <td class="d-none" data-testid="token-column"><?= esc($r['access_token']) ?></td>

                  <td data-testid="number-column"><?= esc($index + 1) ?></td>

                  <td>
                    <div class="fw-semibold" data-testid="name-column"><?= esc($r['name']) ?></div>
                    <div class="text-muted small" data-testid="phone-column"><?= esc($r['phone']) ?></div>
                  </td>

                  <td>
                    <span class="badge <?= esc($typeBadge) ?>" data-testid="request-type-column">
                      <?= esc($r['request_type']) ?>
                    </span>

                    <?php if ($r['target_member_type'] !== '-'): ?>
                      <div class="small text-muted mt-1" data-testid="member-type-column">
                        <?= esc($r['target_member_type']) ?>
                      </div>
                    <?php endif; ?>
                  </td>

                  <td data-testid="plan-column">
                    <div class="fw-semibold" data-testid="plan-name-column"><?= esc($r['plan_name_snapshot']) ?></div>
                    <div class="text-muted small" data-testid="plan-code-duration-column">
                      <?= esc($r['plan_code_snapshot']) ?> · <?= esc($r['duration_snapshot']) ?> days
                    </div>
                  </td>

                  <td data-testid="status-column">
                    <span class="badge <?= esc($requestStatusBadge) ?>">
                      <?= esc($r['status']) ?>
                    </span>
                  </td>

                  <td>
                    <div data-testid="date-column"><?= date('d M Y', strtotime($r['created_at'])) ?></div>
                    <div class="text-muted small" data-testid="time-column"><?= date('H:i', strtotime($r['created_at'])) ?></div>
                  </td>

                  <td class="text-center">
                    <div class="btn-group">

                      <!-- Detail Button -->
                      <button
                        type="button"
                        class="btn btn-sm btn-outline-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#detailRequestModal"
                        data-id="<?= esc($r['id']) ?>"
                        data-type="<?= esc($r['request_type']) ?>"
                        data-name="<?= esc($r['name']) ?>"
                        data-gender="<?= esc($r['gender']) ?>"
                        data-dob="<?= esc($r['date_of_birth']) ?>"
                        data-phone="<?= esc($r['phone']) ?>"
                        data-email="<?= esc($r['email']) ?>"
                        data-member-type="<?= esc($r['target_member_type']) ?>"
                        data-plan-code="<?= esc($r['plan_code_snapshot']) ?>"
                        data-plan-name="<?= esc($r['plan_name_snapshot']) ?>"
                        data-duration="<?= esc($r['duration_snapshot']) ?>"
                        data-qty="<?= esc($r['quantity']) ?>"
                        data-price="<?= esc($r['price_snapshot']) ?>"
                        data-payment-method="<?= esc($r['payment_method']) ?>"
                        data-payment-status="<?= esc($r['status']) ?>"
                        data-status="<?= esc($r['status']) ?>"
                        data-created="<?= esc($r['created_at']) ?>"
                        data-testid="detail-request-btn"
                        >
                        <i class="bi bi-eye"></i>
                      </button>

                      <?php if (in_array($r['status'], ['SUBMITTED', 'REJECTED'])): ?>

                        <!-- Approve Button -->
                        <button
                          type="button"
                          class="btn btn-sm btn-outline-success"
                          data-bs-toggle="modal"
                          data-bs-target="#approveRequestModal"
                          data-id="<?= esc($r['id'], 'attr') ?>"
                          data-name="<?= esc($r['name'], 'attr') ?>"
                          data-type="<?= esc($r['request_type'], 'attr') ?>"
                          data-action="<?= base_url('admin/membership-request/approve/' . $r['id']) ?>"
                          data-testid="approve-request-btn"
                          >
                          <i class="bi bi-check-circle"></i>
                        </button>
                        
                        <!-- Reject Button -->
                        <button
                          type="button"
                          class="btn btn-sm btn-outline-danger"
                          data-bs-toggle="modal"
                          data-bs-target="#rejectRequestModal"
                          data-id="<?= esc($r['id'], 'attr') ?>"
                          data-name="<?= esc($r['name'], 'attr') ?>"
                          data-type="<?= esc($r['request_type'], 'attr') ?>"
                          data-action="<?= base_url('admin/membership-request/reject/' . $r['id']) ?>"
                          data-testid="reject-request-btn"
                          >
                          <i class="bi bi-x-circle"></i>
                        </button>

                      <?php else: ?>

                        <button type="button" 
                                class="btn btn-sm btn-outline-secondary" 
                                disabled
                                data-testid="disabled-action"
                                >
                          <i class="bi bi-lock"></i>
                        </button>

                      <?php endif; ?>

                    </div>
                  </td>
                </tr>

              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>

      </div>
    </div>
  </div>

</div>

<!-- Detail Request Modal -->
<?= view('pages/admin/membership_requests/modals/detail_request') ?>

<!-- Approve Request Modal -->
<?= view('pages/admin/membership_requests/modals/approve_request') ?>

<!-- Reject Request Modal -->
<?= view('pages/admin/membership_requests/modals/reject_request') ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const formatRupiah = function (number) {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0
    }).format(number);
  };

  /**
   * Detail modal
   */
  const detailRequestModal = document.getElementById('detailRequestModal');

  if (detailRequestModal) {
    detailRequestModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;

      document.getElementById('detailName').textContent = button.getAttribute('data-name') || '-';
      document.getElementById('detailGender').textContent = button.getAttribute('data-gender') || '-';
      document.getElementById('detailDob').textContent = button.getAttribute('data-dob') || '-';
      document.getElementById('detailPhone').textContent = button.getAttribute('data-phone') || '-';
      document.getElementById('detailEmail').textContent = button.getAttribute('data-email') || '-';
      document.getElementById('detailType').textContent = button.getAttribute('data-type') || '-';
      document.getElementById('detailMemberType').textContent = button.getAttribute('data-member-type') || '-';

      document.getElementById('detailPlanName').textContent = button.getAttribute('data-plan-name') || '-';
      document.getElementById('detailDuration').textContent = (button.getAttribute('data-duration') || '-') + ' days';
      document.getElementById('detailQty').textContent = (button.getAttribute('data-qty') || '-') + ' item';

      const price = button.getAttribute('data-price') || 0;
      document.getElementById('detailPrice').textContent = formatRupiah(price);

      document.getElementById('detailPaymentMethod').textContent = button.getAttribute('data-payment-method') || 'NONE';
      const rawStatus = button.getAttribute('data-payment-status') || '';
      const paymentStatus = rawStatus === 'PAID' || rawStatus === 'COMPLETED'
                    ? 'PAID'
                    : 'UNPAID';

      document.getElementById('detailPaymentStatus').textContent = paymentStatus;
    });
  }

  /**
   * Approve modal
   */
  const approveRequestModal = document.getElementById('approveRequestModal');

  if (approveRequestModal) {
    approveRequestModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;

      const requestId = button.getAttribute('data-id') || '';
      const actionUrl = button.getAttribute('data-action') || '';

      document.getElementById('approveRequestId').value = button.getAttribute('data-id') || '';
      document.getElementById('approveCustomerName').textContent = button.getAttribute('data-name') || '-';
      document.getElementById('approveRequestType').textContent = button.getAttribute('data-type') || '-';

      const form = document.getElementById('approveRequestForm');
      if (form){
        form.setAttribute('action', actionUrl);
      }
    });
  }

  /**
   * Reject modal
   */
  const rejectRequestModal = document.getElementById('rejectRequestModal');

  if (rejectRequestModal) {
    rejectRequestModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;

      const requestId = button.getAttribute('data-id') || '';
      const actionUrl = button.getAttribute('data-action') || '';

      document.getElementById('rejectRequestId').value = button.getAttribute('data-id') || '';
      document.getElementById('rejectCustomerName').textContent = button.getAttribute('data-name') || '-';
      document.getElementById('rejectRequestType').textContent = button.getAttribute('data-type') || '-';

      const form = document.getElementById('rejectRequestForm');
      if (form){
        form.setAttribute('action', actionUrl);
      }
    });
  }

  /**
 * FILTER + SEARCH
 */
const applyFilterBtn = document.getElementById('applyFilterBtn');
const searchInput = document.getElementById('searchInput');
const typeFilter = document.getElementById('typeFilter');

  if (applyFilterBtn) {
    applyFilterBtn.addEventListener('click', function () {
      const searchKeyword = (searchInput.value || '').toLowerCase();
      const selectedType = typeFilter.value;

      const rows = document.querySelectorAll('tbody tr');

      rows.forEach(row => {
        const name = row.getAttribute('data-name') || '';
        const phone = row.getAttribute('data-phone') || '';
        const type = row.getAttribute('data-type') || '';
        const plan = row.getAttribute('data-plan') || '';

        const matchSearch =
          name.includes(searchKeyword) ||
          phone.includes(searchKeyword) ||
          plan.includes(searchKeyword);

        const matchType =
          selectedType === '' || type === selectedType;

        if (matchSearch && matchType) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });
  }
  if (searchInput) {
  searchInput.addEventListener('keyup', function () {
    applyFilterBtn.click();
  });
  }

  if (typeFilter) {
    typeFilter.addEventListener('change', function () {
      applyFilterBtn.click();
    });
  }
});
</script>

<?= $this->endSection() ?>