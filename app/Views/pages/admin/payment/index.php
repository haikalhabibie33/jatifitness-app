<?= $this->extend('pages/admin/layout/template') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-3 py-md-4">
  <div class="mx-auto" style="max-width: 1200px;">

    <h2 class="fw-bold mb-4">Payment Processing</h2>

    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
      <!-- ✅ SCANNER BUTTON -->
      <button type="button"
              data-testid="scan-qr-btn" 
              class="btn btn-outline-secondary payment-action-btn" 
              onclick="startScanner()">
        <i class="bi bi-camera me-2"></i>
        Scan QR
      </button>

      <button type="button"
              data-testid="stop-qr-btn" 
              class="btn btn-danger payment-action-btn" 
              onclick="stopScanner()">
        <i class="bi bi-stop-circle me-2"></i>
        Stop
      </button>
      <!-- ✅ BUY CASUAL PASS BUTTON -->
      <button
        type="button"
        data-testid="buy-casual-passes"
        class="btn btn-warning text-dark fw-bold payment-action-btn"
        data-bs-toggle="modal"
        data-bs-target="#adminCasualPassModal">
        <i class="bi bi-ticket-perforated me-2"></i>
        Casual Pass
      </button>

      <button
        type="button"
        data-testid="buy-member-passes"
        class="btn btn-secondary text-dark fw-bold payment-action-btn"
        data-bs-toggle="modal"
        data-bs-target="#adminMemberPassModal">
        <i class="bi bi-person-badge me-2"></i>
        Member Pass
      </button>
    </div>

    <!-- ✅ ACTION BAR -->
    <div class="row g-3 mb-3">
      <div class="card shadow-sm bg-light">
        <div class="card-body">
          <div class="col-12 col-md-8">
             <!-- ✅ TOKEN FORM -->
            <form 
                method="GET" 
                action="<?= base_url('/admin/payment') ?>" 
                class="d-flex gap-2"
                id="tokenSearchForm"
                >

              <div class="input-group">
                <span class="input-group-text">🔑📱</span>
                <input data-testid="input-token-qr"
                       type="text"
                       name="input"
                       id="tokenInput"
                       class="form-control"
                       placeholder="Paste token, phone number, or Scan QR ...">
              </div>
    
              <button data-testid="load-token-qr-btn" 
                      class="btn btn-outline-primary payment-action-btn-sm">
                      Load
              </button>
          </div>

          <div class="d-flex gap-4 mt-2">
            <div class="form-check">
              <input class="form-check-input" 
                    type="radio" 
                    name="mode" 
                    value="auto"
                    id="flexRadioDefault1"
                    checked>
              <label class="form-check-label" for="flexRadioDefault1">
                Auto
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" 
                    type="radio" 
                    name="mode" 
                    id="flexRadioDefault2" 
                    value="phone">
              <label class="form-check-label" for="flexRadioDefault2">
                Phone
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" 
                    type="radio" 
                    name="mode" 
                    id="flexRadioDefault2" 
                    value="token">
              <label class="form-check-label" for="flexRadioDefault2">
                Token
              </label>
            </div>
          </form>
          </div>

        </div>
      </div>
      

    </div>
    
    

    <!-- ✅ QR SCANNER AREA -->
    <div id="qr-reader" style="display:none; max-width:400px;" class="mb-4"></div>

    <!-- ✅ TABLE RESULTS -->
    <?php if (!empty($searchResults)): ?>

    <div class="card shadow-sm">
      <div class="card-body">

        <h6 class="fw-bold mb-3">Payment Candidates</h6>

        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle" data-testid="payment-processing-table">

            <thead class="table-dark">
              <tr>
                <th class="d-none">Access Token</th>
                <th>Name</th>
                <th>Contacts</th>
                <th>Plan</th>
                <th>Details</th>
                <th>Total</th>
                <th>Date</th>
                <th>Status</th>
                <th width="140">Action</th>
              </tr>
            </thead>

            <tbody>
            <?php foreach ($searchResults as $item):

              $isRegular = $item['target_member_type'] === 'REGULAR';
              $isStudent = $item['target_member_type'] === 'STUDENT';

              $canConfirm =
                  ($isRegular && $item['status'] === 'SUBMITTED') ||
                  ($isStudent && $item['status'] === 'APPROVED');

            ?>
              <tr data-testid="payment-processing-row">

                <td class="d-none" data-testid="access-token-column">
                  <?= esc($item['access_token']) ?>
                </td>

                <td data-testid="name-column"><?= esc($item['name']) ?></td>

                <td data-testid="phone-column">
                  <?= esc($item['phone']) ?><br>
                  <small data-testid="email-column" class="text-muted"><?= esc($item['email']) ?></small>
                </td>

                <td data-testid="plan-name-column">
                  <?= esc($item['plan_name_snapshot']) ?><br>
                  <small data-testid="duration-column"><?= esc($item['duration_snapshot']) ?> days</small>
                </td>

                <td>
                  <span data-testid="member-type-column" class="badge <?= $isStudent ? 'bg-info' : 'bg-secondary' ?>">
                    <?= esc($item['target_member_type']) ?>
                  </span>
                  <?php
                    $paymentBadge = match ($item['payment_method']){
                      'CASH'      => 'bg-primary',
                      'TRANSFER'  => 'bg-warning text-dark',
                      'QRIS'      => 'bg-danger text-dark',
                      default     => 'bg-primary'
                    };
                  ?>
                  <span class="badge <?= $paymentBadge ?>">
                    <?= $item['payment_method'] ?>
                  </span>
                </td>

                <td data-testid="price-column" class="fw-bold">
                  <?= number_format($item['total_price_snapshot']) ?>
                </td>

                <td>
                  <?= esc($item['created_at']) ?>
                </td>

                <td>
                  <?php
                    $badge = match ($item['status']) {
                      'COMPLETED' => 'bg-success',
                      'PAID'      => 'bg-warning text-dark',
                      'APPROVED'  => 'bg-info',
                      default     => 'bg-secondary',
                    };
                  ?>
                  <span data-testid="status-column" class="badge <?= $badge ?>">
                    <?= esc($item['status']) ?>
                  </span>
                </td>

                <td>

                  <?php if ($canConfirm): ?>

                    <button 
                      data-testid="confirm-payment-btn"
                      class="btn btn-sm btn-success payment-action-btn-sm"
                      onclick="confirmPayment(
                        '<?= esc($item['name'], 'js') ?>',
                        '<?= esc($item['phone'], 'js') ?>',
                        '<?= number_format($item['total_price_snapshot']) ?>',
                        '<?= esc($item['access_token'], 'js') ?>',
                        '<?= esc($item['payment_method'] ?? 'CASH', 'js') ?>'
                      )">
                      Confirm
                    </button>

                  <?php elseif ($item['target_member_type'] === 'STUDENT' && $item['status'] === 'SUBMITTED'): ?>

                    <span class="text-danger small" data-testid="payment-wait-approval">Awaiting approval</span>

                  <?php elseif ($item['status'] === 'COMPLETED' || $item['status'] === 'PAID'): ?>
                    
                    <a href="<?= base_url('/admin/payment/access/' . $item['access_token']) ?>"
                       target="_blank"
                       data-testid="payment-view-qr"
                       class="btn btn-sm btn-outline-success">
                      View QR
                    </a>

                  <?php else: ?>

                    <span class="text-muted small" data-testid="payment-not-eligible">Not eligible</span>

                  <?php endif; ?>

                </td>

              </tr>
            <?php endforeach; ?>
            </tbody>

          </table>
        </div>

      </div>
    </div>

    <?php endif; ?>

  </div>

  <style>
  .payment-action-btn {
    width: 125px;
    height: 36px;
    flex: 0 0 125px;
    box-sizing: border-box;

    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;

    padding: 0 10px;
    font-size: 13.5px;
    font-weight: 600;
    line-height: 1;
    white-space: nowrap;
  }

