<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionTransferDetailModel extends Model
{
    protected $table = 'transaction_transfer_details';

    protected $allowedFields = [
        'transaction_id',

        'bank_name',
        'account_number',
        'account_holder',

        'sender_bank',
        'sender_name',

        'proof_image_path',
        'uploaded_at',

        'verified_by',
        'verified_at',
        'verification_note',
    ];

    protected $useTimestamps = true;
}