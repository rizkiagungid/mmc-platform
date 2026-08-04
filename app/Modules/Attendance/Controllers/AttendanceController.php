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

        $allUsers = $this->userModel->getUsersWithRole(null, null, false);

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
        if (session()->get('role_slug') === 'superadmin') {
            return redirect()->to('/dashboard')->with('info', 'Sebagai Super Admin (Pengelola Web), Anda tidak diwajibkan melakukan presensi.');
        }

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
        if (session()->get('role_slug') === 'superadmin') {
            return redirect()->to('/dashboard')->with('info', 'Sebagai Super Admin (Pengelola Web), Anda tidak diwajibkan memiliki riwayat presensi.');
        }

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

    public function export()
    {
        $filters = [
            'meeting_id' => $this->request->getGetPost('meeting_id'),
            'status'     => $this->request->getGetPost('status'),
            'method'     => $this->request->getGetPost('method'),
            'start_date' => $this->request->getGetPost('start_date'),
            'end_date'   => $this->request->getGetPost('end_date'),
        ];

        $delimiter = $this->request->getGetPost('delimiter') ?: ';';
        if (!in_array($delimiter, [';', ','])) {
            $delimiter = ';';
        }

        $selectedCols = $this->request->getGetPost('columns');
        if (!is_array($selectedCols) || empty($selectedCols)) {
            $selectedCols = ['no', 'date', 'meeting', 'nis_nip', 'name', 'class', 'role', 'status', 'method', 'scan_time', 'notes'];
        }

        $attendances = $this->attendanceService->getFilteredAttendances($filters);

        $filename = 'Rekap_Presensi_MMC_' . date('Y-m-d_H-i') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        // Add UTF-8 BOM for Excel compatibility
        fputs($output, "\xEF\xBB\xBF");
        
        // Add sep directive for Excel automatic column splitting
        fputs($output, "sep={$delimiter}\n");

        // Available headers map
        $allHeaders = [
            'no'        => 'No',
            'date'      => 'Tanggal Pertemuan',
            'meeting'   => 'Sesi Pertemuan',
            'nis_nip'   => 'NIS / NIP',
            'name'      => 'Nama Anggota',
            'class'     => 'Kelas / Departemen',
            'role'      => 'Role',
            'status'    => 'Status Presensi',
            'method'    => 'Metode Presensi',
            'scan_time' => 'Waktu Scan',
            'notes'     => 'Catatan',
        ];

        $headerRow = [];
        foreach ($selectedCols as $colKey) {
            if (isset($allHeaders[$colKey])) {
                $headerRow[] = $allHeaders[$colKey];
            }
        }
        fputcsv($output, $headerRow, $delimiter);

        $no = 1;
        foreach ($attendances as $row) {
            $statusLabel = match($row['status'] ?? '') {
                'present'   => 'Hadir (Present)',
                'late'      => 'Terlambat (Late)',
                'sick'      => 'Sakit (Sick)',
                'permitted' => 'Izin (Permitted)',
                'alpha'     => 'Alpa (Tanpa Keterangan)',
                default     => strtoupper($row['status'] ?? '-')
            };

            $methodLabel = match($row['method'] ?? '') {
                'meeting_qr'  => 'Scan QR Poster',
                'member_qr'   => 'Scan QR Member',
                'pin'         => '4-Digit PIN',
                'manual'      => 'Manual Input Admin',
                'system_auto' => 'Otomatis Sistem (Auto-Alpha)',
                default       => ucfirst($row['method'] ?? '-')
            };

            $dataRowMap = [
                'no'        => $no++,
                'date'      => date('d/m/Y', strtotime($row['meeting_date'] ?? $row['created_at'] ?? date('Y-m-d'))),
                'meeting'   => $row['meeting_title'] ?? '-',
                'nis_nip'   => $row['nis_nip'] ?? '-',
                'name'      => $row['full_name'] ?? '-',
                'class'     => $row['class_dept'] ?? '-',
                'role'      => strtoupper($row['role_slug'] ?? $row['role_name'] ?? 'MEMBER'),
                'status'    => $statusLabel,
                'method'    => $methodLabel,
                'scan_time' => !empty($row['scan_time']) ? date('d/m/Y H:i:s', strtotime($row['scan_time'])) : '-',
                'notes'     => $row['notes'] ?? '-'
            ];

            $line = [];
            foreach ($selectedCols as $colKey) {
                if (array_key_exists($colKey, $dataRowMap)) {
                    $line[] = $dataRowMap[$colKey];
                }
            }
            fputcsv($output, $line, $delimiter);
        }

        fclose($output);
        exit;
    }
}
