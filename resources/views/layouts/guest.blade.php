<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    </head>
    <body class="font-sans text-gray-200 antialiased bg-[#04040a] overflow-x-hidden min-h-screen relative selection:bg-purple-500/30 selection:text-white">
        
        {{-- Galaxy background elements --}}
        <div class="fixed inset-0 z-0 bg-gradient-to-br from-[#060613] via-[#090b1a] to-[#04040a] pointer-events-none">
            <!-- Interstellar dust glows -->
            <div class="absolute top-[-20%] left-[-10%] w-[60%] h-[60%] bg-[#221051] rounded-full blur-[140px] opacity-40"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-[#08184a] rounded-full blur-[130px] opacity-50"></div>
            <div class="absolute top-[30%] left-[30%] w-[40%] h-[40%] bg-[#310c3b] rounded-full blur-[150px] opacity-30"></div>
            
            <!-- Star patterns -->
            <div class="absolute inset-0 opacity-[0.3]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 60px 60px;"></div>
            <div class="absolute inset-0 opacity-[0.2]" style="background-image: radial-gradient(#fff 1.5px, transparent 1.5px); background-size: 110px 110px; background-position: 25px 25px;"></div>
            <div class="absolute inset-0 opacity-[0.1]" style="background-image: radial-gradient(#aaa 1px, transparent 1px); background-size: 25px 25px; background-position: 10px 10px;"></div>
        </div>
        
        <div class="min-h-screen flex items-center justify-center p-4 relative z-10">
            
            {{-- Main Container layout --}}
            <div class="w-full max-w-[500px] flex flex-col shadow-[0_20px_60px_-15px_rgba(100,50,255,0.2)] rounded-3xl overflow-hidden min-h-[580px] relative border border-white/10 backdrop-blur-3xl bg-[#0a0a1a]/60">
                
                {{-- Content Area (Slot) --}}
                <div class="w-full flex-1 flex flex-col justify-center p-8 sm:p-12 relative bg-transparent z-10">
                    <div class="w-full max-w-sm mx-auto">
                        {{ $slot }}
                    </div>
                </div>

            </div>
        </div>
    </body>
</html>
