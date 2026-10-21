<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';

    protected $allowedFields = [
        'membership_request_id',
        'membership_plan_id',

        'invoice_no',

        'purchase_type',

        'target_member_type_snapshot',

        'plan_code_snapshot',
        'plan_name_snapshot',
        'price_snapshot',
        'duration_days_snapshot',

        'quantity',
        'total_amount',

        'payment_method',
        'payment_status',
        'paid_at',
        'expired_at',

        'refund_amount',
        'refunded_at',
        'corrected_from_transaction_id',
    ];

    protected $useTimestamps = true;
}