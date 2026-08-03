<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    private function generateUuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public function run()
    {
        $defaultPassword = password_hash('password123', PASSWORD_BCRYPT);
        $now = date('Y-m-d H:i:s');

        $users = [
            [
                'id'            => 1,
                'member_uuid'   => $this->generateUuid(),
                'role_id'       => 1, // Super Admin
                'username'      => 'superadmin',
                'email'         => 'admin@multimedia-sman1tamansari.sch.id',
                'password_hash' => $defaultPassword,
                'full_name'     => 'Super Administrator MMC',
                'nis_nip'       => '198501012010011001',
                'class_dept'    => 'Pembina Utama & Admin System',
                'phone'         => '081234567890',
                'avatar'        => null,
                'qr_version'    => 1,
                'qr_updated_at' => $now,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id'            => 2,
                'member_uuid'   => $this->generateUuid(),
                'role_id'       => 2, // Pembina
                'username'      => 'pembina',
                'email'         => 'pembina@multimedia-sman1tamansari.sch.id',
                'password_hash' => $defaultPassword,
                'full_name'     => 'Dra. Endang Setyowati, M.Pd',
                'nis_nip'       => '197603122005012004',
                'class_dept'    => 'Pembina Ekstrakurikuler Multimedia',
                'phone'         => '081298765432',
                'avatar'        => null,
                'qr_version'    => 1,
                'qr_updated_at' => $now,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id'            => 3,
                'member_uuid'   => $this->generateUuid(),
                'role_id'       => 3, // BPH
                'username'      => 'bph_ketua',
                'email'         => 'ketua@multimedia-sman1tamansari.sch.id',
                'password_hash' => $defaultPassword,
                'full_name'     => 'Muhammad Rizky Pratama',
                'nis_nip'       => '222310101',
                'class_dept'    => 'XI MIPA 1 (Ketua Club)',
                'phone'         => '085711223344',
                'avatar'        => null,
                'qr_version'    => 1,
                'qr_updated_at' => $now,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id'            => 4,
                'member_uuid'   => 'e8d641fa-8349-4b68-8a8b-112233445566', // Fixed demo UUID for testing member QR scanning
                'role_id'       => 4, // Member
                'username'      => 'rizki_member',
                'email'         => 'rizki@gmail.com',
                'password_hash' => $defaultPassword,
                'full_name'     => 'Rizki Agung Febrian',
                'nis_nip'       => '222310102',
                'class_dept'    => 'XI MIPA 2 (Divisi Videography)',
                'phone'         => '088122334455',
                'avatar'        => null,
                'qr_version'    => 1,
                'qr_updated_at' => $now,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id'            => 5,
                'member_uuid'   => 'a1b2c3d4-e5f6-4789-8012-3456789abcde',
                'role_id'       => 4, // Member
                'username'      => 'adit_member',
                'email'         => 'adit@gmail.com',
                'password_hash' => $defaultPassword,
                'full_name'     => 'Aditya Kurniawan',
                'nis_nip'       => '222310103',
                'class_dept'    => 'XI IPS 1 (Divisi Photography)',
                'phone'         => '088133445566',
                'avatar'        => null,
                'qr_version'    => 1,
                'qr_updated_at' => $now,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id'            => 6,
                'member_uuid'   => 'f9e8d7c6-b5a4-4321-9876-543210fedcba',
                'role_id'       => 4, // Member
                'username'      => 'fajar_member',
                'email'         => 'fajar@gmail.com',
                'password_hash' => $defaultPassword,
                'full_name'     => 'Fajar Nugraha',
                'nis_nip'       => '222310104',
                'class_dept'    => 'XI MIPA 3 (Divisi Graphic Design)',
                'phone'         => '088144556677',
                'avatar'        => null,
                'qr_version'    => 1,
                'qr_updated_at' => $now,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ];

        $this->db->table('users')->ignore(true)->insertBatch($users);
    }
}
