<?php

namespace App\DTO\MembershipRequest;

final readonly class SubmitMemberDto
{
    public function __construct(
        public int $planId,
        public string $targetType,
        public string $name,
        public string $phone,
        public string $email,
        public string $gender,
        public string $dateOfBirth,
        public string $address,
        public string $paymentMethod,
    )
    {}

    public static function fromPost(array $post): self
    {
        return new self(
            planId: (int) $post['membership_plan'],
            targetType: (string) $post['target_type'],
            name: trim((string)$post['name']),
            phone: trim((string)$post['phone']),
            email: trim((string)$post['email']),
            gender: (string) $post['gender'],
            dateOfBirth:  (string) $post['date_of_birth'],
            address: trim((string)$post['address']),
            paymentMethod:(string) $post['payment_method'],
        );
    }
}