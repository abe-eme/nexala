<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseQuiz;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    /**
     * Display the quiz page to the student.
     */
    public function show(Course $course)
    {
        $alreadyTaken = DB::table('quiz_attempts')
            ->where('course_id', $course->id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($alreadyTaken) {
            return redirect()->route('student.courses.show', $course->id)
                ->with('error', 'You have already completed this quiz.');
        }

        $quizzes = CourseQuiz::where('course_id', $course->id)->get();

        return Inertia::render('Student/Quizzes/Show', [
            'course' => $course,
            'quizzes' => $quizzes
        ]);
    }

    /**
     * Process, score, and save the quiz answers.
     */
    public function submit(Course $course, Request $request)
    {
        // 1. Verify submission state
        $alreadyTaken = DB::table('quiz_attempts')
            ->where('course_id', $course->id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($alreadyTaken) {
            return redirect()->route('student.courses.show', $course->id)
                ->with('error', 'This quiz has already been submitted.');
        }

        // 2. Validate input
        $request->validate([
            'answers' => 'required|array'
        ]);

        $studentAnswers = $request->input('answers');
        $quizQuestions = CourseQuiz::where('course_id', $course->id)->get();
        $totalQuestions = $quizQuestions->count();
        $correctCount = 0;

        // 3. Scoring logic
        if ($totalQuestions > 0) {
            foreach ($quizQuestions as $question) {
                // Compare student choice with database correct_option
                if (isset($studentAnswers[$question->id]) && 
                    strtoupper(trim($studentAnswers[$question->id])) === strtoupper(trim($question->correct_option))) {
                    $correctCount++;
                }
            }
            $calculatedScore = round(($correctCount / $totalQuestions) * 100);
        } else {
            $calculatedScore = 100;
        }

        // 4. Save record with correct array syntax
        DB::table('quiz_attempts')->insert([
            'user_id'    => auth()->id(),
            'course_id'  => $course->id,
            'score'      => $calculatedScore,
            'answers'    => json_encode($studentAnswers),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('student.courses.show', $course->id)
            ->with('success', "Quiz processed successfully! Score: {$calculatedScore}%");
    }
}