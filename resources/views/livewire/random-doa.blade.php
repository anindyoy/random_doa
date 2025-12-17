<div class="max-w-3xl mx-auto py-1 px-4 sm:px-6 lg:px-8">
    {{-- Auth Section --}}
    <div class="mb-4 flex justify-end" x-data="{ showLoginModal: false }">
        @auth
            <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-lg shadow-md border border-gray-200">
                <span class="text-sm text-gray-700">
                    <i class="fas fa-user-circle text-indigo-600 mr-1"></i>
                    <span class="font-medium">{{ Auth::user()->name }}</span>
                </span>
                <div class="flex gap-2">
                    <a href="{{ route('filament.admin.pages.dashboard') }}"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition duration-150 no-underline shadow-md">
                        <i class="fas fa-tachometer-alt mr-1"></i> Member Area
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition duration-150 shadow-md">
                            <i class="fas fa-sign-out-alt mr-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        @else
            <button @click="showLoginModal = true"
                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg border border-indigo-700 shadow-md hover:bg-indigo-700 transition duration-150">
                <i class="fas fa-sign-in-alt mr-1"></i> Login
            </button>

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

                    {{-- Overlay --}}
                    <div @click="showLoginModal = false" class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

                    {{-- Modal Content --}}
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

                            {{-- Close Button --}}
                            <button @click="showLoginModal = false"
                                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>

                            {{-- Modal Header --}}
                            <div class="px-6 pt-6 pb-4 border-b border-gray-200">
                                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                                    <i class="fas fa-sign-in-alt text-indigo-600"></i>
                                    Login
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">Masuk untuk mengakses admin panel</p>
                            </div>

                            {{-- Login Form --}}
                            <form method="POST" action="{{ route('login') }}" class="p-6">
                                @csrf

                                {{-- Display Errors --}}
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

                                {{-- Email Field --}}
                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                        Email
                                    </label>
                                    <input type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autofocus
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                        placeholder="admin@example.com">
                                </div>

                                {{-- Password Field --}}
                                <div class="mb-6">
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-lock text-gray-400 mr-1"></i>
                                        Password
                                    </label>
                                    <input type="password"
                                        id="password"
                                        name="password"
                                        required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                        placeholder="••••••••">
                                </div>

                                {{-- Remember Me --}}
                                <div class="mb-6 flex items-center">
                                    <input type="checkbox"
                                        id="remember"
                                        name="remember"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="remember" class="ml-2 block text-sm text-gray-700">
                                        Ingat saya
                                    </label>
                                </div>

                                {{-- Submit Button --}}
                                <button type="submit"
                                    class="w-full py-3 px-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 transition duration-150">
                                    <i class="fas fa-sign-in-alt mr-2"></i>
                                    Masuk
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </template>
        @endauth
    </div>

    {{-- Header --}}
    <div class="mb-2 text-center">
        <h1 class="text-4xl font-extrabold text-gray-800 mb-2">
            <i class="fas fa-random text-indigo-600 mr-2"></i>
            Random Doa
        </h1>
        <p class="text-gray-600 text-sm">Temukan inspirasi doa, sesuai al-Qur'an dan Sunnah, secara acak</p>
    </div>

    @if ($doa)
        {{-- Menggunakan Alpine.js untuk mengelola state modal --}}
        <div x-data="{ open: false, imageUrl: '' }">
            <div class="bg-white shadow-2xl rounded-xl overflow-hidden transform hover:scale-[1.01] transition duration-300">

                {{-- Gambar Doa --}}
                <div class="p-4 bg-gray-50 border-b border-gray-100">
                    <div class="flex justify-center">
                        {{-- Tambahkan event handler x-on:click untuk membuka modal --}}
                        <img src="{{ asset('storage/' . $doa->gambar) }}"
                            alt="Gambar {{ $doa->judul }}"
                            class="h-96 w-auto rounded-lg object-contain shadow-md border-4 border-white cursor-pointer"
                            style="max-height: 400px;"
                            x-on:click="imageUrl = '{{ asset('storage/' . $doa->gambar) }}'; open = true">
                    </div>
                </div>

                <div class="p-6 md:p-8">
                    {{-- ID dan Judul --}}
                    <div class="mb-4 border-b pb-2">
                        <div class="flex items-center gap-2 flex-wrap mb-2">
                            <span class="inline-block px-3 py-1 text-xs font-semibold bg-indigo-100 text-indigo-700 rounded-full">
                                #{{ $doa->id }}
                            </span>
                            @if ($doa->tags && $doa->tags->count() > 0)
                                @foreach ($doa->tags as $tag)
                                    <span class="inline-block px-3 py-1 text-xs font-medium bg-gray-200 text-gray-700 rounded-full">
                                        {{ $tag->nama }}
                                    </span>
                                @endforeach
                            @endif
                        </div>
                        <h2 class="text-3xl font-extrabold text-indigo-700">
                            {{ $doa->judul }}
                        </h2>
                    </div>
                    <div class="text-xl leading-relaxed text-gray-800 space-y-4">
                        <p>{!! nl2br(e($doa->keterangan)) !!}</p>
                    </div>

                    {{-- Riwayat (Jika Ada) --}}
                    @if ($doa->riwayat)
                        <div class="mt-6 p-2 font-bold bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 text-sm rounded-md">
                            <span>{{ $doa->riwayat }}</span>
                        </div>
                    @endif

                    {{-- Sumber Desain (Jika Ada) --}}
                    @if ($doa->sumber_desain)
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-500">
                                Design by:
                                <strong class="text-gray-700">{{ $doa->sumber_desain }}</strong>
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- MODAL UNTUK GAMBAR BESAR --}}
            <template x-teleport="body">
                <div x-show="open"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-50 overflow-y-auto"
                    aria-labelledby="modal-title" role="dialog" aria-modal="true"
                    style="display: none;">

                    {{-- Overlay latar belakang --}}
                    <div x-on:click="open = false" class="fixed inset-0 bg-black bg-opacity-90 transition-opacity"></div>

                    {{-- Konten Modal --}}
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div x-show="open"
                            x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="relative transform transition-all"
                            style="max-width: 90vw; max-height: 90vh;"
                            x-on:click.away="open = false">

                            {{-- Tombol tutup --}}
                            <button x-on:click="open = false"
                                class="absolute -top-12 right-0 z-10 p-2 text-white bg-black bg-opacity-50 rounded-full hover:bg-opacity-75 transition"
                                aria-label="Tutup">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>

                            {{-- Gambar di dalam modal --}}
                            <img :src="imageUrl"
                                alt="Gambar Besar"
                                class="rounded-lg shadow-2xl"
                                style="max-width: 90vw; max-height: 90vh; width: auto; height: auto; object-fit: contain;">
                        </div>
                    </div>
                </div>
            </template>
        </div>
    @else
        <div class="p-6 bg-blue-50 border border-blue-200 rounded-lg text-center text-blue-700">
            <p class="text-lg font-medium">Belum ada doa yang tersedia.</p>
        </div>
    @endif

    {{-- Tombol Navigasi --}}
    <div class="mt-8 grid grid-cols-2 gap-4">
        {{-- Tombol Previous --}}
        <button
            wire:click="loadPreviousDoa"
            {{ $historyIndex <= 0 ? 'disabled' : '' }}
            class="py-3 px-6 font-bold rounded-lg shadow-xl focus:outline-none focus:ring-4 transition duration-150 {{ $historyIndex <= 0 ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 focus:ring-opacity-50' }}">
            <i class="fas fa-arrow-left mr-2"></i> Doa Sebelumnya
        </button>

        {{-- Tombol Next (Doa Acak) --}}
        <button
            wire:click="loadRandomDoa"
            class="py-3 px-6 bg-green-600 text-white font-bold rounded-lg shadow-xl hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-500 focus:ring-opacity-50 transition duration-150">
            <i class="fas fa-redo-alt mr-2"></i> Doa Acak Berikutnya
        </button>
    </div>

    {{-- Tags --}}
    <div class="mt-10 pt-6 border-t border-gray-300">
        <h3 class="text-xl font-semibold text-gray-700 mb-3">🏷️ Tags</h3>
        <div class="flex flex-wrap gap-2">
            @foreach ($tags as $tag)
                <span class="px-3 py-1 text-sm font-medium bg-gray-200 text-gray-800 rounded-full hover:bg-gray-300 transition duration-150 cursor-default">
                    {{ $tag->nama }}
                </span>
            @endforeach
        </div>
    </div>
    <style>
        .no-underline,
        a {
            text-decoration: none !important;
        }
    </style>
</div>
