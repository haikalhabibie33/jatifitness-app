<?php

namespace App\Repositories;

use App\Contracts\Repositories\HistoryTransactionRepositoryInterface;
use App\Models\HistoryTransactionModel;
use App\Traits\RepositoryResponseTrait;
use CodeIgniter\Database\Exceptions\DatabaseException;

class HistoryTransactionRepository implements HistoryTransactionRepositoryInterface
{
    use RepositoryResponseTrait;

    private HistoryTransactionModel $historyTransactionModel;

    public function __construct()
    {
        $this->historyTransactionModel = new HistoryTransactionModel();
    }

    public function create(array $payload): array
    {
        try {
            $id = $this->historyTransactionModel->insert($payload, true);

            if (!$id) {
                return $this->failedResponse(
                    $this->historyTransactionModel->errors()
                        ?: 'Failed to create transaction history.'
                );
            }

            $created = $this->historyTransactionModel
                ->asArray()
                ->find($id);

            return $this->successResponse($created);

        } catch (DatabaseException $e) {
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }

    public function record(int $transactionId, string $event, ?string $note = null): array
    {
        return $this->create([
            'transaction_id' => $transactionId,
            'event'          => $event,
            'note'           => $note,
        ]);
    }

    public function findByTransactionId(int $transactionId): array
    {
        try {
            $result = $this->historyTransactionModel
                ->asArray()
                ->where('transaction_id', $transactionId)
                ->orderBy('created_at', 'ASC')
                ->findAll();

            return $this->successResponse($result);

        } catch (DatabaseException $e) {
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }
}