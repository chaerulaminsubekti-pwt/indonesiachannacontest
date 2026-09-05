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

    <article class="article-body mt-8 max-w-none text-icc-dark">
        {!! $article->isi !!}
    </article>
    <style>
        .article-body { font-size: 1rem; line-height: 1.8; overflow-wrap: break-word; }
        .article-body > *:first-child { margin-top: 0; }
        .article-body > *:last-child { margin-bottom: 0; }
        .article-body h1 { font-size: 1.6rem; font-weight: 800; line-height: 1.35; margin: 1.75rem 0 1rem; }
        .article-body h2 { font-size: 1.3rem; font-weight: 800; line-height: 1.4; margin: 1.75rem 0 0.75rem; padding-left: 0.75rem; border-left: 4px solid #FF1A1A; }
        .article-body h3 { font-size: 1.12rem; font-weight: 700; line-height: 1.45; margin: 1.5rem 0 0.6rem; }
        .article-body h4 { font-size: 1rem; font-weight: 700; margin: 1.25rem 0 0.5rem; }
        .article-body p { margin: 1rem 0; }
        .article-body ul { list-style: disc; padding-left: 1.5rem; margin: 1rem 0; }
        .article-body ol { list-style: decimal; padding-left: 1.5rem; margin: 1rem 0; }
        .article-body li { margin: 0.35rem 0; }
        .article-body li > p { margin: 0.25rem 0; }
        .article-body a { color: #FF1A1A; text-decoration: underline; }
        .article-body strong { font-weight: 700; }
        .article-body em { font-style: italic; }
        .article-body hr { margin: 2rem 0; border: 0; border-top: 1px solid #e5e7eb; }
        .article-body img { max-width: 100%; height: auto; border-radius: 0.75rem; margin: 1.25rem auto; display: block; }
        .article-body blockquote { border-left: 4px solid #e5e7eb; background: #f9fafb; padding: 0.75rem 1rem; margin: 1.25rem 0; border-radius: 0 0.5rem 0.5rem 0; font-style: italic; }
        .article-body blockquote p { margin: 0.4rem 0; }
        .article-body table { display: block; width: 100%; overflow-x: auto; border-collapse: collapse; margin: 1.25rem 0; font-size: 0.9rem; }
        .article-body th, .article-body td { border: 1px solid #e5e7eb; padding: 0.6rem 0.8rem; text-align: left; vertical-align: top; }
        .article-body th { background: #f9fafb; font-weight: 700; white-space: nowrap; }
        .article-body code { background: #f3f4f6; padding: 0.15rem 0.4rem; border-radius: 0.375rem; font-size: 0.875em; }
        .article-body pre { background: #0A0A0A; color: #f9fafb; padding: 1rem; border-radius: 0.75rem; overflow-x: auto; margin: 1.25rem 0; font-size: 0.875rem; }
        .article-body pre code { background: transparent; padding: 0; }
        @media (min-width: 640px) { .article-body { font-size: 1.05rem; } }
    </style>

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
