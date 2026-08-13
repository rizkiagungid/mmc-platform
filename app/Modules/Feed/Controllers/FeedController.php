<?php

namespace App\Modules\Feed\Controllers;

use App\Controllers\BaseController;
use App\Modules\Feed\Services\FeedService;

class FeedController extends BaseController
{
    protected $feedService;

    public function __construct()
    {
        $this->feedService = new FeedService();
    }

    /**
     * Main Feed Page
     */
    public function index()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $filter = $this->request->getGet('filter') === 'following' ? 'following' : 'all';

        // Initial load: max 10 posts, sorted by latest
        $posts       = $this->feedService->getFeedPosts($userId, $filter, 10, 0);
        $whoToFollow = $this->feedService->getWhoToFollow($userId);
        $myProfile   = $this->feedService->getUserSocialProfile($userId, $userId);

        $userModel      = new \App\Models\UserModel();
        $todayBirthdays = $userModel->getTodayBirthdayUsers();

        $allMembers = $userModel->select('users.id, users.username, users.full_name, users.avatar, users.class_dept, users.nis_nip, roles.name as role_name, roles.slug as role_slug')
                                ->join('roles', 'roles.id = users.role_id', 'left')
                                ->where('users.status', 'active')
                                ->orderBy('users.full_name', 'ASC')
                                ->findAll();

        $totalPosts = $this->feedService->getFeedPostCount($userId, $filter);

        $data = [
            'title'          => 'Beranda Club - Feed Sosial MMC',
            'posts'          => $posts,
            'whoToFollow'    => $whoToFollow,
            'myProfile'      => $myProfile['body']['data'] ?? [],
            'todayBirthdays' => $todayBirthdays,
            'allMembers'     => $allMembers,
            'activeFilter'   => $filter,
            'totalPosts'     => $totalPosts,
            'perPage'        => 10,
        ];

