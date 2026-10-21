<?php /** @var \CodeIgniter\View\View $this */ ?>

<?= $this->extend('pages/web/layout/login_template') ?>
<?= $this->section('content') ?>

<main class="main-login d-flex align-items-center justify-content-center">
    <div class="card shadow-sm px-5 py-5 rounded-4" style="width: 380px">

        <form action="<?= site_url('forgot-password/verify') ?>" method="post" id="otpForm">
            <?= csrf_field() ?>

            <div class="single-defination text-center" style="user-select:none">
                <h1 style="font-size: 5rem;">OTP VERIFICATION</h1>
            </div>

            <div class="single-defination">
                <p>
                    Enter the 6-digit OTP sent to your email.
                </p>
            </div>

            <div class="mt-10 d-flex justify-content-between gap-2">
                <?php for ($i = 0; $i < 6; $i++): ?>
                    <input
                        type="text"
                        class="otp-box text-center"
                        name="otp[]"
                        maxlength="1"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        autocomplete="one-time-code"
                        required
                        style="width: 45px; height: 50px; font-size: 2rem;"
                    >
                <?php endfor; ?>
            </div>

            <!-- OTP gabungan -->
            <input type="hidden" name="otp_value" id="otp_value">

            <div class="button-group-area mt-20">
                <button type="submit"
                    class="genric-btn danger w-100 btn-login-lg"
                    style="font-size: 1.5rem;">
                    Verify OTP
                </button>
            </div>

            <div class="single-defination mt-3 text-center">
                <p>Your OTP will expire in 5 minutes.</p>
            </div>
        </form>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('.otp-box');
    const hiddenInput = document.getElementById('otp_value');

    inputs[0].focus();

    inputs.forEach((input, index) => {

        input.addEventListener('input', () => {
            input.value = input.value.replace(/[^0-9]/g, '');

            if (input.value && inputs[index + 1]) {
                inputs[index + 1].focus();
            }

            updateOtpValue();
        });

        input.addEventListener('keydown', e => {
            if (e.key === 'Backspace' && !input.value && inputs[index - 1]) {
                inputs[index - 1].focus();
            }
        });

        // Paste OTP support
        input.addEventListener('paste', e => {
            e.preventDefault();
            const paste = e.clipboardData.getData('text').replace(/\D/g, '');
            paste.split('').forEach((num, i) => {
                if (inputs[i]) inputs[i].value = num;
            });
            updateOtpValue();
        });
    });

    function updateOtpValue() {
        hiddenInput.value = Array.from(inputs).map(i => i.value).join('');
    }

    document.getElementById('otpForm').addEventListener('submit', e => {
        updateOtpValue();
        if (hiddenInput.value.length !== 6) {
            alert('Please enter complete OTP');
            e.preventDefault();
        }
    });
});
</script>

<?= $this->endSection() ?>