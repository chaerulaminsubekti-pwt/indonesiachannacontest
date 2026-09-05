@extends('layouts.public')

@section('title', 'Artikel')

@section('meta_description', 'Artikel dan berita seputar ikan channa: panduan perawatan, liputan event, dan tips dari Indonesia Channa Contest.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-icc-dark mb-2">Artikel</h1>
    <p class="text-icc-gray mb-8">Panduan, liputan event, dan berita seputar dunia ikan channa</p>

    {{-- Filter Kategori --}}
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('artikel.index', array_merge(request()->except('kategori', 'page'), ['kategori' => ''])) }}"
            class="px-4 py-2 text-sm font-semibold rounded-lg border transition
                {{ empty($selectedKategori) ? 'bg-[#FF1A1A] text-white border-[#FF1A1A]' : 'bg-white text-gray-500 border-gray-300 hover:border-[#FF1A1A] hover:text-[#FF1A1A]' }}">
            Semua
        </a>
        @foreach (\App\Models\Article::KATEGORI as $kat)
            <a href="{{ route('artikel.index', array_merge(request()->except('kategori', 'page'), ['kategori' => $kat])) }}"
                class="px-4 py-2 text-sm font-semibold rounded-lg border transition
                    {{ $selectedKategori === $kat ? 'bg-[#FF1A1A] text-white border-[#FF1A1A]' : 'bg-white text-gray-500 border-gray-300 hover:border-[#FF1A1A] hover:text-[#FF1A1A]' }}">
                {{ $kat }}
            </a>
        @endforeach
    </div>

    {{-- Pencarian --}}
    <form method="GET" action="{{ route('artikel.index') }}" class="mb-8">
        @if ($selectedKategori)
            <input type="hidden" name="kategori" value="{{ $selectedKategori }}">
        @endif
        <div class="flex gap-3">
            <input type="text" name="search" placeholder="Cari artikel..." value="{{ $search ?? request('search') }}"
                class="flex-1 min-w-[200px] border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#FF1A1A]/30 focus:border-[#FF1A1A] transition">
            <button type="submit"
                class="px-4 py-2 bg-[#FF1A1A] text-white text-sm font-semibold rounded-lg hover:bg-[#CC1515] transition">
                Cari
            </button>
        </div>
    </form>

    {{-- Grid Artikel --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($articles as $article)
            <a href="{{ route('artikel.show', $article->slug) }}"
                class="group bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all">
                @if ($article->gambar_sampul)
                    <div class="aspect-[16/9] overflow-hidden">
                        <img src="{{ Storage::url($article->gambar_sampul) }}" alt="{{ $article->judul }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform" loading="lazy" decoding="async">
                    </div>
                @endif
                <div class="p-5">
                    <span class="inline-block px-2.5 py-1 text-[11px] font-bold text-[#FF1A1A] bg-[#FF1A1A]/10 rounded-full mb-2">
                        {{ $article->kategori }}
                    </span>
                    <h2 class="font-bold text-icc-dark group-hover:text-[#FF1A1A] transition line-clamp-2">{{ $article->judul }}</h2>
                    @if ($article->ringkasan)
                        <p class="text-sm text-icc-gray mt-2 line-clamp-2">{{ $article->ringkasan }}</p>
                    @endif
                    <p class="text-xs text-icc-gray mt-3">
                        {{ $article->published_at?->isoFormat('D MMM YYYY') }}
                        @if ($article->author) • {{ $article->author->name }} @endif
                    </p>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-16 text-icc-gray">
                Belum ada artikel.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $articles->links() }}
    </div>
</div>
@endsection
