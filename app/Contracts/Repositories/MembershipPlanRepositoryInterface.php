<?php 

namespace App\Contracts\Repositories;

use App\DTO\MembershipPlan\PagerViewDto;

interface MembershipPlanRepositoryInterface 
{
    public function getMembershipPlanById(int $planId): array;
    public function findByCode(string $code): array;
    public function findActivePlan(int $planId): array;
    public function findAllCasualActivePlans():array;
    public function findAllMemberActivePlans(): array;
    public function create(array $data): array;
    public function update(array $data): array;
    public function findAll(PagerViewDto $dto): array;
    public function findAllActivePlans(): array;
    public function delete(int $id): array;
}