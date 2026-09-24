<?php

namespace App\Modules\Chat\Services;

use App\Services\BaseService;
use App\Models\ChatConversationModel;
use App\Models\ChatParticipantModel;
use App\Models\ChatMessageModel;
use App\Models\UserModel;
use App\Models\NotificationModel;
use App\Models\AuditLogModel;

class ChatService extends BaseService
{
    protected $conversationModel;
    protected $participantModel;
    protected $messageModel;
    protected $userModel;
    protected $notificationModel;
    protected $auditLogModel;

    public function __construct()
    {
        parent::__construct();
        $this->conversationModel = new ChatConversationModel();
        $this->participantModel    = new ChatParticipantModel();
        $this->messageModel        = new ChatMessageModel();
        $this->userModel           = new UserModel();
        $this->notificationModel   = new NotificationModel();
        $this->auditLogModel       = new AuditLogModel();
    }

    /**
     * Get all active conversations for a user with unread count and latest message preview
     */
    public function getUserConversations(int $userId): array
    {
        // 1. Get conversation IDs where user is a participant
        $participants = $this->db->table('chat_participants')
                                 ->where('user_id', $userId)
                                 ->get()->getResultArray();
        if (empty($participants)) {
            return [];
        }

        $convIds = array_column($participants, 'conversation_id');
        $conversations = $this->conversationModel->whereIn('id', $convIds)
                                                 ->orderBy('updated_at', 'DESC')
                                                 ->findAll();

        $userRole = session()->get('role_slug');
        $isSystemAdmin = in_array($userRole, ['superadmin', 'pembina', 'bph']);

        foreach ($conversations as &$c) {
            $convId = (int)$c['id'];

            // Fetch creator details
            $creator = $c['created_by'] ? $this->userModel->find($c['created_by']) : null;
            $c['creator_name'] = $creator ? $creator['full_name'] : 'Pengurus Club';
            $c['created_at_formatted'] = !empty($c['created_at']) ? date('d M Y, H:i', strtotime($c['created_at'])) : '-';

            // Fetch participants with group roles and complete user profile info
            $allParts = $this->db->table('chat_participants')
                                 ->select('chat_participants.role as group_role, chat_participants.joined_at, users.id, users.full_name, users.username, users.email, users.phone, users.avatar, users.nis_nip, users.class_dept, users.status, roles.name as role_name')
                                 ->join('users', 'users.id = chat_participants.user_id')
                                 ->join('roles', 'roles.id = users.role_id', 'left')
                                 ->where('chat_participants.conversation_id', $convId)
                                 ->get()->getResultArray();

            $c['participants'] = $allParts;

            // My group role & permissions
            $myPart = array_filter($allParts, function($p) use ($userId) {
                return (int)$p['id'] === $userId;
            });
            $myRoleObj = !empty($myPart) ? reset($myPart) : null;
            $myGroupRole = $myRoleObj ? ($myRoleObj['group_role'] ?? 'member') : 'member';

            $c['my_group_role']  = $myGroupRole;
            $c['is_creator']      = ((int)$c['created_by'] === $userId);
            $c['is_group_admin']  = ($c['is_creator'] || $myGroupRole === 'admin' || $isSystemAdmin);

            // For Direct Chat, resolve title & avatar to the OTHER user
            if ($c['type'] === 'direct') {
                $otherUser = array_filter($allParts, function($p) use ($userId) {
                    return (int)$p['id'] !== $userId;
                });
                $other = !empty($otherUser) ? reset($otherUser) : null;

                $c['display_name']   = $other ? $other['full_name'] : ($c['name'] ?: 'Pengguna MMC');
                $c['display_avatar'] = $other ? $other['avatar'] : null;
                $c['display_sub']    = $other ? ($other['class_dept'] ?: $other['role_name']) : 'Chat Personal';
                $c['target_user_id'] = $other ? (int)$other['id'] : null;
            } else {
                $c['display_name']   = $c['name'] ?: 'Grup MMC';
                $c['display_avatar'] = $c['icon'];
                $c['display_sub']    = count($allParts) . ' Anggota Grup';
                $c['target_user_id'] = null;
            }

            // Get last message
            $lastMsg = $this->messageModel->where('conversation_id', $convId)
                                          ->orderBy('created_at', 'DESC')
                                          ->first();
            $c['last_message']      = $lastMsg ? $lastMsg['message'] : 'Belum ada pesan';
            $c['last_message_time'] = $lastMsg ? $lastMsg['created_at'] : $c['updated_at'];

            // Get unread count for this user (messages sent by OTHERS and is_read = 0)
            $unreadCount = $this->messageModel->where('conversation_id', $convId)
                                              ->where('sender_id !=', $userId)
                                              ->where('is_read', 0)
                                              ->countAllResults();
            $c['unread_count'] = $unreadCount;
        }

        return $conversations;
    }

