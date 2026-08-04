<?php

namespace App\Modules\Attendance\Services;

use App\Services\BaseService;
use App\Models\AttendanceModel;
use App\Models\MeetingModel;
use App\Models\UserModel;
use App\Models\AuditLogModel;

class AttendanceService extends BaseService
{
    protected $attendanceModel;
    protected $meetingModel;
    protected $userModel;
    protected $auditLogModel;

    public function __construct()
    {
        parent::__construct();
        $this->attendanceModel = new AttendanceModel();
        $this->meetingModel    = new MeetingModel();
        $this->userModel       = new UserModel();
        $this->auditLogModel   = new AuditLogModel();
    }

    public function processAutoAlphaForExpiredMeetings(): void
    {
        $nowStr = date('Y-m-d H:i:s');
        
        // Clean up any attendance records belonging to superadmin
        $superAdminUserIds = array_column(
            $this->userModel->select('users.id')
                            ->join('roles', 'roles.id = users.role_id')
                            ->where('roles.slug', 'superadmin')
                            ->findAll(),
            'id'
        );
        if (!empty($superAdminUserIds)) {
            $this->db->table('attendances')->whereIn('user_id', $superAdminUserIds)->delete();
        }

        $meetings = $this->meetingModel->where('deleted_at IS NULL')->findAll();
        $allUsers = $this->userModel->select('users.*, roles.slug as role_slug')
                                    ->join('roles', 'roles.id = users.role_id', 'left')
                                    ->where('users.deleted_at IS NULL')
                                    ->where('users.status', 'active')
                                    ->where('roles.slug !=', 'superadmin')
                                    ->findAll();

        if (empty($meetings) || empty($allUsers)) {
            return;
        }

        foreach ($meetings as $meeting) {
            $meetingEnd = $meeting['meeting_date'] . ' ' . ($meeting['end_time'] ?? '23:59:59');
            $isExpired  = strtotime($meetingEnd) <= time();

            // If an active meeting has passed end_time, mark status as completed
            if ($meeting['status'] === 'active' && $isExpired) {
                $this->meetingModel->update($meeting['id'], ['status' => 'completed']);
                $meeting['status'] = 'completed';
            }

            // If meeting is completed or expired active meeting, assign Alpha to unrecorded users
            if ($meeting['status'] === 'completed' || ($meeting['status'] === 'active' && $isExpired)) {
                $existingUserIds = array_column(
                    $this->db->table('attendances')
                             ->select('user_id')
                             ->where('meeting_id', $meeting['id'])
                             ->get()->getResultArray(),
                    'user_id'
                );

                $inserts = [];
                foreach ($allUsers as $user) {
                    if (!in_array($user['id'], $existingUserIds)) {
                        $inserts[] = [
                            'meeting_id'          => $meeting['id'],
                            'user_id'             => $user['id'],
                            'method'              => 'system_auto',
                            'scanned_by_admin_id' => null,
                            'scan_time'           => $meetingEnd,
                            'status'              => 'alpha',
                            'notes'               => 'Otomatis Alpha (Tidak scan presensi hingga waktu berakhir)',
                            'created_at'          => $nowStr,
                            'updated_at'          => $nowStr,
                        ];
                    }
                }

                if (!empty($inserts)) {
                    $this->db->table('attendances')->insertBatch($inserts);
                }
            }
        }
    }

    public function getActiveMeeting(): ?array
    {
        $this->processAutoAlphaForExpiredMeetings();
        return $this->meetingModel->getActiveMeeting();
    }

    public function getMeetingById(int $meetingId): ?array
    {
        return $this->meetingModel->find($meetingId);
    }

    public function getAllMeetings(): array
    {
        $this->processAutoAlphaForExpiredMeetings();
        return $this->meetingModel->orderBy('meeting_date', 'DESC')->findAll();
    }

    public function getAttendancesByMeeting(int $meetingId): array
    {
        $this->processAutoAlphaForExpiredMeetings();
        return $this->attendanceModel->getAttendancesByMeeting($meetingId);
    }

    public function getAllAttendances($meetingId = null, ?int $userId = null): array
    {
        $this->processAutoAlphaForExpiredMeetings();
        $mId = ($meetingId && $meetingId !== 'all') ? (int)$meetingId : null;
        return $this->attendanceModel->getAllAttendances($mId, $userId);
    }

    public function getFilteredAttendances(array $filters = []): array
    {
        $this->processAutoAlphaForExpiredMeetings();
        return $this->attendanceModel->getFilteredAttendances($filters);
    }

    public function getUserAttendanceHistory(int $userId): array
    {
        return $this->attendanceModel->getAttendancesByUser($userId);
    }

