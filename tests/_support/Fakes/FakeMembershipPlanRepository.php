<?php

namespace Tests\Support\Fakes;

use App\Contracts\Repositories\MembershipPlanRepositoryInterface;

class FakeMembershipPlanRepository implements MembershipPlanRepositoryInterface 
{
    public function getMembershipPlanById(int $planId): array
    {
        return [];
    }
    
    public function getActiveCasualPackage(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Regular Daily Visit',
                'request_type' => 'CASUAL',
            ],
        ];

    } 
    public function getActiveMemberPackage(): array
    {
        return [
            [
                'id' => 10,
                'name' => 'Regular Monthly',
                'request_type' => 'MEMBERSHIP',
            ],
        ];

    }
}