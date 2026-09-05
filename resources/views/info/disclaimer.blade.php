@extends('layouts.public')

@section('title', 'Disclaimer')

@section('meta_description', 'Disclaimer Indonesia Channa Contest mengenai keakuratan konten, data event, dan iklan pihak ketiga.')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-icc-dark mb-8">Disclaimer</h1>

    <div class="space-y-6 text-icc-dark text-sm sm:text-base leading-relaxed">
        <section>
            <h2 class="text-lg font-bold mb-2">1. Keakuratan Informasi</h2>
            <p>
                Seluruh informasi di website ini disajikan dengan itikad baik untuk tujuan informasi umum.
                Jadwal event, hasil lomba, dan data peserta dapat berubah sewaktu-waktu mengikuti kondisi
                penyelenggaraan. Kami berupaya menjaga keakuratan data, namun tidak memberikan jaminan
                atas kelengkapan maupun ketepatan seluruh informasi.
            </p>
        </section>

        <section>
            <h2 class="text-lg font-bold mb-2">2. Data Event & Penyelenggara</h2>
            <p>
                Data peserta, juara, sertifikat, dan galeri pada halaman detail event dikelola oleh
                masing-masing penyelenggara. Ketidaksesuaian data menjadi tanggung jawab penyelenggara
                yang bersangkutan dan dapat dilaporkan kepada pengurus ICC untuk ditindaklanjuti.
            </p>
        </section>

        <section>
            <h2 class="text-lg font-bold mb-2">3. Iklan Pihak Ketiga</h2>
            <p>
                Website ini dapat menampilkan iklan dari pihak ketiga, termasuk Google AdSense. Isi dan
                materi iklan sepenuhnya menjadi tanggung jawab pemasang iklan. Tindakan Anda atas iklan
                tersebut (termasuk pembelian produk atau layanan) berada di luar tanggung jawab kami.
            </p>
        </section>

        <section>
            <h2 class="text-lg font-bold mb-2">4. Tautan Eksternal</h2>
            <p>
                Tautan ke situs eksternal disediakan untuk kemudahan. Kami tidak mengendalikan isi situs
                tersebut dan tidak bertanggung jawab atas kerugian yang timbul dari penggunaannya.
            </p>
        </section>

        <section>
            <h2 class="text-lg font-bold mb-2">5. Persetujuan</h2>
            <p>
                Dengan menggunakan website ini, Anda dianggap telah membaca, memahami, dan menyetujui
                disclaimer ini beserta <a href="{{ route('privasi') }}" class="text-[#FF1A1A] hover:underline">Kebijakan Privasi</a> kami.
            </p>
        </section>
    </div>
</div>
@endsection
