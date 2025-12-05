@extends('layouts.user')
@section('title', 'Dashboard')
@section('content')

<section class="py-10">
    <div class="max-w-6xl mx-auto">

        <!-- Heading -->
        <div class="text-center mb-14">
            <h2 class="text-3xl md:text-5xl font-extrabold text-gray-800">
                Dashboard <span class="text-purple-600">VibeSense AI</span>
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto mt-4 text-lg">
                Statistik emosimu berdasarkan diary & analisis AI.
            </p>
        </div>

        {{-- <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-16">

            <!-- Total Diary -->
            <div class="p-6 bg-white rounded-3xl border border-purple-100 shadow-md text-center">
                <p class="text-gray-500 text-sm">Total Diary</p>
                <p class="text-purple-600 font-extrabold text-4xl mt-2">
                    {{ $totalDiary ?? 0 }}
                </p>
            </div>

            <!-- Total Reflection -->
            <div class="p-6 bg-white rounded-3xl border border-purple-100 shadow-md text-center">
                <p class="text-gray-500 text-sm">AI Reflection</p>
                <p class="text-purple-600 font-extrabold text-4xl mt-2">
                    {{ $totalReflection ?? 0 }}
                </p>
            </div>

            <!-- Total Habits -->
            <div class="p-6 bg-white rounded-3xl border border-purple-100 shadow-md text-center">
                <p class="text-gray-500 text-sm">AI Habits</p>
                <p class="text-purple-600 font-extrabold text-4xl mt-2">
                    {{ $totalHabits ?? 0 }}
                </p>
            </div>

            <!-- Average Mood -->
            <div class="p-6 bg-white rounded-3xl border border-purple-100 shadow-md text-center">
                <p class="text-gray-500 text-sm">Rata-rata Mood</p>
                <p class="text-purple-600 font-extrabold text-4xl mt-2">
                    {{ $averageMood ?? '-' }}/10
                </p>
            </div>

        </div>

        <!-- Latest Diaries -->
        <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-xl border border-purple-100 p-10 mb-14">

            <h3 class="text-xl font-semibold text-gray-800 mb-5">Daftar Diary Terbaru</h3>

            @forelse($latestDiaries as $diary)
                <div class="p-5 border border-gray-200 rounded-xl mb-4 bg-gray-50">
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="text-lg font-semibold text-gray-800">
                            {{ $diary->title ?? 'Tanpa Judul' }}
                        </h4>
                        <span class="text-sm px-3 py-1 rounded-full bg-purple-100 text-purple-700">
                            Mood: {{ $diary->mood_score }}/10
                        </span>
                    </div>

                    <p class="text-gray-600 line-clamp-2">
                        {{ $diary->content }}
                    </p>

                    <div class="flex justify-between items-center text-sm text-gray-400 mt-3">
                        <p>{{ $diary->created_at->format('d M Y') }}</p>

                        <a href="{{ route('user.diary.show', $diary->id) }}"
                           class="text-purple-600 font-semibold hover:underline">
                           Lihat →
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 py-10">Belum ada diary.</p>
            @endforelse

            <div class="text-center mt-8">
                <a href="{{ route('user.diary.create') }}"
                    class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-3 rounded-full font-semibold shadow-md hover:shadow-lg transition">
                    Tulis Diary Baru
                </a>
            </div>

        </div> --}}

        <p class="mt-10 text-center text-gray-500 text-sm">
            Data diambil dari diary kamu & analisis AI secara otomatis.
        </p>

    </div>
</section>

@endsection
