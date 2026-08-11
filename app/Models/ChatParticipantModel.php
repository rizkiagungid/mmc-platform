<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatParticipantModel extends Model
{
    protected $table            = 'chat_participants';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['conversation_id', 'user_id', 'role', 'joined_at'];
    protected $useTimestamps    = false;
}
