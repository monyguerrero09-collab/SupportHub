<!-- resources/views/agent/dashboard.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Panel de Agente IT</h1>
    </x-slot>
<div class="container mx-auto p-4 animate-fadeIn">
    
    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">Acciones Rápidas</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Ver Tickets -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6"></path></svg>
                <h2 class="ml-3 text-xl font-semibold text-gray-800 dark:text-gray-200">Ver Tickets</h2>
            </div>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Listado de tickets asignados y pendientes.</p>
            <a href="#" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Ir</a>
        </div>
        <!-- Tomar Ticket -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"></path></svg>
                <h2 class="ml-3 text-xl font-semibold text-gray-800 dark:text-gray-200">Tomar Ticket</h2>
            </div>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Asignarse un ticket disponible para trabajar.</p>
            <a href="#" class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">Ir</a>
        </div>
        <!-- Trabajar Ticket -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <h2 class="ml-3 text-xl font-semibold text-gray-800 dark:text-gray-200">Trabajar Ticket</h2>
            </div>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Acceder a la información del ticket y actualizar su progreso.</p>
            <a href="#" class="inline-block bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition">Ir</a>
        </div>
        <!-- Cambiar Estado -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16h8M8 12h8m-6-4h6"></path></svg>
                <h2 class="ml-3 text-xl font-semibold text-gray-800 dark:text-gray-200">Cambiar Estado</h2>
            </div>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Marcar tickets como Pendiente, En Proceso o Resuelto.</p>
            <a href="#" class="inline-block bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 transition">Ir</a>
        </div>
        <!-- Resolver Ticket -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18"></path></svg>
                <h2 class="ml-3 text-xl font-semibold text-gray-800 dark:text-gray-200">Resolver Ticket</h2>
            </div>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Cerrar el ticket una vez solucionado.</p>
            <a href="#" class="inline-block bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">Ir</a>
        </div>
        
        <!-- Estadísticas -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <h2 class="ml-3 text-xl font-semibold text-gray-800 dark:text-gray-200">Tendencias / Reportes</h2>
            </div>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Ver métricas de desempeño, tendencias y progresos semanales y mensuales de los problemas.</p>
            <a href="{{ route('statistics.index') }}" class="inline-block bg-cyan-500 text-white px-4 py-2 rounded hover:bg-cyan-600 transition">Ver Gráficas</a>
        </div>
    </div>
</div>
</x-app-layout>
