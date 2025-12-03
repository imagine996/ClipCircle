<?php
// 安装程序主入口

// 启动会话
session_start();

// 安装步骤
$step = isset($_GET['step']) ? $_GET['step'] : 1;

// 数据库配置文件路径
$databaseConfigPath = '../../config/database.php';

// 安装完成标志文件
$installLockFile = '../../install.lock';

// 如果已经安装，则重定向到首页
if (file_exists($installLockFile)) {
    header('Location: /');
    exit;
}

// 环境检测结果
$envResult = [];

// 数据库连接测试结果
$dbResult = false;

// 错误信息
$error = '';

// 成功信息
$success = '';

// 处理环境检测
if ($step == 1 && $_SERVER['REQUEST_METHOD'] == 'POST') {
    // 检查PHP版本
    $envResult['php_version'] = version_compare(PHP_VERSION, '7.4.0', '>=');
    
    // 检查MySQL扩展
    $envResult['mysql_extension'] = extension_loaded('mysqli');
    
    // 检查PDO扩展
    $envResult['pdo_extension'] = extension_loaded('pdo_mysql');
    
    // 检查session扩展
    $envResult['session_extension'] = extension_loaded('session');
    
    // 检查gd扩展
    $envResult['gd_extension'] = extension_loaded('gd');
    
    // 检查config目录权限
    $envResult['config_writable'] = is_writable('../../config');
    
    // 检查public/uploads目录权限
    $envResult['uploads_writable'] = is_writable('../uploads');
    
    // 检查public/themes目录权限
    $envResult['themes_writable'] = is_writable('../themes');
    
    // 如果所有检测项都通过，则进入下一步
    if (array_filter($envResult) == $envResult) {
        header('Location: index.php?step=2');
        exit;
    } else {
        $error = '环境检测未通过，请检查相关配置';
    }
} else if ($step == 1) {
    // 默认执行环境检测
    $envResult['php_version'] = version_compare(PHP_VERSION, '7.4.0', '>=');
    $envResult['mysql_extension'] = extension_loaded('mysqli');
    $envResult['pdo_extension'] = extension_loaded('pdo_mysql');
    $envResult['session_extension'] = extension_loaded('session');
    $envResult['gd_extension'] = extension_loaded('gd');
    $envResult['config_writable'] = is_writable('../../config');
    $envResult['uploads_writable'] = is_writable('../uploads');
    $envResult['themes_writable'] = is_writable('../themes');
}

