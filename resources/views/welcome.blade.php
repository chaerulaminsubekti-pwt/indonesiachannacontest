@extends('layouts.public')

@section('title', 'Home')

@section('content')

{{-- Hero Slider --}}
<section class="swiper hero-swiper w-full h-[320px] sm:h-[400px] md:h-[500px] lg:h-[580px] bg-gray-900 relative">
    <div class="swiper-wrapper">
        @forelse ($sliders as $slide)
            <div class="swiper-slide relative">
                @if ($slide->gambar)
                    <img src="{{ Storage::url($slide->gambar) }}" alt="{{ $slide->judul }}"
                        class="w-full h-full object-cover object-center">
                @else
                    <div class="w-full h-full bg-gradient-to-r from-icc-primary to-icc-primary-dark"></div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent flex items-end">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10 sm:pb-16 w-full">
                        @if ($slide->link)
                            <a href="{{ $slide->link }}" class="inline-block mt-3 px-5 py-2.5 bg-[#FF1A1A] text-white font-semibold rounded-lg hover:bg-[#CC1515] transition text-sm">Selengkapnya</a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="swiper-slide bg-gradient-to-br from-icc-primary to-icc-primary-dark flex items-center justify-center">
                <div class="text-center text-white px-4">
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4">Selamat Datang di ICC</h2>
                    <p class="text-base sm:text-lg md:text-xl text-teal-100 max-w-2xl mx-auto">Indonesia Channa Contest — portal resmi informasi event dan kontes di seluruh Indonesia.</p>
                </div>
            </div>
        @endforelse
    </div>
    <div class="swiper-pagination hero-pagination"></div>
</section>

{{-- Sambutan Ketua & Pembina --}}
@if ($settings?->nama_ketua || $settings?->nama_pembina)
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-bold text-icc-dark">Sambutan {{ $settings->nama_website ?? 'ICC' }}</h2>
            <p class="text-icc-gray mt-2">Kata sambutan dari pimpinan organisasi</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 lg:gap-12">
            {{-- Sambutan Pembina (kiri) --}}
            @if ($settings?->nama_pembina)
            <div class="bg-gradient-to-br from-amber-500/5 to-amber-500/10 rounded-3xl p-6 md:p-8 border border-amber-500/10 hover:border-amber-500/20 hover:shadow-lg hover:shadow-amber-500/10 transition-all duration-300">
                <div class="flex flex-col items-center text-center mb-6">
                    <div class="w-28 h-28 md:w-32 md:h-32 rounded-full overflow-hidden ring-4 ring-amber-400/30 mb-4 bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center">
                        @if ($settings->foto_pembina)
                            <img src="{{ Storage::url($settings->foto_pembina) }}" alt="{{ $settings->nama_pembina }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-4xl md:text-5xl text-white font-bold">{{ Str::upper(Str::substr($settings->nama_pembina, 0, 1)) }}</span>
                        @endif
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-icc-dark">{{ $settings->nama_pembina }}</h3>
                    <p class="text-amber-600 font-medium text-sm md:text-base">{{ $settings->jabatan_pembina ?? ('Pembina ' . ($settings->nama_website ?? 'ICC')) }}</p>
                </div>
                <div class="text-icc-gray leading-relaxed prose prose-sm max-w-none">
                    {!! safe_html($settings->sambutan_pembina) !!}
                </div>
            </div>
            @endif

            {{-- Sambutan Ketua (kanan) --}}
            @if ($settings?->nama_ketua)
            <div class="bg-gradient-to-br from-icc-primary/5 to-icc-primary/10 rounded-3xl p-6 md:p-8 border border-icc-primary/10 hover:border-icc-primary/20 hover:shadow-lg hover:shadow-icc-primary/10 transition-all duration-300">
                <div class="flex flex-col items-center text-center mb-6">
                    <div class="w-28 h-28 md:w-32 md:h-32 rounded-full overflow-hidden ring-4 ring-icc-gold/30 mb-4 bg-gradient-to-br from-icc-primary to-icc-primary-dark flex items-center justify-center">
                        @if ($settings->foto_ketua)
                            <img src="{{ Storage::url($settings->foto_ketua) }}" alt="{{ $settings->nama_ketua }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-4xl md:text-5xl text-white font-bold">{{ Str::upper(Str::substr($settings->nama_ketua, 0, 1)) }}</span>
                        @endif
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-icc-dark">{{ $settings->nama_ketua }}</h3>
                    <p class="text-icc-primary font-medium text-sm md:text-base">Ketua {{ $settings->nama_website ?? 'ICC' }}</p>
                </div>
                <div class="text-icc-gray leading-relaxed prose prose-sm max-w-none">
                    {!! safe_html($settings->sambutan_ketua) !!}
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- Event Manager --}}
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl md:text-3xl font-bold text-icc-dark text-center mb-2">Event Terkini</h2>
        <p class="text-icc-gray text-center mb-10">Jelajahi event yang sedang dan akan berlangsung</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse ($latestEvents as $event)
                                <x-event-card :event="$event" />
                            @empty
                                <div class="col-span-full text-center py-12 text-icc-gray">
                                    Belum ada event.
                                </div>
                            @endforelse
                        </div>
