<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProfileFieldsToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'address' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'phone',
            ],
            'birth_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'address',
            ],
            'social_instagram' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'birth_date',
            ],
            'social_linkedin' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'social_instagram',
            ],
            'social_github' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'social_linkedin',
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['address', 'birth_date', 'social_instagram', 'social_linkedin', 'social_github']);
    }
}
