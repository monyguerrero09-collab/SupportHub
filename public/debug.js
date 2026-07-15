<script>
document.addEventListener('DOMContentLoaded', () => {
    setInterval(() => {
        let debug = document.getElementById('debug-box');
        if (!debug) {
            debug = document.createElement('div');
            debug.id = 'debug-box';
            debug.style.position = 'fixed';
            debug.style.bottom = '10px';
            debug.style.right = '10px';
            debug.style.background = 'black';
            debug.style.color = 'lime';
            debug.style.padding = '10px';
            debug.style.zIndex = '9999';
            document.body.appendChild(debug);
        }
        let activeTab = 'unknown';
        if (window.Alpine) {
            const root = document.querySelector('[x-data]');
            if (root && root.__x) activeTab = root.__x.$data.activeTab;
        }
        const statsTab = document.getElementById('stats-json-payload');
        debug.innerHTML = `
            ActiveTab: ${activeTab}<br>
            Stats Element Exists: ${!!statsTab}<br>
        `;
    }, 1000);
});
</script>