<div class="text-center mt-8">
                            <a href="{{ route('event.index') }}" class="inline-block px-6 py-2.5 text-sm font-semibold text-[#FF1A1A] border-2 border-[#FF1A1A] rounded-lg hover:bg-[#FF1A1A] hover:text-white transition">
                                Lihat Semua Event
                            </a>
                        </div>
    </div>
</section>

{{-- Dokumentasi Event --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl md:text-3xl font-bold text-icc-dark text-center mb-2">Dokumentasi Event</h2>
        <p class="text-icc-gray text-center mb-10">Dokumentasi kegiatan organisasi</p>
        @if ($galleries->count())
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($galleries as $gallery)
                    <div class="aspect-square bg-gray-100 rounded-xl overflow-hidden group cursor-pointer" onclick="openGallery(this)">
                        @if ($gallery->file_path)
                            <img src="{{ Storage::url($gallery->file_path) }}" alt="{{ $gallery->caption ?? 'Gallery' }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-500" loading="lazy">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-icc-primary/20 to-icc-gold/20 flex items-center justify-center">
                                <span class="text-icc-gray text-sm">{{ $gallery->judul_album ?? 'Foto' }}</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
<div class="text-center mt-8">
                <a href="{{ route('gallery.index') }}" class="inline-block px-6 py-2.5 text-sm font-semibold text-[#FF1A1A] border-2 border-[#FF1A1A] rounded-lg hover:bg-[#FF1A1A] hover:text-white transition">
                    Lihat Semua Gallery
                </a>
            </div>
        @else
            <p class="text-center text-icc-gray py-8">Belum ada gallery.</p>
        @endif
    </div>
</section>

<x-gallery-lightbox />

{{-- Testimoni --}}
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl md:text-3xl font-bold text-icc-dark text-center mb-2">Testimoni Penyelenggara</h2>
        <p class="text-icc-gray text-center mb-10">Apa kata mereka tentang ICC</p>
        @livewire('public.testimoni-slider')
    </div>
</section>

{{-- Kontak Person --}}
@if ($contacts->count())
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl md:text-3xl font-bold text-icc-dark text-center mb-2">Kontak Person</h2>
        <p class="text-icc-gray text-center mb-10">Hubungi pengurus ICC</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($contacts as $contact)
                <div class="bg-gray-50 rounded-2xl p-6 text-center border border-gray-200">
                    <div class="w-16 h-16 bg-icc-primary/10 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-xl text-icc-primary font-bold">{{ substr($contact->nama, 0, 1) }}</span>
                    </div>
                    <h3 class="font-bold text-icc-dark">{{ $contact->nama }}</h3>
                    <p class="text-xs text-icc-gray mb-2">{{ $contact->jabatan }}</p>
                    @if ($contact->no_wa)
                        <a href="https://wa.me/{{ $contact->no_wa }}" target="_blank"
                            class="text-sm text-green-600 hover:underline">WhatsApp</a>
                    @endif
                    @if ($contact->email)
                        <br><a href="mailto:{{ $contact->email }}" class="text-sm text-icc-primary hover:underline">{{ $contact->email }}</a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Hero Swiper Init --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Swiper !== 'undefined' && typeof Autoplay !== 'undefined' && typeof Pagination !== 'undefined') {
        Swiper.use([Autoplay, Pagination]);
        const heroEl = document.querySelector('.hero-swiper');
        if (heroEl) {
            const slides = heroEl.querySelectorAll('.swiper-slide');
            const enableLoop = slides.length > 2;
            new Swiper('.hero-swiper', {
                loop: enableLoop,
                autoplay: { delay: 6000, disableOnInteraction: false },
                pagination: { clickable: true, el: '.hero-pagination' },
                effect: 'slide',
                speed: 800,
                slidesPerView: 1,
                spaceBetween: 0,
                touchRatio: 1,
                touchAngle: 45,
                grabCursor: true,
                breakpoints: {
                    0: { slidesPerView: 1, spaceBetween: 0 },
                    640: { slidesPerView: 1, spaceBetween: 0 },
                    1024: { slidesPerView: 1, spaceBetween: 0 },
                },
            });
        }
    }
});

</script>
@endpush

@endsection