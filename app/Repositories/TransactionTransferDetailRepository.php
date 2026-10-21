<?php

namespace App\Repositories;

use App\Contracts\Repositories\TransactionTransferDetailRepositoryInterface;
use App\Models\TransactionTransferDetailModel;
use App\Traits\RepositoryResponseTrait;
use CodeIgniter\Database\Exceptions\DatabaseException;

class TransactionTransferDetailRepository implements TransactionTransferDetailRepositoryInterface
{
    use RepositoryResponseTrait;

    private TransactionTransferDetailModel $transactionTransferDetailModel;

    public function __construct()
    {
        $this->transactionTransferDetailModel = new TransactionTransferDetailModel();
    }

    public function create(array $payload): array
    {
        try {
            $id = $this->transactionTransferDetailModel->insert($payload, true);

            if (!$id) {
                return $this->failedResponse(
                    $this->transactionTransferDetailModel->errors()
                        ?: 'Failed to create transfer detail.'
                );
            }

            $created = $this->transactionTransferDetailModel
                ->asArray()
                ->find($id);

            return $this->successResponse($created);

        } catch (DatabaseException $e) {
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }

    public function findByTransactionId(int $transactionId): array
    {
        try {
            $result = $this->transactionTransferDetailModel
                ->asArray()
                ->where('transaction_id', $transactionId)
                ->first();

            if (!$result) {
                return $this->failedResponse('Transfer detail not found.');
            }

            return $this->successResponse($result);

        } catch (DatabaseException $e) {
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }

    public function markUploaded(int $transactionId, array $payload): array
    {
        try {
            $existing = $this->transactionTransferDetailModel
                ->asArray()
                ->where('transaction_id', $transactionId)
                ->first();

            if (!$existing) {
                return $this->failedResponse('Transfer detail not found.');
            }

            $updated = $this->transactionTransferDetailModel->update($existing['id'], [
                'sender_bank'      => $payload['sender_bank'] ?? null,
                'sender_name'      => $payload['sender_name'] ?? null,
                'proof_image_path' => $payload['proof_image_path'] ?? null,
                'uploaded_at'      => date('Y-m-d H:i:s'),
            ]);

            if (!$updated) {
                return $this->failedResponse(
                    $this->transactionTransferDetailModel->errors()
                        ?: 'Failed to upload transfer proof.'
                );
            }

            $result = $this->transactionTransferDetailModel
                ->asArray()
                ->find($existing['id']);

            return $this->successResponse($result);

        } catch (DatabaseException $e) {
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }

    public function markVerified(int $transactionId, int $adminId, ?string $note = null): array
    {
        try {
            $existing = $this->transactionTransferDetailModel
                ->asArray()
                ->where('transaction_id', $transactionId)
                ->first();

            if (!$existing) {
                return $this->failedResponse('Transfer detail not found.');
            }

            $updated = $this->transactionTransferDetailModel->update($existing['id'], [
                'verified_by'       => $adminId,
                'verified_at'       => date('Y-m-d H:i:s'),
                'verification_note' => $note,
            ]);

            if (!$updated) {
                return $this->failedResponse(
                    $this->transactionTransferDetailModel->errors()
                        ?: 'Failed to verify transfer detail.'
                );
            }

            $result = $this->transactionTransferDetailModel
                ->asArray()
                ->find($existing['id']);

            return $this->successResponse($result);

        } catch (DatabaseException $e) {
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }

    public function markRejected(int $transactionId, int $adminId, ?string $note = null): array
    {
        try {
            $existing = $this->transactionTransferDetailModel
                ->asArray()
                ->where('transaction_id', $transactionId)
                ->first();

            if (!$existing) {
                return $this->failedResponse('Transfer detail not found.');
            }

            $updated = $this->transactionTransferDetailModel->update($existing['id'], [
                'verified_by'       => $adminId,
                'verified_at'       => date('Y-m-d H:i:s'),
                'verification_note' => $note,
            ]);

            if (!$updated) {
                return $this->failedResponse(
                    $this->transactionTransferDetailModel->errors()
                        ?: 'Failed to reject transfer detail.'
                );
            }

            $result = $this->transactionTransferDetailModel
                ->asArray()
                ->find($existing['id']);

            return $this->successResponse($result);

        } catch (DatabaseException $e) {
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }
}