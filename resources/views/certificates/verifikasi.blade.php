@extends('layouts.public')

@section('title', 'Verifikasi Sertifikat')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if ($valid && $certificate)
        @php
            $winner = $certificate->winner;
            $event = $winner->event;
            $predikat = $winner->predikat?->nama_predikat ?? $winner->class?->nama_kelas ?? 'Juara';
        @endphp

        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-8 text-center">
            <!-- Valid Seal -->
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 ring-4 ring-green-200">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-green-800 mb-2">Sertifikat Valid</h1>
            <p class="text-green-700 font-semibold">Resmi Dari Indonesia Channa Contest</p>

            <div class="bg-white/80 backdrop-blur rounded-xl p-6 mt-6 text-left space-y-3 text-sm border border-green-100">
                <div class="grid grid-cols-2 gap-y-3 gap-x-4">
                    <span class="text-gray-500">Nama Pemenang:</span>
                    <span class="font-semibold text-gray-900">{{ $winner->nama_pemenang }}</span>

                    <span class="text-gray-500">Kelas:</span>
                    <span class="font-semibold text-gray-900">{{ $winner->class?->nama_kelas ?? '-' }}</span>

                    <span class="text-gray-500">Event:</span>
                    <span class="font-semibold text-gray-900">{{ $event->nama_event }}</span>

                    <span class="text-gray-500">Tanggal Event:</span>
                    <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($event->tanggal_mulai)->format('d M Y') }}</span>

                    <span class="text-gray-500">Venue:</span>
                    <span class="font-semibold text-gray-900">{{ $event->venue }}, {{ $event->wilayah_kota }}</span>

                    <span class="text-gray-500">No. Sertifikat:</span>
                    <span class="font-semibold text-gray-900 font-mono text-xs">{{ $certificate->nomor_sertifikat }}</span>

                    <span class="text-gray-500">Tanggal Terbit:</span>
                    <span class="font-semibold text-gray-900">{{ $certificate->generated_at ? \Carbon\Carbon::parse($certificate->generated_at)->format('d M Y') : '-' }}</span>
                </div>
            </div>

            @if ($certificate->file_path)
                <a href="{{ route('sertifikat.download', $certificate) }}"
                    class="inline-flex items-center gap-2 mt-6 px-6 py-3 text-sm font-semibold text-white bg-[#FF1A1A] rounded-lg hover:bg-[#CC1515] transition shadow-lg shadow-red-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download Sertifikat
                </a>
            @endif

            <div class="mt-6 pt-4 border-t border-green-200 text-xs text-green-600">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Sertifikat ini telah diverifikasi dan merupakan dokumen resmi dari Indonesia Channa Contest
            </div>
        </div>
    @else
        <div class="bg-gradient-to-br from-red-50 to-red-100 border-2 border-red-200 rounded-2xl p-8 text-center">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 ring-4 ring-red-200">
                <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-red-800 mb-2">Sertifikat Tidak Ditemukan</h1>
            <p class="text-red-700 mb-4">Kode verifikasi yang Anda masukkan tidak valid atau tidak terdaftar dalam sistem kami.</p>
            <a href="{{ route('verifikasi.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Verifikasi
            </a>
        </div>
    @endif
</div>
@endsection
