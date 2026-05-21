<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $fillable = ['user_id', 'course_id', 'answers', 'score'];

    // This converts the JSON database column back into a clean PHP array automatically
    protected $casts = [
        'answers' => 'array',
    ];
    // app/Models/QuizAttempt.php
public function user()
{
    return $this->belongsTo(User::class);
}
}