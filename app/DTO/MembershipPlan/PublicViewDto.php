<?php

namespace App\DTO\MembershipPlan;

use App\DTO\ViewDtoInterface;
use App\Traits\DtoCollectionTrait;

final readonly class PublicViewDto implements ViewDtoInterface
{
    use DtoCollectionTrait;

    public function __construct(
        public int $id,
        public string $code,
        public string $name,
        public float $price,
        public int $durationDays,
        public int $isActive,
        public ?string $description,
        public string $targetMemberType,
        public string $audienceScope,
        public int $dailyLimit
    )
    {}
    
    protected static function fromArray(array $plan): static
    {
        return new static(
            id: (int) $plan['id'],
            code: $plan['code'],
            name:$plan['name'],
            price: (float) $plan['price'],
            durationDays: (int) $plan['duration_days'],
            isActive: (int) $plan['is_active'],
            description: $plan['description'] ?? null,
            targetMemberType: $plan['target_member_type'],
            audienceScope: $plan['audience_scope'],
            dailyLimit: (int) $plan['daily_limit']
        );
    }

    protected function toArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name'=> $this->name,
            'price' => $this->price,
            'duration_days' => $this->durationDays,
            'is_active'=> $this->isActive,
            'description' => $this->description,
            'target_member_type' => $this->targetMemberType,
            'audience_scope'=> $this->audienceScope,
            'daily_limit'=> $this->dailyLimit,
        ];
    }

    // public static function array(array $plan):array
    // {
    //     return self::fromArray($plan)->toArray();
    // }

    // public static function collection(array $plans): array
    // {
    //     return array_map(
    //         fn (array $plan) => self::fromArray($plan)->toArray(),
    //         $plans
    //     );
    // }
}