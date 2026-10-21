<?php

namespace App\Controllers\Testing;

use App\Controllers\BaseController;
use App\Services\Testing\MembershipRequestsTestService;

class MembershipRequestTestController extends BaseController
{
    private MembershipRequestsTestService $testService;

    public function __construct()
    {
        $this->testService = new MembershipRequestsTestService();
    }

    public function getAllMembershipRequests()
    {
        $requests = $this->testService->getAllMembershipRequests();

        return $this->response->setJSON([
            'message' => 'Successfully get all membership requests',
            'requests' => $requests
        ]);
    }

    public function getAllStudentMembershipRequests()
    {
        $requests = $this->testService->getAllStudentMembershipRequests();

        return $this->response->setJSON([
            'message' => 'Successfully get all student membership requests',
            'requests' => $requests
        ]);
    }

    public function getAllRegularMembershipRequests()
    {
        $requests = $this->testService->getAllRegularMembershipRequests();

        return $this->response->setJSON([
            'message' => 'Succesfully get all regular membership requests',
            'requests' => $requests
        ]);
    }

    public function getMembershipRequestsByPhoneEmail()
    {
        $json = $this->request->getJSON(true);
        $data = [
            'phone' => $json['phone'] ?? '',
            'email' => $json['email'] ?? ''
        ];

        $requests = $this->testService->getMembershipRequestsByPhoneEmail($data);

        return $this->response->setJSON([
            'message' => 'Successfully get membership requests by phone and email',
            'requests' => $requests
        ]);
    }
}