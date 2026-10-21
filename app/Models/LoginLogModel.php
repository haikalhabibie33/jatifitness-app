<?php 

namespace App\Models;

use CodeIgniter\Model;

class LoginLogModel extends Model
{
    protected $table = 'login_logs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'ip_address',
        'user_agent',
        'location',
        'login_type',
        'status',
        'created_at'
    ];
}