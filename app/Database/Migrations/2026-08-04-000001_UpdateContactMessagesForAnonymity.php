<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateContactMessagesForAnonymity extends Migration
{
    public function up()
    {
        $fields = [
            'is_anonymous' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'after'      => 'message',
            ],
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'Kritik & Saran',
                'after'      => 'subject',
            ],
        ];

        $this->forge->addColumn('contact_messages', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('contact_messages', ['is_anonymous', 'category']);
    }
}
