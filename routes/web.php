<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return redirect()->route('supporthub');
})->middleware(['auth'])->name('dashboard');

Route::get('/supporthub', \App\Livewire\SupportHub::class)->middleware(['auth'])->name('supporthub');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas de sistema de tickets
    Route::view('/tickets', 'tickets')->name('tickets.index');
    // Admin dashboard
    Route::view('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
    // Agent dashboard
    Route::view('/agent/dashboard', 'agent.dashboard')->name('agent.dashboard');
    // Statistics dashboard
    Route::view('/statistics', 'statistics')->name('statistics.index');
    // Inventory panel
    Route::get('/inventory', \App\Livewire\InventoryPanel::class)->name('inventory.index');
    // Station map
    Route::get('/stations', \App\Livewire\StationMap::class)->name('stations.index');
});

require __DIR__.'/auth.php';
