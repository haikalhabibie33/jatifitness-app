<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUsageLimitToCasualPasses extends Migration
{
    public function up()
    {
        $this->forge->addColumn('casual_passes', [
            'usage_limit' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'default'    => 1,
                'null'       => false,
                'after'      => 'daily_limit',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('casual_passes', 'usage_limit');
    }
}