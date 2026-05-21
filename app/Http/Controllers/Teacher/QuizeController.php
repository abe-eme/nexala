<?php
namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string',
            'questions' => 'required|array'
        ]);

        $quiz = Quiz::create([
            'course_id' => $request->course_id,
            'title' => $request->title,
        ]);

        foreach ($request->questions as $q) {
            $question = $quiz->questions()->create(['question_text' => $q['text']]);
            foreach ($q['options'] as $o) {
                $question->options()->create([
                    'option_text' => $o['text'],
                    'is_correct' => $o['is_correct']
                ]);
            }
        }

        return back()->with('success', 'Quiz created successfully.');
    }
}