<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateSocialFieldsToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'social_tiktok' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'social_instagram',
            ],
            'social_facebook' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'social_tiktok',
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['social_tiktok', 'social_facebook']);
    }
}
