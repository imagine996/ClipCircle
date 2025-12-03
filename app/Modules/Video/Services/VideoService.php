<?php

namespace App\Modules\Video\Services;

use App\Modules\Video\Models\Video;

class VideoService
{
    private $videoModel;
    
    public function __construct()
    {
        $this->videoModel = new Video();
    }
    
    /**
     * 上传视频
     */
    public function uploadVideo($videoData, $videoFile, $coverFile)
    {
        // 开启错误报告以便调试
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        ini_set('log_errors', 1);
        ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/logs/error.log');
        
        // 记录调试信息
        error_log("[DEBUG] 开始上传视频，视频数据: " . print_r($videoData, true));
        error_log("[DEBUG] 视频文件: " . print_r($videoFile, true));
        error_log("[DEBUG] 封面文件: " . print_r($coverFile, true));
        
        // 验证视频数据
        if (empty($videoData['title'])) {
            error_log("[ERROR] 视频标题不能为空");
            return ['success' => false, 'message' => '视频标题不能为空'];
        }
        
        // 验证视频文件
        if (empty($videoFile) || $videoFile['error'] !== UPLOAD_ERR_OK) {
            $errorMsg = empty($videoFile) ? '未接收到视频文件' : '视频文件上传错误: ' . $this->getUploadErrorMsg($videoFile['error']);
            error_log("[ERROR] {$errorMsg}");
            return ['success' => false, 'message' => '请选择要上传的视频文件'];
        }
        
        // 验证视频大小（限制为50MB）
        $maxVideoSize = 50 * 1024 * 1024; // 50MB
        if ($videoFile['size'] > $maxVideoSize) {
            error_log("[ERROR] 视频文件大小超过限制，大小: {$videoFile['size']}，限制: {$maxVideoSize}");
            return ['success' => false, 'error' => '视频文件大小不能超过50MB'];
        }
        
        // 创建视频目录
        $videoDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/videos/';
        $coverDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/covers/';
        $logDir = $_SERVER['DOCUMENT_ROOT'] . '/logs/';
        
        // 创建日志目录
        if (!file_exists($logDir)) {
            if (!mkdir($logDir, 0777, true)) {
                error_log("[ERROR] 无法创建日志目录: {$logDir}");
            }
        }
        
        if (!file_exists($videoDir)) {
            if (mkdir($videoDir, 0777, true)) {
                error_log("[DEBUG] 创建视频目录成功: {$videoDir}");
            } else {
                error_log("[ERROR] 无法创建视频目录: {$videoDir}");
                return ['success' => false, 'error' => '服务器存储目录创建失败'];
            }
        }
        
        if (!file_exists($coverDir)) {
            if (mkdir($coverDir, 0777, true)) {
                error_log("[DEBUG] 创建封面目录成功: {$coverDir}");
            } else {
                error_log("[ERROR] 无法创建封面目录: {$coverDir}");
                return ['success' => false, 'error' => '服务器存储目录创建失败'];
            }
        }
        
        // 生成唯一的文件名
        $videoFileName = time() . '_' . uniqid() . '_' . basename($videoFile['name']);
        $videoPath = $videoDir . $videoFileName;
        
        error_log("[DEBUG] 准备移动视频文件，临时路径: {$videoFile['tmp_name']}，目标路径: {$videoPath}");
        
        // 移动上传的视频文件
        if (!move_uploaded_file($videoFile['tmp_name'], $videoPath)) {
            error_log("[ERROR] 视频文件移动失败，临时文件: {$videoFile['tmp_name']}，目标路径: {$videoPath}");
            return ['success' => false, 'message' => '视频文件上传失败'];
        }
        
        // 直接使用上传的视频文件，不进行转码
        $videoPath = '/uploads/videos/' . $videoFileName;
        $duration = 0; // 不获取视频时长
        
        // 处理封面图
        // 确保封面目录存在
        if (!is_dir($coverDir)) {
            mkdir($coverDir, 0755, true);
        }
        
        if (!empty($coverFile) && $coverFile['error'] === UPLOAD_ERR_OK) {
            // 验证封面图片类型
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = mime_content_type($coverFile['tmp_name']);
            
            if (!in_array($fileType, $allowedTypes)) {
                // 如果文件类型不允许，使用默认封面
                $coverPath = '/assets/images/default-cover.png';
            } else {
                $coverName = time() . '_' . uniqid() . '_' . basename($coverFile['name']);
                $absoluteCoverPath = $coverDir . $coverName;
                
                if (!move_uploaded_file($coverFile['tmp_name'], $absoluteCoverPath)) {
                    // 如果封面图上传失败，使用默认封面
                    $coverPath = '/assets/images/default-cover.png';
                } else {
                    // 保存相对路径
                    $coverPath = '/uploads/covers/' . $coverName;
                }
            }
        } else {
            // 用户未上传封面，使用默认封面
            $coverPath = '/assets/images/default-cover.png';
        }
        
        // 保存视频信息到数据库
        unset($videoData['category']); // 移除category字段，数据库中不存在该列
        $videoData['video_path'] = $videoPath;
        $videoData['cover'] = $coverPath; // 使用数据库中实际的字段名cover
        $videoData['duration'] = $duration;
        $videoData['views'] = 0;
        $videoData['likes'] = 0;
        $videoData['dislikes'] = 0;
        $videoData['status'] = 1;
        $videoData['created_at'] = date('Y-m-d H:i:s'); // 使用数据库中实际的字段名created_at
        
        error_log("[DEBUG] 准备保存视频信息到数据库: " . print_r($videoData, true));
        
        // 创建视频
        $videoId = $this->videoModel->createVideo($videoData);
        
        if ($videoId) {
            error_log("[DEBUG] 视频上传成功，视频ID: {$videoId}");
            return ['success' => true, 'video_id' => $videoId];
        } else {
            // 如果数据库保存失败，删除上传的文件
            if (file_exists($videoDir . $videoFileName)) {
                if (unlink($videoDir . $videoFileName)) {
                    error_log("[DEBUG] 删除失败的视频文件: {$videoDir}{$videoFileName}");
                }
            }
            
            error_log("[ERROR] 视频保存到数据库失败");
            return ['success' => false, 'message' => '视频保存失败，请稍后重试'];
        }
    }
    
    /**
     * 获取上传错误信息
     */
    private function getUploadErrorMsg($errorCode)
    {
        $errorMessages = [
            UPLOAD_ERR_OK => '没有错误发生，文件上传成功。',
            UPLOAD_ERR_INI_SIZE => '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值。',
            UPLOAD_ERR_FORM_SIZE => '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值。',
            UPLOAD_ERR_PARTIAL => '文件只有部分被上传。',
            UPLOAD_ERR_NO_FILE => '没有文件被上传。',
            UPLOAD_ERR_NO_TMP_DIR => '找不到临时文件夹。',
            UPLOAD_ERR_CANT_WRITE => '文件写入失败。',
            UPLOAD_ERR_EXTENSION => '文件上传被PHP扩展停止。'
        ];
        
        return isset($errorMessages[$errorCode]) ? $errorMessages[$errorCode] : '未知错误。';
    }
    
    /**
     * 根据ID获取视频
     */
    public function getVideoById($id)
    {
        return $this->videoModel->getById($id);
    }
    
    /**
     * 增加视频观看次数
     */
    public function incrementVideoViews($id)
    {
        return $this->videoModel->incrementViews($id);
    }
    
    /**
     * 获取视频列表
     */
    public function getVideos($limit = 20, $offset = 0)
    {
        return $this->videoModel->getPaginated($limit, $offset);
    }
}