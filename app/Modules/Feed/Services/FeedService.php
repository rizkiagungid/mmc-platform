<?php

namespace App\Modules\Feed\Services;

use App\Services\BaseService;
use App\Models\PostModel;
use App\Models\PostLikeModel;
use App\Models\PostCommentModel;
use App\Models\UserFollowModel;
use App\Models\UserModel;
use App\Models\NotificationModel;
use App\Models\AuditLogModel;

class FeedService extends BaseService
{
    protected $postModel;
    protected $likeModel;
    protected $commentModel;
    protected $followModel;
    protected $userModel;
    protected $notificationModel;
    protected $auditLogModel;

    public function __construct()
    {
        parent::__construct();
        $this->postModel        = new PostModel();
        $this->likeModel        = new PostLikeModel();
        $this->commentModel     = new PostCommentModel();
        $this->followModel      = new UserFollowModel();
        $this->userModel        = new UserModel();
        $this->notificationModel = new NotificationModel();
        $this->auditLogModel    = new AuditLogModel();
    }

    /**
     * Helper to check if role slug is verified (Superadmin, Pembina, BPH)
     */
    public function isRoleVerified(?string $roleSlug): bool
    {
        return in_array(strtolower((string)$roleSlug), ['superadmin', 'pembina', 'bph']);
    }

    /**
     * Create a new status post
     */
    public function createPost(int $userId, string $content, $mediaFile = null): array
    {
        $content = trim($content);
        $mediaUrl  = null;
        $mediaType = 'none';

        if ($mediaFile && $mediaFile->isValid() && !$mediaFile->hasMoved()) {
            $mime = $mediaFile->getMimeType();
            $ext  = strtolower($mediaFile->getExtension());

            if (strpos($mime, 'image/') === 0 || in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $mediaType = 'image';
            } elseif (strpos($mime, 'video/') === 0 || in_array($ext, ['mp4', 'webm', 'mov'])) {
                $mediaType = 'video';
            } else {
                $mediaType = 'document';
            }

            $newName = $mediaFile->getRandomName();
            $mediaFile->move(ROOTPATH . 'public/uploads/feed_media', $newName);
            $mediaUrl = 'uploads/feed_media/' . $newName;
        }

        if (empty($content) && empty($mediaUrl)) {
            return $this->error('Tuliskan sesuatu atau unggah berkas media untuk membuat status.');
        }

        $postId = $this->postModel->insert([
            'user_id'    => $userId,
            'content'    => $content,
            'media_url'  => $mediaUrl,
            'media_type' => $mediaType,
            'likes_count' => 0,
            'comments_count' => 0,
        ]);

        $this->auditLogModel->recordLog($userId, 'FEED_POST_CREATE', "Membuat status baru (Post ID: {$postId})");

        return $this->success('Status berhasil dipublikasikan ke Beranda Club!');
    }

    /**
     * Delete status post
     */
    public function deletePost(int $postId, int $userId): array
    {
        $post = $this->postModel->find($postId);
        if (!$post) {
            return $this->error('Postingan status tidak ditemukan.');
        }

        $userRole = session()->get('role_slug');
        $isAdmin  = in_array($userRole, ['superadmin', 'pembina', 'bph']);

        if ((int)$post['user_id'] !== $userId && !$isAdmin) {
            return $this->error('Anda tidak memiliki izin untuk menghapus postingan ini.');
        }

        // Physical media cleanup
        if (!empty($post['media_url'])) {
            $filePath = ROOTPATH . 'public/' . ltrim($post['media_url'], '/');
            if (file_exists($filePath) && is_file($filePath)) {
                @unlink($filePath);
            }
        }

        $this->likeModel->where('post_id', $postId)->delete();
        $this->commentModel->where('post_id', $postId)->delete();
        $this->postModel->delete($postId);

        $this->auditLogModel->recordLog($userId, 'FEED_POST_DELETE', "Menghapus postingan status ID {$postId}");

        return $this->success('Postingan status berhasil dihapus.');
    }

