<!DOCTYPE html>
<html>
<head>
    <title>外部视频导入 - 后台管理</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- 顶部导航简写 -->
    <nav class="bg-white shadow p-4 mb-6">
        <div class="container mx-auto flex justify-between">
            <h1 class="font-bold text-xl">MyVideo 导入系统</h1>
            <a href="/?c=Admin&a=index" class="text-blue-500">返回管理面板</a>
        </div>
    </nav>

    <div class="container mx-auto bg-white p-8 rounded shadow max-w-4xl">
        <h2 class="text-2xl font-bold mb-6 border-b pb-2">从外部平台导入视频</h2>
        
        <!-- 搜索表单 -->
        <form action="/?c=Import&a=process" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="col-span-2">
                <label class="block font-bold mb-1">导入关键词 / 链接</label>
                <input type="text" name="keyword" value="<?= $keyword ?? '' ?>" placeholder="输入搜索词 或 直接粘贴 YouTube/TikTok/Bilibili 链接" class="w-full border p-2 rounded" required>
                <p class="text-xs text-gray-500 mt-1">支持来源: YouTube, Bilibili, TikTok, Twitch 等</p>
            </div>

            <div>
                <label class="block font-bold mb-1">选择分类</label>
                <select name="category" class="w-full border p-2 rounded">
                    <option value="动画" <?= ($category ?? '') == '动画' ? 'selected' : '' ?>>动画</option>
                    <option value="游戏" <?= ($category ?? '') == '游戏' ? 'selected' : '' ?>>游戏</option>
                    <option value="生活" <?= ($category ?? '') == '生活' ? 'selected' : '' ?>>生活</option>
                    <option value="搬运" <?= ($category ?? '') == '搬运' ? 'selected' : '' ?>>搬运</option>
                </select>
            </div>

            <div>
                <label class="block font-bold mb-1">导入为 (用户名)</label>
                <input type="text" name="target_user" value="<?= $target_user ?? 'admin' ?>" class="w-full border p-2 rounded" placeholder="输入现有用户的用户名">
            </div>

            <div class="col-span-2 flex items-center gap-4 border p-4 rounded bg-gray-50">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="auto_import" class="w-5 h-5">
                    <span class="font-bold text-pink-600">启用自动导入</span>
                </label>
                <p class="text-sm text-gray-500">选中后，系统将自动下载搜索到的前 3 个视频，无需手动确认。</p>
            </div>

            <div class="col-span-2">
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded hover:bg-blue-700">
                    🔍 开始搜索 / 导入
                </button>
            </div>
        </form>

        <!-- 搜索结果列表 (如果不是自动导入模式，则显示此区域) -->
        <?php if (!empty($results)): ?>
            <h3 class="font-bold text-xl mb-4">搜索结果</h3>
            <div class="space-y-4">
                <?php foreach ($results as $item): ?>
                    <div class="flex gap-4 border p-4 rounded hover:bg-gray-50 transition" id="row-<?= $item['id'] ?>">
                        <img src="<?= $item['thumbnail'] ?>" class="w-40 h-24 object-cover rounded bg-black">
                        <div class="flex-1">
                            <h4 class="font-bold text-lg line-clamp-1"><?= htmlspecialchars($item['title']) ?></h4>
                            <p class="text-sm text-gray-500 mb-2">源链接: <?= $item['url'] ?></p>
                            
                            <button onclick="importVideo('<?= $item['url'] ?>', '<?= addslashes(htmlspecialchars($item['title'])) ?>', this)" class="bg-green-500 text-white px-4 py-2 rounded text-sm hover:bg-green-600">
                                ⬇️ 立即导入
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
    function importVideo(url, title, btn) {
        // 获取表单里的当前设置
        const category = document.querySelector('select[name="category"]').value;
        const user = document.querySelector('input[name="target_user"]').value;

        // UI 状态更新
        const originalText = btn.innerText;
        btn.innerText = '正在下载并转码...';
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');

        // 发送 AJAX 请求
        const formData = new FormData();
        formData.append('url', url);
        formData.append('title', title);
        formData.append('category', category);
        formData.append('target_user', user);

        fetch('/?c=Import&a=importSelected', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if(data.status === 'success') {
                btn.innerText = '✅ 已导入';
                btn.classList.remove('bg-green-500');
                btn.classList.add('bg-gray-500');
            } else {
                alert('导入失败: ' + data.msg);
                btn.innerText = originalText;
                btn.disabled = false;
            }
        })
        .catch(err => {
            alert('请求错误，请检查网络或后台日志');
            btn.innerText = originalText;
            btn.disabled = false;
        });
    }
    </script>
</body>
</html>