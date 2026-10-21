<?php

namespace App\Repositories;

use App\Contracts\Repositories\MemberAccessRepositoryInterface;
use App\Models\MemberAccessModel;
use App\Traits\RepositoryResponseTrait;
use CodeIgniter\Database\Exceptions\DatabaseException;

class MemberAccessRepository implements MemberAccessRepositoryInterface
{
    use RepositoryResponseTrait;
    private MemberAccessModel $memberAccessModel;

    public function __construct()
    {
        $this->memberAccessModel = new MemberAccessModel();
    }

    public function create(int $membershipId, string $accessToken): array
    {
        $result = $this->memberAccessModel->insert([
            'membership_id' => $membershipId,
            'access_token' => $accessToken,
            'status' => 'ACTIVE',
        ],true);

        if (!$result) [
            'ok' => false,
            'error' => $this->memberAccessModel->errors(),
        ];
        return [
            'ok' => true,
            'data' => $result, 
        ];
    }

    public function findMemberAccessByMembershipId(int $membershipId): array
    {
        $result = $this->memberAccessModel
            ->where('membership_id', $membershipId)
            ->first();
        if(!$result) {
            return [
                'ok' => false,
                'error' => $this->memberAccessModel->errors(),
            ];
        }
        return [
            'ok' => true,
            'data' => $result,
        ];
    }

    public function getMemberAccessByToken(string $token): array
    {
        try {
            $member = $this->memberAccessModel
                ->select('member_access.*,
                          members.name,
                          members.phone,
                          members.email,
                          members.gender,
                          members.member_type as member_type,
                          memberships.status as membership_status,
                          memberships.plan_name_snapshot as plan_name,
                          memberships.daily_limit,
                          memberships.start_date,
                          memberships.end_date')
                ->join('memberships', 'memberships.id = member_access.membership_id', 'LEFT')
                ->join('members', 'members.id = memberships.member_id', 'LEFT')
                ->where('member_access.access_token', $token)
                ->where('memberships.status', 'ACTIVE')
                ->where("CURDATE() between memberships.start_date and memberships.end_date")
                ->first();
                if ($member) {
                    return $this->successResponse($member);
                }
            return $this->failedResponse('cannot find member access by using provided token :' . $token,[]);
        } catch(DatabaseException $e){
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);

        }
    }
    
}