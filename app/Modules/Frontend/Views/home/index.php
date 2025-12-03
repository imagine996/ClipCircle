<!-- 频道分类 -->
<div class="container mx-auto mt-4 px-4 flex gap-4 text-gray-600 text-sm overflow-x-auto">
    <a href="/" class="bg-pink-100 text-pink-600 px-4 py-1 rounded-full font-bold">首页</a>
    <?php foreach ($categories as $cat): ?>
        <a href="#" class="hover:bg-gray-200 px-4 py-1 rounded-full whitespace-nowrap"><?= $cat ?></a>
    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/../../Components/VideoCard.php'; ?>
<!-- 视频列表 -->
<div class="container mx-auto mt-6 px-4 pb-10">
    <h2 class="font-bold text-xl mb-4 text-gray-700">热门推荐</h2>
    <?php if(empty($videos)): ?>
        <div class="text-center py-20 text-gray-400">
            <p class="text-4xl mb-2">📹</p>
            <p>暂无视频，快去<a href="/upload" class="text-blue-500">投稿</a>吧！</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($videos as $v): ?>
                <?php videoCard($v); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>