<?php

namespace App\Contracts\Repositories;

interface MembershipRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): array;
    public function isMembershipActive(array $userInput): array;
    // public function createCompleteMembership(array $data): array;
    public function findAllMembershipOfMember(): array;
    public function findMembershipRequestId(int $membershipRequestId): array;
    public function create(array $data, int $memberId): array;
    public function findMembershipsByMemberId(int $memberId): array;
    public function findMembershipsByMemberIds(array $memberId): array;
}