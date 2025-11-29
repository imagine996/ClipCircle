<?php
namespace App\Models;
use App\Config\Database;

class Video {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getRecommended(): array {
        // 获取已发布的视频
        return $this->db->query("SELECT * FROM videos WHERE status='published' ORDER BY views DESC LIMIT 12")->fetchAll();
    }

    public function getByUser(int $userId): array {
        $stmt = $this->db->prepare("SELECT * FROM videos WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): void {
        $sql = "INSERT INTO videos (user_id, title, category, cover_path, file_path, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['user_id'], $data['title'], $data['category'], 
            $data['cover_path'], $data['file_path'], $data['status']
        ]);
    }
    
    public function find(int $id) {
        $stmt = $this->db->prepare("SELECT v.*, u.username FROM videos v JOIN users u ON v.user_id = u.id WHERE v.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function incrementView(int $id): void {
        $this->db->prepare("UPDATE videos SET views = views + 1 WHERE id = ?")->execute([$id]);
    }

    public function getPending(): array {
        return $this->db->query("SELECT * FROM videos WHERE status='pending'")->fetchAll();
    }

    public function updateStatus(int $id, string $status): void {
        $stmt = $this->db->prepare("UPDATE videos SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
    }

    public function search(string $keyword): array {
        $stmt = $this->db->prepare("SELECT * FROM videos WHERE title LIKE ? AND status='published'");
        $stmt->execute(["%$keyword%"]);
        return $stmt->fetchAll();
    }
	// ... 在 Video 类中 ...

    // [修复] 之前的 search 方法只查 published，这会导致管理员看不到其他状态的视频
    // 保持 search 用于前台搜索，新增一个专门给后台用的方法
    
    public function getAllForAdmin(): array {
        // 管理员看所有状态的视频
        return $this->db->query("SELECT * FROM videos ORDER BY created_at DESC")->fetchAll();
    }
}