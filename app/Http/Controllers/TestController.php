<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\PassedTest;
use App\Models\Test;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function createTest(Request $request) {
        Test::create([...$request->all(), 'user_id' => Auth::id()]);

        return redirect()->route('getMyTests');
    }

    public function getMyTests(Course $course = null) {
        $tests = Test::where('user_id', '=', Auth::id())->orderBy('created_at', 'desc')->get();

        if ($course != null) {
            return view('tests.my-tests', ['tests' => $tests, 'course' => $course]);
        }

        return view('tests.my-tests', ['tests' => $tests]);
    }

    public function changeTest(Test $test) {
        return view('tests.test-change-constructor', ['test' => $test]);
    }

    public function saveTest(Request $request) {
        Test::where('id', '=', $request->id)->update(['content' => $request->content, 'max_score' => $request->max_score]);

        return redirect()->route('getMyTests');
    }

    public function getTest(Test $test) {
        return view('tests.test', ['test' => $test]);
    }

    public function deleteTest(Test $test) {
        $test->delete();

        return redirect()->route('getMyTests');
    }

    public function completeTest(Test $test, Request $request) {
        PassedTest::create([
            'user_id' => Auth::id(),
            'test_id' => $test->id,
            'estimation' => $request->estimation,
            'score' => $request->score
        ]);

        return redirect()->route('getUserPerformance');
    }
}
