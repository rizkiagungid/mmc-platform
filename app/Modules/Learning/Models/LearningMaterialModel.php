<?php

namespace App\Modules\Learning\Models;

use CodeIgniter\Model;

class LearningMaterialModel extends Model
{
    protected $table            = 'learning_materials';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'banner',
        'division_id',
        'category',
        'author_id',
        'status',
        'visibility',
        'is_featured',
        'reading_time',
        'attachments',
        'total_downloads',
        'avg_completion_rate',
        'views_count',
        'last_viewed_at',
        'published_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
