<?php

namespace App\Contracts\Repositories;

use App\DTO\Transaction\ViewDTO;

interface TransactionRepositoryInterface
{
    public function completeTransaction(array $data): array;
    public function viewReport(ViewDTO $dto): array;
    public function markWaitingConfirmation(int $transactionId): array;
    public function markSuccess(int $transactionId): array;
    public function markFailed(int $transactionId): array;
}