<?php

namespace App\Modules\Cms\Controllers;

use App\Controllers\BaseController;

class AchievementCmsController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $achievements = $db->table('achievements')->orderBy('event_date', 'DESC')->get()->getResultArray();

        foreach ($achievements as &$ach) {
            $ach['team_members'] = $db->table('achievement_members')
                                      ->select('achievement_members.user_id, users.full_name, achievement_members.role_in_team')
                                      ->join('users', 'users.id = achievement_members.user_id')
                                      ->where('achievement_id', $ach['id'])
                                      ->get()->getResultArray();
        }

        $members = $db->table('users')->where('status', 'active')->get()->getResultArray();

        return view('App\Modules\Cms\Views\achievements\index', [
            'title'        => 'Manajemen Prestasi & Tim Juara',
            'achievements' => $achievements,
            'members'      => $members,
        ]);
    }

    public function store()
    {
        $db          = \Config\Database::connect();
        $title       = trim($this->request->getPost('title') ?? '');
        $competition = trim($this->request->getPost('competition') ?? '');
        $award       = trim($this->request->getPost('award') ?: 'Juara 1');
        $teamMembers = $this->request->getPost('team_members') ?? [];

        if (empty($title) || empty($competition)) {
            return redirect()->back()->with('error', 'Judul Karya & Nama Kompetisi wajib diisi.');
        }

        $db->transStart();

        $db->table('achievements')->insert([
            'title'             => $title,
            'competition'       => $competition,
            'organizer'         => trim($this->request->getPost('organizer') ?? ''),
            'category'          => trim($this->request->getPost('category') ?: 'Tingkat Nasional'),
            'award'             => $award,
            'event_date'        => $this->request->getPost('event_date') ?: date('Y-m-d'),
            'description'       => trim($this->request->getPost('description') ?? ''),
            'certificate_image' => trim($this->request->getPost('certificate_image') ?? ''),
            'is_featured'       => isset($_POST['is_featured']) ? 1 : 0,
            'created_at'        => date('Y-m-d H:i:s'),
        ]);

        $achievementId = $db->insertID();

        // Multi-Member Team Insertion
        if (is_array($teamMembers)) {
            foreach ($teamMembers as $userId) {
                $db->table('achievement_members')->insert([
                    'achievement_id' => $achievementId,
                    'user_id'        => (int)$userId,
                    'role_in_team'   => 'Anggota Tim Juara',
                    'created_at'     => date('Y-m-d H:i:s'),
                ]);
            }
        }

        $db->transComplete();

        return redirect()->to('/admin/cms/achievements')->with('success', 'Prestasi & daftar anggota tim juara berhasil disimpan.');
    }

    public function update(int $id)
    {
        $db          = \Config\Database::connect();
        $title       = trim($this->request->getPost('title') ?? '');
        $competition = trim($this->request->getPost('competition') ?? '');
        $teamMembers = $this->request->getPost('team_members') ?? [];

        if (empty($title) || empty($competition)) {
            return redirect()->back()->with('error', 'Judul Karya & Nama Kompetisi wajib diisi.');
        }

        $db->transStart();

        $db->table('achievements')->where('id', $id)->update([
            'title'             => $title,
            'competition'       => $competition,
            'organizer'         => trim($this->request->getPost('organizer') ?? ''),
            'category'          => trim($this->request->getPost('category') ?: 'Tingkat Nasional'),
            'award'             => trim($this->request->getPost('award') ?: 'Juara 1'),
            'event_date'        => $this->request->getPost('event_date') ?: date('Y-m-d'),
            'description'       => trim($this->request->getPost('description') ?? ''),
            'certificate_image' => trim($this->request->getPost('certificate_image') ?? ''),
            'is_featured'       => isset($_POST['is_featured']) ? 1 : 0,
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);

        // Sync Multi-Member Team
        $db->table('achievement_members')->where('achievement_id', $id)->delete();
        if (is_array($teamMembers)) {
            foreach ($teamMembers as $userId) {
                $db->table('achievement_members')->insert([
                    'achievement_id' => $id,
                    'user_id'        => (int)$userId,
                    'role_in_team'   => 'Anggota Tim Juara',
                    'created_at'     => date('Y-m-d H:i:s'),
                ]);
            }
        }

        $db->transComplete();

        return redirect()->to('/admin/cms/achievements')->with('success', 'Data Prestasi berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $db = \Config\Database::connect();
        $db->table('achievements')->where('id', $id)->delete();
        return redirect()->to('/admin/cms/achievements')->with('success', 'Prestasi berhasil dihapus.');
    }
}
