<?php

namespace Tests\Support\Fakes;

use App\Contracts\Repositories\MembershipRequestRepositoryInterface;

class FakeMembershipRequestRepository implements MembershipRequestRepositoryInterface
{
    public function insertToMembershipRequest(array $planInputData) : void
    {

    }
    public function getMembershipRequestByPhone(string $phone) : array
    {
        return [
            [
                'id' => 1,
                'phone' => $phone,
                'name' => 'Test User',
                'status' => 'SUBMITTED',
            ],
        ];

    }
    public function getMembershipRequestByToken(string $token): array
    {
        return [
            [
                'id' => 2,
                'access_token' => $token,
                'name' => 'Token User',
                'status' => 'APPROVED',
            ],
        ];
    }

}