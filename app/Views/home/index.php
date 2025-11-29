<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>MyVideo - 首页</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- 导航栏 -->
    <nav class="bg-white shadow p-4 sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="text-pink-500 font-bold text-2xl">MyVideo</a>
            
            <div class="flex-1 mx-10">
                <form action="/" method="GET" class="flex">
                    <input type="hidden" name="c" value="Home">
                    <input type="hidden" name="a" value="search">
                    <input type="text" name="q" placeholder="搜索视频..." class="w-full border p-2 rounded-l focus:outline-none">
                    <button class="bg-pink-500 text-white px-6 rounded-r hover:bg-pink-600">搜</button>
                </form>
            </div>

            <div class="space-x-4 text-sm font-bold">
                <?php if (isset($_SESSION['user'])): ?>
                    <span class="text-gray-500">Hi, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
                    <a href="/?c=User&a=dashboard" class="text-blue-500 hover:text-blue-700">创作中心</a>
                    <?php if($_SESSION['user']['role'] === 'admin'): ?>
                        <a href="/?c=Admin&a=index" class="text-purple-600 hover:text-purple-800">后台管理</a>
                    <?php endif; ?>
                    <a href="/?c=Auth&a=logout" class="text-red-400 hover:text-red-600">退出</a>
                <?php else: ?>
                    <a href="/?c=Auth&a=login" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">登录 / 注册</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- 频道分类 -->
    <div class="container mx-auto mt-4 px-4 flex gap-4 text-gray-600 text-sm overflow-x-auto">
        <a href="/" class="bg-pink-100 text-pink-600 px-4 py-1 rounded-full font-bold">首页</a>
        <?php foreach ($categories as $cat): ?>
            <a href="#" class="hover:bg-gray-200 px-4 py-1 rounded-full"><?= $cat ?></a>
        <?php endforeach; ?>
    </div>

    <!-- 视频列表 -->
    <div class="container mx-auto mt-6 px-4 pb-10">
        <h2 class="font-bold text-xl mb-4 text-gray-700">热门推荐</h2>
        <?php if(empty($videos)): ?>
            <div class="text-center py-20 text-gray-400">
                <p class="text-4xl mb-2">📹</p>
                <p>暂无视频，快去<a href="/?c=Auth&a=login" class="text-blue-500">投稿</a>吧！</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php foreach ($videos as $v): ?>
                    <a href="/?c=Video&a=play&id=<?= $v['id'] ?>" class="group block">
                        <div class="aspect-video bg-gray-200 rounded-lg overflow-hidden relative shadow-sm group-hover:shadow-md transition">
                            <img src="<?= $v['cover_path'] ?>" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-300">
                            <div class="absolute bottom-2 right-2 bg-black bg-opacity-60 text-white text-xs px-1 rounded">HD</div>
                        </div>
                        <h3 class="font-bold mt-2 text-gray-800 line-clamp-2 group-hover:text-pink-500 transition"><?= htmlspecialchars($v['title']) ?></h3>
                        <div class="flex justify-between items-center mt-1 text-xs text-gray-500">
                            <span>UP: 这里的名字需要联表查</span> <!-- 简化处理 -->
                            <span>👀 <?= $v['views'] ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>