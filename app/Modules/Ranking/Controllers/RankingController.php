<?php

namespace App\Modules\Ranking\Controllers;

use App\Controllers\BaseController;
use App\Modules\Ranking\Services\RankingService;

class RankingController extends BaseController
{
    protected $rankingService;

    public function __construct()
    {
        $this->rankingService = new RankingService();
    }

    public function index()
    {
        $selectedPeriod = $this->request->getGet('period');
        
        if (!empty($selectedPeriod) && preg_match('/^(\d{4})-(\d{2})$/', $selectedPeriod, $matches)) {
            $year  = (int)$matches[1];
            $month = (int)$matches[2];
        } else {
            $year  = (int)date('Y');
            $month = (int)date('n');
            $selectedPeriod = sprintf('%04d-%02d', $year, $month);
        }

        $leaderboard     = $this->rankingService->getMonthlyLeaderboard($year, $month, 10);
        $availableMonths = $this->rankingService->getAvailableMonths(12);
        $currentMonthName = $this->rankingService->getIndonesianMonthName($month);

        $userId = (int)session()->get('user_id');
        $myRank = $this->rankingService->getUserMonthlyRank($userId, $year, $month);
        $resetCutoff = $this->rankingService->getPeriodResetInfo($year, $month);

        return view('App\Modules\Ranking\Views\index', [
            'title'            => 'Ranking MM - Papan Peringkat Keaktifan & Prestasi',
            'leaderboard'      => $leaderboard,
            'availableMonths'  => $availableMonths,
            'selectedPeriod'   => $selectedPeriod,
            'selectedYear'     => $year,
            'selectedMonth'    => $month,
            'monthName'        => $currentMonthName,
            'myRank'           => $myRank,
            'resetCutoff'      => $resetCutoff,
            'isCurrentMonth'   => ($year === (int)date('Y') && $month === (int)date('n')),
        ]);
    }

    /**
     * Handle manual point reset for a period (Superadmin only)
     */
    public function resetPeriod()
    {
        $roleSlug = session()->get('role_slug');
        if ($roleSlug !== 'superadmin') {
            return redirect()->back()->with('error', 'Hanya Superadmin yang memiliki otoritas untuk mereset poin ranking.');
        }

        $period = $this->request->getPost('period');
        if (empty($period) || !preg_match('/^(\d{4})-(\d{2})$/', $period, $matches)) {
            return redirect()->back()->with('error', 'Format periode tidak valid.');
        }

        $year  = (int)$matches[1];
        $month = (int)$matches[2];
        $userId = (int)session()->get('user_id');

        $result = $this->rankingService->resetPeriodPoints($year, $month, $userId);
        $msg = $result['body']['message'] ?? $result['message'] ?? 'Skor ranking berhasil direset menjadi 0.';

        return redirect()->to(base_url('ranking?period=' . $period))->with('success', $msg);
    }

    /**
     * Restore / Cancel point reset for a period (Superadmin only)
     */
    public function cancelResetPeriod()
    {
        $roleSlug = session()->get('role_slug');
        if ($roleSlug !== 'superadmin') {
            return redirect()->back()->with('error', 'Hanya Superadmin yang memiliki otoritas untuk membatalkan reset.');
        }

        $period = $this->request->getPost('period');
        if (empty($period) || !preg_match('/^(\d{4})-(\d{2})$/', $period, $matches)) {
            return redirect()->back()->with('error', 'Format periode tidak valid.');
        }

        $year  = (int)$matches[1];
        $month = (int)$matches[2];
        $userId = (int)session()->get('user_id');

        $result = $this->rankingService->cancelResetPeriodPoints($year, $month, $userId);
        $msg = $result['body']['message'] ?? $result['message'] ?? 'Reset skor ranking berhasil dibatalkan.';

        return redirect()->to(base_url('ranking?period=' . $period))->with('success', $msg);
    }
}
