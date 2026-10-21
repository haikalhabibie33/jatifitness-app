<?php

namespace App\Repositories;

use App\Contracts\Repositories\MembershipRequestRepositoryInterface;
use App\Models\MembershipRequestModel;

class MembershipRequestRepository implements MembershipRequestRepositoryInterface
{
    private MembershipRequestModel $membershipRequestModel;

    public function __construct()
    {
        $this->membershipRequestModel = new MembershipRequestModel();
    }

    public function create(array $insertCasualData): array
    {
        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        try {
            $db->transBegin();

          $result = $this->membershipRequestModel->insert([
            'access_token'        => $insertCasualData['access_token'],
            'request_type'        => $insertCasualData['request_type'],
            'target_member_type'  => $insertCasualData['target_member_type'],
            'membership_plan_id'  => $insertCasualData['membership_plan_id'],
            'plan_code_snapshot'  => $insertCasualData['plan_code_snapshot'],
            'plan_name_snapshot'  => $insertCasualData['plan_name_snapshot'],
            'price_snapshot'      => $insertCasualData['price_snapshot'],
            'duration_snapshot'   => $insertCasualData['duration_snapshot'],
            'daily_limit_snapshot'=> $insertCasualData['daily_limit_snapshot'],
            'quantity'            => $insertCasualData['quantity'],
            'total_price_snapshot'=> $insertCasualData['total_price_snapshot'],
            'approval_required'   => $insertCasualData['approval_required'],
            'name'                => $insertCasualData['name'],
            'phone'               => $insertCasualData['phone'],
            'email'               => $insertCasualData['email'],
            'status'              => $insertCasualData['status'],
            'gender'              => $insertCasualData['gender'] ?? null,
            'date_of_birth'       => $insertCasualData['date_of_birth'] ?? null,
            'address'             => $insertCasualData['address'] ?? null,
            'payment_source'      => $insertCasualData['payment_source'],
            'payment_method'      => $insertCasualData['payment_method'],
            'created_at'          => $now,
            'updated_at'          => $now,
            ]);
        if(!$result) {
            return [
                'ok' => false,
                'error' => $this->membershipRequestModel->errors(),
            ];
        }

        if ($db->transStatus() == false) {
            throw new \RuntimeException('Db transaction failed');
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

    public function findMembershipRequestByIdAndAccessToken(int $id, string $accessToken): array
    {
        $result = $this->membershipRequestModel
            ->where('id', $id)
            ->where('access_token', $accessToken)
            ->groupStart()
                ->where('status', 'SUBMITTED')
                ->orWhere('status', 'APPROVED')
            ->groupEnd()
            ->first();
        if (!$result) {
            return [
                'ok' => false,
                'error' => $this->membershipRequestModel->errors(),
            ];
        }

        return [
            'ok' => true,
            'data' => $result,
        ];
    }

    public function markComplete(int $membershipRequestId): array
    {
        $now = date('Y-m-d H:i:s');
        $result = $this->membershipRequestModel
            ->update($membershipRequestId, [
                'status' => 'COMPLETED',
                'updated_at' => $now,
            ]);
        if (!$result) {
            return [
                'ok' => false,
                'error' => $this->membershipRequestModel->errors(),
            ];
        }
        return [
            'ok' => true,
            'error' => null,
        ];
    }

}