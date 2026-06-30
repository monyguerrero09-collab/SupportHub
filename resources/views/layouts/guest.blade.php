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
            
            {{-- Main Containe layout  --}}
            <div class="w-full max-w-[1100px] flex flex-col md:flex-row shadow-[0_20px_60px_-15px_rgba(100,50,255,0.2)] rounded-3xl overflow-hidden min-h-[650px] relative border border-white/10 backdrop-blur-3xl bg-[#0a0a1a]/60">
                
                {{-- Left Side: Branding / Welcome (Galaxy Violet/Blue Gradient) --}}
                <div class="hidden md:flex md:w-[45%] relative bg-gradient-to-br from-[#120836] via-[#0a0b25] to-[#040410] p-12 text-white flex-col justify-between overflow-hidden border-r border-white/5">
                    {{-- Decorative Abstract Lines (matching image) --}}
                    <div class="absolute inset-0 z-0 bg-opacity-20" style="background: repeating-linear-gradient(45deg, transparent, transparent 18px, rgba(255,255,255,0.02) 18px, rgba(255,255,255,0.02) 36px);"></div>
                    <div class="absolute -top-10 -right-20 w-[150%] h-[3px] bg-purple-400/40 rotate-[-45deg] blur-[1px]"></div>
                    <div class="absolute top-[20%] -right-20 w-[150%] h-[2px] bg-white/30 rotate-[-45deg] blur-[1px]"></div>
                    <div class="absolute top-[40%] -right-20 w-[150%] h-[5px] bg-indigo-400/20 rotate-[-45deg] blur-[2px]"></div>
                    <div class="absolute -bottom-10 -left-10 w-[120%] h-[4px] bg-cyan-300/30 rotate-[-45deg]"></div>

                    <div class="relative z-10 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full shadow-[0_0_15px_rgba(255,255,255,0.4)] flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 border border-white/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <span class="text-xl font-black tracking-widest text-white uppercase drop-shadow-[0_0_8px_rgba(255,255,255,0.3)]">SupportHub</span>
                    </div>
                    
                    <div class="relative z-10 mt-auto mb-10">
                        <h1 class="text-[3.5rem] font-black leading-[1.1] tracking-tight mb-4 drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]">Hello,<br>welcome!</h1>
                        <p class="text-indigo-200/80 text-sm max-w-[250px] leading-relaxed mb-6 font-medium">Sumérgete en el vasto cosmos de soporte de SupportHub.</p>
                        
                        <a href="#" class="px-8 py-3 bg-white/10 border border-white/20 text-white rounded-full font-bold text-xs shadow-xl transition-transform hover:scale-105 inline-block hover:bg-white/20 hover:shadow-[0_0_15px_rgba(255,255,255,0.3)] backdrop-blur-md">
                            Explorar Portal
                        </a>
                    </div>

                    <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-purple-600/30 blur-[80px] rounded-full mix-blend-screen"></div>
                </div>

                {{-- Right Side: Content Area (Slot) --}}
                <div class="w-full md:w-[55%] flex flex-col justify-center p-8 sm:p-14 lg:p-20 relative bg-transparent z-10">
                    <div class="w-full max-w-sm mx-auto">
                        {{ $slot }}
                    </div>
                </div>

            </div>
        </div>
    </body>
</html>
