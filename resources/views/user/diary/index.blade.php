@extends('layouts.user')
@section('title', 'My Diaries')

@section('content')

    <div class="max-w-6xl mx-auto">

        <!-- Heading -->
        <div class="mb-10">
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800">
                Diaries <span class="text-purple-600">Kamu</span>
            </h2>
            <p class="text-gray-500 mt-2">
                Lihat semua diaries yang sudah kamu tulis, lengkap dengan analisis emosi & refleksi AI.
            </p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-2xl border border-purple-100 shadow-sm text-center">
                <p class="text-gray-500 text-sm">Total Diaries</p>
                <p class="text-4xl font-bold text-purple-600 mt-1">{{ $diaries->count() }}</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-purple-100 shadow-sm text-center">
                <p class="text-gray-500 text-sm">Emosi Terdeteksi</p>
                <p class="text-3xl font-bold text-purple-600 mt-1">
                    {{ $diaries->pluck('primary_emotion')->unique()->count() }}
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-purple-100 shadow-sm text-center">
                <p class="text-gray-500 text-sm">Rata-rata Mood</p>
                <p class="text-3xl font-bold text-purple-600 mt-1">
                    {{ round($diaries->avg('mood_score')) }}/10
                </p>
            </div>
        </div>

        <!-- Filter Box -->
        <div class="bg-white p-6 rounded-2xl border shadow-sm mb-10">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari diaries..."
                    class="px-4 py-2 rounded-lg border focus:ring-purple-300 focus:outline-none">

                <select name="emotion" class="px-4 py-2 rounded-lg border focus:ring-purple-300 focus:outline-none">
                    <option value="">Semua Emosi</option>
                    <option value="senang" {{ request('emotion') == 'senang' ? 'selected' : '' }}>Senang</option>
                    <option value="sedih" {{ request('emotion') == 'sedih' ? 'selected' : '' }}>Sedih</option>
                    <option value="cemas" {{ request('emotion') == 'cemas' ? 'selected' : '' }}>Cemas</option>
                    <option value="lelah" {{ request('emotion') == 'lelah' ? 'selected' : '' }}>Lelah</option>
                </select>

                <button class="bg-purple-600 text-white py-2 rounded-lg font-semibold hover:bg-purple-700 transition">
                    Filter
                </button>
            </form>
        </div>

        <!-- Diary List -->
        <div class="space-y-6">

            @forelse ($diaries as $diary)
                <div class="bg-white p-7 rounded-2xl border shadow-sm hover:shadow-md transition">

                    <div class="flex justify-between items-start">

                        <h3 class="text-xl font-semibold text-gray-800">
                            {{ $diary->title ?? 'Tanpa Judul' }}
                        </h3>

                        <span
                            class="
                        px-3 py-1 text-sm rounded-full
                        {{ $diary->primary_emotion == 'sedih' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600' }}
                    ">
                            {{ ucfirst($diary->primary_emotion ?? 'Tidak diketahui') }}
                        </span>

                    </div>

                    <p class="text-gray-600 mt-2 line-clamp-3">
                        {{ $diary->content }}
                    </p>

                    <div class="flex justify-between items-center mt-5">
                        <p class="text-gray-400 text-sm">
                            {{ $diary->created_at->format('d M Y') }}
                        </p>

                        <a href="{{ route('user.diary.show', $diary->id) }}"
                            class="text-purple-600 font-semibold hover:underline">
                            Baca Selengkapnya →
                        </a>
                    </div>

                </div>
            @empty

                <div class="text-center py-20">
                    <p class="text-gray-500">Belum ada diaries.</p>

                    <a href="{{ route('user.diary.create') }}"
                        class="mt-4 inline-block bg-purple-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-purple-700 transition">
                        Tulis Diary Pertama Kamu
                    </a>
                </div>
            @endforelse

        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $diaries->links() }}
        </div>

    </div>

@endsection
