<?php 

namespace App\Services\Testing;

use App\Models\MembershipPlanModel;

class MembershipPlansTestService 
{
    private MembershipPlanModel $membershipPlanModel;

    public function __construct()
    {
        $this->membershipPlanModel = new MembershipPlanModel();
    }

    public function getAllMembershipPlans(): array
    {
        $plans = $this->membershipPlanModel->findAll();

        return $plans ?: [];
    }

    public function getAllStudentMembershipPlans(): array
    {
        $plans = $this->membershipPlanModel->
                whereIn('target_member_type', ['STUDENT', 'ALL'])
                ->findAll();

        return $plans ?: [];
    }

    public function getAllRegularMembershipPlans(): array
    {
        $plans = $this->membershipPlanModel->
                 whereIn('target_member_type',['REGULAR', 'ALL'])->
                 findAll();

        return $plans ?: [];
    }
}