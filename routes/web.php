<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManagerController;

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/history', [DashboardController::class, 'history'])->name('history');
    Route::get('/history/{id}', [DashboardController::class, 'show'])->name('view.history');

    Route::get('/admin/sppd/export', [DashboardController::class, 'export'])->name('sppd.export');

    Route::middleware('manager')->group(function () {
        Route::get('/manager', [ManagerController::class, 'index'])->name('manager.index');
    });
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
