<?php

namespace App\Services\Transactions;

use App\Contracts\Repositories\CasualPassRepositoryInterface;
use App\Contracts\Repositories\MemberAccessRepositoryInterface;
use App\Contracts\Repositories\MemberRepositoryInterface;
use App\Contracts\Repositories\MembershipRepositoryInterface;
use App\Contracts\Repositories\MembershipRequestRepositoryInterface;
use App\Contracts\Repositories\TransactionRepositoryInterface;
use App\Repositories\CasualPassRepository;
use App\Repositories\MemberAccessRepository;
use App\Repositories\MemberRepository;
use App\Repositories\MembershipRepository;
use App\Repositories\MembershipRequestRepository;
use App\Repositories\TransactionRepository;
use App\Traits\GenerateAccessToken;

class PaymentService
{
    use GenerateAccessToken;

    private MembershipRequestRepositoryInterface $membershipRequestRepository;
    private TransactionRepositoryInterface $transactionRepository;
    private CasualPassRepositoryInterface $casualPassRepository;
    private MembershipRepositoryInterface $membershipRepository;
    private MemberRepositoryInterface $memberRepository;
    private MemberAccessRepositoryInterface $memberAccessRepository;

    public function __construct(
        ?MembershipRequestRepositoryInterface $membershipRequestRepository = null,
        ?TransactionRepositoryInterface $transactionRepository = null,
        ?CasualPassRepositoryInterface $casualPassRepository = null,
        ?MembershipRepositoryInterface $membershipRepository = null,
        ?MemberRepositoryInterface $memberRepository = null,
        ?MemberAccessRepositoryInterface $memberAccessRepository = null,
    )
    {
        $this->membershipRequestRepository = $membershipRequestRepository ?? new MembershipRequestRepository;
        $this->transactionRepository = $transactionRepository ?? new TransactionRepository();
        $this->casualPassRepository = $casualPassRepository ?? new CasualPassRepository();
        $this->membershipRepository = $membershipRepository ?? new MembershipRepository();
        $this->memberRepository = $memberRepository ?? new MemberRepository();
        $this->memberAccessRepository = $memberAccessRepository ?? new MemberAccessRepository();
    }

    private function paymentMeta(array $request): array
    {
        $paymentMethod = strtoupper((string) ($request['payment_method']));
        $reference = match ($paymentMethod) {
            'CASH' => 'CASH-' . (session()->get('admin_id') ?? 'SYSTEM') . '-',
            'TRANSFER' => 'TRANSFER-' . time(),
            'QRIS'  => 'QRIS-' . time(),
            default   => 'SYSTEM-' . time(),
        };

        $method = match ($paymentMethod) {
            'SYSTEM' => 'TRANSFER',
            'OFFLINE' => 'CASH',
            'ONLINE'  => 'QRIS',
            default   => 'SIMULATION',
        };
        return [
            'reference' => $reference,
            'method' => $method,
        ];
    }

    private function createCompleteMembership(array $data): array
    {
        $db = \Config\Database::connect();
        $db->transBegin();
        try {
            $isMemberExist = $this->memberRepository->checkPhoneAndEmailExists($data['phone'], $data['email']);
      
            $memberId = null;
            if (!$isMemberExist['ok']) {
                $memberId = $this->memberRepository->create($data);
                if (!$memberId['ok']) {
                    throw new \RuntimeException('Failed to create member data!');
                } else {
                    $memberId = $memberId['data'];
                }
            } else {
                $memberId = $isMemberExist['data']['id'];
            }

            $membershipId = $this->membershipRepository->create($data, $memberId);
            if (!$membershipId['ok']) {
                throw new \RuntimeException('Failed to create membership');
            }

            $memberAccessExist = $this->memberAccessRepository->findMemberAccessByMembershipId($membershipId['data']);
            if (!$memberAccessExist['ok']) {
                $memberAccess = $this->memberAccessRepository->create($membershipId['data'], $data['access_token']);
                if (!$memberAccess['ok']) {
                    throw new \RuntimeException('Failed to find or create new member access!');
                }
            }

            $markComplete = $this->membershipRequestRepository->markComplete($data['requestId']);
            if (!$markComplete['ok']) {
                throw new \RuntimeException('Failed to mark as completed on membership request!');
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed!');
            }

            $db->transCommit();
            return [
                'success' => true,
                'error' => null,
            ];
        } catch (\Throwable $th) {
            $db->transRollback();
            return [
                'success' => false,
                'error' => $th->getMessage(),
            ];
        }
    }

