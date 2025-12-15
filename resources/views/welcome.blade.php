@extends('layouts.app') {{-- Atau layout utama Anda --}}

@section('content')
    <div>
        {{-- Tanam komponen Livewire di sini --}}
        @livewire('random-doa')
    </div>
@endsection