    public function processScanApi(string $scanType, string $qrCode, int $actorUserId, ?string $device = null, ?string $ip = null): array
    {
        $qrCode = trim($qrCode);
        if (empty($qrCode)) {
            return $this->error('Data QR Code tidak valid / kosong.');
        }

        $activeMeeting = $this->meetingModel->getActiveMeeting();
        if (!$activeMeeting) {
            return $this->error('Tidak ada sesi pertemuan yang sedang aktif saat ini.');
        }

        if ($scanType === 'meeting_qr') {
            // Member scanned Meeting QR Poster
            if (($activeMeeting['qr_token'] ?? '') !== $qrCode) {
                return $this->error('QR Code Meeting tidak cocok atau sudah kadaluarsa.');
            }

            $actorUser = $this->userModel->select('users.*, roles.slug as role_slug')->join('roles', 'roles.id = users.role_id')->find($actorUserId);
            if ($actorUser && ($actorUser['role_slug'] ?? '') === 'superadmin') {
                return $this->error('Pengguna dengan role Super Admin adalah pengelola web dan tidak diwajibkan mencatat presensi.');
            }

            $targetUserId = $actorUserId;
            $adminId      = null;
            $method       = 'meeting_qr';
        } elseif ($scanType === 'member_qr') {
            // Operator scanned Member Permanent QR
            $member = $this->userModel->getUserByUuid($qrCode);
            if (!$member) {
                return $this->error('Permanent Member QR Code tidak ditemukan atau sudah diregenerasi.');
            }

            if (($member['role_slug'] ?? '') === 'superadmin') {
                return $this->error('Anggota dengan role Super Admin adalah pengelola web dan tidak diwajibkan mengikuti presensi.');
            }

            if (($member['status'] ?? '') !== 'active') {
                return $this->error("Status anggota {$member['full_name']} dalam kondisi {$member['status']}.");
            }

            $targetUserId = (int)$member['id'];
            $adminId      = $actorUserId;
            $method       = 'member_qr';
        } else {
            return $this->error('Tipe scanner presensi tidak valid.');
        }

        // Check duplicate attendance
        if ($this->attendanceModel->isAlreadyAttended((int)$activeMeeting['id'], $targetUserId)) {
            return $this->warning('Anggota ini sudah mencatatkan presensi pada sesi pertemuan ini.');
        }

        $this->db->transBegin();

        try {
            // Record attendance
            $this->attendanceModel->insert([
                'meeting_id'          => $activeMeeting['id'],
                'user_id'             => $targetUserId,
                'method'              => $method,
                'scanned_by_admin_id' => $adminId,
                'scan_time'           => date('Y-m-d H:i:s'),
                'status'              => 'present',
                'device'              => $device,
                'ip_address'          => $ip,
            ]);

            $user = $this->userModel->find($targetUserId);
            $userName = $user['full_name'] ?? 'Anggota';
            $this->auditLogModel->recordLog($actorUserId, 'ATTENDANCE_SUCCESS', "Presensi berhasil tercatat via {$method} untuk: {$userName}");

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return $this->error('Gagal memproses transaksi presensi ke database.');
            }

            $this->db->transCommit();
            return $this->success("Presensi Berhasil! Selamat datang {$userName}.", [
                'user'    => $userName,
                'meeting' => $activeMeeting['title'],
                'time'    => date('H:i:s'),
            ]);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal memproses presensi: ' . $e->getMessage());
        }
    }

    public function processPinApi(string $pinCode, int $userId, ?string $device = null, ?string $ip = null): array
    {
        $pinCode = trim($pinCode);
        if (empty($pinCode)) {
            return $this->error('4-Digit PIN wajib diisi.');
        }

        $user = $this->userModel->select('users.*, roles.slug as role_slug')->join('roles', 'roles.id = users.role_id')->find($userId);
        if ($user && ($user['role_slug'] ?? '') === 'superadmin') {
            return $this->error('Pengguna dengan role Super Admin adalah pengelola web dan tidak diwajibkan mencatat presensi.');
        }

        $activeMeeting = $this->meetingModel->getActiveMeeting();
        if (!$activeMeeting) {
            return $this->error('Tidak ada sesi pertemuan yang sedang aktif saat ini.');
        }

        if (($activeMeeting['pin_code'] ?? '') !== $pinCode) {
            return $this->error('Kode PIN 4-Digit tidak sesuai.');
        }

        if ($this->attendanceModel->isAlreadyAttended((int)$activeMeeting['id'], $userId)) {
            return $this->warning('Anda sudah mencatatkan presensi pada sesi pertemuan ini.');
        }

        $this->db->transBegin();

        try {
            $this->attendanceModel->insert([
                'meeting_id'          => $activeMeeting['id'],
                'user_id'             => $userId,
                'method'              => 'pin',
                'scanned_by_admin_id' => null,
                'scan_time'           => date('Y-m-d H:i:s'),
                'status'              => 'present',
                'device'              => $device,
                'ip_address'          => $ip,
            ]);

            $user = $this->userModel->find($userId);
            $userName = $user['full_name'] ?? 'Anggota';
            $this->auditLogModel->recordLog($userId, 'ATTENDANCE_PIN_SUCCESS', "Presensi via 4-digit PIN berhasil untuk: {$userName}");

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return $this->error('Gagal memproses transaksi PIN presensi ke database.');
            }

            $this->db->transCommit();
            return $this->success("Presensi via PIN Berhasil! Selamat mengikuti sesi '{$activeMeeting['title']}'.");
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal memproses PIN presensi: ' . $e->getMessage());
        }
    }

    public function recordManual(array $data, int $operatorId): array
    {
        $meetingId = (int)($data['meeting_id'] ?? 0);
        $userId    = (int)($data['user_id'] ?? 0);
        $status    = $data['status'] ?? 'present';
        $notes     = trim($data['notes'] ?? '');

        if ($meetingId <= 0 || $userId <= 0) {
            return $this->error('Sesi pertemuan dan anggota wajib dipilih.');
        }

        $targetUser = $this->userModel->select('users.*, roles.slug as role_slug')->join('roles', 'roles.id = users.role_id')->find($userId);
        if ($targetUser && ($targetUser['role_slug'] ?? '') === 'superadmin') {
            return $this->error('Anggota dengan role Super Admin adalah pengelola web dan tidak dapat dicatat presensinya.');
        }

        $this->db->transBegin();

        try {
            if ($this->attendanceModel->isAlreadyAttended($meetingId, $userId)) {
                $this->attendanceModel->where('meeting_id', $meetingId)->where('user_id', $userId)->set([
                    'status'              => $status,
                    'notes'               => $notes,
                    'scanned_by_admin_id' => $operatorId,
                    'scan_time'           => date('Y-m-d H:i:s'),
                ])->update();
                $msg = 'Presensi anggota berhasil diperbarui.';
            } else {
                $this->attendanceModel->insert([
                    'meeting_id'          => $meetingId,
                    'user_id'             => $userId,
                    'method'              => 'manual',
                    'scanned_by_admin_id' => $operatorId,
                    'scan_time'           => date('Y-m-d H:i:s'),
                    'status'              => $status,
                    'notes'               => $notes,
                ]);
                $msg = 'Presensi manual anggota berhasil dicatat.';
            }

            $user = $this->userModel->find($userId);
            $userName = $user['full_name'] ?? 'Anggota';
            $this->auditLogModel->recordLog($operatorId, 'ATTENDANCE_MANUAL', "Presensi manual ({$status}) dicatat untuk: {$userName}");

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return $this->error('Gagal mencatat presensi manual di database.');
            }

            $this->db->transCommit();
            return $this->success($msg);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal mencatat presensi manual: ' . $e->getMessage());
        }
    }

    public function updateAttendance(int $attendanceId, array $data, int $operatorId): array
    {
        $attendance = $this->attendanceModel->find($attendanceId);
        if (!$attendance) {
            return $this->error('Data presensi tidak ditemukan.');
        }

        $status = $data['status'] ?? $attendance['status'];
        $notes  = trim($data['notes'] ?? '');

        $this->db->transBegin();

        try {
            $this->attendanceModel->update($attendanceId, [
                'status'              => $status,
                'notes'               => $notes,
                'scanned_by_admin_id' => $operatorId,
            ]);

            $user = $this->userModel->find($attendance['user_id']);
            $userName = $user['full_name'] ?? 'Anggota';
            $this->auditLogModel->recordLog($operatorId, 'ATTENDANCE_UPDATE', "Status presensi diperbarui menjadi '{$status}' untuk: {$userName}");

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return $this->error('Gagal memperbarui presensi di database.');
            }

            $this->db->transCommit();
            return $this->success('Data presensi berhasil diperbarui.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal memperbarui presensi: ' . $e->getMessage());
        }
    }

    public function deleteAttendance(int $attendanceId, int $operatorId): array
    {
        $attendance = $this->attendanceModel->find($attendanceId);
        if (!$attendance) {
            return $this->error('Data presensi tidak ditemukan.');
        }

        $this->db->transBegin();

        try {
            $user = $this->userModel->find($attendance['user_id']);
            $userName = $user['full_name'] ?? 'Anggota';

            $this->attendanceModel->delete($attendanceId);
            $this->auditLogModel->recordLog($operatorId, 'ATTENDANCE_DELETE', "Data presensi dihapus untuk: {$userName}");

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return $this->error('Gagal menghapus presensi di database.');
            }

            $this->db->transCommit();
            return $this->success('Data presensi berhasil dihapus.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal menghapus presensi: ' . $e->getMessage());
        }
    }
}
