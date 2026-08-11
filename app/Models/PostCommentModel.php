<?php

namespace App\Models;

use CodeIgniter\Model;

class PostCommentModel extends Model
{
    protected $table            = 'post_comments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'post_id',
        'user_id',
        'comment',
        'created_at',
    ];

    protected $useTimestamps = false;
}
