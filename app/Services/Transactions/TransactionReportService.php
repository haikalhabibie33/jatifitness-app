<?php

namespace App\Services\Transactions;

use App\Contracts\Repositories\TransactionRepositoryInterface;
use App\Repositories\TransactionRepository;
use App\DTO\Transaction\ViewDTO;

class TransactionReportService
{
    private TransactionRepositoryInterface $transactionRepository;

    public function __construct(
        ?TransactionRepositoryInterface $transactionRepository = null,
    )
    {
        $this->transactionRepository = $transactionRepository ?? new TransactionRepository();
    }

    public function view(ViewDTO $dto)
    {
        $result = $this->transactionRepository->viewReport($dto);
        return $result;
    }
}