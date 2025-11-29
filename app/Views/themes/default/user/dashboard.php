<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>创作中心 - ClipCircle</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= $themeUrl ?>/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-[#F8F8F8] min-h-screen font-sans text-gray-800">

    <!-- 1. 顶部导航 -->
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50 h-[60px] flex items-center shadow-sm">
        <div class="w-full max-w-[1400px] mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="/" class="flex items-center gap-1">
                    <span class="text-2xl font-extrabold tracking-tighter">
                        <span style="text-shadow: -1px -1px 0 #25F4EE;">Clip</span><span style="color: #FE2C55; text-shadow: 1px 1px 0 #25F4EE;">Circle</span>
                    </span>
                </a>
                <span class="text-gray-300 text-xl font-light">|</span>
                <span class="font-bold text-gray-600">创作中心</span>
            </div>
            
            <div class="flex items-center gap-4">
                <a href="/" class="text-sm font-bold text-gray-500 hover:text-[#FE2C55]">返回首页</a>
                <div class="w-8 h-8 rounded-full bg-gray-200 overflow-hidden">
                    <img src="<?= $user['avatar'] ?? '/uploads/default_avatar.png' ?>" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-[1200px] mx-auto mt-8 px-4 pb-20 flex flex-col md:flex-row gap-6">

        <!-- 2. 左侧侧边栏 -->
        <aside class="w-full md:w-[240px] shrink-0">
            <!-- 用户信息卡片 -->
            <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100 text-center mb-4">
                
                <!-- 头像上传区域 (表单包裹) -->
                <form id="avatarForm" action="/?c=User&a=updateAvatar" method="POST" enctype="multipart/form-data">
                    <div class="relative w-24 h-24 mx-auto mb-4 group cursor-pointer" onclick="document.getElementById('avatarInput').click()">
                        
                        <!-- 头像容器 -->
                        <div class="w-full h-full rounded-full overflow-hidden border-4 border-white shadow-lg bg-gray-200">
                            <img src="<?= $user['avatar'] ?? '/uploads/default_avatar.png' ?>" class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
                        </div>
                        
                        <!-- 悬停显示的遮罩层 -->
                        <div class="absolute inset-0 rounded-full bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                            <i class="fas fa-camera text-white text-2xl drop-shadow-md"></i>
                        </div>

                        <!-- 右下角的小编辑图标 -->
                        <div class="absolute bottom-0 right-0 bg-[#FE2C55] text-white w-7 h-7 rounded-full flex items-center justify-center border-2 border-white shadow-sm z-10">
                            <i class="fas fa-pen text-xs"></i>
                        </div>
                    </div>

                    <!-- 隐藏的文件输入框 -->
                    <input type="file" id="avatarInput" name="avatar" accept="image/*" class="hidden" onchange="uploadAvatar()">
                </form>

                <h2 class="font-bold text-lg truncate"><?= htmlspecialchars($user['username']) ?></h2>
                <p class="text-xs text-gray-400 mt-1">ID: <?= $user['id'] ?></p>
            </div>

            <!-- 菜单导航 -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <nav class="flex flex-col">
                    <a href="#" class="px-6 py-4 border-l-4 border-[#FE2C55] bg-red-50 text-[#FE2C55] font-bold text-sm flex items-center gap-3">
                        <i class="fas fa-chart-line w-4"></i> 数据概览
                    </a>
                    <a href="/?c=User&a=upload" class="px-6 py-4 border-l-4 border-transparent hover:bg-gray-50 text-gray-600 font-bold text-sm flex items-center gap-3 transition">
                        <i class="fas fa-upload w-4"></i> 发布视频
                    </a>
                    <a href="#" class="px-6 py-4 border-l-4 border-transparent hover:bg-gray-50 text-gray-600 font-bold text-sm flex items-center gap-3 transition">
                        <i class="fas fa-comment-alt w-4"></i> 评论管理
                    </a>
                    <a href="/?c=Auth&a=logout" class="px-6 py-4 border-t border-gray-100 text-gray-400 hover:text-[#FE2C55] text-sm flex items-center gap-3 transition">
                        <i class="fas fa-sign-out-alt w-4"></i> 退出登录
                    </a>
                </nav>
            </div>
        </aside>

        <!-- 3. 右侧主要内容 -->
        <main class="flex-1 min-w-0">
            
            <!-- 数据卡片 -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 text-xl">
                        <i class="fas fa-play"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-wide">总播放量</p>
                        <p class="text-2xl font-black text-gray-800"><?= $stats['views'] ?? 0 ?></p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-pink-50 flex items-center justify-center text-[#FE2C55] text-xl">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-wide">总获赞</p>
                        <p class="text-2xl font-black text-gray-800"><?= $stats['likes'] ?? 0 ?></p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center text-purple-500 text-xl">
                        <i class="fas fa-video"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-wide">稿件数量</p>
                        <p class="text-2xl font-black text-gray-800"><?= count($videos) ?></p>
                    </div>
                </div>
            </div>

            <!-- 视频列表管理 -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-lg">稿件管理</h3>
                    <a href="/?c=User&a=upload" class="bg-[#FE2C55] text-white px-4 py-2 rounded text-xs font-bold hover:bg-[#E6284D] transition">
                        + 新投稿
                    </a>
                </div>

                <?php if (empty($videos)): ?>
                    <div class="p-10 text-center text-gray-400">
                        <i class="fas fa-box-open text-4xl mb-3 text-gray-200"></i>
                        <p>还没有发布过视频</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-400 text-xs uppercase font-bold">
                                <tr>
                                    <th class="px-6 py-3">视频内容</th>
                                    <th class="px-6 py-3">数据</th>
                                    <th class="px-6 py-3">状态</th>
                                    <th class="px-6 py-3">日期</th>
                                    <th class="px-6 py-3 text-right">操作</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($videos as $v): ?>
                                <tr class="group hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <a href="/?c=Video&a=play&id=<?= $v['id'] ?>" target="_blank" class="w-24 h-14 bg-black rounded overflow-hidden relative shrink-0">
                                                <img src="<?= $v['cover_path'] ?>" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition">
                                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                                    <i class="fas fa-play text-white drop-shadow-md"></i>
                                                </div>
                                            </a>
                                            <div class="min-w-0">
                                                <a href="/?c=Video&a=play&id=<?= $v['id'] ?>" target="_blank" class="font-bold text-sm text-gray-800 hover:text-[#FE2C55] truncate block max-w-[200px]">
                                                    <?= htmlspecialchars($v['title']) ?>
                                                </a>
                                                <span class="text-xs text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded mt-1 inline-block">
                                                    <?= $v['category'] ?>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs text-gray-500">
                                            <p><i class="fas fa-play w-4"></i> <?= $v['views'] ?></p>
                                            <p class="mt-1"><i class="fas fa-heart w-4"></i> <?= $v['likes'] ?></p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($v['status'] == 'published'): ?>
                                            <span class="inline-flex items-center gap-1 text-green-600 bg-green-50 px-2 py-1 rounded text-xs font-bold">
                                                <i class="fas fa-check-circle"></i> 已发布
                                            </span>
                                        <?php elseif ($v['status'] == 'pending'): ?>
                                            <span class="inline-flex items-center gap-1 text-yellow-600 bg-yellow-50 px-2 py-1 rounded text-xs font-bold">
                                                <i class="fas fa-clock"></i> 审核中
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 text-red-600 bg-red-50 px-2 py-1 rounded text-xs font-bold">
                                                <i class="fas fa-ban"></i> 未通过
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500">
                                        <?= substr($v['created_at'], 0, 10) ?>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button class="text-gray-400 hover:text-blue-500 p-2" title="编辑 (暂未开放)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-gray-400 hover:text-red-500 p-2" title="删除 (暂未开放)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- 3. 页脚脚本 (放置于 Body 结束前) -->
    <script>
    function uploadAvatar() {
        const input = document.getElementById('avatarInput');
        if (input.files && input.files[0]) {
            // 简单的文件大小检查 (限制 2MB)
            if (input.files[0].size > 2 * 1024 * 1024) {
                alert('图片大小不能超过 2MB');
                input.value = ''; // 清空
                return;
            }
            // 自动提交表单
            document.getElementById('avatarForm').submit();
        }
    }
    </script>
</body>
</html>