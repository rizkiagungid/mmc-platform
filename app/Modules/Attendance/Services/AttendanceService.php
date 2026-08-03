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

    public function getActiveMeeting(): ?array
    {
        return $this->meetingModel->getActiveMeeting();
    }

    public function getMeetingById(int $meetingId): ?array
    {
        return $this->meetingModel->find($meetingId);
    }

    public function getAllMeetings(): array
    {
        return $this->meetingModel->orderBy('meeting_date', 'DESC')->findAll();
    }

    public function getAttendancesByMeeting(int $meetingId): array
    {
        return $this->attendanceModel->getAttendancesByMeeting($meetingId);
    }

    public function getAllAttendances($meetingId = null, ?int $userId = null): array
    {
        $mId = ($meetingId && $meetingId !== 'all') ? (int)$meetingId : null;
        return $this->attendanceModel->getAllAttendances($mId, $userId);
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

        $this->beginTransaction();

        try {
            if ($scanType === 'meeting_qr') {
                // Member scanned Meeting QR Poster
                if ($activeMeeting['qr_token'] !== $qrCode) {
                    $this->db->transRollback();
                    return $this->error('QR Code Meeting tidak cocok atau sudah kadaluarsa.');
                }

                $targetUserId = $actorUserId;
                $adminId      = null;
                $method       = 'meeting_qr';
            } elseif ($scanType === 'member_qr') {
                // Operator scanned Member Permanent QR
                $member = $this->userModel->getUserByUuid($qrCode);
                if (!$member) {
                    $this->db->transRollback();
                    return $this->error('Permanent Member QR Code tidak ditemukan atau sudah diregenerasi.');
                }

                if ($member['status'] !== 'active') {
                    $this->db->transRollback();
                    return $this->error("Status anggota {$member['full_name']} dalam kondisi {$member['status']}.");
                }

                $targetUserId = $member['id'];
                $adminId      = $actorUserId;
                $method       = 'member_qr';
            } else {
                $this->db->transRollback();
                return $this->error('Tipe scanner presensi tidak valid.');
            }

            // Check duplicate attendance
            if ($this->attendanceModel->isAlreadyAttended($activeMeeting['id'], $targetUserId)) {
                $this->db->transRollback();
                return $this->warning('Anggota ini sudah mencatatkan presensi pada sesi pertemuan ini.');
            }

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
            $this->auditLogModel->recordLog($actorUserId, 'ATTENDANCE_SUCCESS', "Presensi berhasil tercatat via {$method} untuk: {$user['full_name']}");

            $this->commitTransaction();
            return $this->success("Presensi Berhasil! Selamat datang {$user['full_name']}.", [
                'user'    => $user['full_name'],
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

        $activeMeeting = $this->meetingModel->getActiveMeeting();
        if (!$activeMeeting) {
            return $this->error('Tidak ada sesi pertemuan yang sedang aktif saat ini.');
        }

        if ($activeMeeting['pin_code'] !== $pinCode) {
            return $this->error('Kode PIN 4-Digit tidak sesuai.');
        }

        $this->beginTransaction();

        try {
            if ($this->attendanceModel->isAlreadyAttended($activeMeeting['id'], $userId)) {
                $this->db->transRollback();
                return $this->warning('Anda sudah mencatatkan presensi pada sesi pertemuan ini.');
            }

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
            $this->auditLogModel->recordLog($userId, 'ATTENDANCE_PIN_SUCCESS', "Presensi via 4-digit PIN berhasil untuk: {$user['full_name']}");

            $this->commitTransaction();
            return $this->success("Presensi via PIN Berhasil! Selamat mengikuti sesi '{$activeMeeting['title']}'.");
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal memproses PIN presensi: ' . $e->getMessage());
        }
    }

    public function recordManual(array $data, int $operatorId): array
    {
        $meetingId = (int)$data['meeting_id'];
        $userId    = (int)$data['user_id'];
        $status    = $data['status'] ?? 'present';
        $notes     = trim($data['notes'] ?? '');

        $this->beginTransaction();

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
            $this->auditLogModel->recordLog($operatorId, 'ATTENDANCE_MANUAL', "Presensi manual ({$status}) dicatat untuk: {$user['full_name']}");

            $this->commitTransaction();
            return $this->success($msg);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal mencatat presensi manual: ' . $e->getMessage());
        }
    }
}
