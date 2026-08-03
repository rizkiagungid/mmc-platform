<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('RoleSeeder');
        $this->call('TaskMasterSeeder');
        $this->call('UserSeeder');
        $this->call('SettingSeeder');
        $this->call('SampleDataSeeder');
    }
}
