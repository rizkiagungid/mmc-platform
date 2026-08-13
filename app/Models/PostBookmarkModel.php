<?php

namespace App\Models;

use CodeIgniter\Model;

class PostBookmarkModel extends Model
{
    protected $table            = 'post_bookmarks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'post_id',
        'user_id',
        'created_at',
    ];

    protected $useTimestamps = false;
}
