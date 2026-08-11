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

        $totalPosts = $this->feedService->getFeedPostCount($userId, $filter);

        $data = [
            'title'          => 'Beranda Club - Feed Sosial MMC',
            'posts'          => $posts,
            'whoToFollow'    => $whoToFollow,
            'myProfile'      => $myProfile['body']['data'] ?? [],
            'todayBirthdays' => $todayBirthdays,
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
     * Toggle Like (AJAX)
     */
    public function toggleLike(int $id)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $result = $this->feedService->toggleLike($id, $userId);
        return $this->response->setJSON($result['body']);
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

        return redirect()->to('/feed#post-' . $id)->with('success', $result['body']['message']);
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
     * Toggle Follow / Unfollow (AJAX or Form)
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

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->with('error', $result['body']['message']);
        }

        return redirect()->back()->with('success', $result['body']['message']);
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
        if (!$userId) {
            return $this->response->setJSON([]);
        }

        $following = $this->feedService->getFollowingList($targetUserId, $userId);
        return $this->response->setJSON(['status' => 'success', 'data' => $following]);
    }
}
