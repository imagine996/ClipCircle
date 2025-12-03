<?php

spl_autoload_register(function ($class) {
    // 定义项目前缀和基础目录
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/app/';

    // 检查类名是否使用了该前缀
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // 获取相对类名
    $relative_class = substr($class, $len);

    // 将命名空间前缀替换为基础目录，将命名空间分隔符替换为目录分隔符，并追加 .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // 如果文件存在，则引入它
    if (file_exists($file)) {
        require $file;
    }
});