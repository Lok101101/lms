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
                ['lesson' => 'Что такое Laravel?', 'test' => 'Что такое Laravel?'],
                ['lesson' => 'Маршрутизация и контроллеры в Laravel', 'test' => 'Маршрутизация и контроллеры'],
                ['lesson' => 'Погружение в Eloquent ORM', 'test' => 'Eloquent ORM'],
            ],
            'js' => [
                ['lesson' => 'Введение в JavaScript: история и назначение', 'test' => 'Введение в JavaScript: история и назначение'],
                ['lesson' => 'Основы синтаксиса и структуры JavaScript', 'test' => 'Основы синтаксиса JavaScript'],
                ['lesson' => 'Переменные, типы данных и преобразования в JavaScript', 'test' => 'Переменные, типы данных и преобразования в JavaScript'],
            ],
            'php' => [
                ['lesson' => 'Введение в PHP: история и применение', 'test' => 'Введение в PHP: история и применение'],
                ['lesson' => 'Основы синтаксиса PHP', 'test' => 'Основы синтаксиса PHP'],
                ['lesson' => 'Управляющие конструкции и функции в PHP', 'test' => 'Управляющие конструкции и функции в PHP'],
            ],
            'sql' => [
                ['lesson' => 'Введение в SQL: история и назначение', 'test' => 'Введение в SQL: история и назначение'],
                ['lesson' => 'Основные команды SELECT, INSERT, UPDATE, DELETE', 'test' => 'Основные команды SELECT, INSERT, UPDATE, DELETE'],
                ['lesson' => 'Создание и изменение таблиц в SQL', 'test' => 'Создание и изменение таблиц в SQL'],
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
