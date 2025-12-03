<div class="p-6 bg-white rounded-lg shadow">
    <h1 class="text-2xl font-bold mb-6">主题管理</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($themes as $themeKey => $theme): ?>
            <div class="border rounded-lg p-4 <?php echo $currentTheme === $themeKey ? 'border-blue-500 bg-blue-50' : 'border-gray-200'; ?>">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800"><?php echo $theme['name']; ?></h2>
                        <p class="text-sm text-gray-600 mt-1"><?php echo $theme['description']; ?></p>
                    </div>
                    <div class="flex items-center">
                        <?php if ($currentTheme === $themeKey): ?>
                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">当前使用</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="text-sm text-gray-500 mb-4">
                    <p>作者: <?php echo $theme['author']; ?></p>
                    <p>版本: <?php echo $theme['version']; ?></p>
                </div>
                
                <?php if ($currentTheme !== $themeKey): ?>
                    <form action="/admin/theme/change" method="POST">
                        <input type="hidden" name="theme" value="<?php echo $themeKey; ?>">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                            使用此主题
                        </button>
                    </form>
                <?php else: ?>
                    <button type="button" class="w-full bg-gray-200 text-gray-500 font-medium py-2 px-4 rounded-md cursor-not-allowed">
                        当前使用
                    </button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
