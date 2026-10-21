<?php

namespace App\Repositories;

use App\Contracts\Repositories\TransactionRepositoryInterface;
use App\Models\HistoryTransactionModel;
use App\Models\MembershipRequestModel;
use App\Models\TransactionModel;
use App\DTO\Transaction\ViewDTO;
use CodeIgniter\Database\Exceptions\DatabaseException;
use App\Traits\RepositoryResponseTrait;

class TransactionRepository implements TransactionRepositoryInterface
{
    use RepositoryResponseTrait;
    private TransactionModel $transactionModel;
    private MembershipRequestModel $membershipRequestModel;
    private HistoryTransactionModel $historyTransactionModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->membershipRequestModel = new MembershipRequestModel();
        $this->historyTransactionModel = new HistoryTransactionModel();

    }

    public function completeTransaction(array $data): array
    {
        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        $db->transBegin();

        try {
            $requestIdExist = $this->membershipRequestModel->find($data['requestId']);
            if (!$requestIdExist) {
                throw new \RuntimeException('Membership request id is not found!');
            }
            
            $updatedPaid = $this->membershipRequestModel->update($data['requestId'],[
                'status' => 'PAID',
                'payment_source'    => $data['payment_source'],
                'payment_reference' => $data['payment_reference'],
                'paid_at'           => $now,
                'updated_at'        => $now,
            ]);

            if (!$updatedPaid) {
                throw new \RuntimeException('Failed to mark as paid');
            }

            $transactionId = $this->transactionModel->insert([
                'membership_request_id'       => $data['requestId'],
                'membership_plan_id'          => $data['membership_plan_id'],
                'target_member_type_snapshot' => $data['member_type'],
                'purchase_type'               => $data['purchase_type'],
                'plan_code_snapshot'          => $data['plan_code_snapshot'],
                'plan_name_snapshot'          => $data['plan_name_snapshot'],
                'price_snapshot'              => $data['price_snapshot'],
                'duration_days_snapshot'      => $data['duration_days_snapshot'],
                'quantity'                    => $data['quantity'],
                'total_amount'                => $data['total_amount'],
                'payment_method'              => $data['payment_method'],
                'payment_status'              => 'SUCCESS',
                'paid_at'                     => $now,
                'created_at'                  => $now,
                'updated_at'                  => $now,
            ],true);

            if (!$transactionId) {
                throw new \RuntimeException('Failed to insert transaction');
            }

            $historyTransaction = $this->historyTransactionModel->insert([
                'transaction_id' => $transactionId,
                'event'          => 'PAID',
                'note'           => 'Payment confirmed',
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);

            if (!$historyTransaction) {
                throw new \RuntimeException('Failed to insert history transaction');
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed!');
            }

            $db->transCommit();
            return [
                'ok' => true,
            ];
        }
        catch(\Throwable $th){
            $db->transRollback();
            return [
                'ok' => false,
                'error' => $th->getMessage(),
            ];
        }
    }

    public function viewReport(ViewDTO $dto): array
    {
        try {
            $builder = $this->transactionModel
                ->from('transactions t')
                ->select('
                    t.*,
                    mr.name,
                    mr.phone
                ')
                ->join('membership_requests mr', 'mr.id = t.membership_request_id', 'LEFT');

            // ========================
            // SEARCH
            // ========================
            if (!empty($dto->search)) {
                $builder->groupStart()
                    ->like('mr.name', $dto->search)
                    ->orLike('mr.phone', $dto->search)
                    ->groupEnd();
            }

            // ========================
            // FILTER
            // ========================
            if (!empty($dto->method)) {
                $builder->where('t.payment_method', $dto->method);
            }

            if (!empty($dto->status)) {
                $builder->where('t.payment_status', $dto->status);
            }

            if (!empty($dto->from)) {
                $builder->where('t.paid_at >=', $dto->from . ' 00:00:00');
            }

            if (!empty($dto->to)) {
                $builder->where('t.paid_at <=', $dto->to . ' 23:59:59');
            }

            // ========================
            // EXECUTE
            // ========================
            $transactions = $builder
                ->orderBy('t.paid_at', 'DESC')
                ->paginate($dto->limit, 'default', $dto->page);

            $pager = $builder->pager;
            // return [
            //     'transactions' => $transactions,
            //     'pager' => $pager
            // ];
            return $this->successResponse([
                'transactions' => $transactions,
                'pager' => $pager,
            ]);
        } catch(DatabaseException $de){
            return $this->failedResponse(
                $de->getMessage(),
                [
                    'transactions' => [],
                    'pager' => [],
                ]);
        } catch (\Throwable $th) {
            return $this->failedResponse($th->getMessage(),[
                'transactions' => [],
                'pager' => []
            ]);
        }
        
    }

    public function markWaitingConfirmation(int $transactionId): array
    {
        try {
            $updated = $this->transactionModel->update($transactionId, [
                'payment_status' => 'WAITING_CONFIRMATION',
            ]);

            if (!$updated) {
                return $this->failedResponse(
                    $this->transactionModel->errors()
                        ?: 'Failed to mark transaction as waiting confirmation.'
                );
            }

            $transaction = $this->transactionModel
                ->asArray()
                ->find($transactionId);

            return $this->successResponse($transaction);

        } catch (DatabaseException $e) {
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }

    public function markSuccess(int $transactionId): array
    {
        try {
            $updated = $this->transactionModel->update($transactionId, [
                'payment_status' => 'SUCCESS',
                'paid_at'        => date('Y-m-d H:i:s'),
            ]);

            if (!$updated) {
                return $this->failedResponse(
                    $this->transactionModel->errors()
                        ?: 'Failed to mark transaction as success.'
                );
            }

            $transaction = $this->transactionModel
                ->asArray()
                ->find($transactionId);

            return $this->successResponse($transaction);

        } catch (DatabaseException $e) {
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }

    public function markFailed(int $transactionId): array
    {
        try {
            $updated = $this->transactionModel->update($transactionId, [
                'payment_status' => 'FAILED',
            ]);

            if (!$updated) {
                return $this->failedResponse(
                    $this->transactionModel->errors()
                        ?: 'Failed to mark transaction as failed.'
                );
            }

            $transaction = $this->transactionModel
                ->asArray()
                ->find($transactionId);

            return $this->successResponse($transaction);

        } catch (DatabaseException $e) {
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }
}