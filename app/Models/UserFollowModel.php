<?php

namespace App\Models;

use CodeIgniter\Model;

class UserFollowModel extends Model
{
    protected $table            = 'user_follows';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'follower_id',
        'following_id',
        'created_at',
    ];

    protected $useTimestamps = false;
}
