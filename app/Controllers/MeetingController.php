<?php

namespace App\Controllers;

use App\Models\MeetingModel;
use App\Models\AuditLogModel;
use App\Models\AttendanceModel;

class MeetingController extends BaseController
{
    protected $meetingModel;
    protected $auditLogModel;
    protected $attendanceModel;

    public function __construct()
    {
        $this->meetingModel    = new MeetingModel();
        $this->auditLogModel   = new AuditLogModel();
        $this->attendanceModel = new AttendanceModel();
    }

    public function index()
    {
        $meetings = $this->meetingModel->select('meetings.*, users.full_name as creator_name')
                                        ->join('users', 'users.id = meetings.created_by', 'left')
                                        ->orderBy('meeting_date', 'DESC')
                                        ->findAll();

        return view('admin/meetings/index', [
            'title'    => 'Manajemen Pertemuan & Kegiatan - Admin CMS',
            'meetings' => $meetings,
        ]);
    }

    public function create()
    {
        return view('admin/meetings/create', [
            'title' => 'Buat Jadwal Pertemuan / Workshop Baru',
        ]);
    }

    public function store()
    {
        $rules = [
            'title'        => 'required|min_length[3]',
            'meeting_date' => 'required|valid_date',
            'start_time'   => 'required',
            'end_time'     => 'required',
            'location'     => 'permit_empty',
            'mentor'       => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $meetingId = $this->meetingModel->insert([
            'uuid'              => $this->meetingModel->generateUuid(),
            'title'             => trim($this->request->getPost('title')),
            'description'       => trim($this->request->getPost('description')),
            'learning_material' => trim($this->request->getPost('learning_material')),
            'mentor'            => trim($this->request->getPost('mentor')),
            'location'          => trim($this->request->getPost('location')),
            'meeting_date'      => $this->request->getPost('meeting_date'),
            'start_time'        => $this->request->getPost('start_time'),
            'end_time'          => $this->request->getPost('end_time'),
            'qr_token'          => $this->meetingModel->generateQrToken(),
            'pin_code'          => $this->meetingModel->generatePinCode(),
            'status'            => $this->request->getPost('status') ?? 'draft',
            'created_by'        => session()->get('user_id'),
        ]);

        $this->auditLogModel->recordLog(session()->get('user_id'), 'MEETING_CREATE', "Membuat pertemuan baru: {$this->request->getPost('title')}");

        return redirect()->to('/admin/meetings')->with('success', 'Jadwal pertemuan baru berhasil dibuat.');
    }

    public function edit($id)
    {
        $meeting = $this->meetingModel->find($id);
        if (!$meeting) {
            return redirect()->to('/admin/meetings')->with('error', 'Pertemuan tidak ditemukan.');
        }

        return view('admin/meetings/edit', [
            'title'   => 'Edit Pertemuan - ' . $meeting['title'],
            'meeting' => $meeting,
        ]);
    }

    public function update($id)
    {
        $meeting = $this->meetingModel->find($id);
        if (!$meeting) {
            return redirect()->to('/admin/meetings')->with('error', 'Pertemuan tidak ditemukan.');
        }

        $rules = [
            'title'        => 'required|min_length[3]',
            'meeting_date' => 'required|valid_date',
            'start_time'   => 'required',
            'end_time'     => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->meetingModel->update($id, [
            'title'             => trim($this->request->getPost('title')),
            'description'       => trim($this->request->getPost('description')),
            'learning_material' => trim($this->request->getPost('learning_material')),
            'mentor'            => trim($this->request->getPost('mentor')),
            'location'          => trim($this->request->getPost('location')),
            'meeting_date'      => $this->request->getPost('meeting_date'),
            'start_time'        => $this->request->getPost('start_time'),
            'end_time'          => $this->request->getPost('end_time'),
            'status'            => $this->request->getPost('status'),
        ]);

        $this->auditLogModel->recordLog(session()->get('user_id'), 'MEETING_UPDATE', "Mengubah data pertemuan ID: {$id}");

        return redirect()->to('/admin/meetings')->with('success', 'Jadwal pertemuan berhasil diperbarui.');
    }

    public function delete($id)
    {
        $meeting = $this->meetingModel->find($id);
        if (!$meeting) {
            return redirect()->to('/admin/meetings')->with('error', 'Pertemuan tidak ditemukan.');
        }

        $this->meetingModel->delete($id);
        $this->auditLogModel->recordLog(session()->get('user_id'), 'MEETING_DELETE', "Soft delete pertemuan ID: {$id}");

        return redirect()->to('/admin/meetings')->with('success', 'Jadwal pertemuan berhasil dihapus.');
    }

    public function activate($id)
    {
        $result = $this->meetingModel->activateMeeting($id);
        if (!$result) {
            return redirect()->back()->with('error', 'Gagal mengaktifkan pertemuan.');
        }

        $meeting = $this->meetingModel->find($id);
        $this->auditLogModel->recordLog(session()->get('user_id'), 'MEETING_ACTIVATE', "Mengaktifkan presensi untuk pertemuan: {$meeting['title']}");

        return redirect()->to("/admin/meetings/qr/{$id}")->with('success', "Pertemuan '{$meeting['title']}' kini SANGAT AKTIF untuk presensi QR & PIN!");
    }

    public function qrPoster($id)
    {
        $meeting = $this->meetingModel->find($id);
        if (!$meeting) {
            return redirect()->to('/admin/meetings')->with('error', 'Pertemuan tidak ditemukan.');
        }

        return view('admin/meetings/qr_poster', [
            'title'   => 'QR Code & PIN Poster Presensi - ' . $meeting['title'],
            'meeting' => $meeting,
        ]);
    }
}
