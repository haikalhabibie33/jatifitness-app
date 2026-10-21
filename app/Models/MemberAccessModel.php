<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberAccessModel extends Model
{
    protected $table      = 'member_access';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'membership_id',
        'access_token',
        'status',
    ];

    protected $useTimestamps = true;
}