<?php

namespace App\DTO\Checkin;

final readonly class AccessHistoryQueryDto
{
    public function __construct(
        public string $search,
        public string $type,
        public ?string $from,
        public ?string $to,
        public int $page,
        public int $limit,
    ) {}

    public static function fromQuery(array $query): self
    {
        return new self(
            search: trim((string) ($query['search'] ?? '')),
            type: strtolower(trim((string) ($query['type'] ?? 'all'))),
            from: !empty($query['from']) ? (string) $query['from'] : null,
            to: !empty($query['to']) ? (string) $query['to'] : null,
            page: max(1, (int) ($query['page'] ?? 1)),
            limit: max(1, min(100, (int) ($query['limit'] ?? 10))),
        );
    }

    public function offset(): int
    {
        return ($this->page - 1) * $this->limit;
    }

    public function toFilterArray(): array
    {
        return [
            'search' => $this->search,
            'type' => $this->type,
            'from' => $this->from,
            'to' => $this->to,
        ];
    }
}