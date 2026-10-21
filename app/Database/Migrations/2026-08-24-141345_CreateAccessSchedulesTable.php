<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAccessSchedulesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'day_of_week' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'MONDAY',
                    'TUESDAY',
                    'WEDNESDAY',
                    'THURSDAY',
                    'FRIDAY',
                    'SATURDAY',
                    'SUNDAY',
                ],
            ],

            'start_time' => [
                'type' => 'TIME',
            ],

            'end_time' => [
                'type' => 'TIME',
            ],

            'allowed_gender' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'ALL',
                    'MALE',
                    'FEMALE',
                ],
                'default' => 'ALL',
            ],

            'access_type' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'ALL',
                    'MEMBER',
                    'CASUAL',
                ],
                'default' => 'ALL',
            ],

            /*
             * 0 berarti tidak dibatasi.
             * Nilai di atas 0 adalah maksimal sesi terbuka bersamaan.
             */
            'capacity_limit' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'default'    => 0,
            ],

            /*
             * Jadwal dengan priority tertinggi dipilih
             * jika terdapat beberapa jadwal yang cocok.
             */
            'priority' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'default'    => 0,
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

        $this->forge->addKey([
            'day_of_week',
            'is_active',
        ]);

        $this->forge->addKey([
            'start_time',
            'end_time',
        ]);

        $this->forge->addKey('priority');

        $this->forge->createTable('access_schedules');
    }

    public function down()
    {
        $this->forge->dropTable('access_schedules');
    }
}