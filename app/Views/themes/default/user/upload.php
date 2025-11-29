<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>创作中心 - 视频投稿</title>
    <!-- 引入 Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- 引入主题 CSS (假设你按上一步设置了) -->
    <link rel="stylesheet" href="<?= $themeUrl ?>/css/style.css">
    <style>
        /* 自定义虚线边框动画 */
        .upload-zone {
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='16' ry='16' stroke='%23CBD5E1FF' stroke-width='2' stroke-dasharray='12%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
            transition: all 0.3s ease;
        }
        .upload-zone:hover, .upload-zone.dragover {
            background-color: #fdf2f8; /* pink-50 */
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='16' ry='16' stroke='%23EC4899FF' stroke-width='2' stroke-dasharray='12%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
        }
        /* 加载动画 */
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #ec4899;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <!-- 顶部极简导航 -->
    <nav class="bg-white border-b px-6 py-3 flex justify-between items-center sticky top-0 z-40">
        <div class="flex items-center gap-4">
            <a href="/" class="text-2xl font-bold text-pink-500 hover:opacity-80 transition">ClipCircle</a>
            <span class="text-gray-300 text-xl">|</span>
            <span class="font-bold text-gray-700">创作中心</span>
        </div>
        <div class="flex items-center gap-4 text-sm">
            <a href="/?c=User&a=dashboard" class="text-gray-500 hover:text-pink-500">返回仪表盘</a>
            <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center text-pink-500 font-bold">
                <?= mb_substr($_SESSION['user']['username'] ?? 'U', 0, 1) ?>
            </div>
        </div>
    </nav>

    <!-- 主内容区 -->
    <div class="max-w-5xl mx-auto mt-8 px-4 pb-20">
        
        <form id="uploadForm" action="/?c=User&a=doUpload" method="POST" enctype="multipart/form-data" onsubmit="return showUploading()">
            
            <div class="flex flex-col lg:flex-row gap-8">
                
                <!-- 左侧：核心信息 -->
                <div class="flex-1 space-y-6">
                    <!-- 标题区域 -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                            <span class="w-1 h-5 bg-pink-500 rounded-full"></span> 
                            基本信息
                        </h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">视频标题 <span class="text-red-500">*</span></label>
                                <input type="text" name="title" required 
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-pink-500 focus:ring-2 focus:ring-pink-100 outline-none transition"
                                       placeholder="取个吸引人的标题吧 (建议30字以内)">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">所属分区 <span class="text-red-500">*</span></label>
                                <select name="category" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-pink-500 outline-none transition">
                                    <option value="生活">生活</option>
                                    <option value="游戏">游戏</option>
                                    <option value="动画">动画</option>
                                    <option value="科技">科技</option>
                                    <option value="音乐">音乐</option>
                                    <option value="影视">影视</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">简介 (选填)</label>
                                <textarea name="description" rows="4" 
                                          class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-pink-500 focus:ring-2 focus:ring-pink-100 outline-none transition"
                                          placeholder="介绍一下你的视频..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- 投稿须知 -->
                    <div class="bg-blue-50 p-4 rounded-xl text-sm text-blue-800 border border-blue-100">
                        <p class="font-bold mb-1">📝 投稿须知：</p>
                        <ul class="list-disc list-inside space-y-1 opacity-80">
                            <li>请遵守社区公约，严禁上传色情、暴力、反动内容。</li>
                            <li>单个文件建议不超过 500MB，支持 MP4, AVI, MOV 格式。</li>
                            <li>上传后系统将自动进行转码，请耐心等待。</li>
                        </ul>
                    </div>
                </div>

                <!-- 右侧：文件上传区 -->
                <div class="w-full lg:w-96 space-y-6">
                    
                    <!-- 1. 视频上传卡片 -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <h2 class="text-lg font-bold mb-4">视频源文件</h2>
                        
                        <!-- 拖拽上传区 -->
                        <div class="relative w-full h-48 rounded-2xl upload-zone flex flex-col items-center justify-center cursor-pointer overflow-hidden group"
                             onclick="document.getElementById('videoInput').click()"
                             ondragover="event.preventDefault(); this.classList.add('dragover');"
                             ondragleave="this.classList.remove('dragover');"
                             ondrop="handleVideoDrop(event)">
                            
                            <!-- 默认显示 -->
                            <div id="videoPlaceholder" class="text-center p-4 transition group-hover:scale-105">
                                <div class="w-12 h-12 bg-pink-100 text-pink-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                </div>
                                <p class="text-sm font-bold text-gray-600">点击或拖拽上传</p>
                                <p class="text-xs text-gray-400 mt-1">支持 MP4, MKV 等</p>
                            </div>

                            <!-- 预览显示 (默认隐藏) -->
                            <video id="videoPreview" class="absolute inset-0 w-full h-full object-cover hidden bg-black" controls></video>
                            
                            <input type="file" name="video" id="videoInput" accept="video/*" required class="hidden" onchange="handleVideoSelect(this)">
                        </div>
                        <p id="videoFileName" class="text-xs text-center mt-2 text-gray-500 truncate h-4"></p>
                    </div>

                    <!-- 2. 封面上传卡片 -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <h2 class="text-lg font-bold mb-4 flex justify-between">
                            封面设置
                            <span class="text-xs font-normal text-gray-400 bg-gray-100 px-2 py-1 rounded">可选</span>
                        </h2>
                        
                        <div class="relative w-full h-32 rounded-xl bg-gray-50 border-2 border-dashed border-gray-300 flex items-center justify-center cursor-pointer overflow-hidden hover:border-pink-300 transition"
                             onclick="document.getElementById('coverInput').click()">
                            
                            <div id="coverPlaceholder" class="text-center text-gray-400">
                                <span class="text-2xl">🖼️</span>
                                <p class="text-xs mt-1">上传封面</p>
                            </div>
                            
                            <img id="coverPreview" class="absolute inset-0 w-full h-full object-cover hidden">
                            <input type="file" name="cover" id="coverInput" accept="image/*" class="hidden" onchange="handleCoverSelect(this)">
                        </div>
                        <p class="text-xs text-gray-400 mt-2 text-center">如果不上传，将自动截取视频画面</p>
                    </div>

                    <!-- 3. 发布按钮 -->
                    <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-rose-500 text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-pink-500/30 hover:-translate-y-1 transition transform active:scale-95">
                        🚀 立即发布
                    </button>

                </div>
            </div>
        </form>
    </div>

    <!-- 全屏加载遮罩 (默认隐藏) -->
    <div id="loadingOverlay" class="fixed inset-0 bg-white/90 z-50 hidden flex-col items-center justify-center backdrop-blur-sm">
        <div class="loader mb-4"></div>
        <h3 class="text-xl font-bold text-gray-800">正在上传并处理视频...</h3>
        <p class="text-gray-500 mt-2">根据视频大小，可能需要几分钟</p>
        <p class="text-gray-400 text-sm mt-1">请勿关闭本页面</p>
    </div>

    <!-- JS 交互逻辑 -->
    <script>
        // 视频文件选择处理
        function handleVideoSelect(input) {
            const file = input.files[0];
            if (file) {
                // 显示文件名
                document.getElementById('videoFileName').textContent = file.name;
                
                // 生成预览 URL
                const url = URL.createObjectURL(file);
                const videoPreview = document.getElementById('videoPreview');
                const placeholder = document.getElementById('videoPlaceholder');
                
                videoPreview.src = url;
                videoPreview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
        }

        // 视频拖拽处理
        function handleVideoDrop(e) {
            e.preventDefault();
            e.stopPropagation();
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0 && files[0].type.startsWith('video/')) {
                document.getElementById('videoInput').files = files;
                handleVideoSelect(document.getElementById('videoInput'));
            }
        }

        // 封面选择处理
        function handleCoverSelect(input) {
            const file = input.files[0];
            if (file) {
                const url = URL.createObjectURL(file);
                const img = document.getElementById('coverPreview');
                const placeholder = document.getElementById('coverPlaceholder');
                
                img.src = url;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
        }

        // 表单提交时的动画
        function showUploading() {
            // 简单校验
            const video = document.getElementById('videoInput').files[0];
            if (!video) {
                alert('请先选择视频文件！');
                return false;
            }
            
            document.getElementById('loadingOverlay').classList.remove('hidden');
            document.getElementById('loadingOverlay').classList.add('flex');
            return true;
        }
    </script>
</body>
</html>