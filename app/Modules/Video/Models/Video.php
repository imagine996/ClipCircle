<?php

namespace App\Modules\Video\Models;

use App\Core\Model;
use PDO;

class Video extends Model {
    protected $table = 'videos';
    
    /**
     * 根据ID获取视频信息
     * @param int $id 视频ID
     * @return array|null 视频信息
     */
    public function getById($id) {
        return parent::getById($id);
    }
    
    /**
     * 获取视频列表（带分页）
     * @param int $limit 限制数量
     * @param int $offset 偏移量
     * @return array 视频列表
     */
    public function getPaginated($limit = 20, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        // 使用bindValue显式地将参数绑定为整数类型
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * 创建新视频
     * @param array $data 视频数据
     * @return int|false 新创建的视频ID或false
     */
    public function createVideo($data) {
        return $this->create($data);
    }
    
    /**
     * 更新视频信息
     * @param int $id 视频ID
     * @param array $data 更新数据
     * @return bool 更新结果
     */
    public function updateVideo($id, $data) {
        return $this->update($id, $data);
    }
    
    /**
     * 删除视频
     * @param int $id 视频ID
     * @return bool 删除结果
     */
    public function deleteVideo($id) {
        return $this->delete($id);
    }
    
    /**
     * 获取视频统计信息
     * @return array 统计数据
     */
    public function getStats() {
        $sql = "SELECT COUNT(*) as total, SUM(views) as total_views FROM {$this->table}";
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }
    
    /**
     * 增加视频观看次数
     * @param int $id 视频ID
     * @return bool 更新结果
     */
    public function incrementViews($id) {
        $sql = "UPDATE {$this->table} SET views = views + 1 WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * 根据用户ID获取视频列表
     * @param int $userId 用户ID
     * @param int $limit 限制数量
     * @param int $offset 偏移量
     * @return array 视频列表
     */
    public function getByUserId($userId, $limit = 20, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        // 使用bindValue显式地将参数绑定为整数类型
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