// 处理数据库配置
if ($step == 2 && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $dbHost = $_POST['db_host'];
    $dbName = $_POST['db_name'];
    $dbUser = $_POST['db_user'];
    $dbPass = $_POST['db_pass'];
    $dbPrefix = $_POST['db_prefix'];
    
    // 保存数据库配置到会话
    $_SESSION['db_config'] = [
        'host' => $dbHost,
        'database' => $dbName,
        'username' => $dbUser,
        'password' => $dbPass,
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => $dbPrefix,
    ];
    
    // 测试数据库连接
    try {
        $dsn = "mysql:host=$dbHost;charset=utf8mb4";
        $pdo = new PDO($dsn, $dbUser, $dbPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // 检查数据库是否存在
        $stmt = $pdo->query("SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = '$dbName'");
        if (!$stmt->fetch()) {
            // 创建数据库
            $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbName CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        }
        
        // 选择数据库
        $pdo->exec("USE $dbName");
        
        $dbResult = true;
        header('Location: index.php?step=3');
        exit;
    } catch (PDOException $e) {
        $error = '数据库连接失败：' . $e->getMessage();
        $dbResult = false;
    }
}

// 处理管理员账户创建
if ($step == 3 && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $adminUsername = $_POST['admin_username'];
    $adminPassword = $_POST['admin_password'];
    $adminConfirmPassword = $_POST['admin_confirm_password'];
    
    // 验证密码
    if ($adminPassword != $adminConfirmPassword) {
        $error = '两次输入的密码不一致';
    } else if (strlen($adminPassword) < 6) {
        $error = '密码长度不能少于6个字符';
    } else {
        // 获取数据库配置
        $dbConfig = $_SESSION['db_config'];
        
        try {
            // 连接数据库
            $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['database']};charset=utf8mb4";
            $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // 创建数据表
            $prefix = $dbConfig['prefix'];
            
            // 创建用户表
            $pdo->exec("CREATE TABLE IF NOT EXISTS `{$prefix}users` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `username` varchar(50) NOT NULL,
                `email` varchar(100) NOT NULL,
                `password` varchar(255) NOT NULL,
                `avatar` varchar(255) DEFAULT NULL,
                `role` enum('user','admin') NOT NULL DEFAULT 'user',
                `status` tinyint(4) NOT NULL DEFAULT 1,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `username` (`username`),
                UNIQUE KEY `email` (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
            
            // 创建视频表
            $pdo->exec("CREATE TABLE IF NOT EXISTS `{$prefix}videos` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `user_id` int(11) NOT NULL,
                `title` varchar(255) NOT NULL,
                `description` text,
                `cover` varchar(255) DEFAULT NULL,
                `video_path` varchar(255) NOT NULL,
                `duration` int(11) DEFAULT NULL,
                `views` int(11) NOT NULL DEFAULT 0,
                `likes` int(11) NOT NULL DEFAULT 0,
                `dislikes` int(11) NOT NULL DEFAULT 0,
                `status` tinyint(4) NOT NULL DEFAULT 1,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `user_id` (`user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
            
            // 创建评论表
            $pdo->exec("CREATE TABLE IF NOT EXISTS `{$prefix}comments` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `video_id` int(11) NOT NULL,
                `user_id` int(11) NOT NULL,
                `content` text NOT NULL,
                `status` tinyint(4) NOT NULL DEFAULT 1,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `video_id` (`video_id`),
                KEY `user_id` (`user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
            
            // 创建管理员账户
            $hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);
            $pdo->exec("INSERT INTO `{$prefix}users` (`username`, `email`, `password`, `role`) VALUES ('$adminUsername', '$adminUsername@example.com', '$hashedPassword', 'admin')");
            
            // 更新数据库配置文件
            $configContent = "<?php

return [
    'host' => '{$dbConfig['host']}',
    'database' => '{$dbConfig['database']}',
    'username' => '{$dbConfig['username']}',
    'password' => '{$dbConfig['password']}',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '{$dbConfig['prefix']}',
];";
            
            file_put_contents($databaseConfigPath, $configContent);
            
            // 创建安装锁定文件
            touch($installLockFile);
            
            header('Location: index.php?step=4');
            exit;
        } catch (PDOException $e) {
            $error = '安装失败：' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>安装程序 · ClipCircle</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="install-container">
        <div class="install-header">
            <h1>ClipCircle</h1>
            <p>欢迎使用 ClipCircle 网站安装向导</p>
        </div>
        
        <div class="install-progress">
            <div class="progress-item <?php echo $step >= 1 ? 'active' : ''; ?>">环境检测</div>
            <div class="progress-item <?php echo $step >= 2 ? 'active' : ''; ?>">数据库配置</div>
            <div class="progress-item <?php echo $step >= 3 ? 'active' : ''; ?>">管理员设置</div>
            <div class="progress-item <?php echo $step >= 4 ? 'active' : ''; ?>">安装完成</div>
        </div>
        
        <div class="install-content">
            <?php if ($error) { ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php } ?>
            
            <?php if ($success) { ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php } ?>
            
            <?php if ($step == 1) { ?>
                <!-- 环境检测步骤 -->
                <h2>环境检测</h2>
                <p>请确保您的服务器满足以下环境要求：</p>
                
                <form method="post">
                    <table class="env-table">
                        <tr>
                            <td>PHP版本</td>
                            <td><?php echo PHP_VERSION; ?></td>
                            <td class="status <?php echo $envResult['php_version'] ? 'success' : 'error'; ?>">
                                <?php echo $envResult['php_version'] ? '满足要求' : '需要PHP 7.4.0+'; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>MySQL扩展</td>
                            <td>mysqli</td>
                            <td class="status <?php echo $envResult['mysql_extension'] ? 'success' : 'error'; ?>">
                                <?php echo $envResult['mysql_extension'] ? '已安装' : '未安装'; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>PDO扩展</td>
                            <td>pdo_mysql</td>
                            <td class="status <?php echo $envResult['pdo_extension'] ? 'success' : 'error'; ?>">
                                <?php echo $envResult['pdo_extension'] ? '已安装' : '未安装'; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Session扩展</td>
                            <td>session</td>
                            <td class="status <?php echo $envResult['session_extension'] ? 'success' : 'error'; ?>">
                                <?php echo $envResult['session_extension'] ? '已安装' : '未安装'; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>GD扩展</td>
                            <td>gd</td>
                            <td class="status <?php echo $envResult['gd_extension'] ? 'success' : 'error'; ?>">
                                <?php echo $envResult['gd_extension'] ? '已安装' : '未安装'; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Config目录权限</td>
                            <td>../../config</td>
                            <td class="status <?php echo $envResult['config_writable'] ? 'success' : 'error'; ?>">
                                <?php echo $envResult['config_writable'] ? '可写' : '不可写'; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Uploads目录权限</td>
                            <td>../uploads</td>
                            <td class="status <?php echo $envResult['uploads_writable'] ? 'success' : 'error'; ?>">
                                <?php echo $envResult['uploads_writable'] ? '可写' : '不可写'; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Themes目录权限</td>
                            <td>../themes</td>
                            <td class="status <?php echo $envResult['themes_writable'] ? 'success' : 'error'; ?>">
                                <?php echo $envResult['themes_writable'] ? '可写' : '不可写'; ?>
                            </td>
                        </tr>
                    </table>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">下一步</button>
                    </div>
                </form>
            <?php } ?>
            
            <?php if ($step == 2) { ?>
                <!-- 数据库配置步骤 -->
                <h2>数据库配置</h2>
                <p>请输入您的数据库连接信息：</p>
                
                <form method="post">
                    <div class="form-group">
                        <label for="db_host">数据库主机：</label>
                        <input type="text" id="db_host" name="db_host" value="localhost" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="db_name">数据库名：</label>
                        <input type="text" id="db_name" name="db_name" value="video_site" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="db_user">数据库用户名：</label>
                        <input type="text" id="db_user" name="db_user" value="root" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="db_pass">数据库密码：</label>
                        <input type="password" id="db_pass" name="db_pass">
                    </div>
                    
                    <div class="form-group">
                        <label for="db_prefix">数据表前缀：</label>
                        <input type="text" id="db_prefix" name="db_prefix" value="vs_" required>
                    </div>
                    
                    <div class="form-actions">
                        <a href="index.php?step=1" class="btn btn-secondary">上一步</a>
                        <button type="submit" class="btn btn-primary">测试连接并下一步</button>
                    </div>
                </form>
            <?php } ?>
            
            <?php if ($step == 3) { ?>
                <!-- 管理员设置步骤 -->
                <h2>管理员账户设置</h2>
                <p>请设置网站管理员账户信息：</p>
                
                <form method="post">
                    <div class="form-group">
                        <label for="admin_username">管理员用户名：</label>
                        <input type="text" id="admin_username" name="admin_username" value="admin" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="admin_password">管理员密码：</label>
                        <input type="password" id="admin_password" name="admin_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="admin_confirm_password">确认密码：</label>
                        <input type="password" id="admin_confirm_password" name="admin_confirm_password" required>
                    </div>
                    
                    <div class="form-actions">
                        <a href="index.php?step=2" class="btn btn-secondary">上一步</a>
                        <button type="submit" class="btn btn-primary">完成安装</button>
                    </div>
                </form>
            <?php } ?>
            
            <?php if ($step == 4) { ?>
                <!-- 安装完成步骤 -->
                <h2>安装完成</h2>
                <div class="success-message">
                    <p>恭喜！视频网站已成功安装完成。</p>
                    <p>系统将在 <span id="countdown">20</span> 秒后自动跳转到首页...</p>
                </div>
                
                <div class="warning-message">
                    <p><strong>安全提示：</strong>为了保障网站安全，请立即删除 <code>public/install</code> 目录！</p>
                </div>
                
                
                <script>
                    // 倒计时跳转
                    var countdown = 20;
                    var timer = setInterval(function() {
                        countdown--;
                        document.getElementById('countdown').innerHTML = countdown;
                        if (countdown <= 0) {
                            clearInterval(timer);
                            window.location.href = '/';
                        }
                    }, 1000);
                </script>
            <?php } ?>
        </div>
    </div>
</body>
</html>