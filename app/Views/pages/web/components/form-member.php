<!-- form member -->
<form class="js-form-member" id="contact-form" action="<?= base_url('membership/submit-member') ?>" method="POST">
    <?= csrf_field() ?>

    <input type="hidden" 
           name="payment_method" 
           id="memberPaymentMethodInput"
           value="CASH"
           >

    <div class="row">
        <!-- Membership plan section -->
        <div class="col-12">
            <div class="row border border-danger rounded p-5">
                <div class="col-lg-3 col-md-3">
                    <label class="form-label-enhanced" data-testid="personal-label">Personal Category</label>
                    <div class="select-itms">
                            <select name="target_type" id="targetType" data-testid="select-personal">
                                <option value="" selected disabled > -- Select your Category --</option>
                                <option value="REGULAR">Regular</option>
                                <option value="STUDENT">Student</option>
                            </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="select-itms">
                            <label class="form-label-enhanced" data-testid="membership-package-label">Membership Package</label>
                            <select name="membership_plan" id="membershipPlan" data-testid="select-package">
                            <option value="" selected disabled>-- Choose Membership --</option>
                            <!-- <option value="Public">Regular</option>
                            <option value="Student">Student</option> -->
                            <?php foreach ($memberPlans as $plan) : ?>
                                <option value="<?= esc($plan['id']) ?>"
                                        data-type="<?= esc($plan['target_member_type']) ?>"
                                        data-audience="<?= esc($plan['audience_scope']) ?>"
                                        data-duration="<?= esc($plan['duration_days']) ?>"
                                        data-price="<?= esc($plan['price']) ?>">
                                        <?= esc($plan['name']) ?>
                                </option>
                            <?php endforeach; ?>
                            </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <label class="form-label-enhanced" data-testid="duration-label">Duration (days)</label>
                    <div class="duration-wrapper">
                        <input class="duration-input" 
                                type="number" 
                                name="duration"
                                id="durationInput" 
                                value="" 
                                data-testid="duration-input"
                                required readonly>
                        <span class="duration-suffix">days</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <label class="form-label-enhanced" data-testid="price-label">Price (IDR)</label>
                    <div class="price-wrapper">
                        <input class="price-input" 
                                type="number" 
                                name="price"
                                id="priceInput" 
                                value="" 
                                data-testid="price-input"
                                required readonly>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of membership plan section -->
        <div class="col-12">
            <div class="row border border-danger rounded p-5">
                <div class="col-lg-6 col-md-6">
                    <div class="form-box user-icon mb-30"> 
                        <label class="form-label-enhanced" data-testid="fullname-label">Full Name</label>
                        <input type="text" 
                                name="name" 
                                placeholder="Name"
                                maxlength="40"
                                data-testid="fullname-input"
                                required>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="select-itms">
                            <label class="form-label-enhanced" data-testid="gender-label">Gender</label>
                            <select name="gender" data-testid="select-gender">
                                <option value="" selected disabled>-- Select Gender --</option>
                                <option value="MALE">Male</option>
                                <option value="FEMALE">Female</option>
                            </select>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="form-box user-icon mb-30">
                        <label class="form-label-enhanced" data-testid="dob-label">Date of Birth</label>
                        <input type="date" 
                                name="date_of_birth" 
                                placeholder="Date of birth"
                                maxlength="40"
                                data-testid="dob-input"
                                required>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="form-box email-icon mb-30">
                        <label class="form-label-enhanced" data-testid="phone-label">Phone Number</label>
                        <input type="tel" 
                                name="phone" 
                                placeholder="Phone" 
                                pattern="[0-9+\-\s\(\)]*"
                                maxlength="15"
                                data-testid="phone-input"
                                required>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="form-box subject-icon mb-30">
                        <label class="form-label-enhanced" data-testid="email-label">Email Address</label>
                        <input type="email" 
                                name="email" 
                                placeholder="Email"
                                maxlength="30"
                                data-testid="email-input"
                                required>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="form-box user-icon mb-50">
                        <label class="form-label-enhanced" data-testid="street-label">Street Address</label>
                        <input type="text" 
                                name="address" 
                                id="address" 
                                placeholder="Address"
                                maxlength="100"
                                data-testid="street-input"
                                required></input>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="submit-info">
                        <button class="btn" 
                                type="button"
                                data-testid="apply-button"
                                data-flow="MEMBERSHIP">
                                Apply Membership
                        </button>

                        <!-- ✅ Bootstrap Alert: MEMBER -->
                        <div
                        id="memberFormWarning"
                        class="alert alert-danger mt-3 d-none"
                        role="alert"
                        data-testid="member-error-message">
                        Please complete all required fields before continuing.
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
