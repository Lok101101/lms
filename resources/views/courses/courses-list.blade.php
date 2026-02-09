@extends('layouts.main')

@section('title', 'Курсы')

@section('content')

<div class="grid grid-cols-1 gap-6">
    @if ($courses->isEmpty())
        <h2 class="text-center text-bold text-3xl">Пока нет ни одного курса
        <div class="flex justify-center mt-5">
            @teacher
            <a href="{{ route('createCoursePage') }}"
               class="px-5 py-2.5 bg-[#17b292] text-white rounded-lg hover:bg-[#11957a] transition-colors flex items-center gap-1">
                Новый курс
                <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
            @endTeacher
        </div>
    @else
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div class="relative w-full sm:max-w-md">
            <input type="text" id="courseSearch" placeholder="Поиск по названию курса..."
                   class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#17b292] focus:border-transparent">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-2.5 text-gray-400" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        @teacher
        <a href="{{ route('createCoursePage') }}"
           class="px-5 py-2.5 bg-[#17b292] text-white rounded-lg hover:bg-[#11957a] transition-colors flex items-center justify-center sm:justify-start whitespace-nowrap">
            Новый курс
            <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 w-4 h-4" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
        </a>
        @endTeacher
    </div>

        @foreach($courses as $course)
            <div class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg w-full border border-gray-100 course-card"
            data-name="{{ mb_strtolower($course->name) }}">
                <div class="bg-[#17b292] px-6 py-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="white">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <h2 class="text-xl font-bold text-white">{{ $course->name }}</h2>
                </div>

                <div class="p-6">
                    <div class="flex justify-between gap-4 mb-4">
                        <p class="text-gray-600 mb-6 line-clamp-3 w-230">
                            {{ $course->description ?? '' }}
                        </p>

                        <span class="text-sm text-gray-500 flex">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $course->created_at->format('d.m.Y') }}
                        </span>
                    </div>

                    <div class="flex justify-end gap-2">
                        @teacher
                            @isCourseCreator($course)
                            <form action="{{ route('deleteCourse', $course) }}" method="post">
                                @csrf
                                <button class="px-4 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors flex items-center gap-1 cursor-pointer">
                                    Удалить
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                            @endIsCourseCreator
                        @endTeacher
                        <a href="{{ route('getCoursePublications', $course) }}"
                           class="px-4 py-2 bg-[#17b292] text-white rounded-lg hover:bg-[#11957a] transition-colors flex items-center">
                            Посмотреть
                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('courseSearch');
        const cards = document.querySelectorAll('.course-card');

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
