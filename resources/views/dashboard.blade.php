<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800">
            {{ __('Dashboard de Soporte') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6 space-y-10">

            {{-- Hero --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="grid md:grid-cols-2 gap-8 items-center p-8">

                    <div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">
                            Bienvenido a SupportHub
                        </h3>

                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Gestiona tus incidencias de forma rápida y eficiente desde el
                            panel centralizado de soporte.
                        </p>

                        <a href="{{ route('tickets.index') }}" class="px-8 py-4 bg-[#0c4aed] hover:bg-[#0535b5] text-white rounded-xl text-lg font-bold transition-all duration-300 shadow-[0_10px_20px_-10px_rgba(12,74,237,0.5)] hover:-translate-y-1">

                        Ir al Centro de Soporte (Tickets)

                    </a>

                    </div>

                    <div class="bg-gray-100 rounded-xl overflow-hidden">
                        <img 
                            src="{{ asset('img/it-support.jpg') }}" alt="IT support"
                        
                            class="w-full h-full object-cover"
                        >
                    </div>

                </div>
            </div>

            {{-- Section --}}
            <div>

                <div class="flex items-center mb-6">
                    <div class="w-1 h-6 bg-blue-600 rounded mr-3"></div>
                    <h4 class="text-xl font-semibold text-gray-800">
                        Incidencias más frecuentes
                    </h4>
                </div>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">

                    {{-- Card --}}
                    <div class="bg-white border border-gray-100 rounded-xl overflow-hidden hover:shadow-md transition">
                        <img 
                            src="{{ asset('img/conexion_error.jpg') }}" alt="error de conexión"
                            class="w-full h-40 object-cover"
                        >

                        <div class="p-5">
                            <h5 class="font-semibold text-gray-900 mb-1">
                                Errores de Conexión
                            </h5>

                            <p class="text-gray-500 text-sm">
                                No puedo acceder al internet.
                            </p>
                        </div>
                    </div>

                    {{-- Card --}}
                    <div class="bg-white border border-gray-100 rounded-xl overflow-hidden hover:shadow-md transition">
                        <img 
                            src="{{ asset('img/password.png') }}" alt="restablecer contraseña"
                            class="w-full h-40 object-cover"
                        >

                        <div class="p-5">
                            <h5 class="font-semibold text-gray-900 mb-1">
                                Restablecer Contraseña
                            </h5>

                            <p class="text-gray-500 text-sm">
                                Olvidé mis credenciales de acceso.
                            </p>
                        </div>
                    </div>

                    {{-- Card --}}
                    <div class="bg-white border border-gray-100 rounded-xl overflow-hidden hover:shadow-md transition">
                        <img 
                            src="{{ asset('img/actualizacion_software.jpg') }}" alt="actualización de software"
                            class="w-full h-40 object-cover"
                        >

                        <div class="p-5">
                            <h5 class="font-semibold text-gray-900 mb-1">
                                Actualización de Software
                            </h5>

                            <p class="text-gray-500 text-sm">
                                La plataforma no carga la última versión.
                            </p>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
