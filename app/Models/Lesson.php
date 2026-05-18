<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'sort_order',
        'media_type',
        'text_content',
        'media_path',
    ];

    // Each lesson belongs directly to a single parent Course
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}