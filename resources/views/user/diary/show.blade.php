@extends('layouts.user')
@section('title', 'Detail Diary')
@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('user.diary.index') }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-purple-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                <span>Kembali ke Daftar Diary</span>
            </a>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-lg overflow-hidden">

            <!-- Header Section -->
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 sm:p-8">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 text-purple-100 text-sm mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span>{{ $diary->created_at->format('l, d F Y') }}</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white mb-1">
                            Diary Entry
                        </h1>
                        <p class="text-purple-100 text-sm">
                            {{ $diary->created_at->format('H:i') }} WIB
                        </p>
                    </div>

                    <!-- Diary ID Badge -->
                    <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-xl">
                        <p class="text-xs text-purple-100 mb-0.5">ID</p>
                        <p class="text-white font-bold">#{{ $diary->id }}</p>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-6 sm:p-8">

                <!-- Diary Content -->
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                        Isi Diary
                    </h2>
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $diary->content }}</p>
                    </div>
                </div>

                @if ($diary->analysis)
                    <!-- AI Analysis Section -->
                    <div class="space-y-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                            Analisis AI
                        </h2>

                        <!-- Mood & Score Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Mood Card -->
                            <div
                                class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-100">
                                <div class="flex items-center gap-3 mb-2">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-purple-600">Mood Terdeteksi</p>
                                        <p class="text-2xl font-bold text-purple-800">
                                            {{ $diary->analysis->mood ?? 'Netral' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Mood Score Card -->
                            <div class="bg-gradient-to-br from-pink-50 to-purple-50 rounded-xl p-6 border border-pink-100">
                                <div class="flex items-center gap-3 mb-2">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-r from-pink-600 to-purple-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-pink-600">Skor Mood</p>
                                        <p class="text-2xl font-bold text-pink-800">
                                            {{ $diary->analysis->mood_score ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- AI Reflection -->
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-100">
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-10 h-10 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <span class="text-xl">💭</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-bold text-purple-800 mb-2">AI Reflection</h3>
                                    <p class="text-sm text-purple-700 leading-relaxed">
                                        {{ $diary->analysis->reflection ?? 'Tidak ada refleksi tersedia.' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Habit Insight -->
                        <div class="bg-gradient-to-br from-pink-50 to-purple-50 rounded-xl p-6 border border-pink-100">
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-10 h-10 bg-gradient-to-r from-pink-600 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-bold text-pink-800 mb-2">Habit Insight</h3>
                                    <p class="text-sm text-pink-700 leading-relaxed">
                                        {{ $diary->analysis->habit_insight ?? 'Tidak ada insight kebiasaan tersedia.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- No Analysis State -->
                    <div class="bg-gray-50 rounded-xl p-8 text-center border-2 border-dashed border-gray-300">
                        <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Belum Ada Analisis</h3>
                        <p class="text-gray-600 text-sm">Diary ini belum dianalisis oleh AI.</p>
                    </div>
                @endif

            </div>

            <!-- Footer Actions -->
            <div class="bg-gray-50 px-6 sm:px-8 py-4 border-t border-gray-200 flex items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <span>Dibuat {{ $diary->created_at->diffForHumans() }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('user.diary.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-all text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                        Kembali
                    </a>

                    <button type="button" onclick="confirmDeleteDetail({{ $diary->id }})"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-all text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                        Hapus
                    </button>

                    <!-- Hidden Form -->
                    <form id="delete-detail-form" action="{{ route('user.diary.destroy', $diary->id) }}" method="POST"
                        class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        console.log('SweetAlert2 loaded:', typeof Swal !== 'undefined');

        function confirmDeleteDetail(diaryId) {
            console.log('confirmDeleteDetail called with ID:', diaryId);

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
                    document.getElementById('delete-detail-form').submit();
                }
            });
        }
    </script>
@endsection
