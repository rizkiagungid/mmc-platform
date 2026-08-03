<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateContactRepliesTable extends Migration
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
            'contact_message_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'sender_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'admin', // 'admin' or 'visitor'
            ],
            'sender_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'message' => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('contact_message_id', 'contact_messages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('contact_replies', true);
    }

    public function down()
    {
        $this->forge->dropTable('contact_replies', true);
    }
}
