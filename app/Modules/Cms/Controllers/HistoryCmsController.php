<?php

namespace App\Modules\Cms\Controllers;

use App\Controllers\BaseController;

class HistoryCmsController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $history   = $db->table('club_histories')->orderBy('id', 'DESC')->get()->getRowArray();
        $missions  = $db->table('club_missions')->orderBy('sort_order', 'ASC')->get()->getResultArray();
        $timelines = $db->table('history_timelines')->orderBy('sort_order', 'ASC')->get()->getResultArray();

        return view('App\Modules\Cms\Views\history\index', [
            'title'     => 'Manajemen Sejarah Klub & Timeline',
            'history'   => $history,
            'missions'  => $missions,
            'timelines' => $timelines,
        ]);
    }

    public function saveHistory()
    {
        $db = \Config\Database::connect();
        $title   = trim($this->request->getPost('title') ?? '');
        $content = trim($this->request->getPost('content') ?? '');
        $vision  = trim($this->request->getPost('vision') ?? '');

        $existing = $db->table('club_histories')->get()->getRowArray();

        if ($existing) {
            $db->table('club_histories')->where('id', $existing['id'])->update([
                'title'      => $title,
                'content'    => $content,
                'vision'     => $vision,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $db->table('club_histories')->insert([
                'title'      => $title,
                'content'    => $content,
                'vision'     => $vision,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->to('/admin/cms/history')->with('success', 'Sejarah & Visi Klub berhasil diperbarui.');
    }

    public function saveMission()
    {
        $db   = \Config\Database::connect();
        $text = trim($this->request->getPost('mission_text') ?? '');

        if (empty($text)) {
            return redirect()->back()->with('error', 'Teks misi wajib diisi.');
        }

        $db->table('club_missions')->insert([
            'mission_text' => $text,
            'sort_order'   => (int)($this->request->getPost('sort_order') ?? 0),
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/cms/history')->with('success', 'Misi baru berhasil ditambahkan.');
    }

    public function saveTimeline()
    {
        $db    = \Config\Database::connect();
        $year  = trim($this->request->getPost('year') ?? '');
        $title = trim($this->request->getPost('title') ?? '');

        if (empty($year) || empty($title)) {
            return redirect()->back()->with('error', 'Tahun dan Judul Timeline wajib diisi.');
        }

        $db->table('history_timelines')->insert([
            'year'        => $year,
            'title'       => $title,
            'description' => trim($this->request->getPost('description') ?? ''),
            'sort_order'  => (int)($this->request->getPost('sort_order') ?? 0),
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/cms/history')->with('success', 'Timeline sejarah berhasil ditambahkan.');
    }
}
