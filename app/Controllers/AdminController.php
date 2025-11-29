<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\Video;
use App\Models\User;
use App\Config\Database;

class AdminController extends Controller {
    
    // =====================================================
    // 1. 主入口：负责加载页面视图
    // =====================================================
    public function index() {
        // 1. 权限验证
        $currentUser = $this->authCheck();
        if (!$currentUser || $currentUser['role'] !== 'admin') {
            die("无权访问：请先登录管理员账号");
        }

        $videoModel = new Video();
        $userModel = new User();
        $db = Database::getConnection();

        // 2. 获取当前页面 (默认为 dashboard)
        $page = $_GET['page'] ?? 'dashboard';

        // 3. 全局数据
        $pendingCount = count($videoModel->getPending());

        // 4. 初始化数据数组
        $data = [
            'user' => $currentUser,
            'page' => $page,
            'pending_count' => $pendingCount
        ];

        // 5. 根据页面加载特定数据
        switch ($page) {
            case 'dashboard':
                $data['stats'] = [
                    'users' => $userModel->count(),
                    'videos' => count($videoModel->getAllForAdmin()),
                    'pending' => $pendingCount,
                    'php_ver' => phpversion(),
                    'db_ver'  => $db->getAttribute(\PDO::ATTR_SERVER_VERSION)
                ];
                break;
            
            case 'users':
                $data['user_list'] = $userModel->getAll();
                break;
            
            case 'themes':
                $data['themes'] = $this->scanThemes();
                $data['current_theme'] = $db->query("SELECT value FROM settings WHERE key_name = 'active_theme'")->fetchColumn();
                break;
                
            case 'system_status':
                $data['server_info'] = [
                    'os' => php_uname('s') . ' ' . php_uname('r'),
                    'software' => $_SERVER['SERVER_SOFTWARE'],
                    'upload_max' => ini_get('upload_max_filesize'),
                    'post_max' => ini_get('post_max_size'),
                    'memory_limit' => ini_get('memory_limit'),
                    'disk_free' => disk_free_space(".") ? round(disk_free_space(".") / 1024 / 1024 / 1024, 2) . ' GB' : 'N/A'
                ];
                break;

            case 'languages':
                // 获取所有语言文件列表
                $data['lang_list'] = \App\Core\Lang::getList();
                
                // 获取当前正在编辑的语言（默认 zh-CN）
                $editLang = $_GET['edit'] ?? 'zh-CN';
                $filePath = __DIR__ . '/../Languages/' . $editLang . '.json';
                
                if (file_exists($filePath)) {
                    $data['lang_data'] = json_decode(file_get_contents($filePath), true);
                    $data['current_edit'] = $editLang;
                } else {
                    $data['lang_data'] = [];
                    $data['current_edit'] = 'Error';
                }
                break;
        }

        // 6. 渲染视图
        $this->view('admin/index', $data);
    }

    // =====================================================
    // 2. 功能方法：处理表单提交和逻辑 (必须在 index 方法外面)
    // =====================================================

    // 保存语言包
    public function saveLanguage() {
        $currentUser = $this->authCheck();
        if ($currentUser['role'] !== 'admin') die("Access Denied");

        $lang = $_POST['lang_name'];
        $keys = $_POST['keys'] ?? [];
        $values = $_POST['values'] ?? [];
        
        // 组合数组
        $newData = [];
        for ($i = 0; $i < count($keys); $i++) {
            if (!empty($keys[$i])) {
                $newData[$keys[$i]] = $values[$i];
            }
        }

        // 写入文件
        $filePath = __DIR__ . '/../Languages/' . $lang . '.json';
        file_put_contents($filePath, json_encode($newData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->redirect('/?c=Admin&page=languages&edit=' . $lang);
    }

    // 创建新语言
    public function createLanguage() {
        $currentUser = $this->authCheck();
        if ($currentUser['role'] !== 'admin') die("Access Denied");
        
        $name = trim($_POST['new_lang_name']); // e.g., ja-JP
        if (!$name) die("Name required");
        
        // 复制默认语言作为模板
        $defaultPath = __DIR__ . '/../Languages/zh-CN.json';
        $newPath = __DIR__ . '/../Languages/' . $name . '.json';
        
        if (!file_exists($newPath)) {
            copy($defaultPath, $newPath);
        }
        
        $this->redirect('/?c=Admin&page=languages&edit=' . $name);
    }

    // 审核操作
    public function audit() {
        $currentUser = $this->authCheck();
        if ($currentUser['role'] !== 'admin') return;

        $id = $_POST['id'];
        $action = $_POST['action'];
        $videoModel = new Video();

        if ($action === 'delete') {
             $videoModel->updateStatus($id, 'rejected'); 
        } else {
            $status = ($action === 'pass') ? 'published' : 'rejected';
            $videoModel->updateStatus($id, $status);
        }
        $referer = $_SERVER['HTTP_REFERER'] ?? '/?c=Admin';
        $this->redirect($referer);
    }
    
    // 保存主题设置
    public function saveTheme() {
        $currentUser = $this->authCheck();
        if ($currentUser['role'] !== 'admin') die("Access Denied");

        $newTheme = $_POST['theme_id'];
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE settings SET value = ? WHERE key_name = 'active_theme'");
        $stmt->execute([$newTheme]);
        if ($stmt->rowCount() == 0) {
             $db->prepare("INSERT IGNORE INTO settings (key_name, value) VALUES ('active_theme', ?)")->execute([$newTheme]);
        }
        $this->redirect('/?c=Admin&page=themes');
    }

    // =====================================================
    // 3. 私有辅助方法
    // =====================================================

    private function scanThemes() {
        $themeDir = __DIR__ . '/../Views/themes/';
        $themes = [];
        if (is_dir($themeDir)) {
            $dirs = scandir($themeDir);
            foreach ($dirs as $dir) {
                if ($dir !== '.' && $dir !== '..' && is_dir($themeDir . $dir)) {
                    $themes[] = ['id' => $dir, 'name' => ucfirst($dir)];
                }
            }
        }
        return $themes;
    }
}