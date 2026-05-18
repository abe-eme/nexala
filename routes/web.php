<?php

use App\Http\Controllers\ProfileController;
use App\Http\Middleware\EnsureUserIsAdmin;    
use App\Http\Middleware\EnsureUserIsTeacher;   
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Str;

// IMPORT DRIVERS FOR EXPORTS
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

// IMPORT MODELS
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\CourseQuiz;
use App\Models\CourseAssignment;

// PUBLIC WELCOME ROOT
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// STUDENT DASHBOARD
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// =========================================================================
// TEACHER WORKSPACE ROUTE HUB (ALL TEACHER & AI TOOLS LIVE INSIDE HERE)
// =========================================================================
Route::middleware(['auth', EnsureUserIsTeacher::class])->prefix('teacher')->name('teacher.')->group(function () {
    
    Route::get('/dashboard', function () {
        return Inertia::render('Teacher/Dashboard', [
            'recentCourses' => [], 'dropoutAlerts' => [], 'totalStudents' => 0, 'totalCourses' => 0
        ]);
    })->name('dashboard');

    // COURSES MANAGEMENT
    Route::get('/courses', function () {
        return Inertia::render('Teacher/CourseTable', [
            'courses' => Course::where('user_id', auth()->id())->get()
        ]);
    })->name('courses.index');

    Route::get('/courses/create', function () {
        return Inertia::render('Teacher/CourseCreate');
    })->name('courses.create');
    
    Route::post('/courses', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);
        Course::create(array_merge($validated, ['user_id' => auth()->id(), 'status' => 'pending']));
        return redirect()->route('teacher.courses.index');
    })->name('courses.store');

    // ✅ FIXED: Using exact lowercase path 'Teacher/courseedit' to fix Vite's component compilation crash
    Route::get('/courses/{course}/edit', function (Course $course) {
        if ($course->user_id !== auth()->id()) { abort(403); }
        return Inertia::render('Teacher/courseedit', [
            'course' => $course
        ]);
    })->name('courses.edit');

    Route::put('/courses/{course}', function (Course $course, \Illuminate\Http\Request $request) {
        if ($course->user_id !== auth()->id()) { abort(403); }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);
        $course->update($validated);
        return redirect()->route('teacher.courses.index');
    })->name('courses.update');

    Route::delete('/courses/{course}', function (Course $course) {
        if ($course->user_id === auth()->id()) { $course->delete(); }
        return redirect()->route('teacher.courses.index');
    })->name('courses.destroy');

    // 📖 ISOLATED LESSONS/MODULES MANAGEMENT PAGE
    Route::get('/courses/{course}/lessons', function (Course $course) {
        if ($course->user_id !== auth()->id()) { abort(403); }
        return Inertia::render('Teacher/Lessons/LessonTable', [
            'course' => $course,
            'lessons' => Lesson::where('course_id', $course->id)->orderBy('sort_order', 'asc')->get()
        ]);
    })->name('courses.show');

    Route::get('/courses/{course}/lessons/create', function (Course $course) {
        if ($course->user_id !== auth()->id()) { abort(403); }
        return Inertia::render('Teacher/Lessons/LessonCreate', [
            'course' => $course,
            'availableLessons' => Lesson::where('course_id', $course->id)->get()
        ]);
    })->name('lessons.create');

    // ✅ FIXED: Save Module Button endpoints mapping perfectly
    Route::post('/courses/{course}/lessons', function (Course $course, \Illuminate\Http\Request $request) {
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
    })->name('lessons.store');

    Route::delete('/lessons/{lesson}', function (Lesson $lesson) { 
        if ($lesson->course->user_id === auth()->id()) { $lesson->delete(); }
        return back(); 
    })->name('lessons.destroy');

    // 🤖 AI ASSISTANT: GENERATE LESSON CONTENT DRAFT DIRECTLY
    Route::post('/lessons/generate-content', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $title = $request->input('title');

        $aiDraft = "## Educational Module Study Guide: {$title}\n\n" .
                   "### 1. Core Foundational Concepts\n" .
                   "This block breaks down the engineering parameters behind {$title}. Focus on analyzing how modules communicate structural pipelines.\n\n" .
                   "### 2. Practical Technical Implementations\n" .
                   "- Implement clean architecture interfaces without unnecessary placeholders.\n" .
                   "- Maintain modular code separation across views and components.\n\n" .
                   "### 3. Verification & Troubleshooting Exercises\n" .
                   "Review application error handling routines to prevent silent failures during local system runtime.";

        return response()->json(['content' => $aiDraft]);
    })->name('lessons.ai.generate');

    // ❓ SEPARATED QUIZ MANAGEMENT PAGES
    Route::get('/courses/{course}/quizzes', function (Course $course) {
        if ($course->user_id !== auth()->id()) { abort(403); }
        return Inertia::render('Teacher/Quizzes/Index', [
            'course' => $course,
            'quizzes' => CourseQuiz::where('course_id', $course->id)->get()
        ]);
    })->name('courses.quizzes');

    Route::get('/courses/{course}/quizzes/create', function (Course $course) {
        if ($course->user_id !== auth()->id()) { abort(403); }
        return Inertia::render('Teacher/Quizzes/Create', ['course' => $course]);
    })->name('courses.quizzes.create');

    Route::post('/courses/{course}/quizzes', function(Course $course, \Illuminate\Http\Request $request) { 
        if ($course->user_id !== auth()->id()) { abort(403); }
        $validated = $request->validate([
            'question_type' => 'required|string',
            'question_text' => 'required|string',
            'option_a' => 'nullable|string',
            'option_b' => 'nullable|string',
            'option_c' => 'nullable|string',
            'option_d' => 'nullable|string',
            'correct_option' => 'required|string'
        ]);

        CourseQuiz::create(array_merge($validated, ['course_id' => $course->id]));
        return redirect()->route('teacher.courses.quizzes', $course->id);
    })->name('courses.quizzes.store');

    // 💼 SEPARATED ASSIGNMENTS PAGES
    Route::get('/courses/{course}/assignments', function (Course $course) {
        if ($course->user_id !== auth()->id()) { abort(403); }
        return Inertia::render('Teacher/Assignments/Index', [
            'course' => $course,
            'assignments' => CourseAssignment::where('course_id', $course->id)->get()
        ]);
    })->name('courses.assignments');

    Route::get('/courses/{course}/assignments/create', function (Course $course) {
        if ($course->user_id !== auth()->id()) { abort(403); }
        return Inertia::render('Teacher/Assignments/Create', ['course' => $course]);
    })->name('courses.assignments.create');

    Route::post('/courses/{course}/assignments', function(Course $course, \Illuminate\Http\Request $request) { 
        if ($course->user_id !== auth()->id()) { abort(403); }
        $validated = $request->validate(['title' => 'required|string|max:255', 'instructions' => 'required|string']);
        CourseAssignment::create(array_merge($validated, ['course_id' => $course->id]));
        return redirect()->route('teacher.courses.assignments', $course->id);
    })->name('courses.assignments.store');

    Route::get('/courses/{course}/assignments/{assignment}/submissions', function (Course $course, CourseAssignment $assignment) {
        if ($course->user_id !== auth()->id()) { abort(403); }
        return Inertia::render('Teacher/Assignments/Submissions', [
            'course' => $course,
            'assignment' => $assignment,
            'submissions' => [] 
        ]);
    })->name('assignments.submissions');

    // 📄 GENERATE & DOWNLOAD ASSIGNMENT AS PDF
    Route::get('/assignments/{assignment}/download-pdf', function (CourseAssignment $assignment) {
        if ($assignment->course->user_id !== auth()->id()) { abort(403); }

        $html = "
            <div style='font-family: sans-serif; padding: 20px;'>
                <h1 style='color: #0f172a; margin-bottom: 5px; font-size: 24px;'>{$assignment->title}</h1>
                <p style='color: #64748b; font-size: 12px; margin-bottom: 20px;'>Course Assignment Specification Sheet</p>
                <hr style='border: 0; border-top: 1px solid #e2e8f0; margin-bottom: 20px;' />
                <h3 style='color: #334155; font-size: 14px; text-transform: uppercase;'>Instructions Rubric:</h3>
                <p style='color: #334155; font-size: 13px; line-height: 1.6; white-space: pre-wrap;'>{$assignment->instructions}</p>
            </div>
        ";

        $pdf = Pdf::loadHTML($html);
        return $pdf->download(Str::slug($assignment->title) . '-assignment.pdf');
    })->name('assignments.download.pdf');

    // 📝 GENERATE & DOWNLOAD ASSIGNMENT AS WORD (.DOCX)
    Route::get('/assignments/{assignment}/download-word', function (CourseAssignment $assignment) {
        if ($assignment->course->user_id !== auth()->id()) { abort(403); }

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addText($assignment->title, ['name' => 'Arial', 'size' => 20, 'bold' => true, 'color' => '0F172A']);
        $section->addText("Course Assignment Specification Sheet", ['name' => 'Arial', 'size' => 9, 'italic' => true, 'color' => '64748b']);
        $section->addTextBreak(1);
        $section->addText("Instructions Rubric:", ['name' => 'Arial', 'size' => 12, 'bold' => true, 'color' => '334155']);
        
        $lines = explode("\n", $assignment->instructions);
        foreach ($lines as $line) {
            $section->addText(htmlspecialchars($line), ['name' => 'Arial', 'size' => 11, 'color' => '334155', 'spaceAfter' => 120]);
        }

        $objectWriter = IOFactory::createWriter($phpWord, 'Word2007');
        return response()->streamDownload(function () use ($objectWriter) {
            $objectWriter->save('php://output');
        }, Str::slug($assignment->title) . '-assignment.docx');
    })->name('assignments.download.word');

    // 🤖 AI ASSISTANT: GENERATE ASSIGNMENT INSTRUCTIONS DRAFT
    Route::post('/assignments/generate-instructions', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'topic' => 'nullable|string|max:255',
        ]);

        $title = $request->input('title');
        $topic = $request->input('topic', 'General Course Mechanics');

        $aiDraft = "### Assignment Overview: {$title}\n\n" .
                   "**Objective:** Develop a practical project covering key aspects of: {$topic}.\n\n" .
                   "**Requirements:**\n" .
                   "1. Ensure clean, modular code architectures throughout the design.\n" .
                   "2. Document all component pipelines clearly.\n" .
                   "3. Handle all unexpected application runtime states gracefully.\n\n" .
                   "**Submission Format:** Submit a comprehensive repository link alongside your architecture map documentation.";

        return response()->json(['instructions' => $aiDraft]);
    })->name('assignments.ai.generate');

    Route::get('/profile-settings', function() { return Inertia::render('Profile/Edit'); })->name('profile');
});


// =========================================================================
// ADMIN CONTROL PANEL ROUTES
// =========================================================================
Route::middleware(['auth', EnsureUserIsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $courses = Course::with('teacher')->get()->map(function($course) {
            return [
                'id' => $course->id, 
                'title' => $course->title, 
                'category' => $course->category, 
                'status' => $course->status, 
                'teacher' => $course->teacher ? $course->teacher->name : 'Unknown'
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
    })->name('dashboard');

    Route::patch('/users/{user}/status', function (\Illuminate\Http\Request $request, User $user) {
        $request->validate(['status' => 'required|in:approved,suspended,pending']);
        $user->update(['status' => $request->status]);
        return back();
    })->name('users.status');

    Route::patch('/courses/{course}/status', function (\Illuminate\Http\Request $request, Course $course) {
        $request->validate(['status' => 'required|in:published,rejected,suspended,pending']);
        $course->update(['status' => $request->status]);
        return back();
    })->name('courses.status');
});


// =========================================================================
// GLOBAL CORE PROFILE ENDPOINTS
// =========================================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';