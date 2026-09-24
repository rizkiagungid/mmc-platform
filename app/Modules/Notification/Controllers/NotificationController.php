<?php

namespace App\Modules\Notification\Controllers;

use App\Controllers\BaseController;
use App\Models\NotificationModel;

class NotificationController extends BaseController
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $filterType = $this->request->getGet('type') ?: 'all';
        $notifications = $this->notificationModel->getUserNotifications($userId, $filterType, 100);
        $unreadCount   = $this->notificationModel->getUnreadCount($userId);

        return view('App\Modules\Notification\Views\index', [
            'title'         => 'Pusat Notifikasi - Multimedia Club System',
            'notifications' => $notifications,
            'unreadCount'   => $unreadCount,
            'filterType'    => $filterType,
        ]);
    }

    public function unreadCount()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['unread_count' => 0]);
        }

        $count = $this->notificationModel->getUnreadCount($userId);
        return $this->response->setJSON(['unread_count' => $count]);
    }

    public function dropdown()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['unread_count' => 0, 'notifications' => []]);
        }

        $unreadCount   = $this->notificationModel->getUnreadCount($userId);
        $notifications = $this->notificationModel->getUnreadNotifications($userId, 7);

        // If unread notifications are fewer than 5, get recent notifications regardless of read status
        if (count($notifications) < 5) {
            $notifications = $this->notificationModel->getUserNotifications($userId, 'all', 7);
        }

        return $this->response->setJSON([
            'unread_count'  => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    public function markRead($id)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $notification = $this->notificationModel->where('id', $id)->where('user_id', $userId)->first();
        if ($notification) {
            $this->notificationModel->update($id, ['is_read' => 1]);

            if (!empty($notification['link'])) {
                $targetUrl = str_starts_with($notification['link'], 'http') 
                    ? $notification['link'] 
                    : base_url($notification['link']);
                return redirect()->to($targetUrl);
            }

            // Fallback redirect for task/evaluation notifications without an explicit link
            if (in_array($notification['type'], ['task', 'task_eval', 'mention']) || str_contains(strtolower($notification['title']), 'tugas')) {
                $userRole = session()->get('role_slug');
                $isAdmin  = in_array($userRole, ['superadmin', 'pembina', 'bph']);
                return redirect()->to(base_url($isAdmin ? 'admin/tasks' : 'member/tasks'));
            }
        }

        return redirect()->back()->with('success', 'Notifikasi ditandai telah dibaca.');
    }

    public function markAllRead()
    {
        $userId = session()->get('user_id');
        if ($userId) {
            $this->notificationModel->where('user_id', $userId)->set(['is_read' => 1])->update();
        }

        return redirect()->back()->with('success', 'Semua notifikasi ditandai telah dibaca.');
    }

    public function delete($id)
    {
        $userId = session()->get('user_id');
        if ($userId) {
            $this->notificationModel->where('id', $id)->where('user_id', $userId)->delete();
        }

        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    public function clearAll()
    {
        $userId = session()->get('user_id');
        if ($userId) {
            $this->notificationModel->where('user_id', $userId)->delete();
        }

        return redirect()->back()->with('success', 'Seluruh riwayat notifikasi telah dibersihkan.');
    }

    public function checkNew()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON([
                'status'        => 'unauthenticated',
                'unread_count'  => 0,
                'unread_chat'   => 0,
                'notifications' => [],
                'latest_id'     => 0,
            ]);
        }

        $lastId = (int)($this->request->getVar('last_id') ?? 0);

        $unreadCount = $this->notificationModel->getUnreadCount($userId);

        $builder = $this->notificationModel->where('user_id', $userId)
                                           ->where('is_read', 0);
        if ($lastId > 0) {
            $builder->where('id >', $lastId);
        }

        $newNotifs = $builder->orderBy('id', 'ASC')->findAll(10);

        $maxId = $lastId;
        if (!empty($newNotifs)) {
            $maxId = max(array_column($newNotifs, 'id'));
        } else {
            // Find overall max id for user if lastId is 0
            $latestRow = $this->notificationModel->where('user_id', $userId)->orderBy('id', 'DESC')->first();
            if ($latestRow) {
                $maxId = (int)$latestRow['id'];
            }
        }

        // Calculate unread chat messages
        $unreadChat = 0;
        try {
            $chatParticipantModel = new \App\Models\ChatParticipantModel();
            $chatMessageModel     = new \App\Models\ChatMessageModel();
            $userConvs            = array_column($chatParticipantModel->where('user_id', $userId)->findAll(), 'conversation_id');
            if (!empty($userConvs)) {
                $unreadChat = $chatMessageModel->whereIn('conversation_id', $userConvs)
                                               ->where('sender_id !=', $userId)
                                               ->where('is_read', 0)
                                               ->countAllResults();
            }
        } catch (\Throwable $e) {
            $unreadChat = 0;
        }

        // Format links for notifications
        foreach ($newNotifs as &$n) {
            if (!empty($n['link'])) {
                $n['target_url'] = str_starts_with($n['link'], 'http') ? $n['link'] : base_url($n['link']);
            } else {
                $n['target_url'] = base_url('notifications');
            }
        }
        unset($n);

        return $this->response->setJSON([
            'status'        => 'success',
            'unread_count'  => $unreadCount,
            'unread_chat'   => $unreadChat,
            'notifications' => $newNotifs,
            'latest_id'     => $maxId,
        ]);
    }

    public function testPush()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Silakan login terlebih dahulu.']);
        }

        $notifId = $this->notificationModel->notifyUser(
            $userId,
            'Tes Notifikasi MMC Berhasil! 🎉',
            'Notifikasi sistem Multimedia Club kini aktif dan siap muncul di layar HP atau Laptop Anda.',
            'general',
            base_url('notifications')
        );

        return $this->response->setJSON([
            'status'       => 'success',
            'message'      => 'Notifikasi uji coba berhasil dikirim ke perangkat Anda!',
            'notif_id'     => $notifId,
            'title'        => 'Tes Notifikasi MMC Berhasil! 🎉',
            'body'         => 'Notifikasi sistem Multimedia Club kini aktif dan siap muncul di layar HP atau Laptop Anda.',
            'url'          => base_url('notifications'),
        ]);
    }
}