.payment-action-btn-sm {
    width: 82px;
    height: 36px;
    flex: 0 0 82px;
    box-sizing: border-box;

    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;

    padding: 0 9px;
    font-size: 13.5px;
    font-weight: 600;
    line-height: 1;
    white-space: nowrap;
  }

.payment-action-btn i,
.payment-action-btn-sm i {
    font-size: 14px;
    line-height: 1;
  }

  .form-check-label {
    user-select: none;
  }

  .form-check-input:checked + .form-check-label {
    font-weight: bold;
  }

  .payment-method-card {
  display: block;
  width: 100%;
  height: 100%;
  min-height: 112px;
  padding: 16px;
  border: 2px solid transparent;
  border-radius: 10px;
  cursor: pointer;
  user-select: none;
  transition:
    border-color 0.2s ease,
    box-shadow 0.2s ease,
    transform 0.2s ease;
}

.payment-method-card:hover:not(.disabled) {
  transform: translateY(-2px);
  box-shadow: 0 5px 14px rgba(0, 0, 0, 0.1);
}

/* Cash: hijau pastel */
.payment-cash {
  color: #155724;
  background-color: #e8f5e9;
  border-color: #b7dfbd;
}

/* Transfer: kuning pastel */
.payment-transfer {
  color: #664d03;
  background-color: #fff8dd;
  border-color: #f3da8a;
}

