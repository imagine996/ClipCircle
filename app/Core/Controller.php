<?php
namespace App\Core;
use App\Config\Database;

class Controller {
    
    // 获取当前激活的主题名
    protected function getActiveTheme() {
        static $theme = null;
        if ($theme === null) {
            $db = Database::getConnection();
            // 尝试获取设置，如果表不存在或没设置，回退到 default
            try {
                $stmt = $db->query("SELECT value FROM settings WHERE key_name = 'active_theme' LIMIT 1");
                $theme = $stmt ? $stmt->fetchColumn() : 'default';
            } catch (\Exception $e) {
                $theme = 'default';
            }
        }
        return $theme ?: 'default';
    }

    /**
     * 智能视图加载
     */
    protected function view(string $viewPath, array $data = []): void {
        extract($data);
        
        // 1. 获取当前主题
        $theme = $this->getActiveTheme();

        // 2. 路径判定逻辑
        // 如果是后台(admin)或登录(auth)，直接去根Views找
        $isSystemView = (str_starts_with($viewPath, 'admin/') || str_starts_with($viewPath, 'auth/'));
        
        if ($isSystemView) {
            $realPath = __DIR__ . "/../Views/$viewPath.php";
        } else {
            // === 关键点：去主题目录找 ===
            $realPath = __DIR__ . "/../Views/themes/$theme/$viewPath.php";
            
            // 如果找不到，回退到 default 主题
            if (!file_exists($realPath)) {
                $realPath = __DIR__ . "/../Views/themes/default/$viewPath.php";
            }
        }

        // 3. 渲染
        if (file_exists($realPath)) {
            // 注入 $themeUrl 变量供视图使用 css/js
            $themeUrl = "themes/$theme"; 
            require $realPath;
        } else {
            // 调试信息：如果找不到文件，打印出来路径
            die("<h1>View Error</h1><p>无法找到视图文件，系统尝试加载路径：</p><pre>$realPath</pre><p>请检查文件是否在 <code>app/Views/themes/default/</code> 目录下。</p>");
        }
    }

    protected function json(mixed $data): void {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect(string $url): void {
        header("Location: $url");
        exit;
    }

    protected function authCheck(): ?array {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return $_SESSION['user'] ?? null;
    }
}