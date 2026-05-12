<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (sin autenticación)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Laravel Breeze / Fortify genera estas automáticamente)
| Si usas Breeze: php artisan breeze:install
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (requieren login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Orders ──────────────────────────────────
    Route::prefix('pedidos')->name('orders.')->group(function () {

        // Lista y CRUD básico
        Route::get('/',          [OrderController::class, 'index']  )->name('index');
        Route::get('/nuevo',     [OrderController::class, 'create'] )->name('create');
        Route::post('/',         [OrderController::class, 'store']  )->name('store');
        Route::get('/{order}',   [OrderController::class, 'show']   )->name('show');
        Route::get('/{order}/editar', [OrderController::class, 'edit']  )->name('edit');
        Route::put('/{order}',   [OrderController::class, 'update'] )->name('update');

        // Acciones especiales
        Route::patch('/{order}/estado',  [OrderController::class, 'changeStatus'])->name('status');
        Route::patch('/{order}/foto',    [OrderController::class, 'uploadPhoto'] )->name('photo');
        Route::delete('/{order}',        [OrderController::class, 'destroy']     )->name('destroy');

        // Archivados
        Route::get('/archivados/lista',  [OrderController::class, 'archived']    )->name('archived');
        Route::patch('/{id}/restaurar',  [OrderController::class, 'restore']     )->name('restore');
    });

    // ── Users (Admin only) ───────────────────────
    Route::prefix('usuarios')->name('users.')->middleware('can:admin')->group(function () {
        Route::get('/',              [UserController::class, 'index'] )->name('index');
        Route::get('/nuevo',         [UserController::class, 'create'])->name('create');
        Route::post('/',             [UserController::class, 'store'] )->name('store');
        Route::get('/{user}/editar', [UserController::class, 'edit']  )->name('edit');
        Route::put('/{user}',        [UserController::class, 'update'])->name('update');
        Route::patch('/{user}/toggle',[UserController::class, 'toggle'])->name('toggle');
    });
});
