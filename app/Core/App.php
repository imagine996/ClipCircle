<?php
namespace App\Core;

class App {
    private $router;
    private $db;
    
    public function __construct() {
        // 初始化路由
        $this->router = new Router();
        
        // 初始化数据库连接
        $this->db = Database::getInstance();
        
        // 启动会话
        session_start();
    }
    
    public function run() {
        // 处理请求
        $this->router->dispatch();
    }
}
