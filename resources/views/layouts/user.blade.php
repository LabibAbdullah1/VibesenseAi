<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'VibeSense AI') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 flex">

    <!-- ============ SIDEBAR ============ -->
    <aside class="w-64 h-screen bg-indigo-900 text-white fixed left-0 top-0">
        @include('layouts.partials.sidebar-content')
    </aside>

    <!-- ============ MAIN CONTENT ============ -->
    <div class="ml-64 w-full min-h-screen">

        {{-- TOPBAR --}}
        <header
            class="bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-sm
        px-8 py-4 flex items-center justify-between sticky top-0 z-40">

            <!-- Page Title -->
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">
                @yield('title', 'Dashboard')
            </h2>

            <!-- User Profile -->
            <div class="flex items-center gap-4">

                <div class="text-right leading-tight">
                    <p class="text-gray-800 font-semibold text-base">
                        {{ Auth::user()->name }}
                    </p>
                    <p class="text-gray-500 text-sm">
                        {{ Auth::user()->email }}
                    </p>
                </div>

                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff"
                    class="w-11 h-11 rounded-full border-2 border-indigo-500 shadow-md" alt="User Avatar">

            </div>

        </header>


        {{-- PAGE CONTENT --}}
        <main class="p-8">
            @yield('content')
        </main>
    </div>

</body>

</html>
