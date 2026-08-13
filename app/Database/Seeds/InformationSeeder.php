<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InformationSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $user = $db->table('users')->get()->getFirstRow('array');
        if (!$user) return;

        $db->table('informations')->insertBatch([
            [
                'title'       => 'Selamat Datang di Portal Informasi Baru MMC SMAN 1 Tamansari',
                'category'    => 'Informasi Ekskul',
                'description' => 'Seluruh pengumuman resmi ekstrakurikuler, informasi kegiatan, dan pembaruan penting klub multimedia sekarang dapat diakses secara langsung melalui Pusat Informasi MMC ini.',
                'date_time'   => date('Y-m-d H:i:s'),
                'created_by'  => $user['id'],
                'is_popup'    => 1,
                'status'      => 'active',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'title'       => 'Modul Pembelajaran & Silabus Divisi Programming Update',
                'category'    => 'Divisi Programming',
                'description' => 'Materi silabus koding web dan latihan algoritma dasar telah diperbarui. Anggota divisi programming diharapkan mengecek menu materi.',
                'date_time'   => date('Y-m-d H:i:s', strtotime('-1 day')),
                'created_by'  => $user['id'],
                'is_popup'    => 0,
                'status'      => 'active',
                'created_at'  => date('Y-m-d H:i:s', strtotime('-1 day')),
                'updated_at'  => date('Y-m-d H:i:s', strtotime('-1 day')),
            ]
        ]);
    }
}
