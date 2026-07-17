/*
|--------------------------------------------------------------------------
| layout-demo / app.js — SHARED site JavaScript (real-world pattern)
|--------------------------------------------------------------------------
|
| WHY a separate JS file?
|   - Shared behavior (theme toggle, flash dismiss, mobile menu, etc.)
|   - Cached by browser like CSS
|   - Layout loads it once; every child page gets it automatically
|
| Loaded in layout with:
|   <script src="{{ asset('js/layout-demo/app.js') }}" defer></script>
|
| defer = run after HTML is parsed (safer than blocking scripts in <head>)
|
*/

document.addEventListener('DOMContentLoaded', function () {
    // 1) Live clock in the header (shows JS running from shared file)
    const clock = document.getElementById('live-clock');
    if (clock) {
        const tick = () => {
            clock.textContent = new Date().toLocaleTimeString();
        };
        tick();
        setInterval(tick, 1000);
    }

    // 2) Dismiss flash messages
    document.querySelectorAll('[data-dismiss-flash]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const box = btn.closest('.flash');
            if (box) box.remove();
        });
    });

    // 3) Simple "like" counter demo (client-side only)
    const likeBtn = document.getElementById('like-btn');
    const likeCount = document.getElementById('like-count');
    if (likeBtn && likeCount) {
        let count = Number(likeCount.textContent) || 0;
        likeBtn.addEventListener('click', function () {
            count += 1;
            likeCount.textContent = String(count);
            likeBtn.textContent = 'Liked ✓';
        });
    }

    console.log('[layout-demo] app.js loaded — shared JS is working');
});
