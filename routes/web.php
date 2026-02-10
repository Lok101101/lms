<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('courses');
});

Route::get('/courses', [CourseController::class, 'getCourses'])->name('courses');

Route::middleware('auth')->group(function () {
    Route::get('/logout', [UserController::class, 'logoutUser'])->name('logout');

    Route::middleware('checkTeacher')->group(function () {
        Route::post('/courses/delete/{course}', [CourseController::class, 'deleteCourse'])->name('deleteCourse');
        Route::post('/courses/publications/delete/{coursePublication}', [CourseController::class, 'deleteCoursePublication'])->name('deleteCoursePublication');
        Route::get('courses/{course}/add/tests', [TestController::class, 'getMyTests'])->name('testsListForAddToCourse');
        Route::post('/courses/{course}/add/{test}', [CourseController::class, 'addTestToCourse'])->name('addTestToCourse');
        Route::view('/courses/create','courses.create-course')->name('createCoursePage');
        Route::post('/courses/create', [CourseController::class, 'createCourse'])->name('create-course');

        Route::get('/tests', [TestController::class, 'getMyTests'])->name('getMyTests');
        Route::view('/tests/constructor', 'tests.test-constructor')->name('testConstructor');
        Route::get('/tests/{test}/constructor', [TestController::class, 'changeTest'])->name('changeTest');
        Route::post('/tests/create', [TestController::class, 'createTest'])->name('createTest');
        Route::post('/tests/save', [TestController::class, 'saveTest'])->name('saveTest');
        Route::get('/tests/delete/{test}', [TestController::class, 'deleteTest'])->name('deleteTest');

        Route::get('/performance/students', [UserController::class, 'getAllUsersPerformance'])->name('getAllUsersPerformance');
    });

    Route::middleware('checkStudent')->group(function () {
        Route::get('/tests/{test}', [TestController::class, 'getTest'])->name('getTest');
        Route::post('/tests/{test}/complete', [TestController::class, 'completeTest'])->name('completeTest');

        Route::get('/performance', [UserController::class, 'getUserPerformance'])->name('getUserPerformance');
    });

    Route::get('/courses/{course}', [CourseController::class, 'getCoursePublications'])->name('getCoursePublications');
});

Route::middleware('guest')->group(function () {
    Route::view('/register', 'user.register')->name('register');
    Route::post('/register', [UserController::class, 'registerUser'])->name('register');
    Route::view('/login', 'user.login')->name('login');
    Route::post('/login', [UserController::class, 'loginUser'])->name('login');
});
