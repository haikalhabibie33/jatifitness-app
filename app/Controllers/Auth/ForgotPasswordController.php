<?php

namespace App\Controllers\Auth;

use App\Services\Auth\OtpService;
use App\Services\Auth\PasswordService;
use App\Controllers\BaseController;

class ForgotPasswordController extends BaseController
{
    protected OtpService $otpService;
    protected PasswordService $passwordService;

    public function __construct()
    {
        $this->otpService      = new OtpService();
        $this->passwordService = new PasswordService();
    }

    // STEP 1: Form email
    public function index()
    {
        return view('pages/web/forgot-password');
    }

    // STEP 2: Kirim OTP
    public function sendOtp()
    {
        $email = $this->request->getPost('email');

        $this->otpService->sendForgotPasswordOtp($email);

        // ✅ SIMPAN KONTEXT RESET
        session()->set('reset_email', $email);

        return redirect()
            ->to('/forgot-password/verify')
            ->with('message', 'Jika email terdaftar, OTP telah dikirim.');
    }

    // STEP 3: Form OTP
    public function verifyForm()
    {
        return view('pages/web/otp-verification');
    }

    // STEP 4: Verifikasi OTP
    public function verifyOtp()
    {
        $email = session('reset_email');
        $otp   = $this->request->getPost('otp_value');

        if (! $email || ! $otp) {
            return redirect()
                ->to('/forgot-password')
                ->with('error', 'Sesi tidak valid. Silakan ulangi proses reset password.');
        }

        if (! $this->passwordService->verifyOtp($email, $otp)) {
            return redirect()
                ->back()
                ->with('error', 'OTP tidak valid atau sudah kadaluarsa.');
        }

        return redirect()->to('/forgot-password/reset');
    }

    // STEP 5: Form reset password
    public function resetForm()
    {
        if (! session('reset_email')) {
            return redirect()
                ->to('/forgot-password')
                ->with('error', 'Sesi tidak valid. Silakan ulangi proses reset password.');
        }

        return view('pages/web/reset_password');
    }

    // STEP 6: Proses reset password
    public function resetPassword()
    {
        $email           = session('reset_email');
        $password        = $this->request->getPost('password');
        $passwordConfirm = $this->request->getPost('password_confirm');

        if (! $email) {
            return redirect()
                ->to('/forgot-password')
                ->with('error', 'Sesi tidak valid.');
        }

        if ($password !== $passwordConfirm) {
            return redirect()
                ->back()
                ->with('error', 'Konfirmasi password tidak cocok.');
        }

        if (! $this->passwordService->resetPassword($email, $password)) {
            return redirect()
                ->back()
                ->with('error', 'Gagal reset password.');
        }

        // ✅ BERSIHKAN SESSION RESET
        session()->remove('reset_email');

        return redirect()
            ->to('/login')
            ->with('message', 'Password berhasil direset. Silakan login.');
    }
}