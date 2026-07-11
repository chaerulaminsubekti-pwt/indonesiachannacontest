@extends('layouts.public')

@section('title', 'Daftar Juri')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-icc-dark mb-2">Daftar Juri Aktif</h1>
    <p class="text-icc-gray mb-8">Juri yang bertugas di event-event ICC</p>

    @if ($data && $data->file_path)
        <div class="bg-white rounded-2xl shadow-lg p-4">
            @if ($data->tipe === 'pdf')
                <iframe src="{{ asset('storage/' . $data->file_path) }}" class="w-full h-[80vh] rounded-xl border border-gray-200" frameborder="0"></iframe>
            @else
                <img src="{{ asset('storage/' . $data->file_path) }}" alt="Daftar Juri ICC" class="w-full rounded-xl border border-gray-200">
            @endif
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
            <p class="text-icc-gray">Belum ada data daftar juri.</p>
        </div>
    @endif
</div>
@endsection
