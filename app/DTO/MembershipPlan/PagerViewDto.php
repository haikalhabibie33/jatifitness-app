<?php

namespace App\DTO\MembershipPlan;

final readonly class PagerViewDto
{
    public function __construct(
        public string $search,
        public string $target,
        public string $scope,
        public string $status,
        public int $limit,
        public int $page,
    )
    {}

    public static function fromGet(array $get): self
    {
        return new self(
            search:(string) ($get['search'] ?? ''),
            target:(string) ($get['target'] ?? ''),
            scope:(string) ($get['scope'] ?? ''),
            status:(string) ($get['status'] ?? ''),
            limit:max(1, min(100, (int) ($get['limit'] ?? 10))),
            page:max(1, (int) ($get['page'] ?? 1))
        );
    }
}