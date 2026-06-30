<x-app-layout>
    <x-slot name="header">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Panel de Estadísticas y Progresos</h1>
    </x-slot>
<div class="container mx-auto p-4 animate-fadeIn">
    
    <div class="mb-6 flex flex-col md:flex-row justify-between md:items-center bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 p-6 rounded-2xl border border-blue-100 dark:border-blue-800/50 shadow-sm gap-4">
        <div>
            <h2 class="text-xl font-bold text-blue-800 dark:text-blue-200">Tendencias de incidencias y progreso</h2>
            <p class="text-sm text-blue-600 dark:text-blue-300 mt-1">
                Explora el rendimiento en la resolución de tickets. Visualiza lo que ya se arregló, los tickets faltantes (En proceso o Abiertos) y revisa tu mejora continua de forma semanal y mensual.
            </p>
        </div>
        <div>
            <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('agent.dashboard') }}" class="text-sm bg-white dark:bg-gray-800 px-5 py-2.5 rounded-xl font-bold shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all text-gray-700 dark:text-gray-200 flex items-center justify-center whitespace-nowrap">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Panel
            </a>
        </div>
    </div>

    <!-- Cargar gráficas dinámicas -->
    <livewire:dashboard-charts />
</div>
</x-app-layout>
