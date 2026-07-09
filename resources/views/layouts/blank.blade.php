<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="SupportHub - Plataforma de gestión de tickets IT">
    <title>SupportHub Central Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- ApexCharts (Local) -->
    <script src="{{ asset('apexcharts.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* =====================================================
           GALAXY BACKGROUND SYSTEM — Professional Dark Space
           ===================================================== */
        :root {
            color-scheme: dark;
            --galaxy-deep: #010108;
            --galaxy-mid: #06051a;
            --nebula-purple: #2d1b69;
            --nebula-blue: #0a1e4a;
            --nebula-teal: #0a2a2a;
            --star-color: #e8f0ff;
        }

        *, *::before, *::after { box-sizing: border-box; }

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            min-height: 100vh;
            overflow-x: hidden;
        }

        body {
            font-family: 'Inter', 'Figtree', sans-serif;
            background-color: var(--galaxy-deep);
            color: #d1d5db;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* --- Galaxy Canvas --- */
        #galaxy-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        /* Deep space gradient base */
        .galaxy-gradient {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 120% 80% at 10% 20%, rgba(45,27,105,0.55) 0%, transparent 55%),
                radial-gradient(ellipse 90% 70% at 90% 80%, rgba(10,30,74,0.7) 0%, transparent 55%),
                radial-gradient(ellipse 70% 60% at 50% 50%, rgba(10,42,42,0.3) 0%, transparent 60%),
                radial-gradient(ellipse 100% 100% at 80% 10%, rgba(20,10,60,0.4) 0%, transparent 50%),
                linear-gradient(135deg, #010108 0%, #06051a 30%, #030312 60%, #010108 100%);
        }

        /* Animated nebula blobs */
        .nebula {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            mix-blend-mode: screen;
            animation: nebulaDrift 25s ease-in-out infinite alternate;
        }
        .nebula-1 {
            width: 70vw;
            height: 70vh;
            top: -20vh;
            left: -20vw;
            background: radial-gradient(circle, rgba(88,40,200,0.25) 0%, rgba(40,20,120,0.15) 40%, transparent 70%);
            animation-duration: 30s;
            animation-delay: 0s;
        }
        .nebula-2 {
            width: 60vw;
            height: 60vh;
            bottom: -15vh;
            right: -10vw;
            background: radial-gradient(circle, rgba(14,60,160,0.3) 0%, rgba(10,30,100,0.15) 40%, transparent 70%);
            animation-duration: 22s;
            animation-delay: -8s;
        }
        .nebula-3 {
            width: 45vw;
            height: 45vh;
            top: 30vh;
            left: 35vw;
            background: radial-gradient(circle, rgba(80,20,100,0.2) 0%, rgba(40,10,60,0.1) 50%, transparent 70%);
            animation-duration: 35s;
            animation-delay: -15s;
        }
        .nebula-4 {
            width: 30vw;
            height: 30vh;
            top: 60vh;
            left: 10vw;
            background: radial-gradient(circle, rgba(0,100,150,0.15) 0%, transparent 70%);
            animation-duration: 28s;
            animation-delay: -5s;
        }

        @keyframes nebulaDrift {
            0%   { transform: translate(0, 0) scale(1); opacity: 0.7; }
            33%  { transform: translate(3vw, -2vh) scale(1.05); opacity: 0.9; }
            66%  { transform: translate(-2vw, 3vh) scale(0.97); opacity: 0.75; }
            100% { transform: translate(2vw, -1vh) scale(1.02); opacity: 0.85; }
        }

        /* --- Star Layers --- */
        .star-field {
            position: absolute;
            inset: 0;
        }
        .star-field-1 {
            background-image: radial-gradient(1px 1px at var(--x,50%) var(--y,50%), rgba(255,255,255,0.9) 0%, transparent 100%);
            background-size: 400px 400px;
            background-position: 0 0;
            animation: starTwinkle1 8s ease-in-out infinite alternate;
        }
        .star-field-2 {
            background-image: radial-gradient(1.5px 1.5px at 20% 30%, rgba(255,255,255,0.8) 0%, transparent 100%),
                              radial-gradient(1px 1px at 70% 60%, rgba(200,220,255,0.7) 0%, transparent 100%),
                              radial-gradient(1px 1px at 40% 80%, rgba(255,255,255,0.6) 0%, transparent 100%);
            background-size: 300px 300px, 250px 250px, 200px 200px;
            opacity: 0.5;
            animation: starTwinkle2 12s ease-in-out infinite alternate;
        }

        /* Static star canvas rendered via JS */
        #star-canvas {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
        }

        @keyframes starTwinkle1 {
            0%   { opacity: 0.4; }
            50%  { opacity: 0.7; }
            100% { opacity: 0.5; }
        }
        @keyframes starTwinkle2 {
            0%   { opacity: 0.3; }
            50%  { opacity: 0.6; }
            100% { opacity: 0.4; }
        }

        /* Shooting stars */
        .shooting-star {
            position: absolute;
            height: 1px;
            background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.8) 50%, rgba(255,255,255,0) 100%);
            border-radius: 50%;
            animation: shoot linear infinite;
            opacity: 0;
        }
        @keyframes shoot {
            0%   { transform: translateX(0) translateY(0) rotate(-30deg); opacity: 0; width: 0; }
            5%   { opacity: 1; width: 200px; }
            50%  { opacity: 0.5; width: 200px; }
            100% { transform: translateX(80vw) translateY(30vh) rotate(-30deg); opacity: 0; width: 0; }
        }

        /* Cosmic dust overlay */
        .cosmic-dust {
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(1px 1px at 15% 25%, rgba(255,255,255,0.5) 0%, transparent 100%),
                radial-gradient(1px 1px at 85% 15%, rgba(180,200,255,0.4) 0%, transparent 100%),
                radial-gradient(1px 1px at 45% 65%, rgba(255,255,255,0.3) 0%, transparent 100%),
                radial-gradient(1px 1px at 75% 85%, rgba(200,230,255,0.4) 0%, transparent 100%),
                radial-gradient(1px 1px at 30% 90%, rgba(255,255,255,0.3) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 60% 40%, rgba(255,255,255,0.6) 0%, transparent 100%),
                radial-gradient(1px 1px at 90% 55%, rgba(255,255,255,0.4) 0%, transparent 100%),
                radial-gradient(1px 1px at 10% 75%, rgba(200,210,255,0.3) 0%, transparent 100%);
            background-size: 100% 100%;
            animation: dustShimmer 6s ease-in-out infinite alternate;
        }
        @keyframes dustShimmer {
            0%   { opacity: 0.5; }
            100% { opacity: 0.9; }
        }

        /* Responsive overrides for layout elements throughout app */
        @media (max-width: 640px) {
            .mobile-full { width: 100% !important; }
            .mobile-stack { flex-direction: column !important; }
            .mobile-p-4 { padding: 1rem !important; }
        }

        /* Scrollbar global */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: rgba(0,0,0,0.2); }
        ::-webkit-scrollbar-thumb { background: rgba(99,102,241,0.4); border-radius: 8px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(99,102,241,0.7); }
    </style>
