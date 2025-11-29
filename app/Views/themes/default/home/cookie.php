<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookie 政策 - ClipCircle</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= $themeUrl ?>/css/style.css">
</head>
<body class="bg-white text-gray-800">

    <!-- 简单头部 -->
    <nav class="border-b p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="text-2xl font-extrabold tracking-tighter">
                <span style="text-shadow: -1px -1px 0 #25F4EE;">Clip</span><span style="color: #FE2C55; text-shadow: 1px 1px 0 #25F4EE;">Circle</span>
            </a>
            <a href="/" class="text-sm font-bold hover:text-[#FE2C55]">返回首页</a>
        </div>
    </nav>

    <div class="container mx-auto max-w-3xl p-8 leading-relaxed">
        <h1 class="text-3xl font-bold mb-6">Cookie 政策</h1>
        <p class="text-gray-500 mb-8 text-sm">更新日期：<?= date('Y年m月d日') ?></p>

        <section class="mb-8">
            <h2 class="text-xl font-bold mb-4">1. 什么是 Cookie？</h2>
            <p class="mb-2">Cookie 是您访问网站时存储在您设备（电脑、手机等）上的小型文本文件。它们广泛用于让网站运行更加高效，以及向网站所有者提供信息。</p>
        </section>

        <section class="mb-8">
            <h2 class="text-xl font-bold mb-4">2. 我们如何使用 Cookie</h2>
            <p class="mb-2">ClipCircle 使用 Cookie 主要用于以下目的：</p>
            <ul class="list-disc list-inside space-y-2 ml-4 text-gray-600">
                <li><strong>必要性 Cookie：</strong> 比如维持您的登录状态（Session），没有这些 Cookie，您无法登录或发布视频。</li>
                <li><strong>功能性 Cookie：</strong> 记住您的偏好设置，例如音量设置或您刚才浏览的位置。</li>
                <li><strong>分析性 Cookie：</strong> 帮助我们了解有多少用户访问了网站，以及哪些视频最受欢迎。</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-xl font-bold mb-4">3. 如何管理 Cookie</h2>
            <p class="mb-2">您可以在浏览器设置中随时清除或禁止 Cookie。但请注意，禁用某些 Cookie 可能会导致 ClipCircle 的部分功能（如登录、评论）无法正常使用。</p>
        </section>
        
        <div class="mt-12 pt-8 border-t text-sm text-gray-500">
            <p>&copy; <?= date('Y') ?> ClipCircle. All Rights Reserved.</p>
        </div>
    </div>

</body>
</html>