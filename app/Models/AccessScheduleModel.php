<?php

namespace App\Models;

use CodeIgniter\Model;

class AccessScheduleModel extends Model
{
    protected $table      = 'access_schedules';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'name',
        'day_of_week',
        'start_time',
        'end_time',
        'allowed_gender',
        'access_type',
        'capacity_limit',
        'priority',
        'is_active',
    ];

    protected $validationRules = [
        'name' => [
            'label' => 'Schedule name',
            'rules' => 'required|max_length[100]',
        ],

        'day_of_week' => [
            'label' => 'Day',
            'rules' => 'required|in_list[MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY,SUNDAY]',
        ],

        'start_time' => [
            'label' => 'Start time',
            'rules' => 'required',
        ],

        'end_time' => [
            'label' => 'End time',
            'rules' => 'required',
        ],

        'allowed_gender' => [
            'label' => 'Allowed gender',
            'rules' => 'required|in_list[ALL,MALE,FEMALE]',
        ],

        'access_type' => [
            'label' => 'Access type',
            'rules' => 'required|in_list[ALL,MEMBER,CASUAL]',
        ],

        'capacity_limit' => [
            'label' => 'Capacity limit',
            'rules' => 'required|integer|greater_than_equal_to[0]',
        ],

        'priority' => [
            'label' => 'Priority',
            'rules' => 'required|integer|greater_than_equal_to[0]',
        ],

        'is_active' => [
            'label' => 'Status',
            'rules' => 'required|in_list[0,1]',
        ],
    ];

    protected $validationMessages = [
        'capacity_limit' => [
            'greater_than_equal_to' => 'Capacity limit cannot be negative.',
        ],
        'priority' => [
            'greater_than_equal_to' => 'Priority cannot be negative.',
        ],
    ];
}