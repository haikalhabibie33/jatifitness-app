<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInvoiceNoAndExpiredAtToTransactions extends Migration
{
    public function up()
    {
        $this->forge->addColumn('transactions', [
            'invoice_no' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'id',
            ],

            'expired_at' => [
                'type'  => 'DATETIME',
                'null'  => true,
                'after' => 'paid_at',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('transactions', [
            'invoice_no',
            'expired_at',
        ]);
    }
}