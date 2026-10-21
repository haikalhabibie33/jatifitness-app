<?php 

namespace App\Services\Testing;

use App\Models\MemberModel;
use App\Models\MembershipModel;

class MembershipTestService 
{
    private MemberModel $memberModel;
    private MembershipModel $membershipModel;

    public function __construct()
    {
        $this->memberModel = new MemberModel();
        $this->membershipModel = new MembershipModel();;
    }

    private const RULES = [
        'name' => 'required|min_length[3]|max_length[100]',
        'phone' => 'required|min_length[10]|max_length[15]|numeric',
        'email' => 'required|valid_email|max_length[100]'
    ];

    public function getRules()
    {
        return self::RULES;
    }

    public function getAllMembers(): array
    {
        $members = $this->memberModel->findAll();
        return $members ?: [];
    }

    public function getMemberById(int $id): array
    {
        $member = $this->memberModel->find($id);
        return $member ?: [];
    }

    public function getAllMemberships(): array
    {
        $memberships = $this->membershipModel->findAll();
        return $memberships ?: [];
    }

    private function getMemberDetails(int $memberId)
    {
        $memberDetails = $this->memberModel->find($memberId);
        return $memberDetails;
    }

    public function getMembershipsByMemberId(int $memberId): array
    {
        $memberships = $this->membershipModel->where('member_id', $memberId)->findAll();

        $memberDetails = $this->getMemberDetails($memberId);

        return [
            'name' => $memberDetails['name'] ?? '',
            'type' => $memberDetails['member_type'] ?? '',
            'memberships' => $memberships ?: []  
        ]; 
    }

    public function getActiveMembershipsByMemberId(int $memberId): array
    {
        $memberships = $this->membershipModel->
                       where('status', 'ACTIVE')->
                       where('member_id', $memberId)->
                       orderBy('start_date', 'ASC')->
                       findAll();

        $memberDetails = $this->getMemberDetails($memberId);

        return [
            'name' => $memberDetails['name'] ?? '',
            'type' => $memberDetails['member_type'] ?? '',
            'memberships' => $memberships ?: []
        ];
    }

    public function getExpiredMembershipsByMemberId(int $memberId): array
    {
        $memberships = $this->membershipModel->
                       where('status', 'EXPIRED')->
                       where('member_id', $memberId)->
                       orderBy('start_date', 'ASC')->
                       findAll();

        $memberDetails = $this->getMemberDetails($memberId);

        return [
            'name' => $memberDetails['name'] ?? '',
            'type' => $memberDetails['member_type'] ?? '',
            'memberships' => $memberships
        ];
    }

    public function getMemberByPhoneAndEmail(array $data)
    {
        $member = $this->memberModel->
                  where('phone', $data['phone'])->
                  where('email',$data['email'])->
                  first();
        return $member ?: [];
    }

    public function createMemberWithoutMembership(array $data): array
    {
        $isAvailable = $this->memberModel->
                       groupStart()->
                        where('phone', $data['phone'])->
                        orWhere('email', $data['email'])->
                       groupEnd()->
                       first();
        
        if ($isAvailable) {
            return [
                'success' => false,
                'message' => 'Member is exist with inputted phone or email'
            ];
        }

        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        $db->transBegin();

        try {
            $insertedMember = $this->memberModel->insert([
                'name'          => $data['name'],
                'gender'        => $data['gender'],
                'date_of_birth' => $data['date_of_birth'],
                'phone'         => $data['phone'],
                'email'         => $data['email'],
                'address'       => $data['address'],
                'member_type'   => $data['member_type'],
                'is_active'     => 1,
                'created_at'    => $now,
                'updated_at'    => $now
            ]);

            if (!$insertedMember) {
                throw new \RuntimeException('Failed to insert member data!');
            }
            
            if ($db->transStatus() === false) {
                throw new \RuntimeException('Failed to create member without membership');
            }
            $db->transCommit();

            return [
                'success' => true,
                'message' => 'Member is created successfully!'
            ];
        } catch (\Throwable $e) {
            $db->transRollback();
            return [
                'success' => false,
                'message' => 'Failed to create member without membership'
            ];
        }
                       
    }

}