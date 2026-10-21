<?php

namespace App\Repositories;

use App\Contracts\Repositories\CasualPassRepositoryInterface;
use App\Models\CasualPassModel;
use App\Models\MembershipRequestModel;
use App\Traits\RepositoryResponseTrait;
use CodeIgniter\Database\Exceptions\DatabaseException;

class CasualPassRepository implements CasualPassRepositoryInterface
{

    use RepositoryResponseTrait;
    private CasualPassModel $casualPassModel;
    private MembershipRequestModel $membershipRequestModel;

    public function __construct()
    {
        $this->casualPassModel = new CasualPassModel();
        $this->membershipRequestModel = new MembershipRequestModel();
    }

    public function create(array $data): array
    {
        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        $db->transBegin();

        try {
            $casualPassInMembershipRequest = $this->membershipRequestModel->find($data['requestId']);
            if (!$casualPassInMembershipRequest) {
                throw new \RuntimeException('Membership request id is not found!');
            }

            $isCasualPassAlreadyExist = $this->casualPassModel
                ->where('membership_request_id', $data['requestId'])
                ->first();
            if ($isCasualPassAlreadyExist) {
                throw new \RuntimeException('Casual pass is already exist!');
            }

            $result = $this->casualPassModel->insert([
                'membership_request_id' => $data['requestId'],
                'access_token'           => $data['access_token'],
                'daily_limit'           => $data['daily_limit_casual'],
                'usage_limit'           => $data['usage_limit'],
                'status'                => 'UNUSED',
                'created_at'            => $now,
                'updated_at'            => $now,
            ]);
            if (!$result) {
                throw new \RuntimeException('Failed to insert casual pass');
            }

            $completeMembershipRequest = $this->membershipRequestModel->update($data['requestId'],[
                'status' => 'COMPLETED',
            ]);

            if (!$completeMembershipRequest) {
                throw new \RuntimeException('Failed to mark COMPLETE on Membership Request!');
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed!');
            }


            $db->transCommit();

            return [
                'ok' => true,
            ];

        } catch (\Throwable $th) {
            $db->transRollback();
            return [
                'ok' => false,
                'error' => $th->getMessage(),
            ];
        }
    }

    public function getCasualPassByToken(string $token): array
    {
        try {

            $casual = $this->casualPassModel
            ->select('casual_passes.*,
                        membership_requests.phone,
                        membership_requests.name,
                        membership_requests.email,
                        membership_requests.plan_name_snapshot as plan_name,
                        membership_requests.target_member_type as target_type,
                        membership_requests.quantity as qty')
            ->join('membership_requests','membership_requests.id = casual_passes.membership_request_id', 'LEFT')
            ->where('casual_passes.access_token', $token)
            ->first();

        if (!$casual) {
            return $this->failedResponse('Failed to find casual pass by using token: ' . $token,[]);
        }

        return $this->successResponse($casual);

        } catch(DatabaseException $e){
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }
    
}