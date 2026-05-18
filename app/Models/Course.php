<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'category', 'status', 'user_id'];

    // This tells Laravel that a course belongs to a User (Teacher)
    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}