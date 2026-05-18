<?php

use App\Http\Controllers\ProfileController;
use App\Http\Middleware\EnsureUserIsAdmin;     
use App\Http\Middleware\EnsureUserIsTeacher;   
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// 1. IMPORT MODELS
use App\Models\User;
use App\Models\Course;

// 2. PUBLIC WELCOME ROUTE
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// 3. STUDENT DASHBOARD
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 4. TEACHER DASHBOARD
Route::get('/teacher/dashboard', function () {
    return Inertia::render('Teacher/Dashboard');
})->middleware(['auth', EnsureUserIsTeacher::class])->name('teacher.dashboard');

// 5. ADMIN DASHBOARD ROUTE
Route::get('/admin/dashboard', function () {
    // Fetch courses with teacher data safely
    $courses = Course::with('teacher')->get()->map(function($course) {
        return [
            'id' => $course->id,
            'title' => $course->title,
            'category' => $course->category,
            'status' => $course->status,
            'teacher' => $course->teacher ? $course->teacher->name : 'Unknown Teacher',
        ];
    });

    return Inertia::render('Admin/Dashboard', [
        'users' => User::all(),
        'courses' => $courses,
        'metrics' => [
            'total_students' => User::where('role', 'student')->count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'pending_teachers' => User::where('role', 'teacher')->where('status', 'pending')->count(),
        ]
    ]);
})->middleware(['auth', EnsureUserIsAdmin::class])->name('admin.dashboard');

// 6. ADMIN ACTION: CHANGE USER STATUS
Route::patch('/admin/users/{user}/status', function (\Illuminate\Http\Request $request, User $user) {
    $request->validate(['status' => 'required|in:approved,suspended,pending']);
    $user->update(['status' => $request->status]);
    return back();
})->middleware(['auth', EnsureUserIsAdmin::class])->name('admin.users.status');

// 7. ADMIN ACTION: CHANGE COURSE STATUS
Route::patch('/admin/courses/{course}/status', function (\Illuminate\Http\Request $request, Course $course) {
    $request->validate(['status' => 'required|in:published,rejected,suspended,pending']);
    $course->update(['status' => $request->status]);
    return back();
})->middleware(['auth', EnsureUserIsAdmin::class])->name('admin.courses.status');

// 8. PROFILE MANAGEMENT
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 9. AUTHENTICATION ROUTE REQUIREMENT
require __DIR__.'/auth.php';