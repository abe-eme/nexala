<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable {
    protected $fillable = ['name', 'email', 'password', 'role', 'status'];

    public function enrolledCourses(): BelongsToMany {
        return $this->belongsToMany(Course::class, 'course_user')
                    ->withPivot('status', 'certificate_status', 'final_grade')
                    ->withTimestamps();
    }

    public function completedLessons(): BelongsToMany {
        return $this->belongsToMany(Lesson::class, 'lesson_user')->withTimestamps();
    }
    // app/Models/User.php

// ... Keep your existing filling rules and enrolledCourses code lines

    /**
     * Link to all theoretical quiz records submitted by the student.
     */
    public function quizSubmissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(QuizSubmission::class);
    }

    /**
     * Link to all practical project payloads uploaded by the student.
     */
    public function assignmentSubmissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
