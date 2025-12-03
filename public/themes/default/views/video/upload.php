<div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-6">
    <h1 class="text-2xl font-bold mb-6">上传视频</h1>
    
    <?php if (isset($error)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p><?php echo htmlspecialchars($error); ?></p>
        </div>
    <?php endif; ?>
    
    <form id="uploadForm" class="space-y-6">
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">视频标题</label>
            <input type="text" id="title" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent">
        </div>
        
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">视频描述</label>
            <textarea id="description" name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"></textarea>
        </div>
        
        <div>
            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">视频分类</label>
            <select id="category_id" name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                <option value="1">生活</option>
                <option value="2">娱乐</option>
                <option value="3">科技</option>
                <option value="4">教育</option>
                <option value="5">游戏</option>
            </select>
        </div>
        
        <div>
            <label for="video" class="block text-sm font-medium text-gray-700 mb-1">视频文件</label>
            <input type="file" id="video" name="video" accept="video/*" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100">
            <p class="text-xs text-gray-500 mt-1">支持的格式：MP4、AVI、FLV、WMV（最大50MB）</p>
        </div>
        
        <div>
            <label for="cover" class="block text-sm font-medium text-gray-700 mb-1">封面图片</label>
            <input type="file" id="cover" name="cover" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
            <p class="text-xs text-gray-500 mt-1">支持的格式：JPG、PNG、GIF（最大5MB）</p>
        </div>
        
        <!-- 上传进度条 -->
        <div id="progressContainer" class="hidden">
            <div class="text-sm font-medium text-gray-700 mb-1">上传进度</div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div id="progressBar" class="bg-pink-500 h-2.5 rounded-full" style="width: 0%"></div>
            </div>
            <div id="progressText" class="text-xs text-gray-500 mt-1">0%</div>
        </div>
        
        <div class="flex justify-end gap-3">
            <a href="/" class="px-6 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-100 transition">取消</a>
            <button type="button" id="uploadButton" class="px-6 py-2 bg-pink-500 text-white rounded-full hover:bg-pink-600 transition">上传视频</button>
        </div>
    </form>
    
    <!-- 上传进度条脚本 -->
    <script>
        document.getElementById('uploadButton').addEventListener('click', function() {
            const form = document.getElementById('uploadForm');
            const formData = new FormData(form);
            const progressContainer = document.getElementById('progressContainer');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');
            const uploadButton = this;
            
            // 显示进度条
            progressContainer.classList.remove('hidden');
            // 禁用上传按钮
            uploadButton.disabled = true;
            uploadButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> 上传中...';
            
            // 创建XMLHttpRequest
            const xhr = new XMLHttpRequest();
            
            // 设置超时时间（30秒）
            xhr.timeout = 30000;
            
            // 监听上传进度
            xhr.upload.addEventListener('progress', function(event) {
                if (event.lengthComputable) {
                    const percentComplete = Math.round((event.loaded / event.total) * 100);
                    progressBar.style.width = percentComplete + '%';
                    progressText.textContent = percentComplete + '%';
                }
            });
            
            // 监听上传完成
            xhr.addEventListener('load', function() {
                if (xhr.status === 200) {
                    try {
                        // 上传成功，处理响应
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            window.location.href = '/video/' + response.video_id;
                        } else {
                            // 显示错误信息
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6';
                            errorDiv.innerHTML = '<p>' + response.message + '</p>';
                            form.insertBefore(errorDiv, form.firstChild);
                            // 重置进度条
                            progressContainer.classList.add('hidden');
                            progressBar.style.width = '0%';
                            progressText.textContent = '0%';
                            // 启用上传按钮
                            uploadButton.disabled = false;
                            uploadButton.innerHTML = '上传视频';
                        }
                    } catch (e) {
                        // 处理JSON解析错误
                        alert('服务器响应错误，请稍后重试');
                        // 重置进度条
                        progressContainer.classList.add('hidden');
                        progressBar.style.width = '0%';
                        progressText.textContent = '0%';
                        // 启用上传按钮
                        uploadButton.disabled = false;
                        uploadButton.innerHTML = '上传视频';
                    }
                } else {
                    // 上传失败
                    alert('上传失败，状态码：' + xhr.status + '，请稍后重试');
                    // 重置进度条
                    progressContainer.classList.add('hidden');
                    progressBar.style.width = '0%';
                    progressText.textContent = '0%';
                    // 启用上传按钮
                    uploadButton.disabled = false;
                    uploadButton.innerHTML = '上传视频';
                }
            });
            
            // 监听上传错误
            xhr.addEventListener('error', function() {
                alert('上传出错，请检查网络连接后重试');
                // 重置进度条
                progressContainer.classList.add('hidden');
                progressBar.style.width = '0%';
                progressText.textContent = '0%';
                // 启用上传按钮
                uploadButton.disabled = false;
                uploadButton.innerHTML = '上传视频';
            });
            
            // 监听超时
            xhr.addEventListener('timeout', function() {
                alert('上传超时，请稍后重试');
                // 重置进度条
                progressContainer.classList.add('hidden');
                progressBar.style.width = '0%';
                progressText.textContent = '0%';
                // 启用上传按钮
                uploadButton.disabled = false;
                uploadButton.innerHTML = '上传视频';
            });
            
            // 发送请求
            xhr.open('POST', '/do_upload');
            xhr.send(formData);
        });
    </script>
</div>