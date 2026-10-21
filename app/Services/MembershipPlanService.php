<?php 

namespace App\Services;

use App\Contracts\Repositories\MembershipPlanRepositoryInterface;
use App\DTO\MembershipPlan\AdminViewDto;
use App\DTO\MembershipPlan\CreatePlanDto;
use App\DTO\MembershipPlan\PublicViewDto;
use App\DTO\MembershipPlan\UpdatePlanDto;
use App\DTO\MembershipRequest\SubmitCasualDTO;
use App\DTO\MembershipRequest\SubmitMemberDto;
use App\Repositories\MembershipPlanRepository;
use App\DTO\MembershipPlan\PagerViewDto;

use App\Traits\RepositoryResponseTrait;

class MembershipPlanService
{
    use RepositoryResponseTrait;
    private MembershipPlanRepositoryInterface $membershipPlanRepository;

    public function __construct(
        ?MembershipPlanRepositoryInterface $membershipPlanRepository = null
    )
    {
        $this->membershipPlanRepository = $membershipPlanRepository ?? new MembershipPlanRepository;
    }

     public function validateCasualInputToMembershipPlan(SubmitCasualDTO $dto) :array
    {
        $findPlan = $this->membershipPlanRepository->getMembershipPlanById($dto->planId);
        if (!$findPlan['ok']) {
            return [
                'ok' => false,
                'error' => $findPlan['error'],
            ];
        }
        $plan = $findPlan['data'];

        $audienceScope = in_array($plan['audience_scope'],['CASUAL_ONLY','BOTH']);
        if (!$audienceScope) {
            return [
                'ok' => false, 
                'error' => 'This plan is not available for casual access'
                ];
        }

        $targetMemberType = $plan['target_member_type'];
        if ($targetMemberType !== $dto->targetType) {
            return [
                'ok' => false, 
                'error'=> 'Plan does not match selected category'
                ];
        }

        return [
            'ok' => true
            ];
    }

    public function validateMemberInputToMembershipPlan(SubmitMemberDto $dto): array
    {
        $findPlan = $this->membershipPlanRepository->getMembershipPlanById($dto->planId);
        if (!$findPlan['ok']) {
            return [
                'ok' => false,
                'error' => $findPlan['error'],
            ];
        }
        $plan = $findPlan['data'];

        $audienceScope = in_array($plan['audience_scope'], ['MEMBER_ONLY','BOTH']);
        if (!$audienceScope) {
            return [
                'ok' => false, 
                'error' => 'This plan is not available for member access'
                ];
        }

        $targetMemberType = $plan['target_member_type'];
        if ($targetMemberType !== $dto->targetType) {
            return [
                'ok' => false, 
                'error'=> 'Plan does not match selected category'
            ];
        }

        return [
            'ok' => true
            ];

    }

    public function findAllCasualActivePlans(): array
    {
        $result = $this->membershipPlanRepository->findAllCasualActivePlans();
        if (!$result['ok']) {
            return [
                'success' => false,
                'message' => $result['error'],
            ];
        }

        return [
            'success' => true,
            'data' => $result['data'],
        ];
    }

    public function findAllMemberActivePlans(): array
    {
        $result = $this->membershipPlanRepository->findAllMemberActivePlans();
        if (!$result['ok']) {
            return [
                'success' => false,
                'message' => $result['error'],
            ];
        }

        return [
            'success' => true,
            'data' => $result['data'],
        ];
    }

    public function create(CreatePlanDto $dto): array
    {
        $data = [
            'code'          => $dto->code,
            'name'          => $dto->name,
            'price'         => $dto->price,
            'duration_days' => $dto->durationDays,
            'description'   => $dto->description,
            'target_member_type' => $dto->targetMemberType,
            'audience_scope' => $dto->audienceScope,
            'daily_limit' => $dto->dailyLimit
        ];
        $result = $this->membershipPlanRepository->create($data);
        if (!$result['ok']) {
            return [
                'success' => false,
                'error' => $result['error'],
            ];
        }
        return [
            'success' => true,
        ];
    }

    public function update(int $id, UpdatePlanDto $dto): array
    {
        $data = [
            'id'            => $id,
            'code'          => $dto->code,
            'name'          => $dto->name,
            'price'         => $dto->price,
            'duration_days' => $dto->durationDays,
            'description'   => $dto->description,
            'target_member_type' => $dto->targetMemberType,
            'audience_scope' => $dto->audienceScope,
            'daily_limit' => $dto->dailyLimit,
            'is_active' => $dto->isActive,
        ];

        $result = $this->membershipPlanRepository->update($data);
        if (!$result['ok']) {
            return [
                'success' => false,
                'error' => $result['error'],
            ];
        }
        return [
            'success' => true,
        ];
    }

