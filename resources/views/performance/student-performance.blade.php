@extends('layouts.main')

@section('title', 'Моя успеваемость')

@section('content')
@isset($passesTests)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach ($passesTests as $passedTest)
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 hover:shadow-lg transition-all">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-[#17b292] flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="#17b292">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                {{ $passedTest->test->name }}</h3>
        </div>
        <p class="text-gray-600 mb-4">Оценка: <span class="font-semibold text-gray-800">{{ $passedTest->estimation }} / 5</span></p>
        <p class="text-gray-600 mb-4">Баллы: <span class="font-semibold text-gray-800">{{ number_format($passedTest->score, 1)}} / {{ number_format($passedTest->test->max_score) }}</span></p>
        <div class="flex items-center justify-between text-sm text-gray-500">
            <div class="flex">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ $passedTest->created_at->format('d.m.Y') }}
            </div>
            <span class="bg-[#17b292] text-white px-3 py-1 rounded-lg">Пройден</span>
        </div>
    </div>
    @endforeach
</div>
@else
<h2 class="text-3xl text-center"><span class="font-bold">{{ Auth::user()->name}}</span>, вы ещё не прошли ни одного теста. Пройдите хотя бы один тест, чтобы узнать свою успеваемость</h2>
@endisset
@endsection
