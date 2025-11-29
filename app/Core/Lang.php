<?php
namespace App\Core;
use App\Config\Database;

class Lang {
    private static $phrases = [];
    private static $currentLang = 'zh-CN';

    // 加载语言包
    public static function load() {
        // 1. 优先级：Session (用户切换) > 数据库设置 (全局默认) > 代码默认
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (isset($_SESSION['lang'])) {
            self::$currentLang = $_SESSION['lang'];
        } else {
            // 尝试从数据库获取默认语言
            try {
                $db = Database::getConnection();
                $stmt = $db->query("SELECT value FROM settings WHERE key_name = 'default_language'");
                $val = $stmt->fetchColumn();
                if ($val) self::$currentLang = $val;
            } catch (\Exception $e) {}
        }

        // 2. 读取 JSON 文件
        $filePath = __DIR__ . '/../Languages/' . self::$currentLang . '.json';
        
        // 如果文件不存在，回退到 zh-CN
        if (!file_exists($filePath)) {
            $filePath = __DIR__ . '/../Languages/zh-CN.json';
        }

        if (file_exists($filePath)) {
            $json = file_get_contents($filePath);
            self::$phrases = json_decode($json, true) ?? [];
        }
    }

    // 获取翻译
    public static function get($key) {
        // 如果没加载过，先加载
        if (empty(self::$phrases)) self::load();
        
        // 返回对应文本，如果找不到，直接返回 key 名方便调试
        return self::$phrases[$key] ?? $key;
    }

    // 获取当前语言列表
    public static function getList() {
        $dir = __DIR__ . '/../Languages/';
        $files = glob($dir . '*.json');
        $list = [];
        foreach ($files as $f) {
            $name = basename($f, '.json');
            $list[] = $name;
        }
        return $list;
    }
}