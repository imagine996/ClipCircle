<?php
// 确保视频数据存在
if (!isset($video) || !is_array($video)) {
    echo '<div class="text-center py-20 text-gray-500">视频数据加载失败</div>';
    return;
}

// 转义输出防止XSS攻击
$videoId = htmlspecialchars($video['id'] ?? '');
$title = htmlspecialchars($video['title'] ?? '未知标题');
$description = htmlspecialchars($video['description'] ?? '');
$videoPath = htmlspecialchars($video['video_path'] ?? '');
$coverPath = htmlspecialchars($video['cover_path'] ?? '/assets/images/default-cover.png');
$views = htmlspecialchars(formatNumber($video['views'] ?? 0));
$uploadTime = htmlspecialchars(formatUploadTime(strtotime($video['upload_time'] ?? time())));
$userId = htmlspecialchars($video['user_id'] ?? '');
$userName = htmlspecialchars($video['user_name'] ?? '未知用户');
$userAvatar = htmlspecialchars($video['user_avatar'] ?? '/assets/images/default-avatar.png');
$category = htmlspecialchars($video['category'] ?? '未分类');
?>

<div class="container mx-auto max-w-6xl">
    <!-- 视频播放区域 -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <!-- 视频播放器 -->
        <div class="aspect-video bg-black rounded-md overflow-hidden mb-4">
            <video 
                src="<?= $videoPath ?>" 
                controls 
                class="w-full h-full object-contain"
                poster="<?= $coverPath ?>"
                playsinline
                autoplay
            >
                您的浏览器不支持视频播放
            </video>
        </div>
        
        <!-- 视频信息 -->
        <div class="space-y-3">
            <h1 class="text-2xl font-bold text-gray-800"><?= $title ?></h1>
            
            <div class="flex justify-between items-center text-sm text-gray-600">
                <div class="flex items-center space-x-6">
                    <span class="flex items-center"><i class="fas fa-eye mr-1"></i> <?= $views ?> 次观看</span>
                    <span><i class="fas fa-calendar-alt mr-1"></i> <?= $uploadTime ?></span>
                    <span><i class="fas fa-tag mr-1"></i> <?= $category ?></span>
                </div>
                
                <!-- 操作按钮 -->
                <div class="flex space-x-4">
                    <button class="flex items-center text-gray-600 hover:text-pink-500 transition">
                        <i class="fas fa-heart mr-1"></i> <span>点赞</span>
                    </button>
                    <button class="flex items-center text-gray-600 hover:text-blue-500 transition">
                        <i class="fas fa-share mr-1"></i> <span>分享</span>
                    </button>
                    <button class="flex items-center text-gray-600 hover:text-orange-500 transition">
                        <i class="fas fa-bookmark mr-1"></i> <span>收藏</span>
                    </button>
                </div>
            </div>
            
            <!-- 视频描述 -->
            <?php if (!empty($description)): ?>
                <div class="border-t border-gray-100 pt-3">
                    <h3 class="font-bold text-gray-800 mb-1">视频描述</h3>
                    <p class="text-gray-600 whitespace-pre-wrap"><?= $description ?></p>
                </div>
            <?php endif; ?>
            
            <!-- 用户信息 -->
            <div class="border-t border-gray-100 pt-3 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-200">
                        <img src="<?= $userAvatar ?>" alt="<?= $userName ?>" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <div class="font-bold text-gray-800"><?= $userName ?></div>
                        <div class="text-xs text-gray-500">用户ID: <?= $userId ?></div>
                    </div>
                </div>
                <button class="bg-pink-500 text-white px-4 py-1 rounded-full text-sm hover:bg-pink-600 transition">
                    关注
                </button>
            </div>
        </div>
    </div>
    
    <!-- 相关推荐视频 -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">相关推荐</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php
            // 模拟相关推荐数据 - 实际应该从数据库获取
            // 这里直接使用一个空数组，避免出现错误
            $relatedVideos = [];
            
            if (empty($relatedVideos)) {
                echo '<div class="col-span-full text-center py-10 text-gray-500">暂无相关推荐</div>';
            } else {
                foreach ($relatedVideos as $relatedVideo) {
                    // 复用视频卡片组件
                    videoCard($relatedVideo);
                }
            }
            ?>
        </div>
    </div>
</div>

<!-- 格式化工具函数 -->
<?php
function formatNumber($num) {
    if ($num < 1000) {
        return $num;
    } elseif ($num < 10000) {
        return round($num / 1000, 1) . 'k';
    } else {
        return floor($num / 1000) . 'k';
    }
}

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

function videoCard($video) {
    // 生成视频链接
    $videoId = htmlspecialchars($video['id'] ?? '');
    $videoUrl = "/video/{$videoId}";
    
    // 确保封面图存在
    $coverPath = htmlspecialchars($video['cover_path'] ?? '/assets/images/default-cover.png');
    
    // 转义标题
    $title = htmlspecialchars($video['title'] ?? '未知标题');
    
    // 格式化视频时长
    $duration = formatDuration($video['duration'] ?? 0);
    
    // 格式化观看次数
    $views = formatNumber($video['views'] ?? 0);
    
    // 格式化上传时间
    $uploadTime = formatUploadTime($video['upload_time'] ?? time());
    
    echo "<a href='{$videoUrl}' class='video-card group block'>
            <div class='aspect-video bg-gray-200 rounded-lg overflow-hidden relative shadow-sm group-hover:shadow-md transition'>
                <img src='{$coverPath}' alt='{$title}' class='w-full h-full object-cover transform group-hover:scale-105 transition duration-300'>
                <div class='absolute bottom-2 right-2 bg-black bg-opacity-60 text-white text-xs px-1 rounded'>{$duration}</div>
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

function formatDuration($seconds) {
    if ($seconds < 60) {
        return "0:{$seconds}";
    }
    $minutes = floor($seconds / 60);
    $secs = $seconds % 60;
    $formattedSecs = $secs < 10 ? '0' . $secs : $secs;
    return "{$minutes}:{$formattedSecs}";
}
?>