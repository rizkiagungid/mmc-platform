<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAlumniRole extends Migration
{
    public function up()
    {
        $existing = $this->db->table('roles')->where('slug', 'alumni')->get()->getRowArray();
        if (!$existing) {
            $this->db->table('roles')->insert([
                'name'        => 'Alumni',
                'slug'        => 'alumni',
                'description' => 'Alumni klub: akses portal alumni, riwayat kegiatan, dan komunitas ekskul.',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public function down()
    {
        $this->db->table('roles')->where('slug', 'alumni')->delete();
    }
}