    /**
     * Get existing 1-on-1 direct conversation or create a new one
     */
    public function getOrCreateDirectConversation(int $userId, int $targetUserId): array
    {
        if ($userId === $targetUserId) {
            return $this->error('Tidak dapat membuat pesan dengan akun Anda sendiri.');
        }

        $targetUser = $this->userModel->find($targetUserId);
        if (!$targetUser) {
            return $this->error('Anggota tujuan tidak ditemukan.');
        }

        // Find existing direct conversation shared by both users
        $userConvs = array_column($this->db->table('chat_participants')->where('user_id', $userId)->get()->getResultArray(), 'conversation_id');
        $targetConvs = array_column($this->db->table('chat_participants')->where('user_id', $targetUserId)->get()->getResultArray(), 'conversation_id');

        $commonIds = array_intersect($userConvs, $targetConvs);

        if (!empty($commonIds)) {
            $existing = $this->conversationModel->whereIn('id', $commonIds)
                                                ->where('type', 'direct')
                                                ->first();
            if ($existing) {
                return $this->success('Percakapan ditemukan.', ['conversation_id' => (int)$existing['id']]);
            }
        }

        // Create new Direct Conversation
        $this->beginTransaction();

        try {
            $convId = $this->conversationModel->insert([
                'uuid'       => $this->conversationModel->generateUuid(),
                'type'       => 'direct',
                'name'       => null,
                'created_by' => $userId,
            ]);

            $now = date('Y-m-d H:i:s');
            $this->participantModel->insertBatch([
                ['conversation_id' => $convId, 'user_id' => $userId, 'role' => 'admin', 'joined_at' => $now],
                ['conversation_id' => $convId, 'user_id' => $targetUserId, 'role' => 'member', 'joined_at' => $now],
            ]);

            $this->commitTransaction();
            return $this->success('Percakapan baru berhasil dibuat.', ['conversation_id' => $convId]);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal membuat percakapan: ' . $e->getMessage());
        }
    }

    /**
     * Create a new Group Conversation
     */
    public function createGroupConversation(int $creatorId, string $name, ?string $description, array $memberIds): array
    {
        $name = trim($name);
        if (empty($name)) {
            return $this->error('Nama grup obrolan wajib diisi.');
        }

        $memberIds = array_unique(array_filter(array_map('intval', $memberIds)));
        if (!in_array($creatorId, $memberIds)) {
            $memberIds[] = $creatorId;
        }

        if (count($memberIds) < 2) {
            return $this->error('Pilih minimal 1 anggota lain untuk membentuk grup obrolan.');
        }

        $this->beginTransaction();

        try {
            $convId = $this->conversationModel->insert([
                'uuid'        => $this->conversationModel->generateUuid(),
                'type'        => 'group',
                'name'        => $name,
                'description' => $description ? trim($description) : null,
                'created_by'  => $creatorId,
            ]);

            $now = date('Y-m-d H:i:s');
            $batch = [];
            foreach ($memberIds as $uid) {
                $batch[] = [
                    'conversation_id' => $convId,
                    'user_id'         => $uid,
                    'role'            => ($uid === $creatorId) ? 'admin' : 'member',
                    'joined_at'       => $now,
                ];
            }
            $this->participantModel->insertBatch($batch);

            // Send notification to members
            $otherMembers = array_diff($memberIds, [$creatorId]);
            $creator = $this->userModel->find($creatorId);
            $creatorName = $creator ? $creator['full_name'] : 'Pengurus';

            $this->notificationModel->notifyUsers(
                $otherMembers,
                'Grup Obrolan Baru: ' . $name,
                "{$creatorName} telah menambahkan Anda ke dalam grup obrolan '{$name}'",
                'chat',
                base_url('inbox?conv=' . $convId)
            );

            $this->auditLogModel->recordLog($creatorId, 'CHAT_GROUP_CREATE', "Membuat grup obrolan baru: {$name} dengan " . count($memberIds) . " anggota.");

            $this->commitTransaction();
            return $this->success("Grup '{$name}' berhasil dibuat!", ['conversation_id' => $convId]);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal membuat grup: ' . $e->getMessage());
        }
    }

