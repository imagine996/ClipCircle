<?php
namespace App\Models;
use App\Config\Database;

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // ... 之前的代码 ...

    // 新增：获取所有用户
    public function getAll(): array {
        return $this->db->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
    }

    // 新增：统计用户总数
    public function count(): int {
        return $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn(); 
    }
}