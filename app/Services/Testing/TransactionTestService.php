<?php 

namespace App\Services\Testing;

use App\Models\HistoryTransactionModel;
use App\Models\TransactionModel;

class TransactionTestService 
{
    private TransactionModel $transactionModel;
    private HistoryTransactionModel $historyTransactionModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->historyTransactionModel = new HistoryTransactionModel();
    }

    public function getAllTransactions(): array
    {
        $transactions = $this->transactionModel->findAll();
        return $transactions ?: [];
    }

    public function getTransactionsByPhoneEmail(array $data): array
    {
        $transactions = $this->transactionModel->
                        select('transactions.*')->
                        join('membership_requests', 'membership_requests.id = transactions.membership_request_id')->
                        where('membership_requests.email', $data['email'])->
                        where('membership_requests.phone', $data['phone'])->
                        where('transactions.payment_status', 'SUCCESS')->findAll();
        
        return $transactions ?: [];
    }
}