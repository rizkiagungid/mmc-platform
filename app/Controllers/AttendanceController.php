<?php

namespace App\Controllers;

use App\Models\AttendanceModel;
use App\Models\MeetingModel;
use App\Models\UserModel;
use App\Models\AuditLogModel;

class AttendanceController extends BaseController
{
    protected $attendanceModel;
    protected $meetingModel;
    protected $userModel;
    protected $auditLogModel;

    public function __construct()
    {
        $this->attendanceModel = new AttendanceModel();
        $this->meetingModel    = new MeetingModel();
        $this->userModel       = new UserModel();
        $this->auditLogModel   = new AuditLogModel();
    }

    public function index()
    {
        $meetingId = $this->request->getGet('meeting_id');
        $meetings  = $this->meetingModel->orderBy('meeting_date', 'DESC')->findAll();

        if ($meetingId) {
            $attendances = $this->attendanceModel->getAttendancesByMeeting((int)$meetingId);
            $currentMeeting = $this->meetingModel->find($meetingId);
        } else {
            $activeMeeting = $this->meetingModel->getActiveMeeting();
            if ($activeMeeting) {
                $meetingId = $activeMeeting['id'];
                $currentMeeting = $activeMeeting;
                $attendances = $this->attendanceModel->getAttendancesByMeeting((int)$meetingId);
            } else {
                $attendances = [];
                $currentMeeting = null;
            }
        }

        $allUsers = $this->userModel->where('status', 'active')->where('role_id', 4)->findAll();

        return view('admin/attendance/index', [
            'title'          => 'Manajemen & Rekap Presensi - Admin CMS',
            'meetings'       => $meetings,
            'currentMeeting' => $currentMeeting,
            'attendances'    => $attendances,
            'allUsers'       => $allUsers,
        ]);
    }

    public function scanMeetingQr()
    {
        $activeMeeting = $this->meetingModel->getActiveMeeting();

        return view('member/scan_meeting_qr', [
            'title'         => 'Scan Meeting QR Code - Presensi Mandiri',
            'activeMeeting' => $activeMeeting,
        ]);
    }

    public function scanMemberQr()
    {
        $activeMeeting = $this->meetingModel->getActiveMeeting();

        return view('admin/attendance/scan_member_qr', [
            'title'         => 'Scan Member QR Code - Operator Check-In',
            'activeMeeting' => $activeMeeting,
        ]);
    }

