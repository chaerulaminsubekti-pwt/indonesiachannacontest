@extends('layouts.public')

@section('title', 'Struktur Organisasi')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-icc-dark mb-2">Struktur Organisasi ICC</h1>
    <p class="text-icc-gray mb-8">Susunan kepengurusan Indonesia Channa Contest</p>

    @if ($data && $data->file_path)
        <div class="bg-white rounded-2xl shadow-lg p-4">
            @if ($data->tipe === 'pdf')
                <iframe src="{{ asset('storage/' .$data->file_path) }}" class="w-full h-[80vh] rounded-xl border border-gray-200" frameborder="0"></iframe>
            @else
                <img src="{{ asset('storage/' .$data->file_path) }}" alt="Struktur Organisasi ICC" class="w-full rounded-xl border border-gray-200">
            @endif
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
            <p class="text-icc-gray">Belum ada data struktur organisasi.</p>
        </div>
    @endif
</div>
@endsection
