<?php

namespace App\Modules\Attendance\Controllers;

use App\Controllers\BaseController;
use App\Modules\Attendance\Services\AttendanceService;
use App\Models\UserModel;

class AttendanceController extends BaseController
{
    protected $attendanceService;
    protected $userModel;

    public function __construct()
    {
        $this->attendanceService = new AttendanceService();
        $this->userModel         = new UserModel();
    }

    public function index()
    {
        $meetingId = $this->request->getGet('meeting_id');
        $meetings  = $this->attendanceService->getAllMeetings();

        if ($meetingId && $meetingId !== 'all') {
            $currentMeeting    = $this->attendanceService->getMeetingById((int)$meetingId);
            $attendances       = $this->attendanceService->getAttendancesByMeeting((int)$meetingId);
            $selectedMeetingId = (string)$meetingId;
        } elseif ($meetingId === 'all') {
            $currentMeeting    = null;
            $attendances       = $this->attendanceService->getAllAttendances();
            $selectedMeetingId = 'all';
        } else {
            $activeMeeting = $this->attendanceService->getActiveMeeting();
            if ($activeMeeting) {
                $selectedMeetingId = (string)$activeMeeting['id'];
                $currentMeeting    = $activeMeeting;
                $attendances       = $this->attendanceService->getAttendancesByMeeting((int)$selectedMeetingId);
            } else {
                $currentMeeting    = null;
                $attendances       = $this->attendanceService->getAllAttendances();
                $selectedMeetingId = 'all';
            }
        }

        $allUsers = $this->userModel->getUsersWithRole();

        return view('App\Modules\Attendance\Views\index', [
            'title'             => 'Rekap & Kelola Presensi - Admin CMS',
            'meetings'          => $meetings,
            'currentMeeting'    => $currentMeeting,
            'selectedMeetingId' => $selectedMeetingId,
            'attendances'       => $attendances,
            'allUsers'          => $allUsers,
        ]);
    }

    public function scanMeetingQr()
    {
        $activeMeeting = $this->attendanceService->getActiveMeeting();

        return view('App\Modules\Attendance\Views\scan_meeting_qr', [
            'title'         => 'Presensi Mandiri: Scan Meeting QR / PIN',
            'activeMeeting' => $activeMeeting,
        ]);
    }

    public function scanMemberQr()
    {
        $activeMeeting = $this->attendanceService->getActiveMeeting();

        return view('App\Modules\Attendance\Views\scan_member_qr', [
            'title'         => 'Operator Scanner: Scan Member QR',
            'activeMeeting' => $activeMeeting,
        ]);
    }

    public function processScanApi()
    {
        $scanType = (string)$this->request->getPost('scan_type');
        $qrCode   = (string)$this->request->getPost('qr_code');
        $device   = (string)$this->request->getUserAgent();
        $ip       = $this->request->getIPAddress();

        $result = $this->attendanceService->processScanApi($scanType, $qrCode, session()->get('user_id'), $device, $ip);

        return $this->response->setJSON($result['body']);
    }

    public function processPinApi()
    {
        $pinCode = (string)$this->request->getPost('pin_code');
        $device  = (string)$this->request->getUserAgent();
        $ip      = $this->request->getIPAddress();

        $result = $this->attendanceService->processPinApi($pinCode, session()->get('user_id'), $device, $ip);

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->withInput()->with('error', $result['body']['message']);
        }

        return redirect()->to('/dashboard')->with('success', $result['body']['message']);
    }

    public function manualStore()
    {
        $rules = [
            'meeting_id' => 'required|integer',
            'user_id'    => 'required|integer',
            'status'     => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $result = $this->attendanceService->recordManual($this->request->getPost(), session()->get('user_id'));

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->withInput()->with('error', $result['body']['message']);
        }

        return redirect()->back()->with('success', $result['body']['message']);
    }

    public function history()
    {
        $userId      = session()->get('user_id');
        $attendances = $this->attendanceService->getUserAttendanceHistory($userId);

        return view('App\Modules\Attendance\Views\history', [
            'title'       => 'Riwayat Presensi Saya',
            'attendances' => $attendances,
        ]);
    }

    public function update(int $id)
    {
        $result = $this->attendanceService->updateAttendance($id, $this->request->getPost(), session()->get('user_id'));

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->withInput()->with('error', $result['body']['message']);
        }

        return redirect()->back()->with('success', $result['body']['message']);
    }

    public function delete(int $id)
    {
        $result = $this->attendanceService->deleteAttendance($id, session()->get('user_id'));

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->with('error', $result['body']['message']);
        }

        return redirect()->back()->with('success', $result['body']['message']);
    }
}
