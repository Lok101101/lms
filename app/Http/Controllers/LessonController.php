<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function createLesson(Request $request) {
        Lesson::create([...$request->all(), 'user_id' => Auth::id()]);

        return redirect()->route('getMyLessons');
    }

    public function getMyLessons(Course $course = null) {
        $lessons = Lesson::where('user_id', '=', Auth::id())->orderBy('created_at', 'desc')->get();

        if ($course != null) {
            return view('lessons.my-lessons', ['lessons' => $lessons, 'course' => $course]);
        }

        return view('lessons.my-lessons', ['lessons' => $lessons]);
    }

    public function changeLesson(Request $request, Lesson $lesson) {
        $lesson->update(['content' => $request->content]);

        return redirect()->route('getMyLessons');
    }

    public function getLessonConstructor(Lesson $lesson = null) {
        if ($lesson != null) {
            return view('lessons.lesson-constructor', ['lesson' => $lesson]);
        }

        return view('lessons.lesson-constructor');
    }

    public function getLesson(Lesson $lesson) {
        return view('lessons.lesson', ['lesson' => $lesson]);
    }

    public function deleteLesson(Lesson $lesson) {
        $lesson->delete();

        return redirect()->back();
    }
}