    /**
     * Get messages feed for a conversation
     */
    public function getMessages(int $conversationId, int $userId, int $limit = 100): array
    {
        // Check if user is participant
        $part = $this->participantModel->where('conversation_id', $conversationId)
                                       ->where('user_id', $userId)
                                       ->first();
        if (!$part) {
            return [];
        }

        // Mark unread messages sent by others as read
        $this->messageModel->where('conversation_id', $conversationId)
                           ->where('sender_id !=', $userId)
                           ->where('is_read', 0)
                           ->set(['is_read' => 1])
                           ->update();

        $messages = $this->db->table('chat_messages')
                             ->select('chat_messages.*, users.full_name as sender_name, users.avatar as sender_avatar, roles.name as sender_role')
                             ->join('users', 'users.id = chat_messages.sender_id')
                             ->join('roles', 'roles.id = users.role_id', 'left')
                             ->where('chat_messages.conversation_id', $conversationId)
                             ->orderBy('chat_messages.created_at', 'ASC')
                             ->get()->getResultArray();

        return $messages;
    }

    /**
     * Send a new message or file attachment
     */
    public function sendMessage(int $conversationId, int $senderId, ?string $messageText, $attachmentFile = null): array
    {
        $part = $this->participantModel->where('conversation_id', $conversationId)
                                       ->where('user_id', $senderId)
                                       ->first();
        if (!$part) {
            return $this->error('Anda bukan anggota dari obrolan ini.');
        }

        $messageText = trim((string)$messageText);
        $attachmentUrl = null;

        if ($attachmentFile && $attachmentFile->isValid() && !$attachmentFile->hasMoved()) {
            $newName = $attachmentFile->getRandomName();
            $attachmentFile->move(ROOTPATH . 'public/uploads/chat', $newName);
            $attachmentUrl = base_url('uploads/chat/' . $newName);
        }

        if (empty($messageText) && empty($attachmentUrl)) {
            return $this->error('Pesan atau berkas lampiran tidak boleh kosong.');
        }

        $this->beginTransaction();

        try {
            $now = date('Y-m-d H:i:s');
            $msgId = $this->messageModel->insert([
                'conversation_id' => $conversationId,
                'sender_id'       => $senderId,
                'message'         => $messageText,
                'attachment_url'  => $attachmentUrl,
                'is_read'         => 0,
                'created_at'      => $now,
            ]);

            // Update conversation updated_at
            $this->db->table('chat_conversations')->where('id', $conversationId)->update(['updated_at' => $now]);

            // Notify other participants
            $allParts = $this->participantModel->where('conversation_id', $conversationId)->findAll();
            $otherUserIds = array_diff(array_column($allParts, 'user_id'), [$senderId]);

            $sender = $this->userModel->find($senderId);
            $senderName = $sender ? $sender['full_name'] : 'Anggota';
            $conv = $this->conversationModel->find($conversationId);
            $convTitle = ($conv['type'] === 'group') ? $conv['name'] : $senderName;

            if (!empty($otherUserIds)) {
                $this->notificationModel->notifyUsers(
                    $otherUserIds,
                    'Pesan Baru dari ' . $senderName,
                    ($conv['type'] === 'group' ? "[{$convTitle}] " : "") . (mb_strlen($messageText) > 60 ? mb_substr($messageText, 0, 60) . '...' : $messageText),
                    'chat',
                    base_url('inbox?conv=' . $conversationId)
                );
            }

            $this->commitTransaction();

            $newMsg = $this->db->table('chat_messages')
                               ->select('chat_messages.*, users.full_name as sender_name, users.avatar as sender_avatar, roles.name as sender_role')
                               ->join('users', 'users.id = chat_messages.sender_id')
                               ->join('roles', 'roles.id = users.role_id', 'left')
                               ->where('chat_messages.id', $msgId)
                               ->get()->getRowArray();

            return $this->success('Pesan berhasil dikirim.', ['message' => $newMsg]);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal mengirim pesan: ' . $e->getMessage());
        }
    }

