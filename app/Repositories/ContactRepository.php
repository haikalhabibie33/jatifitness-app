<?php

namespace App\Repositories;

use App\Contracts\Repositories\ContactRepositoryInterface;
use App\Models\ContactModel;
use App\Traits\RepositoryResponseTrait;

class ContactRepository implements ContactRepositoryInterface
{
    private ContactModel $contactModel;
    use RepositoryResponseTrait;

    public function __construct()
    {
        $this->contactModel = new ContactModel();
    }

    public function findAll(): array
    {
        $result = $this->contactModel->findAll();
        if (!$result) {
            return $this->failedResponse($this->contactModel->errors());
        }

        return $this->successResponse($result);
    }

    public function delete(int $id): array
    {
        $result = $this->contactModel->delete($id);
        if (!$result) {
            return $this->failedResponse($this->contactModel->errors());
        }
        return $this->successResponse();
    }
}