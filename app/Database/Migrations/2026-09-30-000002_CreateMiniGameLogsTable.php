<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMiniGameLogsTable extends Migration
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
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'game_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'level' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'score' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'stars' => [
                'type'       => 'TINYINT',
                'constraint' => 2,
                'default'    => 1,
            ],
            'points_awarded' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 5,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('created_at');
        $this->forge->addKey('game_id');
        $this->forge->createTable('mini_game_logs', true);
    }

    public function down()
    {
        $this->forge->dropTable('mini_game_logs', true);
    }
}
