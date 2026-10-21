<?php

namespace App\Repositories;

use App\Contracts\Repositories\RememberTokenRepositoryInterface;
use App\Models\RememberTokenModel;

use App\Traits\RepositoryResponseTrait;

class RememberTokenRepository implements RememberTokenRepositoryInterface
{
    private RememberTokenModel $rememberTokenModel;
    use RepositoryResponseTrait;

    public function __construct()
    {
        $this->rememberTokenModel = new RememberTokenModel();
    }

    public function create(array $data): array
    {
        $result = $this->rememberTokenModel->insert([
            'user_id'       => $data['user_id'],
            'token_hash'    => $data['token_hash'],
            'expires_at'    => $data['expires_at'],
            'created_at'    => $data['created_at']
        ]);
        if (!$result) {
            return $this->failedResponse($this->rememberTokenModel->errors());
        }
        return $this->successResponse($result);
    }
}