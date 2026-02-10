<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursePublicationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            'laravel' => 1,
            'js'      => 2,
            'php'     => 3,
            'sql'     => 4
        ];

        $structure = [
            'laravel' => [
                ['test' => 'Что такое Laravel?'],
                ['test' => 'Маршрутизация и контроллеры'],
                ['test' => 'Eloquent ORM'],
            ],
            'js' => [
                ['test' => 'Введение в JavaScript: история и назначение'],
                ['test' => 'Основы синтаксиса JavaScript'],
                ['test' => 'Переменные, типы данных и преобразования в JavaScript'],
            ],
            'php' => [
                ['test' => 'Введение в PHP: история и применение'],
                ['test' => 'Основы синтаксиса PHP'],
                ['test' => 'Управляющие конструкции и функции в PHP'],
            ],
            'sql' => [
                ['test' => 'Введение в SQL: история и назначение'],
                ['test' => 'Основные команды SELECT, INSERT, UPDATE, DELETE'],
                ['test' => 'Создание и изменение таблиц в SQL'],
            ],
        ];

        foreach ($structure as $courseKey => $pairs) {
            $courseId = $courses[$courseKey];
            foreach ($pairs as $pair) {
                $test = DB::table('tests')->where('name', $pair['test'])->first();
                if ($test) {
                    DB::table('courses_publications')->insert([
                        'course_id' => $courseId,
                        'test_id' => $test->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    dump("Тест не найден: {$pair['test']}");
                }
            }
        }
    }
}
