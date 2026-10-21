<?php

namespace App\Repositories;

use App\Contracts\Repositories\MembershipRepositoryInterface;
use App\Models\MemberModel;
use App\Models\MembershipModel;

use App\Traits\RepositoryResponseTrait;
use CodeIgniter\Database\Exceptions\DatabaseException;

class MembershipRepository implements MembershipRepositoryInterface 
{

    use RepositoryResponseTrait;
    private MembershipModel $membershipModel;
    private MemberModel $memberModel;

    public function __construct()
    {
        $this->membershipModel = new MembershipModel();
        $this->memberModel = new MemberModel();
    }

    public function findAll(): array
    {
        try {
            $result = $this->membershipModel->findAll();
            if(!$result) {
                return $this->failedResponse($this->membershipModel->errors());
            }

            return $this->successResponse($result);

        } catch(DatabaseException $e){
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }

    public function findById(int $id): array
    {
        try {
            $result = $this->membershipModel->find($id);
            if (!$result) {
                return $this->failedResponse($this->membershipModel->errors());
            }
            return $this->successResponse($result);

        } catch(DatabaseException $e){
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);

        }
    }

    public function isMembershipActive(array $userInput): array
    {
        try {
            $today = date('Y-m-d');
            $result = $this->membershipModel
                            ->where('member_id', $userInput['id'])
                            ->where('end_date >=', $today)
                            ->where('status', 'ACTIVE')
                            ->orderBy('end_date', 'DESC')
                            ->first();
            if (!$result) {
                return $this->failedResponse($this->membershipModel->errors());
            }
            return $this->successResponse($result);

        } catch(DatabaseException $e){
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);

        }
    }

    public function findMembershipRequestId(int $membershipRequestId): array
    {
        try {
            $result = $this->membershipModel->where('membership_request_id',$membershipRequestId)->first();
            if (!$result) {
                return $this->failedResponse($this->membershipModel->errors());
            }
            return $this->successResponse($result);

        } catch(DatabaseException $e){
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);

        }
    }

    public function create(array $data, int $memberId): array
    {
        try {
            // get data from membership request, validate member data, and create membership
            $today = date('Y-m-d');
            $now = date('Y-m-d H:i:s');
            
            // membership repository 1 --> create membership
            $latestActiveMembership = $this->membershipModel
                ->where('member_id', $memberId)
                ->where('status', 'ACTIVE')
                ->where('end_date >=', $today)
                ->orderBy('end_date', 'DESC')
                ->first();
            
            // membership repository 2
            $startDate = $latestActiveMembership ? date('Y-m-d', strtotime($latestActiveMembership['end_date'] . ' +' . 1 . ' days')): $today;
            $endDate = date('Y-m-d', strtotime($startDate . ' +' . (int) $data['duration_days_snapshot'] . ' days'));

            //  membership repository 3
            $membership = $this->membershipModel
                ->where('membership_request_id', $data['requestId'])
                ->first();

            // membership repository 4
            $membershipId = $membership
                ? $membership['id']
                : $this->membershipModel->insert([
                    'membership_request_id' => $data['requestId'],
                    'member_id'             => $memberId,
                    'membership_plan_id'    => $data['membership_plan_id'],
                    'plan_name_snapshot'    => $data['plan_name_snapshot'],
                    'price_snapshot'        => $data['price_snapshot'],
                    'daily_limit'           => $data['daily_limit_member'],
                    'status'                => 'ACTIVE',
                    'start_date'            => $startDate,
                    'end_date'              => $endDate,
                    'created_at'            => $now,
                    'updated_at'            => $now,
                ], true);
            if (!$membershipId) {
                return $this->failedResponse($this->membershipModel->errors());
            }
            return $this->successResponse($membershipId);

        } catch(DatabaseException $e){
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }

    public function findMembershipsByMemberId(int $memberId): array
    {
        try {
            $result = $this->membershipModel
                ->where('member_id',$memberId)
                ->orderBy('end_date', 'DESC')
                ->findAll();
            if (!$result) {
                return $this->failedResponse($this->membershipModel->errors());
            }

            return $this->successResponse($result);

        } catch(DatabaseException $e){
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);

        }
    }

    public function findMembershipsByMemberIds(array $memberId): array
    {
        try {
            $result = $this->membershipModel
                ->asArray()
                ->whereIn('member_id',$memberId)
                ->orderBy('end_date', 'DESC')
                ->findAll();
            // if (!$result) {
            //     return $this->failedResponse($this->membershipModel->errors());
            // }

            return $this->successResponse($result);

        } catch(DatabaseException $e){
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);

        }
    }

    public function findAllMembershipOfMember(): array
    {
        try {
            $members = $this->memberModel
                ->orderBy('id', 'ASC')
                ->findAll();

            if (!$members) {
                return $this->failedResponse($this->memberModel->errors());
            }

            foreach ($members as &$member) {
                $membership = $this->membershipModel
                    ->where('member_id', $member['id'])
                    ->orderBy('end_date', 'DESC')
                    ->findAll();

                $member['membership'] = $membership ?: [];
            }
            unset($member);

            return $this->successResponse($members);

        } catch(DatabaseException $e){
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }
}