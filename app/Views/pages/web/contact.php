<?= $this->extend('pages/web/layout/template') ?>

<?= $this->section('content') ?>

<main>

    <!-- Hero Start -->
    <div class="slider-area2">
        <div class="slider-height2 d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="hero-cap hero-cap2 pt-70 text-center">
                            <h2 data-testid="contact-page-title">
                                Contact Us
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->

    <!-- Contact Area Start -->
    <section class="contact-section">
        <div class="container">

            <!-- ✅ GOOGLE MAPS EMBED (GRATIS, TANPA JS) -->
        <?php if (!env('CYPRESS')): ?>
        <div class="d-none d-sm-block mb-5 pb-4">
            <iframe
                data-testid="gym-map-location"
                width="100%"
                height="480"
                style="border:0;"
                loading="lazy"
                allowfullscreen
                referrerpolicy="no-referrer-when-downgrade"
                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDmTSyFlMUmZYS6uW5RZ_ZFXjPHxjq2grc&q=place_id:ChIJk1l7FQCB9i0R_8KSJO-kRbg&maptype=satellite&zoom=18">
            </iframe>
        </div>
        <?php endif; ?>


            <div class="row">
                <!-- Form -->
                <div class="col-lg-8">
                    <h2 class="contact-title" data-testid="form-title">Get in Touch</h2>

                    <form class="form-contact contact_form"
                          action="<?= base_url('contact/submit') ?>"
                          method="POST"
                          id="contactForm">

                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <input class="form-control"
                                           type="text"
                                           name="message"
                                           data-testid="input-message"
                                           placeholder="Enter Message"
                                           maxlength="1000"
                                           required>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control"
                                           name="name"
                                           type="text"
                                           data-testid="input-name"
                                           placeholder="Enter your name"
                                           maxlength="50"
                                           required>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control"
                                           name="email"
                                           type="email"
                                           data-testid="input-email"
                                           placeholder="Enter email address"
                                           maxlength="50"
                                           required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <input class="form-control"
                                           name="subject"
                                           type="text"
                                           data-testid="input-subject"
                                           placeholder="Enter Subject"
                                           maxlength="50"
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit"
                                    data-testid="send-button"
                                    class="button button-contactForm boxed-btn">
                                Send
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-3 offset-lg-1">
                    <div class="media contact-info">
                        <span class="contact-info__icon"><i class="ti-home"></i></span>
                        <div class="media-body">
                            <h3 data-testid="contact-city">
                                <?= esc($contact['contact_city']) ?>
                            </h3>
                            <p data-testid="contact-street">
                                <?= esc($contact['contact_street']) ?>
                            </p>
                        </div>
                    </div>

                    <div class="media contact-info">
                        <span class="contact-info__icon"><i class="ti-tablet"></i></span>
                        <div class="media-body">
                            <h3 data-testid="contact-phone">
                                <?= esc($contact['contact_phone']) ?>
                            </h3>
                            <p data-testid="available-phone">
                                <?= esc($contact['contact_availableToPhone']) ?>
                            </p>
                        </div>
                    </div>

                    <div class="media contact-info">
                        <span class="contact-info__icon"><i class="ti-email"></i></span>
                        <div class="media-body">
                            <h3 data-testid="contact-email">
                                <?= esc($contact['contact_email']) ?>
                            </h3>
                            <p data-testid="contact-email-message">
                                <?= esc($contact['contact_emailMessage']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- Contact Area End -->

</main>

<?= $this->endSection() ?>