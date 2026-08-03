<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MeetingModel;
use App\Models\AttendanceModel;
use App\Models\TaskModel;
use App\Models\TaskSubmissionModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $session  = session();
        $userRole = $session->get('role_slug');
        $userId   = $session->get('user_id');

        $userModel       = new UserModel();
        $meetingModel    = new MeetingModel();
        $attendanceModel = new AttendanceModel();
        $taskModel       = new TaskModel();

        $data = [
            'title'         => 'Dashboard Portal - Multimedia Club',
            'user'          => $userModel->find($userId),
            'activeMeeting' => $meetingModel->getActiveMeeting(),
        ];

        if (in_array($userRole, ['superadmin', 'pembina', 'bph'])) {
            $data['totalMembers']     = $userModel->where('role_id', 4)->countAllResults();
            $data['totalMeetings']    = $meetingModel->countAllResults();
            $data['totalTasks']       = $taskModel->countAllResults();
            $data['recentMeetings']   = $meetingModel->orderBy('meeting_date', 'DESC')->findAll(5);
            $data['recentTasks']      = $taskModel->getTasksWithDetails();
            $data['recentAttendances']= $attendanceModel->select('attendances.*, users.full_name, meetings.title as meeting_title')
                                                      ->join('users', 'users.id = attendances.user_id')
                                                      ->join('meetings', 'meetings.id = attendances.meeting_id')
                                                      ->orderBy('attendances.scan_time', 'DESC')
                                                      ->findAll(5);

            return view('dashboard/admin', $data);
        } else {
            // Member Dashboard
            $data['myAttendances'] = $attendanceModel->getAttendancesByUser($userId);
            $data['myTasks']       = $taskModel->getTasksWithDetails($userId);

            return view('dashboard/member', $data);
        }
    }
}
