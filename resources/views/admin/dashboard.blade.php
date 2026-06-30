<!-- resources/views/admin/dashboard.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Panel de Administrador</h1>
    </x-slot>
<div class="container mx-auto p-4 animate-fadeIn">

    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">Acciones Rápidas</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Asignar Ticket -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6"></path></svg>
                <h2 class="ml-3 text-xl font-semibold text-gray-800 dark:text-gray-200">Asignar Ticket</h2>
            </div>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Crear y asignar tickets a los agentes de IT.</p>
            <a href="#" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Ir</a>
        </div>
        <!-- Ver Todos los Tickets -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"></path></svg>
                <h2 class="ml-3 text-xl font-semibold text-gray-800 dark:text-gray-200">Todos los Tickets</h2>
            </div>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Listado completo de tickets con filtros avanzados.</p>
            <a href="#" class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">Ir</a>
        </div>
        <!-- Administrar Usuarios -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <h2 class="ml-3 text-xl font-semibold text-gray-800 dark:text-gray-200">Gestión de Usuarios</h2>
            </div>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Crear, editar, desactivar y asignar roles a usuarios.</p>
            <a href="#" class="inline-block bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition">Ir</a>
        </div>
        <!-- Base de Conocimientos -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16h8M8 12h8m-6-4h6"></path></svg>
                <h2 class="ml-3 text-xl font-semibold text-gray-800 dark:text-gray-200">Base de Conocimientos</h2>
            </div>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Artículos, guías y FAQ para soporte interno.</p>
            <a href="#" class="inline-block bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 transition">Ir</a>
        </div>
        <!-- Estadísticas -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18"></path></svg>
                <h2 class="ml-3 text-xl font-semibold text-gray-800 dark:text-gray-200">Estadísticas</h2>
            </div>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Gráficos y métricas de rendimiento del soporte.</p>
            <a href="{{ route('statistics.index') }}" class="inline-block bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">Ver Gráficas</a>
        </div>
    </div>
</div>
</x-app-layout>
