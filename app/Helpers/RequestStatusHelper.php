<?php

namespace App\Helpers;

class RequestStatusHelper
{
    protected array $request;
    protected ?string $adminPhone;

    public function __construct(array $request, ?string $adminPhone = null)
    {
        $this->request = $request;
        $this->adminPhone = $adminPhone;
    }

    /* ======================================================
     * APPROVAL FLOW
     * ====================================================== */

    public function isWaitingApproval(): bool
    {
        return !empty($this->request['approval_required'])
            && $this->request['status'] === 'SUBMITTED';
    }

    public function isApproved(): bool
    {
        return !empty($this->request['approval_required'])
            && $this->request['status'] === 'APPROVED';
    }

    /* ======================================================
     * PAYMENT FLOW (CASUAL & MEMBER)
     * ====================================================== */

    /**
     * ✅ Ready to pay:
     * - Member REGULAR (no approval)
     * - Member STUDENT that already APPROVED
     */
    public function isReadyPayment(): bool
    {
        // no approval required
        if (empty($this->request['approval_required'])) {
            return $this->request['status'] === 'SUBMITTED';
        }

        // approval required
        return $this->request['status'] === 'APPROVED';
    }

    public function isPaid(): bool
    {
        return $this->request['status'] === 'PAID';
    }

    public function isCompleted(): bool
    {
        return $this->request['status'] === 'COMPLETED';
    }

    public function isRejected(): bool
    {
        return $this->request['status'] === 'REJECTED';
    }

    /* ======================================================
     * MEMBER SPECIFIC
     * ====================================================== */

    public function isMemberRequest(): bool
    {
        return $this->request['request_type'] === 'MEMBERSHIP';
    }

    public function isCasualRequest(): bool
    {
        return $this->request['request_type'] === 'CASUAL';
    }

    /* ======================================================
    * PAYMENT METHOD / SOURCE
    * ====================================================== */

    public function paymentSource(): string
    {
        return strtoupper((string) ($this->request['payment_source'] ?? 'OFFLINE'));
    }

    public function paymentMethod(): string
    {
        return strtoupper((string) ($this->request['payment_method'] ?? 'CASH'));
    }

    public function isOfflinePayment(): bool
    {
        return $this->paymentSource() === 'OFFLINE';
    }

    public function isOnlinePayment(): bool
    {
        return $this->paymentSource() === 'ONLINE';
    }

    public function isCashPayment(): bool
    {
        return $this->paymentMethod() === 'CASH';
    }

    public function isTransferPayment(): bool
    {
        return $this->paymentMethod() === 'TRANSFER';
    }

    public function isQrisPayment(): bool
    {
        return $this->paymentMethod() === 'QRIS';
    }

    /**
     * QR only exists AFTER COMPLETED
     */
    public function hasAccessQr(): bool
    {
        return $this->isCompleted();
    }

    /* ======================================================
     * WHATSAPP CTA (MEMBER APPROVAL)
     * ====================================================== */

    public function hasWhatsAppApprovalCTA(): bool
    {
        return $this->isWaitingApproval() && !empty($this->adminPhone);
    }

    public function getWhatsAppApprovalLink(): string
    {
        if (!$this->hasWhatsAppApprovalCTA()) {
            return '';
        }

        $phone = preg_replace('/\D+/', '', $this->adminPhone);

        $message = rawurlencode(
            "Hello Admin,\n\n"
          . "My membership request is currently *Waiting for Approval*.\n"
          . "I would like to submit my student ID for verification.\n\n"
          . "Name: {$this->request['name']}\n"
          . "Phone: {$this->request['phone']}\n"
          . "Email: {$this->request['email']}\n\n"
          . "[Please attach photo of your student ID after sending this message]"
        );

        return "https://wa.me/{$phone}?text={$message}";
    }
}