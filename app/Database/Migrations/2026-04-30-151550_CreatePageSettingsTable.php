<?php

namespace App\Database\MigrationTemplate;

use CodeIgniter\Database\Migration;
USE Config\Database;

class CreatePageSettingsTable extends Migration {
    public function up()
    {
        $db = Database::connect();

        if (in_array('page_settings', $db->listTables(), true)) {
            return;
        }
        
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'page' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'settings_json' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'updatedAt' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id');
        $this->forge->createTable('page_settings');
    }
    public function down()
    {
        $this->forge->dropTable('page_settings',true);
    }
}