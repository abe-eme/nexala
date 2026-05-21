<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseAssignment;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    /**
     * Show assignment detail view.
     */
    public function show(Course $course)
    {
        // 1. CHECK IF ALREADY SUBMITTED
        $alreadySubmitted = DB::table('assignment_submissions')
            ->where('course_id', $course->id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($alreadySubmitted) {
            return redirect()->route('student.courses.show', $course->id)
                ->with('error', 'You have already uploaded your assignment.');
        }

        // Fetch the details of the assignment
        $assignment = CourseAssignment::where('course_id', $course->id)->first();

        return Inertia::render('Student/Assignments/Show', [
            'course' => $course,
            'assignment' => $assignment
        ]);
    }

    /**
     * Save the incoming file or code workspace text payload.
     */
    public function submit(Request $request, $courseId) 
    {
        $studentId = auth()->id();
        
        // CHECK: Has student already submitted?
        $existing = DB::table('assignment_submissions')
            ->where('user_id', $studentId)
            ->where('course_id', $courseId)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already submitted this assignment.');
        }

        // INSERT: Save submission
        DB::table('assignment_submissions')->insert([
            'user_id' => $studentId,
            'course_id' => $courseId,
            'assignment_id' => $request->assignment_id,
            'status' => 'pending', // Teacher must approve
            'created_at' => now()
        ]);

        return back()->with('success', 'Assignment submitted!');
    }
}
