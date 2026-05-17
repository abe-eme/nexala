<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Standard Student Dashboard Layout
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Protected Teacher Dashboard Layout
Route::get('/teacher/dashboard', function () {
    return Inertia::render('Teacher/Dashboard');
})->middleware(['auth'])->name('teacher.dashboard');

require __DIR__.'/auth.php';