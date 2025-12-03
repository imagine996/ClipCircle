<?php
namespace App\Core;

class Router {
    private $routes = [];
    
    public function __construct() {
        $this->registerRoutes();
    }
    
    private function registerRoutes() {
        // 前端路由
        $this->addRoute('GET', '/', 'App\Modules\Frontend\Controllers\HomeController', 'index');
        $this->addRoute('GET', '/login', 'App\Modules\Frontend\Controllers\AuthController', 'login');
        $this->addRoute('POST', '/login', 'App\Modules\Frontend\Controllers\AuthController', 'doLogin');
        $this->addRoute('GET', '/register', 'App\Modules\Frontend\Controllers\AuthController', 'register');
        $this->addRoute('POST', '/register', 'App\Modules\Frontend\Controllers\AuthController', 'doRegister');
        $this->addRoute('GET', '/logout', 'App\Modules\Frontend\Controllers\AuthController', 'logout');
        $this->addRoute('GET', '/video/{id}', 'App\Modules\Frontend\Controllers\VideoController', 'play');
        $this->addRoute('GET', '/upload', 'App\Modules\Frontend\Controllers\VideoController', 'upload');
        $this->addRoute('POST', '/upload', 'App\Modules\Frontend\Controllers\VideoController', 'doUpload');
        $this->addRoute('GET', '/live/{id}', 'App\Modules\Frontend\Controllers\LiveController', 'watch');
        $this->addRoute('GET', '/user/{id}', 'App\Modules\Frontend\Controllers\UserController', 'profile');
        $this->addRoute('GET', '/search', 'App\Modules\Frontend\Controllers\HomeController', 'search');
        
        // 后台路由
        $this->addRoute('GET', '/admin', 'App\Modules\Admin\Controllers\AdminController', 'dashboard');
        $this->addRoute('GET', '/admin/dashboard', 'App\Modules\Admin\Controllers\AdminController', 'dashboard');
        $this->addRoute('GET', '/admin/videos', 'App\Modules\Admin\Controllers\AdminController', 'videos');
        $this->addRoute('GET', '/admin/users', 'App\Modules\Admin\Controllers\AdminController', 'users');
        $this->addRoute('GET', '/admin/live', 'App\Modules\Admin\Controllers\AdminController', 'live');
        $this->addRoute('GET', '/admin/comments', 'App\Modules\Admin\Controllers\AdminController', 'comments');
        $this->addRoute('GET', '/admin/settings', 'App\Modules\Admin\Controllers\AdminController', 'settings');
        // 主题管理路由
        $this->addRoute('GET', '/admin/theme', 'App\Modules\Admin\Controllers\ThemeController', 'index');
        $this->addRoute('POST', '/admin/theme/change', 'App\Modules\Admin\Controllers\ThemeController', 'change');
        
        // 视频上传路由
        $this->addRoute('GET', '/upload', 'App\Modules\Frontend\Controllers\VideoController', 'upload');
        $this->addRoute('POST', '/do_upload', 'App\Modules\Frontend\Controllers\VideoController', 'doUpload');
    }
    
    private function addRoute($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }
    
    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];
        
        // 移除查询参数
        $requestUri = explode('?', $requestUri)[0];
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod) {
                $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route['path']);
                $pattern = '#^' . $pattern . '$#';
                
                if (preg_match($pattern, $requestUri, $matches)) {
                    array_shift($matches);
                    
                    $controller = new $route['controller']();
                    $action = $route['action'];
                    
                    call_user_func_array([$controller, $action], $matches);
                    return;
                }
            }
        }
        
        // 404处理
        $this->notFound();
    }
    
    private function notFound() {
        http_response_code(404);
        echo "Page not found";
    }
}
