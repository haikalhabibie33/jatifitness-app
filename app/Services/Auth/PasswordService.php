<?php

namespace App\Services\Auth;

use App\Models\UserModel;

class PasswordService
{
    protected UserModel $userModel;
    protected OtpService $otpService;

    public function __construct()
    {
        $this->userModel  = new UserModel();
        $this->otpService = new OtpService();
    }

    /**
     * Verifikasi OTP forgot password
     *
     * @param string|null $email
     * @param string|null $otp
     */
    public function verifyOtp(?string $email, ?string $otp): bool
    {
        // ✅ Guard paling penting (hindari TypeError & session null)
        if (empty($email) || empty($otp)) {
            log_message('warning', 'verifyOtp failed: email or otp empty');
            return false;
        }

        $user = $this->userModel
            ->where('email', $email)
            ->first();

        if (! $user) {
            log_message('warning', "verifyOtp failed: user not found ({$email})");
            return false;
        }

        return $this->otpService->verifyUserOtp(
            $user['id'],
            $otp,
            'forgot_password'
        );
    }

    /**
     * Reset password user setelah OTP tervalidasi
     *
     * @param string|null $email
     * @param string      $newPassword
     */
    public function resetPassword(?string $email, string $newPassword): bool
    {
        if (empty($email) || empty($newPassword)) {
            log_message('warning', 'resetPassword failed: invalid input');
            return false;
        }

        $user = $this->userModel
            ->where('email', $email)
            ->first();

        if (! $user) {
            log_message('warning', "resetPassword failed: user not found ({$email})");
            return false;
        }

        // ✅ Update password
        $this->userModel->update($user['id'], [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);

        // ✅ Cleanup OTP (SATU PUSAT)
        $this->otpService->deleteUserOtps(
            $user['id'],
            'forgot_password'
        );

        return true;
    }
}