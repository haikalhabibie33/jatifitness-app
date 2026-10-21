<?php

namespace App\Contracts\Repositories;

interface MembershipRequestRepositoryInterface 
{
    public function create(array $insertCasualData): array;
    public function findMembershipRequestByIdAndAccessToken(int $id, string $accessToken): array;
    public function markComplete(int $membershipRequestId): array;
    // public function addCasualData(array $insertCasualData): array;
    // public function addMemberData(array $insertMemberData): array;
}