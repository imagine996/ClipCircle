<?php
namespace App\Core;

class Auth {
    private $db;
    private $tablePrefix;
    
    public function __construct() {
        $this->db = Database::getInstance();
        // 加载数据库配置
        $config = require __DIR__ . '/../../config/database.php';
        $this->tablePrefix = $config['prefix'];
    }
    
    /**
     * 用户注册
     * @param array $userData 用户数据
     * @return bool 是否注册成功
     */
    public function register($userData) {
        // 检查邮箱是否已存在
        $sql = "SELECT id FROM " . $this->tablePrefix . "users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userData['email']]);
        
        if ($stmt->fetch()) {
            return false; // 邮箱已存在
        }
        
        // 密码加密
        $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        
        // 创建用户
        $sql = "INSERT INTO " . $this->tablePrefix . "users (username, email, password, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $userData['username'],
            $userData['email'],
            $userData['password']
        ]);
    }
    
    /**
     * 用户登录
     * @param string $usernameOrEmail 用户名或邮箱
     * @param string $password 密码
     * @return bool|array 用户信息或false
     */
    public function login($usernameOrEmail, $password) {
        // 获取用户信息 - 支持用户名或邮箱登录
        $sql = "SELECT * FROM " . $this->tablePrefix . "users WHERE username = ? OR email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return false; // 用户不存在
        }
        
        // 验证密码
        if (!password_verify($password, $user['password'])) {
            return false; // 密码错误
        }
        
        // 存储用户信息到会话
        $_SESSION['user'] = $user;
        return $user;
    }
    
    /**
     * 用户登出
     */
    public function logout() {
        unset($_SESSION['user']);
        session_destroy();
    }
    
    /**
     * 检查用户是否已登录
     * @return bool|array 登录用户信息或false
     */
    public function check() {
        return isset($_SESSION['user']) ? $_SESSION['user'] : false;
    }
    
    /**
     * 检查用户是否为管理员
     * @return bool 是否为管理员
     */
    public function checkAdmin() {
        $user = $this->check();
        return $user && $user['role'] === 'admin';
    }
}