    /**
     * Delete a single message (and physically remove attachment file if exists)
     */
    public function deleteMessage(int $messageId, int $userId): array
    {
        $msg = $this->messageModel->find($messageId);
        if (!$msg) {
            return $this->error('Pesan tidak ditemukan.');
        }

        // Check permission (Sender or Conv Admin / Superadmin)
        $part = $this->participantModel->where('conversation_id', $msg['conversation_id'])
                                       ->where('user_id', $userId)
                                       ->first();
        $userRole = session()->get('role_slug');
        $isAdmin  = in_array($userRole, ['superadmin', 'pembina', 'bph']);

        if ((int)$msg['sender_id'] !== $userId && !$isAdmin && ($part['role'] ?? '') !== 'admin') {
            return $this->error('Anda tidak memiliki hak akses untuk menghapus pesan ini.');
        }

        // Delete physical attachment file from disk if present
        if (!empty($msg['attachment_url'])) {
            $filePath = ROOTPATH . 'public/' . ltrim($msg['attachment_url'], '/');
            if (file_exists($filePath) && is_file($filePath)) {
                @unlink($filePath);
            }
        }

        $this->messageModel->delete($messageId);
        return $this->success('Pesan dan berkas lampiran berhasil dihapus.');
    }

    /**
     * Delete entire conversation or group (and physically remove all message attachments & group icon)
     */
    public function deleteConversation(int $conversationId, int $userId): array
    {
        $conv = $this->conversationModel->find($conversationId);
        if (!$conv) {
            return $this->error('Obrolan tidak ditemukan.');
        }

        $part = $this->participantModel->where('conversation_id', $conversationId)
                                       ->where('user_id', $userId)
                                       ->first();

        $userRole = session()->get('role_slug');
        $isAdmin  = in_array($userRole, ['superadmin', 'pembina', 'bph']);

        if (!$part && !$isAdmin) {
            return $this->error('Akses ditolak.');
        }

        // If Group, require Creator/Admin or System Admin
        if ($conv['type'] === 'group' && (int)$conv['created_by'] !== $userId && !$isAdmin && ($part['role'] ?? '') !== 'admin') {
            return $this->error('Hanya pembuat grup atau pengurus yang dapat menghapus grup obrolan ini.');
        }

        $this->beginTransaction();

        try {
            // 1. Delete all physical attachment files associated with messages in this conversation
            $messagesWithFiles = $this->messageModel->where('conversation_id', $conversationId)
                                                    ->where('attachment_url !=', '')
                                                    ->where('attachment_url IS NOT NULL')
                                                    ->findAll();
            foreach ($messagesWithFiles as $m) {
                if (!empty($m['attachment_url'])) {
                    $filePath = ROOTPATH . 'public/' . ltrim($m['attachment_url'], '/');
                    if (file_exists($filePath) && is_file($filePath)) {
                        @unlink($filePath);
                    }
                }
            }

            // 2. Delete physical group photo icon file if present
            if (!empty($conv['icon'])) {
                $iconPath = ROOTPATH . 'public/' . ltrim($conv['icon'], '/');
                if (file_exists($iconPath) && is_file($iconPath)) {
                    @unlink($iconPath);
                }
            }

            // 3. Delete database records
            $this->messageModel->where('conversation_id', $conversationId)->delete();
            $this->participantModel->where('conversation_id', $conversationId)->delete();
            $this->conversationModel->delete($conversationId);

            $this->auditLogModel->recordLog($userId, 'CHAT_DELETE', "Menghapus obrolan ID {$conversationId} (" . ($conv['name'] ?: 'Direct') . ") beserta seluruh berkas lampirannya.");

            $this->commitTransaction();
            return $this->success('Obrolan dan seluruh berkas lampirannya berhasil dihapus secara permanen.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal menghapus obrolan: ' . $e->getMessage());
        }
    }

