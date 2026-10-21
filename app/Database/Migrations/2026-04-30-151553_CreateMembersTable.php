<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMembersTable extends Migration
  {
      
  public function up()
    {
      $this->forge->addField([
          'id' => [
              'type'           => 'INT',
              'unsigned'       => true,
              'auto_increment' => true,
          ],

          // IDENTITY
          'name' => [
              'type'       => 'VARCHAR',
              'constraint' => 100,
          ],

          'gender' => [
              'type'       => 'ENUM',
              'constraint' => ['MALE', 'FEMALE'],
              'null'       => true,
          ],

          'date_of_birth' => [
              'type' => 'DATE',
              'null' => true,
          ],

          // CONTACT (UNIQUE IDENTITY)
          'phone' => [
              'type'       => 'VARCHAR',
              'constraint' => 30,
              'null'       => true,
          ],

          'email' => [
              'type'       => 'VARCHAR',
              'constraint' => 100,
              'null'       => true,
          ],

          'address' => [
              'type' => 'TEXT',
              'null' => true,
          ],

          // SEGMENTATION
          'member_type' => [
              'type'       => 'ENUM',
              'constraint' => ['REGULAR', 'STUDENT'],
              'default'    => 'REGULAR',
          ],

          // SOFT STATUS
          'is_active' => [
              'type'    => 'BOOLEAN',
              'default' => true,
          ],
          'identity_key' => [
            'type' => 'VARCHAR',
            'constraint' => 255,
            'null' => false,
          ],

          // AUDIT
          'created_at' => [
              'type' => 'DATETIME',
              'null' => true,
          ],

          'updated_at' => [
              'type' => 'DATETIME',
              'null' => true,
          ],

          // OPTIONAL SOFT DELETE (RECOMMENDED)
          'deleted_at' => [
              'type' => 'DATETIME',
              'null' => true,
          ],
      ]);

      // PRIMARY
      $this->forge->addKey('id', true);

      $this->forge->createTable('members');
    }

    public function down()
    {
      $this->forge->dropTable('members');
    }

  }