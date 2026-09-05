@extends('layouts.public')

@section('title', 'Kebijakan Privasi')

@section('meta_description', 'Kebijakan privasi Indonesia Channa Contest: data yang dikumpulkan, penggunaan cookies, dan iklan Google AdSense.')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-icc-dark mb-2">Kebijakan Privasi</h1>
    <p class="text-sm text-icc-gray mb-8">Terakhir diperbarui: {{ date('d F Y') }}</p>

    <div class="space-y-6 text-icc-dark text-sm sm:text-base leading-relaxed">
        <section>
            <h2 class="text-lg font-bold mb-2">1. Data yang Kami Kumpulkan</h2>
            <p>
                Saat menggunakan website ini, kami dapat mengumpulkan data yang Anda berikan secara langsung,
                seperti nama, alamat email, nomor WhatsApp, dan data pendaftaran event atau komentar artikel.
                Kami juga mencatat data teknis secara otomatis, seperti alamat IP, jenis peramban, dan halaman
                yang dikunjungi, untuk keperluan keamanan dan peningkatan layanan.
            </p>
        </section>

        <section>
            <h2 class="text-lg font-bold mb-2">2. Penggunaan Data</h2>
            <p>
                Data yang dikumpulkan digunakan untuk mengelola event, memproses pengajuan dan pendaftaran,
                menampilkan komentar, mengirim notifikasi terkait layanan, serta meningkatkan kualitas website.
                Kami tidak menjual data pribadi Anda kepada pihak ketiga.
            </p>
        </section>

        <section>
            <h2 class="text-lg font-bold mb-2">3. Cookies</h2>
            <p>
                Website ini menggunakan cookies untuk menjaga sesi login, mengingat preferensi, dan menganalisis
                traffic. Anda dapat menonaktifkan cookies melalui pengaturan peramban, namun sebagian fitur
                (seperti login panel) mungkin tidak berfungsi optimal.
            </p>
        </section>

        <section>
            <h2 class="text-lg font-bold mb-2">4. Iklan Google</h2>
            <p>
                Website ini dapat menayangkan iklan dari <strong>Google AdSense</strong>. Google menggunakan
                cookie, termasuk cookie DoubleClick DART, untuk menayangkan iklan yang relevan berdasarkan
                kunjungan Anda ke website ini dan website lain di internet. Anda dapat memilih untuk tidak
                menggunakan cookie DART dengan mengunjungi
                <a href="https://policies.google.com/technologies/ads" target="_blank" rel="noopener" class="text-[#FF1A1A] hover:underline">kebijakan iklan Google</a>.
                Pelajari lebih lanjut mengenai cara Google menggunakan data di
                <a href="https://policies.google.com/privacy" target="_blank" rel="noopener" class="text-[#FF1A1A] hover:underline">kebijakan privasi Google</a>.
            </p>
        </section>

        <section>
            <h2 class="text-lg font-bold mb-2">5. Tautan Eksternal</h2>
            <p>
                Website ini memuat tautan ke situs eksternal (misalnya WhatsApp, media sosial, dan Google Sheets).
                Kami tidak bertanggung jawab atas kebijakan privasi situs-situs tersebut.
            </p>
        </section>

        <section>
            <h2 class="text-lg font-bold mb-2">6. Hak Anda</h2>
            <p>
                Anda berhak meminta akses, perbaikan, atau penghapusan data pribadi Anda yang kami simpan.
                Silakan hubungi kami melalui halaman <a href="{{ route('kontak') }}" class="text-[#FF1A1A] hover:underline">Kontak</a>
                untuk keperluan tersebut.
            </p>
        </section>

        <section>
            <h2 class="text-lg font-bold mb-2">7. Perubahan Kebijakan</h2>
            <p>
                Kebijakan privasi ini dapat diperbarui sewaktu-waktu. Perubahan akan diumumkan melalui halaman
                ini dengan tanggal pembaruan terbaru.
            </p>
        </section>
    </div>
</div>
@endsection
