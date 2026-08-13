<?php

namespace App\Models;

use CodeIgniter\Model;

class InformationModel extends Model
{
    protected $table            = 'informations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title',
        'category',
        'description',
        'date_time',
        'created_by',
        'is_popup',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get active informations for members with read status
     */
    public function getInformationsForMember(int $userId, ?string $category = null, int $limit = 50): array
    {
        $builder = $this->db->table($this->table)
                            ->select('informations.*, users.full_name as author_name, roles.name as author_role, roles.slug as author_role_slug, information_reads.read_at')
                            ->join('users', 'users.id = informations.created_by', 'left')
                            ->join('roles', 'roles.id = users.role_id', 'left')
                            ->join('information_reads', 'information_reads.information_id = informations.id AND information_reads.user_id = ' . (int)$userId, 'left')
                            ->where('informations.status', 'active');

        if (!empty($category) && $category !== 'all') {
            $builder->where('informations.category', $category);
        }

        $results = $builder->orderBy('informations.date_time', 'DESC')
                           ->orderBy('informations.id', 'DESC')
                           ->limit($limit)
                           ->get()
                           ->getResultArray();

        foreach ($results as &$row) {
            $row['is_read'] = !empty($row['read_at']);
        }

        return $results;
    }

    /**
     * Get unread popup informations for a member
     */
    public function getUnreadPopupsForUser(int $userId): array
    {
        return $this->db->table($this->table)
                        ->select('informations.*, users.full_name as author_name, roles.name as author_role, roles.slug as author_role_slug')
                        ->join('users', 'users.id = informations.created_by', 'left')
                        ->join('roles', 'roles.id = users.role_id', 'left')
                        ->join('information_reads', 'information_reads.information_id = informations.id AND information_reads.user_id = ' . (int)$userId, 'left')
                        ->where('informations.status', 'active')
                        ->where('informations.is_popup', 1)
                        ->where('information_reads.id IS NULL')
                        ->orderBy('informations.date_time', 'DESC')
                        ->get()
                        ->getResultArray();
    }

    /**
     * Get all informations for admin list
     */
    public function getAdminInformations(): array
    {
        return $this->db->table($this->table)
                        ->select('informations.*, users.full_name as author_name, roles.name as author_role')
                        ->join('users', 'users.id = informations.created_by', 'left')
                        ->join('roles', 'roles.id = users.role_id', 'left')
                        ->orderBy('informations.date_time', 'DESC')
                        ->get()
                        ->getResultArray();
    }
}
