<?php

namespace App\Modules\Admin\Controllers;

use App\Core\Controller;

class AdminController extends Controller {
    
    public function __construct() {
        // 检查是否登录且是管理员
        $this->checkAdmin();
    }
    
    /**
     * 后台仪表盘
     */
    public function dashboard() {
        // 模拟数据
        $stats = [
            'total_videos' => 125,
            'total_users' => 2300,
            'videos_pending' => 15,
            'total_views' => 15600,
            'total_comments' => 890
        ];
        
        $recentVideos = [
            ['id' => 1, 'title' => '精彩瞬间回顾', 'uploader' => '用户1', 'views' => 1200, 'date' => '2023-01-15'],
            ['id' => 2, 'title' => '教学视频', 'uploader' => '用户2', 'views' => 800, 'date' => '2023-01-14'],
            ['id' => 3, 'title' => '游戏直播剪辑', 'uploader' => '用户3', 'views' => 2500, 'date' => '2023-01-13'],
        ];
        
        $data = [
            'page_title' => '仪表盘',
            'page' => 'dashboard',
            'stats' => $stats,
            'recentVideos' => $recentVideos,
            'user' => $this->authCheck()
        ];
        
        $this->adminView('dashboard.index', $data);
    }
    
    /**
     * 视频管理
     */
    public function videos() {
        $data = [
            'page_title' => '视频管理',
            'page' => 'videos',
            'user' => $this->authCheck()
        ];
        
        $this->adminView('videos.index', $data);
    }
    
    /**
     * 用户管理
     */
    public function users() {
        $data = [
            'page_title' => '用户管理',
            'page' => 'users',
            'user' => $this->authCheck()
        ];
        
        $this->adminView('users.index', $data);
    }
    
    /**
     * 评论管理
     */
    public function comments() {
        $data = [
            'page_title' => '评论管理',
            'page' => 'comments',
            'user' => $this->authCheck()
        ];
        
        $this->adminView('comments.index', $data);
    }
    
    /**
     * 直播管理
     */
    public function live() {
        $data = [
            'page_title' => '直播管理',
            'page' => 'live',
            'user' => $this->authCheck()
        ];
        
        $this->adminView('live.index', $data);
    }
    
    /**
     * 系统设置
     */
    public function settings() {
        $data = [
            'page_title' => '系统设置',
            'page' => 'settings',
            'user' => $this->authCheck()
        ];
        
        $this->adminView('settings.index', $data);
    }
}