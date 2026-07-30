@extends('layouts.public')

@php
    $eventOgImage = $event->flyers()->first()?->file_path
        ? Storage::url($event->flyers()->first()->file_path)
        : null;
@endphp

@section('title', $event->nama_event)
@section('meta_description', Str::limit(strip_tags($event->deskripsi ?? 'Informasi lengkap mengenai ' . $event->nama_event), 160))
@section('og_title', $event->nama_event . ' - ' . ($settings->nama_website ?? 'Indonesia Channa Contest'))
@section('og_description', Str::limit(strip_tags($event->deskripsi ?? 'Informasi lengkap mengenai ' . $event->nama_event), 200))
@section('og_type', 'article')
@if($eventOgImage)
    @section('og_image', $eventOgImage)
@endif

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="flex flex-wrap gap-4 mb-8 border-b border-gray-200 pb-4">
        <a href="{{ route('event.index') }}" class="text-sm text-[#FF1A1A] hover:underline">&larr; Kembali ke Event</a>
        <span class="text-icc-gray text-sm">/</span>
        <span class="text-sm text-icc-dark font-medium">{{ $event->nama_event }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <div class="lg:col-span-1">
            @php
                $flyers = $event->flyers()->get();
            @endphp
            @if ($flyers->isNotEmpty())
                <div x-data="{ active: 0 }" class="space-y-2">
                    <div class="relative aspect-[3/4] overflow-hidden rounded-2xl">
                        <template x-for="(flyer, index) in @js($flyers->map(fn($f) => ['url' => Storage::url($f->file_path), 'caption' => $f->caption]))" :key="index">
                            <img :src="flyer.url" :alt="'Flyer ' + (index + 1)"
                                x-show="active === index"
                                class="w-full h-full object-cover object-center" loading="lazy">
                        </template>
                    </div>
                    @if ($flyers->count() > 1)
                    <div class="flex gap-2 justify-center">
                        @foreach ($flyers as $i => $flyer)
                        <button @click="active = {{ $i }}"
                            :class="active === {{ $i }} ? 'bg-[#FF1A1A] border-[#FF1A1A]' : 'bg-gray-200 border-gray-300'"
                            class="w-2.5 h-2.5 rounded-full border transition"></button>
                        @endforeach
                    </div>
                    @endif
                </div>
            @else
                <div class="aspect-[3/4] bg-gradient-to-br from-icc-primary to-icc-primary-dark rounded-2xl flex items-center justify-center p-6 shadow-lg">
                    <span class="text-white text-xl font-bold text-center">{{ $event->nama_event }}</span>
                </div>
            @endif
        </div>

        <div class="lg:col-span-2">
            @php
                $kategori = strtolower($event->kategori);
                $katStyle = match ($kategori) {
                    'nasional' => ['bg' => 'bg-amber-600', 'icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z', 'label' => 'Nasional'],
                    'regional' => ['bg' => 'bg-emerald-600', 'icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7', 'label' => 'Regional'],
                    'mini_contest' => ['bg' => 'bg-violet-600', 'icon' => 'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z', 'label' => 'Mini Contest'],
                    'latber' => ['bg' => 'bg-sky-600', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z', 'label' => 'Latber'],
                    default => ['bg' => 'bg-gray-600', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z', 'label' => $event->kategori],
                };
            @endphp
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold text-white shadow-md {{ $katStyle['bg'] }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $katStyle['icon'] }}"/>
                </svg>
                {{ $katStyle['label'] }}
            </span>
            <h1 class="text-3xl font-bold text-icc-dark mt-2">{{ $event->nama_event }}</h1>

            @if ($event->deskripsi)
                <div class="mt-4">
                    <p class="text-icc-dark text-sm leading-relaxed">{{ $event->deskripsi }}</p>
                </div>
            @endif

            <div class="grid grid-cols-2 gap-4 mt-6 text-sm">
                <div>
                    <span class="text-icc-gray">Tanggal</span>
                    <p class="font-semibold text-icc-dark">
                        {{ \Carbon\Carbon::parse($event->tanggal_mulai)->isoFormat('DD MMM YYYY') }}
                        @if ($event->tanggal_mulai != $event->tanggal_selesai)
                            &mdash; {{ \Carbon\Carbon::parse($event->tanggal_selesai)->isoFormat('DD MMM YYYY') }}
                        @endif
                    </p>
                </div>
                @if ($event->venue)
                <div>
                    <span class="text-icc-gray">Venue</span>
                    <p class="font-semibold text-icc-dark">{{ $event->venue }}</p>
                </div>
                @endif
                @if ($event->wilayah_kota)
                <div>
                    <span class="text-icc-gray">Kota/Wilayah</span>
                    <p class="font-semibold text-icc-dark">{{ $event->wilayah_kota }}</p>
                </div>
                @endif
                @if ($event->organizer?->user?->name)
                <div>
                    <span class="text-icc-gray">Penyelenggara</span>
                    <p class="font-semibold text-icc-dark">{{ $event->organizer->user->name }}</p>
                </div>
                @endif
                <div>
                    <span class="text-icc-gray">Jumlah Kelas</span>
                    <p class="font-semibold text-icc-dark">{{ $classes->count() }} kelas</p>
                </div>
                <div>
                    <span class="text-icc-gray">Status</span>
                    <p class="font-semibold
                        {{ $event->status === 'selesai' ? 'text-red-600' : ($event->status === 'berjalan' ? 'text-green-600' : 'text-icc-primary') }}">
                        {{ ucfirst($event->status) }}
                    </p>
                </div>
            </div>

            @if ($event->cps->isNotEmpty())
            <div class="border-t border-gray-100 pt-4 mt-4">
                <span class="text-icc-gray text-xs font-medium uppercase tracking-wider">Contact Person</span>
                <div class="flex flex-wrap gap-3 mt-3">
                    @foreach ($event->cps as $cp)
                        <a href="https://wa.me/{{ $cp->no_wa }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-3 bg-white border border-gray-200 rounded-xl px-5 py-3 text-sm text-icc-dark shadow-sm hover:shadow-md hover:border-green-300 transition-all">
                            <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-green-500">
                                <img src="{{ asset('img/whatsapp.svg') }}" alt="WA" class="w-5 h-5">
                            </span>
                            <span>
                                <span class="font-medium block">{{ $cp->nama }}</span>
                                <span class="text-xs text-icc-gray">{{ $cp->no_wa }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            @if ($event->judges->isNotEmpty())
            <div class="border-t border-gray-100 pt-4 mt-4">
                <span class="text-icc-gray text-xs font-medium uppercase tracking-wider">Juri</span>
                <div class="flex flex-wrap gap-3 mt-3">
                    @foreach ($event->judges as $judge)
                        <span class="inline-flex items-center gap-4 bg-white border border-gray-200 rounded-xl px-5 py-3 text-sm text-icc-dark shadow-sm hover:shadow-md transition-shadow">
                            <span class="w-7 h-7 rounded-full bg-icc-primary text-white flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            <span class="font-medium">{{ $judge->nama }}</span>
                            @if ($judge->kota)
                                <span class="text-icc-gray text-xs">({{ $judge->kota }})</span>
                            @endif
                            <span class="text-[11px] text-icc-primary font-medium ml-1 px-2 py-0.5 bg-icc-primary/10 rounded-full">Juri {{ $judge->pivot->urutan }}</span>
                        </span>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- Tabs --}}
    <div x-data="{ tab: 'peserta' }">
        <div class="flex gap-1 mb-6 border-b border-gray-200">
            <button @click="tab = 'peserta'" :class="tab === 'peserta' ? 'border-b-2 border-[#FF1A1A] text-[#FF1A1A]' : 'text-[#0A0A0A] hover:text-[#FF1A1A]'"
                class="px-4 py-2.5 text-sm font-medium transition-all rounded-t-lg">Data Peserta</button>
            <button @click="tab = 'juara'" :class="tab === 'juara' ? 'border-b-2 border-[#FF1A1A] text-[#FF1A1A]' : 'text-[#0A0A0A] hover:text-[#FF1A1A]'"
                class="px-4 py-2.5 text-sm font-medium transition-all rounded-t-lg">Sertifikat Juara</button>
            <button @click="tab = 'gallery'" :class="tab === 'gallery' ? 'border-b-2 border-[#FF1A1A] text-[#FF1A1A]' : 'text-[#0A0A0A] hover:text-[#FF1A1A]'"
                class="px-4 py-2.5 text-sm font-medium transition-all rounded-t-lg">Gallery Event</button>
        </div>

        <div x-show="tab === 'peserta'" x-cloak>
            @if ($event->google_sheet_url)
                <div class="text-center py-16">
                    <div class="text-6xl mb-4 text-icc-primary/30 font-bold">📋</div>
                    <h3 class="text-xl font-semibold text-icc-dark mb-2">Data Peserta</h3>
                    <p class="text-icc-gray text-sm mb-6">Data peserta dikelola via Google Sheets</p>
                    <a href="{{ $event->google_sheet_url }}" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-[#FF1A1A] text-white rounded-xl font-semibold hover:bg-[#CC1515] transition shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Lihat Data Peserta
                    </a>
                </div>
            @else
                <p class="text-icc-gray py-8 text-center">Belum ada data peserta.</p>
            @endif
        </div>
        <div x-show="tab === 'juara'" x-cloak>
            @include('event.partials.winners')
        </div>
        <div x-show="tab === 'gallery'" x-cloak>
            @include('event.partials.gallery')
        </div>
    </div>
</div>
@endsection