<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Test;
use App\Models\Lesson;

class CoursePublication extends Model
{
    use HasFactory;

    protected $table = 'courses_publications';

    protected $fillable = [
        'course_id',
        'lesson_id',
        'test_id'
    ];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
