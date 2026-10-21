<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdatePaymentMetaInMembershipRequests extends Migration
{
    public function up()
    {
        /*
         * Normalize existing data before changing payment_source to ENUM.
         * This prevents migration errors if old rows contain NULL or unexpected values.
         */
        $this->db->query("
            UPDATE membership_requests
            SET payment_source = 'OFFLINE'
            WHERE payment_source IS NULL
               OR payment_source NOT IN ('OFFLINE', 'ONLINE', 'SYSTEM')
        ");

        $this->forge->modifyColumn('membership_requests', [
            'payment_source' => [
                'type'       => 'ENUM',
                'constraint' => ['OFFLINE', 'ONLINE', 'SYSTEM'],
                'default'    => 'OFFLINE',
                'null'       => false,
            ],
        ]);

        $this->forge->addColumn('membership_requests', [
            'payment_method' => [
                'type'       => 'ENUM',
                'constraint' => ['CASH', 'TRANSFER', 'QRIS', 'SIMULATION'],
                'default'    => 'CASH',
                'null'       => false,
                'after'      => 'payment_source',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('membership_requests', 'payment_method');

        $this->forge->modifyColumn('membership_requests', [
            'payment_source' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'default'    => null,
            ],
        ]);
    }
}