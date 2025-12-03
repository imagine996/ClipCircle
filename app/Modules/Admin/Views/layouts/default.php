<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? '管理后台' ?></title>
    <script src="/assets/vendor/tailwind.js"></script>
    <script defer src="/assets/vendor/alpine.js"></script>
    <script src="/assets/vendor/fa.js"></script>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <style>
        .glass-nav { background: #1e293b; color: white; }
        [x-cloak] { display: none !important; }
        /* 针对子页面的淡入动画 */
        .fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-gray-50 text-slate-800" x-data="{ sidebarOpen: true }">
    <!-- 左侧菜单栏 -->
    <aside class="fixed top-0 left-0 z-40 h-screen transition-all duration-300 glass-nav flex flex-col"
           :class="sidebarOpen ? 'w-64' : 'w-20'">
        
        <div class="h-16 flex items-center justify-center border-b border-gray-700">
            <span class="font-bold text-xl" x-show="sidebarOpen">ClipAdmin</span>
            <span class="font-bold text-xl" x-show="!sidebarOpen">C</span>
        </div>

        <div class="flex-1 overflow-y-auto py-4 space-y-1">
            <?php 
            // 【修复关键点 1】确保 page 变量存在，如果未定义则为空字符串
            $page = $page ?? '';

            $menu = function($id, $icon, $name) use ($page) {
                // 这里的判断现在是安全的
                $active = ($page === $id) ? 'bg-pink-600 text-white shadow-lg' : 'text-gray-400 hover:bg-gray-800 hover:text-white';
                $show = "x-show='sidebarOpen'";
                
                // 生成URL
                $url = "/admin/{$id}";
                
                echo "<a href='{$url}' class='flex items-center px-4 py-3 mx-2 rounded transition {$active}'>
                        <i class='{$icon} w-6 text-center'></i>
                        <span class='ml-3 text-sm font-bold whitespace-nowrap' {$show}>{$name}</span>
                      </a>";
            };
            ?>

            <div class="px-4 py-2 text-xs text-gray-500 font-bold uppercase" x-show="sidebarOpen">概览</div>
            <?php $menu('dashboard', 'fas fa-chart-pie', '仪表板'); ?>

            <div class="px-4 py-2 mt-4 text-xs text-gray-500 font-bold uppercase" x-show="sidebarOpen">内容</div>
            <?php $menu('videos', 'fas fa-video', '视频管理'); ?>
            <?php $menu('live', 'fas fa-broadcast-tower', '直播管理'); ?>
            <?php $menu('users', 'fas fa-users', '用户管理'); ?>
            <?php $menu('comments', 'fas fa-comments', '评论管理'); ?>

            <div class="px-4 py-2 mt-4 text-xs text-gray-500 font-bold uppercase" x-show="sidebarOpen">设置</div>
            <?php $menu('theme', 'fas fa-palette', '主题管理'); ?>
            <?php $menu('settings', 'fas fa-cog', '系统设置'); ?>
        </div>
    </aside>

    <!-- 右侧主体 -->
    <div class="transition-all duration-300" :class="sidebarOpen ? 'ml-64' : 'ml-20'">
        <header class="h-16 bg-white border-b flex items-center justify-between px-6 sticky top-0 z-30 shadow-sm">
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500"><i class="fas fa-bars text-xl"></i></button>
            <div class="flex items-center gap-4">
                <a href="/" target="_blank" class="text-sm text-gray-500 hover:text-pink-600 font-bold"><i class="fas fa-external-link-alt"></i> 前台</a>
                <div class="flex items-center gap-2 pl-4 border-l">
                    <!-- 【修复关键点 2】防止 user 变量未定义导致报错 -->
                    <span class="font-bold text-sm text-gray-700"><?= $user['username'] ?? '管理员' ?></span>
                    <img src="<?= $user['avatar'] ?? '/assets/images/default-avatar.png' ?>" class="w-8 h-8 rounded-full border">
                </div>
            </div>
        </header>

        <!-- 动态加载子页面 -->
        <main class="p-6 fade-in">
            <?php include $__admin_view_path; ?>
        </main>
    </div>

    <script src="/assets/js/admin.js"></script>
</body>
</html>