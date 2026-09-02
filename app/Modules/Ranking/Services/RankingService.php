<?php

namespace App\Modules\Ranking\Services;

use App\Services\BaseService;

class RankingService extends BaseService
{
    /**
     * Get monthly leaderboard calculation with point breakdowns
     */
    public function getMonthlyLeaderboard(int $year, int $month, int $limit = 10): array
    {
        $startDate = sprintf('%04d-%02d-01 00:00:00', $year, $month);
        $endDate   = date('Y-m-t 23:59:59', strtotime($startDate));

        // Check if there is a manual reset cutoff for this period
        $resetCutoff = $this->getPeriodResetInfo($year, $month);
        if ($resetCutoff && strtotime($resetCutoff) > strtotime($startDate)) {
            $effectiveStartDate = $resetCutoff;
        } else {
            $effectiveStartDate = $startDate;
        }

        $db = \Config\Database::connect();

        // 1. Get all active users eligible for leaderboard (exclude superadmin, alumni, pembina)
        $users = $db->table('users')
            ->select('users.id, users.full_name, users.username, users.avatar, users.class_dept, users.role_id, roles.name as role_name, roles.slug as role_slug')
            ->join('roles', 'roles.id = users.role_id', 'left')
            ->where('users.status', 'active')
            ->where('users.deleted_at IS NULL')
            ->whereNotIn('roles.slug', ['superadmin', 'alumni', 'pembina'])
            ->get()
            ->getResultArray();

        if (empty($users)) {
            return [];
        }

        $userIds = array_column($users, 'id');

        // 2. Aggregate Attendance Points
        // present = 25 pts, late = 15 pts, sick/permitted = 5 pts
        $attQuery = $db->table('attendances')
            ->select("user_id, 
                SUM(CASE 
                    WHEN status = 'present' THEN 25 
                    WHEN status = 'late' THEN 15 
                    WHEN status IN ('sick', 'permitted') THEN 5 
                    ELSE 0 
                END) as att_points,
                COUNT(id) as total_attendance,
                SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late_count")
            ->whereIn('user_id', $userIds)
            ->where("COALESCE(scan_time, created_at) >= '{$effectiveStartDate}'")
            ->where("COALESCE(scan_time, created_at) <= '{$endDate}'")
            ->groupBy('user_id')
            ->get()
            ->getResultArray();

        $attendanceMap = [];
        foreach ($attQuery as $row) {
            $attendanceMap[$row['user_id']] = [
                'points'     => (int)($row['att_points'] ?? 0),
                'total'      => (int)($row['total_attendance'] ?? 0),
                'present'    => (int)($row['present_count'] ?? 0),
                'late'       => (int)($row['late_count'] ?? 0),
            ];
        }

        // 3. Aggregate Task Submissions & Grade Points
        // 20 pts per submission + (grade * 0.5)
        $taskQuery = $db->table('task_submissions')
            ->select("user_id,
                COUNT(id) as total_submitted,
                SUM(20 + CASE WHEN grade IS NOT NULL THEN (grade * 0.5) ELSE 0 END) as task_points,
                AVG(grade) as avg_grade")
            ->whereIn('user_id', $userIds)
            ->where("COALESCE(submitted_at, updated_at) >= '{$effectiveStartDate}'")
            ->where("COALESCE(submitted_at, updated_at) <= '{$endDate}'")
            ->groupBy('user_id')
            ->get()
            ->getResultArray();

        $taskMap = [];
        foreach ($taskQuery as $row) {
            $taskMap[$row['user_id']] = [
                'points'          => (int)round($row['task_points'] ?? 0),
                'total_submitted' => (int)($row['total_submitted'] ?? 0),
                'avg_grade'       => $row['avg_grade'] !== null ? round($row['avg_grade'], 1) : null,
            ];
        }

        // 4. Aggregate Feed Posts (1 pt per post, max 20 pts)
        $postQuery = $db->table('posts')
            ->select('user_id, COUNT(id) as total_posts')
            ->whereIn('user_id', $userIds)
            ->where('deleted_at IS NULL')
            ->where("created_at >= '{$effectiveStartDate}'")
            ->where("created_at <= '{$endDate}'")
            ->groupBy('user_id')
            ->get()
            ->getResultArray();

        $postMap = [];
        foreach ($postQuery as $row) {
            $count = (int)($row['total_posts'] ?? 0);
            $points = min(20, $count * 1);
            $postMap[$row['user_id']] = [
                'count'  => $count,
                'points' => $points,
            ];
        }

        // 5. Aggregate Feed Comments (1 pt per comment, max 20 pts)
        $commentQuery = $db->table('post_comments')
            ->select('user_id, COUNT(id) as total_comments')
            ->whereIn('user_id', $userIds)
            ->where("created_at >= '{$effectiveStartDate}'")
            ->where("created_at <= '{$endDate}'")
            ->groupBy('user_id')
            ->get()
            ->getResultArray();

        $commentMap = [];
        foreach ($commentQuery as $row) {
            $count = (int)($row['total_comments'] ?? 0);
            $points = min(20, $count * 1);
            $commentMap[$row['user_id']] = [
                'count'  => $count,
                'points' => $points,
            ];
        }

        // 6. Combine and calculate Total Score for each user
        $leaderboard = [];
        foreach ($users as $u) {
            $uid = $u['id'];

            $attData     = $attendanceMap[$uid] ?? ['points' => 0, 'total' => 0, 'present' => 0, 'late' => 0];
            $taskData    = $taskMap[$uid] ?? ['points' => 0, 'total_submitted' => 0, 'avg_grade' => null];
            $postData    = $postMap[$uid] ?? ['count' => 0, 'points' => 0];
            $commentData = $commentMap[$uid] ?? ['count' => 0, 'points' => 0];

            $feedPoints  = $postData['points'] + $commentData['points'];
            $totalPoints = $attData['points'] + $taskData['points'] + $feedPoints;

            $leaderboard[] = [
                'user_id'            => $uid,
                'full_name'          => $u['full_name'],
                'username'           => $u['username'],
                'avatar'             => $u['avatar'],
                'class_dept'         => $u['class_dept'],
                'role_name'          => $u['role_name'],
                'role_slug'          => $u['role_slug'],
                'attendance_points'  => $attData['points'],
                'attendance_count'   => $attData['total'],
                'task_points'        => $taskData['points'],
                'task_count'         => $taskData['total_submitted'],
                'task_avg_grade'     => $taskData['avg_grade'],
                'feed_points'        => $feedPoints,
                'feed_post_count'    => $postData['count'],
                'feed_comment_count' => $commentData['count'],
                'total_points'       => $totalPoints,
            ];
        }

        // 8. Sort descending by total_points, then attendance_points, then task_points
        usort($leaderboard, function ($a, $b) {
            if ($b['total_points'] !== $a['total_points']) {
                return $b['total_points'] <=> $a['total_points'];
            }
            if ($b['attendance_points'] !== $a['attendance_points']) {
                return $b['attendance_points'] <=> $a['attendance_points'];
            }
            if ($b['task_points'] !== $a['task_points']) {
                return $b['task_points'] <=> $a['task_points'];
            }
            return strcmp($a['full_name'], $b['full_name']);
        });

        // 9. Assign ranks and apply limit
        $ranked = [];
        $rank = 1;
        foreach ($leaderboard as $item) {
            $item['rank'] = $rank++;
            $ranked[] = $item;
        }

        return $limit > 0 ? array_slice($ranked, 0, $limit) : $ranked;
    }

    /**
     * Get specific user ranking for the current month
     */
    public function getUserMonthlyRank(int $userId, int $year, int $month): ?array
    {
        $allRanked = $this->getMonthlyLeaderboard($year, $month, 0);
        foreach ($allRanked as $item) {
            if ((int)$item['user_id'] === $userId) {
                return $item;
            }
        }
        return null;
    }

    /**
     * Get list of historical months available for filter selection
     */
    public function getAvailableMonths(int $numMonths = 12): array
    {
        $months = [];
        $current = new \DateTime();

        for ($i = 0; $i < $numMonths; $i++) {
            $y = (int)$current->format('Y');
            $m = (int)$current->format('n');
            $label = $this->getIndonesianMonthName($m) . ' ' . $y;

            $months[] = [
                'year'    => $y,
                'month'   => $m,
                'key'     => sprintf('%04d-%02d', $y, $m),
                'label'   => $label,
                'is_current' => ($i === 0),
            ];

            $current->modify('-1 month');
        }

        return $months;
    }

    /**
     * Translate month number to Indonesian name
     */
    public function getIndonesianMonthName(int $month): string
    {
        $monthNames = [
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        return $monthNames[$month] ?? 'Bulan ' . $month;
    }

    /**
     * Reset points for a given month/year period by setting a reset cutoff timestamp
     */
    public function resetPeriodPoints(int $year, int $month, ?int $operatorId = null): array
    {
        $resetKey   = sprintf('ranking_reset_cutoff_%04d_%02d', $year, $month);
        $currentNow = date('Y-m-d H:i:s');

        helper('setting');
        set_setting($resetKey, $currentNow);

        $monthName = $this->getIndonesianMonthName($month);

        try {
            $auditLogModel = new \App\Models\AuditLogModel();
            $auditLogModel->recordLog($operatorId, 'RANKING_RESET', "Mereset skor perolehan Ranking MM periode {$monthName} {$year} menjadi 0 poin pada {$currentNow}");
        } catch (\Throwable $e) {
            // Log fallback
        }

        return $this->success("Skor Ranking MM periode {$monthName} {$year} berhasil direset menjadi 0 Poin.");
    }

    /**
     * Cancel/restore reset points for a given period
     */
    public function cancelResetPeriodPoints(int $year, int $month, ?int $operatorId = null): array
    {
        $resetKey = sprintf('ranking_reset_cutoff_%04d_%02d', $year, $month);
        
        $db = \Config\Database::connect();
        $db->table('settings')->where('setting_key', $resetKey)->delete();

        $monthName = $this->getIndonesianMonthName($month);

        try {
            $auditLogModel = new \App\Models\AuditLogModel();
            $auditLogModel->recordLog($operatorId, 'RANKING_RESTORE', "Membatalkan reset skor Ranking MM periode {$monthName} {$year}");
        } catch (\Throwable $e) {
            // Log fallback
        }

        return $this->success("Reset skor Ranking MM periode {$monthName} {$year} berhasil dibatalkan. Skor historis telah dipulihkan.");
    }

    /**
     * Get period reset cutoff timestamp if any
     */
    public function getPeriodResetInfo(int $year, int $month): ?string
    {
        $resetKey = sprintf('ranking_reset_cutoff_%04d_%02d', $year, $month);
        helper('setting');
        return get_setting($resetKey, null);
    }
}
