<?php

namespace App\Modules\Meeting\Services;

use App\Services\BaseService;
use App\Models\MeetingModel;
use App\Models\AuditLogModel;

class MeetingService extends BaseService
{
    protected $meetingModel;
    protected $auditLogModel;

    public function __construct()
    {
        parent::__construct();
        $this->meetingModel  = new MeetingModel();
        $this->auditLogModel = new AuditLogModel();
    }

    public function getAllMeetings(): array
    {
        return $this->meetingModel->orderBy('meeting_date', 'DESC')->findAll();
    }

    public function getActiveMeeting(): ?array
    {
        return $this->meetingModel->getActiveMeeting();
    }

    public function getMeetingById(int $id): ?array
    {
        return $this->meetingModel->find($id);
    }

    public function createMeeting(array $data, ?int $operatorId = null): array
    {
        $this->beginTransaction();

        try {
            $meetingId = $this->meetingModel->insert([
                'uuid'              => $this->meetingModel->generateUuid(),
                'title'             => trim($data['title']),
                'description'       => trim($data['description'] ?? ''),
                'learning_material' => trim($data['learning_material'] ?? ''),
                'mentor'            => trim($data['mentor'] ?? ''),
                'location'          => trim($data['location'] ?? ''),
                'meeting_date'      => $data['meeting_date'],
                'start_time'        => $data['start_time'],
                'end_time'          => $data['end_time'],
                'status'            => 'draft',
                'created_by'        => $operatorId,
            ]);

            $this->auditLogModel->recordLog($operatorId, 'MEETING_CREATE', "Membuat sesi pertemuan baru: {$data['title']}");

            $this->commitTransaction();
            return $this->success('Pertemuan baru berhasil dibuat.', ['meeting_id' => $meetingId]);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal membuat pertemuan: ' . $e->getMessage());
        }
    }

    public function updateMeeting(int $id, array $data, ?int $operatorId = null): array
    {
        $meeting = $this->meetingModel->find($id);
        if (!$meeting) {
            return $this->error('Pertemuan tidak ditemukan.', null, 404);
        }

        $this->beginTransaction();

        try {
            $this->meetingModel->update($id, [
                'title'             => trim($data['title']),
                'description'       => trim($data['description'] ?? ''),
                'learning_material' => trim($data['learning_material'] ?? ''),
                'mentor'            => trim($data['mentor'] ?? ''),
                'location'          => trim($data['location'] ?? ''),
                'meeting_date'      => $data['meeting_date'],
                'start_time'        => $data['start_time'],
                'end_time'          => $data['end_time'],
            ]);

            $this->auditLogModel->recordLog($operatorId, 'MEETING_UPDATE', "Mengubah data pertemuan ID: {$id} ({$data['title']})");

            $this->commitTransaction();
            return $this->success('Data pertemuan berhasil diperbarui.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal memperbarui pertemuan: ' . $e->getMessage());
        }
    }

    public function activateMeeting(int $id, ?int $operatorId = null): array
    {
        $meeting = $this->meetingModel->find($id);
        if (!$meeting) {
            return $this->error('Pertemuan tidak ditemukan.', null, 404);
        }

        $this->beginTransaction();

        try {
            $qrToken = $this->meetingModel->generateQrToken();
            $pinCode = $this->meetingModel->generatePinCode();

            // Deactivate any currently active meetings
            $this->meetingModel->where('status', 'active')->set(['status' => 'completed'])->update();

            // Activate target meeting
            $this->meetingModel->update($id, [
                'status'   => 'active',
                'qr_token' => $qrToken,
                'pin_code' => $pinCode,
            ]);

            $this->auditLogModel->recordLog($operatorId, 'MEETING_ACTIVATE', "Mengaktifkan sesi pertemuan ID: {$id} ({$meeting['title']}) dengan PIN {$pinCode}");

            $this->commitTransaction();
            return $this->success("Sesi pertemuan '{$meeting['title']}' berhasil diaktifkan! PIN: {$pinCode}", [
                'meeting_id' => $id,
                'qr_token'   => $qrToken,
                'pin_code'   => $pinCode,
            ]);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal mengaktifkan pertemuan: ' . $e->getMessage());
        }
    }

    public function completeMeeting(int $id, ?int $operatorId = null): array
    {
        $meeting = $this->meetingModel->find($id);
        if (!$meeting) {
            return $this->error('Pertemuan tidak ditemukan.', null, 404);
        }

        $this->beginTransaction();

        try {
            $this->meetingModel->update($id, ['status' => 'completed']);
            
            // Run Auto Alpha
            $attendanceService = new \App\Modules\Attendance\Services\AttendanceService();
            $attendanceService->processAutoAlphaForExpiredMeetings();

            $this->auditLogModel->recordLog($operatorId, 'MEETING_COMPLETE', "Selesai & Auto-Alpa pertemuan ID: {$id} ({$meeting['title']})");

            $this->commitTransaction();
            return $this->success("Sesi pertemuan '{$meeting['title']}' berhasil diselesaikan dan status Alpa telah otomatis diberikan kepada anggota yang tidak scan.");
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal menyelesaikan pertemuan: ' . $e->getMessage());
        }
    }

    public function deleteMeeting(int $id, ?int $operatorId = null): array
    {
        $meeting = $this->meetingModel->find($id);
        if (!$meeting) {
            return $this->error('Pertemuan tidak ditemukan.', null, 404);
        }

        $this->beginTransaction();

        try {
            $this->meetingModel->delete($id);
            $this->db->table('attendances')->where('meeting_id', $id)->delete();
            $this->auditLogModel->recordLog($operatorId, 'MEETING_DELETE', "Soft delete pertemuan ID: {$id} ({$meeting['title']}) dan presensi terkait");

            $this->commitTransaction();
            return $this->success('Pertemuan berhasil dihapus.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal menghapus pertemuan: ' . $e->getMessage());
        }
    }
}
