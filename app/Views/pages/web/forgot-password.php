
<?php
/** @var \CodeIgniter\View\View $this */
?>

<?= $this->extend('pages/web/layout/login_template') ?>
<?= $this->section('content') ?>
<main class="d-flex align-items-center justify-content-center" 
style="
    min-height: 100svh;  
    min-height: 100dvh;
    min-height: 100vh;
    background:
      linear-gradient(0deg, rgba(0,0,0,.45), rgba(0,0,0,.45)),
      url('<?= base_url('assets/img/hero/h1_hero.png') ?>');    
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    user-select: none;">
    <div class="card shadow-sm px-5 py-5 rounded-4" style="width: 380px">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('message')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('message') ?>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('forgot-password/send') ?>" method="POST">
             <?= csrf_field() ?>
            <div class="single-defination" style="user-select: none !important; text-align: center;">
                <h1 style="font-size: 5rem;">Forgot Password</h1>
            </div>
             <div class="single-defination">
                <p>To reset your password, enter your email. A 6-digit OTP will be sent to verify your account.</p>
            </div>
            <div class="single-defination pt-10" style="user-select: none !important;">
                <h3 class="ml-10" style="font-size: 2.5rem;">Email</h3>
            </div>
            <div class="mt-10">
                <input type="email" name="email" placeholder="Enter your email"
                    onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter your email'" required
                    class="single-input" autocomplete="new-password" style="font-size: 1.5rem;">
            </div>
            <div class="button-group-area mt-10">
                <button type="submit" class="genric-btn danger w-100 btn-login-lg" style="font-size: 1.5rem;">Send OTP</button>
            </div>
        </form>
    </div>
</main>
<?= $this->endSection('content') ?>
