<?php
// public/check.php
header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>MyVideo 项目环境自检</h1>";

// 1. 检查 PHP 版本
echo "<h3>1. PHP 版本</h3>";
echo "当前版本：" . phpversion();
if (version_compare(phpversion(), '8.0.0', '<')) {
    echo " <span style='color:red'>❌ 失败：必须 PHP 8.0 以上</span>";
} else {
    echo " <span style='color:green'>✅ 通过</span>";
}

// 2. 检查 Composer 依赖
echo "<h3>2. 依赖检查</h3>";
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    echo "vendor/autoload.php 存在 <span style='color:green'>✅ (Composer 已安装)</span>";
} else {
    echo "vendor/autoload.php 不存在 <span style='color:orange'>⚠️ (Composer 未安装)</span><br>";
    echo "提示：如果你没安装 Composer，请去修改 public/index.php，注释掉 require vendor/autoload.php 那几行。";
}

// 3. 检查目录权限
echo "<h3>3. 目录权限</h3>";
$dirs = [
    '../app/Config' => '配置文件目录',
    'uploads' => '上传目录'
];

foreach ($dirs as $path => $name) {
    $fullPath = __DIR__ . '/' . $path;
    if (!is_dir($fullPath)) {
        // 尝试创建
        @mkdir($fullPath, 0777, true);
    }
    
    if (is_writable($fullPath)) {
        echo "$name ($path): <span style='color:green'>✅ 可写</span><br>";
    } else {
        echo "$name ($path): <span style='color:red'>❌ 不可写 (请设置权限 chmod 777)</span><br>";
    }
}

// 4. 检查扩展
echo "<h3>4. 关键扩展</h3>";
$exts = ['pdo', 'pdo_mysql', 'mbstring'];
foreach ($exts as $ext) {
    if (extension_loaded($ext)) {
        echo "$ext: <span style='color:green'>✅ 已加载</span><br>";
    } else {
        echo "$ext: <span style='color:red'>❌ 未加载 (请修改 php.ini)</span><br>";
    }
}

echo "<hr>检测结束。如果以上都是绿色，请访问 <a href='index.php'>index.php</a>";
?>