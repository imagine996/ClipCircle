<?php
namespace App\Core;

class VideoGrabber {
    private $binPath;

    public function __construct() {
        // Linux 通常是 'yt-dlp'，Windows 可能是 'yt-dlp.exe' 或绝对路径
        // 请根据你的环境修改这里
        $this->binPath = 'yt-dlp'; 
    }

    /**
     * 搜索视频 (目前主要利用 YouTube 搜索接口，因为它最开放)
     */
    public function search(string $keyword, int $limit = 5): array {
        // 构建命令：搜索 x 个视频，不下载，只打印 JSON 元数据
        // --flat-playlist: 加快搜索速度，不深入解析
        $cmd = sprintf(
            '%s "ytsearch%d:%s" --dump-json --flat-playlist --no-warnings',
            $this->binPath,
            $limit,
            escapeshellarg($keyword)
        );

        $output = [];
        exec($cmd, $output); // 执行命令

        $results = [];
        foreach ($output as $line) {
            $data = json_decode($line, true);
            if ($data) {
                $results[] = [
                    'id' => $data['id'],
                    'title' => $data['title'],
                    'url' => $data['url'] ?? "https://www.youtube.com/watch?v=" . $data['id'],
                    'duration' => $data['duration'] ?? 0,
                    'thumbnail' => "https://i.ytimg.com/vi/{$data['id']}/hqdefault.jpg" // 预估封面
                ];
            }
        }
        return $results;
    }

    /**
     * 下载视频到临时目录
     */
    public function download(string $url, string $saveDir): ?string {
        $fileName = time() . '_' . md5($url) . '.mp4';
        $outputPath = $saveDir . $fileName;

        // 命令：下载并合并最佳画质+音质，输出为 mp4
        $cmd = sprintf(
            '%s %s -f "bestvideo[ext=mp4]+bestaudio[ext=m4a]/best[ext=mp4]/best" -o %s --no-warnings',
            $this->binPath,
            escapeshellarg($url),
            escapeshellarg($outputPath)
        );

        // 设置较长的超时时间，因为下载很慢
        set_time_limit(300); 
        exec($cmd, $output, $returnVar);

        if ($returnVar === 0 && file_exists($outputPath)) {
            return $outputPath;
        }
        return null;
    }
}