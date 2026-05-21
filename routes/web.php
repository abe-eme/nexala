<?php

use App\Http\Controllers\ProfileController;
use App\Http\Middleware\EnsureUserIsAdmin;    
use App\Http\Middleware\EnsureUserIsTeacher;   
use App\Http\Controllers\Student\CourseController;
use App\Http\Controllers\Student\AssignmentController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\QuizController; 
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;

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

// =========================================================================
// STUDENT WORKSPACE ROUTE HUB (WIRED DIRECTLY TO CONTROLLERS)
// =========================================================================
Route::middleware(['auth', 'verified'])->prefix('student')->name('student.')->group(function () {
    
    // Core Dashboard Analytics
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Academic Course Library & Lecture Rooms
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    
    // Enrollment Engine
    Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
    Route::post('/courses/{course}/unenroll', [CourseController::class, 'unenroll'])->name('courses.unenroll');
    
    // Lesson Navigation & Progress Tracking
    Route::get('/courses/{course}/lessons/{lesson}', [CourseController::class, 'showLesson'])->name('lessons.show');
    Route::post('/lessons/{lesson}/complete', [CourseController::class, 'completeLesson'])->name('lessons.complete');

    // High-Security Quiz Portal (With Anti-Cheat Frontend Synchronization)
    Route::get('/courses/{course}/quiz', [QuizController::class, 'show'])->name('quizzes.show');
    Route::post('/courses/{course}/quiz/submit', [QuizController::class, 'submit'])->name('quizzes.submit');

    // Tasks & Assignments Hub (File Upload Views & Submissions)
    Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/courses/{course}/assignment', [AssignmentController::class, 'show'])->name('assignments.show');
    Route::post('/courses/{course}/assignment/submit', [AssignmentController::class, 'submit'])->name('assignments.submit');

    // Credential Generation Download Engine
    Route::get('/courses/{course}/certificate/download', [CourseController::class, 'downloadCertificate'])->name('certificate.download');
});

