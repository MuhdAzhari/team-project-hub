<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

// Redirect homepage → dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard (requires login + email verification)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Protected routes
Route::middleware(['auth'])->group(function () {

    // Profile routes (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Clients & Projects
    Route::resource('clients', ClientController::class);
    Route::resource('projects', ProjectController::class);

    // Tasks (no separate index/show; tasks are always viewed via project)
    Route::resource('tasks', TaskController::class)->except(['index', 'show']);

    // Quick status update for Kanban
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])
        ->name('tasks.update-status');
});

// Auth scaffolding
require __DIR__.'/auth.php';
