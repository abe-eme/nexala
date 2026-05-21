<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class AssignmentController extends Controller
{
    public function index(Course $course)
    {
        if ($course->user_id !== auth()->id()) { abort(403); }

        return Inertia::render('Teacher/Assignments/Index', [
            'course' => $course,
            'assignments' => CourseAssignment::where('course_id', $course->id)->get()
        ]);
    }

    public function create(Course $course)
    {
        if ($course->user_id !== auth()->id()) { abort(403); }

        return Inertia::render('Teacher/Assignments/Create', ['course' => $course]);
    }

    public function store(Course $course, Request $request)
    {
        if ($course->user_id !== auth()->id()) { abort(403); }

        $validated = $request->validate([
            'title' => 'required|string|max:255', 
            'instructions' => 'required|string'
        ]);

        CourseAssignment::create(array_merge($validated, ['course_id' => $course->id]));

        return redirect()->route('teacher.courses.assignments', $course->id);
    }

  public function submissions(Course $course, CourseAssignment $assignment)
{
    if ($course->user_id !== auth()->id()) { abort(403); }

    // Fetch submissions for this specific assignment
    $submissions = DB::table('assignment_submissions')
        ->join('users', 'assignment_submissions.user_id', '=', 'users.id')
        ->where('assignment_submissions.assignment_id', $assignment->id)
        ->select('assignment_submissions.*', 'users.name as student_name')
        ->get();

    return Inertia::render('Teacher/Assignments/Submissions', [
        'course' => $course,
        'assignment' => $assignment,
        'submissions' => $submissions // Now this will contain data
    ]);
}

    public function downloadPdf(CourseAssignment $assignment)
    {
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
    }

    public function downloadWord(CourseAssignment $assignment)
    {
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
    }

    public function generateInstructionsDraft(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255', 
            'topic' => 'nullable|string|max:255'
        ]);
        
        $title = $request->input('title');
        $topic = $request->input('topic', 'General Course Mechanics');

        $aiDraft = "### Assignment Overview: {$title}\n\n**Objective:** Project covering: {$topic}.\n\n**Requirements:**\n1. Ensure clean, modular code architectures.";
        return response()->json(['instructions' => $aiDraft]);
    }
    public function evaluate(Request $request, $submissionId)
{
    $request->validate([
        'score' => 'required|numeric|min:0|max:100',
        'feedback' => 'required|string'
    ]);

    DB::table('assignment_submissions')
        ->where('id', $submissionId)
        ->update([
            'score' => $request->score,
            'feedback' => $request->feedback,
            'status' => 'graded', // This triggers the student view to show feedback
            'updated_at' => now()
        ]);

    return back()->with('success', 'Assignment graded successfully.');
}
}