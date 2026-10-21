<!-- ✅ ADMIN MEMBER PASS MODAL -->
<div class="modal fade" id="adminMemberPassModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">

      <form id="adminMemberPassForm" method="POST" action="<?= base_url('/admin/payment/member-pass/store') ?>">
        <?= csrf_field() ?>

        <!-- HEADER -->
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title" data-testid="member-modal-title">Create Membership</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- BODY -->
        <div class="modal-body">

          <div class="row g-4">

            <!-- PERSONAL CATEGORY -->
            <div class="col-md-4">
              <label class="form-label fw-semibold">Personal Category</label>
              <select id="adminMemberCategory" 
                      data-testid="member-personal-category"
                      name="target_type" 
                      class="form-select" 
                      required>
                <option value="">– Select –</option>
                <option value="REGULAR">Regular</option>
                <option value="STUDENT">Student</option>
              </select>
            </div>

            <!-- MEMBERSHIP PACKAGE -->
            <div class="col-md-4">
              <label class="form-label fw-semibold">Membership Package</label>
              <select id="adminMemberPackage" 
                      data-testid="member-membership-package"
                      name="membership_plan" 
                      class="form-select" 
                      required>
                <option value="">– Choose Membership –</option>

                <?php foreach (($memberPackages ?? []) as $package): ?>
                  <?php
                    $price = (float)$package['price'] ?? 0;
                    $duration = (int)$package['duration_days'];
                    $targetMemberType = strtoupper($package['target_member_type'] ?? 'ALL');
                  ?>

                  <option 
                    value="<?= esc($package['id']) ?>"
                    data-code="<?= esc($package['code'] ?? '', 'attr') ?>"
                    data-name="<?= esc($package['name'] ?? '', 'attr') ?>"
                    data-duration="<?= $duration ?>"
                    data-price="<?= $price ?>"
                    data-target-member-type = <?= esc($targetMemberType, 'attr') ?>
                  >
                    <?= esc($package['name']) ?> 
                    — 
                    <?= $duration ?> days 
                    — 
                    Rp <?= number_format($price,0,',','.') ?>
                  </option>
                <?php endforeach ?>
              </select>
            </div>

            <!-- PRICE DISPLAY -->
            <div class="col-md-4">
              <label class="form-label fw-semibold">Price</label>
              <input type="text" 
                     id="memberPriceDisplay" 
                     class="form-control" 
                     readonly>

              <input type="hidden" 
                     name="price" 
                     data-testid="member-price"
                     id="memberPrice">
            </div>

          </div>

          <hr class="my-4">

          <div class="row g-4">

            <!-- FULL NAME -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Full Name</label>
              <input type="text" 
                     name="name" 
                     data-testid="member-fullname"
                     class="form-control" 
                     required>
            </div>

            <!-- GENDER -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Gender</label>
              <select name="gender" data-testid="member-gender" class="form-select" required>
                <option value="">– Select –</option>
                <option value="MALE">Male</option>
                <option value="FEMALE">Female</option>
              </select>
            </div>

            <!-- DATE OF BIRTH -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Date of Birth</label>
              <input type="date" name="date_of_birth" data-testid="member-dob" class="form-control" required>
            </div>

            <!-- PHONE -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Phone</label>
              <input type="tel" name="phone" data-testid="member-phone" class="form-control" required>
            </div>

            <!-- EMAIL -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Email</label>
              <input type="email" name="email" data-testid="member-email" class="form-control" required>
            </div>

            <!-- ADDRESS -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Address</label>
              <input type="text" name="address" data-testid="member-address" class="form-control" required>
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

        <!-- FOOTER -->
        <div class="modal-footer">
          <button type="button" 
                  data-testid="member-cancel"
                  class="btn btn-secondary" 
                  data-bs-dismiss="modal">
            Cancel
          </button>

          <button class="btn btn-danger" type="submit" data-testid="member-create">
            Create Membership
          </button>
        </div>
        <div id="adminMemberWarning"
              class="alert alert-danger mt-3 d-none">
        </div>

      </form>

    </div>
  </div>
</div>