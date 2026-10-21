<?php

namespace App\DTO\Member;

use App\DTO\ViewDtoInterface;
use App\Traits\DtoCollectionTrait;

final readonly class AdminViewListDTO implements ViewDtoInterface
{
    use DtoCollectionTrait;

    public function __construct(
        public int $id,
        public string $name,
        public string $gender,
        public string $dateOfBirth,
        public string $phone,
        public string $email,
        public string $address,
        public string $memberType,
        public int $isActive,
        public string $identityKey,
        public string $createdAt,
        public string $updatedAt
    )
    {}

    protected static function fromArray(array $member): static
    {
        return new static(
            id: (int) $member['id'],
            name:$member['name'],
            gender:$member['gender'],
            dateOfBirth:$member['date_of_birth'],
            phone:$member['phone'],
            email:$member['email'],
            address:$member['address'],
            memberType:$member['member_type'],
            isActive:(int) $member['is_active'],
            identityKey: $member['identity_key'],
            createdAt:$member['created_at'],
            updatedAt:$member['updated_at']
        );
    }

    protected function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'gender' => $this->gender,
            'date_of_birth' => $this->dateOfBirth,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'member_type' => $this->memberType,
            'is_active' => $this->isActive,
            'identity_key' => $this->identityKey,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}