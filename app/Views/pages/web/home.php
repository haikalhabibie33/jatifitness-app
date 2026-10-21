<?= $this->extend('pages/web/layout/template') ?>

<?= $this->section('content') ?>

<!-- ISI KONTEN ABOUT MULAI DARI slider-area2 sampai sebelum </main> -->
 <main>
        <!--? slider Area Start-->
        <div class="slider-area position-relative">
            <div class="slider-active">
                <!-- Single Slider -->
                <div class="single-slider slider-height d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-7 col-lg-9 col-md-8 col-sm-9">
                                <div class="hero__caption">
                                    <!-- home_owner_name -->
                                    <span data-animation="fadeInLeft" data-delay="0.1s">with muhammad haikal ramadhan</span>
                                    <!-- home_hero_caption -->
                                    <h1 data-animation="fadeInLeft" data-delay="0.4s">WUJUDKAN TUBUH IDEALMU DI JATI FITNESS SAMARINDA</h1>
                                    <a href=<?= base_url('membership/index') ?> class="btn hero-btn" data-animation="fadeInLeft" data-delay="0.8s">became a member</a>
                                </div>
                            </div>
                        </div>
                    </div>          
                </div>
                <!-- Single Slider -->
                <div class="single-slider slider-height d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-7 col-lg-9 col-md-8 col-sm-9">
                                <div class="hero__caption">
                                    <!-- home_owner_name -->
                                    <span data-testid="home-owner-name"
                                          data-animation="fadeInLeft" 
                                          data-delay="0.1s">
                                          with <?= esc($home['home_owner_name'] ?? '') ?>
                                    </span>
                                    <!-- home_hero_caption -->
                                    <h1 data-animation="fadeInLeft" 
                                        data-delay="0.4s"
                                        data-testid="home-hero-caption">
                                        <?= esc($home['home_hero_caption'] ?? '') ?>
                                   </h1>
                                    <a href=<?= base_url('membership/index') ?> 
                                       class="btn hero-btn" data-animation="fadeInLeft" 
                                       data-delay="0.8s"
                                       data-testid="home-membership-btn">
                                       daftar member sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>          
                </div>
            </div>
            <!-- Video icon -->
            <div class="video-icon">
                <!-- home_youtube_url -->
                <a class="popup-video btn-icon" 
                   href="<?= esc($home['home_youtube_url'] ?? '') ?>"
                   data-testid="home-youtube-url">
                   <i class="fas fa-play"></i>
                </a>
            </div>
        </div>
        <!-- slider Area End-->

        <!--? About-2 Area Start -->
        <section class="about-area2 testimonial-area section-padding30 fix">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-5 col-md-11 col-sm-11">
                        <!-- about-img -->
                        <div class="about-img2">
                            <img src="<?= base_url('assets/img/gallery/about2.png') ?>" 
                                 alt=""
                                 data-testid="quote-about-image">
                            <!-- Shape -->
                            <div class="shape-qutaion d-none d-sm-block">
                                <img src="<?= base_url('assets/img/gallery/qutaion.png') ?>" 
                                     alt=""
                                     data-testid="quote-quotation-image">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-9">
                        <div class="about-caption">
                            <!-- Section Tittle -->
                            <div class="section-tittle mb-55">
                                <span data-testid="quote-section-title">
                                    <?= esc($quoteHeader['quote_section_title'] ?? '') ?>
                                </span>
                                <h2 data-testid="quote-section-description">
                                    <?= esc($quoteHeader['quote_section_description'] ?? '') ?>
                                </h2>
                            </div>
                            <!-- Testimonial Start -->
                            <div class="h1-testimonial-active">
                                <!-- Single Testimonial -->
                                 <?php foreach ($quoteList as $quote) : ?>
                                     <div class="single-testimonial">
                                        <div class="testimonial-caption">
                                            <!-- text -->
                                            <p data-testid="quote-text">
                                                <?= esc($quote['text'] ?? '') ?>
                                            </p>
                                            <div class="rattiong-caption">
                                                <!-- author and role -->
                                                <span data-testid="quote-author">
                                                    <?= esc($quote['author'] ?? '') ?>
                                                    <span data-testid="quote-role"><?= esc($quote['role'] ?? '') ?> </span> 
                                               </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

<?= $this->endSection() ?>