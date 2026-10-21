<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentBankAccountsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'bank_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'account_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'account_holder' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],

            'is_active' => [
                'type'    => 'TINYINT',
                'default' => 1,
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

        $this->forge->createTable('payment_bank_accounts');
    }

    public function down()
    {
        $this->forge->dropTable('payment_bank_accounts');
    }
}