<?php
// public/index.php

// ---------------------------------------------------------
// 1. 自动检测安装状态
// ---------------------------------------------------------
$configFile = __DIR__ . '/../app/Config/Database.php';

// 如果配置文件不存在，且当前不是在访问安装程序，则强制跳转到安装程序
if (!file_exists($configFile)) {
    if (strpos($_SERVER['SCRIPT_NAME'], 'install.php') === false) {
        header('Location: install.php');
        exit;
    }
}
// ---------------------------------------------------------

// 开启 Session
session_start();
// public/index.php

// ... session_start(); 下面添加 ...

// 全局翻译辅助函数
function __($key) {
    return \App\Core\Lang::get($key);
}

// 开启错误提示 (调试阶段开启，上线后建议改为 0)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ---------------------------------------------------------
// 2. 自动加载 (Autoload)
// ---------------------------------------------------------

// A. 引入 Composer 自动加载 (用于 php-ffmpeg 等第三方库)
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
}

// B. 核心 MVC 自动加载 (加载我们自己写的 app/ 目录下的类)
spl_autoload_register(function ($class) {
    // 定义项目命名空间前缀
    $prefix = 'App\\';
    
    // 定义基目录 (指向 app 文件夹)
    $base_dir = __DIR__ . '/../app/';
    
    // 检查类名是否使用该前缀
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return; // 不属于本项目的类，移交给下一个自动加载器
    }
    
    // 获取相对类名
    $relative_class = substr($class, $len);
    
    // 将命名空间分隔符替换为目录分隔符，并拼接路径
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // 如果文件存在，则引入
    if (file_exists($file)) {
        require $file;
    }
});

// ---------------------------------------------------------
// 3. 启动应用
// ---------------------------------------------------------

use App\Core\App;

// 实例化核心应用类并运行
$app = new App();
$app->run();