<?php

/**
 * 搜索组件
 * 用于显示搜索表单
 */
function searchBox() {
    echo "<div class='relative'>
            <input type='text' placeholder='搜索视频...' 
                   class='bg-gray-100 pl-10 pr-4 py-2 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-pink-500 w-full md:w-80 lg:w-96'>
            <i class='fas fa-search absolute left-3 top-2.5 text-gray-400'></i>
          </div>";
}

/**
 * 分类导航组件
 * 用于显示视频分类
 */
function categoryNav($categories, $current = '') {
    echo "<div class='container mx-auto mt-4 px-4 flex gap-4 text-gray-600 text-sm overflow-x-auto pb-2'>
            <a href='/' class='" . ($current === '' ? 'bg-pink-100 text-pink-600' : 'hover:bg-gray-200') . " px-4 py-1 rounded-full font-bold whitespace-nowrap'>首页</a>";
    
    foreach ($categories as $cat) {
        $active = ($current === $cat['slug']) ? 'bg-pink-100 text-pink-600' : 'hover:bg-gray-200';
        echo "<a href='/category/{$cat['slug']}' class='{$active} px-4 py-1 rounded-full whitespace-nowrap'>{$cat['name']}</a>";
    }
    
    echo "</div>";
}
