<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LessonController extends Controller
{
    public function index(Course $course)
    {
        if ($course->user_id !== auth()->id()) { abort(403); }

        return Inertia::render('Teacher/Lessons/LessonTable', [
            'course' => $course,
            'lessons' => Lesson::where('course_id', $course->id)->orderBy('sort_order', 'asc')->get()
        ]);
    }

    public function create(Course $course)
    {
        if ($course->user_id !== auth()->id()) { abort(403); }

        return Inertia::render('Teacher/Lessons/LessonCreate', [
            'course' => $course,
            'availableLessons' => Lesson::where('course_id', $course->id)->get()
        ]);
    }

    public function store(Course $course, Request $request)
    {
        if ($course->user_id !== auth()->id()) { abort(403); }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'media_type' => 'required|in:text,video,audio,image',
            'text_content' => 'nullable|string',
            'prerequisite_lesson_id' => 'nullable|exists:lessons,id'
        ]);

        $filePath = null;
        if ($request->hasFile('file_payload')) {
            $filePath = $request->file('file_payload')->store('lessons', 'public');
        }

        Lesson::create([
            'course_id' => $course->id,
            'title' => $validated['title'],
            'media_type' => $validated['media_type'],
            'text_content' => $validated['text_content'],
            'media_path' => $filePath,
            'prerequisite_lesson_id' => $validated['prerequisite_lesson_id'],
            'sort_order' => Lesson::where('course_id', $course->id)->count() + 1
        ]);

        return redirect()->route('teacher.courses.show', $course->id);
    }

    public function destroy(Lesson $lesson)
    {
        if ($lesson->course->user_id === auth()->id()) { 
            $lesson->delete(); 
        }
        return back();
    }

    public function updateRules(Lesson $lesson, Request $request)
    {
        if ($lesson->course->user_id !== auth()->id()) { abort(403); }
        
        $validated = $request->validate([
            'gate_type' => 'required|in:none,time_lock,quiz_pass',
            'min_time' => 'required|integer|min:0'
        ]);

        $lesson->update([
            'gate_type' => $validated['gate_type'],
            'min_time' => $validated['min_time']
        ]);

        return back();
    }

    public function generateAiDraft(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255']);
        $title = $request->input('title');

        $aiDraft = "## Educational Module Study Guide: {$title}\n\n" .
                   "### 1. Core Foundational Concepts\n" .
                   "This block breaks down the engineering parameters behind {$title}.\n\n" .
                   "### 2. Practical Technical Implementations\n" .
                   "- Maintain modular code separation across views and components.\n\n" .
                   "### 3. Verification & Troubleshooting Exercises\n" .
                   "Review application error handling routines to prevent silent failures.";

        return response()->json(['content' => $aiDraft]);
    }
}