<?php

namespace App\Controllers\Api\Public;

use App\Controllers\BaseController;
use App\Services\MembershipPlanService;
use App\Traits\RequestValidationTrait;

class PublicMembershipPlanController extends BaseController
{
    use RequestValidationTrait;
    private MembershipPlanService $membershipPlanService;

    public function __construct()
    {
        $this->membershipPlanService = new MembershipPlanService();
    }

    public function findAll()
    {
        $result = $this->membershipPlanService->findAllPublic();
        if (!$result['ok']) {
            return $this->response
            ->setJSON([
                'error' => $result['error'],
            ])
            ->setStatusCode(500);
        }
        return $this->response
            ->setJSON([
                'message' => 'success',
                'data' => $result['data'],
            ])
            ->setStatusCode(200);
    }

    public function findActiveByID(int $planId)
    {
        if (!$this->isValidID($planId)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'message' => 'invalid membership plan id',
                ]);
        }

        $result = $this->membershipPlanService->findActivePlanByIDPublic($planId);
        if (!$result['ok']) {
            return $this->response
                ->setJSON([
                    'error' => $result['error'],
                ])
                ->setStatusCode(500);
        }

        return $this->response
            ->setStatusCode(200)
            ->setJSON([
                'message' => 'success',
                'data' => $result['data'],
            ]);
    }

    public function findByID(int $planId)
    {
        if (!$this->isValidID($planId)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'message' => 'invalid membership plan id',
                ]);
        }

        $result = $this->membershipPlanService->findByIdPublic($planId);
        if (!$result['ok']) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'message' => 'failed to find membership plan',
                    'error' => $result['error'],
                ]);
        }

        return $this->response
            ->setStatusCode(200)
            ->setJSON([
                'message' => 'success',
                'data' => $result['data'],
            ]);
    }

    public function findByCode(string $code)
    {
        $result = $this->membershipPlanService->findByCodePublic($code);
        if (!$result['ok']) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'message' => 'failed to find membership plan',
                    'error' => $result['error'],
                ]);
        }

        return $this->response
            ->setStatusCode(200)
            ->setJSON([
                'message' => 'success',
                'data' => $result['data'],
            ]);
    }
}