<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index()
    {
        return Inertia::render('Teacher/CourseTable', [
            'courses' => Course::where('user_id', auth()->id())->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Teacher/CourseCreate');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        Course::create(array_merge($validated, [
            'user_id' => auth()->id(), 
            'status' => 'pending'
        ]));

        return redirect()->route('teacher.courses.index');
    }

    public function edit(Course $course)
    {
        if ($course->user_id !== auth()->id()) { abort(403); }
        
        return Inertia::render('Teacher/courseedit', [
            'course' => $course
        ]);
    }

    public function update(Course $course, Request $request)
    {
        if ($course->user_id !== auth()->id()) { abort(403); }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $course->update($validated);
        return redirect()->route('teacher.courses.index');
    }

    public function destroy(Course $course)
    {
        if ($course->user_id === auth()->id()) { 
            $course->delete(); 
        }
        return redirect()->route('teacher.courses.index');
    }
}