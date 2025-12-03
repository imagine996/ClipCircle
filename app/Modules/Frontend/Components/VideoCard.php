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
    $coverPath = htmlspecialchars($video['cover_path'] ?? '/assets/images/default-cover.png');
    
    // 转义标题防止XSS
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
