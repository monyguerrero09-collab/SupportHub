<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sistema de Tickets') }}
        </h2>
    </x-slot>

        <div class="px-4 sm:px-0">
            @livewire('support-tickets')
        </div>
</x-app-layout>
