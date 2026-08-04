<?php

namespace App\Modules\Cms\Controllers;

use App\Controllers\BaseController;
use App\Modules\Cms\Services\CmsService;

class CmsController extends BaseController
{
    protected $cmsService;
    protected $db;

    public function __construct()
    {
        $this->cmsService = new CmsService();
        $this->db         = \Config\Database::connect();
    }

    public function index()
    {
        return view('App\Modules\Cms\Views\homepage_builder', [
            'title'    => 'Homepage Section Builder & WCMS',
            'sections' => $this->cmsService->getHomepageSections(),
            'hero'     => $this->cmsService->getHeroSection(),
            'stats'    => $this->cmsService->getHomepageStats(),
        ]);
    }

    public function updateSections()
    {
        $result = $this->cmsService->updateHomepageSections($this->request->getPost('sections') ?? [], session()->get('user_id'));

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->with('error', $result['body']['message']);
        }

        return redirect()->back()->with('success', $result['body']['message']);
    }

    public function updateHero()
    {
        $result = $this->cmsService->updateHeroSection($this->request->getPost(), session()->get('user_id'));

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->with('error', $result['body']['message']);
        }

        return redirect()->back()->with('success', $result['body']['message']);
    }

    public function saveStat()
    {
        $result = $this->cmsService->saveHomepageStat($this->request->getPost(), session()->get('user_id'));

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->with('error', $result['body']['message']);
        }

        return redirect()->back()->with('success', $result['body']['message']);
    }

    public function deleteStat(int $id)
    {
        $result = $this->cmsService->deleteHomepageStat($id, session()->get('user_id'));
        return redirect()->back()->with('success', $result['body']['message']);
    }

    public function contactMessages()
    {
        $messages = $this->cmsService->getContactMessages();

        foreach ($messages as &$msg) {
            $msg['replies'] = $this->db->table('contact_replies')
                                      ->where('contact_message_id', $msg['id'])
                                      ->orderBy('created_at', 'ASC')
                                      ->get()->getResultArray();
        }

        return view('App\Modules\Cms\Views\contact_messages', [
            'title'    => 'Kritik & Saran',
            'messages' => $messages,
        ]);
    }

    public function getChatThread(int $id)
    {
        $message = $this->db->table('contact_messages')->where('id', $id)->get()->getRowArray();
        if (!$message) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pesan tidak ditemukan.']);
        }

        $replies = $this->db->table('contact_replies')
                            ->where('contact_message_id', $id)
                            ->orderBy('created_at', 'ASC')
                            ->get()->getResultArray();

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => $message,
            'replies' => $replies,
        ]);
    }

    public function replyMessage(int $id)
    {
        $text = trim($this->request->getPost('reply_text') ?? '');
        if (empty($text)) {
            return redirect()->back()->with('error', 'Balasan pesan tidak boleh kosong.');
        }

        $userRole = session()->get('role_slug');
        $isAdmin = in_array($userRole, ['superadmin', 'pembina', 'bph']);

        $senderType = $isAdmin ? 'admin' : 'member';
        $senderName = $isAdmin ? (session()->get('full_name') ?: 'Pengurus MMC') : 'Anonim';

        $this->db->table('contact_replies')->insert([
            'contact_message_id' => $id,
            'sender_type'        => $senderType,
            'sender_name'        => $senderName,
            'message'            => $text,
            'created_at'         => date('Y-m-d H:i:s'),
        ]);

        $this->db->table('contact_messages')->where('id', $id)->update([
            'status'     => 'replied',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Balasan berhasil dikirimkan.');
    }

    public function storeFeedback()
    {
        $result = $this->cmsService->saveFeedbackMessage($this->request->getPost());

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result['body']);
        }

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->withInput()->with('error', $result['body']['message']);
        }

        return redirect()->back()->with('success', $result['body']['message']);
    }

    public function submitContact()
    {
        $result = $this->cmsService->saveContactMessage($this->request->getPost());

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result['body']);
        }

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->withInput()->with('error', $result['body']['message']);
        }

        return redirect()->back()->with('success', $result['body']['message']);
    }

    public function visitorChatReply()
    {
        $msgId = (int)$this->request->getPost('contact_message_id');
        $text  = trim($this->request->getPost('message') ?? '');
        $name  = trim($this->request->getPost('sender_name') ?? 'Pengunjung');

        if ($msgId <= 0 || empty($text)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pesan tidak boleh kosong.']);
        }

        $this->db->table('contact_replies')->insert([
            'contact_message_id' => $msgId,
            'sender_type'        => 'visitor',
            'sender_name'        => $name,
            'message'            => $text,
            'created_at'         => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['status' => 'success']);
    }

    public function pollVisitorChat(int $id)
    {
        $replies = $this->db->table('contact_replies')
                            ->where('contact_message_id', $id)
                            ->orderBy('created_at', 'ASC')
                            ->get()->getResultArray();

        return $this->response->setJSON(['status' => 'success', 'data' => $replies]);
    }
}
