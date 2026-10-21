<?php 

namespace App\Database\Seeds;
use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run()
    {
        $this->call('PageSettingsSeeder');
        // $this->call('MembershipSeeder');
        $this->call('UserSeeder');
        $this->call('ContactSeeder');
        $this->call('QuoteSeeder');
        $this->call('MembershipPlanSeeder');
    }
}