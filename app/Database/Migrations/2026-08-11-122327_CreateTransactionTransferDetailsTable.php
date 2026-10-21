<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionTransferDetailsTable extends Migration
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

            /*
             * Rekening tujuan transfer.
             * Sengaja snapshot supaya kalau rekening admin berubah,
             * histori transaksi lama tetap aman.
             */
            'bank_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'account_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'account_holder' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],

            /*
             * Data pengirim.
             */
            'sender_bank' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'sender_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],

            /*
             * Bukti transfer.
             */
            'proof_image_path' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'uploaded_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            /*
             * Verifikasi admin.
             */
            'verified_by' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'verified_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'verification_note' => [
                'type' => 'TEXT',
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
        $this->forge->addKey('verified_by');

        $this->forge->createTable('transaction_transfer_details');
    }

    public function down()
    {
        $this->forge->dropTable('transaction_transfer_details');
    }
}