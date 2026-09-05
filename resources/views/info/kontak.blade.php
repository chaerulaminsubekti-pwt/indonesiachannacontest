@extends('layouts.public')

@section('title', 'Kontak')

@section('meta_description', 'Hubungi pengurus Indonesia Channa Contest melalui email, WhatsApp, dan media sosial resmi.')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-icc-dark mb-2">Kontak</h1>
    <p class="text-icc-gray mb-8">Hubungi pengurus ICC untuk informasi event, kerja sama, dan keperluan lainnya.</p>

    <div class="space-y-4">
        @if (! empty($settings?->email_kontak))
            <a href="mailto:{{ $settings->email_kontak }}"
                class="flex items-center gap-4 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-[#FF1A1A]/40 transition">
                <span class="flex-shrink-0 w-11 h-11 rounded-full bg-[#FF1A1A]/10 text-[#FF1A1A] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </span>
                <span>
                    <span class="block text-xs text-icc-gray uppercase tracking-wider">Email</span>
                    <span class="block font-bold text-icc-dark">{{ $settings->email_kontak }}</span>
                </span>
            </a>
        @endif

        @if (! empty($settings?->no_wa_kontak))
            <a href="https://wa.me/{{ $settings->no_wa_kontak }}" target="_blank" rel="noopener"
                class="flex items-center gap-4 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-green-300 transition">
                <span class="flex-shrink-0 w-11 h-11 rounded-full bg-green-500 text-white flex items-center justify-center">
                    <img src="{{ asset('img/whatsapp.svg') }}" alt="WA" class="w-5 h-5">
                </span>
                <span>
                    <span class="block text-xs text-icc-gray uppercase tracking-wider">WhatsApp</span>
                    <span class="block font-bold text-icc-dark">{{ $settings->no_wa_kontak }}</span>
                </span>
            </a>
        @endif

        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
            <p class="text-xs text-icc-gray uppercase tracking-wider mb-3">Media Sosial</p>
            <div class="flex flex-wrap gap-3">
                @if (! empty($settings?->link_instagram))
                    <a href="{{ $settings->link_instagram }}" target="_blank" rel="noopener"
                        class="px-4 py-2 text-sm font-semibold border border-gray-300 rounded-lg hover:border-[#FF1A1A] hover:text-[#FF1A1A] transition">Instagram</a>
                @endif
                @if (! empty($settings?->link_facebook))
                    <a href="{{ $settings->link_facebook }}" target="_blank" rel="noopener"
                        class="px-4 py-2 text-sm font-semibold border border-gray-300 rounded-lg hover:border-[#FF1A1A] hover:text-[#FF1A1A] transition">Facebook</a>
                @endif
                @if (! empty($settings?->link_youtube))
                    <a href="{{ $settings->link_youtube }}" target="_blank" rel="noopener"
                        class="px-4 py-2 text-sm font-semibold border border-gray-300 rounded-lg hover:border-[#FF1A1A] hover:text-[#FF1A1A] transition">YouTube</a>
                @endif
                @if (! empty($settings?->link_tiktok))
                    <a href="{{ $settings->link_tiktok }}" target="_blank" rel="noopener"
                        class="px-4 py-2 text-sm font-semibold border border-gray-300 rounded-lg hover:border-[#FF1A1A] hover:text-[#FF1A1A] transition">TikTok</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
