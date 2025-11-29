<?php
namespace App\Core;

use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\TimeCode;

class Transcoder {
    private $ffmpeg;
    private $uploadDir;

    public function __construct() {
        // 初始化 FFmpeg
        // 如果是 Windows，可能需要指定路径，例如: 
        // 'ffmpeg.binaries'  => 'C:/ffmpeg/bin/ffmpeg.exe',
        // 'ffprobe.binaries' => 'C:/ffmpeg/bin/ffprobe.exe'
        $this->ffmpeg = FFMpeg::create([
            'timeout' => 3600, // 设置超时时间为 1 小时 (转码很慢)
            'ffmpeg.threads' => 2, // 使用 2 个线程
        ]);
        
        $this->uploadDir = __DIR__ . '/../../public/uploads/';
    }

    /**
     * 处理视频：转码 + 自动截图
     * @param string $tmpFile 临时文件路径
     * @return array [视频路径, 封面路径]
     */
    public function process(string $tmpFile): array {
        // 1. 打开视频
        $video = $this->ffmpeg->open($tmpFile);

        // 生成唯一文件名
        $baseName = md5(uniqid());
        $outputVideoName = $baseName . '.mp4';
        $outputCoverName = $baseName . '.jpg';

        // 2. 自动生成封面 (截取第 5 秒的画面)
        $frame = $video->frame(TimeCode::fromSeconds(5));
        $coverPath = $this->uploadDir . 'covers/' . $outputCoverName;
        $frame->save($coverPath);

        // 3. 视频转码 (压缩为 720p, H.264 编码)
        $format = new X264();
        $format->setKiloBitrate(1000); // 码率 1000kbps (平衡画质与体积)
        $format->setAudioCodec('aac'); // 音频编码

        // 调整分辨率为 1280x720 (保持比例)
        $video->filters()->resize(new Dimension(1280, 720))->synchronize();

        $videoPath = $this->uploadDir . 'videos/' . $outputVideoName;
        
        // 开始转码 (这一步最耗时)
        $video->save($format, $videoPath);

        // 返回相对路径供数据库存储
        return [
            'video_path' => '/uploads/videos/' . $outputVideoName,
            'cover_path' => '/uploads/covers/' . $outputCoverName
        ];
    }
}