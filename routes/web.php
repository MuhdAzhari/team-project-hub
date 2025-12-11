<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect homepage → dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard (requires login + email verification)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Routes for all authenticated users
Route::middleware(['auth'])->group(function () {

    // Profile routes (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Project views (read-only for non-admin)
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

    // Kanban status update – all authenticated users can move tasks
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])
        ->name('tasks.update-status');
});

// Admin-only routes (full management)
Route::middleware(['auth', 'role:admin'])->group(function () {

    // Full client management
    Route::resource('clients', ClientController::class);

    // Admin manages projects (create/edit/delete)
    // index + show already defined above for all users
    Route::resource('projects', ProjectController::class)->except(['index', 'show']);

    // Admin manages tasks (create/edit/delete)
    // tasks are always viewed via project, so no index/show here
    Route::resource('tasks', TaskController::class)->except(['index', 'show']);
});

// Auth scaffolding (Breeze)
require __DIR__.'/auth.php';
