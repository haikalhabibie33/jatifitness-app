
<?php
/** @var \CodeIgniter\View\View $this */
?>

<?= $this->extend('pages/web/layout/login_template') ?>
<?= $this->section('content') ?>
<main class="main-login d-flex align-items-center justify-content-center">
    <div class="card shadow-sm px-5 py-5 rounded-4" style="width: 380px">
        
        <form action="<?= base_url('login') ?>" method="post">
            <?= csrf_field() ?>
            <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger mb-3">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
            <?php endif; ?>
            <div class="single-defination" style="user-select: none !important;">
                <h1 style="font-size: 6rem;">Login</h1>
            </div>
            <div class="single-defination pt-20" style="user-select: none !important;">
                <h3 class="ml-10" style="font-size: 2.5rem;">Username</h3>
            </div>
            <div class="mt-10">
                <input type="text" name="username" 
                       placeholder="Username"
                       onfocus="this.placeholder = ''" 
                       onblur="this.placeholder = 'Username'" 
                       data-testid = "username"
                       required
                    class="single-input" autocomplete="new-password" style="font-size: 1.5rem;">
            </div>
            <div class="single-defination mt-10" style="user-select: none !important;">
                <h3 class="ml-10" style="font-size: 2.5rem;">Password</h3>
            </div>
            <div class="mt-10">
                <input type="password" 
                       name="password" 
                       placeholder="Password"
                       onfocus="this.placeholder = ''" 
                       onblur="this.placeholder = 'Password'"
                       data-testid="password" 
                       required
                    class="single-input" autocomplete="current-password" style="font-size: 1.5rem;">
            </div>
            <div class="button-group-area mt-10">
                <button 
                   type="submit" 
                   data-testid="login-btn"
                   class="genric-btn danger w-100 btn-login-lg" style="font-size: 1.5rem;">Login</button>
            </div>
            <div class="d-flex col justify-content-between align-items-center p-0 mt-10">
                <div class="d-flex justify-content-between align-items-center" style="gap: 12px;">
                    <label for="default-checkbox" class="m-0 cursor-pointer" style="font-size: 1.5rem;">Remember me</label>
                    <div class="primary-checkbox">
                        <input type="checkbox" 
                               id="default-checkbox" 
                               name="remember" 
                               value="1"
                               data-testid="remember-me">
                        <label for="default-checkbox"></label>
                    </div>
                </div>
                <a href=<?= base_url('/forgot-password')  ?> 
                   class="forgot-link"
                   data-testid="forgot-password">Forgot Password?</a>
            </div>
        </form>
    </div>
</main>
<?= $this->endSection('content') ?>
