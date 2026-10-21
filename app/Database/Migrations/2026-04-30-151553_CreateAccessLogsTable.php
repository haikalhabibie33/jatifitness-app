<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAccessLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([

            // =========================
            // PRIMARY KEY
            // =========================
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            // =========================
            // ACCESS SOURCE
            // =========================
            'access_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
            ],

            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['MEMBER', 'CASUAL'],
                'comment'    => 'Source type',
            ],

            'ref_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'comment'  => 'membership_id or casual_pass_id',
            ],

            // =========================
            // ACTIVITY
            // =========================
            'check_in_at' => [
                'type' => 'DATETIME',
            ],

            // (optional future upgrade)
            'check_out_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            // =========================
            // AUDIT
            // =========================
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['access_token']);
        $this->forge->addKey(['type', 'ref_id']);

        $this->forge->createTable('access_logs');
    }

    public function down()
    {
        $this->forge->dropTable('access_logs', true);
    }
}