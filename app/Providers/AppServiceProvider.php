<?php

namespace App\Providers;

use App\Models\Course;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('teacher', function () {
            return "<?php if (Auth::check() && trim(Auth::user()->role->name) === 'teacher'): ?>";
        });

        Blade::directive('endTeacher', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('student', function () {
            return "<?php if (Auth::check() && trim(Auth::user()->role->name) === 'student'): ?>";
        });

        Blade::directive('endStudent', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('isCourseCreator', function ($expression) {
            return "<?php if (Auth::check() && ({$expression} instanceof \App\Models\Course) && {$expression}->user_id === Auth::id()): ?>";
        });

        Blade::directive('endIsCourseCreator', function () {
            return "<?php endif; ?>";
        });
    }
}
