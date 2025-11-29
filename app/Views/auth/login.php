<!DOCTYPE html>
<html>
<head>
    <title>登录 / 注册</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex justify-center items-center">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6 text-center text-pink-500">MyVideo 通行证</h2>
        
        <!-- 登录表单 -->
        <form action="/?c=Auth&a=doLogin" method="POST" id="loginForm">
            <input type="text" name="username" placeholder="用户名" class="w-full border p-2 mb-4 rounded" required>
            <input type="password" name="password" placeholder="密码" class="w-full border p-2 mb-4 rounded" required>
            <button class="w-full bg-pink-500 text-white py-2 rounded font-bold">登录</button>
            <p class="text-center mt-4 text-sm text-gray-500 cursor-pointer" onclick="toggle()">没有账号？去注册</p>
        </form>

        <!-- 注册表单 (默认隐藏) -->
        <form action="/?c=Auth&a=doRegister" method="POST" id="regForm" class="hidden">
            <input type="text" name="username" placeholder="设置用户名" class="w-full border p-2 mb-4 rounded" required>
            <input type="password" name="password" placeholder="设置密码" class="w-full border p-2 mb-4 rounded" required>
            <button class="w-full bg-blue-500 text-white py-2 rounded font-bold">注册新账号</button>
            <p class="text-center mt-4 text-sm text-gray-500 cursor-pointer" onclick="toggle()">已有账号？去登录</p>
        </form>
    </div>

    <script>
        function toggle() {
            document.getElementById('loginForm').classList.toggle('hidden');
            document.getElementById('regForm').classList.toggle('hidden');
        }
    </script>
</body>
</html>