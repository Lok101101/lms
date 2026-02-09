<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'student',
            'email' => 'student@mail.ru',
            'role_id' => Role::where('name', '=', 'student')->first()->id,
            'password' => Hash::make('student'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('users')->insert([
            'name' => 'teacher',
            'email' => 'teacher@mail.ru',
            'role_id' => Role::where('name', '=', 'teacher')->first()->id,
            'password' => Hash::make('teacher'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
