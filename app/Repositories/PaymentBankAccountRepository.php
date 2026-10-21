<?php

namespace App\Repositories;

use App\Contracts\Repositories\PaymentBankAccountRepositoryInterface;
use App\Models\PaymentBankAccountModel;
use App\Traits\RepositoryResponseTrait;
use CodeIgniter\Database\Exceptions\DatabaseException;

class PaymentBankAccountRepository implements PaymentBankAccountRepositoryInterface
{
    use RepositoryResponseTrait;

    private PaymentBankAccountModel $paymentBankAccountModel;

    public function __construct()
    {
        $this->paymentBankAccountModel = new PaymentBankAccountModel();
    }

    public function findAllActive(): array
    {
        try {
            $result = $this->paymentBankAccountModel
                ->asArray()
                ->where('is_active', 1)
                ->orderBy('bank_name', 'ASC')
                ->findAll();

            return $this->successResponse($result);

        } catch (DatabaseException $e) {
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }

    public function findById(int $id): array
    {
        try {
            $result = $this->paymentBankAccountModel
                ->asArray()
                ->find($id);

            if (!$result) {
                return $this->failedResponse('Payment bank account not found.');
            }

            return $this->successResponse($result);

        } catch (DatabaseException $e) {
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }
}