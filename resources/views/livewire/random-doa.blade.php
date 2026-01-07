<div class="max-w-3xl mx-auto py-1 px-4 sm:px-6 lg:px-8">
    {{-- Auth Section --}}
    @include('livewire.auth')

    {{-- Header --}}
    <div class="mb-2 text-center">
        <h1 class="text-4xl font-extrabold text-gray-800 mb-2">
            <i class="fas fa-random text-green-600 mr-2"></i>
            Random Doa
        </h1>
        <p class="text-gray-600 text-sm">Temukan inspirasi doa, sesuai al-Qur'an dan Sunnah, secara acak</p>
    </div>

    @if ($doa)
        {{-- Menggunakan Alpine.js untuk mengelola state modal --}}
        @include('livewire.show-doa')
    @else
        <div class="p-6 bg-blue-50 border border-blue-200 rounded-lg text-center text-blue-700">
            <p class="text-lg font-medium">Belum ada doa yang tersedia.</p>
        </div>
    @endif

    {{-- Tombol Navigasi --}}
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg z-40">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="grid grid-cols-2 gap-4">
                {{-- Tombol Previous --}}
                <button
                    wire:click="loadPreviousDoa"
                    {{ $historyIndex <= 0 ? 'disabled' : '' }}
                    class="py-3 px-6 font-bold rounded-lg shadow-xl focus:outline-none focus:ring-4 transition duration-150 {{ $historyIndex <= 0 ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 cursor-pointer focus:ring-opacity-50' }}">
                    <i class="fas fa-arrow-left mr-2"></i> Doa Sebelumnya
                </button>

                {{-- Tombol Next (Doa Acak) --}}
                <button
                    wire:click="loadRandomDoa"
                    class="py-3 px-6 bg-green-600 text-white font-bold rounded-lg shadow-xl hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-500 focus:ring-opacity-50 transition cursor-pointer duration-150"
                    id="next-random-doa-button">
                    <i class="fas fa-random mr-2"></i> Doa Acak Berikutnya
                </button>
            </div>
        </div>
    </div>

    {{-- Off-Canvas Sidebar for History --}}
    <div
        x-data="{ open: false }"
        @toggle-sidebar.window="open = !open"
        @keydown.escape.window="open = false">

        {{-- Overlay --}}
        <div
            x-data="{ open: false }"
            @toggle-sidebar.window="open = !open"
            @keydown.escape.window="open = false">

            {{-- Overlay: Diubah dari bg-gray-900 ke bg-black/40 untuk transparansi yang lebih cantik --}}
            <div
                x-show="open"
                x-transition:enter="transition-opacity ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="open = false"
                class="fixed inset-0 bg-black/40 backdrop-blur-[2px] z-50"
                style="display: none;">
            </div>

            {{-- Sidebar --}}
            <aside
                x-show="open"
                x-transition:enter="transform transition ease-in-out duration-300"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-300"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="fixed top-0 right-0 z-[60] h-screen w-80 bg-white shadow-2xl overflow-y-auto"
                style="display: none;">
                {{-- Tambahkan Tab atau Section Baru di Sidebar --}}
                <div class="p-4 border-b">
                    <h3 class="font-bold text-gray-800"><i class="fas fa-heart text-red-500 mr-2"></i> Doa Favorit Saya</h3>
                </div>

                <div class="p-4 space-y-3">
                    @forelse ($this->favoriteList as $fav)
                        <div class="flex items-center justify-between bg-red-50 p-2 rounded-lg border border-red-100">
                            <div class="flex items-center gap-2 cursor-pointer" wire:click="loadDoaFromHistory({{ $fav->id }})">
                                <img src=""{{ asset('storage/' . $fav->gambar) }}"" class="w-10 h-10 rounded object-cover">
                                <span class="text-sm font-medium truncate w-32">{{ $fav->judul }}</span>
                            </div>
                            {{-- Tombol Hapus Love dari List --}}
                            <button wire:click="toggleLove({{ $fav->id }})" class="text-red-400 hover:text-red-600 p-1">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 text-center">Belum ada doa yang disukai.</p>
                    @endforelse
                </div>

                {{-- Header --}}
                <div class="sticky top-0 bg-white border-b border-gray-200 p-4 z-10">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-history text-green-600 mr-2"></i>
                            Riwayat Doa
                        </h3>
                        <button
                            type="button"
                            @click="open = false"
                            class="text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-green-500 rounded-lg p-1.5 transition duration-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">{{ count($historyDetails) }} doa terakhir</p>
                </div>

                {{-- History List --}}
                <div class="p-4 space-y-3">
                    @forelse ($historyDetails as $index => $item)
                        <div
                            wire:key="history-{{ $item['id'] }}-{{ $index }}"
                            class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg hover:border-green-300 transition duration-200 cursor-pointer group"
                            wire:click="loadDoaFromHistory({{ $item['id'] }})"
                            @click="open = false">

                            <div class="flex gap-3 p-3">
                                {{-- Image --}}
                                <div class="flex-shrink-0">
                                    <img
                                        src="{{ asset('storage/' . $item['gambar']) }}"
                                        alt="{{ $item['judul'] }}"
                                        class="w-20 h-20 object-cover rounded-lg group-hover:scale-105 transition duration-200">
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold text-green-700 bg-green-100 rounded">
                                            #{{ $item['id'] }}
                                        </span>
                                        @if ($index === 0)
                                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold text-green-700 bg-green-100 rounded">
                                                Terbaru
                                            </span>
                                        @endif
                                    </div>

                                    <h4 class="mt-1 text-sm font-semibold text-gray-900 line-clamp-2 group-hover:text-green-600 transition duration-200">
                                        {{ $item['judul'] }}
                                    </h4>

                                    <p class="mt-1 text-xs text-gray-500">
                                        <i class="far fa-clock mr-1"></i>
                                        {{ $index === 0 ? 'Baru saja' : $index + 1 . ' doa yang lalu' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                            <p class="text-sm text-gray-500">Belum ada riwayat</p>
                        </div>
                    @endforelse
                </div>

                {{-- Footer --}}
                @if (count($historyDetails) > 0)
                    <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 p-4">
                        <button
                            wire:click="clearHistory"
                            @click="open = false"
                            class="w-full py-2.5 px-4 text-sm font-medium text-red-600 bg-white border border-red-300 rounded-lg hover:bg-red-50 focus:ring-4 focus:outline-none focus:ring-red-200 transition duration-200">
                            <i class="fas fa-trash-alt mr-2"></i>
                            Hapus Semua Riwayat
                        </button>
                    </div>
                @endif
            </aside>
        </div>

    </div>
</div>
