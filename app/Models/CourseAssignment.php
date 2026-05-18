<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseAssignment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'title',        // 🟢 Added to fix your exact error!
        'instructions', // 🟢 Added so instructions can save safely too!
    ];

    /**
     * Get the course that owns this project assignment.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}