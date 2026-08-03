<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskPriorityModel extends Model
{
    protected $table            = 'task_priorities';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['name', 'color', 'sort_order'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function getOrderedPriorities()
    {
        return $this->orderBy('sort_order', 'ASC')->findAll();
    }
}
