<?php

namespace App\Models;

use CodeIgniter\Model;

final class OtpCodeModel extends Model
{
    protected $table = 'otp_codes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $returnType = 'array';
    protected $allowedFields = [
        'user_id',
        'identifier',
        'otp_hash',
        'purpose',
        'attempts',
        'is_used',
        'expires_at',
        'created_at'
    ];

    protected $useTimestamps = false;

    // Ambil OTP aktif untuk user tertentu
    public function getActiveOtp(int $userId, string $purpose)
    {
        return $this->where('user_id', $userId)
        ->where('purpose', $purpose)
        ->where('is_used', 0)
        ->where('expires_at >=', date('Y-m-d H:i:s'))
        ->orderBy('id', 'DESC')
        ->first();
    }

    // Tandai OTP yang sudah dipakai
    public function markAsUsed(int $otpId): bool
    {
        return $this->update($otpId, [
            'is_used' => 1,
            ]);
    }

    // Increment percobaan OTP
    public function incrementAttempts(int $otpId): bool
    {
        return $this->set('attempts', 'attempts + 1', false)
        ->where('id', $otpId)
        ->update();
    }

    // Invalidate semua OTP aktif untuk user
    public function invalidateActiveOtps(int $userId, string $purpose): bool
    {
        return $this->where('user_id', $userId)
        ->where('purpose', $purpose)
        ->where('is_used',0)
        ->set(['is_used' => 1])
        ->update();
    }
    
}


