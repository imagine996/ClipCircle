<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClipCircle 管理控制台</title>
    
    <!-- 1. 本地加载 Tailwind (样式) -->
    <script src="ui/vendor/tailwind.js"></script>
    
    <!-- 2. 本地加载 Alpine.js (交互) -->
    <script defer src="ui/vendor/alpine.js"></script>
    
    <!-- 3. 本地加载图标库 -->
    <script src="ui/vendor/fa.js"></script>
    
    <!-- 4. 解决本地 Tailwind 加载时的闪烁问题 -->
    <style>
        /* 隐藏滚动条 */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .glass-nav { background: rgba(30, 41, 59, 0.95); backdrop-filter: blur(10px); }
        
        /* 预加载防止样式抖动 */
        [x-cloak] { display: none !important; }
    </style>
    
    <!-- 配置 Tailwind (可选，自定义颜色) -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        tiktok: '#FE2C55',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-slate-800 font-sans antialiased" x-data="{ sidebarOpen: true }">

    <!-- 1. 左侧侧边栏 -->
    <aside class="fixed top-0 left-0 z-40 h-screen transition-transform duration-300 ease-in-out border-r border-slate-700 glass-nav text-white flex flex-col"
           :class="sidebarOpen ? 'w-64 translate-x-0' : 'w-20 -translate-x-0'">
        
        <!-- Logo -->
        <div class="h-16 flex items-center justify-center border-b border-slate-700/50">
            <a href="/" class="flex items-center gap-2 overflow-hidden px-4">
                <div class="w-8 h-8 bg-gradient-to-tr from-pink-500 to-purple-600 rounded-lg flex items-center justify-center shrink-0 shadow-lg shadow-pink-500/30">
                    <span class="font-bold text-white">C</span>
                </div>
                <span class="text-xl font-bold tracking-tight whitespace-nowrap" x-show="sidebarOpen" x-transition>
                    ClipAdmin
                </span>
            </a>
        </div>

        <!-- 菜单列表 -->
        <div class="flex-1 overflow-y-auto py-4 scrollbar-hide space-y-1">
            
            <!-- 分组标题 -->
            <div class="px-4 py-2 text-xs font-bold text-slate-500 uppercase tracking-wider" x-show="sidebarOpen">概览</div>
            
            <?php 
            $menuItem = function($id, $icon, $label, $activePage, $badge = null) use ($page) {
                $isActive = $page === $id;
                $bgClass = $isActive ? 'bg-pink-600 text-white shadow-md shadow-pink-900/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white';
                return <<<HTML
                <a href="/?c=Admin&page={$id}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-all duration-200 group {$bgClass}">
                    <i class="{$icon} w-6 text-center text-lg"></i>
                    <span class="ml-3 text-sm font-medium whitespace-nowrap flex-1" x-show="sidebarOpen">{$label}</span>
                    {$badge}
                </a>
HTML;
            };

            // Badge HTML
            $pendingBadge = $pending_count > 0 ? "<span class='bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full' x-show='sidebarOpen'>{$pending_count}</span>" : "";

            echo $menuItem('dashboard', 'fas fa-chart-pie', '仪表板', $page);
            ?>

            <div class="px-4 py-2 mt-4 text-xs font-bold text-slate-500 uppercase tracking-wider" x-show="sidebarOpen">内容管理</div>
            <?php
            echo $menuItem('videos', 'fas fa-video', '视频管理', $page, $pendingBadge);
            echo $menuItem('users', 'fas fa-users', '用户管理', $page);
            echo $menuItem('memberships', 'fas fa-crown', '会员等级', $page);
            echo $menuItem('reports', 'fas fa-flag', '举报/报告', $page);
            ?>

            <div class="px-4 py-2 mt-4 text-xs font-bold text-slate-500 uppercase tracking-wider" x-show="sidebarOpen">系统设置</div>
            <?php
            echo $menuItem('themes', 'fas fa-palette', '主题外观', $page);
            echo $menuItem('pages', 'fas fa-file-alt', '描述页/CMS', $page);
            echo $menuItem('languages', 'fas fa-language', '语言包', $page);
            echo $menuItem('settings', 'fas fa-cog', '全局设置', $page);
            echo $menuItem('api', 'fas fa-code', 'API 管理', $page);
            echo $menuItem('tools', 'fas fa-toolbox', '工具箱', $page);
            echo $menuItem('system_status', 'fas fa-server', '系统状态', $page);
            echo $menuItem('changelog', 'fas fa-history', '更新日志', $page);
            ?>
        </div>

        <!-- 底部用户 -->
        <div class="p-4 border-t border-slate-700/50">
            <a href="/?c=Auth&a=logout" class="flex items-center gap-3 px-2 py-2 rounded hover:bg-slate-800 transition text-slate-400 hover:text-red-400">
                <i class="fas fa-sign-out-alt w-6 text-center"></i>
                <span class="text-sm font-medium" x-show="sidebarOpen">退出系统</span>
            </a>
        </div>
    </aside>

    <!-- 2. 主体区域 -->
    <div class="transition-all duration-300" :class="sidebarOpen ? 'ml-64' : 'ml-20'">
        
        <!-- 顶部 Header -->
        <header class="h-16 bg-white border-b border-gray-200 sticky top-0 z-30 flex items-center justify-between px-6 shadow-sm">
            <!-- 侧边栏开关 -->
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-pink-600 transition">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <!-- 右侧工具 -->
            <div class="flex items-center gap-6">
                <a href="/" target="_blank" class="text-sm text-gray-500 hover:text-pink-600 flex items-center gap-1" title="查看前台">
                    <i class="fas fa-external-link-alt"></i> <span class="hidden sm:inline">浏览网站</span>
                </a>
                
                <!-- 简单的通知铃铛 -->
                <div class="relative cursor-pointer">
                    <i class="fas fa-bell text-gray-500 hover:text-gray-700"></i>
                    <?php if($pending_count > 0): ?>
                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    <?php endif; ?>
                </div>

                <!-- 管理员头像 -->
                <div class="flex items-center gap-3 pl-6 border-l border-gray-200">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-gray-800"><?= $user['username'] ?></p>
                        <p class="text-xs text-green-500">Super Admin</p>
                    </div>
                    <img src="<?= $user['avatar'] ?? '/uploads/default_avatar.png' ?>" class="w-9 h-9 rounded-full bg-gray-200 border border-gray-300">
                </div>
            </div>
        </header>

        <!-- 内容画布 -->
        <main class="p-6">
            
            <!-- 动态内容渲染区 -->
            <?php switch($page): 
                // ==========================
                // 1. 仪表板 (Dashboard)
                // ==========================
                case 'dashboard': ?>
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-gray-800">仪表板</h1>
                        <p class="text-gray-500 text-sm">欢迎回来，这是今天的概况。</p>
                    </div>

                    <!-- 统计卡片 -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition">
                            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-xl"><i class="fas fa-users"></i></div>
                            <div>
                                <p class="text-gray-400 text-xs font-bold uppercase">总用户</p>
                                <p class="text-2xl font-black text-gray-800"><?= $stats['users'] ?></p>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition">
                            <div class="w-12 h-12 rounded-full bg-pink-50 text-pink-500 flex items-center justify-center text-xl"><i class="fas fa-video"></i></div>
                            <div>
                                <p class="text-gray-400 text-xs font-bold uppercase">视频总数</p>
                                <p class="text-2xl font-black text-gray-800"><?= $stats['videos'] ?></p>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition">
                            <div class="w-12 h-12 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center text-xl"><i class="fas fa-tasks"></i></div>
                            <div>
                                <p class="text-gray-400 text-xs font-bold uppercase">待审核</p>
                                <p class="text-2xl font-black text-gray-800"><?= $stats['pending'] ?></p>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition">
                            <div class="w-12 h-12 rounded-full bg-green-50 text-green-500 flex items-center justify-center text-xl"><i class="fas fa-server"></i></div>
                            <div>
                                <p class="text-gray-400 text-xs font-bold uppercase">系统版本</p>
                                <p class="text-lg font-bold text-gray-800">v1.0</p>
                            </div>
                        </div>
                    </div>

                    <!-- 快捷入口 -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                            <h3 class="font-bold text-gray-800 mb-4">快捷操作</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <a href="/?c=Import&a=index" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg hover:bg-purple-50 hover:text-purple-600 transition cursor-pointer border border-transparent hover:border-purple-100">
                                    <i class="fas fa-cloud-download-alt text-2xl mb-2"></i>
                                    <span class="text-sm font-bold">外部导入</span>
                                </a>
                                <a href="/?c=Admin&page=settings" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition cursor-pointer border border-transparent hover:border-blue-100">
                                    <i class="fas fa-cog text-2xl mb-2"></i>
                                    <span class="text-sm font-bold">网站配置</span>
                                </a>
                                <a href="/?c=Admin&page=themes" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg hover:bg-pink-50 hover:text-pink-600 transition cursor-pointer border border-transparent hover:border-pink-100">
                                    <i class="fas fa-palette text-2xl mb-2"></i>
                                    <span class="text-sm font-bold">主题切换</span>
                                </a>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-slate-800 to-slate-900 p-6 rounded-xl shadow-lg text-white">
                            <h3 class="font-bold text-lg mb-2">ClipCircle Pro</h3>
                            <p class="text-slate-400 text-sm mb-4">当前开源版。如需获取更多高级功能（如支付网关、直播流媒体、AI 审核），请查看文档。</p>
                            <button class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded text-sm font-bold">查看文档</button>
                        </div>
                    </div>
                <?php break; ?>

                <?php 
                // ==========================
                // 2. 主题管理 (Themes)
                // ==========================
                case 'themes': ?>
                    <div class="mb-6 flex justify-between items-center">
                        <h1 class="text-2xl font-bold text-gray-800">主题外观</h1>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php foreach ($themes as $t): 
                            $isActive = ($t['id'] === $current_theme); ?>
                            <div class="bg-white rounded-xl shadow-sm border-2 overflow-hidden flex flex-col <?= $isActive ? 'border-green-500 ring-2 ring-green-100' : 'border-gray-100' ?>">
                                <div class="h-32 bg-gray-100 flex items-center justify-center text-4xl">🎨</div>
                                <div class="p-6 flex-1 flex flex-col">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="font-bold text-lg"><?= $t['name'] ?></h3>
                                        <?php if($isActive): ?><span class="bg-green-100 text-green-600 text-xs px-2 py-1 rounded-full font-bold">Active</span><?php endif; ?>
                                    </div>
                                    <p class="text-sm text-gray-400 mb-4 flex-1">ID: <?= $t['id'] ?></p>
                                    <form action="/?c=Admin&a=saveTheme" method="POST">
                                        <input type="hidden" name="theme_id" value="<?= $t['id'] ?>">
                                        <button type="submit" <?= $isActive ? 'disabled' : '' ?> class="w-full py-2 rounded-lg text-sm font-bold transition <?= $isActive ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-slate-800 text-white hover:bg-slate-900' ?>">
                                            <?= $isActive ? '使用中' : '启用主题' ?>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php break; ?>

                <?php 
                // ==========================
                // 3. 用户管理 (Users)
                // ==========================
                case 'users': ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="font-bold">用户列表</h3>
                            <input type="text" placeholder="搜索用户..." class="border rounded-lg px-3 py-1.5 text-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-pink-200 outline-none">
                        </div>
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 text-gray-500">
                                <tr>
                                    <th class="px-6 py-3">ID</th>
                                    <th class="px-6 py-3">用户名</th>
                                    <th class="px-6 py-3">角色</th>
                                    <th class="px-6 py-3">注册时间</th>
                                    <th class="px-6 py-3 text-right">操作</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($user_list as $u): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-gray-500">#<?= $u['id'] ?></td>
                                    <td class="px-6 py-4 font-bold flex items-center gap-2">
                                        <div class="w-6 h-6 bg-gray-200 rounded-full overflow-hidden"><img src="<?= $u['avatar'] ?? '/uploads/default_avatar.png' ?>"></div>
                                        <?= htmlspecialchars($u['username']) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded text-xs font-bold <?= $u['role'] === 'admin' ? 'bg-purple-100 text-purple-600' : 'bg-gray-100 text-gray-600' ?>">
                                            <?= ucfirst($u['role']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500"><?= $u['created_at'] ?></td>
                                    <td class="px-6 py-4 text-right">
                                        <button class="text-blue-500 hover:underline">编辑</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php break; ?>

                <?php 
                // ==========================
                // 4. 系统状态 (System Status)
                // ==========================
                case 'system_status': ?>
                    <h1 class="text-2xl font-bold text-gray-800 mb-6">系统状态</h1>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
                        <div class="space-y-4">
                            <?php foreach ($server_info as $key => $val): ?>
                            <div class="flex justify-between border-b border-gray-50 pb-2">
                                <span class="text-gray-500 font-medium"><?= strtoupper(str_replace('_', ' ', $key)) ?></span>
                                <span class="font-mono font-bold text-slate-700"><?= $val ?></span>
                            </div>
                            <?php endforeach; ?>
                            <div class="flex justify-between pt-2">
                                <span class="text-gray-500 font-medium">PHP Version</span>
                                <span class="font-mono font-bold text-green-600"><?= phpversion() ?></span>
                            </div>
                        </div>
                    </div>
                <?php break; ?>
                
                <?php 
                // ==========================
                // 5. 更新日志 (Changelog)
                // ==========================
                case 'changelog': ?>
                    <div class="max-w-3xl">
                        <h1 class="text-2xl font-bold text-gray-800 mb-6">更新日志</h1>
                        <div class="border-l-2 border-slate-200 ml-3 space-y-8 pl-6 relative">
                            <div class="relative">
                                <span class="absolute -left-[31px] top-1 w-4 h-4 rounded-full bg-pink-500 border-4 border-white shadow"></span>
                                <h3 class="font-bold text-lg text-slate-800">v1.0.0 <span class="text-sm font-normal text-gray-500 ml-2">2025-11-29</span></h3>
                                <p class="text-gray-600 mt-2">ClipCircle 初始版本发布。</p>
                                <ul class="list-disc list-inside mt-2 text-sm text-gray-500 space-y-1">
                                    <li>实现完整的视频上传、转码流程。</li>
                                    <li>集成 FFmpeg 和 yt-dlp。</li>
                                    <li>全新的 TikTok 风格 UI。</li>
                                    <li>现代化的管理后台。</li>
                                </ul>
                            </div>
                        </div>
                        <?php 
                // ==========================
                // 6. 语言包管理 (Languages)
                // ==========================
                case 'languages': ?>
                    <div class="flex flex-col md:flex-row gap-6 h-[calc(100vh-150px)]">
                        
                        <!-- 左侧：文件列表 -->
                        <div class="w-full md:w-64 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col shrink-0">
                            <div class="p-4 border-b border-gray-100 font-bold text-gray-700 flex justify-between items-center">
                                语言列表
                                <!-- 新建按钮 -->
                                <button onclick="document.getElementById('newLangModal').classList.remove('hidden')" class="text-xs bg-gray-100 hover:bg-gray-200 px-2 py-1 rounded">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="flex-1 overflow-y-auto p-2 space-y-1">
                                <?php foreach ($lang_list as $l): 
                                    $isEditing = ($l === $current_edit); ?>
                                    <a href="/?c=Admin&page=languages&edit=<?= $l ?>" class="block px-4 py-3 rounded-lg text-sm font-medium transition <?= $isEditing ? 'bg-pink-50 text-pink-600 border border-pink-100' : 'text-gray-600 hover:bg-gray-50' ?>">
                                        <div class="flex justify-between items-center">
                                            <span><?= $l ?></span>
                                            <?php if($isEditing): ?><i class="fas fa-edit"></i><?php endif; ?>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- 右侧：编辑器 -->
                        <div class="flex-1 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col overflow-hidden">
                            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                                <div>
                                    <h2 class="font-bold text-lg text-gray-800">正在编辑: <span class="text-pink-600 font-mono"><?= $current_edit ?>.json</span></h2>
                                    <p class="text-xs text-gray-500">修改下方的翻译内容，Key (键名) 请勿随意修改。</p>
                                </div>
                                <button form="langForm" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-bold text-sm shadow-sm transition">
                                    <i class="fas fa-save mr-2"></i> 保存更改
                                </button>
                            </div>
                            
                            <div class="flex-1 overflow-y-auto p-6 bg-slate-50">
                                <form id="langForm" action="/?c=Admin&a=saveLanguage" method="POST">
                                    <input type="hidden" name="lang_name" value="<?= $current_edit ?>">
                                    
                                    <div class="space-y-3" id="kv-container">
                                        <?php foreach ($lang_data as $key => $val): ?>
                                            <div class="flex gap-4 items-start group">
                                                <div class="w-1/3">
                                                    <input type="text" name="keys[]" value="<?= htmlspecialchars($key) ?>" readonly 
                                                           class="w-full bg-gray-200 text-gray-500 border border-gray-300 rounded px-3 py-2 text-sm font-mono cursor-not-allowed focus:outline-none">
                                                </div>
                                                <div class="flex-1 relative">
                                                    <textarea name="values[]" rows="1" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:border-pink-500 focus:ring-1 focus:ring-pink-200 outline-none transition resize-none h-[38px] overflow-hidden focus:h-24 focus:absolute focus:z-10 focus:shadow-lg"><?= htmlspecialchars($val) ?></textarea>
                                                </div>
                                                <!-- 删除按钮 (可选) -->
                                                <button type="button" onclick="this.parentElement.remove()" class="text-gray-300 hover:text-red-500 pt-2 opacity-0 group-hover:opacity-100 transition">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <!-- 添加新 Key -->
                                    <div class="mt-6 pt-6 border-t border-gray-200">
                                        <h3 class="text-sm font-bold text-gray-600 mb-3">添加新字段</h3>
                                        <div class="flex gap-4 items-start bg-yellow-50 p-4 rounded border border-yellow-100">
                                            <div class="w-1/3">
                                                <input type="text" id="new_key" placeholder="例如: home_title" class="w-full border border-gray-300 rounded px-3 py-2 text-sm font-mono">
                                            </div>
                                            <div class="flex-1">
                                                <input type="text" id="new_val" placeholder="翻译内容" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                                            </div>
                                            <button type="button" onclick="addLangKey()" class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700">
                                                添加
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- 新建语言弹窗 -->
                    <div id="newLangModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center backdrop-blur-sm">
                        <div class="bg-white p-6 rounded-xl shadow-2xl w-96">
                            <h3 class="font-bold text-lg mb-4">创建新语言包</h3>
                            <form action="/?c=Admin&a=createLanguage" method="POST">
                                <label class="block text-sm text-gray-600 mb-1">语言代码 (如 ja-JP)</label>
                                <input type="text" name="new_lang_name" class="w-full border p-2 rounded mb-4" required>
                                <div class="flex justify-end gap-2">
                                    <button type="button" onclick="document.getElementById('newLangModal').classList.add('hidden')" class="px-4 py-2 text-gray-500 hover:bg-gray-100 rounded">取消</button>
                                    <button type="submit" class="px-4 py-2 bg-pink-600 text-white rounded hover:bg-pink-700">创建</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <script>
                    function addLangKey() {
                        const key = document.getElementById('new_key').value;
                        const val = document.getElementById('new_val').value;
                        if(!key) return alert('Key 不能为空');

                        const container = document.getElementById('kv-container');
                        const div = document.createElement('div');
                        div.className = 'flex gap-4 items-start group';
                        div.innerHTML = `
                            <div class="w-1/3">
                                <input type="text" name="keys[]" value="${key}" class="w-full bg-white text-gray-800 border border-green-500 rounded px-3 py-2 text-sm font-mono">
                            </div>
                            <div class="flex-1 relative">
                                <textarea name="values[]" rows="1" class="w-full border border-green-500 rounded px-3 py-2 text-sm h-[38px]">${val}</textarea>
                            </div>
                            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 pt-2"><i class="fas fa-trash"></i></button>
                        `;
                        container.appendChild(div);
                        
                        // 清空输入
                        document.getElementById('new_key').value = '';
                        document.getElementById('new_val').value = '';
                    }
                    </script>
                <?php break; ?>
                    </div>
                <?php break; ?>

                <?php 
                // ==========================
                // 默认：功能开发中
                // ==========================
                default: ?>
                    <div class="flex flex-col items-center justify-center h-96 bg-white rounded-xl border border-dashed border-gray-300">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-3xl text-gray-400 mb-4">
                            <i class="fas fa-hammer"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">功能开发中</h2>
                        <p class="text-gray-500 mt-2">该模块 (<?= htmlspecialchars($page) ?>) 尚未实现。</p>
                    </div>
                <?php break; ?>

            <?php endswitch; ?>
        </main>
    </div>

</body>
</html>