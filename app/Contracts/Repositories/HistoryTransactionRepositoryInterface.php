<?php

namespace App\Contracts\Repositories;

interface HistoryTransactionRepositoryInterface
{
    public function create(array $payload): array;

    public function record(int $transactionId, string $event, ?string $note = null): array;

    public function findByTransactionId(int $transactionId): array;
}