</head>
<body>

    <!-- Professional Galaxy Background -->
    <div id="galaxy-bg">
        <div class="galaxy-gradient"></div>
        <div class="nebula nebula-1"></div>
        <div class="nebula nebula-2"></div>
        <div class="nebula nebula-3"></div>
        <div class="nebula nebula-4"></div>
        <canvas id="star-canvas"></canvas>
        <div class="cosmic-dust"></div>
        <!-- Shooting stars -->
        <div class="shooting-star" style="top:10%;left:0;animation-duration:6s;animation-delay:1s;"></div>
        <div class="shooting-star" style="top:30%;left:0;animation-duration:9s;animation-delay:5s;"></div>
        <div class="shooting-star" style="top:55%;left:0;animation-duration:7s;animation-delay:11s;"></div>
        <div class="shooting-star" style="top:70%;left:0;animation-duration:12s;animation-delay:3s;"></div>
    </div>

    <div class="relative z-10 w-full min-h-screen">
        {{ $slot }}
    </div>

    <script>
    (function() {
        // Draw stars on canvas for high-performance rendering
        function initStarCanvas() {
            const canvas = document.getElementById('star-canvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            let W = canvas.width = window.innerWidth;
            let H = canvas.height = window.innerHeight;

            function drawStars() {
                ctx.clearRect(0, 0, W, H);
                // Layer 1: small dim stars (many)
                for (let i = 0; i < 350; i++) {
                    const x = Math.random() * W;
                    const y = Math.random() * H;
                    const r = Math.random() * 0.7 + 0.2;
                    const alpha = Math.random() * 0.5 + 0.2;
                    ctx.beginPath();
                    ctx.arc(x, y, r, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(210,225,255,${alpha})`;
                    ctx.fill();
                }
                // Layer 2: medium bright stars
                for (let i = 0; i < 80; i++) {
                    const x = Math.random() * W;
                    const y = Math.random() * H;
                    const r = Math.random() * 1.2 + 0.5;
                    const alpha = Math.random() * 0.7 + 0.4;
                    ctx.beginPath();
                    ctx.arc(x, y, r, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(230,240,255,${alpha})`;
                    ctx.fill();
                    // Soft glow halo
                    const grd = ctx.createRadialGradient(x, y, 0, x, y, r * 5);
                    grd.addColorStop(0, `rgba(180,200,255,${alpha * 0.3})`);
                    grd.addColorStop(1, 'transparent');
                    ctx.beginPath();
                    ctx.arc(x, y, r * 5, 0, Math.PI * 2);
                    ctx.fillStyle = grd;
                    ctx.fill();
                }
                // Layer 3: large bright accent stars with cross sparkle
                for (let i = 0; i < 15; i++) {
                    const x = Math.random() * W;
                    const y = Math.random() * H;
                    const r = Math.random() * 1.5 + 1;
                    const alpha = Math.random() * 0.5 + 0.6;
                    ctx.beginPath();
                    ctx.arc(x, y, r, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(255,255,255,${alpha})`;
                    ctx.fill();
                    // Cross sparkle lines
                    ctx.strokeStyle = `rgba(255,255,255,${alpha * 0.4})`;
                    ctx.lineWidth = 0.5;
                    const len = r * 8;
                    ctx.beginPath(); ctx.moveTo(x - len, y); ctx.lineTo(x + len, y); ctx.stroke();
                    ctx.beginPath(); ctx.moveTo(x, y - len); ctx.lineTo(x, y + len); ctx.stroke();
                    // Wide glow
                    const grd2 = ctx.createRadialGradient(x, y, 0, x, y, r * 10);
                    grd2.addColorStop(0, `rgba(150,180,255,${alpha * 0.25})`);
                    grd2.addColorStop(1, 'transparent');
                    ctx.beginPath();
                    ctx.arc(x, y, r * 10, 0, Math.PI * 2);
                    ctx.fillStyle = grd2;
                    ctx.fill();
                }
            }

            drawStars();

            // Twinkling animation
            let twinklePhase = 0;
            function twinkle() {
                twinklePhase += 0.008;
                canvas.style.opacity = 0.7 + Math.sin(twinklePhase) * 0.15;
                requestAnimationFrame(twinkle);
            }
            twinkle();

            // Resize handler
            window.addEventListener('resize', function() {
                W = canvas.width = window.innerWidth;
                H = canvas.height = window.innerHeight;
                drawStars();
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initStarCanvas);
        } else {
            initStarCanvas();
        }
    })();
    </script>
@stack('scripts')
</body>
</html>
