<?php

namespace App\Contracts\Repositories;

interface PaymentBankAccountRepositoryInterface
{
    public function findAllActive(): array;

    public function findById(int $id): array;
}