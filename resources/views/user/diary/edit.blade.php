@extends('layouts.user')
@section('title', 'Edit Diary')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Heading -->
        <div class="mb-6 sm:mb-10">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-gray-800">
                Edit <span class="text-purple-600">Diary</span>
            </h2>
            <p class="text-sm sm:text-base text-gray-500 mt-2">
                Perbarui catatanmu dan biarkan AI memperbarui analisanya.
            </p>
        </div>

        @if ($errors->any())
            <div
                class="bg-red-50 border border-red-200 text-red-800 p-3 sm:p-4 rounded-xl sm:rounded-2xl mb-4 sm:mb-6 flex items-start gap-2 sm:gap-3">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="font-semibold text-sm sm:text-base">Terjadi Kesalahan</p>
                    <ul class="text-xs sm:text-sm mt-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div id="formSection">
            <form method="POST" action="{{ route('user.diary.update', $diary->id) }}" id="diaryForm">
                @csrf
                @method('PUT')

                <div class="bg-white p-4 sm:p-6 lg:p-8 rounded-xl sm:rounded-2xl border shadow-sm mb-4 sm:mb-6">
                    <label for="content" class="block text-base sm:text-lg font-semibold text-gray-800 mb-2 sm:mb-3">
                        📝 Ceritakan Harimu
                    </label>

                    <textarea name="content" id="content" rows="12"
                        class="w-full p-3 sm:p-4 text-sm sm:text-base border border-gray-200 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all resize-none"
                        placeholder="Tulis diary di sini..."
                        required>{{ old('content', $diary->content) }}</textarea>

                    @error('content')
                        <p class="text-red-600 text-xs sm:text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <button type="submit" id="submitBtn"
                        class="w-full sm:flex-1 bg-purple-600 text-white px-4 sm:px-6 py-3 rounded-full text-sm sm:text-base font-semibold hover:bg-purple-700 transition shadow-md hover:shadow-lg">
                        💫 Perbarui & Re-Analisis
                    </button>
                    <a href="{{ route('user.diary.show', $diary->id) }}"
                        class="w-full sm:w-auto text-center px-4 sm:px-6 py-3 rounded-full text-sm sm:text-base font-semibold border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Loading Section -->
        <div id="loadingSection"
            class="hidden bg-white p-6 sm:p-8 rounded-xl sm:rounded-2xl border border-purple-100 shadow-sm">
            <div class="flex flex-col items-center justify-center py-4 sm:py-6">
                <div class="relative">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 border-4 border-purple-200 border-t-purple-600 rounded-full animate-spin"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-xl sm:text-2xl">🤖</span>
                    </div>
                </div>
                <p class="text-gray-700 font-semibold mt-3 sm:mt-4 text-base sm:text-lg text-center">
                    Memproses pembaruan...
                </p>
                <p class="text-gray-500 text-xs sm:text-sm mt-2 text-center px-4">
                    AI sedang menganalisis perubahan catatanmu
                </p>

                <div class="w-full max-w-md mt-4 sm:mt-6 bg-gray-200 rounded-full h-2 overflow-hidden">
                    <div id="progressBar" class="bg-gradient-to-r from-purple-500 to-pink-500 h-full rounded-full transition-all duration-300" style="width: 0%;"></div>
                </div>
                <p id="progressText" class="text-xs text-gray-400 mt-2">0%</p>
            </div>
        </div>

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

            if (form) {
                form.addEventListener('submit', function(e) {
                    const content = document.getElementById('content').value.trim();
                    if (!content) return;

                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Memproses...';
                    submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

                    formSection.classList.add('hidden');
                    loadingSection.classList.remove('hidden');

                    let progress = 0;
                    const progressInterval = setInterval(() => {
                        if (progress < 90) {
                            progress += Math.random() * 10;
                            if (progress > 90) progress = 90;
                            progressBar.style.width = progress + '%';
                            progressText.textContent = Math.round(progress) + '%';
                        }
                    }, 500);
                });
            }
        });
    </script>
@endsection
