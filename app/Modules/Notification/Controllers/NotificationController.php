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
}
