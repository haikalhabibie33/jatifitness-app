<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\MembershipModel;

class MembershipCron extends BaseCommand
{
    protected $group       = 'Membership';
    protected $name        = 'membership:cron';
    protected $description = 'Auto activate REGISTERED memberships and expire ACTIVE ones';

    public function run(array $params)
    {
        $model = new MembershipModel();

        $activated = $model->activateToday();
        $expired   = $model->expireEnded();

        CLI::write('Membership cron executed', 'green');
        CLI::write('Activated REGISTERED → ACTIVE: ' . ($activated ? 'YES' : 'NO'), 'yellow');
        CLI::write('Expired ACTIVE → EXPIRED: ' . ($expired ? 'YES' : 'NO'), 'yellow');
    }
}

// Tambahkan ini nanti di linux
// 0 0 * * * /usr/bin/php /path/to/project/public/index.php membership:cron >/dev/null 2>&1