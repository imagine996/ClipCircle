<!DOCTYPE html>
<html>
<head>
    <title><?= $video['title'] ?> - MyVideo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .danmaku-stage { position: absolute; top:0; left:0; width:100%; height:100%; pointer-events:none; overflow:hidden; }
        .dm-item { position: absolute; white-space: nowrap; font-weight: bold; text-shadow: 1px 1px 2px #000; animation: move 8s linear infinite; }
        @keyframes move { from { left: 100%; transform: translateX(0); } to { left: 0; transform: translateX(-100%); } }
    </style>
</head>
<body class="bg-white">
    <div class="container mx-auto mt-4">
        <!-- 播放器区域 -->
        <div class="flex gap-6">
            <div class="w-3/4">
                <div class="relative bg-black w-full aspect-video">
                    <div id="stage" class="danmaku-stage z-10"></div>
                    <video id="player" src="<?= $video['file_path'] ?>" controls class="w-full h-full z-0"></video>
                </div>
                <!-- 弹幕发送栏 -->
                <div class="bg-gray-100 p-3 flex gap-2 mt-2">
                    <input type="text" id="dm-text" placeholder="发个弹幕见证当下..." class="flex-1 p-2 border rounded">
                    <button onclick="sendDm()" class="bg-pink-500 text-white px-6 rounded">发送</button>
                </div>
                
                <h1 class="text-2xl font-bold mt-4"><?= $video['title'] ?></h1>
                <p class="text-gray-500 text-sm">UP主: <?= $video['username'] ?> | 播放: <?= $video['views'] ?> | <?= $video['created_at'] ?></p>
            </div>
            
            <!-- 推荐侧边栏 -->
            <div class="w-1/4">
                <h3 class="font-bold mb-2">相关推荐</h3>
                <div class="bg-gray-50 h-32 mb-2">推荐位 1</div>
                <div class="bg-gray-50 h-32 mb-2">推荐位 2</div>
            </div>
        </div>
    </div>

    <script>
        const vid = <?= $video['id'] ?>;
        const player = document.getElementById('player');
        const stage = document.getElementById('stage');
        let danmakus = [];

        // 1. 获取弹幕
        fetch(`/?c=Video&a=getDanmaku&vid=${vid}`)
            .then(r => r.json())
            .then(data => danmakus = data);

        // 2. 模拟弹幕引擎
        player.addEventListener('timeupdate', () => {
            const now = player.currentTime;
            danmakus.forEach(d => {
                if (!d.shown && Math.abs(d.time_point - now) < 0.5) {
                    renderDm(d.content, d.color);
                    d.shown = true;
                }
            });
        });
        
        // 进度条拖动重置
        player.addEventListener('seeked', () => {
            stage.innerHTML = '';
            danmakus.forEach(d => d.shown = false);
        });

        function renderDm(text, color) {
            const div = document.createElement('div');
            div.className = 'dm-item text-xl';
            div.style.color = color;
            div.innerText = text;
            div.style.top = Math.random() * 80 + '%';
            stage.appendChild(div);
            div.addEventListener('animationend', () => div.remove());
        }

        // 3. 发送弹幕
        function sendDm() {
            const text = document.getElementById('dm-text').value;
            if(!text) return;
            const time = player.currentTime;
            
            fetch('/?c=Video&a=sendDanmaku', {
                method: 'POST',
                body: JSON.stringify({ vid, content: text, time, color: '#fff' })
            });
            
            renderDm(text, '#fff');
            document.getElementById('dm-text').value = '';
        }
    </script>
</body>
</html>