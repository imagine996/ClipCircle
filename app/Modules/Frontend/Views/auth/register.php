<!-- 注册表单 -->
<div class="max-w-md mx-auto mt-10 bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-700">用户注册</h2>
    
    <?php if (isset($error)): ?>
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-times text-red-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700"><?php echo $error; ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <form action="/register" method="post">
        <div class="mb-4">
            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">用户名</label>
            <input type="text" id="username" name="username" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent" required>
        </div>
        
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">邮箱</label>
            <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent" required>
        </div>
        
        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">密码</label>
            <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent" required>
        </div>
        
        <div class="mb-6">
            <input type="checkbox" id="terms" name="terms" class="h-4 w-4 text-pink-500 focus:ring-pink-500 border-gray-300 rounded" required>
            <label for="terms" class="ml-2 block text-sm text-gray-700">
                我已阅读并同意<a href="/terms" class="text-pink-500 hover:text-pink-600">服务条款</a>和<a href="/privacy" class="text-pink-500 hover:text-pink-600">隐私政策</a>
            </label>
        </div>
        
        <div class="flex gap-3">
            <button type="submit" class="flex-1 py-2 px-4 bg-pink-500 text-white font-medium rounded-md hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 transition">注册</button>
            <a href="/login" class="flex-1 py-2 px-4 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 transition text-center">登录</a>
        </div>
    </form>
</div>