<?php

namespace App\Modules\User\Models;

use App\Core\Model;

class User extends Model {
    protected $table = 'users';
    
    /**
     * 根据ID获取用户信息
     * @param int $id 用户ID
     * @return array|null 用户信息
     */
    public function getById($id) {
        return $this->find($id);
    }
    
    /**
     * 根据用户名获取用户信息
     * @param string $username 用户名
     * @return array|null 用户信息
     */
    public function getByUsername($username) {
        $sql = "SELECT * FROM {$this->table} WHERE username = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
    
    /**
     * 根据邮箱获取用户信息
     * @param string $email 邮箱
     * @return array|null 用户信息
     */
    public function getByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    /**
     * 获取所有用户列表
     * @param int $limit 限制数量
     * @param int $offset 偏移量
     * @return array 用户列表
     */
    public function getAll($limit = 20, $offset = 0) {
        return $this->getAllRecords($limit, $offset);
    }
    
    /**
     * 创建新用户
     * @param array $data 用户数据
     * @return int|false 新创建的用户ID或false
     */
    public function createUser($data) {
        return $this->create($data);
    }
    
    /**
     * 更新用户信息
     * @param int $id 用户ID
     * @param array $data 更新数据
     * @return bool 更新结果
     */
    public function updateUser($id, $data) {
        return $this->update($id, $data);
    }
    
    /**
     * 删除用户
     * @param int $id 用户ID
     * @return bool 删除结果
     */
    public function deleteUser($id) {
        return $this->delete($id);
    }
    
    /**
     * 获取用户统计信息
     * @return array 统计数据
     */
    public function getStats() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        return $this->db->query($sql)->fetch();
    }
}
