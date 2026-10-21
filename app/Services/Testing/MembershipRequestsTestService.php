<?php 

namespace App\Services\Testing;

use App\Models\MembershipRequestModel;

class MembershipRequestsTestService
{
    private MembershipRequestModel $model;

    public function __construct()
    {
        $this->model = new MembershipRequestModel();
    }

    public function getAllMembershipRequests(): array
    {
        $membershipRequests = $this->model->findAll();

        return $membershipRequests ?: [];
    }

    public function getAllStudentMembershipRequests(): array
    {
        $membershipRequests = $this->model->
                              where('target_member_type', 'STUDENT')->
                              findAll();
        return $membershipRequests ?: [];
    }

    public function getAllRegularMembershipRequests(): array
    {
        $membershipRequests = $this->model->  
                              where('target_member_type', 'REGULAR')->
                              findAll();
        return $membershipRequests ?: [];
    }

    public function getMembershipRequestsByPhoneEmail(array $data):array
    {
        $requests = $this->model->
                    where('phone', $data['phone'])->
                    where('email', $data['email'])->
                    findAll();
        return $requests ?: [];
    }

}