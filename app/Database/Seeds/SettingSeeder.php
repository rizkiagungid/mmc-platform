<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $settings = [
            ['setting_key' => 'site_title', 'setting_value' => 'Multimedia Club SMAN 1 Tamansari', 'updated_at' => $now],
            ['setting_key' => 'site_tagline', 'setting_value' => 'Creative Tech & Visual Media Hub', 'updated_at' => $now],
            ['setting_key' => 'club_email', 'setting_value' => 'multimedia@sman1tamansari.sch.id', 'updated_at' => $now],
            ['setting_key' => 'club_phone', 'setting_value' => '+62 812-3456-7890', 'updated_at' => $now],
            ['setting_key' => 'club_address', 'setting_value' => 'Jl. Taman Sari No. 1, Tamansari, Kab. Bogor', 'updated_at' => $now],
            ['setting_key' => 'registration_open', 'setting_value' => '1', 'updated_at' => $now],
            ['setting_key' => 'maintenance_mode', 'setting_value' => '0', 'updated_at' => $now],
        ];

        $this->db->table('settings')->ignore(true)->insertBatch($settings);
    }
}
