<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SupportHub') }}</title>
        <meta name="description" content="SupportHub - Sistema de gestión de tickets IT">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="{{ asset('apexcharts.js') }}"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased text-gray-200 relative overflow-x-hidden min-h-screen selection:bg-purple-500/30 selection:text-white"
          style="font-family: 'Inter', 'Figtree', sans-serif; background-color: #010108;">
        
        {{-- Galaxy background elements --}}
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
            <!-- Deep space gradient -->
            <div style="position:absolute;inset:0;background:radial-gradient(ellipse 120% 80% at 10% 20%, rgba(45,27,105,0.5) 0%, transparent 55%),radial-gradient(ellipse 90% 70% at 90% 80%, rgba(10,30,74,0.65) 0%, transparent 55%),radial-gradient(ellipse 70% 60% at 50% 50%, rgba(10,42,42,0.25) 0%, transparent 60%),linear-gradient(135deg, #010108 0%, #06051a 30%, #030312 60%, #010108 100%);"></div>
            <!-- Nebula blobs -->
            <div style="position:absolute;width:70vw;height:70vh;top:-20vh;left:-20vw;border-radius:50%;filter:blur(90px);mix-blend-mode:screen;background:radial-gradient(circle, rgba(88,40,200,0.22) 0%, rgba(40,20,120,0.12) 40%, transparent 70%);animation:nebulaDrift 30s ease-in-out infinite alternate;"></div>
            <div style="position:absolute;width:60vw;height:60vh;bottom:-15vh;right:-10vw;border-radius:50%;filter:blur(80px);mix-blend-mode:screen;background:radial-gradient(circle, rgba(14,60,160,0.28) 0%, rgba(10,30,100,0.12) 40%, transparent 70%);animation:nebulaDrift 22s ease-in-out infinite alternate;animation-delay:-8s;"></div>
            <div style="position:absolute;width:45vw;height:45vh;top:30vh;left:35vw;border-radius:50%;filter:blur(80px);mix-blend-mode:screen;background:radial-gradient(circle, rgba(80,20,100,0.18) 0%, transparent 70%);animation:nebulaDrift 35s ease-in-out infinite alternate;animation-delay:-15s;"></div>
            <!-- Star canvas -->
            <canvas id="app-star-canvas" style="position:absolute;inset:0;width:100%;height:100%;"></canvas>
        </div>
        <style>
            @keyframes nebulaDrift {
                0%   { transform: translate(0,0) scale(1); opacity:0.7; }
                33%  { transform: translate(3vw,-2vh) scale(1.05); opacity:0.9; }
                66%  { transform: translate(-2vw,3vh) scale(0.97); opacity:0.75; }
                100% { transform: translate(2vw,-1vh) scale(1.02); opacity:0.85; }
            }
        </style>
        <script>
            (function() {
                function initCanvas() {
                    const c = document.getElementById('app-star-canvas');
                    if (!c) return;
                    const ctx = c.getContext('2d');
                    let W = c.width = window.innerWidth, H = c.height = window.innerHeight;
                    function draw() {
                        ctx.clearRect(0,0,W,H);
                        for (let i=0;i<300;i++) {
                            const x=Math.random()*W,y=Math.random()*H,r=Math.random()*0.7+0.2,a=Math.random()*0.5+0.2;
                            ctx.beginPath();ctx.arc(x,y,r,0,Math.PI*2);ctx.fillStyle=`rgba(210,225,255,${a})`;ctx.fill();
                        }
                        for (let i=0;i<60;i++) {
                            const x=Math.random()*W,y=Math.random()*H,r=Math.random()*1.2+0.5,a=Math.random()*0.6+0.3;
                            ctx.beginPath();ctx.arc(x,y,r,0,Math.PI*2);ctx.fillStyle=`rgba(230,240,255,${a})`;ctx.fill();
                            const g=ctx.createRadialGradient(x,y,0,x,y,r*5);g.addColorStop(0,`rgba(180,200,255,${a*0.3})`);g.addColorStop(1,'transparent');
                            ctx.beginPath();ctx.arc(x,y,r*5,0,Math.PI*2);ctx.fillStyle=g;ctx.fill();
                        }
                        for (let i=0;i<10;i++) {
                            const x=Math.random()*W,y=Math.random()*H,r=Math.random()*1.5+1,a=Math.random()*0.5+0.6;
                            ctx.beginPath();ctx.arc(x,y,r,0,Math.PI*2);ctx.fillStyle=`rgba(255,255,255,${a})`;ctx.fill();
                            ctx.strokeStyle=`rgba(255,255,255,${a*0.3})`;ctx.lineWidth=0.5;
                            const l=r*8;
                            ctx.beginPath();ctx.moveTo(x-l,y);ctx.lineTo(x+l,y);ctx.stroke();
                            ctx.beginPath();ctx.moveTo(x,y-l);ctx.lineTo(x,y+l);ctx.stroke();
                        }
                    }
                    draw();
                    let phase=0;
                    function twinkle(){phase+=0.007;c.style.opacity=0.6+Math.sin(phase)*0.2;requestAnimationFrame(twinkle);}
                    twinkle();
                    window.addEventListener('resize',function(){W=c.width=window.innerWidth;H=c.height=window.innerHeight;draw();});
                }
                if(document.readyState==='loading'){document.addEventListener('DOMContentLoaded',initCanvas);}else{initCanvas();}
            })();
        </script>

        <div class="relative z-10 flex flex-col min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-[#0f1122]/60 backdrop-blur-xl border border-white/5 shadow-[0_4px_30px_rgba(0,0,0,0.3)] sticky top-0 z-40 mx-2 mt-2 rounded-2xl">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1 w-full max-w-7xl mx-auto py-6 px-4 md:px-8">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
