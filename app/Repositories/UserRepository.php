<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\UserModel;
use App\Traits\RepositoryResponseTrait;

class UserRepository implements UserRepositoryInterface
{
    use RepositoryResponseTrait;
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login(string $username): array
    {
        $result = $this->userModel
            ->where('username', $username)
            ->first();
        if (!$result) {
            return $this->failedResponse($this->userModel->errors());
        }
        return $this->successResponse($result);
    }

}