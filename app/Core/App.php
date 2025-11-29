<?php
namespace App\Core;

class App {
    
    public function run() {
        // 1. 获取 URL 参数 (默认 Home/index)
        $controllerName = $_GET['c'] ?? 'Home';
        $actionName = $_GET['a'] ?? 'index';

        // 2. 安全过滤：只允许字母和数字，防止恶意路径包含
        $controllerName = preg_replace('/[^a-zA-Z0-9]/', '', $controllerName);
        // 方法名允许下划线 (比如 do_upload)
        $actionName = preg_replace('/[^a-zA-Z0-9_]/', '', $actionName);

        // 3. 拼接完整的控制器类名 (例如 App\Controllers\HomeController)
        $controllerClass = "App\\Controllers\\" . ucfirst($controllerName) . "Controller";

        // 4. 检查控制器类是否存在
        if (!class_exists($controllerClass)) {
            $this->send404("控制器 [{$controllerName}] 不存在");
            return;
        }

        // 5. 实例化控制器
        $controller = new $controllerClass();

        // 6. 检查方法是否存在
        if (!method_exists($controller, $actionName)) {
            $this->send404("方法 [{$actionName}] 在控制器中未找到");
            return;
        }

        // 7. 执行方法
        // call_user_func 可以处理数组形式的调用 [$object, 'methodName']
        call_user_func([$controller, $actionName]);
    }

    // 简单的 404 处理
    private function send404($msg) {
        header("HTTP/1.0 404 Not Found");
        // 为了美观，你可以 include 一个 404.php 视图，这里先简单输出
        echo "<div style='text-align:center; margin-top:50px;'>";
        echo "<h1>404 Not Found</h1>";
        echo "<p style='color:gray'>{$msg}</p>";
        echo "<a href='/'>返回首页</a>";
        echo "</div>";
        exit;
    }
}