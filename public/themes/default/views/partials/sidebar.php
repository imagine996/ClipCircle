<div class="flex flex-col h-full px-2 py-4 pb-20">
    
    <!-- 1. 主要导航 -->
    <nav class="space-y-1 mb-4 border-b border-gray-100 pb-4">
        <?php
        $menus = [
            ['title' => '推荐', 'icon' => 'fas fa-home', 'link' => '/', 'active' => true],
            ['title' => '关注', 'icon' => 'fas fa-user-friends', 'link' => '/following', 'active' => false],
            ['title' => '探索', 'icon' => 'far fa-compass', 'link' => '/explore', 'active' => false],
            ['title' => '直播', 'icon' => 'fas fa-video', 'link' => '/live', 'active' => false],
            ['title' => '电影', 'icon' => 'fas fa-film', 'link' => '/movies', 'active' => false],
        ];
        
        foreach ($menus as $menu): 
            $activeClass = $menu['active'] ? 'text-[#FE2C55]' : 'text-gray-800 hover:bg-gray-100';
        ?>
        <a href="<?= $menu['link'] ?>" class="flex items-center gap-3 px-2 py-3 rounded-lg transition <?= $activeClass ?>">
            <span class="text-xl w-6 text-center"><i class="<?= $menu['icon'] ?>"></i></span>
            <span class="font-bold text-lg"><?= $menu['title'] ?></span>
        </a>
        <?php endforeach; ?>
    </nav>

    <!-- 2. 登录提示 (仅未登录时显示) -->
    <?php if (!isset($user) || !$user): ?>
    <div class="px-2 py-4 border-b border-gray-100">
        <p class="text-gray-400 text-sm mb-4 leading-relaxed">登录后可以关注作者，点赞视频，并查看更多评论。</p>
        <a href="/login" class="block w-full text-center border border-[#FE2C55] text-[#FE2C55] font-bold py-2.5 rounded hover:bg-[#FE2C55] hover:bg-opacity-5 transition">
            登录
        </a>
    </div>
    <?php endif; ?>

    <!-- 3. 关注列表 (仅登录时显示) -->
    <?php if (isset($user) && $user): ?>
    <div class="px-2 py-4 border-b border-gray-100">
        <h3 class="text-gray-500 text-xs font-semibold mb-3">关注的账号</h3>
        <div class="space-y-3">
            <!-- 模拟数据 loop -->
            <?php for($i=1; $i<=5; $i++): ?>
            <a href="#" class="flex items-center gap-3 hover:bg-gray-50 rounded p-1">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=User<?= $i ?>" class="w-8 h-8 rounded-full bg-gray-200">
                <div class="flex flex-col">
                    <span class="text-sm font-bold truncate w-24">创意工坊 <?= $i ?></span>
                    <span class="text-xs text-gray-400">@creator_<?= $i ?></span>
                </div>
            </a>
            <?php endfor; ?>
            
            <?php if(true): // if more than 5 ?>
            <button class="text-gray-400 text-xs mt-2 flex items-center gap-1 hover:underline">
                查看更多 <i class="fas fa-chevron-down"></i>
            </button>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- 4. 底部版权信息 (Footer) -->
    <div class="mt-auto px-2 pt-4">
        <div class="flex flex-wrap gap-x-2 gap-y-1 text-xs text-gray-400 mb-2">
            <a href="#" class="hover:underline">关于我们</a>
            <a href="#" class="hover:underline">联系方式</a>
            <a href="#" class="hover:underline">加入我们</a>
        </div>
        <div class="flex flex-wrap gap-x-2 gap-y-1 text-xs text-gray-400 mb-4">
            <a href="#" class="hover:underline">用户协议</a>
            <a href="#" class="hover:underline">隐私政策</a>
        </div>
        <div class="text-xs text-gray-300">
            &copy; 2025 ClipCircle 
        </div>
    </div>
</div>