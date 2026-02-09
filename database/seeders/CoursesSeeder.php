<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'name' => 'Laravel для начинающих',
                'description' => 'В этом курсе вы познакомитесь с популярным PHP-фреймворком Laravel. Вас ждёт обзор структуры проектов, маршрутизации, контроллеров и возможностей Eloquent ORM для удобной работы с базой данных.',
                'user_id' => User::where('name', '=', 'teacher')->first()->id,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Основы JavaScript',
                'description' => 'Вы изучите историю языка, его назначение, базовый синтаксис, работу с переменными и типами данных, а также научитесь использовать JavaScript для создания интерактивных веб-страниц.',
                'user_id' => User::where('name', '=', 'teacher')->first()->id,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'PHP: Основы программирования',
                'description' => 'История, синтаксис и базовые возможности языка PHP. Вы узнаете, как создавать переменные, работать с основными управляющими конструкциями и писать простые функции для динамических веб-сайтов.',
                'user_id' => User::where('name', '=', 'teacher')->first()->id,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Базы данных и SQL',
                'description' => 'Этот курс познакомит вас с основами языка SQL — стандартного инструмента для работы с реляционными базами данных. Вы научитесь создавать таблицы, выполнять запросы на выборку, добавление, изменение и удаление данных, а также узнаете об истории и возможностях SQL.',
                'user_id' => User::where('name', '=', 'teacher')->first()->id,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('courses')->insert($courses);
    }
}