    /**
     * Toggle Like on Post
     */
    public function toggleLike(int $postId, int $userId): array
    {
        $post = $this->postModel->find($postId);
        if (!$post) {
            return $this->error('Postingan tidak ditemukan.');
        }

        $existing = $this->likeModel->where('post_id', $postId)->where('user_id', $userId)->first();
        $isLiked  = false;

        if ($existing) {
            $this->likeModel->delete($existing['id']);
            $isLiked = false;
        } else {
            $this->likeModel->insert([
                'post_id'    => $postId,
                'user_id'    => $userId,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $isLiked = true;

            // Notify post author if different user
            if ((int)$post['user_id'] !== $userId) {
                $liker = $this->userModel->find($userId);
                $likerName = $liker ? $liker['full_name'] : 'Seseorang';
                $this->notificationModel->insert([
                    'user_id'    => $post['user_id'],
                    'title'      => '❤️ Menyukai Status Anda',
                    'message'    => "{$likerName} menyukai status yang Anda bagikan.",
                    'type'       => 'system',
                    'link'       => 'feed',
                    'is_read'    => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        $likesCount = $this->likeModel->where('post_id', $postId)->countAllResults();
        $this->postModel->update($postId, ['likes_count' => $likesCount]);

        return $this->success($isLiked ? 'Disukai' : 'Batal Suka', [
            'is_liked'    => $isLiked,
            'likes_count' => $likesCount,
        ]);
    }

    /**
     * Add Comment to Post
     */
    public function addComment(int $postId, int $userId, string $commentText): array
    {
        $commentText = trim($commentText);
        if (empty($commentText)) {
            return $this->error('Komentar tidak boleh kosong.');
        }

        $post = $this->postModel->find($postId);
        if (!$post) {
            return $this->error('Postingan tidak ditemukan.');
        }

        $commentId = $this->commentModel->insert([
            'post_id'    => $postId,
            'user_id'    => $userId,
            'comment'    => $commentText,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $commentsCount = $this->commentModel->where('post_id', $postId)->countAllResults();
        $this->postModel->update($postId, ['comments_count' => $commentsCount]);

        // Notify post author
        if ((int)$post['user_id'] !== $userId) {
            $commenter = $this->userModel->find($userId);
            $cName = $commenter ? $commenter['full_name'] : 'Seseorang';
            $preview = mb_strimwidth($commentText, 0, 50, '...');
            $this->notificationModel->insert([
                'user_id'    => $post['user_id'],
                'title'      => '💬 Komentar Baru di Status Anda',
                'message'    => "{$cName} mengomentari: \"{$preview}\"",
                'type'       => 'system',
                'link'       => 'feed',
                'is_read'    => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return $this->success('Komentar berhasil ditambahkan!', [
            'comment_id'     => $commentId,
            'comments_count' => $commentsCount,
        ]);
    }

    /**
     * Repost a status
     */
    public function repost(int $originalPostId, int $userId): array
    {
        $orig = $this->postModel->find($originalPostId);
        if (!$orig) {
            return $this->error('Postingan tidak ditemukan.');
        }

        $author = $this->userModel->find($orig['user_id']);
        $authorName = $author ? $author['full_name'] : 'Anggota MMC';

        $repostContent = "🔁 *Mengunggah Ulang (Repost) status dari {$authorName}:*\n\n" . $orig['content'];

        $newPostId = $this->postModel->insert([
            'user_id'        => $userId,
            'content'        => $repostContent,
            'media_url'      => $orig['media_url'],
            'media_type'     => $orig['media_type'],
            'likes_count'    => 0,
            'comments_count' => 0,
            'reposts_count'  => 0,
            'bookmarks_count'=> 0,
        ]);

        // Increment reposts_count on original post
        $currentRepostsCount = (int)($orig['reposts_count'] ?? 0) + 1;
        $this->postModel->update($originalPostId, ['reposts_count' => $currentRepostsCount]);

        // Notify original author
        if ((int)$orig['user_id'] !== $userId) {
            $user = $this->userModel->find($userId);
            $userName = $user ? $user['full_name'] : 'Seseorang';
            $this->notificationModel->insert([
                'user_id'    => $orig['user_id'],
                'title'      => '🔁 Status Anda Di-repost',
                'message'    => "{$userName} mengunggah ulang (repost) status karya Anda.",
                'type'       => 'system',
                'link'       => 'feed/post/' . $newPostId,
                'is_read'    => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $this->auditLogModel->recordLog($userId, 'FEED_POST_REPOST', "Meng-repost status ID {$originalPostId}");

        return $this->success('Status berhasil di-repost ke beranda Anda!');
    }

    /**
     * Share post content directly to Chat Inbox conversation
     */
    public function shareToChat(int $postId, int $userId, string $targetType, int $targetId): array
    {
        $post = $this->postModel->find($postId);
        if (!$post) {
            return $this->error('Postingan tidak ditemukan.');
        }

        $author = $this->userModel->find($post['user_id']);
        $authorName = $author ? $author['full_name'] : 'Anggota MMC';
        $postLink = base_url('feed/post/' . $postId);

        $chatMsg = "📢 *Membagikan Status Feed MMC dari {$authorName}:*\n" . mb_strimwidth($post['content'] ?? '', 0, 150, '...') . "\n\n🔗 Lihat selengkapnya: " . $postLink;

        $chatMessageModel = new \App\Models\ChatMessageModel();
        $chatConvModel    = new \App\Models\ChatConversationModel();

        if ($targetType === 'group') {
            $chatMessageModel->insert([
                'conversation_id' => $targetId,
                'sender_id'       => $userId,
                'message'         => $chatMsg,
                'attachment_url'  => $post['media_url'],
                'attachment_type' => $post['media_type'] !== 'none' ? $post['media_type'] : null,
                'created_at'      => date('Y-m-d H:i:s'),
            ]);
            $chatConvModel->update($targetId, ['updated_at' => date('Y-m-d H:i:s')]);
        } else {
            // Personal user chat: find or create conversation
            $conv = $this->db->table('chat_conversations')
                             ->where('type', 'personal')
                             ->groupStart()
                                 ->where('user_one_id', $userId)->where('user_two_id', $targetId)
                                 ->orGroupStart()->where('user_one_id', $targetId)->where('user_two_id', $userId)->groupEnd()
                             ->groupEnd()
                             ->get()->getRowArray();

            if ($conv) {
                $convId = $conv['id'];
            } else {
                $convId = $chatConvModel->insert([
                    'type'        => 'personal',
                    'user_one_id' => $userId,
                    'user_two_id' => $targetId,
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s'),
                ]);
            }

            $chatMessageModel->insert([
                'conversation_id' => $convId,
                'sender_id'       => $userId,
                'message'         => $chatMsg,
                'attachment_url'  => $post['media_url'],
                'attachment_type' => $post['media_type'] !== 'none' ? $post['media_type'] : null,
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            $chatConvModel->update($convId, ['updated_at' => date('Y-m-d H:i:s')]);
        }

        return $this->success('Status berhasil dibagikan ke obrolan Inbox!');
    }

    /**
     * Toggle Bookmark / Save Post (Persisted to Database)
     */
    public function toggleBookmark(int $postId, int $userId): array
    {
        $post = $this->postModel->find($postId);
        if (!$post) {
            return $this->error('Postingan tidak ditemukan.');
        }

        $bookmarkModel = new \App\Models\PostBookmarkModel();
        $existing = $bookmarkModel->where('post_id', $postId)->where('user_id', $userId)->first();
        $isBookmarked = false;

        if ($existing) {
            $bookmarkModel->delete($existing['id']);
            $isBookmarked = false;
        } else {
            $bookmarkModel->insert([
                'post_id'    => $postId,
                'user_id'    => $userId,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $isBookmarked = true;
        }

        $bmCount = $bookmarkModel->where('post_id', $postId)->countAllResults();
        $this->postModel->update($postId, ['bookmarks_count' => $bmCount]);

        return $this->success($isBookmarked ? 'Status disimpan ke markah!' : 'Batal menyimpan status.', [
            'is_bookmarked'   => $isBookmarked,
            'bookmarks_count' => $bmCount,
        ]);
    }

    /**
     * Delete Comment
     */
    public function deleteComment(int $commentId, int $userId): array
    {
        $comment = $this->commentModel->find($commentId);
        if (!$comment) {
            return $this->error('Komentar tidak ditemukan.');
        }

        $post = $this->postModel->find($comment['post_id']);
        $userRole = session()->get('role_slug');
        $isAdmin  = in_array($userRole, ['superadmin', 'pembina', 'bph']);

        $isCommentOwner = ((int)$comment['user_id'] === $userId);
        $isPostOwner    = ($post && (int)$post['user_id'] === $userId);

        if (!$isCommentOwner && !$isPostOwner && !$isAdmin) {
            return $this->error('Anda tidak memiliki izin menghapus komentar ini.');
        }

        $this->commentModel->delete($commentId);

        if ($post) {
            $commentsCount = $this->commentModel->where('post_id', $post['id'])->countAllResults();
            $this->postModel->update($post['id'], ['comments_count' => $commentsCount]);
        }

        return $this->success('Komentar berhasil dihapus.');
    }

    /**
     * Toggle Follow / Unfollow user
     */
    public function toggleFollow(int $followerId, int $targetUserId): array
    {
        if ($followerId === $targetUserId) {
            return $this->error('Anda tidak dapat mengikuti akun Anda sendiri.');
        }

        $targetUser = $this->userModel->find($targetUserId);
        if (!$targetUser) {
            return $this->error('Pengguna tidak ditemukan.');
        }

        $existing = $this->followModel->where('follower_id', $followerId)->where('following_id', $targetUserId)->first();
        $isFollowing = false;

        if ($existing) {
            $this->followModel->delete($existing['id']);
            $isFollowing = false;
        } else {
            $this->followModel->insert([
                'follower_id'  => $followerId,
                'following_id' => $targetUserId,
                'created_at'   => date('Y-m-d H:i:s'),
            ]);
            $isFollowing = true;

            // Notify target user
            $follower = $this->userModel->find($followerId);
            $fName = $follower ? $follower['full_name'] : 'Seseorang';
            $this->notificationModel->insert([
                'user_id'    => $targetUserId,
                'title'      => '👥 Pengikut Baru',
                'message'    => "{$fName} mulai mengikuti Anda di MMC Feed.",
                'type'       => 'system',
                'link'       => 'feed/user/' . $followerId,
                'is_read'    => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $followerCount = $this->followModel->where('following_id', $targetUserId)->countAllResults();

        return $this->success($isFollowing ? "Berhasil mengikuti {$targetUser['full_name']}!" : "Batal mengikuti {$targetUser['full_name']}.", [
            'is_following'   => $isFollowing,
            'follower_count' => $followerCount,
        ]);
    }

    /**
     * Fetch Social Feed Posts
     */
    public function getFeedPosts(int $currentUserId, string $filter = 'all', int $limit = 10, int $offset = 0): array
    {
        $builder = $this->db->table('posts')
                            ->select('posts.*, users.full_name as author_name, users.avatar as author_avatar, users.class_dept as author_class, roles.name as author_role_name, roles.slug as author_role_slug')
                            ->join('users', 'users.id = posts.user_id')
                            ->join('roles', 'roles.id = users.role_id', 'left')
                            ->where('posts.deleted_at IS NULL')
                            ->orderBy('posts.created_at', 'DESC')
                            ->limit($limit, $offset);

        if ($filter === 'following') {
            $followingIds = array_column(
                $this->followModel->where('follower_id', $currentUserId)->findAll(),
                'following_id'
            );
            $followingIds[] = $currentUserId; // include own posts
            $builder->whereIn('posts.user_id', $followingIds);
        }

        $posts = $builder->get()->getResultArray();

        foreach ($posts as &$p) {
            $pId = (int)$p['id'];
            $authorId = (int)$p['user_id'];

            // Verified Blue Checkmark Badge logic
            $p['author_verified'] = $this->isRoleVerified($p['author_role_slug']);
            $p['is_own_post']     = ($authorId === $currentUserId);

            // Is Liked by Me
            $liked = $this->likeModel->where('post_id', $pId)->where('user_id', $currentUserId)->first();
            $p['is_liked'] = !empty($liked);

            // Is Following Author
            $p['is_following_author'] = !empty($this->followModel->where('follower_id', $currentUserId)->where('following_id', $authorId)->first());

            // Is Bookmarked by Me & Total Bookmarks Count
            $bookmarkModel = new \App\Models\PostBookmarkModel();
            $bookmarked = $bookmarkModel->where('post_id', $pId)->where('user_id', $currentUserId)->first();
            $p['is_bookmarked']    = !empty($bookmarked);
            // Reposts Count & Bookmarks Count from database column directly
            $p['reposts_count']  = (int)($p['reposts_count'] ?? 0);
            $p['bookmarks_count'] = (int)($p['bookmarks_count'] ?? 0);

            // Time formatted
            $p['time_ago'] = $this->timeAgo($p['created_at']);

            // Fetch comments
            $comments = $this->db->table('post_comments')
                                 ->select('post_comments.*, users.full_name as commenter_name, users.avatar as commenter_avatar, roles.name as commenter_role_name, roles.slug as commenter_role_slug')
                                 ->join('users', 'users.id = post_comments.user_id')
                                 ->join('roles', 'roles.id = users.role_id', 'left')
                                 ->where('post_comments.post_id', $pId)
                                 ->orderBy('post_comments.created_at', 'ASC')
                                 ->get()->getResultArray();

            foreach ($comments as &$c) {
                $c['commenter_verified'] = $this->isRoleVerified($c['commenter_role_slug']);
                $c['is_own_comment']     = ((int)$c['user_id'] === $currentUserId);
                $c['time_ago']           = $this->timeAgo($c['created_at']);
            }
            $p['comments'] = $comments;
        }

        return $posts;
    }

    /**
     * Get Single Post By ID
     */
    public function getSinglePostById(int $postId, int $currentUserId): ?array
    {
        $posts = $this->db->table('posts')
                          ->select('posts.*, users.full_name as author_name, users.avatar as author_avatar, users.class_dept as author_class, roles.name as author_role_name, roles.slug as author_role_slug')
                          ->join('users', 'users.id = posts.user_id')
                          ->join('roles', 'roles.id = users.role_id', 'left')
                          ->where('posts.id', $postId)
                          ->where('posts.deleted_at IS NULL')
                          ->get()->getResultArray();

        if (empty($posts)) {
            return null;
        }

        $p = $posts[0];
        $p['author_verified']    = $this->isRoleVerified($p['author_role_slug']);
        $p['is_own_post']        = ((int)$p['user_id'] === $currentUserId);
        $p['is_liked']           = (bool)$this->likeModel->where('post_id', $postId)->where('user_id', $currentUserId)->first();
        $bookmarkModel = new \App\Models\PostBookmarkModel();
        $p['is_bookmarked']       = (bool)$bookmarkModel->where('post_id', $postId)->where('user_id', $currentUserId)->first();
        $p['bookmarks_count']     = (int)($p['bookmarks_count'] ?? 0);
        $p['reposts_count']       = (int)($p['reposts_count'] ?? 0);
        $p['is_following_author'] = (bool)$this->followModel->where('follower_id', $currentUserId)->where('following_id', $p['user_id'])->first();
        $p['time_ago']           = $this->timeAgo($p['created_at']);

        $comments = $this->db->table('post_comments')
                             ->select('post_comments.*, users.full_name as commenter_name, users.avatar as commenter_avatar, roles.name as commenter_role_name, roles.slug as commenter_role_slug')
                             ->join('users', 'users.id = post_comments.user_id')
                             ->join('roles', 'roles.id = users.role_id', 'left')
                             ->where('post_comments.post_id', $postId)
                             ->orderBy('post_comments.created_at', 'ASC')
                             ->get()->getResultArray();

        foreach ($comments as &$c) {
            $c['commenter_verified'] = $this->isRoleVerified($c['commenter_role_slug']);
            $c['is_own_comment']     = ((int)$c['user_id'] === $currentUserId);
            $c['time_ago']           = $this->timeAgo($c['created_at']);
        }
        $p['comments'] = $comments;

        return $p;
    }

    /**
     * Count total feed posts (for pagination)
     */
    public function getFeedPostCount(int $currentUserId, string $filter = 'all'): int
    {
        $builder = $this->db->table('posts')
                            ->where('posts.deleted_at IS NULL');

        if ($filter === 'following') {
            $followingIds = array_column(
                $this->followModel->where('follower_id', $currentUserId)->findAll(),
                'following_id'
            );
            $followingIds[] = $currentUserId;
            $builder->whereIn('posts.user_id', $followingIds);
        }

        return (int)$builder->countAllResults();
    }

    /**
     * Count new posts since a given datetime
     */
    public function getNewPostsCount(int $currentUserId, string $filter = 'all', string $sinceTime = ''): int
    {
        $builder = $this->db->table('posts')
                            ->where('posts.deleted_at IS NULL')
                            ->where('posts.created_at >', $sinceTime);

        if ($filter === 'following') {
            $followingIds = array_column(
                $this->followModel->where('follower_id', $currentUserId)->findAll(),
                'following_id'
            );
            $followingIds[] = $currentUserId;
            $builder->whereIn('posts.user_id', $followingIds);
        }

        return (int)$builder->countAllResults();
    }

    /**
     * Get Who to Follow recommendations
     */
    public function getWhoToFollow(int $currentUserId, int $limit = 5): array
    {
        $followingIds = array_column(
            $this->followModel->where('follower_id', $currentUserId)->findAll(),
            'following_id'
        );
        $followingIds[] = $currentUserId;

        $members = $this->db->table('users')
                            ->select('users.id, users.full_name, users.avatar, users.class_dept, roles.name as role_name, roles.slug as role_slug')
                            ->join('roles', 'roles.id = users.role_id', 'left')
                            ->where('users.deleted_at IS NULL')
                            ->where('users.status', 'active')
                            ->whereNotIn('users.id', $followingIds)
                            ->orderBy('users.id', 'RANDOM')
                            ->limit($limit)
                            ->get()->getResultArray();

        foreach ($members as &$m) {
            $m['is_verified'] = $this->isRoleVerified($m['role_slug']);
        }

        return $members;
    }

    /**
     * Get User Social Profile Wall Data
     */
    public function getUserSocialProfile(int $targetUserId, int $currentUserId): array
    {
        $user = $this->db->table('users')
                         ->select('users.*, roles.name as role_name, roles.slug as role_slug')
                         ->join('roles', 'roles.id = users.role_id', 'left')
                         ->where('users.id', $targetUserId)
                         ->get()->getRowArray();

        if (!$user) {
            return $this->error('Profil anggota tidak ditemukan.');
        }

        $user['is_verified'] = $this->isRoleVerified($user['role_slug']);

        $followerCount  = $this->followModel->where('following_id', $targetUserId)->countAllResults();
        $followingCount = $this->followModel->where('follower_id', $targetUserId)->countAllResults();
        $postsCount     = $this->postModel->where('user_id', $targetUserId)->where('deleted_at IS NULL')->countAllResults();

        $isFollowing = !empty($this->followModel->where('follower_id', $currentUserId)->where('following_id', $targetUserId)->first());

        // Get user's posts
        $posts = $this->db->table('posts')
                          ->select('posts.*, users.full_name as author_name, users.avatar as author_avatar, users.class_dept as author_class, roles.name as author_role_name, roles.slug as author_role_slug')
                          ->join('users', 'users.id = posts.user_id')
                          ->join('roles', 'roles.id = users.role_id', 'left')
                          ->where('posts.user_id', $targetUserId)
                          ->where('posts.deleted_at IS NULL')
                          ->orderBy('posts.created_at', 'DESC')
                          ->get()->getResultArray();

        foreach ($posts as &$p) {
            $pId = (int)$p['id'];
            $p['author_verified']     = $user['is_verified'];
            $p['is_own_post']         = ((int)$p['user_id'] === $currentUserId);
            $p['is_following_author'] = $isFollowing;
            $p['is_liked']            = !empty($this->likeModel->where('post_id', $pId)->where('user_id', $currentUserId)->first());
            $p['time_ago']            = $this->timeAgo($p['created_at']);
            $p['is_bookmarked']       = !empty($this->db->table('post_bookmarks')->where('post_id', $pId)->where('user_id', $currentUserId)->get()->getRowArray());
            $p['reposts_count']       = (int)($p['reposts_count'] ?? 0);
            $p['bookmarks_count']     = (int)($p['bookmarks_count'] ?? 0);
            $comments = $this->db->table('post_comments')
                                 ->select('post_comments.*, users.full_name as commenter_name, users.avatar as commenter_avatar, roles.name as commenter_role_name, roles.slug as commenter_role_slug')
                                 ->join('users', 'users.id = post_comments.user_id')
                                 ->join('roles', 'roles.id = users.role_id', 'left')
                                 ->where('post_comments.post_id', $pId)
                                 ->orderBy('post_comments.created_at', 'ASC')
                                 ->get()->getResultArray();

            foreach ($comments as &$c) {
                $c['commenter_verified'] = $this->isRoleVerified($c['commenter_role_slug']);
                $c['is_own_comment']     = ((int)$c['user_id'] === $currentUserId);
                $c['time_ago']           = $this->timeAgo($c['created_at']);
            }
            $p['comments'] = $comments;
        }

        // Get user's bookmarked posts (if viewing own profile)
        $bookmarkedPosts = [];
        if ($targetUserId === $currentUserId) {
            $bmRows = $this->db->table('post_bookmarks')
                               ->select('posts.*, users.full_name as author_name, users.avatar as author_avatar, users.class_dept as author_class, roles.name as author_role_name, roles.slug as author_role_slug')
                               ->join('posts', 'posts.id = post_bookmarks.post_id')
                               ->join('users', 'users.id = posts.user_id')
                               ->join('roles', 'roles.id = users.role_id', 'left')
                               ->where('post_bookmarks.user_id', $currentUserId)
                               ->where('posts.deleted_at IS NULL')
                               ->orderBy('post_bookmarks.created_at', 'DESC')
                               ->get()->getResultArray();

            foreach ($bmRows as $bm) {
                $bmId = (int)$bm['id'];
                $bmAuthorId = (int)$bm['user_id'];
                $bm['author_verified']     = $this->isRoleVerified($bm['author_role_slug']);
                $bm['is_own_post']         = ($bmAuthorId === $currentUserId);
                $bm['is_following_author'] = !empty($this->followModel->where('follower_id', $currentUserId)->where('following_id', $bmAuthorId)->first());
                $bm['is_liked']            = !empty($this->likeModel->where('post_id', $bmId)->where('user_id', $currentUserId)->first());
                $bm['is_bookmarked']       = true;
                $bm['reposts_count']       = (int)($bm['reposts_count'] ?? 0);
                $bm['bookmarks_count']     = (int)($bm['bookmarks_count'] ?? 0);
                $bm['time_ago']            = $this->timeAgo($bm['created_at']);

                $bmComments = $this->db->table('post_comments')
                                       ->select('post_comments.*, users.full_name as commenter_name, users.avatar as commenter_avatar, roles.name as commenter_role_name, roles.slug as commenter_role_slug')
                                       ->join('users', 'users.id = post_comments.user_id')
                                       ->join('roles', 'roles.id = users.role_id', 'left')
                                       ->where('post_comments.post_id', $bmId)
                                       ->orderBy('post_comments.created_at', 'ASC')
                                       ->get()->getResultArray();

                foreach ($bmComments as &$bmc) {
                    $bmc['commenter_verified'] = $this->isRoleVerified($bmc['commenter_role_slug']);
                    $bmc['is_own_comment']     = ((int)$bmc['user_id'] === $currentUserId);
                    $bmc['time_ago']           = $this->timeAgo($bmc['created_at']);
                }
                $bm['comments'] = $bmComments;
                $bookmarkedPosts[] = $bm;
            }
        }

        return $this->success('Profile loaded', [
            'user'             => $user,
            'follower_count'   => $followerCount,
            'following_count'  => $followingCount,
            'posts_count'      => $postsCount,
            'is_following'     => $isFollowing,
            'posts'            => $posts,
            'bookmarked_posts' => $bookmarkedPosts,
        ]);
    }

    /**
     * Get Followers List Modal Data
     */
    public function getFollowersList(int $targetUserId, int $currentUserId): array
    {
        $followers = $this->db->table('user_follows')
                              ->select('users.id, users.full_name, users.avatar, users.class_dept, roles.name as role_name, roles.slug as role_slug')
                              ->join('users', 'users.id = user_follows.follower_id')
                              ->join('roles', 'roles.id = users.role_id', 'left')
                              ->where('user_follows.following_id', $targetUserId)
                              ->get()->getResultArray();

        foreach ($followers as &$f) {
            $f['is_verified']    = $this->isRoleVerified($f['role_slug']);
            $f['is_self']        = ((int)$f['id'] === $currentUserId);
            $f['is_following']   = !empty($this->followModel->where('follower_id', $currentUserId)->where('following_id', $f['id'])->first());
        }

        return $followers;
    }

    /**
     * Get Following List Modal Data
     */
    public function getFollowingList(int $targetUserId, int $currentUserId): array
    {
        $following = $this->db->table('user_follows')
                              ->select('users.id, users.full_name, users.avatar, users.class_dept, roles.name as role_name, roles.slug as role_slug')
                              ->join('users', 'users.id = user_follows.following_id')
                              ->join('roles', 'roles.id = users.role_id', 'left')
                              ->where('user_follows.follower_id', $targetUserId)
                              ->get()->getResultArray();

        foreach ($following as &$f) {
            $f['is_verified']  = $this->isRoleVerified($f['role_slug']);
            $f['is_self']      = ((int)$f['id'] === $currentUserId);
            $f['is_following'] = !empty($this->followModel->where('follower_id', $currentUserId)->where('following_id', $f['id'])->first());
        }

        return $following;
    }

    /**
     * Search Users
     */
    public function searchUsers(string $query, int $currentUserId): array
    {
        $query = trim($query);
        if (str_starts_with($query, '@')) {
            $query = ltrim($query, '@');
        }

        $builder = $this->db->table('users')
                            ->select('users.id, users.username, users.full_name, users.avatar, users.class_dept, users.nis_nip, roles.name as role_name, roles.slug as role_slug')
                            ->join('roles', 'roles.id = users.role_id', 'left')
                            ->where('users.status', 'active');

        if (!empty($query)) {
            $builder->groupStart()
                        ->like('users.full_name', $query)
                        ->orLike('users.username', $query)
                        ->orLike('users.nis_nip', $query)
                        ->orLike('users.class_dept', $query)
                        ->orLike('roles.name', $query)
                    ->groupEnd();
        }

        $results = $builder->limit(50)->get()->getResultArray();

        foreach ($results as &$u) {
            $u['is_verified']  = $this->isRoleVerified($u['role_slug']);
            $u['is_self']      = ((int)$u['id'] === $currentUserId);
            $u['is_following'] = !empty($this->followModel->where('follower_id', $currentUserId)->where('following_id', $u['id'])->first());
        }

        return $results;
    }

    /**
     * Time ago helper
     */
    private function timeAgo(?string $datetime): string
    {
        if (!$datetime) return 'Baru saja';
        $time = strtotime($datetime);
        $diff = time() - $time;

        if ($diff < 10) return 'Baru saja';
        if ($diff < 60) return max(1, (int)$diff) . ' dtk lalu';
        if ($diff < 3600) return floor($diff / 60) . ' mnt lalu';
        if ($diff < 86400) return floor($diff / 3600) . ' jam lalu';
        if ($diff < 2592000) return floor($diff / 86400) . ' hr lalu';
        return date('d M Y, H:i', $time);
    }
}
