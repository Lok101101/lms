<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\PassedTest;
use App\Models\User;
use App\Models\Role;
use \Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function registerUser(RegisterRequest $request) {
        $user = User::create([
            ...$request->validated(),
            'password' => Hash::make($request->password),
            'role_id' => Role::where('name', '=', 'student')->first()->id
        ]);

        Auth::login($user);

        return redirect()->route('courses');
    }

    public function loginUser(LoginRequest $request){
        if (Auth::attempt($request->validated())) {
            return redirect()->route('courses');
        }

        return view('user.login', ['loginError' => 'Неправильная почта или пароль, попробуйте ещё раз']);
    }

    public function logoutUser() {
        Auth::logout();

        return redirect()->route('login');
    }

    public function getUserPerformance(User $user = null) {
        $passesTests = PassedTest::where('user_id', '=', Auth::id())->get();

        $estimations = [];
        foreach ($passesTests as $passedTest) {
            array_push($estimations, $passedTest->estimation);
        }

        if (count($passesTests) !== 0) {
            return view('performance.student-performance', ['passesTests' => $passesTests]);
        }

        return view('performance.student-performance');
    }

    public function getAllUsersPerformance() {
        $users = User::where('role_id', '=', Role::where('name', '=', 'student')->first()->id)->get();

        return view('performance.teacher-performance', ['users' => $users]);
    }
}
