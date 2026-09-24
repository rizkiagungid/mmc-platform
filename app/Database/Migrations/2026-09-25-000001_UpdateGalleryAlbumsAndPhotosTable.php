<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateGalleryAlbumsAndPhotosTable extends Migration
{
    public function up()
    {
        // Add columns to gallery_albums
        $albumFields = [
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default'    => 'Kegiatan',
                'after'      => 'title',
            ],
            'event_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'category',
            ],
            'external_link' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => true,
                'after'      => 'description',
            ],
            'external_link_title' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'external_link',
            ],
            'media_files' => [
                'type' => 'LONGTEXT',
                'null' => true,
                'after' => 'cover_image',
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'external_link_title',
            ],
        ];

        // Only add if column doesn't exist
        foreach ($albumFields as $col => $def) {
            if (!$this->db->fieldExists($col, 'gallery_albums')) {
                $this->forge->addColumn('gallery_albums', [$col => $def]);
            }
        }

        // Add media_type to gallery_photos
        if ($this->db->tableExists('gallery_photos')) {
            if (!$this->db->fieldExists('media_type', 'gallery_photos')) {
                $this->forge->addColumn('gallery_photos', [
                    'media_type' => [
                        'type'       => 'VARCHAR',
                        'constraint' => '20',
                        'default'    => 'image',
                        'after'      => 'image_url',
                    ],
                ]);
            }
        }
    }

    public function down()
    {
        $cols = ['category', 'event_date', 'external_link', 'external_link_title', 'media_files', 'created_by'];
        foreach ($cols as $c) {
            if ($this->db->fieldExists($c, 'gallery_albums')) {
                $this->forge->dropColumn('gallery_albums', $c);
            }
        }

        if ($this->db->tableExists('gallery_photos') && $this->db->fieldExists('media_type', 'gallery_photos')) {
            $this->forge->dropColumn('gallery_photos', 'media_type');
        }
    }
}
