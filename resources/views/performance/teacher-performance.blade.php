@extends('layouts.main')

@section('title', 'Успеваемость студентов')

@section('content')
@empty($users)
<h2 class="text-3xl text-center">Студентов пока нет. Для просмотра успеваемости должен появиться хотя бы один студент</h2>
@else
<div class="relative w-full sm:max-w-md">
    <input type="text" id="studentSearch" placeholder="Поиск по имени студента..."
           class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#17b292] focus:border-transparent">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-2.5 text-gray-400" fill="none"
         viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
    </svg>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach ($users as $user)
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 hover:shadow-lg transition-all student-card" data-name="{{ mb_strtolower($user->name) }}">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-[#17b292] flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="#17b292">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                {{ $user->name }}
            </h3>
        </div>

        <div class="space-y-3">
            <p class="text-gray-600">
                <span class="font-semibold">Пройдено тестов:</span>
                <span class="font-bold text-gray-800">{{ $user->passesTestsCount }}</span>
            </p>

            <p class="text-gray-600">
                <span class="font-semibold">Средняя оценка:</span>
                <span class="font-bold text-gray-800">
                    {{ number_format($user->avgEstimation, 2) }}
                </span>
            </p>

            <p class="text-gray-600">
                <span class="font-semibold">Средний балл:</span>
                <span class="font-bold text-gray-800">
                    {{ number_format($user->avgScore, 1) }}
                </span>
            </p>

            <div class="flex items-center text-sm text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Зарегистрирован: {{ $user->created_at->format('d.m.Y') }}
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end">
            <span class="px-3 py-1 rounded-lg bg-[#17b292] text-white"></span>
        </div>
    </div>
    @endforeach
</div>
<div class="mt-10">
    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2 mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-[#17b292]" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        История пройденных тестов
    </h2>

    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 space-y-4">
        @foreach ($users as $user)
        @if ($user->passesTests->count() < 1)
            <p>Ни один тест ещё не был пройден</p>
        @endif
            @foreach ($user->passesTests as $passedTest)
                <div class="flex items-center justify-between text-sm text-gray-600 border-b border-gray-100 pb-2 last:border-0">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="#17b292">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 10v4c0 1 2.686 3 6 3s6-2 6-3v-4"/>
                        </svg>

                        <span>
                            <span class="font-semibold text-gray-800">{{ $user->name }}</span>
                            прошёл тест <span class="font-semibold text-gray-800">{{ $passedTest->test->name }}</span> на оценку <span class="font-semibold text-gray-800">{{ $passedTest->estimation }}</span> с баллом <span class="font-semibold text-gray-800">{{ number_format($passedTest->score, 1)}}/{{ number_format($passedTest->test->max_score, 1)}}</span>
                        </span>
                    </div>

                    <span class="text-gray-500">
                        {{ $passedTest->created_at->format('d.m.Y H:i') }}
                    </span>
                </div>
            @endforeach
        @endforeach
    </div>
</div>
@endempty
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('studentSearch');
        const cards = document.querySelectorAll('.student-card');

        searchInput.addEventListener('input', function () {
            const query = this.value.trim().toLowerCase();

            cards.forEach(card => {
                const name = card.getAttribute('data-name');
                if (name.includes(query)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection
