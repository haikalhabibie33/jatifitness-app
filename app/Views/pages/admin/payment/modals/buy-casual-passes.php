<!-- ✅ ADMIN CASUAL PASS MODAL -->
<div class="modal fade" id="adminCasualPassModal" tabindex="-1" aria-labelledby="adminCasualPassModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">

      <form method="POST" action="<?= base_url('/admin/payment/casual-pass/store') ?>" id="adminCasualPassForm">
        <?= csrf_field() ?>

        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title" id="adminCasualPassModalLabel" data-testid="casual-modal-title">
            Sell Casual Pass
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">

          <div class="row g-4">

            <!-- Personal Category -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Personal Category</label>
              <select
                id="adminCasualCategory"
                data-testid="casual-personal-category"
                name="target_type"
                class="form-select"
                required>
                <option value="">– Select your Category –</option>
                <option value="REGULAR">Regular</option>
                <option value="STUDENT">Student</option>
              </select>
            </div>

            <!-- Casual Package -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Casual Package</label>
              <select
                id="adminCasualPackage"
                data-testid="casual-package"
                name="membership_plan"
                class="form-select"
                required>
                <option value="">– Choose Package –</option>

                <?php foreach (($casualPackages ?? []) as $package): ?>
                    
                <?php
                    $price = (float) ($package['price'] ?? 0);
                    $duration = (int) ($package['duration_days'] ?? $package['duration'] ?? 0);
                    $targetMemberType = strtoupper($package['target_member_type'] ?? 'ALL');
                ?>

                  <option
                    value="<?= esc($package['id'], 'attr') ?>"
                    data-code="<?= esc($package['code'] ?? '', 'attr') ?>"
                    data-name="<?= esc($package['name'] ?? '', 'attr') ?>"
                    data-duration="<?= esc($duration, 'attr') ?>"
                    data-price="<?= esc($price, 'attr') ?>"
                    data-target-member-type = "<?= esc($targetMemberType, 'attr') ?>"
                    >
                    <?= esc($package['name'] ?? '-') ?>
                    —
                    <?= esc($duration) ?> days
                    —
                    <?= esc($targetMemberType) ?>
                    —
                    Rp <?= number_format($price,0,',','.')  ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Quantity -->
            <div class="col-md-4">
              <label class="form-label fw-semibold">Package Quantity (max:30)</label>
              <input
                type="number"
                name="quantity"
                id="adminCasualQuantity"
                data-testid="casual-quantity"
                class="form-control"
                value="1"
                min="1"
                max="30"
                required>
            </div>

            <!-- Total Duration -->
            <div class="col-md-4">
              <label class="form-label fw-semibold">Total Duration (Days)</label>
              <input
                type="text"
                name="total_duration"
                id="adminCasualTotalDuration"
                data-testid="casual-total-duration"
                class="form-control"
                placeholder="Total Duration"
                readonly>
            </div>

            <!-- Total Price -->
            <div class="col-md-4">
              <label class="form-label fw-semibold">Total Price (IDR)</label>
              <input
                type="text"
                id="adminCasualTotalPriceDisplay"
                data-testid="casual-total-price"
                class="form-control"
                placeholder="Total Price"
                readonly>

              <input
                type="hidden"
                name="total_price"
                id="adminCasualTotalPrice">
            </div>

            <!-- Full Name -->
            <div class="col-md-3">
              <label class="form-label fw-semibold">Full Name</label>
              <input
                type="text"
                name="name"
                id="adminCasualName"
                data-testid="casual-fullname"
                class="form-control"
                placeholder="Name"
                required>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Gender</label>
              <select name="gender" data-testid="casual-gender" class="form-select" required>
                <option value="">– Select –</option>
                <option value="MALE">Male</option>
                <option value="FEMALE">Female</option>
              </select>
            </div>

            <!-- Phone -->
            <div class="col-md-3">
              <label class="form-label fw-semibold">Phone Number</label>
              <input
                type="tel"
                name="phone"
                id="adminCasualPhone"
                data-testid="casual-phone"
                class="form-control"
                placeholder="Phone"
                required>
            </div>

            <!-- Email -->
            <div class="col-md-3">
              <label class="form-label fw-semibold">Email Address</label>
              <input
                type="email"
                name="email"
                id="adminCasualEmail"
                data-testid="casual-email"
                class="form-control"
                placeholder="Email"
                required>
            </div>

            <div class="col-md-12">
            <label class="form-label fw-semibold">Payment Method</label>

            <div class="row g-3">

              <div class="col-md-4">
                <label
                  class="payment-method-card payment-cash"
                  for="adminPaymentCash"
                  data-payment-card
                >
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="payment_method"
                      id="adminPaymentCash"
                      value="CASH"
                      checked
                      data-testid="admin-payment-method-cash"
                    >
                    <span class="form-check-label fw-semibold">
                      Cash
                    </span>
                  </div>

                  <div class="small text-muted mt-2">
                    Customer pays directly at the front desk.
                  </div>
                </label>
              </div>

              <div class="col-md-4">
                <label
                  class="payment-method-card payment-transfer"
                  for="adminPaymentTransfer"
                  data-payment-card
                >
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="payment_method"
                      id="adminPaymentTransfer"
                      value="TRANSFER"
                      data-testid="admin-payment-method-transfer"
                    >
                    <span class="form-check-label fw-semibold">
                      Bank Transfer
                    </span>
                  </div>

                  <div class="small text-muted mt-2">
                    Customer transfers payment and shows proof later.
                  </div>
                </label>
              </div>

              <div class="col-md-4">
                <label
                  class="payment-method-card payment-qris disabled"
                  for="adminPaymentQris"
                  data-payment-card
                >
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="payment_method"
                      id="adminPaymentQris"
                      value="QRIS"
                      disabled
                      data-testid="admin-payment-method-qris"
                    >
                    <span class="form-check-label fw-semibold">
                      QRIS
                    </span>
                  </div>

                  <div class="small text-muted mt-2">
                    Coming soon via payment gateway.
                  </div>
                </label>
              </div>

            </div>
          </div>

          </div>

          <div class="alert alert-info mt-4 mb-0">
            This form is used by admin to create a casual pass purchase directly from payment processing page.
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-testid="casual-cancel">
            Cancel
          </button>

          <button type="submit" class="btn btn-danger" data-testid="casual-create">
            Create Casual Pass
          </button>
        </div>

      </form>

    </div>
  </div>
</div>