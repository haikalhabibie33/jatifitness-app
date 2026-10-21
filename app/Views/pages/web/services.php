<?= $this->extend('pages/web/layout/template') ?>

<?= $this->section('content') ?>

<!-- ISI KONTEN ABOUT MULAI DARI slider-area2 sampai sebelum </main> -->
 <main>
        <!--? Hero Start -->
        <div class="slider-area2">
            <div class="slider-height2 d-flex align-items-center">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="hero-cap hero-cap2 text-center pt-70">
                                <h2 data-testid="service-title">Our Services</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Hero End -->
        <!--? Services Area Start -->
        <section class="services-area pt-100 pb-150">
            <!--? Want To work -->
            <section class="wantToWork-area w-padding">
                <div class="container">
                    <div class="row align-items-end justify-content-between">
                        <div class="col-lg-6 col-md-10 col-sm-10">
                            <div class="section-tittle">
                                <!-- service_section_title -->
                                <span data-testid="service-section-title">
                                    <?= esc($services['services_section_title'] ?? '') ?>
                                </span> 
                                <!-- service_hero_caption -->
                                <h2 data-testid="service-hero-caption">
                                    <?= esc($services['services_hero_caption'] ?? '') ?>
                                </h2>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-3">
                            <a 
                              href=<?= base_url('contact') ?> 
                              data-testid="more-services-btn"
                              class="btn wantToWork-btn f-right">
                              More Services
                            </a>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Want To work End -->
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="single-cat single-cat2 text-center mb-50">
                            <div class="cat-icon">
                                <i class="flaticon-fitness"></i>
                            </div>
                            <div class="cat-cap">
                                <h5>
                                    <a data-testid="quality-equipment-title" href="#">QUALITY EQUIPMENT</a>
                                </h5>
                                <p data-testid="quality-equipment-description">
                                    View clear information about gym facilities and available equipment to ensure they match your training needs.
                                </p>
                            </div>
                            <div data-testid="card-parent-quality" class="img-cap">
                                <a 
                                  href="<?= esc($contact['contact_instagram_url']) ?>" 
                                  target="_blank"
                                  data-testid="quality-equipment-url">
                                  Discover More About Us <i class="ti-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="single-cat single-cat2 text-center mb-50">
                            <div class="cat-icon">
                                <i class="flaticon-healthcare-and-medical"></i>
                            </div>
                            <div class="cat-cap">
                                <h5>
                                    <a data-testid="health-caring-title" href="#">HEALTH CARING</a>
                                </h5>
                                <p data-testid="health-caring-description">
                                    Find gyms that support a healthy lifestyle with transparent membership details and direct communication with gym owners.
                                </p>
                            </div>
                            <div data-testid="card-parent-health" class="img-cap">
                                <a 
                                  href="<?= esc($contact['contact_instagram_url']) ?>" 
                                  target="_blank"
                                  data-testid="health-caring-url">
                                  Discover More About Us <i class="ti-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6" data-testid="card-parent-gym">
                        <div class="single-cat single-cat2 text-center mb-50">
                            <div class="cat-icon">
                                <i class="flaticon-clock"></i>
                            </div>
                            <div class="cat-cap">
                                <h5>
                                    <a data-testid="gym-strategies-title" href="#">GYM STRATEGIES</a>
                                </h5>
                                <p data-testid="gym-strategies-description">
                                    Compare gym memberships easily and message gym owners directly to find the best option for you.
                                </p>
                            </div>
                            <div data-testid="card-parent-gym" class="img-cap">
                                <a 
                                  href="<?= esc($contact['contact_instagram_url']) ?>" 
                                  target="_blank"
                                  data-testid="gym-strategies-url">Discover More About Us <i class="ti-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Services Area End -->
    </main>

<?= $this->endSection() ?>