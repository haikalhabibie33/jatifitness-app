<?php

namespace App\Models;

use CodeIgniter\Model;

class MembershipPlanModel extends Model
{
    protected $table      = 'membership_plans';
    protected $primaryKey = 'id';

    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'code',
        'name',
        'target_member_type', // REGULAR | STUDENT | ALL
        'audience_scope', // CASUAL | MEMBER | BOTH
        'price',
        'duration_days',
        'daily_limit',
        'description',
        'is_active',
    ];

    /* ============================================================
     * BASIC QUERIES
     * ============================================================
     */

    /**
     * Ambil semua plan yang aktif (global)
     */
    public function getActivePlans(): array
    {
        return $this->where('is_active', 1)
            ->orderBy('price', 'ASC')
            ->findAll();
    }

    
    public function getActivePlansByType(string $type): array
    {
        return $this->where('is_active', 1)
                    ->where('target_member_type', $type)
                    ->findAll();
    }

    /**
     * Ambil plan aktif yang SESUAI dengan tipe member
     * Dipakai untuk Add Membership Modal (filter UI)
     *
     * @param string $memberType REGULAR | STUDENT
     */
    public function getActivePlansForMember(string $memberType): array
    {
        return $this->where('is_active', 1)
            ->groupStart()
                ->where('target_member_type', 'ALL')
                ->orWhere('target_member_type', $memberType)
            ->groupEnd()
            ->orderBy('price', 'ASC')
            ->findAll();
    }

    /**
     * Ambil satu plan aktif & valid
     * Dipakai di MembershipController (backend final gatekeeper)
     */
    public function getValidPlan(int $id): ?array
    {
        return $this->where('id', $id)
            ->where('is_active', 1)
            ->first();
    }

    /* ============================================================
     * BUSINESS RULE VALIDATION
     * ============================================================
     */

    /**
     * Cek apakah plan boleh dibeli oleh tipe member tertentu
     *
     * @param array  $plan
     * @param string $memberType REGULAR | STUDENT
     */
    public function isAllowedForMember(array $plan, string $memberType): bool
    {
        return $plan['target_member_type'] === 'ALL'
            || $plan['target_member_type'] === $memberType;
    }

    /* ============================================================
     * GUARD / SAFETY HELPERS
     * ============================================================
     */

    /**
     * Cek apakah plan sudah pernah dipakai membership
     * (penting jika nanti mau lock field tertentu)
     */
    public function isUsed(int $planId): bool
    {
        return $this->db->table('memberships')
            ->where('membership_plan_id', $planId)
            ->countAllResults() > 0;
    }

    
    public function getPlansForAudience(string $audience, string $targetType): array
    {
        return $this->where('is_active', 1)
            ->where('target_member_type', $targetType)
            ->groupStart()
                ->where('audience_scope', 'BOTH')
                ->orWhere('audience_scope', $audience)
            ->groupEnd()
            ->findAll();
    }

}