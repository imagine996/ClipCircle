<?php

namespace App\Modules\Frontend\Controllers;

use App\Core\Controller;
use App\Modules\Video\Services\VideoService;

class VideoController extends Controller
{
    private $videoService;
    
    public function __construct()
    {
        parent::__construct();
        
        // 初始化视频服务
        $this->videoService = new VideoService();
    }
    
    /**
     * 显示视频上传页面
     */
    public function upload()
    {
        $data = [
            'title' => '上传视频 - MyVideo',
            'user' => $this->authCheck(),
            'is_login' => true
        ];
        
        $this->view('video/upload', $data, 'main');
    }
    
    /**
     * 处理视频上传请求
     */
    public function doUpload()
    {
        // 确保这是一个POST请求
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => '请求方法错误'
            ]);
            exit;
        }
        
        // 确保没有输出其他内容
        ob_clean();
        
        // 获取用户信息
        $user = $this->authCheck();
        if (!$user) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => '用户未登录'
            ]);
            exit;
        }
        
        // 处理上传的视频文件
        // 分类ID到分类名称的映射
        $categories = [
            1 => '生活',
            2 => '娱乐',
            3 => '科技',
            4 => '教育',
            5 => '游戏'
        ];
        
        // 获取分类ID并转换为分类名称
        $categoryId = $_POST['category_id'] ?? 1;
        $categoryName = isset($categories[$categoryId]) ? $categories[$categoryId] : '生活';
        
        $videoData = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'category' => $categoryName,
            'user_id' => $user['id']
        ];
        
        // 调用视频服务上传视频
        $result = $this->videoService->uploadVideo($videoData, $_FILES['video'] ?? null, $_FILES['cover'] ?? null);
        
        // 设置响应头为JSON格式
        header('Content-Type: application/json');
        
        if ($result['success']) {
            // 上传成功，返回JSON响应
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => '视频上传成功',
                'video_id' => $result['video_id']
            ]);
        } else {
            // 上传失败，返回JSON错误信息
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $result['error']
            ]);
        }
        exit;
    }
    
    /**
     * 播放视频
     */
    public function play($id)
    {
        // 检查视频是否存在
        $video = $this->videoService->getVideoById($id);
        if (!$video) {
            http_response_code(404);
            echo "视频不存在";
            exit;
        }
        
        // 增加视频观看次数
        $this->videoService->incrementVideoViews($id);
        
        $data = [
            'title' => $video['title'] . ' - MyVideo',
            'user' => $this->authCheck(),
            'is_login' => !empty($this->authCheck()),
            'video' => $video
        ];
        
        $this->view('video/play', $data, 'main');
    }
}
