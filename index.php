<?php

// 引入自动加载器
require_once __DIR__ . '/autoloader.php';

// 引入应用核心类
use App\Core\App;

// 创建应用实例
$app = new App();

// 运行应用
$app->run();
