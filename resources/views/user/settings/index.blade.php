@extends('layouts.user')
@section('title', 'Settings')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Heading -->
        <div class="mb-6 sm:mb-8">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-gray-800">
                <span class="bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Settings</span>
            </h2>
            <p class="text-sm sm:text-base text-gray-500 mt-2">
                Kelola informasi akun dan keamanan kamu
            </p>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div
                class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Error Message -->
        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="space-y-6">

            <!-- Profile Information Card -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-gradient-to-br from-purple-500 to-pink-500 p-3 rounded-xl text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Profile Information</h3>
                        <p class="text-sm text-gray-500">Update your name and email address</p>
                    </div>
                </div>

                <form action="{{ route('user.settings.update-profile') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                            class="w-full px-4 py-3 text-sm sm:text-base rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all"
                            placeholder="Enter your full name" required>
                        @error('name')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                            class="w-full px-4 py-3 text-sm sm:text-base rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all"
                            placeholder="your@email.com" required>
                        @error('email')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full sm:w-auto bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-purple-700 hover:to-pink-700 transition-all shadow-md hover:shadow-lg">
                        Save Changes
                    </button>
                </form>
            </div>

            <!-- Change Password Card -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-500 p-3 rounded-xl text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Change Password</h3>
                        <p class="text-sm text-gray-500">Update your password to keep your account secure</p>
                    </div>
                </div>

                <form action="{{ route('user.settings.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                        <div class="relative">
                            <input type="password" name="current_password" id="current_password"
                                class="w-full px-4 py-3 pr-12 text-sm sm:text-base rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all"
                                placeholder="Enter your current password" required>
                            <button type="button" onclick="togglePassword('current_password', 'current_eye')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors">
                                <svg id="current_eye" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="new_password"
                                class="w-full px-4 py-3 pr-12 text-sm sm:text-base rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all"
                                placeholder="Enter new password (min. 8 characters)" required>
                            <button type="button" onclick="togglePassword('new_password', 'new_eye')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors">
                                <svg id="new_eye" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Password harus minimal 8 karakter</p>
                    </div>

                    <!-- Confirm New Password -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="confirm_password"
                                class="w-full px-4 py-3 pr-12 text-sm sm:text-base rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all"
                                placeholder="Re-enter your new password" required>
                            <button type="button" onclick="togglePassword('confirm_password', 'confirm_eye')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors">
                                <svg id="confirm_eye" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all shadow-md hover:shadow-lg">
                        Update Password
                    </button>
                </form>
            </div>
            
            <!-- Account Info Card -->
            <div
                class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border border-purple-200 shadow-sm p-6 sm:p-8">
                <div class="flex items-start gap-4">
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-800 mb-2">Account Information</h4>
                        <div class="space-y-2 text-sm text-gray-600">
                            <p>• Member sejak: <span
                                    class="font-semibold">{{ Auth::user()->created_at->format('d M Y') }}</span></p>
                            <p>• Total Diaries: <span
                                    class="font-semibold">{{ \App\Models\Diary::where('user_id', Auth::id())->count() }}</span>
                            </p>
                            </p>
                            <p>• Last Login: <span
                                    class="font-semibold">{{ Auth::user()->updated_at->diffForHumans() }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
<script>
function togglePassword(inputId, eyeId) {
    const input = document.getElementById(inputId);
    const eye = document.getElementById(eyeId);
    
    if (input.type === 'password') {
        input.type = 'text';
        // Icon untuk "hide" (eye with slash)
        eye.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
            </path>
        `;
    } else {
        input.type = 'password';
        // Icon untuk "show" (eye normal)
        eye.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
            </path>
        `;
    }
}
</script>