<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAlumniRequestToUsersTable extends Migration
{
    public function up()
    {
        $fields = [
            'alumni_request_status' => [
                'type'       => 'ENUM',
                'constraint' => ['none', 'pending', 'approved', 'rejected'],
                'default'    => 'none',
                'after'      => 'status',
            ],
            'alumni_requested_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'after'      => 'alumni_request_status',
            ],
            'alumni_request_notes' => [
                'type'       => 'TEXT',
                'null'       => true,
                'after'      => 'alumni_requested_at',
            ],
        ];

        if (!$this->db->fieldExists('alumni_request_status', 'users')) {
            $this->forge->addColumn('users', $fields);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('alumni_request_status', 'users')) {
            $this->forge->dropColumn('users', ['alumni_request_status', 'alumni_requested_at', 'alumni_request_notes']);
        }
    }
}
