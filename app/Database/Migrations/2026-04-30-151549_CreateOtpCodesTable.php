<?php 

namespace App\Database\MigrationTemplates;

use CodeIgniter\Database\Migration;

class CreateOtpCodesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'identifier' => [
                'type' => 'VARCHAR',
                'constraint' => 191,
            ],
            'otp_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'purpose' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'attempts' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'is_used' => [
                'type' => 'BOOLEAN',
            ],
            'expires_at' => [
                'type' => 'DATETIME',
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['user_id', 'purpose', 'is_used']);
        $this->forge->addKey('expires_at');

        $this->forge->createTable('otp_codes');
    }
    public function down()
    {
        $this->forge->dropTable('otp_codes');
    }
    
}
