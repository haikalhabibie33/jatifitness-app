<?php 

namespace App\Controllers\Testing;

use App\Controllers\BaseController;
use App\Services\Testing\MembershipTestService;

class MembershipTestController extends BaseController {
    
    private MembershipTestService $testService;

    public function __construct()
    {
        $this->testService = new MembershipTestService();

    }

    public function getAllMembers()
    {
        $members = $this->testService->getAllMembers();

        return $this->response->setJSON([
            'message' => 'Successfully get member list',
            'member_list' => $members
        ]);
    }

    public function getMemberById(int $id)
    {
        $member = $this->testService->getMemberById($id);

        return $this->response->setJSON([
            'message' => 'Successfully get member by id',
            'member' => $member
        ]);

    }

    public function getMemberByPhoneAndEmail()
    {
        $json = $this->request->getJSON(true);

        $data = [
            'phone' => $json['phone'] ?? '',
            'email' => $json['email'] ?? ''
        ];

        $member = $this->testService->getMemberByPhoneAndEmail($data);

        return $this->response->setJSON([
            'message' => 'Successfully get member by phone and email',
            'member' => $member
        ]);

    }

    public function getAllMemberships()
    {
        $memberships = $this->testService->getAllMemberships();

        return $this->response->setJSON([
            'message' => 'Successfully get all memberships',
            'memberships' => $memberships
        ]);
    }

    public function getMembershipsByMemberId(int $memberId)
    {
        $memberships = $this->testService->getMembershipsByMemberId($memberId);

        return $this->response->setJSON([
            'message' => 'Successfully get membership(s) by member id',
            'name' => $memberships['name'],
            'type' => $memberships['type'],
            'memberships' => $memberships['memberships']
        ]);
    }

    public function getActiveMembershipsByMemberId(int $memberId)
    {
        $memberships = $this->testService->getActiveMembershipsByMemberId($memberId);

        return $this->response->setJSON([
            'message' => 'Successfully get active memberships by member id',
            'name' => $memberships['name'],
            'type' => $memberships['type'],
            'active_memberships' => $memberships['memberships']
        ]);
    }

    public function getExpiredMembershipsByMemberId(int $memberId)
    {
        $memberships = $this->testService->getExpiredMembershipsByMemberId($memberId);

        return $this->response->setJSON([
            'message' => 'Successfully get active memberships by member id',
            'name' => $memberships['name'],
            'type' => $memberships['type'],
            'expired_memberships' => $memberships['memberships']
        ]);

    }

    public function createMemberWithoutMembership()
    {
        $json = $this->request->getJSON(true);
        $data = [
            'name'          => $json['name'],
            'gender'        => $json['gender'],
            'date_of_birth' => $json['date_of_birth'],
            'phone'         => $json['phone'],
            'email'         => $json['email'],
            'address'       => $json['address'],
            'member_type'   => $json['member_type']
        ];

        $result = $this->testService->createMemberWithoutMembership($data);
        return $this->response->setJSON([
            'message' => $result['message'],
            'data' => $result['success'] ? $data : []
        ]);
    }
}