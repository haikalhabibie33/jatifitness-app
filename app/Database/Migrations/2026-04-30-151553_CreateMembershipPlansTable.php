<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMembershipPlansTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],

            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
            ],

            'duration_days' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],

            'is_active' => [
                'type'       => 'BOOLEAN',
                'default'    => true,
            ],

            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'target_member_type' => [
                'type'       => 'ENUM',
                'constraint' => ['REGULAR', 'STUDENT', 'ALL'],
                'default'    => 'REGULAR',
            ],
            
            'audience_scope' => [
                'type'       => 'ENUM',
                'constraint' => ['MEMBER_ONLY', 'CASUAL_ONLY', 'BOTH'],
                'default'    => 'BOTH',
                'after'      => 'target_member_type',
                'comment'    => 'Who can purchase this plan',
            ],

            'daily_limit' => [
                'type' => 'INT',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('code');

        $this->forge->createTable('membership_plans');
    }

    public function down()
    {
        $this->forge->dropTable('membership_plans');
    }
}