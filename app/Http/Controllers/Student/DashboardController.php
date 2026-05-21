<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Structural Metric Computations
        $enrolledCount = $user->enrolledCourses()->count();
        $completedCount = $user->enrolledCourses()->where('course_user.status', 'completed')->count();
        
        $totalLessonsCompleted = $user->completedLessons()->count();

        // 2. Compute Historical Growth Series for Activity Charts (Grouped by Date)
        $activityChartData = DB::table('lesson_user')
            ->where('user_id', $user->id)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->take(7)
            ->get();

        // 3. Compile Active Progression Streams
        $recentCourses = $user->enrolledCourses()
            ->with(['lessons', 'teacher'])
            ->withCount('lessons')
            ->take(3)
            ->get()
            ->map(function ($course) use ($user) {
                $courseLessonIds = $course->lessons->pluck('id')->toArray();
                $completedInCourse = $user->completedLessons()
                    ->whereIn('lesson_id', $courseLessonIds)
                    ->count();

                $course->completed_lessons_count = $completedInCourse;
                $course->progress_percentage = $course->lessons_count > 0 
                    ? round(($completedInCourse / $course->lessons_count) * 100) 
                    : 0;
                return $course;
            });

        return Inertia::render('Student/Dashboard', [
            'metrics' => [
                'enrolled_count' => $enrolledCount,
                'completed_count' => $completedCount,
                'lessons_completed' => $totalLessonsCompleted
            ],
            'recentCourses' => $recentCourses,
            'chartData' => $activityChartData
        ]);
    }
}