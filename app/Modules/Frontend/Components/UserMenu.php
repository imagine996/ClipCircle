<?php

/**
 * 用户菜单组件
 * 用于显示用户信息和操作选项
 */
function userMenu($user = null) {
    
    if ($user) {
        $username = $user['username'];
        $avatar = $user['avatar'] ? $user['avatar'] : '/assets/images/default-avatar.png';
        
        echo "<div class='relative' x-data='{ open: false }' @click.away='open = false'>
                <div class='flex items-center gap-2 cursor-pointer' @click='open = !open'>
                    <img src='{$avatar}' class='w-8 h-8 rounded-full border border-gray-300 object-cover'>
                    <span class='font-bold text-sm'>{$username}</span>
                    <i class='fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200' :class="{ 'rotate-180': open }"></i>
                </div>
                <!-- 下拉菜单 -->
                <div class='absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 transition-all duration-200 transform origin-top-right' :class="{ 'opacity-100 visible scale-100': open, 'opacity-0 invisible scale-95': !open }">
                    <a href='/profile' class='flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-pink-100 hover:text-pink-600 transition'>
                        <i class='fas fa-user-circle w-5 mr-2'></i>
                        <span>个人主页</span>
                    </a>
                    <a href='/admin' class='flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-pink-100 hover:text-pink-600 transition'>
                        <i class='fas fa-cog w-5 mr-2'></i>
                        <span>后台管理</span>
                    </a>
                    <div class='border-t border-gray-100 my-1'></div>
                    <a href='/logout' class='flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition'>
                        <i class='fas fa-sign-out-alt w-5 mr-2'></i>
                        <span>退出</span>
                    </a>
                </div>
              </div>";
    } else {
        echo "<div class='flex items-center gap-3'>
                <a href='/login' class='text-sm font-bold text-gray-600 hover:text-pink-500 transition'>登录</a>
                <a href='/register' class='text-sm font-bold bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 transition'>注册</a>
              </div>";
    }
}
