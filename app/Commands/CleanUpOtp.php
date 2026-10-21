<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Services\OtpService;

class CleanUpOtp extends BaseCommand
{
    protected $group       = 'Maintenance';
    protected $name        = 'otp:cleanup';
    protected $description = 'Cleanup expired & orphan OTPs';

    public function run(array $params)
    {
        $otpService = new OtpService();

        $expired = $otpService->cleanupExpiredOtps();
        $orphan  = $otpService->cleanupOrphanOtps();

        CLI::write("Expired OTP deleted: {$expired}");
        CLI::write("Orphan OTP deleted: {$orphan}");
    }
}
