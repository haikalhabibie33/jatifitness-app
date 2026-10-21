<?php

namespace App\Models;

use CodeIgniter\Model;

class MembershipRequestModel extends Model
{
    protected $table            = 'membership_requests';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        
        // core
        'access_token',
        'request_type',
        'target_member_type',

        // plan snapshot
        'membership_plan_id',
        'plan_code_snapshot',
        'plan_name_snapshot',
        'price_snapshot',
        'total_price_snapshot',
        'duration_snapshot',
        'daily_limit_snapshot',
        'quantity',

        // ✅ payment meta
        'payment_source',
        'payment_method',
        'payment_reference',
        'paid_at',

        // applicant
        'name',
        'gender',
        'date_of_birth',
        'phone',
        'email',
        'address',

        // status
        'status',
        'approval_required',

    ];

    /**
     * Generate secure resume token
     */
    public function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Create new request
     */
    public function createRequest(array $data): int
    {
        $data['access_token'] = $this->generateToken();
        $data['status'] = 'SUBMITTED';

        $this->insert($data);
        return $this->getInsertID();
    }

    /**
     * Resume request securely
     */
    public function findByToken(int $id, string $token): ?array
    {
        return $this->where([
            'id'           => $id,
            'access_token' => $token,
        ])->first();
    }
}