/* QRIS: abu-abu */
.payment-qris {
  color: #6c757d;
  background-color: #f1f3f5;
  border-color: #dee2e6;
}

/* Selected state */
.payment-method-card:has(input:checked) {
  border-color: #0d6efd;
  box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.14);
}

/* Disabled state */
.payment-method-card.disabled {
  cursor: not-allowed;
  opacity: 0.65;
}

.payment-method-card .form-check {
  margin: 0;
}

.payment-method-card .small {
  line-height: 1.5;
}
</style>
</div>

<!-- ✅ MODAL -->
<?= view('pages/admin/payment/modals/buy-casual-passes') ?> 
<?= view('pages/admin/payment/modals/buy-member-passes') ?> 
<?= view('pages/admin/payment/modals/confirm-payment') ?>


<!-- ✅ SCRIPT -->
<script>
function confirmPayment(name, phone, total, token, paymentMethod) {
  const method = (paymentMethod || 'CASH').toUpperCase();

  document.getElementById('modalName').innerText = name;
  document.getElementById('modalPhone').innerText = phone;
  document.getElementById('modalTotal').innerText = total;
  document.getElementById('modalToken').value = token;
  document.getElementById('modalPaymentMethod').value = method;

  const methodLabel = document.getElementById('modalPaymentMethodLabel');
        methodLabel.innerText = method;
        methodLabel.className = 'badge';
        if (method === 'CASH') {
        methodLabel.classList.add('bg-primary');
        } else if (method === 'TRANSFER') {
        methodLabel.classList.add('bg-warning', 'text-dark');
        } else if (method === 'QRIS') {
        methodLabel.classList.add('bg-danger');
        } else {
        methodLabel.classList.add('bg-secondary');
        }

  new bootstrap.Modal(document.getElementById('confirmModal')).show();
}
</script>

<!-- PAYMENT CARD SELECTION -->
<script>
  document.querySelectorAll('[data-payment-card]').forEach(card => {
  card.addEventListener('click', event => {
    const radio = card.querySelector('input[type="radio"]');

    if (!radio || radio.disabled) {
      return;
    }

    radio.checked = true;
    radio.dispatchEvent(new Event('change', { bubbles: true }));
  });
});
</script>

<!-- ✅ QR LIB + JS -->
<script src="https://unpkg.com/html5-qrcode"></script>
<script src="<?= base_url('assets/js/payment-qr.js') ?>"></script>

<!-- casual passes handler -->
<script src="<?= base_url('assets/js/buy-casual-passes.js') ?>"></script>
<script src="<?= base_url('assets/js/buy-member-passes.js') ?>"></script>
<script src="<?= base_url('assets/js/precheck-buy-member.js') ?>"></script>
<script src="<?= base_url('assets/js/load-token-after-registration.js') ?>"></script>


<?= $this->endSection() ?>
