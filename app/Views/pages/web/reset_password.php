<?php /** @var \CodeIgniter\View\View $this */ ?>

<?= $this->extend('pages/web/layout/login_template') ?>
<?= $this->section('content') ?>

<main class="main-login d-flex align-items-center justify-content-center">
    <div class="card shadow-sm px-5 py-5 rounded-4" style="width: 380px">

        <form action="<?= site_url('forgot-password/reset') ?>" method="post">
            <?= csrf_field() ?>

            <div class="single-defination text-center" style="user-select:none">
                <h1 style="font-size: 4.5rem;">RESET PASSWORD</h1>
            </div>

            <div class="single-defination">
                <p>
                    Create a new password for your account.
                </p>
            </div>

            <!-- New Password -->
            <div class="mt-10">
                <input
                    type="password"
                    name="password"
                    class="single-input"
                    placeholder="New Password"
                    required
                    minlength="8"
                >
            </div>

            <!-- Confirm Password -->
            <div class="mt-10">
                <input
                    type="password"
                    name="password_confirm"
                    class="single-input"
                    placeholder="Confirm New Password"
                    required
                    minlength="8"
                >
            </div>

            <div class="button-group-area mt-20">
                <button
                    type="submit"
                    class="genric-btn danger w-100 btn-login-lg"
                    style="font-size: 1.5rem;"
                >
                    Reset Password
                </button>
            </div>

            <div class="single-defination mt-3 text-center">
                <p>Password must be at least 8 characters.</p>
            </div>
        </form>

    </div>
</main>

<?= $this->endSection() ?>