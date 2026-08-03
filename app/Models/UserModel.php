<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
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

    public function getUsersWithRole($roleId = null, ?string $keyword = null)
    {
        $builder = $this->select('users.*, roles.name as role_name, roles.slug as role_slug')
                        ->join('roles', 'roles.id = users.role_id');

        if ($roleId) {
            $builder->where('users.role_id', $roleId);
        }

        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('users.full_name', $keyword)
                    ->orLike('users.username', $keyword)
                    ->orLike('users.email', $keyword)
                    ->orLike('users.nis_nip', $keyword)
                    ->orLike('users.class_dept', $keyword)
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
}
