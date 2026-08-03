<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CmsSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // 1. Website Settings
        $settings = [
            ['setting_key' => 'site_title', 'setting_value' => 'Multimedia Club SMAN 1 Tamansari', 'setting_group' => 'general'],
            ['setting_key' => 'tagline', 'setting_value' => 'Wadah Kreativitas & Inovasi Teknologi Media Digital', 'setting_group' => 'general'],
            ['setting_key' => 'contact_email', 'setting_value' => 'multimedia@sman1tamansari.sch.id', 'setting_group' => 'contact'],
            ['setting_key' => 'contact_phone', 'setting_value' => '+62 812-3456-7890', 'setting_group' => 'contact'],
            ['setting_key' => 'school_address', 'setting_value' => 'Jl. Raya Tamansari No. 1, Kab. Bogor, Jawa Barat', 'setting_group' => 'contact'],
            ['setting_key' => 'maps_url', 'setting_value' => 'https://maps.google.com/?q=SMAN+1+Tamansari', 'setting_group' => 'contact'],
            ['setting_key' => 'footer_copyright', 'setting_value' => '© 2026 Multimedia Club SMAN 1 Tamansari. Built with CodeIgniter 4.', 'setting_group' => 'general'],
        ];
        foreach ($settings as $s) {
            $s['created_at'] = date('Y-m-d H:i:s');
            $db->table('website_settings')->ignore(true)->insert($s);
        }

        // 2. Homepage Sections Builder
        $sections = [
            ['section_key' => 'hero', 'name' => 'Hero Banner', 'sort_order' => 1, 'is_active' => 1],
            ['section_key' => 'stats', 'name' => 'Statistics Counter', 'sort_order' => 2, 'is_active' => 1],
            ['section_key' => 'welcome', 'name' => 'Welcome Statement', 'sort_order' => 3, 'is_active' => 1],
            ['section_key' => 'divisions', 'name' => 'Divisions Showcase', 'sort_order' => 4, 'is_active' => 1],
            ['section_key' => 'portfolio', 'name' => 'Featured Portfolio', 'sort_order' => 5, 'is_active' => 1],
            ['section_key' => 'gallery', 'name' => 'Photo Gallery', 'sort_order' => 6, 'is_active' => 1],
            ['section_key' => 'faq', 'name' => 'FAQ Accordion', 'sort_order' => 7, 'is_active' => 1],
        ];
        foreach ($sections as $sec) {
            $sec['created_at'] = date('Y-m-d H:i:s');
            $db->table('homepage_sections')->ignore(true)->insert($sec);
        }

        // 3. Homepage Stats
        $stats = [
            ['label' => 'Total Anggota', 'value' => '120', 'icon' => 'fa-users', 'suffix' => '+', 'sort_order' => 1, 'is_active' => 1],
            ['label' => 'Tahun Berdiri', 'value' => '2017', 'icon' => 'fa-calendar', 'sort_order' => 2, 'is_active' => 1],
            ['label' => 'Penghargaan Juara', 'value' => '15', 'icon' => 'fa-trophy', 'suffix' => '+', 'sort_order' => 3, 'is_active' => 1],
            ['label' => 'Proyek Karya', 'value' => '45', 'icon' => 'fa-video', 'suffix' => '+', 'sort_order' => 4, 'is_active' => 1],
        ];
        foreach ($stats as $st) {
            $st['created_at'] = date('Y-m-d H:i:s');
            $db->table('homepage_stats')->ignore(true)->insert($st);
        }

        // 4. Hero Section
        $db->table('hero_sections')->ignore(true)->insert([
            'title'              => 'Eksplorasi Kreativitas Digital Tanpa Batas',
            'subtitle'           => 'Ekstrakurikuler Multimedia SMAN 1 Tamansari',
            'description'        => 'Wadah kolaborasi karya videografi, fotografi, desain grafis, broadcasting, dan web development bagi generasi emas masa depan.',
            'primary_btn_text'   => 'Gabung Sekarang',
            'primary_btn_url'    => '/register',
            'secondary_btn_text' => 'Lihat Karya',
            'secondary_btn_url'  => '/portfolio',
            'created_at'         => date('Y-m-d H:i:s'),
        ]);

        // 5. Divisions
        $divisions = [
            [
                'slug' => 'broadcasting',
                'name' => 'Broadcasting & Live Streaming',
                'icon' => 'fa-tower-broadcast',
                'short_description' => 'Produksi siaran langsung event sekolah, liputan berita MMC News, dan manajemen streaming multi-kamera.',
                'full_description' => 'Divisi Broadcasting berfokus pada penguasaan alur produksi liputan berita, streaming studio, tata suara audio podcast, dan penyiaran sinyal video berkualitas tinggi.',
                'sort_order' => 1,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'slug' => 'programming',
                'name' => 'Programming & Web Development',
                'icon' => 'fa-code',
                'short_description' => 'Pengembangan website platform, aplikasi interaktif, dan logika pemrograman modern.',
                'full_description' => 'Divisi Programming membekali anggota dengan fondasi HTML, CSS, JavaScript, PHP, CodeIgniter, serta arsitektur database untuk membangun produk digital profesional.',
                'sort_order' => 2,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'slug' => 'videography',
                'name' => 'Videography & Color Grading',
                'icon' => 'fa-film',
                'short_description' => 'Teknik pengambilan gambar sinematik, penyutradaraan short film, dan color grading DaVinci Resolve.',
                'full_description' => 'Divisi Videografi melatih teknik angle kamera, lighting studio, penyusunan storyboard, editing video Premiere Pro, dan pengolahan warna sinematik.',
                'sort_order' => 3,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'slug' => 'photography',
                'name' => 'Photography & Lighting',
                'icon' => 'fa-camera-retro',
                'short_description' => 'Eksplorasi segitiga eksposisi, fotografi jurnalistik, dan portraiture studio.',
                'full_description' => 'Divisi Fotografi mendalami komposisi visual, pengaturan shutter speed & aperture, pengerjaan retouching Photoshop, dan liputan acara resmi sekolah.',
                'sort_order' => 4,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];
        foreach ($divisions as $d) {
            $db->table('divisions')->ignore(true)->insert($d);
        }

        // 6. FAQs
        $faqs = [
            ['question' => 'Siapa saja yang boleh bergabung dengan Multimedia Club?', 'answer' => 'Seluruh siswa-siswi SMAN 1 Tamansari dari semua tingkatan kelas dan jurusan dapat mendaftar sebagai anggota aktif.', 'sort_order' => 1, 'status' => 'active', 'created_at' => date('Y-m-d H:i:s')],
            ['question' => 'Apakah harus memiliki kamera atau laptop sendiri?', 'answer' => 'Tidak wajib. Klub menyediakan fasilitas alat laboratorium komputer, kamera studio, dan perangkat audio yang dapat digunakan bersama saat latihan rutin.', 'sort_order' => 2, 'status' => 'active', 'created_at' => date('Y-m-d H:i:s')],
            ['question' => 'Kapan jadwal latihan rutin dilaksanakan?', 'answer' => 'Latihan rutin dilaksanakan 2 kali seminggu setiap hari Selasa dan Jumat sore sepulang sekolah di Lab Komputer 2 SMAN 1 Tamansari.', 'sort_order' => 3, 'status' => 'active', 'created_at' => date('Y-m-d H:i:s')],
        ];
        foreach ($faqs as $fq) {
            $db->table('faqs')->ignore(true)->insert($fq);
        }
    }
}
