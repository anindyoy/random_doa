<div class="container">
    {{-- Tampilkan doa saat ini --}}
    @if ($doa)
        <h2>{{ $doa->judul_doa }}</h2>
        <p>{{ $doa->keterangan }}</p>
        {{-- Tampilkan detail doa lainnya --}}
    @else
        <p>Belum ada doa yang tersedia.</p>
    @endif

    {{-- Tombol untuk memuat doa acak lain --}}
    <button wire:click="loadRandomDoa" class="btn btn-primary mt-3">
        Muat Doa Acak Lain
    </button>

    {{-- Anda juga bisa menampilkan daftar tags --}}
    <div class="mt-4">
        <h3>Tags</h3>
        @foreach ($tags as $tag)
            <span class="badge bg-secondary">{{ $tag->nama }}</span>
        @endforeach
    </div>
</div>