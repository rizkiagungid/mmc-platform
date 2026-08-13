<?php

namespace App\Modules\Chat\Controllers;

use App\Controllers\BaseController;
use App\Modules\Chat\Services\ChatService;
use App\Models\UserModel;

class ChatController extends BaseController
{
    protected $chatService;
    protected $userModel;

    public function __construct()
    {
        $this->chatService = new ChatService();
        $this->userModel   = new UserModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $conversations = $this->chatService->getUserConversations($userId);

        // Fetch all active members for "+ Chat Baru" / "+ Grup Baru" modal
        $members = $this->userModel->select('users.id, users.full_name, users.username, users.avatar, users.nis_nip, users.class_dept, roles.name as role_name, roles.slug as role_slug')
                                   ->join('roles', 'roles.id = users.role_id', 'left')
                                   ->where('users.status', 'active')
                                   ->where('users.id !=', $userId)
                                   ->orderBy('users.full_name', 'ASC')
                                   ->findAll();

        $activeConvId = (int)($this->request->getGet('conv') ?? 0);

        return view('App\Modules\Chat\Views\index', [
            'title'         => 'Inbox Pesan & Chat Grup - Multimedia Club',
            'conversations' => $conversations,
            'members'       => $members,
            'activeConvId'  => $activeConvId,
        ]);
    }

    public function getMessages(int $convId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthenticated']);
        }

        $messages = $this->chatService->getMessages($convId, $userId);
        return $this->response->setJSON(['status' => 'success', 'messages' => $messages]);
    }

    public function sendMessage(int $convId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthenticated']);
        }

        $text = $this->request->getPost('message');
        $file = $this->request->getFile('attachment');

        $result = $this->chatService->sendMessage($convId, $userId, $text, $file);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result['body']);
        }

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->with('error', $result['body']['message']);
        }

        return redirect()->to('/inbox?conv=' . $convId);
    }

    public function startDirect()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $targetUserId = (int)$this->request->getPost('target_user_id');
        $result = $this->chatService->getOrCreateDirectConversation($userId, $targetUserId);

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->with('error', $result['body']['message']);
        }

        $convId = $result['body']['data']['conversation_id'];
        return redirect()->to('/inbox?conv=' . $convId);
    }

    public function createGroup()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $name        = (string)$this->request->getPost('group_name');
        $description = (string)$this->request->getPost('group_description');
        $memberIds   = (array)$this->request->getPost('member_ids');

        $result = $this->chatService->createGroupConversation($userId, $name, $description, $memberIds);

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->withInput()->with('error', $result['body']['message']);
        }

        $convId = $result['body']['data']['conversation_id'];
        return redirect()->to('/inbox?conv=' . $convId)->with('success', $result['body']['message']);
    }

    public function deleteMessage(int $id)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $result = $this->chatService->deleteMessage($id, $userId);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result['body']);
        }

        return redirect()->back()->with('success', $result['body']['message']);
    }

    public function deleteConversation(int $convId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $result = $this->chatService->deleteConversation($convId, $userId);

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->with('error', $result['body']['message']);
        }

        return redirect()->to('/inbox')->with('success', $result['body']['message']);
    }

    public function leaveGroup(int $convId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $result = $this->chatService->leaveGroup($convId, $userId);

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->with('error', $result['body']['message']);
        }

        return redirect()->to('/inbox')->with('success', $result['body']['message']);
    }

    public function updateGroupInfo(int $convId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $postData = $this->request->getPost();
        $iconFile = $this->request->getFile('group_icon');

        $result = $this->chatService->updateGroupInfo($convId, $userId, $postData, $iconFile);

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->withInput()->with('error', $result['body']['message']);
        }

        return redirect()->to('/inbox?conv=' . $convId)->with('success', $result['body']['message']);
    }

    public function toggleGroupAdmin(int $convId, int $targetUserId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $result = $this->chatService->toggleGroupAdmin($convId, $userId, $targetUserId);

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->with('error', $result['body']['message']);
        }

        return redirect()->to('/inbox?conv=' . $convId)->with('success', $result['body']['message']);
    }

    public function removeGroupMember(int $convId, int $targetUserId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $result = $this->chatService->removeGroupMember($convId, $userId, $targetUserId);

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->with('error', $result['body']['message']);
        }

        return redirect()->to('/inbox?conv=' . $convId)->with('success', $result['body']['message']);
    }

    public function addGroupMembers(int $convId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $memberIds = (array)$this->request->getPost('member_ids');
        $result = $this->chatService->addGroupMembers($convId, $userId, $memberIds);

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->with('error', $result['body']['message']);
        }

        return redirect()->to('/inbox?conv=' . $convId)->with('success', $result['body']['message']);
    }
}
