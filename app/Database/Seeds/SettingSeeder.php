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
            // Footer & Social Settings
            ['setting_key' => 'footer_brand_name', 'setting_value' => 'MMC SMAN 1 Tamansari', 'updated_at' => $now],
            ['setting_key' => 'footer_about', 'setting_value' => 'Wadah kreativitas siswa SMAN 1 Tamansari dalam bidang videografi, fotografi, desain grafis, pemrograman web, dan penyiaran media digital.', 'updated_at' => $now],
            ['setting_key' => 'footer_nav_title', 'setting_value' => 'Navigasi Cepat', 'updated_at' => $now],
            ['setting_key' => 'footer_contact_title', 'setting_value' => 'Kontak & Lokasi', 'updated_at' => $now],
            ['setting_key' => 'footer_address', 'setting_value' => 'SMAN 1 Tamansari, Kab. Bogor', 'updated_at' => $now],
            ['setting_key' => 'footer_email', 'setting_value' => 'multimediasman1t@gmail.com', 'updated_at' => $now],
            ['setting_key' => 'footer_phone', 'setting_value' => '+62 812-3456-7890', 'updated_at' => $now],
            ['setting_key' => 'social_instagram', 'setting_value' => '#', 'updated_at' => $now],
            ['setting_key' => 'social_youtube', 'setting_value' => '#', 'updated_at' => $now],
            ['setting_key' => 'social_tiktok', 'setting_value' => '#', 'updated_at' => $now],
            ['setting_key' => 'social_github', 'setting_value' => '#', 'updated_at' => $now],
            ['setting_key' => 'footer_copyright', 'setting_value' => '&copy; {year} Multimedia Club SMAN 1 Tamansari. Built with CodeIgniter 4 & Dark SaaS UI.', 'updated_at' => $now],
        ];

        $this->db->table('settings')->ignore(true)->insertBatch($settings);
    }
}
