<?php

namespace App\Modules\Meeting\Controllers;

use App\Controllers\BaseController;
use App\Modules\Meeting\Services\MeetingService;

class MeetingController extends BaseController
{
    protected $meetingService;

    public function __construct()
    {
        $this->meetingService = new MeetingService();
    }

    public function index()
    {
        $meetings = $this->meetingService->getAllMeetings();

        return view('App\Modules\Meeting\Views\index', [
            'title'    => 'Manajemen Pertemuan & Workshop - Admin CMS',
            'meetings' => $meetings,
        ]);
    }

    public function create()
    {
        return view('App\Modules\Meeting\Views\create', [
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
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $result = $this->meetingService->createMeeting($this->request->getPost(), session()->get('user_id'));

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->withInput()->with('error', $result['body']['message']);
        }

        return redirect()->to('/admin/meetings')->with('success', $result['body']['message']);
    }

    public function edit($id)
    {
        $meeting = $this->meetingService->getMeetingById((int)$id);
        if (!$meeting) {
            return redirect()->to('/admin/meetings')->with('error', 'Pertemuan tidak ditemukan.');
        }

        return view('App\Modules\Meeting\Views\edit', [
            'title'   => 'Edit Pertemuan - ' . $meeting['title'],
            'meeting' => $meeting,
        ]);
    }

    public function update($id)
    {
        $rules = [
            'title'        => 'required|min_length[3]',
            'meeting_date' => 'required|valid_date',
            'start_time'   => 'required',
            'end_time'     => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $result = $this->meetingService->updateMeeting((int)$id, $this->request->getPost(), session()->get('user_id'));

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->withInput()->with('error', $result['body']['message']);
        }

        return redirect()->to('/admin/meetings')->with('success', $result['body']['message']);
    }

    public function activate($id)
    {
        $result = $this->meetingService->activateMeeting((int)$id, session()->get('user_id'));

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->with('error', $result['body']['message']);
        }

        return redirect()->to('/admin/meetings')->with('success', $result['body']['message']);
    }

    public function delete($id)
    {
        $result = $this->meetingService->deleteMeeting((int)$id, session()->get('user_id'));

        if ($result['body']['status'] !== 'success') {
            return redirect()->to('/admin/meetings')->with('error', $result['body']['message']);
        }

        return redirect()->to('/admin/meetings')->with('success', $result['body']['message']);
    }

    public function qrPoster($id)
    {
        $meeting = $this->meetingService->getMeetingById((int)$id);
        if (!$meeting) {
            return redirect()->to('/admin/meetings')->with('error', 'Pertemuan tidak ditemukan.');
        }

        return view('App\Modules\Meeting\Views\qr_poster', [
            'title'   => 'Poster Presensi Meeting - ' . $meeting['title'],
            'meeting' => $meeting,
        ]);
    }
}
