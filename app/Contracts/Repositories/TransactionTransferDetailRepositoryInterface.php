<?php

namespace App\Contracts\Repositories;

interface TransactionTransferDetailRepositoryInterface
{
    public function create(array $payload): array;

    public function findByTransactionId(int $transactionId): array;

    public function markUploaded(int $transactionId, array $payload): array;

    public function markVerified(int $transactionId, int $adminId, ?string $note = null): array;

    public function markRejected(int $transactionId, int $adminId, ?string $note = null): array;
}