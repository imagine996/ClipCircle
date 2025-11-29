<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($video['title']) ?> - ClipCircle</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= $themeUrl ?>/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* 隐藏播放器默认控件的背景 */
        video::-webkit-media-controls-panel {
            background-image: linear-gradient(transparent, transparent) !important; 
        }
    </style>
</head>
<body class="bg-black overflow-hidden h-screen w-screen flex flex-col md:flex-row">

    <!-- 左侧：沉浸式视频区 -->
    <div class="flex-1 relative bg-black/90 flex items-center justify-center h-1/2 md:h-full group">
        
        <!-- 背景模糊效果 (毛玻璃) -->
        <div class="absolute inset-0 z-0 opacity-20 blur-3xl" style="background-image: url('<?= $video['cover_path'] ?>'); background-size: cover; background-position: center;"></div>

        <!-- 返回按钮 (浮动) -->
        <a href="/" class="absolute top-6 left-6 z-20 w-10 h-10 bg-gray-800/50 rounded-full flex items-center justify-center text-white hover:bg-gray-700 transition">
            <i class="fas fa-times text-xl"></i>
        </a>

        <!-- 视频主体 -->
        <div class="relative z-10 w-full h-full max-w-[calc(100vh*9/16)] md:max-w-none md:w-auto md:h-full flex items-center justify-center">
            <!-- 弹幕层 -->
            <div id="danmaku-stage" class="absolute inset-0 pointer-events-none overflow-hidden z-20"></div>
            
            <video id="player" src="<?= $video['file_path'] ?>" class="max-h-full max-w-full object-contain shadow-2xl" autoplay controls loop></video>
        </div>

        <!-- 悬浮操作栏 (右下角) -->
        <div class="absolute right-6 bottom-20 z-20 flex flex-col gap-4 text-white md:hidden">
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 bg-gray-800/80 rounded-full flex items-center justify-center mb-1">
                    <i class="fas fa-heart text-[#FE2C55]"></i>
                </div>
                <span class="text-xs font-bold"><?= $video['likes'] ?></span>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 bg-gray-800/80 rounded-full flex items-center justify-center mb-1">
                    <i class="fas fa-comment-dots text-white"></i>
                </div>
                <span class="text-xs font-bold">评论</span>
            </div>
        </div>
    </div>

    <!-- 右侧：信息与评论区 (白色面板) -->
    <div class="w-full md:w-[500px] bg-white h-1/2 md:h-full flex flex-col border-l border-gray-100 relative z-30">
        
        <!-- 1. 作者信息头部 -->
        <div class="p-4 md:p-6 border-b border-gray-100 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <a href="#" class="w-10 h-10 md:w-12 md:h-12 bg-gray-200 rounded-full overflow-hidden hover:opacity-80">
                    <img src="/uploads/default_avatar.png" class="w-full h-full object-cover">
                </a>
                <div>
                    <a href="#" class="font-bold text-base md:text-lg hover:underline block leading-tight">
                        <?= htmlspecialchars($video['username']) ?>
                    </a>
                    <span class="text-xs text-gray-500"><?= substr($video['created_at'], 0, 10) ?></span>
                </div>
            </div>
            <button class="bg-[#FE2C55] text-white px-6 py-2 rounded text-sm font-bold hover:bg-[#E6284D] transition">关注</button>
        </div>

        <!-- 2. 视频文案 -->
        <div class="px-4 md:px-6 py-4 shrink-0">
            <h1 class="text-base md:text-lg text-gray-800 leading-snug font-normal">
                <?= htmlspecialchars($video['title']) ?>
                <!-- 模拟话题标签 -->
                <span class="font-bold text-gray-800">#fyp #tiktokstyle #coding</span>
            </h1>
            <div class="mt-3 flex items-center justify-between text-sm text-gray-500 bg-gray-50 p-3 rounded">
                <span class="flex items-center gap-2"><i class="fas fa-music"></i> 原声 - <?= htmlspecialchars($video['username']) ?></span>
            </div>
        </div>

        <!-- 3. 数据统计栏 -->
        <div class="px-4 md:px-6 py-3 flex items-center gap-6 border-b border-gray-100 shrink-0">
            <div class="flex items-center gap-2 cursor-pointer group">
                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-gray-200 transition">
                    <i class="fas fa-heart text-lg group-hover:text-[#FE2C55] transition"></i>
                </div>
                <span class="text-xs font-bold text-gray-600"><?= $video['likes'] ?></span>
            </div>
            <div class="flex items-center gap-2 cursor-pointer group">
                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-gray-200 transition">
                    <i class="fas fa-comment-dots text-lg"></i>
                </div>
                <span class="text-xs font-bold text-gray-600">评论</span>
            </div>
            <div class="flex items-center gap-2 cursor-pointer group">
                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-gray-200 transition">
                    <i class="fas fa-share text-lg text-blue-500"></i>
                </div>
                <span class="text-xs font-bold text-gray-600">分享</span>
            </div>
        </div>

        <!-- 4. 评论列表 (可滚动) -->
        <div class="flex-1 overflow-y-auto p-4 md:p-6 bg-[#F8F8F8]">
            <!-- 模拟评论 -->
            <div class="space-y-4">
                <?php if (empty($comments)): ?>
                    <div class="text-center text-gray-400 py-10">
                        <p>还没有评论，快来抢沙发！</p>
                    </div>
                <?php endif; ?>

                <?php foreach ($comments as $c): ?>
                    <div class="flex gap-3">
                        <div class="w-8 h-8 bg-gray-200 rounded-full shrink-0"></div>
                        <div>
                            <p class="text-xs font-bold text-gray-600 mb-0.5"><?= htmlspecialchars($c['username']) ?></p>
                            <p class="text-sm text-gray-800 leading-relaxed"><?= htmlspecialchars($c['content']) ?></p>
                            <p class="text-xs text-gray-400 mt-1"><?= substr($c['created_at'], 5, 11) ?> · 回复</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- 5. 底部输入框 -->
        <div class="p-4 border-t border-gray-200 bg-white shrink-0">
            <?php if ($user): ?>
            <div class="flex items-center gap-3">
                <input type="text" id="comment-input" placeholder="留下你的精彩评论..." class="flex-1 bg-gray-100 border-transparent focus:bg-white focus:border-gray-300 border px-4 py-3 rounded-lg text-sm outline-none transition">
                <button onclick="sendComment()" class="text-[#FE2C55] font-bold text-sm px-2">发送</button>
            </div>
            <?php else: ?>
            <div class="text-center py-2 bg-gray-50 rounded text-pink-500 font-bold cursor-pointer" onclick="location.href='/?c=Auth&a=login'">
                登录后发表评论
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- 弹幕和评论 JS (复用之前的逻辑，略作调整) -->
    <script>
        const vid = <?= $video['id'] ?>;
        // ... (保持之前的 JS 逻辑，或者精简) ...
        
        function sendComment() {
            const input = document.getElementById('comment-input');
            if(!input.value) return;
            // 发送请求逻辑...
            alert('评论功能演示：' + input.value);
            input.value = '';
        }
    </script>
    
    <style>
        /* 简单的弹幕样式覆盖 */
        .danmaku-item { position: absolute; font-weight: bold; color: white; text-shadow: 1px 1px 2px black; white-space: nowrap; animation: move 8s linear infinite; pointer-events: none; }
        @keyframes move { from { left: 100%; } to { left: -100%; } }
    </style>
</body>
</html>