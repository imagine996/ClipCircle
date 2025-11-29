<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Config\Database;

class AuthController extends Controller {
    
    // 显示登录/注册页
    public function login() {
        $this->view('auth/login');
    }

    // 处理注册
    public function doRegister() {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        
        if (!$username || !$password) die("用户名或密码不能为空");

        $db = Database::getConnection();
        
        // 检查重名
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) die("用户名已存在");

        // 插入用户 (默认 role = user)
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
        $stmt->execute([$username, $hash]);

        // 自动登录
        $uid = $db->lastInsertId();
        $_SESSION['user'] = [
            'id' => $uid,
            'username' => $username,
            'role' => 'user'
        ];

        $this->redirect('/?c=User&a=dashboard');
    }

    // 处理登录
    public function doLogin() {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            // 如果是管理员，跳转到后台，否则去个人中心
            if ($user['role'] === 'admin') {
                $this->redirect('/?c=Admin&a=index');
            } else {
                $this->redirect('/?c=User&a=dashboard');
            }
        } else {
            die("账号或密码错误 <a href='/?c=Auth&a=login'>返回</a>");
        }
    }

    // 退出
    public function logout() {
        session_destroy();
        $this->redirect('/');
    }
}