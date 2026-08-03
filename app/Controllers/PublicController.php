<?php

namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\UserModel;
use App\Models\MeetingModel;
use App\Models\TaskModel;

class PublicController extends BaseController
{
    protected $settingModel;
    protected $db;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
        $this->db           = \Config\Database::connect();
    }

    public function index()
    {
        $userModel    = new UserModel();
        $meetingModel = new MeetingModel();
        $taskModel    = new TaskModel();

        // 1. Dynamic Homepage Sections Builder & Order
        $sections = $this->db->table('homepage_sections')
                             ->where('is_active', 1)
                             ->orderBy('sort_order', 'ASC')
                             ->get()->getResultArray();

        // 2. Hero Section
        $hero = $this->db->table('hero_sections')->orderBy('id', 'DESC')->get()->getRowArray();

        // 3. Stats
        $stats = $this->db->table('homepage_stats')
                          ->where('is_active', 1)
                          ->orderBy('sort_order', 'ASC')
                          ->get()->getResultArray();

        // 4. Divisions
        $divisions = $this->db->table('divisions')
                              ->where('status', 'active')
                              ->orderBy('sort_order', 'ASC')
                              ->get()->getResultArray();

        // 5. Featured Portfolios
        $portfolios = $this->db->table('portfolios')
                               ->orderBy('id', 'DESC')
                               ->get()->getResultArray();

        foreach ($portfolios as &$p) {
            $p['contributors'] = $this->db->table('portfolio_contributors')
                                          ->select('users.full_name')
                                          ->join('users', 'users.id = portfolio_contributors.user_id')
                                          ->where('portfolio_id', $p['id'])
                                          ->get()->getResultArray();
        }

        // 6. FAQs
        $faqs = $this->db->table('faqs')
                         ->where('status', 'active')
                         ->orderBy('sort_order', 'ASC')
                         ->get()->getResultArray();

        // 7. Achievements & Winning Teams
        $achievements = $this->db->table('achievements')
                                 ->orderBy('event_date', 'DESC')
                                 ->get()->getResultArray();

        foreach ($achievements as &$ach) {
            $ach['team_members'] = $this->db->table('achievement_members')
                                            ->select('achievement_members.user_id, users.full_name, users.avatar, users.class_dept, achievement_members.role_in_team')
                                            ->join('users', 'users.id = achievement_members.user_id')
                                            ->where('achievement_id', $ach['id'])
                                            ->get()->getResultArray();
        }

        return view('public/home', [
            'title'          => 'Multimedia Club SMAN 1 Tamansari - Home',
            'siteTitle'      => $this->settingModel->getSetting('site_title', 'Multimedia Club SMAN 1 Tamansari'),
            'sections'       => $sections,
            'hero'           => $hero,
            'stats'          => $stats,
            'divisions'      => $divisions,
            'portfolios'     => $portfolios,
            'achievements'   => $achievements,
            'faqs'           => $faqs,
            'totalMembers'   => $userModel->where('status', 'active')->countAllResults(),
            'totalMeetings'  => $meetingModel->countAllResults(),
            'totalTasks'     => $taskModel->countAllResults(),
            'activeMeeting'  => $meetingModel->getActiveMeeting(),
        ]);
    }

    public function achievements()
    {
        $achievements = $this->db->table('achievements')
                                 ->orderBy('event_date', 'DESC')
                                 ->get()->getResultArray();

        foreach ($achievements as &$ach) {
            $ach['team_members'] = $this->db->table('achievement_members')
                                            ->select('achievement_members.user_id, users.full_name, users.avatar, users.class_dept, achievement_members.role_in_team')
                                            ->join('users', 'users.id = achievement_members.user_id')
                                            ->where('achievement_id', $ach['id'])
                                            ->get()->getResultArray();
        }

        $totalNational = 0;
        $totalGold     = 0;
        foreach ($achievements as $a) {
            if (strpos(strtolower($a['category']), 'nasional') !== false) {
                $totalNational++;
            }
            if (strpos(strtolower($a['award']), 'juara 1') !== false || strpos(strtolower($a['award']), 'emas') !== false) {
                $totalGold++;
            }
        }

        return view('public/achievements', [
            'title'         => 'Prestasi & Tim Juara - Multimedia Club',
            'siteTitle'     => $this->settingModel->getSetting('site_title', 'Multimedia Club SMAN 1 Tamansari'),
            'achievements'  => $achievements,
            'totalCount'    => count($achievements),
            'totalNational' => $totalNational,
            'totalGold'     => $totalGold,
        ]);
    }

    public function about()
    {
        $history   = $this->db->table('club_histories')->orderBy('id', 'DESC')->get()->getRowArray();
        $missions  = $this->db->table('club_missions')->orderBy('sort_order', 'ASC')->get()->getResultArray();
        $timelines = $this->db->table('history_timelines')->orderBy('sort_order', 'ASC')->get()->getResultArray();
        $logos     = $this->db->table('logo_histories')->orderBy('sort_order', 'ASC')->get()->getResultArray();
        $structures= $this->db->table('org_structures')->where('status', 'active')->orderBy('sort_order', 'ASC')->get()->getResultArray();

        return view('public/about', [
            'title'      => 'Tentang Kami & Sejarah - Multimedia Club',
            'siteTitle'  => $this->settingModel->getSetting('site_title', 'Multimedia Club SMAN 1 Tamansari'),
            'history'    => $history,
            'missions'   => $missions,
            'timelines'  => $timelines,
            'logos'      => $logos,
            'structures' => $structures,
        ]);
    }

    public function learningPath()
    {
        $divisions = $this->db->table('divisions')
                              ->where('status', 'active')
                              ->orderBy('sort_order', 'ASC')
                              ->get()->getResultArray();

        foreach ($divisions as &$div) {
            $div['programs'] = $this->db->table('learning_programs')
                                        ->where('division_id', $div['id'])
                                        ->orderBy('sort_order', 'ASC')
                                        ->get()->getResultArray();
        }

        return view('public/learning_path', [
            'title'     => 'Learning Path & Kurikulum Divisi - Multimedia Club',
            'siteTitle' => $this->settingModel->getSetting('site_title', 'Multimedia Club SMAN 1 Tamansari'),
            'divisions' => $divisions,
        ]);
    }

    public function portfolio()
    {
        $portfolios = $this->db->table('portfolios')->orderBy('id', 'DESC')->get()->getResultArray();

        foreach ($portfolios as &$p) {
            $p['contributors'] = $this->db->table('portfolio_contributors')
                                          ->select('users.full_name')
                                          ->join('users', 'users.id = portfolio_contributors.user_id')
                                          ->where('portfolio_id', $p['id'])
                                          ->get()->getResultArray();
        }

        return view('public/portfolio', [
            'title'      => 'Showcase Portofolio Karya - Multimedia Club',
            'siteTitle'  => $this->settingModel->getSetting('site_title', 'Multimedia Club SMAN 1 Tamansari'),
            'portfolios' => $portfolios,
        ]);
    }

    public function gallery()
    {
        $albums = $this->db->table('gallery_albums')->orderBy('id', 'DESC')->get()->getResultArray();

        return view('public/gallery', [
            'title'     => 'Galeri Kegiatan & Workshop - Multimedia Club',
            'siteTitle' => $this->settingModel->getSetting('site_title', 'Multimedia Club SMAN 1 Tamansari'),
            'albums'    => $albums,
        ]);
    }

    public function faq()
    {
        $faqs = $this->db->table('faqs')->where('status', 'active')->orderBy('sort_order', 'ASC')->get()->getResultArray();

        return view('public/faq', [
            'title'     => 'Frequently Asked Questions (FAQ) - Multimedia Club',
            'siteTitle' => $this->settingModel->getSetting('site_title', 'Multimedia Club SMAN 1 Tamansari'),
            'faqs'      => $faqs,
        ]);
    }
}
