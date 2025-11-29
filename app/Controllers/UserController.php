<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\Video;

class UserController extends Controller {

    // ==========================================
    // 1. 用户中心 / 仪表盘
    // ==========================================
    public function dashboard() {
        $user = $this->authCheck();
        if (!$user) {
            $this->redirect('/?c=Auth&a=login');
            return;
        }

        $videoModel = new Video();
        // 获取该用户的视频列表
        $myVideos = $videoModel->getByUser($user['id']);
        
        // 简单的统计数据
        $totalViews = 0;
        $totalLikes = 0;
        if (!empty($myVideos)) {
            $totalViews = array_sum(array_column($myVideos, 'views'));
            $totalLikes = array_sum(array_column($myVideos, 'likes'));
        }

        $this->view('user/dashboard', [
            'user' => $user,
            'videos' => $myVideos,
            'stats' => ['views' => $totalViews, 'likes' => $totalLikes]
        ]);
    }

    // ==========================================
    // 2. 显示上传页面
    // ==========================================
    public function upload() {
        if (!$this->authCheck()) {
            $this->redirect('/?c=Auth&a=login');
            return;
        }
        $this->view('user/upload');
    }

    // ==========================================
    // 3. 处理视频上传 (核心逻辑)
    // ==========================================
    public function doUpload() {
        $user = $this->authCheck();
        if (!$user) {
            die('请先登录');
        }

        // 增加脚本执行时间，防止大文件上传超时
        set_time_limit(300); 

        $title = $_POST['title'] ?? '未命名视频';
        $category = $_POST['category'] ?? '其他';
        $description = $_POST['description'] ?? ''; // 接收简介
        
        // 检查视频文件是否上传
        if (!isset($_FILES['video']) || $_FILES['video']['error'] !== UPLOAD_ERR_OK) {
            die("上传失败，错误代码: " . ($_FILES['video']['error'] ?? '未知'));
        }

        // 准备目录
        $baseUploadPath = __DIR__ . '/../../public/uploads/';
        $videoDir = $baseUploadPath . 'videos/';
        $coverDir = $baseUploadPath . 'covers/';
        
        // 自动创建目录
        if (!is_dir($videoDir)) mkdir($videoDir, 0777, true);
        if (!is_dir($coverDir)) mkdir($coverDir, 0777, true);

        // --- 智能判断模式 ---
        // 检测是否安装了 php-ffmpeg 库
        $transcoderClass = 'App\\Core\\Transcoder';
        $hasFFmpeg = class_exists($transcoderClass) && file_exists(__DIR__ . '/../../vendor/autoload.php');

        $finalVideoPath = '';
        $finalCoverPath = '';

        if ($hasFFmpeg) {
            // === 模式 A: 高级转码模式 ===
            try {
                // 先保存临时文件
                $tmpPath = $videoDir . 'temp_' . time() . '_' . $_FILES['video']['name'];
                move_uploaded_file($_FILES['video']['tmp_name'], $tmpPath);

                // 调用转码器
                $transcoder = new $transcoderClass();
                $result = $transcoder->process($tmpPath);

                // 如果用户自己上传了封面，覆盖自动生成的封面
                if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
                    $customCoverName = time() . '_custom_' . $_FILES['cover']['name'];
                    move_uploaded_file($_FILES['cover']['tmp_name'], $coverDir . $customCoverName);
                    $result['cover_path'] = '/uploads/covers/' . $customCoverName;
                }

                $finalVideoPath = $result['video_path'];
                $finalCoverPath = $result['cover_path'];
                
                // 删除临时文件
                @unlink($tmpPath);

            } catch (\Exception $e) {
                // 如果转码失败，尝试回退到普通模式或报错
                // 这里为了演示，直接报错
                die("视频处理失败: " . $e->getMessage());
            }

        } else {
            // === 模式 B: 普通上传模式 (无 FFmpeg) ===
            // 直接移动文件，不转码
            $ext = pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION);
            $newVideoName = time() . '_' . uniqid() . '.' . $ext;
            
            if (move_uploaded_file($_FILES['video']['tmp_name'], $videoDir . $newVideoName)) {
                $finalVideoPath = '/uploads/videos/' . $newVideoName;
                
                // 处理封面 (必填或使用默认)
                if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
                    $newCoverName = time() . '_' . uniqid() . '_' . $_FILES['cover']['name'];
                    move_uploaded_file($_FILES['cover']['tmp_name'], $coverDir . $newCoverName);
                    $finalCoverPath = '/uploads/covers/' . $newCoverName;
                } else {
                    // 如果没传封面且没 FFmpeg，给个默认图
                    $finalCoverPath = '/uploads/default_avatar.png'; 
                }
            } else {
                die("无法移动上传文件，请检查 public/uploads 目录权限");
            }
        }

        // 写入数据库
        $videoModel = new Video();
        $videoModel->create([
            'user_id' => $user['id'],
            'title' => $title,
            'category' => $category,
            'cover_path' => $finalCoverPath,
            'file_path' => $finalVideoPath,
            'status' => 'published'
        ]);

        // 跳转回创作中心
        $this->redirect('/?c=User&a=dashboard');
    }

    // ==========================================
    // 4. 更新头像 (新增功能)
    // ==========================================
    public function updateAvatar() {
        $user = $this->authCheck();
        if (!$user) die('请先登录');

        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            echo "<script>alert('请选择有效的图片文件'); history.back();</script>";
            return;
        }

        // 验证文件类型
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($_FILES['avatar']['tmp_name']);

        if (!in_array($mime, $allowedTypes)) {
            echo "<script>alert('只支持 JPG, PNG, GIF, WEBP 格式'); history.back();</script>";
            return;
        }

        // 准备目录
        $uploadDir = __DIR__ . '/../../public/uploads/avatars/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        // 生成文件名
        $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $fileName = 'u' . $user['id'] . '_' . time() . '.' . $ext;
        $targetPath = $uploadDir . $fileName;
        $publicUrl = '/uploads/avatars/' . $fileName;

        // 移动并更新
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
            // 更新数据库
            $db = \App\Config\Database::getConnection();
            $stmt = $db->prepare("UPDATE users SET avatar = ? WHERE id = ?");
            $stmt->execute([$publicUrl, $user['id']]);

            // 实时更新 Session
            $_SESSION['user']['avatar'] = $publicUrl;

            $this->redirect('/?c=User&a=dashboard');
        } else {
            echo "<script>alert('上传失败，请检查目录权限'); history.back();</script>";
        }
    }

} // <--- 这个大括号是 Class 的结束，千万不能删