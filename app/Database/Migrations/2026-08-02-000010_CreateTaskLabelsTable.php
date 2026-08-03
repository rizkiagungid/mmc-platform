<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTaskLabelsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'task_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'label_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
        ]);
        $this->forge->addKey(['task_id', 'label_id'], true);
        $this->forge->addForeignKey('task_id', 'tasks', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('label_id', 'labels', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('task_labels');
    }

    public function down()
    {
        $this->forge->dropTable('task_labels');
    }
}