    public function process(int $requestId, string $token, string $source): array
    {
        // todo validate input and compare it to membership_requests table
        $requestData = [
            'requestId' => $requestId,
            'access_token' => $token,
            'payment_source' => $source,
        ];

        $validateRequest = $this->membershipRequestRepository->findMembershipRequestByIdAndAccessToken($requestId, $token);
        if (!$validateRequest['ok']) {
            return [
                'success' => false,
                'message' => 'Membership Request is not found!',
            ];
        }
        //temporary bypass 
        $request = $validateRequest['data'];

        //todo adjusting input for payment type and input
        $paymentMeta = $this->paymentMeta($request);

        //todo marking as paid on membership_requests and then fill transactions + history_transactions
        $data = [
            'requestId'                   => $requestId,
            'payment_source'              => $requestData['payment_source'],
            'payment_reference'           => $paymentMeta['reference'],
            'membership_request_id'       => $requestData['requestId'],
            'membership_plan_id'          => $request['membership_plan_id'],
            'member_type'                 => $request['target_member_type'],
            'purchase_type'               => $request['request_type'],
            'plan_code_snapshot'          => $request['plan_code_snapshot'],
            'plan_name_snapshot'          => $request['plan_name_snapshot'],
            'price_snapshot'              => $request['price_snapshot'],
            'duration_days_snapshot'      => $request['duration_snapshot'],
            'quantity'                    => $request['quantity'] ?? 1,
            'total_amount'                => $request['total_price_snapshot'],
            'name'                        => $request['name'],
            'email'                       => $request['email'],
            'phone'                       => $request['phone'],
            'gender'                      => $request['gender'],
            'identity_key'                => $this->generateAccessToken(),
            'date_of_birth'               => $request['date_of_birth'],
            'address'                     => $request['address'],
            'payment_method'              => $request['payment_method'],
            'access_token'                => $token,
            'daily_limit_member'          => $request['daily_limit_snapshot'],
            'daily_limit_casual'          => $request['daily_limit_snapshot'],
            'usage_limit'                 => $request['duration_snapshot']
        ];
        // $transactionCompleted = $this->completeTransaction($request,$requestData,$paymentMeta);
        $transactionCompleted = $this->transactionRepository->completeTransaction($data);
        if (!$transactionCompleted['ok']) {
            return [
                'success' => false,
                'message' => $transactionCompleted['error'],
            ];
        }
        
        /**
         * ✅ DOMAIN PROVISIONING
         */
        if ($request['request_type'] === 'CASUAL') {
            // $casualProvisioning = $this->casualProvisioning($request, $requestData);
            $casualProvisioning = $this->casualPassRepository->create($data);
            if(!$casualProvisioning['ok']) {
                return [
                    'success' => false,
                    'message' => $casualProvisioning['error'],
                ];
            }
        }

        if ($request['request_type'] === 'MEMBER') {
            // $memberProvisioning = $this->membershipRepository->createCompleteMembership($data);
            $memberProvisioning = $this->createCompleteMembership($data);
            if (!$memberProvisioning['success']) {
                return [
                    'success' => false,
                    'message' => $memberProvisioning['error'],
                ];
            }
        }

        return [
            'success' => true,
            'message' => 'Successfully processing the payment!'
        ];
    }
}