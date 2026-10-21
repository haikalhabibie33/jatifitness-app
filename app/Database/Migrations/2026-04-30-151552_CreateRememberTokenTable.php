<?php 

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRememberTokenTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'token_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        
        // $this->forge->addForeignKey(
        //     'user_id', 
        //     'users', 
        //     'id', 
        //     'CASCADE', 
        //     'CASCADE');

        $this->forge->createTable('remember_tokens', true, [
            'ENGINE' => 'InnoDB',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('remember_tokens');
    }
}