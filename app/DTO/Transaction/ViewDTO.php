<?php

namespace App\DTO\Transaction;

final readonly class ViewDTO
{
    public function __construct(
        public string $search,
        public string $method,
        public string $status,
        public string $from,
        public string $to,
        public int $limit,
        public int $page,
    )
    {}

    public static function fromGet(array $get): self
    {
        return new self(
            search: (string) ($get['search'] ?? ''),
            method:(string) ($get['method'] ?? ''),
            status:(string) ($get['status'] ?? ''),
            from:(string) ($get['from'] ?? ''),
            to:(string) ($get['to'] ?? ''),
            limit: max(1, min(100, (int) ($get['limit'] ?? 10))),
            page:max(1, (int) ($get['page'] ?? 1))
        );
    }
}