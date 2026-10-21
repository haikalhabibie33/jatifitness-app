<?php

namespace App\DTO\MembershipPlan;

final readonly class CreatePlanDto
{
    public function __construct(
        public string $code,
        public string $name,
        public string $price,
        public string $durationDays,
        public string $description,
        public string $targetMemberType,
        public string $audienceScope,
        public string $dailyLimit,
    )
    {}

    public static function fromPost(array $post): self
    {
        return new self (
            code: strtoupper($post['code']),
            name: $post['name'],
            price: $post['price'],
            durationDays: $post['duration_days'],
            description: $post['description'],
            targetMemberType: $post['target_member_type'],
            audienceScope: $post['audience_scope'],
            dailyLimit:$post['daily_limit'],
        );
    }
}