<?php

namespace App\Contracts\Repositories;

interface AccessScheduleRepositoryInterface
{
    public function findAll(
        array $filters,
        int $limit = 10,
        int $page = 1
        ): array;
    public function findById(int $id): array;
    public function create(array $payload): array;
    public function update(int $id, array $payload): array;
    public function changeStatus(int $id, bool $isActive): array;
    public function getSummary(): array;
    public function findCurrentSchedule(
        string $day,
        string $time,
        string $accessType)
        : array;
}