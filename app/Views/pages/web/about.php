<?= $this->extend('pages/web/layout/template') ?>

<?= $this->section('content') ?>

<main>
    <!-- HERO / BANNER UTAMA -->
    <div class="slider-area2">
        <div class="slider-height2 d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="hero-cap hero-cap2 text-center pt-70">
                            <!-- Judul Banner dengan efek Gradasi Magenta-Teal -->
                            <h2 class="text-gradient">About Us</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SEKSI TENTANG KAMI -->
    <section class="about-area section-padding30">
        <div class="container">
            <div class="row align-items-center">
                <!-- Foto Utama -->
                <div class="col-lg-6 col-md-12">
                    <div class="about-img">
                        <img src="<?= esc($about['about_hero_url'] ?? '') ?>" 
                             alt="About Jati Fitness"
                             class="img-fluid rounded shadow-lg"
                             data-testid="about-hero-image">
                    </div>
                </div>

                <!-- Teks Deskripsi -->
                <div class="col-lg-6 col-md-12">
                    <div class="about-caption">
                        <div class="section-tittle section-tittle3 mb-35">
                            <!-- Subtitle otomatis menggunakan warna Teal dari CSS -->
                            <span data-testid="section-title">tentang jati fitness</span>
                            <!-- Judul Seksi -->
                            <h2 data-testid="section-description">kebugaran itu untuk semua kalangan, asalkan mereka mau untuk bergerak</h2>
                        </div>
                        <p class="pera-top" data-testid="primary-description">
                            Jati Fitness hadir menyediakan fasilitas latihan yang lengkap, nyaman, dan bersih untuk mendukung perjalanan fitness dan kesehatan Anda.
                        </p>
                        <p class="mb-65 pera-bottom" data-testid="secondary-description">
                            Bergabunglah bersama komunitas Jati Fitness Samarinda dan capai bentuk tubuh ideal Anda dengan suasana latihan yang positif.
                        </p>
                        <!-- Tombol Pendaftaran (Otomatis memakai gradasi Magenta-Teal dari .btn) -->
                        <a href="<?= base_url('membership/index') ?>" 
                           class="btn"
                           data-testid="become-member-btn">
                            Become A Member
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?= $this->endSection() ?>