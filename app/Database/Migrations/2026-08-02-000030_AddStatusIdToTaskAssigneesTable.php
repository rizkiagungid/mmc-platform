<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusIdToTaskAssigneesTable extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('status_id', 'task_assignees')) {
            $fields = [
                'status_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'default'    => 1, // 1 = Todo / Belum Dikerjakan
                    'after'      => 'user_id',
                ],
            ];
            $this->forge->addColumn('task_assignees', $fields);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('status_id', 'task_assignees')) {
            $this->forge->dropColumn('task_assignees', 'status_id');
        }
    }
}
