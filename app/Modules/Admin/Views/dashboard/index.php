<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">仪表盘</h1>
        <div class="text-sm text-gray-500">
            <span class="font-bold text-pink-600">欢迎回来，<?= $user['username'] ?></span>
        </div>
    </div>

    <!-- 统计卡片 -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">总视频数</p>
                    <p class="text-2xl font-bold text-pink-600 mt-1"><?= $stats['total_videos'] ?></p>
                </div>
                <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center">
                    <i class="fas fa-video text-pink-500"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">总用户数</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1"><?= $stats['total_users'] ?></p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-users text-blue-500"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">总播放量</p>
                    <p class="text-2xl font-bold text-green-600 mt-1"><?= $stats['total_views'] ?></p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-eye text-green-500"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">总评论数</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1"><?= $stats['total_comments'] ?></p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-comments text-purple-500"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">视频待审核</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1"><?= $stats['videos_pending'] ?></p>
                </div>
                <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-yellow-500"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 近期上传视频 -->
    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200 mb-6">
        <h2 class="text-lg font-bold mb-4">近期上传视频</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="text-left py-2 px-4 font-medium text-gray-600">视频标题</th>
                        <th class="text-left py-2 px-4 font-medium text-gray-600">上传者</th>
                        <th class="text-left py-2 px-4 font-medium text-gray-600">播放量</th>
                        <th class="text-left py-2 px-4 font-medium text-gray-600">上传时间</th>
                        <th class="text-right py-2 px-4 font-medium text-gray-600">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentVideos as $video): ?>
                    <tr class="border-b border-gray-100">
                        <td class="py-2 px-4"><a href="/admin/videos/<?= $video['id'] ?>" class="text-pink-600 hover:underline font-bold"><?= $video['title'] ?></a></td>
                        <td class="py-2 px-4"><?= $video['uploader'] ?></td>
                        <td class="py-2 px-4"><i class="fas fa-eye text-gray-400"></i> <?= $video['views'] ?></td>
                        <td class="py-2 px-4 text-sm text-gray-500"><?= $video['date'] ?></td>
                        <td class="py-2 px-4 text-right">
                            <button class="text-blue-500 hover:text-blue-700 mr-2"><i class="fas fa-edit"></i></button>
                            <button class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 数据统计图表 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <h2 class="text-lg font-bold mb-4">视频播放趋势</h2>
            <div class="h-64">
                <canvas id="viewsChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <h2 class="text-lg font-bold mb-4">用户增长趋势</h2>
            <div class="h-64">
                <canvas id="usersChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <script>
        // 播放趋势图表
        const viewsCtx = document.getElementById('viewsChart').getContext('2d');
        const viewsChart = new Chart(viewsCtx, {
            type: 'line',
            data: {
                labels: ['周一', '周二', '周三', '周四', '周五', '周六', '周日'],
                datasets: [{
                    label: '播放量',
                    data: [1200, 1900, 3000, 5000, 2780, 4300, 6000],
                    borderColor: '#e11d48',
                    backgroundColor: 'rgba(225, 29, 72, 0.1)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // 用户增长图表
        const usersCtx = document.getElementById('usersChart').getContext('2d');
        const usersChart = new Chart(usersCtx, {
            type: 'bar',
            data: {
                labels: ['周一', '周二', '周三', '周四', '周五', '周六', '周日'],
                datasets: [{
                    label: '新增用户',
                    data: [65, 59, 80, 81, 56, 55, 40],
                    backgroundColor: '#3b82f6',
                    borderColor: '#2563eb',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</div>