<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\Video;
use App\Models\Comment;
use App\Models\Danmaku;

class VideoController extends Controller {
    public function play() {
        $id = $_GET['id'] ?? 0;
        $videoModel = new Video();
        $video = $videoModel->find($id);
        
        if (!$video) die("视频不存在");

        // 增加播放量
        $videoModel->incrementView($id);
        
        $this->view('video/play', [
            'video' => $video,
            'user' => $this->authCheck()
        ]);
    }

    // AJAX 获取弹幕
    public function getDanmaku() {
        $vid = $_GET['vid'];
        $danmakuModel = new Danmaku();
        $list = $danmakuModel->getByVideo($vid);
        $this->json($list); // 返回JSON供前端JS使用
    }

    // AJAX 发送弹幕
    public function sendDanmaku() {
        $data = json_decode(file_get_contents('php://input'), true);
        $danmakuModel = new Danmaku();
        $danmakuModel->add($data['vid'], $data['content'], $data['time'], $data['color']);
        $this->json(['status' => 'ok']);
    }
}