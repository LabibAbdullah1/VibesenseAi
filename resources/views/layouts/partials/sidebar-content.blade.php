<div class="flex flex-col h-full bg-indigo-900 text-indigo-100 shadow-xl">

    <!-- Logo -->
    <div class="flex items-center gap-3 px-6 py-6 border-b border-indigo-700/50">
        <div class="w-11 h-11 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7l9-4 9 4-9 4-9-4zm0 0v6a9 9 0 009 9 9 9 0 009-9V7" />
            </svg>
        </div>
        <h1 class="text-xl font-extrabold tracking-wide">MyDiary</h1>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-5 space-y-1">

        {{-- Dashboard --}}
        <a href="{{ route('user.dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all
                {{ request()->routeIs('user.dashboard') ? 'bg-indigo-700 text-white shadow-md' : 'hover:bg-indigo-800 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" />
            </svg>
            Dashboard
        </a>

        {{-- MyDiary --}}
        <a href="{{ route('user.diary.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all
                {{ request()->routeIs('user.diary.*') ? 'bg-indigo-700 text-white shadow-md' : 'hover:bg-indigo-800 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 20l9-5-9-5-9 5 9 5zm0-10l9-5-9-5-9 5 9 5z" />
            </svg>
            MyDiary
        </a>

        {{-- AI Analysis --}}
        <a href="{{ route('user.analysis.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all
                {{ request()->routeIs('user.analysis.*') ? 'bg-indigo-700 text-white shadow-md' : 'hover:bg-indigo-800 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 1.343-3 3v7h6v-7c0-1.657-1.343-3-3-3z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14H4m16 0h-4m-4 6v-6" />
            </svg>
            AI Analysis
        </a>

    </nav>

    <!-- Logout -->
    <div class="px-4 mb-6">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-semibold
                    text-red-200 hover:bg-red-700 hover:text-white transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 20a4 4 0 01-4-4V8a4 4 0 014-4" />
                </svg>
                Logout
            </button>
        </form>
    </div>

</div>
