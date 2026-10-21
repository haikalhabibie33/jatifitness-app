<?php

namespace App\Contracts\Repositories;

interface MemberRepositoryInterface 
{
    public function findAll(): array;
    public function findById(int $id): array;
    public function checkPhoneAndEmailExists(string $phone, string $email): array;
    public function checkPhoneAndEmailExistsDifferentId(int $id, string $phone, string $email): array;
    public function create(array $userInput): array;
    public function update(array $userInput): array;
    public function deactivate(int $memberid): array;
}