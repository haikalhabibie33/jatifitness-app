<?php

namespace App\Controllers\Api\Admin;

use App\Controllers\BaseController;
use App\Services\MembershipPlanService;
use App\DTO\MembershipPlan\CreatePlanDto;
use App\DTO\MembershipPlan\UpdatePlanDto;

use App\Traits\RequestValidationTrait;

class AdminMembershipPlanController extends BaseController {

    use RequestValidationTrait;
    private MembershipPlanService $membershipPlanService;

    public function __construct()
    {
        $this->membershipPlanService = new MembershipPlanService();
    }

    public const RULES = [
        'code' => 'required|max_length[50]',
        'name' => 'required|max_length[100]',
        'target_member_type' => 'required|in_list[REGULAR,STUDENT,ALL]',
        'audience_scope' => 'required|in_list[MEMBER_ONLY,CASUAL_ONLY,BOTH]',
        'price' => 'required|decimal',
        'duration_days' => 'required|integer|greater_than_equal_to[1]',
        'daily_limit' => 'required|integer|greater_than_equal_to[1]',
        'description' => 'permit_empty|max_length[255]',
        'is_active' => 'permit_empty|in_list[0,1]',
    ];

    public function create()
    {
        $body = $this->request->getJSON(true) ?? [];

        if (!$this->validateBody(self::RULES, $body)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'message' => 'bad request',
                    // 'error' => $validation->getErrors(),
                    'error' => 'invalid body request',
                ]);
        }

        $dto = CreatePlanDto::fromPost($body);
        $result = $this->membershipPlanService->create($dto);
        if (!$result['success']) {
            return $this->response
                ->setJSON([
                    'message' => 'failed to create membership plan!',
                    'error' => $result['error'],
                ])
                ->setStatusCode(400);
        }
        return $this->response
            ->setJSON([
                'message' => 'membership plan created successfully!'
            ])
            ->setStatusCode(201);
    }

    public function update(int $planId)
    {
        if (!$this->isValidId($planId)){
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    "message" => "invalid membership plan id"
                ]);
        }

        $body = $this->request->getJSON(true);

        $validation = \Config\Services::validation();
        $validation->setRules(self::RULES);

        if (!$validation->run($body)){
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    "message" => "bad request",
                    "error" => $validation->getErrors(),
                    // "error" => "invalid body request"
                ]);
        }   
        $dto = UpdatePlanDto::fromPost($body);
        $result = $this->membershipPlanService->update($planId, $dto);

        if (!$result['success']) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    "message" => "failed to update membership plan!",
                    "error" => $result['error'],
                ]);
        }
        return $this->response
            ->setStatusCode(201)
            ->setJSON([
                "message" => "successfully updated membership plan"
            ]);
    }

    public function delete(int $id)
    {
        $result = $this->membershipPlanService->delete($id);
        if (!$result['ok']) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    "message" => "Failed to delete the membership plan",
                    "error" => $result['error'],
                ]);
        }

        return $this->response
            ->setStatusCode(201)
            ->setJSON([
                "message" => "Successfully delete the membership plan",
            ]);
    }

    public function findAll()
    {
        $result = $this->membershipPlanService->findAllAdmin();
        if (!$result['ok']) {
            return $this->response
                ->setJSON([
                    'error' => $result['error'],
                ]);
        }

        return $this->response->setJSON([
            'message' => 'ok',
            'data' => $result['data'],
        ]);
    }

    public function findActiveByID(int $planId)
    {
        $result = $this->membershipPlanService->findActivePlanByIDAdmin($planId);
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

        $result = $this->membershipPlanService->findByIdAdmin($planId);
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
        $result = $this->membershipPlanService->findByCodeAdmin($code);
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