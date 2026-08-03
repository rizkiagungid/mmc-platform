<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        // Remove dummy test data
        $db->table('achievements')->where('title', 'fsdfsdf')->delete();

        $sampleAchievements = [
            [
                'id'          => 1,
                'title'       => 'Film Pendek "Jejak Sang Sineas"',
                'competition' => 'FLS2N Bidang Film Pendek 2025',
                'organizer'   => 'Kemendikbudristek RI',
                'category'    => 'Tingkat Nasional',
                'award'       => 'Juara 1 / Emas',
                'event_date'  => '2025-10-15',
                'description' => 'Karya film pendek dokumenter fiksi yang mengangkat keindahan seni budaya lokal dan kearifan lokal kaki Gunung Salak Tamansari.',
                'is_featured' => 1,
                'created_at'  => $now,
            ],
            [
                'id'          => 2,
                'title'       => 'Desain Identitas Visual & Campaign Digital',
                'competition' => 'LKS SMK & SMA Bidang Graphic Design 2025',
                'organizer'   => 'Dinas Pendidikan Jawa Barat',
                'category'    => 'Tingkat Provinsi',
                'award'       => 'Juara 1 Utama',
                'event_date'  => '2025-08-20',
                'description' => 'Pengembangan sistem branding identitas visual digital dan materi kampanye lingkungan hidup digital.',
                'is_featured' => 1,
                'created_at'  => $now,
            ],
            [
                'id'          => 3,
                'title'       => 'Video Dokumenter "Pesona Tamansari"',
                'competition' => 'Lomba Video Kreatif Pelajar 2025',
                'organizer'   => 'Pemerintah Kabupaten Bogor',
                'category'    => 'Tingkat Kabupaten/Kota',
                'award'       => 'Juara 2 / Perak',
                'event_date'  => '2025-05-10',
                'description' => 'Cinematic short video liputan kebudayaan, potensi pariwisata, dan kreasi kriya siswa di Tamansari.',
                'is_featured' => 1,
                'created_at'  => $now,
            ],
            [
                'id'          => 4,
                'title'       => 'Web App Platform Interactive Learning MMC',
                'competition' => 'National Hackathon Student Tech 2025',
                'organizer'   => 'Universitas Indonesia',
                'category'    => 'Tingkat Nasional',
                'award'       => 'Juara Best Innovation',
                'event_date'  => '2025-11-28',
                'description' => 'Inovasi platform web interaktif berbasis CI4 untuk manajemen presensi QR dan pembelajaran mandiri multimedia.',
                'is_featured' => 1,
                'created_at'  => $now,
            ]
        ];

        foreach ($sampleAchievements as $ach) {
            $db->table('achievements')->ignore(true)->insert($ach);
        }

        // Team members
        $members = [
            ['achievement_id' => 1, 'user_id' => 4, 'role_in_team' => 'Sutradara & Editor'],
            ['achievement_id' => 1, 'user_id' => 5, 'role_in_team' => 'Kameramen'],
            ['achievement_id' => 1, 'user_id' => 6, 'role_in_team' => 'Penulis Naskah'],
            ['achievement_id' => 2, 'user_id' => 6, 'role_in_team' => 'Desainer Utama'],
            ['achievement_id' => 3, 'user_id' => 4, 'role_in_team' => 'Videografer'],
            ['achievement_id' => 3, 'user_id' => 5, 'role_in_team' => 'Fotografer & Drone Pilot'],
            ['achievement_id' => 4, 'user_id' => 3, 'role_in_team' => 'Team Leader'],
            ['achievement_id' => 4, 'user_id' => 4, 'role_in_team' => 'Fullstack Developer'],
        ];

        foreach ($members as $m) {
            $m['created_at'] = $now;
            $db->table('achievement_members')->ignore(true)->insert($m);
        }
    }
}
