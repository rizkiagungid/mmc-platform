<?php

namespace App\Modules\Learning\Models;

use CodeIgniter\Model;

class LearningRevisionModel extends Model
{
    protected $table            = 'learning_material_revisions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'material_id',
        'edited_by',
        'title',
        'excerpt',
        'content',
        'summary',
        'created_at',
    ];

    protected $useTimestamps = false;
}
