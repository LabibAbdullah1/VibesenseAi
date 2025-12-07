@extends('layouts.user')
@section('title', 'Tulis Diary')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Heading -->
        <div class="mb-6 sm:mb-10">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-gray-800">
                Tulis <span class="text-purple-600">Diary Baru</span>
            </h2>
            <p class="text-sm sm:text-base text-gray-500 mt-2">
                Ceritakan harimu dan biarkan AI memberikan refleksi & saran untukmu.
            </p>
        </div>

        @if (isset($error))
            <div
                class="bg-red-50 border border-red-200 text-red-800 p-3 sm:p-4 rounded-xl sm:rounded-2xl mb-4 sm:mb-6 flex items-start gap-2 sm:gap-3">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="font-semibold text-sm sm:text-base">Terjadi Kesalahan</p>
                    <p class="text-xs sm:text-sm mt-1">{{ $error }}</p>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div id="formSection">
            <form method="POST" action="{{ route('user.diary.store') }}" id="diaryForm">
                @csrf

                <div class="bg-white p-4 sm:p-6 lg:p-8 rounded-xl sm:rounded-2xl border shadow-sm mb-4 sm:mb-6">
                    <label for="content" class="block text-base sm:text-lg font-semibold text-gray-800 mb-2 sm:mb-3">
                        📝 Ceritakan Harimu
                    </label>

                    <textarea name="content" id="content" rows="10"
                        class="w-full p-3 sm:p-4 text-sm sm:text-base border border-gray-200 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all resize-none"
                        placeholder="Tulis diary di sini... Ceritakan perasaanmu, apa yang terjadi hari ini, atau apa pun yang ingin kamu bagikan."
                        required>{{ old('content') }}</textarea>

                    @error('content')
                        <p class="text-red-600 text-xs sm:text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <button type="submit" id="submitBtn"
                        class="w-full sm:flex-1 bg-purple-600 text-white px-4 sm:px-6 py-3 rounded-full text-sm sm:text-base font-semibold hover:bg-purple-700 transition shadow-md hover:shadow-lg">
                        💫 Simpan & Analisis dengan AI
                    </button>
                    <a href="{{ route('user.diary.index') }}"
                        class="w-full sm:w-auto text-center px-4 sm:px-6 py-3 rounded-full text-sm sm:text-base font-semibold border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Loading Section (Hidden by default) -->
        <div id="loadingSection"
            class="hidden bg-white p-6 sm:p-8 rounded-xl sm:rounded-2xl border border-purple-100 shadow-sm">
            <div class="flex flex-col items-center justify-center py-4 sm:py-6">
                <div class="relative">
                    <div
                        class="w-12 h-12 sm:w-16 sm:h-16 border-4 border-purple-200 border-t-purple-600 rounded-full animate-spin">
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-xl sm:text-2xl">🤖</span>
                    </div>
                </div>
                <p class="text-gray-700 font-semibold mt-3 sm:mt-4 text-base sm:text-lg text-center">
                    AI sedang menganalisis diary kamu...
                </p>
                <p class="text-gray-500 text-xs sm:text-sm mt-2 text-center px-4">
                    Mohon tunggu, proses ini memerlukan beberapa saat
                </p>

                <!-- Progress Bar Animation -->
                <div class="w-full max-w-md mt-4 sm:mt-6 bg-gray-200 rounded-full h-2 overflow-hidden">
                    <div id="progressBar"
                        class="bg-gradient-to-r from-purple-500 to-pink-500 h-full rounded-full transition-all duration-300"
                        style="width: 0%;">
                    </div>
                </div>
                <p id="progressText" class="text-xs text-gray-400 mt-2">0%</p>
            </div>
        </div>

        @if (isset($diary) && isset($analysis))
            <!-- Hasil Analysis -->
            <div id="analysisSection" class="space-y-4 sm:space-y-6">

                <!-- Success Banner -->
                <div
                    class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm">
                    <div class="flex items-start gap-3 sm:gap-4">
                        <div class="bg-green-100 rounded-full p-2 sm:p-3 flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-green-800 text-base sm:text-lg">✨ Analisis Selesai!</h3>
                            <p class="text-green-700 text-sm sm:text-base mt-1">
                                Diary kamu berhasil disimpan dan telah dianalisis oleh AI. Berikut hasil analisisnya:
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Diary Content Card -->
                <div class="bg-white p-4 sm:p-6 lg:p-8 rounded-xl sm:rounded-2xl border border-gray-200 shadow-sm">
                    <div class="flex items-start gap-3 sm:gap-4 mb-3 sm:mb-4">
                        <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-full p-2 sm:p-3 flex-shrink-0">
                            <span class="text-2xl sm:text-3xl">📝</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-800 text-lg sm:text-xl">Diary Kamu</h3>
                            <p class="text-gray-500 text-xs sm:text-sm mt-1">{{ $diary->created_at->format('d F Y, H:i') }}
                                WIB</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg sm:rounded-xl p-4 sm:p-5 border border-gray-200">
                        <p class="text-gray-700 leading-relaxed whitespace-pre-wrap text-sm sm:text-base">
                            {{ $diary->content }}</p>
                    </div>
                </div>

                <!-- Empati AI Card -->
                <div class="bg-white p-4 sm:p-6 lg:p-8 rounded-xl sm:rounded-2xl border border-purple-100 shadow-sm">
                    <div class="flex items-start gap-3 sm:gap-4 mb-3 sm:mb-4">
                        <div class="bg-gradient-to-br from-purple-100 to-pink-100 rounded-full p-2 sm:p-3 flex-shrink-0">
                            <span class="text-2xl sm:text-3xl">💭</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-800 text-lg sm:text-xl">Empati dari AI</h3>
                            <p class="text-gray-500 text-xs sm:text-sm mt-1">Refleksi hangat untuk harimu</p>
                        </div>
                    </div>
                    <div
                        class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg sm:rounded-xl p-4 sm:p-5 border border-purple-100">
                        <p class="text-gray-700 leading-relaxed text-sm sm:text-base lg:text-lg">
                            {{ $analysis->reflection }}
                        </p>
                    </div>
                </div>

                <!-- Saran & Habit Card -->
                <div class="bg-white p-4 sm:p-6 lg:p-8 rounded-xl sm:rounded-2xl border border-purple-100 shadow-sm">
                    <div class="flex items-start gap-3 sm:gap-4 mb-3 sm:mb-4">
                        <div class="bg-gradient-to-br from-blue-100 to-cyan-100 rounded-full p-2 sm:p-3 flex-shrink-0">
                            <span class="text-2xl sm:text-3xl">💡</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-800 text-lg sm:text-xl">Saran & Habit dari AI</h3>
                            <p class="text-gray-500 text-xs sm:text-sm mt-1">Langkah kecil untuk hari yang lebih baik</p>
                        </div>
                    </div>
                    <div
                        class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-lg sm:rounded-xl p-4 sm:p-5 border border-blue-100">
                        <p class="text-gray-700 leading-relaxed text-sm sm:text-base lg:text-lg">
                            {{ $analysis->habit_insight }}
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-2 sm:pt-4">
                    <a href="{{ route('user.diary.show', $diary->id) }}"
                        class="w-full sm:flex-1 bg-purple-600 text-white px-4 sm:px-6 py-3 rounded-full text-sm sm:text-base font-semibold hover:bg-purple-700 transition text-center shadow-md hover:shadow-lg">
                        📖 Lihat Detail Diary
                    </a>
                    <a href="{{ route('user.diary.create') }}"
                        class="w-full sm:flex-1 bg-gradient-to-r from-pink-500 to-purple-500 text-white px-4 sm:px-6 py-3 rounded-full text-sm sm:text-base font-semibold hover:from-pink-600 hover:to-purple-600 transition text-center shadow-md hover:shadow-lg">
                        ✍️ Tulis Diary Baru
                    </a>
                    <a href="{{ route('user.diary.index') }}"
                        class="w-full sm:w-auto text-center px-4 sm:px-6 py-3 rounded-full text-sm sm:text-base font-semibold border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                        ← Kembali
                    </a>
                </div>

            </div>
        @endif

    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('diaryForm');
            const formSection = document.getElementById('formSection');
            const loadingSection = document.getElementById('loadingSection');
            const submitBtn = document.getElementById('submitBtn');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');

            @if (isset($diary) && isset($analysis))
                // Jika sudah ada hasil analisis, sembunyikan loading dan tampilkan hasil
                if (loadingSection) {
                    loadingSection.classList.add('hidden');
                }
                if (formSection) {
                    formSection.classList.add('hidden');
                }
            @else
                // Jika belum ada hasil, setup form submission
                if (form) {
                    form.addEventListener('submit', function(e) {
                        // Validasi textarea tidak kosong
                        const content = document.getElementById('content').value.trim();
                        if (!content) {
                            return; // Biarkan validasi HTML5 bekerja
                        }

                        // Disable submit button
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'Memproses...';
                        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

                        // Sembunyikan form dan tampilkan loading
                        formSection.classList.add('hidden');
                        loadingSection.classList.remove('hidden');

                        // Animasi progress bar
                        let progress = 0;
                        const progressInterval = setInterval(() => {
                            if (progress < 90) { // Max 90% saat loading, 100% saat selesai
                                progress += Math.random() * 10;
                                if (progress > 90) progress = 90;

                                progressBar.style.width = progress + '%';
                                progressText.textContent = Math.round(progress) + '%';
                            }
                        }, 500);

                        // Simpan interval ID untuk dibersihkan nanti jika perlu
                        window.progressInterval = progressInterval;
                    });
                }

                // Jika ada error validasi, tampilkan kembali form
                @if ($errors->any())
                    if (formSection && loadingSection) {
                        formSection.classList.remove('hidden');
                        loadingSection.classList.add('hidden');
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.textContent = '💫 Simpan & Analisis dengan AI';
                            submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                        }
                    }
                @endif
            @endif
        });
    </script>
@endsection
