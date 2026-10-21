<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberModel extends Model
{
    protected $table      = 'members';
    protected $primaryKey = 'id';

    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'name',
        'gender',
        'date_of_birth',
        'phone',
        'email',
        'address',
        'member_type',
        'is_active',
    ];

    /**
     * Ambil hanya member aktif
     */
    public function getActiveMembers()
    {
        return $this->where('is_active', true)->findAll();
    }
}