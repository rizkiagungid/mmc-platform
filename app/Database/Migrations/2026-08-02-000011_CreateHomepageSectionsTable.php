<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHomepageSectionsTable extends Migration
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
            'section_key' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'bg_color' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'transparent',
            ],
            'bg_image' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'container_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'container',
            ],
            'padding_top' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'py-5',
            ],
            'padding_bottom' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'py-5',
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        $this->forge->createTable('homepage_sections', true);
    }

    public function down()
    {
        $this->forge->dropTable('homepage_sections', true);
    }
}
