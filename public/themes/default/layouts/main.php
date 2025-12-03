<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'MyVideoApp' ?></title>
    <!-- 引入 Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- 引入 Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    <!-- 引入 FontAwesome 图标 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- 自定义样式覆盖 -->
    <style>
        /* 模拟 TikTok 的主题色 */
        :root {
            --primary: #FE2C55;
            --dark: #161823;
        }
        .text-primary { color: var(--primary); }
        .bg-primary { background-color: var(--primary); }
        .btn-primary { background-color: var(--primary); color: white; }
        .btn-primary:hover { opacity: 0.9; }
        
        /* 隐藏滚动条但保留滚动功能 */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-white text-[#161823] overflow-hidden h-screen flex flex-col">

    <!-- 1. 顶部导航栏 -->
    <?php include __DIR__ . '/../views/partials/header.php'; ?>

    <div class="flex flex-1 overflow-hidden pt-[60px]">
        <!-- 2. 左侧侧边栏 -->
        <aside class="w-[240px] flex-shrink-0 border-r border-gray-100 overflow-y-auto hidden md:block custom-scroll hover:overflow-y-auto">
            <?php include __DIR__ . '/../views/partials/sidebar.php'; ?>
        </aside>

        <!-- 3. 主内容区域 -->
        <main class="flex-1 overflow-y-auto bg-[#F8F8F8] p-4 relative">
            <!-- 视图内容 -->
            <?php 
        // 基类 Controller::view() 会定义 $__view_path 变量
        if (isset($__view_path) && file_exists($__view_path)) {
            include $__view_path;
        } else {
            echo "错误：找不到视图文件。";
        }
            ?>
        </main>
    </div>

    <!-- 主题脚本 -->
    <script src="/themes/default/js/script.js"></script>
</body>
</html>