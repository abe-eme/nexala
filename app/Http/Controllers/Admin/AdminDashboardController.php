<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Safely check what relation exists on the model to avoid crash triggers
        $relation = method_exists(Course::class, 'teacher') ? 'teacher' : (method_exists(Course::class, 'user') ? 'user' : null);
        
        $query = Course::query();
        if ($relation) {
            $query->with($relation);
        }

        $courses = $query->get()->map(function($course) use ($relation) {
            $teacherName = 'Unknown';
            if ($relation && $course->{$relation}) {
                $teacherName = $course->{$relation}->name;
            }

            return [
                'id' => $course->id,
                'title' => $course->title,
                'category' => $course->category,
                'status' => $course->status,
                'teacher' => $teacherName
            ];
        });

        return Inertia::render('Admin/Dashboard', [
            'users' => User::all(),
            'courses' => $courses,
            'metrics' => [
                'total_students' => User::where('role', 'student')->count(),
                'total_teachers' => User::where('role', 'teacher')->count(),
                'pending_teachers' => User::where('role', 'teacher')->where('status', 'pending')->count()
            ]
        ]);
    }

    public function updateUserStatus(Request $request, User $user)
    {
        $request->validate(['status' => 'required|in:approved,suspended,pending']);
        $user->update(['status' => $request->status]);
        return back();
    }

    public function updateCourseStatus(Request $request, Course $course)
    {
        $request->validate(['status' => 'required|in:published,rejected,suspended,pending']);
        $course->update(['status' => $request->status]);
        return back();
    }
}