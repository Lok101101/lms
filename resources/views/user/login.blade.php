@extends('layouts.main')

@section('title', 'Авторизация')

@section('content')
<div class="flex flex-col items-center">
    <h2 class="mb-4 font-bold text-3xl">Авторизация</h2>
    <form class="max-w-sm w-full" action="{{ @route('login') }}" method="POST">
        @csrf
        <div class="mb-2">
            <input type="email" id="email" name="email"
            class="bg-white-50 border text-slate-600 outline-none transition-all duration-200 focus:border-[#17b292] focus:ring-2 focus:ring-[#17b292]/20 hover:border-[#17b292] text-sm rounded-lg block w-full px-2.5 py-3"
            value="{{ @old('email') }}" placeholder="Электронная почта" required/>
        </div>
        <div class="mb-2">
            <input type="password" id="password" name="password"
            class="bg-white-50 border text-slate-600 outline-none transition-all duration-200 focus:border-[#17b292] focus:ring-2 focus:ring-[#17b292]/20 hover:border-[#17b292] text-sm rounded-lg block w-full px-2.5 py-3"
            value="{{ @old('password') }}" placeholder="Пароль" required/>
            @isset($loginError)
                <div class="mt-1 flex items-center text-sm text-red-600">
                    <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd"/>
                    </svg>
                    {{ $loginError }}
                </div>
            @endisset
        </div>
        <div class="mt-3">
            <button type="submit" class="px-4 py-2 bg-[#17b292] text-white rounded-lg hover:bg-[#11957a] transition-colors flex items-center cursor-pointer">
                Войти
                <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>
    </form>
</div>
@endsection
