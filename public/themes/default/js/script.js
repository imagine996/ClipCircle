document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search-input');
    const searchPopup = document.getElementById('search-popup');
    const searchContainer = document.querySelector('.group');
    const searchBtn = searchContainer?.querySelector('.fa-search').closest('button');

    if (searchInput && searchPopup && searchContainer) {
        // 当输入框获得焦点时显示
        searchInput.addEventListener('focus', () => {
            searchPopup.classList.remove('hidden');
        });

        // 当点击搜索按钮时，如果搜索框为空则显示弹出层
        searchBtn?.addEventListener('click', (e) => {
            if (!searchInput.value.trim()) {
                e.preventDefault(); // 阻止表单提交
                searchPopup.classList.remove('hidden');
            }
        });

        // 点击页面其他地方时隐藏
        document.addEventListener('click', (e) => {
            // 如果点击的不是搜索容器，也不是弹出层本身
            if (!searchContainer.contains(e.target) && !searchPopup.contains(e.target)) {
                searchPopup.classList.add('hidden');
            }
        });

        // 点击搜索弹出层中的链接时隐藏弹出层
        searchPopup.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                searchPopup.classList.add('hidden');
            });
        });
    }
});