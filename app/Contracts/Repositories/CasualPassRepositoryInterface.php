<?php

namespace App\Contracts\Repositories;

interface CasualPassRepositoryInterface
{
    public function create(array $data): array;
    public function getCasualPassByToken(string $token): array;
}