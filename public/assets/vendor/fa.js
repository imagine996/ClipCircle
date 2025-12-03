// Font Awesome 图标库加载脚本
// 使用 CDN 加载 Font Awesome 5.15.4

(function() {
    // 检查 Font Awesome 是否已加载
    if (document.querySelector('link[href*="font-awesome"]')) {
        return;
    }

    // 创建 link 元素加载 Font Awesome CSS
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css';
    link.integrity = 'sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==';
    link.crossOrigin = 'anonymous';
    link.referrerPolicy = 'no-referrer';
    document.head.appendChild(link);

    // 等待 Font Awesome 加载完成后初始化
    link.onload = function() {
        console.log('Font Awesome loaded successfully');
    };

    link.onerror = function() {
        console.error('Failed to load Font Awesome');
        // 加载失败时使用备用方案
        loadFallbackIcons();
    };
})();

// 备用图标方案（当 CDN 加载失败时使用）
function loadFallbackIcons() {
    // 图标映射
    const iconMap = {
        'fas fa-chart-pie': '📊',
        'fas fa-video': '🎥',
        'fas fa-broadcast-tower': '📡',
        'fas fa-users': '👥',
        'fas fa-comments': '💬',
        'fas fa-palette': '🎨',
        'fas fa-cog': '⚙️',
        'fas fa-bars': '☰',
        'fas fa-external-link-alt': '↗️',
        'fas fa-eye': '👁️',
        'fas fa-edit': '✏️',
        'fas fa-trash': '🗑️',
        'fas fa-chart-line': '📈',
        'fas fa-clock': '⏰',
        'fas fa-check-circle': '✅',
        'fas fa-exclamation-triangle': '⚠️'
    };

    // 替换图标
    const iconElements = document.querySelectorAll('.fas');
    iconElements.forEach(element => {
        const className = Array.from(element.classList).join(' ');
        if (iconMap[className]) {
            element.textContent = iconMap[className];
            element.classList.remove('fas');
            element.classList.add('fake-fa');
        }
    });

    // 添加样式
    const style = document.createElement('style');
    style.textContent = `
        .fake-fa {
            font-style: normal;
            font-size: 18px;
        }
    `;
    document.head.appendChild(style);
}