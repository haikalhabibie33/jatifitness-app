<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMemberAccess extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'membership_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'access_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'unique'     => true,
            ],

            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['ACTIVE', 'SUSPENDED', 'REVOKED'],
                'default'    => 'ACTIVE',
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->createTable('member_access');
    }

    public function down()
    {
        $this->forge->dropTable('member_access', true);
    }
}