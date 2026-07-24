<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div x-data="{
        currentView: '{{ $errors->any() || old('codigo_acceso') ? 'login' : 'selection' }}',
        selectedRole: '{{ old('selected_role', 'Usuario') }}',
        codigoAcceso: '{{ old('codigo_acceso') }}',
        setRole(role) {
            this.selectedRole = role;
            this.currentView = 'login';
            this.codigoAcceso = '';
        }
    }" class="w-full">

        <!-- Selection View -->
        <div x-show="currentView === 'selection'" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="flex flex-col items-center justify-center space-y-10 w-full animate-slideUp">
            
            <div class="text-center space-y-3">
                <h1 class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-indigo-400 to-purple-400 tracking-tight drop-shadow-[0_0_15px_rgba(99,102,241,0.2)]">
                    SupportHub
                </h1>
                <h2 class="text-lg font-bold text-gray-400 tracking-widest uppercase">
                    Welcome
                </h2>
                <p class="text-gray-500 text-sm">Selecciona tu perfil para acceder al sistema</p>
            </div>

            <!-- Profile Grid: 4 Buttons -->
            <div class="grid grid-cols-2 w-full gap-4 justify-center items-stretch">
                <!-- Usuario Button -->
                <button
                    type="button"
                    @click="setRole('Usuario')"
                    class="group relative flex-1 bg-white/[0.02] hover:bg-blue-600/[0.04] p-5 rounded-2xl border border-white/10 hover:border-blue-500/50 hover:shadow-[0_0_20px_rgba(37,99,235,0.15)] transition-all duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                >
                    <div class="flex flex-col items-center space-y-3">
                        <div class="p-3.5 bg-blue-500/10 rounded-xl group-hover:bg-blue-500/20 group-hover:scale-110 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-gray-200 group-hover:text-blue-400 transition-colors">Usuario</span>
                    </div>
                </button>

                <!-- Agente TI Button -->
                <button
                    type="button"
                    @click="setRole('Agente TI')"
                    class="group relative flex-1 bg-white/[0.02] hover:bg-purple-600/[0.04] p-5 rounded-2xl border border-white/10 hover:border-purple-500/50 hover:shadow-[0_0_20px_rgba(124,58,237,0.15)] transition-all duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-purple-500/50"
                >
                    <div class="flex flex-col items-center space-y-3">
                        <div class="p-3.5 bg-purple-500/10 rounded-xl group-hover:bg-purple-500/20 group-hover:scale-110 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-gray-200 group-hover:text-purple-400 transition-colors">Agente TI</span>
                    </div>
                </button>

                <!-- Administrador Button -->
                <button
                    type="button"
                    @click="setRole('Administrador')"
                    class="group relative flex-1 bg-white/[0.02] hover:bg-emerald-600/[0.04] p-5 rounded-2xl border border-white/10 hover:border-emerald-500/50 hover:shadow-[0_0_20px_rgba(16,185,129,0.15)] transition-all duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                >
                    <div class="flex flex-col items-center space-y-3">
                        <div class="p-3.5 bg-emerald-500/10 rounded-xl group-hover:bg-emerald-500/20 group-hover:scale-110 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-gray-200 group-hover:text-emerald-400 transition-colors">Admin</span>
                    </div>
                </button>

                <!-- Gestor de Stocks Button -->
                <button
                    type="button"
                    @click="setRole('Gestor de Stocks')"
                    class="group relative flex-1 bg-white/[0.02] hover:bg-amber-600/[0.04] p-5 rounded-2xl border border-white/10 hover:border-amber-500/50 hover:shadow-[0_0_20px_rgba(245,158,11,0.15)] transition-all duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-amber-500/50"
                >
                    <div class="flex flex-col items-center space-y-3">
                        <div class="p-3.5 bg-amber-500/10 rounded-xl group-hover:bg-amber-500/20 group-hover:scale-110 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-gray-200 group-hover:text-amber-400 transition-colors leading-tight text-center">Gestor Stock</span>
                    </div>
                </button>
            </div>
        </div>

        <!-- Login View -->
        <div x-show="currentView === 'login'" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="w-full animate-fadeIn"
             style="display: none;">
            
            <div class="mb-6">
                <!-- Back Button -->
                <button 
                    type="button"
                    @click="currentView = 'selection'"
                    class="flex items-center text-gray-500 hover:text-blue-400 transition-colors text-[10px] font-black uppercase tracking-widest mb-4 group"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                    Volver a perfiles
                </button>
                
                <!-- Dynamic Title -->
                <h2 class="text-3xl font-black text-white tracking-tight uppercase">
                    Acceso <span :class="selectedRole === 'Usuario' ? 'text-blue-400' : (selectedRole === 'Agente TI' ? 'text-purple-400' : (selectedRole === 'Gestor de Stocks' ? 'text-amber-400' : 'text-emerald-400'))" x-text="selectedRole === 'Gestor de Stocks' ? 'Gestor' : selectedRole"></span>
                </h2>
                <!-- Dynamic Subtitle -->
                <p class="text-gray-500 text-xs mt-1"
                   x-text="selectedRole === 'Usuario' ? 'Ingresa tu ID de Empleado' : (selectedRole === 'Agente TI' ? 'Ingresa tu Código de Acceso' : (selectedRole === 'Gestor de Stocks' ? 'Ingresa tu Código de Gestor' : 'Ingresa tu Código de Administrador'))">
                </p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                
                <!-- Hidden inputs -->
                <input type="hidden" name="selected_role" :value="selectedRole">

                <!-- Access Code Input -->
                <div>
                    <!-- Dynamic Label -->
                    <label for="codigo_acceso" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 px-1"
                           x-text="selectedRole === 'Usuario' ? 'ID de Usuario' : (selectedRole === 'Agente TI' ? 'PIN / Credencial TI' : (selectedRole === 'Gestor de Stocks' ? 'Código de Gestor' : 'Código de Administrador'))">
                    </label>
                    <div class="relative bg-[#131b2f] border border-[#1e293b]/50 rounded-[0.75rem] flex items-center p-2.5 focus-within:border-blue-500/80 transition-all">
                        <div class="w-9 h-9 rounded-lg bg-blue-500/10 flex items-center justify-center shrink-0">
                            <!-- Dynamic Icon Color -->
                            <svg class="w-4 h-4" :class="selectedRole === 'Usuario' ? 'text-blue-400' : (selectedRole === 'Agente TI' ? 'text-purple-400' : (selectedRole === 'Gestor de Stocks' ? 'text-amber-400' : 'text-emerald-400'))" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <input id="codigo_acceso" type="text" name="codigo_acceso" x-model="codigoAcceso" required autofocus
                                class="block w-full border-0 p-0 text-sm text-white bg-transparent focus:ring-0 placeholder-slate-700 font-medium" 
                                :placeholder="selectedRole === 'Usuario' ? 'Ej. US-1234' : '••••••••'" />
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('codigo_acceso')" class="text-xs font-bold pl-2 mt-1 text-red-400" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1 px-1">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                        <div class="relative flex items-center justify-center">
                            <input id="remember_me" type="checkbox" name="remember" class="peer appearance-none w-4 h-4 border border-white/10 rounded checked:bg-blue-600 checked:border-blue-600 focus:outline-none focus:ring-0 transition-all cursor-pointer bg-white/5">
                            <svg class="absolute w-2.5 h-2.5 text-white pointer-events-none opacity-0 peer-checked:opacity-100 transition-all" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span class="ms-2 text-xs font-bold text-slate-400 group-hover:text-slate-300 transition-colors">{{ __('Remember me') }}</span>
                    </label>
                </div>


                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" 
                        :class="selectedRole === 'Usuario' ? 'bg-blue-600 hover:bg-blue-700 shadow-[0_8px_25px_rgba(37,99,235,0.3)]' : (selectedRole === 'Agente TI' ? 'bg-purple-600 hover:bg-purple-700 shadow-[0_8px_25px_rgba(124,58,237,0.3)]' : (selectedRole === 'Gestor de Stocks' ? 'bg-amber-600 hover:bg-amber-700 shadow-[0_8px_25px_rgba(245,158,11,0.3)]' : 'bg-emerald-600 hover:bg-emerald-700 shadow-[0_8px_25px_rgba(16,185,129,0.3)]'))"
                        class="w-full py-4 rounded-xl text-white font-bold text-xs uppercase tracking-widest transition-all duration-300 hover:scale-[1.02] active:scale-95 text-center flex items-center justify-center">
                        Entrar al Sistema
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
