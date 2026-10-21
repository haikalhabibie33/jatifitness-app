<?php 

namespace App\Services\Transactions;

use App\Models\TransactionModel;
use App\Models\HistoryTransactionModel;
use App\Models\CasualPassModel;
use App\Models\MemberAccessModel;
use App\Models\MembershipRequestModel;
use App\Models\MembershipModel;
use App\Models\AccessLogModel;

class RefundService 
{
    private TransactionModel $transactionModel;
    private HistoryTransactionModel $historyTransactionModel;
    private CasualPassModel $casualPassModel;
    private MemberAccessModel $memberAccessModel;
    private MembershipRequestModel $membershipRequestModel;
    private MembershipModel $membershipModel;
    private AccessLogModel $accessLogModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->historyTransactionModel = new HistoryTransactionModel();
        $this->casualPassModel = new CasualPassModel();
        $this->memberAccessModel = new MemberAccessModel();
        $this->membershipRequestModel = new MembershipRequestModel();
        $this->membershipModel = new MembershipModel();
        $this->accessLogModel = new AccessLogModel();
    }

    public function getEligibleCasualForRefund(int $perPage = 10): array
    {
         $transactions = $this->transactionModel
            ->select('transactions.id,
                      membership_requests.name,
                      membership_requests.phone,
                      membership_requests.email,
                      transactions.target_member_type_snapshot,
                      transactions.purchase_type,
                      transactions.plan_name_snapshot,
                      transactions.payment_method,
                      transactions.quantity,
                      transactions.total_amount,
                      transactions.paid_at, 
                      transactions.payment_status, 
                      transactions.refund_amount, 
                      transactions.refunded_at,
                      casual_passes.status, 
                      casual_passes.revoked_at')
            ->join('membership_requests','membership_requests.id = transactions.membership_request_id')
            ->join('casual_passes', 'membership_requests.id = casual_passes.membership_request_id')
            ->join('access_logs',"access_logs.ref_id = casual_passes.id AND access_logs.type = 'CASUAL'",'left')
                ->where('transactions.purchase_type', 'CASUAL')
                ->where('transactions.payment_status', 'SUCCESS')
                ->where('access_logs.id', null)
                ->where('transactions.refund_amount', null)
                ->where('transactions.refunded_at', null)
                ->where('casual_passes.revoked_at', null)
                ->where('casual_passes.status', 'UNUSED')
                ->orderBy('transactions.paid_at', 'DESC')
                ->paginate($perPage);

            return [
                'transactions' => $transactions,
                'pager' => $this->transactionModel->pager,
            ];
    }

    public function executeCasualRefund(int $transactionId, ?string $note=null): array 
    {
        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        $db->transBegin();

        try {
            $refundCandidate = $this->transactionModel
                ->select('transactions.id as transaction_id,
                          transactions.membership_request_id as membership_request_id,
                          transactions.total_amount,
                          transactions.payment_status,
                          transactions.refund_amount,
                          transactions.refunded_at,
                          casual_passes.id as casual_pass_id,
                          casual_passes.status as casual_status,
                          casual_passes.revoked_at')
                ->join('membership_requests', 'membership_requests.id = transactions.membership_request_id')
                ->join('casual_passes', 'membership_requests.id = casual_passes.membership_request_id')
                ->join('access_logs', "access_logs.ref_id = casual_passes.id AND access_logs.type ='CASUAL'", 'left' )
                    ->where('transactions.id', $transactionId)
                    ->where('transactions.purchase_type', 'CASUAL')
                    ->where('transactions.payment_status', 'SUCCESS')
                    ->where('transactions.refund_amount', null)
                    ->where('transactions.refunded_at', null)
                    ->where('casual_passes.status', 'UNUSED')
                    ->where('casual_passes.revoked_at', null)
                    ->where('access_logs.id', null)
                    ->get()
                    ->getRowArray();

            if (!$refundCandidate) {throw new \RuntimeException("Transaksi casual tidak eligible untuk refund");}

            $updateTransaction = $this->transactionModel
                                ->where('id', $refundCandidate['transaction_id'])
                                ->where('purchase_type', 'CASUAL')
                                ->where('payment_status', 'SUCCESS')
                                ->where('refund_amount', null)
                                ->where('refunded_at', null)
                                    ->set([
                                        'payment_status' => 'REFUNDED',
                                        'refund_amount' => $refundCandidate['total_amount'],
                                        'refunded_at' => $now,
                                        'updated_at' => $now
                                    ])
                                    ->update();

            if (!$updateTransaction) { throw new \RuntimeException('Gagal mengubah status transaksi menjadi REFUNDED');}
            
            $updatedCasualPasses = $this->casualPassModel
                    ->where('id', $refundCandidate['casual_pass_id'])
                    ->where('status', 'UNUSED')
                    ->where('revoked_at', null)
                        ->set([
                            'status' => 'REVOKED',
                            'revoked_at' => $now,
                            'updated_at' => $now,
                        ])
                        ->update();
            if (!$updatedCasualPasses) { throw new \RuntimeException('Gagal mengubah status casual pass menjadi REVOKED');}

            $historyNote = $note ? : 'Casual refund executed';

            $historyInserted = $this->historyTransactionModel->insert([
                'transaction_id' => $refundCandidate['transaction_id'],
                'event' => 'REFUNDED',
                'note' => $historyNote,
                'created_at' => $now,
                'updated_at' => $now
            ]);
            if (!$historyInserted) { throw new \RuntimeException('Gagal menyimpan history refund');}

            $updatedMembershipRequest = $this->membershipRequestModel
                ->where('id', $refundCandidate['membership_request_id'])
                ->set([
                    'status'    => 'REFUNDED',
                ])
                ->update();

            if (!$updatedMembershipRequest) {
                throw new \RuntimeException('Failed to update membership request');
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed');
            }

            $db->transCommit();

            return [
                'success' => true,
                'message' => 'Refund is done successfully',
                'data' => [
                    'transaction_id' => (int) $refundCandidate['transaction_id'],
                    'casual_pass_id' => (int) $refundCandidate['casual_pass_id'],
                    'refund_amount' => (float) $refundCandidate['total_amount'],
                    'refunded_at' => $now,
                ],
            ];
        } catch (\Throwable $e) 
        {
            $db->transRollback();
            return [
                'success' => false,
                'message' => $e->getMessage()            
                ];
        }

    }

    public function getEligibleMemberForRefund(int $perPage = 10): array
    {
        $transactions = $this->transactionModel
                ->select('transactions.id as transaction_id,
                          membership_requests.name,
                          membership_requests.phone,
                          membership_requests.email,
                          transactions.target_member_type_snapshot,
                          transactions.purchase_type,
                          transactions.plan_name_snapshot,
                          transactions.payment_method,
                          transactions.quantity,
                          transactions.total_amount,
                          transactions.paid_at, 
                          transactions.payment_status, 
                          transactions.refund_amount, 
                          transactions.refunded_at')
                ->join('membership_requests', 'membership_requests.id = transactions.membership_request_id')
                ->join('memberships', 'membership_requests.id = memberships.membership_request_id')
                ->join('member_access', 'memberships.id = member_access.membership_id')
                ->join('access_logs', "access_logs.ref_id = memberships.id AND access_logs.type = 'MEMBER'",'left')
                    ->where('transactions.purchase_type','MEMBER')
                    ->where('transactions.payment_status', 'SUCCESS')
                    ->where('memberships.status', 'ACTIVE')
                    ->where('member_access.status', 'ACTIVE')
                    ->where('transactions.refunded_at', null)
                    ->where('transactions.refund_amount', null)
                    ->where('transactions.corrected_from_transaction_id', null)
                    ->where('access_logs.id', null)
                    ->where("
                        NOT EXISTS (
                            SELECT 1
                            from memberships m2
                            WHERE m2.member_id = memberships.member_id
                            AND m2.status = 'ACTIVE'
                            AND m2.start_date > memberships.start_date
                            )", null, false)
                ->orderBy('transactions.paid_at', 'DESC')
                ->paginate($perPage);

                return [
                'transactions' => $transactions,
                'pager' => $this->transactionModel->pager,
            ];

    }

    public function executeMemberRefund(int $transactionId, ?string $note=null) : array 
    {
        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        $db->transBegin();

        try {
            $refundCandidate = $this->transactionModel
                    ->select('transactions.id as transaction_id,
                              transactions.membership_request_id as membership_request_id,
                              transactions.total_amount,
                              transactions.payment_status,
                              transactions.refund_amount,
                              transactions.refunded_at,
                              membership_requests.id as membership_requests_id,
                              memberships.id as memberships_id')
                    ->join('membership_requests','membership_requests.id = transactions.membership_request_id')
                    ->join('memberships', 'memberships.membership_request_id = membership_requests.id')
                    ->join('member_access', 'memberships.id = member_access.membership_id')
                    ->join('access_logs', "access_logs.ref_id = memberships.id AND access_logs.type ='MEMBER'", 'left' )
                        ->where('transactions.id', $transactionId)
                        ->where('transactions.purchase_type', 'MEMBER')
                        ->where('transactions.payment_status', 'SUCCESS')
                        ->where('memberships.status', 'ACTIVE')
                        ->where('member_access.status', 'ACTIVE')
                        ->where('transactions.refund_amount', null)
                        ->where('transactions.refunded_at', null)
                        ->where('transactions.corrected_from_transaction_id', null)
                        ->where('access_logs.id', null)
                        ->get()
                        ->getRowArray();
            if (!$refundCandidate) { throw new \RuntimeException('Failed to find eligible member to be refunded');}

            $updateTransaction = $this->transactionModel
                                 ->where('id',$refundCandidate['transaction_id'])
                                 ->where('purchase_type', 'MEMBER')
                                 ->where('payment_status', 'SUCCESS')
                                 ->where('refund_amount', null)
                                 ->where('refunded_at', null)
                                 ->set([
                                        'payment_status' => 'REFUNDED',
                                        'refund_amount' => $refundCandidate['total_amount'],
                                        'refunded_at' => $now,
                                        'updated_at' => $now
                                    ])
                                ->update();
            if (!$updateTransaction) { throw new \RuntimeException('Failed to update refunded transactions!');}

            $historyNote = $note ? : 'Member refund executed';

            $historyInserted = $this->historyTransactionModel->insert([
                'transaction_id' => $refundCandidate['transaction_id'],
                'event' => 'REFUNDED',
                'note' => $historyNote,
                'created_at' => $now,
                'updated_at' => $now
            ]);
            if (!$historyInserted) { throw new \RuntimeException('Gagal menyimpan history refund');}

            $updateMemberships = $this->membershipModel
                                 ->where('id',$refundCandidate['memberships_id'])
                                 ->where('status','ACTIVE')
                                 ->set([
                                    'status' => 'CANCELLED',
                                    'updated_at' => $now,
                                 ])
                                 ->update();
            if (!$updateMemberships) { throw new \RuntimeException('Failed to update refunded Memberships!');}

            $updateMemberAccess = $this->memberAccessModel
                                  ->where('membership_id',$refundCandidate['memberships_id'])
                                  ->where('status', 'ACTIVE')
                                  ->set([
                                    'status' => 'REVOKED',
                                    'updated_at' => $now,
                                  ])
                                  ->update();

            if (!$updateMemberAccess) { throw new \RuntimeException('Failed to update refunded Member Access');}

            $updatedMembershipRequest = $this->membershipRequestModel
                ->where('id', $refundCandidate['membership_request_id'])
                ->set([
                    'status'    => 'REFUNDED',
                ])
                ->update();
            
            if (!$updatedMembershipRequest) {
                throw new \RuntimeException('Failed to update membership request');
            }
            
            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed');
            }

            $db->transCommit();

            return [
                'success' => true,
                'message' => 'Refund is done successfully',
                'data' => [
                    'transaction_id' => (int) $refundCandidate['transaction_id'],
                    'memberships_id' => (int) $refundCandidate['memberships_id'],
                    'refund_amount' => (float) $refundCandidate['total_amount'],
                    'refunded_at' => $now,
                ],
            ];
        } catch(\Throwable $e) {
            $db->transRollback();
            return [
                'success' => false,
                'message' => $e->getMessage()            
                ];
        }
    }

}