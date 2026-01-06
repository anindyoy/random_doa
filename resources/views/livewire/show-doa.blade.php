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
                    <button wire:click="toggleLove" class="focus:outline-none transition transform active:scale-125 cursor-pointer">
                        @if ($isLoved)
                            <i class="fa-solid fa-heart text-red-500 text-3xl"></i>
                        @else
                            <i class="fa-regular fa-heart text-gray-400 text-3xl hover:text-red-400"></i>
                        @endif
                    </button>

                    <span class="inline-block px-3 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">
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
                <h2 class="text-3xl font-extrabold text-orange-700">
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
