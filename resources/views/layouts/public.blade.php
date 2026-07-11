<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="index, follow">

    @php
        $siteName = $settings->nama_website ?? 'Indonesia Channa Contest';
        $defaultDescription = $settings->meta_description
            ?? $settings->tagline
            ?? 'Indonesia Channa Contest — wadah resmi penyelenggaraan kontes dan lomba ikan channa di seluruh Indonesia.';

        $routeName = request()->route()?->getName();
        $pageTitle = trim($__env->yieldContent('title'));
        if (!$pageTitle) {
            if ($routeName && $routeName !== 'filament.admin.pages.dashboard') {
                $parts = explode('.', $routeName);
                $pageTitle = ucfirst($parts[0]);
            } else {
                $pageTitle = 'Home';
            }
        }

        $fullTitle = $pageTitle . ' - ' . $siteName;
        $currentUrl = url()->current();
        $defaultOgImage = $settings->logo_header
            ? Storage::url($settings->logo_header)
            : asset('favicon.ico');
    @endphp

    <title>{{ $fullTitle }}</title>
    <link rel="canonical" href="{{ $currentUrl }}">
    <link rel="icon" type="image/x-icon" href="{{ $settings->favicon ? Storage::url($settings->favicon) : asset('favicon.ico') }}">

    <meta name="description" content="@yield('meta_description', $defaultDescription)">
    <meta name="keywords" content="@yield('meta_keywords', 'channa, ikan channa, kontes ikan, lomba channa, Indonesia channa contest, ICC, kontes channa Indonesia')">

    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="@yield('og_title', $fullTitle)">
    <meta property="og:description" content="@yield('og_description', $defaultDescription)">
    <meta property="og:url" content="{{ $currentUrl }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:locale" content="id_ID">
    <meta property="og:image" content="@yield('og_image', $defaultOgImage)">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', $fullTitle)">
    <meta name="twitter:description" content="@yield('og_description', $defaultDescription)">
    <meta name="twitter:image" content="@yield('og_image', $defaultOgImage)">

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "{{ $siteName }}",
        "url": "{{ url('/') }}",
        "logo": "{{ $settings->logo_header ? Storage::url($settings->logo_header) : '' }}",
        "description": "{{ $defaultDescription }}",
        "contactPoint": {
            "@@type": "ContactPoint",
            "telephone": "{{ $settings->no_wa_kontak ?? '' }}",
            "contactType": "customer service"
        },
        "sameAs": [
            @if($settings->link_instagram)"{{ $settings->link_instagram }}",@endif
            @if($settings->link_facebook)"{{ $settings->link_facebook }}",@endif
            @if($settings->link_youtube)"{{ $settings->link_youtube }}",@endif
            @if($settings->link_tiktok)"{{ $settings->link_tiktok }}"@endif
        ]
    }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased text-icc-dark bg-gray-50 relative min-h-screen">

    {{-- Indonesia Map Silhouette Background --}}
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none" aria-hidden="true">
        <svg class="w-full h-full opacity-5" viewBox="0 0 1200 800" preserveAspectRatio="xMidYMid slice"
             xmlns="http://www.w3.org/2000/svg">
            <path fill="#0A0A0A" d="M980,120c-15-8-32-12-50-10c-18,2-35,8-50,18c-15,10-28,22-38,38c-10,16-15,35-12,53c3,18,11,35,22,50c11,15,26,28,44,38c18,10,38,15,58,12c20-3,38-11,53-22c15-11,28-26,38-44c10-18,12-38,10-58C1020,145,1005,130,980,120z"/>
            <path fill="#0A0A0A" d="M680,280c-8-5-18-8-28-8c-10,0-20,3-28,8c-8,5-15,12-20,20c-5,8-8,18-8,28c0,10,3,20,8,28c5,8,12,15,20,20c8,5,18,8,28,8c10,0,20-3,28-8c8-5,15-12,20-20c5-8,8-18,8-28C700,298,695,288,685,283C680,280,680,280,680,280z"/>
            <path fill="#0A0A0A" d="M820,380c-5-3-12-5-18-5c-6,0-12,2-16,5c-4,3-7,8-8,13c-1,5-2,11-2,16c0,6,2,12,5,16c3,4,8,7,13,8c5,1,11,2,16,2c6,0,12-2,16-5c4-3,7-8,8-13c1-5,2-11,2-16C835,390,830,385,825,383C820,380,820,380,820,380z"/>
            <path fill="#0A0A0A" d="M480,480c-12-8-28-12-45-10c-17,2-32,8-45,18c-13,10-25,22-35,37c-10,15-14,32-12,48c2,16,9,32,20,45c11,13,25,25,42,33c17,8,35,12,53,10c18-2,35-10,48-22c13-12,25-27,33-44c8-17,10-35,8-53C510,505,498,490,480,480z"/>
            <path fill="#0A0A0A" d="M280,320c-10-5-22-8-35-8c-13,0-25,3-35,8c-10,5-18,12-23,22c-5,10-8,22-8,35c0,13,3,25,8,35c5,10,12,18,22,23c10,5,22,8,35,8c13,0,25-3,35-8c10-5,18-12,23-22c5-10,8-22,8-35C310,342,305,330,295,325C280,320,280,320,280,320z"/>
            <path fill="#0A0A0A" d="M1080,220c-5-3-12-5-18-5c-6,0-12,2-16,5c-4,3-7,8-8,13c-1,5-2,11-2,16c0,6,2,12,5,16c3,4,8,7,13,8c5,1,11,2,16,2c6,0,12-2,16-5c4-3,7-8,8-13c1-5,2-11,2-16C1100,232,1092,225,1085,222C1080,220,1080,220,1080,220z"/>
            <path fill="#0A0A0A" d="M180,420c-8-5-18-8-28-8c-10,0-20,3-28,8c-8,5-15,12-20,20c-5,8-8,18-8,28c0,10,3,20,8,28c5,8,12,15,20,20c8,5,18,8,28,8c10,0,20-3,28-8c8-5,15-12,20-20c5-8,8-18,8-28C200,438,195,428,185,423C180,420,180,420,180,420z"/>
            <path fill="#0A0A0A" d="M1120,320c-3-2-8-3-12-3c-4,0-8,1-10,3c-2,2-3,5-3,8c0,3,1,6,3,8c2,2,6,3,10,3c4,0,8-1,10-3c2-2,3-5,3-8C1125,326,1123,323,1120,320z"/>
        </svg>
    </div>

    <x-public.navbar />

    <main class="min-h-screen relative z-10">
        @isset($slot)
            {{ $slot }}
        @else
            @yield('content')
        @endisset
    </main>

    <x-public.footer />

    @livewireScripts
    @stack('scripts')

</body>
</html>
