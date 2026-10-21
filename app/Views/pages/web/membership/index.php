<?= $this->extend('pages/web/layout/template') ?>

<?= $this->section('content') ?>

 <main>
        <!--? Hero Start -->
        <div class="slider-area2">
            <div class="slider-height2 d-flex align-items-center">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="hero-cap hero-cap2 text-center pt-70">
                                <h2 data-testid="member-page-title">New Members</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Hero End -->
        <!--? Contact form Start -->
        <div class="contact-form-main mt-100 mb-100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-12 col-lg-12">
                        <div class="form-wrapper">
                            <!--Section Tittle  -->
                            <div class="form-tittle">
                                <div class="row ">
                                    <div class="col-lg-11 col-md-10 col-sm-10">
                                        <div class="section-tittle">
                                            <span data-testid="section-title">Application Form</span>
                                            <h2 data-testid="section-description">Feel Free to join us!</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="membership-tabs">
                                <ul class="nav nav-pills nav-fill mt-5">
                                    <li class="nav-item">
                                        <a class="nav-link active" 
                                            onclick="showForm('member', this)"
                                            data-testid="form-member-tab">
                                            Member
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" 
                                            aria-current="page" 
                                            onclick="showForm('casual', this)"
                                            data-testid="form-casual-tab">
                                            Casual
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!--End Section Tittle  -->
                             <!-- form member -->
                            <div id="memberForm">
                                <?= view('pages/web/components/form-member') ?>
                            </div>
                            <!-- form casual -->
                            <div id="casualForm" style="display: none;">
                                <?= view('pages/web/components/form-casual') ?>
                            </div>
                            <?= view('pages/web/modals/summaryModal') ?>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Contact form End -->
    </main>
    
<?= $this->endSection() ?>