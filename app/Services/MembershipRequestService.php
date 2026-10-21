<?php

namespace App\Services;

use App\Contracts\Repositories\MembershipPlanRepositoryInterface;
use App\Contracts\Repositories\MembershipRequestRepositoryInterface;
use App\Contracts\Repositories\PaymentBankAccountRepositoryInterface;
use App\Contracts\Repositories\TransactionTransferDetailRepositoryInterface;
use App\DTO\MembershipRequest\SubmitCasualDTO;
use App\DTO\MembershipRequest\SubmitMemberDto;
use App\Repositories\MembershipPlanRepository;
use App\Repositories\MembershipRequestRepository;
use App\Repositories\PaymentBankAccountRepository;
use App\Repositories\TransactionTransferDetailRepository;
use App\Traits\GenerateAccessToken;

class MembershipRequestService
{
    use GenerateAccessToken;
    private MembershipRequestRepositoryInterface $membershipRequestRepository;
    private MembershipPlanRepositoryInterface $membershipPlanRepository;
    private PaymentBankAccountRepositoryInterface $paymentBankAccountRepository;
    private TransactionTransferDetailRepositoryInterface $transactionTransferDetailRepository;

    public function __construct(
        ?MembershipRequestRepositoryInterface $membershipRequestRepository = null,
        ?MembershipPlanRepositoryInterface $membershipPlanRepository = null,
        ?PaymentBankAccountRepositoryInterface $paymentBankAccountRepository = null,
        ?TransactionTransferDetailRepositoryInterface $transactionTransferDetailRepository = null,
    )
    {
        $this->membershipRequestRepository = $membershipRequestRepository ?? new MembershipRequestRepository;
        $this->membershipPlanRepository = $membershipPlanRepository ?? new MembershipPlanRepository;
        $this->paymentBankAccountRepository = $paymentBankAccountRepository ?? new PaymentBankAccountRepository();
        $this->transactionTransferDetailRepository = $transactionTransferDetailRepository ?? new TransactionTransferDetailRepository();
    }

    public function submitCasual(SubmitCasualDTO $dto): array
    {
        $findPlan = $this->membershipPlanRepository->getMembershipPlanById($dto->planId);
        if (!$findPlan['ok']) {
            return [
                'ok' => false,
                'error' => $findPlan['error'],
            ];
        }
        $plan = $findPlan['data'];

        $quantity = max(1, min(30, $dto->quantity));
        $baseDuration = (int) $plan['duration_days'];
        $basePrice    = (string) $plan['price'];
        $totalDuration = $baseDuration * $quantity;
        $totalPrice    = bcmul($basePrice, (string) $quantity, 2);
        $dailyLimit = (int) $plan['daily_limit'];
        $approvalRequired = $dto->targetType === 'STUDENT' ? 1: 0;
        $name = $dto->name;
        $phone = $dto->phone;
        $email = $dto->email;
        $accessToken = $this->generateAccessToken();
        $paymentSource = match (strtoupper($dto->paymentMethod)) {
            'TRANSFER'  => 'SYSTEM' ,
            'CASH'      => 'OFFLINE' ,
            'QRIS'      => 'ONLINE',
            default     => 'SYSTEM',
        };

        $insertCasualData = [
          'access_token'                => $accessToken,
          'request_type'                => 'CASUAL',
          'target_member_type'          => $dto->targetType,
          'membership_plan_id'          => $dto->planId,
          'plan_code_snapshot'          => $plan['code'],
          'plan_name_snapshot'          => $plan['name'],
          'price_snapshot'              => $basePrice,
          'duration_snapshot'           => $totalDuration,
          'daily_limit_snapshot'        => $dailyLimit,
          'quantity'                    => $quantity,
          'total_price_snapshot'        => $totalPrice,
          'approval_required'           => $approvalRequired,
          'name'                        => $name,
          'phone'                       => $phone,
          'email'                       => $email,
          'gender'                      => $dto->gender,
          'status'                      => 'SUBMITTED',
          'payment_method'              => $dto->paymentMethod,
          'payment_source'              => $paymentSource
        ];

        $result = $this->membershipRequestRepository->create($insertCasualData);
        if (!$result['ok']) {
            return [
                'ok' => false,
                'error' => $result['error']
            ];
        
        }
        return [
            'ok' => true,
            'accessToken' => $insertCasualData['access_token'],
        ];
    }

    public function submitMember(SubmitMemberDto $dto): array
    {
        $findPlan = $this->membershipPlanRepository->getMembershipPlanById($dto->planId);
        if (!$findPlan['ok']) {
            return [
                'ok' => false,
                'error' => $findPlan['error'],
            ];
        }

        $plan = $findPlan['data'];

        $baseDuration = (int) $plan['duration_days'];
        $basePrice    = (string) $plan['price'];
        $dailyLimit = (int) $plan['daily_limit'];
        $approvalRequired = $dto->targetType === 'STUDENT' ? 1 : 0;
        $accessToken = $this->generateAccessToken();
        $paymentSource = match (strtoupper($dto->paymentMethod)) {
            'TRANSFER'  => 'SYSTEM' ,
            'CASH'      => 'OFFLINE' ,
            'QRIS'      => 'ONLINE',
            default     => 'SYSTEM',
        };

        $insertMemberData = [
            'access_token'                  => $accessToken,
            'request_type'                  => 'MEMBER',
            'target_member_type'            => $dto->targetType,
            'membership_plan_id'            => $dto->planId,
            'plan_code_snapshot'            => $plan['code'],
            'plan_name_snapshot'            => $plan['name'],
            'price_snapshot'                => $basePrice,
            'duration_snapshot'             => $baseDuration,
            'quantity'                      => 1,
            'total_price_snapshot'          => $basePrice,
            'daily_limit_snapshot'          => $dailyLimit,
            'approval_required'             => $approvalRequired,
            'name'                          => $dto->name,
            'phone'                         => $dto->phone,
            'email'                         => $dto->email,
            'gender'                        => $dto->gender,
            'date_of_birth'                 => $dto->dateOfBirth,
            'address'                       => $dto->address,
            'status'                        => 'SUBMITTED',
            'payment_source'                => $paymentSource,
            'payment_method'                => $dto->paymentMethod,
        ];

        $result = $this->membershipRequestRepository->create($insertMemberData);
        if (!$result['ok']) {
            return [
                'ok' => false,
                'error' => $result['error'],
            ];
        }
         return [
                'ok' => true,
                'accessToken' => $accessToken,
            ];
    }

}