// 后台管理系统JavaScript文件
document.addEventListener('DOMContentLoaded', function() {
    
    // 统计卡片悬停效果
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.classList.add('shadow-md', 'transform', 'translate-y-[-2px]');
        });
        card.addEventListener('mouseleave', function() {
            this.classList.remove('shadow-md', 'transform', 'translate-y-[-2px]');
        });
    });
    
    // 表格行悬停效果
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.classList.add('bg-gray-50');
        });
        row.addEventListener('mouseleave', function() {
            this.classList.remove('bg-gray-50');
        });
    });
    
    // 按钮交互效果
    const buttons = document.querySelectorAll('button');
    buttons.forEach(button => {
        button.addEventListener('mousedown', function() {
            this.classList.add('scale-95');
        });
        button.addEventListener('mouseup', function() {
            this.classList.remove('scale-95');
        });
        button.addEventListener('mouseleave', function() {
            this.classList.remove('scale-95');
        });
    });
});
