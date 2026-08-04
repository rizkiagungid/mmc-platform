<?php

namespace App\Modules\User\Services;

use App\Services\BaseService;
use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\AuditLogModel;

class UserService extends BaseService
{
    protected $userModel;
    protected $roleModel;
    protected $auditLogModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel     = new UserModel();
        $this->roleModel     = new RoleModel();
        $this->auditLogModel = new AuditLogModel();
    }

    public function getAllUsers(?int $roleId = null, ?string $keyword = null): array
    {
        return $this->userModel->getUsersWithRole($roleId, $keyword);
    }

    public function getAllRoles(): array
    {
        return $this->roleModel->findAll();
    }

    public function getUserById(int $id): ?array
    {
        return $this->userModel->find($id);
    }

    public function getUserByUuid(string $uuid): ?array
    {
        return $this->userModel->getUserByUuid($uuid) ?? $this->userModel->find((int)$uuid);
    }

    public function createUser(array $data, ?int $operatorId = null): array
    {
        $this->beginTransaction();

        try {
            $userId = $this->userModel->insert([
                'member_uuid'   => $this->userModel->generateUuid(),
                'role_id'       => (int) $data['role_id'],
                'username'      => trim($data['username']),
                'email'         => trim($data['email']),
                'password_hash' => password_hash($data['password'], PASSWORD_BCRYPT),
                'full_name'     => trim($data['full_name']),
                'nis_nip'       => trim($data['nis_nip'] ?? ''),
                'class_dept'    => trim($data['class_dept'] ?? ''),
                'phone'         => trim($data['phone'] ?? ''),
                'qr_version'    => 1,
                'qr_updated_at' => date('Y-m-d H:i:s'),
                'status'        => $data['status'] ?? 'active',
            ]);

            $this->auditLogModel->recordLog($operatorId, 'USER_CREATE', "Membuat pengguna baru: {$data['full_name']} (@{$data['username']})");

            $this->commitTransaction();
            return $this->success('Pengguna baru berhasil ditambahkan.', ['user_id' => $userId]);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal menambahkan pengguna: ' . $e->getMessage());
        }
    }

    public function updateUser(int $id, array $data, ?int $operatorId = null): array
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->error('Pengguna tidak ditemukan.', null, 404);
        }

        $this->beginTransaction();

        try {
            $updateData = [
                'role_id'    => (int) $data['role_id'],
                'username'   => trim($data['username']),
                'email'      => trim($data['email']),
                'full_name'  => trim($data['full_name']),
                'nis_nip'    => trim($data['nis_nip'] ?? ''),
                'class_dept' => trim($data['class_dept'] ?? ''),
                'phone'      => trim($data['phone'] ?? ''),
                'status'     => $data['status'] ?? 'active',
            ];

            if (!empty($data['password'])) {
                $updateData['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
            }

            $this->userModel->update($id, $updateData);

            $this->auditLogModel->recordLog($operatorId, 'USER_UPDATE', "Mengubah data pengguna ID: {$id} ({$data['full_name']})");

            $this->commitTransaction();
            return $this->success('Data pengguna berhasil diperbarui.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal memperbarui pengguna: ' . $e->getMessage());
        }
    }

    public function deleteUser(int $id, ?int $operatorId = null): array
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->error('Pengguna tidak ditemukan.', null, 404);
        }

        $this->beginTransaction();

        try {
            $this->userModel->delete($id);
            $this->auditLogModel->recordLog($operatorId, 'USER_DELETE', "Soft delete pengguna ID: {$id} ({$user['full_name']})");

            $this->commitTransaction();
            return $this->success('Pengguna berhasil dihapus.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }

    public function regenerateMemberQr(int $userId, ?int $operatorId = null): array
    {
        $user = $this->userModel->find($userId);
        if (!$user) {
            return $this->error('Pengguna tidak ditemukan.', null, 404);
        }

        $this->beginTransaction();

        try {
            $newUuid    = $this->userModel->generateUuid();
            $newVersion = ($user['qr_version'] ?? 1) + 1;
            $now        = date('Y-m-d H:i:s');

            $this->userModel->update($userId, [
                'member_uuid'   => $newUuid,
                'qr_version'    => $newVersion,
                'qr_updated_at' => $now,
            ]);

            $this->auditLogModel->recordLog($operatorId ?? $userId, 'QR_REGENERATE', "Meregenerasi Permanent Member QR v{$newVersion} untuk anggota: {$user['full_name']}");

            $this->commitTransaction();
            return $this->success("Permanent Member QR Code untuk {$user['full_name']} (v{$newVersion}) berhasil diperbarui!", [
                'member_uuid' => $newUuid,
                'qr_version'  => $newVersion,
            ]);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal meregenerasi QR Code: ' . $e->getMessage());
        }
    }

    public function updateSelfProfile(int $userId, array $data, $avatarFile = null): array
    {
        $user = $this->userModel->find($userId);
        if (!$user) {
            return $this->error('Pengguna tidak ditemukan.', null, 404);
        }

        $this->beginTransaction();

        try {
            $updateData = [
                'full_name'  => trim($data['full_name']),
                'email'      => trim($data['email']),
                'phone'      => trim($data['phone'] ?? ''),
                'class_dept' => trim($data['class_dept'] ?? ''),
            ];

            if (!empty($data['password'])) {
                $updateData['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
            }

            // Handle avatar removal request
            if (!empty($data['remove_avatar']) && $data['remove_avatar'] == '1') {
                if (!empty($user['avatar']) && file_exists(FCPATH . $user['avatar'])) {
                    @unlink(FCPATH . $user['avatar']);
                }
                $updateData['avatar'] = null;
            }

            // Handle avatar file upload
            if ($avatarFile && $avatarFile->isValid() && !$avatarFile->hasMoved()) {
                $uploadDir = FCPATH . 'uploads/avatars/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Delete old avatar file if exists
                if (!empty($user['avatar']) && file_exists(FCPATH . $user['avatar'])) {
                    @unlink(FCPATH . $user['avatar']);
                }

                $newName = $avatarFile->getRandomName();
                $avatarFile->move($uploadDir, $newName);
                $updateData['avatar'] = 'uploads/avatars/' . $newName;
            }

            $this->userModel->update($userId, $updateData);

            $currentAvatar = array_key_exists('avatar', $updateData) ? $updateData['avatar'] : ($user['avatar'] ?? null);

            session()->set('full_name', $updateData['full_name']);
            session()->set('email', $updateData['email']);
            session()->set('avatar', $currentAvatar);

            $this->auditLogModel->recordLog($userId, 'PROFILE_UPDATE', 'Pengguna memperbarui data profil dan foto profil');

            $this->commitTransaction();
            return $this->success('Profil Anda berhasil diperbarui.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    public function activateUser(int $id, int $adminId): array
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->error('Pengguna tidak ditemukan.', null, 404);
        }

        $this->beginTransaction();

        try {
            $this->userModel->update($id, [
                'status' => 'active',
            ]);

            $this->auditLogModel->recordLog($adminId, 'USER_ACTIVATED', "Mengkonfirmasi & mengaktifkan akun pendaftar anggota: {$user['full_name']} (@{$user['username']})");

            $this->commitTransaction();
            return $this->success("Akun {$user['full_name']} (@{$user['username']}) telah berhasil dikonfirmasi dan diaktifkan!");
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal mengaktifkan akun: ' . $e->getMessage());
        }
    }

    public function bulkUpdateUsers(array $userIds, array $data, ?int $operatorId = null): array
    {
        $userIds = array_filter(array_map('intval', $userIds));
        if (empty($userIds)) {
            return $this->error('Pilih minimal satu anggota untuk diubah secara massal.');
        }

        $updateData = [];

        if (!empty($data['change_status']) && isset($data['status'])) {
            $updateData['status'] = $data['status'];
        }

        if (!empty($data['change_role']) && isset($data['role_id'])) {
            $updateData['role_id'] = (int)$data['role_id'];
        }

        if (!empty($data['change_class']) && isset($data['class_dept'])) {
            $updateData['class_dept'] = trim($data['class_dept']);
        }

        if (empty($updateData)) {
            return $this->error('Tentukan bidang yang ingin diubah (centang minimal satu bidang edit).');
        }

        $this->beginTransaction();

        try {
            $count = count($userIds);
            $this->userModel->whereIn('id', $userIds)->set($updateData)->update();

            $changedFields = implode(', ', array_keys($updateData));
            $this->auditLogModel->recordLog($operatorId, 'BULK_USER_UPDATE', "Mengubah data massal ({$changedFields}) untuk {$count} anggota.");

            $this->commitTransaction();
            return $this->success("Berhasil memperbarui data secara massal untuk {$count} anggota terpilih!");
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal memperbarui data massal: ' . $e->getMessage());
        }
    }

    public function bulkActionUsers(array $userIds, string $action, ?int $operatorId = null): array
    {
        $userIds = array_filter(array_map('intval', $userIds));
        if (empty($userIds)) {
            return $this->error('Pilih minimal satu anggota.');
        }

        $count = count($userIds);
        $this->beginTransaction();

        try {
            if ($action === 'activate') {
                $this->userModel->whereIn('id', $userIds)->set(['status' => 'active'])->update();
                $msg = "Berhasil mengkonfirmasi dan mengaktifkan {$count} akun anggota!";
                $this->auditLogModel->recordLog($operatorId, 'BULK_USER_ACTIVATE', "Mengaktifkan akun massal untuk {$count} anggota.");
            } elseif ($action === 'regenerate_qr') {
                $users = $this->userModel->whereIn('id', $userIds)->findAll();
                $now   = date('Y-m-d H:i:s');
                foreach ($users as $u) {
                    $newUuid    = $this->userModel->generateUuid();
                    $newVersion = ($u['qr_version'] ?? 1) + 1;
                    $this->userModel->update($u['id'], [
                        'member_uuid'   => $newUuid,
                        'qr_version'    => $newVersion,
                        'qr_updated_at' => $now,
                    ]);
                }
                $msg = "Berhasil meregenerasi QR Code v baru untuk {$count} anggota terpilih!";
                $this->auditLogModel->recordLog($operatorId, 'BULK_QR_REGENERATE', "Regenerasi Member QR massal untuk {$count} anggota.");
            } elseif ($action === 'delete') {
                $this->userModel->whereIn('id', $userIds)->delete();
                $msg = "Berhasil menghapus {$count} anggota terpilih dari sistem.";
                $this->auditLogModel->recordLog($operatorId, 'BULK_USER_DELETE', "Hapus massal {$count} anggota.");
            } else {
                $this->db->transRollback();
                return $this->error('Aksi massal tidak dikenal.');
            }

            $this->commitTransaction();
            return $this->success($msg);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal memproses aksi massal: ' . $e->getMessage());
        }
    }
}
