<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRepostsCountAndBookmarksCountToPostsTable extends Migration
{
    public function up()
    {
        $fields = [
            'reposts_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'comments_count',
            ],
            'bookmarks_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'reposts_count',
            ],
        ];

        $this->forge->addColumn('posts', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('posts', ['reposts_count', 'bookmarks_count']);
    }
}
