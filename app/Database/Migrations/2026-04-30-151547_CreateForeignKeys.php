<?php

namespace App\Database\MigrationTemplates;

use CodeIgniter\Database\Migration;

class CreateForeignKeys extends Migration
{
    public function up()
    {
        $db = db_connect();

        // Pastikan semua tabel parent ada
        if (! $db->tableExists('users') || ! $db->tableExists('remember_tokens')) {
            return;
        }

        // Add FK remember_tokens.user_id -> users.id
        $db->query("
            ALTER TABLE remember_tokens
            ADD CONSTRAINT fk_remember_tokens_user
            FOREIGN KEY (user_id)
            REFERENCES users(id)
            ON DELETE CASCADE
            ON UPDATE CASCADE
        ");
    }

    public function down()
    {
        $db = db_connect();

        // Drop FK jika ada
        $db->query("
            ALTER TABLE remember_tokens
            DROP FOREIGN KEY fk_remember_tokens_user
        ");
    }
}
