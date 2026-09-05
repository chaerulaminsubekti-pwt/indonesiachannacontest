@extends('layouts.public')

@section('title', $article->judul)
@section('meta_description', Str::limit($article->ringkasan ?? strip_tags($article->isi), 160))
@section('og_title', $article->judul)
@section('og_description', Str::limit($article->ringkasan ?? strip_tags($article->isi), 200))
@section('og_type', 'article')
@if($article->gambar_sampul)
    @section('og_image', Storage::url($article->gambar_sampul))
@endif

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route('artikel.index') }}" class="text-sm text-[#FF1A1A] hover:underline">&larr; Kembali ke Artikel</a>

    <div class="mt-4">
        <span class="inline-block px-3 py-1 text-xs font-bold text-[#FF1A1A] bg-[#FF1A1A]/10 rounded-full">
            {{ $article->kategori }}
        </span>
        <h1 class="text-3xl font-bold text-icc-dark mt-3">{{ $article->judul }}</h1>
        <p class="text-sm text-icc-gray mt-2">
            {{ $article->published_at?->isoFormat('D MMMM YYYY') }}
            @if ($article->author) • Oleh {{ $article->author->name }} @endif
            • {{ $article->views }}x dibaca
        </p>
    </div>

    @if ($article->gambar_sampul)
        <div class="mt-6 rounded-2xl overflow-hidden shadow-md">
            <img src="{{ Storage::url($article->gambar_sampul) }}" alt="{{ $article->judul }}" class="w-full object-cover">
        </div>
    @endif

    <article class="mt-8 prose prose-sm sm:prose-base max-w-none text-icc-dark">
        {!! $article->isi !!}
    </article>

    {{-- Bagikan --}}
    <div class="mt-8 flex items-center gap-3 border-t border-gray-200 pt-6">
        <span class="text-sm text-icc-gray font-medium">Bagikan:</span>
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener"
            class="px-3 py-1.5 text-xs font-semibold bg-[#1877F2] text-white rounded-lg hover:opacity-90 transition">Facebook</a>
        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($article->judul) }}" target="_blank" rel="noopener"
            class="px-3 py-1.5 text-xs font-semibold bg-black text-white rounded-lg hover:opacity-90 transition">X</a>
        <a href="https://wa.me/?text={{ urlencode($article->judul.' '.url()->current()) }}" target="_blank" rel="noopener"
            class="px-3 py-1.5 text-xs font-semibold bg-green-500 text-white rounded-lg hover:opacity-90 transition">WhatsApp</a>
    </div>

    {{-- Komentar --}}
    <div class="mt-10">
        <h2 class="text-xl font-bold text-icc-dark mb-4">Komentar ({{ $article->approvedComments->count() }})</h2>

        @if (session('comment_sent'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl">
                {{ session('comment_sent') }}
            </div>
        @endif

        @forelse ($article->approvedComments as $comment)
            <div class="mb-4 bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <p class="text-sm font-bold text-icc-dark">{{ $comment->nama }}</p>
                <p class="text-xs text-icc-gray mb-2">{{ $comment->created_at->isoFormat('D MMM YYYY, HH:mm') }}</p>
                <p class="text-sm text-icc-dark leading-relaxed">{{ $comment->isi }}</p>
            </div>
        @empty
            <p class="text-sm text-icc-gray mb-4">Belum ada komentar. Jadilah yang pertama!</p>
        @endforelse

        <form method="POST" action="{{ route('artikel.comment', $article->slug) }}" class="mt-6 bg-gray-50 border border-gray-200 rounded-2xl p-5">
            @csrf
            <h3 class="font-bold text-icc-dark mb-4">Tulis Komentar</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-icc-dark mb-1">Nama *</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required maxlength="100"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#FF1A1A]/30 focus:border-[#FF1A1A]">
                    @error('nama')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-icc-dark mb-1">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required maxlength="255"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#FF1A1A]/30 focus:border-[#FF1A1A]">
                    @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-icc-dark mb-1">Komentar *</label>
                <textarea name="isi" rows="4" required maxlength="2000"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#FF1A1A]/30 focus:border-[#FF1A1A]">{{ old('isi') }}</textarea>
                @error('isi')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <input type="text" name="website" value="" class="hidden" tabindex="-1" autocomplete="off">
            <button type="submit"
                class="mt-4 px-5 py-2 bg-[#FF1A1A] text-white text-sm font-semibold rounded-lg hover:bg-[#CC1515] transition">
                Kirim Komentar
            </button>
            <p class="text-xs text-icc-gray mt-2">Komentar tampil setelah disetujui admin.</p>
        </form>
    </div>

    {{-- Terkait --}}
    @if ($related->isNotEmpty())
        <div class="mt-12">
            <h2 class="text-xl font-bold text-icc-dark mb-4">Artikel Terkait</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach ($related as $item)
                    <a href="{{ route('artikel.show', $item->slug) }}"
                        class="group bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                        @if ($item->gambar_sampul)
                            <div class="aspect-[16/9] overflow-hidden">
                                <img src="{{ Storage::url($item->gambar_sampul) }}" alt="{{ $item->judul }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform" loading="lazy">
                            </div>
                        @endif
                        <div class="p-3">
                            <h3 class="text-sm font-bold text-icc-dark group-hover:text-[#FF1A1A] transition line-clamp-2">{{ $item->judul }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
