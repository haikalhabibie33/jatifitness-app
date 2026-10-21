<?php

namespace App\Controllers\Api\Admin;

use App\Controllers\BaseController;
use App\Services\MemberService;
use App\Traits\RequestValidationTrait;

class AdminMemberController extends BaseController
{
    private MemberService $memberService;
    use RequestValidationTrait;

    public function __construct()
    {
        $this->memberService = new MemberService();
    }

    public function findAll()
    {
        $result = $this->memberService->findAllAdmin();
        if (!$result['ok']) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'message' => 'failed',
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

    public function findByID(int $id)
    {
        $result = $this->memberService->findByIDAdmin($id);
        if (!$result['ok']){
            return $this->response
            ->setStatusCode(400)
                ->setJSON([
                    'message' => 'failed',
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