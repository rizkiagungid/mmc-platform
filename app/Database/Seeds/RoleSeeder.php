<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id'          => 1,
                'name'        => 'Super Admin',
                'slug'        => 'superadmin',
                'description' => 'Akses penuh ke seluruh sistem dan konfigurasi.',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'id'          => 2,
                'name'        => 'Pembina',
                'slug'        => 'pembina',
                'description' => 'Mengelola kegiatan, absensi, tugas, dan evaluasi anggota.',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'id'          => 3,
                'name'        => 'BPH',
                'slug'        => 'bph',
                'description' => 'Badan Pengurus Harian: kelola absensi, pertemuan, dan tugas harian.',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'id'          => 4,
                'name'        => 'Member',
                'slug'        => 'member',
                'description' => 'Anggota klub: presensi QR/PIN, pengumpulan tugas, dan profil.',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('roles')->ignore(true)->insertBatch($data);
    }
}