// FALLBACK ROOT DASHBOARD REDIRECTOR
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// =========================================================================
// TEACHER WORKSPACE ROUTE HUB (ALL TEACHER & AI TOOLS LIVE INSIDE HERE)
// =========================================================================
Route::middleware(['auth', EnsureUserIsTeacher::class])->prefix('teacher')->name('teacher.')->group(function () {
    
    Route::get('/dashboard', function () {
        return Inertia::render('Teacher/Dashboard', [
            'recentCourses' => [], 'dropoutAlerts' => [], 'totalStudents' => 0, 'totalCourses' => 0
        ]);
    })->name('dashboard');

    // =========================================================================
    // FULL FIXED: SYNCHRONOUS GRADING EVALUATION TERMINAL (Unified Stream Engine)
    // =========================================================================
    Route::get('/submissions', function () {
        $teacherId = auth()->id();

        // 1. Hydrate File-Based Student Assignment Pipelines
        $submissions = DB::table('assignment_submissions')
            ->join('users', 'assignment_submissions.user_id', '=', 'users.id')
            ->join('courses', 'assignment_submissions.course_id', '=', 'courses.id')
            ->where('courses.user_id', $teacherId)
            ->select(
                'assignment_submissions.*', 
                'users.name as student_name', 
                'users.email as student_email', 
                'courses.title as course_title'
            )
            ->orderBy('assignment_submissions.created_at', 'desc')
            ->get();

        // 2. Hydrate Automated Quiz Performance Audit Trackers
        $quizAttempts = DB::table('quiz_attempts')
            ->join('users', 'quiz_attempts.user_id', '=', 'users.id')
            ->join('courses', 'quiz_attempts.course_id', '=', 'courses.id')
            ->where('courses.user_id', $teacherId)
            ->select(
                'quiz_attempts.*',
                'users.name as student_name',
                'users.email as student_email',
                'courses.title as course_title'
            )
            ->orderBy('quiz_attempts.created_at', 'desc')
            ->get();

        return Inertia::render('Teacher/GradingQueue', [
            'submissions' => $submissions,
            'quizAttempts' => $quizAttempts
        ]);
    })->name('submissions.index');

    // Process Assignment Feedback Rubrics & Metrics
    Route::post('/submissions/{id}/evaluate', function ($id, Request $request) {
        $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string',
            'status' => 'required|in:approved,rejected'
        ]);
        
        DB::table('assignment_submissions')->where('id', $id)->update([
            'score' => $request->score,
            'feedback' => $request->feedback,
            'status' => $request->status,
            'updated_at' => now()
        ]);
        
        return back();
    })->name('submissions.evaluate');

    // AUDIT AND OVERWRITE QUIZ PERFORMANCES (FIXED AGAINST 42S22 UNKNOWN COLUMN EXCEPTIONS)
    Route::post('/quizzes/{id}/evaluate', function ($id, Request $request) {
        $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string',
            'status' => 'required|string'
        ]);
        
        DB::table('quiz_attempts')->where('id', $id)->update([
            'score' => $request->score,
            'feedback' => $request->feedback ?? null, // Prevents blank text-area save strings
            'status' => (string) $request->status,    // String cast normalization
            'updated_at' => now()
        ]);
        
        return back();
    })->name('quizzes.evaluate');
    // =========================================================================

    // INTEGRATED: SELF-PACED LESSON SEQUENCER RULES ENGINE
    Route::put('/lessons/{lesson}/update-rules', function (Lesson $lesson, Request $request) {
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
    })->name('lessons.update-rules');

    // COURSES MANAGEMENT
    Route::get('/courses', function () {
        return Inertia::render('Teacher/CourseTable', [
            'courses' => Course::where('user_id', auth()->id())->get()
        ]);
    })->name('courses.index');

    Route::get('/courses/create', function () {
        return Inertia::render('Teacher/CourseCreate');
    })->name('courses.create');
    
    Route::post('/courses', function (Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);
        Course::create(array_merge($validated, ['user_id' => auth()->id(), 'status' => 'pending']));
        return redirect()->route('teacher.courses.index');
    })->name('courses.store');

    Route::get('/courses/{course}/edit', function (Course $course) {
        if ($course->user_id !== auth()->id()) { abort(403); }
        return Inertia::render('Teacher/courseedit', [
            'course' => $course
        ]);
    })->name('courses.edit');

    Route::put('/courses/{course}', function (Course $course, Request $request) {
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

    // ISOLATED LESSONS/MODULES MANAGEMENT PAGE
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

    Route::post('/courses/{course}/lessons', function (Course $course, Request $request) {
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

    // AI ASSISTANT: GENERATE LESSON CONTENT DRAFT DIRECTLY
    Route::post('/lessons/generate-content', function (Request $request) {
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
    })->name('lessons.ai.generate');

    // QUIZ ARCHITECTURE BUILDER
    Route::get('/courses/{course}/quizzes', function (Course $course) {
        if ($course->user_id !== auth()->id()) { abort(403); }
        
        $attempts = DB::table('quiz_attempts')
            ->join('users', 'quiz_attempts.user_id', '=', 'users.id')
            ->where('quiz_attempts.course_id', $course->id)
            ->select([
                'quiz_attempts.id as id',
                'quiz_attempts.score as score',
                'quiz_attempts.created_at as created_at',
                'users.name as student_name', 
                'users.email as student_email'
            ])
            ->orderBy('quiz_attempts.created_at', 'desc')
            ->get();

        return Inertia::render('Teacher/Quizzes/Index', [
            'course' => $course,
            'quizzes' => CourseQuiz::where('course_id', $course->id)->get(),
            'attempts' => $attempts 
        ]);
    })->name('courses.quizzes');

    Route::get('/courses/{course}/quizzes/create', function (Course $course) {
        if ($course->user_id !== auth()->id()) { abort(403); }
        return Inertia::render('Teacher/Quizzes/Create', ['course' => $course]);
    })->name('courses.quizzes.create');

    Route::post('/courses/{course}/quizzes', function(Course $course, Request $request) { 
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

    // ASSIGNMENTS MANAGEMENT STRAT
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

    Route::post('/courses/{course}/assignments', function(Course $course, Request $request) { 
        if ($course->user_id !== auth()->id()) { abort(403); }
        $validated = $request->validate(['title' => 'required|string|max:255', 'instructions' => 'required|string']);
        CourseAssignment::create(array_merge($validated, ['course_id' => $course->id]));
        return redirect()->route('teacher.courses.assignments', $course->id);
    })->name('courses.assignments.store');

    // SUBMISSIONS BY ASSIGNMENT
    Route::get('/courses/{course}/assignments/{assignment}/submissions', function (Course $course, CourseAssignment $assignment) {
        if ($course->user_id !== auth()->id()) { abort(403); }
        
        $submissions = DB::table('assignment_submissions')
            ->join('users', 'assignment_submissions.user_id', '=', 'users.id')
            ->where('assignment_submissions.course_id', $course->id)
            ->select('assignment_submissions.*', 'users.name as student_name', 'users.email as student_email')
            ->get();

        return Inertia::render('Teacher/Assignments/Submissions', [
            'course' => $course,
            'assignment' => $assignment,
            'submissions' => $submissions 
        ]);
    })->name('assignments.submissions');

    // GENERATE & DOWNLOAD ASSIGNMENT AS PDF
    Route::get('/assignments/{assignment}/download-pdf', function (CourseAssignment $assignment) {
        if ($assignment->course->user_id !== auth()->id()) { abort(403); }

        $html = "<div style='font-family: sans-serif; padding: 20px;'>
                    <h1 style='color: #0f172a; margin-bottom: 5px;'>{$assignment->title}</h1>
                    <p style='color: #64748b; font-size: 12px;'>Course Assignment Specification Sheet</p>
                    <hr style='border: 0; border-top: 1px solid #e2e8f0; margin-bottom: 20px;' />
                    <h3>Instructions Rubric:</h3>
                    <p style='white-space: pre-wrap;'>{$assignment->instructions}</p>
                 </div>";

        $pdf = Pdf::loadHTML($html);
        return $pdf->download(Str::slug($assignment->title) . '-assignment.pdf');
    })->name('assignments.download.pdf');

    // GENERATE & DOWNLOAD ASSIGNMENT AS WORD (.DOCX)
    Route::get('/assignments/{assignment}/download-word', function (CourseAssignment $assignment) {
        if ($assignment->course->user_id !== auth()->id()) { abort(403); }

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText($assignment->title, ['name' => 'Arial', 'size' => 20, 'bold' => true]);
        $section->addTextBreak(1);
        
        $lines = explode("\n", $assignment->instructions);
        foreach ($lines as $line) {
            $section->addText(htmlspecialchars($line), ['name' => 'Arial', 'size' => 11]);
        }

        $objectWriter = IOFactory::createWriter($phpWord, 'Word2007');
        return response()->streamDownload(function () use ($objectWriter) {
            $objectWriter->save('php://output');
        }, Str::slug($assignment->title) . '-assignment.docx');
    })->name('assignments.download.word');

    // AI ASSISTANT: GENERATE ASSIGNMENT INSTRUCTIONS DRAFT
    Route::post('/assignments/generate-instructions', function (Request $request) {
        $request->validate(['title' => 'required|string|max:255', 'topic' => 'nullable|string|max:255']);
        $title = $request->input('title');
        $topic = $request->input('topic', 'General Course Mechanics');

        $aiDraft = "### Assignment Overview: {$title}\n\n**Objective:** Project covering: {$topic}.\n\n**Requirements:**\n1. Ensure clean, modular code architectures.";
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

    Route::patch('/users/{user}/status', function (Request $request, User $user) {
        $request->validate(['status' => 'required|in:approved,suspended,pending']);
        $user->update(['status' => $request->status]);
        return back();
    })->name('users.status');

    Route::patch('/courses/{course}/status', function (Request $request, Course $course) {
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