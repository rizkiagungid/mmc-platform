<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTaskSubmissionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'task_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'submission_text' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'attachment_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'status_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'feedback' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'grade' => [
                'type'       => 'INT',
                'constraint' => 5,
                'null'       => true,
            ],
            'evaluated_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'submitted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('task_id', 'tasks', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('status_id', 'task_statuses', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('evaluated_by', 'users', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('task_submissions');
    }

    public function down()
    {
        $this->forge->dropTable('task_submissions');
    }
}
