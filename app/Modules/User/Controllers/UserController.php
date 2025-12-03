<?php

namespace App\Modules\User\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Modules\User\Models\User;

class UserController extends Controller {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }
    
    /**
     * 获取用户列表
     * @return array 用户列表
     */
    public function getUsers() {
        return $this->userModel->getAll();
    }
    
    /**
     * 获取用户详情
     * @param int $id 用户ID
     * @return array|null 用户详情
     */
    public function getUser($id) {
        return $this->userModel->getById($id);
    }
    
    /**
     * 根据用户名获取用户
     * @param string $username 用户名
     * @return array|null 用户详情
     */
    public function getUserByUsername($username) {
        return $this->userModel->getByUsername($username);
    }
    
    /**
     * 创建用户
     * @param array $data 用户数据
     * @return int|false 新用户ID或false
     */
    public function createUser($data) {
        // 密码加密
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        $data['created_at'] = date('Y-m-d H:i:s');
        
        return $this->userModel->createUser($data);
    }
    
    /**
     * 更新用户信息
     * @param int $id 用户ID
     * @param array $data 更新数据
     * @return bool 更新结果
     */
    public function updateUser($id, $data) {
        // 如果有密码，加密处理
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->userModel->updateUser($id, $data);
    }
    
    /**
     * 删除用户
     * @param int $id 用户ID
     * @return bool 删除结果
     */
    public function deleteUser($id) {
        return $this->userModel->deleteUser($id);
    }
    
    /**
     * 获取用户统计
     * @return array 统计数据
     */
    public function getStats() {
        return $this->userModel->getStats();
    }
}