    /**
     * Leave Group Conversation
     */
    public function leaveGroup(int $conversationId, int $userId): array
    {
        $conv = $this->conversationModel->find($conversationId);
        if (!$conv || $conv['type'] !== 'group') {
            return $this->error('Grup obrolan tidak ditemukan.');
        }

        $part = $this->db->table('chat_participants')
                         ->where('conversation_id', $conversationId)
                         ->where('user_id', $userId)
                         ->get()->getRowArray();
        if (!$part) {
            return $this->error('Anda bukan anggota dari grup ini.');
        }

        $this->beginTransaction();

        try {
            // Remove user from participants
            $this->db->table('chat_participants')
                     ->where('conversation_id', $conversationId)
                     ->where('user_id', $userId)
                     ->delete();

            // Check remaining participants
            $remaining = $this->db->table('chat_participants')
                                  ->where('conversation_id', $conversationId)
                                  ->get()->getResultArray();

            if (empty($remaining)) {
                // If no members left, delete conversation & messages
                $this->db->table('chat_messages')->where('conversation_id', $conversationId)->delete();
                $this->db->table('chat_conversations')->where('id', $conversationId)->delete();
            } else {
                if ((int)$conv['created_by'] === $userId) {
                    // Reassign creator to first remaining participant
                    $nextLeader = reset($remaining);
                    $this->db->table('chat_conversations')
                             ->where('id', $conversationId)
                             ->update(['created_by' => $nextLeader['user_id']]);
                    $this->db->table('chat_participants')
                             ->where('conversation_id', $conversationId)
                             ->where('user_id', $nextLeader['user_id'])
                             ->update(['role' => 'admin']);
                }

                $user = $this->userModel->find($userId);
                $userName = $user ? $user['full_name'] : 'Anggota';

                // Post system message in group
                $this->db->table('chat_messages')->insert([
                    'conversation_id' => $conversationId,
                    'sender_id'       => $userId,
                    'message'         => "🚪 {$userName} telah keluar dari grup obrolan.",
                    'is_read'         => 1,
                    'created_at'      => date('Y-m-d H:i:s'),
                ]);
            }

            $this->commitTransaction();
            return $this->success('Anda telah keluar dari grup obrolan.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal keluar dari grup: ' . $e->getMessage());
        }
    }

    /**
     * Update Group Name, Description, and Icon
     */
    public function updateGroupInfo(int $conversationId, int $userId, array $postData, $iconFile = null): array
    {
        $conv = $this->conversationModel->find($conversationId);
        if (!$conv || $conv['type'] !== 'group') {
            return $this->error('Grup obrolan tidak ditemukan.');
        }

        $part = $this->db->table('chat_participants')
                         ->where('conversation_id', $conversationId)
                         ->where('user_id', $userId)
                         ->get()->getRowArray();
        $userRole = session()->get('role_slug');
        $isAdmin  = in_array($userRole, ['superadmin', 'pembina', 'bph']);

        if (!$isAdmin && (int)$conv['created_by'] !== $userId && ($part['role'] ?? '') !== 'admin') {
            return $this->error('Hanya Admin Grup yang dapat mengedit profil grup.');
        }

        $updateData = [];
        if (!empty($postData['group_name'])) {
            $updateData['name'] = trim($postData['group_name']);
        }
        if (isset($postData['group_description'])) {
            $updateData['description'] = trim($postData['group_description']);
        }

        if ($iconFile && $iconFile->isValid() && !$iconFile->hasMoved()) {
            // Delete old icon file from disk if exists
            if (!empty($conv['icon'])) {
                $oldIconPath = ROOTPATH . 'public/' . ltrim($conv['icon'], '/');
                if (file_exists($oldIconPath) && is_file($oldIconPath)) {
                    @unlink($oldIconPath);
                }
            }

            $newName = $iconFile->getRandomName();
            $iconFile->move(ROOTPATH . 'public/uploads/chat_icons', $newName);
            $updateData['icon'] = 'uploads/chat_icons/' . $newName;
        }

        if (empty($updateData)) {
            return $this->error('Tidak ada perubahan data grup.');
        }

        $updateData['updated_at'] = date('Y-m-d H:i:s');
        $this->db->table('chat_conversations')->where('id', $conversationId)->update($updateData);

        return $this->success('Informasi profil grup berhasil diperbarui!');
    }

