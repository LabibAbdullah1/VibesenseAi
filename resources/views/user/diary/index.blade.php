@extends('layouts.user')
@section('title', 'My Diaries')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Heading Section -->
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-gray-800">
                        My <span
                            class="bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Diaries</span>
                    </h2>
                    <p class="text-sm sm:text-base text-gray-500 mt-2">
                        Semua catatan harian kamu dengan analisis AI yang mendalam
                    </p>
                </div>

                <!-- Quick Write Button -->
                <a href="{{ route('user.diary.create') }}"
                    class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-3 rounded-full font-semibold hover:from-purple-700 hover:to-pink-700 transition-all shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Tulis Diary Baru</span>
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Filter & Search Bar -->
        <div class="bg-white p-4 sm:p-6 rounded-2xl border border-gray-200 shadow-sm mb-6 sm:mb-8">
            <form method="GET" class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                <!-- Search Input -->
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari diary berdasarkan kata kunci..."
                        class="w-full pl-10 pr-4 py-3 text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all">
                </div>

                <!-- Mood Filter -->
                <select name="mood"
                    class="px-4 py-3 text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all bg-white">
                    <option value="">🎭 Semua Mood</option>
                    <option value="senang" {{ request('mood') == 'senang' ? 'selected' : '' }}>😊 Senang</option>
                    <option value="sedih" {{ request('mood') == 'sedih' ? 'selected' : '' }}>😢 Sedih</option>
                    <option value="cemas" {{ request('mood') == 'cemas' ? 'selected' : '' }}>😰 Cemas</option>
                    <option value="lelah" {{ request('mood') == 'lelah' ? 'selected' : '' }}>😴 Lelah</option>
                    <option value="marah" {{ request('mood') == 'marah' ? 'selected' : '' }}>😠 Marah</option>
                    <option value="tenang" {{ request('mood') == 'tenang' ? 'selected' : '' }}>😌 Tenang</option>
                    <option value="kesal" {{ request('mood') == 'kesal' ? 'selected' : '' }}>😤 kesal</option>
                </select>

                <!-- Sort Filter -->
                <select name="sort"
                    class="px-4 py-3 text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all bg-white">
                    <option value="latest">📅 Terbaru</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>📆 Terlama</option>
                </select>
                <!-- Filter Button -->
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-semibold rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all shadow-md hover:shadow-lg">
                    <span class="hidden sm:inline">Filter</span>
                    <span class="sm:hidden">🔍</span>
                </button>
            </form>
        </div>

        <!-- Diary Grid/List -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            @forelse ($diaries as $diary)
                <div
                    class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    <!-- Card Header -->
                    <div class="p-5 sm:p-6 border-b border-gray-100">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <h3
                                    class="text-lg sm:text-xl font-bold text-gray-800 group-hover:text-purple-600 transition-colors truncate">
                                    {{ Str::limit($diary->content, 50) }}
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-500 mt-1 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    {{ $diary->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>

                            @if ($diary->analysis)
                                <span
                                    class="px-3 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 flex-shrink-0">
                                    {{ $diary->analysis->mood ?? 'Netral' }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="p-5 sm:p-6">
                        <p class="text-sm sm:text-base text-gray-600 line-clamp-3 leading-relaxed">
                            {{ $diary->content }}
                        </p>

                        @if ($diary->analysis)
                            <div
                                class="mt-4 p-3 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                                <div class="flex items-start gap-2">
                                    <span class="text-lg">💭</span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold text-purple-800">AI Reflection:</p>
                                        <p class="text-xs text-purple-700 mt-1 line-clamp-2">
                                            {{ $diary->analysis->reflection }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Card Footer -->
                    <div class="px-5 sm:px-6 py-4 bg-gray-50 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            @if ($diary->analysis)
                                <div
                                    class="flex items-center gap-1.5 px-2.5 py-1 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border border-purple-100">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                    <span class="text-xs font-semibold text-purple-700">
                                        {{ $diary->analysis->mood_score ?? 'N/A' }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('user.diary.show', $diary->id) }}"
                                class="inline-flex items-center gap-1 text-sm font-semibold text-purple-600 hover:text-purple-700 transition-colors">
                                <span>Baca Detail</span>
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                            </a>

                            <!-- Delete Button -->
                            <button type="button" onclick="confirmDelete({{ $diary->id }})"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 hover:bg-red-50 transition-all"
                                title="Hapus diary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>

                            <!-- Hidden Form -->
                            <form id="delete-form-{{ $diary->id }}"
                                action="{{ route('user.diary.destroy', $diary->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="col-span-full">
                    <div class="bg-white rounded-2xl border-2 border-dashed border-gray-300 p-12 text-center">
                        <div
                            class="w-20 h-20 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Diary</h3>
                        <p class="text-gray-500 mb-6">Mulai tulis diary pertamamu dan biarkan AI menganalisisnya!</p>
                        <a href="{{ route('user.diary.create') }}"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-3 rounded-full font-semibold hover:from-purple-700 hover:to-pink-700 transition-all shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Tulis Diary Pertama
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($diaries->hasPages())
            <div class="mt-8 sm:mt-10">
                {{ $diaries->links() }}
            </div>
        @endif

    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Test apakah SweetAlert loaded
        console.log('SweetAlert2 loaded:', typeof Swal !== 'undefined');

        function confirmDelete(diaryId) {
            console.log('confirmDelete called with ID:', diaryId);

            Swal.fire({
                title: '🗑️ Hapus Diary?',
                html: `
                    <p class="text-gray-600">Apakah kamu yakin ingin menghapus diary ini?</p>
                    <p class="text-sm text-red-600 mt-2">⚠️ Tindakan ini tidak dapat dibatalkan!</p>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '✓ Ya, Hapus!',
                cancelButtonText: '✕ Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-3 font-semibold',
                    cancelButton: 'rounded-xl px-6 py-3 font-semibold',
                },
                buttonsStyling: true
            }).then((result) => {
                console.log('SweetAlert result:', result);

                if (result.isConfirmed) {
                    // Tampilkan loading
                    Swal.fire({
                        title: 'Menghapus...',
                        html: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit form
                    document.getElementById('delete-form-' + diaryId).submit();
                }
            });
        }

        // Success alert jika ada session success
        @if (session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '✅ Berhasil!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonColor: '#8b5cf6',
                    confirmButtonText: 'OK',
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-6 py-3 font-semibold',
                    }
                });
            });
        @endif
    </script>
@endsection
