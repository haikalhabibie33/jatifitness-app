<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCasualPassesTable extends Migration
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
            // SOURCE REQUEST (SINGLE SOURCE OF TRUTH)
            // ===================================
            'membership_request_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'comment'  => 'Origin membership request',
            ],

            // ===================================
            // ACCESS TOKEN (QR / CODE)
            // ===================================
            'access_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'unique'     => true,
                'comment'    => 'QR or manual access code',
            ],

            // ===================================
            // ACTIVATION & EXPIRY
            // ===================================
            'activated_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'First successful scan',
            ],

            'expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'daily_limit' => [
                'type' => 'INT',
                'default' => 1,
            ],
            // ===================================
            // STATUS ACCESS
            // ===================================
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['UNUSED', 'ACTIVE', 'EXPIRED', 'REVOKED'],
                'default'    => 'UNUSED',
            ],
            'revoked_at' => [
                'type' => 'DATETIME',
                'null' => true,
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
        // INDEXES
        // ===================================
        $this->forge->addKey('id', true);
        $this->forge->addKey('membership_request_id');
        $this->forge->addKey('status');

        $this->forge->createTable('casual_passes');
    }

    public function down()
    {
        $this->forge->dropTable('casual_passes');
    }
}
