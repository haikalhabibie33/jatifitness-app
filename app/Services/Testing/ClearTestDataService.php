<?php 

namespace App\Services\Testing;

use App\Models\AccessLogModel;
use App\Models\CasualPassModel;
use App\Models\HistoryTransactionModel;
use App\Models\MemberAccessModel;
use App\Models\MemberModel;
use App\Models\MembershipModel;
use App\Models\MembershipRequestModel;
use App\Models\TransactionModel;

class ClearTestDataService
{
    private MembershipRequestModel $membershipRequestModel;
    private MembershipModel $membershipModel;
    private TransactionModel $transactionModel;
    private HistoryTransactionModel $historyTransactionModel;
    private MemberModel $memberModel;
    private MemberAccessModel $memberAccessModel;
    private CasualPassModel $casualPassModel;
    private AccessLogModel $accessLogModel;

    public function __construct()
    {
        $this->membershipRequestModel = new MembershipRequestModel();
        $this->membershipModel = new MembershipModel();
        $this->transactionModel = new TransactionModel();
        $this->historyTransactionModel = new HistoryTransactionModel();
        $this->memberModel = new MemberModel();
        $this->memberAccessModel = new MemberAccessModel();
        $this->casualPassModel = new CasualPassModel();
        $this->accessLogModel = new AccessLogModel();
    }

    private function getMemberId(array $data): ?int
    {
        $memberId = $this->memberModel->
                    where('phone', $data['phone'])->
                    where('email', $data['email'])->
                    first();
        return $memberId['id'] ?? null;
    }

    private function getMembershipRequestId(array $data): array
    {
        $rows = $this->membershipRequestModel->
              where('phone', $data['phone'])->
              where('email', $data['email'])->
              findAll();
        return $rows ? array_column($rows, 'id') : [];
    }

    private function getCasualPassId(array $data): array
    {
        $membershipRequestIds = $this->getMembershipRequestId($data);

        $ids = [];
        foreach ($membershipRequestIds as $membershipRequestId) {
            $casualPass = $this->casualPassModel->
                          where('membership_request_id', $membershipRequestId)->
                          first();
            if ($casualPass) {
                $ids[] = $casualPass['id'];
            }
        }
        return $ids;
    }

    private function getMembershipsId(array $data): array
    {
        $memberId = $this->getMemberId($data);
        $membershipRequestIds = $this->getMembershipRequestId($data);

        $ids = [];
        foreach ($membershipRequestIds as $membershipRequestId) {
            $membershipId = $this->membershipModel->
                            where('membership_request_id', $membershipRequestId)->
                            where('member_id', $memberId)
                            ->first();
            if ($membershipId) {
                $ids[] = $membershipId['id'];
            }
        }
        return $ids;
    }

    private function getMemberAccessId(array $data): array
    {
        $membershipsIds = $this->getMembershipsId($data);

        $ids = [];
        foreach ($membershipsIds as $membershipsId) {
            $memberAccess = $this->memberAccessModel->
                            where('membership_id', $membershipsId)->
                            first();
            if ($memberAccess) {
                $ids[] = $memberAccess['id'];
            }
        }
        return $ids;
    }

    private function getTransactionId(array $data): array
    {
        $membershipRequestIds = $this->getMembershipRequestId($data);
        $ids = [];
        foreach ($membershipRequestIds as $membershipRequestId) {
            $transaction =$this->transactionModel->
                 where('membership_request_id',$membershipRequestId)->
                 first();
            if ($transaction) {
                $ids[] = $transaction['id'];
            }
        }
        return $ids;
    }

    private function getHistoryTransactionId(array $data): array
    {
        $ids = [];
        $transactionIds = $this->getTransactionId($data);

        foreach ($transactionIds as $transactionId) {
            $historyTransactionId = $this->historyTransactionModel->
                                  where('transaction_id',$transactionId)->
                                  first();
            if ($historyTransactionId) {
                $ids[] = $historyTransactionId['id'];
            }
        }

        return $ids;
    }

    private function getMemberAccessLogId(array $data): array
    {
        $ids=[];
        $membershipIds = $this->getMembershipsId($data);
        foreach ($membershipIds as $membershipId) {
            $accessLog = $this->accessLogModel->
                         where('ref_id', $membershipId)->
                         first();
            if ($accessLog) {
                $ids[] = $accessLog['id'];
            }
        }

        return $ids;
    }

    private function getCasualAccessLogId(array $data): array
    {
        $ids=[];
        $casualPassesIds = $this->getCasualPassId($data);
        foreach ($casualPassesIds as $casualPassesId) {
            $accessLog = $this->accessLogModel->
                         where('ref_id', $casualPassesId)->
                         first();
            if ($accessLog) {
                $ids[] = $accessLog['id'];
            }
        }
        return $ids;
    }

