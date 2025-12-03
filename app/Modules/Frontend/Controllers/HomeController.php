<?php

namespace App\Modules\Frontend\Controllers;

use App\Core\Controller;
use App\Modules\Video\Services\VideoService;

class HomeController extends Controller
{
    private $videoService;
    
    public function __construct()
    {
        parent::__construct();
        
        // 初始化视频服务
        $this->videoService = new VideoService();
    }
    
    public function index()
    {
        // 1. 获取用户信息 (基类中已经封装了 authCheck，可以直接用)
        $user = $this->authCheck();
        
        // 2. 获取视频列表
        $videos = $this->videoService->getVideos();
        
        // 3. 模拟分类数据
        $categories = [
            '推荐', '热门', '游戏', '音乐', '生活', '科技', '娱乐', '教育'
        ];
        
        // 4. 准备数据
        $data = [
            'title'      => '首页 - MyVideo',
            'user'       => $user,
            'is_login'   => !empty($user),
            'videos'     => $videos,
            'categories' => $categories
        ];

        // 5. 调用基类的 view 方法渲染
        // 参数说明: 
        // 'home' -> 对应 views/home.php
        // $data  -> 数据数组
        // 'main' -> 对应 layouts/main.php (如果不传第三个参数，默认找 layouts/default.php)
        $this->view('home', $data, 'main');
    }
    
    public function search()
    {
        // 1. 获取用户信息
        $user = $this->authCheck();
        
        // 2. 获取搜索关键词
        $keyword = $_GET['q'] ?? '';
        
        // 3. 模拟分类数据
        $categories = [
            '推荐', '热门', '游戏', '音乐', '生活', '科技', '娱乐', '教育'
        ];
        
        // 4. 根据关键词获取视频列表（这里简化处理，实际应该使用VideoService进行搜索）
        $videos = [];
        if (!empty($keyword)) {
            $allVideos = $this->videoService->getVideos();
            // 简单的标题模糊匹配
            $videos = array_filter($allVideos, function($video) use ($keyword) {
                return strpos(strtolower($video['title']), strtolower($keyword)) !== false;
            });
        }
        
        // 5. 准备数据
        $data = [
            'title'      => '搜索结果 - MyVideo',
            'user'       => $user,
            'is_login'   => !empty($user),
            'videos'     => array_values($videos), // 重置数组索引
            'categories' => $categories,
            'keyword'    => $keyword
        ];

        // 6. 调用基类的 view 方法渲染搜索结果页面
        // 暂时复用home视图，后续可以创建专门的search视图
        $this->view('home', $data, 'main');
    }
}