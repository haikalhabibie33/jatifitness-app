<?php

namespace App\Models;

use CodeIgniter\Model;

class HistoryTransactionModel extends Model
{
    protected $table = 'history_transactions';

    protected $allowedFields = [
        'transaction_id',
        'event',
        'note',
    ];

    protected $useTimestamps = true;
}