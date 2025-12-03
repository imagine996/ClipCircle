<?php

namespace App\Modules\Frontend\Controllers;

use App\Core\Controller;
use App\Core\Auth;

class AuthController extends Controller
{
    private $auth;
    
    public function __construct()
    {
        parent::__construct();
        $this->auth = new Auth();
    }
    
    /**
     * 显示登录页面
     */
    public function login()
    {
        // 检查用户是否已登录，如果已登录则重定向到首页
        if ($this->authCheck()) {
            $this->redirect('/');
        }
        
        $data = [
            'title' => '登录 - MyVideo',
            'is_login' => false
        ];
        
        $this->view('auth/login', $data, 'main');
    }
    
    /**
     * 处理登录请求
     */
    public function doLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            // 使用Auth类进行登录验证
            $user = $this->auth->login($username, $password);
            
            if ($user) {
                // 登录成功，重定向到首页
                $this->redirect('/');
            } else {
                // 登录失败，显示错误信息
                $data = [
                    'title' => '登录 - MyVideo',
                    'is_login' => false,
                    'error' => '用户名或密码错误'
                ];
                
                $this->view('auth/login', $data, 'main');
            }
        }
    }
    
    /**
     * 显示注册页面
     */
    public function register()
    {
        // 检查用户是否已登录，如果已登录则重定向到首页
        if ($this->authCheck()) {
            $this->redirect('/');
        }
        
        $data = [
            'title' => '注册 - MyVideo',
            'is_login' => false
        ];
        
        $this->view('auth/register', $data, 'main');
    }
    
    /**
     * 处理注册请求
     */
    public function doRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => $_POST['password']
            ];
            
            // 使用Auth类进行注册
            $success = $this->auth->register($userData);
            
            if ($success) {
                // 注册成功，自动登录并跳转到首页
                $user = $this->auth->login($userData['username'], $userData['password']);
                $this->redirect('/');
            } else {
                // 注册失败，显示错误信息
                $data = [
                    'title' => '注册 - MyVideo',
                    'is_login' => false,
                    'error' => '注册失败，该邮箱可能已被使用'
                ];
                
                $this->view('auth/register', $data, 'main');
            }
        }
    }

    /**
     * 处理退出登录请求
     */
    public function logout()
    {
        // 使用Auth类进行退出登录
        $this->auth->logout();
        // 重定向到首页
        $this->redirect('/');
    }
}