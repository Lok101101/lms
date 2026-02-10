<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\CoursePublication;
use App\Models\Test;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function createCourse(CourseRequest $request) {
        Course::create([...$request->validated(), 'user_id' => Auth::id()]);

        return redirect()->route('courses');
    }

    public function getCourses() {
        return view('courses.courses-list', ['courses' => Course::get()]);
    }

    public function deleteCourse(Course $course) {
        $course->delete();

        return redirect()->route('courses');
    }

    public function getCoursePublications(Course $course) {
        foreach ($course->publications as $publication) {
            if ($publication->test === null) continue;

            if ($publication->test->passesTests !== null) {
                $isPassedByUser = $publication->test->passesTests->where('user_id', '=',Auth::id())->isNotEmpty();

                $publication->test->setAttribute('isPassedByUser', $isPassedByUser);
            }
        }

        return view('courses.course', ['course' => $course]);
    }

    public function deleteCoursePublication(CoursePublication $coursePublication) {
        $coursePublication->delete();

        return redirect()->back();
    }

    public function addTestToCourse(Course $course, Test $test) {
        CoursePublication::create([
            'course_id' => $course->id,
            'test_id' => $test->id
        ]);

        return redirect()->route('getCoursePublications', ['course' => $course]);
    }
}
