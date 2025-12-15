<!DOCTYPE html>
<html>

<head>
    <title>{{ $doa->judul }}</title>
</head>

<body>
    <h1>{{ $doa->judul }}</h1>
    @if ($doa->gambar)
        <img src="{{ asset('storage/' . $doa->gambar) }}" alt="Gambar Doa">
    @endif
    <p>{{ $doa->keterangan }}</p>

    <form action="{{ route('doa.previous') }}" method="POST">
        @csrf
        <input type="hidden" name="current_doa_id" value="{{ $doa->id }}">
        <button type="submit">Sebelumnya</button>
    </form>

    <a href="{{ route('doa.next') }}">Selanjutnya</a>

    <a href="{{ route('doa.random') }}">Random Doa</a>

</body>

</html>
