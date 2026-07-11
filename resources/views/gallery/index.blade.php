@extends('layouts.public')

@section('title', 'Gallery')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-icc-dark mb-2">Dokumentasi Event</h1>
    <p class="text-icc-gray mb-10">Dokumentasi kegiatan organisasi ICC</p>

    @if ($galleries->count())
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($galleries as $gallery)
                <div class="relative aspect-square bg-gray-100 rounded-xl overflow-hidden group cursor-pointer"
                     onclick="openGallery(this)">
                    <img src="{{ Storage::url($gallery->file_path) }}"
                         alt="{{ $gallery->caption ?? $gallery->judul_album ?? 'Gallery' }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                         loading="lazy">
                    @if ($gallery->caption)
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent p-3 opacity-0 group-hover:opacity-100 transition">
                            <p class="text-white text-xs">{{ $gallery->caption }}</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $galleries->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <p class="text-icc-gray text-lg">Belum ada gallery.</p>
            <a href="/" class="inline-block mt-4 px-6 py-2.5 text-sm font-semibold text-[#FF1A1A] border-2 border-[#FF1A1A] rounded-lg hover:bg-[#FF1A1A] hover:text-white transition">
                Kembali ke Beranda
            </a>
        </div>
    @endif
</div>

<x-gallery-lightbox />
@endsection
