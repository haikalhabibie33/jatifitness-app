<?php

namespace App\Controllers\Api\Auth;

use App\Controllers\BaseController;
use App\DTO\LoginDto;
use App\Services\Auth\UserService;
use App\Traits\GenerateAccessToken;

class LoginControllerApi extends BaseController
{
    use GenerateAccessToken;
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function authenticate()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->response
                ->setJSON([
                'error' => 'username or password required!'
            ])
            ->setStatusCode(400);
        }

        $dto = LoginDto::fromJson($this->request->getJSON(true));
        $result = $this->userService->login($dto);
        if (!$result['ok']) {
            return $this->response->setJSON([
                'message' => 'Invalid username or password'
            ]);
        }

        $user = $result['data'];

        session()->set([
            'admin_id' => $user['id'],
            'admin_name' => $user['name'],
            'admin_username' => $user['username'],
            'admin_role' => 'ADMIN',
            'admin_logged_in' => true
        ]);

        if ($dto->remember) {
            return $this->response
                ->setJSON([
                    'message' => 'You have been successfully login'
                ])
                ->setStatusCode(200)
                ->setCookie([
                    'name' => 'remember_tokens',
                    'value' => $result['token'],
                    'expire' => 60 * 60 * 24 * 30,
                    'path' => '/',
                    'secure' => false,
                    'httponly' => true,
                    'samesite' => 'Lax',
                ]);
        }

        return $this->response
            ->setJSON([
                'message' => 'You have been successfully login'
            ])
            ->setStatusCode(200);
    }
}