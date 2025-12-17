<div class="max-w-3xl mx-auto py-1 px-4 sm:px-6 lg:px-8">
    {{-- Auth Section --}}
    @include('livewire.auth')

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
        @include('livewire.show-doa')
    @else
        <div class="p-6 bg-blue-50 border border-blue-200 rounded-lg text-center text-blue-700">
            <p class="text-lg font-medium">Belum ada doa yang tersedia.</p>
        </div>
    @endif

    {{-- Tombol Navigasi --}}
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg z-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="grid grid-cols-2 gap-4">
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
        </div>
    </div>
</div>