    /**
     * Promote / Demote Group Admin Role
     */
    public function toggleGroupAdmin(int $conversationId, int $operatorId, int $targetUserId): array
    {
        $conv = $this->conversationModel->find($conversationId);
        if (!$conv || $conv['type'] !== 'group') {
            return $this->error('Grup tidak ditemukan.');
        }

        $operatorPart = $this->db->table('chat_participants')
                                 ->where('conversation_id', $conversationId)
                                 ->where('user_id', $operatorId)
                                 ->get()->getRowArray();
        $userRole = session()->get('role_slug');
        $isSystemAdmin = in_array($userRole, ['superadmin', 'pembina', 'bph']);

        if (!$isSystemAdmin && (int)$conv['created_by'] !== $operatorId && ($operatorPart['role'] ?? '') !== 'admin') {
            return $this->error('Hanya Admin Grup yang dapat mengubah peran anggota.');
        }

        $targetPart = $this->db->table('chat_participants')
                               ->where('conversation_id', $conversationId)
                               ->where('user_id', $targetUserId)
                               ->get()->getRowArray();
        if (!$targetPart) {
            return $this->error('Anggota tidak ditemukan di grup ini.');
        }

        $newRole = ($targetPart['role'] === 'admin') ? 'member' : 'admin';
        $this->db->table('chat_participants')
                 ->where('id', $targetPart['id'])
                 ->update(['role' => $newRole]);

        $roleTitle = ($newRole === 'admin') ? 'Admin Grup' : 'Anggota Biasa';
        return $this->success("Peran anggota berhasil diubah menjadi {$roleTitle}.");
    }

    /**
     * Remove member from group
     */
    public function removeGroupMember(int $conversationId, int $operatorId, int $targetUserId): array
    {
        $conv = $this->conversationModel->find($conversationId);
        if (!$conv || $conv['type'] !== 'group') {
            return $this->error('Grup tidak ditemukan.');
        }

        $operatorPart = $this->db->table('chat_participants')
                                 ->where('conversation_id', $conversationId)
                                 ->where('user_id', $operatorId)
                                 ->get()->getRowArray();
        $userRole = session()->get('role_slug');
        $isSystemAdmin = in_array($userRole, ['superadmin', 'pembina', 'bph']);

        if (!$isSystemAdmin && (int)$conv['created_by'] !== $operatorId && ($operatorPart['role'] ?? '') !== 'admin') {
            return $this->error('Hanya Admin Grup yang dapat mengeluarkan anggota.');
        }

        if ((int)$conv['created_by'] === $targetUserId) {
            return $this->error('Pembuat utama grup tidak dapat dikeluarkan dari grup.');
        }

        $this->db->table('chat_participants')
                 ->where('conversation_id', $conversationId)
                 ->where('user_id', $targetUserId)
                 ->delete();

        $targetUser = $this->userModel->find($targetUserId);
        $targetName = $targetUser ? $targetUser['full_name'] : 'Anggota';

        $this->db->table('chat_messages')->insert([
            'conversation_id' => $conversationId,
            'sender_id'       => $operatorId,
            'message'         => "🚫 {$targetName} telah dikeluarkan dari grup oleh admin.",
            'is_read'         => 1,
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        return $this->success('Anggota berhasil dikeluarkan dari grup.');
    }

    /**
     * Add new members to existing group
     */
    public function addGroupMembers(int $conversationId, int $operatorId, array $newMemberIds): array
    {
        $conv = $this->conversationModel->find($conversationId);
        if (!$conv || $conv['type'] !== 'group') {
            return $this->error('Grup tidak ditemukan.');
        }

        $existingParts = array_column(
            $this->db->table('chat_participants')
                     ->where('conversation_id', $conversationId)
                     ->get()->getResultArray(), 
            'user_id'
        );
        $toAdd = array_diff($newMemberIds, $existingParts);

        if (empty($toAdd)) {
            return $this->error('Seluruh anggota yang dipilih sudah berada di dalam grup.');
        }

        $now = date('Y-m-d H:i:s');
        $batch = [];
        foreach ($toAdd as $uid) {
            $batch[] = [
                'conversation_id' => $conversationId,
                'user_id'         => (int)$uid,
                'role'            => 'member',
                'joined_at'       => $now,
            ];
        }
        $this->db->table('chat_participants')->insertBatch($batch);

        return $this->success('Anggota baru berhasil ditambahkan ke dalam grup.');
    }
}
