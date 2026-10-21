<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMembershipsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([

            // ===================================
            // PRIMARY KEY
            // ===================================
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            // ===================================
            // SOURCE REQUEST (IMPORTANT)
            // ===================================
            'membership_request_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'comment'  => 'Source membership_request',
            ],

            // ===================================
            // MEMBER (OWNER)
            // ===================================
            'member_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'comment'  => 'Owner of this membership',
            ],

            // ===================================
            // PLAN REFERENCE (SNAPSHOT SAFE)
            // ===================================
            'membership_plan_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'plan_name_snapshot' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'price_snapshot' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'daily_limit' => [
                'type' => 'INT',
            ],
            // ===================================
            // LIFECYCLE
            // ===================================
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['REGISTERED', 'ACTIVE', 'EXPIRED', 'SUSPENDED', 'CANCELLED'],
                'default'    => 'REGISTERED',
                'comment'    => 'REGISTERED = future / stacked',
            ],

            // ===================================
            // PERIOD
            // ===================================
            'start_date' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'end_date' => [
                'type' => 'DATE',
                'null' => true
            ],

            // ===================================
            // AUDIT
            // ===================================
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        // ===================================
        // INDEXES (CRITICAL)
        // ===================================
        $this->forge->addKey('id', true);
        $this->forge->addKey('membership_request_id');
        $this->forge->addKey('member_id');
        $this->forge->addKey('status');
        $this->forge->addKey(['member_id', 'status']); // 🔥 stacking logic

        $this->forge->createTable('memberships');
    }

    public function down()
    {
        $this->forge->dropTable('memberships');
    }
}