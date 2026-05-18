<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// 1. Move this line to the VERY top of the file (Outside the class block):
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'description',
        'user_id',
        'status',
    ];

    // Simple relationship lookup for the course owner teacher
    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 2. The relationship method stays clean inside the class:
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('sort_order', 'asc');
    }
}