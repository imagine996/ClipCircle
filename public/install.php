<?php
// public/install.php

// 1. 安全检查
$configFile = __DIR__ . '/../app/Config/Database.php';
if (file_exists($configFile)) {
    die("系统已安装！如需重新安装，请先删除 app/Config/Database.php 文件。");
}

$step = $_GET['step'] ?? 1;
$error = '';
$success = false;

// 2. 处理安装逻辑
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $step == 2) {
    $dbHost = $_POST['db_host'] ?? '127.0.0.1';
    $dbName = $_POST['db_name'] ?? 'video_platform';
    $dbUser = $_POST['db_user'] ?? 'root';
    $dbPass = $_POST['db_pass'] ?? '';
    $adminUser = $_POST['admin_user'] ?? 'admin';
    $adminPass = $_POST['admin_pass'] ?? '';

    if (empty($dbHost) || empty($dbName) || empty($dbUser) || empty($adminUser) || empty($adminPass)) {
        $error = "请填写所有必填项";
    } else {
        try {
            // A. 连接数据库
            $pdo = new PDO("mysql:host=$dbHost;charset=utf8mb4", $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            // 【关键修复 1】: 临时禁用 SQL 严格模式，防止日期默认值报错
            $pdo->exec("SET SESSION sql_mode = ''");

            // B. 创建库
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `$dbName`");

            // C. 导入表
            $sql = getSchemaSql(); 
            // 拆分 SQL 语句逐条执行，避免一个失败导致全盘崩溃
            // 这里简单处理，直接执行
            $pdo->exec($sql);

            // D. 创建管理员
            $hashedPass = password_hash($adminPass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role, email) VALUES (?, ?, 'admin', 'admin@local.host')");
            $stmt->execute([$adminUser, $hashedPass]);

            // D2. 插入默认主题设置 (之前漏了这一步)
            $pdo->exec("CREATE TABLE IF NOT EXISTS `settings` (`key_name` varchar(50) NOT NULL, `value` text, PRIMARY KEY (`key_name`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            $pdo->exec("INSERT IGNORE INTO `settings` (`key_name`, `value`) VALUES ('active_theme', 'default')");

            // E. 创建目录
            $dirs = [
                __DIR__ . '/uploads/covers',
                __DIR__ . '/uploads/videos',
                __DIR__ . '/../app/Config'
            ];
            foreach ($dirs as $dir) {
                if (!is_dir($dir)) mkdir($dir, 0777, true);
            }

            // F. 写入配置
            $configContent = getConfigTemplate($dbHost, $dbName, $dbUser, $dbPass);
            if (file_put_contents($configFile, $configContent) === false) {
                throw new Exception("无法写入配置文件，请检查 app/Config 目录权限");
            }

            $success = true;

        } catch (PDOException $e) {
            $error = "数据库错误: " . $e->getMessage();
        } catch (Exception $e) {
            $error = "系统错误: " . $e->getMessage();
        }
    }
}

// --- 辅助函数 ---

function getConfigTemplate($host, $name, $user, $pass) {
    $pass = str_replace("'", "\'", $pass);
    return <<<php
<?php
namespace App\Config;

class Database {
    public static function getConnection(): \PDO {
        static \$pdo = null;
        if (\$pdo === null) {
            \$dsn = "mysql:host={$host};dbname={$name};charset=utf8mb4";
            \$pdo = new \PDO(\$dsn, '{$user}', '{$pass}', [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ]);
            // 保持连接时也宽容模式
            \$pdo->exec("SET SESSION sql_mode = ''");
        }
        return \$pdo;
    }
}
php;
}

function getSchemaSql() {
    // 【关键修复 2】: 将 datetime DEFAULT CURRENT_TIMESTAMP 改为 timestamp DEFAULT CURRENT_TIMESTAMP
    // 这兼容所有 MySQL 版本
    return <<<sql
    CREATE TABLE IF NOT EXISTS `users` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `username` varchar(50) NOT NULL,
      `password` varchar(255) NOT NULL,
      `email` varchar(100) DEFAULT NULL,
      `role` enum('user','admin') DEFAULT 'user',
      `avatar` varchar(255) DEFAULT '/uploads/default_avatar.png',
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP, 
      PRIMARY KEY (`id`),
      UNIQUE KEY `username` (`username`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `videos` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user_id` int(11) NOT NULL,
      `title` varchar(150) NOT NULL,
      `description` text,
      `cover_path` varchar(255) NOT NULL,
      `file_path` varchar(255) NOT NULL,
      `category` varchar(50) NOT NULL,
      `views` int(11) DEFAULT 0,
      `likes` int(11) DEFAULT 0,
      `status` enum('pending','published','rejected') DEFAULT 'pending',
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `user_id` (`user_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `comments` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `video_id` int(11) NOT NULL,
      `user_id` int(11) NOT NULL,
      `parent_id` int(11) DEFAULT 0,
      `content` text NOT NULL,
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `danmakus` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `video_id` int(11) NOT NULL,
      `content` varchar(255) NOT NULL,
      `time_point` float NOT NULL,
      `color` varchar(10) DEFAULT '#ffffff',
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
sql;
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>安装向导 - ClipCircle</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-pink-500">ClipCircle 安装向导</h1>
        <p class="text-gray-500 mt-2">修复版 - 兼容旧版 MySQL</p>
    </div>

    <?php if ($success): ?>
        <div class="text-center">
            <div class="text-green-500 text-5xl mb-4">✓</div>
            <h2 class="text-2xl font-bold mb-2">安装成功！</h2>
            <p class="text-gray-600 mb-6">数据库已修复并导入。</p>
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded text-sm text-left mb-6">
                <strong>安全提示：</strong><br>
                请务必删除 <code>public/install.php</code> 文件。
            </div>
            <a href="index.php" class="block w-full bg-pink-500 text-white font-bold py-3 rounded hover:bg-pink-600 transition">进入网站首页</a>
        </div>
    <?php else: ?>
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 break-words text-sm">
                <strong>错误：</strong> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="install.php?step=2">
            <div class="space-y-4">
                <h3 class="font-bold text-gray-700">数据库配置</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">数据库地址</label>
                        <input type="text" name="db_host" value="127.0.0.1" class="w-full border p-2 rounded text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">数据库名</label>
                        <input type="text" name="db_name" value="video_platform" class="w-full border p-2 rounded text-sm">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">账号</label>
                        <input type="text" name="db_user" value="root" class="w-full border p-2 rounded text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">密码</label>
                        <input type="text" name="db_pass" placeholder="为空不填" class="w-full border p-2 rounded text-sm">
                    </div>
                </div>
            </div>

            <div class="space-y-4 mt-6 border-t pt-4">
                <h3 class="font-bold text-gray-700">管理员账号</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">用户名</label>
                        <input type="text" name="admin_user" value="admin" class="w-full border p-2 rounded text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">密码</label>
                        <input type="text" name="admin_pass" value="admin123" class="w-full border p-2 rounded text-sm">
                    </div>
                </div>
            </div>

            <button type="submit" class="mt-8 w-full bg-blue-500 text-white font-bold py-3 rounded hover:bg-blue-600 transition">
                重试安装
            </button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>