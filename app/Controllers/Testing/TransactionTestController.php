<?php

namespace App\Controllers\Testing;

use App\Controllers\BaseController;
use App\Services\Testing\TransactionTestService;

class TransactionTestController extends BaseController
{
    private TransactionTestService $testService;

    public function __construct()
    {
        $this->testService = new TransactionTestService();
    }

    public function getAllTransactions()
    {
        $transactions = $this->testService->getAllTransactions();

        return $this->response->setJSON([
            'message' => 'Successfully get all transactions',
            'transactions' => $transactions
        ]);
    }

    public function getTransactionsByPhoneEmail()
    {
        $json = $this->request->getJSON(true);
        $data = [
            'phone' => $json['phone'],
            'email' => $json['email']
        ];

        $transactions = $this->testService->getTransactionsByPhoneEmail($data);

        return $this->response->setJSON([
            'message' => 'Successfully get transactions by phone and email',
            'phone' => $data['phone'],
            'email' => $data['email'],
            'transactions' => $transactions
        ]);
    }
}