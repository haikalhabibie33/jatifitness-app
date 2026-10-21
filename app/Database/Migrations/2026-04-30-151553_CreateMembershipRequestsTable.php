<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMembershipRequestsTable extends Migration
{
    
public function up()
    {
        $this->forge->addField([

            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'corrected_from_request_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ],

            // =============================
            // ACCESS TOKEN
            // =============================
            'access_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'unique'     => true,
            ],

            // =============================
            // REQUEST TYPE
            // =============================
            'request_type' => [
                'type'       => 'ENUM',
                'constraint' => ['CASUAL', 'MEMBER'],
            ],

            'target_member_type' => [
                'type'       => 'ENUM',
                'constraint' => ['REGULAR', 'STUDENT'],
            ],

            // =============================
            // PLAN SNAPSHOT
            // =============================
            'membership_plan_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'plan_name_snapshot' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'plan_code_snapshot' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],

            'price_snapshot' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => true,
            ],

            'total_price_snapshot' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => true,
            ],
            'daily_limit_snapshot' => [
                'type' => 'INT'
            ],
            'duration_snapshot' => [
                'type' => 'INT',
            ],

            'quantity' => [
                'type'    => 'INT',
                'default' => 1,
            ],

            // =============================
            // ✅ PAYMENT META (FIXED)
            // =============================
            'payment_source' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],

            'payment_reference' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'paid_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            // =============================
            // APPLICANT DATA
            // =============================
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'gender' => [
                'type'       => 'ENUM',
                'constraint' => ['MALE', 'FEMALE'],
                'null'       => true,
            ],

            'date_of_birth' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],

            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'address' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            // =============================
            // STATUS
            // =============================
            'status' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'SUBMITTED',
                    'APPROVED',
                    'REJECTED',
                    'PAID',
                    'COMPLETED',
                    'REFUNDED',
                ],
                'default' => 'SUBMITTED',
            ],

            'approval_required' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],

            // =============================
            // AUDIT
            // =============================
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
        $this->forge->addKey('corrected_from_request_id');
        $this->forge->addKey('status');
        $this->forge->addKey('request_type');

        $this->forge->createTable('membership_requests');
    }

    public function down()
    {
        $this->forge->dropTable('membership_requests');
    }
}
