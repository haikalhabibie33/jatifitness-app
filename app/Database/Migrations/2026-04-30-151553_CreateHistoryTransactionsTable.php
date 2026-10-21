<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHistoryTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([

            // ===============================
            // PRIMARY KEY
            // ===============================
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            // ===============================
            // RELATION
            // ===============================
            'transaction_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'comment'  => 'Reference to transactions table',
            ],

            // ===============================
            // EVENT
            // ===============================
            'event' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'CREATED',
                    'PAID',
                    'FAILED',
                    'REFUND_REQUESTED',
                    'REFUNDED',
                ],
            ],

            // ===============================
            // METADATA
            // ===============================
            'note' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Optional note (reason, actor, gateway response)',
            ],

            // ===============================
            // AUDIT
            // ===============================
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        // ===============================
        // INDEXES
        // ===============================
        $this->forge->addKey('id', true);
        $this->forge->addKey('transaction_id');
        $this->forge->addKey('event');
        $this->forge->addKey('created_at');

        $this->forge->createTable('history_transactions');
    }

    public function down()
    {
        $this->forge->dropTable('history_transactions');
    }
}