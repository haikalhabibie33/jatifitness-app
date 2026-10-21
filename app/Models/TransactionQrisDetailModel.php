<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionQrisDetailModel extends Model
{
    protected $table = 'transaction_qris_details';

    protected $allowedFields = [
        'transaction_id',

        'provider',
        'external_id',
        'provider_reference',

        'qris_type',
        'qr_string',
        'qris_payload',
        'qris_image_url',

        'amount',
        'currency',

        'gateway_status',
        'paid_at',
        'expired_at',

        'callback_payload',
        'last_callback_at',
    ];

    protected $useTimestamps = true;
}