<?php

namespace App\Controllers\Testing;

use App\Controllers\BaseController;
use App\Services\Testing\MembershipPlansTestService;

class MembershipPlanTestController extends BaseController 
{
    private MembershipPlansTestService $testService;

    public function __construct()
    {
        $this->testService = new MembershipPlansTestService();
    }

     public function getAllMembershipPlans()
    {
        $plans = $this->testService->getAllMembershipPlans();

        return $this->response->setJSON([
            'message' => 'Successfully get all membership plans',
            'plans' => $plans
        ]);
    }

    public function getAllStudentMembershipPlans()
    {
        $plans = $this->testService->getAllStudentMembershipPlans();

        return $this->response->setJSON([
            'message' => 'Successfully get all student membership plans',
            'plans' => $plans
        ]);
    }

    public function getAllRegularMembershipPlans()
    {
        $plans = $this->testService->getAllStudentMembershipPlans();

        return $this->response->setJSON([
            'message' => 'Successfully get all regular membership plans',
            'plans' => $plans
        ]);
    }
}