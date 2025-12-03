// 前端主JavaScript文件
// 视频卡片交互
document.addEventListener('DOMContentLoaded', function() {
    // 视频卡片悬停效果
    const videoCards = document.querySelectorAll('.video-card');
    videoCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.classList.add('shadow-md', 'transform', 'translate-y-[-2px]');
        });
        card.addEventListener('mouseleave', function() {
            this.classList.remove('shadow-md', 'transform', 'translate-y-[-2px]');
        });
    });
    
    // 搜索框交互 - 注意：此功能已迁移到script.js，这里只保留基础的焦点样式
    const searchInput = document.querySelector('input[type="text"]');
    if (searchInput) {
        searchInput.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-pink-500');
        });
        searchInput.addEventListener('blur', function() {
            // 移除ring样式，但不影响弹出层的显示
            this.parentElement.classList.remove('ring-2', 'ring-pink-500');
        });
    }
    
    // 导航栏滚动效果
    const navbar = document.querySelector('header');
    let lastScrollTop = 0;
    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        if (scrollTop > lastScrollTop) {
            navbar.classList.add('shadow-sm');
        } else {
            navbar.classList.remove('shadow-sm');
        }
        lastScrollTop = scrollTop;
    });
});
