<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? '视频分享平台' ?></title>
    <script src="/assets/vendor/tailwind.js"></script>
    <script defer src="/assets/vendor/alpine.js"></script>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="bg-gray-50">
    <!-- 顶部导航栏 -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-4">
                    <a href="/" class="font-bold text-xl text-pink-500">ClipCircle</a>
                    <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                        <a href="/" class="text-pink-500">首页</a>
                        <a href="/following" class="text-gray-600 hover:text-pink-500 transition">关注</a>
                        <a href="/explore" class="text-gray-600 hover:text-pink-500 transition">探索</a>
                        <a href="/live" class="text-gray-600 hover:text-pink-500 transition">直播</a>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- 搜索框 -->
                    <div class="relative">
                        <input type="text" placeholder="搜索视频" class="pl-10 pr-4 py-2 rounded-full border border-gray-200 focus:outline-none focus:ring-2 focus:ring-pink-500">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    
                    <!-- 用户菜单 -->
                    <?php if (isset($user)): ?>
                        <div class="flex items-center gap-3">
                            <a href="/upload" class="px-4 py-2 bg-pink-500 text-white rounded-full text-sm font-medium hover:bg-pink-600 transition">
                                <i class="fas fa-plus mr-1"></i> 上传
                            </a>
                            <a href="#" class="text-gray-600 hover:text-pink-500">
                                <i class="fas fa-bell text-xl"></i>
                            </a>
                            <?php include __DIR__ . '/../../Components/UserMenu.php'; userMenu($user); ?>
                        </div>
                    <?php else: ?>
                        <div class="flex items-center gap-2">
                            <a href="/login" class="px-4 py-2 border border-gray-200 rounded-full text-sm font-medium hover:bg-gray-50 transition">登录</a>
                            <a href="/register" class="px-4 py-2 bg-pink-500 text-white rounded-full text-sm font-medium hover:bg-pink-600 transition">注册</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- 主内容区 -->
    <main class="container mx-auto px-4 py-6">
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
</body>
</html>