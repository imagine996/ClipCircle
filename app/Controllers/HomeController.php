<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\Video;
use App\Models\User;
use App\Config\Database;

class HomeController extends Controller {

    // 1. 首页 (推荐)
    public function index() {
        $videoModel = new Video();
        $videos = $videoModel->getRecommended();
        $this->renderHome('推荐', $videos, 'home');
    }

    // 2. 关注页
    public function following() {
        $user = $this->authCheck();
        if (!$user) {
            // 未登录跳去登录，或者显示空
            $this->redirect('/?c=Auth&a=login');
            return;
        }
        $videoModel = new Video();
        $videos = $videoModel->getFollowedVideos($user['id']);
        $this->renderHome('关注中', $videos, 'following');
    }

    // 3. 探索页 (随机发现)
    public function explore() {
        $videoModel = new Video();
        $videos = $videoModel->getRandom(20);
        $this->renderHome('探索发现', $videos, 'explore');
    }

    // 4. 直播页
    public function live() {
        $db = Database::getConnection();
        $lives = $db->query("SELECT * FROM live_streams WHERE status='live' ORDER BY viewers DESC")->fetchAll();
        $this->renderHome('直播 Live', [], 'live', $lives);
    }

    // 5. 搜索功能
    public function search() {
        $keyword = $_GET['q'] ?? '';
        if (empty($keyword)) {
            $this->redirect('/');
            return;
        }
        $videoModel = new Video();
        $results = $videoModel->search($keyword);
        $this->renderHome('搜索结果: ' . htmlspecialchars($keyword), $results, 'search');
    }

    // 6. 关注动作接口
    public function toggleFollow() {
        $user = $this->authCheck();
        if (!$user) die(json_encode(['code'=>401, 'msg'=>'请登录']));

        $targetId = $_POST['user_id'] ?? 0;
        $userModel = new User();
        
        if ($userModel->isFollowing($user['id'], $targetId)) {
            $userModel->unfollow($user['id'], $targetId);
            echo json_encode(['code'=>200, 'status'=>'unfollowed', 'msg'=>'已取消关注']);
        } else {
            $userModel->follow($user['id'], $targetId);
            echo json_encode(['code'=>200, 'status'=>'followed', 'msg'=>'关注成功']);
        }
    }

    // 私有辅助：统一渲染
    private function renderHome($title, $videos, $activeTab, $lives = []) {
        $this->view('home/index', [
            'page_title' => $title,
            'videos' => $videos,
            'lives' => $lives,      // 直播数据
            'active_tab' => $activeTab // 当前激活的菜单(home, following, explore, live)
        ]);
    }
    
    // Cookie页
    public function cookiePolicy() {
        $this->view('home/cookie');
    }
}