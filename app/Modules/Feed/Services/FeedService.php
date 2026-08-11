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
            $following = $this->followModel->where('follower_id', $currentUserId)->where('following_id', $authorId)->first();
            $p['is_following_author'] = !empty($following);

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
            $p['author_verified'] = $user['is_verified'];
            $p['is_own_post']     = ((int)$p['user_id'] === $currentUserId);
            $p['is_liked']        = !empty($this->likeModel->where('post_id', $pId)->where('user_id', $currentUserId)->first());
            $p['time_ago']        = $this->timeAgo($p['created_at']);

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

        return $this->success('Profile loaded', [
            'user'            => $user,
            'follower_count'  => $followerCount,
            'following_count' => $followingCount,
            'posts_count'     => $postsCount,
            'is_following'    => $isFollowing,
            'posts'           => $posts,
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
     * Time ago helper
     */
    private function timeAgo(?string $datetime): string
    {
        if (!$datetime) return 'Baru saja';
        $time = strtotime($datetime);
        $diff = time() - $time;

        if ($diff < 60) return 'Baru saja';
        if ($diff < 3600) return floor($diff / 60) . ' mnt lalu';
        if ($diff < 86400) return floor($diff / 3600) . ' jam lalu';
        if ($diff < 2592000) return floor($diff / 86400) . ' hr lalu';
        return date('d M Y, H:i', $time);
    }
}
