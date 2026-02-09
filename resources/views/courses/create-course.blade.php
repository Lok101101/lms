@extends('layouts.main')

@section('title', 'Создать курс')

@section('content')
<form class="max-w-sm" action="{{ @route('create-course') }}" method="POST">
    @csrf
    <div class="mb-3">
        <h4 class="mb-1">Название</h4>
        <input type="name" id="name" name="name"
        class="@error("name") border-red-500 @else border-gray-300 @enderror bg-white-50 border text-slate-600 outline-none transition-all duration-200 focus:border-[#17b292] focus:ring-2 focus:ring-[#17b292]/20 hover:border-[#17b292] text-sm rounded-lg block w-full px-2.5 py-3"
        value="{{ @old('name') }}" placeholder="" required>
        @error('name')
        <div class="mt-1 flex items-center text-sm text-red-600">
            <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd"/>
            </svg>
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="mb-3">
        <h4 class="mb-1">Описание (по желанию, до 250 символов)</h4>
        <textarea id="description" name="description"
        class="@error("description") border-red-500 @else border-gray-300 @enderror bg-white-50 border text-slate-600 outline-none transition-all duration-200 focus:border-[#17b292] focus:ring-2 focus:ring-[#17b292]/20 hover:border-[#17b292] text-sm rounded-lg block w-full px-2.5 py-3 placeholder-gray-400"
        placeholder="">
        {{ @old('description') }}
        </textarea>
        @error('description')
        <div class="mt-1 flex items-center text-sm text-red-600">
            <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd"/>
            </svg>
            {{ $message }}
        </div>
        @enderror
    </div>
    <button type="submit" class="px-4 py-2 bg-[#17b292] text-white rounded-lg hover:bg-[#11957a] transition-colors flex items-center cursor-pointer">
        Создать курс
        <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
        </svg>
    </button>
</form>
@endsection
