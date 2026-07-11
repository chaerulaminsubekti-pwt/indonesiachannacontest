@extends('layouts.public')

@section('title', 'Regulasi')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-icc-dark mb-2">Regulasi ICC</h1>
    <p class="text-icc-gray mb-8">Peraturan dan ketentuan yang berlaku di ICC</p>

    @if ($data && count($data) > 0)
        <div class="bg-white rounded-2xl shadow-lg divide-y divide-gray-200">
            @foreach ($data as $regulasi)
                <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                    <span class="text-icc-dark font-medium">{{ $regulasi->nama }}</span>
                    <a href="{{ route('regulasi.download', $regulasi->id) }}"
                       class="px-4 py-2 text-sm font-semibold text-white bg-icc-primary rounded-lg hover:bg-icc-primary-dark transition">
                        Download
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
            <p class="text-icc-gray">Belum ada data regulasi.</p>
        </div>
    @endif
</div>
@endsection