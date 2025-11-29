<!-- Cookie Consent Banner -->
<div id="cookie-banner" class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] z-[100] transform translate-y-full transition-transform duration-500 ease-in-out">
    <div class="container mx-auto px-4 py-6 md:py-4 flex flex-col md:flex-row items-center justify-between gap-4">
        
        <div class="text-sm text-gray-600 text-center md:text-left">
            <p>
                我们使用 Cookie 来改善您的体验。继续浏览即表示您同意我们的 
                <a href="/?c=Home&a=cookiePolicy" target="_blank" class="font-bold text-[#FE2C55] hover:underline">Cookie 政策</a>。
            </p>
        </div>

        <div class="flex gap-3">
            <button onclick="acceptCookies()" class="bg-[#FE2C55] text-white px-6 py-2 rounded font-bold text-sm hover:bg-[#E6284D] transition shadow-sm">
                接受所有
            </button>
            <button onclick="rejectCookies()" class="bg-gray-100 text-gray-600 px-6 py-2 rounded font-bold text-sm hover:bg-gray-200 transition">
                仅必要
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 检查用户是否已经做出过选择
        if (!localStorage.getItem('clipcircle_cookie_consent')) {
            // 延迟 1 秒显示，增加交互感
            setTimeout(() => {
                const banner = document.getElementById('cookie-banner');
                banner.classList.remove('translate-y-full');
            }, 1000);
        }
    });

    function acceptCookies() {
        // 记录同意状态
        localStorage.setItem('clipcircle_cookie_consent', 'accepted');
        hideBanner();
    }

    function rejectCookies() {
        // 记录拒绝状态（实际上我们目前没有追踪脚本，所以逻辑是一样的，只是记录一下态度）
        localStorage.setItem('clipcircle_cookie_consent', 'rejected');
        hideBanner();
    }

    function hideBanner() {
        const banner = document.getElementById('cookie-banner');
        banner.classList.add('translate-y-full');
        // 动画结束后移除 DOM (可选)
        // setTimeout(() => banner.remove(), 500);
    }
</script>