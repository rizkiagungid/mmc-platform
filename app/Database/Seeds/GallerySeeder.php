<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $exists = $db->table('gallery_albums')->countAllResults();
        if ($exists === 0) {
            $db->table('gallery_albums')->insert([
                'title'               => 'Workshop Sinematografi & Lighting Indoor 2026',
                'category'            => 'Workshop',
                'event_date'          => '2026-09-20',
                'slug'                => 'workshop-sinematografi-lighting-indoor-2026',
                'cover_image'         => 'assets/logo-mm-2023.png',
                'media_files'         => json_encode([
                    ['url' => 'assets/logo-mm-2023.png', 'type' => 'image', 'name' => 'Dokumentasi Lighting.png'],
                    ['url' => 'assets/logo-mm-2023.png', 'type' => 'image', 'name' => 'Praktek Kamera.png'],
                ]),
                'description'         => 'Pelatihan intensif teknik pencahayaan 3-point lighting dan pengoperasian kamera sinema untuk produksi konten video pendek sekolah bersama mentor multimedia.',
                'external_link'       => 'https://drive.google.com',
                'external_link_title' => 'Google Drive Dokumentasi Lengkap',
                'created_at'          => date('Y-m-d H:i:s'),
                'updated_at'          => date('Y-m-d H:i:s'),
            ]);
            $albumId = $db->insertID();

            $db->table('gallery_photos')->insert([
                'album_id'    => $albumId,
                'image_url'   => 'assets/logo-mm-2023.png',
                'media_type'  => 'image',
                'title'       => 'Dokumentasi Lighting',
                'caption'     => 'Setup 3-point lighting workshop',
                'is_featured' => 1,
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
