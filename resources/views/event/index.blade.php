@extends('layouts.public')

@section('title', 'Event')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-icc-dark mb-2">Event</h1>
    <p class="text-icc-gray mb-8">Jelajahi seluruh event yang terselenggara di bawah naungan ICC</p>

    {{-- Filter Kategori --}}
<div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('event.index', array_merge(request()->except('kategori', 'page'), ['kategori' => ''])) }}"
            class="px-4 py-2 text-sm font-semibold rounded-lg border transition
                {{ empty($selectedKategori) ? 'bg-[#FF1A1A] text-white border-[#FF1A1A]' : 'bg-white text-gray-500 border-gray-300 hover:border-[#FF1A1A] hover:text-[#FF1A1A]' }}">
            Semua
        </a>
        @foreach (['Nasional', 'Regional', 'Mini Contest', 'Latber'] as $kat)
            <a href="{{ route('event.index', array_merge(request()->except('kategori', 'page'), ['kategori' => $kat])) }}"
                class="px-4 py-2 text-sm font-semibold rounded-lg border transition
                    {{ $selectedKategori === $kat ? 'bg-[#FF1A1A] text-white border-[#FF1A1A]' : 'bg-white text-gray-500 border-gray-300 hover:border-[#FF1A1A] hover:text-[#FF1A1A]' }}">
                {{ $kat }}
            </a>
        @endforeach
    </div>

    {{-- Pencarian --}}
    <form method="GET" action="{{ route('event.index') }}" class="mb-8">
        @if ($selectedKategori)
            <input type="hidden" name="kategori" value="{{ $selectedKategori }}">
        @endif
        <div class="flex gap-3">
            <input type="text" name="search" placeholder="Cari event..." value="{{ request('search') }}"
                class="flex-1 min-w-[200px] border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#FF1A1A]/30 focus:border-[#FF1A1A] transition">
<button type="submit"
                class="px-4 py-2 bg-[#FF1A1A] text-white text-sm font-semibold rounded-lg hover:bg-[#CC1515] transition">
            Cari
        </button>
        </div>
    </form>

    {{-- Grid Event --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse ($events as $event)
            <x-event-card :event="$event" />
        @empty
            <div class="col-span-full text-center py-16 text-icc-gray">
                Tidak ada event ditemukan.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $events->withQueryString()->links() }}
    </div>
</div>
@endsection
