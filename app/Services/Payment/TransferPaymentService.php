<?php

namespace App\Services\Payment;

use App\Contracts\Repositories\TransactionRepositoryInterface;
use App\Contracts\Repositories\TransactionTransferDetailRepositoryInterface;
use App\Contracts\Repositories\HistoryTransactionRepositoryInterface;
use App\Repositories\TransactionRepository;
use App\Repositories\TransactionTransferDetailRepository;
use App\Repositories\HistoryTransactionRepository;
use App\Traits\RepositoryResponseTrait;
use CodeIgniter\Database\Exceptions\DatabaseException;

class TransferPaymentService
{
    use RepositoryResponseTrait;

    private TransactionRepositoryInterface $transactionRepository;
    private TransactionTransferDetailRepositoryInterface $transferDetailRepository;
    private HistoryTransactionRepositoryInterface $historyTransactionRepository;

    public function __construct(
        ?TransactionRepositoryInterface $transactionRepository = null,
        ?TransactionTransferDetailRepositoryInterface $transferDetailRepository = null,
        ?HistoryTransactionRepositoryInterface $historyTransactionRepository = null,
    ) {
        $this->transactionRepository = $transactionRepository ?? new TransactionRepository();
        $this->transferDetailRepository = $transferDetailRepository ?? new TransactionTransferDetailRepository();
        $this->historyTransactionRepository = $historyTransactionRepository ?? new HistoryTransactionRepository();
    }

    public function submitProof(
        int $transactionId,
        string $senderBank,
        string $senderName,
        string $proofImagePath
    ): array {
        $db = \Config\Database::connect();

        $db->transBegin();

        try {
            $transferResult = $this->transferDetailRepository->markUploaded($transactionId, [
                'sender_bank'      => $senderBank,
                'sender_name'      => $senderName,
                'proof_image_path' => $proofImagePath,
            ]);

            if (!$transferResult['ok']) {
                throw new \RuntimeException(
                    is_array($transferResult['error'])
                        ? json_encode($transferResult['error'])
                        : $transferResult['error']
                );
            }

            $transactionResult = $this->transactionRepository
                ->markWaitingConfirmation($transactionId);

            if (!$transactionResult['ok']) {
                throw new \RuntimeException(
                    is_array($transactionResult['error'])
                        ? json_encode($transactionResult['error'])
                        : $transactionResult['error']
                );
            }

            $historyResult = $this->historyTransactionRepository->record(
                transactionId: $transactionId,
                event: 'TRANSFER_PROOF_UPLOADED',
                note: 'Transfer proof uploaded by customer.'
            );

            if (!$historyResult['ok']) {
                throw new \RuntimeException(
                    is_array($historyResult['error'])
                        ? json_encode($historyResult['error'])
                        : $historyResult['error']
                );
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed.');
            }

            $db->transCommit();

            return $this->successResponse([
                'transaction' => $transactionResult['data'],
                'transfer_detail' => $transferResult['data'],
            ]);

        } catch (DatabaseException $e) {
            $db->transRollback();

            return $this->failedResponse($e->getMessage());

        } catch (\Throwable $th) {
            $db->transRollback();

            return $this->failedResponse($th->getMessage());
        }
    }

    public function approve(
        int $transactionId,
        int $adminId,
        ?string $note = null
    ): array {
        $db = \Config\Database::connect();

        $db->transBegin();

        try {
            $transferResult = $this->transferDetailRepository
                ->markVerified($transactionId, $adminId, $note);

            if (!$transferResult['ok']) {
                throw new \RuntimeException(
                    is_array($transferResult['error'])
                        ? json_encode($transferResult['error'])
                        : $transferResult['error']
                );
            }

            $transactionResult = $this->transactionRepository
                ->markSuccess($transactionId);

            if (!$transactionResult['ok']) {
                throw new \RuntimeException(
                    is_array($transactionResult['error'])
                        ? json_encode($transactionResult['error'])
                        : $transactionResult['error']
                );
            }

            $historyResult = $this->historyTransactionRepository->record(
                transactionId: $transactionId,
                event: 'TRANSFER_PAYMENT_APPROVED',
                note: $note ?? 'Transfer payment approved by admin.'
            );

            if (!$historyResult['ok']) {
                throw new \RuntimeException(
                    is_array($historyResult['error'])
                        ? json_encode($historyResult['error'])
                        : $historyResult['error']
                );
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed.');
            }

            $db->transCommit();

            return $this->successResponse([
                'transaction' => $transactionResult['data'],
                'transfer_detail' => $transferResult['data'],
            ]);

        } catch (DatabaseException $e) {
            $db->transRollback();

            return $this->failedResponse($e->getMessage());

        } catch (\Throwable $th) {
            $db->transRollback();

            return $this->failedResponse($th->getMessage());
        }
    }

    public function reject(
        int $transactionId,
        int $adminId,
        ?string $note = null
    ): array {
        $db = \Config\Database::connect();

        $db->transBegin();

        try {
            $transferResult = $this->transferDetailRepository
                ->markRejected($transactionId, $adminId, $note);

            if (!$transferResult['ok']) {
                throw new \RuntimeException(
                    is_array($transferResult['error'])
                        ? json_encode($transferResult['error'])
                        : $transferResult['error']
                );
            }

            $transactionResult = $this->transactionRepository
                ->markFailed($transactionId);

            if (!$transactionResult['ok']) {
                throw new \RuntimeException(
                    is_array($transactionResult['error'])
                        ? json_encode($transactionResult['error'])
                        : $transactionResult['error']
                );
            }

            $historyResult = $this->historyTransactionRepository->record(
                transactionId: $transactionId,
                event: 'TRANSFER_PAYMENT_REJECTED',
                note: $note ?? 'Transfer payment rejected by admin.'
            );

            if (!$historyResult['ok']) {
                throw new \RuntimeException(
                    is_array($historyResult['error'])
                        ? json_encode($historyResult['error'])
                        : $historyResult['error']
                );
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed.');
            }

            $db->transCommit();

            return $this->successResponse([
                'transaction' => $transactionResult['data'],
                'transfer_detail' => $transferResult['data'],
            ]);

        } catch (DatabaseException $e) {
            $db->transRollback();

            return $this->failedResponse($e->getMessage());

        } catch (\Throwable $th) {
            $db->transRollback();

            return $this->failedResponse($th->getMessage());
        }
    }
}