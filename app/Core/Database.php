<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $pdo;
    
    private function __construct() {
        // 检查是否在安装模式下
        $installPath = __DIR__ . '/../../public/install/index.php';
        $isInstalling = file_exists($installPath) && strpos($_SERVER['REQUEST_URI'], '/install/') !== false;
        
        // 在安装模式下，不自动连接数据库
        if ($isInstalling) {
            return;
        }
        
        try {
            // 从配置文件加载数据库配置
            $config = require __DIR__ . '/../../config/database.php';
            
            // 只有当数据库配置完整时才尝试连接
            if (!empty($config['host']) && !empty($config['database']) && !empty($config['username'])) {
                $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
                $this->pdo = new PDO($dsn, $config['username'], $config['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            }
        } catch (PDOException $e) {
            // 记录数据库连接错误
            error_log("Database connection failed: " . $e->getMessage());
            // 不中断程序，而是将pdo设为null，让调用者处理
            $this->pdo = null;
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function __call($method, $args) {
        if ($this->pdo === null) {
            error_log("Database operation failed: Connection not established");
            return false;
        }
        try {
            return call_user_func_array([$this->pdo, $method], $args);
        } catch (PDOException $e) {
            error_log("Database operation failed: " . $e->getMessage());
            return false;
        }
    }
}