    public function processScanApi()
    {
        $scanType = $this->request->getPost('scan_type'); // 'meeting_qr' or 'member_qr'
        $qrCode   = trim($this->request->getPost('qr_code') ?? '');
        $device   = (string) $this->request->getUserAgent();
        $ip       = $this->request->getIPAddress();
        $now      = date('Y-m-d H:i:s');

        if (empty($qrCode)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data QR Code tidak valid / kosong.']);
        }

        if ($scanType === 'meeting_qr') {
            // Member scanning Meeting QR
            $userId  = session()->get('user_id');
            $meeting = $this->meetingModel->where('qr_token', $qrCode)->where('status', 'active')->first();

            if (!$meeting) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'QR Code Pertemuan tidak aktif atau tidak ditemukan.']);
            }

            $already = $this->attendanceModel->checkAlreadyAttended($meeting['id'], $userId);
            if ($already) {
                return $this->response->setJSON([
                    'status'  => 'warning',
                    'message' => "Anda sudah terverifikasi presensi pada jam " . date('H:i', strtotime($already['scan_time'])),
                ]);
            }

            // Determine if late based on meeting start time
            $meetingStart = strtotime($meeting['meeting_date'] . ' ' . $meeting['start_time']);
            $attStatus    = (time() > $meetingStart + 900) ? 'late' : 'present'; // 15 mins tolerance

            $this->attendanceModel->insert([
                'meeting_id'          => $meeting['id'],
                'user_id'             => $userId,
                'method'              => 'meeting_qr',
                'scanned_by_admin_id' => null,
                'scan_time'           => $now,
                'status'              => $attStatus,
                'notes'               => 'Presensi sukses via Scan QR Pertemuan',
                'device'              => $device,
                'ip_address'          => $ip,
            ]);

            $this->auditLogModel->recordLog($userId, 'ATTENDANCE_MEETING_QR', "Presensi sukses via Meeting QR untuk pertemuan: {$meeting['title']}");

            return $this->response->setJSON([
                'status'        => 'success',
                'message'       => 'Presensi Berhasil Terverifikasi!',
                'meeting_title' => $meeting['title'],
                'att_status'    => strtoupper($attStatus),
                'scan_time'     => date('H:i:s d/m/Y'),
            ]);

        } elseif ($scanType === 'member_qr') {
            // Admin/Operator scanning Member Permanent QR
            $adminId = session()->get('user_id');
            $meeting = $this->meetingModel->getActiveMeeting();

            if (!$meeting) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada pertemuan aktif saat ini. Silakan aktifkan pertemuan terlebih dahulu.']);
            }

            // QR code contains member_uuid only
            $member = $this->userModel->where('member_uuid', $qrCode)->where('status', 'active')->first();
            if (!$member) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'QR Code Member tidak valid atau sudah kadaluarsa (QR telah diregenerasi).']);
            }

            $already = $this->attendanceModel->checkAlreadyAttended($meeting['id'], $member['id']);
            if ($already) {
                return $this->response->setJSON([
                    'status'  => 'warning',
                    'message' => "Anggota {$member['full_name']} sudah tercatat presensi sebelumnya pada jam " . date('H:i', strtotime($already['scan_time'])),
                    'member'  => $member['full_name'],
                ]);
            }

            $meetingStart = strtotime($meeting['meeting_date'] . ' ' . $meeting['start_time']);
            $attStatus    = (time() > $meetingStart + 900) ? 'late' : 'present';

            $this->attendanceModel->insert([
                'meeting_id'          => $meeting['id'],
                'user_id'             => $member['id'],
                'method'              => 'member_qr',
                'scanned_by_admin_id' => $adminId,
                'scan_time'           => $now,
                'status'              => $attStatus,
                'notes'               => 'Presensi via Scan QR Member oleh Admin/Operator',
                'device'              => $device,
                'ip_address'          => $ip,
            ]);

            $this->auditLogModel->recordLog($adminId, 'ATTENDANCE_MEMBER_QR', "Operator memverifikasi presensi member {$member['full_name']} ({$member['nis_nip']})");

            return $this->response->setJSON([
                'status'      => 'success',
                'message'     => "Presensi Berhasil Ditambahkan!",
                'member_name' => $member['full_name'],
                'nis_nip'     => $member['nis_nip'],
                'class_dept'  => $member['class_dept'],
                'att_status'  => strtoupper($attStatus),
                'scan_time'   => date('H:i:s d/m/Y'),
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Tipe scanner tidak dikenali.']);
    }

    public function processPinApi()
    {
        $userId  = session()->get('user_id');
        $pinCode = trim($this->request->getPost('pin_code') ?? '');
        $now     = date('Y-m-d H:i:s');

        $meeting = $this->meetingModel->where('pin_code', $pinCode)->where('status', 'active')->first();
        if (!$meeting) {
            return redirect()->back()->with('error', 'Kode PIN Presensi tidak sesuai atau pertemuan belum aktif.');
        }

        $already = $this->attendanceModel->checkAlreadyAttended($meeting['id'], $userId);
        if ($already) {
            return redirect()->back()->with('warning', 'Anda sudah tercatat presensi untuk pertemuan ini.');
        }

        $meetingStart = strtotime($meeting['meeting_date'] . ' ' . $meeting['start_time']);
        $attStatus    = (time() > $meetingStart + 900) ? 'late' : 'present';

        $this->attendanceModel->insert([
            'meeting_id'          => $meeting['id'],
            'user_id'             => $userId,
            'method'              => 'pin',
            'scanned_by_admin_id' => null,
            'scan_time'           => $now,
            'status'              => $attStatus,
            'notes'               => 'Presensi via Masukkan 4-Digit PIN',
            'device'              => (string) $this->request->getUserAgent(),
            'ip_address'          => $this->request->getIPAddress(),
        ]);

        $this->auditLogModel->recordLog($userId, 'ATTENDANCE_PIN', "Presensi sukses via PIN untuk pertemuan: {$meeting['title']}");

        return redirect()->back()->with('success', "Presensi Berhasil Terverifikasi via PIN ({$meeting['title']})!");
    }

    public function manualStore()
    {
        $meetingId = (int) $this->request->getPost('meeting_id');
        $userId    = (int) $this->request->getPost('user_id');
        $status    = $this->request->getPost('status') ?? 'present';
        $notes     = trim($this->request->getPost('notes') ?? '');
        $adminId   = session()->get('user_id');
        $now       = date('Y-m-d H:i:s');

        if (!$meetingId || !$userId) {
            return redirect()->back()->with('error', 'Pilih pertemuan dan anggota terlebih dahulu.');
        }

        $already = $this->attendanceModel->checkAlreadyAttended($meetingId, $userId);
        if ($already) {
            $this->attendanceModel->update($already['id'], [
                'status'              => $status,
                'notes'               => $notes . ' (Diubah manual oleh admin)',
                'scanned_by_admin_id' => $adminId,
            ]);
            return redirect()->back()->with('success', 'Status presensi anggota berhasil diperbarui.');
        }

        $this->attendanceModel->insert([
            'meeting_id'          => $meetingId,
            'user_id'             => $userId,
            'method'              => 'manual',
            'scanned_by_admin_id' => $adminId,
            'scan_time'           => $now,
            'status'              => $status,
            'notes'               => $notes ?: 'Input manual oleh Admin/Pembina',
            'device'              => (string) $this->request->getUserAgent(),
            'ip_address'          => $this->request->getIPAddress(),
        ]);

        $this->auditLogModel->recordLog($adminId, 'ATTENDANCE_MANUAL', "Input presensi manual untuk User ID: {$userId}");

        return redirect()->back()->with('success', 'Presensi manual berhasil dicatat.');
    }

    public function history()
    {
        $userId      = session()->get('user_id');
        $attendances = $this->attendanceModel->getAttendancesByUser($userId);

        return view('member/attendance_history', [
            'title'       => 'Riwayat Presensi Saya - Multimedia Club',
            'attendances' => $attendances,
        ]);
    }
}
