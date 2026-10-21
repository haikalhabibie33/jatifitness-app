
<!-- form casual -->
<form class="js-form-casual" id="contact-form" action="<?= base_url('membership/submit-casual') ?>" method="POST">
    <?= csrf_field() ?>

    <input type="hidden" 
           name="payment_method" 
           id="casualPaymentMethodInput"
           value="CASH"
           >
           
    <div class="row">
        <div class="col-12">
            <div class="row border border-danger rounded p-5">
                <div class="col-lg-6 col-md-6">
                    <label class="form-label-enhanced" data-testid="casual-personal-label">Personal Category</label>
                    <div class="select-itms">
                            <select name="target_type" id="casualCategory" data-testid="select-casual-personal" required>
                                <option value="" selected disabled>-- Select your Category --</option>
                                <option value="REGULAR">Regular</option>
                                <option value="STUDENT">Student</option>
                            </select>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="select-itms">
                            <label class="form-label-enhanced" data-testid="casual-package-label">Casual Package</label>
                            <select id="casualPackage" name="membership_plan" data-testid="select-casual-package" required>
                                <option value="" selected disabled>-- Choose Package --</option>
                                <?php foreach ($casualPlans as $plan): ?>
                                <option value="<?= esc($plan['id']) ?>"
                                        data-target="<?= esc($plan['target_member_type']) ?>"
                                        data-audience="<?= esc($plan['audience_scope']) ?>"
                                        data-duration="<?= esc($plan['duration_days']) ?>"
                                        data-price="<?= esc($plan['price']) ?>"
                                        >
                                        <?= esc($plan['name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-box user-icon mb-30"> 
                        <label class="form-label-enhanced" data-testid="casual-quantity-label">Package Quantity (max:30)</label>
                        <input type="number" 
                                name="quantity" 
                                id="quantityInput"
                                placeholder="Quantity"
                                data-testid="casual-quantity-input"
                                min="1"
                                max="30"
                                step="1"
                                value="1"
                                required>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-box user-icon mb-30"> 
                        <label class="form-label-enhanced" data-testid="casual-duration-label">Total Duration (Days)</label>
                        <input type="number" 
                                name="total_duration" 
                                id="totalDurationInput"
                                placeholder="Total Duration"
                                data-testid="casual-duration-input"
                                readonly>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-box user-icon mb-30"> 
                        <label class="form-label-enhanced" data-testid="casual-price-label">Total Price (IDR)</label>
                        <input type="number" 
                                name="total_price" 
                                id="totalPriceInput"
                                placeholder="Total Price"
                                data-testid="casual-price-input"
                                readonly>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-3">
                    <div class="form-box user-icon mb-30"> 
                        <label class="form-label-enhanced" data-testid="casual-fullname-label">Full Name</label>
                        <input type="text" 
                               id="name"
                               name="name" 
                               placeholder="Name"
                               data-testid="casual-fullname-input"
                               maxlength="40"
                               required>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="select-itms">
                            <label class="form-label-enhanced" data-testid="casual-gender-label">Gender</label>
                            <select name="gender" data-testid="select-gender" required>
                                <option value="" selected disabled>-- Select Gender --</option>
                                <option value="MALE">Male</option>
                                <option value="FEMALE">Female</option>
                            </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="form-box email-icon mb-30">
                        <label class="form-label-enhanced" data-testid="casual-phone-label">Phone Number</label>
                        <input type="tel" 
                                name="phone"
                                id="phone" 
                                placeholder="Phone"
                                data-testid="casual-phone-input" 
                                pattern="[0-9+\-\s\(\)]*"
                                maxlength="15"
                                required>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="form-box subject-icon mb-30">
                        <label class="form-label-enhanced" data-testid="casual-email-label">Email Address</label>
                        <input type="email" 
                                name="email" 
                                id="email"
                                placeholder="Email"
                                data-testid="casual-email-input"
                                maxlength="30"
                                required>
                    </div>
                </div>
                
                <div class="col-lg-6 col-md-6">
                    <div class="submit-info">
                        <button class="btn" 
                                type="button"
                                data-flow="CASUAL"
                                data-testid="apply-casual-button"
                                id="openSummaryBtn">Apply Casual Access
                        </button>
                        
                        <!-- ✅ Bootstrap 4 Alert -->
                        <div
                            id="formWarning"
                            class="alert alert-danger mt-3 d-none"
                            role="alert"
                            data-testid="casual-error-message">
                            Please complete all required fields before continuing.
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
