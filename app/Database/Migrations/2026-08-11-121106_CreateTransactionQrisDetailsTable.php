<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionQrisDetailsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'transaction_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'provider' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],

            'external_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],

            'provider_reference' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],

            'qris_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'DYNAMIC',
            ],

            'qr_string' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'qris_payload' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'qris_image_url' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
            ],

            'currency' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'default'    => 'IDR',
            ],

            'gateway_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'PENDING',
            ],

            'paid_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'expired_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'callback_payload' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],

            'last_callback_at' => [
                'type' => 'DATETIME',
                'null' => true,
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
        $this->forge->addKey('transaction_id');
        $this->forge->addKey('external_id');
        $this->forge->addKey('provider_reference');

        $this->forge->createTable('transaction_qris_details');
    }

    public function down()
    {
        $this->forge->dropTable('transaction_qris_details');
    }
}