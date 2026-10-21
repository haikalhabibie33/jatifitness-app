<?php

namespace App\Contracts\Repositories;

interface MemberAccessRepositoryInterface
{
    public function create(int $membershipId, string $accessToken): array;
    public function findMemberAccessByMembershipId(int $membershipId): array;
    public function getMemberAccessByToken(string $token): array;
}