        return view('App\Modules\Feed\Views\index', $data);
    }

    /**
     * Create Post
     */
    public function createPost()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $content   = (string)$this->request->getPost('content');
        $mediaFile = $this->request->getFile('media');

        $result = $this->feedService->createPost($userId, $content, $mediaFile);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result['body']);
        }

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->withInput()->with('error', $result['body']['message']);
        }

        return redirect()->to('/feed')->with('success', $result['body']['message']);
    }

    /**
     * Delete Post
     */
    public function deletePost(int $id)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $result = $this->feedService->deletePost($id, $userId);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result['body']);
        }

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->with('error', $result['body']['message']);
        }

        return redirect()->back()->with('success', $result['body']['message']);
    }

    /**
     * Toggle Like (Form POST Direct with Scroll Anchor)
     */
    public function toggleLike(int $id)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $result = $this->feedService->toggleLike($id, $userId);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result['body']);
        }

        // Return redirect back directly with scroll anchor #post-ID to preserve scroll position
        $referer = (string)$this->request->getServer('HTTP_REFERER');
        if ($referer) {
            // Remove existing anchor if present
            $cleanUrl = strtok($referer, '#');
            return redirect()->to($cleanUrl . '#post-' . $id);
        }

        return redirect()->to(base_url('feed#post-' . $id));
    }

    /**
     * Add Comment
     */
    public function addComment(int $id)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $commentText = (string)$this->request->getPost('comment');
        $result = $this->feedService->addComment($id, $userId, $commentText);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result['body']);
        }

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->with('error', $result['body']['message']);
        }

        $referer = (string)$this->request->getServer('HTTP_REFERER');
        if ($referer) {
            $cleanUrl = strtok($referer, '#');
            return redirect()->to($cleanUrl . '#post-' . $id)->with('success', $result['body']['message']);
        }

        return redirect()->to(base_url('feed#post-' . $id))->with('success', $result['body']['message']);
    }

    /**
     * Delete Comment
     */
    public function deleteComment(int $id)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $result = $this->feedService->deleteComment($id, $userId);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result['body']);
        }

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->with('error', $result['body']['message']);
        }

        return redirect()->back()->with('success', $result['body']['message']);
    }

    /**
     * Toggle Follow / Unfollow (AJAX or Form with Scroll Anchor)
     */
    public function toggleFollow(int $targetUserId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $result = $this->feedService->toggleFollow($userId, $targetUserId);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result['body']);
        }

        $postId = (int)$this->request->getPost('post_id');
        $anchor = $postId ? '#post-' . $postId : '';

        $referer = (string)$this->request->getServer('HTTP_REFERER');
        if ($referer) {
            $cleanUrl = strtok($referer, '#');
            return redirect()->to($cleanUrl . $anchor)->with('success', $result['body']['message']);
        }

        return redirect()->to(base_url('feed' . $anchor))->with('success', $result['body']['message']);
    }

    /**
     * User Social Wall Profile
     */
    public function userWall(int $targetUserId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $profile = $this->feedService->getUserSocialProfile($targetUserId, $userId);

        if ($profile['body']['status'] !== 'success') {
            return redirect()->to('/feed')->with('error', $profile['body']['message']);
        }

        $data = [
            'title'       => 'Dinding Sosial - ' . $profile['body']['data']['user']['full_name'],
            'profileData' => $profile['body']['data'],
        ];

        return view('App\Modules\Feed\Views\user_wall', $data);
    }

    /**
     * Single Post Detail Page
     */
    public function singlePost(int $postId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $post = $this->feedService->getSinglePostById($postId, $userId);
        if (!$post) {
            return redirect()->to('/feed')->with('error', 'Postingan status tidak ditemukan atau telah dihapus.');
        }

        $whoToFollow = $this->feedService->getWhoToFollow($userId);
        $myProfile   = $this->feedService->getUserSocialProfile($userId, $userId);

        $data = [
            'title'       => 'Detail Status - ' . $post['author_name'],
            'post'        => $post,
            'whoToFollow' => $whoToFollow,
            'myProfile'   => $myProfile['body']['data'] ?? [],
        ];

        return view('App\Modules\Feed\Views\single_post', $data);
    }

    /**
     * Repost Status (AJAX / Form with Scroll Anchor)
     */
    public function repost(int $postId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $result = $this->feedService->repost($postId, $userId);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result['body']);
        }

        $referer = (string)$this->request->getServer('HTTP_REFERER');
        if ($referer) {
            $cleanUrl = strtok($referer, '#');
            return redirect()->to($cleanUrl . '#post-' . $postId)->with('success', $result['body']['message']);
        }

        return redirect()->to(base_url('feed#post-' . $postId))->with('success', $result['body']['message']);
    }

    /**
     * Toggle Save / Bookmark Post (AJAX / Form)
     */
    public function toggleSave(int $postId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $result = $this->feedService->toggleBookmark($postId, $userId);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result['body']);
        }

        // Return redirect with scroll anchor #post-ID to preserve scroll position
        $referer = (string)$this->request->getServer('HTTP_REFERER');
        if ($referer) {
            $cleanUrl = strtok($referer, '#');
            return redirect()->to($cleanUrl . '#post-' . $postId)->with('success', $result['body']['message']);
        }

        return redirect()->to(base_url('feed#post-' . $postId))->with('success', $result['body']['message']);
    }

    /**
     * Share to Chat Inbox (AJAX)
     */
    public function shareToChat(int $postId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $targetType = (string)$this->request->getPost('target_type');
        $targetId   = (int)$this->request->getPost('target_id');

        $result = $this->feedService->shareToChat($postId, $userId, $targetType, $targetId);
        return $this->response->setJSON($result['body']);
    }

    /**
     * Load More Posts (AJAX JSON)
     */
    public function loadMore()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $filter = $this->request->getGet('filter') === 'following' ? 'following' : 'all';
        $offset = (int)($this->request->getGet('offset') ?? 0);
        $limit  = 10;

        $posts = $this->feedService->getFeedPosts($userId, $filter, $limit, $offset);
        $total = $this->feedService->getFeedPostCount($userId, $filter);

        // Render posts HTML via a partial view
        $html = '';
        foreach ($posts as $post) {
            $html .= view('App\Modules\Feed\Views\_post_card', ['post' => $post]);
        }

        return $this->response->setJSON([
            'status'   => 'success',
            'html'     => $html,
            'has_more' => ($offset + $limit) < $total,
            'next_offset' => $offset + $limit,
            'total'    => $total,
        ]);
    }

    /**
     * Check for new posts since a given timestamp (AJAX JSON)
     */
    public function checkNewPosts()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['count' => 0]);
        }

        $filter    = $this->request->getGet('filter') === 'following' ? 'following' : 'all';
        $sinceTime = $this->request->getGet('since') ?? date('Y-m-d H:i:s', strtotime('-1 minute'));

        $count = $this->feedService->getNewPostsCount($userId, $filter, $sinceTime);

        return $this->response->setJSON(['count' => $count]);
    }

    /**
     * Get Followers List (AJAX JSON)
     */
    public function getFollowers(int $targetUserId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON([]);
        }

        $followers = $this->feedService->getFollowersList($targetUserId, $userId);
        return $this->response->setJSON(['status' => 'success', 'data' => $followers]);
    }

    /**
     * Get Following List (AJAX JSON)
     */
    public function getFollowing(int $targetUserId)
    {
        $userId = session()->get('user_id');
        $following = $this->feedService->getFollowingList($targetUserId, $userId);
        return $this->response->setJSON(['status' => 'success', 'data' => $following]);
    }

    /**
     * Search Users (AJAX JSON)
     */
    public function searchUsers()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        try {
            $query = (string)($this->request->getGet('q') ?? '');
            $users = $this->feedService->searchUsers($query, $userId);
            return $this->response->setJSON(['status' => 'success', 'data' => $users]);
        } catch (\Throwable $e) {
            log_message('error', 'Feed searchUsers error: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
