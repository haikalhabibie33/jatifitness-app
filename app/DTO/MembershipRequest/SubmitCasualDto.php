<?php 

namespace App\DTO\MembershipRequest;

final readonly class SubmitCasualDTO 
{
    public function __construct(
        public int $planId,
        public string $targetType,
        public int $quantity,
        public string $name,
        public string $phone,
        public string $email,
        public string $gender,
        public string $paymentMethod,
    )
    {}

    public static function fromPost(array $post): self
    {
        return new self(
            planId: (int) $post['membership_plan'],
            quantity: (int) $post['quantity'],
            targetType: $post['target_type'],
            name: trim((string)$post['name']),
            phone: trim((string)$post['phone']),
            email: trim((string)$post['email']),
            gender: trim((string)$post['gender']),
            paymentMethod: (string) $post['payment_method'],
        );
    }
}