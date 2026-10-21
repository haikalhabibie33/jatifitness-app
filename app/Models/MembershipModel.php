<?php

namespace App\Models;

use CodeIgniter\Model;

class MembershipModel extends Model
{
    protected $table            = 'memberships';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'membership_request_id',
        'member_id',
        'membership_plan_id',
        'plan_name_snapshot',
        'price_snapshot',
        'daily_limit',
        'status',
        'start_date',
        'end_date',
        'updated_at'
    ];

    /**
     * Get active membership of a member
     */
    public function getActiveMembership(int $memberId): ?array
    {
        return $this->where([
                'member_id' => $memberId,
                'status'    => 'ACTIVE',
            ])
            ->orderBy('start_date', 'DESC')
            ->first();
    }

    /**
     * Check if member has ongoing membership
     */
    public function hasActiveMembership(int $memberId): bool
    {
        return $this->where([
                'member_id' => $memberId,
                'status'    => 'ACTIVE',
            ])
            ->countAllResults() > 0;
    }

    /**
     * Register new membership (stacking aware)
     */
    public function registerMembership(array $data): int
    {
        // force safe default
        $data['status'] = $data['status'] ?? 'REGISTERED';

        $this->insert($data);
        return $this->getInsertID();
    }

    /**
     * Activate membership
     */
    public function activate(int $membershipId): bool
    {
        return $this->update($membershipId, [
            'status' => 'ACTIVE',
        ]);
    }

    /**
     * Expire membership
     */
    public function expire(int $membershipId): bool
    {
        return $this->update($membershipId, [
            'status' => 'EXPIRED',
        ]);
    }
}