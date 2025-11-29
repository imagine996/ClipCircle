<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'ClipCircle' ?> - TikTok Style</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= $themeUrl ?>/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-white min-h-screen flex flex-col">

    <!-- 1. 顶部导航栏 -->
    <nav class="bg-white border-b border-gray-100 fixed w-full top-0 z-50 h-[60px] flex items-center">
        <div class="w-full max-w-[1400px] mx-auto px-4 flex justify-between items-center">
            
            <!-- Logo -->
            <a href="/" class="flex items-center gap-1 shrink-0">
                <span class="text-3xl font-extrabold tracking-tighter" style="font-family: sans-serif;">
                    <span style="text-shadow: -2px -2px 0 #25F4EE;">Clip</span><span style="color: #FE2C55; text-shadow: 2px 2px 0 #25F4EE;">Circle</span>
                </span>
            </a>
            
            <!-- 搜索框 (修复版：使用 Flexbox 完美对齐) -->
            <div class="hidden md:flex flex-1 max-w-[500px] mx-8">
                <form action="/" method="GET" class="w-full flex items-center bg-gray-100 rounded-full overflow-hidden border border-transparent focus-within:border-gray-300 focus-within:bg-gray-200/50 transition group">
                    <input type="hidden" name="c" value="Home">
                    <input type="hidden" name="a" value="search">
                    
                    <!-- 输入框 -->
                    <input type="text" name="q" placeholder="搜索你感兴趣的内容" value="<?= $_GET['q'] ?? '' ?>"
                           class="flex-1 bg-transparent py-2.5 px-5 outline-none text-sm placeholder-gray-400 text-gray-800">
                    
                    <!-- 分割线 -->
                    <div class="w-[1px] h-6 bg-gray-300"></div>
                    
                    <!-- 按钮 -->
                    <button type="submit" class="px-5 py-2.5 bg-transparent text-gray-500 hover:bg-gray-200 transition">
                        <i class="fas fa-search text-lg"></i>
                    </button>
                </form>
            </div>

            <!-- 右侧按钮 -->
            <div class="flex items-center gap-4 shrink-0">
                <a href="/?c=User&a=upload" class="flex items-center gap-2 px-3 py-1.5 hover:bg-gray-100 rounded-sm transition cursor-pointer border border-gray-200 hover:border-gray-300">
                    <i class="fas fa-plus text-gray-800"></i>
                    <span class="font-bold text-gray-800 text-sm">上传</span>
                </a>
                
                <?php if (isset($_SESSION['user'])): ?>
                    <div class="relative group cursor-pointer z-50">
                        <img src="<?= $_SESSION['user']['avatar'] ?? '/uploads/default_avatar.png' ?>" class="w-8 h-8 rounded-full border border-gray-200 object-cover">
                        <!-- 下拉菜单 -->
                        <div class="absolute right-0 top-full mt-2 w-48 bg-white shadow-xl rounded-lg py-2 hidden group-hover:block border border-gray-100">
                            <a href="/?c=User&a=dashboard" class="block px-4 py-2 hover:bg-gray-50 text-sm font-bold text-gray-700">
                                <i class="fas fa-user mr-2 w-4 text-center"></i> 个人主页
                            </a>
                            <?php if(isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
                                <a href="/?c=Admin&a=index" class="block px-4 py-2 hover:bg-gray-50 text-sm font-bold text-purple-600">
                                    <i class="fas fa-tachometer-alt mr-2 w-4 text-center"></i> 进入后台
                                </a>
                            <?php endif; ?>
                            <a href="/?c=Auth&a=logout" class="block px-4 py-2 hover:bg-gray-50 text-sm border-t mt-1 text-gray-700">
                                <i class="fas fa-sign-out-alt mr-2 w-4 text-center"></i> 退出登录
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/?c=Auth&a=login" class="btn-primary px-6 py-1.5 text-sm">登录</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- 2. 主体布局 -->
    <div class="w-full max-w-[1400px] mx-auto flex pt-[60px] flex-1">
        
        <!-- 左侧侧边栏 (动态激活状态) -->
        <aside class="w-[240px] hidden lg:block h-[calc(100vh-60px)] overflow-y-auto sticky top-[60px] pr-4 py-4 scrollbar-hide">
            <div class="space-y-1 mb-6 border-b pb-4">
                <?php 
                $menuClass = function($id) use ($active_tab) {
                    $base = "flex items-center gap-3 px-2 py-3 font-bold text-lg rounded transition ";
                    return $active_tab === $id ? $base . "text-[#FE2C55]" : $base . "text-gray-700 hover:bg-gray-100";
                };
                ?>
                <a href="/?c=Home&a=index" class="<?= $menuClass('home') ?>">
                    <i class="fas fa-home w-8 text-center"></i> 推荐
                </a>
                <a href="/?c=Home&a=following" class="<?= $menuClass('following') ?>">
                    <i class="fas fa-user-friends w-8 text-center"></i> 关注
                </a>
                <a href="/?c=Home&a=explore" class="<?= $menuClass('explore') ?>">
                    <i class="fas fa-compass w-8 text-center"></i> 探索
                </a>
                <a href="/?c=Home&a=live" class="<?= $menuClass('live') ?>">
                    <i class="fas fa-video w-8 text-center"></i> 直播
                </a>
            </div>

            <?php if (!isset($_SESSION['user'])): ?>
            <div class="mb-6 border-b pb-6 px-2">
                <p class="text-gray-400 text-sm mb-4">登录后关注作者、点赞视频并查看评论。</p>
                <a href="/?c=Auth&a=login" class="block w-full text-center border border-[#FE2C55] text-[#FE2C55] font-bold py-2.5 rounded hover:bg-[#FE2C5508] transition">登录</a>
            </div>
            <?php endif; ?>
            
            <div class="mt-10 px-2 text-xs text-gray-400 leading-relaxed">
                <p>&copy; 2025 ClipCircle</p>
            </div>
        </aside>

        <!-- 中间内容流 -->
        <main class="flex-1 p-4 lg:p-6 bg-[#F8F8F8]">
            
            <!-- 标题栏 -->
            <?php if($active_tab !== 'home'): ?>
                <h1 class="text-xl font-bold mb-6 ml-2"><?= $page_title ?></h1>
            <?php endif; ?>

            <!-- A. 直播模式 -->
            <?php if($active_tab === 'live'): ?>
                <?php if(empty($lives)): ?>
                    <div class="text-center py-20 text-gray-400">
                        <i class="fas fa-satellite-dish text-6xl mb-4 text-gray-300"></i>
                        <p>当前没有正在进行的直播</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <?php foreach($lives as $live): ?>
                            <div class="relative bg-black aspect-video rounded-lg overflow-hidden group cursor-pointer">
                                <!-- 模拟直播画面 -->
                                <img src="<?= $live['cover_path'] ?>" class="w-full h-full object-cover opacity-60">
                                <div class="absolute top-2 left-2 bg-[#FE2C55] text-white text-xs px-2 py-0.5 rounded animate-pulse">
                                    LIVE
                                </div>
                                <div class="absolute bottom-2 left-2 text-white">
                                    <p class="font-bold text-sm shadow-black drop-shadow-md"><?= $live['title'] ?></p>
                                    <p class="text-xs text-gray-300"><?= $live['viewers'] ?> 观看</p>
                                </div>
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                    <i class="fas fa-play-circle text-5xl text-white/80"></i>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            
            <!-- B. 视频模式 (推荐/关注/探索/搜索) -->
            <?php else: ?>
                <?php if(empty($videos)): ?>
                    <div class="flex flex-col items-center justify-center h-[60vh] text-gray-400">
                        <i class="fas fa-film text-6xl mb-4 text-gray-300"></i>
                        <p><?= $active_tab === 'following' ? '你还没有关注任何人' : '暂时没有相关视频' ?></p>
                        <?php if($active_tab === 'following'): ?>
                            <a href="/?c=Home&a=explore" class="mt-4 text-[#FE2C55] font-bold hover:underline">去探索发现</a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-6">
                        <?php foreach ($videos as $v): ?>
                            <div class="video-card group relative cursor-pointer">
                                <a href="/?c=Video&a=play&id=<?= $v['id'] ?>" class="block relative aspect-[3/4] rounded-lg overflow-hidden bg-black shadow-sm">
                                    <video src="<?= $v['file_path'] ?>" poster="<?= $v['cover_path'] ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" muted loop onmouseover="this.play()" onmouseout="this.pause();this.currentTime=0;"></video>
                                    <div class="absolute bottom-0 w-full h-1/2 bg-gradient-to-t from-black/60 to-transparent pointer-events-none"></div>
                                    <div class="absolute bottom-2 left-3 text-white text-sm font-bold flex items-center gap-1">
                                        <i class="fas fa-play text-xs"></i> <?= $v['views'] ?>
                                    </div>
                                </a>
                                <div class="mt-2">
                                    <h3 class="font-bold text-gray-800 text-sm line-clamp-1 group-hover:text-[#FE2C55] transition"><?= htmlspecialchars($v['title']) ?></h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <div class="w-5 h-5 rounded-full bg-gray-200 overflow-hidden">
                                            <img src="/uploads/default_avatar.png" class="w-full h-full object-cover">
                                        </div>
                                        <span class="text-xs text-gray-500 font-medium">user_<?= $v['user_id'] ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>