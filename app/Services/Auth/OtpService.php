<?php

namespace App\Services\Auth;

use App\Models\UserModel;
use App\Models\OtpCodeModel;

class OtpService
{
    protected UserModel $userModel;
    protected OtpCodeModel $otpModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->otpModel  = new OtpCodeModel();
    }

    /**
     * KIRIM OTP FORGOT PASSWORD
     */
    public function sendForgotPasswordOtp(string $email): void
    {
        // 1️⃣ CARI USER DULU (WAJIB)
        $user = $this->userModel->where('email', $email)->first();

        // SECURITY: jangan bocorkan email ada atau tidak
        if (! $user) {
            log_message('info', 'Forgot password OTP: email not found');
            return;
        }

        // 2️⃣ RATE LIMIT CHECK
        if (! $this->canSendOtp($user['id'])) {
            log_message('info', 'Forgot password OTP blocked by rate limit for user ' . $user['id']);
            return;
        }

        // 3️⃣ INVALIDATE OTP LAMA
        $this->otpModel->invalidateActiveOtps($user['id'], 'forgot_password');

        // 4️⃣ GENERATE OTP
        $otp = random_int(100000, 999999);

        // 5️⃣ SIMPAN OTP
        $this->otpModel->insert([
            'user_id'    => $user['id'],
            'identifier' => $email,
            'otp_hash'   => password_hash((string) $otp, PASSWORD_DEFAULT),
            'purpose'    => 'forgot_password',
            'attempts'   => 0,
            'is_used'    => 0,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // 6️⃣ KIRIM EMAIL (INI YANG TADI LU KEJAR)
        $this->sendEmail($email, $otp);
    }

    /**
     * RESEND OTP
     */
    public function resendForgotPasswordOtp(string $email): void
    {
        $user = $this->userModel->where('email', $email)->first();
        if (! $user) {
            return;
        }

        if (! $this->canSendOtp($user['id'])) {
            log_message('info', 'Resend OTP blocked by rate limit for user ' . $user['id']);
            return;
        }

        $this->otpModel->invalidateActiveOtps($user['id'], 'forgot_password');

        $otp = random_int(100000, 999999);

        $this->otpModel->insert([
            'user_id'    => $user['id'],
            'identifier' => $email,
            'otp_hash'   => password_hash((string) $otp, PASSWORD_DEFAULT),
            'purpose'    => 'forgot_password',
            'attempts'   => 0,
            'is_used'    => 0,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->sendEmail($email, $otp);
    }

    /**
     * KIRIM EMAIL OTP
     */
    protected function sendEmail(string $email, int $otp): void
    {
        $emailService = service('email');

        $emailService->setTo($email);
        $emailService->setSubject('OTP Reset Password - Jati Fitness');
        $emailService->setMessage(
            "Kode OTP kamu adalah <b>{$otp}</b><br> Berlaku selama 5 menit."
        );

        // 🔥 DEBUG SAFE
        if (! $emailService->send()) {
            log_message(
                'error',
                'OTP email failed: ' . $emailService->printDebugger(['headers','subject','body'])
            );
        }
    }

    /**
     * RATE LIMIT: MAX 3 OTP / 15 MENIT
     */
    private function canSendOtp(int $userId): bool
    {
        $count = $this->otpModel
            ->where('user_id', $userId)
            ->where('purpose', 'forgot_password')
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-15 minutes')))
            ->countAllResults();

        return $count < 3;
    }

    /**
     * VERIFIKASI OTP
     */
    public function verifyUserOtp(int $userId, string $otp, string $purpose): bool
    {
        $otpData = $this->otpModel->getActiveOtp($userId, $purpose);

        if (! $otpData) {
            return false;
        }

        if ($otpData['attempts'] >= 5) {
            return false;
        }

        if (! password_verify($otp, $otpData['otp_hash'])) {
            $this->otpModel->incrementAttempts($otpData['id']);
            return false;
        }

        $this->otpModel->markAsUsed($otpData['id']);
        return true;
    }

    /**
     * CLEANUP OTP USER
     */
    public function deleteUserOtps(int $userId, ?string $purpose = null): void
    {
        $builder = $this->otpModel->where('user_id', $userId);

        if ($purpose !== null) {
            $builder->where('purpose', $purpose);
        }

        $builder->delete();
    }

    /**
     * CRON CLEANUP EXPIRED OTP
     */
    public function cleanupExpiredOtps(): int
    {
        return $this->otpModel
            ->where('expires_at <', date('Y-m-d H:i:s'))
            ->delete();
    }
}