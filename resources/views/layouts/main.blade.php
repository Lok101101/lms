<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="icon" href="{{ asset('favicon.svg') }}">
    <title>@yield('title')</title>

    <style>
    .current {
        background-color: white;
        box-shadow: 0 1px 2px 0 rgba(203, 213, 225, 0.5);
    }

    [x-cloak] { display: none !important; }
    </style>
</head>
<body>
<div x-data="{ mobileSidebarOpen: false }">
    <div id="page-container" class="mx-auto flex min-h-screen w-full min-w-[320px] flex-col bg-white lg:ps-64">
      <nav id="page-sidebar" class="fixed start-0 top-0 bottom-0 z-50 flex h-full w-80 flex-col overflow-auto bg-slate-100 transition-transform duration-500 ease-out lg:w-64 lg:ltr:translate-x-0 lg:rtl:translate-x-0" x-bind:class="{ 'ltr:-translate-x-full rtl:translate-x-full': !mobileSidebarOpen,'translate-x-0': mobileSidebarOpen, }" aria-label="Main Sidebar Navigation" x-cloak>
        <div class="flex h-20 w-full flex-none items-center justify-between pl-5 lg:justify-center lg:pl-0 lg:pr-4 px-3">
          <a href="{{ route('courses') }}" class="inline-flex items-center gap-2 text-lg font-bold tracking-wide text-slate-800 transition hover:opacity-75 active:opacity-100">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 inline-block pl-2 lg:pl-0" fill="#6A7A8F" viewBox="0 0 24 24">
            <path d="M12 3L1 9l11 6 9-4.91V17h2V9M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/>
          </svg>
          </a>
          <div class="lg:hidden">
            <button type="button" class="flex size-10 items-center justify-center text-slate-400 hover:text-slate-600 active:text-slate-400" x-on:click="mobileSidebarOpen = false">
              <svg class="hi-solid hi-x -mx-1 inline-block size-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
              </svg>
            </button>
          </div>
        </div>

        <div class="w-full grow space-y-3 p-4">
            <a href="{{ route('courses') }}" class="{{ request()->routeIs('courses') ? 'current' : ''}} flex items-center gap-3 rounded-lg px-4 py-2.5 font-semibold text-slate-600">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="#6A7A8F">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
          </svg>
            <span>Курсы</span>
            </a>
            @auth

            @teacher
            <a
            href="{{ route('getMyTests') }}"
            class="{{ request()->routeIs('getMyTests') ? 'current' : ''}} flex items-center gap-3 rounded-lg px-4 py-2.5 font-semibold text-slate-600 hover:bg-white hover:shadow-xs hover:shadow-slate-300/50 active:bg-white/75 active:text-slate-800 active:shadow-slate-300/10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="#6A7A8F">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            <span class="">Мои тесты</span>
            </a>
            <a
            href="{{ route('getAllUsersPerformance') }}"
            class="{{ request()->routeIs('getAllUsersPerformance') ? 'current' : ''}} flex items-center gap-3 rounded-lg px-4 py-2.5 font-semibold text-slate-600 hover:bg-white hover:shadow-xs hover:shadow-slate-300/50 active:bg-white/75 active:text-slate-800 active:shadow-slate-300/10">
          <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="#6A7A8F" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 20h18M7 16l3-3 2 2 4-5 2 1" />
            <path stroke-linecap="round" d="M7 16l3-3 2 2 4-5 2 1" />
          </svg>
            <span class="">Успеваемость</span>
            </a>
            @else
            <a href="{{ route('getUserPerformance') }}" class="{{ request()->routeIs('getUserPerformance') ? 'current' : ''}} flex items-center gap-3 rounded-lg px-4 py-2.5 font-semibold text-slate-600 hover:bg-white hover:shadow-xs hover:shadow-slate-300/50 active:bg-white/75 active:text-slate-800 active:shadow-slate-300/10">
          <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="#6A7A8F" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 20h18M7 16l3-3 2 2 4-5 2 1" />
            <path stroke-linecap="round" d="M7 16l3-3 2 2 4-5 2 1" />
          </svg>
            <span class="">Моя успеваемость</span>
            </a>
            @endTeacher
            @endauth
        </div>

        <div class="w-full flex-none space-y-3 p-4">
            @auth
            <a href="{{ route('logout') }}" class="flex items-center gap-3 rounded-lg px-4 py-2.5 font-semibold text-slate-600 hover:bg-white hover:shadow-xs hover:shadow-slate-300/50 active:bg-white/75 active:text-slate-800 active:shadow-slate-300/10">
                <svg class="bi bi-box-arrow-right inline-block size-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                </svg>
                <span>Выйти</span>
            </a>
            @endauth
            @guest
            <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'current' : ''}} flex items-center gap-3 rounded-lg px-4 py-2.5 font-semibold text-slate-600 hover:bg-white hover:shadow-xs hover:shadow-slate-300/50 active:bg-white/75 active:text-slate-800 active:shadow-slate-300/10">
            <svg class="bi bi-door-open inline-block size-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8.5 10c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1z"/>
                <path d="M10.828.122A.5.5 0 0 1 11 .5V1h.5A1.5 1.5 0 0 1 13 2.5V15h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V1.5a.5.5 0 0 1 .43-.495l7-1a.5.5 0 0 1 .398.117zM11.5 2H11v13h1V2.5a.5.5 0 0 0-.5-.5zM4 1.934V15h6V1.077l-6 .857z"/>
            </svg>
            <span>Войти</span>
            </a>
            <a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? 'current' : ''}} flex items-center gap-3 rounded-lg px-4 py-2.5 font-semibold text-slate-600 hover:bg-white hover:shadow-xs hover:shadow-slate-300/50 active:bg-white/75 active:text-slate-800 active:shadow-slate-300/10">
            <svg class="bi bi-person-plus inline-block size-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
            </svg>
            <span>Регистрация</span>
            </a>
            @endguest
        </div>
      </nav>

      <header id="page-header" class="fixed start-0 end-0 top-0 z-30 flex h-20 flex-none items-center bg-white shadow-xs lg:hidden">
        <div class="container mx-auto flex justify-between px-4 lg:px-8 xl:max-w-7xl">
        <a href="{{ route('courses') }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-10 h-10" fill="#6A7A8F" viewBox="0 0 24 24">
                <path d="M12 3L1 9l11 6 9-4.91V17h2V9M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/>
            </svg>
        </a>

          <div class="flex items-center gap-2">
            <button type="button" class="inline-flex items-center justify-center gap-2 rounded-sm border border-slate-200 bg-white px-2 py-1.5 leading-6 font-semibold text-slate-800 shadow-xs hover:border-slate-300 hover:bg-slate-100 hover:text-slate-800 hover:shadow-sm focus:ring-3 focus:ring-slate-500/25 focus:outline-hidden active:border-white active:bg-white active:shadow-none" x-on:click="mobileSidebarOpen = true">
                <svg class="hi-solid hi-menu-alt-1 inline-block size-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                </svg>
            </button>
          </div>
        </div>
      </header>

      <main id="page-content" class="flex max-w-full flex-auto flex-col pt-20 lg:pt-0">
        <div class="container mx-auto space-y-10 px-4 py-8 lg:space-y-16 lg:px-8 lg:py-12 xl:max-w-7xl">
            @yield('content')
        </div>
      </main>
    </div>
  </div>
</body>
</html>
