<?php
namespace App\Modules\Admin\Controllers;

use App\Core\Controller;

class ThemeController extends Controller {
    /**
     * 主题管理首页
     */
    public function index() {
        // 获取可用主题列表
        $themes = [];
        $themeConfig = $this->themeConfig;
        
        // 检查主题目录下的所有主题
        $themeDir = $themeConfig['path'];
        if (is_dir($themeDir)) {
            $themeFolders = scandir($themeDir);
            foreach ($themeFolders as $folder) {
                if ($folder != '.' && $folder != '..' && is_dir($themeDir . '/' . $folder)) {
                    // 如果主题在配置文件中存在，则使用配置信息，否则使用默认信息
                    $themeInfo = $themeConfig['themes'][$folder] ?? [
                        'name' => ucfirst($folder),
                        'description' => '未配置的主题',
                        'author' => '未知',
                        'version' => '1.0.0',
                    ];
                    
                    $themes[$folder] = $themeInfo;
                }
            }
        }
        
        $data = [
            'currentTheme' => $themeConfig['default'],
            'themes' => $themes,
            'title' => '主题管理',
            'page' => 'theme',
            'user' => $this->authCheck()
        ];
        
        $this->adminView('theme.index', $data);
    }
    
    /**
     * 切换主题
     */
    public function change() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $theme = $_POST['theme'] ?? 'default';
            
            // 更新主题配置
            $themeConfigPath = __DIR__ . '/../../../config/theme.php';
            $themeConfig = require $themeConfigPath;
            $themeConfig['default'] = $theme;
            
            // 保存配置文件
            $configContent = '<?php
/**
 * 主题配置文件
 */

return ' . var_export($themeConfig, true) . ';
';
            
            file_put_contents($themeConfigPath, $configContent);
            
            // 重定向回主题管理页面
            header('Location: /admin/theme');
            exit;
        }
        
        // 如果不是POST请求，重定向回主题管理页面
        header('Location: /admin/theme');
        exit;
    }
}
