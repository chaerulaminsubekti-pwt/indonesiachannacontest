@extends('layouts.public')

@section('title', 'Tentang ICC')

@section('meta_description', 'Tentang Indonesia Channa Contest (ICC) — wadah resmi penyelenggaraan kontes dan lomba ikan channa di Indonesia.')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-icc-dark mb-6">Tentang ICC</h1>

    <div class="space-y-4 text-icc-dark text-sm sm:text-base leading-relaxed">
        <p>
            <strong>Indonesia Channa Contest (ICC)</strong> adalah wadah resmi penyelenggaraan kontes dan
            lomba ikan channa di seluruh Indonesia. ICC menaungi penyelenggara event dari berbagai daerah
            dengan regulasi yang jelas, sistem penjurian yang profesional, serta pelayanan yang transparan
            dan berintegritas.
        </p>
        <p>
            Website ini merupakan pusat informasi resmi ICC: jadwal dan hasil event, galeri dokumentasi,
            struktur organisasi, daftar juri aktif, regulasi, artikel seputar dunia channa, serta layanan
            pengajuan event bagi komunitas yang ingin menyelenggarakan kontes di bawah naungan ICC.
        </p>
        <p>
            Nilai yang kami junjung dalam setiap penyelenggaraan kontes adalah
            <strong>profesionalisme, sportivitas, integritas, dan kebersamaan</strong>.
        </p>
    </div>

    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
        <a href="{{ route('struktur') }}"
            class="block bg-white border border-gray-200 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-[#FF1A1A]/40 transition">
            <h2 class="font-bold text-icc-dark">Struktur Organisasi</h2>
            <p class="text-sm text-icc-gray mt-1">Kenali pengurus resmi ICC.</p>
        </a>
        <a href="{{ route('regulasi') }}"
            class="block bg-white border border-gray-200 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-[#FF1A1A]/40 transition">
            <h2 class="font-bold text-icc-dark">Regulasi ICC</h2>
            <p class="text-sm text-icc-gray mt-1">Aturan resmi penyelenggaraan kontes.</p>
        </a>
        <a href="{{ route('juri') }}"
            class="block bg-white border border-gray-200 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-[#FF1A1A]/40 transition">
            <h2 class="font-bold text-icc-dark">Daftar Juri Aktif</h2>
            <p class="text-sm text-icc-gray mt-1">Juri resmi yang bertugas di event ICC.</p>
        </a>
        <a href="{{ route('kontak') }}"
            class="block bg-white border border-gray-200 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-[#FF1A1A]/40 transition">
            <h2 class="font-bold text-icc-dark">Hubungi Kami</h2>
            <p class="text-sm text-icc-gray mt-1">Kontak pengurus untuk informasi lebih lanjut.</p>
        </a>
    </div>
</div>
@endsection
