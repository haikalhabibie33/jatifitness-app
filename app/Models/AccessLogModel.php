<?php

namespace App\Models;

use CodeIgniter\Model;

class AccessLogModel extends Model
{
    protected $table = 'access_logs';

    protected $allowedFields = [
        'access_token',
        'type',
        'ref_id',
        'check_in_at',
        'check_out_at',
        'created_at',
    ];

    protected $useTimestamps = false;
}
