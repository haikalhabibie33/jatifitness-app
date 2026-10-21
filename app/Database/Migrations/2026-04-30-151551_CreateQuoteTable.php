<?php

namespace App\Database\MigrationTemplates;

use CodeIgniter\Database\Migration;
USE cONFIG\Database;

class CreateQuoteTable extends Migration {
    public function up(){

        $db = Database::connect();

        if (in_array('quote', $db->listTables(), true)) {
            return;
        }

        $this->forge->addField([
            'id' => ['type'=> 'INT', 'unsigned' => true, 'auto_increment' => true],
            'author' => ['type' => 'VARCHAR', 'constraint' => 255],
            'role' => ['type' => 'VARCHAR', 'constraint' => 100],
            'text' => ['type' => 'TEXT'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('quote');
    }

    public function down(){
        $this->forge->dropTable('quote');
    }
}