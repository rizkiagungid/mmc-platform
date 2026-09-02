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

        $activeMeeting = $meetingModel->getActiveMeeting();
        $myActiveAttendance = null;
        if ($activeMeeting) {
            $myActiveAttendance = $attendanceModel->checkAlreadyAttended((int)$activeMeeting['id'], (int)$userId);
        }

        // Attendance & Performance AI Summary Calculation
        $totalMeetingsCount = $meetingModel->where('deleted_at IS NULL')->countAllResults();
        $userAttendances    = $attendanceModel->getAttendancesByUser($userId);
        $userAttendedCount  = count($userAttendances);
        $attendanceRate     = ($totalMeetingsCount > 0) ? round(($userAttendedCount / $totalMeetingsCount) * 100) : 100;

        $userTasks          = $taskModel->getTasksWithDetails($userId);
        $totalAssignedTasks = count($userTasks);
        $completedTasks     = 0;
        foreach ($userTasks as $t) {
            if (!empty($t['is_submitted']) || !empty($t['my_submission'])) {
                $completedTasks++;
            }
        }
        $taskRate = ($totalAssignedTasks > 0) ? round(($completedTasks / $totalAssignedTasks) * 100) : 100;
        $aiScore  = min(100, max(0, (int)round(($attendanceRate * 0.7) + ($taskRate * 0.3))));

        if ($aiScore >= 85) {
            $aiBadge          = 'Sangat Baik (Top Performer)';
            $aiBadgeClass     = 'bg-success bg-opacity-25 text-success border-success';
            $aiStatusText     = 'Luar Biasa! Kehadiran dan performa Anda berada pada tingkat puncak.';
            $aiPertahankan    = [
                'Kedisiplinan scan QR / PIN pada setiap sesi pertemuan',
                'Tingkat kehadiran presensi konsisten tinggi (> 85%)',
                'Partisipasi aktif dan ketepatan waktu pengumpulan tugas'
            ];
            $aiPerbaikan      = [
                'Pertahankan waktu kedatangan agar selalu lebih awal sebelum sesi dibuka',
                'Bantu merangkul dan mengingatkan rekan divisi yang belum presensi'
            ];
            $aiRecommendation = 'Pertahankan konsistensi performa luar biasa ini! Kedisiplinan Anda menjadi teladan bagi seluruh anggota klub Multimedia.';
        } elseif ($aiScore >= 70) {
            $aiBadge          = 'Baik (Konsisten)';
            $aiBadgeClass     = 'bg-info bg-opacity-25 text-info border-info';
            $aiStatusText     = 'Performa Anda cukup baik dan konsisten secara keseluruhan.';
            $aiPertahankan    = [
                'Kehadiran rutin pada sesi kumpul utama divisi',
                'Keaktifan mengikuti kegiatan dan instruksi organisasi'
            ];
            $aiPerbaikan      = [
                'Tingkatkan persentase kehadiran hingga mencapai target di atas 85%',
                'Pastikan langsung melakukan scan presensi saat sesi pertemuan dibuka'
            ];
            $aiRecommendation = 'Performa Anda sudah baik. Tingkatkan sedikit lagi kedisiplinan presensi agar bisa mencapai predikat Top Performer!';
        } elseif ($aiScore >= 50) {
            $aiBadge          = 'Cukup (Perlu Ditingkatkan)';
            $aiBadgeClass     = 'bg-warning bg-opacity-25 text-warning border-warning';
            $aiStatusText     = 'Tingkat kehadiran Anda membutuhkan perhatian ekstra.';
            $aiPertahankan    = [
                'Semangat dalam mengikuti kegiatan klub saat berkesempatan hadir'
            ];
            $aiPerbaikan      = [
                'Tingkatkan kehadiran pada sesi pertemuan rutin mendatang',
                'Hindari keterlambatan dan pastikan presensi tercatat resmi di sistem',
                'Segera hubungi pengurus BPH jika ada kendala izin atau sakit'
            ];
            $aiRecommendation = 'Sistem mengidentifikasi tingkat kehadiran Anda masih perlu ditingkatkan. Diharapkan untuk lebih aktif hadir pada sesi-sesi berikutnya.';
        } else {
            $aiBadge          = 'Kritis (Perlu Perhatian Khusus)';
            $aiBadgeClass     = 'bg-danger bg-opacity-25 text-danger border-danger';
            $aiStatusText     = 'Tingkat presensi berada di bawah standar minimum klub.';
            $aiPertahankan    = [
                'Komunikasi terbuka dengan BPH dan Pembina mengenai kendala Anda'
            ];
            $aiPerbaikan      = [
                'Tingkatkan kehadiran presensi secara signifikan pada seluruh agenda wajib',
                'Segera selesaikan tugas-tugas terpending',
                'Lakukan koordinasi dengan ketua divisi untuk evaluasi keaktifan'
            ];
            $aiRecommendation = 'Perhatian! Kehadiran Anda masih sangat rendah. Mohon aktif hadir pada sesi mendatang untuk menjaga keanggotaan klub tetap aktif.';
        }

        $aiSummary = [
            'score'              => $aiScore,
            'attendanceRate'     => $attendanceRate,
            'taskRate'           => $taskRate,
            'attendedCount'      => $userAttendedCount,
            'totalMeetings'      => $totalMeetingsCount,
            'completedTasks'     => $completedTasks,
            'totalAssignedTasks' => $totalAssignedTasks,
            'badge'              => $aiBadge,
            'badgeClass'         => $aiBadgeClass,
            'statusText'         => $aiStatusText,
            'pertahankan'        => $aiPertahankan,
            'perbaikan'          => $aiPerbaikan,
            'recommendation'     => $aiRecommendation,
        ];

        if ($userRole === 'alumni') {
            $aiSummary = [
                'score'              => 100,
                'attendanceRate'     => 100,
                'taskRate'           => 100,
                'attendedCount'      => $userAttendedCount,
                'totalMeetings'      => $totalMeetingsCount,
                'completedTasks'     => $completedTasks,
                'totalAssignedTasks' => $totalAssignedTasks,
                'badge'              => 'Alumni Kehormatan (Excellence)',
                'badgeClass'         => 'bg-warning bg-opacity-25 text-warning border-warning',
                'statusText'         => 'Selamat atas kelulusan Anda! Akun Anda kini berstatus Alumni Kehormatan.',
                'pertahankan'        => [
                    'Jejak karya dan inspirasi yang telah diberikan untuk adik-adik tingkat MMC',
                    'Koneksi dan silaturahmi dengan keluarga besar Multimedia Club',
                    'Semangat berkarya dan mengukir prestasi di jenjang pendidikan atau karir selanjutnya'
                ],
                'perbaikan'          => [
                    'Bagikan pengalaman, portofolio karya, dan tips di Feed Sosial & Komunitas MMC',
                    'Bantu memberikan masukan atau motivasi bagi adik-adik tingkat'
                ],
                'recommendation'     => 'Sebagai Alumni Kehormatan, Anda bebas dari kewajiban presensi maupun penugasan harian. Portal ini tetap terbuka penuh untuk Anda!',
            ];
        }

        $infoModel = new \App\Models\InformationModel();
        $latestInformations = $infoModel->getInformationsForMember($userId, null, 15);

        $currentUser = $userModel->select('users.*, roles.name as role_name, roles.slug as role_slug')
                                 ->join('roles', 'roles.id = users.role_id', 'left')
                                 ->find($userId);

        $rankingService   = new \App\Modules\Ranking\Services\RankingService();
        $currentYear      = (int)date('Y');
        $currentMonth     = (int)date('n');
        $myMonthlyRank    = $rankingService->getUserMonthlyRank((int)$userId, $currentYear, $currentMonth);
        $currentMonthName = $rankingService->getIndonesianMonthName($currentMonth);

        $data = [
            'title'              => 'Dashboard Portal - Multimedia Club',
            'user'               => $currentUser ?: $userModel->find($userId),
            'activeMeeting'      => $activeMeeting,
            'myActiveAttendance' => $myActiveAttendance,
            'aiSummary'          => $aiSummary,
            'todayBirthdays'     => $userModel->getTodayBirthdayUsers(),
            'latestInformations' => $latestInformations,
            'myMonthlyRank'      => $myMonthlyRank,
            'currentMonthName'   => $currentMonthName,
            'currentYear'        => $currentYear,
        ];

        if (in_array($userRole, ['superadmin', 'pembina', 'bph'])) {
            $data['totalMembers']     = $userModel->where('role_id', 4)->countAllResults();
            $data['totalMeetings']    = $totalMeetingsCount;
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
            $data['myAttendances'] = $userAttendances;
            $data['myTasks']       = $userTasks;

            return view('dashboard/member', $data);
        }
    }
}
