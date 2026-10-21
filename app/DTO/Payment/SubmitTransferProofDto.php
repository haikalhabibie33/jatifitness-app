<?php

namespace App\DTO\Payment;

final readonly class SubmitTransferProofDto
{
    public function __construct(
        public int $transactionId,
        public string $senderBank,
        public string $senderName,
        public string $proofImagePath,
    ) {}

    public static function fromPost(array $post, string $proofImagePath): self
    {
        return new self(
            transactionId: (int) $post['transaction_id'],
            senderBank: trim((string) $post['sender_bank']),
            senderName: trim((string) $post['sender_name']),
            proofImagePath: $proofImagePath,
        );
    }
}
