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
        <?php if ($is_login): // 如果用户信息存在，则视为已登录 ?>
            <!-- 已登录状态 -->
            <!-- 上传按钮 -->
            <a href="/upload" class="flex items-center gap-2 bg-[#FE2C55] text-white px-4 py-2 rounded-full text-sm font-medium hover:bg-opacity-90 transition-all transform hover:scale-105 shadow-sm">
                <i class="fas fa-upload"></i>
                <span class="hidden sm:inline">上传</span>
            </a>
            <div class="relative cursor-pointer group">
                <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-full transition-colors">
                    <i class="far fa-paper-plane text-xl"></i>
                </button>
            </div>
            <!-- 用户菜单 -->
            <?php 
                // 直接实现用户菜单功能
                if ($user) {
                    $username = $user['username'];
                    $avatar = $user['avatar'] ?? 'https://api.dicebear.com/7.x/avataaars/svg?seed=Felix';
                    
                    echo '<div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <div class="flex items-center gap-2 cursor-pointer" @click="open = !open">
                                <img src="' . $avatar . '" class="w-9 h-9 rounded-full border-2 border-gray-200 hover:border-[#FE2C55] transition-all transform hover:scale-110">
                                <span class="font-bold text-sm">' . $username . '</span>
                                <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200" :class="{ \'rotate-180\': open }"></i>
                            </div>
                            <!-- 下拉菜单 -->
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 transition-all duration-200 transform origin-top-right" :class="{ \'opacity-100 visible scale-100\': open, \'opacity-0 invisible scale-95\': !open }">
                                <a href="/profile" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-pink-100 hover:text-pink-600 transition">
                                    <i class="fas fa-user-circle w-5 mr-2"></i>
                                    <span>个人主页</span>
                                </a>
                                <a href="/admin" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-pink-100 hover:text-pink-600 transition">
                                    <i class="fas fa-cog w-5 mr-2"></i>
                                    <span>后台管理</span>
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <a href="/logout" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                    <i class="fas fa-sign-out-alt w-5 mr-2"></i>
                                    <span>退出</span>
                                </a>
                            </div>
                          </div>';
                } else {
                    echo '<div class="flex items-center gap-3">
                            <a href="/login" class="text-sm font-bold text-gray-600 hover:text-pink-500 transition">登录</a>
                            <a href="/register" class="text-sm font-bold bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 transition">注册</a>
                          </div>';
                }
            ?>
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