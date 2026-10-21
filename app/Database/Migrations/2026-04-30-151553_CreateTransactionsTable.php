<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionsTable extends Migration
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
            // RELATIONS
            // ===============================
            'membership_request_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'comment'  => 'Origin membership request',
            ],

            'membership_plan_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
                'comment'  => 'Original plan reference (historical)',
            ],
            // ===============================
            // REFUND UTILITIES
            // ===============================
            'corrected_from_transaction_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'comment' => 'Reference to old transaction that was corrected/refunded'
            ],

            // ===============================
            // PURCHASE TYPE
            // ===============================
            'purchase_type' => [
                'type'       => 'ENUM',
                'constraint' => ['CASUAL', 'MEMBER'],
                'comment'    => 'Type of purchase',
            ],

            // ===============================
            // SNAPSHOT: TARGET & AUDIENCE
            // ===============================
            'target_member_type_snapshot' => [
                'type'       => 'ENUM',
                'constraint' => ['STUDENT', 'REGULAR'],
            ],
            // ===============================
            // SNAPSHOT: PRODUCT
            // ===============================
            'plan_code_snapshot' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],

            'plan_name_snapshot' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],

            'price_snapshot' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => true,
            ],

            'duration_days_snapshot' => [
                'type'    => 'INT',
                'null'    => true,
                'comment' => 'Duration in days (membership or daily casual)',
            ],

            // ===============================
            // CASUAL ONLY
            // ===============================
            'quantity' => [
                'type'    => 'INT',
                'default' => 1,
                'comment' => 'Number of days (casual)',
            ],

            // ===============================
            // FINANCIAL
            // ===============================
            'total_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => true,
            ],

            'payment_method' => [
                'type'       => 'ENUM',
                'constraint' => ['QRIS', 'CASH', 'TRANSFER'],
                'default'    => 'QRIS',
            ],

            'payment_status' => [
                'type'       => 'ENUM',
                'constraint' => ['PENDING', 'SUCCESS', 'FAILED', 'REFUNDED'],
                'default'    => 'PENDING',
            ],

            'paid_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Timestamp when payment is successful',
            ],
            'refund_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'null' => true,
            ],
            'refunded_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            // ===============================
            // AUDIT
            // ===============================
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        // ===============================
        // INDEXES
        // ===============================
        $this->forge->addKey('id', true);
        $this->forge->addKey('membership_request_id');
        $this->forge->addKey('corrected_from_transaction_id');
        $this->forge->addKey('purchase_type');
        $this->forge->addKey('payment_status');
        $this->forge->addKey('paid_at');
        $this->forge->addKey('refunded_at');

        $this->forge->createTable('transactions');
    }

    public function down()
    {
        $this->forge->dropTable('transactions');
    }
}