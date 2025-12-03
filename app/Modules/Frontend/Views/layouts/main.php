<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? '视频分享平台' ?></title>
    <script src="/assets/vendor/tailwind.js"></script>
    <script defer src="/assets/vendor/alpine.js"></script>
    <link rel="stylesheet" href="/assets/css/app.css">
    <!-- 加载主题CSS -->
    <link rel="stylesheet" href="/themes/default/css/theme.css">
</head>
<body class="bg-gray-50">
    <!-- 顶部导航栏 -->
    <header class="h-[60px] w-full border-b border-gray-200 fixed top-0 left-0 bg-white z-50 flex items-center justify-between px-4 lg:px-6">
        <!-- Logo -->
        <a href="/" class="flex items-center gap-2">
            <!-- 这里用 Icon 模拟 Logo -->
            <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center text-white font-bold">
                <i class="fab fa-tiktok"></i>
            </div>
            <span class="text-xl font-bold tracking-tighter">ClipCircle</span>
        </a>

        <!-- 搜索框 (中间) -->
        <div class="hidden md:block relative group w-[300px] lg:w-[500px]">
            <form action="/search" method="GET" class="relative z-10">
                <input type="text" 
                       id="search-input"
                       class="w-full bg-gray-100 rounded-full py-3 pl-5 pr-12 text-sm focus:outline-none focus:ring-1 focus:ring-gray-300 transition-all caret-[#FE2C55]" 
                       placeholder="搜索你感兴趣的内容..." autocomplete="off">
                <button type="submit" class="absolute right-0 top-0 h-full w-12 flex items-center justify-center border-l border-gray-300/50 text-gray-400 hover:bg-gray-200 rounded-r-full cursor-pointer">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            <!-- 猜你搜索 (弹出层) -->
            <div id="search-popup" class="hidden absolute top-full left-0 w-full bg-white shadow-lg rounded-lg mt-2 p-4 border border-gray-100 z-20">
                <h4 class="text-gray-500 text-xs font-semibold mb-2">猜你想搜</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="flex items-center gap-2 hover:bg-gray-50 p-1 rounded text-sm text-gray-700"><i class="fas fa-fire text-red-500"></i> 热门编程教程</a></li>
                    <li><a href="#" class="flex items-center gap-2 hover:bg-gray-50 p-1 rounded text-sm text-gray-700"><i class="fas fa-star text-yellow-500"></i> 2024最新电影</a></li>
                    <li><a href="#" class="flex items-center gap-2 hover:bg-gray-50 p-1 rounded text-sm text-gray-700">PHP 框架开发</a></li>
                    <li><a href="#" class="flex items-center gap-2 hover:bg-gray-50 p-1 rounded text-sm text-gray-700">Tailwind 实战</a></li>
                </ul>
            </div>
        </div>

        <!-- 右侧操作栏 -->
        <div class="flex items-center gap-3">
            <?php if (isset($user) && !empty($user)): // 如果用户信息存在，则视为已登录 ?>
                <!-- 已登录状态 -->
                <!-- 上传按钮 -->
                <a href="/upload" class="flex items-center gap-2 bg-[#FE2C55] text-white px-4 py-2 rounded-full text-sm font-medium hover:bg-opacity-90 transition-all transform hover:scale-105 shadow-sm">
                    <i class="fas fa-upload"></i>
                    <span class="hidden sm:inline">上传</span>
                </a>
                <div class="relative cursor-pointer group">
                    <!-- 用户头像 -->
                    <div class="w-8 h-8 bg-gray-200 rounded-full overflow-hidden">
                        <img src="<?= $user['avatar_path'] ?? '/assets/images/default-avatar.png' ?>" alt="用户头像" class="w-full h-full object-cover">
                    </div>
                    <!-- 用户下拉菜单 -->
                    <div class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 hidden group-hover:block z-50">
                        <ul class="py-2">
                            <li class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <a href="/user/profile" class="flex items-center gap-2">
                                    <i class="fas fa-user"></i>
                                    <span>我的主页</span>
                                </a>
                            </li>
                            <li class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <a href="/user/videos" class="flex items-center gap-2">
                                    <i class="fas fa-video"></i>
                                    <span>我的视频</span>
                                </a>
                            </li>
                            <li class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <a href="/user/favorites" class="flex items-center gap-2">
                                    <i class="fas fa-heart"></i>
                                    <span>我的收藏</span>
                                </a>
                            </li>
                            <li class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <a href="/user/settings" class="flex items-center gap-2">
                                    <i class="fas fa-cog"></i>
                                    <span>设置</span>
                                </a>
                            </li>
                            <li class="border-t border-gray-200 mt-2 pt-2"></li>
                            <li class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <a href="/logout" class="flex items-center gap-2 text-red-500">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>退出登录</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php else: ?>
                <!-- 未登录状态 -->
                <a href="/login" class="bg-gray-100 text-gray-800 px-5 py-2 rounded-full text-sm font-medium hover:bg-gray-200 transition-all transform hover:scale-105">
                    登录
                </a>
                <a href="/register" class="bg-black text-white px-5 py-2 rounded-full text-sm font-medium hover:bg-opacity-90 transition-all transform hover:scale-105">
                    注册
                </a>
            <?php endif; ?>
        </div>
    </header>

    <!-- 主内容区 -->
    <main class="container mx-auto px-4 py-20 max-w-[1280px]">
        <?php include $__view_path; ?>
    </main>

    <!-- 页脚 -->
    <footer class="bg-white border-t mt-10">
        <div class="container mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row justify-between gap-6">
                <div>
                    <h2 class="font-bold text-xl text-pink-500 mb-4">ClipCircle</h2>
                    <p class="text-gray-500 text-sm">分享精彩视频，发现更多乐趣</p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div>
                        <h3 class="font-medium mb-3">关于我们</h3>
                        <ul class="space-y-2 text-sm text-gray-500">
                            <li><a href="/about" class="hover:text-pink-500 transition">公司简介</a></li>
                            <li><a href="/contact" class="hover:text-pink-500 transition">联系我们</a></li>
                            <li><a href="/jobs" class="hover:text-pink-500 transition">加入我们</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-medium mb-3">帮助中心</h3>
                        <ul class="space-y-2 text-sm text-gray-500">
                            <li><a href="/faq" class="hover:text-pink-500 transition">常见问题</a></li>
                            <li><a href="/terms" class="hover:text-pink-500 transition">服务条款</a></li>
                            <li><a href="/privacy" class="hover:text-pink-500 transition">隐私政策</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-medium mb-3">资源</h3>
                        <ul class="space-y-2 text-sm text-gray-500">
                            <li><a href="/creators" class="hover:text-pink-500 transition">创作者中心</a></li>
                            <li><a href="/api" class="hover:text-pink-500 transition">API文档</a></li>
                            <li><a href="/blog" class="hover:text-pink-500 transition">博客</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-medium mb-3">关注我们</h3>
                        <div class="flex gap-3 text-gray-500 text-xl">
                            <a href="#" class="hover:text-pink-500 transition"><i class="fab fa-weibo"></i></a>
                            <a href="#" class="hover:text-pink-500 transition"><i class="fab fa-wechat"></i></a>
                            <a href="#" class="hover:text-pink-500 transition"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="hover:text-pink-500 transition"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t mt-8 pt-8 text-center text-sm text-gray-500">
                <p>&copy; 2023 ClipCircle. 保留所有权利。</p>
            </div>
        </div>
    </footer>

    <script src="/assets/js/app.js"></script>
    <script src="/assets/js/frontend.js"></script>
    <script src="/themes/default/js/script.js"></script>
</body>
</html>