<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentBankAccountModel extends Model
{
    protected $table = 'payment_bank_accounts';

    protected $allowedFields = [
        'bank_name',
        'account_number',
        'account_holder',
        'is_active',
    ];

    protected $useTimestamps = true;
}