    public function delete(int $id): array
    {
        $result = $this->membershipPlanRepository->delete($id);
        if (!$result['ok']) {
            return $this->failedResponse($result['error']);
        }
        return $this->successResponse($result['data']);
    }

    public function findAll(PagerViewDto $dto): array
    {
        $result = $this->membershipPlanRepository->findAll($dto);
        if (!$result['ok']) {
            return [
                'success' => false,
                'data' => $result['data'],
                'error' => $result['error'],
            ];
        }

        return [
            'success' => true,
            'data' => $result['data'],
            'error' => null,
        ];
    }

    public function findAllActivePlans(): array
    {
        $result = $this->membershipPlanRepository->findAllActivePlans();
        if (!$result['ok']) {
            return [
                'success' => false,
                'error' => $result['error'],
            ];
        }

        return [
            'success' => true,
            'data' => $result['data'],
        ];
    }

    // starting to create service for API
    public function findAllAdmin(PagerViewDto $dto): array
    {
        $result = $this->membershipPlanRepository->findAll($dto);
        if (!$result['ok']) {
            return [
                'ok' => false,
                'error' => $result['error'],
                'data' => null,
            ];
        }

        return [
            'ok' => true,
            'error' => null,
            'data' => AdminViewDto::collection($result['data']),
        ];
    }

    public function findAllPublic(PagerViewDto $dto): array
    {
        $result = $this->membershipPlanRepository->findAll($dto);
        if (!$result['ok']) {
            return [
                'ok' => false,
                'error' => $result['error'],
                'data' => null,
            ];
        }

        return [
            'ok' => true,
            'error' => null,
            'data' => PublicViewDto::collection($result['data']),
            // 'data' => $result['data'],
        ];
    }

    public function findActivePlanByIDAdmin(int $planId): array
    {
        $result = $this->membershipPlanRepository->findActivePlan($planId);
        if (!$result['ok']) {
            return [
                'ok' => false,
                'error' => $result['error'],
                'data' => null,
            ];
        }
        return [
            'ok' => true,
            'error' => null,
            'data' => AdminViewDto::array($result['data']),
        ];
    }

    public function findActivePlanByIDPublic(int $planId): array
    {
        $result = $this->membershipPlanRepository->findActivePlan($planId);
        if (!$result['ok']) {
            return [
                'ok' => false,
                'error' => $result['error'],
                'data' => null,
            ];
        }
        return [
            'ok' => true,
            'error' => null,
            'data' => PublicViewDto::array($result['data']),
        ];
    }

    public function findByIdAdmin(int $planId): array
    {
        $result = $this->membershipPlanRepository->getMembershipPlanById($planId);
        if (!$result['ok']) {
            return [
                'ok' => false,
                'error' => $result['error'],
                'data' => $result['data'],
            ];
        }

        return [
            'ok' => true,
            'error' => null,
            'data' => AdminViewDto::array($result['data']),
        ];
    }

    public function findByIdPublic(int $planId): array
    {
        $result = $this->membershipPlanRepository->getMembershipPlanById($planId);
        if (!$result['ok']) {
            return [
                'ok' => false,
                'error' => $result['error'],
                'data' => $result['data'],
            ];
        }

        return [
            'ok' => true,
            'error' => null,
            'data' => PublicViewDto::array($result['data']),
        ];
    }

    public function findByCodeAdmin(string $code): array
    {
        $result = $this->membershipPlanRepository->findByCode($code);
        if (!$result['ok']) {
            return [
                'ok' => false,
                'error' => $result['error'],
                'data' => null,
            ];
        }

        return [
            'ok' => true,
            'data' => AdminViewDto::array($result['data']),
            'error' => null,
        ];
    }

    public function findByCodePublic(string $code): array
    {
        $result = $this->membershipPlanRepository->findByCode($code);
        if (!$result['ok']) {
            return [
                'ok' => false,
                'error' => $result['error'],
                'data' => null,
            ];
        }

        return [
            'ok' => true,
            'data' => PublicViewDto::array($result['data']),
            'error' => null,
        ];
    }
}