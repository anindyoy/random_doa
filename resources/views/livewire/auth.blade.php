<div class="mb-6 flex flex-wrap justify-end items-center gap-2" x-data="{ showLoginModal: false, showRegisterModal: false, showForgotModal: false }" @open-login-modal.window="showLoginModal = true">

    {{-- Tombol Riwayat --}}
    @if (isset($historyDetails) && count($historyDetails) > 0)
        <button
            type="button"
            @click="$dispatch('toggle-sidebar')"
            class="inline-flex items-center px-4 py-2 bg-amber-500 text-white text-sm font-semibold rounded-lg hover:bg-amber-600 transition duration-150 shadow-sm cursor-pointer border border-amber-600/20">
            <i class="fas fa-history mr-2"></i>
            Riwayat <span class="ml-1 px-1.5 py-0.5 bg-white/20 rounded text-xs">{{ count($historyDetails) }}</span>
        </button>
    @endif

    @auth
        {{-- Sisi User Terautentikasi --}}
        <div class="flex items-center gap-2 bg-white p-1 pr-2 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center gap-2 px-3 py-1 bg-gray-50 rounded-md border border-gray-100">
                <i class="fas fa-user-circle text-indigo-600"></i>
                <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
            </div>

            <div class="flex gap-1.5">
                <a href="{{ route('filament.admin.pages.dashboard') }}"
                    class="px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-md hover:bg-indigo-700 transition duration-150 no-underline shadow-sm">
                    <i class="fas fa-tachometer-alt mr-1"></i> Member Area
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-md hover:bg-red-700 transition duration-150 shadow-sm cursor-pointer">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    @else
        {{-- Sisi Guest --}}
        <div class="flex gap-2">
            <button @click="showRegisterModal = true"
                class="px-4 py-2 bg-white text-gray-700 text-sm font-semibold rounded-lg border border-gray-300 shadow-sm hover:bg-gray-50 transition duration-150 cursor-pointer">
                Daftar
            </button>
            <button @click="showLoginModal = true"
                class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition duration-150 cursor-pointer">
                Login
            </button>
        </div>

        {{-- LOGIN MODAL --}}
        <template x-teleport="body">
            <div x-show="showLoginModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 overflow-y-auto"
                style="display: none;">

                <div @click="showLoginModal = false" class="fixed inset-0 bg-black/40 backdrop-blur-[2px] transition-opacity"></div>

                <div class="flex items-center justify-center min-h-screen p-4">
                    <div x-show="showLoginModal"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="relative bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all"
                        @click.away="showLoginModal = false">

                        <button @click="showLoginModal = false"
                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition cursor-pointer">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>

                        <div class="px-6 pt-6 pb-4 border-b border-gray-200">
                            <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-sign-in-alt text-indigo-600"></i>
                                Login
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Masuk untuk mengakses halaman kelola doa pribadimu</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}" class="p-6">
                            @csrf

                            @if ($errors->any())
                                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <div class="flex items-start gap-2">
                                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                                        <div class="text-sm text-red-700">
                                            @foreach ($errors->all() as $error)
                                                <p>{{ $error }}</p>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                    Email
                                </label>
                                <input type="email"
                                    id="email"
                                    autocomplete="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                    placeholder="admin@example.com">
                            </div>

                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-lock text-gray-400 mr-1"></i>
                                    Password
                                </label>
                                <input type="password"
                                    id="password"
                                    name="password"
                                    autocomplete="current-password"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                    placeholder="••••••••">
                            </div>

                            <div class="mb-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <input type="checkbox"
                                        id="remember"
                                        name="remember"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded cursor-pointer">
                                    <label for="remember" class="ml-2 block text-sm text-gray-700 cursor-pointer">
                                        Ingat saya
                                    </label>
                                </div>
                                <button type="button"
                                    @click="showLoginModal = false; showForgotModal = true"
                                    class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                    Lupa password?
                                </button>
                            </div>

                            <button type="submit"
                                class="w-full py-3 px-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 transition duration-150 cursor-pointer">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Masuk
                            </button>

                            <div class="mt-4 text-center">
                                <span class="text-sm text-gray-600">Belum punya akun?</span>
                                <button type="button"
                                    @click="showLoginModal = false; showRegisterModal = true"
                                    class="text-sm text-green-600 hover:text-green-800 font-medium ml-1">
                                    Daftar sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        {{-- REGISTER MODAL --}}
        <template x-teleport="body">
            <div x-show="showRegisterModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 overflow-y-auto"
                style="display: none;">

                <div @click="showRegisterModal = false" class="fixed inset-0 bg-black/40 backdrop-blur-[2px] transition-opacity"></div>

                <div class="flex items-center justify-center min-h-screen p-4">
                    <div x-show="showRegisterModal"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="relative bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all"
                        @click.away="showRegisterModal = false">

                        <button @click="showRegisterModal = false"
                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition cursor-pointer z-10">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>

                        <div class="px-6 pt-6 pb-4 border-b border-gray-200">
                            <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-user-plus text-green-600"></i>
                                Daftar Akun
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Buat akun baru untuk mulai kelola doa pribadimu</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}" class="p-6 max-h-[calc(100vh-200px)] overflow-y-auto">
                            @csrf

                            @if ($errors->any())
                                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <div class="flex items-start gap-2">
                                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                                        <div class="text-sm text-red-700">
                                            @foreach ($errors->all() as $error)
                                                <p>{{ $error }}</p>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="mb-4">
                                <label for="register_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user text-gray-400 mr-1"></i>
                                    Nama Lengkap
                                </label>
                                <input type="text"
                                    id="register_name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    autofocus
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                    placeholder="John Doe">
                            </div>

                            <div class="mb-4">
                                <label for="register_email" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                    Email
                                </label>
                                <input type="email"
                                    id="register_email"
                                    autocomplete="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                    placeholder="john@example.com">
                            </div>

                            <div class="mb-4">
                                <label for="register_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-lock text-gray-400 mr-1"></i>
                                    Password
                                </label>
                                <input type="password"
                                    id="register_password"
                                    name="password"
                                    required
                                    autocomplete="new-password"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                    placeholder="Min. 8 karakter">
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-lock text-gray-400 mr-1"></i>
                                    Konfirmasi Password
                                </label>
                                <input type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    autocomplete="new-password"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                    placeholder="Ulangi password">
                            </div>

                            {{-- Google reCAPTCHA v2 --}}
                            <div class="mb-6">
                                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                                @error('g-recaptcha-response')
                                    <p class="mt-2 text-sm text-red-600">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="w-full py-3 px-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-500 focus:ring-opacity-50 transition duration-150 cursor-pointer">
                                <i class="fas fa-user-plus mr-2"></i>
                                Daftar
                            </button>

                            <div class="mt-4 text-center">
                                <span class="text-sm text-gray-600">Sudah punya akun?</span>
                                <button type="button"
                                    @click="showRegisterModal = false; showLoginModal = true"
                                    class="text-sm text-indigo-600 hover:text-indigo-800 font-medium ml-1">
                                    Login di sini
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        {{-- FORGOT PASSWORD MODAL --}}
        <template x-teleport="body">
            <div x-show="showForgotModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 overflow-y-auto"
                style="display: none;">

                <div @click="showForgotModal = false" class="fixed inset-0 bg-black/40 backdrop-blur-[2px] transition-opacity"></div>

                <div class="flex items-center justify-center min-h-screen p-4">
                    <div x-show="showForgotModal"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="relative bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all"
                        @click.away="showForgotModal = false">

                        <button @click="showForgotModal = false"
                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition cursor-pointer">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>

                        <div class="px-6 pt-6 pb-4 border-b border-gray-200">
                            <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-key text-amber-600"></i>
                                Lupa Password
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Masukkan email Anda untuk menerima link reset password</p>
                        </div>

                        <form method="POST" action="{{ route('password.email') }}" class="p-6">
                            @csrf

                            @if (session('status'))
                                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-start gap-2">
                                        <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                                        <div class="text-sm text-green-700">
                                            {{ session('status') }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <div class="flex items-start gap-2">
                                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                                        <div class="text-sm text-red-700">
                                            @foreach ($errors->all() as $error)
                                                <p>{{ $error }}</p>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="mb-6">
                                <label for="forgot_email" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                    Email
                                </label>
                                <input type="email"
                                    id="forgot_email"
                                    autocomplete="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition"
                                    placeholder="admin@example.com">
                            </div>

                            <button type="submit"
                                class="w-full py-3 px-4 bg-amber-600 text-white font-semibold rounded-lg hover:bg-amber-700 focus:outline-none focus:ring-4 focus:ring-amber-500 focus:ring-opacity-50 transition duration-150 cursor-pointer">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Kirim Link Reset Password
                            </button>

                            <div class="mt-4 text-center">
                                <button type="button"
                                    @click="showForgotModal = false; showLoginModal = true"
                                    class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                    <i class="fas fa-arrow-left mr-1"></i>
                                    Kembali ke Login
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    @endauth
</div>

{{-- Load Google reCAPTCHA Script --}}
@push('scripts')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
