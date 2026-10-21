<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Traits\GenerateAccessToken;

class CasualPassModel extends Model
{
    protected $table         = 'casual_passes';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    use GenerateAccessToken;

    protected $allowedFields = [
        'membership_request_id',
        'access_token',
        'activated_at',
        'expires_at',
        'status',
        'daily_limit',
        'usage_limit',
        'revoked_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Generate secure access code
     */
    public function generateAccessCode(): string
    {
        return $this->generateAccessToken();
    }

    /**
     * Create casual pass from completed request
     */
    public function createFromRequest(int $requestId, int $dailyLimit, int $usageLimit): int
    {
        $this->insert([
            'membership_request_id' => $requestId,
            'access_token'          => $this->generateAccessCode(),
            'daily_limit'           => $dailyLimit,
            'usage_limit'           => $usageLimit,
            'status'                => 'UNUSED',
        ]);

        return $this->getInsertID();
    }

    /**
     * Activate pass on first scan
     */
    public function activate(int $passId, int $durationDays): bool
    {
        $pass = $this->find($passId);

        if (!$pass || $pass['status'] !== 'UNUSED') {
            return false;
        }

        $now = date('Y-m-d H:i:s');

        return $this->update($passId, [
            'activated_at' => $now,
            'expires_at'   => date(
                'Y-m-d H:i:s',
                strtotime("+{$durationDays} days")
            ),
            'status'       => 'ACTIVE',
        ]);
    }

    /**
     * Validate access code
     */
    public function isValid(string $accessToken): bool
    {
        return $this->where([
                'access_token' => $accessToken,
                'status'      => 'ACTIVE',
            ])
            ->where('expires_at >=', date('Y-m-d H:i:s'))
            ->countAllResults() > 0;
    }
}