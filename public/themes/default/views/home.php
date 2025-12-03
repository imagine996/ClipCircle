<?php defined('BASE_PATH') || die(); ?>

<?php
/**
 * 视频卡片组件
 * 用于显示视频信息的卡片
 */
function videoCard($video) {
    // 生成视频链接 - 转义ID防止XSS
    $videoId = htmlspecialchars($video['id'] ?? '');
    $videoUrl = "/video/{$videoId}";
    
    // 确保封面图存在 - 转义URL防止XSS
    $coverPath = htmlspecialchars($video['cover'] ?? '/assets/images/default-cover.png');
    
    // 转义标题防止XSS
    $title = htmlspecialchars($video['title'] ?? '未知标题');
    
    // 格式化视频时长
    $duration = formatDuration($video['duration'] ?? 0);
    
    // 格式化观看次数
    $views = formatNumber($video['views'] ?? 0);
    
    // 格式化上传时间
    $uploadTime = formatUploadTime($video['created_at'] ?? time());
    
    echo "<a href='{$videoUrl}' class='video-card group block'>
            <div class='aspect-video bg-gray-200 rounded-lg overflow-hidden relative shadow-sm group-hover:shadow-md transition'>
                <img src='{$coverPath}' alt='{$title}' class='w-full h-full object-cover transform group-hover:scale-105 transition duration-300'>
                <div class='absolute bottom-2 right-2 bg-black bg-opacity-60 text-white text-xs px-1 rounded'>{$duration}</div>
                <div class='absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent h-24 flex items-end p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300'>
                    <div class='text-white text-sm font-bold'>{$title}</div>
                </div>
            </div>
            <div class='mt-2'>
                <h3 class='font-bold text-gray-800 line-clamp-2 group-hover:text-pink-500 transition'>{$title}</h3>
                <div class='flex justify-between items-center mt-1 text-xs text-gray-500'>
                    <span class='flex items-center'><i class='fas fa-eye mr-1'></i> {$views}</span>
                    <span>{$uploadTime}</span>
                </div>
            </div>
          </a>";
}

/**
 * 格式化视频时长
 */
function formatDuration($seconds) {
    if ($seconds < 60) {
        return "0:{$seconds}";
    }
    $minutes = floor($seconds / 60);
    $secs = $seconds % 60;
    // 将三元运算符移出字符串，避免语法错误
    $formattedSecs = $secs < 10 ? '0' . $secs : $secs;
    return "{$minutes}:{$formattedSecs}";
}

/**
 * 格式化数字（如观看次数）
 */
function formatNumber($num) {
    if ($num < 1000) {
        return $num;
    } elseif ($num < 10000) {
        return round($num / 1000, 1) . 'k';
    } else {
        return floor($num / 1000) . 'k';
    }
}

/**
 * 格式化上传时间
 */
function formatUploadTime($timestamp) {
    $now = time();
    $diff = $now - $timestamp;
    
    if ($diff < 60) {
        return "刚刚";
    } elseif ($diff < 3600) {
        return floor($diff / 60) . "分钟前";
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . "小时前";
    } elseif ($diff < 604800) {
        return floor($diff / 86400) . "天前";
    } else {
        return date("Y-m-d", $timestamp);
    }
}
?>

<div class="max-w-[700px] mx-auto">
    <!-- 搜索结果标题 -->
    <?php if (isset($keyword) && !empty($keyword)): ?>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">搜索结果："<?= htmlspecialchars($keyword) ?>"</h2>
    </div>
    <?php endif; ?>
    
    <!-- 分类导航 -->
    <div class="overflow-x-auto whitespace-nowrap py-3 mb-6 border-b border-gray-200">
        <div class="inline-flex gap-4 px-2">
            <a href="/" class="px-3 py-1 rounded-full bg-red-500 text-white font-medium">推荐</a>
            <?php foreach($categories as $category): ?>
            <a href="/category/<?= urlencode($category) ?>" class="px-3 py-1 rounded-full bg-gray-100 text-gray-800 hover:bg-gray-200 transition font-medium"><?= htmlspecialchars($category) ?></a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 视频列表 -->
    <div class="space-y-6">
        <?php if (!empty($videos)): ?>
            <?php foreach($videos as $video): ?>
                <!-- 视频卡片 -->
                <?php videoCard($video); ?>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- 没有视频时的提示 -->
            <div class="text-center py-10">
                <div class="text-6xl text-gray-300 mb-3">🎥</div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">暂无视频</h3>
                <p class="text-gray-500 mb-4">还没有任何视频，成为第一个上传视频的人吧！</p>
                <a href="/upload" class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                    <i class="fas fa-plus"></i>
                    上传视频
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>