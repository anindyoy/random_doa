@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Random Doa</h1>

        <form action="{{ route('doa.random') }}" method="GET">
            <div class="form-group">
                <label for="tags">Filter by Tags:</label>
                <select class="form-control" id="tags" name="tags[]" multiple>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->nama_tag }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Random Doa</button>
        </form>

        @if (isset($doa))
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $doa->judul_doa }}</h5>
                    @if ($doa->gambar)
                        <img src="{{ asset('storage/' . $doa->gambar) }}" alt="Doa Image" class="img-fluid">
                    @endif
                    <p class="card-text">{{ $doa->keterangan }}</p>
                    <p class="card-text"><small class="text-muted">{{ $doa->riwayat }}</small></p>
                </div>
            </div>

            <div class="mt-3">
                <form action="{{ route('doa.previous') }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden" name="current_doa_id" value="{{ $doa->id }}">
                    <button type="submit" class="btn btn-secondary">Previous</button>
                </form>

                <form action="{{ route('doa.next') }}" method="GET" style="display:inline;">
                    @csrf
                    @if (request()->has('tags'))
                        @foreach (request('tags') as $tag)
                            <input type="hidden" name="tags[]" value="{{ $tag }}">
                        @endforeach
                    @endif
                    <button type="submit" class="btn btn-secondary">Next</button>
                </form>
            </div>

        @endif

        @if (session('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
        @endif
    </div>
@endsection
