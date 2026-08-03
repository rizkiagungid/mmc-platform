<?php

namespace App\Modules\Learning\Services;

use App\Services\BaseService;
use App\Models\AuditLogModel;
use App\Modules\Learning\Models\LearningMaterialModel;
use App\Modules\Learning\Models\LearningTagModel;
use App\Modules\Learning\Models\LearningRevisionModel;

class LearningService extends BaseService
{
    protected LearningMaterialModel $materialModel;
    protected LearningTagModel $tagModel;
    protected LearningRevisionModel $revisionModel;
    protected $auditLogModel;

    public function __construct()
    {
        parent::__construct();
        $this->materialModel = new LearningMaterialModel();
        $this->tagModel      = new LearningTagModel();
        $this->revisionModel = new LearningRevisionModel();
        $this->auditLogModel = new AuditLogModel();
        $this->ensureTablesExist();
    }

    private function ensureTablesExist()
    {
        if (!$this->db->tableExists('learning_materials')) {
            $forge = \Config\Database::forge();
            
            $forge->addField([
                'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'title'          => ['type' => 'VARCHAR', 'constraint' => '255'],
                'slug'           => ['type' => 'VARCHAR', 'constraint' => '255', 'unique' => true],
                'excerpt'        => ['type' => 'TEXT', 'null' => true],
                'content'        => ['type' => 'LONGTEXT', 'null' => true],
                'thumbnail'      => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
                'banner'         => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
                'division_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
                'category'       => ['type' => 'VARCHAR', 'constraint' => '100', 'default' => 'Tutorial'],
                'author_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
                'status'         => ['type' => 'ENUM', 'constraint' => ['draft', 'published', 'archived'], 'default' => 'draft'],
                'visibility'     => ['type' => 'ENUM', 'constraint' => ['public', 'member'], 'default' => 'public'],
                'is_featured'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
                'reading_time'   => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
                'views_count'    => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
                'last_viewed_at' => ['type' => 'DATETIME', 'null' => true],
                'published_at'   => ['type' => 'DATETIME', 'null' => true],
                'created_at'     => ['type' => 'DATETIME', 'null' => true],
                'updated_at'     => ['type' => 'DATETIME', 'null' => true],
                'deleted_at'     => ['type' => 'DATETIME', 'null' => true],
            ]);
            $forge->addKey('id', true);
            $forge->createTable('learning_materials', true);
        }

        if (!$this->db->tableExists('learning_tags')) {
            $forge = \Config\Database::forge();
            $forge->addField([
                'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'name'       => ['type' => 'VARCHAR', 'constraint' => '100'],
                'slug'       => ['type' => 'VARCHAR', 'constraint' => '100', 'unique' => true],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $forge->addKey('id', true);
            $forge->createTable('learning_tags', true);
        }

        if (!$this->db->tableExists('learning_material_tags')) {
            $forge = \Config\Database::forge();
            $forge->addField([
                'material_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
                'tag_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            ]);
            $forge->addKey(['material_id', 'tag_id'], true);
            $forge->createTable('learning_material_tags', true);
        }

        if (!$this->db->tableExists('learning_material_revisions')) {
            $forge = \Config\Database::forge();
            $forge->addField([
                'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'material_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
                'edited_by'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
                'title'       => ['type' => 'VARCHAR', 'constraint' => '255'],
                'excerpt'     => ['type' => 'TEXT', 'null' => true],
                'content'     => ['type' => 'LONGTEXT', 'null' => true],
                'summary'     => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
                'created_at'  => ['type' => 'DATETIME', 'null' => true],
            ]);
            $forge->addKey('id', true);
            $forge->createTable('learning_material_revisions', true);
        }

        // Check missing columns in learning_materials table
        if ($this->db->tableExists('learning_materials')) {
            $fields = $this->db->getFieldNames('learning_materials');
            $forge = \Config\Database::forge();
            if (!in_array('attachments', $fields)) {
                $forge->addColumn('learning_materials', [
                    'attachments' => ['type' => 'LONGTEXT', 'null' => true]
                ]);
            }
            if (!in_array('total_downloads', $fields)) {
                $forge->addColumn('learning_materials', [
                    'total_downloads' => ['type' => 'INT', 'constraint' => 11, 'default' => 0]
                ]);
            }
            if (!in_array('avg_completion_rate', $fields)) {
                $forge->addColumn('learning_materials', [
                    'avg_completion_rate' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00]
                ]);
            }
        }

        // Seed initial sample materials if empty
        if ($this->db->table('learning_materials')->countAllResults() === 0) {
            $now = date('Y-m-d H:i:s');
            $adminUser = $this->db->table('users')->where('role_id', 1)->get()->getRowArray();
            $authorId  = $adminUser ? (int)$adminUser['id'] : 1;

            $initials = [
                [
                    'title'        => 'Fundamental Python & Structuring Code untuk Pemula',
                    'slug'         => 'fundamental-python-structuring-code',
                    'excerpt'      => 'Panduan dasar sintaksis bahasa pemrograman Python, variabel, kontrol alur kondisi, serta best practice menyusun file proyek aplikasi.',
                    'content'      => '<h3>1. Pengenalan Python</h3><p>Python adalah bahasa pemrograman yang serbaguna, populer, dan mudah dipelajari. Pada modul ini, kita akan mempelajari struktur sintaks dasar, tipe data, serta cara membuat modul fungsi mandiri.</p><h3>2. Sintaksis Dasar & Variabel</h3><p>Di bawah ini adalah contoh penulisan variabel dan fungsi print pada Python:</p><pre><code>name = "Multimedia Club"\nyear = 2026\nprint(f"Welcome to {name} {year}")</code></pre><h3>3. Kesimpulan & Tugas Mandiri</h3><p>Pastikan Anda sudah menginstall Python 3.11+ dan menyusun repositori lokal pertama Anda.</p>',
                    'thumbnail'    => 'https://images.unsplash.com/photo-1526379095098-d400fd0bf935?w=600&auto=format&fit=crop&q=80',
                    'banner'       => 'https://images.unsplash.com/photo-1526379095098-d400fd0bf935?w=1200&auto=format&fit=crop&q=80',
                    'division_id'  => 2, // Programming
                    'category'     => 'Fundamental',
                    'author_id'    => $authorId,
                    'status'       => 'published',
                    'visibility'   => 'public',
                    'is_featured'  => 1,
                    'reading_time' => 3,
                    'views_count'  => 142,
                    'published_at' => $now,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ],
                [
                    'title'        => 'Panduan Dasar Penyiaran Broadcast & Setup Live Streaming OBS',
                    'slug'         => 'panduan-dasar-broadcast-setup-obs',
                    'excerpt'      => 'Teknik mengatur kamera, audio mixer, capture card, serta tata alur adegan pada Open Broadcaster Software (OBS Studio) untuk event sekolah.',
                    'content'      => '<h3>1. Pengenalan Alur Penyiaran</h3><p>Divisi Broadcasting bertanggung jawab penuh atas kualitas produksi tayangan langsung pada event SMAN 1 Tamansari.</p><h3>2. Konfigurasi Bitrate & Audio Encoder</h3><p>Gunakan bitrate 4500-6000 Kbps untuk resolusi 1080p60fps dengan standar audio 48kHz Stereo AAC.</p><h3>3. Multi-Camera Scene Management</h3><p>Gunakan tombol shortcut keyboard atau Stream Deck untuk berpindah kamera secara halus dengan transisi stinger.</p>',
                    'thumbnail'    => 'https://images.unsplash.com/photo-1598899134739-24c46f58b8c0?w=600&auto=format&fit=crop&q=80',
                    'banner'       => 'https://images.unsplash.com/photo-1598899134739-24c46f58b8c0?w=1200&auto=format&fit=crop&q=80',
                    'division_id'  => 1, // Broadcasting
                    'category'     => 'Tutorial',
                    'author_id'    => $authorId,
                    'status'       => 'published',
                    'visibility'   => 'public',
                    'is_featured'  => 1,
                    'reading_time' => 4,
                    'views_count'  => 98,
                    'published_at' => $now,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ],
                [
                    'title'        => 'Teknik Layouting & Desain Grafis Profesional dengan Adobe Illustrator',
                    'slug'         => 'teknik-layouting-desain-grafis-illustrator',
                    'excerpt'      => 'Pelajari hierarki visual, pemilihan warna warna terintegrasi, serta pembuatan aset komersial berupa poster, pamflet, dan banner media sosial.',
                    'content'      => '<h3>1. Dasar Hierarki Visual</h3><p>Hierarki mengarahkan pandangan pembaca dari elemen terpenting seperti headline utama hingga ke detail kontak.</p><h3>2. Color Theory & Palette Creation</h3><p>Gunakan aturan 60-30-10 dalam komposisi warna agar poster terasa seimbang dan tidak melelahkan mata pembaca.</p>',
                    'thumbnail'    => 'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=600&auto=format&fit=crop&q=80',
                    'banner'       => 'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=1200&auto=format&fit=crop&q=80',
                    'division_id'  => 3,
                    'category'     => 'Guide',
                    'author_id'    => $authorId,
                    'status'       => 'published',
                    'visibility'   => 'member',
                    'is_featured'  => 0,
                    'reading_time' => 5,
                    'views_count'  => 64,
                    'published_at' => $now,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ],
            ];

            foreach ($initials as $item) {
                $this->db->table('learning_materials')->insert($item);
            }
        }
    }

    public function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        helper('text');
        $baseSlug = url_title($title, '-', true);
        if (empty($baseSlug)) {
            $baseSlug = 'materi-' . time();
        }

        $slug = $baseSlug;
        $counter = 2;

        while (true) {
            $builder = $this->db->table('learning_materials')
                               ->where('slug', $slug);
            if ($ignoreId > 0) {
                $builder->where('id !=', $ignoreId);
            }
            $count = $builder->countAllResults();
            if ($count === 0) {
                break;
            }
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function calculateReadingTime(?string $content): int
    {
        if (empty($content)) {
            return 1;
        }
        $text = strip_tags($content);
        $wordCount = str_word_count($text);
        $minutes = (int)ceil($wordCount / 200);
        return max(1, $minutes);
    }

    public function getAdminMaterials(array $filters = []): array
    {
        $builder = $this->db->table('learning_materials lm')
                           ->select('lm.*, users.full_name as author_name, users.avatar as author_avatar, divisions.name as division_name')
                           ->join('users', 'users.id = lm.author_id', 'left')
                           ->join('divisions', 'divisions.id = lm.division_id', 'left');

        $statusFilter = $filters['status'] ?? 'all';
        if ($statusFilter === 'trash') {
            $builder->where('lm.deleted_at IS NOT NULL');
        } else {
            $builder->where('lm.deleted_at IS NULL');
            if ($statusFilter === 'published') {
                $builder->where('lm.status', 'published')
                        ->where('lm.published_at <=', date('Y-m-d H:i:s'));
            } elseif ($statusFilter === 'scheduled') {
                $builder->where('lm.status', 'published')
                        ->where('lm.published_at >', date('Y-m-d H:i:s'));
            } elseif ($statusFilter === 'draft') {
                $builder->where('lm.status', 'draft');
            } elseif ($statusFilter === 'archived') {
                $builder->where('lm.status', 'archived');
            }
        }

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $builder->groupStart()
                    ->like('lm.title', $s)
                    ->orLike('lm.excerpt', $s)
                    ->orLike('lm.content', $s)
                    ->orLike('lm.category', $s)
                    ->groupEnd();
        }

        if (!empty($filters['division_id'])) {
            $builder->where('lm.division_id', (int)$filters['division_id']);
        }

        if (!empty($filters['category'])) {
            $builder->where('lm.category', $filters['category']);
        }

        $materials = $builder->orderBy('lm.created_at', 'DESC')->get()->getResultArray();

        // Attach tags to each material
        foreach ($materials as &$m) {
            $m['tags'] = $this->getTagsForMaterial($m['id']);
        }

        return $materials;
    }

    public function getPublicMaterials(array $filters = []): array
    {
        $now = date('Y-m-d H:i:s');
        $builder = $this->db->table('learning_materials lm')
                           ->select('lm.*, users.full_name as author_name, users.avatar as author_avatar, divisions.name as division_name')
                           ->join('users', 'users.id = lm.author_id', 'left')
                           ->join('divisions', 'divisions.id = lm.division_id', 'left')
                           ->where('lm.status', 'published')
                           ->where('lm.published_at <=', $now)
                           ->where('lm.deleted_at IS NULL');

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $builder->groupStart()
                    ->like('lm.title', $s)
                    ->orLike('lm.excerpt', $s)
                    ->orLike('lm.content', $s)
                    ->orLike('lm.category', $s)
                    ->orLike('divisions.name', $s)
                    ->groupEnd();
        }

        if (!empty($filters['division_id'])) {
            $builder->where('lm.division_id', (int)$filters['division_id']);
        }

        if (!empty($filters['category'])) {
            $builder->where('lm.category', $filters['category']);
        }

        if (!empty($filters['tag'])) {
            $builder->join('learning_material_tags lmt', 'lmt.material_id = lm.id')
                    ->join('learning_tags lt', 'lt.id = lmt.tag_id')
                    ->where('lt.slug', $filters['tag']);
        }

        $sort = $filters['sort'] ?? 'latest';
        if ($sort === 'popular') {
            $builder->orderBy('lm.views_count', 'DESC');
        } elseif ($sort === 'featured') {
            $builder->where('lm.is_featured', 1)->orderBy('lm.published_at', 'DESC');
        } else {
            $builder->orderBy('lm.published_at', 'DESC');
        }

        $materials = $builder->get()->getResultArray();

        foreach ($materials as &$m) {
            $m['tags'] = $this->getTagsForMaterial($m['id']);
        }

        return $materials;
    }

    public function getMaterialBySlug(string $slug, bool $isPublic = true): ?array
    {
        $builder = $this->db->table('learning_materials lm')
                           ->select('lm.*, users.full_name as author_name, users.avatar as author_avatar, divisions.name as division_name')
                           ->join('users', 'users.id = lm.author_id', 'left')
                           ->join('divisions', 'divisions.id = lm.division_id', 'left')
                           ->where('lm.slug', $slug)
                           ->where('lm.deleted_at IS NULL');

        if ($isPublic) {
            $builder->where('lm.status', 'published')
                    ->where('lm.published_at <=', date('Y-m-d H:i:s'));
        }

        $material = $builder->get()->getRowArray();
        if (!$material) {
            return null;
        }

        $material['tags'] = $this->getTagsForMaterial($material['id']);
        return $material;
    }

    public function getTagsForMaterial(int $materialId): array
    {
        return $this->db->table('learning_material_tags lmt')
                        ->select('lt.*')
                        ->join('learning_tags lt', 'lt.id = lmt.tag_id')
                        ->where('lmt.material_id', $materialId)
                        ->get()->getResultArray();
    }

    public function getAllTags(): array
    {
        return $this->db->table('learning_tags')->orderBy('name', 'ASC')->get()->getResultArray();
    }

    public function incrementViewCount(int $materialId)
    {
        $now = date('Y-m-d H:i:s');
        $this->db->query("UPDATE learning_materials SET views_count = views_count + 1, last_viewed_at = '{$now}' WHERE id = {$materialId}");
    }

    public function getRelatedMaterials(array $currentMaterial, int $limit = 4): array
    {
        $currentId  = (int)$currentMaterial['id'];
        $divisionId = (int)($currentMaterial['division_id'] ?? 0);
        $category   = $currentMaterial['category'] ?? '';
        $tags       = array_column($currentMaterial['tags'] ?? [], 'id');
        $now        = date('Y-m-d H:i:s');

        $related = [];
        $fetchedIds = [$currentId];

        // Priority 1: Same Tags
        if (!empty($tags)) {
            $p1 = $this->db->table('learning_materials lm')
                           ->select('lm.*, divisions.name as division_name')
                           ->join('learning_material_tags lmt', 'lmt.material_id = lm.id')
                           ->join('divisions', 'divisions.id = lm.division_id', 'left')
                           ->whereIn('lmt.tag_id', $tags)
                           ->whereNotIn('lm.id', $fetchedIds)
                           ->where('lm.status', 'published')
                           ->where('lm.published_at <=', $now)
                           ->where('lm.deleted_at IS NULL')
                           ->limit($limit)
                           ->get()->getResultArray();

            foreach ($p1 as $item) {
                if (count($related) < $limit && !in_array($item['id'], $fetchedIds)) {
                    $related[] = $item;
                    $fetchedIds[] = $item['id'];
                }
            }
        }

        // Priority 2: Same Division
        if (count($related) < $limit && $divisionId > 0) {
            $p2 = $this->db->table('learning_materials lm')
                           ->select('lm.*, divisions.name as division_name')
                           ->join('divisions', 'divisions.id = lm.division_id', 'left')
                           ->where('lm.division_id', $divisionId)
                           ->whereNotIn('lm.id', $fetchedIds)
                           ->where('lm.status', 'published')
                           ->where('lm.published_at <=', $now)
                           ->where('lm.deleted_at IS NULL')
                           ->limit($limit - count($related))
                           ->get()->getResultArray();

            foreach ($p2 as $item) {
                if (count($related) < $limit && !in_array($item['id'], $fetchedIds)) {
                    $related[] = $item;
                    $fetchedIds[] = $item['id'];
                }
            }
        }

        // Priority 3: Same Category
        if (count($related) < $limit && !empty($category)) {
            $p3 = $this->db->table('learning_materials lm')
                           ->select('lm.*, divisions.name as division_name')
                           ->join('divisions', 'divisions.id = lm.division_id', 'left')
                           ->where('lm.category', $category)
                           ->whereNotIn('lm.id', $fetchedIds)
                           ->where('lm.status', 'published')
                           ->where('lm.published_at <=', $now)
                           ->where('lm.deleted_at IS NULL')
                           ->limit($limit - count($related))
                           ->get()->getResultArray();

            foreach ($p3 as $item) {
                if (count($related) < $limit && !in_array($item['id'], $fetchedIds)) {
                    $related[] = $item;
                    $fetchedIds[] = $item['id'];
                }
            }
        }

        // Priority 4: Latest Published Materials
        if (count($related) < $limit) {
            $p4 = $this->db->table('learning_materials lm')
                           ->select('lm.*, divisions.name as division_name')
                           ->join('divisions', 'divisions.id = lm.division_id', 'left')
                           ->whereNotIn('lm.id', $fetchedIds)
                           ->where('lm.status', 'published')
                           ->where('lm.published_at <=', $now)
                           ->where('lm.deleted_at IS NULL')
                           ->orderBy('lm.published_at', 'DESC')
                           ->limit($limit - count($related))
                           ->get()->getResultArray();

            foreach ($p4 as $item) {
                if (count($related) < $limit && !in_array($item['id'], $fetchedIds)) {
                    $related[] = $item;
                    $fetchedIds[] = $item['id'];
                }
            }
        }

        return $related;
    }

    public function storeMaterial(array $data, int $authorId): array
    {
        $this->beginTransaction();
        try {
            $title   = trim($data['title'] ?? '');
            $slug    = !empty($data['slug']) ? url_title($data['slug'], '-', true) : '';
            $slug    = $this->generateUniqueSlug(!empty($slug) ? $slug : $title);
            $content = $data['content'] ?? '';
            $readingTime = $this->calculateReadingTime($content);

            $status = $data['status'] ?? 'draft';
            $publishedAt = null;
            if ($status === 'published') {
                $publishedAt = !empty($data['published_at']) ? date('Y-m-d H:i:s', strtotime($data['published_at'])) : date('Y-m-d H:i:s');
            }

            $attachmentsRaw = $data['attachments'] ?? null;
            $attachmentsJson = is_array($attachmentsRaw) ? json_encode($attachmentsRaw) : $attachmentsRaw;

            $payload = [
                'title'        => $title,
                'slug'         => $slug,
                'excerpt'      => trim($data['excerpt'] ?? ''),
                'content'      => $content,
                'thumbnail'    => trim($data['thumbnail'] ?? ''),
                'banner'       => trim($data['banner'] ?? ''),
                'attachments'  => $attachmentsJson,
                'division_id'  => !empty($data['division_id']) ? (int)$data['division_id'] : null,
                'category'     => trim($data['category'] ?: 'Tutorial'),
                'author_id'    => $authorId,
                'status'       => $status,
                'visibility'   => $data['visibility'] ?? 'public',
                'is_featured'  => isset($data['is_featured']) ? 1 : 0,
                'reading_time' => $readingTime,
                'published_at' => $publishedAt,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ];

            $this->db->table('learning_materials')->insert($payload);
            $materialId = $this->db->insertID();

            // Sync tags
            $rawTags = $data['tags'] ?? '';
            $this->syncMaterialTags($materialId, $rawTags);

            $this->auditLogModel->recordLog($authorId, 'LEARNING_MATERIAL_CREATE', "Membuat materi pembelajaran baru: '{$title}'");
            $this->commitTransaction();

            return $this->success('Materi pembelajaran berhasil disimpan.', ['id' => $materialId, 'slug' => $slug]);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal menyimpan materi: ' . $e->getMessage());
        }
    }

    public function updateMaterial(int $id, array $data, int $editorId): array
    {
        $old = $this->db->table('learning_materials')->where('id', $id)->get()->getRowArray();
        if (!$old) {
            return $this->error('Materi pembelajaran tidak ditemukan.');
        }

        $this->beginTransaction();
        try {
            // Save Revision Record
            $this->revisionModel->insert([
                'material_id' => $id,
                'edited_by'   => $editorId,
                'title'       => $old['title'],
                'excerpt'     => $old['excerpt'],
                'content'     => $old['content'],
                'summary'     => 'Revisi sebelum update pada ' . date('d M Y H:i'),
                'created_at'  => date('Y-m-d H:i:s'),
            ]);

            $title   = trim($data['title'] ?? $old['title']);
            $slug    = !empty($data['slug']) ? url_title($data['slug'], '-', true) : $old['slug'];
            if ($slug !== $old['slug']) {
                $slug = $this->generateUniqueSlug($slug, $id);
            }

            $content = $data['content'] ?? $old['content'];
            $readingTime = $this->calculateReadingTime($content);

            $status = $data['status'] ?? $old['status'];
            $publishedAt = $old['published_at'];
            if ($status === 'published') {
                if (!empty($data['published_at'])) {
                    $publishedAt = date('Y-m-d H:i:s', strtotime($data['published_at']));
                } elseif (empty($publishedAt)) {
                    $publishedAt = date('Y-m-d H:i:s');
                }
            }

            $attachmentsRaw = isset($data['attachments']) ? (is_array($data['attachments']) ? json_encode($data['attachments']) : $data['attachments']) : $old['attachments'];

            $payload = [
                'title'        => $title,
                'slug'         => $slug,
                'excerpt'      => trim($data['excerpt'] ?? ''),
                'content'      => $content,
                'thumbnail'    => isset($data['thumbnail']) ? trim($data['thumbnail']) : $old['thumbnail'],
                'banner'       => isset($data['banner']) ? trim($data['banner']) : $old['banner'],
                'attachments'  => $attachmentsRaw,
                'division_id'  => !empty($data['division_id']) ? (int)$data['division_id'] : null,
                'category'     => trim($data['category'] ?: 'Tutorial'),
                'status'       => $status,
                'visibility'   => $data['visibility'] ?? 'public',
                'is_featured'  => isset($data['is_featured']) ? 1 : 0,
                'reading_time' => $readingTime,
                'published_at' => $publishedAt,
                'updated_at'   => date('Y-m-d H:i:s'),
            ];

            $this->db->table('learning_materials')->where('id', $id)->update($payload);

            // Sync tags
            if (isset($data['tags'])) {
                $this->syncMaterialTags($id, $data['tags']);
            }

            $this->auditLogModel->recordLog($editorId, 'LEARNING_MATERIAL_UPDATE', "Memperbarui materi pembelajaran ID {$id}: '{$title}'");
            $this->commitTransaction();

            return $this->success('Materi pembelajaran berhasil diperbarui.', ['id' => $id, 'slug' => $slug]);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal memperbarui materi: ' . $e->getMessage());
        }
    }

    public function softDeleteMaterial(int $id, int $actorId): array
    {
        $material = $this->db->table('learning_materials')->where('id', $id)->get()->getRowArray();
        if (!$material) {
            return $this->error('Materi tidak ditemukan.');
        }

        $now = date('Y-m-d H:i:s');
        $this->db->table('learning_materials')->where('id', $id)->update(['deleted_at' => $now]);
        $this->auditLogModel->recordLog($actorId, 'LEARNING_MATERIAL_TRASH', "Memindahkan materi ID {$id} ('{$material['title']}') ke Sampah/Trash");

        return $this->success('Materi berhasil dipindahkan ke Sampah (Trash).');
    }

    public function restoreMaterial(int $id, int $actorId): array
    {
        $material = $this->db->table('learning_materials')->where('id', $id)->get()->getRowArray();
        if (!$material) {
            return $this->error('Materi tidak ditemukan.');
        }

        $this->db->table('learning_materials')->where('id', $id)->update(['deleted_at' => null]);
        $this->auditLogModel->recordLog($actorId, 'LEARNING_MATERIAL_RESTORE', "Memulihkan materi ID {$id} ('{$material['title']}') dari Sampah/Trash");

        return $this->success('Materi berhasil dipulihkan dari Sampah.');
    }

    public function purgeMaterial(int $id, int $actorId): array
    {
        $material = $this->db->table('learning_materials')->where('id', $id)->get()->getRowArray();
        if (!$material) {
            return $this->error('Materi tidak ditemukan.');
        }

        // Safe Asset Cleanup check before unlinking files
        $this->safeUnlinkAsset($material['thumbnail']);
        $this->safeUnlinkAsset($material['banner']);

        // Clean up attachments files
        $attachments = !empty($material['attachments']) ? (is_string($material['attachments']) ? json_decode($material['attachments'], true) : $material['attachments']) : [];
        if (is_array($attachments)) {
            foreach ($attachments as $att) {
                if (!empty($att['url'])) {
                    $this->safeUnlinkAsset($att['url']);
                }
            }
        }

        // Clean up inline content images
        if (!empty($material['content'])) {
            preg_match_all('/<img[^>]+src="([^">]+)"/i', $material['content'], $matches);
            if (!empty($matches[1])) {
                foreach ($matches[1] as $imgUrl) {
                    $this->safeUnlinkAsset($imgUrl);
                }
            }
        }

        // Delete pivot tags & revisions
        $this->db->table('learning_material_tags')->where('material_id', $id)->delete();
        $this->db->table('learning_material_revisions')->where('material_id', $id)->delete();
        $this->db->table('learning_materials')->where('id', $id)->delete();

        $this->auditLogModel->recordLog($actorId, 'LEARNING_MATERIAL_PURGE', "Menghapus permanen materi ID {$id} ('{$material['title']}') dan pembersihan aset safe-unlink (thumbnail, banner, lampiran, gambar konten)");

        return $this->success('Materi dan aset terkait berhasil dihapus secara permanen.');
    }

    private function safeUnlinkAsset(?string $assetPath)
    {
        if (empty($assetPath) || strpos($assetPath, 'http') === 0) {
            return;
        }

        $relativePath = str_replace(base_url(), '', $assetPath);
        $fullPath = ROOTPATH . 'public/' . ltrim($relativePath, '/\\');
        if (!is_file($fullPath)) {
            return;
        }

        // Verify that this asset is NOT referenced elsewhere in DB (portfolios, gallery, homepage sections, other materials, etc.)
        $inPortfolios  = $this->db->table('portfolios')->where('thumbnail', $assetPath)->orWhere('media_file', $assetPath)->countAllResults();
        $inDivisions   = $this->db->table('divisions')->where('cover_image', $assetPath)->countAllResults();
        $inOtherMat    = $this->db->table('learning_materials')->where('thumbnail', $assetPath)->orWhere('banner', $assetPath)->countAllResults();

        if ($inPortfolios === 0 && $inDivisions === 0 && $inOtherMat <= 1) {
            @unlink($fullPath);
        }
    }

    public function bulkAction(string $action, array $ids, int $actorId): array
    {
        if (empty($ids)) {
            return $this->error('Pilih setidaknya satu materi.');
        }

        $now = date('Y-m-d H:i:s');
        if ($action === 'publish') {
            $this->db->table('learning_materials')->whereIn('id', $ids)->update(['status' => 'published', 'published_at' => $now, 'updated_at' => $now]);
            $this->auditLogModel->recordLog($actorId, 'LEARNING_BULK_PUBLISH', "Mempublikasikan masal " . count($ids) . " materi");
        } elseif ($action === 'draft') {
            $this->db->table('learning_materials')->whereIn('id', $ids)->update(['status' => 'draft', 'updated_at' => $now]);
            $this->auditLogModel->recordLog($actorId, 'LEARNING_BULK_DRAFT', "Mengubah status draft masal " . count($ids) . " materi");
        } elseif ($action === 'archive') {
            $this->db->table('learning_materials')->whereIn('id', $ids)->update(['status' => 'archived', 'updated_at' => $now]);
            $this->auditLogModel->recordLog($actorId, 'LEARNING_BULK_ARCHIVE', "Mengarsipkan masal " . count($ids) . " materi");
        } elseif ($action === 'trash') {
            $this->db->table('learning_materials')->whereIn('id', $ids)->update(['deleted_at' => $now]);
            $this->auditLogModel->recordLog($actorId, 'LEARNING_BULK_TRASH', "Memindahkan ke sampah masal " . count($ids) . " materi");
        } elseif ($action === 'restore') {
            $this->db->table('learning_materials')->whereIn('id', $ids)->update(['deleted_at' => null]);
            $this->auditLogModel->recordLog($actorId, 'LEARNING_BULK_RESTORE', "Memulihkan dari sampah masal " . count($ids) . " materi");
        } elseif ($action === 'purge') {
            foreach ($ids as $id) {
                $this->purgeMaterial((int)$id, $actorId);
            }
        }

        return $this->success('Aksi masal berhasil dijalankan.');
    }

    private function syncMaterialTags(int $materialId, $tagsInput)
    {
        $this->db->table('learning_material_tags')->where('material_id', $materialId)->delete();

        if (is_string($tagsInput)) {
            $tagNames = array_map('trim', explode(',', $tagsInput));
        } elseif (is_array($tagsInput)) {
            $tagNames = $tagsInput;
        } else {
            $tagNames = [];
        }

        foreach ($tagNames as $name) {
            if (empty($name)) continue;
            $slug = url_title($name, '-', true);

            $existing = $this->db->table('learning_tags')->where('slug', $slug)->get()->getRowArray();
            if ($existing) {
                $tagId = $existing['id'];
            } else {
                $this->db->table('learning_tags')->insert([
                    'name'       => $name,
                    'slug'       => $slug,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                $tagId = $this->db->insertID();
            }

            $this->db->table('learning_material_tags')->ignore(true)->insert([
                'material_id' => $materialId,
                'tag_id'      => $tagId,
            ]);
        }
    }
}
