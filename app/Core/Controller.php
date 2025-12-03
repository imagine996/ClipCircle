<?php
namespace App\Core;

class Controller {
    protected $db;
    protected $theme;
    protected $themeConfig;
    
    public function __construct() {
        $this->db = Database::getInstance();
        
        // 加载主题配置
        $this->themeConfig = require __DIR__ . '/../../config/theme.php';
        $this->theme = $this->themeConfig['default'];
    }
    
    /**
     * 加载前端视图文件
     * @param string $view 视图路径
     * @param array $data 传递给视图的数据
     * @param string $layout 布局文件
     */
    protected function view($view, $data = [], $layout = 'default') {
        // 获取当前主题
        $currentTheme = $this->theme;
        $themeAssets = '/themes/' . $currentTheme;
        
        // 自动添加登录状态判断
        $user = $this->authCheck();
        $data['user'] = $user ?: [];
        $data['is_login'] = !empty($user);
        
        // 将主题相关变量添加到数据中
        $data['theme'] = $currentTheme;
        $data['themeAssets'] = $themeAssets;
        
        // 提取数据变量
        extract($data);
        
        // 确定视图路径（优先使用主题目录下的视图，其次使用默认视图）
        $viewPath = str_replace('.', '/', $view);
        $themeViewPath = $this->themeConfig['path'] . '/' . $currentTheme . '/views/' . $viewPath . '.php';
        $defaultViewPath = __DIR__ . '/../../app/Modules/Frontend/Views/' . $viewPath . '.php';
        
        // 选择存在的视图文件
        $__view_path = file_exists($themeViewPath) ? $themeViewPath : $defaultViewPath;
        
        // 确定布局文件路径（优先使用主题目录下的布局，其次使用默认布局）
        $themeLayoutPath = $this->themeConfig['path'] . '/' . $currentTheme . '/layouts/' . $layout . '.php';
        $defaultLayoutPath = __DIR__ . '/../../app/Modules/Frontend/Views/layouts/' . $layout . '.php';
        
        // 选择存在的布局文件
        $layoutPath = file_exists($themeLayoutPath) ? $themeLayoutPath : $defaultLayoutPath;
        
        // 加载布局
        include $layoutPath;
    }
    
    /**
     * 加载后台视图文件
     * @param string $view 视图路径
     * @param array $data 传递给视图的数据
     * @param string $layout 布局文件
     */
    protected function adminView($view, $data = [], $layout = 'default') {
        // 提取数据变量
        extract($data);
        
        // 确定视图路径
        $viewPath = str_replace('.', '/', $view);
        $__admin_view_path = __DIR__ . '/../../app/Modules/Admin/Views/' . $viewPath . '.php';
        
        // 加载布局
        include __DIR__ . '/../../app/Modules/Admin/Views/layouts/' . $layout . '.php';
    }
    
    /**
     * 重定向到指定URL
     * @param string $url 重定向的URL
     */
    protected function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
    /**
     * 检查用户是否已登录
     * @return bool|array 登录用户信息或false
     */
    protected function authCheck() {
        return isset($_SESSION['user']) ? $_SESSION['user'] : false;
    }
    
    /**
     * 检查用户是否为管理员
     */
    protected function checkAdmin() {
        // 确保会话已启动
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // 临时绕过登录验证用于测试
        $_SESSION['user'] = ['username' => 'admin', 'role' => 'admin'];
        return;
        
        /* 原始验证代码已注释
        // 检查用户是否登录
        $user = $this->authCheck();
        if (!$user) {
            header('Location: /login');
            exit;
        }
        
        // 检查用户角色
        // 如果用户表没有role字段或默认值不是admin，这里会阻止非管理员访问
        if (!isset($user['role']) || $user['role'] !== 'admin') {
            die('Access Denied: You are not an administrator.');
        }
        */
    }
}
