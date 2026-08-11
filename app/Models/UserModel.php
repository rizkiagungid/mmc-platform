<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'member_uuid',
        'role_id',
        'username',
        'email',
        'password_hash',
        'full_name',
        'nis_nip',
        'class_dept',
        'phone',
        'address',
        'birth_date',
        'social_instagram',
        'social_tiktok',
        'social_facebook',
        'social_linkedin',
        'social_github',
        'avatar',
        'qr_version',
        'qr_updated_at',
        'status',
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    public function generateUuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public function getUsersWithRole($roleId = null, ?string $keyword = null, bool $includeSuperAdmin = true, array $filters = [])
    {
        $builder = $this->select('users.*, roles.name as role_name, roles.slug as role_slug')
                        ->join('roles', 'roles.id = users.role_id');

        if ($roleId) {
            $builder->where('users.role_id', $roleId);
        }

        if (!$includeSuperAdmin) {
            $builder->where('roles.slug !=', 'superadmin');
        }

        if (!empty($filters['status'])) {
            $builder->where('users.status', $filters['status']);
        }

        if (!empty($filters['class_grade'])) {
            $builder->like('users.class_dept', $filters['class_grade'] . ' ');
        }

        if (!empty($filters['class_room'])) {
            $builder->like('users.class_dept', ' ' . $filters['class_room'] . ' ');
        }

        if (!empty($filters['division'])) {
            $builder->like('users.class_dept', $filters['division']);
        }

        if (isset($filters['has_avatar']) && $filters['has_avatar'] !== null && $filters['has_avatar'] !== '') {
            if ($filters['has_avatar'] === '1') {
                $builder->where('users.avatar IS NOT NULL')->where('users.avatar !=', '');
            } elseif ($filters['has_avatar'] === '0') {
                $builder->groupStart()
                        ->where('users.avatar IS NULL')
                        ->orWhere('users.avatar', '')
                        ->groupEnd();
            }
        }

        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('users.full_name', $keyword)
                    ->orLike('users.username', $keyword)
                    ->orLike('users.email', $keyword)
                    ->orLike('users.nis_nip', $keyword)
                    ->orLike('users.class_dept', $keyword)
                    ->orLike('users.phone', $keyword)
                    ->orLike('users.address', $keyword)
                    ->groupEnd();
        }

        return $builder->orderBy('users.created_at', 'DESC')->findAll();
    }

    public function getUserByUuid(string $uuid)
    {
        return $this->select('users.*, roles.name as role_name, roles.slug as role_slug')
                    ->join('roles', 'roles.id = users.role_id')
                    ->where('users.member_uuid', $uuid)
                    ->first();
    }

    public function regenerateQrCode(int $userId)
    {
        $user = $this->find($userId);
        if (!$user) {
            return false;
        }

        $newUuid = $this->generateUuid();
        $newVersion = ($user['qr_version'] ?? 1) + 1;

        return $this->update($userId, [
            'member_uuid'   => $newUuid,
            'qr_version'    => $newVersion,
            'qr_updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function getTodayBirthdayUsers(): array
    {
        return $this->select('users.id, users.full_name, users.avatar, users.class_dept, users.birth_date, users.social_instagram, users.social_tiktok, users.social_facebook, users.social_linkedin, users.social_github, roles.name as role_name, roles.slug as role_slug')
                    ->join('roles', 'roles.id = users.role_id', 'left')
                    ->where('users.deleted_at IS NULL')
                    ->where('users.status', 'active')
                    ->where('users.birth_date IS NOT NULL')
                    ->where("DATE_FORMAT(users.birth_date, '%m-%d') =", date('m-d'))
                    ->findAll();
    }
}