    public function clearMemberTestData(array $data): bool
    {
        $memberId = $this->getMemberId($data);
        $membershipRequestIds = $this->getMembershipRequestId($data);
        $transactionIds = $this->getTransactionId($data);
        $historyTransactionIds = $this->getHistoryTransactionId($data);
        $membershipIds = $this->getMembershipsId($data);
        $memberAccessids = $this->getMemberAccessId($data);
        $accessLogIds = $this->getMemberAccessId($data);

        $db = \Config\Database::connect();

        $db->transBegin();

        try {
            foreach ($membershipRequestIds as $membershipRequestId) {
                $deletedMembershipRequest = $this->membershipRequestModel->delete($membershipRequestId);
                if (!$deletedMembershipRequest) {
                    throw new \RuntimeException('Failed to delete membership requests!');
                }
            }

            foreach ($transactionIds as $transactionId) {
                $deletedTransactionId = $this->transactionModel->delete($transactionId);

                if (!$deletedTransactionId) {
                    throw new \RuntimeException('Failed to delete transactions!');
                }
            }

            foreach ($historyTransactionIds as $historyTransactionId) {
                $deletedHistoryTransactionId = $this->historyTransactionModel->delete($historyTransactionId);

                if (!$deletedHistoryTransactionId) {
                    throw new \RuntimeException('Failed to delete history transactions!');
                }
            }

            foreach ($membershipIds as $membershipId) {
                $deletedMembershipId = $this->membershipModel->delete($membershipId);

                if (!$deletedMembershipId) {
                    throw new \RuntimeException('Failed to delete memberships!');
                }
            }

            foreach ($memberAccessids as $memberAccessid) {
                $deletedMemberAccessId = $this->memberAccessModel->delete($memberAccessid);

                if (!$deletedMemberAccessId) {
                    throw new \RuntimeException('Failed to delete member access!');
                }
            }
            if ($memberId !== null) {
                $deletedMemberId = $this->memberModel->delete($memberId);
                if (!$deletedMemberId) {
                    throw new \RuntimeException('Failed to delete member ID');
                }
            }

            foreach ($accessLogIds as $accessLogId) {
                $deletedAccessLogId = $this->accessLogModel->delete($accessLogId);

                if (!$deletedAccessLogId) {
                    throw new \RuntimeException('Failed to delete access log ID');
                }
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed!');
            }
            $db->transCommit();
            return true;
        } catch(\Throwable $e) {
            $db->transRollback();
            return false;
        }
    }

    public function clearCasualTestData(array $data):bool 
    {
        $membershipRequestIds = $this->getMembershipRequestId($data);
        $transactionIds = $this->getTransactionId($data);
        $historyTransactionIds = $this->getHistoryTransactionId($data);
        $casualPassIds = $this->getCasualPassId($data);
        $accessLogIds = $this->getCasualAccessLogId($data);

        $db = \Config\Database::connect();

        $db->transBegin();

        try {

        foreach ($membershipRequestIds as $membershipRequestId) {
            $deletedMembershipRequest = $this->membershipRequestModel->delete($membershipRequestId);
            if (!$deletedMembershipRequest) {
                throw new \RuntimeException('Failed to delete membership request!');
            }
        }

        foreach ($transactionIds as $transactionId) {
            $deletedTransactions = $this->transactionModel->delete($transactionId);
            if (!$deletedTransactions) {
                throw new \RuntimeException('Failed to delete transactions!');
            }
        }

        foreach ($historyTransactionIds as $historyTransactionId) {
            $deletedHistoryTransaction = $this->historyTransactionModel->delete($historyTransactionId);
            if (!$deletedHistoryTransaction) {
                throw new \RuntimeException('Failed to delete history transactions!');
            }
        }

        foreach ($casualPassIds as $casualPassId) {
            $deletedCasualPass = $this->casualPassModel->delete($casualPassId);
            if (!$deletedCasualPass) {
                throw new \RuntimeException('Failed to delete casual pass!');
            }
        }

        foreach ($accessLogIds as $accessLogId) {
            $deletedAccessLog = $this->accessLogModel->delete($accessLogId);
            if (!$deletedAccessLog) {
                throw new \RuntimeException('Failed to delete access log!');
            }
        }

        if ($db->transStatus() === false){
            throw new \RuntimeException('DB transaction failed');
        }
        $db->transCommit();
        return true;
        } catch(\Throwable $e) {
            $db->transRollback();
            return false;
        }
    }

}