<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\VideoGrabber;
use App\Core\Transcoder;
use App\Models\Video;
use App\Config\Database;

class ImportController extends Controller {

    // 显示导入页面
    public function index() {
        // 权限检查
        $user = $this->authCheck();
        if (!$user || $user['role'] !== 'admin') die("Access Denied");

        $this->view('admin/import', ['results' => []]);
    }

    // 处理搜索或自动导入
    public function process() {
        $user = $this->authCheck();
        if (!$user || $user['role'] !== 'admin') die("Access Denied");

        $keyword = $_POST['keyword'] ?? '';
        $autoImport = isset($_POST['auto_import']); // 是否自动导入
        $category = $_POST['category'] ?? '其他';
        $targetUsername = $_POST['target_user'] ?? $user['username'];

        if (empty($keyword)) $this->redirect('/?c=Import&a=index');

        $grabber = new VideoGrabber();
        
        // 1. 如果选择了自动导入，直接搜索并下载前 3 个
        if ($autoImport) {
            $results = $grabber->search($keyword, 3);
            foreach ($results as $item) {
                $this->doImportSingle($item['url'], $item['title'], $category, $targetUsername);
            }
            // 导入完成，跳转回列表
            echo "<script>alert('自动导入任务完成！'); location.href='/?c=Admin&a=index';</script>";
            exit;
        } 
        
        // 2. 如果是手动，显示搜索结果供选择
        else {
            $results = $grabber->search($keyword, 10);
            $this->view('admin/import', [
                'results' => $results,
                'keyword' => $keyword,
                'category' => $category,
                'target_user' => $targetUsername
            ]);
        }
    }

    // 执行单个视频导入
    public function importSelected() {
        $url = $_POST['url'];
        $title = $_POST['title'];
        $category = $_POST['category'];
        $username = $_POST['target_user'];

        $success = $this->doImportSingle($url, $title, $category, $username);
        
        if ($success) {
            $this->json(['status' => 'success', 'msg' => '导入成功']);
        } else {
            $this->json(['status' => 'error', 'msg' => '导入失败']);
        }
    }

    // 内部私有方法：处理下载、转码、入库
    private function doImportSingle($url, $title, $category, $username) {
        $db = Database::getConnection();
        
        // 1. 获取目标用户ID
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $targetUser = $stmt->fetch();
        if (!$targetUser) return false; // 用户不存在

        // 2. 下载原始文件
        $grabber = new VideoGrabber();
        $tempDir = __DIR__ . '/../../public/uploads/temp/';
        if (!is_dir($tempDir)) mkdir($tempDir, 0777, true);
        
        $rawFile = $grabber->download($url, $tempDir);
        if (!$rawFile) return false;

        // 3. 转码 (复用之前的 Transcoder 类)
        try {
            $transcoder = new Transcoder();
            $result = $transcoder->process($rawFile);
            
            // 4. 入库
            $videoModel = new Video();
            $videoModel->create([
                'user_id' => $targetUser['id'],
                'title' => $title,
                'category' => $category,
                'cover_path' => $result['cover_path'],
                'file_path' => $result['video_path'],
                'status' => 'published'
            ]);

            // 清理临时文件
            @unlink($rawFile);
            return true;

        } catch (\Exception $e) {
            @unlink($rawFile);
            return false;
        }
    }
}