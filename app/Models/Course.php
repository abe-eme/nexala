<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model {
    protected $fillable = ['title', 'category', 'description', 'user_id', 'status'];

    public function teacher(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lessons(): HasMany {
        return $this->hasMany(Lesson::class)->orderBy('sort_order', 'asc');
    }

    public function quizzes(): HasMany { return $this->hasMany(CourseQuiz::class); }
    public function assignments(): HasMany { return $this->hasMany(CourseAssignment::class); }
}