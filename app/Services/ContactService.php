<?php

namespace App\Services;

use App\Contracts\Repositories\ContactRepositoryInterface;
use App\Repositories\ContactRepository;

class ContactService
{
    private ContactRepositoryInterface $contactRepository;

    public function __construct
    (
        ?ContactRepositoryInterface $contactRepository = null,
    )
    {
        $this->contactRepository = $contactRepository ?? new ContactRepository();
    }

    public function findAll():array
    {
        $result = $this->contactRepository->findAll();
        if (!$result['ok']) {
            return [
                'ok' => false,
                'error' => $result['error'],
                'data' => [],
            ];
        }

        return [
            'ok' => true,
            'data' => $result['data'],
        ];
    }

    public function delete(int $id): array
    {
        $result = $this->contactRepository->delete($id);
        if (!$result['ok']) {
            return [
                'ok' => false,
                'error' => $result['error'],
            ];
        }
        return [
            'ok' => true,
        ];
    }
}