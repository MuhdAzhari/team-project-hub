<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

// Debug route (to confirm Laravel is handling the domain)
Route::get('/route-debug', function () {
    return 'Laravel is handling this request.';
});

// Redirect homepage → dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// All app features require login + verified email
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // User Profile (Laravel Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Clients (Read-only for MVP)
    |--------------------------------------------------------------------------
    */
    Route::resource('clients', ClientController::class)->only(['index', 'show']);

    /*
    |--------------------------------------------------------------------------
    | Projects (FULL CRUD for MVP — create/edit/delete working)
    |--------------------------------------------------------------------------
    */
    Route::resource('projects', ProjectController::class);

    /*
    |--------------------------------------------------------------------------
    | Tasks (Used inside project pages)
    |--------------------------------------------------------------------------
    */
    Route::resource('tasks', TaskController::class)->except(['index', 'show']);

    // Kanban drag-and-drop quick update
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])
        ->name('tasks.update-status');
});

// Authentication system (login, registration, etc.)
require __DIR__.'/auth.php';
