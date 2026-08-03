<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateUserStatusAndRoleName extends Migration
{
    public function up()
    {
        // 1. Update role name for ID 4 to 'Anggota'
        $this->db->table('roles')->where('id', 4)->update(['name' => 'Anggota']);

        // 2. Modify users status column to VARCHAR(30)
        $this->forge->modifyColumn('users', [
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'active',
            ],
        ]);
    }

    public function down()
    {
        $this->db->table('roles')->where('id', 4)->update(['name' => 'Member']);
    }
}
