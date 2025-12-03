<?php

namespace App\Modules\Video\Controllers;

use App\Core\Controller;
use App\Modules\Video\Models\Video;

class VideoController extends Controller {
    private $videoModel;
    
    public function __construct() {
        parent::__construct();
        $this->videoModel = new Video();
    }
    
    /**
     * 获取视频列表
     * @param int $limit 限制数量
     * @param int $offset 偏移量
     * @return array 视频列表
     */
    public function getVideos($limit = 20, $offset = 0) {
        return $this->videoModel->getPaginated($limit, $offset);
    }
    
    /**
     * 增加视频观看次数
     * @param int $id 视频ID
     * @return bool 更新结果
     */
    public function incrementViews($id) {
        return $this->videoModel->incrementViews($id);
    }
    
    /**
     * 根据用户ID获取视频列表
     * @param int $userId 用户ID
     * @param int $limit 限制数量
     * @param int $offset 偏移量
     * @return array 视频列表
     */
    public function getVideosByUserId($userId, $limit = 20, $offset = 0) {
        return $this->videoModel->getByUserId($userId, $limit, $offset);
    }
    
    /**
     * 获取视频详情
     * @param int $id 视频ID
     * @return array|null 视频详情
     */
    public function getVideo($id) {
        return $this->videoModel->getById($id);
    }
    
    /**
     * 创建视频
     * @param array $data 视频数据
     * @return int|false 新视频ID或false
     */
    public function createVideo($data) {
        // 添加创建者信息
        $user = $this->authCheck();
        $data['user_id'] = $user['id'];
        $data['created_at'] = date('Y-m-d H:i:s');
        
        return $this->videoModel->createVideo($data);
    }
    
    /**
     * 更新视频
     * @param int $id 视频ID
     * @param array $data 更新数据
     * @return bool 更新结果
     */
    public function updateVideo($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->videoModel->updateVideo($id, $data);
    }
    
    /**
     * 删除视频
     * @param int $id 视频ID
     * @return bool 删除结果
     */
    public function deleteVideo($id) {
        return $this->videoModel->deleteVideo($id);
    }
    
    /**
     * 获取视频统计
     * @return array 统计数据
     */
    public function getStats() {
        return $this->videoModel->getStats();
    }
}
