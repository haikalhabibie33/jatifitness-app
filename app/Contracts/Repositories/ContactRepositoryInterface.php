<?php

namespace App\Contracts\Repositories;

interface ContactRepositoryInterface
{
    public function findAll(): array;
    public function delete(int $id): array;
}