<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLinkToNotificationsTable extends Migration
{
    public function up()
    {
        $fields = [
            'link' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'type',
            ],
        ];
        $this->forge->addColumn('notifications', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('notifications', 'link');
    }
}
